@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Unit</h2>
    <form action="{{ route('units.update', $unit->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="property_id" class="form-label">Property</label>
            <select name="property_id" class="form-control" required>
                @foreach($properties as $property)
                    <option value="{{ $property->id }}" {{ $unit->property_id == $property->id ? 'selected' : '' }}>{{ $property->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="unit_number" class="form-label">Unit Number</label>
            <input type="text" name="unit_number" class="form-control" value="{{ $unit->unit_number }}" required>
        </div>

        <div class="mb-3">
            <label for="floor" class="form-label">Floor (optional)</label>
            <input type="number" name="floor" class="form-control" value="{{ $unit->floor }}">
        </div>

        <div class="mb-3">
            <label for="rent_amount" class="form-label">Rent Amount (UGX)</label>
            <input type="number" name="rent_amount" class="form-control" value="{{ $unit->rent_amount }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="Occupied" {{ $unit->status == 'Occupied' ? 'selected' : '' }}>Occupied</option>
                <option value="Vacant" {{ $unit->status == 'Vacant' ? 'selected' : '' }}>Vacant</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Unit</button>
    </form>
</div>
@endsection

