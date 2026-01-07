@extends('layouts.app')

@section('title', 'Conversion Funnel - Admin Analytics')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-funnel"></i> Conversion Funnel Analysis
            </h1>
            <p class="text-muted">Track customer journey from visitor to payment</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.analytics.conversion') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Period</label>
                            <select name="period" class="form-select" onchange="this.form.submit()">
                                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select" onchange="this.form.submit()">
                                @for($y = now()->year - 5; $y <= now()->year; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Funnel Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Funnel Visualization</h6>
                </div>
                <div class="card-body">
                    <canvas id="funnelChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Funnel Details -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-table"></i> Funnel Stages</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Stage</th>
                                <th class="text-end">Count</th>
                                <th class="text-end">Percentage</th>
                                <th class="text-end">Drop-off Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $previousCount = null;
                            @endphp
                            @foreach($funnelData as $stage)
                                <tr>
                                    <td>
                                        <i class="fas fa-arrow-right"></i> {{ $stage['stage'] }}
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ $stage['count'] }}</strong>
                                    </td>
                                    <td class="text-end">{{ $stage['percentage'] }}%</td>
                                    <td class="text-end">
                                        @if($previousCount !== null)
                                            {{ round((1 - ($stage['count'] / $previousCount)) * 100, 2) }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $previousCount = $stage['count'];
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const funnelData = {!! $chartData !!};
        
        const ctx = document.getElementById('funnelChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: funnelData.map(item => item.stage),
                datasets: [{
                    label: 'Users',
                    data: funnelData.map(item => item.count),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                    ],
                    borderColor: [
                        'rgb(54, 162, 235)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                        'rgb(255, 159, 64)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                    }
                }
            }
        });
    });
</script>
@endsection
