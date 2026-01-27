@extends('layouts.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<style>
    .payment-header {
        background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px 8px 0 0;
    }

    .account-box {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #b8860b;
        margin-bottom: 1.5rem;
    }

    .account-field {
        margin-bottom: 1rem;
    }

    .account-label {
        font-size: 0.875rem;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .account-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: #212529;
        font-family: monospace;
    }

    .copy-btn {
        font-size: 0.875rem;
        padding: 0.25rem 0.75rem;
    }

    .status-badge {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-whatsapp {
        background-color: #25d366;
        color: white;
        border: none;
    }

    .btn-whatsapp:hover {
        background-color: #1ebd56;
        color: white;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: none;">
                <div class="payment-header">
                    <h5><i class="fas fa-check-circle me-2"></i>Konfirmasi Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Status Alert -->
                    <div class="alert alert-warning border-0 d-flex align-items-center mb-4">
                        <i class="fas fa-info-circle me-2" style="font-size: 1.2rem;"></i>
                        <div>
                            <strong>Menunggu Verifikasi</strong><br>
                            <small>Transfer ke rekening di bawah dan hubungi kami via WhatsApp untuk konfirmasi.</small>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="mb-4">
                        <h6 class="mb-3" style="color: #666; font-weight: 600;">Order Summary</h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Order Number</small>
                                <div style="font-weight: 600;">{{ $order->order_number }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Package</small>
                                <div style="font-weight: 600;">{{ $order->package->name }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Bank Account Info -->
                    <h6 class="mb-3" style="color: #666; font-weight: 600;">
                        <i class="fas fa-bank me-2"></i>Rekening Tujuan Transfer
                    </h6>

                    <div class="account-box">
                        <div class="account-field">
                            <div class="account-label">Bank</div>
                            <div class="account-value">{{ $bank->name }}</div>
                        </div>

                        <div class="account-field">
                            <div class="account-label">Nomor Rekening</div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="account-value" id="accountNumber">{{ $bank->account_number }}</div>
                                <button type="button" class="btn btn-sm btn-outline-secondary copy-btn" onclick="copyToClipboard()">
                                    <i class="fas fa-copy me-1"></i>Copy
                                </button>
                            </div>
                        </div>

                        <div class="account-field">
                            <div class="account-label">Atas Nama</div>
                            <div class="account-value" style="font-size: 1rem;">{{ $bank->account_holder }}</div>
                        </div>

                        <div class="account-field">
                            <div class="account-label">Jumlah Transfer (Tepat)</div>
                            <div class="account-value" style="color: #b8860b;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                        </div>

                        @if ($bank->instruction)
                            <div class="account-field">
                                <div class="account-label">Catatan Transfer</div>
                                <small class="text-muted">{{ $bank->instruction }}</small>
                            </div>
                        @endif
                    </div>

                    <!-- Important Notes -->
                    <div class="alert alert-info border-0">
                        <h6 class="mb-2"><i class="fas fa-lightbulb me-2"></i>Penting!</h6>
                        <ul class="mb-0 ps-3" style="font-size: 0.9rem;">
                            <li>Transfer tepat sesuai jumlah yang ditampilkan</li>
                            <li>Sertakan reference number <strong>{{ $order->order_number }}</strong> di catatan transfer jika memungkinkan</li>
                            <li>Tunggu 1-2 jam untuk verifikasi pembayaran</li>
                            <li>Hubungi kami via WhatsApp jika ada kendala</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="button-group">
                        <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-whatsapp flex-grow-1">
                            <i class="fab fa-whatsapp me-2"></i>Hubungi via WhatsApp
                        </a>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Order
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payment Status Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-hourglass-half me-2"></i>Status Pembayaran</h6>
                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded">
                        <div>
                            <strong>Verification Status</strong><br>
                            <small class="text-muted">Updated at {{ $payment->updated_at->format('d M Y H:i') }}</small>
                        </div>
                        <div>
                            <span class="status-badge status-pending">
                                <i class="fas fa-clock me-1"></i>Pending
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard() {
        const accountNumber = document.getElementById('accountNumber').textContent;
        navigator.clipboard.writeText(accountNumber).then(() => {
            alert('Nomor rekening berhasil dicopy!');
        });
    }
</script>
@endsection
