<!-- resources/views/invoices/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Invoices</h1>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary mb-3">Create Invoice</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tenant</th>
                <th>Invoice Date</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->id }}</td>
                    <td>{{ $invoice->tenant->name }}</td>
                    <td>{{ $invoice->invoice_date }}</td>
                    <td>{{ $invoice->due_date }}</td>
                    <td>UGX {{ number_format($invoice->amount) }}</td>
                    <td>{{ ucfirst($invoice->status) }}</td>
                    <td>
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $invoices->links() }}

    <!-- resources/views/invoices/index.blade.php -->
<!-- Add this inside the invoice action buttons (Edit/Delete) -->

<form action="{{ route('invoices.pay', $invoice->id) }}" method="POST">
    @csrf
    <label for="paid_amount">Paid Amount:</label>
    <input type="number" name="paid_amount" min="0" max="{{ $invoice->amount - $invoice->paid_amount }}" value="0" required>
    <button type="submit">Pay</button>
</form>

<!-- resources/views/invoices/index.blade.php -->

@foreach($invoices as $invoice)
    <div>
        <h2>{{ $invoice->title }}</h2>
        <!-- Other invoice details -->
        
        <!-- Link to generate PDF for this invoice -->
        <a href="{{ route('invoices.pdf', ['id' => $invoice->id]) }}" class="btn btn-primary">
            Download PDF
        </a>
    </div>
@endforeach

</div>
@endsection
