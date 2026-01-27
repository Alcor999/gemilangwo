@extends('layouts.app')

@section('title', 'Verified Payments')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-check-circle me-2"></i>Verified Payments</h4>
                <div>
                    <a href="{{ route('admin.payments.pending') }}" class="btn btn-sm btn-outline-warning">
                        <i class="fas fa-hourglass-half me-1"></i>Pending ({{ \App\Models\Payment::where('verification_status', 'pending')->count() }})
                    </a>
                </div>
            </div>

            @if ($payments->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No verified payments yet.
                </div>
            @else
                <div class="card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Verified By</th>
                                    <th>Verified Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>
                                            <strong>{{ $payment->order->order_number }}</strong><br>
                                            <small class="text-muted">{{ $payment->order->package->name }}</small>
                                        </td>
                                        <td>
                                            {{ $payment->order->user->name }}<br>
                                            <small class="text-muted">{{ $payment->order->user->email }}</small>
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            {{ $payment->verifiedBy?->name ?? '-' }}
                                        </td>
                                        <td>
                                            <small>{{ $payment->paid_at?->format('d M Y H:i') ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ Str::limit($payment->verification_notes, 30) }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
