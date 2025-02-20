@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Action Buttons -->
    <div class="mb-4">
        <a href="{{ route('properties.create') }}" class="btn btn-primary">Add Property</a>
        <a href="{{ route('properties.report') }}" class="btn btn-info">View Property Report</a>
        <a href="{{ route('properties.performance-chart') }}" class="btn btn-warning">View Performance Chart</a>
        <a href="{{ route('properties.export.pdf') }}" class="btn btn-danger">Export to PDF</a>
        <a href="{{ route('properties.export.excel') }}" class="btn btn-success">Export to Excel</a>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

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
    
    <h2>Properties List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Location</th>
                <th>Units</th>
                <th>Floors</th>
                <th>Manager</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($properties as $property)
            <tr>
                <td>{{ $property->name }}</td>
                <td>{{ $property->type }}</td>
                <td>{{ $property->location }}</td>
                <td>{{ $property->num_units }}</td>
                <td>{{ $property->num_floors ?? 'N/A' }}</td>
                <td>{{ $property->manager->name ?? 'Unassigned' }}</td>
                <td>
                    <a href="{{ route('properties.show', $property->id) }}" class="btn btn-primary btn-sm">View</a>
                    <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('properties.destroy', $property->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">No properties found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $properties->links() }}
    </div>
</div>
@endsection
