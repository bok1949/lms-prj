
<div class="container-fluid mb-4 bg-white">
    <div class="row">
        <div class="col-md-12">
            {{-- <div class="mb-2">
                <div class="mt-1 mb-1 ml-2"><a href="{{route('deanlevelusermgmt', 'student')}}"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back</a></div>
            </div> --}}
            <div class="border-bottom border-success">
                <h5 class="text-center">Create Student Account</h5>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-4">
            <form action="{{route('uploadstudentlist')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-header">
                        Upload Students in Excel Format <a href="#">{{-- <i class="fa fa-question-circle" aria-hidden="true"></i> --}}</a>
                    </div>
                    <div class="card-body">
                        @isset($departments)
                            @foreach ($departments as $dept)
                                @php
                                    $deptid=$dept->dept_id;
                                @endphp
                            @endforeach
                            <div class="form-group">
                                <input type="hidden" name="dept_id" value="{{$deptid}}">
                                <input type="file" name="upload_students" required id="" class="form-control-file" >
                            </div>
                        @endisset
                    </div>
                    <div class="card-footer">   
                        <button type="submit" name="uploadStudents" class="btn btn-success form-control"><i class="fa fa-upload" aria-hidden="true"></i> Upload Students</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="">
                <form action="{{route('createstudent')}}" method="POST">
                    {{ csrf_field() }}
                        
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 ">
                            <small class="form-text text-danger"><strong>NOTE:</strong> Fields with askterisks are required.</small>
                        </div> 
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="studid">Student ID:*</label>
                                <input type="number" class="form-control" name="student_id" required id="" pattern="" placeholder="ID Number...">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="yearlevel">Year Level*</label>
                                <select name="yearlevel" id="" class="form-control" required>
                                    <option value="">--Select Year Level--</option>
                                    <option value="1st">1st Year</option>
                                    <option value="2nd">2nd Year</option>
                                    <option value="3rd">3rd Year</option>
                                    <option value="4th">4th Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname">Last Name:*</label>
                                <input type="text" name="lastname" required class="form-control" id="" placeholder="Last Name...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstname">First Name:*</label>
                                <input type="text" name="firstname" required class="form-control" id="" placeholder="First Name...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="middlename">Middle Name:</label>
                                <input type="text" name="middlename" class="form-control" id="" placeholder="Middle Name...">
                            </div>  
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="extensionname">Extension Name:</label>
                                <input type="text" name="ext_name" class="form-control" id="" placeholder="Extension Name...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if (count($programs) > 0)
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="program">Program*</label>
                                            <select name="program_id" id="" class="form-control" required>
                                            <option value="">--Select a Program--</option>
                                            @foreach ($programs as $prog)
                                                <option value="{{$prog->program_id}}">{{$prog->program_description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-12">{{-- $pmgmt == 'addprogram-form' --}}
                                    <div class="alert alert-warning text-center">
                                        <strong>Warning!</strong> No Programs has been created yet. You need to create PROGRAMS before adding student.
                                        <br><a href="{{route('programmgmt',['pmgmt'=>'addprogram-form'])}}"> <i class="fa fa-plus-circle" aria-hidden="true"></i> Create Programs</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="college">Department:</label>
                                @foreach ($departments as $dept)
                                    <input type="text" name="dept"  class="form-control" value="{{$dept->dept_description}}" readonly>
                                    <input type="hidden" name="dept_id" value="{{$dept->dept_id}}" readonly>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>{{-- end of form container --}}
                @if (count($programs)>0)
                    <button type="submit" class="btn btn-primary col-md-6 offset-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
                @endif
                
                </form>
            </div>{{-- end of col-md-12 --}}
        </div>{{-- end of col-md-8 offset-2 --}}

    </div>{{-- row --}} 
</div>
<br>
<br>