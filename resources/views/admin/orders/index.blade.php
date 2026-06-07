@extends('layouts.app')

@section('title', 'Logistik & Pesanan - Administrator')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em]">Operational Ledger</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Logistik <span class="not-italic text-stone-300">& Pesanan</span></h1>
        </div>
        
        <div class="flex items-center gap-4 bg-white/50 backdrop-blur-md px-6 py-3 rounded-2xl border border-stone-100">
            <div class="flex items-center gap-2">
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Sesi Real-time Terhubung</span>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <x-luxury.card class="overflow-hidden border-stone-100/50 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/50 border-b border-stone-100">
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">ID & Pelanggan</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Paket Terpilih</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Tanggal Acara</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Total Nilai</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Pembayaran</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Status</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($orders as $order)
                        <tr class="group hover:bg-stone-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-gold-600 uppercase tracking-widest">#{{ $order->order_number }}</p>
                                    <p class="text-xs font-bold text-choco-900 group-hover:text-gold-600 transition-colors">{{ $order->user->name }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-lg bg-stone-100 flex items-center justify-center text-stone-400 group-hover:bg-gold-50 group-hover:text-gold-500 transition-colors">
                                        <i class="fas fa-box-open text-[10px]"></i>
                                    </div>
                                    <span class="text-xs text-stone-500 font-light">{{ $order->package->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2 text-stone-400">
                                    <i class="far fa-calendar text-[10px]"></i>
                                    <span class="text-xs">{{ $order->event_date->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-xs font-bold text-choco-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-stone-500">{{ $order->payment_status_label }}</span>
                                @if($order->remaining_amount > 0)
                                    <div class="text-[9px] text-rose-400">Sisa Rp {{ number_format($order->remaining_amount, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td class="px-8 py-6">
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
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border {{ $class }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                       class="h-9 w-9 flex items-center justify-center rounded-xl bg-stone-50 text-stone-400 hover:bg-choco-900 hover:text-gold-400 transition-all border border-stone-100 hover:border-choco-900" 
                                       title="Review Pesanan">
                                        <i class="fas fa-external-link-alt text-[10px]"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-20 text-center">
                                <div class="space-y-4">
                                    <div class="h-16 w-16 bg-stone-50 rounded-3xl mx-auto flex items-center justify-center text-stone-200">
                                        <i class="fas fa-clipboard-list text-2xl"></i>
                                    </div>
                                    <p class="text-stone-400 font-serif italic">Belum ada pesanan yang masuk ke sistem.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginasi -->
        @if($orders->hasPages())
            <div class="px-8 py-6 border-t border-stone-50 bg-stone-50/30">
                {{ $orders->links() }}
            </div>
        @endif
    </x-luxury.card>
</div>
@endsection
