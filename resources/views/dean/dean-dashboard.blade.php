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
                            <a class="nav-link active" href="{{route('deanlevelusermgmt')}}"><i class="fa fa-users mr-2" aria-hidden="true"></i>  Users Management</a>
                        </li>
                        <li class="nav-item mb-4">
                            <a class="nav-link active" href="{{route('programmgmt')}}"> <i class="fa fa-book mr-2" aria-hidden="true"></i> Program Management</a>
                        </li>
                        <li class="nav-item mb-4">
                            <a class="nav-link active" href="{{route('coursemgmt')}}"><i class="fa fa-file mr-2"></i> Course Management</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            {{-- This is dean Management --}}
        </div>
    </div>
</div>


@endsection

@section('footer')
    @include('../layouts/footer')
@endsection


