@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order {{ $order->order_number }}</h5>
                    <span class="badge-status badge-{{ strtolower(str_replace('_', '-', $order->status)) }}">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <h6 class="mb-3">Customer Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $order->user->name }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone:</strong> {{ $order->user->phone ?? '-' }}</p>
                            <p><strong>Address:</strong> {{ $order->user->address ?? '-' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Event Details</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Package:</strong> {{ $order->package->name }}</p>
                            <p><strong>Event Date:</strong> {{ $order->event_date->format('d M Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Event Location:</strong> {{ $order->event_location }}</p>
                            <p><strong>Guest Count:</strong> {{ $order->guest_count }}</p>
                        </div>
                    </div>

                    @if($order->special_request)
                        <hr>
                        <h6>Special Requests</h6>
                        <p>{{ $order->special_request }}</p>
                    @endif

                    <hr>

                    <h6 class="mb-3">Payment</h6>
                    <div class="row">
                        <div class="col-md-12">
                            @if($order->payment)
                                <p><strong>Payment Status:</strong> <span class="badge bg-{{ $order->payment->isSuccess() ? 'success' : 'warning' }}">{{ ucfirst($order->payment->status) }}</span></p>
                                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment->payment_method ?? 'N/A')) }}</p>
                                <p><strong>Amount:</strong> Rp {{ number_format($order->payment->amount, 0, ',', '.') }}</p>
                                @if($order->payment->paid_at)
                                    <p><strong>Paid On:</strong> {{ $order->payment->paid_at->format('d M Y H:i') }}</p>
                                @endif
                            @else
                                <p class="text-muted">No payment yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Package Price:</span>
                        <strong>Rp {{ number_format($order->package->price, 0, ',', '.') }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Amount:</span>
                        <h5 class="text-primary mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manage Order</h5>
                    <div class="mb-3">
                        <label class="form-label">Update Status</label>
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select class="form-select form-select-sm mb-2" name="status" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ $order->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary w-100">Update</button>
                        </form>
                    </div>

                    @if($order->isPending())
                        <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Cancel this order?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger w-100">Cancel Order</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
