@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tenant Management</h1>

    <!-- Action Button -->
    <div class="mb-4">
        <a href="{{ route('tenants.create') }}" class="btn btn-primary">Add New Tenant</a>
    </div>

    <form action="{{ route('tenants.sendBulkSms') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">Send Bulk SMS</button>
</form>

    @foreach ($tenants as $tenant)
<tr>
    <td>{{ $tenant->name }}</td>
    <td>{{ $tenant->phone_number }}</td>
    <td>
        <a href="{{ route('tenants.sendSms', $tenant->id) }}" class="btn btn-primary">
            Send SMS
        </a>
    </td>
</tr>
@endforeach


    <!-- Display Tenants -->
    <div class="row">
        @forelse ($tenants as $tenant)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $tenant->name }}</h5>
                        <p><strong>Property:</strong> {{ $tenant->property->name }}</p>
                        <p><strong>Unit:</strong> {{ $tenant->unit ? $tenant->unit->unit_number : 'N/A' }}</p>
                        <p><strong>Lease Start:</strong> {{ $tenant->lease_start_date }}</p>
                        <p><strong>Rent Amount (UGX):</strong> {{ number_format($tenant->rent_amount, 2) }}</p>

                        <a href="{{ route('tenants.show', $tenant->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('tenants.edit', $tenant->id) }}" class="btn btn-warning">Edit</a>

                        <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>No tenants found.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $tenants->links() }}
    </div>
</div>
@endsection
