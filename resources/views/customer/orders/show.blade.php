@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order Details</h5>
                    <span class="badge-status badge-{{ strtolower(str_replace('_', '-', $order->status)) }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Order Number</h6>
                            <p><strong>{{ $order->order_number }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Order Date</h6>
                            <p>{{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Package Name</h6>
                            <p><strong>{{ $order->package->name }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Harga Paket (Dasar)</h6>
                            <p>Rp {{ number_format($order->package->getDiscountedPrice(), 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($order->orderVendors->count() > 0)
                        <hr>
                        <h6 class="text-muted mb-2">Vendor Terpilih</h6>
                        <ul class="list-group list-group-flush mb-3">
                            @foreach($order->orderVendors as $ov)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span><strong>{{ $ov->vendor_category_name }}:</strong> {{ $ov->vendor_name }}</span>
                                    <span>Rp {{ number_format($ov->price, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <hr>

                    <h5 class="mb-3">Event Information</h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Event Date</h6>
                            <p>{{ $order->event_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Number of Guests</h6>
                            <p>{{ $order->guest_count }} guests</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h6 class="text-muted">Event Location</h6>
                            <p>{{ $order->event_location }}</p>
                        </div>
                    </div>

                    @if($order->special_request)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6 class="text-muted">Special Requests</h6>
                                <p>{{ $order->special_request }}</p>
                            </div>
                        </div>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-muted">Total Amount</h6>
                            <h3 class="text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            @if($order->payment && $order->payment->isSuccess())
                <div class="card mb-4 border-success">
                    <div class="card-body">
                        <h5 class="text-success mb-3">
                            <i class="fas fa-check-circle"></i> Payment Confirmed
                        </h5>
                        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment->payment_method) }}</p>
                        <p><strong>Paid On:</strong> {{ $order->payment->paid_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            @if($order->isPending())
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Payment Required</h5>
                        <p class="text-muted">Complete your payment to confirm this booking.</p>
                        <a href="{{ route('customer.orders.payment', $order->id) }}" class="btn btn-success w-100">
                            <i class="fas fa-credit-card"></i> Proceed to Payment
                        </a>
                    </div>
                </div>
            @endif

            @if(!$order->isCompleted() && !$order->isCancelled())
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Actions</h5>
                        @if($order->isPending())
                            <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" class="mb-0">
                                @csrf
                                <button type="button" class="btn btn-danger w-100"
                                    data-confirm="Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan."
                                    data-confirm-title="Batalkan Pesanan"
                                    data-confirm-btn="Ya, Batalkan"
                                    data-confirm-danger="1">
                                    <i class="fas fa-times"></i> Batalkan Pesanan
                                </button>
                            </form>
                        @else
                            <p class="text-muted">Your booking is confirmed and cannot be cancelled at this stage.</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
