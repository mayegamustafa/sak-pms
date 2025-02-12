<!-- resources/views/payments/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Payment for {{ $invoice->tenant->name }}</h1>

    <div class="mb-3">
        <strong>Invoice Amount:</strong> UGX {{ number_format($invoice->amount) }}<br>
        <strong>Paid:</strong> UGX {{ number_format($invoice->paid_amount) }}<br>
        <strong>Outstanding:</strong> UGX {{ number_format($invoice->outstanding_amount) }}
    </div>

    <form action="{{ route('payments.store', $invoice->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="amount" class="form-label">Payment Amount (UGX)</label>
            <input type="number" name="amount" class="form-control" max="{{ $invoice->outstanding_amount }}" required>
        </div>

        <div class="mb-3">
            <label for="payment_date" class="form-label">Payment Date</label>
            <input type="date" name="payment_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <input type="text" name="payment_method" class="form-control" placeholder="e.g., Cash, Bank Transfer, Mobile Money">
        </div>

        <div class="mb-3">
            <label for="reference" class="form-label">Reference</label>
            <input type="text" name="reference" class="form-control" placeholder="Transaction Reference">
        </div>

        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Record Payment</button>
    </form>
</div>
@endsection
