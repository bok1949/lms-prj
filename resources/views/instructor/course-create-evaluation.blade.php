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
                        <br>    
                        <h4 class="border-bottom">Course Management</h4>
                        
                        <div class="mb-3 shadow-sm p-3 mb-2 bg-white rounded p-2">
                            <h3 class="pt-2 text-center">Web Development 2</h3>
                        </div>
                        <div class="mt-1 mb-1"><a href="{{route('viewcourse')}}"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back</a></div>
                        <div class="d-inline-flex mb-2">
                            <div class="">
                                <a href="{{route('vcm')}}" class="btn btn-success mr-3"><i class="fa fa-eye" aria-hidden="true"></i> View Course Materials</a>
                                <a href="{{route('veras')}}" class="btn btn-success mr-3"><i class="fa fa-eye" aria-hidden="true"></i> View Evaluation Result</a>
                                <a href="{{route('ce')}}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Create Evaluation</a>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <h4>Types of Evaluation to Create</h4>
                            <hr>
                            <ul class="nav justify-content-center">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{route('cet', ['quiz'])}}">Quizzes </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('cet', ['assign'])}}">Assignments</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('cet', ['exam'])}}">Exams</a>
                                </li>
                            </ul>
                            <hr>
                        </div>
                        <div>
                            @if(isset($type))
                                @if ($type == 'quiz')
                                    <h5 class="text-center border-bottom border-info pb-2">Quizz</h5>
                                    <div>
                                        @include('instructor/instruct-includes/create-quizzes')
                                    </div>
                                @elseif($type == 'assign')
                                    <h5 class="text-center">Assignments</h5>
                                     <div>
                                        @include('instructor/instruct-includes/create-assignment')
                                    </div>
                                @elseif($type == 'exam')
                                    <h5 class="text-center">Exams</h5>
                                     <div>
                                        @include('instructor/instruct-includes/create-exams')
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- The Modal For Editing Individual Course -->
<div class="modal fade" id="myModalEdit">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Changing the File</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">           
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <form>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">Change Course Material...</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
               
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Change</button>
            </div>

        </div>
    </div>
</div>
<!-- eND OF The Modal For Editting Course Individually -->

<!-- The Modal For Removing Course Confirmation -->
<div class="modal fade" id="myModalDelete">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Removing Course Material</h4>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <h4 class="text-danger text-center">Do you realy want to continue this action?</h4>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i>&nbsp; Cancel</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-check" aria-hidden="true"></i>&nbsp; Ok</button>
            </div>

        </div>
    </div>
</div>
<!-- eND OF The Modal For Removing Course Confirmation -->

@endsection


@section('footer')
    @include('../layouts/footer')
@endsection


