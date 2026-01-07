@extends('layouts.app')

@section('title', 'Analytics Dashboard - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line"></i> Analytics Dashboard
            </h1>
            <p class="text-muted">System-wide analytics and insights</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <!-- Revenue Today -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Revenue Today</p>
                            <h4 class="mb-0">Rp {{ number_format($revenueToday, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue This Month -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Revenue This Month</p>
                            <h4 class="mb-0">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</h4>
                        </div>
                        <i class="fas fa-calendar-alt fa-3x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Total Customers</p>
                            <h4 class="mb-0">{{ $totalCustomers }}</h4>
                        </div>
                        <i class="fas fa-users fa-3x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conversion Rate -->
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-1">Conversion Rate</p>
                            <h4 class="mb-0">{{ $conversionRate }}%</h4>
                        </div>
                        <i class="fas fa-chart-pie fa-3x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Overview -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-shopping-cart"></i> Orders Overview</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted small">Total Orders</p>
                                <h3>{{ $totalOrders }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted small">Completed Orders</p>
                                <h3 class="text-success">{{ $completedOrders }}</h3>
                                <small class="text-muted">{{ round(($completedOrders / max($totalOrders, 1)) * 100, 1) }}% completion rate</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-user-plus"></i> Customer Acquisition</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted small">New This Month</p>
                                <h3>{{ $newCustomersThisMonth }}</h3>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted small">This Year Total</p>
                                <h3>{{ $totalCustomers }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Links -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-link"></i> Analytics Reports</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.revenue') }}" class="btn btn-outline-primary btn-block w-100">
                                <i class="fas fa-chart-bar"></i> Revenue Reports
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.customers') }}" class="btn btn-outline-info btn-block w-100">
                                <i class="fas fa-users"></i> Customer Analysis
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.packages') }}" class="btn btn-outline-success btn-block w-100">
                                <i class="fas fa-box"></i> Package Performance
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.conversion') }}" class="btn btn-outline-warning btn-block w-100">
                                <i class="fas fa-funnel"></i> Conversion Funnel
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('admin.analytics.payments') }}" class="btn btn-outline-danger btn-block w-100">
                                <i class="fas fa-credit-card"></i> Payment Methods
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="#" class="btn btn-outline-secondary btn-block w-100" data-bs-toggle="modal" data-bs-target="#exportModal">
                                <i class="fas fa-download"></i> Export Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Analytics Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('admin.analytics.export') }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select name="type" class="form-select" required>
                            <option value="">Select Report Type</option>
                            <option value="revenue">Revenue Report</option>
                            <option value="customers">Customer Report</option>
                            <option value="packages">Package Report</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="pdf" id="formatPdf" checked>
                                <label class="form-check-label" for="formatPdf">
                                    PDF
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="format" value="excel" id="formatExcel">
                                <label class="form-check-label" for="formatExcel">
                                    Excel
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Period</label>
                        <select name="period" class="form-select">
                            <option value="month">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Export</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 0.25rem solid #007bff !important;
    }
    .border-left-success {
        border-left: 0.25rem solid #28a745 !important;
    }
    .border-left-info {
        border-left: 0.25rem solid #17a2b8 !important;
    }
    .border-left-warning {
        border-left: 0.25rem solid #ffc107 !important;
    }
</style>
@endsection
