@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4" style="font-size: 2rem;">Admin Dashboard</h1>

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
                        <h6 class="mb-2">Total Customers</h6>
                        <h3 class="mb-0">{{ $total_customers }}</h3>
                    </div>
                    <i class="fas fa-users fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Active Packages</h6>
                        <h3 class="mb-0">{{ $total_packages }}</h3>
                    </div>
                    <i class="fas fa-box fa-2x" style="opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-2">Total Revenue</h6>
                        <h3 class="mb-0">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h3>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x" style="opacity: 0.5;"></i>
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
                        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Package
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-list"></i> View All Orders
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-users"></i> Manage Users
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
                    <h5 class="mb-0" style="font-size: 1.1rem;">Recent Orders</h5>
                </div>
                <div class="card-body p-2 p-md-3">
                    @if($recent_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th style="font-size: 0.9rem;">Order ID</th>
                                        <th style="font-size: 0.9rem;">Customer</th>
                                        <th style="font-size: 0.9rem;">Package</th>
                                        <th style="font-size: 0.9rem;">Date</th>
                                        <th style="font-size: 0.9rem;">Total</th>
                                        <th style="font-size: 0.9rem;">Status</th>
                                        <th style="font-size: 0.9rem;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_orders as $order)
                                        <tr style="font-size: 0.9rem;">
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->user->name }}</td>
                                            <td>{{ $order->package->name }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge-status badge-{{ strtolower(str_replace('_', '-', $order->status)) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" style="font-size: 0.8rem;">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">No orders yet.</div>
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
