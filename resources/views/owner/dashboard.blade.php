@extends('layouts.app')

@section('title', 'Dasbor Pemilik Bisnis - Gemilang WO')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">
        <div>
            <h1 class="mb-1" style="font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 600; color: var(--text-dark);">Dasbor Bisnis</h1>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Laporan & ringkasan operasional keuangan Gemilang WO.</p>
        </div>
        <div class="d-none d-md-block text-end">
            <h6 class="mb-0 fw-bold" style="color: var(--text-dark);">Pemantauan <span style="color: var(--primary-color);">Real-Time</span></h6>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 border-0 overflow-hidden" style="background: linear-gradient(145deg, #ffffff 0%, #fdfbf7 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Total Omset Sukses</p>
                            <h3 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--text-dark);">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 border-0 overflow-hidden" style="background: linear-gradient(145deg, #ffffff 0%, #fdfbf7 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-center text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px; text-align: left !important;">Dana Tertahan</p>
                            <h3 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif; color: #f59e0b;">Rp {{ number_format($pending_revenue, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 border-0 overflow-hidden" style="background: linear-gradient(145deg, #ffffff 0%, #fdfbf7 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Total Pesanan</p>
                            <h2 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--text-dark);">{{ $total_orders }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(184, 134, 11, 0.1); color: var(--primary-color);">
                            <i class="fas fa-receipt fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 border-0 shadow-sm" style="background: #ffffff;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Klien Terdaftar</p>
                            <h2 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--text-dark);">{{ $total_customers }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(0, 0, 0, 0.05); color: var(--text-muted);">
                            <i class="fas fa-users fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Tautan & Report Cepat -->
        <div class="col-xl-3 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif;">Akses Laporan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        <a href="{{ route('owner.statistics') }}" class="btn btn-primary text-start px-4 py-3 d-flex align-items-center rounded-3 w-100 action-btn text-white">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3 text-primary shadow-sm" style="width: 40px; height: 40px;">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Statistik Bisnis</h6>
                            </div>
                        </a>
                        
                        <a href="{{ route('owner.payments') }}" class="btn btn-outline-secondary text-start px-4 py-3 d-flex align-items-center rounded-3 w-100 action-btn">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 40px; height: 40px;">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Data Pembayaran</h6>
                            </div>
                        </a>
                    </div>

                    <!-- Flow Pesanan Berdasarkan Status -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="fw-bold mb-3" style="font-family: 'Playfair Display', serif;">Grafik Statis Status</h6>
                        @php
                            $statuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'];
                            $status_labels = ['Menunggu', 'Dikonfirmasi', 'Berjalan', 'Selesai', 'Batal'];
                        @endphp
                        <div class="d-flex flex-column gap-2">
                        @foreach($statuses as $idx => $status)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted" style="font-size: 0.85rem;">{{ $status_labels[$idx] }}</span>
                                <span class="badge bg-light text-dark border fw-bold">{{ $orders_by_status[$status] ?? 0 }}</span>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesanan Terbaru -->
        <div class="col-xl-9 col-lg-8">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom border-light pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif;">Aktivitas Pesanan Terkini</h5>
                </div>
                <div class="card-body p-0">
                    @if($recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background: rgba(0,0,0,0.02);">
                                    <tr>
                                        <th class="ps-4 border-0 text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">No. Pesanan</th>
                                        <th class="border-0 text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">Klien / Paket</th>
                                        <th class="border-0 text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">Nilai Transaksi</th>
                                        <th class="border-0 text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">Status Bayar</th>
                                        <th class="pe-4 border-0 text-end text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">Proses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold" style="color: var(--primary-color);">{{ $order->order_number }}</div>
                                                <small class="text-muted" style="font-size: 0.75rem;">{{ $order->created_at->format('d M') }}</small>
                                            </td>
                                            <td class="py-3">
                                                <div class="fw-bold text-dark">{{ $order->user->name }}</div>
                                                <div class="text-muted" style="font-size: 0.85rem;">{{ Str::limit($order->package->name, 25) }}</div>
                                            </td>
                                            <td class="py-3 fw-bold text-dark">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3">
                                                @if($order->payment)
                                                    <span class="badge rounded-pill bg-{{ $order->payment->status === 'success' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $order->payment->status === 'success' ? 'success' : 'warning text-dark' }} px-3 py-1 border border-{{ $order->payment->status === 'success' ? 'success' : 'warning' }} border-opacity-25" style="font-weight: 600;">
                                                        <i class="fas fa-{{ $order->payment->status === 'success' ? 'check' : 'clock' }} me-1"></i> {{ ucfirst($order->payment->status) }}
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-1 border border-secondary border-opacity-25" style="font-weight: 600;">
                                                        Belum Dibayar
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="pe-4 py-3 text-end">
                                                <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                                    {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-folder-open text-muted fs-2"></i>
                            </div>
                            <h6 class="text-dark fw-bold" style="font-family: 'Playfair Display', serif;">Belum Ada Pesanan</h6>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .action-btn { transition: all 0.3s ease; }
    .action-btn:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
</style>
@endsection
