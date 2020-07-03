@extends('layouts.app')

@section('title')
    System Admin | Program Mgmt.
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
                    <h3 class="text-center border-bottom border-secondary p-2"><a href="{{route('deandashboard')}}" class="text-reset text-decoration-none"><i class="fa fa-tachometer" aria-hidden="true"></i> System Admin</a></h3>

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
                        <h4 class="border-bottom">Department Management</h4>
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
                                    <li class="breadcrumb-item active"><a href="{{route('viewCollegeList',['cm'=>'viewcollegelist'])}}"><i class="fa fa-list-ol" aria-hidden="true"></i> List of Colleges</a></li>
                                    {{-- viewCollegeInfo {{route('viewCollegeInfo',['collegeid'=>$deptid])}} --}}
                                    {{-- @isset($prog_course)  --}}
                                    @if (isset($prog_course))
                                        <li class="breadcrumb-item active"><a href="{{route('viewCollegeInfo',['collegeid'=>$deptid])}}"><i class="fa fa-info" aria-hidden="true"></i> {{$deptcode}} Info.</a></li> 
                                        <li class="breadcrumb-item active"><i class="fa fa-plus" aria-hidden="true"></i> {{ucfirst($prog_course)}}</li> 
                                    @else
                                        <li class="breadcrumb-item active"><i class="fa fa-info" aria-hidden="true"></i> {{$deptcode}} Info.</li> 
                                    @endif
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row mb-2">
                    <div class="col-md-12 border-bottom border-success">
                        <h4 class="text-center">{{$deptdesc}}</h4>
                    </div>
                </div>
                <div class="row border-bottom border-success pb-2">
                    <div class="col-md-12 ">
                        <a href="{{route('viewCollegeInfoPC',['collegeid'=>$deptid,'prog_course'=>'programs'])}}" class="btn {{ (isset($prog_course)&&$prog_course=='programs')?'border border-secondary':'btn-secondary'}}"><i class="fa fa-plus" aria-hidden="true"></i> Create Programs</a>
                       {{--  @if (isset($programs) && count($programs)>0)
                            <a href="{{route('viewCollegeInfoPC',['collegeid'=>$deptid,'prog_course'=>'course'])}}" class="btn {{ (isset($prog_course)&&$prog_course=='course')?'border border-secondary':'btn-secondary'}}"><i class="fa fa-plus" aria-hidden="true"></i> Create Courses</a> 
                        @endif --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if (isset($prog_course) && $prog_course=="programs")
                            @include('admin.admin-program-mgmt.create-programs-form')
                        {{-- @elseif (isset($prog_course) && $prog_course=="course")
                            @include('admin.admin-program-mgmt.create-courses-form') --}}
                        @endif
                        
                        @if(isset($programs))
                            @if(count($programs) > 0)
                            @php
                                $ctr=1;
                            @endphp
                            <table class="table mt-2">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Program Code</th>
                                        <th scope="col">Program Description</th>
                                        {{-- <th scope="col">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($programs as $p)
                                    <tr>
                                        <td>{{$ctr}}</td>
                                        <td>{{$p->program_code}}</td>
                                        <td>{{$p->program_description}}</td>
                                       {{--  <td>
                                            <a href="#" class="editProgram" id="{{$p->program_id}}" 
                                                data-progCode="{{$p->program_code}}" data-progDesc={{$p->program_description}}>
                                                <i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>
                                            {{-- <a href="{{route('viewCollegeInfoPC',['collegeid'=>$deptid,'prog_course'=>'course','progid'=>$p->program_id])}}"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                        </td> --}}
                                    </tr>
                                    @php
                                        $ctr++;
                                    @endphp
                                @endforeach
                                    </tbody>
                                </table>
                                <div class="col-md-10 offset-1">
                                    {{ $programs->links() }}
                                </div>
                            @else
                                <div class="alert alert-warning col-md-8 offset-2 mt-4" role="alert">
                                    No <b>Programs</b> available. <br>Please Create Programs.
                                </div>
                            @endif
                        @endif

                        
                        {{-- @if (isset($courses))
                            @if(count($courses)==0)
                                <div class="alert alert-warning col-md-8 offset-2 mt-4" role="alert">
                                    No <b>Courses</b> available. <br>Please Create Course.
                                </div>
                            @else
                                @php
                                    $ctr=1;
                                @endphp
                                <table class="table mt-2">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Course Code</th>
                                            <th scope="col">Descriptive Title</th>
                                            <th scope="col">Program</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($courses as $c)
                                        <tr>
                                            <td>{{$ctr}}</td>
                                            <td>{{$c->course_code}}</td>
                                            <td>{{$c->descriptive_title}}</td>
                                            <td>{{$c->program_code}}</td>
                                        </tr>
                                        @php
                                            $ctr++;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                                    <div class="col-md-10 offset-1">
                                        {{ $courses->links() }}
                                    </div>
                            @endif
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @if (isset($errors) && count($errors) > 0) --}}
@if (\Session::has('prognotfound'))
<!-- The Modal to show students not yet enrolled -->
<div class="modal fade" id="programNotFound" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Warning!</h4>
                <a href="#" id="programNotFound" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <div class="alert alert-danger">
                    <h5>Program not found.</h5>
                </div>
                <ol>
                @foreach (Session::get('prognotfound') as $sne)
                    <li>{{ $sne }}</li>
                @endforeach
                </ol>
            </div>{{-- end of modal body --}}
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal to show students not yet enrolled -->
@endif


<div class="modal fade" id="editProgram" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Editing Program</h4>
                <a href="#" id="editProgram" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <form action="" class="editProgram">
                <div class="container-fluid">
                    <input type="hidden" name="program_id"value="" >
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Program Code:</label>
                            <input type="text" name="program_code" class="form-control" value="" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Program Description:</label>
                            <input type="text" name="program_desc" class="form-control" value="" >
                        </div>
                    </div>
                </div>
                
            </div>{{-- end of modal body --}}
            <div class="modal-footer">
                <button type="button" id="saveProgramEdit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>{{-- end of modal content --}}
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
            $('.collapse').collapse('toggle');
            $("#programNotFound").modal('show');
            /* $("#myModalEdit").modal('show');
            $('#closeEditModal').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            });
            $('#submitEdit').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            }); */
            $(".editProgram").on('click',function(e){
                e.preventDefault();
                var progrid = $(this).attr('id'); 
                var progCode = $(this).attr('data-progCode'); 
                var progDesc = $(this).attr('data-progDesc'); 
                $("#editProgram").modal('show');
                $("input[name='program_id']").val(progrid);
                $("input[name='program_code']").val(progCode);
                $("input[name='program_desc']").val(progDesc);
            });
        });
            
    </script>
@endsection