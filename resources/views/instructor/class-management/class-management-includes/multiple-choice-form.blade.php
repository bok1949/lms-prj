
 <div class="row">

    <div class="col-md-3">{{-- Card Counting Questions Made --}}
        <div class="card">
            <div class="card-header bg-secondary text-white">Questions Made</div>
            <div class="card-footer">Number of Item/s:  <span id="numitems_mc"></span></div>
            <div class="card-footer">Total Points: <span id="totalpoints_mc"></span></div>
        </div>
    </div>{{-- End of Card Counting Questions Made --}}
    <div class="col-md-9">
    <div class="card">
        <div class="card-header bg-secondary text-white qdeschead">Question <span id="eqctr"></span></div>
        <form action="" class="saveExamQuestionForm multiple-choice">{{-- Form for Multiple Choice --}}
            {{ csrf_field() }}
            <input type="hidden" name="einstruct_id" value="">
            <input type="hidden" name="examtype" value="mc">
            <input type="hidden" name="examtype_ei" value="mcei1">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <textarea name="question_desc_emc" class="form-control qd-mc" placeholder="Create Question..."></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8"><h5 class="text-center">Responses</h5></div>
                    <div class="col-md-4"><h6 class="text-left">Correct Answer</h6></div>
                </div>
                <div class="responsesEMC">
                    <div class="row">
                        <div class="form-group col-md-8">
                            <input type="text" name="response[]" id="1" class="form-control" placeholder="Enter Response...">
                        </div>
                        <div class="form-group col-md-2 mt-2">
                            <input type="checkbox" name="isanswer[]" value="0"  >{{--  checked --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <input type="text" name="response[]" id="2" class="form-control" placeholder="Enter Response...">
                        </div>
                        <div class="form-group col-md-2 mt-2">
                            <input type="checkbox" name="isanswer[]" value="1"  >{{--  checked --}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <input type="text" name="response[]" id="3" class="form-control" placeholder="Enter Response...">
                        </div>
                        <div class="form-group col-md-2 mt-2">
                            <input type="checkbox" name="isanswer[]" value="2"  >{{--  checked --}}
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h5>Scoring</h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="number" min="1" name="points_emc" class="form-control points">
                        </div>
                    </div>
                    <div class="col-md-3 pt-2 ml-n4"><span>point/s</span></div>
                </div>

            </div>{{-- end card body --}}
            
            <div class="card-footer ">
                <div class="form-group col-md-8 offset-2">
                    <button type="submit" name="saveExamMC" class="btn btn-secondary" id="saveExamMC"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                    <button type="button" class="btn btn-secondary" id="addResponseEMC"><i class="fa fa-plus" aria-hidden="true"></i> Add Response</button>
                </div>
            </div>
        </form>
    </div>
    </div>
 </div>
