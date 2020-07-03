<div class="row" id="quizdetail">
    <div class="col-md-3">
        <h5 class="mt-4 text-right">Quiz Details</h5>
    </div>
    <div class="col-md-9">
       {{--  <form action="{{route('submitQuizDetails')}}" method="post" class="createquizdetails"> --}}
        <form method="post" class="createquizdetails">
            {{ csrf_field() }}
            <input type="hidden" name="cpo_id" value="{{$cpoid}}">
            <input type="hidden" name="evaltype" value="quiz">
            <div class="row">
                <div class="form-group col-md-8">
                    <label for=""><strong>Instruction</strong></label>
                    <textarea name="instruction" class="form-control" placeholder="Create Instruction..."></textarea>
                </div>
                <div class="form-group col-md-4">
                    <button type="button" name="createQuizDetails" id="createQuiz" class="btn btn-info form-control mt-4"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
                </div>
            </div>
        </form>
    </div>
</div> 
<div class="container-fluid hide" id="quizForm"> {{-- to be uncommented --}}
{{-- <div class="container-fluid" id="quizForm"> --}}
    <div class="row">
        <div class="col-md-3">{{-- Card Counting Questions Made --}}
            <div class="card">
                <div class="card-header bg-secondary text-white">Questions Made</div>
                <div class="card-footer">Number of Item/s:  <span id="numitems"></span></div>
                <div class="card-footer">Total Points: <span id="totalpoints"></span></div>
            </div>
        </div>{{-- End of Card Counting Questions Made --}}
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-secondary text-white qdeschead">Question <span id="qctr"></span></div>
                <form action="" class="saveQuizQuestionForm multiple-choice">{{-- Form for Multiple Choice --}}
                    {{ csrf_field() }}
                    <input type="hidden" name="einstruct_id" value="">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <select name="quiztype" id="quiztype" class="form-control selectQtype">
                                    <option value="mc">Multiple Choice</option>
                                    <option value="ar">Alternate Response</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <textarea name="question_desc" class="form-control" placeholder="Create Question..."></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8"><h5 class="text-center">Responses</h5></div>
                            <div class="col-md-4"><h6 class="text-left">Correct Answer</h6></div>
                        </div>
                        <div class="responses">
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="number" name="points" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2 pt-2 ml-n4"><span>point/s</span></div>
                        </div>
                        
                    </div>
                    <div class="card-footer ">
                        <div class="form-group col-md-8 offset-2">
                            <button type="button" class="btn btn-secondary" id="saveQuiz"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                            <button type="button" class="btn btn-secondary" id="addResponse"><i class="fa fa-plus" aria-hidden="true"></i> Add Response</button>
                            <button type="button" class="btn btn-secondary" id="loadQuestions"><i class="fa fa-database" aria-hidden="true"></i> Load Question</button>
                        </div>
                    </div>
                </form>
                <form action="" class="alternateResponse hide">
                    <div class="card-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="einstruct_id" value="">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <select name="quiztype" id="quiztype" class="form-control selectQtype">
                                    <option value="mc">Multiple Choice</option>
                                    <option value="ar">Alternate Response</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <textarea name="question_desc_ar" class="form-control" placeholder="Create Question..."></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"><h6 class="text-center">Correct Answer</h6></div>
                        </div>
                        <div class="row">    
                            <div class="form-group col-md-6">
                                <label for="" class="form-control border-0 text-center">True</label>
                                <input type="radio" name="ans" id="3" value="1" class=" form-control" placeholder="Enter Response...">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="" class="form-control border-0 text-center">False</label>
                                <input type="radio" name="ans" value="0"  class=" form-control">{{--  checked --}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h5>Scoring</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <input type="number" name="pointsar" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2 pt-2 ml-n4"><span>point/s</span></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group col-md-6 offset-3">
                            <button type="button" class="btn btn-secondary" id="saveQuizAr"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                            <button type="button" class="btn btn-secondary" id="loadQuestions"><i class="fa fa-database" aria-hidden="true"></i> Load Question</button>
                        </div>
                    </div>
                </form>
            </div>{{-- end of card --}}
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
                Do you want to continue this action even without INSTRUCTION to the Quiz you are about to create.
            </div>{{-- end of modal body --}}

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" id="continue" class="btn btn-danger continue" {{-- data-dismiss="modal" --}}> Continue</button>
                <button type="button" id="cancel" class="btn btn-primary" data-dismiss="modal"> Cancel</button>
            </div>
               
        </div>{{-- end of modal content --}}
    </div>
</div>
<!-- eND OF The Modal Submiting Quiz Details Blank -->

{{-- Modal on Loading Question Bank --}}
<div class="modal fade" id="qBank" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-archive" aria-hidden="true"></i> Load Questions</h4>
                <a href="#" id="qBankClose" class="close" data-dismiss="modal">×</a>
            </div>
               
            <!-- Modal body -->
            <div class="modal-body">
             
                <ul class="nav nav-tabs " id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="mc-tab" data-toggle="tab" href="#mc" role="tab" aria-controls="mc" aria-selected="true">Multiple Choice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="ar-tab" data-toggle="tab" href="#ar" role="tab" aria-controls="ar" aria-selected="false">Alternate Response</a>
                    </li>
                </ul>
                <div class="tab-content tab-content-custom" id="myTabContent">
                    <div class="tab-pane fade show active" id="mc" role="tabpanel" aria-labelledby="mc-tab">
                        @isset($mcQuestionBank)
                        <div class="container-fluid verticalScrollBar">
                            @foreach ($mcQuestionBank as $item)
                                <div class="row">
                                    <div class="col-md-10 offset-1 border-bottom mt-2 mb-2">
                                        <p>
                                            <a href="#" class="btn btn-info btn-sm add-qb-mc add-qb" id="{{$item['qb_id']}}" data-einstructid=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Add</a>
                                            {{$item['question_desc']}}
                                            <br>
                                            <small>{{$item['points']}} Point(s) Question | <a data-toggle="collapse" href="#mcChoices{{$item['qb_id']}}" role="button" aria-expanded="false" aria-controls="mcChoices{{$item['qb_id']}}">View Choices</a></small>
                                        </p>
                                        <div class="collapse" id="mcChoices{{$item['qb_id']}}">
                                            <div class="">
                                                <ul class="list-group">
                                                    @foreach ($item['choices'] as $item1)
                                                        <li class="list-group-item mb-2">
                                                            @if ($item1->mc_is_answer==1)
                                                                <input type="radio" checked disabled>
                                                            @else
                                                                <input type="radio" disabled>
                                                            @endif
                                                            {{$item1->choices_description}}
                                                        </li>
                                                    @endforeach
                                                </ul>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @endisset
                    </div>
                    <div class="tab-pane fade" id="ar" role="tabpanel" aria-labelledby="ar-tab">
                        
                        @isset($arQuestionBank)
                            <div class="container-fluid verticalScrollBar">
                                @foreach ($arQuestionBank as $item)
                                    <div class="row">
                                        <div class="col-md-10 offset-1 border-bottom mt-2 mb-2">
                                            <p>
                                                <a href="#" class="btn btn-info btn-sm add-qb-ar add-qb" id="{{$item->qb_id}}" data-einstructid=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Add</a>
                                                {{$item->question_desc}}
                                                <br>
                                                <small>{{$item->points}} Point(s) Question | <a data-toggle="collapse" href="#arChoices{{$item->qb_id}}" role="button" aria-expanded="false" aria-controls="arChoices{{$item->qb_id}}">View Answer</a></small>
                                            </p>
                                            <div class="collapse" id="arChoices{{$item->qb_id}}">
                                                <div class="">
                                                    <ul class="list-group">
                                                        <li class="list-group-item mb-2">
                                                            @if ($item->ar_is_answer==1)
                                                                True
                                                            @else
                                                                False
                                                            @endif
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endisset
                    </div>
                </div>
            </div>{{-- end of modal body --}}

            <!-- Modal footer -->
            <div class="modal-footer">
                {{-- <button type="button" id="continue" class="btn btn-primary save-questions"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button> --}}
                <button type="button" id="cancel" class="btn btn-warning" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
            </div>
               
        </div>{{-- end of modal content --}}
    </div>
</div>
{{-- End of Modal on Loading Question Bank --}}
