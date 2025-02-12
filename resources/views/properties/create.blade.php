@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Property</h2>
    <form method="POST" action="{{ route('properties.store') }}">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" required>
        </div>
        <div class="mb-3">
        <label for="units">Units:</label>
        <input type="number" name="units" id="units" required>
</div>
        <div class="mb-3">
            <label>Type</label>
            <select name="type" class="form-control" required>
                <option value="Apartment">Apartment</option>
                <option value="Flat">Flat</option>
                <option value="Bungalow">Bungalow</option>
                <!-- Add more types here if needed -->
            </select>
        </div>
      {{--  <div class="mb-3">
            <label>Units</label>
            <input type="number" name="units" class="form-control" required>
        </div>--}}
        <button type="submit" class="btn btn-success">Save Property</button>
    </form>
</div>
@endsection
