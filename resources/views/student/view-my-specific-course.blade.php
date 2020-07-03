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

                    <ul class="nav flex-column nav-pills text-center p-4">
                        <li class="nav-item mb-4">
                            <a class="nav-link border border-primary" href="{{route('vmc')}}"> <i class="fa fa-file mr-2"></i> View My Courses</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link active" href="{{route('vmer')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> View My Evaluation Result</a>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 bg-white">
            <div class="container-fluid mt-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3 shadow-sm p-3 mb-2 bg-white rounded p-2">
                            <h3 class="pt-2 text-center">Web Development 2</h3>
                        </div>
                        <div class="mt-1 mb-1"><a href="{{route('cm')}}"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back</a></div>
                        <div class="d-inline-flex mb-2">
                            <div class="">
                                <a href="{{route('vcm')}}" class="btn btn-success mr-3"><i class="fa fa-eye" aria-hidden="true"></i> View Course Materials</a>
                                <a href="{{route('vm-eval-result')}}" class="btn btn-success mr-3"><i class="fa fa-eye" aria-hidden="true"></i> View Evaluation Result</a>
                                <a href="{{route('take-eval')}}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Take Evaluation</a>
                            </div>
                        </div>
                        <hr>

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


