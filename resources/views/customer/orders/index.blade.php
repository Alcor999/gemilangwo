@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Bookings</h1>
        <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Booking
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Package</th>
                        <th>Event Date</th>
                        <th>Location</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->package->name }}</td>
                            <td>{{ $order->event_date->format('d M Y') }}</td>
                            <td>{{ Str::limit($order->event_location, 25) }}</td>
                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge-status badge-{{ strtolower(str_replace('_', '-', $order->status)) }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($order->payment && $order->payment->isSuccess())
                                    <span class="badge bg-success">Paid</span>
                                @elseif($order->payment && $order->payment->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-secondary">Unpaid</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($order->isPending() && (!$order->payment || !$order->payment->isSuccess()))
                                    <a href="{{ route('customer.orders.payment', $order->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-credit-card"></i> Pay
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="alert alert-info">
            You haven't made any bookings yet. <a href="{{ route('customer.packages.index') }}">Browse our packages</a> and create your first booking!
        </div>
    @endif
</div>
@endsection
