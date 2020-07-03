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
                            <a class="nav-link active" href="{{route('vmc')}}"> <i class="fa fa-file mr-2"></i> View My Courses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border border-primary" href="{{route('vmer')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> View My Evaluation Result</a>
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
                        <h4 class="border-bottom pl-2">My Course Evaluation Result</h4>
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
                                    <li class="breadcrumb-item active"><a href="{{route('vmer')}}"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes Tests Result</a></li>
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
              
                <div class="row">{{-- My Class List --}}
                    <div class="col-md-12">
                        <p>
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                Quiz 
                            </button>
                            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample">
                               Exam
                            </button>
                        </p>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                @if (isset($resultQuiz) && count($resultQuiz)>0)
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-center">Quiz Result</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 offset-2">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</td>
                                                            <th scope="col">Result</td>
                                                            <th scope="col">Date Taken</td>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $ctr=1;
                                                    @endphp
                                                    <tbody>
                                                        @foreach ($resultQuiz as $item)
                                                            <tr>
                                                                <td>{{$ctr}}</td>
                                                                <td>{{$item->eval_result}}/{{$item->total_points}}</td>
                                                                <td>{{\Carbon\Carbon::parse($item->created_at)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        No Availabel Quiz Result/s
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="collapse" id="collapseExample1">
                            <div class="card card-body">
                                @if (isset($resultExam) && count($resultExam)>0)
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-center">Exam Result</h4>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8 offset-2">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">#</td>
                                                            <th scope="col">Result</td>
                                                            <th scope="col">Date Taken</td>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $ctr=1;
                                                    @endphp
                                                    <tbody>
                                                        @foreach ($resultExam as $item)
                                                            <tr>
                                                                <td>{{$ctr}}</td>
                                                                <td>{{$item->eval_result}}/{{$item->total_points}}</td>
                                                                <td>{{\Carbon\Carbon::parse($item->created_at)}}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        No Available Exam Result/s
                                    </div>
                                @endif
                            </div>
                        </div>
                
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
