@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Action Buttons -->
    <div class="mb-8 flex space-x-4 flex-wrap">
        <a href="{{ route('properties.create') }}" class="btn btn-primary px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 mb-4 sm:mb-0">Add Property</a>
        <a href="{{ route('properties.report') }}" class="btn btn-info px-6 py-3 bg-teal-600 text-white rounded-md hover:bg-teal-700 transition duration-300 mb-4 sm:mb-0">Property Report</a>
        <a href="{{ route('properties.performance-chart') }}" class="btn btn-warning px-6 py-3 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition duration-300 mb-4 sm:mb-0">Performance Chart</a>
        <a href="{{ route('properties.export.pdf') }}" class="btn btn-danger px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300 mb-4 sm:mb-0">PDF</a>
        <a href="{{ route('properties.export.excel') }}" class="btn btn-success px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 mb-4 sm:mb-0">Excel</a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success mb-6 p-4 bg-green-100 text-green-800 rounded-md shadow-md">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('properties.index') }}" class="mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="flex flex-col">
                <input type="text" name="search" class="form-control w-full p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search Name or Location" value="{{ request('search') }}">
            </div>
            <div class="flex flex-col">
                <input type="number" name="min_price" class="form-control w-full p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Min Price" value="{{ request('min_price') }}">
            </div>
            <div class="flex flex-col">
                <input type="number" name="max_price" class="form-control w-full p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Max Price" value="{{ request('max_price') }}">
            </div>
            <div class="flex flex-col">
                <select name="sort" class="form-control w-full p-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                    <option value="units" {{ request('sort') == 'units' ? 'selected' : '' }}>Sort by Units</option>
                    <option value="type" {{ request('sort') == 'type' ? 'selected' : '' }}>Sort by Type</option>
                </select>
            </div>
            <div class="flex justify-center sm:justify-start">
                <button type="submit" class="btn btn-primary px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300 w-full sm:w-auto">Filter</button>
            </div>
        </div>
    </form>
    
    <h2 class="text-2xl font-semibold mb-4">Properties List</h2>
    <table class="table table-bordered w-full text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Location</th>
                <th class="px-4 py-2">Units</th>
                <th class="px-4 py-2">Floors</th>
                <th class="px-4 py-2">Manager</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($properties as $property)
            <tr>
                <td class="px-4 py-2">{{ $property->name }}</td>
                <td class="px-4 py-2">{{ $property->type }}</td>
                <td class="px-4 py-2">{{ $property->location }}</td>
                <td class="px-4 py-2">{{ $property->num_units }}</td>
                <td class="px-4 py-2">{{ $property->num_floors ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $property->manager->name ?? 'Unassigned' }}</td>
                <td class="px-4 py-2 flex space-x-2">
                    <a href="{{ route('properties.show', $property->id) }}" class="btn btn-primary px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">View</a>
                    <a href="{{ route('properties.edit', $property->id) }}" class="btn btn-warning px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition duration-300">Edit</a>
                    <form action="{{ route('properties.destroy', $property->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-2 text-center">No properties found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center mt-4">
        {{ $properties->links() }}
    </div>
</div>
@endsection
