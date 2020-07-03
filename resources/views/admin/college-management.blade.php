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
                                    <li class="breadcrumb-item active"><i class="fa fa-list-ol" aria-hidden="true"></i> List of Colleges</li> 
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
                        <a href="{{route('viewAddCourses')}}" class="btn btn-secondary"><i class="fa fa-plus" aria-hidden="true"></i> Add Courses</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if (isset($cm)&& $cm=='addcollege')
                            @include('admin.admin-college-mgmt.create-colleges-form')
                        @endif
                        @if (isset($cm)&& $cm=='viewcollegelist')
                            @if (isset($getColleges))
                                @if (count($getColleges)>0)
                                    <table class="table table-custom col-md-8 offset-2">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Department Code</th>
                                                <th>Department Title</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;    
                                            @endphp
                                            @foreach ($getColleges as $gc)
                                                <tr>
                                                    <td>{{$counter}}</td>
                                                    <td>{{$gc->dept_code}}</td>
                                                    <td>{{$gc->dept_description}}</td>
                                                    <td>
                                                        {{-- <a href="{{route('deanlevelusermgmt', ['deanusers'=>'instructor','id'=>$emp->people_id])}}" data-toggle="tooltip" title="Edit Credentials"><i class="fa fa-pencil" aria-hidden="true"></i></a>  --}}
                                                        {{-- <a href="" data-toggle="tooltip" title="Edit Credentials"><i class="fa fa-pencil" aria-hidden="true"></i></a> |
                                                        <a href="" data-toggle="tooltip" title="Edit Credentials"><i class="fa fa-eye" aria-hidden="true"></i></a>  --}}
                                                            
                                                        <div class="btn-group">
                                                        <button type="button" class="btn btn-success text-white btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu"> 
                                                            <a class="dropdown-item" href="{{route('editcollege',['collegeid'=>$gc->dept_id])}}"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                                                            <a class="dropdown-item" href="{{route('viewCollegeInfo',['collegeid'=>$gc->dept_id])}}"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                                                            {{-- <a class="dropdown-item" href="{{route('createFacultyLoading',['collegeid'=>$gc->dept_id])}}"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create Course Offerings</a> --}}
                                                            <a class="dropdown-item" href="{{route('createFacultyLoading',['collegeid'=>$gc->dept_id])}}"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create Faculty Loading</a>
                                                            
                                                        </div>{{-- //viewMyClass =>cpoid_ceic --}}
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                    $counter++;    
                                                @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div>
                                        {{$getColleges->links()}}
                                    </div>
                                @else
                                    <div class="col-md-12">
                                        <div class="alert alert-warning text-center">
                                            <strong>Warning!</strong> <br>No Colleges/Departments has been created yet.
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif
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