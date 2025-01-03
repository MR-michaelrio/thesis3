<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
</head>
<body>
    <h1>Invoice {{ $invoice->invoice_number }}</h1>
    <p>Client: {{ $invoice->company->company_name }}</p>
    <p>Date: {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/F/Y') }}</p>
    <p>Period: {{ \Carbon\Carbon::parse($invoice->period_start)->format('d/F/Y') }} - {{ \Carbon\Carbon::parse($invoice->period_end)->format('d/F/Y') }}</p>

    <!-- Add your table and details here -->
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        @foreach($invoice->invoiceitem as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td>{{ $item->price }}</td>
                <td>{{ $item->discount }}</td>
                <td>{{ $item->sub_total }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    
    <p><strong>Total: </strong>{{ $invoice->invoiceitem->sum('sub_total') }}</p>
</body>
</html>
