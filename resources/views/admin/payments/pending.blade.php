@extends('layouts.app')

@section('title', 'Pembayaran Menunggu Verifikasi')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4><i class="fas fa-hourglass-half me-2"></i>Pembayaran Menunggu Verifikasi</h4>
                <div>
                    <a href="{{ route('admin.payments.verified') }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-check me-1"></i>Terverifikasi ({{ \App\Models\Payment::where('verification_status', 'verified')->count() }})
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($payments->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Tidak ada pembayaran yang menunggu verifikasi saat ini.
                </div>
            @else
                <div class="card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th>Nomor Pesanan</th>
                                    <th>Pelanggan</th>
                                    <th>Jumlah</th>
                                    <th>Bank</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
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
                                            {{ $payment->bank->name }}
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>Menunggu
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $payment->created_at->format('d M Y H:i') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.payments.verify', $payment->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye me-1"></i>Verifikasi
                                            </a>
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
