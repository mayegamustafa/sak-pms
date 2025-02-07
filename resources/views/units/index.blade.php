@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Units</h2>
    <a href="{{ route('units.create') }}" class="btn btn-primary mb-3">Add Unit</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Unit Number</th>
                <th>Property</th>
                <th>Floor</th>
                <th>Rent Amount (UGX)</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($units as $unit)
                <tr>
                    <td>{{ $unit->unit_number }}</td>
                    <td>{{ $unit->property->name }}</td>
                    <td>{{ $unit->floor }}</td>
                    <td>{{ number_format($unit->rent_amount, 2) }}</td>
                    <td>{{ $unit->status }}</td>
                    <td>
                        <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('units.destroy', $unit->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $units->links() }}
</div>
@endsection