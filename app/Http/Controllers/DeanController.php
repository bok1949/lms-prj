<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserAccount;
use App\Department;
use App\Employee;
use App\People;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class DeanController extends Controller
{
    public function __construct(){
        $this->middleware('dean');
    }
   /*  public function getUserCredentials(){
        return UserAccount::find(Auth::id());
    } */
    /* Get the user credentials who logged in */
    public function getUserLoggedInCredentials(){
        return DB::table('people')
            ->join('user_accounts', 'people.people_id','=','user_accounts.people_id')
            ->join('employees', 'people.people_id','=','employees.people_id')
            ->join('departments', 'employees.dept_id','=','departments.dept_id')
            ->where('people.people_id', '=', UserAccount::find(Auth::id())->people_id)
            ->whereIn('user_accounts.username', [UserAccount::find(Auth::id())->username])
            ->get();
    }
    public function dashboard(){
        $useraccount = $this->getUserLoggedInCredentials();
        return view('dean.dean-dashboard', compact('useraccount'));
    }
    /* Change Password View */
    public function accountSettings(){
        //return $this->getUserLoggedInCredentials()->password;
        $useraccount = $this->getUserLoggedInCredentials();
        /* foreach($useraccount as $ua){
            return $ua->password;
        } */
        return view('dean.dean-accountsettings', compact('useraccount'));
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
    /* View List of Users (Students and Instructors) */
    public function userDeanLevelManagement(Request $request){           
        //dd($request->deanusers);
       
        foreach ($this->getUserLoggedInCredentials() as $key => $value) {
            $dept_id = $value->dept_id;
        }
        
        $useraccount = $this->getUserLoggedInCredentials();
        if(!empty($request->deanusers)){
            $deanusers =$request->deanusers;
            /* **************Instructors Request****************** */
            if($deanusers == 'instructor'){
                $deanusers='instructor';
                /* list of faculty members per-department */
                $employees = DB::table('people')
                ->join('user_accounts', 'people.people_id','=','user_accounts.people_id')
                ->join('employees', 'people.people_id','=','employees.people_id')
                ->join('departments', 'employees.dept_id','=','departments.dept_id')
                ->where('people.people_id', '<>', UserAccount::find(Auth::id())->people_id)
                ->whereIn('departments.dept_id', [$dept_id])
                ->orderBy('people.last_name', 'asc')
                ->paginate(20);
                //dd($employees);
                /* For Editing specific employee */
                if(isset($request->id)){
                    $getEmpInfo = $this->instructorInfoCredentials($request->id);
                    //dd($getEmpInfo);
                    return view('dean.user-management', compact('useraccount','deanusers', 'employees','getEmpInfo'));
                }
                //dd("Return view list of Emplouyees");
                return view('dean.user-management', compact('useraccount','deanusers', 'employees'));
            }
            /* ****Adding Instructor Form***** */
            if($deanusers == "addinstructor-form"){
                $addinstructor='addinstructor-form';
                $departments = $this->getDepartment($dept_id);
                return view('dean.user-management', compact('useraccount','deanusers','departments','addinstructor'));
            }
            /* ****Adding Students*********** */
            if($deanusers == "addstudent-form"){
                $addstudent='addstudent-form';
                $departments = $this->getDepartment($dept_id);
                $programs = DB::table('programs')->where('dept_id', $dept_id)->get();
                //dd($programs);
                return view('dean.user-management', compact('useraccount','deanusers','departments', 'programs','addstudent'));
            }
            /* ******************Students Request****************************** */
            if($deanusers == 'student'){
                $deanusers='student';
                /* list of students per-department */
                //dd($dept_id);
                $students = $this->getStudents(null,$dept_id);
                //dd($request->id);
                if(isset($request->id)){
                    $getStudInfo = $this->getStudents($request->id,null);
                    $programs = DB::table('programs')->where('dept_id',$dept_id)->get();
                    return view('dean.user-management', compact('useraccount','deanusers', 'students','getStudInfo','programs'));
                }
                return view('dean.user-management', compact('useraccount','deanusers', 'students'));    
            }
            return redirect()->back();
            /* abort(404, "Page Not Found"); */        
        }
       
        return view('dean.user-management', compact('useraccount'));
    }
/* *****Upload List of Students in the System***** */
    public function uploadStudentList(Request $request){
        //dd($request);
        $path = $request->file('upload_students')->getRealPath();
        $data = Excel::load($path)->get();
        //dd($data);
        $studExist = array();
        $programNotFound=array();
        $programId=array();
        $studentData=array();
        if(count($data)>0){
            /* Check Student number if existing then return notifation if IDnumber already existing*/
            foreach ($data as $key => $value) {
                if($this->findStudentDuplicate($value->id_number) > 0){
                    $studExist[] = $value->lastname.", ".$value->firstname;
                }
                if(count($this->getProgramId($value->program_code)) == 0){
                    $programNotFound[] = $value->program_code;
                }else{
                    $programId[] = $this->getProgramId($value->program_code);
                    $studentData[]=[
                        'idnumber'          =>$value->id_number,
                        'lastname'          =>$value->lastname,
                        'firstname'         =>$value->firstname,
                        'middlename'        =>$value->lastname,
                        'extension_name'    =>$value->extension_name,
                        'yearlevel'         =>$value->year_level,
                        'program_id'        =>$this->getProgramId($value->program_code)
                    ];
                }
            }
            /* return warning message for duplicate idnumber */
            if(count($studExist) > 0){
                return redirect()->back()->with(['studExist'=>$studExist]);
            }
            /* Program not found */
            if(count($programNotFound)>0){
                return redirect()->back()->with(['programNotFound'=>$programNotFound]);
            }
            /* Get Program ID and stud_id then insert in students programs */
            foreach ($studentData as $key => $value) {
                //return $value['program_id'];
                foreach ($value['program_id'] as $key1 => $value1) {
                    //Insert to People-Table insertGetId
                    $insertPeolpleGetId = DB::table('people')->insertGetId([
                        'id_number'     => $value['idnumber'],
                        'last_name'     => $value['lastname'],
                        'first_name'    => $value['firstname'],
                        'middle_name'   => $value['middlename'],
                        'ext_name'      => $value['extension_name'],
                        'created_at'    => Carbon::now()
                    ]);
                    //insert to students-table insertGetId
                    $insertStudentsGetId = DB::table('students')->insertGetId([
                        'people_id'     => $insertPeolpleGetId,
                        'year_level'    => $value['yearlevel'],
                        'created_at'    => Carbon::now()
                    ]);   
                    //insert to students_programs
                    $insertIntoStudProg = DB::table('students_programs')->insert([
                        'stud_id'       => $insertStudentsGetId,
                        'program_id'    => $value1->program_id,
                        'created_at'    => Carbon::now()    
                    ]);
                    /* Create student account  */
                    $createUserAccount = DB::table('user_accounts')->insert([
                        'username'      => $value['idnumber'],
                        'password'      => bcrypt($value['idnumber']),
                        'user_type'     => 'Student',
                        'ua_status'     => 1,
                        'people_id'     => $insertPeolpleGetId,
                        'created_at'    => Carbon::now()
                    ]);
                    //return $value['lastname'];
                    //return $value1->program_id;
                }
            }
            return back()->with('success', 'Students successfully added in the System');
            //dd ($studentData);
        }else{
            Alert::error('WARNING', "The FILE you are trying to upload has no data.");
            return back()->withErrors(""); 
        }
    }
/* ****Find Students Duplicate in People table************** */
    public function findStudentDuplicate($idnum){
        return DB::table('people')->where('id_number',$idnum)->get()->count();
    }
/* *****Get Program ID*************** */
    public function getProgramId($program_code){
        return DB::table('programs')->where('program_code',$program_code)->get('program_id');
    }
    /* Create Instructor User */
    public function postCreateinstructor(Request $request){
        $validate = $request->validate([
            'employee_id'   => 'required|digits:6',
            'lastname'      => 'required|max:125',
            'firstname'     => 'required|max:125',
            'middlename'    => 'max:125',
            'ext_name'      => 'max:6',
        ]);
        $insertPeoplegetId = DB::table('people')->insertGetId([
            'id_number'     => $request->employee_id,
            'last_name'     => $request->lastname,
            'first_name'    => $request->firstname,
            'middle_name'   => $request->middlename,
            'ext_name'      => $request->ext_name,
            'created_at'    => Carbon::now(),
            'updated_at'    => null 
        ]);
        $insertEmployee = DB::table('employees')->insert([
            'dept_id'       => $request->dept_id,
            'people_id'     => $insertPeoplegetId,
            'created_at'    => Carbon::now(),
            'updated_at'    =>  null
        ]);
        $insertUA = DB::table('user_accounts')->insert([
            'username'      => $request->employee_id,
            'password'      => bcrypt($request->employee_id),
            'user_type'     => 'Instructor',
            'ua_status'     => 1,
            'people_id'     => $insertPeoplegetId,
            'created_at'    => Carbon::now(),
            'updated_at'    =>  null
        ]);
        //dd($validate);
        return redirect()->back()->with('success', 'Instructor Successfully Added.');
    }
    /* Get specific instructor information credentials */
    public function instructorInfoCredentials($ins_id=null){
        return DB::table('people')
            ->join('user_accounts', 'people.people_id','=','user_accounts.people_id')
            ->join('employees', 'people.people_id','=','employees.people_id')
            ->join('departments', 'employees.dept_id','=','departments.dept_id')
            ->where('people.people_id', '=', $ins_id)
            ->get();
    }
    /* Send to update Instructor */
    public function postUpdateInstructor(Request $request){
        $validate = $request->validate([
            'eid_num'       => 'required|digits:6',
            'lastname'      => 'required|max:125',
            'firstname'     => 'required|max:125',
            'mname'         => 'max:125',
            'xname'         => 'max:6',
        ]);
        
        $updateQuery = DB::table('people')
            ->where('people_id', $request->people_id)
            ->update([
                'id_number'     => $request->eid_num,
                'last_name'     => Str::ucfirst(Str::lower($request->lastname)),
                'first_name'    => ucwords(Str::lower($request->firstname)),
                'middle_name'   => Str::ucfirst(Str::lower($request->mname)),
                'ext_name'      => ucwords(Str::lower($request->xname)),
                'updated_at'    => Carbon::now()
            ]);
        return redirect()->route('deanlevelusermgmt', ['instructor'])->with('success', 'Instructor Successfully Updated.');
    }
    /* Reset Instructor Account */
    public function resetAccount(Request $request){
        $people_info = DB::table('people')->where('people_id',$request->pid)->get();
        foreach ($people_info as $key => $value) {
            # code...
            $lname = $value->last_name;
            $fname = $value->first_name;
            $val = $value->id_number;
        }
        $resetAccount = DB::table('user_accounts')
            ->where('id', $request->id)
            ->update([
                'username'  => $val,
                'password'  => bcrypt($val)
                ]);
        return response()->json(['success'=>'Account has been reset.','lname'=>$lname,'fname'=>$fname]);
        
    }
    /* Disable Enable User Account */
    public function enableDisableUserAccount(Request $request){
        $findUser = DB::table('user_accounts')
                ->join('people', 'people.people_id','=','user_accounts.people_id')
                ->where('user_accounts.id', $request->id)->get();
        foreach ($findUser as $key => $value) {
            $lname = $value->last_name;
            $fname = $value->first_name;
            $useraccountStatus = $value->ua_status;
        }
        if($useraccountStatus==1){
            $deactivate = DB::table('user_accounts')->where('id',$request->id)
                        ->update(['ua_status'=>0]);
            return response()->json(['success'=>'User Account has been DEACTIVATED.','lname'=>$lname,'fname'=>$fname,'uastatus'=>0]);
        }
        if($useraccountStatus==0){
            $activate = DB::table('user_accounts')->where('id',$request->id)
                        ->update(['ua_status'=>1]);
            return response()->json(['success'=>'User Account has been ACTIVATED.','lname'=>$lname,'fname'=>$fname,'uastatus'=>1]);
        }
        //return $request->id . " -- " . $request->uastatus;

    }   
    /* Submit to create student */
    public function createPostStudent(Request $request){
        //dd($request);
        $validate = $request->validate([
            'student_id'    => 'required|numeric',
            'lastname'      => 'required|max:125',
            'firstname'     => 'required|max:125',
            'middlename'    => 'max:125',
            'ext_name'      => 'max:6',
            'yearlevel'     => 'required',
            'program_id'    => 'required'
        ]);
        $checkStudentEntry = DB::table('people')->where('id_number', $request->student_id)->count();
        if($checkStudentEntry > 0){
            return redirect()->back()->with('errors', $request->lastname.', '.$request->firstname.' is already added.');
        }
        $addToPeople = DB::table('people')->insertGetId([
            'id_number'     => $request->student_id,
            'last_name'     => Str::ucfirst(Str::lower($request->lastname)),
            'first_name'    => ucwords(Str::lower($request->firstname)),
            'middle_name'   => Str::ucfirst(Str::lower($request->middlename)),
            'ext_name'      => ucwords(Str::lower($request->ext_name)),
            'created_at'    => Carbon::now(),
        ]);
        $insertUA = DB::table('user_accounts')->insert([
            'username'      => $request->student_id,
            'password'      => bcrypt($request->student_id),
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
    /* Update Student Post Method */
    public function postUpdateStudent(Request $request){
        //dd($request);
        $validate = $request->validate([
            'student_id'    => 'required|numeric',
            'lastname'      => 'required|max:125',
            'firstname'     => 'required|max:125',
            'middlename'    => 'max:125',
            'ext_name'      => 'max:6',
            'yearlevel'     => 'required',
            'program_id'    => 'required'
        ]);
        $updateStudents=DB::table('students')
            ->where('stud_id', $request->stud_id)
            ->update([
                'year_level'    => $request->yearlevel,
                'updated_at'    => Carbon::now()
            ]);
        $updateStudentProgram=DB::table('students_programs')
            ->where('stud_id', $request->stud_id)
            ->update([
                'program_id'    => $request->program_id,
                'updated_at'    => Carbon::now()
            ]);
        $updatePeople=DB::table('people')
            ->where('people_id', $request->people_id)
            ->update([
                'id_number'     => $request->student_id,
                'last_name'     => $request->lastname,
                'first_name'    => $request->firstname,
                'middle_name'   => $request->middlename,
                'ext_name'      => $request->ext_name,
                'updated_at'    => Carbon::now()    
            ]);
        return redirect()->route('deanlevelusermgmt', ['student'])->with('success', 'Student Successfully Updated.');
    }
    /* List of Students */
    public function getStudents($studId = null, $dept_id=null){
        if($studId!=null){
            return DB::table('people')
            ->join('user_accounts', 'people.people_id','=','user_accounts.people_id')
            ->join('students', 'people.people_id','=','students.people_id')
            ->join('students_programs', 'students.stud_id','=','students_programs.stud_id')
            ->join('programs', 'students_programs.program_id','=','programs.program_id')
            ->join('departments', 'programs.dept_id','=','departments.dept_id')
            ->where('people.people_id', '=', $studId)
            ->get();
        }

        return DB::table('people')
            ->join('user_accounts', 'people.people_id','=','user_accounts.people_id')
            ->join('students', 'people.people_id','=','students.people_id')
            ->join('students_programs', 'students.stud_id','=','students_programs.stud_id')
            ->join('programs', 'students_programs.program_id','=','programs.program_id')
            ->join('departments', 'programs.dept_id','=','departments.dept_id')
            ->where('departments.dept_id', '=', $dept_id)
            ->orderBy('people.last_name', 'asc')
            ->paginate(20);
    }
    /* list of all departments */
    public function getDepartment($deptId=null){
        if($deptId != null)
            return DB::table('departments')->where('dept_id', $deptId)->get();
        return DB::table('departments')->orderBy('dept_code', 'asc')->get();
    }
    /* list of programs */
    public function getPrograms($programId=null){
        $userDeptId = $this->getUserLoggedInCredentials();
        foreach ($userDeptId as $key => $value) {
            $udId = $value->dept_id;
        }
        if($programId!=null){
            //dd(DB::table('programs')->where('program_id', $programId)->get());
            return DB::table('programs')->where('program_id', $programId)->get(); 
        }
           
        return DB::table('programs')->where('dept_id', $udId)->orderBy('program_code', 'asc')->paginate(20);
    }
    /* ******************Program Management********************* */
    public function programManagement(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $programs = $this->getPrograms();
        
        /* show form to create programs */
        if(isset($request->pmgmt)){
            
            if($request->pmgmt=='addprogram-form'){
                $pmgmt = $request->pmgmt;
                return view('dean.program-management', compact('useraccount', 'pmgmt'));
            }
            if($request->pmgmt=='edit'){
                
                if(count($this->getPrograms($request->programid))){
                    $specificProgram = $this->getPrograms($request->programid);
                    return view('dean.program-management', compact('useraccount', 'programs','specificProgram'));
                }
                return redirect()->back()->with('errors', 'Request not found.');
            }
            return redirect()->back()->with('errors', 'Request not found.');
        }
        
        return view('dean.program-management', compact('useraccount', 'programs'));
    }
    /* Submit Edit Program */
    public function postEditProgram(Request $request){
        $validate = $request->validate([
            'program_code'       => 'required',
            'program_desc'      => 'required|max:125',
        ]);
        $updateQuery = DB::table('programs')
                    ->where('program_id', $request->program_id)
                    ->update([
                        'program_code'          => strtoupper($request->program_code),
                        'program_description'   => ucwords(Str::lower($request->program_desc)),
                        'updated_at'            => Carbon::now()
                    ]);
        return redirect()->route('programmgmt')->with('success', 'Program Successfully Updated.');
        //dd($request);
    }
    /* Create Programs */
    public function postProgram(Request $request){
        //dd($request);
        $validate = $request->validate([
            'program_code'       => 'required',
            'program_desc'      => 'required|max:125',
        ]);
        //dd(ucwords(Str::lower($request->program_desc)));
        $insertProgram = DB::table('programs')->insert([
            'dept_id'               => $request->dept_id,
            'program_code'          => strtoupper($request->program_code),
            'program_description'   => ucwords(Str::lower($request->program_desc)),
            'created_at'            => Carbon::now(),
            'updated_at'            =>  null
        ]);
        return redirect()->back()->with('success', ucwords(Str::lower($request->program_desc)) . ' Created Successfully.');
    }
    /* ***************Courses Management**************** */
    public function courseManagement(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        foreach ($useraccount as $key => $value) {
            $udId = $value->dept_id;
        }
        $allCourses = $this->getAllCourses();
        $programs = DB::table('programs')->where('dept_id', $udId)->orderBy('program_code', 'asc')->get();
        if (isset($request->cmgmt)) {
            if ($request->cmgmt=='addcourse-form') {
                $cmgmt = $request->cmgmt;
                return view('dean.course-management', compact('useraccount', 'cmgmt', 'programs'));
            }
            if($request->cmgmt=='edit' && isset($request->courseid)){
                $getSpecificCourse = $this->getAllCourses($request->courseid);
                if(count($getSpecificCourse)>0){
                    $programsList=DB::table('programs')->where('dept_id',$udId)->get();
                    return view('dean.course-management', compact('useraccount','allCourses','getSpecificCourse','programsList'));
                } 
                return redirect()->back();
            }
            return redirect()->back();
        }
        
        return view('dean.course-management', compact('useraccount','allCourses'));
    }
    /* Get all Courses */
    public function getAllCourses($courseId = null){
        $userDeptId = $this->getUserLoggedInCredentials();
        foreach ($userDeptId as $key => $value) {
            $udId = $value->dept_id;
        }
        $employees = DB::table('courses')
                ->join('courses_programs', 'courses.course_id','=','courses_programs.course_id')
                ->join('programs', 'courses_programs.program_id','=','programs.program_id')
                ->where('programs.dept_id', $udId)
                ->orderBy('courses.descriptive_title', 'asc')
                ->paginate(20);
        if($courseId!=null){
            return DB::table('courses')
                ->join('courses_programs', 'courses.course_id','=','courses_programs.course_id')    
                ->join('programs', 'courses_programs.program_id','=','programs.program_id')
                ->where('courses.course_id', $courseId)->get();
        }
        return $employees;
    }
    /* Create Courses */
    public function postCourse(Request $request){
        $validate = $request->validate([
            'course_code'   => 'required',
            'course_desc'   => 'required|max:125',
            'program_id'    => 'required',
            'lec_unit'      => 'required',
            'lab_unit'      => 'required' 
        ]);
        if(DB::table('courses')->where('course_code', $request->course_code)->count() > 0){
            Alert::error('WARNING', $request->course_code.' Course Code has duplicate!');
            return back()->withErrors(""); 
        }
        $insertCourseId = DB::table('courses')->insertGetId([
            'course_code'       => $request->course_code,
            'descriptive_title' => $request->course_desc,
            'lab_units'         => $request->lab_unit,
            'lec_units'         => $request->lec_unit,
            'created_at'        => Carbon::now(),
            'updated_at'        => null 
        ]);
        $coursePrograms = DB::table('courses_programs')->insert([
            'program_id'    => $request->program_id,
            'course_id'     => $insertCourseId,
            'created_at'    => Carbon::now()
        ]);
        //dd($request);
        return redirect()->back()->with('success', ucwords(Str::lower($request->course_desc)) . ' Created Successfully.');
    }
    /* ***Upload Courses*** */
    public function uploadCourses(Request $request){
        //uploadCourses
        //dd($request);
        /* $path = $request->file('add_courses')->getRealPath();
        
        $data = Excel::load($path)->get();
        dd($data); */
        $path = $request->file('upload_courses')->getRealPath();
        
        $data = Excel::load($path)->get();
        //dd($data);
        $dataStore=array();
        $getProgramID=array();
        $getProgramIDToInsert=array();
        $getCourseCodeIDToInsert=array();
        $findNullRow=array();
        $getCourseCodeId=array();
        $coursesCreatedArraySuccess=array();
        $coursesCreatedArrayError=array();
        $ctr=2;
        if($data->count() > 0){
            //dd("Data greater than 0");
            foreach ($data as $key => $value) {
               
                /* Find Program Code if existing */
                if(DB::table('programs')->where('program_code',$value->program_code)->count() == 0){
                    Alert::error('WARNING', $value->program_code." Program Code Not Found at Row Number: ".$ctr );
                    return back()->withErrors(""); 
                }
                /* Insert Courses that is not available in the DB */
                if(DB::table('courses')->where('course_code',$value['course_code'])->count() == 0){
                    $insertCourseGetId = DB::table('courses')->insert([
                            'course_code'           =>$value->course_code,
                            'descriptive_title'     =>$value->descriptive_title,
                            'lab_units'             =>$value->lab_unit,
                            'lec_units'             =>$value->lec_unit,
                            'created_at'            =>Carbon::now()
                        ]);
                }
                //Check if course_code and descriptive_title column is has empty cell
                if(empty($value->course_code) || empty($value->descriptive_title)){
                    $findNullRow[]="Row Number: ".$ctr." has empty cell.";
                }
                //Store Program Id in getProgramID
                //$getProgramID[]=DB::table('programs')->where([['program_code','=',$value->program_code]])->get('program_id');
                //Store Course Code ID
                if(DB::table('courses')->where('course_code',$value['course_code'])->count() > 0)
                    $getCourseCodeId[]=DB::table('courses')->where('course_code',$value['course_code'])->get('course_id');
                
                //Store all data in an array
                $dataStore[]=[
                    'course_code'           =>$value->course_code,
                    'descriptive_title'     =>$value->descriptive_title,
                    'lec_unit'              =>$value->lec_unit,
                    'lab_unit'              =>$value->lab_unit,
                    'program_code'          =>$value->program_code,
                    'program_id'            =>DB::table('programs')->where([['program_code','=',$value->program_code]])->get('program_id')
                ];
                //'program_id'            =>DB::table('programs')->where('program_code',$value->program_code)->get()
                $ctr++;
            }
            //Check if course code and descriptive title cell if null and identify the row
            if(count($findNullRow)>0){
                Alert::error('WARNING', implode('',$findNullRow));
                return back()->withErrors(""); 
            }
            //Assign program id to $getProgramIDToInsert array
            foreach ($dataStore as $key => $value) {
                foreach ($value['program_id'] as $key => $value) {
                   $getProgramIDToInsert[]=$value->program_id;
                }
            }
            foreach ($getCourseCodeId as $key => $value) {
                foreach ($value as $key => $value) {
                    $getCourseCodeIDToInsert[]= $value->course_id;
                }
                
            }

            foreach ($getCourseCodeIDToInsert as $key => $value) {
                if(DB::table('courses_programs')->where([['program_id','=',$getProgramIDToInsert[$key]], ['course_id','=',$value]])->count() > 0){
                    $coursesCreatedArrayError[]= $dataStore[$key]['course_code'].': '.$dataStore[$key]['descriptive_title'];
                }else{
                    $insertCoursesPrograms=DB::table('courses_programs')->insert([
                        'program_id'    =>$getProgramIDToInsert[$key],
                        'course_id'     =>$value,
                        'created_at'    =>Carbon::now()
                    ]);
                    $coursesCreatedArraySuccess[]= $dataStore[$key]['course_code'].': '.$dataStore[$key]['descriptive_title'];
                }
            }

            if(count($coursesCreatedArraySuccess)>0 && count($coursesCreatedArrayError)>0){
                return back()->with(['createdCourse'=>$coursesCreatedArraySuccess, 'errorCourse'=>$coursesCreatedArrayError]);
            }elseif (count($coursesCreatedArraySuccess)>0) {
                return back()->with(['createdCourse'=>$coursesCreatedArraySuccess]);
            }elseif (count($coursesCreatedArrayError)>0) {
                return back()->with(['errorCourse'=>$coursesCreatedArrayError]);
            }
            //return back()->with(['createdCourse'=>$coursesCreatedArraySuccess, 'errorCourse'=>$coursesCreatedArrayError]);
           
                //return view('dean.course-management', compact('useraccount', 'cmgmt', 'programs'));
            //return response()->json(['createdCourse'=>$coursesCreatedArraySuccess, 'errorCourse'=>$coursesCreatedArrayError]);
            
            //return $courseCodeArr;
            //return back()->with('success', 'Data are successfully Inserted.');
           
        }else{
            Alert::error('WARNING', "File has no valid content.");
            return back()->withErrors(""); 
        }
    }
    /* Update Courses */
    public function postEditCourses(Request $request){
        //dd($request);
        $validate = $request->validate([
            'course_code'   => 'required',
            'course_desc'   => 'required|max:125',
            'lec_unit'      => 'required',
            'lab_unit'      => 'required' 
        ]);
        $updateCourse = DB::table('courses')
            ->where('course_id', $request->course_id)
            ->update([
                'course_code'           => strtoupper($request->course_code),
                'descriptive_title'     => ucwords(Str::lower($request->course_desc)),
                'lab_units'     => strtoupper($request->lab_unit),
                'lec_units'     => ucwords(Str::lower($request->lec_unit)),
                'updated_at'            => Carbon::now()
        ]);
        $cupdateCoursesPrograms = DB::table('courses_programs')
            ->where('cp_id', $request->cp_id)
            ->update([
                'program_id'    => $request->program_id,
                'updated_at'    => Carbon::now()
        ]);
        //dd($updateCourse.$cupdateCoursesPrograms);
        return redirect()->route('coursemgmt')->with('success', 'Course Successfully Updated.');
    }
}
