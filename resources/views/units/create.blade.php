@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Unit</h2>
    <form action="{{ route('units.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="property_id" class="form-label">Property</label>
            <select name="property_id" class="form-control" required>
                @foreach($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="unit_number" class="form-label">Unit Number</label>
            <input type="text" name="unit_number" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="floor" class="form-label">Floor (optional)</label>
            <input type="number" name="floor" class="form-control">
        </div>

        <div class="mb-3">
            <label for="rent_amount" class="form-label">Rent Amount (UGX)</label>
            <input type="number" name="rent_amount" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="Occupied">Occupied</option>
                <option value="Vacant">Vacant</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Add Unit</button>
    </form>
</div>
@endsection
