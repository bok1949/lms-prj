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
                            <a class="nav-link active" href="{{route('admincm')}}"><i class="fa fa-building" aria-hidden="true"></i> College Management</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link border border-primary" href="{{route('adminUserMgmt')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> User Management</a>
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
                        <h4 class="border-bottom">Users Management</h4>
                    </div>
                </div>
                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('sadashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    @if (isset($adduser))
                                    <li class="breadcrumb-item"><a href="{{route('adminUserMgmt')}}"><i class="fa fa-users" aria-hidden="true"></i> Show Users</a></li> 
                                        <li class="breadcrumb-item active"><i class="fa fa-plus" aria-hidden="true"></i> Adding Users</li>
                                    @else 
                                        <li class="breadcrumb-item active"><i class="fa fa-users" aria-hidden="true"></i> Users</li> 
                                    @endif
                                    
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>{{-- end of bread crumb --}}
                
                @if (!isset($adduser))
                <div class="row mb-2">
                    <div class="col-md-12">
                        <a href="{{route('addusers',['adduser'=>'adduser'])}}" class="btn btn-info"><i class="fa fa-plus" aria-hidden="true"></i> Create User</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{route('adminusertype', ['usertype'=>'Student'])}}" class="btn {{(isset($usertype) && $usertype=='Student')?'btn-dark':'btn-outline-dark'}}"><i class="fa fa-list" aria-hidden="true"></i> Students</a>
                        <a href="{{route('adminusertype', ['usertype'=>'Instructor'])}}" class="btn {{(isset($usertype) && $usertype=='Instructor')?'btn-dark':'btn-outline-dark'}}"><i class="fa fa-list" aria-hidden="true"></i> Instructors</a>
                        <a href="{{route('adminusertype', ['usertype'=>'Dean'])}}" class="btn {{(isset($usertype) && $usertype=='Dean')?'btn-dark':'btn-outline-dark'}}"><i class="fa fa-list" aria-hidden="true"></i> Deans</a>
                        <a href="{{route('adminusertype', ['usertype'=>'Admin'])}}" class="btn {{(isset($usertype) && $usertype=='Admin')?'btn-dark':'btn-outline-dark'}}"><i class="fa fa-list" aria-hidden="true"></i> Admin</a>
                    </div>
                </div>
                @endif
                @isset($adduser)
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center border-bottom border-success">Creating Users</h4>
                    </div>
                </div>
                @endisset
                <div class="row">
                    <div class="col-md-10 offset-1">
                        @isset($adduser)
                            @include('admin.admin-user-mgmt.admin-user-add-form')
                        @endisset
                        @isset($users)
                            @if (count($users) > 0)
                                <h4 class="mt-4 text-center">{{$usertype}}</h4>
                                <table class="table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">ID-Number</th>
                                            <th scope="col">Last Name</th>
                                            <th scope="col">First Name</th>
                                            @if ($usertype=='Student')
                                                <th>Program</th>    
                                            @endif
                                            @if ($usertype!='Admin')
                                                <th scope="col">Department</th>
                                            @endif
                                            
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $u)
                                            <tr>    
                                                <td>{{$u->id_number}}</td>
                                                <td>{{$u->last_name}}</td>
                                                <td>{{$u->first_name}}</td>
                                                @if ($usertype=='Student')
                                                <td>{{$u->program_code}}</td>    
                                                @endif
                                                @if ($usertype!='Admin')
                                                    <td>{{$u->dept_code}}</td>
                                                @endif
                                                
                                                <td>
                                                    <a href="" class="reset-useraccount" id="{{$u->id}}" data-idnum="{{$u->id_number}}" data-name="{{$u->first_name." ".$u->last_name}}" data-toggle="tooltip" title="Reset Password and Username"><i class="fa fa-repeat" aria-hidden="true"></i> </a> |
                                                    @if($u->ua_status==1)
                                                        <a href="#" class="btn btn-sm btn-success btn-xs enableDisable" id="{{$u->id}}" data-uastatus="1" data-toggle="tooltip" title="Deactivate Account" ><i class="fa fa-toggle-on" aria-hidden="true"></i></a> 
                                                    @elseif($u->ua_status==0)
                                                        <a href="#" class="btn btn-sm btn-danger btn-xs enableDisable" id="{{$u->id}}" data-uastatus="0" data-toggle="tooltip" title="Activate Account" ><i class="fa fa-toggle-off" aria-hidden="true"></i></a> 
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{$users->links()}}
                            @else
                                <div class="alert alert-warning mt-4 text-center">
                                    <h5>Warning!</h5>
                                    <p>No Available <strong>{{$usertype}}</strong> User-Type.</p>
                                </div>
                            @endif
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal to Reset User Account Credentials -->
<div class="modal fade" id="warningModalResetAccount" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Warning <i class="fa fa-exclamation" aria-hidden="true"></i> </h4>
                <a href="#" id="warningModalResetAccount" class="close" data-dismiss="modal">×</a>
            </div>
               
            <!-- Modal body -->
            <div class="modal-body">
                You are about to reset <strong><span id="studentName"></span></strong> password and username.
                <br>Are you sure you want to continue this action?
            </div>{{-- end of modal body --}}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" id="continueResetAccount" class="btn btn-danger continue-exam" {{-- data-dismiss="modal" --}}> Continue</button>
                <button type="button" id="cancel" class="btn btn-primary" data-dismiss="modal"> Cancel</button>
            </div>
               
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- End of The Modal to remove student in a class -->


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
            $("#studentDuplicate").modal('show');
            $("#programdontexist").modal('show');

            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
            /* $("#myModalEdit").modal('show');
            $('#closeEditModal').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            });
            $('#submitEdit').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            }); */
            let uid='';
            let idnum='';
            let name='';
            $(".reset-useraccount").on('click',function(e){
                e.preventDefault();
                console.log($(this).attr('data-name'));
                //console.log('User Id='+$(this).attr('id') + " ID Number="+$(this).attr('data-idnum'));
                uid = $(this).attr('id');
                idnum = $(this).attr('data-idnum');
                name = $(this).attr('data-name');
                $("#warningModalResetAccount").modal('show');
                $("#studentName").text(name);
            });

            $("#continueResetAccount").on('click',function(e){
                e.preventDefault();
                $.ajax({
                    url: "{{route('resetUserCredentials')}}",
                    type: "POST",
                    data: {uid:uid,idnum:idnum}
                }).done(function(response){
                    //console.log(response);
                    if(response.success){
                        swal.fire("Done", response.success, 'success');
                        setTimeout(function() { 
                                location.reload();
                                }, 3000);
                    }
                    if(response.error){
                        swal.fire("Warning", response.error, 'error');
                        setTimeout(function() { 
                                location.reload();
                                }, 3000);
                    }
                });
            });

            $(".enableDisable").on('click',function(e){
                e.preventDefault();
                console.log("User-ID="+$(this).attr('id')+" status="+$(this).attr('data-uastatus'));
                let uid=$(this).attr('id');
                let status=$(this).attr('data-uastatus');
                $.ajax({
                    url:"{{route('adUserAccount')}}",
                    type:"POST",
                    data:{uid:uid,status:status}
                }).done(function(response){
                    if(response.success){
                        swal.fire("Done", response.success, 'success');
                        setTimeout(function() { 
                                location.reload();
                                }, 3000);
                    }
                    if(response.error){
                        swal.fire("Warning", response.error, 'error');
                        setTimeout(function() { 
                                location.reload();
                                }, 3000);
                    }
                });
            });


        });
            
    </script>
@endsection