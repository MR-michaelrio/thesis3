@extends('index')
@section('title', 'Personal Information')
@section('content')
<form>
    <div class="row">
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
                            <input type="file" id="profileImageInput" accept="image/*" style="display: none;"
                                onchange="loadProfileImage(event)">
                        </div>

                        <div class="row">
                            <!-- First Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstName">First Name <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="firstName"
                                        placeholder="Enter first name">
                                </div>
                            </div>
                            <!-- Last Name -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastName">Last Name <span style="color:red">*</span></label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Enter last name">
                                </div>
                            </div>
                        </div>

                        <hr style="border: '1px solid gray'">

                        <div class="row">
                            <!-- Gender -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gender">Gender <span style="color:red">*</span></label>
                                    <select class="form-control" id="gender">
                                        <option>Select a gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Marital Status -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="maritalStatus">Marital Status</label>
                                    <select class="form-control" id="maritalStatus">
                                        <option>Select a status</option>
                                        <option>Single</option>
                                        <option>Married</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Religion -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="religion">Religion <span style="color:red">*</span></label>
                                    <select class="form-control" id="religion">
                                        <option>Select a religion</option>
                                        <option>Christianity</option>
                                        <option>Islam</option>
                                        <option>Hinduism</option>
                                        <option>Buddhism</option>
                                        <option>Other</option>
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
                                    <input type="text" class="form-control" id="placeOfBirth"
                                        placeholder="Enter place of birth">
                                </div>
                            </div>
                            <!-- Date of Birth -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Date of Birth <span style="color:red">*</span></label>
                                    <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
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
                        The body of the card
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
                        The body of the card
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
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password<span style="color:red">*</span></label>
                            <input type="password" class="form-control" id="exampleInputPassword1"
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
                        <div class="form-group">
                            <label>Employee Id<span style="color:red">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter Employee Id">
                        </div>
                        <div class="form-group">
                            <label for="gender">Department<span style="color:red">*</span></label>
                            <select class="form-control" id="gender">
                                <option>Select a Department</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gender">Position Title<span style="color:red">*</span></label>
                            <select class="form-control" id="gender">
                                <option>Select a Position Title</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="gender">Reports to<span style="color:red">*</span></label>
                            <select class="form-control" id="gender">
                                <option>Select a supervisor</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>

                        <hr style="border: '1px solid gray'">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dateOfBirth">Start Date<span style="color:red">*</span></label>
                                    <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input"
                                            placeholder="DD/MM/YYYY" data-target="#reservationdate2">
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
                                            placeholder="DD/MM/YYYY" data-target="#reservationdate3">
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
