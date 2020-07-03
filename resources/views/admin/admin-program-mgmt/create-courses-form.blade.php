@isset($findProgramId)
    @foreach ($findProgramId as $item)
        @php
            $programid=$item->program_id;
            $programcode=$item->program_code;
            $programdesc=$item->program_description;
        @endphp
    @endforeach


<div class="container-fluid mt-2">
    <div class="row bg-light mb-2">
        <div class="col-md-12">
            <h5 class="pt-2 text-center">Create Major Courses Only on {{$programcode}}</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <form action="{{route('uploadCourses')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-header">
                        Upload Courses in Excel Format <a href="#">{{-- <i class="fa fa-question-circle" aria-hidden="true"></i> --}}</a>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" name="dept_id" value="{{$deptid}}">
                            <input type="hidden" name="program_id" value="{{$programid}}">
                            <input type="file" name="upload_courses" required id="" class="form-control-file" >
                        </div>
                    </div>
                    <div class="card-footer">   
                        <button type="submit" name="uploadCourses" class="btn btn-success form-control"><i class="fa fa-upload" aria-hidden="true"></i> Upload</button>
                    </div>
                </div>
            </form>
{{-- @if ($errors->any())
<div class="alert alert-danger">
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif --}}
        </div>
        <div class="col-md-8">
            <form action="{{route('createcourses')}}" method="POST">
                {{ csrf_field() }}
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="dept_id" value="{{$deptid}}">
                               
                                <input type="hidden" name="program_id" value="{{$programid}}">
                                
                                <small class="form-text text-danger"><strong>NOTE:</strong> Fields with askterisks are required.</small>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3 text-right offset-1">
                            <label for="progcode" class="mt-2 ">Course Code:*</label>
                        </div>
                        <div class="form-group col-md-7 ">
                            <input type="text" class="form-control" name="course_code" value="{{ old('course_code') }}" id="" placeholder="Course Code...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="progdesc" class="mt-2">Course Title:*</label>
                            <input type="text" class="form-control" name="course_desc" value="{{ old('course_desc') }}" required id="" placeholder="Course Title...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="" class="mt-2">Lecture Unit:*</label>
                            <input type="number" class="form-control" name="lec_unit" value="{{ old('lec_unit') }}" required min="1" placeholder="Lecture Unit...">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="mt-2">Laboratory Unit:*</label>
                            <input type="number" class="form-control" name="lab_unit" min="0" value="{{ old('lab_unit') }}" required  placeholder="Laboratory Unit...">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary col-md-6 offset-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
            </form>
        </div>
    </div>
</div>
@endisset