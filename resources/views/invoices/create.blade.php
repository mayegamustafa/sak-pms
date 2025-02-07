<!-- resources/views/invoices/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Invoice</h1>

    <form action="{{ route('invoices.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="tenant_id" class="form-label">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="form-control" required>
                <option value="">Select Tenant</option>
                @foreach($tenants as $tenant)
                    <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="invoice_date" class="form-label">Invoice Date</label>
            <input type="date" name="invoice_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount (UGX)</label>
            <input type="number" name="amount" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Create Invoice</button>
    </form>
</div>
@endsection
