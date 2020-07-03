@extends('layouts.app')

@section('title')
    Student
@endsection

@section('header')
    @include('../layouts/header')
@endsection

@if (isset($useraccount))
    @foreach ($useraccount as $ua1)
        @php
            $deptCode=$ua1->dept_code;
        @endphp
    @endforeach
@endif
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 bg-faded" id="">
            <div class="row">
                <div class="col-md-3 position-fixed mt-2" id="sticky-sidebar">
                    <h3 class="text-center border-bottom border-secondary p-2"><a href="{{route('studentdashboard')}}" class="text-reset text-decoration-none"><i class="fa fa-tachometer" aria-hidden="true"></i> {{$deptCode}} Student</a></h3>
                    <ul class="nav flex-column nav-pills text-left p-4">
                        <li class="nav-item mb-4">
                            <a class="nav-link border border-primary" href="{{route('vmc')}}"> <i class="fa fa-file mr-2"></i> View My Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('vmer')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> View My Evaluation Result</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @foreach ($evaluationInfo as $mc)
            @php
                $scid       =$mc->sc_id;
                $sccode     =$mc->course_code;
                $desc       =$mc->descriptive_title;
                $sched      =$mc->schedule;
                $evalType   =$mc->cpoep_type;
                $cpoep_id   =$mc->cpoep_id;
            @endphp
        @endforeach
        @if (isset($getenrolledClass))
            @foreach ($getenrolledClass as $item)
                @php
                    $cpoes_id = $item->cpoes_id;
                @endphp
            @endforeach
        @endif
        <div class="col-md-9 mb-4 bg-white">
            <div class="container-fluid mt-2">
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <h4 class="border-bottom pl-2">Evaluation</h4>
                    </div>
                </div>

                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('studentdashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    <li class="breadcrumb-item active"><a href="{{route('vmc')}}"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes</a></li>
                                    <li class="breadcrumb-item active"><a href="{{route('vmcinfo_er', ['vmcer'=>'eval'])}}"><i class="fa fa-info" aria-hidden="true"></i> {{$desc}}</a></li>
                                    <li class="breadcrumb-item active"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Taking {{ucfirst($evalType)}}</li>
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="pt-2 pl-2 border-bottom border-success">{{$sccode}} <small>({{$desc}} | SC_ID <i class="fa fa-caret-right" aria-hidden="true"></i> {{$scid}})</small></h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="pl-2">
                            <i class="fa fa-calendar" aria-hidden="true"></i> Class Schedule 
                            <i class="fa fa-caret-right" aria-hidden="true"></i> {{$sched}}
                        </p>
                    </div>
                </div>
                @php
                    $ctr=1;
                    $ctr1=1;
                    $ctr2=1;
                @endphp
                {{-- @if (isset($questionschoices) && count($questionschoices) > 0) --}}{{-- Check if questions and responses are availabel --}}
                <form action="" class="exam_evaluation">{{-- ******Form for questionaires and responses******* --}}
{{-- ------------------Exam Part I----------------------}}
                    @if (isset($getPartsOfExam))
                        <div class="row mt-2 mb-2">
                            <div class="col-md-12">
                        @foreach ($getPartsOfExam as $item)
                            @if ($item->eval_parts == 'I')
                                @php
                                    $partI = $item->eval_parts;
                                @endphp
                                <h5 class="text-center border-bottom border-top border-warning">{{$item->eval_parts}}. {{$item->instruction_desc}}</h5>
                            @endif
                        @endforeach
                            {{-- {{count($questionschoices)}} --}}
                            </div>
                        </div>
                    @endif
                    <div class="row">{{-- Questions Handler --}}
                        <div class="col-md-8 offset-2">
            {{-- ***********Part One Alternate Response************* --}}                
                            @if (isset($partOneAr) && count($partOneAr)>0)
                                
                                @foreach ($partOneAr as $itemar1)
                                    @php
                                        $questionsCount=\Illuminate\Support\Facades\DB::table('eval_instructions')
                                            ->join('cpoep_questions', 'eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                                            ->where('eval_instructions.einstruct_id',$itemar1['einstruct_id'])->count()
                                    @endphp
                                    <div class="card ari{{$ctr}} questions-holder" id="{{$questionsCount}}">
                                            <div class="card-header" >
                                                {{$ctr}}. {{$itemar1['question_desc']}} | <small class="badge badge-success">{{ ($itemar1['points']==1)? $itemar1['points']." Point ":$itemar1['points']. " Points" }}</small>
                                            </div>
                                           
                                            <div class="card-body">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="" class="form-control border-0 text-center">True</label>
                                                            <input type="radio" name="ar_ans{{$ctr}}" value="1" class="form-control ar_ans{{$ctr}}" id="take_eval_design">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="" class="form-control border-0 text-center">False</label>
                                                            <input type="radio" name="ar_ans{{$ctr}}" value="0"  class="form-control ar_ans{{$ctr}}" id="take_eval_design">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  

                                            <div class="card-footer text-muted">
                                                <input type="hidden" name="ar1_qb_id[]" value="{{$itemar1['qb_id']}}">
                                                <button class="btn btn-primary prev{{$ctr}}" name="prev[]" id="{{$ctr}}" value="{{$ctr}}">Previous </button>
                                                <button class="btn btn-primary nextquestion{{$ctr}}" name="nextquestion[]" id="{{$ctr}}" value="{{$ctr}}">Next </button>
                                            </div>    
                                            @php
                                                $ctr++;
                                            @endphp
                                        
                                    </div>{{-- End of Card Questions Handler --}}
                                @endforeach
                               
                            @endif
            {{-- **************Part One Multiple Choice****************** --}}
                            @if (isset($partOneMc) && count($partOneMc)>0)
                                @foreach ($partOneMc as $itemMc)
                                    @php
                                        $questionsCount=\Illuminate\Support\Facades\DB::table('eval_instructions')
                                            ->join('cpoep_questions', 'eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                                            ->where('eval_instructions.einstruct_id',$itemMc['einstruct_id'])->count()
                                    @endphp
                                    <div class="card mcii{{$ctr}} mcii-qholder" id="{{$questionsCount}}">
                                        <div class="card-header">
                                            {{$ctr}}. {{$itemMc['question_desc']}} | <small class="badge badge-success">{{ ($itemMc['points']==1)? $itemMc['points']." Point ":$itemMc['points']. " Points" }}</small>
                                            {{-- <br>{{$questionsCount}} --}}
                                        </div>
                                        <div class="card-body">
                                            @if (count($itemMc['choices']['multiple_choices']) > 0)
                                            @php
                                                $ctrDesign=1;
                                            @endphp
                                                @foreach ($itemMc['choices']['multiple_choices'] as $mc)
                                                    <div class="container-fluid">
                                                        <div class="row" id={{($ctrDesign % 2 == 0) ? "zebra":""}}>
                                                            <div class="form-group col-md-8 align-self-center mt-3">
                                                                {{-- <p class="mb-0"> --}}{{$mc->choices_description}} {{-- </p> --}}
                                                            </div>
                                                            @if ($itemMc['choices']['mc_count_ans'] > 1)
                                                                <div class="form-group col-md-2 mt-3">
                                                                    <input type="checkbox" name="exam_mcii{{$ctr}}[]" class="form-control mcii_ans{{$ctr}}" value="{{$mc->mc_id}}"  id="take_eval_design">
                                                                </div>
                                                                {{-- <input type="hidden" name="mc2_qb_id[]" value="{{$itemMc2['qb_id']}}"> --}}
                                                            @else
                                                                <div class="form-group col-md-2 mt-3">
                                                                    <input type="radio" name="exam_mcii{{$ctr}}" class="form-control mcii_ans{{$ctr}}" value="{{$mc->mc_id}}" id="take_eval_design">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @php
                                                    $ctrDesign++;
                                                @endphp
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="card-footer text-muted">
                                            <input type="hidden" name="mc1_qb_id[]" value="{{$itemMc['qb_id']}}">
                                            <button class="btn btn-primary prev_mcii{{$ctr}}" name="prev_mcii[]" id="{{$ctr}}" value="{{$ctr}}">Previous </button>
                                            <button class="btn btn-primary nextquestion_mcii{{$ctr}}" name="nextquestion_mcii[]" id="{{$ctr}}" value="{{$ctr}}">Next </button>
                                        </div>
                                    </div>
                                    @php
                                        $ctr++;
                                    @endphp
                                @endforeach
                            @endif
                        </div>
                    </div>{{-- End of Questions Handler --}}
    {{-- ------------------End of Exam Part I ------------------- --}}


    {{-- ------------------Start of Exam Part II ------------------- --}}
                    @if (isset($getPartsOfExam))
                        <div class="row mt-2 mb-2">
                            <div class="col-md-12">
                        @foreach ($getPartsOfExam as $item)
                            @if ($item->eval_parts == 'II')
                                @php
                                    $partII = $item->eval_parts;
                                @endphp
                                <h5 class="text-center border-bottom border-top border-warning mt-2">{{$item->eval_parts}}. {{$item->instruction_desc}}</h5>
                            @endif
                        @endforeach
                            {{-- {{count($questionschoices)}} --}}
                            </div>
                        </div>
                    @endif
                    <div class="row">{{-- Questions Handler --}}
                        <div class="col-md-8 offset-2">
            {{-- *****************Part Two Alternate Reponse******************* --}}                    
                            @if (isset($partTwoAr) && count($partTwoAr)>0)
                                @foreach ($partTwoAr as $itemar1)
                                    @php
                                        $questionsCount=\Illuminate\Support\Facades\DB::table('eval_instructions')
                                            ->join('cpoep_questions', 'eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                                            ->where('eval_instructions.einstruct_id',$itemar1['einstruct_id'])->count()
                                    @endphp
                                    <div class="card ari{{$ctr1}} questions-holder" id="{{$questionsCount}}">
                                            <div class="card-header" >
                                                {{$ctr1}}. {{$itemar1['question_desc']}} | <small class="badge badge-success">{{ ($itemar1['points']==1)? $itemar1['points']." Point ":$itemar1['points']. " Points" }}</small>
                                            </div>
                                            <div class="card-body">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="" class="form-control border-0 text-center">True</label>
                                                            <input type="radio" name="ar_ans{{$ctr1}}" value="1" class="form-control ar_ans{{$ctr1}}" id="take_eval_design">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="" class="form-control border-0 text-center">False</label>
                                                            <input type="radio" name="ar_ans{{$ctr1}}" value="0"  class="form-control ar_ans{{$ctr1}}" id="take_eval_design">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  

                                            <div class="card-footer text-muted">
                                                <input type="hidden" name="ar2_qb_id[]" value="{{$itemar1['qb_id']}}">
                                                <button class="btn btn-primary prev{{$ctr1}}" name="prev[]" id="{{$ctr1}}" value="{{$ctr1}}">Previous </button>
                                                <button class="btn btn-primary nextquestion{{$ctr1}}" name="nextquestion[]" id="{{$ctr1}}" value="{{$ctr1}}">Next </button>
                                            </div>    
                                            @php
                                                $ctr1++;
                                            @endphp
                                        
                                    </div>{{-- End of Card Questions Handler --}}
                                @endforeach
                               
                            @endif
            {{-- ******************Part Two Multiple Choice*********************** --}}
                            @if (isset($partTwoMc) && count($partTwoMc)>0)
                                @foreach ($partTwoMc as $itemMc2)
                                    @php
                                        $questionsCount=\Illuminate\Support\Facades\DB::table('eval_instructions')
                                            ->join('cpoep_questions', 'eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                                            ->where('eval_instructions.einstruct_id',$itemMc2['einstruct_id'])->count()
                                    @endphp
                                    <div class="card mcii{{$ctr1}} mcii-qholder" id="{{$questionsCount}}">
                                        <div class="card-header">
                                            {{$ctr1}}. {{$itemMc2['question_desc']}} | <small class="badge badge-success">{{ ($itemMc2['points']==1)? $itemMc2['points']." Point ":$itemMc2['points']. " Points" }}</small>
                                            {{-- <br>{{$questionsCount}} --}}
                                        </div>
                                        <div class="card-body">
                                            @if (count($itemMc2['choices']['multiple_choices']) > 0)
                                            @php
                                                $ctrDesign=1;
                                            @endphp
                                                @foreach ($itemMc2['choices']['multiple_choices'] as $mc)
                                                    <div class="container-fluid">
                                                        <div class="row" id={{($ctrDesign % 2 == 0) ? "zebra":""}}>
                                                            <div class="form-group col-md-8 align-self-center mt-3">
                                                                {{-- <p class="mb-0"> --}}{{$mc->choices_description}} {{-- </p> --}}
                                                            </div>
                                                            @if ($itemMc2['choices']['mc_count_ans'] > 1)
                                                                <div class="form-group col-md-2 mt-3">
                                                                    <input type="checkbox" name="exam_mcii{{$ctr1}}[]" class="form-control mcii_ans{{$ctr1}}" value="{{$mc->mc_id}}"  id="take_eval_design">
                                                                </div>
                                                                {{-- <input type="hidden" name="mc2_qb_id[]" value="{{$itemMc2['qb_id']}}"> --}}
                                                            @else
                                                                <div class="form-group col-md-2 mt-3">
                                                                    <input type="radio" name="exam_mcii{{$ctr1}}" class="form-control mcii_ans{{$ctr1}}" value="{{$mc->mc_id}}" id="take_eval_design">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @php
                                                    $ctrDesign++;
                                                @endphp
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="card-footer text-muted">
                                            <input type="hidden" name="mc2_qb_id[]" value="{{$itemMc2['qb_id']}}">
                                            <button class="btn btn-primary prev_mcii{{$ctr1}}" name="prev_mcii[]" id="{{$ctr1}}" value="{{$ctr1}}">Previous </button>
                                            <button class="btn btn-primary nextquestion_mcii{{$ctr1}}" name="nextquestion_mcii[]" id="{{$ctr1}}" value="{{$ctr1}}">Next </button>
                                        </div>
                                    </div>
                                    @php
                                        $ctr1++;
                                    @endphp
                                @endforeach
                            @endif
                        </div>
                    </div>{{-- End of Questions Handler --}}
                    <div class="row">
                        <div class="col-md-12 mt-2 mb-2">
                            <div class="form-group col-md-6 offset-3">
                                <input type="hidden" name="cpoep_id" value="{{$cpoep_id}}">
                                <input type="hidden" name="cpoes_id" value="{{$cpoes_id}}">
                                <button type="submit" name="fin_eval_exam" class="form-control btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>{{-- ******End of Form for questionaires and responses******* --}}
{{-- ------------------Start of Exam Part II ------------------- --}}
                {{-- @else
                    <div class="alert alert-warning" role="alert">
                        <strong>Warning!</strong> No Available Questions yet.
                    </div>
                @endif --}}{{-- end of Check if questions and responses are availabel --}}
            </div>{{-- *******container******* --}}
        </div>
    </div>
</div>


<!-- The Modal For Showing Student Exam Score -->
<div class="modal fade" id="showEvalScore" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
        
            <!-- Modal body -->
            <div class="modal-body">
                <h6 class="text-center" id="showScore"></h6>
            </div>{{-- end of modal body --}}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" id="closeEvalScoreModal" class="btn btn-info" >Close</button>
            </div>
           
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal For showing Student score -->



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
            //let me=23;
            //console.log("me");
            $("button[name='prev[]']").hide();
            $("button[name='prev_mcii[]']").hide();
            /* $("button[name='fin_eval_exam']").hide(); */
            var selected = 1;
            var totalNumQ = $('.questions-holder').attr('id');
            //console.log(totalNumQ);
            for(var i = 1; i<=totalNumQ;i++ ){
                if(i<=selected){
                    $(".ari"+i).show();
                }else{
                    $(".ari"+i).hide();
                }
            }
            
           let selected_mc = 1;
           let totalNumQ_mc = $('.mcii-qholder').attr('id');
           for(var i = 1; i<=totalNumQ_mc;i++ ){
                if(i<=selected_mc){
                    $(".mcii"+i).show();
                }else{
                    $(".mcii"+i).hide();
                }
            }     

            let counter=1;
            let ctr=1;
            let ar1=[];
            $("button[name='nextquestion[]']").each(function(index){
                $(this).on('click',function(e){
                    e.preventDefault();
                    
                    if(!$(".ar_ans"+ctr).is(":checked")){
                        swal.fire("Warning!", "You must select your answer first.","warning");
                    }else{
                        counter+=1;
                        $(".ari"+counter).show().siblings().hide();
                        //console.log($("button[name='nextquestion[]']").length);
                        if(counter == $("button[name='nextquestion[]']").length){
                            $("button[name='nextquestion[]']").hide();
                        }
                        if(counter>1){
                            $("button[name='prev[]']").show();
                        }
                        ctr++;
                    }
                   /*  console.log($("button[name='nextquestion[]']").length+" ctr-variable="+ctr);
                    console.log("value"+$(this).attr('class')+"|"+" index "+index+" | counter"+counter);
                    console.log(" value "+$(this).val()); */
                }); 
            });
            $("button[name='prev[]']").each(function(index){
                $(this).on('click',function(e){
                    e.preventDefault();
                    counter-=1;
                    ctr--;
                    let idval=$(this).attr('id')-1;
                    $(".ari"+counter).show().siblings().hide();
                    //console.log("prev"+" Counter="+counter+" | Value="+$(this).val()+" ID="+idval);
                    if(counter==1){
                        $('.prev'+counter).hide();
                    }
                    if(counter < $("button[name='nextquestion[]']").length){
                        $("button[name='nextquestion[]']").show();
                    }
                });
                
            });
             
/**************** Part II *******************/
            let counterII=1;
            let ctrII=1;
            let mc2=[];
            $('button[name="nextquestion_mcii[]"').each(function(index2){
                $(this).on('click',function(e){
                    e.preventDefault();
                    
                    if(!$(".mcii_ans"+ctrII).is(":checked")){
                        swal.fire("Warning!", "You must select your answer first.","warning");
                    }else{
                        counterII+=1;
                        /* alert(counterII); */
                        $(".mcii"+counterII).show().siblings().hide();
                        //console.log($("button[name='nextquestion_mcii[]']").length);
                        if(counterII == $("button[name='nextquestion_mcii[]']").length){
                            $("button[name='nextquestion_mcii[]']").hide();
                        }
                        if(counterII>1){
                            $("button[name='prev_mcii[]']").show();
                        }
                        ctrII++;
                    }

                });
            });
            $('button[name="prev_mcii[]"]').each(function(index2){
                $(this).on('click',function(e){
                    e.preventDefault();
                    counterII-=1;
                    ctrII--;
                    let idval=$(this).attr('id')-1;
                    $(".mcii"+counterII).show().siblings().hide();
                    //console.log("prev"+" Counter="+counter+" | Value="+$(this).val()+" ID="+idval);
                    if(counterII==1){
                        $('.prev_mcii'+counterII).hide();
                    }
                    if(counterII < $("button[name='nextquestion_mcii[]']").length){
                        $("button[name='nextquestion_mcii[]']").show();
                    }
                });
            });
            //$(".ar_ans"+ctr).is(":checked") //$(".mcii_ans"+ctrII).is(":checked")
            let fin1 = "";
            let fin2 = "";
            $("button[name='fin_eval_exam']").on('click',function(e){
                e.preventDefault();
                //alert("Fin Clicked");
                console.log("nextquestion_mcii[]"+$("button[name='nextquestion_mcii[]']").length +"|"+$("button[name='nextquestion[]']").length);
                if($("button[name='nextquestion_mcii[]']").length > 0){
                    
                    for (let index = 1; index <= $("button[name='nextquestion[]']").length; index++) {
                        if(!$(".ar_ans"+index).is(":checked")){
                            swal.fire("Warning!","Must answer all questions.nxtq","error");
                            fin1=false;
                        }else{
                            fin1=true;
                        }
                    }
                    for (let index = 1; index <= $("button[name='nextquestion_mcii[]']").length; index++) {
                        if(!$(".mcii_ans"+index).is(":checked")){
                            swal.fire("Warning!","Must answer all questions. mcii","error");
                            fin2=false;
                        }else{
                            fin2=true;
                        }
                    }
                }else{
                    for (let index = 1; index <= $("button[name='nextquestion[]']").length; index++) {
                        if(!$(".ar_ans"+index).is(":checked")){
                            swal.fire("Warning!","Must answer all questions. else","error");
                        }else{
                            fin1=true;
                        }
                    }
                }

                if(fin1==true && fin2==true){
                    //send to database for checking
                    //console.log($('.exam_evaluation').serializeArray());
                    evalExamTaken($('.exam_evaluation').serialize());
                }else if(fin1==true){
                    //send to database for checking
                    //console.log($('.exam_evaluation').serializeArray());
                    evalExamTaken($('.exam_evaluation').serialize());
                }
                
            });
         
            let evalExamTaken = (value) => {
                $.ajax({
                    url: "{{route('sbumitEvaluationResult')}}",
                    type: "post",
                    data: value
                }).done(function(response){
                    console.log(response);
                    $("#showEvalScore").modal("show");
                    //showScore
                    $("#showScore").text("Your score is: "+response.totalscore+" out of "+response.getTotalPoints+" points.");
                    $("#closeEvalScoreModal").on('click',function(e){
                        e.preventDefault();
                        window.location="{{route('take_eval')}}";
                    });
                });
            }
            
        });
             
    </script>
@endsection
