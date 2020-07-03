@extends('layouts.app')

@section('title')
    Student
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
        <div class="col-md-9 mb-4 bg-white">
            <div class="container-fluid mt-2 mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <h4 class="border-bottom pl-2">My Courses</h4>
                    </div>
                </div>
                @foreach ($myclasses as $mc)
                    @php
                        $scid       =$mc->sc_id;
                        $sccode     =$mc->course_code;
                        $desc       =$mc->descriptive_title;
                        $sched      =$mc->schedule;
                    @endphp
                @endforeach

                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('studentdashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    <li class="breadcrumb-item active"><a href="{{route('vmc')}}"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes</a></li>
                                    <li class="breadcrumb-item active"><i class="fa fa-info" aria-hidden="true"></i> {{$desc}}</li>
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
                <div class="row">
                    <div class="col-md-12">
                        <nav class="navbar navbar-expand-sm navbar-light">
                            <a class="btn {{(isset($vmcer)&&$vmcer=='eval')? 'btn-outline-secondary' : 'btn-secondary' }}" href="{{route('vmcinfo_er', ['vmcer'=>'eval'])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Evaluation</a> &nbsp; 
                            <a class="btn {{(isset($vmcer)&&$vmcer=='resources')? 'btn-outline-secondary' : 'btn-secondary' }}" href="{{route('vmcinfo_er', ['vmcer'=>'resources'])}}"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Resources</a>
                        </nav>
                    </div>
                </div>
                <div class="row">{{-- My Class List --}}
                    <div class="col-md-12">
                        @if (isset($vmcer) && $vmcer=="resources")
                            @if (isset($getfiles))
                                @if (count($getfiles))
                                    <table class="table mylistofclass-tbl">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Course Management Description</th>
                                                <th>File</th>
                                                <th>Created-At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm">
                                            @foreach ($getfiles as $gf)
                                                <tr>
                                                    <td>{{$gf->cm_description}}</td>
                                                    <td><img src="/storage/coursematerials/{{$gf->cm_files}}" alt="">{{$gf->cm_files}}</td>
                                                    <td>{{\Carbon\Carbon::parse($gf->created_at)->diffForhumans()}}</td>
                                                    <td> <a href="{{route('dlResources',['file'=>$gf->cm_files])}}" class="dl-file" id="{{$gf->cm_id}}"><i class="fa fa-download" aria-hidden="true"></i> </a> </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            @endif
                            
                        @endif

                        @if (isset($myClassEval))
                            @if (count($myClassEval) >0)
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Type</th>
                                            <th>Part/s</th>
                                            <th>Description/Instruction</th>
                                            <th># of Questions</th>
                                            <th>Time Created</th>
                                            <th>Take</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach ($myClassEval as $item)
                                            @if ($item['isactive'] == 1)
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

                                                    <td>
                                                        {{\Carbon\Carbon::parse($item['created_at'])->diffForhumans()}}
                                                    </td>
                                                    <td>
                                                        <a href="{{route('take_eval',['take'=>$item['cpoep_id']])}}" class="btn btn-info"><i class="fa fa-play" aria-hidden="true"></i> Start</a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    No Available Quiz/zes and Exam/s Yet.
                                </div>
                            @endif
                            
                        @endif
                
                    </div>
                </div>{{-- End of My Class List --}}
            </div>{{-- ************** --}}
        </div>
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
           /*  $(".dl-file").on('click',function(e){
                e.preventDefault();
                //console.log($(this).attr('id'));
                sendDataToDL($(this).attr('id'));
            });
            
            let sendDataToDL = (value) =>{
                $.ajax({
                    url: "{{route('dlResources')}}",
                    type: "POST",
                    data: {cmid:value}
                }).done(function(response){
                    console.log(response);
                });
            }
 */
        });
             
    </script>
@endsection
