<?php
use RealRashid\SweetAlert\Facades\Alert;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */
Route::get('/', 'LoginPage@home')->name('home');
Route::get('/index', 'LoginPage@home')->name('index');
Route::get('/logout', 'LoginPage@logout')->name('logout');
Route::post('/validatelogin', 'LoginPage@postLogin')->name('login');
//Auth::routes();
/* *****Instructor ROUTES**** *///['prefix'=>'instructor', 'middleware'=>'instructor'],
//Route::group(['prefix'=>'instructor', 'middleware'=>['instructor']],function(){
Route::middleware(['instructor'])->prefix('instructor')->group(function(){

    Route::get('/accountsettings', 'InstructorController@accountSettings')->name('Instructoraccountsettings');
    Route::post('/submitchangepassword', 'InstructorController@submitChangePassword')->name('submitChangePasswordIns');

    Route::get('/', 'InstructorController@dashboard')->name('instructordashboard');
    Route::get('/dashboard', 'InstructorController@dashboard')->name('instructordashboard');
    Route::get('/cm', 'InstructorController@classMgmt')->name('cm');
    Route::get('/cm/createclass/{cpid?}', 'InstructorController@createClasses')->name('cmCreateClass');

    Route::get('/cm/editmyclass/{cpoid_edit?}', 'InstructorController@editMyClass')->name('editmyclass');
    Route::post('/cm/editmyclass/posteditmyclass', 'InstructorController@postEditMyClass')->name('posteditmyclass');
    
    Route::post('/cm/submitmycreatedclass', 'InstructorController@postCreateClass')->name('submitmycreatedclass');
    Route::post('/cm/removingclass', 'InstructorController@removeMyClass')->name('removingclassajax');
    /* Search Course */
    Route::post('/cm/searchingCourse', 'InstructorController@serachCourseAjax')->name('serchcourseajax');
    
    Route::get('/cm/enrollstudents/{cpoid_enroll?}', 'InstructorController@enrollStudents')->name('enrollStudents');
    Route::post('/cm/findstudent', 'InstructorController@findStudentEnrollAjax')->name('findStudentEnrollAjax');
    Route::post('/cm/enrollStudentsInClass', 'InstructorController@enrollStudentInClass')->name('enrollstudentsAjax');
    Route::post('/cm/createstudentaccount', 'InstructorController@createStudentAccount')->name('csa');

    Route::post('/cm/uploadstudentlistinclass', 'InstructorController@uploadStudentListInClass')->name('uploadStudentListInClass');

    Route::get('cm/classevalcreate/{cpoid_ceic?}','InstructorController@classEvaluationCreate')->name('createEvalInClass');
    Route::get('cm/showmyclass/{cpoid_ceic?}','InstructorController@showMyClass')->name('viewMyClass');
    Route::get('cm/showmyclass/{cpoid_ceic?}/{viewclasslist?}','InstructorController@showMyClass')->name('viewMyClassList');
    //Route::get('cm/showmyclass/{cpoid_ceic?}/{viewclasslistresult?}','InstructorController@showMyClass')->name('viewMyClassListResult');
    Route::get('cm/classevalcreate/{cpoid_ceic?}/{evaltype?}','InstructorController@classEvaluationCreateType')->name('chooseTypeEvalInClass');
    
    Route::post('cm/removestudentinaclass/','InstructorController@ajaxPostRemoveStudClass')->name('removeStudentInClass');
    Route::post('cm/activateDeactivate/','InstructorController@postActivateDeactivareEval')->name('activateDeactivateEval');

    Route::post('cm/classevalcreate/submitExamDetails','InstructorController@postClassEvaluationCreateType')->name('submitExamDetails');/* submitExamDetails */
    Route::post('cm/classevalcreate/submitQuizDetails','InstructorController@postClassEvaluationCreateType')->name('submitQuizDetails');

    Route::post('cm/classevalcreate/submitQuizQuestions','InstructorController@postClassCreateQuizQuestion')->name('submitQuizQuestions');
    Route::post('cm/classevalcreate/submitExamQuestions','InstructorController@postClassCreateExamQuestion')->name('submitExamQuestions');
    /* Load Questions in QUIZ from Question Banks */
    Route::post('cm/classevalcreate/loadQuizQuestions','InstructorController@loadQuizQuestionFromQB')->name('loadQuizQuestionFromQB');
    
    /* Route::post('cm/classevalcreate/postcreate','InstructorController@postCreateClassEvaluation')->name('cpostcreateclasseval'); */
    Route::get('/vmc/{file?}', 'InstructorController@downLoadCourseMaterials')->name('downLoadCourseMaterials');
    
    Route::get('cm/uploadcm/{cpoid?}', 'InstructorController@pageToUploadCM')->name('uploadCM');
    Route::post('cm/postCourseMaterials', 'InstructorController@postCourseMaterials')->name('postCourseMaterials');
    Route::post('cm/postRemoveCM/removeCourseMaterials', 'InstructorController@removeCourseMaterials')->name('removeCourseMaterials');

    
    /* Not yet working */

    /* Route::get('/viewcourse', 'InstructorController@viewSpecificCourse')->name('viewcourse');
    Route::get('/smgmt', 'InstructorController@studentManagement')->name('studmgmt');
    Route::get('/smgmt/ces', 'InstructorController@studentListPerCourse')->name('courseEnrolledStudent');
    Route::get('/smgmt/ces/evalresult', 'InstructorController@viewEvaluationResult')->name('viewEvaluationResult');

    Route::get('/vcm', 'InstructorController@viewCourseMaterials')->name('vcm');
    Route::get('/verasr/{val?}', 'InstructorController@viewEvaluationResultAllStudResult')->name('veras-result');
    Route::get('/veras', 'InstructorController@viewEvaluationResultAllStud')->name('veras');
    Route::get('/ce/{ceval?}', 'InstructorController@createEvaluationType')->name('cet');
    Route::get('/ce', 'InstructorController@createEvaluation')->name('ce');

    Route::get('/evalmgmt', 'InstructorController@createEvaluationManagement')->name('evalmgmt');
    Route::get('/cevc', 'InstructorController@createEvaluationViewCourse')->name('cevc'); */

});


/* *********System Administrator Routes********** */
Route::middleware(['admin'])->prefix('admins')->group(function(){
    Route::get('/', 'SystemAdministratorController@dashboard')->name('sadashboard');
    /* College */
    Route::get('/collegemgmt', 'SystemAdministratorController@collegeManagement')->name('admincm');
    Route::get('/coursemgmt/{cm?}', 'SystemAdministratorController@collegeManagement')->name('viewCollegeList');

    Route::post('coursemgmt/postcreateCollege','SystemAdministratorController@postCreateCollege')->name('postCreateCollege');

    Route::get('/collegemgmt/{collegeid}', 'SystemAdministratorController@editingCollegeInfo')->name('editcollege');
    Route::post('/collegemgmt/saveedit', 'SystemAdministratorController@saveEditingCollegeInfo')->name('saveeditcollege');
    Route::get('/collegemgmt/addcolleges', 'SystemAdministratorController@createColleges')->name('addcollege');
    /* Faculty Loadings */
    Route::get('/collegemgmt/createfacultyloading/{collegeid}', 'SystemAdministratorController@createFacultyLoading')->name('createFacultyLoading');
    Route::post('/collegemgmt/uploadfacultyloadings/', 'SystemAdministratorController@uploadFacultyLoading')->name('ufloadings');
    Route::post('/collegemgmt/createfacultyloadings/', 'SystemAdministratorController@postCreateFacultyLoadings')->name('postCreateFacultyLoadings');
    
    /* Programs */
    Route::get('/coursemgmt/view/{collegeid?}', 'SystemAdministratorController@programListManagement')->name('viewCollegeInfo');
    Route::get('/coursemgmt/view/{collegeid?}/{prog_course?}/{progid?}', 'SystemAdministratorController@programListManagement')->name('viewCollegeInfoPC');
    
    Route::post('/coursemgmt/submitcreateprogram/', 'SystemAdministratorController@createPrograms')->name('createprograms');
    /* Courses */
    Route::get('coursemgmt/view-all-courses/show', 'SystemAdministratorController@viewAllCourses')->name('viewAllCourses');
    Route::get('coursemgmt/add-courses/add', 'SystemAdministratorController@viewAddCourses')->name('viewAddCourses');
    Route::post('/coursemgmt/submitcreatecourses/', 'SystemAdministratorController@createCourse')->name('createcourses');
    Route::post('/coursemgmt/submitcreatecourse/fileupload', 'SystemAdministratorController@uploadCourses')->name('uploadCourses');
    Route::post('/coursemgmt/create-all-course/fileupload-all', 'SystemAdministratorController@uploadCoursesAll')->name('uploadCoursesAll');
    
    Route::post('/coursemgmt/update-course-info/ajax', 'SystemAdministratorController@updateCourseInfo')->name('updateCourseInfo');

    Route::get('/usersmgmt', 'SystemAdministratorController@usersManagement')->name('adminUserMgmt');
    Route::get('/usersmgmt/{usertype?}', 'SystemAdministratorController@usersManagement')->name('adminusertype');
    Route::get('/usersmgmt/adduser/{adduser?}', 'SystemAdministratorController@usersManagement')->name('addusers');
    
    Route::post('/usersmgmt/add-student', 'SystemAdministratorController@postAddStudent')->name('postAddStudent');
    Route::post('/usersmgmt/add-multiple-students', 'SystemAdministratorController@postAddMultiStudent')->name('postAddMultiStudent');
    Route::post('/usersmgmt/add-instructor', 'SystemAdministratorController@createInstructorAccount')->name('createInstructorAccount');
    Route::post('/usersmgmt/add-multiple-instructor', 'SystemAdministratorController@postAddMultiInstructor')->name('postAddMultiInstructor');
    Route::post('/usersmgmt/add-dean', 'SystemAdministratorController@createDeanAccount')->name('createDeanAccount');
    Route::post('/usersmgmt/add-admin', 'SystemAdministratorController@createAdminAccount')->name('createAdminAccount');

    Route::post('/usersmgmt/reset-account-credentials', 'SystemAdministratorController@ajaxResetUserCredentials')->name('resetUserCredentials');
    Route::post('/usersmgmt/activate-deactivate-credentials', 'SystemAdministratorController@activateDeactivateAccount')->name('adUserAccount');

    Route::get('/adduser', 'SystemAdministratorController@creatingAdminDean')->name('createuser');

    Route::get('/accountsettings', 'SystemAdministratorController@accountSettings')->name('Adminaccountsettings');
    Route::post('/submitchangepassword', 'SystemAdministratorController@submitChangePassword')->name('submitChangePasswordAdmin');
});


/* *********Dean Routes********** */
Route::middleware(['dean'])->prefix('deans')->group(function(){
    Route::get('/', 'DeanController@dashboard')->name('deandashboard');
    Route::get('/dashboard', 'DeanController@dashboard')->name('deandashboard');
       
    Route::get('/deanlevelusermgmt/{deanusers?}/{id?}', 'DeanController@userDeanLevelManagement')->name('deanlevelusermgmt');
    Route::post('/deanlevelusermgmt/createinstructor', 'DeanController@postCreateinstructor')->name('createinstructor');
    Route::post('/sendUpdateInstructor', 'DeanController@postUpdateInstructor')->name('sendUpdateInstructor');

    Route::post('/resetaccount', 'DeanController@resetAccount')->name('resetAccount');
    Route::post('/deactivateAccount', 'DeanController@enableDisableUserAccount')->name('deactivateAccount');
    /* Student Management */
    Route::post('/deanlevelusermgmt/uploadstudentlist', 'DeanController@uploadStudentList')->name('uploadstudentlist');
    Route::post('/deanlevelusermgmt', 'DeanController@createPostStudent')->name('createstudent');
    Route::post('/deanlevelusermgmt/sendUpdateStudent', 'DeanController@postUpdateStudent')->name('sendUpdateStudent');

    /* Program Management */
    Route::get('/programmgmt/{pmgmt?}/{programid?}', 'DeanController@programManagement')->name('programmgmt');
    Route::post('/programmgmt/submitprogram', 'DeanController@postProgram')->name('submitprogram');
    Route::post('/programmgmt/submitprogramedit', 'DeanController@postEditProgram')->name('submitprogramedit');

    
    Route::get('/coursemgmt/{cmgmt?}/{courseid?}', 'DeanController@courseManagement')->name('coursemgmt');
    Route::post('/coursemgmt/submitcreatecourse', 'DeanController@postCourse')->name('submitcreatecourse');
    Route::post('/coursemgmt/submitcreatecourse/fileupload', 'DeanController@uploadCourses')->name('uploadCourses');   
    Route::post('/coursemgmt/submitcourseedit', 'DeanController@postEditCourses')->name('submitcourseedit');

    Route::get('/accountsettings', 'DeanController@accountSettings')->name('Deanaccountsettings');
    Route::post('/submitchangepassword', 'DeanController@submitChangePassword')->name('submitChangePassword');
});

/* *********Students Routes********** */
Route::middleware(['student'])->prefix('student')->group(function(){
    Route::get('/', 'StudentController@dashboard')->name('studentdashboard');
    Route::get('/vmc', 'StudentController@viewMyCourse')->name('vmc');
    Route::get('/vmc/vmcinfo', 'StudentController@viewMyClassInfo')->name('vmcinfo');
    Route::get('/vmc/vmcinfo/{vmcer?}', 'StudentController@viewMyClassInfo')->name('vmcinfo_er');
    Route::get('/vmc/vmcinfo/take-eval/{take?}', 'StudentController@takeMyEval')->name('take_eval');
    Route::post('/vmc/submit-eval', 'StudentController@postCheckExamResult')->name('sbumitEvaluationResult');
    Route::post('/vmc/submit-eval-quiz', 'StudentController@postCheckQuizResult')->name('submitEvalQuizResult');

    Route::get('/vmc/{file?}', 'StudentController@dlResources')->name('dlResources');

    Route::get('/vmer', 'StudentController@viewMyEvaluationResult')->name('vmer');
    Route::get('/vmer/{classid?}','StudentController@viewClassEvalResult')->name('viewClassEvalResult');
    Route::get('/studentsettings', 'StudentController@editAccountSettings')->name('studentsettings');

    Route::get('/accountsettings', 'StudentController@accountSettings')->name('Studentaccountsettings');
    Route::post('/submitchangepassword', 'StudentController@submitChangePassword')->name('submitChangePasswordStud');
});

