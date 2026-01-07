@extends('layouts.app')

@section('title', 'Customer Lifetime Value - Owner Analytics')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-pie"></i> Customer Lifetime Value
            </h1>
            <p class="text-muted">Analyze customer value and repeat bookings</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Repeat Customers</p>
                    <h4>{{ $repeatCustomers->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <p class="text-muted small mb-1">Avg Orders Per Customer</p>
                    <h4>{{ round($repeatCustomers->avg('order_count'), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <p class="text-muted small mb-1">Highest LTV</p>
                    <h4>{{ $clvData->max('ltv') ? 'Rp ' . number_format($clvData->max('ltv'), 0, ',', '.') : '-' }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <p class="text-muted small mb-1">Avg LTV</p>
                    <h4>{{ $clvData->count() > 0 ? 'Rp ' . number_format($clvData->avg('ltv'), 0, ',', '.') : '-' }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers by LTV -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-crown"></i> Top Customers by Lifetime Value</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Customer Name</th>
                                <th class="text-end">Total Orders</th>
                                <th class="text-end">Lifetime Value</th>
                                <th class="text-end">Avg Order Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clvData as $customer)
                                <tr>
                                    <td>
                                        @if($customer->user)
                                            {{ $customer->user->name }}
                                        @else
                                            Unknown Customer
                                        @endif
                                    </td>
                                    <td class="text-end">{{ $customer->orders }}</td>
                                    <td class="text-end">
                                        <strong>Rp {{ number_format($customer->ltv, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($customer->ltv / max($customer->orders, 1), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> No customer data
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Repeat Customer Analysis -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-redo"></i> Repeat Customers Analysis</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Customer Name</th>
                                <th class="text-end">Repeat Orders</th>
                                <th class="text-end">First Order</th>
                                <th class="text-end">Last Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($repeatCustomers as $customer)
                                <tr>
                                    <td>
                                        @if($customer->user)
                                            {{ $customer->user->name }}
                                        @else
                                            Unknown Customer
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success">{{ $customer->order_count }}</span>
                                    </td>
                                    <td class="text-end text-muted small">
                                        {{ $customer->user && $customer->user->orders->min('created_at') ? $customer->user->orders->min('created_at')->format('d M Y') : '-' }}
                                    </td>
                                    <td class="text-end text-muted small">
                                        {{ $customer->user && $customer->user->orders->max('created_at') ? $customer->user->orders->max('created_at')->format('d M Y') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> No repeat customers yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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
