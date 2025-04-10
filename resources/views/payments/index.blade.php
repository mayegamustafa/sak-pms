@extends('layouts.app') <!-- Extend your layout file -->

@section('content')
<div class="container">
    <h2>Payments</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tenant ID</th>
                <th>Tenant Name</th>
                <th>Amount Paid</th>
                <th>Payment Date</th>
                <th>Payment Method</th>
                <th>For Month</th>
                <th>For Year</th>
                <th>Invoice ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $payment->tenant_id }}</td>
                    <td>{{ $payment->tenant->name }}</td>
                    <td>{{ number_format($payment->amount_paid, 2) }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->payment_method }}</td>
                    <td>{{ $payment->for_month }}</td>
                    <td>{{ $payment->for_year }}</td>
                    <td>{{ $payment->invoice_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Display pagination links -->
    {{ $payments->links() }}
</div>

@endsection
