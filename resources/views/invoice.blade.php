
@extends('index')
@section('title','Attendance Data')
@section('css')
<style>
    /* Custom CSS for active tab background */
    .nav-tabs .nav-link.active {
        background-color: #0FBEF2 !important; /* Replace this with your desired 'prinart' color */
        color: white !important; /* Ensure text is readable on the colored background */
        border-color: #0FBEF2 !important; /* Match border with background */
    }
    .nav-tabs .nav-link {
        color: #000; /* Default text color for inactive tabs */
    }

    .active-invoice {
        background-color: #0FBEF2 !important;
        border-color: #0FBEF2 !important;
        color: white !important;
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="attendanceTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tab-1" data-toggle="tab" href="#table1" role="tab" aria-controls="table1" aria-selected="true">Unpaid</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-2" data-toggle="tab" href="#table2" role="tab" aria-controls="table2" aria-selected="false">Paid</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" style="padding:10px 10px 10px 10px; border:1px solid #DDE2E5" id="attendanceTabsContent">
                    <!-- Table 1 -->
                    <div class="tab-pane fade show active" id="table1" role="tabpanel" aria-labelledby="tab-1">
                        @foreach($unpaid as $u)
                        <div class="card collapsed-card">
                            <div class="card-header active-invoice">
                                <h3 class="card-title">{{ \Carbon\Carbon::parse($u->payment_due)->format('F Y') }}</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" style="color:white" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <!-- Header Section -->
                                    <div class="col-6">
                                        <h3 style="color:#4776F4">AntTendance</h3>
                                        <img src="{{ asset('assets/logo/logo.png') }}" alt="Logo" style="max-width: 100px;">
                                    </div>
                                    <div class="col-6 text-right">
                                        <h3 style="color:#4776F4">Invoice</h3>
                                        <p>Invoice Number: <strong>{{$u->invoice_number}}</strong></p>
                                        <p>Invoice Date: <strong>{{\Carbon\Carbon::parse($u->created_at)->format('d/F/Y')}}</strong></p>
                                        <p>Period: <strong>{{\Carbon\Carbon::parse($u->period_start)->format('d/F/Y')}} - {{\Carbon\Carbon::parse($u->period_end)->format('d/F/Y')}}</strong></p>
                                        <p>Payment Due: <strong>{{\Carbon\Carbon::parse($u->payment_due)->format('d/F/Y')}}</strong></p>
                                    </div>
                                </div>
                                <hr>
                                <!-- From and To Section -->
                                <div class="row">
                                    <div class="col-6">
                                        <h5>From</h5>
                                        <p>
                                            <strong style="color:#4776F4">AntTendance</strong><br>
                                            Alamat perusahaan kita<br>
                                            Telp: +62 800 000 000<br>
                                            Email: company.email@example.com
                                        </p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <h5>To</h5>
                                        <p>
                                            <strong style="color:#4776F4">{{$u->company->company_name}}</strong><br>
                                            {{$u->company->full_address}}<br>
                                            Telp: {{$u->company->company_phone}}<br>
                                            Email: {{$u->company->company_email}}
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <!-- Items Table -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-white" style="background-color:#0798C2">
                                            <tr>
                                                <th>Item</th>
                                                <th>Currency</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($u->invoiceitem as $item) 
                                            <tr style="background-color:#E7F9FE">
                                                <td>Face Recognition Attendance System</td>
                                                <td>IDR</td>
                                                <td>{{$item->price}}</td>
                                                <td>{{$item->discount}}</td>
                                                <td>{{$item->sub_total}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Payment Information -->
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <h5 style="color:#4776F4">Payment Information</h5>
                                        <p>
                                            Bank Central Asia - Account Name<br>
                                            xxxxxxxxxx
                                        </p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <h5>Total</h5>
                                        <p>
                                            Subtotal: <strong>{{ $u->invoiceitem->sum('sub_total') }}</strong><br>
                                            Tax: <strong>{{$u->tax}}</strong><br>
                                            <strong>Total: {{$u->invoiceitem->sum('payed_amount')}}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer" style="display: none;">
                                <button type="button" class="btn btn-primary">Paid</button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Table 2 -->
                    <div class="tab-pane fade" id="table2" role="tabpanel" aria-labelledby="tab-2">
                        @foreach($paid as $u)
                        <div class="card collapsed-card">
                            <div class="card-header active-invoice">
                                <h3 class="card-title">{{ \Carbon\Carbon::parse($u->payment_due)->format('F Y') }}</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" style="color:white" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <!-- Header Section -->
                                    <div class="col-6">
                                        <h3 style="color:#4776F4">AntTendance</h3>
                                        <img src="{{ asset('assets/logo/logo.png') }}" alt="Logo" style="max-width: 100px;">
                                    </div>
                                    <div class="col-6 text-right">
                                        <h3 style="color:#4776F4">Invoice</h3>
                                        <p>Invoice Number: <strong>{{$u->invoice_number}}</strong></p>
                                        <p>Invoice Date: <strong>{{\Carbon\Carbon::parse($u->created_at)->format('d/F/Y')}}</strong></p>
                                        <p>Period: <strong>{{\Carbon\Carbon::parse($u->period_start)->format('d/F/Y')}} - {{\Carbon\Carbon::parse($u->period_end)->format('d/F/Y')}}</strong></p>
                                        <p>Payment Due: <strong>{{\Carbon\Carbon::parse($u->payment_due)->format('d/F/Y')}}</strong></p>
                                    </div>
                                </div>
                                <hr>
                                <!-- From and To Section -->
                                <div class="row">
                                    <div class="col-6">
                                        <h5>From</h5>
                                        <p>
                                            <strong style="color:#4776F4">AntTendance</strong><br>
                                            Alamat perusahaan kita<br>
                                            Telp: +62 800 000 000<br>
                                            Email: company.email@example.com
                                        </p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <h5>To</h5>
                                        <p>
                                            <strong style="color:#4776F4">{{$u->company->company_name}}</strong><br>
                                            {{$u->company->full_address}}<br>
                                            Telp: {{$u->company->company_phone}}<br>
                                            Email: {{$u->company->company_email}}
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <!-- Items Table -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="text-white" style="background-color:#0798C2">
                                            <tr>
                                                <th>Item</th>
                                                <th>Currency</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($u->invoiceitem as $item) 
                                            <tr style="background-color:#E7F9FE">
                                                <td>Face Recognition Attendance System</td>
                                                <td>IDR</td>
                                                <td>{{$item->price}}</td>
                                                <td>{{$item->discount}}</td>
                                                <td>{{$item->sub_total}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Payment Information -->
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <h5 style="color:#4776F4">Payment Information</h5>
                                        <p>
                                            Bank Central Asia - Account Name<br>
                                            xxxxxxxxxx
                                        </p>
                                    </div>
                                    <div class="col-6 text-right">
                                        <h5>Total</h5>
                                        <p>
                                            Subtotal: <strong>{{ $u->invoiceitem->sum('sub_total') }}</strong><br>
                                            Tax: <strong>{{$u->tax}}</strong><br>
                                            <strong>Total: {{$u->invoiceitem->sum('payed_amount')}}</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer" style="display: none;">
                                <button type="button" class="btn btn-primary">Paid</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

