@extends('layouts.app')

@section('title', 'Analisis Pelanggan - Analitik Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-users"></i> Analisis Akuisisi Pelanggan
            </h1>
            <p class="text-muted">Pantau pertumbuhan dan tren pelanggan</p>
        </div>
    </div>

    <!-- Penyaringan -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.analytics.customers') }}" class="row g-3">
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
                    <h6 class="mb-0"><i class="fas fa-chart-line"></i> Tren Pertumbuhan Pelanggan</h6>
                </div>
                <div class="card-body">
                    <canvas id="customerChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Pelanggan -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-table"></i> Statistik Pelanggan</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ $period === 'monthly' ? 'Bulan' : 'Tahun' }}</th>
                                <th class="text-end">Pelanggan Baru</th>
                                <th class="text-end">Tingkat Pertumbuhan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $prevCount = null;
                            @endphp
                            @forelse($customerData as $item)
                                <tr>
                                    <td>{{ $item['month'] ?? $item['year'] }}</td>
                                    <td class="text-end"><strong>{{ $item['customers'] }}</strong></td>
                                    <td class="text-end">
                                        @if($prevCount !== null)
                                            <span class="badge bg-{{ $item['customers'] > $prevCount ? 'success' : 'danger' }}">
                                                {{ round((($item['customers'] - $prevCount) / $prevCount) * 100, 2) }}%
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $prevCount = $item['customers'];
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> Tidak ada data pelanggan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Lokasi Teratas -->
    @if($topCountries->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-map-marker-alt"></i> Lokasi Pelanggan Teratas</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kota</th>
                                    <th class="text-end">Pelanggan</th>
                                    <th class="text-end">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalCustomers = $topCountries->sum('count');
                                @endphp
                                @foreach($topCountries as $location)
                                    <tr>
                                        <td>{{ $location->city }}</td>
                                        <td class="text-end">{{ $location->count }}</td>
                                        <td class="text-end">
                                            {{ round(($location->count / $totalCustomers) * 100, 2) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('customerChart').getContext('2d');
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
                    }
                }
            }
        });
    });
</script>
@endsection
