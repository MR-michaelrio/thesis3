<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AntTendance</title>

    <style>
        /* Set the page size to A4 */
        @page {
            size: A4;
            margin: 10mm;
        }

        /* Google Font: Source Sans Pro */
        @import url('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback');

        /* Font Awesome */
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

        /* AdminLTE Style */
        body {
            font-family: 'Source Sans Pro', sans-serif;
            margin: 0;
            padding: 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #0798C2;
            color: white;
        }
        .table td {
            background-color: #E7F9FE;
        }
        h3 {
            color: #4776F4;
        }

        /* Ensures everything stays within the page */
        .content {
            width: 100%;
            box-sizing: border-box;
        }

        .content hr {
            margin: 10px 0;
        }
        hr{
            border: 1px solid #E5E5E6;
        }
        /* Styling the sections */
        .section {
            margin-bottom: 20px;
        }

        .section h5 {
            margin-bottom: 5px;
        }

        .section p {
            margin: 0;
        }

        /* Table layout for header */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: top;
        }
        .header-table .left {
            width: 50%;
        }
        .header-table .right {
            width: 50%;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="content">
        <table class="header-table">
            <tr>
                <td class="left">
                    <h3>AntTendance</h3>
                    <img src="data:image/png;base64,{{ $logo }}" alt="Logo" style="max-width: 100px;">
                </td>
                <td class="right">
                    <h3>Invoice</h3>
                    <table style="float:right; font-size:13px;">
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
                </td>
            </tr>
        </table>

        <hr>

        <table class="header-table">
            <tr>
                <td class="left">
                    <p>From</p>
                    <p>
                        <strong style="color:#4776F4">AntTendance</strong><br>
                        Alamat perusahaan kita<br>
                        Telp: +62 800 000 000<br>
                        Email: company.email@example.com
                    </p>
                </td>
                <td class="right" style="text-align:left">
                    <p>To</p>
                    <p>
                        <strong style="color:#4776F4">{{ $u->company->company_name }}</strong><br>
                        {{ $u->company->full_address }}<br>
                        Telp: {{ $u->company->company_phone }}<br>
                        Email: {{ $u->company->company_email }}
                    </p>
                </td>
            </tr>
        </table>

        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Currency</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Face Recognition Attendance System</td>
                    <td>IDR</td>
                    <td>{{ $u->invoiceitem->price }}</td>
                    <td>{{ $u->invoiceitem->discount }}</td>
                    <td>{{ $u->invoiceitem->sub_total }}</td>
                </tr>
            </tbody>
        </table>

        <table class="header-table" style="margin-top: 20px;">
            <tr>
                <td class="left">
                    <p style="color:#4776F4">Payment Information</p>
                    <p>
                        Bank Central Asia - Account Name<br>
                        xxxxxxxxxx
                    </p>
                </td>
                <td class="right">
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
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
