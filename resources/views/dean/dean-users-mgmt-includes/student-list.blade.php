<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center">List of Students</h4>
            <div class="float-right mb-2">
                {{-- <a href="{{route('createinstructor')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Instructor</a> --}}
                <a href="{{route('deanlevelusermgmt', 'addstudent-form')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Students</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{-- <pre>
                @php
                    print_r($students);
                @endphp
            </pre> --}}
            @if (count($students) > 0)
                <table class="table table-custom">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Extension Name</th>
                            <th>Program</th>
                            <th>Year-Level</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;    
                        @endphp
                        @foreach ($students as $stud)
                            <tr>
                                <td>{{$counter}}</td>
                                <td>{{$stud->last_name}}</td>
                                <td>{{$stud->first_name}}</td>
                                <td>{{$stud->middle_name}}</td>
                                <td>{{$stud->ext_name}}</td>
                                <td>{{$stud->program_code}}</td>
                                <td>{{$stud->year_level}}</td>
                                <td>
                                    <a href="{{route('deanlevelusermgmt', ['deanusers'=>'student','id'=>$stud->people_id])}}" data-toggle="tooltip" title="Edit Credentials"><i class="fa fa-pencil" aria-hidden="true"></i></a> |
                                    <a href="" class="reset-insaccount" id="{{$stud->id}}" data-pid="{{$stud->people_id}}" data-toggle="tooltip" title="Reset Password and Username"><i class="fa fa-repeat" aria-hidden="true"></i> </a> |
                                    @if($stud->ua_status==1)
                                        <a href="#" class="btn btn-sm btn-success btn-xs enableDisable" id="{{$stud->id}}" data-uastatus="{{$stud->ua_status}}" data-toggle="tooltip" title="Deactivate Account" ><i class="fa fa-toggle-on" aria-hidden="true"></i></a> 
                                    @elseif($stud->ua_status==0)
                                        <a href="#" class="btn btn-sm btn-danger btn-xs enableDisable" id="{{$stud->id}}" data-uastatus="{{$stud->ua_status}}" data-toggle="tooltip" title="Deactivate Account" ><i class="fa fa-toggle-on" aria-hidden="true"></i></a> 
                                    @endif
                                    
                                </td>
                            </tr>
                            @php
                                $counter++;    
                            @endphp
                        @endforeach
                    </tbody>
                </table>
                <div>
                    {{$students->links()}}
                </div>
            @else
                <div class="alert alert-warning text-center">
                    <strong>Warning!</strong> there is no Students added yet. Might want to add Students First.
                </div>
            @endif
        </div>
    </div>
</div>

@if (isset($getStudInfo))
    @include('dean.dean-users-mgmt-includes.edit-student-modal')
@endif



