@extends('layouts.app')

@section('content')
    <h1>Edit Lease</h1>

    <form action="{{ route('leases.update', $lease->id) }}" method="POST">
        @csrf
        @method('PUT')

        <select name="tenant_id">
            @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}" {{ $tenant->id == $lease->tenant_id ? 'selected' : '' }}>
                    {{ $tenant->name }}
                </option>
            @endforeach
        </select>

        <select name="property_id">
            @foreach($properties as $property)
                <option value="{{ $property->id }}" {{ $property->id == $lease->property_id ? 'selected' : '' }}>
                    {{ $property->name }}
                </option>
            @endforeach
        </select>

        <input type="date" name="start_date" value="{{ $lease->start_date }}" required>
        <input type="date" name="end_date" value="{{ $lease->end_date }}" required>

        <button type="submit">Update Lease</button>
    </form>
@endsection
