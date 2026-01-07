@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-4">Payment Reports</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Payment Methods</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Payment Method</th>
                                    <th>Count</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payment_methods as $method)
                                    <tr>
                                        <td>{{ $method->payment_method ? ucfirst(str_replace('_', ' ', $method->payment_method)) : 'Unpaid' }}</td>
                                        <td><span class="badge bg-primary">{{ $method->count }}</span></td>
                                        <td>Rp{{ number_format($method->total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Payment Status</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payment_status as $status)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $status->status === 'success' ? 'success' : ($status->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($status->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $status->count }}</td>
                                        <td>Rp{{ number_format($status->total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Recent Payment Transactions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order Number</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_payments as $payment)
                            <tr>
                                <td><strong>{{ $payment->order_number ?? '-' }}</strong></td>
                                <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($payment->status === 'success')
                                        <span class="badge bg-success">Success</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                                <td>{{ isset($payment->created_at) ? \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No payments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
