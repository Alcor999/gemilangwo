@extends('layouts.app')

@section('title', 'Dasbor Analitik - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line"></i> Dasbor Analitik
            </h1>
            <p class="text-muted">Analitik dan wawasan untuk seluruh sistem</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <!-- Pendapatan Hari Ini -->
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

        <!-- Pendapatan Bulan Ini -->
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

        <!-- Total Pelanggan -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Pelanggan</p>
                            <h4 class="mb-0">{{ $totalCustomers }}</h4>
                        </div>
                        <i class="fas fa-users fa-3x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tingkat Konversi -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Tingkat Konversi</p>
                            <h4 class="mb-0">{{ $conversionRate }}%</h4>
                        </div>
                        <i class="fas fa-chart-pie fa-3x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Pesanan -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-shopping-cart"></i> Ringkasan Pesanan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted small">Total Pesanan</p>
                                <h3>{{ $totalOrders }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted small">Pesanan Selesai</p>
                                <h3 class="text-success">{{ $completedOrders }}</h3>
                                <small class="text-muted">{{ round(($completedOrders / max($totalOrders, 1)) * 100, 1) }}% tingkat penyelesaian</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-user-plus"></i> Akuisisi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted small">Baru Bulan Ini</p>
                                <h3>{{ $newCustomersThisMonth }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted small">Total Tahun Ini</p>
                                <h3>{{ $totalCustomers }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tautan Analitik -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-link"></i> Laporan Analitik</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.revenue') }}" class="btn btn-outline-primary btn-block w-100">
                                <i class="fas fa-chart-bar"></i> Laporan Pendapatan
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.customers') }}" class="btn btn-outline-info btn-block w-100">
                                <i class="fas fa-users"></i> Analisis Pelanggan
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.packages') }}" class="btn btn-outline-success btn-block w-100">
                                <i class="fas fa-box"></i> Kinerja Paket
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.conversion') }}" class="btn btn-outline-warning btn-block w-100">
                                <i class="fas fa-funnel"></i> Corong Konversi
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.payments') }}" class="btn btn-outline-danger btn-block w-100">
                                <i class="fas fa-credit-card"></i> Metode Pembayaran
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="#" class="btn btn-outline-secondary btn-block w-100" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="fas fa-download"></i> Ekspor Data
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
                <h5 class="modal-title">Ekspor Laporan Analitik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('admin.analytics.export') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Jenis Laporan</label>
                        <select name="type" class="form-select" required>
                            <option value="">Pilih Jenis Laporan</option>
                            <option value="revenue">Laporan Pendapatan</option>
                            <option value="customers">Laporan Pelanggan</option>
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
                    <div class="mb-3">
                        <label class="form-label">Periode</label>
                        <select name="period" class="form-select">
                            <option value="month">Bulanan</option>
                            <option value="yearly">Tahunan</option>
                        </select>
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
