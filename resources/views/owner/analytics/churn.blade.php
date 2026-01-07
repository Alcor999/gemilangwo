@extends('layouts.app')

@section('title', 'Churn Analysis - Owner Analytics')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line"></i> Churn Analysis
            </h1>
            <p class="text-muted">Track customer activity and churn trends</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('owner.analytics.churn') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Months to Display</label>
                            <select name="months" class="form-select" onchange="this.form.submit()">
                                <option value="6" {{ $months == 6 ? 'selected' : '' }}>Last 6 Months</option>
                                <option value="12" {{ $months == 12 ? 'selected' : '' }}>Last 12 Months</option>
                                <option value="24" {{ $months == 24 ? 'selected' : '' }}>Last 24 Months</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-area"></i> Active Customers Trend</h6>
                </div>
                <div class="card-body">
                    <canvas id="churnChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Churn Details -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-table"></i> Monthly Active Customers</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th class="text-end">Active Customers</th>
                                <th class="text-end">Change from Previous</th>
                                <th class="text-end">Change %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $prevCount = null;
                            @endphp
                            @forelse($churnData as $item)
                                <tr>
                                    <td>{{ $item['month'] }}</td>
                                    <td class="text-end">
                                        <strong>{{ $item['active_customers'] }}</strong>
                                    </td>
                                    <td class="text-end">
                                        @if($prevCount !== null)
                                            <span class="badge bg-{{ $item['active_customers'] >= $prevCount ? 'success' : 'danger' }}">
                                                {{ $item['active_customers'] - $prevCount > 0 ? '+' : '' }}{{ $item['active_customers'] - $prevCount }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($prevCount !== null && $prevCount > 0)
                                            {{ round((($item['active_customers'] - $prevCount) / $prevCount) * 100, 2) }}%
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $prevCount = $item['active_customers'];
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> No churn data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Insights -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                <h6 class="alert-heading">
                    <i class="fas fa-lightbulb"></i> Insights
                </h6>
                <p class="mb-0">
                    Churn analysis helps you understand customer retention patterns. 
                    An increase in active customers indicates growth, while a decrease might indicate churn. 
                    Monitor trends to identify periods where you need to focus on customer engagement and retention strategies.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const churnData = {!! $chartData !!};
        
        const ctx = document.getElementById('churnChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: churnData.map(item => item.month),
                datasets: [{
                    label: 'Active Customers',
                    data: churnData.map(item => item.active_customers),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: 'rgb(54, 162, 235)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    });
</script>
@endsection
