@extends('layouts.app')

@section('title', 'Bookings Analysis - Owner Analytics')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-calendar-check"></i> Bookings Analysis
            </h1>
            <p class="text-muted">Track your booking trends and performance</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('owner.analytics.bookings') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Year</label>
                            <select name="year" class="form-select" onchange="this.form.submit()">
                                @for($y = now()->year - 5; $y <= now()->year; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <a href="{{ route('owner.analytics.export', ['type' => 'bookings', 'year' => $year]) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-download"></i> Export
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Bookings</p>
                    <h4>{{ $bookingData->sum('bookings') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <p class="text-muted small mb-1">Completed Bookings</p>
                    <h4>{{ $bookingData->sum('completed') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <p class="text-muted small mb-1">Completion Rate</p>
                    <h4>{{ round(($bookingData->sum('completed') / max($bookingData->sum('bookings'), 1)) * 100, 2) }}%</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <p class="text-muted small mb-1">Avg Bookings/Month</p>
                    <h4>{{ round($bookingData->avg('bookings'), 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Table -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Monthly Bookings</h6>
                </div>
                <div class="card-body">
                    <canvas id="bookingsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Packages -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-fire"></i> Top Packages</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Package</th>
                                <th class="text-end">Orders</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $package)
                                <tr>
                                    <td>{{ Str::limit($package->name, 20) }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ $package->orders_count }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">No data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-table"></i> Monthly Details</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th class="text-end">Total Bookings</th>
                                <th class="text-end">Completed</th>
                                <th class="text-end">Pending</th>
                                <th class="text-end">Completion %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookingData as $item)
                                <tr>
                                    <td>{{ Carbon\Carbon::createFromDate(2024, $item['month'])->format('F') }}</td>
                                    <td class="text-end">{{ $item['bookings'] }}</td>
                                    <td class="text-end"><span class="badge bg-success">{{ $item['completed'] }}</span></td>
                                    <td class="text-end">{{ $item['bookings'] - $item['completed'] }}</td>
                                    <td class="text-end">{{ round(($item['completed'] / max($item['bookings'], 1)) * 100, 1) }}%</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">No booking data</td>
                                </tr>
                            @endforelse
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
        const ctx = document.getElementById('bookingsChart').getContext('2d');
        const data = {!! $chartData !!};

        new Chart(ctx, {
            type: 'bar',
            data: data,
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

<style>
    .border-left-primary {
        border-left: 0.25rem solid #007bff !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #28a745 !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #17a2b8 !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #ffc107 !important;
    }
</style>
@endsection
