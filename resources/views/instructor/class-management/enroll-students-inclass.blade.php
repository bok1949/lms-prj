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
            <div class="container-fluid mt-2 mb-4">
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
                                    <li class="breadcrumb-item active"><i class="fa fa-users" aria-hidden="true"></i> Enrolling Students</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>{{-- end of bread crumb --}}
                
                @foreach ($getProgramsOffer as $po)
                    @php
                        $cpoid = $po->cpo_id;
                        $coursecode = $po->course_code;
                        $coursetitle = $po->descriptive_title;
                        $sched = $po->schedule;
                        $ay = $po->ay;
                        $term = $po->term;
                        $programcode = $po->program_code;
                    @endphp
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-center bg-success text-white p-2">Enrolling Students in <strong>{{$coursecode}}</strong> <span class="small font-italic">({{$coursetitle}}).</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="ml-4"><span class="text-dark"><i class="fa fa-calendar" aria-hidden="true"></i> <strong>Class Schedule</strong></span> <i class="fa fa-caret-right" aria-hidden="true"></i> <span class="text-dark">{{$sched}}</span></p>
                    </div>
                </div>
                <div class="row mb-2">
                   
                    <div class="col-md-2">
                        <a href="#" class="btn btn-primary" id="addStudent"> <i class="fa fa-plus" aria-hidden="true"></i> Add Student</a>
                    </div>
                    <div class="col-md-8">
                        <form action="{{route('uploadStudentListInClass')}}" method="POST" enctype="multipart/form-data" class="form-inline">
                            {{ csrf_field() }}
                            <input type="hidden" name="cpo_id" value="{{$cpoid}}">
                            <div class="custom-file col-md-8">
                                <input type="file" name="add_studlist" required class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file of Student List</label>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="add_students" class="btn btn-secondary mr-2"><i class="fa fa-upload" aria-hidden="true"></i> Upload Students</button>
                            </div>
                            
                        </form>

                    </div>
                   
                </div>
                <div class="row mb-1">
                    <div class="col-md-8 offset-2">
                        <div class="search-container form-group">
                            <form action="">
                                <input type="text" class="form-control" id="searchStudent" data-cpoid="{{$cpoid}}" placeholder="Search [Student Id, Last Name, or First Name]...." name="searchStudent">
                                <button type="button"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <h5 class="text-center pr-5 pt-2">List of Students</h5>
                    </div>
                </div>
                <div class="row mt-2">
                   <div class="col-md-10 offset-1">
                        <table class="table listofstudents">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID Number</th>
                                    <th>Last Name</th>
                                    <th>First Name</th>
                                    <th>Middle name</th>
                                    <th>Program</th>
                                    <th>Add</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach ($students as $stud)
                                    <tr>
                                        <td>{{$stud->id_number}}</td>
                                        <td>{{$stud->last_name}}</td>
                                        <td>{{$stud->first_name}}</td>
                                        <td>{{$stud->middle_name}}</td>
                                        <td>{{$stud->program_code}}</td>
                                        <td>
                                            <a href="#" data-cpoid="{{$cpoid}}" data-studid="{{$stud->stud_id}}" class="enrollStudent"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add</a>
                                        </td>{{-- Studid={{$stud->stud_id}} CPO-ID={{$cpoid}} --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                   </div>
                </div>
                <div class="row pagination">
                    <div class="col-md-10 offset-1">
                        {{$students->links()}}
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>

<!-- The Modal For Editing Individual Course -->
<div class="modal fade" id="modalAddStudent" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Adding Student</h4>
                <a href="#" id="closeAddStudModal" class="close" data-dismiss="modal">×</a>
            </div>
            <form action="{{route('csa')}}" method="POST">
                {{ csrf_field() }}
                <!-- Modal body -->
                <div class="modal-body">
                    <small class="form-text text-danger ml-2 mb-2"><strong>NOTE:</strong> Fields with asterisks are required.</small>
                    <div class="form-group">
                        <label for="">Student Id-Number*:</label>
                        <input type="number" name="stud_idnumber" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Last Name*:</label>
                        <input type="text" name="lastname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">First Name*:</label>
                        <input type="text" name="firstname" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Middle Name:</label>
                        <input type="text" name="middlename" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Extension Name:</label>
                        <input type="text" name="ext_name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="yearlevel">Year Level*</label>
                        <select name="yearlevel" class="form-control" required>
                            <option value="">--Select Year Level--</option>
                            <option value="1st">1st Year</option>
                            <option value="2nd">2nd Year</option>
                            <option value="3rd">3rd Year</option>
                            <option value="4th">4th Year</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="mt-2">Program:*</label>
                        <select name="program_id" class="form-control">
                            <option value="">--Select Program--</option>
                            @foreach ($programs as $sprog)
                                <option value="{{$sprog->program_id}}">{{$sprog->program_description}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md 12">
                        <p><strong><span class="text-danger">NOTE:</span></strong> <span class="text-muted">The Student Credentials to logged-in is his/her Id-number.</span></p>
                    </div>
                </div>{{-- end of modal body --}}

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" id="submitAdd" class="btn btn-info" {{-- data-dismiss="modal" --}}><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Create</button>
                </div>
            </form>
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal For Adding Student -->

{{-- @if (isset($errors) && count($errors) > 0) --}}
@if (\Session::has('studentNotExist'))
<!-- The Modal to show students not yet enrolled -->
<div class="modal fade" id="showStudNotEnrolled" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Warning!</h4>
                <a href="#" id="closeModalStudNotEnrolled" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <div class="alert alert-danger">
                    <h5>The Following Students are not yet in the System, 
                        add them first in the system bofore you can assign them in your class.</h5>
                </div>
                <ol>
                @foreach (Session::get('studentNotExist') as $sne)
                    <li>{{ $sne }}</li>
                @endforeach
                </ol>
            </div>{{-- end of modal body --}}
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal to show students not yet enrolled -->
@endif

{{-- @if (isset($enrolled) && count($enrolled) > 0) --}}
@if (\Session::has('enrolled'))
@php
    $enroll = Session::get('enrolled');
@endphp
<!-- The Modal to show students already enrolled -->
<div class="modal fade" id="showStudEnrolled" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Warning!</h4>
                <a href="#" id="closeModalStudEnrolled" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <div class="alert alert-danger">
                    <h5>The Following Students are already enrolled in your class.</h5>
                </div>
                <ol>
                @foreach ($enroll as $en)
                    <li>{{ $en}}</li>
                @endforeach
                </ol>
            </div>{{-- end of modal body --}}
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal to show students already enrolled -->



@endif


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

            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });

            $("#showStudNotEnrolled").modal('show');
            $("#showStudEnrolled").modal('show');

            $('#addStudent').on('click', function(a){
                $('#modalAddStudent').modal('show');
            });
            /* Enrolling Student */
            $('.enrollStudent').on('click', function(e){
                e.preventDefault();
                //console.log("Student-id"+$(this).attr('data-studid') + " | CPO-ID"+$(this).attr('data-cpoid'));
                $.ajax({
                    url: "{{route('enrollstudentsAjax')}}",
                    type: "POST",
                    data: {cpoid:$(this).attr('data-cpoid'), studid:$(this).attr('data-studid')}
                }).done(function(response){
                    //console.log(response);
                    if(response.duplicate){
                        //console.log(response.duplicate);
                        swal.fire('Warning', response.duplicate, 'error');
                    }
                    if(response.enrolled){
                        //console.log(response.enrolled);
                        swal.fire('Done', response.enrolled, 'success');
                    }
                });
            });
            /* Searching Students */
            $('#searchStudent').on('keyup',function(e){
                e.preventDefault();
                //console.log($(this).attr('data-cpoid'));
                if($(this).val()==""){
                    window.location.href="{{route('enrollStudents',['cpoid_enroll'=>"+$(this).attr('data-cpoid')+"])}}"
                }
                $.ajax({
                    url: "{{route('findStudentEnrollAjax')}}",
                    type: 'post',
                    data: {searchStud:$(this).val(), cpoid:$(this).attr('data-cpoid')}
                }).done(function(response){
                    if(response.nodata){
                        $('table').html(
                            "<div class='alert alert-warning'>"+
                            "<p><strong>Warning!</strong>"+response.nodata+"</p></div>"
                        );
                    }
                    $('.listofstudents tbody').html(response);
                    $('.pagination').hide();
                });
            });
        });
            
    </script>
@endsection

