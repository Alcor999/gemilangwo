@extends('layouts.app')

@section('title', 'Dasbor Pelanggan - Gemilang WO')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">
        <div>
            <h1 class="mb-1" style="font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 600; color: var(--text-dark);">Dasbor Saya</h1>
            <p class="text-muted mb-0" style="font-size: 0.95rem;">Selamat kembali! Pantau persiapan acara bahagia Anda dari sini.</p>
        </div>
        <div class="d-none d-md-block text-end">
            <h6 class="mb-0 fw-bold" style="color: var(--primary-color);">{{ now()->translatedFormat('d F Y') }}</h6>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
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
            <div class="card stat-card h-100 border-0 overflow-hidden" style="background: linear-gradient(145deg, #ffffff 0%, #fdfbf7 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Acara Selesai</p>
                            <h2 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--text-dark);">{{ $completed_orders }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                            <i class="fas fa-check-circle fs-4"></i>
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
                            <p class="text-muted mb-1 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Dalam Proses</p>
                            <h2 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif; color: var(--text-dark);">{{ $pending_orders }}</h2>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 54px; height: 54px; background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                            <i class="fas fa-clock fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card h-100 border-0 overflow-hidden shadow-sm" style="background: #ffffff;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <p class="text-muted mb-0 text-uppercase fw-semibold" style="font-size: 0.75rem; letter-spacing: 1px;">Status Pemesanan</p>
                        <i class="fas fa-calendar" style="color: var(--primary-color); opacity: 0.5;"></i>
                    </div>
                    @if($pending_orders > 0)
                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning px-3 py-2 border border-warning border-opacity-25" style="font-weight: 600;"><i class="fas fa-circle ms-1" style="font-size:6px; vertical-align: middle;"></i> Aktif Berjalan</span>
                    @else
                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 border border-success border-opacity-25" style="font-weight: 600;"><i class="fas fa-check ms-1" style="font-size:9px;"></i> Siap Melayani</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Aksi Cepat -->
        <div class="col-xl-4 col-lg-5">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif;">Eksplorasi Hari Ini</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column gap-3">
                        <a href="{{ route('customer.packages.index') }}" class="btn btn-outline-primary text-start px-4 py-3 d-flex align-items-center rounded-3 w-100 action-btn">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <i class="fas fa-search"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">Eksplor Paket Pernikahan</h6>
                                <small class="text-muted fw-normal" style="font-size: 0.75rem;">Temukan penawaran eksklusif kami</small>
                            </div>
                        </a>
                        
                        <a href="{{ route('customer.orders.create') }}" class="btn btn-outline-secondary text-start px-4 py-3 d-flex align-items-center rounded-3 w-100 action-btn">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 40px; height: 40px;">
                                <i class="fas fa-cart-plus"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Buat Pemesanan Baru</h6>
                                <small class="text-muted fw-normal" style="font-size: 0.75rem;">Mulai rencanakan momen Anda</small>
                            </div>
                        </a>

                        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-secondary text-start px-4 py-3 d-flex align-items-center rounded-3 w-100 action-btn">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 40px; height: 40px;">
                                <i class="fas fa-list"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">Riwayat Transaksi</h6>
                                <small class="text-muted fw-normal" style="font-size: 0.75rem;">Pantau seluruh pesanan Anda</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-xl-8 col-lg-7">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom border-light pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold" style="font-family: 'Playfair Display', serif;">Pemesanan Terbaru Anda</h5>
                    @if($recent_orders->count() > 0)
                    <a href="{{ route('customer.orders.index') }}" class="text-primary text-decoration-none" style="font-size: 0.85rem; font-weight: 600;">Lihat Semua <i class="fas fa-arrow-right ms-1"></i></a>
                    @endif
                </div>
                <div class="card-body p-0">
                    @if($recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background: rgba(0,0,0,0.02);">
                                    <tr>
                                        <th class="ps-4 border-0 text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">No. Pesanan</th>
                                        <th class="border-0 text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">Paket</th>
                                        <th class="border-0 text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">Tanggal Acara</th>
                                        <th class="border-0 text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">Total</th>
                                        <th class="border-0 text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">Status</th>
                                        <th class="pe-4 border-0 text-end text-muted" style="font-size: 0.75rem; letter-spacing:0.5px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr>
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold" style="color: var(--primary-color);">{{ $order->order_number }}</div>
                                            </td>
                                            <td class="py-3 text-dark fw-medium">{{ Str::limit($order->package->name, 25) }}</td>
                                            <td class="py-3 text-muted" style="font-size:0.9rem;">{{ $order->event_date->format('d M Y') }}</td>
                                            <td class="py-3 fw-bold text-dark">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </td>
                                            <td class="py-3">
                                                <span class="badge rounded-pill bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }} bg-opacity-10 text-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning text-dark' : ($order->status === 'cancelled' ? 'danger' : 'info text-dark')) }} px-3 py-2 border border-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : ($order->status === 'cancelled' ? 'danger' : 'info')) }} border-opacity-25" style="font-weight: 600;">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td class="pe-4 py-3 text-end">
                                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; color: var(--primary-color);">
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-shopping-bag text-muted fs-2"></i>
                            </div>
                            <h6 class="text-dark fw-bold" style="font-family: 'Playfair Display', serif; font-size:1.25rem;">Belum Ada Pemesanan</h6>
                            <p class="text-muted" style="font-size: 0.95rem; max-width:400px; margin: 0 auto 20px;">Anda belum melakukan pemesanan apapun. Mari melangkah mulai dari mencari paket impian Anda.</p>
                            <a href="{{ route('customer.packages.index') }}" class="btn btn-primary px-4 py-2 rounded-3">Lihat Paket</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for action buttons */
    .action-btn {
        transition: all 0.3s ease;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-color: var(--primary-color) !important;
    }
    .btn-outline-primary.action-btn:hover {
        background: rgba(184, 134, 11, 0.05);
    }
    .btn-outline-primary.action-btn .bg-light {
        color: var(--primary-color);
        background: rgba(184, 134, 11, 0.1) !important;
    }
</style>
@endsection
