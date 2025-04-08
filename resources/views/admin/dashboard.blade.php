@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-2">Welcome, Super Admin</h1>
<p class="text-gray-600 mb-6">This is the admin dashboard.</p>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 my-6">
    <a href="{{ route('tenants.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        ➕ Add Tenant
    </a>
    <a href="{{ route('units.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
        🏠 Add Unit
    </a>
    <a href="{{ route('leases.index') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow">
        📊 View Leases
    </a>
    <a href="{{ route('properties.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
        🏢 Manage Properties
    </a>
</div>

<!-- Year Selection -->
<div class="mb-6">
    <form method="GET" action="{{ route('admin.dashboard') }}">
        <label for="year" class="text-lg font-semibold">Select Year</label>
        <select name="year" id="year" class="px-3 py-2 border rounded">
            @for($i = now()->year; $i >= 2015; $i--)  <!-- Example: Limiting to the last 10 years -->
                <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
        <button type="submit" class="ml-3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Filter
        </button>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-blue-100 text-blue-800 rounded-lg p-4 text-center shadow">
        <h3 class="text-sm font-medium">Active Tenants</h3>
        <p class="text-xl font-bold">{{ $totalActiveTenants }}</p>
    </div>
    <div class="bg-red-100 text-red-800 rounded-lg p-4 text-center shadow">
        <h3 class="text-sm font-medium">Past Tenants</h3>
        <p class="text-xl font-bold">{{ $totalPastTenants }}</p>
    </div>
    <div class="bg-green-100 text-green-800 rounded-lg p-4 text-center shadow">
        <h3 class="text-sm font-medium">Occupied Units</h3>
        <p class="text-xl font-bold">{{ $totalOccupiedUnits }}</p>
    </div>
    <div class="bg-yellow-100 text-yellow-800 rounded-lg p-4 text-center shadow">
        <h3 class="text-sm font-medium">Occupancy Rate</h3>
        <p class="text-xl font-bold">{{ number_format($occupancyRate, 2) }}%</p>
    </div>
</div>
{{--
<div>Total Active Tenants: {{ $totalActiveTenants }}</div>
<div>Total Past Tenants: {{ $totalPastTenants }}</div>
<div>Total Occupied Units: {{ $totalOccupiedUnits }}</div>
<div>Occupancy Rate: {{ number_format($occupancyRate, 2) }}%</div>
--}}
<!-- 📊 Occupancy Progress Bar -->
<div class="bg-white shadow rounded-lg p-4 mb-10">
    <h2 class="text-sm text-gray-600 mb-2">Occupancy Progress</h2>
    <div class="w-full bg-gray-200 h-4 rounded overflow-hidden">
        <div class="h-4 bg-green-500 transition-all duration-500 ease-in-out" style="width: {{ $occupancyRate }}%"></div>
    </div>
    <p class="text-sm mt-2 text-gray-700">{{ $occupancyRate }}% of units are currently occupied</p>
</div>

<!-- Rent Collection Chart -->
<div class="chart-container">
    <canvas id="rentCollectionChart"></canvas>
</div>

<!-- ✅ Tenant Overview Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

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
   {{-- @foreach ($pastTenants as $tenant)
    <tr>
        <td>{{ $tenant->name }}</td>
        <td>{{ optional($tenant->unit)->unit_number }}</td>
        <td>{{ $tenant->lease_end_date }}</td>
        <td>{{ $tenant->amount_due }}</td>
        <td>{{ ucfirst($tenant->status) }}</td>
    </tr>
@endforeach
{{ $pastTenants->links() }}
 --}}
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
<!-- Rent Collection Chart -->
<div class="chart-container">
    <canvas id="rentCollectionChart"></canvas>
</div>

<!-- Tenant Stats Chart -->
<canvas id="tenantChart" height="100"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get the data passed from the backend
    const tenantStats = {!! json_encode($tenantStats) !!};
    const rentCollectionStats = {!! json_encode($rentCollectionStats) !!};

    const tenantChartCtx = document.getElementById('tenantChart').getContext('2d');
    const rentCollectionChartCtx = document.getElementById('rentCollectionChart').getContext('2d');

    // Tenant Stats Chart
    const tenantChart = new Chart(tenantChartCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // Monthly labels
            datasets: [{
                label: 'New Tenants per Month ({{ $year }})',
                data: Object.values(tenantStats), // Monthly data for the year
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Rent Collection Stats Chart
    const rentCollectionChart = new Chart(rentCollectionChartCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], // Monthly labels
            datasets: [{
                label: 'Rent Collected per Month ({{ $year }})',
                data: Object.values(rentCollectionStats), // Monthly rent collection data
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    ticks: {
                        beginAtZero: true
                    }
                }
            }
        }
    });
</script>

@endsection
