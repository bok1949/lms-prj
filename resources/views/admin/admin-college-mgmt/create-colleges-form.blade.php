<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('postCreateCollege')}}" method="POST">
                {{ csrf_field() }}
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="mt-2">Department Code:*</label>
                                <input type="text" class="form-control" name="dept_code" required placeholder="Department Code...">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="" class="mt-2">Department Description:*</label>
                                <input type="text" class="form-control" name="dept_desc" required placeholder="Department Description...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 offset-3">
                            <button type="submit" class="btn btn-primary form-control"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>