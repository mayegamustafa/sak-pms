{{-- resources/views/properties/chart.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Property Performance</h2>
    <canvas id="propertyChart"></canvas>
</div>

<script>
    var ctx = document.getElementById('propertyChart').getContext('2d');
    var propertyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Units per Property',
                data: @json($data),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
