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
                                    <li class="breadcrumb-item active"><i class="fa fa-list-ol" aria-hidden="true"></i> List of Courses</li> 
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row mb-2  border-bottom border-success">
                    <div class="col-md-12 mb-2">
                        <a href="{{route('viewCollegeList',['cm'=>'addcollege'])}}" class="btn {{(isset($cm)&&$cm=='addcollege')?'border border-secondary':'btn-secondary'}} "><i class="fa fa-plus" aria-hidden="true"></i> Add College</a>
                        <a href="{{route('viewCollegeList',['cm'=>'viewcollegelist'])}}" class="btn {{(isset($cm)&&$cm=='viewcollegelist')?'border border-secondary':'btn-secondary'}}"><i class="fa fa-eye" aria-hidden="true"></i> View Colleges</a>
                        <a href="{{route('viewAllCourses')}}" class="btn border border-secondary"><i class="fa fa-eye" aria-hidden="true"></i> View Courses</a>
                        <a href="{{route('viewAddCourses')}}" class="btn btn-secondary"><i class="fa fa-plus" aria-hidden="true"></i> Add Courses</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if (isset($getAllCourses) && count($getAllCourses)>0)
                            @php
                                $ctr=1;
                            @endphp
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Course Code</th>
                                        <th>Course Description</th>
                                        <th>Lecture Unit</th>
                                        <th>Lab Unit</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($getAllCourses as $item)
                                        <tr>
                                            <td>{{$ctr}}</td>
                                            <td>{{$item->course_code}}</td>
                                            <td>{{$item->descriptive_title}}</td>
                                            <td>{{$item->lec_units}}</td>
                                            <td>{{$item->lab_units}}</td>
                                            <td>
                                                <a href="#" class="editcourse" id="{{$item->course_id}}" 
                                                    data-cc="{{$item->course_code}}" data-dt="{{$item->descriptive_title}}" 
                                                    data-lecu="{{$item->lec_units}}" data-labu="{{$item->lab_units}}">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </a> {{-- |
                                                <a href="#" class="" id="{{$item->course_id}}"><i class="fa fa-trash" aria-hidden="true"></i> </a> --}}
                                            </td>
                                        </tr>
                                        @php
                                            $ctr++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="col-md-12">
                                {{$getAllCourses->links()}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCourse" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Editing Course</h4>
                <a href="#" id="editCourse" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <form action="" class="editCourse">
                <div class="container-fluid">
                    <input type="hidden" name="course_id"value="" >
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Course Code:</label>
                            <input type="text" name="course_code" class="form-control" value="" >
                        </div>
                        <div class="col-md-3">
                            <label for="">Lecture Unit:</label>
                            <input type="text" name="lec_unit" class="form-control" value="" >
                        </div>
                        <div class="col-md-3">
                            <label for="">Lab Unit:</label>
                            <input type="text" name="lab_unit" class="form-control" value="" >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">Course Title:</label>
                            <input type="text" name="course_title" class="form-control" value="" >
                        </div>
                    </div>
                </div>
                
            </div>{{-- end of modal body --}}
            <div class="modal-footer">
                <button type="button" id="saveCourseEdit" class="btn btn-primary">Save changes</button>
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
            /* $("#myModalEdit").modal('show');
            $('#closeEditModal').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            });
            $('#submitEdit').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            }); */

            $(".editcourse").on('click',function(e){
                e.preventDefault();
                $("#editCourse").modal('show');
                var coursecode=$(this).attr('data-cc');
                var coursetitle=$(this).attr('data-dt');
                var lecunit=$(this).attr('data-lecu');
                var labunit=$(this).attr('data-labu');
                var courseid=$(this).attr('id');
                $("input[name='course_code']").val(coursecode);
                $("input[name='lec_unit']").val(lecunit);
                $("input[name='lab_unit']").val(labunit);
                $("input[name='course_title']").val(coursetitle);
                $("input[name='course_id']").val(courseid);
            });
            $("#saveCourseEdit").on('click',function(e){
                e.preventDefault();
                //console.log($('.editCourse').serializeArray());
                if($("input[name='course_code']").val()==""||$("input[name='lec_unit']").val()==""||
                $("input[name='lab_unit']").val()==""||$("input[name='course_title']").val()==""){
                    swal.fire("Warning","Please Fill all fields", "error");
                }
                $.ajax({
                    url: "{{route('updateCourseInfo')}}",
                    type: "POST",
                    data: $('.editCourse').serializeArray()
                }).done(function(response){
                    if(response.success){
                        swal.fire('Done',response.success,'success');
                        $("#editCourse").modal('hide');
                        setTimeout(function() { 
                            location.reload();
                        }, 3000);
                    }
                    if(response.error){
                        swal.fire('Warning',response.error,'error');
                        $("#editCourse").modal('hide');
                        setTimeout(function() { 
                            location.reload();
                        }, 3000);
                    }
                    //console.log(response);
                });
            })

        });
            
    </script>
@endsection