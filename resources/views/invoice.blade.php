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

                            <div class="card-footer">
                                <button type="button" class="btn btn-paid " style="float: right;background-color:#54B96A;color:white" data-id="{{ $u->invoice_number }}" data-name="{{ $u->company->company_name }}" data-amount="{{ $u->invoiceitem->sum('sub_total') }}" data-due="{{ \Carbon\Carbon::parse($u->payment_due)->format('d/F/Y') }}">
                                    <i class="fas fa-credit-card"></i> Submit Payment
                                </button>
                                <button type="button" class="btn btn-primary mr-2" style="float: right;" onclick="window.location='{{ url('/invoice/pdf/' . $u->invoice_number) }}'">
                                    <i class="fas fa-save"></i> Save As PDF
                                </button>
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

                            <div class="card-footer">
                                <button type="button" class="btn btn-primary mr-2" style="float: right;" onclick="window.location='{{ url('/invoice/pdf/' . $u->invoice_number) }}'">
                                    <i class="fas fa-save"></i> Save As PDF
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Payment Confirmation -->
<div class="modal fade" id="paymentConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="paymentConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#0FBEF2;color:white">
                <h5 class="modal-title" id="paymentConfirmationModalLabel">Payment Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateStatusForm" action="{{ route('invoice.updateevidence') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="col-12">
                        <strong>Invoice ID: </strong><span id="modalInvoiceID"></span>
                        <input type="hidden" name="modalInvoiceinput" id="modalInvoiceinput" value="">
                    </div>
                    <div class="col-12">
                        <strong>Client Name:</strong> <span id="modalClientName"></span>
                    </div>
                    <div class="col-12">
                        <strong>Payment Amount:</strong> <span id="modalAmount"></span>
                        <input type="hidden" name="modalAmountinput" id="modalAmountinput" value="">
                    </div>
                    <div class="col-12">
                        <strong>Payment Due:</strong> <span id="modalDueDate"></span>
                    </div>

                    <hr style="border:1px solid #DADFE5">
                    <div class="col-12" id="documentSection">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="evidence" id="evidence" accept=".pdf" required>
                                <label class="custom-file-label" for="evidence">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Upload</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>Comment (Optional)</label>
                            <textarea class="form-control" rows="3" name="comment" placeholder="Enter Comment"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $(".btn-paid").click(function() {
        var invoiceId = $(this).data('id');
        var clientName = $(this).data('name');
        var amount = $(this).data('amount');
        var dueDate = $(this).data('due');

        $("#modalInvoiceID").text(invoiceId);
        $("#modalInvoiceinput").val(invoiceId);
        $("#modalClientName").text(clientName);
        $("#modalAmount").text(amount);
        $("#modalAmountinput").val(amount);
        $("#modalDueDate").text(dueDate);

        // Show the modal
        $('#paymentConfirmationModal').modal('show');
    });

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop(); // Get the file name
        $(this).next(".custom-file-label").addClass("selected").html(fileName); // Update the label
    });

    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        if (!$('#evidence')[0].files.length) {
            alert("Please select a file to upload.");
            return;
        }

        $.ajax({
            url: "{{route('invoice.updateevidence')}}",
            method: 'POST',
            data: formData,
            processData: false, // Don't process the files
            contentType: false, // Don't set content type
            success: function(response) {
                alert("Payment data submitted successfully.");
                $(".custom-file-label").val("");
                $("#modalAmountinput").val("");
                $("#modalInvoiceinput").val("");
                $('#paymentConfirmationModal').modal('hide'); // Hide modal on success
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
                alert("There was an error while uploading the file: " + error);
            }
        });
    });
});

</script>
@endsection
