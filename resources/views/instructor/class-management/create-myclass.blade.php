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
                        <br>
                        <h4 class="border-bottom">Course Management</h4>
                    </div>
                </div>
                @if (isset($cpid))

                    <div class="row">{{-- bread crumb --}}
                        <div class="col-md-12">
                            <div class="light-font secondary-color">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb ">
                                        <li class="breadcrumb-item"><a href="{{route('instructordashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                        <li class="breadcrumb-item"><a href="{{route('cm')}}"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes</a></li>
                                        <li class="breadcrumb-item"><a href="{{route('cmCreateClass')}}"><i class="fa fa-file-text" aria-hidden="true"></i> Courses</a></li>
                                        <li class="breadcrumb-item active"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create My Class</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>{{-- end of bread crumb --}}

                    <div class="row">
                        
                        @include('instructor.class-management.class-management-includes.create-specific-class') 
                    </div>
                @endif
                @if(isset($courses))
                    <div class="row">{{-- bread crumb --}}
                        <div class="col-md-12">
                            <div class="light-font secondary-color">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb ">
                                        <li class="breadcrumb-item"><a href="{{route('instructordashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                        <li class="breadcrumb-item"><a href="{{route('cm')}}"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes</a></li>
                                        <li class="breadcrumb-item active"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create My Class</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>{{-- end of bread crumb --}}
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-center">Create My Classes</h5>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <div class="search-container form-group">
                                <form action="">
                                    <input type="text" class="form-control" id="searchCourse" placeholder="Search SC-Code or Descriptive Title...." name="searchCourse">
                                    <button type="submit"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 course-content">{{-- class="course-content" --}}
                            @if (count($courses)>0)
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
                                    <tbody >
                                        @php
                                            $counter = 1;    
                                        @endphp
                                        @foreach ($courses as $course)
                                            <tr>
                                                <td>{{$counter}}</td>
                                                <td>{{$course->course_code}}</td>
                                                <td>{{$course->descriptive_title}}</td>
                                                <td>{{$course->program_code}}</td>
                                                <td>
                                                    <a href="{{route('cmCreateClass',['cpid'=>$course->cp_id])}}" data-toggle="tooltip" title="Create a Class"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create {{$course->cp_id}}</a> 
                                                </td>
                                            </tr>
                                            @php
                                                $counter++;    
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <div>
                                    {{$courses->links()}}
                                </div>
                            @else
                                <div class="col-md-12">
                                    <div class="alert alert-warning text-center">
                                        <strong>Warning!</strong> <br>No Courses available yet. Create a Courses first to continue this action.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
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
           /*  $("#myModalEdit").modal('show');
            $('#closeEditModal').on('click',function(){
                window.location.href="{{route('coursemgmt')}}";
            });
            $('#submitEdit').on('click',function(){
                window.location.href="{{route('coursemgmt')}}";
            }); */
            $('#searchCourse').on('keyup', function(e){
                e.preventDefault();
                
                if($(this).val()==""){
                    window.location.href="{{route('cmCreateClass')}}"
                }
                //console.log($(this).val());
                $.ajax({
                    url: "{{route('serchcourseajax')}}",
                    type: 'post',
                    data: {course:$(this).val()}
                }).done(function(response){
                    $('.course-content').html(response);
                });
            });

        });
            
    </script>
@endsection

