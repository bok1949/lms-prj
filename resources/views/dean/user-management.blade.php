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
                            <a class="nav-link border-primary" href="{{route('deanlevelusermgmt')}}"><i class="fa fa-users mr-2" aria-hidden="true"></i>  Users Management</a>
                        </li>
                        <li class="nav-item mb-4">
                            <a class="nav-link border active" href="{{route('programmgmt')}}"> <i class="fa fa-book mr-2" aria-hidden="true"></i> Program Management</a>
                        </li>
                        <li class="nav-item mb-4">
                            <a class="nav-link active" href="{{route('coursemgmt')}}"><i class="fa fa-file mr-2"></i> Course Management</a>
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
                        <h4 class="border-bottom">Users Management</h4>
                    </div>
                </div>
                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('deandashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    
                                    @if (isset($students))
                                        <li class="breadcrumb-item active"><a href="{{route('deanlevelusermgmt')}}"><i class="fa fa-users" aria-hidden="true"></i> Show Users</a></li>
                                        <li class="breadcrumb-item active"><i class="fa fa-list-ol" aria-hidden="true"></i> Students</li>  
                                    @elseif(isset($employees))
                                        <li class="breadcrumb-item active"><a href="{{route('deanlevelusermgmt')}}"><i class="fa fa-users" aria-hidden="true"></i> Show Users</a></li>
                                        <li class="breadcrumb-item active"><i class="fa fa-list-ol" aria-hidden="true"></i> Employees</li>  
                                    @elseif (isset($addinstructor)){{-- {{route('deanlevelusermgmt', 'instructor')}} --}}
                                        <li class="breadcrumb-item active"><a href="{{route('deanlevelusermgmt')}}"><i class="fa fa-users" aria-hidden="true"></i> Show Users</a></li>
                                        <li class="breadcrumb-item active"><a href="{{route('deanlevelusermgmt','instructor')}}"><i class="fa fa-list-ol" aria-hidden="true"></i> Employees</a></li>
                                        <li class="breadcrumb-item active"><i class="fa fa-plus" aria-hidden="true"></i> Adding Employees</li> 
                                    @elseif(isset($addstudent))
                                        <li class="breadcrumb-item active"><a href="{{route('deanlevelusermgmt')}}"><i class="fa fa-users" aria-hidden="true"></i> Show Users</a></li>
                                        <li class="breadcrumb-item active"><a href="{{route('deanlevelusermgmt','student')}}"><i class="fa fa-list-ol" aria-hidden="true"></i> Students</a></li>
                                        <li class="breadcrumb-item active"><i class="fa fa-plus" aria-hidden="true"></i> Adding Students</li> 
                                    @else
                                        <li class="breadcrumb-item active"><i class="fa fa-users" aria-hidden="true"></i> Show Users</li>
                                    @endif
                                                                        
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <ul class="nav">
                                <li class="nav-item">
                                    {{-- @if (isset($deanusers) && $deanusers='student')
                                        <a class="nav-link btn border border-secondary" href="{{route('deanlevelusermgmt',  ['deanusers'=>'student'])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Students</a>
                                    @else
                                        <a class="nav-link btn btn-secondary" href="{{route('deanlevelusermgmt', ['deanusers'=>'student'])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Students</a>
                                    @endif --}}
                                    <a class="nav-link btn {{isset($students)||isset($addstudent)?'border border-secondary':'btn-secondary'}} " href="{{route('deanlevelusermgmt', ['deanusers'=>'student'])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Students</a>
                                </li>
                                <li class="nav-item ml-2">
                                    <a class="nav-link btn {{isset($employees)||isset($addinstructor)?'border border-secondary':'btn-secondary'}} " href="{{route('deanlevelusermgmt', ['deanusers'=>'instructor'])}}"><i class="fa fa-eye" aria-hidden="true"></i> View Faculty Members</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                </div>{{-- end of row --}}
                <div class="row">
                   {{--  @if (isset($deanusers))
                        @if ($deanusers == 'instructor')
                            @include('dean.dean-users-mgmt-includes.instructor-list')
                        @elseif($deanusers == 'student')
                            @include('dean.dean-users-mgmt-includes.student-list')
                        @elseif($deanusers == 'addinstructor-form')
                            @include('dean.dean-users-mgmt-includes.add-instructor-user')
                        @elseif($deanusers == 'addstudent-form')
                            @include('dean.dean-users-mgmt-includes.add-student-user')
                        @else
                            <div class="alert alert-warning">
                                <strong>Warning</strong> Page not found
                            </div>
                        @endif
                        
                    @endif --}}
                    @if (isset($employees))
                        @include('dean.dean-users-mgmt-includes.instructor-list')
                    @endif
                    @if (isset($students))
                        @include('dean.dean-users-mgmt-includes.student-list')
                    @endif
                    @if (isset($addinstructor))
                        @include('dean.dean-users-mgmt-includes.add-instructor-user')
                    @endif
                    @if (isset($addstudent))
                        @include('dean.dean-users-mgmt-includes.add-student-user')
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if (\Session::has('studExist'))
<!-- The Modal to show students already has account -->
<div class="modal fade" id="showstudExist" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Warning!</h4>
                <a href="#" id="closeModalstudExist" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <div class="alert alert-danger">
                    <h5>The following Students are in the system already. Remove them from the list to create student account successful.</h5>
                </div>
                <ol>
                @foreach (Session::get('studExist') as $sne)
                    <li>{{ $sne }}</li>
                @endforeach
                </ol>
            </div>{{-- end of modal body --}}
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal to show students already has account -->
@endif

@if (\Session::has('programNotFound'))
<!-- The Modal to show program not found -->
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
                    <h5>The following PROGRAMS do not exist in the System.</h5>
                </div>
                <ol>
                @foreach (Session::get('programNotFound') as $pnf)
                    <li>{{ $pnf }}</li>
                @endforeach
                </ol>
            </div>{{-- end of modal body --}}
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal to show students not yet enrolled -->
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
            $("#showstudExist").modal('show');
            $("#programNotFound").modal('show');
            $("#myModalEdit").modal('show');
            
            $('#closeEditModal').on('click',function(){
                window.location.href="{{route('deanlevelusermgmt', ['deanusers'=>'instructor'])}}";
            });
            $('#submitEdit').on('click',function(){
                window.location.href="{{route('deanlevelusermgmt', ['deanusers'=>'instructor'])}}";
            });
            $('#closeEditModalStudent').on('click',function(){
                window.location.href="{{route('deanlevelusermgmt', ['deanusers'=>'student'])}}";
            });
            $('#submitEditStudent').on('click',function(){
                window.location.href="{{route('deanlevelusermgmt', ['deanusers'=>'student'])}}";
            });
            /* Reset Instructor User Account */
            $(".reset-insaccount").on('click',function(a){
                a.preventDefault();
                var id = $(this).attr('id');
                var pid = $(this).attr('data-pid');
                $.ajax({
                    url: "{{route('resetAccount')}}",
                    type: "POST",
                    data: {id:id, pid:pid}
                }).done(function(res){
                    swal.fire('Done',"<strong>"+res.fname+" "+res.lname+"</strong> "+ res.success, 'success');
                });
            });
            /* Enable Disable Instructor User Account */
            $(".enableDisable").on('click',function(a){
                a.preventDefault();
                //console.log($(this).attr('id'));
                $.ajax({
                    url: "{{route('deactivateAccount')}}",
                    type: "POST",
                    data: {id:$(this).attr('id'), uastatus:$(this).attr('data-uastatus')}
                }).done(function(response){
                    //console.log("From controller"+response.success+"--"+response.fname+"--"+response.lname+"--"+response.uastatus);
                    
                    if(response.uastatus==0){
                        $('.enableDisable').removeClass('btn-success');
                        $('.enableDisable').addClass('btn-danger');
                    }
                    if(response.uastatus==1){
                        $('.enableDisable').removeClass('btn-danger');
                        $('.enableDisable').addClass('btn-success');
                    }
                    swal.fire('Done', "<strong>"+response.fname+" "+response.lname+"</strong>" + response.success, 'success');
                });
            });
        });
    </script>
@endsection


