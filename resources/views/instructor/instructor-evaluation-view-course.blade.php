@extends('layouts.app')

@section('title')
    Instructor
@endsection

@section('header')
    @include('../layouts/header')
@endsection
@if (isset($useraccount))
    @foreach ($useraccount as $ua)
        @php
            $deptCode=$ua->dept_code;
        @endphp
    @endforeach
@endif
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 bg-faded" id="">
            <div class="row">
                <div class="col-md-3 position-fixed mt-2" id="sticky-sidebar">
                    <h3 class="text-center border-bottom border-secondary p-2"><a href="{{route('instructordashboard')}}" class="text-reset text-decoration-none"><i class="fa fa-tachometer" aria-hidden="true"></i> {{$deptCode}} Instructor</a></h3>
                    <ul class="nav flex-column nav-pills text-left p-4">
                        <li class="nav-item mb-4">
                            <a class="nav-link border border-primary" href="{{route('cm')}}"> <i class="fa fa-file mr-2"></i> Class Management</a>
                        </li>
                    {{--  <li class="nav-item mb-4">
                            <a class="nav-link active" href="{{route('studmgmt')}}"> <i class="fa fa-users mr-2" aria-hidden="true"></i> Student Management</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="{{route('evalmgmt')}}"><i class="fa fa-book mr-2" aria-hidden="true"></i> Evaluation Management</a>
                        </li> --}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9 bg-white">
            <div class="container-fluid mt-2">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Evaluation Management</h4>
                        <hr>
                        <div class="mb-3 shadow-sm p-3 mb-2 bg-white rounded p-2">
                            <h3 class="pt-2 text-center">Web Development 2</h3>
                        </div>
                        <div class="mt-1 mb-1"><a href="{{route('evalmgmt')}}"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back</a></div>
                        <div class="d-inline-flex mb-2">
                            <div class="">
                                <a href="{{route('ce')}}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Create Evaluation</a>
                            </div>
                        </div>
                        <hr>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection


@section('footer')
    @include('../layouts/footer')
@endsection


