@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @php
        $statusLabels = [
            'success' => 'Berhasil',
            'pending' => 'Menunggu',
            'failed' => 'Gagal',
        ];
    @endphp
    <h1 class="h3 mb-4">Laporan Pembayaran</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Metode Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payment_methods as $method)
                                    <tr>
                                        <td>{{ $method->payment_method ? ucfirst(str_replace('_', ' ', $method->payment_method)) : 'Belum Dibayar' }}</td>
                                        <td><span class="badge bg-primary">{{ $method->count }}</span></td>
                                        <td>Rp{{ number_format($method->total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada data</td>
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
                    <h5 class="mb-0">Status Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payment_status as $status)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $status->status === 'success' ? 'success' : ($status->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ $statusLabels[$status->status] ?? ucfirst($status->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $status->count }}</td>
                                        <td>Rp{{ number_format($status->total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Tidak ada data</td>
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
            <h5 class="mb-0">Transaksi Pembayaran Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nomor Pesanan</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
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
                                        <span class="badge bg-success">Berhasil</span>
                                    @elseif($payment->status === 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @else
                                        <span class="badge bg-danger">Gagal</span>
                                    @endif
                                </td>
                                <td>{{ isset($payment->created_at) ? \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Tidak ada pembayaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
