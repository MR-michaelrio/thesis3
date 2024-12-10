
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
            <form action="{{route('companies.update',$companies->id_company)}}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            <div class="card-body">
                <div class="mb-3 text-center">
                    <div class="position-relative d-flex justify-content-center align-items-center">
                        <div style="width: 180px; height: 90px; position: relative;">
                            <input type="file" class="form-control" id="companyLogo" name="logo" value="img{{$companies->logo}}" 
                                style="opacity: 0; position: absolute; width: 100%; height: 100%; top: 0; left: 0; cursor: pointer;" 
                                onchange="previewImage(event)">
                            
                            <img id="logoPreview" 
                                src="{{ $companies->logo ? asset('img/' . $companies->logo) : 'https://media.istockphoto.com/id/1128826884/vector/no-image-vector-symbol-missing-available-icon-no-gallery-for-this-moment.jpg?s=612x612&w=0&k=20&c=390e76zN_TJ7HZHJpnI7jNl7UBpO3UP7hpR2meE1Qd4=' }}" 
                                alt="Company Logo" 
                                class="img-thumbnail" 
                                style="width: 90px; height: 90px;border-radius:50%">
                        </div>
                    </div>
                    <div for="companyLogo" class="form-label">Company Logo (18:9)</div>
                </div>
                <div class="mb-3">
                    <label for="companyName" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="companyName" name="company_name" value="{{$companies->company_name}}" placeholder="Enter company name">
                </div>
                <div class="mb-3">
                    <label for="companyCode" class="form-label">Company Code</label>
                    <input type="text" class="form-control" id="companyCode" name="company_code" value="{{$companies->company_code}}" placeholder="Enter company Code">
                </div>
                
                <hr style="border: 1px solid #CED4DA">

                <div class="mb-3">
                    <label>Country</label>
                    <input type="text" class="form-control" id="country" name="{{$companies->country}}" value="country" placeholder="Enter Street Name">

                </div>

                <div class="mb-3">
                    <label for="streetname" class="form-label">Street Name</label>
                    <input type="text" class="form-control" id="streetname" name="full_address" value="{{$companies->full_address}}" placeholder="Enter Street Name">
                </div>
                
                <div class="mb-3">
                    <label for="postalcode" class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="postalcode" name="postal_code" value="{{$companies->postal_code}}" placeholder="Enter Postal Code">
                </div>

                <hr style="border: 1px solid #CED4DA">

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" class="form-control" id="email" name="company_email" value="{{$companies->company_email}}" placeholder="ex: companynmae@example.com">
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phome" name="company_phone" value="{{$companies->company_phone}}" placeholder="ex: +62000000000">
                </div>
                
            </div>

            <div class="card-footer" style="background-color:#E7F9FE; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn bg-gradient-info">
                    <i class="fas fa-sync"></i> Update
                </button>
            </div>
            </form>
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
                        <td>{{$companies->created_at}}</td>
                    </tr>
                </table>
                <br>
                <table>
                    <tr>
                        <td style="font-weight:bold">Created By</td>
                    </tr>
                    <tr>
                        <td>{{$companies->company_email}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
