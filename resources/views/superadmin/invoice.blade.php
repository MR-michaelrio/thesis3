@extends('index')
@section('title','Client')
@section('css')
    <style>
        /* Custom CSS for active tab background */
        .nav-tabs .nav-link.active {
            background-color: #027BFF !important; /* Replace this with your desired 'prinart' color */
            color: white !important; /* Ensure text is readable on the colored background */
            border-color: #027BFF !important; /* Match border with background */
        }
        .nav-tabs .nav-link {
            color: #000; /* Default text color for inactive tabs */
        }
        #table2 #table3 table {
            width: 100% !important;
        }
        
        #loadingIndicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 60px;  /* Increase the spinner size */
            height: 60px; /* Increase the spinner size */
            border: 5px solid rgba(0, 0, 0, 0.1);
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection
@section('content')
<div class="row">
    <!-- Form Select Client -->
    <div class="col-lg-6 d-flex justify-content-center align-items-center" style="border:1px solid #CED4DB; background-color:white; border-radius:5px;">
        <div class="row w-100">
            <div class="col-12">
                <form id="clientForm" action="javascript:void(0)" method="get">
                    @csrf
                    <div class="form-group">
                        <label for="clientSelect">Select Client</label>
                        <select id="clientSelect" class="form-control select2bs4">
                            <option value="" selected>Select Client</option>
                            @foreach($client as $c)
                                <option value="{{ $c->id_company }}" data-name="{{ $c->company_name }}">{{ $c->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6" id="invoiceData" style="display: none;">
        <div class="row align-items-stretch">
            <!-- Invoice Amount -->
            <div class="col-md-6 d-flex align-items-stretch">
                <div class="small-box bg-danger w-100">
                    <div class="inner d-flex justify-content-between align-items-center" style="height: 100px;">
                        <div>
                            <p>Invoice Amount</p>
                            <h3 id="invoiceAmount"></h3>
                        </div>
                        <div class="icon" style="font-size: 50px;">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paid Invoice -->
            <div class="col-md-6 d-flex align-items-stretch">
                <div class="small-box bg-success w-100">
                    <div class="inner d-flex justify-content-between align-items-center" style="height: 100px;">
                        <div>
                            <p>Paid Invoice</p>
                            <h3 id="paidInvoice"></h3>
                        </div>
                        <div class="icon" style="font-size: 50px;">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unpaid Nominal -->
            <div class="col-md-6 d-flex align-items-stretch mx-auto mt-3">
                <div class="small-box bg-warning w-100">
                    <div class="inner d-flex justify-content-between align-items-center" style="height: 100px;">
                        <div>
                            <p class="text-white">Unpaid Nominal</p>
                            <h3 class="text-white" id="unpaidInvoice"></h3>
                        </div>
                        <div class="icon" style="font-size: 50px;">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2" id="tabinvoice" style="display: none;">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Data Invoice <span id="clientName">[Client Name]</span></h5>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs" id="attendanceTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="tab-1" data-toggle="tab" href="#table1" role="tab" aria-controls="table1" aria-selected="true">Add Invoice</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-2" data-toggle="tab" href="#table2" role="tab" aria-controls="table2" aria-selected="false">History</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="tab-3" data-toggle="tab" href="#table3" role="tab" aria-controls="table3" aria-selected="false">Payment</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" style="padding:10px 10px 10px 10px; border:1px solid #DDE2E5" id="attendanceTabsContent">
                    <!-- Table 1 -->
                    <div class="tab-pane fade show active p-3" id="table1" role="tabpanel" aria-labelledby="tab-1">
                        <form action="{{ route('client.invoicecreate') }}" method="post">
                            @csrf
                            <input type="hidden" name="id_company" id="id_company">
                            <div class="row">
                                <div class="col-3">   
                                    <div class="form-group">
                                        <label>Invoice Date</label>
                                        <div class="input-group date" id="reservationdate1" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                placeholder="DD/MM/YYYY" data-target="#reservationdate1" id="reservationdate1" name="invoice_date">
                                            <div class="input-group-append" data-target="#reservationdate1"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>   
                                <div class="col-3">   
                                    <div class="form-group">
                                        <label>Due Date (Days)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Enter Number Of Days" name="due_days">
                                        </div>
                                    </div> 
                                </div>  
                                <div class="col-3">   
                                    <div class="form-group">
                                        <label>Period Start</label>
                                        <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                placeholder="DD/MM/YYYY" data-target="#reservationdate2" id="reservationdate2" name="period_start">
                                            <div class="input-group-append" data-target="#reservationdate2"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>  
                                <div class="col-3">   
                                    <div class="form-group">
                                        <label>Period End</label>
                                        <div class="input-group date" id="reservationdate3" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input"
                                                placeholder="DD/MM/YYYY" data-target="#reservationdate3" id="reservationdate3" name="period_end">
                                            <div class="input-group-append" data-target="#reservationdate3"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>    
                            </div>
                            <div class="row">
                                <table class="table">
                                    <tr style="background-color:#0798C1;color:white">
                                        <th>Item</th>
                                        <th>Currency</th>
                                        <th>Price</th>
                                        <th>Discount</th>
                                        <th>Subtotal</th>
                                    </tr>
                                    <tr>
                                        <td>Face Recognition Attendance System</td>
                                        <td>IDR</td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="000.000.000" name="price" id="price">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="000.000.000" name="discount" id="discount">
                                        </td>
                                        <td id="discountedPrice">000.000.000</td>
                                    </tr>
                                    <tr>
                                        <th colspan="4">Sub Total</th>
                                        <th id="subTotal">000.000.000</th>
                                        <input type="hidden" name="subtotalinput" id="subtotalinput">
                                    </tr>
                                    <tr>
                                        <th colspan="4">Tax</th>
                                        <th>
                                            <input type="text" class="form-control" placeholder="000.000.000" name="tax" id="tax">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="4">Total</th>
                                        <th id="total">000.000.000</th>
                                        <input type="hidden" name="total" id="totalinput">
                                    </tr>
                                </table>
                            </div>
                            <div class="row">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>

                    <!-- Table 2 -->
                    <div class="tab-pane fade" id="table2" role="tabpanel" aria-labelledby="tab-2">
                        <div class="col-3">   
                            bs   
                        </div> 
                    </div>

                    <!-- Table 3 -->
                    <div class="tab-pane fade" id="table3" role="tabpanel" aria-labelledby="tab-3">
                        <table id="AdminAccount" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Payment Due</th>
                                    <th>Currency</th>
                                    <th>Total Bill</th>
                                    <th>Total Payment</th>
                                    <th>Payment Status</th>
                                    <th>Payment Evidence</th>
                                    <th>Payment Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoicedata as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->payment_due }}</td>
                                    <td>{{ $invoice->invoiceitem->currency }}</td>
                                    <td>{{ $invoice->invoiceitem->sub_total }}</td>
                                    <td>{{ $invoice->payed_amount }}</td>
                                    <td><span style="background-color:red;border-radius:5px;color:white;padding:2px;">Not Yet Paid</span></td>
                                    <td>{{ $invoice->evidence }}</td>
                                    <td>{{ $invoice->payment_date }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success">Accept</button>
                                        <button type="button" class="btn btn-danger">Decline</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loadingIndicator" style="display: none;">
    <div class="spinner"></div>
</div>
@endsection

@section('scripts')
<script>
    // Update client name when client is selected
    $('#clientSelect').on('change', function () {
        var clientName = $(this).find('option:selected').data('name');
        $('#clientName').text(clientName || '[Client Name]'); // Update client name or reset if no selection
    });

    // Fungsi untuk menghitung total
    function calculateTotal() {
        // Ambil nilai dari input
        let price = parseFloat(document.getElementById('price').value.replace(/[^0-9.-]+/g, "")) || 0;
        let discount = parseFloat(document.getElementById('discount').value.replace(/[^0-9.-]+/g, "")) || 0;
        let tax = parseFloat(document.getElementById('tax').value.replace(/[^0-9.-]+/g, "")) || 0;

        // Hitung diskon (diskon dalam persen)
        let discountAmount = (discount / 100) * price;

        // Subtotal setelah diskon
        let subTotal = price - discountAmount;

        // Hitung pajak (pajak dalam persen)
        let taxAmount = (tax / 100) * subTotal;

        // Hitung total
        let total = subTotal + taxAmount;

        // Update hasil di halaman
        document.getElementById('discountedPrice').innerText = formatCurrency(subTotal);
        document.getElementById('subTotal').innerText = formatCurrency(subTotal);
        document.getElementById('total').innerText = formatCurrency(total);
        document.getElementById('totalinput').value = total;
        document.getElementById('subtotalinput').value = subTotal;

    }

    // Fungsi untuk memformat angka ke format mata uang
    function formatCurrency(amount) {
        return amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });
    }

    // Event listener untuk input harga, diskon, dan pajak
    document.getElementById('price').addEventListener('input', calculateTotal);
    document.getElementById('discount').addEventListener('input', calculateTotal);
    document.getElementById('tax').addEventListener('input', calculateTotal);

</script>
<script>
    // Update client name when client is selected
    $('#clientSelect').on('change', function () {
        var clientName = $(this).find('option:selected').data('name');
        $('#clientName').text(clientName || '[Client Name]'); // Update client name or reset if no selection

        // Fetch invoice data for the selected client
        var clientId = $(this).val();

        if (clientId) {
            $('#loadingIndicator').show();

            $.ajax({
                url: "{{ route('client.invoicedata') }}",
                type: 'GET',
                data: { client_id: clientId },
                success: function (data) {
                    $('#loadingIndicator').hide();

                    console.log(data);
                    if (data) {
                        $('#invoiceAmount').text(data.invoiceAmount);
                        $('#paidInvoice').text(data.paidInvoice);
                        $('#unpaidInvoice').text(data.unpaidInvoice);
                        $('#id_company').val(data.id_company);
                        $('#invoiceData').show();
                        $('#tabinvoice').show();
                    }
                },
                error: function () {
                    $('#loadingIndicator').hide();

                    alert('Error fetching data');
                }
            });
        } else {
            $('#invoiceData').hide();
            $('#loadingIndicator').hide();

        }
    });
</script>
@endsection
