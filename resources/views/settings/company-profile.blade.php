
@extends('index')
@section('title','Company')
@section('content')
<div class="row">
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
                            <input type="file" class="form-control" id="companyLogo" style="opacity: 0; position: absolute; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer; " onchange="previewImage(event)">
                            <img id="logoPreview" src="https://media.istockphoto.com/id/1128826884/vector/no-image-vector-symbol-missing-available-icon-no-gallery-for-this-moment.jpg?s=612x612&w=0&k=20&c=390e76zN_TJ7HZHJpnI7jNl7UBpO3UP7hpR2meE1Qd4=" alt="Company Logo" class="img-thumbnail" style="width: 180px; height: 90px;">
                        </div>
                    </div>
                    <div for="companyLogo" class="form-label">Company Logo (18:9)</div>
                </div>
                <div class="mb-3">
                    <label for="companyName" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="companyName" placeholder="Enter company name">
                </div>
                <div class="mb-3">
                    <label for="companyCode" class="form-label">Company Code</label>
                    <input type="text" class="form-control" id="companyCode" placeholder="Enter company Code">
                </div>
                
                <hr style="border: 1px solid #CED4DA">

                <div class="mb-3">
                    <label>Country</label>
                    <input type="text" class="form-control" id="country" placeholder="Enter Street Name">

                </div>

                <div class="mb-3">
                    <label for="streetname" class="form-label">Street Name</label>
                    <input type="text" class="form-control" id="streetname" placeholder="Enter Street Name">
                </div>
                
                <div class="mb-3">
                    <label for="postalcode" class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="postalcode" placeholder="Enter Postal Code">
                </div>

                <hr style="border: 1px solid #CED4DA">

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" class="form-control" id="email" placeholder="ex: companynmae@example.com">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phome" placeholder="ex: +62000000000">
                </div>
                
            </div>

            <div class="card-footer" style="background-color:#E7F9FE; display: flex; justify-content: flex-end;">
                <button type="button" class="btn bg-gradient-info">
                    <i class="fas fa-sync"></i> Update
                </button>
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
                <table>
                    <tr>
                        <td style="font-weight:bold">Created At</td>
                    </tr>
                    <tr>
                        <td>DD/MM/YYY</td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td style="font-weight:bold">Created By</td>
                    </tr>
                    <tr>
                        <td>customer.name@email.com</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
