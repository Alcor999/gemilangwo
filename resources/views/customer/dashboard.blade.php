@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4" style="font-size: 2rem;">My Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-md-3">
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
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Completed</h6>
                        <h3 class="mb-0">{{ $completed_orders }}</h3>
                    </div>
                    <i class="fas fa-check-circle fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Pending</h6>
                        <h3 class="mb-0">{{ $pending_orders }}</h3>
                    </div>
                    <i class="fas fa-clock fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Booking Status</h6>
                        <h3 class="mb-0" style="font-size: 1.5rem;">@if($pending_orders > 0) <span class="badge bg-warning">Active</span> @else <span class="badge bg-success">Free</span> @endif</h3>
                    </div>
                    <i class="fas fa-calendar fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="d-grid gap-2 d-sm-flex flex-wrap">
                        <a href="{{ route('customer.packages.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i> Browse Packages
                        </a>
                        <a href="{{ route('customer.orders.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> New Booking
                        </a>
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-list"></i> View All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0" style="font-size: 1.1rem;">Recent Bookings</h5>
                </div>
                <div class="card-body p-2 p-md-3">
                    @if($recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th style="font-size: 0.9rem;">Order ID</th>
                                        <th style="font-size: 0.9rem;">Package</th>
                                        <th style="font-size: 0.9rem;">Event Date</th>
                                        <th style="font-size: 0.9rem;">Location</th>
                                        <th style="font-size: 0.9rem;">Total</th>
                                        <th style="font-size: 0.9rem;">Status</th>
                                        <th style="font-size: 0.9rem;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr style="font-size: 0.9rem;">
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->package->name }}</td>
                                            <td>{{ $order->event_date->format('d M Y') }}</td>
                                            <td>{{ Str::limit($order->event_location, 30) }}</td>
                                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge-status badge-{{ strtolower(str_replace('_', '-', $order->status)) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.8rem;">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">You haven't made any bookings yet. <a href="{{ route('customer.packages.index') }}">Browse packages</a></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .stat-card h6 {
            font-size: 0.85rem;
        }

        .stat-card h3 {
            font-size: 1.5rem;
        }

        .btn-sm {
            padding: 0.4rem 0.6rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        h1 {
            font-size: 1.5rem;
        }

        .stat-card {
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .stat-card h6 {
            font-size: 0.8rem;
        }

        .stat-card h3 {
            font-size: 1.25rem;
        }

        .stat-card i {
            font-size: 1.5rem !important;
        }

        .table {
            font-size: 0.75rem;
        }

        .badge-status {
            font-size: 0.7rem;
        }
    }
</style>
@endsection
