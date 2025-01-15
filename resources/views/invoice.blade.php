@extends('index')
@section('title','Invoice Data')
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
                                        <table style="float:right">
                                            <tr>
                                                <td>Invoice Number</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ $u->invoice_number }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Invoice Date</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ \Carbon\Carbon::parse($u->created_at)->format('d/F/Y') }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Period</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{\Carbon\Carbon::parse($u->period_start)->format('d/F/Y')}} - {{\Carbon\Carbon::parse($u->period_end)->format('d/F/Y')}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Payment Due</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{\Carbon\Carbon::parse($u->payment_due)->format('d/F/Y')}}</strong></td>
                                            </tr>
                                        </table>   
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
                                    <div class="col-6 text-left">
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
                                    <table class="table">
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
                                            <tr style="background-color:#E7F9FE">
                                                <td>Face Recognition Attendance System</td>
                                                <td>IDR</td>
                                                <td>{{ $u->invoiceitem->price }}</td>
                                                <td>{{ $u->invoiceitem->discount }}</td>
                                                <td>{{ $u->invoiceitem->sub_total }}</td>
                                            </tr>
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
                                        <table style="float:right">
                                            <tr>
                                                <td>Subtotal</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ $u->invoiceitem->sub_total ?? '0' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Tax</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ $u->tax ?? '0' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Total</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ $u->invoiceitem->payed_amount ?? '0' }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                @if($u->payment_status == "unpaid")
                                <button type="button" class="btn btn-paid " style="float: right;background-color:#54B96A;color:white" data-id="{{ $u->invoice_number }}" data-name="{{ $u->company->company_name }}" data-amount="{{ $u->invoiceitem->sum('sub_total') }}" data-due="{{ \Carbon\Carbon::parse($u->payment_due)->format('d/F/Y') }}">
                                    <i class="fas fa-credit-card"></i> Submit Payment
                                </button>
                                @else
                                <button type="button" class="btn btn-default" style="float: right;" disabled><i class="fas fa-credit-card"></i> Waiting Verification</button>
                                @endif
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
                                        <table style="float:right">
                                            <tr>
                                                <td>Invoice Number</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ $u->invoice_number }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Invoice Date</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ \Carbon\Carbon::parse($u->created_at)->format('d/F/Y') }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Period</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{\Carbon\Carbon::parse($u->period_start)->format('d/F/Y')}} - {{\Carbon\Carbon::parse($u->period_end)->format('d/F/Y')}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Payment Due</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{\Carbon\Carbon::parse($u->payment_due)->format('d/F/Y')}}</strong></td>
                                            </tr>
                                        </table>
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
                                    <div class="col-6 text-left">
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
                                    <table class="table">
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
                                            <tr style="background-color:#E7F9FE">
                                                <td>Face Recognition Attendance System</td>
                                                <td>IDR</td>
                                                <td>{{ $u->invoiceitem->price }}</td>
                                                <td>{{ $u->invoiceitem->discount }}</td>
                                                <td>{{ $u->invoiceitem->sub_total }}</td>
                                            </tr>
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
                                        <table style="float:right">
                                            <tr>
                                                <td>Subtotal</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ $u->invoiceitem->sub_total ?? '0' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Tax</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ $u->tax ?? '0' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Total</td>
                                                <td style="padding:0px 5px 0px 5px;">:</td>
                                                <td><strong>{{ $u->invoiceitem->payed_amount ?? '0' }}</strong></td>
                                            </tr>
                                        </table>
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
                    <input type="hidden" name="modalInvoiceinput" id="modalInvoiceinput" value="">
                    <div class="col-12">
                        <table>
                            <tr>
                                <td style="width:30%"><strong>Invoice ID</strong></td>
                                <td>:</td>
                                <td><span id="modalInvoiceID"></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><strong>Client Name</strong></td>
                                <td>:</td>
                                <td><span id="modalClientName"></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><strong>Payment Amount</strong></td>
                                <td>:</td>
                                <td><span id="modalAmount"></td>
                            </tr>
                            <tr>
                                <td style="width:30%"><strong>Payment Due</strong></td>
                                <td>:</td>
                                <td><span id="modalDueDate"></td>
                            </tr>
                        </table>
                    </div>

                    <hr style="border:1px solid #DADFE5">

                    <div class="col-12" id="documentSection">
                        <div class="form-group">
                            <label for="exampleInputFile">Upload Proof of Payment</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="evidence" accept="application/pdf" id="evidence" required>
                                    <label class="custom-file-label" for="evidence">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                </div>
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
        $("#modalDueDate").text(dueDate);

        // Show the modal
        $('#paymentConfirmationModal').modal('show');
    });

    $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop(); // Get the file name
        var fileExtension = fileName.split('.').pop().toLowerCase(); // Get file extension

        if (fileExtension !== 'pdf') {
            alert("Only PDF files are allowed.");
            $(this).val(''); // Reset input file
            $(this).next(".custom-file-label").removeClass("selected").html("Choose file");
            return;
        }

        $(this).next(".custom-file-label").addClass("selected").html(fileName); // Update label
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
