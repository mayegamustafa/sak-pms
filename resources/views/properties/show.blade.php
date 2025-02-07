@extends('layouts.app') <!-- Adjust this if you're using a different layout -->

@section('content')
    <div class="container">
        <h1>{{ $property->name }}</h1>
        <p><strong>Location:</strong> {{ $property->location }}</p>
        <p><strong>Price:</strong> UGX {{ number_format($property->price) }}</p>
        <p><strong>Type:</strong> {{ $property->type }}</p>
        <p><strong>Description:</strong> {{ $property->description }}</p>
        
        <!-- Add other property details as needed -->

        <a href="{{ route('properties.index') }}" class="btn btn-primary">Back to Properties</a>
    </div>
@endsection
