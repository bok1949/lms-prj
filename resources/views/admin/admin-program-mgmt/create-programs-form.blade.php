<div class="container-fluid mt-2">
    <div class="row bg-light mb-2">
        <div class="col-md-12">
            <h5 class="pt-2 text-center">Create Programs </h5>
        </div>
    </div>
    <div class="row">
        {{-- <div class="col-md-4">
            <form action="">
                <div class="card">
                    <div class="card-header">
                        Upload Programs in Excel Format <a href="#"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <input type="hidden" name="dept_id" value="{{$deptid}}">
                            <input type="file" name="upload_programs" id="" class="form-control-file" >
                        </div>
                    </div>
                    <div class="card-footer">   
                        <button type="submit" name="uploadPrograms" class="btn btn-success form-control"><i class="fa fa-upload" aria-hidden="true"></i> Upload</button>
                    </div>
                </div>
            </form>
        </div> --}}
        <div class="col-md-8 offset-2">
            <form action="{{route('createprograms')}}" method="POST">
                {{ csrf_field() }}
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden" name="dept_id" value="{{$deptid}}">
                                <small class="form-text text-danger"><strong>NOTE:</strong> Fields with askterisks are required.</small>
                            </div> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-3 text-right offset-1">
                            <label for="progcode" class="mt-2 ">Program Code:*</label>
                        </div>
                        <div class="form-group col-md-7 ">
                            <input type="text" class="form-control" name="program_code" required value="{{ old('program_code') }}" id="" placeholder="Program Code...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="progdesc" class="mt-2">Program Description:*</label>
                            <input type="text" class="form-control" name="program_desc" required value="{{ old('program_desc') }}" placeholder="Program Description...">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary col-md-6 offset-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
            </form>
        </div>
    </div>
</div>