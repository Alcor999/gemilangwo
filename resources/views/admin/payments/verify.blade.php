@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: none;">
                <div style="background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); color: white; padding: 2rem; border-radius: 8px 8px 0 0;">
                    <h5 class="mb-0"><i class="fas fa-check-circle me-2"></i>Verifikasi Pembayaran</h5>
                </div>

                <div class="card-body p-4">
                    <!-- Order Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Nomor Pesanan</h6>
                            <p class="h5">{{ $payment->order->order_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Pelanggan</h6>
                            <p class="h5">{{ $payment->order->user->name }}</p>
                            <small class="text-muted">{{ $payment->order->user->email }}</small>
                        </div>
                    </div>

                    <hr>

                    <!-- Detail Pembayaran -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Bank</h6>
                            <p class="h5">{{ $payment->bank->name }}</p>
                            <small class="text-muted">{{ $payment->bank->account_number }}</small>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Jumlah</h6>
                            <p class="h5 text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Metode Pembayaran</h6>
                        <p class="h5">Transfer Bank Manual</p>
                    </div>

                    <hr>

                    <!-- Form Verifikasi -->
                    <h6 class="mb-3">Verifikasi</h6>

                    <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST" id="approveForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Catatan Verifikasi</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Contoh: Transfer sesuai mutasi rekening..."></textarea>
                        </div>

                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-success" onclick="document.getElementById('approveForm').submit()">
                                <i class="fas fa-check me-2"></i>Setujui Pembayaran
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-2"></i>Tolak Pembayaran
                            </button>
                        </div>
                    </form>

                    <a href="{{ route('admin.payments.pending') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Menunggu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2" style="color: #dc3545;"></i>Tolak Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan *</label>
                        <textarea class="form-control" name="reason" rows="4" required placeholder="Mengapa pembayaran ini ditolak?"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="background-color: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
