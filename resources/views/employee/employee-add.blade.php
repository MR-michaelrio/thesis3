@extends('index')
@section('title', 'Personal Information')
@section('content')

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


<form method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data" >
    @csrf
    <div class="row">
        <section class="col-12 mt-4 mb-4">
            <div class="text-center float-right">
                <a href="{{ route('employee.index') }}" class="btn btn-default" onclick="return confirm('Are you sure?');">Discard</a>
                <button type="submit" class="btn btn-primary mr-2">Add</button>
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
                                <img src="https://media.istockphoto.com/id/1128826884/vector/no-image-vector-symbol-missing-available-icon-no-gallery-for-this-moment.jpg?s=612x612&w=0&k=20&c=390e76zN_TJ7HZHJpnI7jNl7UBpO3UP7hpR2meE1Qd4="
                                    id="profileImagePreview" alt="Profile Image" class="rounded-circle" width="120"
                                    height="120" style="border: 2px solid #ccc;">
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
                                    <input type="text" class="form-control" value="{{ old('identification_number') }}" id="identification_number" name="identification_number" placeholder="Enter Identification Number" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName">First Name <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" value="{{ old('first_name') }}" id="firstName" name="first_name"
                                        placeholder="Enter first name" required>
                                </div>
                            </div>
                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName">Last Name <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" id="lastName" value="{{ old('last_name') }}" name="last_name"
                                        placeholder="Enter last name" required>
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
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Marital Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maritalStatus">Marital Status</label>
                                    <select class="form-control" id="maritalStatus" name="marital">
                                        <option value="">Select a status</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                        <option value="Separated">Separated</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Single">Single</option>
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
                                        <option value="Christianity">Christianity</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Hinduism">Hinduism</option>
                                        <option value="Buddhism">Buddhism</option>
                                        <option value="Other">Other</option>
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
                                    <input type="text" class="form-control" id="placeOfBirth" value="{{ old('place_of_birth') }}" name="place_of_birth"
                                        placeholder="Enter place of birth" required>
                                </div>
                            </div>
                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Date of Birth <span style="color:red"> *</span></label>
                                    <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="date_of_birth"
                                            placeholder="DD/MM/YYYY" data-target="#reservationdate1" required>
                                        <div class="input-group-append" data-target="#reservationdate1"
                                            data-toggle="datetimepicker">
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
                                    <textarea class="form-control" id="fullAddress" name="full_address"
                                        placeholder="Enter full address" required>{{ old('full_address') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Country <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" id="country" name="country"
                                        placeholder="Enter country" required value="{{ old('country') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="postalCode">Postal Code</label>
                                    <input type="text" class="form-control" id="postalCode" name="postal_code"
                                        placeholder="Enter postal code" value="{{ old('postal_code') }}">
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
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Enter phone" required value="{{ old('phone') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emergency_name">Emergency Contact Name <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" id="emergency_name" name="emergency_name"
                                        placeholder="Enter Emergency Name" required value="{{ old('emergency_name') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emergency_relation">Emergency Contact Relation <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" id="emergency_relation" name="emergency_relation"
                                        placeholder="Enter Emergency Relation" required value="{{ old('emergency_relation') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emergency_phone">Emergency Contact Phone <span style="color:red"> *</span></label>
                                    <input type="text" class="form-control" id="emergency_phone" name="emergency_phone"
                                        placeholder="Enter Emergency Phone" required value="{{ old('emergency_phone') }}">
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
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address<span style="color:red"> *</span></label>
                            <input type="email" class="form-control" value="{{ old('email') }}" id="exampleInputEmail1" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password<span style="color:red"> *</span></label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter Password" required>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>

                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF3;color:white">
                        <h3 class="card-title">Personel Position Information</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-chevron-up" style="color:white"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" style="display: block;">
                        <!-- <div class="form-group">
                            <label>Employee Id<span style="color:red">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter Employee Id">
                        </div> -->
                        <div class="form-group">
                            <label for="department">Department<span style="color:red"> *</span></label>
                            <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" name="id_department" id="department" tabindex="-1" required>
                                <option disabled selected>Select</option>    
                                @foreach($department as $d)
                                    <option value="{{$d->id_department}}">{{$d->department_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="department_position">Position Title<span style="color:red"> *</span></label>
                            <select class="form-control select2" style="width: 100%;" name="id_department_position" id="department_position" tabindex="-1" required>
                                <option disabled selected>Select</option>  
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="gender">Reports to</label>
                            <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" name="supervisor" data-select2-id="3" tabindex="-1" aria-hidden="true">
                                <option disabled selected>Select</option>    
                                <option value="NONE">NONE</option>
                                @foreach($user as $d)
                                    <option value="{{$d->id_user}}">{{$d->employee->full_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr style="border: '1px solid gray'">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Start Date<span style="color:red"> *</span></label>
                                    <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            placeholder="DD/MM/YYYY" name="start_work" data-target="#reservationdate2" value="{{ old('start_work') }}" required>
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
                                            placeholder="DD/MM/YYYY" name="stop_work" value="{{ old('stop_work') }}" data-target="#reservationdate3">
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
                    <!-- /.card-header -->
                    <!-- <div class="card-body" style="display: block;">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="gender">Monday</label>
                                    <select class="form-control select2 monday select2-hidden-accessible" style="width: 100%;" name="monday" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option disabled selected>Select</option>    
                                        @foreach($shift as $d)
                                            <option value="{{$d->id_shift}}">{{$d->shift_name}}  [{{$d->clock_in}} - {{$d->clock_out}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="gender">Tuesday</label>
                                    <select class="form-control select2 tuesday select2-hidden-accessible" style="width: 100%;" name="tuesday" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option disabled selected>Select</option>    
                                        @foreach($shift as $d)
                                            <option value="{{$d->id_shift}}">{{$d->shift_name}}  [{{$d->clock_in}} - {{$d->clock_out}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="gender">Wednesday</label>
                                    <select class="form-control select2 wednesday select2-hidden-accessible" style="width: 100%;" name="wednesday" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option disabled selected>Select</option>    
                                        @foreach($shift as $d)
                                            <option value="{{$d->id_shift}}">{{$d->shift_name}}  [{{$d->clock_in}} - {{$d->clock_out}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="gender">Thursday</label>
                                    <select class="form-control select2 thursday select2-hidden-accessible" style="width: 100%;" name="thursday" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option disabled selected>Select</option>    
                                        @foreach($shift as $d)
                                            <option value="{{$d->id_shift}}">{{$d->shift_name}}  [{{$d->clock_in}} - {{$d->clock_out}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="gender">Friday</label>
                                    <select class="form-control select2 friday select2-hidden-accessible" style="width: 100%;" name="friday" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option disabled selected>Select</option>    
                                        @foreach($shift as $d)
                                            <option value="{{$d->id_shift}}">{{$d->shift_name}}  [{{$d->clock_in}} - {{$d->clock_out}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="gender">Saturday</label>
                                    <select class="form-control select2 saturday select2-hidden-accessible" style="width: 100%;" name="saturday" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option disabled selected>Select</option>    
                                        @foreach($shift as $d)
                                            <option value="{{$d->id_shift}}">{{$d->shift_name}}  [{{$d->clock_in}} - {{$d->clock_out}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="gender">Sunday</label>
                                    <select class="form-control select2 sunday select2-hidden-accessible" style="width: 100%;" name="sunday" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option disabled selected>Select</option>    
                                        @foreach($shift as $d)
                                            <option value="{{$d->id_shift}}">{{$d->shift_name}}  [{{$d->clock_in}} - {{$d->clock_out}}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div> -->
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
                                        <select class="form-control select2 {{ $day }} select2-hidden-accessible" style="width: 100%;" name="{{ $day }}" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                            <option value="">Select</option>
                                            @foreach($shift as $d)
                                                <option value="{{ $d->id_shift }}">
                                                    {{ $d->shift_name }} [{{ $d->clock_in }} - {{ $d->clock_out }}]
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- /.card-body -->
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
                                <div class="row m-2" style="background-color: #F8F9FA; border-radius: 10px;">
                                    <div class="col-1 d-flex justify-content-center align-items-center">
                                        <input type="checkbox" 
                                            id="leave_{{$l->id_leave}}" 
                                            name="leaves[]" 
                                            value="{{$l->id_leave}}">
                                    </div>
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12" style="font-size: 1.1rem; font-weight: bold;">
                                                        {{$l->leave_name}}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12" style="font-size: 0.875rem;">Category: {{$l->category}}</div>
                                                    <div class="col-12" style="font-size: 0.875rem;">Default: {{$l->default_quota}}</div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-12" style="font-size: 1rem;">Custom Quota:</div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="number" 
                                                            name="custom_quotas[{{$l->id_leave}}]" 
                                                            id="quota_{{$l->id_leave}}" 
                                                            class="form-control" 
                                                            style="display: none;" 
                                                            min="0" 
                                                            placeholder="Enter custom quota">
                                                            <div class="col-1 d-flex justify-content-center align-items-center">
                                        <button type="button" 
                                            class="btn btn-sm btn-secondary" 
                                            onclick="toggleQuotaInput({{$l->id_leave}})">
                                            <i class="fa fa-edit"></i> <!-- Ikon edit -->
                                        </button>
                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
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
    // Toggle visibility of the input field
    if (input.style.display === 'none' || input.style.display === '') {
        input.style.display = 'block'; // Show input
    } else {
        input.style.display = 'none'; // Hide input
        input.value = ''; // Reset value when hidden
    }
}

$(document).ready(function() {
    // Trigger when the department is changed
    $('#department').on('change', function() {
        var departmentId = $(this).val();

        // Fetch positions based on the selected department
        if (departmentId) {
            // Fetch positions for the selected department
            $.ajax({
                url: '{{ route("getDepartmentPositions", ":departmentId") }}'.replace(':departmentId', departmentId),
                method: 'GET',
                success: function(response) {
                    // Clear the current position options
                    $('#department_position').empty();
                    $('#department_position').append('<option disabled selected>Select</option>');

                    // Add new position options based on the response
                    $.each(response, function(index, position) {
                        $('#department_position').append('<option value="' + position.id_department_position + '">' + position.position_title + '</option>');
                    });

                    // Reinitialize select2
                    $('.select2').select2({
                        theme: "bootstrap4"
                    });

                    // Fetch supervisors for the selected department
                    $.ajax({
                        url: '{{ route("getSupervisorsByDepartment", ":departmentId") }}'.replace(':departmentId', departmentId),
                        method: 'GET',
                        success: function(response) {
                            // Clear the current supervisor options
                            $('select[name="supervisor"]').empty();
                            $('select[name="supervisor"]').append('<option disabled selected>Select</option>');
                            $('select[name="supervisor"]').append('<option value="NONE">NONE</option>');

                            var addedNames = new Set();
                            $.each(response.supervisors, function(index, supervisor) {
                                // If supervisor's full name has not been added yet, add to dropdown and mark as added
                                if (!addedNames.has(supervisor.employee.full_name)) {
                                    $('select[name="supervisor"]').append('<option value="' + supervisor.id_user + '" selected>' + supervisor.employee.full_name + '</option>');
                                    addedNames.add(supervisor.employee.full_name);
                                }
                            });

                            // Add all supervisors (this could be for a general pool of supervisors)
                            $.each(response.supervisorsall, function(index, supervisor) {
                                // Check if the supervisor's full name is already in the set
                                if (!addedNames.has(supervisor.employee.full_name)) {
                                    $('select[name="supervisor"]').append('<option value="' + supervisor.id_user + '">' + supervisor.employee.full_name + '</option>');
                                    addedNames.add(supervisor.employee.full_name);
                                }
                            });

                            // Reinitialize select2 for supervisors
                            $('.select2').select2({
                                theme: "bootstrap4"
                            });
                        }
                    });

                }
            });
        } else {
            // If no department is selected, clear the position and supervisor dropdowns
            $('#department_position').empty();
            $('#department_position').append('<option disabled selected>Select</option>');
            $('select[name="supervisor"]').empty();
            $('select[name="supervisor"]').append('<option disabled selected>Select</option>');
            $('select[name="supervisor"]').append('<option value="NONE">NONE</option>');
        }
    });
});

</script>
<script>
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
