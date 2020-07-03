<div class="container-fluid mt-2">
    <div class="row">
        <div class="col-md-12">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Student</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Instructor</a>
                    <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Dean</a>
                    <a class="nav-item nav-link" id="nav-admin-tab" data-toggle="tab" href="#nav-admin" role="tab" aria-controls="nav-admin" aria-selected="false">Admin</a>
                </div>
            </nav>
            <div class="tab-content tab-content-custom" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="row">
                        <div class="col-md-10 offset-1 mt-2 border-bottom border-success">
                            <form action="{{route('postAddMultiStudent')}}" method="POST" enctype="multipart/form-data" class="form-inline mb-2">
                                {{ csrf_field() }}
                                <div class="custom-file col-md-8">
                                    <input type="file" name="add_studlist" required class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">Choose file of Student List</label>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="add_students" class="btn btn-secondary mr-2"><i class="fa fa-upload" aria-hidden="true"></i> Upload Students</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-10 offset-1 mb-2">
                            <form action="{{route('postAddStudent')}}" method="POST">
                                {{ csrf_field() }}
                            <p><small class="form-text text-danger ml-2 mb-2"><strong>NOTE:</strong> Fields with asterisks are required.</small></p>
                            <div class="form-group">
                                <label for="">Student Id-Number*:</label>
                                <input type="number" name="stud_idnumber" value="{{old('stud_idnumber')}}" min="1" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Last Name*:</label>
                                        <input type="text" name="lastname" class="form-control" value="{{old('lastname')}}" required>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">First Name*:</label>
                                        <input type="text" name="firstname" class="form-control" value="{{old('firstname')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Middle Name:</label>
                                        <input type="text" name="middlename" class="form-control" value="{{old('middlename')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Extension Name:</label>
                                        <input type="text" name="ext_name" class="form-control" value="{{old('ext_name')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="mt-2">Program:*</label>
                                            <select name="program_id" class="form-control" required>
                                            <option value="">--Select Program--</option>
                                            @foreach ($programs as $sprog)
                                                <option value="{{$sprog->program_id}}">{{$sprog->program_code}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 offset-2">
                                    <button type="submit" id="submitAdd" class="btn btn-info form-control"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Create</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    
                    <div class="row">
                        <div class="col-md-10 offset-1 mt-2 border-bottom border-success">
                            <form action="{{route('postAddMultiInstructor')}}" method="POST" enctype="multipart/form-data" class="form-inline mb-2">
                                {{ csrf_field() }}
                                <div class="custom-file col-md-8">
                                    <input type="file" name="add_instructor_list" required class="custom-file-input" id="customFile">
                                    <label class="custom-file-label" for="customFile">Choose file of Instructor List</label>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" name="add_isntructor" class="btn btn-secondary mr-2"><i class="fa fa-upload" aria-hidden="true"></i> Upload Instructor</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-10 offset-1 mb-2">
                            <form action="{{route('createInstructorAccount')}}" method="POST">
                                {{ csrf_field() }}
                            <p><small class="form-text text-danger ml-2 mb-2"><strong>NOTE:</strong> Fields with asterisks are required.</small></p>
                            <div class="form-group">
                                <label for="">Instructor Id-Number*:</label>
                                <input type="number" name="ins_idnumber" value="{{old('ins_idnumber')}}" min="1" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Last Name*:</label>
                                        <input type="text" name="lastname" class="form-control" value="{{old('lastname')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">First Name*:</label>
                                        <input type="text" name="firstname" class="form-control" value="{{old('firstname')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Middle Name:</label>
                                        <input type="text" name="middlename" class="form-control" value="{{old('middlename')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Extension Name:</label>
                                        <input type="text" name="ext_name" class="form-control" value="{{old('ext_name')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="" class="mt-2">College:*</label>
                                            <select name="college_id" class="form-control" required>
                                            <option value="">--Select College--</option>
                                            @foreach ($department as $dept)
                                                <option value="{{$dept->dept_id}}">{{$dept->dept_description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 offset-2">
                                    <button type="submit" id="submitAdd" class="btn btn-info form-control"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Create</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                    <div class="row">
                        <div class="col-md-10 offset-1 mb-2">
                            <form action="{{route('createDeanAccount')}}" method="POST">
                                {{ csrf_field() }}
                            <p><small class="form-text text-danger ml-2 mb-2"><strong>NOTE:</strong> Fields with asterisks are required.</small></p>
                            <div class="form-group">
                                <label for="">Dean Id-Number*:</label>
                                <input type="number" name="dean_idnumber" value="{{old('dean_idnumber')}}" min="1" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Last Name*:</label>
                                        <input type="text" name="lastname" class="form-control" value="{{old('lastname')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">First Name*:</label>
                                        <input type="text" name="firstname" class="form-control" value="{{old('firstname')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Middle Name:</label>
                                        <input type="text" name="middlename" class="form-control" value="{{old('middlename')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Extension Name:</label>
                                        <input type="text" name="ext_name" class="form-control" value="{{old('ext_name')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="" class="mt-2">College:*</label>
                                            <select name="college_id" class="form-control" required>
                                            <option value="">--Select College--</option>
                                            @foreach ($department as $dept)
                                                <option value="{{$dept->dept_id}}">{{$dept->dept_description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 offset-2">
                                    <button type="submit" id="submitAdd" class="btn btn-info form-control"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Create</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
                <div class="tab-pane fade" id="nav-admin" role="tabpanel" aria-labelledby="nav-admin-tab">
                    
                    <div class="row">
                        <div class="col-md-10 offset-1 mb-2">
                            <form action="{{route('createAdminAccount')}}" method="POST">
                                {{ csrf_field() }}
                            <p><small class="form-text text-danger ml-2 mb-2"><strong>NOTE:</strong> Fields with asterisks are required.</small></p>
                            <div class="form-group">
                                <label for="">Admin Id-Number*:</label>
                                <input type="number" name="admin_idnumber" value="{{old('admin_idnumber')}}" min="1" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Last Name*:</label>
                                        <input type="text" name="lastname" class="form-control" value="{{old('lastname')}}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">First Name*:</label>
                                        <input type="text" name="firstname" class="form-control" value="{{old('firstname')}}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Middle Name:</label>
                                        <input type="text" name="middlename" class="form-control" value="{{old('middlename')}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Extension Name:</label>
                                        <input type="text" name="ext_name" class="form-control" value="{{old('ext_name')}}">
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="" class="mt-2">College:*</label>
                                            <select name="college_id" class="form-control" required>
                                            <option value="">--Select College--</option>
                                            @foreach ($department as $dept)
                                                <option value="{{$dept->dept_id}}">{{$dept->dept_description}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row">
                                <div class="col-md-8 offset-2">
                                    <button type="submit" id="submitAdd" class="btn btn-info form-control"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Create</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@if (\Session::has('studentsexist'))
<!-- The Modal to show duplicate student -->
<div class="modal fade" id="studentDuplicate" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Warning!</h4>
                <a href="#" id="studentDuplicate" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <div class="alert alert-danger">
                    <h5>The list of STUDENTS below was already added.</h5>
                </div>
                <ol>
                @foreach (Session::get('studentsexist') as $sne)
                    <li>{{ $sne }}</li>
                @endforeach
                </ol>
            </div>{{-- end of modal body --}}
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal to show duplicate students -->
@endif

@if (\Session::has('programdontexist'))
<!-- The Modal to show programs dont exist -->
<div class="modal fade" id="programdontexist" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Warning!</h4>
                <a href="#" id="programdontexist" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <div class="alert alert-danger">
                    <h5>The list of PROGRAMS below do not exist in the System.</h5>
                </div>
                <ol>
                @foreach (Session::get('programdontexist') as $sne)
                    <li>{{ $sne }}</li>
                @endforeach
                </ol>
            </div>{{-- end of modal body --}}
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal to show programs dont exits -->
@endif



@if (\Session::has('userexist'))
<!-- The Modal to show duplicate users -->
<div class="modal fade" id="studentDuplicate" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Warning!</h4>
                <a href="#" id="studentDuplicate" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <div class="alert alert-danger">
                    <h5>The list of INSTRUCTORS below was already added.</h5>
                </div>
                <ol>
                @foreach (Session::get('userexist') as $sne)
                    <li>{{ $sne }}</li>
                @endforeach
                </ol>
            </div>{{-- end of modal body --}}
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal to show duplicate users -->
@endif

@if (\Session::has('deptdontexist'))
<!-- The Modal to show colleges dont exist -->
<div class="modal fade" id="programdontexist" data-keyboard="false" data-show="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
           
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Warning!</h4>
                <a href="#" id="programdontexist" class="close" data-dismiss="modal">×</a>
            </div>
            
            <div class="modal-body ">
                <div class="alert alert-danger">
                    <h5>The list of COLLEGES below do not exist in the System.</h5>
                </div>
                <ol>
                @foreach (Session::get('deptdontexist') as $sne)
                    <li>{{ $sne }}</li>
                @endforeach
                </ol>
            </div>{{-- end of modal body --}}
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal to show colleges dont exist -->
@endif




