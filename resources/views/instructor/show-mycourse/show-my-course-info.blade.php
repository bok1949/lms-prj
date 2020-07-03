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
            <div class="container-fluid mt-2"> {{-- container-fluid --}}
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
                                    <li class="breadcrumb-item active"><i class="fa fa-file-word-o" aria-hidden="true"></i> My Class Information</li>
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
                        <h5 class="text-center bg-success text-white p-2">Evaluation Created in <strong>{{$coursecode}}</strong> <span class="small font-italic">({{$coursetitle}}).</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="ml-4"><span class="text-dark"><i class="fa fa-calendar" aria-hidden="true"></i> <strong>Class Schedule</strong></span> <i class="fa fa-caret-right" aria-hidden="true"></i> <span class="text-dark">{{$sched}}</span></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{route('viewMyClassList', ['cpoid_ceic'=>$cpoid,'viewclasslist'=>'classlist'])}}" class="btn {{(isset($getClassList))? 'btn-outline-secondary' : 'btn-secondary'}} "><i class="fa fa-list-alt" aria-hidden="true"></i> Class List</a>
                        <a href="{{route('viewMyClassList', ['cpoid_ceic'=>$cpoid])}}" class="btn {{(isset($myClassEval))? 'btn-outline-secondary' : 'btn-secondary'}}"><i class="fa fa-list-alt" aria-hidden="true"></i> Evaluation List</a>
                        <a href="{{route('viewMyClassList', ['cpoid_ceic'=>$cpoid,'viewclasslist'=>'viewevalresult'])}}" class="btn {{(isset($classEvalResult))? 'btn-outline-secondary' : 'btn-secondary'}}"><i class="fa fa-list-alt" aria-hidden="true"></i> Evaluation Results</a>
                    </div>
                </div>
                <div class="row mt-2 mb-4">
                    {{-- classEvalResult --}}
                    {{-- <pre>
                    @php
                        print_r($evalTypeTotal);
                    @endphp
                    </pre> --}}
                    @isset($classEvalResult)
                        @if (count($classEvalResult) == 0)
                            <div class="col-md-10 offset-1">
                                <div class="alert alert-warning text-center" role="alert">
                                    No Evaluation Result yet!
                                </div>
                            </div>
                        @else
                        <div class="col-md-10 offset-1">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        @foreach ($evalTypeTotal as $item)
                                            <th>
                                                {{ucfirst($item['eval_type'])}} <br>
                                                {{$item['total_eval']}}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($classEvalResult as $item)
                                        <tr>
                                            <td>{{$item['last_name']}}</td>
                                            <td>{{$item['first_name']}}</td>
                                            
                                            @foreach ($item['result'] as $itemVal)
                                                <td>{{$itemVal->eval_result}}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    @endisset

                    @isset($getClassList)
                        @if (count($getClassList)==0)
                            <div class="col-md-10 offset-1">
                                <div class="alert alert-warning text-center" role="alert">
                                    No List of Student created yet in this Course!
                                </div>
                            </div>
                        @else
                        <div class="col-md-10 offset-1">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">ID-Number</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getClassList as $cl)
                                    <tr>
                                        <td>{{$cl->id_number}}</td>
                                        <td>{{$cl->last_name}}</td>
                                        <td>{{$cl->first_name}}</td>
                                        <td><a href="" class="removeFromTheClass" data-name="{{$cl->first_name}} {{$cl->last_name}}" id="{{$cl->cpoes_id}}" data-toggle="tooltip" title="Remove from the Class"><i class="fa fa-minus-circle" aria-hidden="true"></i> </a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{$getClassList->links()}}
                        </div>
                        @endif
                    @endisset
                    @if (isset($myClassEval))
                        @if (count($myClassEval)>0)
                            <div class="col-md-10 offset-1">
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Evaluation Type</th>
                                        <th>Part</th>
                                        <th>Description/Instruction</th>
                                        <th># of Questions</th>
                                        <th>Created-At</th>
                                        <th>Active</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($myClassEval as $item)
                                        <tr>
                                            <td>
                                                {{ucfirst($item['evalType'])}}
                                            </td>
                                            <td>
                                                @foreach ($item['evalinstruct']['eiTbl'] as $val)
                                                    <table class="no-design">
                                                        <tr>
                                                            <td>{{$val->eval_parts}}</td>
                                                        </tr>
                                                    </table>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($item['evalinstruct']['eiTbl'] as $val)
                                                    <table class="no-design">
                                                        <tr>
                                                            <td>{{$val->instruction_desc}}</td>
                                                        </tr>
                                                    </table>
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($item['evalinstruct']['eiTbl'] as $val)
                                                    <table class="no-design">
                                                        <tr>
                                                            <td>{{\Illuminate\Support\Facades\DB::table('eval_instructions')
                                                            ->join('cpoep_questions', 'eval_instructions.einstruct_id','=','cpoep_questions.einstruct_id')
                                                            ->where('eval_instructions.einstruct_id',$val->einstruct_id)->count()}}</td>
                                                        </tr>
                                                    </table>
                                                @endforeach
                                            </td>
                                            <td>{{\Carbon\Carbon::parse($item['created_at'])->diffForhumans()}}</td>
                                            <td> 
                                                @if ($item['isactive'])
                                                    <a href="#" class="btn btn-danger deactivate-me" id={{$item['cpoep_id']}} >Deactivate</a>
                                                @else
                                                    <a href="#" class="btn btn-info activate-me" id={{$item['cpoep_id']}}>Activate</a>
                                                @endif
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        @else
                            <div class="col-md-10 offset-1">
                                <div class="alert alert-warning text-center" role="alert">
                                    No Evaluation created yet for this Course.
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>{{-- end of container fluid --}}
        </div>{{-- Column 9 data container --}}
    </div>{{-- end of row --}}
</div>{{-- Main Wrapper Container Fluid--}}



<!-- The Modal Submiting Quiz Details Blank -->
<div class="modal fade" id="warningModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Warning <i class="fa fa-exclamation" aria-hidden="true"></i> </h4>
                <a href="#" id="closeWarning" class="close" data-dismiss="modal">×</a>
            </div>
               
            <!-- Modal body -->
            <div class="modal-body">
                Are you sure you want to continue this action?
            </div>{{-- end of modal body --}}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" id="continueFile" class="btn btn-danger continue-exam" {{-- data-dismiss="modal" --}}> Continue</button>
                <button type="button" id="cancel" class="btn btn-primary" data-dismiss="modal"> Cancel</button>
            </div>
               
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal Submiting Quiz Details Blank -->


<!-- The Modal to remove student in a class -->
<div class="modal fade" id="warningModalRemoveStudClass" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Warning <i class="fa fa-exclamation" aria-hidden="true"></i> </h4>
                <a href="#" id="warningModalRemoveStudClass" class="close" data-dismiss="modal">×</a>
            </div>
               
            <!-- Modal body -->
            <div class="modal-body">
                You are about to remove <strong><span id="studentName"></span></strong> from your class.
                Are you sure you want to continue this action?
            </div>{{-- end of modal body --}}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" id="continueRemovingStudent" class="btn btn-danger continue-exam" {{-- data-dismiss="modal" --}}> Continue</button>
                <button type="button" id="cancel" class="btn btn-primary" data-dismiss="modal"> Cancel</button>
            </div>
               
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- End of The Modal to remove student in a class -->


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
            $(".deactivate-me").on('click',function(e){
                e.preventDefault();
                //console.log($(this).attr('id'));
                
                $.ajax({
                    url:"{{route('activateDeactivateEval')}}",
                    type: "post",
                    data: {cpoepid:$(this).attr('id')}
                }).done(function(response){
                    if(response.success){
                        swal.fire("Done", response.success, 'success');
                        setTimeout(function() { 
                                location.reload();
                                }, 3000);
                    }
                    console.log(response);
                });
            });
            $(".activate-me").on('click',function(e){
                e.preventDefault();
                //console.log($(this).attr('id'));
                $.ajax({
                    url:"{{route('activateDeactivateEval')}}",
                    type: "post",
                    data: {cpoepid:$(this).attr('id')}
                }).done(function(response){
                    if(response.success){
                        swal.fire("Done", response.success, 'success');
                        setTimeout(function() { 
                                location.reload();
                                }, 3000);
                    }
                    console.log(response);
                });
            });
            var cpoesid='';
            $(".removeFromTheClass").on('click',function(e){
                e.preventDefault();
                $('#warningModalRemoveStudClass').modal('show');
                $('#studentName').text($(this).attr('data-name'));
                cpoesid = $(this).attr('id');
                //console.log($(this).attr('id') + " "+$(this).attr('data-name'));
                
            });
            $("#continueRemovingStudent").on('click',function(){
                console.log("Continue Removing..."+cpoesid);
                $.ajax({
                    url: "{{route('removeStudentInClass')}}",
                    type: "POST",
                    data: {cpoes_id:cpoesid}
                }).done(function(response){
                    if(response.success){
                        swal.fire("Done", response.success, 'success'); 
                            setTimeout(function() { 
                                location.reload();
                                }, 3000);
                    }
                });
            });
            /* $('.remove-file').on('click',function(e){
                e.preventDefault();
                var cmid = $(this).attr('id');
                $("#warningModal").modal('show');
                $('#continueFile').on('click',function(e){
                    e.preventDefault();
                    //console.log('send to delete');
                    $.ajax({
                        url:"{{route('removeCourseMaterials')}}",
                        type: "post",
                        data: {cmid:cmid}
                    }).done(function(response){
                        console.log(response);
                        if(response.removed){
                            swal.fire("Done", response.removed, 'success'); 
                            setTimeout(function() { 
                                location.reload();
                                }, 3000);
                        }
                        if(response.wrong){
                            swal.fire("Warning", response.wrong, 'error');
                        }
                        $("#warningModal").modal('hide');
                    });
                });
            });  */
            
        });
             
    </script>
@endsection

