<!-- resources/views/invoices/pdf/invoice.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        .details th, .details td { text-align: left; padding: 5px; }
        .totals { margin-top: 20px; }
        .totals th, .totals td { text-align: right; padding: 5px; }
        .footer { text-align: center; margin-top: 40px; font-size: 12px; }
        .paid { color: green; font-weight: bold; }
        .due { color: red; font-weight: bold; }
    </style>
</head>
<body>

  {{--  <div class="header">
        <h1>Invoice</h1>
        <h4>{{ $invoice->property->name }}</h4>
    </div>
--}}
<div class="header">
<h4>{{ $invoice->property ? $invoice->property->name : 'N/A' }}</h4>
<strong>Tenant:</strong> {{ $invoice->tenant ? $invoice->tenant->name : 'N/A' }} <br>
</div>

    <div class="details">
        <strong>Invoice Number:</strong> {{ $invoice->invoice_number }} <br>
        <strong>Tenant:</strong> {{ $invoice->tenant->name }} <br>
        <strong>Property:</strong> {{ $invoice->property ? $invoice->property->name : 'N/A' }} <br>
        <strong>Unit:</strong> {{ $invoice->unit }} <br>
        <strong>Issued Date:</strong>
{{ $invoice->issue_date ? \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') : 'N/A' }} <br>

<strong>Due Date:</strong>
{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : 'N/A' }} <br>

    </div>

    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount (UGX)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rent for <strong>Due Date:</strong>

{{ $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') : 'N/A' }} <br>
</td>
                <td>{{ number_format($invoice->amount) }}</td>
            </tr>
        </tbody>
        <tfoot class="totals">
            <tr>
                <th>Total</th>
                <td>{{ number_format($invoice->amount) }} UGX</td>
            </tr>
            <tr>
                <th>Paid</th>
                <td>{{ number_format($invoice->total_paid) }} UGX</td>
            </tr>
            <tr>
                <th>Outstanding</th>
                <td>{{ number_format($invoice->outstanding_amount) }} UGX</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        @if($invoice->status == 'paid')
            <p class="paid">PAID IN FULL</p>
        @elseif($invoice->status == 'overdue')
            <p class="due">OVERDUE - Please settle immediately!</p>
        @else
            <p>Please make payment before the due date.</p>
        @endif

        <p>Thank you for doing business with us!</p>
    </div>

</body>
</html>
