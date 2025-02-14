@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Property</h2>
    <form action="{{ route('properties.store') }}" method="POST">
        @csrf

        <label>Property Name:</label>
        <input type="text" name="name" required>

        <label>Type:</label>
        <select name="type" id="propertyType" required>
            <option value="House">House</option>
            <option value="Flat">Flat</option>
        </select>

        <label>Location:</label>
<input type="text" name="location" required>


        <div id="floorSection" style="display: none;">
            <label>Number of Floors:</label>
            <input type="number" name="num_floors" id="numFloors">
        </div>

        <label>Number of Units:</label>
        <input type="number" name="num_units" required>

        <label>Assign Manager:</label>
        <select name="manager_id" required>
            @foreach($managers as $manager)
                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
            @endforeach
        </select>

        <button type="submit">Add Property</button>
    </form>
</div>

<script>
    document.getElementById("propertyType").addEventListener("change", function () {
        document.getElementById("floorSection").style.display = (this.value === "Flat") ? "block" : "none";
    });
</script>
@endsection
