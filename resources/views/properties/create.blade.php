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
            <label>Units</label>
            <input type="number" name="units" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Price per Unit</label>
            <input type="number" step="0.01" name="price_per_unit" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Save Property</button>
    </form>
</div>
@endsection
