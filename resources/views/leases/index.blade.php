@extends('layouts.app')

@section('content')

    <h1>Lease List</h1>

    @foreach($leases as $lease)
        @if($lease->tenant && $lease->property) <!-- Check if tenant and property exist -->
            <div>{{ $lease->tenant->name }} - {{ $lease->property->name }} - {{ $lease->start_date }} to {{ $lease->end_date }}</div>
        @else
            <div>Lease with missing tenant or property</div>
        @endif
    @endforeach

    <a href="{{ route('leases.create') }}">Create Lease</a>

@endsection
