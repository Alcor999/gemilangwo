@extends('layouts.app')

@section('title', 'Pembayaran - Midtrans')

@section('content')
<style>
    .payment-header {
        background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px 8px 0 0;
    }
    
    .payment-header h5 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }
    
    .order-info {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    
    .order-info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem 0;
        border-bottom: 1px solid #e9ecef;
    }
    
    .order-info-row:last-child {
        border-bottom: none;
    }
    
    .order-info-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .order-info-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
    }
    
    .amount-section {
        background: linear-gradient(135deg, #fff5e1 0%, #f5f0e8 100%);
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        border-left: 4px solid #b8860b;
        text-align: center;
    }
    
    .amount-section h6 {
        color: #666;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .amount-section h2 {
        font-size: 2.5rem;
        color: #b8860b;
        font-weight: 700;
        margin: 0;
    }
    
    .payment-card {
        border: none;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: 8px;
        overflow: hidden;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="payment-card card">
                <div class="payment-header">
                    <h5><i class="fas fa-lock me-2"></i>Selesaikan Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Info Alert -->
                    <div class="alert alert-info border-0 mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Selesaikan pembayaran Anda</strong> untuk mengonfirmasi pesanan acara pernikahan Anda.
                    </div>

                    <!-- Order Information -->
                    <div class="order-info">
                        <div class="order-info-row">
                            <div>
                                <div class="order-info-label">Nomor Pesanan</div>
                                <div class="order-info-value">{{ $order->order_number }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div class="order-info-label">Paket</div>
                                <div class="order-info-value">{{ $order->package->name }}</div>
                            </div>
                        </div>
                        <div class="order-info-row">
                            <div>
                                <div class="order-info-label">Jumlah Tamu</div>
                                <div class="order-info-value">{{ $order->guest_count }} Tamu</div>
                            </div>
                            <div style="text-align: right;">
                                <div class="order-info-label">Tanggal Acara</div>
                                <div class="order-info-value">{{ $order->event_date->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount to Pay -->
                    <div class="amount-section">
                        <h6>Jumlah yang Harus Dibayar</h6>
                        <h2>Rp {{ number_format($order->total_price, 0, ',', '.') }}</h2>
                    </div>

                    <!-- Midtrans Snap Payment Button -->
                    <div id="snap-container" class="mb-4"></div>

                    <!-- Security Info -->
                    <div class="alert alert-info border-0 d-flex align-items-center mb-3">
                        <i class="fas fa-shield-alt me-2 fs-5" style="color: #b8860b;"></i>
                        <small><strong>Pembayaran Aman:</strong> Didukung oleh Midtrans. Kartu Kredit, Transfer Bank, E-Wallet, dan lainnya.</small>
                    </div>

                    <!-- Test Payment Methods Info -->
                    <div class="card border-warning bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-warning mb-2"><i class="fas fa-flask me-2"></i>Metode Pembayaran Uji (Sandbox)</h6>
                            <div class="row text-sm">
                                <div class="col-md-6 mb-2">
                                    <strong>Kartu Kredit:</strong><br>
                                    <code>4011 1111 1111 1112</code><br>
                                    <small>Exp: 12/25 | CVV: 123</small>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Transfer Bank:</strong><br>
                                    <small>BCA, BNI, Mandiri, Permata, dll.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apa Selanjutnya -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-arrow-right me-2" style="color: #b8860b;"></i>Apa Selanjutnya?</h6>
                    <ol class="mb-0">
                        <li class="mb-2"><strong>Selesaikan Pembayaran</strong> - Pilih metode pembayaran yang Anda inginkan</li>
                        <li class="mb-2"><strong>Terima Konfirmasi</strong> - Konfirmasi via email secara instan</li>
                        <li class="mb-2"><strong>Dapatkan Pembaruan</strong> - Tim kami akan menghubungi Anda dalam 24 jam</li>
                        <li><strong>Rencanakan Pernikahan Anda</strong> - Mulai merencanakan acara impian Anda!</li>
                    </ol>
                </div>
            </div>

            <!-- Kembali ke Pesanan -->
            <div class="text-center mt-4 mb-5">
                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"></script>
<script>
    // Setel Client Key Midtrans
    window.snap = window.snap || {};
    window.snap.clientKey = "{{ $client_key }}";
    
    // Tunggu script snap dimuat
    var snapLoadAttempts = 0;
    var maxAttempts = 50;
    var snapToken = "{{ $snap_token }}";
    
    if (!snapToken) {
        document.getElementById('snap-container').innerHTML = '<div class="alert alert-danger"><strong>Kesalahan:</strong> Gagal membuat token pembayaran. Silakan muat ulang halaman.</div>';
    } else {
        var checkSnapLoad = setInterval(function() {
            snapLoadAttempts++;
            
            if (typeof window.snap !== 'undefined' && window.snap.pay) {
                // Snap sudah dimuat
                clearInterval(checkSnapLoad);
                
                window.snap.embed(snapToken, {
                    embedId: 'snap-container',
                    onSuccess: function(result){
                        console.log('Pembayaran berhasil:', result);
                        window.location.href = "{{ route('customer.orders.paymentFinish') }}?order_id={{ $order->order_number }}";
                    },
                    onPending: function(result){
                        console.log('Pembayaran tertunda:', result);
                        alert('Menunggu pembayaran Anda!');
                    },
                    onError: function(result){
                        console.error('Kesalahan pembayaran:', result);
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function(){
                        console.log('Pembayaran ditutup');
                        alert('Anda menutup pop-up pembayaran sebelum menyelesaikan pembayaran');
                    }
                });
            } else if (snapLoadAttempts >= maxAttempts) {
                clearInterval(checkSnapLoad);
                console.error('Snap object failed to load after ' + maxAttempts + ' attempts');
                document.getElementById('snap-container').innerHTML = '<div class="alert alert-danger"><strong>Kesalahan:</strong> Gagal memuat gateway pembayaran Midtrans. Silakan periksa client key Anda dan coba lagi. <br><small>Jika masalah berlanjut, silakan muat ulang halaman atau hubungi dukungan.</small></div>';
            }
        }, 100);
    }
</script>
@endsection
