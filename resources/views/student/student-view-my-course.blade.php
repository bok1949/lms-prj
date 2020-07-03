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
        <div class="col-md-9 bg-white">
            <div class="container-fluid mt-2">
                <div class="row">
                    <div class="col-md-12 ">
                        <br>
                        <h4 class="border-bottom pl-2">My Courses</h4>
                    </div>
                </div>
                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('studentdashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    <li class="breadcrumb-item active"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="pt-2 pl-2 text-center">List of my Courses</h5>
                    </div>
                </div>
                <div class="row mt-2">{{-- My Class List --}}
                    <div class="col-md-12">
                        @if (isset($myclasses))
                            @if (count($myclasses) > 0)
                                <table class="table table-custom">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>SC_ID</th>
                                            <th>Course Code</th>
                                            <th>Course Description</th>
                                            <th>Schedule</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($myclasses as $mc)
                                            <tr>
                                                <td>{{$mc->sc_id}}</td>
                                                <td>{{$mc->course_code}}</td>
                                                <td>{{$mc->descriptive_title}}</td>
                                                <td>{{$mc->schedule}}</td>
                                                <td>
                                                    <a href="{{route('vmcinfo')}}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    You are not yet assigned in any classes
                                </div>          
                            @endif
                        @endif                         
                    </div>
                </div>{{-- End of My Class List --}}
            </div>{{-- *******End of My Class Container******* --}}
        </div>
    </div>
</div>

@endsection

@section('footer')
    @include('../layouts/footer')
@endsection


