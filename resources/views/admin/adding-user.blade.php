@extends('layouts.app')

@section('title')
    System Administrator
@endsection

@section('header')
    @include('../layouts/header')
@endsection

@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3 custom-side-menu">

            <h3 class="text-center p-4">Sidebar Menu</h3>

            <ul class="nav flex-column nav-pills text-center p-4">
                <li class="nav-item mb-4">
                    <a class="nav-link active" href="{{route('collegemgmt')}}"> <i class="fa fa-file mr-2"></i> Colleges Management</a>
                </li>
                <li class="nav-item mb-4">
                    <a class="nav-link active" href="{{route('usersmgmt')}}"> <i class="fa fa-users mr-2" aria-hidden="true"></i> User Management</a>
                </li>
            </ul>

        </div>
        <div class="col-md-9">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Users Management</h4>
                        <hr>
                        <div class="mb-2">
                            <div class="mt-1 mb-1 ml-2"><a href="{{route('usersmgmt')}}"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back</a></div>
                        </div>
                        <div class="shadow-sm p-2 mb-4 bg-white"><h5 class="pt-2 text-center">Creating User</h5></div>
                    </div>
                </div>
                <div class="row">
                    
                        <div class="col-md-8 offset-2">
                            <div class="col-md-12">

                            <div class="form-group">
                                <small class="form-text text-danger">NOTE: Fields with askterisks are required.</small>
                            </div>

                            <div class="form-group">
                                <label for="sel1">User Type:*</label>
                                <select class="form-control" id="sel1">
                                    <option>--Select Type of User--</option>
                                    <option>Admin</option>
                                    <option>Dean</option>
                                </select>
                            </div>
                            <hr>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-3 text-right "><label for="sched" class="mt-2">Employee ID:*</label></div>
                                    <div class="col-md-9 ">
                                        <input type="text" class="form-control" id="sched" placeholder="ID Number...">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sched">Last Name:*</label>
                                            <input type="text" class="form-control" id="sched" placeholder="Last Name...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sched">First Name:*</label>
                                            <input type="text" class="form-control" id="sched" placeholder="First Name...">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sched">Middle Name:</label>
                                            <input type="text" class="form-control" id="sched" placeholder="Middle Name...">
                                        </div>  
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sched">Extension Name:</label>
                                            <input type="text" class="form-control" id="sched" placeholder="Extension Name...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sel1">College Code:</label>
                                <select class="form-control" id="sel1">
                                    <option>--Select College--</option>
                                    <option>COA</option>
                                    <option>CBM</option>
                                    <option>CICS</option>
                                    <option>CCJE</option>
                                    <option>CTE</option>
                                    <option>COT</option>
                                    <option>TTED</option>
                                </select>
                            </div>

                            <button type="button" class="btn btn-primary col-md-6 offset-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>

                        </div>


                    </div>
                </div>{{-- forms --}} 
            </div>
        </div>
    </div>
</div>



@endsection

@section('footer')
    @include('../layouts/footer')
@endsection


