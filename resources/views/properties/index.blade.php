@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Properties</h2>
    <a href="{{ route('properties.create') }}" class="btn btn-primary">Add Property</a>
    <a href="{{ route('properties.report') }}" class="btn btn-info">View Property Report</a>
    <a href="{{ route('properties.performance-chart') }}" class="btn btn-warning">View Performance Chart</a>

    <!-- Export buttons -->
    <div class="mb-3">
        <a href="{{ route('properties.export') }}" class="btn btn-success">Export to Excel</a>
        <a href="{{ route('properties.export-pdf') }}" class="btn btn-danger">Export to PDF</a>
    </div>
    
    <!-- Cards for total properties, units, average rent -->
    <div class="row mb-3">
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
                <h4>Average Rent per Unit</h4>
                <p>UGX {{ number_format($averageRent, 2) }}</p>
            </div>
        </div>
    </div>
    
    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('properties.index') }}" class="mb-3">
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
                    <option value="price_per_unit" {{ request('sort') == 'price_per_unit' ? 'selected' : '' }}>Sort by Price</option>
                    <option value="units" {{ request('sort') == 'units' ? 'selected' : '' }}>Sort by Units</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <!-- Properties Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Units</th>
                <th>Price per Unit</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($properties as $property)
                <tr>
                    <td>{{ $property->name }}</td>
                    <td>{{ $property->location }}</td>
                    <td>{{ $property->units }}</td>
                    <td>{{ $property->price_per_unit }}</td>
                    <td>
                        <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('properties.destroy', $property->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
