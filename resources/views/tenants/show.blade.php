@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tenant Details</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>Name:</strong> {{ $tenant->name }}</p>
            <p><strong>Email:</strong> {{ $tenant->email }}</p>
            <p><strong>Phone:</strong> {{ $tenant->phone_number }}</p>
            <p><strong>Property:</strong> {{ $tenant->property->name ?? 'N/A' }}</p>
            <p><strong>Unit:</strong> {{ $tenant->unit->unit_number ?? 'N/A' }}</p>
            <p><strong>Start Date:</strong> {{ $tenant->start_date }}</p>
            <p><strong>Status:</strong> {{ ucfirst($tenant->status) }}</p>
        </div>
    </div>

    <a href="{{ route('tenants.index') }}" class="btn btn-secondary mt-3">Back to Tenants</a>
</div>
@endsection
