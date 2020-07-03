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
               
                <form action="" class="quiz_evaluation">{{-- ******Form for questionaires and responses******* --}}

                    <div class="row">{{-- Questions Handler --}}
                        <div class="col-md-8 offset-2">
                            @if (isset($quizzes)&&count($quizzes)>0)
                                @foreach ($quizzes as $item)
                                    @php
                                        $questionsCount=\Illuminate\Support\Facades\DB::table('eval_instructions')
                                            ->join('cpoep_questions', 'eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                                            ->where('eval_instructions.einstruct_id',$item['einstruct_id'])->count()
                                    @endphp
                                    @if ($item['question_type']=='mc')
                                        <div class="card qholder{{$ctr}} questions-qholder" id="{{$questionsCount}}">
                                            <div class="card-header">
                                                {{$ctr}}. {{$item['question_desc']}} | <small class="badge badge-success">{{ ($item['points']==1)? $item['points']." Point ":$item['points']. " Points" }}</small>
                                                {{-- <br>{{$questionsCount}} --}}
                                            </div>
                                            <div class="card-body">
                                                @if (count($item['choices']['multiple_choices']) > 0)
                                                @php
                                                    $ctrDesign=1;
                                                @endphp
                                                    @foreach ($item['choices']['multiple_choices'] as $mc)
                                                        <div class="container-fluid">
                                                            <div class="row" id={{($ctrDesign % 2 == 0) ? "zebra":""}}>
                                                                <div class="form-group col-md-8 align-self-center mt-3">
                                                                    {{$mc->choices_description}}
                                                                </div>
                                                                @if ($item['choices']['mc_count_ans'] > 1)
                                                                    <div class="form-group col-md-2 mt-3">
                                                                        <input type="checkbox" name="mc_ans{{$ctr}}[]" class="form-control mc_ans{{$ctr}} quiz_ans{{$ctr}}" value="{{$mc->mc_id}}"  id="take_eval_design">
                                                                    </div>
                                                                    {{-- <input type="hidden" name="mc2_qb_id[]" value="{{$itemMc2['qb_id']}}"> --}}
                                                                @else
                                                                    <div class="form-group col-md-2 mt-3">
                                                                        <input type="radio" name="mc_ans{{$ctr}}" class="form-control mc_ans{{$ctr}} quiz_ans{{$ctr}}" value="{{$mc->mc_id}}" id="take_eval_design">
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
                                                <input type="hidden" name="quiz_qb_id[]" value="{{$item['qb_id']}}">
                                                <input type="hidden" name="questiontype[]" value="{{$item['question_type']}}">
                                                <button class="btn btn-primary prev_mc{{$ctr}}" name="prev_mc[]" id="{{$ctr}}" value="{{$ctr}}">Previous </button>
                                                <button class="btn btn-primary nextquestion_mc{{$ctr}}" name="nextquestion_mc[]" id="{{$ctr}}" value="{{$ctr}}">Next </button>
                                            </div>
                                        </div>
                                    @elseif($item['question_type']=='ar')
                                        <div class="card qholder{{$ctr}} questions-holder" id="{{$questionsCount}}">
                                            <div class="card-header" >
                                                {{$ctr}}. {{$item['question_desc']}} | <small class="badge badge-success">{{ ($item['points']==1)? $item['points']." Point ":$item['points']. " Points" }}</small>
                                            </div>
                                            
                                            <div class="card-body">
                                                <div class="container-fluid">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="" class="form-control border-0 text-center">True</label>
                                                            <input type="radio" name="ar_ans{{$ctr}}" value="1" class="form-control ar_ans{{$ctr}} quiz_ans{{$ctr}}" id="take_eval_design">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="" class="form-control border-0 text-center">False</label>
                                                            <input type="radio" name="ar_ans{{$ctr}}" value="0"  class="form-control ar_ans{{$ctr}} quiz_ans{{$ctr}}" id="take_eval_design">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  

                                            <div class="card-footer text-muted">
                                                <input type="hidden" name="quiz_qb_id[]" value="{{$item['qb_id']}}">
                                                <input type="hidden" name="questiontype[]" value="{{$item['question_type']}}">
                                                <button class="btn btn-primary prev_ar{{$ctr}}" name="prev_ar[]" id="{{$ctr}}" value="{{$ctr}}">Previous </button>
                                                <button class="btn btn-primary nextquestion_ar{{$ctr}}" name="nextquestion_ar[]" id="{{$ctr}}" value="{{$ctr}}">Next </button>
                                            </div>    

                                        </div>{{-- End of Card Questions Handler --}}
                                    @endif
                                    @php
                                        $ctr++;
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
                                <button type="submit" name="fin_eval_quiz" class="form-control btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>{{-- ******End of Form for questionaires and responses******* --}}

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

            $("button[name='prev_ar[]']").hide();
            $("button[name='prev_mc[]']").hide();

            var selected = 1;
            var totalNumQ = $('.questions-holder').attr('id');

            for(var i = 1; i<=totalNumQ;i++ ){
                if(i<=selected){
                    $(".qholder"+i).show();
                }else{
                    $(".qholder"+i).hide();
                }
            }

            let counter=1;
            let ctr=1;
            let ar1=[];
            $("button[name='nextquestion_ar[]']").each(function(index){
                $(this).on('click',function(e){
                    e.preventDefault();
                    
                    if(!$(".ar_ans"+ctr).is(":checked")){
                        swal.fire("Warning!", "You must select your answer first AR.","warning");
                    }else{
                        counter++;
                        $(".qholder"+counter).show().siblings().hide();
        
                        if(counter>1){
                            $("button[name='prev_ar[]']").show();
                            $("button[name='prev_mc[]']").show();
                        }
                        if(counter == totalNumQ){
                            $("button[name='nextquestion_ar[]']").hide();
                            $("button[name='nextquestion_mc[]']").hide();
                        }
                        ctr++;
                    }
                    console.log("AR button fired AR counter="+counter+" ctr="+ctr);
                }); 
            });
            $("button[name='prev_ar[]']").each(function(index){
                $(this).on('click',function(e){
                    e.preventDefault();
                    counter-=1;
                    ctr--;
                    let idval=$(this).attr('id')-1;
                    $(".qholder"+counter).show().siblings().hide();
                
                    if(counter==1){
                        $('.prev_ar'+counter).hide();
                        $('.prev_mc'+counter).hide();
                    }
                    if(counter < totalNumQ){
                        $("button[name='nextquestion_ar[]']").show();
                        $("button[name='nextquestion_mc[]']").show();
                    }
                });
                
            });
            $("button[name='nextquestion_mc[]']").each(function(index){
                $(this).on('click',function(e){
                    e.preventDefault();
                    if(!$(".mc_ans"+ctr).is(":checked")){
                        swal.fire("Warning!", "You must select your answer first MC.","warning");
                    }else{
                        counter++;
                        $(".qholder"+counter).show().siblings().hide();
                        if(counter>1){
                            $("button[name='prev_mc[]']").show();
                            $("button[name='prev_ar[]']").show();
                        }
                        if(counter == totalNumQ){
                            $("button[name='nextquestion_ar[]']").hide();
                            $("button[name='nextquestion_mc[]']").hide();
                        }
                        ctr++;
                    }
                    console.log("MC button fired AR counter="+counter+" ctr="+ctr);
                }); 
            });
             $("button[name='prev_mc[]']").each(function(index){
                $(this).on('click',function(e){
                    e.preventDefault();
                    counter-=1;
                    ctr--;
                    let idval=$(this).attr('id')-1;
                    $(".qholder"+counter).show().siblings().hide();
                    if(counter==1){
                        $('.prev_ar'+counter).hide();
                        $('.prev_mc'+counter).hide();
                    }
                    if(counter < totalNumQ){
                        $("button[name='nextquestion_ar[]']").show();
                        $("button[name='nextquestion_mc[]']").show();
                    }
                });
                
            });
    /* *************************Submit Quiz******************** */
            let finished = ""; 
            let done=[]; 
            let c=0;      
            $("button[name='fin_eval_quiz']").on('click',function(e){
                e.preventDefault();
                //console.log("nextquestion_mc[]"+$("button[name='nextquestion_mc[]']").length +"|"+$("button[name='nextquestion_ar[]']").length);
                if(totalNumQ > 0){
                    
                    for (let index = 1; index <= totalNumQ; index++) {
                        if(!$(".quiz_ans"+index).is(":checked")){
                            swal.fire("Warning!","Must answer all questions.","error");
                            finished=false;
                        }else{
                            finished=true;
                        }
                        
                    }
                }
                //console.log(finished);
                if(finished==true){
                    console.log("Submiting...Form");
                    console.log($('.quiz_evaluation').serializeArray());
                    evalQuizTaken($('.quiz_evaluation').serialize());
                }/* else if(finished==false){
                    console.log("There are questions not anwered");
                }  */

                /* if(fin1==true && fin2==true){
                    evalExamTaken($('.quiz_evaluation').serialize());
                }else if(fin1==true){
                    evalExamTaken($('.exam_evaluation').serialize());
                } */
                
            });
         
            let evalQuizTaken = (value) => {
                $.ajax({
                    url: "{{route('submitEvalQuizResult')}}",
                    type: "post",
                    data: value
                }).done(function(response){
                    console.log(response);
                    $("#showEvalScore").modal("show");
                    $("#showScore").text("Your score is: "+response.totalscore+" out of "+response.totalQuestionPoints+" points.");
                    $("#closeEvalScoreModal").on('click',function(e){
                        e.preventDefault();
                        window.location="{{route('take_eval')}}";
                    });
                });
            } 
/**************** Part II *******************/
            /* let counterII=1;
            let ctrII=1;
            let mc2=[];
            $('button[name="nextquestion_mcii[]"').each(function(index2){
                $(this).on('click',function(e){
                    e.preventDefault();
                    
                    if(!$(".mcii_ans"+ctrII).is(":checked")){
                        swal.fire("Warning!", "You must select your answer first.","warning");
                    }else{
                        counterII+=1;
                        
                        $(".mcii"+counterII).show().siblings().hide();
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
                    if(counterII==1){
                        $('.prev_mcii'+counterII).hide();
                    }
                    if(counterII < $("button[name='nextquestion_mcii[]']").length){
                        $("button[name='nextquestion_mcii[]']").show();
                    }
                });
            });
            let fin1 = "";
            let fin2 = "";
            $("button[name='fin_eval_exam']").on('click',function(e){
                e.preventDefault();
                console.log("nextquestion_mcii[]"+$("button[name='nextquestion_mcii[]']").length +"|"+$("button[name='nextquestion[]']").length);
                if($("button[name='nextquestion_mcii[]']").length > 0){
                    
                    for (let index = 1; index <= $("button[name='nextquestion[]']").length; index++) {
                        if(!$(".ar_ans"+index).is(":checked")){
                            swal.fire("Warning!","Must answer all questions.nxtq","error");
                        }else{
                            fin1=true;
                        }
                    }
                    for (let index = 1; index <= $("button[name='nextquestion_mcii[]']").length; index++) {
                        if(!$(".mcii_ans"+index).is(":checked")){
                            swal.fire("Warning!","Must answer all questions. mcii","error");
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
                    evalExamTaken($('.exam_evaluation').serialize());
                }else if(fin1==true){
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
                    $("#showScore").text("Your score is: "+response.totalscore+" out of "+response.getTotalPoints+" points.");
                    $("#closeEvalScoreModal").on('click',function(e){
                        e.preventDefault();
                        window.location="{{route('take_eval')}}";
                    });
                });
            } */
            
        });
             
    </script>
@endsection
