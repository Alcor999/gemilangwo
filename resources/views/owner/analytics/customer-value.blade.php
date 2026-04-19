@extends('layouts.app')

@section('title', 'Nilai Seumur Hidup Pelanggan - Analitik Owner')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-pie"></i> Nilai Seumur Hidup Pelanggan
            </h1>
            <p class="text-muted">Analisis nilai pelanggan dan pemesanan berulang</p>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Pelanggan Berulang</p>
                    <h4>{{ $repeatCustomers->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <p class="text-muted small mb-1">Rata-rata Pesanan per Pelanggan</p>
                    <h4>{{ round($repeatCustomers->avg('order_count'), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <p class="text-muted small mb-1">LTV Tertinggi</p>
                    <h4>{{ $clvData->max('ltv') ? 'Rp ' . number_format($clvData->max('ltv'), 0, ',', '.') : '-' }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <p class="text-muted small mb-1">Rata-rata LTV</p>
                    <h4>{{ $clvData->count() > 0 ? 'Rp ' . number_format($clvData->avg('ltv'), 0, ',', '.') : '-' }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Pelanggan Teratas berdasarkan LTV -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h6 class="mb-0"><i class="fas fa-crown"></i> Pelanggan Teratas berdasarkan Nilai Seumur Hidup</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Pelanggan</th>
                                <th class="text-end">Total Pesanan</th>
                                <th class="text-end">Nilai Seumur Hidup</th>
                                <th class="text-end">Rata-rata Nilai Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clvData as $customer)
                                <tr>
                                    <td>
                                        @if($customer->user)
                                            {{ $customer->user->name }}
                                        @else
                                            Pelanggan Tidak Dikenal
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
                                        <i class="fas fa-inbox"></i> Tidak ada data pelanggan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Analisis Pelanggan Berulang -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h6 class="mb-0"><i class="fas fa-redo"></i> Analisis Pelanggan Berulang</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Pelanggan</th>
                                <th class="text-end">Pesanan Berulang</th>
                                <th class="text-end">Pesanan Pertama</th>
                                <th class="text-end">Pesanan Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($repeatCustomers as $customer)
                                <tr>
                                    <td>
                                        @if($customer->user)
                                            {{ $customer->user->name }}
                                        @else
                                            Pelanggan Tidak Dikenal
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
                                        <i class="fas fa-inbox"></i> Belum ada pelanggan berulang
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
