@extends('layouts.app')

@section('title', 'Analisis Churn - Analitik Owner')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line"></i> Analisis Churn
            </h1>
            <p class="text-muted">Pantau aktivitas pelanggan dan tren churn</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('owner.analytics.churn') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Jumlah Bulan</label>
                            <select name="months" class="form-select" onchange="this.form.submit()">
                                <option value="6" {{ $months == 6 ? 'selected' : '' }}>6 Bulan Terakhir</option>
                                <option value="12" {{ $months == 12 ? 'selected' : '' }}>12 Bulan Terakhir</option>
                                <option value="24" {{ $months == 24 ? 'selected' : '' }}>24 Bulan Terakhir</option>
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
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h6 class="mb-0"><i class="fas fa-chart-area"></i> Tren Pelanggan Aktif</h6>
                </div>
                <div class="card-body">
                    <canvas id="churnChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Churn -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h6 class="mb-0"><i class="fas fa-table"></i> Pelanggan Aktif per Bulan</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bulan</th>
                                <th class="text-end">Pelanggan Aktif</th>
                                <th class="text-end">Perubahan dari Sebelumnya</th>
                                <th class="text-end">Perubahan %</th>
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
                                        <i class="fas fa-inbox"></i> Tidak ada data churn
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Insight -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                <h6 class="alert-heading">
                    <i class="fas fa-lightbulb"></i> Insight
                </h6>
                <p class="mb-0">
                    Analisis churn membantu Anda memahami pola retensi pelanggan.
                    Peningkatan pelanggan aktif menandakan pertumbuhan, sedangkan penurunan bisa mengindikasikan churn.
                    Pantau tren untuk mengidentifikasi periode yang memerlukan fokus pada engagement dan strategi retensi pelanggan.
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
                    label: 'Pelanggan Aktif',
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
