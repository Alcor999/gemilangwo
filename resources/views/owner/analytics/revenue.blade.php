@extends('layouts.app')

@section('title', 'Laporan Pendapatan - Analitik Owner')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar"></i> Laporan Pendapatan
            </h1>
            <p class="text-muted">Pantau tren pendapatan Anda</p>
        </div>
    </div>

    <!-- Penyaringan -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('owner.analytics.revenue') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Periode</label>
                            <select name="period" class="form-select" onchange="this.form.submit()">
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun</label>
                            <select name="year" class="form-select" onchange="this.form.submit()">
                                @for($y = now()->year - 5; $y <= now()->year; $y++)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <a href="{{ route('owner.analytics.export', ['type' => 'revenue', 'period' => $period, 'year' => $year]) }}" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-download"></i> Ekspor PDF
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Tren Pendapatan</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h6 class="mb-0"><i class="fas fa-table"></i> Detail Pendapatan</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ $period === 'monthly' ? 'Bulan' : 'Tahun' }}</th>
                                <th class="text-end">Pendapatan</th>
                                <th class="text-end">Pesanan</th>
                                <th class="text-end">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revenueData as $item)
                                <tr>
                                    <td>{{ $item['month'] ?? $item['year'] }}</td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($item['revenue'], 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">{{ $item['orders'] }}</td>
                                    <td class="text-end">
                                        Rp {{ number_format($item['revenue'] / max($item['orders'], 1), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> Tidak ada data pendapatan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>Total</th>
                                <th class="text-end">
                                    <strong>Rp {{ number_format($revenueData->sum('revenue'), 0, ',', '.') }}</strong>
                                </th>
                                <th class="text-end">{{ $revenueData->sum('orders') }}</th>
                                <th class="text-end">
                                    Rp {{ number_format($revenueData->sum('revenue') / max($revenueData->sum('orders'), 1), 0, ',', '.') }}
                                </th>
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
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const data = {!! $chartData !!};

        new Chart(ctx, {
            type: 'line',
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
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
