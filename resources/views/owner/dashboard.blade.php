@extends('layouts.app')

@section('title', 'Dasbor Pemilik')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dasbor Bisnis</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Total Pesanan</h6>
                        <h3 class="mb-0">{{ $total_orders }}</h3>
                    </div>
                    <i class="fas fa-receipt fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Total Pelanggan</h6>
                        <h3 class="mb-0">{{ $total_customers }}</h3>
                    </div>
                    <i class="fas fa-users fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Pendapatan Selesai</h6>
                        <h3 class="mb-0">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Pendapatan Tertunda</h6>
                        <h3 class="mb-0">Rp {{ number_format($pending_revenue, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-hourglass-half fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tautan Cepat -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Laporan & Analitik</h5>
                    <a href="{{ route('owner.statistics') }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-chart-bar"></i> Statistik Detail
                    </a>
                    <a href="{{ route('owner.payments') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-credit-card"></i> Detail Pembayaran
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesanan Berdasarkan Status -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pesanan Berdasarkan Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $statuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'];
                            $status_labels = ['Menunggu', 'Dikonfirmasi', 'Sedang Berlangsung', 'Selesai', 'Dibatalkan'];
                            $status_colors = ['warning', 'info', 'primary', 'success', 'danger'];
                        @endphp
                        @foreach($statuses as $idx => $status)
                            <div class="col-md-2 text-center">
                                <h5>{{ $orders_by_status[$status] ?? 0 }}</h5>
                                <small class="text-muted">{{ $status_labels[$idx] }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pesanan Terbaru -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Pesanan Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Pelanggan</th>
                                        <th>Paket</th>
                                        <th>Jumlah</th>
                                        <th>Status Pembayaran</th>
                                        <th>Status Pesanan</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->package->name }}</td>
                                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                @if($order->payment)
                                                    <span class="badge bg-{{ $order->payment->status === 'success' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($order->payment->status) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Belum Dibayar</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge-status badge-{{ strtolower(str_replace('_', '-', $order->status)) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">Belum ada pesanan.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
