@extends('layouts.app')

@section('title', 'Performa Paket - Analitik Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-box"></i> Analisis Performa Paket
            </h1>
            <p class="text-muted">Pantau paket mana yang performanya paling baik</p>
        </div>
    </div>

    <!-- Penyaringan -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.analytics.packages') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Periode</label>
                            <select name="period" class="form-select" onchange="this.form.submit()">
                                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulanan</option>
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
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Pemesanan per Paket</h6>
                </div>
                <div class="card-body">
                    <canvas id="packagesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Paket -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-table"></i> Detail Performa Paket</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Paket</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Total Pesanan</th>
                                <th class="text-end">Total Pendapatan</th>
                                <th class="text-end">Rata-rata</th>
                                <th class="text-end">% dari Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalRevenue = $packages->sum('total_revenue');
                                $totalBookings = $packages->sum('total_bookings');
                            @endphp
                            @forelse($packages as $package)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.packages.show', $package->id) }}" class="text-decoration-none">
                                            <strong>{{ $package->name }}</strong>
                                        </a>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($package->price, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        <span class="badge bg-primary">{{ $package->total_bookings }}</span>
                                    </td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($package->total_revenue, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($package->total_revenue / max($package->total_bookings, 1), 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        {{ round(($package->total_revenue / max($totalRevenue, 1)) * 100, 2) }}%
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> Tidak ada data paket
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2">Total</th>
                                <th class="text-end">{{ $totalBookings }}</th>
                                <th class="text-end">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</th>
                                <th class="text-end">Rp {{ number_format($totalRevenue / max($totalBookings, 1), 0, ',', '.') }}</th>
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
        const ctx = document.getElementById('packagesChart').getContext('2d');
        const data = {!! $chartData !!};

        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                indexAxis: 'y',
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
