@extends('layouts.app')

@section('title', 'Payment - Midtrans')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Complete Payment</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Complete your payment to confirm your wedding event booking.
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Order Number</h6>
                            <p><strong>{{ $order->order_number }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Package</h6>
                            <p>{{ $order->package->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h6 class="text-muted">Amount to Pay</h6>
                            <h2 class="text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h2>
                        </div>
                    </div>

                    <!-- Midtrans Snap Payment Button -->
                    <div id="snap-container" class="mb-4"></div>

                    <div class="alert alert-secondary">
                        <small>
                            <i class="fas fa-lock me-2"></i>
                            Your payment is secured by Midtrans. We accept Credit Cards, Bank Transfer, E-Wallets, and more.
                        </small>
                    </div>

                    <!-- Test Payment Methods Info -->
                    <div class="alert alert-warning">
                        <h6 class="mb-2"><i class="fas fa-flask me-2"></i>Testing Payment Methods</h6>
                        <p class="mb-2 text-sm">For sandbox testing, use one of these methods:</p>
                        <ul class="mb-0 text-sm">
                            <li><strong>Credit Card:</strong> 4111 1111 1111 1111 (any future date & CVV)</li>
                            <li><strong>Bank Transfer:</strong> BCA, BNI, Mandiri, Permata, etc.</li>
                            <li><strong>E-Wallet:</strong> GCash, OVO, DANA, LinkAja</li>
                            <li><strong>QRIS:</strong> QR Code based payments</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">What happens next?</h6>
                    <ol class="mb-0">
                        <li>Complete the payment using your preferred method</li>
                        <li>You'll receive a confirmation email</li>
                        <li>Our team will contact you within 24 hours</li>
                        <li>Start planning your perfect wedding!</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $client_key }}"></script>
<script>
    // Wait for snap script to load
    var snapLoadAttempts = 0;
    var maxAttempts = 50;
    
    var checkSnapLoad = setInterval(function() {
        snapLoadAttempts++;
        
        if (typeof snap !== 'undefined') {
            // Snap is loaded
            clearInterval(checkSnapLoad);
            
            snap.embed('{{ $snap_token }}', {
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
            document.getElementById('snap-container').innerHTML = '<div class="alert alert-danger">Error loading payment gateway. Please refresh the page.</div>';
        }
    }, 100);
</script>
@endsection
@endsection
