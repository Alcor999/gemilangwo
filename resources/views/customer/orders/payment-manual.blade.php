@extends('layouts.app')

@section('title', 'Payment Manual')

@section('content')
<style>
    .payment-header {
        background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px 8px 0 0;
    }

    .bank-card {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .bank-card:hover {
        border-color: #b8860b;
        box-shadow: 0 4px 12px rgba(184, 134, 11, 0.2);
    }

    .bank-card.selected {
        border-color: #b8860b;
        background-color: #fffaf0;
    }

    .bank-logo {
        font-size: 2rem;
        margin-right: 1rem;
    }

    .bank-info {
        flex: 1;
    }

    .bank-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
    }

    .bank-account {
        font-size: 0.9rem;
        color: #666;
        margin-top: 0.25rem;
        font-family: monospace;
    }

    .amount-display {
        background: linear-gradient(135deg, #fff5e1 0%, #f5f0e8 100%);
        padding: 2rem;
        border-radius: 8px;
        border-left: 4px solid #b8860b;
        text-align: center;
        margin-bottom: 2rem;
    }

    .amount-display h2 {
        font-size: 2.5rem;
        color: #b8860b;
        font-weight: 700;
        margin: 0;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1); border: none;">
                <div class="payment-header">
                    <h5><i class="fas fa-bank me-2"></i>Pilih Bank untuk Transfer</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Order Info -->
                    <div class="alert alert-info border-0 mb-4">
                        <strong>Order:</strong> {{ $order->order_number }}<br>
                        <strong>Paket:</strong> {{ $order->package->name }}<br>
                        <strong>Jumlah:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </div>

                    <!-- Amount Display -->
                    <div class="amount-display">
                        <h6 style="color: #666; text-transform: uppercase; font-size: 0.875rem;">Total Pembayaran</h6>
                        <h2>Rp {{ number_format($order->total_price, 0, ',', '.') }}</h2>
                    </div>

                    <!-- Bank Selection Form -->
                    <form id="bankForm" action="{{ route('customer.orders.selectBank', $order->id) }}" method="POST">
                        @csrf

                        <h6 class="mb-3"><i class="fas fa-list me-2"></i>Pilih Bank Tujuan:</h6>

                        @if ($banks->isEmpty())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Tidak ada bank yang tersedia. Silakan hubungi support.
                            </div>
                        @else
                            @foreach ($banks as $bank)
                                <label class="bank-card d-flex align-items-center" style="cursor: pointer;">
                                    <input type="radio" name="bank_id" value="{{ $bank->id }}" class="form-check-input me-3" required>
                                    <div class="bank-logo">
                                        <i class="fas fa-university"></i>
                                    </div>
                                    <div class="bank-info">
                                        <div class="bank-name">{{ $bank->name }}</div>
                                        <div class="bank-account">{{ $bank->account_number }}</div>
                                        <small class="text-muted">{{ $bank->account_holder }}</small>
                                    </div>
                                </label>
                            @endforeach
                        @endif

                        <!-- Buttons -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-lg" style="background-color: #b8860b; color: white; font-weight: 600;">
                                <i class="fas fa-arrow-right me-2"></i>Lanjut ke Pembayaran
                            </button>
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>Tanya Jawab</h6>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Bagaimana cara melakukan transfer?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <small>Setelah memilih bank, Anda akan melihat nomor rekening tujuan. Transfer sesuai jumlah yang tertera dan sertakan nomor referensi di catatan transfer.</small>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Berapa lama verifikasi pembayaran?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <small>Tim kami akan memverifikasi pembayaran Anda dalam 1-2 jam kerja setelah transfer masuk. Anda akan menerima email konfirmasi.</small>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Bagaimana jika ada kesalahan transfer?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <small>Hubungi kami melalui WhatsApp di halaman berikutnya atau email support untuk bantuan lebih lanjut.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
