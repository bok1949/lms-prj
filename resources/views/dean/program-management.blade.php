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
                            <a class="nav-link border border-primary" href="{{route('programmgmt')}}"> <i class="fa fa-book mr-2" aria-hidden="true"></i> Program Management</a>
                        </li>
                        <li class="nav-item mb-4">
                            <a class="nav-link active" href="{{route('coursemgmt')}}"><i class="fa fa-file mr-2"></i> Course Management</a>
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
                        <h4 class="border-bottom">Course Management</h4>
                    </div>
                </div>
                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('deandashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    @if (isset($pmgmt))
                                        @if ($pmgmt == 'addprogram-form')
                                        <li class="breadcrumb-item active"><a href="{{route('programmgmt')}}"><i class="fa fa-list-ol" aria-hidden="true"></i> List of Programs</a></li>  
                                        <li class="breadcrumb-item active"><i class="fa fa-plus" aria-hidden="true"></i> Adding of Programs</li> 
                                        @endif
                                    @else
                                        <li class="breadcrumb-item active"><i class="fa fa-list-ol" aria-hidden="true"></i> List of Programs</li> 
                                    @endif
                                    
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row">
                    <div class="col-md-12">
                        @if (!isset($pmgmt))
                            <div class="d-inline-flex mb-2">
                                <a href="{{route('programmgmt', 'addprogram-form')}}" class="btn btn-primary ml-2"><i class="fa fa-plus" aria-hidden="true"></i> Add Program</a>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-12">
                        @if (isset($pmgmt))
                            @if ($pmgmt == 'addprogram-form')
                                @include('dean.dean-program-management.add-program-form')
                            @endif
                        @endif
                        @if (isset($programs))
                            @if (count($programs)>0)
                                <table class="table table-custom">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Program Code</th>
                                            <th>Program Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;    
                                        @endphp
                                        @foreach ($programs as $prog)
                                            <tr>
                                                <td>{{$counter}}</td>
                                                <td>{{$prog->program_code}}</td>
                                                <td>{{$prog->program_description}}</td>
                                                <td>
                                                    {{-- <a href="{{route('deanlevelusermgmt', ['deanusers'=>'instructor','id'=>$emp->people_id])}}" data-toggle="tooltip" title="Edit Credentials"><i class="fa fa-pencil" aria-hidden="true"></i></a>  --}}
                                                    <a href="{{route('programmgmt', ['pmgmt'=>'edit', 'programid'=>$prog->program_id])}}" data-toggle="tooltip" title="Edit Credentials"><i class="fa fa-pencil" aria-hidden="true"></i></a> 
                                                </td>
                                            </tr>
                                            @php
                                                $counter++;    
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <div>
                                    {{$programs->links()}}
                                </div>
                            @else
                                <div class="col-md-12">
                                    <div class="alert alert-warning text-center">
                                        <strong>Warning!</strong> <br>No Programs has been created yet. You need to create PROGRAMS before adding student.
                                    </div>
                                </div>
                            @endif
                        @endif
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
            $("#myModalEdit").modal('show');
            $('#closeEditModal').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            });
            $('#submitEdit').on('click',function(){
                window.location.href="{{route('programmgmt')}}";
            });
        });
            
    </script>
@endsection