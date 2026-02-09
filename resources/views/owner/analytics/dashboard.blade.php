@extends('layouts.app')

@section('title', 'Dashboard Analitik - Owner')

@section('content')
<div class="container-fluid py-4">
    @php
        $eventStatusLabels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    @endphp
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line"></i> Analitik Bisnis
            </h1>
            <p class="text-muted">Pantau performa paket dan pendapatan Anda</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <!-- Revenue Today -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Pendapatan Hari Ini</p>
                            <h4 class="mb-0">Rp {{ number_format($revenueToday, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue This Month -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Pendapatan Bulan Ini</p>
                            <h4 class="mb-0">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Pesanan</p>
                            <h4 class="mb-0">{{ $totalBookings }}</h4>
                        </div>
                        <i class="fas fa-shopping-cart fa-3x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Events -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Acara Selesai</p>
                            <h4 class="mb-0">{{ $completedBookings }}</h4>
                        </div>
                        <i class="fas fa-check-circle fa-3x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Paket Teratas & Acara Mendatang -->
    <div class="row mb-4">
        <!-- Paket Teratas -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-fire"></i> Paket dengan Performa Terbaik</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Package</th>
                                <th class="text-end">Pesanan</th>
                                <th class="text-end">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPackages as $package)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.packages.show', $package->id) }}" class="text-decoration-none">
                                            {{ Str::limit($package->name, 30) }}
                                        </a>
                                    </td>
                                    <td class="text-end">{{ $package->orders_count ?? 0 }}</td>
                                    <td class="text-end">Rp {{ number_format($package->orders_sum_total_price ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox"></i> Tidak ada data paket
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Acara Mendatang -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-calendar"></i> Acara Mendatang (30 Hari Ke Depan)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Package</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingEvents as $event)
                                <tr>
                                    <td>{{ $event->event_date->format('d M Y') }}</td>
                                    <td>{{ Str::limit($event->package->name, 30) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $event->status === 'completed' ? 'success' : 'primary' }}">
                                            {{ $eventStatusLabels[$event->status] ?? ucfirst($event->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="fas fa-inbox"></i> Tidak ada acara mendatang
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tautan Analitik -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-link"></i> Analitik Detail</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('owner.analytics.revenue') }}" class="btn btn-outline-primary btn-block w-100">
                                <i class="fas fa-chart-bar"></i> Pendapatan
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('owner.analytics.bookings') }}" class="btn btn-outline-info btn-block w-100">
                                <i class="fas fa-calendar-check"></i> Pemesanan
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('owner.analytics.customerValue') }}" class="btn btn-outline-success btn-block w-100">
                                <i class="fas fa-users"></i> Nilai Pelanggan
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('owner.analytics.churn') }}" class="btn btn-outline-warning btn-block w-100">
                                <i class="fas fa-chart-line"></i> Analisis Churn
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="#" class="btn btn-outline-secondary btn-block w-100" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="fas fa-download"></i> Ekspor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ekspor -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ekspor Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('owner.analytics.export') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Laporan</label>
                        <select name="type" class="form-select" required>
                            <option value="">Pilih Jenis Laporan</option>
                            <option value="revenue">Laporan Pendapatan</option>
                            <option value="bookings">Laporan Pemesanan</option>
                            <option value="packages">Laporan Paket</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="pdf" id="formatPdf" checked>
                                <label class="form-check-label" for="formatPdf">
                                    PDF
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="excel" id="formatExcel">
                                <label class="form-check-label" for="formatExcel">
                                    Excel
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ekspor</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
