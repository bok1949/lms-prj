<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UserAccount;
use App\People;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Excel;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class SystemAdministratorController extends Controller
{
    public function __construct(){
        $this->middleware('admin');
    }
    /* Get the user credentials who logged in */
    public function getUserLoggedInCredentials(){
        return DB::table('people')
            ->join('user_accounts', 'people.people_id','=','user_accounts.people_id')
            ->where('people.people_id', '=', UserAccount::find(Auth::id())->people_id)
            ->whereIn('user_accounts.username', [UserAccount::find(Auth::id())->username])
            ->get();
    }
    public function dashboard(){
        $useraccount = $this->getUserLoggedInCredentials();
        return view('admin.admin-dashboard',compact('useraccount'));
    }
    /* Change Password View */
    public function accountSettings(){
        $useraccount = $this->getUserLoggedInCredentials();
        return view('admin.admin-accountsettings', compact('useraccount'));
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
    /* ********List of Colleges******** */
    public function collegeManagement(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $getColleges=DB::table('departments')->orderBy('dept_code', 'asc')->paginate(20);
        if(isset($request->cm)){
            $cm=$request->cm;
            if($request->cm="viewcollegelist"){
                return view('admin.college-management',compact('useraccount','getColleges','cm'));
            }
            if($request->cm="addcollege"){
                return view('admin.college-management',compact('useraccount','cm'));
            }
            return view('admin.college-management',compact('useraccount'));
        }
        
        return view('admin.college-management',compact('useraccount'));
    }
    /* *******Create College****** */
    public function postCreateCollege(Request $request){
        //dd($request);
        $insertCollege=DB::table('departments')->insert([
            'dept_code'             =>strtoupper($request->dept_code),
            'dept_description'      =>$request->dept_desc,
            'dept_status'           =>1,
            'created_at'            =>Carbon::now()
        ]);
        return back()->with('success', 'College Created Successfully.');
    }
    /* ********Editing Specific College*********** */
    public function editingCollegeInfo(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $getCollegeInfo=DB::table('departments')->where('dept_id', $request->collegeid)->get();
        if(count($getCollegeInfo)>0){
            return view('admin.admin-college-mgmt.edit-department-info',compact('useraccount','getCollegeInfo'));
        }
        return redirect()->route('admincm');
    }
    /* *********Saving College Info Edit********** */
    public function saveEditingCollegeInfo(Request $request){
        $validate = $request->validate([
            'dept_code'       => 'required',
            'dept_desc'     => 'required'
        ]);
        $updateDept = DB::table('departments')
            ->where('dept_id', $request->dept_id)->update([
            'dept_code'         => $request->dept_code,
            'dept_description'  => $request->dept_desc,
            'updated_at'    => Carbon::now(),
        ]);
        
        if($updateDept){
            return redirect()->back()->with('success', $request->dept_code." is successfully updated.");
        }
        return redirect()->back()->with('errors', " Update went wrong!");
    }
    /* *******Program Management****** */
    public function programListManagement(Request $request){
        //dd($request->collegeid);
        $useraccount = $this->getUserLoggedInCredentials();
        $getCollegeInfo=DB::table('departments')->where('departments.dept_id', $request->collegeid)->get();
        
        $programs = DB::table('programs')->where('dept_id',$request->collegeid)->paginate(20);
        
        $courses = DB::table('courses_programs')
            ->join('courses','courses_programs.course_id','=','courses.course_id')
            ->join('programs','courses_programs.program_id','=','programs.program_id')
            ->where('dept_id',$request->collegeid)
            ->orderBy('courses.descriptive_title', 'asc')
            ->paginate(15); 
        //return back()->with("errors", "Test Errors",'errors');
        if(isset($request->prog_course)){
            $prog_course=$request->prog_course;
            if($request->prog_course=="programs"){
                return view('admin.admin-program-mgmt.program-list-mgmt-home',compact('useraccount','getCollegeInfo','prog_course'));
            }
            if($request->prog_course=="course"){
                $findProgramId = DB::table('programs')->where('program_id', $request->progid)->get();
                if(count($findProgramId)>0)
                    return view('admin.admin-program-mgmt.program-list-mgmt-home',compact('useraccount','getCollegeInfo','prog_course','findProgramId'));
                return view('admin.admin-program-mgmt.program-list-mgmt-home',compact('useraccount','getCollegeInfo','programs'));
            }
        }
        
        if(count($getCollegeInfo)>0){
            return view('admin.admin-program-mgmt.program-list-mgmt-home',compact('useraccount','getCollegeInfo','programs'));
        }
        return redirect()->route('admincm');
    }
    /* ********Create Programs*********** */
    public function createPrograms(Request $request){
        //dd($request);
        $validator = Validator::make($request->all(), [
            'program_code'  => 'required',
            'program_desc'  => 'required'
        ]);
        
        if ($validator->fails()) {
            Alert::error('WARNING', "All Fields are Required");
            return back()->withErrors($validator)->withInput();
        }
        /* Check Program if Exists */
        $checkProgram = DB::table('programs')->where('program_code',strtoupper($request->program_code))->get()->count();
        if($checkProgram>0){
            Alert::error('WARNING', $request->program_code." already existed.");
            return back()->withErrors($validator)->withInput();
        }
        $insertProgram = DB::table('programs')->insert([
            'dept_id'               => $request->dept_id,
            'program_code'          => $request->program_code,
            'program_description'   => $request->program_desc,
            'created_at'            => Carbon::now()
        ]);
        return back()->with('success',$request->program_code. ' Successfully Inserted.');
    }
    /* *****View All Courses************* */
    public function viewAllCourses(){
        $useraccount = $this->getUserLoggedInCredentials();
        $getAllCourses = DB::table('courses')
            /* ->leftJoin('courses_programs','courses.course_id','=','courses_programs.course_id')
            ->leftJoin('programs','courses_programs.program_id','=','programs.program_id') */
            ->paginate(20);
            //dd($getAllCourses);
        return view('admin.admin-course-mgmt.view-all-courses', compact('useraccount','getAllCourses'));
    }
    /* Update Specific Course */
    public function updateCourseInfo(Request $request){
        //Update Courses
        $updateCourseTable = DB::table('courses')->where('course_id',$request->course_id)->update([
            'course_code'           =>$request->course_code,
            'descriptive_title'     =>$request->course_title,
            'lab_units'             =>$request->lab_unit,
            'lec_units'             =>$request->lec_unit,
            'updated_at'            => Carbon::now()
        ]);
        if($updateCourseTable){
            return response()->json(['success'=>'Successfully Updated']);
        }
        return response()->json(['error'=>'Something went wrong']);
    }
    /* *****Add Gen-Ed Courses****************** */
    public function viewAddCourses(){
        $useraccount = $this->getUserLoggedInCredentials();
        
        return view('admin.admin-course-mgmt.add-courses', compact('useraccount'));
    }
    /* *******Create Courses One at a time******* */
    public function createCourse(Request $request){
        //dd($request);
        //Check input if empty
        $validator = Validator::make($request->all(), [
            'course_code'   => 'required',
            'course_desc'   => 'required',
            'program_id'    => 'required',
            'lec_unit'      => 'required|numeric',
            'lab_unit'      => 'required|numeric'
        ]);
        
        if ($validator->fails()) {
            Alert::error('WARNING', "All Fields are Required");
            return back()->withErrors($validator)->withInput();
        }
        //check if course already exist
        $checkCourse = DB::table('courses')->where('course_code', $request->course_code)->get()->count();
        if ($checkCourse>0) {
            Alert::error('WARNING', $request->course_code." already existed.");
            return back()->withErrors($validator)->withInput();
        }
        //insert course
        //dd($request);
        $insertCoursesGetId = DB::table('courses')->insertGetId([
            'course_code'           => $request->course_code,
            'descriptive_title'     => $request->course_desc,
            'created_at'            => Carbon::now()
        ]);
        $insertCourseProgram = DB::table('courses_programs')->insert([
            'program_id'    =>$request->program_id,
            'course_id'     =>$insertCoursesGetId,
            'lab_units'     =>$request->lab_unit,
            'lec_units'     =>$request->lec_unit,
            'created_at'    =>Carbon::now()
        ]);
        if($insertCoursesGetId&&$insertCourseProgram)
            return back()->with('success', 'Successfully Created.');
        Alert::error('WARNING', "Something went wrong during data insertion in you database.");
        return back()->withErrors($validator)->withInput(); 
    }
    /* Create All courses using Excel File */
    public function uploadCoursesAll(Request $request){
        $path = $request->file('upload_ge_courses')->getRealPath();
        
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
           
        }else{
            Alert::error('WARNING', "File has no valid content.");
            return back()->withErrors(""); 
        }
    }
    /* *******Create Courses in Using Excel File Upload******** */
    public function uploadCourses(Request $request){
        
        $path = $request->file('upload_courses')->getRealPath();
        
        $data = Excel::load($path)->get();
        //dd($data);
        //dd($request->dept_id);
        //$request->dept_id;
        $keyArr=array();
        $dataStore=array();
        $progamNotFound=array();
        $getProgramID=array();
        $findNullRow=array();
        $ctr=2;
        if($data->count() > 0){
            //dd("Data greater than 0");
            foreach ($data as $key => $value) {
               
                $findCourse=DB::table('programs')->where([
                    ['program_code','=',$value->program_code],
                    ['dept_id','=',$request->dept_id]
                ])->get();
                //dd($findCourse);
                if(empty($value->program_code)){
                    $findNullRow[]=$value->course_code." Row Number: ".$ctr." ";
                }
                if($findCourse->count() > 0){
                    $dataStore[]=[
                        'course_code'           =>$value->course_code,
                        'descriptive_title'     =>$value->descriptive_title,
                        'lec_unit'              =>$value->lec_unit,
                        'lab_unit'              =>$value->lab_unit,
                        'program_id'            =>DB::table('programs')->where('program_code',$value->program_code)->get()
                    ];
                }else{
                    //$progamNotFound[] = $value->program_code." Row Number: ".$ctr." ";
                    $progamNotFound[] = $value->program_code;
                }   
                $ctr++;
            }
           
            /* Check program_code column if null */
            if(count($findNullRow)>0){
                Alert::error('WARNING', "Program is empty in ".implode('',$findNullRow));
                return back()->withErrors(""); 
                return back()->with(['prognotfound'=>$progamNotFound]); 
            }
            /* Check Program in the Database */
            if(count($progamNotFound)>0){
                //Alert::error('WARNING', "Program Not Found at ".implode('',$progamNotFound));
                //return back()->withErrors(""); 
                return back()->with(['prognotfound'=>$progamNotFound]); 
            }
            foreach ($dataStore as $key => $value) {
                foreach ($value['program_id'] as $key1 => $value1) {
                    $getProgramID[]=$value1->program_id;
                }
            }
            /* ***Insert Courses*** */
            foreach($getProgramID as $key=>$value){
                    //$var[]= ($dataStore[$key]['course_code']);
                $insertCourseGetId = DB::table('courses')->insertGetId([
                    'course_code'       => $dataStore[$key]['course_code'],
                    'descriptive_title' => $dataStore[$key]['descriptive_title'],
                    'created_at'        => Carbon::now()
                ]);
                $insertCoursesPrograms=DB::table('courses_programs')->insert([
                    'program_id'    =>$value,
                    'course_id'     =>$insertCourseGetId,
                    'lab_units'     =>$dataStore[$key]['lab_unit'],
                    'lec_units'     =>$dataStore[$key]['lec_unit'],
                    'created_at'    =>Carbon::now()
                ]);
                
            }
            return back()->with('success', 'Data are successfully Inserted.');
           
        }else{
            Alert::error('WARNING', "File has no valid content.");
            return back()->withErrors(""); 
        }

    }
    /* *********Create Faculty Loadings Per Department Index************** */
    public function createFacultyLoading(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        $getCollegeInfo=DB::table('departments')->where('departments.dept_id', $request->collegeid)->get();
        
        $courses = DB::table('courses_programs')
            ->join('courses','courses_programs.course_id','=','courses.course_id')
            ->join('programs','courses_programs.program_id','=','programs.program_id')
            ->orderBy('courses.descriptive_title', 'asc')
            ->get();

        if(count($getCollegeInfo)>0){
            return view('admin.admin-college-mgmt.create-faculty-loading',compact('useraccount','getCollegeInfo','courses'));
        }
        return redirect()->route('admincm');
        
    }
    /* ******Create Faculty Loadings One at a Time************ */
    public function postCreateFacultyLoadings(Request $request){
        //dd($request);
        //Check duplicate SC-ID
        $validator = Validator::make($request->all(), [
            'ins_id'                => 'required',
            'schedule'              => 'required',
            'sc_id'                 => 'required',
            'ay'                    => 'required',
            'term'                  => 'required',
            'course_program_id'     => 'required'
        ]);
        $checkScID = DB::table('course_program_offers')->where('sc_id',$request->sc_id)->get()->count();
        if($checkScID > 0){
            Alert::error('WARNING', "SC-ID is already in used.");
            return back()->withErrors("")->withInput();
        }
        //Check Instructor ID if existing or not
        $checkInstructor=DB::table('people')->where('id_number', $request->ins_id)->get()->count();
        if($checkInstructor==0){
            Alert::error('WARNING', "Instructor does not exist.");
            return back()->withErrors("")->withInput();
        }
        /* Get Employee ID */
        $empId = DB::table('people')
            ->join('employees','people.people_id','=','employees.people_id')
            ->where('people.id_number',$request->ins_id)->first('employees.emp_id');
        //dd($empId->emp_id);
        /* Insert faculty load */
        $insertFactLoad=DB::table('course_program_offers')->insert([
            'cp_id'         =>$request->course_program_id,
            'ins_id'        =>$empId->emp_id,
            'sc_id'         =>$request->sc_id,
            'schedule'      =>$request->schedule,
            'ay'            =>$request->ay,
            'term'          =>$request->term,
            'created_at'    =>Carbon::now()
        ]);
        if($insertFactLoad){
            return back()->with('success','Class successfully loaded to Instructor.');
        }
        Alert::error('WARNING', "Database problem.");
        return back()->withErrors("")->withInput();
    }
    /* Upload Excel File Faculty Loadings */
    public function uploadFacultyLoading(Request $request){
      
       /*  $validate = $request->validate([
            'faculty_loadings'     => 'required|mimes:xls,xlsx'
        ]);
        if($validate){
            //dd($validate);
        } */
        $path = $request->file('faculty_loadings')->getRealPath();
        $getCPID=array();
        $data = Excel::load($path)->get();
        //dd($data);
        if($data->count()){
            foreach ($data as $key => $value) {
                $arr[]=[
                    'sc_id'         =>$value->sc_id,
                    'sc_code'       =>$value->sc_code,
                    'desc_title'    =>$value->descriptive_title,
                    'schedule'      =>$value->schedule,
                    'ay'            =>$value->ay,
                    'term'          =>$value->term,
                    'ins_id'        =>$value->instructor_id,
                    'program_code'  =>$value->program_code
                ];
            }
            /* find courses if existing or not */
            foreach ($arr as $key => $value) {
                //dd($value['sc_id']);
                $findCourse=DB::table('courses')->where('course_code', $value['sc_code'])->get();
                $getCPID[] = DB::table('courses')
                    ->join('courses_programs', 'courses.course_id','=','courses_programs.course_id')
                    ->where('courses.course_code', $value['sc_code'])->get('courses_programs.cp_id');
                /* Check if course code is existing */
                if($findCourse->isEmpty()){
                    Alert::error('WARNING', $value['sc_code'].' Course not found.');
                    return redirect()->back();
                }
                /* Check program if existing */
                if(DB::table('programs')->where('program_code',$value['program_code'])->count() == 0){
                    Alert::error('WARNING', $value['program_code']." Program Code Not Found." );
                    return back()->withErrors(""); 
                }
                /* Check if SCID format */
                if(!is_string($value['sc_id'])){
                    Alert::error('WARNING', 'Check the sc_id column it must be in TEXT format.');
                    return redirect()->back();
                }
                /* Check SC_ID */
                if(DB::table('course_program_offers')->where('sc_id',$value['sc_id'])->count() > 0){
                    Alert::error('WARNING', $value['sc_id']." SC-ID is already in used.");
                    return back()->withErrors("")->withInput();
                }
                //Check Instructor if Exisitng
                $checkInstructor=DB::table('people')->where('id_number', $value['ins_id'])->get()->count();
                if($checkInstructor==0){
                    Alert::error('WARNING', 'Instructor ID='.$value['ins_id'].' is not existing in any of the colleges.');
                    return redirect()->back();
                }
                $empId[] = DB::table('people')
                ->join('employees','people.people_id','=','employees.people_id')
                ->where('people.id_number',$value['ins_id'])->first('employees.emp_id');
            }
            $cp_id=array();
            foreach ($getCPID as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $cp_id[]=$value1->cp_id;
                }
            }
            
            foreach ($arr as $key => $value) {
                $insertFactLoad=DB::table('course_program_offers')->insert([
                    'cp_id'         =>$cp_id[$key],
                    'ins_id'        =>$empId[$key]->emp_id,
                    'sc_id'         =>$value['sc_id'],
                    'schedule'      =>$value['schedule'],
                    'ay'            =>$value['ay'],
                    'term'          =>$value['term'],
                    'created_at'    =>Carbon::now()
                ]);
            }
            return back()->with('success', 'Faculty Loadings Uploaded Successfully');
            
        }else{
            Alert::error('WARNING', "File has no valid content to upload.");
            return back()->withErrors(""); 
        }
    }
    /* *****usersManagement***** */
    public function usersManagement(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        if(isset($request->usertype)){
            //return $request->usertype;
            $usertype=$request->usertype;
            if($usertype=='Admin'){
                
                foreach ($useraccount as $key => $value) {
                    //dd($value->username);
                    $users = DB::table('user_accounts')
                    ->join('people','user_accounts.people_id','=','people.people_id')
                    ->where([
                    ['user_accounts.username', '!=', $value->username],
                    ['user_accounts.user_type','=',$usertype]
                    ])->paginate(20);
                }
                
                return view('admin.users-management',compact('useraccount','users','usertype'));
            }
            $users = $this->getAllUsers($request->usertype);
            return view('admin.users-management',compact('useraccount','users','usertype'));
        }
        if(isset($request->adduser)){
            //dd($request->adduser);
            $programs = DB::table('programs')->orderBy('program_code')->get();
            $department = DB::table('departments')->orderBy('dept_code')->get();
            $adduser=$request->adduser;
            return view('admin.users-management',compact('useraccount','adduser','programs','department'));
        }
        return view('admin.users-management',compact('useraccount'));
    }
    /* *******Adding Student********** */
    public function postAddStudent(Request $request){
        //dd($request);
        /* Add student  to People and getId */
        $checkStudentEntry = DB::table('people')->where('id_number', $request->stud_idnumber)->count();
        if($checkStudentEntry > 0){
            Alert::error('WARNING', $request->lastname.', '.$request->firstname.' is already added.');
            return redirect()->back()->with('');
        }
        $peopleId = DB::table('people')->insertGetId([
            'id_number'     => $request->stud_idnumber,
            'last_name'     => Str::ucfirst(Str::lower($request->lastname)),
            'first_name'    => ucwords(Str::lower($request->firstname)),
            'middle_name'   => Str::ucfirst(Str::lower($request->middlename)),
            'ext_name'      => ucwords(Str::lower($request->ext_name)),
            'created_at'    => Carbon::now()
        ]);
        
        /* Add student  to user account table*/
        $insertUA = DB::table('user_accounts')->insert([
            'username'      => $request->stud_idnumber,
            'password'      => bcrypt($request->stud_idnumber),
            'user_type'     => 'Student',
            'ua_status'     => 1,
            'people_id'     => $peopleId,
            'created_at'    => Carbon::now()
        ]);
        /* Add student  to student table */
        $addToStudents = DB::table('students')->insertGetId([
            'people_id'     => $peopleId,
            'year_level'    => $request->yearlevel,
            'created_at'    => Carbon::now(),
        ]);
        /* Add student  to student students_programs */
        $addToStudentsPrograms = DB::table('students_programs')->insert([
            'stud_id'       => $addToStudents,
            'program_id'    => $request->program_id,
            'created_at'    => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'Student Successfully Created.');
    }
    /* Adding Multiple Students */
    public function postAddMultiStudent(Request $request){
        //Get the file path
        $path = $request->file('add_studlist')->getRealPath();
        //create array of data
        $data = Excel::load($path)->get();
        //dd($data);
        $studExist=array();
        $programDontExist=array();
        $studAlreadyEnrolled=array();
        if($data->count() > 0){
            /* Get Student ID from students table */
            /* program_code */
            foreach ($data as $key => $value) {
                //Check student if Exist
                if ($this->findPeopleById($value->id_number)>0) {
                    $studExist[] = $value->lastname.", ".$value->firstname;
                }
                //$programDontExist[] = $value->program_code;
                if(count($this->findProgram($value->program_code)) == 0){
                    $programDontExist[] = $value->program_code;
                }
                 
            }
            //dd($studExist);
            //dd($programDontExist);
            /* Check if students do exist in the system */
            if(count($studExist) > 0){
               return redirect()->back()->with(['studentsexist'=>$studExist]);
            }
            if(count($programDontExist) > 0){
                return redirect()->back()->with(['programdontexist'=>$programDontExist]);
            }
            /* Insert Student in people table with insertGetId */
            foreach ($data as $key => $value) {
                $peopleId = DB::table('people')->insertGetId([
                    'id_number'     => $value->id_number,
                    'last_name'     => Str::ucfirst(Str::lower($value->lastname)),
                    'first_name'    => ucwords(Str::lower($value->firstname)),
                    'middle_name'   => Str::ucfirst(Str::lower($value->middlename)),
                    'ext_name'      => ucwords(Str::lower($value->extension_name)),
                    'created_at'    => Carbon::now()
                ]);
                 /* Add student  to user account table */
                $insertUA = DB::table('user_accounts')->insert([
                    'username'      => $value->id_number,
                    'password'      => bcrypt($value->id_number),
                    'user_type'     => 'Student',
                    'ua_status'     => 1,
                    'people_id'     => $peopleId,
                    'created_at'    => Carbon::now()
                ]);
                /* Add student  to student table */
                $addToStudents = DB::table('students')->insertGetId([
                    'people_id'     => $peopleId,
                    'year_level'    => $value->year_level,
                    'created_at'    => Carbon::now(),
                ]);
                /* Add student  to student students_programs */
                foreach ($this->findProgram($value->program_code) as $key1 => $value1) {
                    $addToStudentsPrograms = DB::table('students_programs')->insert([
                        'stud_id'       => $addToStudents,
                        'program_id'    => $value1->program_id,
                        'created_at'    => Carbon::now(),
                    ]);
                }
                
            }
            
            return back()->with('success', 'Students successfully created.');
        }else{
            Alert::error('WARNING', "File has no valid content, please check your file before uploading it.");
            return back()->withErrors(""); 
        }
    }
    /* ******FIND STUDENTS BY ID-NUMBER******** */
    public function findPeopleById($idnum){
        return DB::table('people')
                //->join('students','people.people_id','=','students.people_id')
                ->where('id_number',$idnum)->get()->count();
    }
    /* *******CHECK IF STUDENTS ALREADY ENROLLED IN THE CLASS******************** */
    public function checkStudentUploadIfEnrolled($studid, $cpoid){
        
        return DB::table('cpo_enroll_students')
                ->join('students','cpo_enroll_students.cpoes_stud_id','=','students.stud_id')
                ->join('people','students.people_id','=','people.people_id')
                ->where([
                    ['people.id_number','=',$studid],
                    ['cpo_enroll_students.cpo_id','=',$cpoid]
                ])
                ->get()->count();
    }
    /* *******Create Instructor Account One at a time********* */
    public function createInstructorAccount(Request $request){
        //dd($request);
        /* Check if Instructor already exist */
        $checkIns = DB::table('people')->where('id_number', $request->ins_idnumber)->count();
        if($checkIns > 0){
            Alert::error('WARNING', $request->lastname.', '.$request->firstname.' is already added.');
            return redirect()->back()->with('');
        }
        /* Add Instructor to People table */
        $peopleId = DB::table('people')->insertGetId([
            'id_number'     => $request->ins_idnumber,
            'last_name'     => Str::ucfirst(Str::lower($request->lastname)),
            'first_name'    => ucwords(Str::lower($request->firstname)),
            'middle_name'   => Str::ucfirst(Str::lower($request->middlename)),
            'ext_name'      => ucwords(Str::lower($request->ext_name)),
            'created_at'    => Carbon::now()
        ]);
        
        /* Create useraccount of Instructor college_id*/
        $insertUA = DB::table('user_accounts')->insert([
            'username'      => $request->ins_idnumber,
            'password'      => bcrypt($request->ins_idnumber),
            'user_type'     => 'Student',
            'ua_status'     => 1,
            'people_id'     => $peopleId,
            'created_at'    => Carbon::now()
        ]);
        /* Add to employees table */
        $addEmployee=DB::table('employees')->insert([
            'people_id'     => $peopleId,
            'dept_id'       => $request->college_id,
            'created_at'    => Carbon::now()
        ]);
        return back()->with('success', 'Instructor successfully created.');
    }
    /* **********Create Instructor via Uploading Excel File************ */
    public function postAddMultiInstructor(Request $request){
        //Get the file path
        $path = $request->file('add_instructor_list')->getRealPath();
        //create array of data
        $data = Excel::load($path)->get();
        //dd($data);
        $insExist=array();
        $collegeDontExist=array();
        if($data->count() > 0){
            /* Get Student ID from students table */
            /* program_code */
            foreach ($data as $key => $value) {
                //Check student if Exist
                if ($this->findPeopleById($value->id_number)>0) {
                    $insExist[] = $value->lastname.", ".$value->firstname;
                }
                if(count($this->findCollages($value->college_code)) == 0){
                    $collegeDontExist[] = $value->college_code;
                }
                 
            }
        
            /* Check if instructor do exist in the system */
            if(count($insExist) > 0){
               return redirect()->back()->with(['userexist'=>$insExist]);
            }
            if(count($collegeDontExist) > 0){
                return redirect()->back()->with(['deptdontexist'=>$collegeDontExist]);
            }
            /* Insert instructor in people table with insertGetId */
            foreach ($data as $key => $value) {
                $peopleId = DB::table('people')->insertGetId([
                    'id_number'     => $value->id_number,
                    'last_name'     => Str::ucfirst(Str::lower($value->lastname)),
                    'first_name'    => ucwords(Str::lower($value->firstname)),
                    'middle_name'   => Str::ucfirst(Str::lower($value->middlename)),
                    'ext_name'      => ucwords(Str::lower($value->extension_name)),
                    'created_at'    => Carbon::now()
                ]);
                 /* Add instructor  to user account table */
                $insertUA = DB::table('user_accounts')->insert([
                    'username'      => $value->id_number,
                    'password'      => bcrypt($value->id_number),
                    'user_type'     => 'Instructor',
                    'ua_status'     => 1,
                    'people_id'     => $peopleId,
                    'created_at'    => Carbon::now()
                ]);
                
                /* Add instructor to employees table */
                foreach ($this->findCollages($value->college_code) as $key1 => $value1) {
                    $addToStudents = DB::table('employees')->insertGetId([
                        'people_id'     => $peopleId,
                        'dept_id'       => $value1->dept_id,
                        'created_at'    => Carbon::now(),
                    ]);
                }
                
            }
            
            return back()->with('success', 'Students successfully created.');
        }else{
            Alert::error('WARNING', "File has no valid content, please check your file before uploading it.");
            return back()->withErrors(""); 
        }
    }
    /* ******CREATE DEAN ACCOUNT****** */
    public function createDeanAccount(Request $request){
        $checkDean = DB::table('people')->where('id_number', $request->dean_idnumber)->count();
        if($checkDean > 0){
            Alert::error('WARNING', $request->lastname.', '.$request->firstname.' is already added.');
            return redirect()->back()->with('');
        }
        /* Add Dean to People table */
        $peopleId = DB::table('people')->insertGetId([
            'id_number'     => $request->dean_idnumber,
            'last_name'     => Str::ucfirst(Str::lower($request->lastname)),
            'first_name'    => ucwords(Str::lower($request->firstname)),
            'middle_name'   => Str::ucfirst(Str::lower($request->middlename)),
            'ext_name'      => ucwords(Str::lower($request->ext_name)),
            'created_at'    => Carbon::now()
        ]);
        
        /* Create useraccount of Dean college_id*/
        $insertUA = DB::table('user_accounts')->insert([
            'username'      => $request->dean_idnumber.'D',
            'password'      => bcrypt($request->dean_idnumber.'D'),
            'user_type'     => 'Dean',
            'ua_status'     => 1,
            'people_id'     => $peopleId,
            'created_at'    => Carbon::now()
        ]);
        /* Add to employees table */
        $addEmployee=DB::table('employees')->insert([
            'people_id'     => $peopleId,
            'dept_id'       => $request->college_id,
            'created_at'    => Carbon::now()
        ]);
        return back()->with('success', 'Dean successfully created.');
    }
    /* *******CREATE ADMIN ACCOUNT******************* */
    public function createAdminAccount(Request $request){
        $checkAdmin= DB::table('people')->where('id_number', $request->admin_idnumber)->count();
        if($checkAdmin > 0){
            Alert::error('WARNING', $request->lastname.', '.$request->firstname.' is already added.');
            return redirect()->back()->with('');
        }
        /* Add Dean to People table */
        $peopleId = DB::table('people')->insertGetId([
            'id_number'     => $request->admin_idnumber,
            'last_name'     => Str::ucfirst(Str::lower($request->lastname)),
            'first_name'    => ucwords(Str::lower($request->firstname)),
            'middle_name'   => Str::ucfirst(Str::lower($request->middlename)),
            'ext_name'      => ucwords(Str::lower($request->ext_name)),
            'created_at'    => Carbon::now()
        ]);
        
        /* Create useraccount of Dean college_id*/
        $insertUA = DB::table('user_accounts')->insert([
            'username'      => $request->admin_idnumber.'A',
            'password'      => bcrypt($request->admin_idnumber.'A'),
            'user_type'     => 'Admin',
            'ua_status'     => 1,
            'people_id'     => $peopleId,
            'created_at'    => Carbon::now()
        ]);
        
        return back()->with('success', 'Dean successfully created.');
    }
    /* *******FIND COLLEGE************** */
    public function findCollages($dept_code){
        return DB::table('departments')->where('dept_code', $dept_code)->get();
    }
    /* *******FIND PROGRAM********** */
    public function findProgram($programcode){
        return DB::table('programs')->where('program_code', $programcode)->get();
    }
    /* ******Get all Users in the System Excep the one logged in***************** */
    public function getAllUsers($usertype){
        if($usertype=='Student'){
            return DB::table('user_accounts')
                ->join('people','user_accounts.people_id','=','people.people_id')
                ->join('students','people.people_id','=','students.people_id')
                ->join('students_programs','students.stud_id','=','students_programs.stud_id')
                ->join('programs', 'students_programs.program_id','=','programs.program_id')
                ->join('departments','programs.dept_id','=','departments.dept_id')
                ->where('user_accounts.user_type',$usertype)->paginate(20);
        }
        if($usertype=='Instructor'){
            return DB::table('user_accounts')
                ->join('people','user_accounts.people_id','=','people.people_id')
                ->join('employees','people.people_id','=','employees.people_id')
                ->join('departments','employees.dept_id','=','departments.dept_id')
                ->where('user_accounts.user_type',$usertype)->paginate(20);
        }
        if($usertype=='Dean'){
            return DB::table('user_accounts')
                ->join('people','user_accounts.people_id','=','people.people_id')
                ->join('employees','people.people_id','=','employees.people_id')
                ->join('departments','employees.dept_id','=','departments.dept_id')
                ->where('user_accounts.user_type',$usertype)->paginate(20);
        }
    }
    /* ********Reset User Credentials**************** */
    public function ajaxResetUserCredentials(Request $request){
        /* Reset User Credentials from user_accounts table */
        $resetUserCredentials = DB::table('user_accounts')->where('id', $request->uid)->update([
            'username'      => $request->idnum,
            'password'      => bcrypt($request->idnum),
            'updated_at'    => Carbon::now()
        ]);
        if($resetUserCredentials){
            return response()->json(['success'=>'Successfully Reset']);
        }
        return response()->json(['error'=>'Problems encountered during resetting the account']);
        //return response()->json($request->uid.$request->idnum);
    }
    /* *****Activate Deactivate User Account********* */
    public function activateDeactivateAccount(Request $request){
        /* Change user account status */
        $activateDeactivate=DB::table('user_accounts')->where('id',$request->uid)->update([
            'user_type'     => $request->status,
            'updated_at'    => Carbon::now()
        ]);
        if($activateDeactivate){
            return response()->json(['success','Successfully Changed Status']);
        }
        return response()->json(['error', 'Changing Status went wrong...']);
    }
    public function creatingAdminDean(){
        return view('admin.adding-user');
    }
}
