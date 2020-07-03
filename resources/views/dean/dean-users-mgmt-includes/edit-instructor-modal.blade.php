<!-- The Modal For Editing Individual Course -->
<div class="modal fade" id="myModalEdit" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            @foreach ($getEmpInfo as $empInfo)
            
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Editing {{$empInfo->last_name}}, {{$empInfo->first_name}}</h4>
                <a href="#" id="closeEditModal" class="close" data-dismiss="modal">×</a>
            </div>
            <form action="{{route('sendUpdateInstructor')}}" method="POST">
                {{ csrf_field() }}
                <!-- Modal body -->
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="">Employee Id Number:</label>
                        <input type="text" name="eid_num" class="form-control" id="" value="{{$empInfo->id_number}}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Last Name:</label>
                        <input type="text" name="lastname" class="form-control" id="" value="{{$empInfo->last_name}}" required>
                    </div>
                    <input type="hidden" name="people_id" value="{{$empInfo->people_id}}">
                    <div class="form-group">
                        <label for="">First Name:</label>
                        <input type="text" name="firstname" class="form-control" id="" value="{{$empInfo->first_name}}" required>
                    </div>

                    <div class="form-group">
                        <label for="">Middle Name:</label>
                        <input type="text" name="mname" class="form-control" id="" value="{{$empInfo->middle_name}}">
                    </div>

                    <div class="form-group">
                        <label for="">Extension Name:</label>
                        <input type="text" name="xname" class="form-control" id="" value="{{$empInfo->ext_name}}">
                    </div>
                    
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




