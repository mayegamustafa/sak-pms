@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Property</h1>

    <form action="{{ route('properties.store') }}" method="POST">
        
        @csrf

        <!-- Property Name -->
        <div class="form-group">
            <label for="name">Property Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Property</button>
        <!-- Property Type -->
        <div class="form-group">
            <label for="type">Property Type</label>
            <select name="type" id="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="House">House</option>
                <option value="Flat">Flat</option>
            </select>
        </div>

        <!-- Number of Units (Hidden for Flats) -->
        <div class="form-group" id="num_units_div">
            <label for="num_units">Total Number of Units</label>
            <input type="number" name="num_units" id="num_units" class="form-control" min="1" required>
        </div>

        <!-- Unit Price (Hidden for Flats) -->
        <div class="form-group" id="unit_price_div">
            <label for="unit_price">Unit Price (UGX)</label>
            <input type="number" name="unit_price" id="unit_price" class="form-control" min="0" required>
        </div>
        
        <!-- Location -->
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" name="location" id="location" class="form-control" required>
        </div>

        <!-- Owner (Dropdown for Owner Role) -->
        <div class="form-group">
            <label for="owner_id">Owner</label>
            <select name="owner_id" id="owner_id" class="form-control" required>
                <option value="">Select Owner</option>
                @foreach($owners as $owner)
                    <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Property Manager -->
        <div class="form-group">
            <label for="manager_id">Property Manager</label>
            <select name="manager_id" id="manager_id" class="form-control">
                <option value="">Select Manager</option>
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Extra Flat Details -->
        <div id="flat-details" style="display: none; border: 1px solid #ccc; padding: 15px; margin-top: 15px;">
            <h4>Flat Details</h4>
            <!-- Number of Floors -->
            <div class="form-group">
                <label for="num_floors">Number of Floors</label>
                <input type="number" name="num_floors" id="num_floors" class="form-control" min="1">
            </div>

            <!-- Optional: Specify units per floor -->
            <div id="floors-units-container">
                <!-- JavaScript will populate this area based on the number of floors -->
            </div>

            <!-- Rent Amount Per Floor -->
            <div id="rent-per-floor-container" style="display: none;">
                <h4>Set Rent Per Floor</h4>
                <div id="rent-per-floor-fields"></div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Create Property</button>
    </form>
</div>

<script>
    // Show/hide flat details based on property type selection
    document.getElementById('type').addEventListener('change', function(){
        if(this.value === 'Flat'){
            document.getElementById('flat-details').style.display = 'block';
            document.getElementById('num_units_div').style.display = 'none';
            document.getElementById('unit_price_div').style.display = 'none';
        } else {
            document.getElementById('flat-details').style.display = 'none';
            document.getElementById('num_units_div').style.display = 'block';
            document.getElementById('unit_price_div').style.display = 'block';
        }
    });

    // When the number of floors is entered, create inputs for units per floor
    document.getElementById('num_floors').addEventListener('change', function(){
        var numFloors = parseInt(this.value);
        var container = document.getElementById('floors-units-container');
        container.innerHTML = ''; // Clear previous inputs

        for(var i = 1; i <= numFloors; i++){
            // Create a new input for each floor
            var div = document.createElement('div');
            div.className = 'form-group';
            div.innerHTML = '<label for="floors_units_' + i + '">Units on Floor ' + i + ':</label>' +
                '<input type="number" name="floors_units[' + i + ']" id="floors_units_' + i + '" class="form-control" min="0" required>';
            container.appendChild(div);
        }

        // Show rent per floor fields after the number of floors is set
        document.getElementById('rent-per-floor-container').style.display = 'block';

        var rentContainer = document.getElementById('rent-per-floor-fields');
        rentContainer.innerHTML = ''; // Clear previous rent inputs

        for (var j = 1; j <= numFloors; j++) {
            var rentDiv = document.createElement('div');
            rentDiv.className = 'form-group';
            rentDiv.innerHTML = '<label for="rent_per_floor_' + j + '">Rent for Floor ' + j + ' (UGX):</label>' +
                '<input type="number" name="rent_per_floor[' + j + ']" id="rent_per_floor_' + j + '" class="form-control" min="0" required>';
            rentContainer.appendChild(rentDiv);
        }
    });
</script>
@endsection
