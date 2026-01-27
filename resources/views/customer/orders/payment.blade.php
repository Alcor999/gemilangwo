@extends('layouts.app')

@section('title', 'Payment - Midtrans')

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
                    <h5><i class="fas fa-lock me-2"></i>Complete Payment</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Info Alert -->
                    <div class="alert alert-info border-0 mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Complete your payment</strong> to confirm your wedding event booking.
                    </div>

                    <!-- Order Information -->
                    <div class="order-info">
                        <div class="order-info-row">
                            <div>
                                <div class="order-info-label">Order Number</div>
                                <div class="order-info-value">{{ $order->order_number }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div class="order-info-label">Package</div>
                                <div class="order-info-value">{{ $order->package->name }}</div>
                            </div>
                        </div>
                        <div class="order-info-row">
                            <div>
                                <div class="order-info-label">Guest Count</div>
                                <div class="order-info-value">{{ $order->guest_count }} Guests</div>
                            </div>
                            <div style="text-align: right;">
                                <div class="order-info-label">Event Date</div>
                                <div class="order-info-value">{{ $order->event_date->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount to Pay -->
                    <div class="amount-section">
                        <h6>Amount to Pay</h6>
                        <h2>Rp {{ number_format($order->total_price, 0, ',', '.') }}</h2>
                    </div>

                    <!-- Midtrans Snap Payment Button -->
                    <div id="snap-container" class="mb-4"></div>

                    <!-- Security Info -->
                    <div class="alert alert-info border-0 d-flex align-items-center mb-3">
                        <i class="fas fa-shield-alt me-2 fs-5" style="color: #b8860b;"></i>
                        <small><strong>Secure Payment:</strong> Powered by Midtrans. Credit Cards, Bank Transfer, E-Wallets & more.</small>
                    </div>

                    <!-- Test Payment Methods Info -->
                    <div class="card border-warning bg-light">
                        <div class="card-body">
                            <h6 class="card-title text-warning mb-2"><i class="fas fa-flask me-2"></i>Sandbox Test Payment Methods</h6>
                            <div class="row text-sm">
                                <div class="col-md-6 mb-2">
                                    <strong>Credit Card:</strong><br>
                                    <code>4011 1111 1111 1112</code><br>
                                    <small>Exp: 12/25 | CVV: 123</small>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Bank Transfer:</strong><br>
                                    <small>BCA, BNI, Mandiri, Permata, etc.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What Happens Next -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3"><i class="fas fa-arrow-right me-2" style="color: #b8860b;"></i>What Happens Next?</h6>
                    <ol class="mb-0">
                        <li class="mb-2"><strong>Complete Payment</strong> - Choose your preferred payment method</li>
                        <li class="mb-2"><strong>Receive Confirmation</strong> - Email confirmation immediately</li>
                        <li class="mb-2"><strong>Get Updated</strong> - Our team will contact you within 24 hours</li>
                        <li><strong>Plan Your Wedding</strong> - Start planning your perfect event!</li>
                    </ol>
                </div>
            </div>

            <!-- Back to Order -->
            <div class="text-center mt-4 mb-5">
                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Order
                </a>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="https://app.sandbox.midtrans.com/snap/snap.js"></script>
<script>
    // Set Midtrans Client Key
    window.snap = window.snap || {};
    window.snap.clientKey = "{{ $client_key }}";
    
    // Wait for snap script to load
    var snapLoadAttempts = 0;
    var maxAttempts = 50;
    var snapToken = "{{ $snap_token }}";
    
    if (!snapToken) {
        document.getElementById('snap-container').innerHTML = '<div class="alert alert-danger"><strong>Error:</strong> Failed to generate payment token. Please refresh the page.</div>';
    } else {
        var checkSnapLoad = setInterval(function() {
            snapLoadAttempts++;
            
            if (typeof window.snap !== 'undefined' && window.snap.pay) {
                // Snap is loaded
                clearInterval(checkSnapLoad);
                
                window.snap.embed(snapToken, {
                    embedId: 'snap-container',
                    onSuccess: function(result){
                        console.log('Payment success:', result);
                        window.location.href = "{{ route('customer.orders.paymentFinish') }}?order_id={{ $order->order_number }}";
                    },
                    onPending: function(result){
                        console.log('Payment pending:', result);
                        alert('Waiting for your payment!');
                    },
                    onError: function(result){
                        console.error('Payment error:', result);
                        alert('Payment failed. Please try again.');
                    },
                    onClose: function(){
                        console.log('Payment closed');
                        alert('You closed the payment popup without finishing the payment');
                    }
                });
            } else if (snapLoadAttempts >= maxAttempts) {
                clearInterval(checkSnapLoad);
                console.error('Snap object failed to load after ' + maxAttempts + ' attempts');
                document.getElementById('snap-container').innerHTML = '<div class="alert alert-danger"><strong>Error:</strong> Failed to load Midtrans payment gateway. Please check your client key and try again. <br><small>If problem persists, please refresh the page or contact support.</small></div>';
            }
        }, 100);
    }
</script>
@endsection
