@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <h1>Welcome, Property Owner</h1>
    <p>This is the owner dashboard.</p>

    {{-- Monthly Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">New Tenants ({{ now()->format('F') }})</h2>
            <p class="text-2xl text-blue-600">{{ $monthlyNewTenants }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Rent Collected ({{ now()->format('F') }})</h2>
            <p class="text-2xl text-green-600">UGX {{ number_format($monthlyRentCollected) }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Vacant Units</h2>
            <p class="text-2xl text-red-600">{{ $vacantUnitsCount }}</p>
        </div>
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-semibold">Total Units</h2>
            <p class="text-2xl text-gray-800">{{ $totalUnitsCount }}</p>
        </div>
    </div>

    {{-- Export Buttons --}}
    <div class="flex gap-4 mb-4">
        <a href="{{ route('owner.export', ['type' => 'tenants']) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Export Tenants
        </a>
        <a href="{{ route('owner.export', ['type' => 'units']) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Export Units
        </a>
    </div>

    {{-- Active Tenants --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h2 class="text-xl font-bold mb-2">Active Tenants</h2>
        <table class="w-full table-auto text-sm">
            <thead>
                <tr>
                    <th class="text-left p-2">Name</th>
                    <th class="text-left p-2">Phone</th>
                    <th class="text-left p-2">Property</th>
                    <th class="text-left p-2">Unit</th>
                    <th class="text-left p-2">Start Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activeTenants as $tenant)
                <tr class="border-t">
                    <td class="p-2">{{ $tenant->name }}</td>
                    <td class="p-2">{{ $tenant->phone }}</td>
                    <td class="p-2">{{ $tenant->property->name ?? 'N/A' }}</td>
                    <td class="p-2">{{ $tenant->unit->unit_number ?? 'N/A' }}</td>
                    <td class="p-2">{{ \Carbon\Carbon::parse($tenant->start_date)->format('d-M-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $activeTenants->links() }}
        </div>
    </div>

    {{-- Past Tenants --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h2 class="text-xl font-bold mb-2">Past Tenants</h2>
        <table class="w-full table-auto text-sm">
            <thead>
                <tr>
                    <th class="text-left p-2">Name</th>
                    <th class="text-left p-2">Phone</th>
                    <th class="text-left p-2">Property</th>
                    <th class="text-left p-2">Unit</th>
                    <th class="text-left p-2">End Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pastTenants as $tenant)
                <tr class="border-t">
                    <td class="p-2">{{ $tenant->name }}</td>
                    <td class="p-2">{{ $tenant->phone }}</td>
                    <td class="p-2">{{ $tenant->property->name ?? 'N/A' }}</td>
                    <td class="p-2">{{ $tenant->unit->unit_number ?? 'N/A' }}</td>
                    <td class="p-2">{{ \Carbon\Carbon::parse($tenant->end_date)->format('d-M-Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $pastTenants->links() }}
        </div>
    </div>

    {{-- Vacant Units --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h2 class="text-xl font-bold mb-2">Vacant Units</h2>
        <table class="w-full table-auto text-sm">
            <thead>
                <tr>
                    <th class="text-left p-2">Unit Number</th>
                    <th class="text-left p-2">Property</th>
                    <th class="text-left p-2">Rent</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vacantUnits as $unit)
                <tr class="border-t">
                    <td class="p-2">{{ $unit->unit_number }}</td>
                    <td class="p-2">{{ $unit->property->name ?? 'N/A' }}</td>
                    <td class="p-2">UGX {{ number_format($unit->rent_amount) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center p-2">No vacant units available</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
