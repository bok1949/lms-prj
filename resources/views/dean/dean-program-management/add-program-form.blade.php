<div class="container-fluid mb-4 bg-white">
    <div class="row">
        <div class="col-md-12">
            <div class="border-bottom border-success p-2 mb-4"><h5 class="pt-2 text-center">Create Program</h5></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="col-md-12">
                <form action="{{route('submitprogram')}}" method="POST">
                    {{ csrf_field() }}
                <div class="form-group">
                    <small class="form-text text-danger"><strong>NOTE:</strong> Fields with askterisks are required.</small>
                </div>         
                <div class="container-fluid">
                    <div class="row">
                        <div class="form-group">
                            <label for="progcode" class="mt-2">Program Code:*</label>
                            <input type="text" class="form-control" name="program_code" required id="" placeholder="Program Code...">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="progdesc" class="mt-2">Program Description:*</label>
                            <input type="text" class="form-control" name="program_desc" required id="" placeholder="Program Description...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="college">Department:</label>
                        @foreach ($useraccount as $ua)
                            <input type="text" name="dept"  class="form-control" value="{{$ua->dept_description}}" readonly>
                            <input type="hidden" name="dept_id" value="{{$ua->dept_id}}" readonly>
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