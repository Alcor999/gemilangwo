@extends('layouts.app')

@section('title', 'Payment Methods - Admin Analytics')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-credit-card"></i> Payment Method Analysis
            </h1>
            <p class="text-muted">Track payment methods used by customers</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.analytics.payments') }}" class="row g-3">
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

    <!-- Pie Chart -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Payment Methods Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="paymentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Total Transactions</p>
                        <h4>{{ $paymentMethods->sum('count') }}</h4>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted small mb-1">Total Revenue</p>
                        <h4>Rp {{ number_format($paymentMethods->sum('amount'), 0, ',', '.') }}</h4>
                    </div>
                    <div>
                        <p class="text-muted small mb-1">Average Transaction</p>
                        <h4>Rp {{ number_format($paymentMethods->sum('amount') / max($paymentMethods->sum('count'), 1), 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-table"></i> Payment Methods Details</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Payment Method</th>
                                <th class="text-end">Transactions</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-end">Average Amount</th>
                                <th class="text-end">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalCount = $paymentMethods->sum('count');
                                $totalAmount = $paymentMethods->sum('amount');
                            @endphp
                            @forelse($paymentMethods as $method)
                                <tr>
                                    <td>
                                        <i class="fas fa-wallet me-2"></i>
                                        <strong>{{ $method['method'] }}</strong>
                                    </td>
                                    <td class="text-end">{{ $method['count'] }}</td>
                                    <td class="text-end">Rp {{ number_format($method['amount'], 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($method['amount'] / max($method['count'], 1), 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">
                                            {{ round(($method['count'] / max($totalCount, 1)) * 100, 2) }}%
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> No payment data available
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-end">{{ $totalCount }}</th>
                                <th class="text-end">Rp {{ number_format($totalAmount, 0, ',', '.') }}</th>
                                <th class="text-end">Rp {{ number_format($totalAmount / max($totalCount, 1), 0, ',', '.') }}</th>
                                <th class="text-end">100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentData = {!! $chartData !!};
        
        const ctx = document.getElementById('paymentChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: paymentData.map(item => item.method),
                datasets: [{
                    data: paymentData.map(item => item.count),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 206, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(153, 102, 255)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>
@endsection
