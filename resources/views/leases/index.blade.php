@extends('layouts.app')

@section('content')
    <h1>Lease List</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <table border="1">
    <thead>
    <tr>
        <th>Tenant</th>
        <th>Property</th>
        <th>Lease Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @foreach($leases as $lease)
        <tr>
            <td>{{ $lease->tenant->name ?? 'Unknown' }}</td>
            <td>{{ $lease->property->name ?? 'Unknown' }}</td>
            <td>{{ ucfirst($lease->tenant->lease_type) }}</td>
            <td>{{ $lease->start_date }}</td>
            <td>{{ $lease->end_date }}</td>
            <td>
                @if($lease->status == 'active')
                    <span style="color: green;">Active</span>
                @elseif($lease->status == 'expired')
                    <span style="color: red;">Expired</span>
                @elseif($lease->status == 'terminated')
                    <span style="color: gray;">Terminated</span>
                @endif
            </td>
            <td>
                @if($lease->status == 'expired')
                    <form action="{{ route('leases.renew', $lease->id) }}" method="POST" style="display:inline;">
                        @csrf @method('PUT')
                        <button type="submit">Renew</button>
                    </form>
                @endif
                <form action="{{ route('leases.terminate', $lease->id) }}" method="POST" style="display:inline;">
                    @csrf @method('PUT')
                    <button type="submit" style="color: red;">Terminate</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>

    </table>

    <a href="{{ route('leases.create') }}">Create Lease</a>
@endsection
