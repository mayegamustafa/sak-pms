@extends('layouts.app')

@section('content')
    <h1>Create Lease</h1>

    <form action="{{ route('leases.store') }}" method="POST">
        @csrf

        <label>Tenant:</label>
        <select name="tenant_id" id="tenant_id" required>
            <option value="">-- Select Tenant --</option>
            @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}" data-lease-type="{{ $tenant->lease_type }}">
                    {{ $tenant->name }} ({{ ucfirst($tenant->lease_type) }} Lease)
                </option>
            @endforeach
        </select>

        <label>Property:</label>
        <select name="property_id" required>
            @foreach($properties as $property)
                <option value="{{ $property->id }}">{{ $property->name }}</option>
            @endforeach
        </select>

        <label>Start Date:</label>
        <input type="date" name="start_date" id="start_date" required>

        <label>End Date:</label>
        <input type="date" name="end_date" id="end_date" required>

        <button type="submit">Create Lease</button>
    </form>

    <script>
        document.getElementById("tenant_id").addEventListener("change", function() {
            let selectedTenant = this.options[this.selectedIndex];
            let leaseType = selectedTenant.getAttribute("data-lease-type");
            let startDate = document.getElementById("start_date").value;
            
            if (startDate) {
                let start = new Date(startDate);
                let endDateField = document.getElementById("end_date");

                if (leaseType === "monthly") {
                    start.setMonth(start.getMonth() + 1);
                } else if (leaseType === "yearly") {
                    start.setFullYear(start.getFullYear() + 1);
                }

                endDateField.value = start.toISOString().split("T")[0];
            }
        });

        document.getElementById("start_date").addEventListener("change", function() {
            document.getElementById("tenant_id").dispatchEvent(new Event("change"));
        });
    </script>
@endsection
