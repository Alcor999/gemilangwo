@extends('layouts.app')

@section('content')
<div class="container mt-4">
    @php
        $orderStatusLabels = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
    @endphp
    <h1 class="h3 mb-4">Statistik Bisnis</h1>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Pesanan per Paket</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama Paket</th>
                                    <th>Pesanan</th>
                                    <th>Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($package_orders as $pkg)
                                    <tr>
                                        <td>{{ $pkg->name }}</td>
                                        <td><span class="badge bg-primary">{{ $pkg->total }}</span></td>
                                        <td>Rp{{ number_format($pkg->revenue, 0, ',', '.') }}</td>
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
                    <h5 class="mb-0">Pesanan per Status</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($status_summary as $status)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ $status->status === 'pending' ? 'warning' : ($status->status === 'confirmed' ? 'info' : ($status->status === 'completed' ? 'success' : 'danger')) }}">
                                                {{ $orderStatusLabels[$status->status] ?? ucfirst($status->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $status->count }}</td>
                                        <td>Rp{{ number_format($status->total_price, 0, ',', '.') }}</td>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Pelanggan Berulang</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Pelanggan</th>
                                    <th>Email</th>
                                    <th>Total Pesanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repeat_customers as $customer)
                                    @if($customer->order_count > 0)
                                        <tr>
                                            <td><strong>{{ $customer->name }}</strong></td>
                                            <td>{{ $customer->email }}</td>
                                            <td><span class="badge bg-primary">{{ $customer->order_count }}</span></td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Tidak ada pelanggan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
