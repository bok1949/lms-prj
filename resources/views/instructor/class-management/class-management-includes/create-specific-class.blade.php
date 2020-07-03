<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 ">
            <h5 class="text-center border-bottom">Create My Class</h5>
        </div>
    </div>
    @if (count($course) > 0)
        <div class="row">
            <div class="col-md-12">     
                {{-- @foreach ($course as $c)
                {{$course->course_code}} <br>
                @endforeach --}}
                <p class="pl-5">Creating a Class in 
                    @foreach ($course as $item)
                        @php
                            $cpid = $item->cp_id
                        @endphp
                        {{$item->course_code}}
                        ({{$item->descriptive_title}}).
                    @endforeach
                </p>
            </div>
        </div>
                @foreach ($useraccount as $eid)
                    @php
                        $empid = $eid->emp_id
                    @endphp
                @endforeach
        <form action="{{route('submitmycreatedclass')}}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="course_id" value="{{$cpid}}">
            <input type="hidden" name="ins_id" value="{{$empid}}">
            <div class="row">
                <div class="col-md-5 offset-2">
                    <div class="form-group">
                        <label for="schedule">Schedule*:</label>
                        <input type="text" name="schedule" class="form-control" required placeholder="Schedule...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="scid">SC_ID*:</label>
                        <input type="number" name="sc_id" class="form-control" required placeholder="SC_ID...">
                    </div>
                </div>
                
            </div>
            <div class="row">
                <div class="col-md-5 offset-2">
                    <div class="form-group">
                        <label for="ay">Academic Year*:</label>
                        <input type="text" name="ay" class="form-control" required placeholder="Academic Year...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="term">Term*:</label>
                        <select name="term" id="" class="form-control" required>
                            <option value="">--Select a Term--</option>
                            <option value="1st Semester">First Semester</option>
                            <option value="2nd Semester">Second Semester</option>
                            <option value="Short Term">Short Term</option>
                        </select>
                    </div>
                </div>
                
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary col-md-6 offset-3"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
                </div>
            </div>
            
                            
        </form>
                {{-- {{$useraccount->emp_id}} --}}
            
        
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-warning text-center">
                    <strong>Warning!</strong> <br>This Course is not available.
                </div>
            </div>
        </div>
    @endif
</div>



