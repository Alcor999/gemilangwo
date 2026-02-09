@extends('layouts.app')

@section('title', 'Laporan Pendapatan - Analitik Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar"></i> Laporan Pendapatan
            </h1>
            <p class="text-muted">Pantau tren pendapatan dari waktu ke waktu</p>
        </div>
    </div>

    <!-- Penyaringan -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.analytics.revenue') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Periode</label>
                            <select name="period" class="form-select" onchange="this.form.submit()">
                                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Harian</option>
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        @if($period !== 'yearly')
                            <div class="col-md-3">
                                <label class="form-label">Tahun</label>
                                <select name="year" class="form-select" onchange="this.form.submit()">
                                    @for($y = now()->year - 5; $y <= now()->year; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        @endif
                        @if($period === 'daily')
                            <div class="col-md-3">
                                <label class="form-label">Bulan</label>
                                <select name="month" class="form-select" onchange="this.form.submit()">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                            {{ Carbon\Carbon::createFromDate(2024, $m)->locale('id_ID')->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <a href="{{ route('admin.analytics.export', ['type' => 'revenue', 'period' => $period, 'year' => $year]) }}" class="btn btn-outline-primary btn-sm w-100">
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
                <div class="card-header bg-light">
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
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-table"></i> Detail Pendapatan</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ $period === 'daily' ? 'Tanggal' : ($period === 'monthly' ? 'Bulan' : 'Tahun') }}</th>
                                <th class="text-end">Pendapatan</th>
                                <th class="text-end">Transaksi</th>
                                <th class="text-end">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revenueData as $item)
                                <tr>
                                    <td>
                                        @if($period === 'daily')
                                            {{ $item['date'] }}
                                        @elseif($period === 'monthly')
                                            {{ $item['month'] }}
                                        @else
                                            {{ $item['year'] }}
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($item['revenue'], 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">{{ $item['transactions'] }}</td>
                                    <td class="text-end">
                                        Rp {{ number_format($item['revenue'] / max($item['transactions'], 1), 0, ',', '.') }}
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
                                <th class="text-end">{{ $revenueData->sum('transactions') }}</th>
                                <th class="text-end">
                                    Rp {{ number_format($revenueData->sum('revenue') / max($revenueData->sum('transactions'), 1), 0, ',', '.') }}
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
