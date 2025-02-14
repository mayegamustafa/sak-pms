@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Add New Tenant</h1>

    <form method="POST" action="{{ route('tenants.store') }}">
        @csrf

        <!-- Tenant Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Tenant Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <!-- Property Selection -->
        <div class="mb-3">
            <label for="property_id" class="form-label">Select Property</label>
            <select name="property_id" id="property_id" class="form-control" onchange="getAvailableUnits()" required>
                <option value="">-- Select Property --</option>
                @foreach ($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Select Units (Only Vacant) -->
        <div class="mb-3">
            <label for="unit_id" class="form-label">Select Unit</label>
            <select name="unit_id" id="unit_id" class="form-control" required>
                <option value="">-- Select Unit --</option>
                <!-- Vacant units will be populated dynamically -->
            </select>
        </div>

        <!-- Phone Number -->
        <div class="form-group mb-3">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number" required>
        </div>

        <!-- Lease Start Date -->
        <div class="mb-3">
            <label for="lease_start_date" class="form-label">Lease Start Date</label>
            <input type="date" name="lease_start_date" class="form-control" required>
        </div>

        <!-- Rent Amount -->
        <div class="mb-3">
            <label for="rent_amount" class="form-label">Rent Amount (UGX)</label>
            <input type="number" name="rent_amount" class="form-control" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Add Tenant</button>
    </form>
</div>

<script>
    // Function to fetch available vacant units when a property is selected
    function getAvailableUnits() {
        const propertyId = document.getElementById('property_id').value;

        // Ensure a property is selected
        if (propertyId) {
            // Clear previous unit options
            const unitDropdown = document.getElementById('unit_id');
            unitDropdown.innerHTML = '<option value="">-- Select Unit --</option>';

            // Fetch vacant units for the selected property
            fetch(`/properties/${propertyId}/vacant-units`)
                .then(response => response.json())
                .then(data => {
                    if (data.units.length > 0) {
                        // Populate the unit dropdown with vacant units
                        data.units.forEach(unit => {
                            let option = document.createElement('option');
                            option.value = unit.id;
                            option.innerText = `Unit ${unit.unit_number}`;
                            unitDropdown.appendChild(option);
                        });
                    } else {
                        // If no vacant units are available
                        let option = document.createElement('option');
                        option.innerText = "No vacant units available";
                        unitDropdown.appendChild(option);
                    }
                })
                .catch(error => console.error('Error fetching units:', error));
        }
    }
</script>
@endsection
