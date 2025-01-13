@extends('index')
@section('title', 'Personal Information')
@section('css')
<style>
input[type="checkbox"].disabled-checkbox:disabled:checked {
    background-color: #007bff; /* Blue background */
    border: 2px solid #007bff; /* Blue border */
    color: white; /* Ensure contrast */
    appearance: none; /* Remove default checkbox styles */
    width: 15px; /* Adjust width */
    height: 15px; /* Adjust height */
    display: inline-block; /* Ensure block-level for styles */
    border-radius: 4px; /* Optional: for rounded corners */
    position: relative; /* Required for pseudo-element positioning */
}

input[type="checkbox"].disabled-checkbox:disabled:checked::after {
    content: '';
    position: absolute;
    top: 1px; /* Adjust positioning */
    left: 3.5px; /* Adjust positioning */
    width: 4px; /* Adjust width of the checkmark */
    height: 8px; /* Adjust height of the checkmark */
    border: solid white; /* White checkmark */
    border-width: 0 2px 2px 0; /* Thickness of the checkmark */
    transform: rotate(45deg); /* Create the checkmark shape */
}
</style>
@endsection
@section('content')
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<form method="POST" action="{{ route('employee.update',$employee->id_employee) }}" enctype="multipart/form-data">
    @csrf
    @method("PUT")
    <div class="row">
    <section class="col-12 mt-4 mb-4">
        <div class="text-center float-right">
            <a href="{{ route('employee.index') }}" class="btn btn-default" onclick="return confirm('Are you sure?');">Discard</a>
            <button type="submit" class="btn btn-primary mr-2">Update</button>
        </div>
    </section>
        <section class="col-lg-6 connectedSortable">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF3;color:white">
                        <h3 class="card-title">Personal Information</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-chevron-up" style="color:white"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="display: block;">
                        <!-- Profile Image Section -->
                        <div class="text-center mb-4">
                            <div style="position: relative; display: inline-block;">
                                <img src="{{ $employee->profile_picture ? asset('profile_picture/' . $employee->profile_picture) : 'https://media.istockphoto.com/id/1128826884/vector/no-image-vector-symbol-missing-available-icon-no-gallery-for-this-moment.jpg?s=612x612&w=0&k=20&c=390e76zN_TJ7HZHJpnI7jNl7UBpO3UP7hpR2meE1Qd4=' }}" 
                                    id="profileImagePreview" 
                                    alt="Profile Image" 
                                    class="rounded-circle" 
                                    width="120" 
                                    height="120" 
                                    style="border: 2px solid #ccc;">
                                <label for="profileImageInput"
                                    style="position: absolute; bottom: 0; right: 0; background-color: white; border-radius: 50%; padding: 5px; cursor: pointer;">
                                    <i class="fas fa-search" style="font-size: 18px; color: gray;"></i>
                                </label>
                            </div>
                            <input type="file" id="profileImageInput" name="profile_picture" accept="image/*" style="display: none;"
                                onchange="loadProfileImage(event)">
                        </div>


                        <div class="row">
                            <!-- Identification Number -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="identification_number">Identification Number <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" id="identification_number" required name="identification_number"
                                        placeholder="Enter Identification Number" value="{{$employee->user->identification_number}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName">First Name <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" id="firstName" required name="first_name"
                                        placeholder="Enter first name" value="{{$employee->first_name}}">
                                </div>
                            </div>
                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName">Last Name <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" id="lastName" required name="last_name"
                                        placeholder="Enter last name" value="{{$employee->last_name}}">
                                </div>
                            </div>
                        </div>

                        <hr style="border: '1px solid gray'">

                        <div class="row">
                            <!-- Gender -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Gender <span style="color:red"> *</span></label>
                                    <select class="form-control" id="gender" name="gender" required>
                                        <option value="">Select a gender</option>
                                        <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Marital Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maritalStatus">Marital Status</label>
                                    <select class="form-control" id="maritalStatus" name="marital">
                                        <option value="">Select a status</option>
                                        <option value="Married" {{ old('marital', $employee->marital) == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed" {{ old('marital', $employee->marital) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ old('marital', $employee->marital) == 'Separated' ? 'selected' : '' }}>Separated</option>
                                        <option value="Divorced" {{ old('marital', $employee->marital) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Single" {{ old('marital', $employee->marital) == 'Single' ? 'selected' : '' }}>Single</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <!-- Religion -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="religion">Religion <span style="color:red"> *</span></label>
                                    <select class="form-control" id="religion" name="religion" required>
                                        <option value="">Select a religion</option>
                                        <option value="Christianity" {{ old('religion', $employee->religion) == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                                        <option value="Islam" {{ old('religion', $employee->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Hinduism" {{ old('religion', $employee->religion) == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                                        <option value="Buddhism" {{ old('religion', $employee->religion) == 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
                                        <option value="Other" {{ old('religion', $employee->religion) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr style="border: '1px solid gray'">

                        <div class="row">
                            <!-- Place of Birth -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="placeOfBirth">Place of Birth <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" id="placeOfBirth" required name="place_of_birth"
                                        placeholder="Enter place of birth" value="{{$employee->place_of_birth}}">
                                </div>
                            </div>
                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Date of Birth <span style="color:red"> *</span></label>
                                    <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                        <input type="text" 
                                            class="form-control datetimepicker-input" 
                                            name="date_of_birth" 
                                            placeholder="DD/MM/YYYY" 
                                            data-target="#reservationdate1" 
                                            required
                                            value="{{ old('date_of_birth', \Carbon\Carbon::parse($employee->date_of_birth)->format('d/m/Y')) }}">
                                        <div class="input-group-append" data-target="#reservationdate1" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF3;color:white">
                        <h3 class="card-title">Address Information</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-chevron-up" style="color:white"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">
                        <!-- Address -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="fullAddress">Full Address <span style="color:red"> *</span></label>
                                    <textarea class="form-control" id="fullAddress" required name="full_address"
                                        placeholder="Enter full address">{{$employee->addressEmployee->full_address}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Country <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" required id="country" name="country"
                                        placeholder="Enter country" value="{{$employee->addressEmployee->country}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="postalCode">Postal Code</label>
                                    <input type="text" class="form-control" id="postalCode" name="postal_code"
                                        placeholder="Enter postal code" value="{{$employee->addressEmployee->postal_code}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>

                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF3;color:white">
                        <h3 class="card-title">Contact Information</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-chevron-up" style="color:white"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="phone">Phone <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" required id="phone" name="phone"
                                        placeholder="Enter phone" value="{{$employee->user->phone}}">
                                </div>
                            </div>

                            <div class="col-12">
                                <hr style="border: '2px solid gray'">
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emergency_name">Emergency Name <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" required id="emergency_name" name="emergency_name"
                                        placeholder="Enter Emergency Name" value="{{$employee->user->emergency_name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emergency_relation">Emergency Relation <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" required id="emergency_relation" name="emergency_relation"
                                        placeholder="Enter Emergency Relation" value="{{$employee->user->emergency_relation}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emergency_phone">Emergency Phone <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" required id="emergency_phone" name="emergency_phone"
                                        placeholder="Enter Emergency Phone" value="{{$employee->user->emergency_phone}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </section>
        <section class="col-lg-6 connectedSortable">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF3;color:white">
                        <h3 class="card-title">Account Information</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-chevron-up" style="color:white"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">
                        @if(Auth::user()->role == "admin" && Auth::user()->employee->id_employee != $employee->id_employee)
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address <span style="color:red"> *</span></label>
                                <input type="email" class="form-control" id="exampleInputEmail1" required name="email" placeholder="Enter email" value="{{$employee->user->email}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" name="password" id="exampleInputPassword1"
                                    placeholder="Enter Password">
                            </div>
                        @else
                            <div class="form-group">
                                <label for="oldPassword">Old Password</label>
                                <input type="password" class="form-control" name="old_password" id="oldPassword" placeholder="Enter Old Password">
                            </div>
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" class="form-control" name="new_password" id="newPassword" placeholder="Enter New Password">
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" class="form-control" name="confirm_password" id="confirmPassword"
                                placeholder="Confirm New Password">
                            </div>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>

                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF3;color:white">
                        <h3 class="card-title">Personnel Position Information</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-chevron-up" style="color:white"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">
                        <div class="form-group">
                            <label for="gender">Department <span style="color:red"> *</span></label>
                            @if(Auth::user()->role == "admin" )
                                <select class="form-control select2 select2-hidden-accessible" required style="width: 100%;" name="id_department" id="department-select" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                    <option disabled>Select</option>    
                                    @foreach($department as $d)
                                        <option value="{{ $d->id_department }}" 
                                                {{ old('id_department', $employee->user->id_department) == $d->id_department ? 'selected' : '' }}>
                                            {{ $d->department_name }}
                                        </option>                                
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ $employee->user->department->department_name }}" disabled>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="gender">Position Title <span style="color:red"> *</span></label>
                            @if(Auth::user()->role == "admin")
                                <select class="form-control select2 select2-hidden-accessible" required style="width: 100%;" name="id_department_position" id="position-select" data-select2-id="2" tabindex="-1" aria-hidden="true">
                                    <option disabled>Select</option>    
                                    @foreach($departmentPosition as $d)
                                        <option value="{{$d->id_department_position}}" {{ old('id_department_position', $employee->user->id_department_position) == $d->id_department_position   ? 'selected' : '' }}>
                                            {{$d->position_title}}
                                        </option>                               
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ $employee->user->position->position_title }}" disabled>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="gender">Reports to <span style="color:red"> *</span></label>
                            @if(Auth::user()->role == "admin")
                                <select class="form-control select2 select2-hidden-accessible" required style="width: 100%;" name="supervisor" id="supervisor-select" data-select2-id="3" tabindex="-1" aria-hidden="true">
                                    <option disabled>Select</option>    
                                    <option value="NONE">NONE</option>
                                    @foreach($user as $d)
                                        <option value="{{$d->id_user}}" {{ old('supervisor', $employee->user->supervisor) == $d->id_user ? 'selected' : '' }}>
                                            {{$d->employee->full_name}}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ $employee->user->supervisior->full_name ?? '' }}" disabled>
                            @endif
                        </div>

                        <hr style="border: '1px solid gray'">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Start Date <span style="color:red"> *</span></label>
                                    <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            placeholder="DD/MM/YYYY" name="start_work" required data-target="#reservationdate2" value="{{ old('start_work', $employee->user->start_work ? \Carbon\Carbon::parse($employee->user->start_work)->format('d/m/Y') : '') }}"
                                            @if(Auth::user()->role != "admin") disabled @endif>
                                        <div class="input-group-append" data-target="#reservationdate2"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Contract End Date</label>
                                    <div class="input-group date" id="reservationdate3" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            placeholder="DD/MM/YYYY" name="stop_work" data-target="#reservationdate3" value="{{ old('stop_work', $employee->user->stop_work ? \Carbon\Carbon::parse($employee->user->stop_work)->format('d/m/Y') : '') }}"
                                            @if(Auth::user()->role != "admin") disabled @endif>
                                        <div class="input-group-append" data-target="#reservationdate3"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>

                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF3;color:white">
                        <h3 class="card-title">Shift Assigment</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-chevron-up" style="color:white"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    @php
                        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                        $dayMapping = [
                            'monday' => 1,
                            'tuesday' => 2,
                            'wednesday' => 3,
                            'thursday' => 4,
                            'friday' => 5,
                            'saturday' => 6,
                            'sunday' => 7,
                        ];
                    @endphp

                    <div class="card-body" style="display: block;">
                        @foreach($days as $day)
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="{{ $day }}">{{ ucfirst($day) }}</label>
                                        @php
                                            // Find the assigned shift for the current day
                                            $assignedShift = $assignShiftByDay->get($dayMapping[$day], collect())->first();
                                        @endphp
                                        @if(Auth::user()->role != "admin")
                                            <input type="text" class="form-control" 
                                            value="{{ $assignedShift?->shift?->shift_name ?? 'No shift assigned' }} [{{$assignedShift?->shift?->clock_in ?? '--:--' }} - {{ $assignedShift?->shift?->clock_out ?? '--:--' }}]" disabled>
                                        @else
                                            <select class="form-control select2 {{ $day }} select2-hidden-accessible" 
                                                    style="width: 100%;" 
                                                    name="{{ $day }}" 
                                                    data-select2-id="1" 
                                                    tabindex="-1" 
                                                    aria-hidden="true">
                                                <option value="">Select</option>
                                                @foreach($shift as $d)
                                                    <option value="{{ $d->id_shift }}"
                                                        {{ old($day, $assignedShift?->id_shift ?? '') == $d->id_shift ? 'selected' : '' }}>
                                                        {{ $d->shift_name }} [{{ $d->clock_in }} - {{ $d->clock_out }}]
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF3;color:white">
                        <h3 class="card-title">Leave Type and Quota</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-chevron-up" style="color:white"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">
                        @foreach($leave as $l)
                            <div class="custom-control custom-checkbox">
                                <div class="row m-2" style="background-color: #F8F9FA;border-radius:10px;">
                                    <div class="col-1 d-flex justify-content-center align-items-center">
                                        <input type="checkbox" 
                                            id="leave_{{$l->id_leave}}" 
                                            name="leaves[]" 
                                            value="{{$l->id_leave}}" 
                                            {{ in_array($l->id_leave, $employeeLeaves) ? 'checked' : '' }}
                                            onchange="toggleQuotaInput({{$l->id_leave}})"
                                            @if(Auth::user()->role != 'admin') disabled @endif>
                                    </div>
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12" style="font-size: 1.1rem;font-weight:bold">
                                                        {{$l->leave_name}}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12" style="font-size: 0.875rem;">Category: {{$l->category}}</div>
                                                    <div class="col-12" style="font-size: 0.875rem;">Default: {{$l->default_quota}}</div>
                                                </div>
                                            </div>
                                            @if(Auth::user()->role == "admin")
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12" style="font-size: 1rem;">Custom Quota:</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" 
                                                            name="custom_quotas[{{$l->id_leave}}]" 
                                                            id="quota_{{$l->id_leave}}" 
                                                            class="form-control" 
                                                            min="0" 
                                                            placeholder="Enter custom quota"
                                                            value="{{ isset($employeeLeavesQuota[$l->id_leave]) ? $employeeLeavesQuota[$l->id_leave] : $l->default_quota }}"
                                                            style="{{ in_array($l->id_leave, $employeeLeaves) ? 'display: block;' : 'display: none;' }}">
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if(Auth::user()->role == 'admin')
                                        <div class="col-1 d-flex justify-content-center align-items-center">
                                            <button type="button" 
                                                class="btn btn-sm btn-secondary" 
                                                onclick="toggleQuotaInput({{$l->id_leave}})">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </section>
    </div>
</form>

@endsection

@section('scripts')


<script>
function toggleQuotaInput(leaveId) {
    const input = document.getElementById(`quota_${leaveId}`);
    if (input.style.display === 'none' || input.style.display === '') {
        input.style.display = 'block';
    } else {
        input.style.display = 'none';
        // Reset value when hidden
    }
}
</script>
<script>
    $(document).ready(function() {
    // Update position and supervisor based on department selection
    $('#department-select').on('change', function() {
        var departmentId = $(this).val();

        // Make an AJAX request to get positions and supervisors based on department
        $.ajax({
            url: '/get-department-details', // Create a route to handle this request
            method: 'GET',
            data: { department_id: departmentId },
            success: function(response) {
                // Update the position select options
                $('#position-select').empty(); // Clear existing options
                $('#position-select').append('<option disabled>Select</option>');
                response.positions.forEach(function(position) {
                    $('#position-select').append('<option value="' + position.id_department_position + '">' + position.position_title + '</option>');
                });

                // Update the supervisor select options
                $('#supervisor-select').empty(); // Clear existing options
                $('#supervisor-select').append('<option disabled>Select</option><option value="NONE">NONE</option>');
                console.log("supervisor",response);
                response.supervisors.forEach(function(supervisor) {  
                    $('#supervisor-select').append('<option value="' + supervisor.id_user + '" selected>' + supervisor.employee.full_name + '</option>');
                });
                response.supervisorsall.forEach(function(supervisor) {
                    $('#supervisor-select').append('<option value="' + supervisor.id_user + '">' + supervisor.employee.full_name + '</option>');
                });
            }
        });
    });
});

    $('.monday, .tuesday, .wednesday, .thursday, .friday, .saturday, .sunday').select2({
        theme: "bootstrap4" // Optional theme, use "default" or customize as needed
    });
    function loadProfileImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('profileImagePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
