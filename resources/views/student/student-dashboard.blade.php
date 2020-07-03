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
            $id = $ua->id_number;
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
                        <a class="nav-link active" href="{{route('vmc')}}"> <i class="fa fa-file mr-2"></i> View My Courses </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('vmer')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> View My Evaluation Result</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            
        </div>
    </div>
</div>

@endsection

@section('footer')
    @include('../layouts/footer')
@endsection


