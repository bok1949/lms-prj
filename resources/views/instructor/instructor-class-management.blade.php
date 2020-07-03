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
                    </div>
                </div>
                <div class="row">{{-- bread crumb --}}
                    <div class="col-md-12">
                        <div class="light-font secondary-color">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb ">
                                    <li class="breadcrumb-item"><a href="{{route('instructordashboard')}}"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                                    <li class="breadcrumb-item active"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes</li>
                                </ol>
                            </nav>
                        </div>
                        
                    </div>
                </div>{{-- end of bread crumb --}}
                <div class="row mb-2">
                    <div class="col-md-9"><div class="listofcourses"><h5 class="pt-2 text-center">My Classes</h5></div></div>
                    {{-- <div class="col-md-3"><a href="{{route('cmCreateClass')}}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Create a Class</a></div> --}}
                </div>
                
                <div class="row">{{-- List of Classes --}}
                    <div class="col-md-10 offset-1">
                        @if (isset($myClasses))
                            @if (count($myClasses)>0)
                                <table class="table mylistofclass-tbl">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>SC_ID</th>
                                            <th>Course Code</th>
                                            <th>Course Description</th>
                                            <th>Schedule</th>
                                            {{-- <th># of Students</th> --}}
                                            <th>AY</th>
                                            <th>Term</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm">
                                        @foreach ($myClasses as $myc)
                                            <tr>
                                                <td>{{$myc->sc_id}}</td>
                                                <td>{{$myc->course_code}}</td>
                                                <td>{{$myc->descriptive_title}}</td>
                                                <td>{{$myc->schedule}}</td>
                                                <td>{{$myc->ay}}</td>
                                                <td>{{$myc->term}}</td>
                                                <td>

                                                    <!-- Example single danger button -->
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info text-white btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="{{route('enrollStudents',['cpoid_enroll'=>$myc->cpo_id])}}"><i class="fa fa-plus-circle" aria-hidden="true"></i> Enroll Students</a>
                                                            <a class="dropdown-item" href="{{route('uploadCM',['cpoid'=>$myc->cpo_id])}}"><i class="fa fa-upload" aria-hidden="true"></i> Upload Materials</a>
                                                            <a class="dropdown-item" href="{{route('createEvalInClass',['cpoid_ceic'=>$myc->cpo_id])}}"><i class="fa fa-tasks" aria-hidden="true"></i> Create Evaluation</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a class="dropdown-item" href="{{route('editmyclass', ['cpoid_edit'=>$myc->cpo_id])}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                                            <a class="dropdown-item" href="{{route('viewMyClass', ['cpoid_ceic'=>$myc->cpo_id])}}"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                                                            <a class="dropdown-item" href="#" id="removeMyClass"><i class="fa fa-minus-circle"  aria-hidden="true"></i> Remove</a>
                                                        </div>{{-- //viewMyClass =>cpoid_ceic --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="col-md-12">
                                    <div class="alert alert-warning text-center">
                                        <strong>Warning!</strong> <br>You don't have classes creted yet.
                                    </div>
                                </div>
                            @endif
                        @endif 
                    </div>
                </div>{{-- End of list of Classes --}}
            </div>
        </div>
    </div>
</div>

<!-- The Modal For Editing Individual Course -->
<div class="modal fade" id="deleteModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-danger">
                <h4 class="modal-title text-white">Warning <i class="fa fa-exclamation" aria-hidden="true"></i> </h4>
                <a href="#" id="closeDeleteModal" class="close" data-dismiss="modal">×</a>
            </div>
                @if (count($myClasses) > 0)
                    <!-- Modal body -->
                    <div class="modal-body">
                        @foreach ($myClasses as $myc)
                            @php
                                $cpoid = $myc->cpo_id;
                            @endphp
                            <p class="deleteMessage">You are about to delete your class <strong>{{$myc->descriptive_title}}</strong>. Do you really want to continue this action?</p>
                        @endforeach
                    </div>{{-- end of modal body --}}

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" id="yesDelete" class="btn btn-danger yesDelete" data-cpoid="{{$cpoid}}" {{-- data-dismiss="modal" --}}> Yes</button>
                        <button type="button" id="cancelDelete" class="btn btn-primary" data-dismiss="modal"> Cancel</button>
                    </div>
                @endif
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal For Editting Course Individually -->


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
           /*  $("#myModalEdit").modal('show');
            $('#cancelDelete').on('click',function(){
                window.location.href="{{route('cm')}}";
            });*/
            $('.yesDelete, #cancelDelete, #closeDeleteModal').on('click',function(){
                window.location.href="{{route('cm')}}";
            });
            $('#removeMyClass').on('click', function(e){
                e.preventDefault();
                //console.log($(this).attr('data-cpoid'));
                $("#deleteModal").modal('show');
               
            });
            $('#yesDelete').on('click', function(a){
                a.preventDefault();
                //console.log($(this).attr('data-cpoid'));
                 $.ajax({
                    url: "{{route('removingclassajax')}}",
                    type: 'post',
                    data: {cpoid:$(this).attr('data-cpoid')}
                }).done(function(response){
                    if(respons.invalid){
                        $('.deleteMessage').text(response.invalid);
                    }
                    swal.fire('Done', response.success, 'success');
                });
            });

        });
            
    </script>
@endsection







