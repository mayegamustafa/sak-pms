<!-- resources/views/invoices/pdf.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .invoice {
            border: 1px solid #ccc;
            padding: 20px;
        }
        .invoice-header {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="invoice">
        <div class="invoice-header">Invoice #{{ $invoice->id }}</div>
        <div class="invoice-details">
            <p>Tenant: {{ $invoice->tenant->name }}</p>
            <p>Invoice Date: {{ $invoice->invoice_date }}</p>
            <p>Due Date: {{ $invoice->due_date }}</p>
            <p>Amount: UGX {{ number_format($invoice->amount) }}</p>
            <p>Paid Amount: UGX {{ number_format($invoice->paid_amount) }}</p>
            <p>Status: {{ ucfirst($invoice->status) }}</p>
        </div>
    </div>

</body>
</html>
