<div class="row">
    <div class="col-md-3">{{-- Card Counting Questions Made --}}
        <div class="card">
            <div class="card-header bg-secondary text-white">Questions Made</div>
            <div class="card-footer">Number of Item/s:  <span id="numitems_ar1"></span></div>
            <div class="card-footer">Total Points: <span id="totalpoints_ar1"></span></div>
        </div>
    </div>{{-- End of Card Counting Questions Made --}}
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-secondary text-white qdeschead">Question <span id="eqctr_ar1"></span></div>
            <form action="" class="alternateResponse2">
                <div class="card-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="einstruct_id" value="">
                    <input type="hidden" name="examtype" value="ar">
                    <input type="hidden" name="examtype_ei" value="arei2">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <textarea name="question_desc_ar1" class="form-control" placeholder="Create Question..."></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><h6 class="text-center">Correct Answer</h6></div>
                    </div>
                    <div class="row">    
                        <div class="form-group col-md-6">
                            <label for="" class="form-control border-0 text-center">True</label>
                            <input type="radio" name="ans1" id="3" value="1" class=" form-control" placeholder="Enter Response...">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="" class="form-control border-0 text-center">False</label>
                            <input type="radio" name="ans1" value="0"  class=" form-control">{{--  checked --}}
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
                                <input type="number" name="pointsar1" min="1" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2 pt-2 ml-n4"><span>point/s</span></div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group col-md-6 offset-3">
                        <button type="button" class="btn btn-secondary form-control" id="saveExamAR2"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


