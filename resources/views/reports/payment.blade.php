@extends('layouts.app')

@section('content')
    <h1>Payment Report</h1>

    @foreach($leases as $lease)
        <div>{{ $lease->property->name }} - Total Rent Paid: {{ $lease->payments->sum('amount') }}</div>
    @endforeach
@endsection
