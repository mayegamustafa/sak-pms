@extends('layouts.app')

@section('content')
    <h1>Renew Lease</h1>

    <form action="{{ route('leases.update', $lease->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Tenant:</label>
        <input type="text" value="{{ $lease->tenant->name }}" disabled>

        <label>Property:</label>
        <input type="text" value="{{ $lease->property->name }}" disabled>

        <label>New End Date:</label>
        <input type="date" name="end_date" value="{{ old('end_date', $lease->end_date) }}" required>

        <button type="submit">Renew Lease</button>
    </form>
@endsection
