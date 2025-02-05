<!DOCTYPE html>
<html>
<head>
    <title>Properties</title>
</head>
<body>
    <h1>Property List</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Units</th>
                <th>Price per Unit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($properties as $property)
                <tr>
                    <td>{{ $property->name }}</td>
                    <td>{{ $property->location }}</td>
                    <td>{{ $property->units }}</td>
                    <td>UGX {{ number_format($property->price_per_unit, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
