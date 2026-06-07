@extends('layouts.app')

@section('title', 'Business Dashboard - Gemilang WO')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2 px-1">Executive Overview</p>
            <h1 class="font-serif text-4xl text-choco-900 leading-tight">Insight <span class="italic text-stone-400">Bisnis</span></h1>
        </div>
        <div class="flex items-center gap-3">
            <x-luxury.button href="{{ route('owner.statistics') }}" variant="secondary" class="h-11 px-6">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Laporan Lengkap
            </x-luxury.button>
        </div>
    </div>

    <!-- Financial Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-2">
        <x-luxury.card class="bg-white p-4">
            <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">DP Diterima</p>
            <p class="text-xl font-serif text-blue-600">Rp {{ number_format($dp_received, 0, ',', '.') }}</p>
            <p class="text-[10px] text-stone-400 mt-1">{{ $partial_orders_count }} order belum lunas</p>
        </x-luxury.card>
        <x-luxury.card class="bg-white p-4">
            <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Piutang Outstanding</p>
            <p class="text-xl font-serif text-amber-600">Rp {{ number_format($outstanding_revenue, 0, ',', '.') }}</p>
        </x-luxury.card>
        <x-luxury.card class="bg-white p-4">
            <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Overdue</p>
            <p class="text-xl font-serif text-rose-600">Rp {{ number_format($overdue_amount, 0, ',', '.') }}</p>
            <p class="text-[10px] text-rose-400 mt-1">{{ $overdue_count }} transaksi</p>
        </x-luxury.card>
        <x-luxury.card class="bg-white p-4">
            <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Forecast Bulan Ini</p>
            <p class="text-xl font-serif text-emerald-600">Rp {{ number_format($forecast_revenue, 0, ',', '.') }}</p>
        </x-luxury.card>
        <x-luxury.card class="bg-white p-4 md:col-span-2">
            <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Dana Tertahan (Pending)</p>
            <p class="text-xl font-serif text-rose-500">Rp {{ number_format($pending_revenue, 0, ',', '.') }}</p>
        </x-luxury.card>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Main Revenue -->
        <x-luxury.card class="bg-choco-800 border-none md:col-span-2 relative overflow-hidden group">
            <div class="absolute -right-20 -top-20 h-64 w-64 bg-gold-400/5 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
            <div class="relative z-10 flex flex-col justify-between h-full">
                <div>
                    <p class="text-gold-400/60 text-[10px] font-bold uppercase tracking-widest mb-2">Total Omset (Selesai)</p>
                    <p class="text-4xl font-serif text-white tracking-tight">Rp {{ number_format($total_revenue, 0, ',', '.') }}</p>
                </div>
                <div class="mt-8 flex items-center justify-between border-t border-white/10 pt-6">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                        <span class="text-white/40 text-[10px] font-bold uppercase tracking-widest">Growth +24%</span>
                    </div>
                    <svg class="h-6 w-6 text-gold-400/20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
                </div>
            </div>
        </x-luxury.card>

        <x-luxury.card class="bg-white">
            <div class="flex flex-col justify-between h-full">
                <div>
                    <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1 text-center lg:text-left">Volume Pesanan</p>
                    <p class="text-3xl font-serif text-choco-900 text-center lg:text-left tracking-tight">{{ $total_orders }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-stone-50">
                    <div class="flex items-center justify-center lg:justify-start gap-1">
                        <svg class="h-3 w-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path></svg>
                        <span class="text-emerald-500 text-[10px] font-bold">12 New</span>
                    </div>
                </div>
            </div>
        </x-luxury.card>
    </div>

    {{-- Charts --}}
    <x-dashboard.charts-section
        :charts="$charts"
        :filter-url="route('owner.dashboard')"
        :filter="$filter"
        :year="$filterYear"
        :month="$filterMonth"
    />

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar: Status Breakdown -->
        <div class="space-y-8 lg:col-span-1">
            <h2 class="font-serif text-2xl text-choco-900 italic px-2">Pipeline</h2>
            
            <x-luxury.card class="bg-stone-50/50 border-none">
                <div class="space-y-6">
                    @php
                        $statuses = [
                            ['key' => 'pending', 'label' => 'Menunggu', 'color' => 'bg-gold-400'],
                            ['key' => 'confirmed', 'label' => 'Dikonfirmasi', 'color' => 'bg-blue-400'],
                            ['key' => 'in_progress', 'label' => 'Berjalan', 'color' => 'bg-emerald-400'],
                            ['key' => 'completed', 'label' => 'Selesai', 'color' => 'bg-stone-800'],
                            ['key' => 'cancelled', 'label' => 'Batal', 'color' => 'bg-stone-300'],
                        ];
                    @endphp

                    @foreach($statuses as $status)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-2 w-2 rounded-full {{ $status['color'] }}"></div>
                                <span class="text-stone-500 text-xs font-medium">{{ $status['label'] }}</span>
                            </div>
                            <span class="text-choco-900 font-bold text-sm">{{ $orders_by_status[$status['key']] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            </x-luxury.card>

            <a href="{{ route('owner.payment-schemes.index') }}" class="group block p-6 bg-white rounded-3xl border border-stone-100 shadow-sm hover:shadow-xl transition-all duration-300 mb-4">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <span class="text-choco-900 font-bold text-sm">Skema Pembayaran</span>
                </div>
            </a>

            <a href="{{ route('owner.payments') }}" class="group block p-6 bg-white rounded-3xl border border-stone-100 shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-gold-50 flex items-center justify-center text-gold-500 group-hover:bg-gold-400 group-hover:text-white transition-colors">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-choco-900 font-bold text-sm">Arus Kas</span>
                    <svg class="ml-auto h-4 w-4 text-stone-300 group-hover:text-gold-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </div>
            </a>
        </div>

        <!-- Main: Recent Performance -->
        <div class="lg:col-span-3 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h2 class="font-serif text-2xl text-choco-900 italic">Pesanan Terkini</h2>
            </div>

            <div class="bg-white rounded-3xl border border-stone-100 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-stone-50/50">
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400">Order ID</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400">Klien & Paket</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400">Nilai</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400">Status Bayar</th>
                                <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-right">Status Operasional</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50">
                            @foreach($recent_orders as $order)
                                <tr class="group hover:bg-stone-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-choco-900 text-sm">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-choco-800 text-sm font-bold">{{ $order->user->name }}</span>
                                            <span class="text-stone-400 text-[10px] uppercase tracking-tighter">{{ Str::limit($order->package->name, 30) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-choco-900 text-sm font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $payLabels = ['fully_paid' => 'Lunas', 'dp_paid' => 'DP Lunas', 'partially_paid' => 'Cicilan', 'unpaid' => 'Belum Bayar'];
                                            $payColors = ['fully_paid' => 'text-emerald-600', 'dp_paid' => 'text-blue-600', 'partially_paid' => 'text-amber-600', 'unpaid' => 'text-stone-400'];
                                        @endphp
                                        <span class="text-xs font-bold {{ $payColors[$order->payment_status] ?? 'text-stone-400' }} uppercase tracking-widest">
                                            {{ $payLabels[$order->payment_status] ?? $order->payment_status }}
                                        </span>
                                        @if($order->remaining_amount > 0)
                                            <div class="text-[10px] text-stone-400">Sisa Rp {{ number_format($order->remaining_amount, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex px-3 py-1 bg-stone-100 rounded-full text-[10px] font-bold text-choco-800 uppercase tracking-widest">
                                            {{ str_replace('_', ' ', $order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
