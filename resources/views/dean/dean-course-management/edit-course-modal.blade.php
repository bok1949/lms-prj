<!-- The Modal For Editing Individual Course -->
<div class="modal fade" id="myModalEdit" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            @foreach ($getSpecificCourse as $courseInfo)
            
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Editing {{$courseInfo->descriptive_title}}</h4>
                <a href="#" id="closeEditModal" class="close" data-dismiss="modal">×</a>
            </div>
            <form action="{{route('submitcourseedit')}}" method="POST">
                {{ csrf_field() }}
                <!-- Modal body -->
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="">Course Code:</label>
                        <input type="text" name="course_code" class="form-control" id="" value="{{$courseInfo->course_code}}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Course Title:</label>
                        <input type="text" name="course_desc" class="form-control" id="" value="{{$courseInfo->descriptive_title}}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Lecture Units:</label>
                        <input type="number" name="lec_unit" class="form-control" id="" value="{{$courseInfo->lab_units}}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Laboratory Units:</label>
                        <input type="number" name="lab_unit" class="form-control" id="" value="{{$courseInfo->lec_units}}" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="" class="mt-2">Program:*</label>
                        <select name="program_id" id="" class="form-control">
                            <option value="{{$courseInfo->program_id}}">{{$courseInfo->program_code}}</option>
                            @foreach ($programsList as $prog)
                                <option value="{{$prog->program_id}}">{{$prog->program_code}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="course_id" value="{{$courseInfo->course_id}}">
                    <input type="hidden" name="cp_id" value="{{$courseInfo->cp_id}}">
                    
                    
                </div>{{-- end of modal body --}}

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" id="submitEdit" class="btn btn-info" {{-- data-dismiss="modal" --}}><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Save</button>
                </div>
            </form>
            @endforeach
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal For Editting Course Individually -->




