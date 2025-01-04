@extends('index')
@section('title','Add Client')
@section('content')
<form action="{{ route('client.add1') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <section class="col-12 mt-4 mb-4">
            <div class="text-center float-right">
                <a href="{{ route('clientindex') }}" class="btn btn-default"
                    onclick="return confirm('Are you sure?');">Discard</a>
                <button type="submit" class="btn btn-primary mr-2">Add</button>
            </div>
        </section>
        <section class="col-lg-6 connectedSortable">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF2;color:white">
                        <h3 class="card-title">Company Information</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="mb-3 text-center">
                            <div class="position-relative d-flex justify-content-center align-items-center">
                                <div style="width: 180px; height: 90px; position: relative;">
                                    <input type="file" class="form-control" id="companyLogo" name="logo"
                                        style="opacity: 0; position: absolute; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;"
                                        onchange="previewImage(event)">

                                    <img id="logoPreview"
                                        src="https://media.istockphoto.com/id/1128826884/vector/no-image-vector-symbol-missing-available-icon-no-gallery-for-this-moment.jpg?s=612x612&w=0&k=20&c=390e76zN_TJ7HZHJpnI7jNl7UBpO3UP7hpR2meE1Qd4="
                                        alt="Company Logo" class="img-thumbnail"
                                        style="width: 90px; height: 90px;border-radius:50%">
                                </div>
                            </div>
                            <div for="companyLogo" class="form-label">Company Logo (18:9)</div>
                        </div>
                        <div class="mb-3">
                            <label for="companyName" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="companyName" name="company_name"
                                placeholder="Enter company name">
                        </div>
                        <div class="mb-3">
                            <label for="companyCode" class="form-label">Company Code</label>
                            <input type="text" class="form-control" id="companyCode" name="company_code"
                                placeholder="Enter company Code">
                        </div>

                        <hr style="border: 1px solid #CED4DA">

                        <div class="mb-3">
                            <label>Country</label>
                            <input type="text" class="form-control" id="country" name="country"
                                placeholder="Enter Country">
                        </div>

                        <div class="mb-3">
                            <label for="streetname" class="form-label">Street Name</label>
                            <input type="text" class="form-control" id="streetname" name="full_address"
                                placeholder="Enter Street Name">
                        </div>

                        <div class="mb-3">
                            <label for="postalcode" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="postalcode" name="postal_code"
                                placeholder="Enter Postal Code">
                        </div>

                        <hr style="border: 1px solid #CED4DA">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="text" class="form-control" id="email" name="company_email"
                                placeholder="ex: companyname@example.com">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="company_phone"
                                placeholder="ex: +62000000000">
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <section class="col-lg-6 connectedSortable">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background-color:#0FBEF2;color:white">
                        <h3 class="card-title">Creation Information</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="mb-3">
                            <div class="row">
                                <!-- First Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstName">First Name<span style="color:red"> *</span></label>
                                        <input type="text" class="form-control" value="{{ old('first_name') }}"
                                            id="firstName" name="first_name" placeholder="Enter first name" required>
                                    </div>
                                </div>
                                <!-- Last Name -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastName">Last Name<span style="color:red"> *</span></label>
                                        <input type="text" class="form-control" id="lastName"
                                            value="{{ old('last_name') }}" name="last_name"
                                            placeholder="Enter last name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                placeholder="ex: +62000000000">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address<span style="color:red"> *</span></label>
                            <input type="text" class="form-control" id="email" name="email"
                                placeholder="ex: companyname@example.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="password">Password<span style="color:red"> *</span></label>
                            <input type="password" class="form-control" name="password" id="password"
                                placeholder="Enter Password" required>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</form>

@endsection
