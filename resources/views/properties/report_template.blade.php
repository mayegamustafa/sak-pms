<!DOCTYPE html>
<html>
<head>
    <title>Properties Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Properties Report</h2>
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
