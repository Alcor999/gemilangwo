@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">
        <h1 class="mb-1" style="font-family: \'Playfair Display\', serif; font-size: 2rem; font-weight: 600; color: var(--text-dark);">Pemesanan Saya</h1>
        <a href="{{ route('customer.orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Pemesanan Baru
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: rgba(0,0,0,0.02);">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Paket</th>
                        <th>Tanggal Acara</th>
                        <th>Lokasi</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
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
                                    <span class="badge bg-success">Sudah Dibayar</span>
                                @elseif($order->payment && $order->payment->status === 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @else
                                    <span class="badge bg-secondary">Belum Dibayar</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                @if($order->isPending() && (!$order->payment || !$order->payment->isSuccess()))
                                    <a href="{{ route('customer.orders.payment', $order->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-credit-card"></i> Bayar
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Anda belum melakukan pemesanan. <a href="{{ route('customer.packages.index') }}">Lihat paket kami</a> dan buat pemesanan pertama Anda!
        </div>
    @endif
</div>
@endsection
