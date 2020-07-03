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
                        <a class="btn btn-secondary ml-4" href="{{route('chooseTypeEvalInClass',['cpoid_ceic'=>$cpoid, 'evaltype'=>'quiz'])}}">Quiz</a>
                        <a class="btn btn-secondary" href="{{route('chooseTypeEvalInClass',['cpoid_ceic'=>$cpoid, 'evaltype'=>'exam'])}}">Exam</a>
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


        });
             
    </script>
@endsection

