@extends('layouts.app')

@section('content')
    <h1>Terminate Lease</h1>

    <p>Are you sure you want to terminate the lease for {{ $lease->tenant->name }} at {{ $lease->property->name }}?</p>

    <form action="{{ route('leases.terminate', $lease->id) }}" method="POST">
        @csrf
        @method('PUT')
        <button type="submit" style="color: red;">Yes, Terminate</button>
    </form>

    <a href="{{ route('leases.index') }}">Cancel</a>
@endsection
