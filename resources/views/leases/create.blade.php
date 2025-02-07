@extends('layouts.app')

@section('content')
    <h1>Create Lease</h1>

    <form action="{{ route('leases.store') }}" method="POST">
        @csrf
        <select name="tenant_id">
            @foreach($tenants as $tenant)
                <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
            @endforeach
        </select>

        <select name="property_id">
            @foreach($properties as $property)
                <option value="{{ $property->id }}">{{ $property->name }}</option>
            @endforeach
        </select>

        <input type="date" name="start_date" required>
        <input type="date" name="end_date" required>

        <button type="submit">Create Lease</button>
    </form>
@endsection

