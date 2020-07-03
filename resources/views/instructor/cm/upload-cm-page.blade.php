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
                                    <li class="breadcrumb-item"><a href="{{route('cm')}}"><i class="fa fa-files-o" aria-hidden="true"></i> My Classes</a></li>
                                    <li class="breadcrumb-item active"><i class="fa fa-file-word-o" aria-hidden="true"></i> Upload Course Materials</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>{{-- end of bread crumb --}}
                @foreach ($courseProgramOffers as $cpo)
                    @php
                        $cpoid          = $cpo->cpo_id;
                        $coursecode     = $cpo->course_code;
                        $coursetitle    = $cpo->descriptive_title;
                        $sched          = $cpo->schedule;
                        $ay             = $cpo->ay;
                        $term           = $cpo->term;
                        $programcode    = $cpo->program_code;
                    @endphp
                @endforeach
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="text-center bg-success text-white p-2">Upload Class Materials in <strong>{{$coursecode}}</strong> <span class="small font-italic">({{$coursetitle}}).</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="ml-4"><span class="text-dark"><i class="fa fa-calendar" aria-hidden="true"></i> <strong>Class Schedule</strong></span> <i class="fa fa-caret-right" aria-hidden="true"></i> <span class="text-dark">{{$sched}}</span></p>
                    </div>
                </div>
                <div class="row">{{-- Evaluation Type Nav Bar --}}
                    <div class="col-md-12">{{-- class="course-content" --}}
                        <div class="card col-md-8 offset-2">
                            <div class="card-body">
                                <form action="{{route('postCourseMaterials')}}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="cpoid" value="{{$cpoid}}">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text" name="file_desc" class="form-control" placeholder="Description of File...">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="file" name="coursematerials" id="" class="form-control-file border" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 offset-3">
                                            <button type="submit" name="create" class="btn btn-secondary">Create</button>
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>{{-- End of Evaluation Type Nav Bar --}}
                <div class="row mt-2 mb-4">
                    @if (isset($getfiles))
                        @if (count($getfiles))
                            <table class="table mylistofclass-tbl">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Course Management Description</th>
                                        <th>File</th>
                                        <th>Created-At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach ($getfiles as $gf)
                                        <tr>
                                            <td>{{$gf->cm_description}}</td>
                                            <td><img src="/storage/coursematerials/{{$gf->cm_files}}" alt="">{{$gf->cm_files}}</td>
                                            <td>{{\Carbon\Carbon::parse($gf->created_at)->diffForhumans()}}</td>
                                            <td> <a href="#" class="remove-file" id="{{$gf->cm_id}}"><i class="fa fa-trash" aria-hidden="true"></i> </a> |
                                            <a href="{{route('downLoadCourseMaterials',['file'=>$gf->cm_files])}}" class="" id="{{$gf->cm_files}}"><i class="fa fa-download" aria-hidden="true"></i> </a> </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<!-- The Modal Submiting Quiz Details Blank -->
<div class="modal fade" id="warningModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Warning <i class="fa fa-exclamation" aria-hidden="true"></i> </h4>
                <a href="#" id="closeWarning" class="close" data-dismiss="modal">×</a>
            </div>
               
            <!-- Modal body -->
            <div class="modal-body">
                Are you sure you want to continue this action?
            </div>{{-- end of modal body --}}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" id="continueFile" class="btn btn-danger continue-exam" {{-- data-dismiss="modal" --}}> Continue</button>
                <button type="button" id="cancel" class="btn btn-primary" data-dismiss="modal"> Cancel</button>
            </div>
               
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal Submiting Quiz Details Blank -->


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
            $('.remove-file').on('click',function(e){
                e.preventDefault();
                var cmid = $(this).attr('id');
                $("#warningModal").modal('show');
                $('#continueFile').on('click',function(e){
                    e.preventDefault();
                    //console.log('send to delete');
                    $.ajax({
                        url:"{{route('removeCourseMaterials')}}",
                        type: "post",
                        data: {cmid:cmid}
                    }).done(function(response){
                        console.log(response);
                        if(response.removed){
                            swal.fire("Done", response.removed, 'success'); 
                            setTimeout(function() { 
                                location.reload();
                                }, 3000);
                        }
                        if(response.wrong){
                            swal.fire("Warning", response.wrong, 'error');
                        }
                        $("#warningModal").modal('hide');
                    });
                });
            }); 
            
        });
             
    </script>
@endsection

