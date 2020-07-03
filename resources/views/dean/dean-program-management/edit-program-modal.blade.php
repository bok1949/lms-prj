<!-- The Modal For Editing Individual Course -->
<div class="modal fade" id="myModalEdit" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            @foreach ($specificProgram as $progInfo)
            
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Editing {{$progInfo->program_description}}</h4>
                <a href="#" id="closeEditModal" class="close" data-dismiss="modal">×</a>
            </div>
            <form action="{{route('submitprogramedit')}}" method="POST">
                {{ csrf_field() }}
                <!-- Modal body -->
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="">Program Code:</label>
                        <input type="text" name="program_code" class="form-control" id="" value="{{$progInfo->program_code}}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Program Description:</label>
                        <input type="text" name="program_desc" class="form-control" id="" value="{{$progInfo->program_description}}" required>
                    </div>
                    <input type="hidden" name="program_id" value="{{$progInfo->program_id}}">
                    
                    
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




