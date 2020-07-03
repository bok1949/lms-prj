<div class="container-fluid mb-4 bg-white">
    <div class="row">
        <div class="col-md-12">
            {{-- <div class="mb-2">
                <div class="mt-1 mb-1 ml-2"><a href="{{route('deanlevelusermgmt', 'instructor')}}"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i> Back</a></div>
            </div> --}}
            <div class="border-bottom border-success">
                <h5 class="text-center">Create Instructor Account</h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="col-md-12">
                <form action="{{route('createinstructor')}}" method="POST">
                    {{ csrf_field() }}
                <div class="form-group">
                    <small class="form-text text-danger"><strong>NOTE:</strong> Fields with askterisks are required.</small>
                </div>         
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3 text-right "><label for="empid" class="mt-2">Employee ID:*</label></div>
                        <div class="col-md-9 ">
                            <input type="text" class="form-control" name="employee_id" required id="" pattern="[^[0-9]*$]{8,}" placeholder="ID Number...">
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
                </div>
                <div class="form-group">
                    <label for="college">Department:</label>
                    
                    @foreach ($departments as $emp)
                       <input type="text" name="dept"  class="form-control" value="{{$emp->dept_description}}" readonly>
                        <input type="hidden" name="dept_id" value="{{$emp->dept_id}}" readonly>
                    @endforeach
                    
                </div>
                <button type="submit" class="btn btn-primary col-md-6 offset-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
                </form>
            </div>{{-- end of col-md-12 --}}
        </div>{{-- end of col-md-8 offset-2 --}}
    </div>{{-- row --}} 
</div>
<br>
<br>