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
                    <td>
    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning">Edit</a>
    <a href="{{ route('payments.create', $invoice->id) }}" class="btn btn-sm btn-success">Record Payment</a>
    <a href="{{ route('invoices.download', $invoice->id) }}" class="btn btn-sm btn-primary">Download PDF</a>
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

    {{ $invoices->links() }} <!-- For pagination if you're using it -->
</div>
@endsection
