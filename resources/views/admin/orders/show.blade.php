@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pesanan {{ $order->order_number }}</h5>
                    <span class="badge-status badge-{{ strtolower(str_replace('_', '-', $order->status)) }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Informasi Pelanggan</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Nama:</strong> {{ $order->user->name }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Telepon:</strong> {{ $order->user->phone ?? '-' }}</p>
                            <p><strong>Alamat:</strong> {{ $order->user->address ?? '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Detail Acara</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Paket:</strong> {{ $order->package->name }}</p>
                            <p><strong>Tanggal Acara:</strong> {{ $order->event_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Lokasi Acara:</strong> {{ $order->event_location }}</p>
                            <p><strong>Jumlah Tamu:</strong> {{ $order->guest_count }}</p>
                        </div>
                    </div>

                    @if($order->orderVendors && $order->orderVendors->count() > 0)
                        <hr>
                        <h6 class="mb-2">Vendor Terpilih</h6>
                        <ul class="list-group list-group-flush mb-0">
                            @foreach($order->orderVendors as $ov)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                    <span><strong>{{ $ov->vendor_category_name }}:</strong> {{ $ov->vendor_name }}</span>
                                    <span>Rp {{ number_format($ov->price, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <hr>
                    @endif

                    @if($order->special_request)
                        <hr>
                        <h6>Permintaan Khusus</h6>
                        <p>{{ $order->special_request }}</p>
                    @endif

                    <hr>

                    <h6 class="mb-3">Pembayaran & Verifikasi</h6>
                    <div class="row">
                        <div class="col-md-12">
                            @if($order->payment)
                                <div style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #b8860b;">
                                    <!-- Payment Header -->
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <p class="mb-1"><strong>Status Pembayaran:</strong> <span class="badge bg-{{ $order->payment->isSuccess() ? 'success' : 'warning' }}">{{ ucfirst($order->payment->status) }}</span></p>
                                            <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method ?? 'Tidak Ada')) }}</p>
                                        </div>
                                        <div>
                                            <span class="badge bg-{{ $order->payment->verification_status === 'verified' ? 'success' : ($order->payment->verification_status === 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order->payment->verification_status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Payment Amount -->
                                    <p class="mb-2"><strong>Jumlah:</strong> Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p>

                                    <!-- Bank Info (if manual transfer) -->
                                    @if($order->payment->bank)
                                        <div style="background: white; padding: 1rem; border-radius: 6px; margin: 1rem 0;">
                                            <p class="mb-1"><strong>Bank:</strong> {{ $order->payment->bank->name }}</p>
                                            <p class="mb-1"><strong>Rekening:</strong> {{ $order->payment->bank->account_number }}</p>
                                            <p class="mb-0"><strong>Pemilik Rekening:</strong> {{ $order->payment->bank->account_holder }}</p>
                                        </div>
                                    @endif

                                    <!-- Paid At -->
                                    @if($order->payment->paid_at)
                                        <p class="mb-2"><strong>Dibayar Pada:</strong> {{ $order->payment->paid_at->format('d M Y H:i') }}</p>
                                    @endif

                                    <!-- Verification Section -->
                                    @if($order->payment->verification_status === 'pending')
                                        <div style="border-top: 1px solid #dee2e6; padding-top: 1rem; margin-top: 1rem;">
                                            <h6 class="mb-3"><i class="fas fa-check-circle me-2"></i>Verifikasi Pembayaran</h6>
                                            
                                            <!-- Approval Form -->
                                            <form action="{{ route('admin.payments.approve', $order->payment->id) }}" method="POST" class="mb-2">
                                                @csrf
                                                <div class="mb-2">
                                                    <textarea name="notes" class="form-control form-control-sm" rows="2" placeholder="Catatan verifikasi (opsional)"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Setujui pembayaran ini?');">
                                                    <i class="fas fa-check me-1"></i>Setujui Pembayaran
                                                </button>
                                            </form>

                                            <!-- Rejection Form -->
                                            <button type="button" class="btn btn-sm btn-danger w-100" data-bs-toggle="collapse" data-bs-target="#rejectForm">
                                                <i class="fas fa-times me-1"></i>Tolak Pembayaran
                                            </button>

                                            <div class="collapse mt-2" id="rejectForm">
                                                <form action="{{ route('admin.payments.reject', $order->payment->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <textarea name="reason" class="form-control form-control-sm" rows="2" placeholder="Alasan penolakan" required></textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Tolak pembayaran ini? Pelanggan akan diberi tahu.');">
                                                        <i class="fas fa-times me-1"></i>Konfirmasi Penolakan
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @elseif($order->payment->verification_status === 'verified')
                                        <div style="border-top: 1px solid #dee2e6; padding-top: 1rem; margin-top: 1rem;">
                                            <div class="alert alert-success mb-0">
                                                <strong><i class="fas fa-check-circle me-2"></i>Pembayaran Terverifikasi</strong><br>
                                                <small>
                                                    Diverifikasi oleh: <strong>{{ $order->payment->verifiedBy?->name ?? 'Sistem' }}</strong><br>
                                                    @if($order->payment->verification_notes)
                                                        Catatan: {{ $order->payment->verification_notes }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    @else
                                        <div style="border-top: 1px solid #dee2e6; padding-top: 1rem; margin-top: 1rem;">
                                            <div class="alert alert-danger mb-0">
                                                <strong><i class="fas fa-times-circle me-2"></i>Pembayaran Ditolak</strong><br>
                                                <small>
                                                    Ditolak oleh: <strong>{{ $order->payment->verifiedBy?->name ?? 'Sistem' }}</strong><br>
                                                    Alasan: {{ $order->payment->verification_notes }}
                                                </small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Belum ada pembayaran
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan Pesanan</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Harga Paket:</span>
                        <strong>Rp {{ number_format($order->package->price, 0, ',', '.') }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total:</span>
                        <h5 class="text-primary mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kelola Pesanan</h5>
                    <div class="mb-3">
                        <label class="form-label">Perbarui Status</label>
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select class="form-select form-select-sm mb-2" name="status" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary w-100">Perbarui</button>
                        </form>
                    </div>

                    @if($order->isPending())
                        <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="mb-0">
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger w-100"
                                data-confirm="Apakah Anda yakin ingin membatalkan pesanan ini?"
                                data-confirm-title="Batalkan Pesanan"
                                data-confirm-btn="Ya, Batalkan"
                                data-confirm-danger="1">Batalkan Pesanan</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
