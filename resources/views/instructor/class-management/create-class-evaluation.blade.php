@extends('layouts.app')

@section('title')
    Instructor
@endsection

@section('header')
    @include('../layouts/header')
@endsection
@if (isset($useraccount))
    @foreach ($useraccount as $ua)
        @php
            $deptCode=$ua->dept_code;
        @endphp
    @endforeach
@endif
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 bg-faded" id="">
            <div class="row">
                <div class="col-md-3 position-fixed mt-2" id="sticky-sidebar">
                    <h3 class="text-center border-bottom border-secondary p-2"><a href="{{route('instructordashboard')}}" class="text-reset text-decoration-none"><i class="fa fa-tachometer" aria-hidden="true"></i> {{$deptCode}} Instructor</a></h3>
                    <ul class="nav flex-column nav-pills text-left p-4">
                        <li class="nav-item mb-4">
                            <a class="nav-link border border-primary" href="{{route('cm')}}"> <i class="fa fa-file mr-2"></i> Class Management</a>
                        </li>
                    {{--  <li class="nav-item mb-4">
                            <a class="nav-link active" href="{{route('studmgmt')}}"> <i class="fa fa-users mr-2" aria-hidden="true"></i> Student Management</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('evalmgmt')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> Evaluation Management</a>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 bg-white">
            <div class="container-fluid mt-2">
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <h4 class="border-bottom">Course Management</h4>
                    </div>
                </div>
                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('instructordashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('cm')}}"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes</a></li>
                                    <li class="breadcrumb-item active"><i class="fa fa-file-word-o" aria-hidden="true"></i> Create Class Evaluation</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>{{-- end of bread crumb --}}
                @foreach ($courseProgramOffers as $cpo)
                    @php
                        $cpoid          = $cpo->cpo_id;
                        $coursecode     = $cpo->course_code;
                        $coursetitle    = $cpo->descriptive_title;
                        $sched          = $cpo->schedule;
                        $ay             = $cpo->ay;
                        $term           = $cpo->term;
                        $programcode    = $cpo->program_code;
                    @endphp
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-center bg-success text-white p-2">Creating Class Evaluation in <strong>{{$coursecode}}</strong> <span class="small font-italic">({{$coursetitle}}).</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="ml-4"><span class="text-dark"><i class="fa fa-calendar" aria-hidden="true"></i> <strong>Class Schedule</strong></span> <i class="fa fa-caret-right" aria-hidden="true"></i> <span class="text-dark">{{$sched}}</span></p>
                    </div>
                </div>
                <div class="row">{{-- Evaluation Type Nav Bar --}}
                    <div class="col-md-12">{{-- class="course-content" --}}
                        {{-- {{count($evalcreated)}} --}}
                       {{--  @if (isset($evalcreated))
                            @if (count($evalcreated))
                                <div class="dropdown">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                                        Quiz
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{route('chooseTypeEvalInClass',['cpoid_ceic'=>$cpoid, 'evaltype'=>'quiz'])}}">Create New Quiz</a>
                                        @foreach ($evalcreated as $ec)
                                            <a class="dropdown-item" href="#">Quiz Created at {{Carbon\Carbon::parse($ec->created_at)->diffForHumans()}}</a>{{--format('d:m:Y') diffForHumans --}}
                                       {{--  @endforeach
                                    </div>
                                </div>  
                            @else --}}
                                <a class="btn btn-secondary ml-4" href="{{route('chooseTypeEvalInClass',['cpoid_ceic'=>$cpoid, 'evaltype'=>'quiz'])}}">Quiz</a>
                                <a class="btn btn-secondary" href="{{route('chooseTypeEvalInClass',['cpoid_ceic'=>$cpoid, 'evaltype'=>'exam'])}}">Exam</a>
                            {{-- @endif
                        @endif --}}
                    </div>
                </div>{{-- End of Evaluation Type Nav Bar --}}
                <div class="row mt-2 mb-4">
                    @if(isset($quiz))
                        <div class="col-md-12 mt-1 mb-1">
                            <h4 class="p-2 text-center bg-dark text-white">Create a Quiz</h4>
                        </div>
                        <div class="col-md-12">
                            @include('instructor.class-management.class-management-includes.create-quiz-form')
                        </div>
                    @elseif (isset($exam))
                        <div class="col-md-12">
                            @include('instructor.class-management.class-management-includes.create-exam-form')
                        </div>
                    @endif
                </div>
            </div>
        </div>{{-- container-fluid --}}
    </div>
</div>


@endsection

@section('footer')
    @include('../layouts/footer')
@endsection


@section('javascripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function(){
/* *************************CREATING EXAM*********************************** */  
             let sendExamDetails = (value) => { 
                 $.ajax({
                    url:"{{route('submitExamDetails')}}",
                    type:"POST",
                    data: value
                }).done(function(response){
                    //console.log(response);
                    $("#examDetails").addClass('hide');
                    $("#examForm").removeClass('hide');

                    /* console.log(response.ei_id + " | "+response.idesc+" | "+response.evalparts);
                    console.log("Length "+ response.evalparts.length); */
                    if(response.evalparts.length == 1){
                        $('.p2').addClass('hide');
                    }
                    if(response.success_res){
                        $('input[name="einstruct_id"]').val(response.ei_id);
                        swal.fire('Done', response.success_res, 'success');
                    }
                    if($(".textHead").attr('id')==response.evalparts){
                        //console.log("eid - "+response.ei_id);
                        $(".pdesc").text(response.idesc);
                    }
                    //console.log($(".textHead").attr('id'));
                });
            }
            let sendExamMC = (value) => {
                $.ajax({
                    url: "{{route('submitExamQuestions')}}",
                    type: "post",
                    data: value
                }).done(function(response){
                    if(response.err){
                        console.log(response.err);
                        swal.fire('Warning!', response.err, 'error');
                    }
                    if(response.err_answer){
                        $('input[type="checkbox"]').addClass('is-invalid');
                        swal.fire('Warning!', response.err_answer, 'error');
                    }
                    if(response.done){
                        /* if(response.ar){
                            
                        } */
                        $('textarea').val("");
                        $('input[type="text"]').val("");
                        $('input[type="number"]').val("");
                        $('input[type="checkbox"]').prop("checked", false);;
                        swal.fire('Done!', response.done, 'success');
                        if(response.examis == 'mcei1'){
                            $("#numitems_mc").text(response.getNumItems);
                            $("#totalpoints_mc").text(response.sum);
                            $("#eqctr").text((response.getNumItems+1)+" of "+response.getNumItems);
                        }
                        if(response.examis == 'mcei2'){
                            $("#numitems_mc1").text(response.getNumItems);
                            $("#totalpoints_mc1").text(response.sum);
                            $("#eqctr1").text((response.getNumItems+1)+" of "+response.getNumItems);
                            /* eqctr1 */
                        }
                        if(response.examis == 'arei1'){
                            $('textarea[name="question_desc_ar"]').val("");
                            $('input[name="ans"]').prop("checked", false);

                            $("#numitems_ar").text(response.getNumItems);
                            $("#totalpoints_ar").text(response.sum);
                            $("#eqctr_ar").text((response.getNumItems+1)+" of "+response.getNumItems);
                        }
                        if(response.examis == 'arei2'){
                            $('textarea[name="question_desc_ar1"]').val("");
                            $('input[name="ans1"]').prop("checked", false);

                            $("#numitems_ar1").text(response.getNumItems);
                            $("#totalpoints_ar1").text(response.sum);
                            $("#eqctr_ar1").text((response.getNumItems+1)+" of "+response.getNumItems);
                        }
                        if(response.ins_desc){
                            $('.pdesc1').text(response.ins_desc);
                        }
                        if(response.ins_desc1){
                            $('.pdesc2').text(response.ins_desc);
                        }
                        /* 'ins_desc1'=>$instruc_desc */
                    }
                    //console.log(response);
                    //console.log(response.done);
                });
            }
            /* ************Creating Multiple Choice Exam*********** */
            $('#saveExamMC').on('click',function(e){
                e.preventDefault();

                $('input[name="response[]"').each(function(){
                    if($(this).val() == ""){
                        $(this).addClass('is-invalid');
                    }
                    $(this).keyup(function(){
                        $(this).removeClass('is-invalid');
                    });
                });

                $('input[name="points_emc"], textarea[name="question_desc_mc"]').on('keyup',function(){
                    $(this).removeClass('is-invalid');
                });
                if($('input[name="points_emc"]').val() == ""){
                    $('input[name="points_emc"]').addClass('is-invalid');
                    
                }
                if($('textarea[name="question_desc_mc"]').val()==""){
                    $('textarea[name="question_desc_mc"]').addClass('is-invalid');
                }
                /* Send to create */
                //console.log($('.saveExamQuestionForm').serializeArray());
                sendExamMC($('.saveExamQuestionForm').serialize());
            });
            /* validate multiple choice exam part 2 */
            $('#saveExamMC1').on('click',function(e){
                e.preventDefault();
                $('input[name="response1[]"]').each(function(){
                    if($(this).val() == ""){
                        $(this).addClass('is-invalid');
                    }
                    $(this).keyup(function(){
                        $(this).removeClass('is-invalid');
                    });
                });//points1
                $('input[name="points_emc1"], textarea[name="question_desc_mc"]').on('keyup',function(){
                    $(this).removeClass('is-invalid');
                });
                if($('input[name="points_emc1"]').val() == ""){
                    $('input[name="points_emc1"]').addClass('is-invalid');
                    
                }
                if($('textarea[name="question_desc_mc1"]').val()==""){
                    $('textarea[name="question_desc_mc1"]').addClass('is-invalid');
                }
                /* Send to create */
                //console.log($('.saveExamQuestionForm1').serializeArray());
                sendExamMC($('.saveExamQuestionForm1').serialize());
            });
/* *************Saving Alternate Exam**************** */
            $('#saveExamAR').on('click', function(e){
                e.preventDefault();
                 //console.log($('.alternateResponse').serialize());
                if($('textarea[name="question_desc_ar"]').val()==""){
                    swal.fire('Warning', 'You Must create question before saving.', 'error');
                }else if($('input[name="ans"]:checked').length==0){
                    swal.fire('Warning', 'Select a Correct Answer', 'error');
                }else if($('input[name="pointsar"]').val()==""){
                    swal.fire('Warning', 'You must indicate point/s to this item.', 'error');
                }else{
                    /* console.log('sending..');
                    console.log($('.alternateResponse').serialize()); */
                    sendExamMC($('.alternateResponse').serialize());
                }
            });
            /* saveExamAR2 */
            $('#saveExamAR2').on('click', function(e){
                e.preventDefault();
                 //console.log($('.alternateResponse').serialize());
                if($('textarea[name="question_desc_ar1"]').val()==""){
                    swal.fire('Warning', 'You Must create question before saving.', 'error');
                }else if($('input[name="ans1"]:checked').length==0){
                    swal.fire('Warning', 'Select a Correct Answer', 'error');
                }else if($('input[name="pointsar1"]').val()==""){
                    swal.fire('Warning', 'You must indicate point/s to this item.', 'error');
                }else{
                    /* console.log('sending..');
                    console.log($('.alternateResponse').serialize()); */
                    sendExamMC($('.alternateResponse2').serialize());
                }
            });
/* *****************Saving Examination Information******************** */
            $("#createExamInfo").on('click',function(a){
                a.preventDefault();
                if($('input[name="exam_part1"]').prop("checked") == false && $('input[name="exam_part2"]').prop("checked") == false){
                    swal.fire("Warning","You Must Select atleat 1 part","error");
                }else if($('input[name="exam_part1"]').prop("checked") == true && $('textarea[name="exam_desc1"]').val()==""){
                    swal.fire("Warning", "You must fillin the Exam Part I Description", "error");
                }else if($('input[name="exam_part2"]').prop("checked") == true && $('textarea[name="exam_desc2"]').val()==""){
                    swal.fire("Warning", "You must fill-in the Exam Part II Description", "error");
                }else if($('input[name="exam_part2"]').prop("checked") == true && $('input[name="exam_part1"]').prop("checked") == false){
                    swal.fire("Warning", "You cannot create Exam Part II over Part I", "error");
                }else if($('textarea[name="exam_desc1"]').val()=="" && $('textarea[name="exam_desc2"]').val()==""){
                    $("#warningModal").modal('show');
                }else{
                    /* console.log('sending exam...');
                    console.log($(".createExamDetails").serialize()); */
                    sendExamDetails($(".createExamDetails").serialize());
                }
                $('.continue-exam').on('click',function(b){
                    b.preventDefault();
                    //console.log('sending exam sss');
                    sendExamDetails($(".createExamDetails").serialize());
                });
                /* submitExamDetails route */
                //sendExamDetails($(".createExamDetails").serialize());
            });
            $(".exam-type").on('click',function(e){
                e.preventDefault();
                //console.log($(this).attr('id'));
                if($(this).attr('id')=='mc'){
                    $("#ar").addClass('hide');
                    $('.emc').removeClass('hide');
                    $('.ear').addClass('hide');
                    $("#mc").addClass('active');
                }else if($(this).attr('id')=='ar'){
                    $("#mc").addClass('hide');
                    $('.emc').addClass('hide')
                    $('.ear').removeClass('hide');
                    $("#er").addClass('active');
                }
            });
            $(".exam-type-ii").on('click',function(e){
                e.preventDefault();
                //console.log($(this).attr('id'));
                if($(this).attr('id')=='mcii'){
                    $("#arii").addClass('hide');
                    $('.emc-ii').removeClass('hide');
                    $('.ear-ii').addClass('hide');
                   
                }else if($(this).attr('id')=='arii'){
                    $("#mcii").addClass('hide');
                    $('.emc-ii').addClass('hide')
                    $('.ear-ii').removeClass('hide');
                }
            });
            $("#p2").on('click',function(e){
                e.preventDefault();
                $("#p1").removeClass('disabled');
                $(this).addClass('disabled');
                $(".epartHead").text("Part II");
                $(".part_i").addClass('hide');
                $(".part_ii").removeClass('hide');
            });
            $("#p1").on('click',function(e){
                e.preventDefault();
                $("#p2").removeClass('disabled');
                $(this).addClass('disabled');
                $(".epartHead").text("Part I");
                $(".part_ii").addClass('hide');
                $(".part_i").removeClass('hide');
            });
/* *******Adding Responses to Exam in Multiple Choice One********** */
            var max_fields = 3; //maximum element
            var wrapper1 = $(".responsesEMC");//element wrapper
            var ctr = 1; //initial element count
            var x = 2;

            $('#addResponseEMC').on('click',function(e){
                //alert("Addin responses in MC One..");
                e.preventDefault();
                if(ctr <= max_fields){
                    x++;
                    $(wrapper1).append(
                        '<div class="row addeddiv" id="'+ctr+'">'+
                            '<div class="form-group col-md-8">'+
                                '<input type="text" name="response[]" class="form-control" placeholder="Enter Response...">'+
                            '</div>'+
                            '<div class="form-group col-md-3 mt-2">'+
                                '<input type="checkbox" name="isanswer[]" value="'+x+'" id="'+x+'" >'+
                                ' <a href="#" class="remove_field" ><i class="fa fa-times"></a>'+
                            '</div>'+
                        '</div>'
                    );
                    ctr++;
                    
                }else{
                    swal.fire('Warning!', 'You cannot add morethan three (3).', 'error');
                }                
            });
            $(wrapper1).on('click','.remove_field', function(e){
                e.preventDefault();
                $(this).parents('.addeddiv').remove();
                ctr--;
                x--;
            });
 /* *******Adding Responses to Exam in Multiple Choice Two********** */
            var max_fields = 3; //maximum element
            var wrapper2 = $(".responsesEMC1");//element wrapper
            var ctr = 1; //initial element count
            var x = 2;

            $('#addResponseEMC1').on('click',function(e){
                e.preventDefault();
                if(ctr <= max_fields){
                    x++;
                    $(wrapper2).append(
                        '<div class="row addeddiv" id="'+ctr+'">'+
                            '<div class="form-group col-md-8">'+
                                '<input type="text" name="response1[]" class="form-control" placeholder="Enter Response...">'+
                            '</div>'+
                            '<div class="form-group col-md-3 mt-2">'+
                                '<input type="checkbox" name="isanswer1[]" value="'+x+'" id="'+x+'" >'+
                                ' <a href="#" class="remove_field" ><i class="fa fa-times"></a>'+
                            '</div>'+
                        '</div>'
                    );
                    ctr++;
                    
                }else{
                    swal.fire('Warning!', 'You cannot add morethan three (3).', 'error');
                }                
            });
            $(wrapper2).on('click','.remove_field', function(e){
                e.preventDefault();
                $(this).parents('.addeddiv').remove();
                ctr--;
                x--;
            });



/* *************************CREATING QUIZ*********************************** */            
            let sendQuizDetail = (value) => {
                $.ajax({
                    url:"{{route('submitQuizDetails')}}",
                    type:"POST",
                    data: value
                }).done(function(response){
                    $("#quizdetail").addClass('hide');
                    $("#quizForm").removeClass('hide');

                    if(response.success_res){
                        $('input[name="einstruct_id"]').val(response.ei_id);
                        $('.add-qb').data('einstructid',response.ei_id);
                        swal.fire("Done!", response.success_res,'success');
                    }
                    if(response.idesc != null){
                        $('.card-body').prepend(
                            '<div class="row">'+
                                '<div class="form-group col-md-12">'+
                                    '<p>'+response.idesc+'</p>'+
                                '</div>'+
                            '</div>'
                        );
                    }
                    //console.log('response.ei_id='+response.ei_id+" response.idesc"+response.idesc);
                });
            }
            $('#createQuiz').on('click', function(e){
                e.preventDefault();
                console.log($('.createquizdetails').serialize());
                if($("textarea[name='instruction']").val() == ""){
                    $("#warningModal").modal('show');
                }else{
                    sendQuizDetail($('.createquizdetails').serialize());
                }
                $("#continue").on('click',function(){
                    //console.log('conitnue');
                    sendQuizDetail($('.createquizdetails').serialize());
                    $("#warningModal").modal('hide');
                });
            });
            /* ******Create Quiz Questions***** */
            let sendQuizQuestion = (value) => {
                $.ajax({
                    url: "{{route('submitQuizQuestions')}}",
                    type: "post",
                    data: value
                }).done(function(response){
                    if(response.err){
                        //console.log(response.err);
                        swal.fire('Warning!', response.err, 'error');
                    }
                    if(response.err_answer){
                        $('input[type="checkbox"]').addClass('is-invalid');
                        swal.fire('Warning!', response.err_answer, 'error');
                    }
                    if(response.done){
                        $('textarea[name="question_desc"]').val("");
                        $('input[type="text"]').val("");
                        $('input[name="points"]').val("");
                        $('input[type="checkbox"]').prop("checked", false);;
                        swal.fire('Done!', response.done, 'success');
                        $("#numitems").text(response.getNumItems);
                        $("#totalpoints").text(response.sum);
                        /* <span id="qctr"></span> */
                        $("#qctr").text((response.getNumItems+1)+" of "+response.getNumItems);
                    }
                    
                    //console.log(response);
                    //console.log(response.done);
                });
            }
            $('#saveQuizAr').on('click',function(e){
                e.preventDefault();
                //console.log($('.alternateResponse').serialize());
                if($('textarea[name="question_desc_ar"]').val()==""){
                    swal.fire('Warning', 'You Must create question before saving.', 'error');
                }else if($('input[name="ans"]:checked').length==0){
                    swal.fire('Warning', 'Select a Correct Answer', 'error');
                }else if($('input[name="pointsar"]').val()==""){
                    swal.fire('Warning', 'You must indicate point/s to this item.', 'error');
                }else{
                    //console.log('sending..');
                    sendQuizQuestion($('.alternateResponse').serialize());
                }
                
            });
            /* Multiple choice */
            $('#saveQuiz').on('click', function(e){
                e.preventDefault();
                //console.log($('.saveQuizQuestionForm').serializeArray());
               $('input[name="response[]"').each(function(){
                    if($(this).val() == ""){
                        $(this).addClass('is-invalid');
                    }
                    $(this).keyup(function(){
                        $(this).removeClass('is-invalid');
                    });
                });
                /* $('input[type="checkbox"]').click(function(){
                    $(this).addClass('is-invalid');
                }); */
                $('input[name="points"], textarea[name="question_desc"]').on('keyup',function(){
                    $(this).removeClass('is-invalid');
                });
                if($('input[name="points"]').val() == ""){
                    $('input[name="points"]').addClass('is-invalid');
                    
                }
                if($('textarea[name="question_desc"]').val()==""){
                    $('textarea[name="question_desc"]').addClass('is-invalid');
                    
                }
                sendQuizQuestion($('.saveQuizQuestionForm').serialize());
            });
            /* Adding responses to Quiz */
            var max_fields = 3; //maximum element
            var wrapper = $(".responses");//element wrapper
            var ctr = 1; //initial element count
            var x = 2;

            $('#addResponse').on('click',function(e){
                e.preventDefault();
                if(ctr <= max_fields){
                    x++;
                    $(wrapper).append(
                        '<div class="row addeddiv" id="'+ctr+'">'+
                            '<div class="form-group col-md-8">'+
                                '<input type="text" name="response[]" class="form-control" placeholder="Enter Response...">'+
                            '</div>'+
                            '<div class="form-group col-md-3 mt-2">'+
                                '<input type="checkbox" name="isanswer[]" value="'+x+'" id="'+x+'" >'+
                                ' <a href="#" class="remove_field" ><i class="fa fa-times"></a>'+
                            '</div>'+
                        '</div>'
                    );
                    ctr++;
                    
                }else{
                    swal.fire('Warning!', 'You cannot add morethan three (3).', 'error');
                }                
            });
            $(wrapper).on('click','.remove_field', function(e){
                e.preventDefault();
                $(this).parents('.addeddiv').remove();
                ctr--;
                x--;
            });

            $('.selectQtype').on('change',function(e){
                e.preventDefault();
                //console.log($(this).val());
                if($(this).val() == 'mc'){
                    $(".selectQtype").val($(this).val());
                    //$(".selectQtype option[value=ar]").attr(''); */
                    $('.alternateResponse').addClass('hide');
                    $('.multiple-choice').removeClass('hide');
                }
                if($(this).val() == 'ar'){
                    $(".selectQtype").val($(this).val());
                    $('.multiple-choice').addClass('hide');
                    $('.alternateResponse').removeClass('hide');
                    //$('.selectQtype option').attr('selected','selected');
                }
            });
/* ****************Loading Question in Quiz*********************************** */
            $('#loadQuestions').on('click', function(e){
                e.preventDefault();
                $("#qBank").modal('show');
            });
            $(".add-qb-mc").on('click',function(e){
                e.preventDefault();
                console.log($(this).attr('id') + $(this).data('einstructid'));
                loadQuizQuestions($(this).attr('id'),$(this).data('einstructid'));
            })
            $(".add-qb-ar").on('click',function(e){
                e.preventDefault();
                console.log($(this).attr('id'));
                loadQuizQuestions($(this).attr('id'),$(this).data('einstructid'));
            })
            /* Send data to controller loadQuizQuestionFromQB */
            let loadQuizQuestions = (qb_id, einstruct_id) => {
                $.ajax({
                    url:"{{route('loadQuizQuestionFromQB')}}",
                    type:"POST",
                    data:{qb_id:qb_id, einstruct_id:einstruct_id}
                }).done(function(response){
                    console.log(response);
                    //$("#totalpoints").text(response.totalQuizzes);
                    
                    if(response.success){
                      swal.fire('Done!', response.success, 'success');
                        $("#numitems").text(response.getNumItems);
                        $("#totalpoints").text(response.totalQuizzes);
                        $("#qctr").text((response.getNumItems+1)+" of "+response.getNumItems);
                    }
                    if(response.error){
                        swal.fire('Warning!', response.error,'error');
                    }
                   
                });
            }
        });
             
    </script>
@endsection

