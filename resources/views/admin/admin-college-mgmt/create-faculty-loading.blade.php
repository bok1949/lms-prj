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
                                    <li class="breadcrumb-item active"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Creating {{$deptcode}} Faculty Loadings</li> 
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row">
                    <div class="col-md-12 border-bottom border-success">
                        <h4 class="text-center">Create <strong>{{$deptcode}}</strong> Faculty Loadings</h4>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 offset-3">
                        <form action="{{route('ufloadings')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card">
                            <div class="card-header">
                                Upload Faculty Loadings
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="file" name="faculty_loadings" required class="form-control-file" >
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="upload_load" class="btn btn-secondary form-control"><i class="fa fa-upload" aria-hidden="true"></i> Upload</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="container-fluid mt-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <small class="form-text text-danger ml-4"><strong>NOTE:</strong> Fields with askterisks are required.</small>
                                </div>
                            </div>
                            <form action="{{route('postCreateFacultyLoadings')}}" method="POST">
                                {{ csrf_field() }}
                                <div class="row mt-2">
                                    <div class="col-md-2 offset-2 text-right">
                                        <div class="form-group">
                                            <label for="schedule">Intructor-ID*:</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="number" min="1" name="ins_id" value="{{old('ins_id')}}" class="form-control" required placeholder="Instructor ID...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 offset-2">
                                        <div class="form-group">
                                            <label for="schedule">Schedule*:</label>
                                            <input type="text" name="schedule" value="{{old('schedule')}}" class="form-control" required placeholder="Schedule...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="scid">SC_ID*:</label>
                                            <input type="number" name="sc_id" min="1"  class="form-control" value="{{old('sc_id')}}" required placeholder="SC_ID...">
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-5 offset-2">
                                        <div class="form-group">
                                            <label for="ay">Academic Year*:</label>
                                            <input type="text" name="ay" class="form-control" value="{{old('ay')}}" required placeholder="Academic Year...">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="term">Term*:</label>
                                            <select name="term" id="" class="form-control" required>
                                                <option value="">--Select a Term--</option>
                                                <option value="1st Semester" {{(old('term')=='1st Semester')?'selected':''}}>First Semester</option>
                                                <option value="2nd Semester" {{(old('term')=='2nd Semester')?'selected':''}}>Second Semester</option>
                                                <option value="Short Term" {{(old('term')=='Short Term')?'selected':''}}>Short Term</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row mb-4">
                               
                                    @if (isset($courses) && count($courses)>0)
                                        
                                        <div class="col-md-2 offset-2 text-right">
                                            <label for="course" class="mt-2">Course*</label>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="course_program_id" class="form-control" required>
                                                <option value="">--Select a Course--</option>
                                                @foreach ($courses as $c)
                                                    <option value="{{$c->cp_id}}" {{(old('course_program_id')==$c->cp_id)?'selected':''}}>{{$c->descriptive_title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="alert alert-warning col-md-12" role="alert">
                                            You cannot create Faculty Loading without Course/s Available.
                                        </div>
                                    @endif
                                    
                                </div>
                                @if (isset($courses))
                                   <div class="row mb-4">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary col-md-6 offset-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
                                        </div>
                                    </div> 
                                @endif
                                
                            </form>
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