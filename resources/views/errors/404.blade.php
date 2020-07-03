
@extends('layouts.app')

@section('title')
    Dean
@endsection

@section('header')
    @include('../layouts/header')
@endsection

@section('content')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12 custom-side-menu">
            <div class="alert alert-warning">
                <strong><h1>WARNING!</h1></strong>
                <h2 class="text-center">{{$exception->getMessage()}} </h2>
                <div class="mt-1 mb-1 ml-2 text-center"><a href="{{route('deandashboard')}}"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back</a></div>
            </div>
            
        </div>
        
    </div>
</div>



@endsection

@section('footer')
    @include('../layouts/footer')
@endsection

@section('javascripts')

@endsection


