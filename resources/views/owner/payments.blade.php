@extends('layouts.app')

@section('title', 'Arus Kas - Gemilang WO')

@section('content')
@php
    $statusLabels = [
        'success' => 'Berhasil',
        'pending' => 'Menunggu',
        'failed' => 'Gagal',
    ];
    $typeLabels = ['full' => 'Lunas', 'dp' => 'DP', 'remaining' => 'Pelunasan', 'installment' => 'Cicilan'];
@endphp

<div class="space-y-10 pb-12">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2 px-1">Finance Terminal</p>
            <h1 class="font-serif text-4xl text-choco-900 leading-tight">Laporan <span class="italic text-stone-400">Pembayaran</span></h1>
        </div>
        <x-luxury.button href="{{ route('owner.payment-schemes.index') }}" variant="outline" size="sm">
            <i class="fas fa-credit-card mr-2"></i> Kelola Skema
        </x-luxury.button>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <x-luxury.card class="p-5 bg-white">
            <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">DP Diterima</p>
            <p class="text-2xl font-serif text-blue-600 tracking-tight">Rp {{ number_format($dp_total, 0, ',', '.') }}</p>
        </x-luxury.card>
        <x-luxury.card class="p-5 bg-white">
            <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Pelunasan</p>
            <p class="text-2xl font-serif text-emerald-600 tracking-tight">Rp {{ number_format($remaining_total, 0, ',', '.') }}</p>
        </x-luxury.card>
        <x-luxury.card class="p-5 bg-white">
            <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Cicilan</p>
            <p class="text-2xl font-serif text-sky-600 tracking-tight">Rp {{ number_format($installment_total, 0, ',', '.') }}</p>
        </x-luxury.card>
        <x-luxury.card class="p-5 bg-gradient-to-br from-amber-50 to-white border-amber-100">
            <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Piutang Outstanding</p>
            <p class="text-2xl font-serif text-amber-700 tracking-tight">Rp {{ number_format($outstanding_total, 0, ',', '.') }}</p>
            <p class="text-[10px] text-stone-400 mt-2">Forecast bulan ini: <span class="font-bold text-choco-900">Rp {{ number_format($forecast_revenue, 0, ',', '.') }}</span></p>
        </x-luxury.card>
    </div>

    {{-- Breakdown Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <x-luxury.card class="overflow-hidden border-stone-100">
            <x-slot:header>
                <h2 class="font-serif text-lg text-choco-900">Metode Pembayaran</h2>
            </x-slot:header>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-stone-100 text-[10px] font-bold uppercase tracking-widest text-stone-400">
                            <th class="px-6 py-4">Metode</th>
                            <th class="px-6 py-4">Jumlah</th>
                            <th class="px-6 py-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($payment_methods as $method)
                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-choco-900">{{ $method->payment_method ? ucfirst(str_replace('_', ' ', $method->payment_method)) : 'Belum Dibayar' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full bg-gold-50 text-gold-600 text-[10px] font-bold">{{ $method->count }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-choco-900">Rp {{ number_format($method->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-stone-400 text-sm italic">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-luxury.card>

        <x-luxury.card class="overflow-hidden border-stone-100">
            <x-slot:header>
                <h2 class="font-serif text-lg text-choco-900">Status Pembayaran</h2>
            </x-slot:header>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-stone-100 text-[10px] font-bold uppercase tracking-widest text-stone-400">
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Jumlah</th>
                            <th class="px-6 py-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @forelse($payment_status as $status)
                            <tr class="hover:bg-stone-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    @php
                                        $badgeClass = match($status->status) {
                                            'success' => 'bg-emerald-50 text-emerald-600',
                                            'pending' => 'bg-amber-50 text-amber-600',
                                            default => 'bg-rose-50 text-rose-600',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $badgeClass }}">
                                        {{ $statusLabels[$status->status] ?? ucfirst($status->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-stone-600">{{ $status->count }}</td>
                                <td class="px-6 py-4 text-right text-sm font-bold text-choco-900">Rp {{ number_format($status->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-stone-400 text-sm italic">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-luxury.card>
    </div>

    {{-- Breakdown by Type --}}
    @if($payment_types->count() > 0)
        <x-luxury.card class="p-6 border-stone-100">
            <h2 class="font-serif text-xl text-choco-900 mb-6">Breakdown per Tipe Pembayaran</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($payment_types as $type)
                    <div class="p-4 rounded-2xl bg-stone-50 border border-stone-100 text-center">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">
                            {{ $typeLabels[$type->payment_type] ?? ucfirst($type->payment_type ?? 'Lainnya') }}
                        </p>
                        <p class="text-lg font-serif font-bold text-choco-900">Rp {{ number_format($type->total, 0, ',', '.') }}</p>
                        <p class="text-[10px] text-stone-400 mt-1">{{ $type->count }} transaksi</p>
                    </div>
                @endforeach
            </div>
        </x-luxury.card>
    @endif

    {{-- Recent Transactions --}}
    <x-luxury.card class="overflow-hidden border-stone-100 shadow-sm">
        <x-slot:header>
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-xl text-choco-900">Transaksi Terbaru</h2>
                <span class="text-[10px] font-bold uppercase tracking-widest text-stone-400">20 entri terakhir</span>
            </div>
        </x-slot:header>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-stone-50/50 border-b border-stone-100 text-[10px] font-bold uppercase tracking-widest text-stone-400">
                        <th class="px-6 py-4">Nomor Pesanan</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Metode</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($recent_payments as $payment)
                        <tr class="group hover:bg-stone-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-choco-900">#{{ $payment->order_number ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-choco-900">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-widest">
                                    {{ $typeLabels[$payment->payment_type ?? 'full'] ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-stone-500">
                                {{ $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $stClass = match($payment->status) {
                                        'success' => 'bg-emerald-50 text-emerald-600',
                                        'pending' => 'bg-amber-50 text-amber-600',
                                        default => 'bg-rose-50 text-rose-600',
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $stClass }}">
                                    {{ $statusLabels[$payment->status] ?? $payment->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-xs text-stone-400">
                                {{ isset($payment->created_at) ? \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="space-y-3">
                                    <div class="h-14 w-14 bg-stone-50 rounded-2xl mx-auto flex items-center justify-center text-stone-200">
                                        <i class="fas fa-receipt text-xl"></i>
                                    </div>
                                    <p class="text-stone-400 font-serif italic text-sm">Belum ada transaksi pembayaran.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-luxury.card>
</div>
@endsection
