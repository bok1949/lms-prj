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
                    <h3 class="text-center border-bottom border-secondary p-2"><a href="" class="text-reset text-decoration-none"><i class="fa fa-tachometer" aria-hidden="true"></i> {{$deptCode}}</a></h3>
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
                        <h4>Evaluation Management</h4>
                        <hr>
                        <div class="mb-2">
                            <div class="listofcourses text-center"><h5 class="pt-2 ">List of Courses</h5></div> &nbsp;&nbsp;&nbsp;&nbsp; 
                        </div>
                        <table class="table table-custom">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>SC_ID</th>
                                    <th>Course Code</th>
                                    <th>Course Description</th>
                                    <th>Schedule</th>
                                    <th># of Students</th>
                                    <th>Create Evaluation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>1909135</td>
                                    <td>IT301</td>
                                    <td>Web Development 2</td>
                                    <td>11:30-12:30/6:00-7:30-M-W/T-Th</td>
                                    <td>10</td>
                                    <td><a href="{{route('cevc')}}"><i class="fa fa-file-text" aria-hidden="true"></i></a></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>1909124</td>
                                    <td>IT201</td>
                                    <td>Data Structure and Algorithms</td>
                                    <td>9:30-10:30/1:30-3:00-M-W/T-Th</td>
                                    <td>0</td>
                                    <td><a href="{{route('cevc')}}"><i class="fa fa-file-text" aria-hidden="true"></i></a></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>1801312</td>
                                    <td>AE21</td>
                                    <td>IT Application Tools in Business</td>
                                    <td>4:30-6:00/T-Th</td>
                                    <td>0</td>
                                   <td><a href="{{route('cevc')}}"><i class="fa fa-file-text" aria-hidden="true"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                           
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


