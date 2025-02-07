@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">My Properties</h1>

    <!-- Action Buttons -->
    <div class="mb-4">
        <a href="{{ route('properties.create') }}" class="btn btn-primary">Add Property</a>
        <a href="{{ route('properties.report') }}" class="btn btn-info">View Property Report</a>
        <a href="{{ route('properties.performance-chart') }}" class="btn btn-warning">View Performance Chart</a>
        <a href="{{ route('properties.export') }}" class="btn btn-success">Export to Excel</a>
        <a href="{{ route('properties.export-pdf') }}" class="btn btn-danger">Export to PDF</a>
    </div>

    <!-- Display Property Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3 bg-info text-white">
                <h4>Total Properties</h4>
                <p>{{ $propertyCount }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-success text-white">
                <h4>Total Units</h4>
                <p>{{ $totalUnits }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 bg-warning text-white">
                <h4>Average Rent (UGX)</h4>
                <p>{{ number_format($averageRent, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('properties.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search Name or Location" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="{{ request('min_price') }}">
            </div>
            <div class="col-md-2">
                <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="{{ request('max_price') }}">
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-control">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                    <option value="units" {{ request('sort') == 'units' ? 'selected' : '' }}>Sort by Units</option>
                    <option value="type" {{ request('sort') == 'type' ? 'selected' : '' }}>Sort by Type</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- List of Properties -->
    <div class="row">
        @forelse ($properties as $property)
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $property->name }}</h5>
                        <p class="card-text"><strong>Location:</strong> {{ $property->location }}</p>
                        <p class="card-text"><strong>Type:</strong> {{ $property->type }}</p>
                        <p class="card-text"><strong>Price per Unit (UGX):</strong> {{ number_format($property->price_per_unit, 2) }}</p>
                        <p class="card-text">
                            <strong>Units:</strong> {{ \App\Models\Unit::where('property_id', $property->id)->count() }}
                        </p>
                        <p class="card-text">
                            <strong>Average Rent (UGX):</strong> {{ number_format(\App\Models\Unit::where('property_id', $property->id)->avg('rent_amount'), 2) }}
                        </p>
                        <a href="{{ route('properties.show', $property->id) }}" class="btn btn-primary">View Details</a>
                        <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('properties.destroy', $property->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p>No properties found.</p>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $properties->links() }}
    </div>
</div>
@endsection
