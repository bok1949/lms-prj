@extends('layouts.app')

@section('title')
    Instructor
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
                    <a class="nav-link active" href="{{route('vmc')}}"> <i class="fa fa-file mr-2"></i> View My Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('vmer')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> View My Evaluation Result</a>
                </li>
            </ul>

        </div>
        <div class="col-md-8">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 ">
                        <h4 class="border-bottom mt-2 text-center">Change Password Setting</h4>
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-md-6 offset-3">
                        <div class="form-group">
                            <label for="sched">Old Password:*</label>
                            <input type="text" class="form-control" id="sched" placeholder="Old Password...">
                        </div>
                    </div>
                    <div class="col-md-6 offset-3">
                        <div class="form-group">
                            <label for="sched">New Password:*</label>
                            <input type="text" class="form-control" id="sched" placeholder="New Password...">
                        </div>
                    </div>
                    <div class="col-md-6 offset-3">
                        <div class="form-group">
                            <label for="sched">Re-type Password:*</label>
                            <input type="text" class="form-control" id="sched" placeholder="Re-type Password...">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary col-md-4 offset-4"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
    @include('../layouts/footer')
@endsection


