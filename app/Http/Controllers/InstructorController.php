<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\UserAccount;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class InstructorController extends Controller
{
    public function __construct(){
        $this->middleware('instructor');
    }

    public function dashboard(){
        $useraccount = $this->getUserLoggedInCredentials();
        
        return view('instructor.instructor-dashboard', compact('useraccount'));
    }
    /* Get Instructor Logged-in Credentials */
    public function getUserLoggedInCredentials(){
        return DB::table('people')
            ->join('user_accounts', 'people.people_id','=','user_accounts.people_id')
            ->join('employees', 'people.people_id','=','employees.people_id')
            ->join('departments', 'employees.dept_id','=','departments.dept_id')
            ->where('people.people_id', '=', UserAccount::find(Auth::id())->people_id)
            ->whereIn('user_accounts.username', [UserAccount::find(Auth::id())->username])
            ->get();
    }
    /* Change Password View */
    public function accountSettings(){
        $useraccount = $this->getUserLoggedInCredentials();
        return view('instructor.instructor-accountsettings', compact('useraccount'));
    }
    /* Change Password Submitted */
    public function submitChangePassword(Request $request){
        
        $useraccount = $this->getUserLoggedInCredentials();
        foreach($useraccount as $ua){
            $uapass= $ua->password;
        }
        //Verify Old Password if Match
        if(!Hash::check($request->old_password, $uapass)){
            return redirect()->back()
            ->withError("Password not Matched with the Old Password")
            ->withInput();
        }
        $rules = array(
            'new_password'          => 'required|min:6',
            'confirm_new_password'  => 'required|same:new_password'
        );
        $validator = Validator::make($request->except('old_password'), $rules);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }
        //Update User Accounts table
        UserAccount::find(Auth::id())->update(['password'=> Hash::make($request->new_password)]);
        return redirect()->back()->with('success', 'Password changed successfully!');
    }
    /* Classes Management */
    public function classMgmt(){
        $useraccount = $this->getUserLoggedInCredentials();
        foreach ($useraccount as $key => $value) {
            # code...
            $empid = $value->emp_id;
        }
        //dd(count(DB::table('course_program_offers')->where('ins_id',$empid)->get()));
        $myClasses = $this->viewInstructorClass($empid);
        return view('instructor.instructor-class-management', compact('useraccount','myClasses'));
    }
    /* View Instructors Classes */
    public function viewInstructorClass($insid=null){
        return DB::table('course_program_offers')
            ->join('courses_programs','course_program_offers.cp_id','=','courses_programs.cp_id')
            ->join('courses','courses_programs.course_id','=','courses.course_id')
            ->where('ins_id',$insid) 
            ->orderBy('courses.descriptive_title', 'asc')
            ->paginate(20);
    }
    /* Create a Classes */
    public function createClasses(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $courses = DB::table('courses_programs')
            ->join('courses','courses_programs.course_id','=','courses.course_id')
            ->join('programs','courses_programs.program_id','=','programs.program_id')
            ->orderBy('courses.descriptive_title', 'asc')
            ->paginate(20);
        if (isset($request->cpid)) {
            $course = DB::table('courses_programs')
            ->join('courses','courses_programs.course_id','=','courses.course_id')
            ->join('programs','courses_programs.program_id','=','programs.program_id')
            ->where('courses_programs.cp_id', $request->cpid)
            ->get();
            //dd($course);
            if(count($course)>0){
                $cpid = $request->cpid;
                //dd($course);
                return view('instructor.class-management.create-myclass', compact('useraccount','course','cpid'));
            }
            
            return redirect()->back();
        }

        return view('instructor.class-management.create-myclass', compact('useraccount','courses'));
    }
    /* *******Submit my create class******** */
    public function postCreateClass(Request $request){
        $validate = $request->validate([
            'schedule'  => 'required',
            'sc_id'     => 'required|numeric',
            'ay'        => 'required',
            'term'      => 'required',
        ]);
        /* ucwords Str::ucfirst(Str::lower */
        //dd($request);
        $findScId = DB::table('course_program_offers')->where('sc_id', $request->sc_id)->get();
        //dd($findScId);
        if(count($findScId)>0){
            //dd($findScId);
            return redirect()->back()->with('errors', $request->sc_id. ' SC-ID has duplicate.');
        }
        $create = DB::table('course_program_offers')->insert([
            'cp_id'         => $request->course_id,
            'ins_id'        => $request->ins_id,
            'sc_id'         => $request->sc_id,
            'schedule'      => strtoupper($request->schedule),
            'ay'            => $request->ay,
            'term'          => $request->term,
            'created_at'    => Carbon::now(),
        ]);
        if($create){
            return redirect()->back()->with('success', 'Class Created');
        }
        return redirect()->back()->with('errors','Somthing went wrong...');
    }
    /* ***Remove My Classes*** */
    public function removeMyClass(Request $request){
        $checkStudsEnrolledResult = DB::table('cpo_enroll_students')
                    ->join('cpoep_cpoes_results', 'cpo_enroll_students.cpoes_id','=','cpoep_cpoes_results.cpoes_id')
                    ->where('cpo_enroll_students.cpo_id', $request->cpoid)->get();
        if(count($checkStudsEnrolledResult)>0){
            return response()->json(['invalid', 'Sorry you cannot delete a class that has Students results!']);
        }
        $checkStudsEnrolled = DB::table('cpo_enroll_students')->where('cpo_id', $request->cpoid)->get();
        if(count($checkStudsEnrolled) > 0){
            $removeStuds=DB::table('cpo_enroll_students')->where('cpo_id', $request->cpoid)->delete();
            $removeClass = DB::table('course_program_offers')->where('cpo_id', $request->cpoid)->delete();
            if($removeStuds && $removeClass){
                return response()->json('success', 'Class Deleted.');
            }
            return redirect()->back()->with('errors','Something went wrong!');
        }
        $removeClass = DB::table('course_program_offers')->where('cpo_id', $request->cpoid)->delete();
        return response()->json(['success', 'Class Successfully Removed!']);
    }
    /* **Search Course** */
    public function serachCourseAjax(Request $request){
        $courses = DB::table('courses_programs')
            ->join('courses','courses_programs.course_id','=','courses.course_id')
            ->join('programs','courses_programs.program_id','=','programs.program_id')
            ->where('courses.course_code','LIKE','%'.$request->course."%")
            ->orWhere('courses.descriptive_title','LIKE','%'.$request->course."%")
            ->orderBy('courses.descriptive_title', 'asc')
            ->get();
        $output = '<table class="table table-custom">
                        <thead class="thead-dark">
                            <tr>
                                <th>Course Code</th>
                                <th>Course Description</th>
                                <th>Program Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    <tbody >';
        foreach ($courses as $key => $value) {
            $output .= "<tr>
                <td>".$value->course_code."</td>
                <td>".$value->descriptive_title."</td>
                <td>".$value->program_code."</td>
                <td>".
                    "<a href=".route('cmCreateClass',['cpid'=>$value->cp_id]). ' data-toggle="tooltip" title="Create a Class"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create</a>' 
                ."</td>
            </tr>";
        }
        $output .= '</tbody>
        </table>';
        //return $request->course;
        //return response()->json(['courses'=>$courses]);
        return response()->json($output);
    }
    /* ********Editing My Class Show Details******* */
    public function editMyClass(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $findCpoId=DB::table('course_program_offers')
            ->join('courses_programs','course_program_offers.cp_id','=','courses_programs.cp_id')
            ->join('courses','courses_programs.course_id','=','courses.course_id')
            ->join('programs','courses_programs.program_id','=','programs.program_id')
            ->where('cpo_id', $request->cpoid_edit)->get();
        if(count($findCpoId)>0){
            return view('instructor.class-management.edit-my-class', compact('useraccount','findCpoId'));
        }
        return redirect()->back();
    }
    /* ****Submit Edit My Class****** */
    public function postEditMyClass(Request $request){
        //dd($request);
        $validate = $request->validate([
            'schedule'  => 'required',
            'sc_id'     => 'required|numeric',
            'ay'        => 'required',
            'term'      => 'required',
        ]);
        $updateCPO = DB::table('course_program_offers')->where('cpo_id', $request->cpo_id)->update([
            'sc_id'         => $request->sc_id,
            'schedule'      => strtoupper($request->schedule),
            'ay'            => $request->ay,
            'term'          => $request->term,
            'updated_at'    => Carbon::now(),
        ]);
        if($updateCPO){
            return redirect()->back()->with('success', "Successfully Updated.");
        }
        return redirect()->back()->with('errors', "Update went wrong!");
    }
    /* *******Enroll Students in a  Class******** */
    public function enrollStudents(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $getProgramsOffer=DB::table('course_program_offers')
            ->join('courses_programs','course_program_offers.cp_id','=','courses_programs.cp_id')
            ->join('courses','courses_programs.course_id','=','courses.course_id')
            ->join('programs','courses_programs.program_id','=','programs.program_id')
            ->where('cpo_id', $request->cpoid_enroll)->get();
        if(count($getProgramsOffer)){
            $students = $this->getStudent();
            $programs = $this->getAllPrograms();
            return view('instructor.class-management.enroll-students-inclass', compact('useraccount', 'getProgramsOffer','students','programs'));
        }
        return redirect()->back();
    }
/* *********Enroll Student in a Class using file upload************ */
    public function uploadStudentListInClass(Request $request){
        //Get the file path
        $path = $request->file('add_studlist')->getRealPath();
        //create array of data
        $data = Excel::load($path)->get();
        //dd($data);
        $studExist=array();
        $studNotExist=array();
        $studAlreadyEnrolled=array();
        if($data->count() > 0){
            /* Get Student ID from students table */
            foreach ($data as $key => $value) {
                //$value->student_idnumber
                if ($this->findStudentsById($value->student_idnumber)>0) {
                    $studExist[] = [
                        'idnumber'  =>DB::table('people')
                                    ->join('students','people.people_id','=','students.people_id')
                                    ->where('people.id_number',$value->student_idnumber)->get('students.stud_id'),
                        'name'      =>$value->name
                    ];
                }else{
                    $studNotExist[] = $value->name;
                }
                
                if($this->checkStudentUploadIfEnrolled($value->student_idnumber,$request->cpo_id)>0){
                    $studAlreadyEnrolled[]=$value->name;
                }    
            }
            /* Check if students do exist in the system */
            if(count($studNotExist)){
               return redirect()->back()->with(['studentNotExist'=>$studNotExist]);
            }
            /* Ceck if students already enrolled in the same class cpo_id and stud_id */
            if(count($studAlreadyEnrolled)>0){
                return redirect()->back()->with(['enrolled'=>$studAlreadyEnrolled]);
            }
            /* Insert cpo_id and cpoes_stud_id in cpo_enroll_students */
            //dd($studExist);
            foreach ($studExist as $key => $value) {
                foreach ($value['idnumber'] as $key1 => $value1) {
                    $insertCpoEnrollStudents = DB::table('cpo_enroll_students')->insert([
                        'cpoes_stud_id' => $value1->stud_id,
                        'cpo_id'        => $request->cpo_id,
                        'created_at'    => Carbon::now()            
                    ]);
                }
            }
            return back()->with('success', 'Students successfully added in your class.');
        }else{
            Alert::error('WARNING', "File has no valid content, please check your file before uploading it.");
            return back()->withErrors(""); 
        }
    }
    /* ******FIND STUDENTS BY ID-NUMBER******** */
    public function findStudentsById($idnum){
        return DB::table('people')
                ->join('students','people.people_id','=','students.people_id')
                ->where('people.id_number',$idnum)->get()->count();
    }
    /* *******CHECK IF STUDENTS ALREADY ENROLLED IN THE CLASS******************** */
    public function checkStudentUploadIfEnrolled($studid, $cpoid){
        /* return DB::table::('people')
                ->join('students','people.people_id','=','students.people_id')
                ->join('cpo_enroll_students', 'students.stud_id','=','cpo_enroll_students.cpoes_stud_id')
                ->where([
                    ['people.id_number']
                ])
                ->get()->count(); */
        return DB::table('cpo_enroll_students')
                ->join('students','cpo_enroll_students.cpoes_stud_id','=','students.stud_id')
                ->join('people','students.people_id','=','people.people_id')
                ->where([
                    ['people.id_number','=',$studid],
                    ['cpo_enroll_students.cpo_id','=',$cpoid]
                ])
                ->get()->count();
    }
    /* Get List of Students of Specific Student */
    public function getStudent($studid=null){
        return DB::table('students')
            ->join('people','students.people_id','=','people.people_id')
            ->join('students_programs','students.stud_id','=','students_programs.stud_id')
            ->join('programs','students_programs.program_id','=','programs.program_id')
            ->orderBy('people.last_name', 'asc')
            ->paginate(20);
    }
    /* *****Get all Programs***** */
    public function getAllPrograms(){
        return DB::table('programs')->orderBy('program_code','asc')->get();
    }
    /* *****Create Student Account******** */
    public function createStudentAccount(Request $request){
        //dd($request);
        $validate = $request->validate([
            'stud_idnumber'    => 'required|numeric',
            'lastname'      => 'required|max:125',
            'firstname'     => 'required|max:125',
            'middlename'    => 'max:125',
            'ext_name'      => 'max:6',
            'yearlevel'     => 'required',
            'program_id'    => 'required'
        ]);
        $checkStudentEntry = DB::table('people')->where('id_number', $request->stud_idnumber)->count();
        if($checkStudentEntry > 0){
            Alert::error('WARNING', $request->lastname.', '.$request->firstname.' is already added.');
            return redirect()->back()->with('');
        }
        $addToPeople = DB::table('people')->insertGetId([
            'id_number'     => $request->stud_idnumber,
            'last_name'     => Str::ucfirst(Str::lower($request->lastname)),
            'first_name'    => ucwords(Str::lower($request->firstname)),
            'middle_name'   => Str::ucfirst(Str::lower($request->middlename)),
            'ext_name'      => ucwords(Str::lower($request->ext_name)),
            'created_at'    => Carbon::now(),
        ]);
        $insertUA = DB::table('user_accounts')->insert([
            'username'      => $request->stud_idnumber,
            'password'      => bcrypt($request->stud_idnumber),
            'user_type'     => 'Student',
            'ua_status'     => 1,
            'people_id'     => $addToPeople,
            'created_at'    => Carbon::now()
        ]);
        $addToStudents = DB::table('students')->insertGetId([
            'people_id'     => $addToPeople,
            'year_level'    => $request->yearlevel,
            'created_at'    => Carbon::now(),
        ]);
        $addToStudentsPrograms = DB::table('students_programs')->insert([
            'stud_id'       => $addToStudents,
            'program_id'    => $request->program_id,
            'created_at'    => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'Student Successfully Created.');
    }
    /* ****Search Student to Enroll in a Class AJAX**** */
    public function findStudentEnrollAjax(Request $request){
        $findstudent = DB::table('people')
            ->join('students','people.people_id','=','students.people_id')
            ->join('students_programs','students.stud_id','=','students_programs.stud_id')
            ->join('programs','students_programs.program_id','=','programs.program_id')
            ->where('people.id_number','LIKE','%'.$request->searchStud."%")
            ->orWhere('people.last_name','LIKE','%'.$request->searchStud."%")
            ->orWhere('people.first_name','LIKE','%'.$request->searchStud."%")
            ->orderBy('people.last_name', 'asc')
            ->get();
        $output="";
        if(count($findstudent)>0){
            foreach ($findstudent as $key => $value) {
                $output .= "<tr>
                    <td>".$value->last_name."</td>
                    <td>".$value->first_name."</td>
                    <td>".$value->middle_name."</td>
                    <td>".$value->program_code."</td>
                    <td>".
                        "<a href=".route('cmCreateClass',['cpid'=>$request->cpoid,'studid'=>$value->stud_id]). ' data-toggle="tooltip" title="Create a Class"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add</a>' 
                    ."</td>
                </tr>";
            }
            //dd($output);
            return response()->json($output);
        }
        if($request->searchStud=="")
            return redirect()->back();
        return response()->json(['nodata'=>'Student Not Found!']);
    }
    /* Enrolling Student in A Class */
    public function enrollStudentInClass(Request $request){
        //dd($request->cpoid);
        $findDuplicate = DB::table('cpo_enroll_students')
                ->where('cpoes_stud_id', $request->studid)
                ->whereIn('cpo_id', [$request->cpoid])
                ->get();
        if(count($findDuplicate)>0){
            return response()->json(['duplicate'=>'Students already enrolled in this class.']);
        }
        $enrollStudent = DB::table('cpo_enroll_students')->insert([
            'cpoes_stud_id' => $request->studid,
            'cpo_id'        => $request->cpoid,
            'created_at'    => Carbon::now()
        ]);
        return response()->json(['enrolled'=>'Students Enrolled.']);
    }
    /* *****Create Evaluation in a Class***** */
    public function classEvaluationCreate(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $courseProgramOffers=$this->courseProgramOffer($request->cpoid_ceic);
        $evalcreated=$this->courseProgramOfferEvalutionCreated($request->cpoid_ceic);
    
        if(!empty($request->cpoid_ceic)){
            
            if(count($courseProgramOffers)>0){
                
                return view('instructor.class-management.create-class-evaluation', compact('useraccount','courseProgramOffers','evalcreated'));
            }
            return redirect()->back();
        }
        return redirect()->back();
    }
    /* ****Choosing Type of Evaluation in a Class**** */
    public function classEvaluationCreateType(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $courseProgramOffers=$this->courseProgramOffer($request->cpoid_ceic);
        $evalcreated=$this->courseProgramOfferEvalutionCreated($request->cpoid_ceic);
        
        $qbankMC = DB::table('question_banks')->orderBy('question_banks.question_desc', 'asc')->where('question_banks.question_type','mc')->get();
        foreach ($qbankMC as $key => $value) {
            $mcQuestionBank[] = array(
                'qb_id'         => $value->qb_id,
                'question_desc' => $value->question_desc,
                'points'        => $value->points,
                'choices'       => DB::table('multiple_choices')->where('qb_id',$value->qb_id)->get()
            );
        }
        $arQuestionBank = DB::table('question_banks')
                    ->join('alternate_responses','question_banks.qb_id','=','alternate_responses.qb_id')
                    ->orderBy('question_banks.question_type', 'asc')
                    ->where('question_banks.question_type','ar')->get();
        //$arQuestionBank = DB::table('question_banks');
        if(isset($request->evaltype)){
            if($request->evaltype == 'quiz'){
                $quiz = $request->evaltype;
                
                return view('instructor.class-management.create-class-evaluation', compact('useraccount','courseProgramOffers','quiz','evalcreated','mcQuestionBank','arQuestionBank'));
            }
            if($request->evaltype=='exam'){
                $exam = $request->evaltype;
                return view('instructor.class-management.create-class-evaluation', compact('useraccount','courseProgramOffers','exam'));
            }
            return redirect()->back();
        }
        return redirect()->back();
    }
    /* postClassEvaluationCreateType */
    public function postClassEvaluationCreateType(Request $request){
        //dd($request); cpo_id | evaltype | instruction
        //dd($request->evaltype);
        if ($request->evaltype=='exam') {
            $createCPOEP_id = DB::table('cpo_evaluation_parts')->insertGetId([
            'cpo_id'            => $request->cpo_id,
            'cpoep_type'        => $request->evaltype,
            'cpoep_isactive'    => 0,
            'created_at'        => Carbon::now(),
            ]);
            if(isset($request->exam_part1)){
                $createEI = DB::table('eval_instructions')->insert([
                    'cpoep_id'          => $createCPOEP_id,
                    'instruction_desc'  => $request->exam_desc1,
                    'eval_parts'        => $request->exam_part1,
                    'created_at'        => Carbon::now(),
                ]);
            }
            if(isset($request->exam_part2)){
                $createEI = DB::table('eval_instructions')->insert([
                    'cpoep_id'          => $createCPOEP_id,
                    'instruction_desc'  => $request->exam_desc2,
                    'eval_parts'        => $request->exam_part2,
                    'created_at'        => Carbon::now(),
                ]);
            }
            //query cpo_evaluation_parts join eval_instructions pass einstruct_id and instruction_desc
            $getcpoInfo = DB::table('cpo_evaluation_parts')
                    ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id')
                    ->where('cpo_evaluation_parts.cpoep_id',$createCPOEP_id)->get();
            foreach ($getcpoInfo as $key => $value) {
                $einstruct_id[] = $value->einstruct_id;
                $instruction_desc[] = $value->instruction_desc;
                $evalparts[] = $value->eval_parts;
            }
            return response()->json(['success_res'=>"Exam Details Successfully Created",'ei_id'=>$einstruct_id,'idesc'=>$instruction_desc,'evalparts'=>$evalparts]);
        }

        if ($request->evaltype=='quiz') {
            //$createEvalType=$this->createCPOEPandEI($request->cpo_id,$request->evaltype,$request->instruction,'');
            $createCPOEP_id = DB::table('cpo_evaluation_parts')->insertGetId([
                'cpo_id'            => $request->cpo_id,
                'cpoep_type'        => $request->evaltype,
                'cpoep_isactive'    => 0,
                'created_at'        => Carbon::now(),
            ]);
            $createEI = DB::table('eval_instructions')->insert([
                'cpoep_id'          => $createCPOEP_id,
                'instruction_desc'  => $request->instruction,
                'eval_parts'        => '',
                'created_at'        => Carbon::now(),
            ]);
            //query cpo_evaluation_parts join eval_instructions pass einstruct_id and instruction_desc
            $getcpoInfo = DB::table('cpo_evaluation_parts')
                    ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id')
                    ->where('cpo_evaluation_parts.cpoep_id',$createCPOEP_id)->get();
            foreach ($getcpoInfo as $key => $value) {
                $einstruct_id = $value->einstruct_id;
                $instruction_desc = $value->instruction_desc;
            }

            $qbankMC = DB::table('question_banks')->orderBy('question_banks.question_desc', 'asc')->where('question_banks.question_type','mc')->get();
            foreach ($qbankMC as $key => $value) {
                $mcQuestionBank[] = array(
                    'qb_id'         => $value->qb_id,
                    'question_desc' => $value->question_desc,
                    'points'        => $value->points,
                    'choices'       => DB::table('multiple_choices')->where('qb_id',$value->qb_id)->get()
                );
            }
            $arQuestionBank = DB::table('question_banks')
                        ->join('alternate_responses','question_banks.qb_id','=','alternate_responses.qb_id')
                        ->orderBy('question_banks.question_type', 'asc')
                        ->where('question_banks.question_type','ar')->get();

            return response()->json(['success_res'=>"Quiz Details Successfully Created.",'ei_id'=>$einstruct_id,'idesc'=>$instruction_desc]);
        }

       
        //return redirect()->route('chooseTypeEvalInClass',['cpoid_ceic'=>$request->cpo_id,'evaltype'=>$request->evaltype])->with('success','Quiz Deatils Created.');
    }
    /* Adding Questions from Question Bank using AJAX */
    public function loadQuizQuestionFromQB(Request $request){
        
        /* Insert QB-ID and EINSTRUCT-ID in cpoep_questions table */
        $load = DB::table('cpoep_questions')->insert([
            'qb_id'         => $request->qb_id,
            'einstruct_id'  => $request->einstruct_id,
            'created_at'    => Carbon::now()   
        ]);
        /* Return Sum of all Created Quizzes */
        $sum = DB::table('cpoep_questions')
            ->join('question_banks', 'cpoep_questions.qb_id','=','question_banks.qb_id')     
            ->where('cpoep_questions.einstruct_id', $request->einstruct_id)->sum('question_banks.points'); 
        /* Count Number of Items */
        $getNumItems = DB::table('cpoep_questions')
            ->join('question_banks', 'cpoep_questions.qb_id','=','question_banks.qb_id')    
            ->where('cpoep_questions.einstruct_id', $request->einstruct_id)->get();
        if($load){
            return response()->json(['success'=>'Successfully Added', 'totalQuizzes'=>$sum, 'getNumItems'=>count($getNumItems)]);
        }
        return response()->json(['error'=>'Something went wrong.']);
       
        //return response()->json(['success'=>'Successfully Added', 'totalQuizzes'=>$sum, 'getNumItems'=>count($getNumItems)]);
        //return response()->json($request);
    }
    /* Post Create Quiz Questions submitQuizQuestions*/
    public function postClassCreateQuizQuestion(Request $request){
              
        if($request->quiztype=='mc'){
            $input = \Request::all();
            $rules=[];
            $arr = array();
            if(isset($input['response'])){
                foreach ($input['response'] as $key => $value) {
                    $rules['response.'.$key]='required';
                }
            }
                
            
            $rules['question_desc']='required';
            $rules['points']='required|numeric';
            $validate = \Validator::make($input,$rules);
            if($validate->fails()){
                return response()->json(['err'=>'All Fields are Required!']);
            }
            if(empty($input['isanswer'])){
                return response()->json(['err_answer'=>'Please Select atleast one answer!']);
            }
            $createQuestionsBank=DB::table('question_banks')->insertGetId([
                'question_desc' => $request->question_desc,
                'question_type' => $request->quiztype,
                'points'        => $request->points,
                'created_at'    => Carbon::now()
            ]);
            $createCpoepQuestions = DB::table('cpoep_questions')->insert([
                'qb_id'         => $createQuestionsBank,
                'einstruct_id'  => $request->einstruct_id,
                'created_at'    => Carbon::now()
            ]);
            
            $mcquestions = DB::table('multiple_choices')->where('qb_id', $createQuestionsBank)->get();
        
            foreach ($request->response as $key => $value) {
                $createMC = DB::table('multiple_choices')->insert([
                    'qb_id'                 => $createQuestionsBank,
                    'choices_description'   => $value,
                    'mc_is_answer'          => 0,
                    'created_at'            => Carbon::now()
                ]);
            }
            foreach ($request->response as $key => $value) {
                if(!empty($request->isanswer)){
                    foreach ($request->isanswer as $iakey => $iavalue) {
                        if($iavalue == $key){
                            $setAnswer = DB::table('multiple_choices')->where('choices_description', $value)->update([
                                'mc_is_answer'  => 1,
                                'updated_at'    => Carbon::now(),
                            ]);
                        }
                    }   
                }
                
            }
            $getNumItems = DB::table('cpoep_questions')
            ->join('question_banks', 'cpoep_questions.qb_id','=','question_banks.qb_id')    
            ->where('cpoep_questions.einstruct_id', $request->einstruct_id)->get();
            $sum = DB::table('cpoep_questions')
            ->join('question_banks', 'cpoep_questions.qb_id','=','question_banks.qb_id')     
            ->where('cpoep_questions.einstruct_id', $request->einstruct_id)->sum('question_banks.points'); 
            return response()->json(['done'=>'Successfully Created!','getNumItems'=>count($getNumItems),'sum'=>$sum,'evalparts'=>$evalparts,'mc'=>'mc']);
        }
        if($request->quiztype=='ar'){
            $createQuestionsBank=DB::table('question_banks')->insertGetId([
                'question_desc' => $request->question_desc_ar,
                'question_type' => $request->quiztype,
                'points'        => $request->pointsar,
                'created_at'    => Carbon::now()
            ]);
            $createCpoepQuestions = DB::table('cpoep_questions')->insert([
                'qb_id'         => $createQuestionsBank,
                'einstruct_id'  => $request->einstruct_id,
                'created_at'    => Carbon::now()
            ]);
            $createAR = DB::table('alternate_responses')->insert([
                'qb_id'         => $createQuestionsBank,
                'ar_is_answer'  => $request->ans,
                'created_at'    => Carbon::now()
            ]);
            $getNumItems = DB::table('cpoep_questions')
            ->join('question_banks', 'cpoep_questions.qb_id','=','question_banks.qb_id')    
            ->where('cpoep_questions.einstruct_id', $request->einstruct_id)->get();
            $sum = DB::table('cpoep_questions')
            ->join('question_banks', 'cpoep_questions.qb_id','=','question_banks.qb_id')     
            ->where('cpoep_questions.einstruct_id', $request->einstruct_id)->sum('question_banks.points'); 
            
        }
        //'evalparts'=>$evalparts
        return response()->json(['done'=>'Successfully Created!','getNumItems'=>count($getNumItems),'sum'=>$sum]);
        
    }

/* ********Post to Create Exam Questions******* */
    public function postClassCreateExamQuestion(Request $request){
        
        if($request->examtype=='mc'){

            $eid_ex = explode(',',$request->einstruct_id);
            if($request->examtype_ei == 'mcei1'){
                
                $evalpartsget = DB::table('eval_instructions')->where('einstruct_id',$eid_ex[0])->get();
                foreach ($evalpartsget as $key => $value) {
                    $evalparts = $value->eval_parts;
                    $instruc_desc   = $value->instruction_desc;
                }
                /* 1. Validate inputs */
                $input = \Request::all();
                $rules = [];
                if(isset($input['response'])){
                    foreach ($input['response'] as $key => $value) {
                        $rules['response.'.$key]='required';
                    }  
                }
                if(empty($input['isanswer'])){
                    return response()->json(['err_answer'=>'Please Select atleast one answer!']);
                }
                if(isset($input['question_desc_emc'])){
                    $rules['question_desc_emc']='required';
                }
            
                if(isset($input['points_emc'])){
                    $rules['points_emc']='required|numeric';
                }
            
                $validate = \Validator::make($input,$rules);
                if($validate->fails()){
                    return response()->json(['err'=>'All Fields are Required!']);
                }
                /* 2. createQuestionsBank */
                $qbid = $this->insertQuestionBank($request->question_desc_emc, $request->examtype, $request->points_emc);
                
                /* 3. insert responses  4. update answer*/
                if(isset($request->response)){
                    foreach ($request->response as $key => $value) {
                        $createresponse = $this->createMultipleChoice($qbid, $value);
                    }
                    foreach ($request->response as $key => $value) {
                        if(!empty($request->isanswer)){
                            foreach ($request->isanswer as $iakey => $iavalue) {
                                if($iavalue == $key){
                                    $setans = $this->setAnswer($value);
                                }
                            }   
                        }
                        
                    }
                }
                /* 5. create CPOEP questions */
                $cpoepquestion = $this->cpoepQuestions($qbid, $eid_ex[0]);
                $getNumItems = $this->numberOfItems($eid_ex[0]);
                $sum = $this->totalPoints($eid_ex[0]);
                /* ****return success*** */
                return response()->json(['done'=>'Successfully Created!','getNumItems'=>count($getNumItems),'sum'=>$sum,'evalparts'=>$evalparts,'examis'=>'mcei1','ins_desc1'=>$instruc_desc]);
            } 
            if($request->examtype_ei == 'mcei2'){
                $evalpartsget = DB::table('eval_instructions')->where('einstruct_id',$eid_ex[1])->get();
                foreach ($evalpartsget as $key => $value) {
                    $evalparts = $value->eval_parts;
                    $instruc_desc   = $value->instruction_desc;
                }
                /* 1. Set validation  */
                $input1 = \Request::all();
                $rules1=[];
           
                if(isset($input1['response1'])){
                    foreach ($input1['response1'] as $key => $value) {
                        $rules1['response1.'.$key]='required';
                    }
                    if(empty($input1['isanswer1'])){
                        return response()->json(['err_answer'=>'Please Select atleast one answer!']);
                    }
                }
                if(isset($input1['question_desc_emc1'])){
                    $rules1['question_desc_emc1']='required';
                }
                if(isset($input1['points_emc1'])){
                    $rules1['points_emc1']='required|numeric';
                }
                $validate1 = \Validator::make($input1,$rules1);
                if($validate1->fails()){
                    return response()->json(['err'=>'All Fields are Required!']);
                }
                /* 2. createQuestionsBank */
                $qbid = $this->insertQuestionBank($request->question_desc_emc1, $request->examtype, $request->points_emc1);
                /* 3. insert responses  4. update answer*/
                if(isset($request->response1)){
                    foreach ($request->response1 as $key => $value) {
                        $createresponse = $this->createMultipleChoice($qbid, $value);
                    }
                    foreach ($request->response1 as $key => $value) {
                        if(!empty($request->isanswer1)){
                            foreach ($request->isanswer1 as $iakey => $iavalue) {
                                if($iavalue == $key){
                                    $setans = $this->setAnswer($value);
                                }
                            }   
                        }
                        
                    }
                }
                /* 5. create CPOEP questions */
                $cpoepquestion = $this->cpoepQuestions($qbid, $eid_ex[1]);
                $getNumItems = $this->numberOfItems($eid_ex[1]);
                $sum = $this->totalPoints($eid_ex[1]);
                /* ****return success*** */
                return response()->json(['done'=>'Successfully Created!','getNumItems'=>count($getNumItems),'sum'=>$sum,'evalparts'=>$evalparts,'examis'=>'mcei2','ins_desc1'=>$instruc_desc]);
            } 
              
        }
        /* Alternate response Exam */
        if($request->examtype=='ar'){
            //createAlternateResponses($qbid, $isanswer)
            $eid_ex = explode(',',$request->einstruct_id);
            if($request->examtype_ei == 'arei1'){
                $evalpartsget = DB::table('eval_instructions')->where('einstruct_id',$eid_ex[0])->get();
                foreach ($evalpartsget as $key => $value) {
                    $evalparts      = $value->eval_parts;
                    $instruc_desc   = $value->instruction_desc;
                }
                /* 1. Store to Question Bank */
                $qb_id = $this->insertQuestionBank($request->question_desc_ar, $request->examtype, $request->pointsar);
                /* 2. Store alternate responses answer */
                $createARresponses = $this->createAlternateResponses($qb_id, $request->ans);
                /* 3. Create CPOEP Questions */
                $createCPOEP=$this->cpoepQuestions($qb_id, $eid_ex[0]);
                /* 4. Get number of items and sum */
                $getNumItems = $this->numberOfItems($eid_ex[0]);
                $sum = $this->totalPoints($eid_ex[0]);
                /* return */
                return response()->json(['done'=>'Successfully Created!','getNumItems'=>count($getNumItems),'sum'=>$sum,'evalparts'=>$evalparts,'examis'=>'arei1','ins_desc'=>$instruc_desc]);
            } 
            if($request->examtype_ei == 'arei2'){
                $evalpartsget = DB::table('eval_instructions')->where('einstruct_id',$eid_ex[0])->get();
                foreach ($evalpartsget as $key => $value) {
                    $evalparts = $value->eval_parts;
                    $instruc_desc   = $value->instruction_desc;
                }
                /* 1. Store to Question Bank */
                $qb_id = $this->insertQuestionBank($request->question_desc_ar1, $request->examtype, $request->pointsar1);
                /* 2. Store alternate responses answer */
                $createARresponses = $this->createAlternateResponses($qb_id, $request->ans1);
                /* 3. Create CPOEP Questions */
                $createCPOEP=$this->cpoepQuestions($qb_id, $eid_ex[1]);
                /* 4. Get number of items and sum */
                $getNumItems = $this->numberOfItems($eid_ex[1]);
                $sum = $this->totalPoints($eid_ex[1]);
                /* return */
                return response()->json(['done'=>'Successfully Created!','getNumItems'=>count($getNumItems),'sum'=>$sum,'evalparts'=>$evalparts,'examis'=>'arei2','ins_desc1'=>$instruc_desc]);
            } 
        }
        
        //return response()->json(['done'=>'Successfully Created!','getNumItems'=>count($getNumItems),'sum'=>$sum,'evalparts'=>$evalparts]);
    }
    /* ***********Insert To Questions Bank Table************* */
    public function insertQuestionBank($qdesc, $examtype, $points){
        return DB::table('question_banks')->insertGetId([
                'question_desc' => $qdesc,
                'question_type' => $examtype,
                'points'        => $points,
                'created_at'    => Carbon::now()
            ]);
    }
    /* **********Get Number of Items********* */
    public function numberOfItems($eid){
        /* $getNumItems = */
        return DB::table('cpoep_questions')
            ->join('question_banks', 'cpoep_questions.qb_id','=','question_banks.qb_id')    
            ->where('cpoep_questions.einstruct_id', $eid)->get();
            /* ->where('cpoep_questions.einstruct_id', $eid_ex[0])->get(); */       
    }
    /* **********Get the total points********* */
    public function totalPoints($eid){
        /* $sum = */
        return DB::table('cpoep_questions')
            ->join('question_banks', 'cpoep_questions.qb_id','=','question_banks.qb_id')     
            ->where('cpoep_questions.einstruct_id', $eid)->sum('question_banks.points');
    }
    /* **********Create Multiple Choice Responses************** */
    public function createMultipleChoice($qbank_id, $desc){
        return DB::table('multiple_choices')->insert([
                'qb_id'                 => $qbank_id,
                'choices_description'   => $desc,
                'mc_is_answer'          => 0,
                'created_at'            => Carbon::now()
            ]);
    }
    /* ***********Create CPOEP Questions************* */
    public function cpoepQuestions($qbank_id, $eid){
        return DB::table('cpoep_questions')->insert([
            'qb_id'         => $qbank_id,
            'einstruct_id'  => $eid,
            'created_at'    => Carbon::now()
        ]);
    }
    /* ************Set Multiple Choice Answer****************** */
    public function setAnswer($choice_desc){
        return DB::table('multiple_choices')->where('choices_description', $choice_desc)->update([
            'mc_is_answer'  => 1,
            'updated_at'    => Carbon::now(),
        ]);
    }
    /* *************Create Alternate Responses**************** */
    public function createAlternateResponses($qbid, $isanswer){
        return DB::table('alternate_responses')->insert([
            'qb_id'         => $qbid,
            'ar_is_answer'  => $isanswer,
            'created_at'    => Carbon::now()
        ]);
    }
    /* Post Create Evaluation createCPOEPandEI*/
    /* public function createCPOEPandEI($cpoid,$cpoeptype,$insrtuctionDesc,$evalpart){

        $createCPOEP_id = DB::table('cpo_evaluation_parts')->insertGetId([
            'cpo_id'            => $cpoid,
            'cpoep_type'        => $cpoeptype,
            'cpoep_isactive'    => 0,
            'created_at'        => Carbon::now(),
        ]);
        $createEI = DB::table('eval_instructions')->insert([
            'cpoep_id'          => $createCPOEP_id,
            'instruction_desc'  => $insrtuctionDesc,
            'eval_parts'        => $evalpart,
            'created_at'        => Carbon::now(),
        ]);
        if($createCPOEP_id && $createEI){
            return true;
        }
        return false;
    } */
    /* Get Specific Course Program Offer  */
    public function courseProgramOffer($cpoid){
        if(isset($cpoid)){
            return DB::table('course_program_offers')
                ->join('courses_programs','course_program_offers.cp_id','=','courses_programs.cp_id')
                ->join('courses','courses_programs.course_id','=','courses.course_id')
                ->join('programs','courses_programs.program_id','=','programs.program_id')
                ->where('course_program_offers.cpo_id', $cpoid)->get();
        }
        return null;
    }
    /* Get Specific Course Program Offer Evaluation Created*/
    public function courseProgramOfferEvalutionCreated($cpoid){
        if(isset($cpoid)){
            return DB::table('course_program_offers')
                ->join('courses_programs','course_program_offers.cp_id','=','courses_programs.cp_id')
                ->join('courses','courses_programs.course_id','=','courses.course_id')
                ->join('programs','courses_programs.program_id','=','programs.program_id')
                ->join('cpo_evaluation_parts','course_program_offers.cpo_id','=','cpo_evaluation_parts.cpo_id')
                ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id')
                ->orderBy('cpo_evaluation_parts.created_at', 'asc')
                ->where('course_program_offers.cpo_id', $cpoid)->get();
        }
    }
    /* ***********Uploading Course Materials*************** */
    public function pageToUploadCM(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $courseProgramOffers=$this->courseProgramOffer($request->cpoid);
        foreach ($courseProgramOffers as $key => $value) {
            $cpoid = $value->cpo_id;
        }
        $getfiles = DB::table('course_materials')
            ->join('cpo_course_materials','course_materials.cm_id','=','cpo_course_materials.cm_id')
            ->orderBy('course_materials.created_at', 'desc')
            ->where('cpo_course_materials.cpo_id',$cpoid)
            ->get();
        return view('instructor.cm.upload-cm-page', compact('useraccount', 'courseProgramOffers','getfiles'));
        
    }
    /* ************Submiting Course Materials************* */
    public function postCourseMaterials(Request $request){
        $validate = $request->validate([
            'file_desc'         => 'required', 
            'coursematerials'   => 'required|max:1999|mimes:jpg,jpeg,bmp,png,doc,docx,csv,rtf,xlsx,xls,txt,pdf,mpeg,mp4,wav,mp3'
        ]);
        
        if($request->hasFile('coursematerials')){
            //Get filename with the extension
            $filenameWithExt = $request->file('coursematerials')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extention = $request->file('coursematerials')->getClientOriginalExtension();
            //Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extention;
            //Upload image
            $path = $request->file('coursematerials')->storeAs('public/coursematerials', $fileNameToStore);
        }
        $insertWithId = DB::table('course_materials')->insertGetId([
            'cm_description'    => $request->file_desc,
            'cm_files'          => $fileNameToStore,
            'created_at'        => Carbon::now()     
        ]);;
        /* cpoid */
        $insertcpocm = DB::table('cpo_course_materials')->insert([
            'cpo_id'        => $request->cpoid,
            'cm_id'         => $insertWithId,
            'created_at'    => Carbon::now()
        ]);
        //dd($request->file('coursematerials'));
        return redirect()->back()->with('success', 'Successfully Created.');
    }
    /* Delete course materials */
    public function removeCourseMaterials(Request $request){
        /* $getfiles = DB::table('course_materials')
            ->join('cpo_course_materials','course_materials.cm_id','=','cpo_course_materials.cm_id')
            ->where('cpo_course_materials.cpo_id',$cpoid)->get(); */
        //return $request->cmid;
        $cpocm=DB::table('cpo_course_materials')->where('cm_id', $request->cmid)->delete();
        $cm=DB::table('course_materials')->where('cm_id', $request->cmid)->delete();
        if($cpocm && $cm){
            return response()->json(['removed'=>"Successfully Deleted."]);
        }
        return response()->json(['wrong'=>"Something went wrong during deletion."]);
    }
    /* Dowload Course Materials */
    public function downLoadCourseMaterials(Request $request){
        return response()->download(public_path()."\\storage\\coursematerials\\".$request->file);
    }
    /* Show My Class */
    public function showMyClass(Request $request){
        //dd($request->cpoid_ceic);
        //get course program offers cpoid_ceic
        //courseProgramOfferEvalutionCreated();
        $useraccount = $this->getUserLoggedInCredentials();
        $courseProgramOffers=$this->courseProgramOffer($request->cpoid_ceic);
        $getcce = DB::table('course_program_offers')
                ->join('cpo_evaluation_parts', 'course_program_offers.cpo_id','=','cpo_evaluation_parts.cpo_id')
                /* ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id') */
                ->where('course_program_offers.cpo_id', $request->cpoid_ceic)->get();
        /* $getcce1 = DB::table('course_program_offers')
        ->join('cpo_evaluation_parts', 'course_program_offers.cpo_id','=','cpo_evaluation_parts.cpo_id')
        ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id')
        ->where('course_program_offers.cpo_id', $request->cpoid_ceic)->get(); */
        $evalParts = DB::table('course_program_offers')
                ->join('cpo_evaluation_parts','course_program_offers.cpo_id','=','cpo_evaluation_parts.cpo_id')
                ->orderBy('cpo_evaluation_parts.created_at', 'desc')
                ->where('course_program_offers.cpo_id',$request->cpoid_ceic)->get();
        $myClassEval=array();
        if(count($evalParts) > 0){
            foreach ($evalParts as $key => $value) {
                $myClassEval[] = array(
                    'cpoep_id'          => $value->cpoep_id,
                    'evalType'          => $value->cpoep_type,
                    'isactive'          => $value->cpoep_isactive,
                    'created_at'        => $value->created_at,
                    'evalinstruct'      => array(
                        'eiTbl'=>DB::table('eval_instructions')->where('cpoep_id',$value->cpoep_id)->get()
                    ),
                );
            }
        }
        if(isset($request->viewclasslist) && $request->viewclasslist=='classlist'){
            $getClassList = DB::table('cpo_enroll_students')
                ->join('students', 'cpo_enroll_students.cpoes_stud_id','=','students.stud_id')
                ->join('people','students.people_id','=','people.people_id')
                ->where('cpo_enroll_students.cpo_id',$request->cpoid_ceic)->paginate(20);
            //dd('view class list');
            return view('instructor.show-mycourse.show-my-course-info',compact('useraccount','courseProgramOffers','getClassList'));
        }
        /* View Evaluation Result per class */
        if(isset($request->viewclasslist)&&$request->viewclasslist=='viewevalresult'){
            //dd($request->cpoid_ceic);
            $getEvaluations = DB::table('course_program_offers')
            ->join('cpo_enroll_students','course_program_offers.cpo_id','=','cpo_enroll_students.cpo_id')
            ->join('students','cpo_enroll_students.cpoes_stud_id','=','students.stud_id')
            ->join('people','students.people_id','=','people.people_id')
            ->orderBy('people.last_name', 'asc')
            ->where('course_program_offers.cpo_id',$request->cpoid_ceic)->get();
            
            $evalTypeTotal=array();
            $evalPartsType = DB::table('cpo_evaluation_parts')
                    ->where('cpo_id',$request->cpoid_ceic)->get();
            foreach ($evalPartsType as $key => $value) {
                $evalTypeTotal[] = array(
                    'eval_type' => $value->cpoep_type,
                    'total_eval'     => DB::table('eval_instructions')
                        ->join('cpoep_questions','eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                        ->join('question_banks','cpoep_questions.qb_id','=','question_banks.qb_id')
                        ->where('eval_instructions.cpoep_id', $value->cpoep_id)->sum('question_banks.points')
                );
            }
            $classEvalResult = array();
            foreach($getEvaluations as $key => $value){
                $classEvalResult[] = array(
                    'last_name'         => $value->last_name,
                    'first_name'        => $value->first_name,
                    'result'            => DB::table('cpoep_cpoes_results')->where('cpoes_id',$value->cpoes_id)->get()
                );
            }
            /* if(count($classEvalResult) > 0){
                return view('instructor.show-mycourse.show-my-course-info',compact('useraccount','courseProgramOffers','classEvalResult','evalTypeTotal'));
            } */
            return view('instructor.show-mycourse.show-my-course-info',compact('useraccount','courseProgramOffers','classEvalResult','evalTypeTotal'));
            //return view('instructor.show-mycourse.show-my-course-info',compact('useraccount','courseProgramOffers','classEvalResult','evalTypeTotal'));
            //dd($classEvalResult);
        }
        
        return view('instructor.show-mycourse.show-my-course-info',compact('useraccount','courseProgramOffers','myClassEval'));
    }
    /* ******Remove Student is A Class************** */
    public function ajaxPostRemoveStudClass(Request $request){
        //dd($request->cpoes_id);
        $reoveStudentFromTheClass = DB::table('cpo_enroll_students')->where('cpoes_id', $request->cpoes_id)->delete();
        return response()->json(['success'=>'Successfully Deleted']);
    }
    /* Activate deactivate evaluation */
    public function postActivateDeactivareEval(Request $request){
        $eifind = DB::table('cpo_evaluation_parts')->where('cpoep_id', $request->cpoepid)->get();
        foreach ($eifind as $key => $value) {
            //return ($value->cpoep_isactive);
            if($value->cpoep_isactive){
                $setdeac = DB::table('cpo_evaluation_parts')->where('cpoep_id', $request->cpoepid)
                ->update([
                    'cpoep_isactive' => 0
                ]);
                if($setdeac)
                    return response()->json(['success'=>'Successfully Deactivated']);
            }else{
                $setac = DB::table('cpo_evaluation_parts')->where('cpoep_id', $request->cpoepid)
                ->update([
                    'cpoep_isactive' => 1
                ]);
                if($setac)
                    return response()->json(['success'=>'Successfully Activated']);
            }
        }
        
    }
    public function viewSpecificCourse(){
        return view('instructor.instructor-view-specific-course');
    }
    /* Students Management */
    public function studentManagement(){
        $useraccount = UserAccount::find(Auth::id());
        return view('instructor.student-management', compact('useraccount'));
    }

    public function studentListPerCourse(){
        return view('instructor.stud-list-percourse');
    }
    public function viewCourseMaterials(){
        return view('instructor.view-course-materials');
    }
    public function viewEvaluationResultAllStud(){
        return view('instructor.view-eval-result-allstud');
    }
    public function viewEvaluationResultAllStudResult(Request $request){
        //dd($request->val);
        $type = $request->val;
        return view('instructor.view-eval-result-allstud', compact('type'));
    }
    public function createEvaluation(){
        return view('instructor.course-create-evaluation');
    }
    public function createEvaluationType(Request $request){
        $type = $request->ceval;
        return view('instructor.course-create-evaluation', compact('type'));
    }

    public function createEvaluationManagement(){
        return view('instructor.instructor-evaluation-mgmt');
    }
    public function createEvaluationViewCourse(){
        return view('instructor.instructor-evaluation-view-course');
    }
}
