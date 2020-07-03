<!-- The Modal For Editing Individual Course -->
<div class="modal fade" id="myModalEdit" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @foreach ($getStudInfo as $studInfo)
            
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Editing {{$studInfo->last_name}}, {{$studInfo->first_name}}</h4>
                <a href="#" id="closeEditModalStudent" class="close" data-dismiss="modal">×</a>
            </div>
            <form action="{{route('sendUpdateStudent')}}" method="POST">
                {{ csrf_field() }}
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                             <div class="form-group col-md-7">
                                <label for="">Student Id-Number:*</label>
                                <input type="text" name="student_id" class="form-control" id="" value="{{$studInfo->id_number}}" required>
                            </div>
                            <div class="form-group col-md-5">
                                <label for="yearlevel">Year Level:*</label>
                                <select name="yearlevel" id="" class="form-control" required>
                                    <option value="{{$studInfo->year_level}}">{{$studInfo->year_level}} Year</option>
                                    <option value="1st">1st Year</option>
                                    <option value="2nd">2nd Year</option>
                                    <option value="3rd">3rd Year</option>
                                    <option value="4th">4th Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="">Last Name:*</label>
                                <input type="text" name="lastname" class="form-control" id="" value="{{$studInfo->last_name}}" required>
                            </div>
                            <input type="hidden" name="people_id" value="{{$studInfo->people_id}}">
                            <input type="hidden" name="stud_id" value="{{$studInfo->stud_id}}">
                            <div class="form-group col-md-3">
                                <label for="">First Name:*</label>
                                <input type="text" name="firstname" class="form-control" id="" value="{{$studInfo->first_name}}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="">Middle Name:</label>
                                <input type="text" name="middlename" class="form-control" id="" value="{{$studInfo->middle_name}}">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="">Extension Name:</label>
                                <input type="text" name="ext_name" class="form-control" id="" value="{{$studInfo->ext_name}}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="program">Program:*</label>
                                    <select name="program_id" id="" class="form-control" required>
                                    <option value="{{$studInfo->program_id}}">{{$studInfo->program_description}}</option>
                                    @foreach ($programs as $prog)
                                        <option value="{{$prog->program_id}}">{{$prog->program_description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    

                   
                    
                </div>{{-- end of modal body --}}

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" id="submitEditStudent" class="btn btn-info" {{-- data-dismiss="modal" --}}><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Save</button>
                </div>
            </form>
            @endforeach
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal For Editting Course Individually -->




