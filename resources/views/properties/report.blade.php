{{-- resources/views/properties/report.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Property Report</h2>
    <div class="card">
        <div class="card-body">
            <h5>Total Properties: {{ $totalProperties }}</h5>
            <h5>Total Units: {{ $totalUnits }}</h5>
            <h5>Average Rent per Unit: UGX {{ number_format($averageRent, 2) }}</h5>
        </div>
    </div>
</div>
@endsection
