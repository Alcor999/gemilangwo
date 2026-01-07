@extends('layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Business Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Total Orders</h6>
                        <h3 class="mb-0">{{ $total_orders }}</h3>
                    </div>
                    <i class="fas fa-receipt fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Total Customers</h6>
                        <h3 class="mb-0">{{ $total_customers }}</h3>
                    </div>
                    <i class="fas fa-users fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Completed Revenue</h6>
                        <h3 class="mb-0">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Pending Revenue</h6>
                        <h3 class="mb-0">Rp {{ number_format($pending_revenue, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-hourglass-half fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Reports & Analytics</h5>
                    <a href="{{ route('owner.statistics') }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-chart-bar"></i> Detailed Statistics
                    </a>
                    <a href="{{ route('owner.payments') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-credit-card"></i> Payment Details
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders by Status -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Orders by Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $statuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'];
                            $status_labels = ['Pending', 'Confirmed', 'In Progress', 'Completed', 'Cancelled'];
                            $status_colors = ['warning', 'info', 'primary', 'success', 'danger'];
                        @endphp
                        @foreach($statuses as $idx => $status)
                            <div class="col-md-2 text-center">
                                <h5>{{ $orders_by_status[$status] ?? 0 }}</h5>
                                <small class="text-muted">{{ $status_labels[$idx] }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Recent Orders</h5>
                </div>
                <div class="card-body">
                    @if($recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Package</th>
                                        <th>Amount</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->package->name }}</td>
                                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                @if($order->payment)
                                                    <span class="badge bg-{{ $order->payment->status === 'success' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($order->payment->status) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Unpaid</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge-status badge-{{ strtolower(str_replace('_', '-', $order->status)) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">No orders yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
