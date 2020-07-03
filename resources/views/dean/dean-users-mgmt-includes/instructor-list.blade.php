<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center">List of Faculty Members</h4>
            <div class="float-right mb-2">
                {{-- <a href="{{route('createinstructor')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Instructor</a> --}}
                <a href="{{route('deanlevelusermgmt', 'addinstructor-form')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Instructor</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if (count($employees) > 0)
                <table class="table table-custom">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Extension Name</th>
                            <th>User-Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;    
                        @endphp
                        @foreach ($employees as $emp)
                            <tr>
                                <td>{{$counter}}</td>
                                <td>{{$emp->last_name}}</td>
                                <td>{{$emp->first_name}}</td>
                                <td>{{$emp->middle_name}}</td>
                                <td>{{$emp->ext_name}}</td>
                                <td>{{$emp->user_type}}</td>
                                <td>
                                    <a href="{{route('deanlevelusermgmt', ['deanusers'=>'instructor','id'=>$emp->people_id])}}" data-toggle="tooltip" title="Edit Credentials"><i class="fa fa-pencil" aria-hidden="true"></i></a> |
                                    <a href="" class="reset-insaccount" id="{{$emp->id}}" data-pid="{{$emp->people_id}}" data-toggle="tooltip" title="Reset Password and Username"><i class="fa fa-repeat" aria-hidden="true"></i> </a> |
                                    @if($emp->ua_status==1)
                                        <a href="#" class="btn btn-sm btn-success btn-xs enableDisable" id="{{$emp->id}}" data-uastatus="{{$emp->ua_status}}" data-toggle="tooltip" title="Deactivate Account" ><i class="fa fa-toggle-on" aria-hidden="true"></i></a> 
                                    @elseif($emp->ua_status==0)
                                        <a href="#" class="btn btn-sm btn-danger btn-xs enableDisable" id="{{$emp->id}}" data-uastatus="{{$emp->ua_status}}" data-toggle="tooltip" title="Deactivate Account" ><i class="fa fa-toggle-on" aria-hidden="true"></i></a> 
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
                    {{$employees->links()}}
                </div>
            @else
                <div class="alert alert-warning text-center">
                    <strong>Warning!</strong> there is no INSTRUCTOR added yet. Might want to add First.
                </div>
            @endif
        </div>
    </div>
</div>

@if (isset($getEmpInfo))
    @include('dean.dean-users-mgmt-includes.edit-instructor-modal')
@endif



