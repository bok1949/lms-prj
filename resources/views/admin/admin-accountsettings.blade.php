@extends('layouts.app')

@section('title')
    System Admin
@endsection

@section('header')
    @include('../layouts/header')
@endsection


@section('content')
{{-- @if (isset($useraccount))
    @foreach ($useraccount as $ua)
        @php
            $deptCode=$ua->dept_code;
        @endphp
    @endforeach
@endif --}}
<div class="container-fluid">
    <div class="row">
       
        <div class="col-md-3 bg-faded" id="">
            <div class="row">
                <div class="col-md-3 position-fixed mt-2" id="sticky-sidebar">
                    <h3 class="text-center border-bottom border-secondary p-2"><a href="{{route('sadashboard')}}" class="text-reset text-decoration-none"><i class="fa fa-tachometer" aria-hidden="true"></i> System Admin</a></h3>
                    <ul class="nav flex-column nav-pills text-left p-4">
                        <li class="nav-item mb-4">
                            <a class="nav-link active" href="{{route('admincm')}}"> <i class="fa fa-building" aria-hidden="true"></i> College Management</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('adminUserMgmt')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> User Management</a>
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
                        <h4 class="border-bottom">Change Password</h4>
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-md-8 offset-2 mt-4">
                        <div class="col-md-12">
                            <form action="{{route('submitChangePasswordAdmin')}}" method="POST">
                                {{ csrf_field() }}
                               
                                <div class="container-fluid">
                                    <div class="row form-group">
                                        <label for="oldpassword" class="col-md-4 col-form-label text-md-right">Old Password</label>    
                                        <div class=" col-md-8">
                                            <input type="password" class="form-control @if(session('error')) is-invalid @endif " name="old_password" required value="{{ old('old_password') }}" placeholder="Old Password...">
                                            @if (session('error'))
                                                <span class="text-danger ml-2">
                                                    {{ session('error') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row form-group">
                                        <label for="" class="col-md-4 col-form-label text-md-right">New Password</label>
                                        <div class="col-md-8">
                                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required value="{{ old('new_password') }}" placeholder="New Password...">
                                            @if ($errors->first('new_password'))
                                                <span class="text-danger ml-2">
                                                    {{ $errors->first('new_password') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                   
                                    <div class="row form-group">
                                         <label for="" class="col-md-4 col-form-label text-md-right">Confirm New Password</label>
                                        <div class="col-md-8">
                                            <input type="password" class="form-control @error('confirm_new_password') is-invalid @enderror" name="confirm_new_password" required placeholder="Confirm New Password...">
                                             @if ($errors->first('confirm_new_password'))
                                                <span class="text-danger ml-2">
                                                    {{ $errors->first('confirm_new_password') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="form-group row mb-4">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">Update Password</button>
                                    </div>
                                </div>
                             
                            </form>
                        </div>{{-- end of col-md-12 --}}
                    </div>{{-- end of col-md-8 offset-2 --}}

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
    @include('../layouts/footer')
@endsection


