@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Add New Tenant</h1>

    <form method="POST" action="{{ route('tenants.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Tenant Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="property_id" class="form-label">Select Property</label>
            <select name="property_id" class="form-control" required>
                @foreach ($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
        </div>

     

        <div class="mb-3">
            <label for="lease_start_date" class="form-label">Lease Start Date</label>
            <input type="date" name="lease_start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="rent_amount" class="form-label">Rent Amount (UGX)</label>
            <input type="number" name="rent_amount" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Tenant</button>
    </form>
</div>
@endsection
