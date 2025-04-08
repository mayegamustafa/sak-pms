@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Tenant Management</h1>

    <!-- ✅ Tenant Overview Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Active Tenants -->
       <!-- Active Tenants -->
<div class="bg-white shadow rounded-lg p-4">
    <h2 class="text-lg font-semibold mb-3 text-blue-700">Active Tenants</h2>
    <table class="w-full text-sm text-left">
        <thead>
            <tr class="border-b text-gray-600">
                <th>Name</th>
                <th>Unit</th>
                <th>Lease End</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activeTenants as $tenant)
                <tr class="border-b hover:bg-gray-50">
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->unit->unit_number }}</td>
                    <td>{{ $tenant->lease_end_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
       {{-- {{ $activeTenants->appends(request()->except('active_page'))->links('pagination::tailwind') }}  --}}
    </div>
</div>

<!-- Past Tenants -->
<div class="bg-white shadow rounded-lg p-4">
    <h2 class="text-lg font-semibold mb-3 text-red-700">Past Tenants</h2>
    <table class="w-full text-sm text-left">
        <thead>
            <tr class="border-b text-gray-600">
                <th>Name</th>
                <th>Unit</th>
                <th>Lease End</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pastTenants as $tenant)
                <tr class="border-b hover:bg-gray-50">
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->unit->unit_number }}</td>
                    <td>{{ $tenant->lease_end_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $pastTenants->appends(request()->except('past_page'))->links('pagination::tailwind') }}
    </div>
</div>

<!-- Vacant Units -->
<div class="bg-white shadow rounded-lg p-4">
    <h2 class="text-lg font-semibold mb-3 text-green-700">Vacant Units</h2>
    <table class="w-full text-sm text-left">
        <thead>
            <tr class="border-b text-gray-600">
                <th>Unit</th>
                <th>Property</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vacantUnits as $unit)
                <tr class="border-b hover:bg-gray-50">
                    <td>{{ $unit->unit_number }}</td>
                    <td>{{ $unit->property->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $vacantUnits->appends(request()->except('vacant_page'))->links('pagination::tailwind') }}
    </div>
</div>
</div>
</div>
    <!-- ✅ Actions -->
    <div class="flex flex-wrap gap-4 mb-6">
        <a href="{{ route('tenants.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Add New Tenant
        </a>

        <form action="{{ route('tenants.sendBulkSms') }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Send Bulk SMS
            </button>
        </form>
    </div>

    <!-- ✅ Individual Tenants (Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse ($tenants as $tenant)
            <div class="bg-white rounded-lg shadow p-5">
                <h3 class="text-xl font-semibold mb-2">{{ $tenant->name }}</h3>
                <p class="text-sm text-gray-700"><strong>Property:</strong> {{ $tenant->property->name }}</p>
                <p class="text-sm text-gray-700"><strong>Unit:</strong> {{ $tenant->unit ? $tenant->unit->unit_number : 'N/A' }}</p>
                <p class="text-sm text-gray-700"><strong>Lease Start:</strong> {{ $tenant->lease_start_date }}</p>
                <p class="text-sm text-gray-700"><strong>Rent Amount (UGX):</strong> {{ number_format($tenant->rent_amount, 2) }}</p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('tenants.show', $tenant->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">View</a>
                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">Edit</a>
                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('Are you sure?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-600">No tenants found.</p>
        @endforelse
    </div>

    <!-- ✅ Pagination -->
    <div class="flex justify-center">
        {{ $tenants->links() }}
    </div>
</div>
@endsection
