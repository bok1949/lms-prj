@extends('layouts.app')

@section('title')
    Dean
@endsection

@section('header')
    @include('../layouts/header')
@endsection

@section('content')

@if (isset($useraccount))
    @foreach ($useraccount as $ua)
        @php
            $deptCode=$ua->dept_code;
        @endphp
    @endforeach
@endif

<div class="container-fluid">
    <div class="row">

        <div class="col-md-3 bg-faded">
            <div class="row">
                <div class="col-md-3 position-fixed mt-2" id="sticky-sidebar">
                    <h3 class="text-center border-bottom border-secondary p-2"><a href="{{route('deandashboard')}}" class="text-reset text-decoration-none"><i class="fa fa-tachometer" aria-hidden="true"></i> {{$deptCode}} Dean</a></h3>

                    <ul class="nav flex-column nav-pills text-left p-4">
                        <li class="nav-item mb-4">
                            <a class="nav-link active" href="{{route('deanlevelusermgmt')}}"><i class="fa fa-users mr-2" aria-hidden="true"></i>  Users Management</a>
                        </li>
                        <li class="nav-item mb-4">
                            <a class="nav-link active" href="{{route('programmgmt')}}"> <i class="fa fa-book mr-2" aria-hidden="true"></i> Program Management</a>
                        </li>
                        <li class="nav-item mb-4">
                            <a class="nav-link border border-primary" href="{{route('coursemgmt')}}"><i class="fa fa-file mr-2"></i> Course Management</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 bg-white">
            <div class="container-fluid mt-2">
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
                                    <li class="breadcrumb-item"><a href="{{route('deandashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    @if (isset($cmgmt))
                                        @if ($cmgmt == 'addcourse-form')
                                        <li class="breadcrumb-item active"><a href="{{route('coursemgmt')}}"><i class="fa fa-list-ol" aria-hidden="true"></i> List of Courses</a></li>  
                                        <li class="breadcrumb-item active"><i class="fa fa-plus" aria-hidden="true"></i> Adding of Courses</li> 
                                        @endif
                                    @else
                                        <li class="breadcrumb-item active"><i class="fa fa-list-ol" aria-hidden="true"></i> List of Courses</li> 
                                    @endif
                                    
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row">
                    <div class="col-md-12">
                        @if (!isset($cmgmt))
                            <div class="d-inline-flex mb-2">
                                {{-- <div class="listofcourses"><h5 class="pt-2 ">List of Courses</h5></div> &nbsp;&nbsp;&nbsp;&nbsp;  --}}
                                <a href="{{route('coursemgmt', 'addcourse-form')}}" class="btn btn-primary ml-2"><i class="fa fa-plus" aria-hidden="true"></i> Add Course</a>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12">
                        @if (isset($cmgmt))
                            @if ($cmgmt == 'addcourse-form')
                                @include('dean.dean-course-management.add-course-form')
                            @endif
                        @endif
                        @if (isset($allCourses))
                            @if (count($allCourses)>0)
                                <table class="table table-custom">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Course Code</th>
                                            <th>Course Description</th>
                                            <th>Program Code</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;    
                                        @endphp
                                        @foreach ($allCourses as $course)
                                            <tr>
                                                <td>{{$counter}}</td>
                                                <td>{{$course->course_code}}</td>
                                                <td>{{$course->descriptive_title}}</td>
                                                <td>{{$course->program_code}}</td>
                                                <td>
                                                    {{-- <a href="{{route('deanlevelusermgmt', ['deanusers'=>'instructor','id'=>$emp->people_id])}}" data-toggle="tooltip" title="Edit Credentials"><i class="fa fa-pencil" aria-hidden="true"></i></a>  --}}
                                                    <a href="{{route('coursemgmt', ['cmgmt'=>'edit', 'courseid'=>$course->course_id])}}" data-toggle="tooltip" title="Edit Credentials"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                                                </td>
                                            </tr>
                                            @php
                                                $counter++;    
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <div>
                                    {{$allCourses->links()}}
                                </div>
                            @else
                                <div class="col-md-12">
                                    <div class="alert alert-warning text-center">
                                        <strong>Warning!</strong> <br>No Course has been created yet. You need to create COURSES first.
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (isset($getSpecificCourse))
    @include('dean.dean-course-management.edit-course-modal')
@endif

@if (\Session::has('createdCourse') || \Session::has('errorCourse'))
<!-- The Modal For Response in Adding Multiple Courses -->
<div class="modal fade" id="responseAMC">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Creating Courses Notification</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">           
                <div class="container">
                    @if (\Session::has('createdCourse'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h5>Courses Created Successfully</h5>
                                <ol>
                                   @foreach (Session::get('createdCourse') as $ccs)
                                        <li>{{ $ccs }}</li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                    @endif 
                    @if (\Session::has('errorCourse'))
                         <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger">
                                    <h5>Courses with duplicate entry</h5>
                                    <ol>
                                        @foreach (Session::get('errorCourse') as $cce)
                                                <li>{{ $cce }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
               
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
<!-- eND OF The Modal  -->
@endif

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
            $("#myModalEdit").modal('show');
            $("#responseAMC").modal('show');
            $('#closeEditModal').on('click',function(){
                window.location.href="{{route('coursemgmt')}}";
            });
            $('#submitEdit').on('click',function(){
                window.location.href="{{route('coursemgmt')}}";
            });
            //let createdCourse
            //console.log("CREATED COURSES "+createdCourse+": ERROR COURSES "+errorCourse)
            /* if(createdCourse.length > 0 || errorCourse.length > 0){
                $("#responseAMC").modal('show');
                for (let index = 0; index < createdCourse.length; index++) {
                    const element = array[index];
                    
                }
            } */
            

        });
            
    </script>
@endsection