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
                @if (isset($getCollegeInfo) && count($getCollegeInfo)>0)
                    @foreach ($getCollegeInfo as $gci)
                        @php
                            $deptid     =$gci->dept_id;
                            $deptcode   =$gci->dept_code;
                            $deptdesc   =$gci->dept_description;
                        @endphp
                    @endforeach
                @endif
                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('sadashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    <li class="breadcrumb-item active"><a href="{{route('admincm')}}"><i class="fa fa-list-ol" aria-hidden="true"></i> List of Colleges</a></li> 
                                    <li class="breadcrumb-item active"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editing {{$deptcode}}</li> 
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row">
                    <div class="col-md-12 border-bottom border-success">
                        <h4 class="text-center">Edit Department</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{route('saveeditcollege')}}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="dept_id" value="{{$deptid}}">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="" class="mt-2">Department Code:*</label>
                                            <input type="text" class="form-control" name="dept_code" required value="{{$deptcode}}" placeholder="Department Code...">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="" class="mt-2">Department Description:*</label>
                                            <input type="text" class="form-control" name="dept_desc" required value="{{$deptdesc}}" placeholder="Department Code...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 offset-3">
                                        <button type="submit" class="btn btn-primary form-control"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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