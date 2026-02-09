@extends('layouts.app')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Manajemen Pesanan</h1>

    <div class="card">
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Tanggal Acara</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->package->name }}</td>
                                    <td>{{ $order->event_date->format('d M Y') }}</td>
                                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge-status badge-{{ strtolower(str_replace('_', '-', $order->status)) }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($order->payment && $order->payment->isSuccess())
                                            <span class="badge bg-success">Sudah Dibayar</span>
                                        @else
                                            <span class="badge bg-secondary">Belum Dibayar</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
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
                <div class="alert alert-info">Belum ada pesanan.</div>
            @endif
        </div>
    </div>
</div>
@endsection
