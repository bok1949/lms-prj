@extends('layouts.app')

@section('title')
    Instructor
@endsection

@section('header')
    @include('../layouts/header')
@endsection

@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        {{-- <div class="col-md-3 custom-side-menu"> --}}
        <div class="col-md-3 position-fixed" id="sticky-sidebar">

            <h3 class="text-center p-4">Menu</h3>

            <ul class="nav flex-column nav-pills text-center p-4">
                <li class="nav-item mb-4">
                    <a class="nav-link active" href="{{route('cm')}}"> <i class="fa fa-file mr-2"></i> Course Management</a>
                </li>
                {{-- <li class="nav-item mb-4">
                    <a class="nav-link active" href="{{route('studmgmt')}}"> <i class="fa fa-users mr-2" aria-hidden="true"></i> Student Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('evalmgmt')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> Evaluation Management</a>
                </li> --}}
            </ul>

        </div>
        <div class="col-md-9">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Courses Management</h4>
                        <hr>
                        <div class="mb-3 shadow-sm p-3 mb-2 bg-white rounded p-2">
                            <h3 class="pt-2 text-center">Web Development 2</h3>
                        </div>
                        <div class="mt-1 mb-1"><a href="{{route('viewcourse')}}"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back</a></div>
                        <div class="d-inline-flex mb-2">
                            <div class="">
                                <a href="{{route('vcm')}}" class="btn btn-success mr-3"><i class="fa fa-eye" aria-hidden="true"></i> View Course Materials</a>
                                <a href="{{route('veras')}}" class="btn btn-success mr-3"><i class="fa fa-eye" aria-hidden="true"></i> View Evaluation Result</a>
                                <a href="" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Create Evaluation</a>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <h4>Results on Different types of Evaluation</h4>
                            <hr>
                            <ul class="nav justify-content-center">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{route('veras-result', ['quiz'])}}">Quizzes </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('veras-result', ['assign'])}}">Assignments</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('veras-result', ['exam'])}}">Exams</a>
                                </li>
                            </ul>
                            <hr>
                        </div>
                        <div>
                            @if(isset($type))
                                @if ($type == 'quiz')
                                    <h5 class="text-center">Quizzes</h5>
                                    <div>
                                        @include('instructor/instruct-includes/quizzess-result')
                                    </div>
                                @elseif($type == 'assign')
                                    <h5 class="text-center">Assignments</h5>
                                     <div>
                                        @include('instructor/instruct-includes/assignment-result')
                                    </div>
                                @elseif($type == 'exam')
                                    <h5 class="text-center">Exams</h5>
                                     <div>
                                        @include('instructor/instruct-includes/exams-result')
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection


@section('footer')
    @include('../layouts/footer')
@endsection


