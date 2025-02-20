<form method="POST" action="{{ route('tenants.store') }}">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Tenant Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
 <!-- Phone Number -->
 <div class="form-group mb-3">
            <label for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number" required>
        </div>
        
    <div class="mb-3">
        <label for="property_id" class="form-label">Select Property</label>
        <select name="property_id" id="property_id" class="form-control" onchange="getAvailableUnits()" required>
            <option value="">-- Select Property --</option>
            @foreach ($properties as $property)
                <option value="{{ $property->id }}">{{ $property->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="unit_id" class="form-label">Select Unit</label>
        <select name="unit_id" id="unit_id" class="form-control" onchange="updateRentAmount()" required>
            <option value="">-- Select Unit --</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="lease_start_date" class="form-label">Lease Start Date</label>
        <input type="date" name="lease_start_date" id="lease_start_date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="months_paid" class="form-label">Months Paid</label>
        <input type="number" name="months_paid" id="months_paid" class="form-control" min="1" required>
    </div>

    <div class="mb-3">
        <label for="rent_amount" class="form-label">Rent Amount (UGX)</label>
        <input type="text" id="rent_amount" class="form-control" disabled>
    </div>

    <button type="submit" class="btn btn-primary">Add Tenant</button>
</form>

<script>
    function getAvailableUnits() {
        const propertyId = document.getElementById('property_id').value;

        if (propertyId) {
            fetch(`/properties/${propertyId}/vacant-units`)
                .then(response => response.json())
                .then(data => {
                    const unitDropdown = document.getElementById('unit_id');
                    unitDropdown.innerHTML = '<option value="">-- Select Unit --</option>';

                    data.units.forEach(unit => {
                        let option = document.createElement('option');
                        option.value = unit.id;
                        option.innerText = `Unit ${unit.unit_number} - UGX ${unit.rent_amount}`;
                        option.dataset.rent = unit.rent_amount; // Store rent amount in dataset
                        unitDropdown.appendChild(option);
                    });
                });
        }
    }

    function updateRentAmount() {
        const unitDropdown = document.getElementById('unit_id');
        const rentField = document.getElementById('rent_amount');

        const selectedOption = unitDropdown.options[unitDropdown.selectedIndex];
        rentField.value = selectedOption.dataset.rent || "0";
    }
</script>
