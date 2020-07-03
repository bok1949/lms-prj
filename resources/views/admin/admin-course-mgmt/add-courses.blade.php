@extends('layouts.app')

@section('title')
    System Admin
@endsection

@section('header')
    @include('../layouts/header')
@endsection

@section('content')

<div class="container-fluid">
    <div class="row">

        <div class="col-md-3 bg-faded">
            <div class="row">
                <div class="col-md-3 position-fixed mt-2" id="sticky-sidebar">
                    <h3 class="text-center border-bottom border-secondary p-2"><a href="{{route('sadashboard')}}" class="text-reset text-decoration-none"><i class="fa fa-tachometer" aria-hidden="true"></i> System Admin</a></h3>

                    <ul class="nav flex-column nav-pills text-left p-4">
                        <li class="nav-item mb-4">
                            <a class="nav-link border border-primary" href="{{route('admincm')}}"><i class="fa fa-building" aria-hidden="true"></i> College Management</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('adminUserMgmt')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> User Management</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 bg-white">
            <div class="container-fluid mt-2 mb-4">
                <div class="row">
                    <div class="col-md-12">
                        <br>    
                        <h4 class="border-bottom">Departments Management</h4>
                    </div>
                </div>
                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('sadashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    <li class="breadcrumb-item active"><i class="fa fa-list-ol" aria-hidden="true"></i> Add Gen-Ed Courses</li> 
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row mb-2  border-bottom border-success">
                    <div class="col-md-12 mb-2">
                        <a href="{{route('viewCollegeList',['cm'=>'addcollege'])}}" class="btn {{(isset($cm)&&$cm=='addcollege')?'border border-secondary':'btn-secondary'}} "><i class="fa fa-plus" aria-hidden="true"></i> Add College</a>
                        <a href="{{route('viewCollegeList',['cm'=>'viewcollegelist'])}}" class="btn {{(isset($cm)&&$cm=='viewcollegelist')?'border border-secondary':'btn-secondary'}}"><i class="fa fa-eye" aria-hidden="true"></i> View Colleges</a>
                        <a href="{{route('viewAllCourses')}}" class="btn btn-secondary"><i class="fa fa-eye" aria-hidden="true"></i> View Courses</a>
                        <a href="{{route('viewAddCourses')}}" class="btn border border-secondary "><i class="fa fa-plus" aria-hidden="true"></i> Add Courses</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8 offset-2">
                        <form action="{{route('uploadCoursesAll')}}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="card">
                                <div class="card-header">
                                    Upload Courses in Excel Format <a href="#">{{-- <i class="fa fa-question-circle" aria-hidden="true"></i> --}}</a>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                    <input type="file" name="upload_ge_courses" required id="" class="form-control-file" >
                                    </div>
                                </div>
                                <div class="card-footer">   
                                    <button type="submit" name="uploadCourses" class="btn btn-success form-control"><i class="fa fa-upload" aria-hidden="true"></i> Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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


@if (isset($specificProgram))
    @include('dean.dean-program-management.edit-program-modal')
@endif

@section('javascripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function(){
            $("#responseAMC").modal('show');
            /* $("#courseDuplicate").modal('show');
            $("#emptyCell").modal('show'); */
            /* $("#myModalEdit").modal('show');
            $('#closeEditModal').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            });
            $('#submitEdit').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            }); */
        });
            
    </script>
@endsection