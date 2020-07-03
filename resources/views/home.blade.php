@extends('layouts.app')

@section('title')
    LMS Home Page
@endsection

@section('header')
    @include('layouts/header')
@endsection

@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-6 offset-3">
        <!-- Default form login -->
            <form class="text-center p-3" action="{{route('login')}}" method="POST">
                @csrf
                <h4 class="shadow p-3 mb-5 bg-white rounded">KCP - Learning Mangement System</h4>
                <p class="h4 mb-4">Sign in</p>
                <!-- Email -->
                <span class="text-danger mt-0">{{ $errors->first('username') }}</span>
                <input type="text" name="username" id="defaultLoginFormEmail" class="form-control mb-4" placeholder="Username">
                <span class="text-danger mt-0">{{ $errors->first('password') }}</span>
                <!-- Password -->
                <input type="password" name="password" id="defaultLoginFormPassword" class="form-control mb-4" placeholder="Password">
                
                <div class="d-flex justify-content-around">
                    <div>
                        <!-- Forgot password -->
                        {{-- <a href="#" data-toggle="modal" data-target="#fpass">Forgot password?</a> --}}
                    </div>
                </div>
                <!-- Sign in button -->
                <button class="btn btn-info btn-block my-4" type="submit">Sign in</button>
               
            </form>
            <!-- Default form login -->
        </div>
    </div>
</div>


<!-- The Modal For Removing Course Confirmation -->
<div class="modal fade" id="fpass">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Information</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <h4 class="text-info text-center">Approach your Instructor to enrol you in his/her class.</h4>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Ok</button>
            </div>

        </div>
    </div>
</div>
<!-- eND OF The Modal For Removing Course Confirmation -->

@endsection

@section('footer')
    @include('layouts/footer')
@endsection


