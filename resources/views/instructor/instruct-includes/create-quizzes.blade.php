<div class="container-fluid">
    <div class="row">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Multiple Choice</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Alternate Response</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12 border-bottom border-primary ">
            <div class="mt-2 d-inline-flex mb-2">
                <p class="mt-3 instruction-label">Instruction:</p> &nbsp;  
                <textarea name="instruction" id="" cols="80" rows="2" class="instruction-textarea"></textarea>
            </div>
        </div>
    </div>
    
    <div class="row mt-2">
        
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-2 ">
                                <h4 class="bg-success p-2 text-light"># of Items</h4>
                                <div>
                                    <ul class="list-group list-group-flush text-center">
                                        <li class="list-group-item">1</li>
                                        <li class="list-group-item">2</li>
                                        <li class="list-group-item">3</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-10 border-left border-secondary">
                                <p class="ml-4 mt-2 mb-0">Question # 4</p>
                                <textarea name="instruction" id="" cols="90" rows="2" class="instruction-textarea ml-4"></textarea>
                                <div class="form-group row">
                                    <div class="col-sm-2"><small for="" class="isanswer-small-text">Is Answer</small><input type="radio" class="mt-3 ml-3"></div>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" placeholder="Enter Choice Here...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2"><small for="" class="isanswer-small-text">Is Answer</small><input type="radio" class="mt-3 ml-3"></div>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" placeholder="Enter Choice Here...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2"><small for="" class="isanswer-small-text">Is Answer</small><input type="radio" class="mt-3 ml-3"></div>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" placeholder="Enter Choice Here...">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-2"><small for="" class="isanswer-small-text">Is Answer</small><input type="radio" class="mt-3 ml-3"></div>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" placeholder="Enter Choice Here...">
                                    </div>
                                </div>
                                <div class="col-sm-6 offset-3">
                                    <button class="btn btn-primary form-control">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- end of first tab --}}
                {{-- Alternate tab --}}
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <h4 class="bg-success p-2 text-light"># of Items</h4>
            <div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">1</li>
                    <li class="list-group-item">2</li>
                    <li class="list-group-item">3</li>
                    <li class="list-group-item">4</li>
                    <li class="list-group-item">5</li>
                    <li class="list-group-item">6</li>
                    <li class="list-group-item">7</li>
                    <li class="list-group-item">8</li>
                    <li class="list-group-item">9</li>
                </ul>
            </div>
        </div>
        <div class="col-md-10 border-left border-secondary">
            <p class="ml-4 mt-2 mb-0">Question # 10</p>
            <textarea name="instruction" id="" cols="90" rows="2" class="instruction-textarea ml-4"></textarea>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row"> 
                        <div class="col-sm-3 offset-3 "><label for="" class="mt-2 ml-4 font-weight-bold">True</label></div>
                        <div class="col-sm-3 pl-0"><input type="radio" class="form-control" name="true"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row"> 
                        <div class="col-sm-3 offset-3"><label for="" class="mt-2 ml-4 font-weight-bold">False</label></div>
                        <div class="col-sm-3 pl-0"><input type="radio" class="form-control" name="true"></div>
                    </div>
                </div>
            </div>
           
            <div class="col-sm-6 offset-3">
                <button class="btn btn-primary form-control">Create</button>
            </div>
        </div>
    </div>
</div>


                    </div>
                </div>
            </div>
        </div>
        
    
    </div>
</div>