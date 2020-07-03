@extends('layouts.app')

@section('title')
    Instructor
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
                    </div>
                </div>
                
                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('instructordashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{route('cm')}}"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes</a></li>
                                    <li class="breadcrumb-item active"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Editing My Class</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>{{-- end of bread crumb --}}
                @foreach ($findCpoId as $cpo)
                    @php
                        $courseTitle=$cpo->descriptive_title;
                        $courseCode=$cpo->course_code;
                        $programCode=$cpo->program_code;
                        $lecunit = $cpo->lec_units;
                        $labunit = $cpo->lab_units;
                        $sched = $cpo->schedule;
                        $ay = $cpo->ay;
                        $term = $cpo->term;
                        $scid = $cpo->sc_id;
                        $cpoid = $cpo->cpo_id;
                    @endphp
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-center bg-success text-white p-2">Edit My Class <strong>{{$courseCode}}</strong> <span class="small font-italic">({{$courseTitle}})</span></h5>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="ml-4">Information to Edit</h4>
                                <small class="form-text text-danger ml-4"><strong>NOTE:</strong> Fields with askterisks are required.</small>
                            </div>
                        </div>
                        
                        <form action="{{route('posteditmyclass')}}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="cpo_id" value="{{$cpoid}}">
                            <div class="row mt-3">
                                <div class="col-md-5 offset-2">
                                    <div class="form-group">
                                        <label for="schedule">Schedule*:</label>
                                        <input type="text" name="schedule" class="form-control" value="{{$sched}}" required placeholder="Schedule...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="scid">SC_ID*:</label>
                                        <input type="number" name="sc_id" class="form-control" value="{{$scid}}" required placeholder="SC_ID...">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 offset-2">
                                    <div class="form-group">
                                        <label for="ay">Academic Year*:</label>
                                        <input type="text" name="ay" class="form-control" value="{{$ay}}" required placeholder="Academic Year...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="term">Term*:</label>
                                        <select name="term" id="" class="form-control" required>
                                            <option value="{{$term}}">{{$term}}</option>
                                            <option value="1st Semester">First Semester</option>
                                            <option value="2nd Semester">Second Semester</option>
                                            <option value="Short Term">Short Term</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary col-md-6 offset-3"><i class="fa fa-paper-plane" aria-hidden="true"></i> Submit</button>
                                </div>
                            </div>
                        </form>
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


@section('javascripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function(){
           
        });
            
    </script>
@endsection

