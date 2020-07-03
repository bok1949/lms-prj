<div class="container-fluid mb-4 bg-white">

    <div class="row">
        <form action="{{route('uploadCourses')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="card">
                <div class="card-header">
                    Upload Courses
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <input type="file" name="upload_courses" id="file_upload_courses" class="form-control-file" required>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group">
                        <button type="submit" name="upload_course_file" id="upload_course_file" class="btn btn-secondary form-control"><i class="fa fa-upload" aria-hidden="true"></i> Upload</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="p-2 mb-4 border-bottom border-success"><h5 class="pt-2 text-center">Create Course</h5></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="col-md-12">
                <form action="{{route('submitcreatecourse')}}" method="POST">
                    {{ csrf_field() }}
                <div class="form-group">
                    <small class="form-text text-danger"><strong>NOTE:</strong> Fields with askterisks are required.</small>
                </div>         
                <div class="container-fluid">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="" class="mt-2">Course Code:*</label>
                            <input type="text" class="form-control" name="course_code" required id="" placeholder="Course Code...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="" class="mt-2">Course Description:*</label>
                            <input type="text" class="form-control" name="course_desc" required id="" placeholder="Course Description...">
                        </div>
                    </div>
                    {{-- programs --}}
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="" class="mt-2">Program:*</label>
                            <select name="program_id" id="" class="form-control">
                                <option value="">--Select a Program--</option>
                                @foreach ($programs as $prog)
                                    <option value="{{$prog->program_id}}">{{$prog->program_code}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="mt-2">Lecture Unit:*</label>
                            <input type="number" class="form-control" name="lec_unit" required id=""  placeholder="Lecture Unit...">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="mt-2">Laboratory Unit:*</label>
                            <input type="number" class="form-control" name="lab_unit" required id=""  placeholder="Laboratory Unit...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="college">Department:</label>
                        @foreach ($useraccount as $ua)
                            <input type="text" name="dept"  class="form-control" value="{{$ua->dept_description}}" readonly>
                        @endforeach
                        
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary col-md-6 offset-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
                </form>
            </div>{{-- end of col-md-12 --}}
        </div>{{-- end of col-md-8 offset-2 --}}
    </div>{{-- row --}} 
</div>
<br>
<br>