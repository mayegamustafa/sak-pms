@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Payment</h1>

        <form method="POST" action="{{ route('payments.store1') }}">
            @csrf
            <div class="form-group">
                <label for="tenant_id">Tenant</label>
                <select name="tenant_id" id="tenant_id" class="form-control" required>
                    <option value="">Select Tenant</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="unit_id">Unit</label>
                <select name="unit_id" id="unit_id" class="form-control">
                    <option value="">Select Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="amount_paid">Amount Paid (UGX)</label>
                <input type="number" name="amount_paid" id="amount_paid" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="payment_date">Payment Date</label>
                <input type="date" name="payment_date" id="payment_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <select name="payment_method" id="payment_method" class="form-control">
                    <option value="Cash">Cash</option>
                    <option value="Mobile Money">Mobile Money</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>

            <div class="form-group">
                <label for="for_month">Month</label>
                <select name="for_month" id="for_month" class="form-control" required>
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 10)) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="for_year">Year</label>
                <input type="number" name="for_year" id="for_year" class="form-control" value="{{ now()->year }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Record Payment</button>
        </form>
    </div>
@endsection
