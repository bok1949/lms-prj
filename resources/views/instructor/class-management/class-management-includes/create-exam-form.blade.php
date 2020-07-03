<div class="container-fluid" id="examDetails">{{-- examDetails --}}
    <div class="row">
        <div class="col-md-12 border-bottom border-secondary">
            <h5 class="text-center">Exam Details</h5>
        </div>
    </div>
    <form action="" method="" class="createExamDetails">
        {{ csrf_field() }}
        <input type="hidden" name="cpo_id" value="{{$cpoid}}">
        <input type="hidden" name="evaltype" value="exam">
        
        <div class="row mt-2">
            <div class="col-md-2 text-center">Exam Parts</div>
            <div class="col-md-10">Exam Parts Description</div>
        </div>
        <div class="row">
            <div class="col-md-1 text-right">I</div>
            <div class="form-group col-md-1 text-left">
                <input type="checkbox" name="exam_part1" value="I" >
            </div>
            <div class="col-md-10">
                <textarea name="exam_desc1" class="form-control" placeholder="Create Instruction..."></textarea>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-1 text-right">II</div>
            <div class="form-group col-md-1 text-left">
                <input type="checkbox" name="exam_part2" value="II" >
            </div>
            <div class="col-md-10">
                <textarea name="exam_desc2" class="form-control" placeholder="Create Instruction..."></textarea>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-4 offset-4">
                <button type="submit" name="" id="createExamInfo" class="btn btn-info form-control mt-4"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
            </div>
        </div>
    </form>
</div> {{--END of examDetails --}}
<div class="container-fluid hide" id="examForm"> {{-- to be uncommented --}}
{{-- <div class="container-fluid mt-2" id="examForm"> --}}
    <div class="row">
        <div class="col-md-12 ">
            <h5 class="text-center border-bottom border-info">Creating Examination</h5>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <h6 class="pl-2 part-left p1" id="p1"><a href="" class="btn btn-outline-secondary form-control disabled " > Part I</a> </h6>
            <h6 class="pl-2 part-left p2" id="p2"><a href="" class="btn btn-outline-secondary form-control " > Part II</a></h6>
        </div>
        <div class="col-md-10">
            <div class="container-fluid">
                <div class="row epartHead ">
                    <div class="col-md-12 ">
                        <h5 class="border-bottom border-success textHead" id="I">Part I </h5>
                        <span class="pdesc1 ml-2"></span>
                        <h5 class="border-bottom border-success hide textHead" id="II">Part II </h5>
                        <span class="pdesc2 ml-2"></span>
                    </div>
                </div>
                <div class="part_i">
                    <div class="row">
                        <div class="col-md-8 offset-2">
                            <button class="btn btn-secondary exam-type" id="mc">Multiple Choice</button>
                            <button class="btn btn-secondary exam-type" id="ar">Alternate Response</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 emc hide" id="emc">
                            @include('instructor.class-management.class-management-includes.multiple-choice-form')
                        </div>
                        <div class="col-md-12 ear hide" id="ear">
                            @include('instructor.class-management.class-management-includes.alternate-response-form')
                        </div>
                    </div>
                </div>
                <div class="part_ii hide">{{-- Part Two --}}
                    <div class="row">
                        <div class="col-md-8 offset-2">
                            <button class="btn btn-secondary exam-type-ii" id="mcii">Multiple Choice</button>
                            <button class="btn btn-secondary exam-type-ii" id="arii">Alternate Response</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12 emc-ii hide" id="emc_ii">
                            @include('instructor.class-management.class-management-includes.multiple-choice-form1')
                        </div>
                        <div class="col-md-12 ear-ii hide" id="ear_ii">
                            @include('instructor.class-management.class-management-includes.alternate-response-form1')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>


<!-- The Modal Submiting Quiz Details Blank -->
<div class="modal fade" id="warningModal" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header bg-warning">
                <h4 class="modal-title">Warning <i class="fa fa-exclamation" aria-hidden="true"></i> </h4>
                <a href="#" id="closeWarning" class="close" data-dismiss="modal">×</a>
            </div>
               
            <!-- Modal body -->
            <div class="modal-body">
                Do you want to continue this action even without INSTRUCTION to the Exam you are about to create.
            </div>{{-- end of modal body --}}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" id="continue_exam" class="btn btn-danger continue-exam" {{-- data-dismiss="modal" --}}> Continue</button>
                <button type="button" id="cancel" class="btn btn-primary" data-dismiss="modal"> Cancel</button>
            </div>
               
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal Submiting Quiz Details Blank -->



