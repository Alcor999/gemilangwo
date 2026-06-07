@extends('layouts.app')

@section('title', 'Executive Hub - Administrator')

@section('content')
<div class="space-y-12 pb-24">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.4em] mb-2">Management Terminal</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Rumah <span class="not-italic text-stone-300">Eksekutif</span></h1>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 bg-white/50 backdrop-blur-md px-4 py-2 rounded-xl border border-stone-100">
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[9px] font-bold uppercase tracking-widest text-stone-400">Live Services Active</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Revenue Card (Premium) -->
        <x-luxury.card class="bg-choco-900 border-none p-8 shadow-2xl relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 h-40 w-40 bg-gold-400/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
            <div class="relative z-10 space-y-4">
                <p class="text-gold-400 text-[10px] font-bold uppercase tracking-[0.2em]">Estimasi Pendapatan</p>
                <p class="text-3xl font-serif text-white tracking-tighter">Rp {{ number_format($total_revenue, 0, ',', '.') }}</p>
                <div class="flex items-center gap-2 pt-2">
                    <span class="text-[9px] font-bold uppercase tracking-widest text-gold-400/40">Update Real-time</span>
                </div>
            </div>
        </x-luxury.card>

        <x-luxury.card class="p-8 border-stone-100 shadow-sm hover:shadow-xl transition-all duration-500">
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest">Total Pesanan</p>
                    <div class="h-8 w-8 rounded-lg bg-stone-50 flex items-center justify-center text-stone-300">
                        <i class="fas fa-shopping-bag text-[10px]"></i>
                    </div>
                </div>
                <p class="text-3xl font-serif text-choco-900 tracking-tighter">{{ $total_orders }}</p>
                <p class="text-[9px] text-emerald-500 font-bold uppercase tracking-widest flex items-center gap-1">
                    <i class="fas fa-caret-up"></i> +12% Efficiency
                </p>
            </div>
        </x-luxury.card>

        <x-luxury.card class="p-8 border-stone-100 shadow-sm hover:shadow-xl transition-all duration-500">
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest">Basis Klien</p>
                    <div class="h-8 w-8 rounded-lg bg-stone-50 flex items-center justify-center text-stone-300">
                        <i class="fas fa-users text-[10px]"></i>
                    </div>
                </div>
                <p class="text-3xl font-serif text-choco-900 tracking-tighter">{{ $total_customers }}</p>
                <p class="text-[9px] text-stone-300 font-bold uppercase tracking-widest italic">Verified Prioritas</p>
            </div>
        </x-luxury.card>

        <x-luxury.card class="p-8 border-stone-100 shadow-sm hover:shadow-xl transition-all duration-500">
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest">Katalog Aktif</p>
                    <div class="h-8 w-8 rounded-lg bg-stone-50 flex items-center justify-center text-stone-300">
                        <i class="fas fa-gem text-[10px]"></i>
                    </div>
                </div>
                <p class="text-3xl font-serif text-choco-900 tracking-tighter">{{ $total_packages }}</p>
                <p class="text-[9px] text-gold-500 font-bold uppercase tracking-widest">Market Ready</p>
            </div>
        </x-luxury.card>
    </div>

    {{-- Charts --}}
    <x-dashboard.charts-section
        :charts="$charts"
        :filter-url="route('admin.dashboard')"
        :filter="$filter"
        :year="$filterYear"
        :month="$filterMonth"
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main: Recent Activity -->
        <div class="lg:col-span-2 space-y-8">
            <div class="flex items-center justify-between px-2">
                <h2 class="font-serif text-2xl text-choco-900 italic">Pesanan Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-bold uppercase tracking-widest text-gold-500 hover:text-gold-600 transition-colors">Manifest Lengkap</a>
            </div>

            <x-luxury.card class="overflow-hidden border-stone-100 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-stone-50/50 border-b border-stone-100">
                                <th class="px-8 py-4 text-[9px] font-bold uppercase tracking-[0.2em] text-stone-400">Order ID</th>
                                <th class="px-8 py-4 text-[9px] font-bold uppercase tracking-[0.2em] text-stone-400">Klien</th>
                                <th class="px-8 py-4 text-[9px] font-bold uppercase tracking-[0.2em] text-stone-400">Nilai Kontrak</th>
                                <th class="px-8 py-4 text-[9px] font-bold uppercase tracking-[0.2em] text-stone-400 text-center">Lifecycle</th>
                                <th class="px-8 py-4 text-[9px] font-bold uppercase tracking-[0.2em] text-stone-400 text-right">View</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50">
                            @foreach($recent_orders as $order)
                                <tr class="group hover:bg-stone-50/30 transition-colors">
                                    <td class="px-8 py-5">
                                        <span class="text-xs font-bold text-gold-600 tracking-widest">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-[11px] font-bold text-choco-900">{{ $order->user->name }}</span>
                                            <span class="text-[9px] text-stone-400 italic">Confirmed Client</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="text-xs font-bold text-choco-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                'confirmed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                'default' => 'bg-stone-50 text-stone-600 border-stone-100'
                                            ];
                                            $currentStatus = strtolower($order->status);
                                            $class = $statusClasses[$currentStatus] ?? $statusClasses['default'];
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-widest border {{ $class }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="h-8 w-8 inline-flex items-center justify-center rounded-xl border border-stone-100 text-stone-300 hover:bg-choco-900 hover:text-gold-400 transition-all">
                                            <i class="fas fa-chevron-right text-[10px]"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-luxury.card>
        </div>

        <!-- Sidebar: Shortcuts -->
        <div class="space-y-8">
            <h2 class="font-serif text-2xl text-choco-900 italic px-2">Kanal Operasional</h2>
            
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('admin.orders.index') }}" class="group p-6 bg-white rounded-3xl border border-stone-100 flex items-center justify-between hover:shadow-2xl hover:-translate-y-1 transition-all duration-500">
                    <div class="flex items-center gap-5">
                        <div class="h-12 w-12 rounded-2xl bg-stone-50 flex items-center justify-center text-stone-300 group-hover:bg-gold-500 group-hover:text-white transition-all duration-500">
                            <i class="fas fa-file-invoice text-sm"></i>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-choco-900 font-bold text-xs">Kelola Transaksi</p>
                            <p class="text-stone-400 text-[9px] uppercase font-bold tracking-[0.2em]">Audit & Logistic</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-[10px] text-stone-100 group-hover:text-gold-500 transition-colors"></i>
                </a>

                <a href="{{ route('admin.packages.index') }}" class="group p-6 bg-white rounded-3xl border border-stone-100 flex items-center justify-between hover:shadow-2xl hover:-translate-y-1 transition-all duration-500">
                    <div class="flex items-center gap-5">
                        <div class="h-12 w-12 rounded-2xl bg-stone-50 flex items-center justify-center text-stone-300 group-hover:bg-choco-900 group-hover:text-white transition-all duration-500">
                            <i class="fas fa-box-open text-sm"></i>
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-choco-900 font-bold text-xs">Kurasi Katalog</p>
                            <p class="text-stone-400 text-[9px] uppercase font-bold tracking-[0.2em]">Package Curator</p>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-[10px] text-stone-100 group-hover:text-gold-500 transition-colors"></i>
                </a>
            </div>

            <!-- Promotion Card -->
            <div class="bg-gradient-to-br from-stone-900 to-black rounded-[3rem] p-10 text-white relative overflow-hidden group shadow-2xl">
                <div class="absolute -right-10 -bottom-10 h-32 w-32 bg-gold-400/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>
                <div class="relative z-10 space-y-6">
                    <div>
                        <p class="text-gold-400 text-[9px] font-bold uppercase tracking-[0.4em] mb-3">Executive Summary</p>
                        <h4 class="font-serif text-xl italic leading-tight">Visi Layanan <span class="text-gold-500">Premium</span></h4>
                    </div>
                    <p class="text-white/50 text-[11px] font-light italic leading-relaxed">"Tingkatkan performa tim dengan memastikan semua pembayaran diverifikasi dalam kurun waktu kurang dari 24 jam."</p>
                    <a href="{{ route('admin.analytics.dashboard') }}" class="flex items-center justify-center w-full py-4 rounded-2xl bg-white/5 border border-white/10 text-gold-400 text-[10px] font-bold uppercase tracking-widest hover:bg-gold-500 hover:text-white transition-all">
                        Performa Lanjutan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
