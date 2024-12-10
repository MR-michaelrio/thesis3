@extends('index')
@section('title', 'Personal Information')
@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
    <section class="col-12 mt-4 mb-4">
        <div class="text-center float-right">
            <button type="reset" class="btn btn-default">Discard</button>
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
                                    <label for="identification_number">Identification Number <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="identification_number" name="identification_number"
                                        placeholder="Enter Identification Number">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName">First Name <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="firstName" name="first_name"
                                        placeholder="Enter first name">
                                </div>
                            </div>
                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName">Last Name <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="lastName" name="last_name"
                                        placeholder="Enter last name">
                                </div>
                            </div>
                        </div>

                        <hr style="border: '1px solid gray'">

                        <div class="row">
                            <!-- Gender -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Gender <span style="color:red">*</span></label>
                                    <select class="form-control" id="gender" name="gender">
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
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Religion -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="religion">Religion <span style="color:red">*</span></label>
                                    <select class="form-control" id="religion" name="religion">
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
                                    <label for="placeOfBirth">Place of Birth <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="placeOfBirth" name="place_of_birth"
                                        placeholder="Enter place of birth">
                                </div>
                            </div>
                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Date of Birth <span style="color:red">*</span></label>
                                    <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="date_of_birth"
                                            placeholder="DD/MM/YYYY" data-target="#reservationdate1">
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
                                    <label for="fullAddress">Full Address <span style="color:red">*</span></label>
                                    <textarea class="form-control" id="fullAddress" name="full_address"
                                        placeholder="Enter full address"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Country <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="country" name="country"
                                        placeholder="Enter country">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="postalCode">Postal Code</label>
                                    <input type="text" class="form-control" id="postalCode" name="postal_code"
                                        placeholder="Enter postal code">
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
                                    <label for="phone">Phone <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        placeholder="Enter phone">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emergency_name">Emergency Name <span style="color:red">*</span></label>
                                    <textarea class="form-control" id="emergency_name" name="emergency_name"
                                        placeholder="Enter Emergency Name"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emergency_relation">Emergency Relation <span style="color:red">*</span></label>
                                    <textarea class="form-control" id="emergency_relation" name="emergency_relation"
                                        placeholder="Enter Emergency Relation"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="emergency_phone">Emergency Phone <span style="color:red">*</span></label>
                                    <textarea class="form-control" id="emergency_phone" name="emergency_phone"
                                        placeholder="Enter Emergency Phone"></textarea>
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
                            <label for="exampleInputEmail1">Email address<span style="color:red">*</span></label>
                            <input type="email" class="form-control" id="exampleInputEmail1" name="email" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password<span style="color:red">*</span></label>
                            <input type="password" class="form-control" name="password" id="exampleInputPassword1"
                                placeholder="Enter Password">
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
                            <label for="gender">Department<span style="color:red">*</span></label>
                            <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" name="id_department" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                <option disabled selected>Select</option>    
                                @foreach($department as $d)
                                    <option value="{{$d->id_department}}">{{$d->department_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gender">Position Title<span style="color:red">*</span></label>
                            <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" name="id_department_position" data-select2-id="2" tabindex="-1" aria-hidden="true">
                                <option disabled selected>Select</option>    
                                @foreach($departmentPosition as $d)
                                    <option value="{{$d->id_department_position}}">
                                        {{$d->position_title}}
                                    </option>                               
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gender">Reports to<span style="color:red">*</span></label>
                            <select class="form-control select2 select2-hidden-accessible" style="width: 100%;" name="supervisor" data-select2-id="3" tabindex="-1" aria-hidden="true">
                                <option disabled selected>Select</option>    
                                @foreach($user as $d)
                                    <option value="{{$d->id_user}}">{{$d->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <hr style="border: '1px solid gray'">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Start Date<span style="color:red">*</span></label>
                                    <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            placeholder="DD/MM/YYYY" name="start_work" data-target="#reservationdate2">
                                        <div class="input-group-append" data-target="#reservationdate2"
                                            data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Contract End Date<span style="color:red">*</span></label>
                                    <div class="input-group date" id="reservationdate3" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            placeholder="DD/MM/YYYY" name="stop_work" data-target="#reservationdate3">
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
                    <div class="card-body" style="display: block;">
                        The body of the card
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
                        The body of the card
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
