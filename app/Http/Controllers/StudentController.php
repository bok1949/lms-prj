<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Auth;
use App\UserAccount;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function __construct(){
        $this->middleware('student');
    }

    public function dashboard(){
        $useraccount = $this->getUserLoggedInCredentials();
        //dd(UserAccount::find(Auth::id()));
        return view('student.student-dashboard', compact('useraccount'));
    }
    /* Get User Logged-in Credentials */
    public function getUserLoggedInCredentials(){
        return DB::table('people')
            ->join('user_accounts', 'people.people_id','=','user_accounts.people_id')
            ->join('students', 'people.people_id','=','students.people_id')
            ->join('students_programs', 'students.stud_id','=','students_programs.stud_id')
            ->join('programs', 'students_programs.program_id','=','programs.program_id')
            ->join('departments', 'programs.dept_id','=','departments.dept_id')
            ->where('people.people_id', '=', UserAccount::find(Auth::id())->people_id)
            ->whereIn('user_accounts.username', [UserAccount::find(Auth::id())->username])
            ->get();
    }
    /* Change Password View */
    public function accountSettings(){
        $useraccount = $this->getUserLoggedInCredentials();
        return view('student.student-accountsettings', compact('useraccount'));
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
    /* View Students Courses Enrolled */
    public function viewMyCourse(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        foreach ($useraccount as $key => $value) {
            $studid = $value->stud_id;
        }
        $myclasses = $this->findEnrolledClass($studid);
        return view('student.student-view-my-course', compact('useraccount','myclasses'));
    }
    /* *****View My Class Information****** */
    public function viewMyClassInfo(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        foreach ($useraccount as $key => $value) {
            $studid = $value->stud_id;
        }

        $myclasses = $this->findEnrolledClass($studid);
        foreach ($myclasses  as $key => $mc) {
            $cpoid = $mc->cpo_id;
        }
        if(isset($request->vmcer)){
            
            if($request->vmcer=='eval'){
                $vmcer = $request->vmcer;
                $evalParts = DB::table('course_program_offers')
                ->join('cpo_evaluation_parts','course_program_offers.cpo_id','=','cpo_evaluation_parts.cpo_id')
                ->orderBy('cpo_evaluation_parts.created_at', 'desc')
                ->where('course_program_offers.cpo_id',$cpoid)->get();
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
               
                return view('student.stud-course-mgmt.my-enrolled-class-info', compact('useraccount','myclasses','myClassEval','vmcer'));
            }
            if($request->vmcer=='resources'){
                $getfiles = DB::table('course_materials')
                ->join('cpo_course_materials','course_materials.cm_id','=','cpo_course_materials.cm_id')
                ->orderBy('course_materials.created_at', 'desc')
                ->where('cpo_course_materials.cpo_id',$cpoid)->get();
                $vmcer=$request->vmcer;
                return view('student.stud-course-mgmt.my-enrolled-class-info', compact('useraccount','myclasses','vmcer','getfiles'));
            }
            return redirect()->route('vmcinfo');
        }

        return view('student.stud-course-mgmt.my-enrolled-class-info', compact('useraccount','myclasses'));
    }
    /* *****Download Resources**************** */
    public function dlResources(Request $request){
        //dd($request->file);
        return response()->download(public_path()."\\storage\\coursematerials\\".$request->file);
      
    }
    /* ***Select my Classes**** */
    public function findEnrolledClass($studId){
        return DB::table('students')
            ->join('cpo_enroll_students', 'students.stud_id','=','cpo_enroll_students.cpoes_stud_id')
            ->join('course_program_offers', 'cpo_enroll_students.cpo_id','=','course_program_offers.cpo_id')
            ->join('courses_programs', 'course_program_offers.cp_id','=','courses_programs.cp_id')
            ->join('courses', 'courses_programs.course_id','=','courses.course_id')
            ->orderBy('courses.descriptive_title', 'asc')
            ->where('students.stud_id', '=', $studId)
            ->get();
    }
    /* Show Evaluation Questions */
    public function takeMyEval(Request $request){
        $useraccount = $this->getUserLoggedInCredentials();
        //dd($request->take);
        foreach ($useraccount as $key => $value) {
            $studentId = $value->stud_id;
        }
        $getenrolledClass = $this->findEnrolledClass($studentId);
        foreach ($getenrolledClass as $key => $value) {
            $cpoesid=$value->cpoes_id;
        }
        $cpoepid = $request->take+0;
        //dd($cpoepid,$cpoesid);
        /* Check if Student is done taking evaluation */
        $checkIfDone = DB::table('cpoep_cpoes_results')
                        ->where([
                            ['cpoep_id', '=', $cpoepid],
                            ['cpoes_id', '=', $cpoesid]
                            ])->get();
        //dd($checkIfDone);
        if(count($checkIfDone) > 0){
            return redirect()->back()->with('errors', "Sorry, you're done taking this Test.");
        }
        
        $evaluationInfo = DB::table('cpo_evaluation_parts')
                ->join('course_program_offers','cpo_evaluation_parts.cpo_id','=','course_program_offers.cpo_id')    
                ->join('courses_programs','course_program_offers.cp_id','=','courses_programs.cp_id')
                ->join('courses','courses_programs.course_id','=','courses.course_id')
                ->where('cpoep_id',$cpoepid)->get();
        /* 
        dd($evaluationInfo); */
        foreach($evaluationInfo as $key => $value){
            $evaltype = $value->cpoep_type;
        }
        if($evaltype == 'exam'){
            $getPartsOfExam = DB::table('eval_instructions')->where('cpoep_id',$cpoepid)->get();
            //dd($this->getExamQuestions($request->take));
            if($this->getExamQuestions($cpoepid)){
                $getExamQuestions = $this->getExamQuestions($cpoepid);
                $partOneAr = array();
                $partOneMc = array();
                $partTwoAr = array();
                $partTwoMc = array();
                foreach ($getExamQuestions as $key => $value) {
                    if($value->eval_parts=='I'){
                        //dd($this->getQuestionByParts($value->einstruct_id)->question_type);
                        if($value->question_type=='ar'){
                            $partOneAr[] =  array(
                                'qb_id'             => $value->qb_id,
                                /* 'instruction_desc'  => $value->instruction_desc, */
                                'einstruct_id'      => $value->einstruct_id,
                                'eval_parts'        => $value->eval_parts,
                                'question_desc'     => $value->question_desc,
                                'question_type'     => $value->question_type,
                                'points'            => $value->points,
                            );
                        }
                        if($value->question_type=='mc'){
                            $partOneMc[] = array(
                                'qb_id'             => $value->qb_id,
                                /* 'instruction_desc'  => $value->instruction_desc, */
                                'einstruct_id'      => $value->einstruct_id,
                                'eval_parts'        => $value->eval_parts,
                                'question_desc'     => $value->question_desc,
                                'question_type'     => $value->question_type,
                                'points'            => $value->points,
                                'choices'           => array(
                                    'multiple_choices'      => DB::table('multiple_choices')->where('qb_id', $value->qb_id)->get()->shuffle(),
                                    'mc_count_ans'          => DB::table('multiple_choices')->where([['qb_id','=',$value->qb_id], ['mc_is_answer','=',1]])->get()->count(),
                                ),
                            );
                        }
                    }
                    if($value->eval_parts=='II'){
                        if($value->question_type=='ar'){
                            $partTwoAr[] =  array(
                                'qb_id'             => $value->qb_id,
                                'instruction_desc'  => $value->instruction_desc,
                                'einstruct_id'      => $value->einstruct_id,
                                'eval_parts'        => $value->eval_parts,
                                'question_desc'     => $value->question_desc,
                                'question_type'     => $value->question_type,
                                'points'            => $value->points,
                            );
                        }
                        if($value->question_type=='mc'){
                            $partTwoMc[] = array(
                                'qb_id'             => $value->qb_id,
                                'instruction_desc'  => $value->instruction_desc,
                                'einstruct_id'      => $value->einstruct_id,
                                'eval_parts'        => $value->eval_parts,
                                'question_desc'     => $value->question_desc,
                                'question_type'     => $value->question_type,
                                'points'            => $value->points,
                                'choices'           => array(
                                    'multiple_choices'      => DB::table('multiple_choices')->where('qb_id', $value->qb_id)->get()->shuffle(),
                                    'mc_count_ans'          => DB::table('multiple_choices')->where([['qb_id','=',$value->qb_id], ['mc_is_answer','=',1]])->get()->count(),
                                ),
                            );
                        }
                    }
                }
                /* dd($partTwoMc); */
                return view('student.stud-course-mgmt.show-eq-page',compact('useraccount', 'evaluationInfo','partOneAr','partOneMc','partTwoAr','partTwoMc','evaltype','getPartsOfExam','getenrolledClass'));
               
            }
            return redirect()->back();
        }
        /* $request->take == $cpoepid */
        if($evaltype == 'quiz'){
            $quizzes = array();
            if(count($this->getExamQuestions($cpoepid))){
                $getQuestions = $this->getExamQuestions($cpoepid);
                foreach ($getQuestions as $key => $value) {
                    if($value->question_type=='mc'){
                        $quizzes[] = array(
                            'qb_id'             => $value->qb_id,
                                'instruction_desc'  => $value->instruction_desc,
                                'einstruct_id'      => $value->einstruct_id,
                                'eval_parts'        => $value->eval_parts,
                                'question_desc'     => $value->question_desc,
                                'question_type'     => $value->question_type,
                                'points'            => $value->points,
                                'choices'           => array(
                                    'multiple_choices'      => DB::table('multiple_choices')->where('qb_id', $value->qb_id)->get()->shuffle(),
                                    'mc_count_ans'          => DB::table('multiple_choices')->where([['qb_id','=',$value->qb_id], ['mc_is_answer','=',1]])->get()->count(),
                                ),
                        );
                    }
                    if($value->question_type=='ar'){
                        $quizzes[] = array(
                                'qb_id'             => $value->qb_id,
                                'instruction_desc'  => $value->instruction_desc,
                                'einstruct_id'      => $value->einstruct_id,
                                'eval_parts'        => $value->eval_parts,
                                'question_desc'     => $value->question_desc,
                                'question_type'     => $value->question_type,
                                'points'            => $value->points,
                        );
                    }
                }
                return view('student.stud-course-mgmt.show-qq-page',compact('useraccount', 'evaluationInfo','quizzes','getenrolledClass'));
            }
            return redirect()->route('vmcinfo_er', ['vmcer'=>'eval']);//
        }
        return redirect()->route('vmcinfo_er', ['vmcer'=>'eval']);
    }
    /* *********Get Questions by Parts******************* */
    public function getQuestionByParts($einstruct_id){
        return DB::table('eval_instructions')
                ->join('cpoep_questions','eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                ->join('question_banks','cpoep_questions.qb_id','=','question_banks.qb_id')
                ->where('eval_instructions.einstruct_id',$einstruct_id)->get()->shuffle();
    }
    /* ********Get Responses******** */
    public function getResponsesMCAR($q_type,$einstruct_id){
        if($q_type == 'mc'){
            return DB::table('eval_instructions')
            ->join('cpoep_questions','eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
            ->join('question_banks','cpoep_questions.qb_id','=','question_banks.qb_id')
            ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
            ->where('eval_instructions.einstruct_id',$einstruct_id)->get()->shuffle();
        }elseif($q_type=='ar'){
            return DB::table('eval_instructions')
            ->join('cpoep_questions','eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
            ->join('question_banks','cpoep_questions.qb_id','=','question_banks.qb_id')
            ->join('alternate_responses','question_banks.qb_id','=','alternate_responses.qb_id')
            ->where('eval_instructions.einstruct_id',$einstruct_id)->get()->shuffle();
        }

    }
    /* *********Get Exam Questions******* */
    public function getExamQuestions($id){
        return DB::table('cpo_evaluation_parts')
                    ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id')
                    ->join('cpoep_questions','eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                    ->join('question_banks','cpoep_questions.qb_id','=','question_banks.qb_id') 
                    /* ->inRandomOrder() */
                    /* ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id') */   
                    ->where('cpo_evaluation_parts.cpoep_id', $id)->get()->shuffle();
    }
    /* *********Get Quiz Questions******* */
    public function getQuizQuestions($id){

    }
    /* Find enrolled class evaluations Distinct */
    //public function findMyClassEvaluationsDistinct($cpoid){
    //public function findMyClassEvaluations($cpoid){
        /* return DB::table('course_program_offers')
                ->join('cpo_evaluation_parts','course_program_offers.cpo_id','=','cpo_evaluation_parts.cpo_id')
                ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id')
                ->orderBy('cpo_evaluation_parts.created_at', 'desc')
                ->where('course_program_offers.cpo_id',$cpoid)->distinct()->get('cpo_evaluation_parts.cpoep_id'); */
       /*  return DB::table('course_program_offers')
                ->join('cpo_evaluation_parts','course_program_offers.cpo_id','=','cpo_evaluation_parts.cpo_id')
                ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id')
                //->join('cpoep_questions','eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                ->orderBy('cpo_evaluation_parts.created_at', 'desc')
                ->where('course_program_offers.cpo_id',$cpoid)->get(); */
    //}
   
    /* Count Questions Made  NOT in used*/
    /* public function countQuestions($ei_id){
        return DB::table('eval_instructions')
                ->join('cpoep_questions','eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')    
                ->where('eval_instructions.einstruct_id', $ei_id)->count();
    } */
    /* ************Check Exam Result*********** */
    public function postCheckExamResult(Request $request){
        $ear1_ans=array();
        $getAr1=array();
        $ear1_correct_ans_score=0;

        $emc1_ans_score=0;
        $findMultiAnsMc1 = array();
        $findAnsMC1=array();
        $getMultiAnsMc1=array();
        
        $ear2_ans=array();
        $getAr2=array();
        $ear2_correct_ans_score=0;

        $emc2_ans_score=0;
        $findMultiAnsMc2 = array();
        $findAnsMC2=array();
        $getMultiAnsMc2=array();

        $getTotalPointsAR1=0;
        $getTotalPointsAR2=0;
        $getTotalPointsMC1=0;
        $getTotalPointsMC2=0;
        $totalPoints=0;
        $input = \Request::all();
        /* Alternate Response Exam Part 1 */
        if(isset($request->ar1_qb_id)){
            for ($i=1; $i <= count($request->ar1_qb_id); $i++) { 
                $ear1_ans[]= $input['ar_ans'.$i];
            }
            foreach ($request->ar1_qb_id as $key => $value) {
                $getTotalPointsAR1 += DB::table("question_banks")   
                    ->where('qb_id', $value)->get()->sum('points');
                $getAr1[] = DB::table('question_banks')
                        ->join('alternate_responses','question_banks.qb_id','=','alternate_responses.qb_id')
                        ->where('question_banks.qb_id', $value)->get();
            }
            foreach ($getAr1 as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    if($value1->ar_is_answer==$ear1_ans[$key]){
                        $ear1_correct_ans_score+=$value1->points;
                    }
                }
            }
        }
        /* Multiple Choice Exam Part 1  */
        $i=1;
        if(isset($request->mc1_qb_id)){
            foreach ($request->mc1_qb_id as $key => $value) {
                $getTotalPointsMC1 += DB::table("question_banks")   
                        ->where('qb_id', $value)->get()->sum('points');

                    $findMultiAnsMc1[]=DB::table('question_banks')
                            ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                            ->where([
                                /* ['multiple_choices.mc_id','=', $value2], */
                                ['mc_is_answer','=',1],
                                ['question_banks.qb_id','=',$value],
                                ])->count();

                    if(is_array($input['exam_mcii'.$i])){
                        if($input['exam_mcii'.$i] > 1){
                            foreach ($input['exam_mcii'.$i] as $key1 => $value2) {
                               
                                $getMultiAnsMc1[]=DB::table('question_banks')
                                ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                                ->where([['multiple_choices.mc_id','=', $value2],['mc_is_answer','=',1]])->get();
                            }
                        }
                    }else{
                        $findAnsMC1[]=DB::table('question_banks')
                            ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                            ->where([['multiple_choices.mc_id','=', $input['exam_mcii'.($i)]],['mc_is_answer','=',1]])->get();
                    }
                    $i++;
            }
           
            //return response()->json([count($getMultiAnsMc1), count($findMultiAnsMc1)]);
            if(count($findMultiAnsMc1) == count($getMultiAnsMc1)){
                if(!in_array(0, $findMultiAnsMc1)){
                    foreach ($getMultiAnsMc1 as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $p=$value1->points;
                        }
                    }
                   $emc1_ans_score += $p;
                }
            }
            
            foreach ($findAnsMC1 as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $emc1_ans_score += $value1->points;
                }
            }
        }
        //return response()->json($emc1_ans_score);
        /* Alternate Response Exam Part 2 */
        if(isset($request->ar2_qb_id)){
            for ($i=1; $i <= count($request->ar2_qb_id); $i++) { 
                $ear2_ans[]= $input['ar_ans'.$i];
            }
            foreach ($request->ar2_qb_id as $key => $value) {
                $getTotalPointsAR2 += DB::table("question_banks")   
                    ->where('qb_id', $value)->get()->sum('points');
                $getAr2[] = DB::table('question_banks')
                        ->join('alternate_responses','question_banks.qb_id','=','alternate_responses.qb_id')
                        ->where('question_banks.qb_id', $value)->get();
            }
            foreach ($getAr2 as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    if($value1->ar_is_answer==$ear2_ans[$key]){
                        $ear2_correct_ans_score+=$value1->points;
                    }
                }
            }
        }
        //return response()->json([$emc1_ans_score,$ear2_correct_ans_score]);
        /* Multiple Choice Exam Part 2 */
        $j=1;
        if(isset($request->mc2_qb_id)){
            foreach ($request->mc2_qb_id as $key => $value) {
                $getTotalPointsMC2 += DB::table("question_banks")   
                        ->where('qb_id', $value)->get()->sum('points');
                $findMultiAnsMc2[]=DB::table('question_banks')
                        ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                        ->where([['question_banks.qb_id','=', $value],['mc_is_answer','=',1]])->count();
                if(is_array($input['exam_mcii'.$j])){
                    if($input['exam_mcii'.$j] > 1){
                        foreach ($input['exam_mcii'.$j] as $key1 => $value2) {
                            $getMultiAnsMc2[]=DB::table('question_banks')
                            ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                            ->where([['multiple_choices.mc_id','=', $value2],['mc_is_answer','=',1]])->get();
                        }
                    }
                }else{
                    $findAnsMC2[]=DB::table('question_banks')
                        ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                        ->where([['multiple_choices.mc_id','=', $input['exam_mcii'.($j)]],['mc_is_answer','=',1]])->get();
                }
                $j++;
            }
                        
            if(count($findMultiAnsMc2) == count($getMultiAnsMc2)){
                if(!in_array(0, $findMultiAnsMc2)){
                    foreach ($getMultiAnsMc2 as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $p=$value1->points;
                        }
                    }
                   $emc2_ans_score += $p;
                }
            }
            foreach ($findAnsMC2 as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $emc2_ans_score += $value1->points;
                }
            }
        }

        if($getTotalPointsAR1 > 0){
            if($getTotalPointsAR2>0)
                $totalPoints = $getTotalPointsAR1+$getTotalPointsAR2;
            if($getTotalPointsMC2>0)
                $totalPoints = $getTotalPointsAR1+$getTotalPointsMC2;
        }
        if($getTotalPointsMC1>0){
            if($getTotalPointsAR2>0)
                $totalPoints = $getTotalPointsMC1+$getTotalPointsAR2;
            if($getTotalPointsMC2>0)
                $totalPoints = $getTotalPointsMC1+$getTotalPointsMC2;
        }
        $total=0;
        if($ear1_correct_ans_score!=0){
            if($ear2_correct_ans_score!=0)
                $total = $ear1_correct_ans_score+$ear2_correct_ans_score;
            if($emc2_ans_score!=0)
                $total = $ear1_correct_ans_score+$emc2_ans_score;
        }
        if($emc1_ans_score!=0){
            if($ear2_correct_ans_score!=0)
                $total = $emc1_ans_score+$ear2_correct_ans_score;
            if($emc2_ans_score!=0)
                $total = $emc1_ans_score+$emc2_ans_score;
        }
        $result = DB::table('cpoep_cpoes_results')->insert([
            'cpoes_id'      => $request->cpoes_id,
            'cpoep_id'      => $request->cpoep_id,
            'eval_result'   => $total,
            'cpoep_type'    => 'exam',
            'total_points'  => $totalPoints,
            'created_at'    => Carbon::now()
        ]);
        return response()->json(['totalscore'=>$total, 'take'=>$request->cpoep_id,'cpoes_id'=>$request->cpoes_id,'getTotalPoints'=>$totalPoints]);
    }
    /* ************Check Quiz Result*********** */
    public function postCheckQuizResult(Request $request){
        $getQtype=array();
        $findMultiAnsMC=array();
        $multiAnsMC=array();
        $getMultiAnsMC=array();
        $singleAnsMC=array();
        $totalPointsMC=0;
        
        $getQuestionAR=array();
        $arUserAns=array();
        $totalPointsAR=0;

        $input = \Request::all();
        $mamc=1;
        $totalQuestionPoints=0;
        $qpoints=0;
        $countQAns=0;
        if(isset($request->quiz_qb_id)){
            //return $request->questiontype;
            foreach ($request->quiz_qb_id as $key => $qbid) {
               $getQtype[] = DB::table('question_banks')->where('qb_id',$qbid)->get();
              
            }

            foreach ($getQtype as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $totalQuestionPoints += DB::table("question_banks")   
                                ->where('qb_id', $value1->qb_id)->get()->sum('points');
                    if($value1->question_type=='mc'){
                        
                        if(isset(($input['mc_ans'.$mamc]))&&is_array($input['mc_ans'.$mamc])){
                            $findMultiAnsMC[]=DB::table('question_banks')
                            ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                            ->where([['question_banks.qb_id','=', $value1->qb_id],['mc_is_answer','=',1]])->get()->count();
                            foreach ($input['mc_ans'.$mamc] as $key2 => $value2) {
                                $multiAnsMC[]=DB::table('question_banks')
                                    ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                                    ->where([
                                        ['multiple_choices.mc_id','=', $value2],
                                        ['mc_is_answer','=',1],
                                        ['question_banks.qb_id','=', $value1->qb_id]
                                        ])->get()->count();
                                $getMultiAnsMC[]=DB::table('question_banks')
                                    ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                                    ->where([
                                        ['multiple_choices.mc_id','=', $value2],
                                        ['mc_is_answer','=',1],
                                        ['question_banks.qb_id','=', $value1->qb_id]
                                        ])->get();
                            }
                            //return count($input['mc_ans'.$mamc]);
                        }else
                        /* if(isset($input['mc_ans'.$mamc])) */{
                            $singleAnsMC[] = DB::table('question_banks')
                                    ->join('multiple_choices','question_banks.qb_id','=','multiple_choices.qb_id')
                                    ->where([
                                        ['multiple_choices.mc_id','=', $input['mc_ans'.$mamc]],
                                        ['mc_is_answer','=',1],
                                        ['question_banks.qb_id','=', $value1->qb_id]
                                        ])->get();
                        }
                    }
                    if($value1->question_type=='ar'){
                        //$test[] = $input['ar_ans'.$mamc];
                        $arUserAns[] = $input['ar_ans'.$mamc];
                        //$value1->qb_id
                        $getQuestionAR[] = DB::table('question_banks')
                                ->join('alternate_responses','question_banks.qb_id','=','alternate_responses.qb_id')
                                ->where('question_banks.qb_id', $value1->qb_id)->get(); 
                    }
                    
                }
                $mamc++;
            }
          
        }
        foreach ($getQuestionAR as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if($value1->ar_is_answer==$arUserAns[$key]){
                    $totalPointsAR+=$value1->points;
                }
            }
        }
        //return response()->json($totalPointsAR);
        
        //return response()->json($singleAnsMC);
        foreach ($singleAnsMC as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $totalPointsMC+=$value1->points;
            }
        }
        //return $totalPointsMC;
        foreach ($findMultiAnsMC as $key => $value) {
            if($value > 1)
                $countQAns=$value;
        }
        $p=0;
        if($countQAns==count($multiAnsMC)){
            if(!in_array(0, $multiAnsMC)){
                foreach ($getMultiAnsMC as $key => $value) {
                    foreach ($value as $key1 => $value1) {
                        $p=$value1->points;
                    }
                }
                $totalPointsMC += $p;
            }
        }

        $qpoints = $totalPointsMC + $totalPointsAR;
        //return response()->json([$qpoints,"/",$totalQuestionPoints]);
        $result = DB::table('cpoep_cpoes_results')->insert([
            'cpoes_id'      => $request->cpoes_id,
            'cpoep_id'      => $request->cpoep_id,
            'eval_result'   => $qpoints,
            'cpoep_type'    => 'quiz',
            'total_points'  => $totalQuestionPoints,
            'created_at'    => Carbon::now()
        ]);
        return response()->json(['totalscore'=>$qpoints, 'totalQuestionPoints'=>$totalQuestionPoints]);
    }
    
    /* Find enrolled class course materials */
    public function findMyEnrolledClassCourseMaterials($cpoid){
        return DB::table('course_program_offers')
                ->join('cpo_course_materials','course_program_offers.cpo_id','=','cpo_course_materials.cpo_id')
                ->join('course_materials','cpo_course_materials.cm_id','=','course_materials.cm_id')
                ->where('course_program_offers.cpo_id',$cpoid)->get();
    }
    /* View My Evaluation Result Class List */
    public function viewMyEvaluationResult(){
        $useraccount = $this->getUserLoggedInCredentials();
        foreach ($useraccount as $key => $value) {
            $studid = $value->stud_id;
        }
        $myclasses = $this->findEnrolledClass($studid);
        /* DB::table('students')
            ->join('cpo_enroll_students', 'students.stud_id','=','cpo_enroll_students.cpoes_stud_id')
            ->join('course_program_offers', 'cpo_enroll_students.cpo_id','=','course_program_offers.cpo_id')
            ->join('courses_programs', 'course_program_offers.cp_id','=','courses_programs.cp_id')
            ->join('courses', 'courses_programs.course_id','=','courses.course_id')
            ->orderBy('courses.descriptive_title', 'asc')
            ->where('students.stud_id', '=', $studId)
            ->get(); */
        $countQuizzesResult=$this->totalNumberQuizzes($studid);
        $countExamResult=$this->totalNumberExams($studid);
        //return view('student.student-view-my-course', compact('useraccount','myclasses'));
        return view('student.student-view-my-evaluation-result',compact('useraccount','myclasses','countQuizzesResult','countExamResult'));
    }
    /* Count Number of Quizzes */
    public function totalNumberQuizzes($studid){
        return DB::table('cpo_enroll_students')
            ->join('cpoep_cpoes_results','cpo_enroll_students.cpoes_id','=','cpoep_cpoes_results.cpoes_id')
            ->join('cpo_evaluation_parts','cpoep_cpoes_results.cpoep_id','=','cpo_evaluation_parts.cpoep_id')
            ->where([
                ['cpo_enroll_students.cpoes_stud_id','=',$studid],
                ['cpo_evaluation_parts.cpoep_type','=','quiz']
                ])->get()->count();
    }
    /* Count Number of Exams */
    public function totalNumberExams($studid){
        return DB::table('cpo_enroll_students')
            ->join('cpoep_cpoes_results','cpo_enroll_students.cpoes_id','=','cpoep_cpoes_results.cpoes_id')
            ->join('cpo_evaluation_parts','cpoep_cpoes_results.cpoep_id','=','cpo_evaluation_parts.cpoep_id')
            ->where([
                ['cpo_enroll_students.cpoes_stud_id','=',$studid],
                ['cpo_evaluation_parts.cpoep_type','=','exam']
                ])->get()->count();
    }
    /* View my Specific Class Evaluation Result */
    public function viewClassEvalResult(Request $request){
        //dd($request->classid);
        $useraccount = $this->getUserLoggedInCredentials();
        foreach ($useraccount as $key => $value) {
            $studid = $value->stud_id;
        }
        /* $getExamResult[] = DB::table('cpo_enroll_students')
            ->join('course_program_offers','cpo_enroll_students.cpo_id','=','course_program_offers.cpo_id')
            ->join('cpo_evaluation_parts','course_program_offers.cpo_id','=','cpo_evaluation_parts.cpo_id')
            ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id')
            ->join('cpoep_questions','eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
            ->join('question_banks','cpoep_questions.qb_id','=','question_banks.qb_id')
            ->select(DB::raw('SUM(question_banks.points)'))
            ->where([
                ['cpo_enroll_students.cpoes_stud_id','=',$studid],
                ['course_program_offers.cpo_id','=',$request->classid],
                ['cpo_evaluation_parts.cpoep_type','=','exam']
            ])->get(); */
           /*  $getcpoid = DB::table('course_program_offers')
                ->join('cpo_evaluation_parts','course_program_offers.cpo_id','=','cpo_evaluation_parts.cpo_id')
                ->join('eval_instructions','cpo_evaluation_parts.cpoep_id','=','eval_instructions.cpoep_id')
                ->join('cpoep_questions','eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                ->join('question_banks','cpoep_questions.qb_id','=','question_banks.qb_id')
                ->where([
                    ['course_program_offers.cpo_id',$request->classid],
                    ['cpo_evaluation_parts.cpoep_type','=','exam']
                    ])->get(); */
            $resultExam = DB::table('cpo_enroll_students')
                ->join('cpoep_cpoes_results','cpo_enroll_students.cpoes_id','=','cpoep_cpoes_results.cpoes_id')
                ->where([
                    ['cpo_enroll_students.cpoes_stud_id',$studid],
                    ['cpoep_cpoes_results.cpoep_type','=','exam']
                    ])->get();
            $resultQuiz = DB::table('cpo_enroll_students')
                ->join('cpoep_cpoes_results','cpo_enroll_students.cpoes_id','=','cpoep_cpoes_results.cpoes_id')
                ->where([
                    ['cpo_enroll_students.cpoes_stud_id',$studid],
                    ['cpoep_cpoes_results.cpoep_type','=','quiz']
                    ])->get();
            //dd($resultExam);
            
        $myclasses = $this->findEnrolledClass($studid);
        return view('student.stud-course-mgmt.view-class-eval-result',compact('useraccount','myclasses','resultExam','resultQuiz'));
    }
    public function editAccountSettings(){
        return view('student.edit-account-settings');
    }
}
