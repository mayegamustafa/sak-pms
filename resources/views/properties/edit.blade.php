@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Property</h2>
    <form method="POST" action="{{ route('properties.update', $property->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ $property->name }}" required>
        </div>
        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" value="{{ $property->location }}" required>
        </div>
        <div class="mb-3">
            <label>Units</label>
            <input type="number" name="units" class="form-control" value="{{ $property->units }}" required>
        </div>
        <div class="mb-3">
            <label>Price per Unit</label>
            <input type="number" step="0.01" name="price_per_unit" class="form-control" value="{{ $property->price_per_unit }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Property</button>
    </form>
</div>
@endsection
