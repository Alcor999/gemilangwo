@extends('layouts.app')

@section('title', 'Pesanan Saya - Gemilang WO')

@section('content')
<div class="space-y-8 pb-12">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">My Bookings</p>
            <h1 class="font-serif text-4xl text-choco-900 leading-tight">Pemesanan <span class="italic text-stone-400">Saya</span></h1>
        </div>
        <x-luxury.button href="{{ route('customer.orders.create') }}" variant="primary" size="sm">
            <i class="fas fa-plus mr-2"></i> Buat Pemesanan Baru
        </x-luxury.button>
    </div>

    @if($orders->count() > 0)
        <x-luxury.card class="overflow-hidden border-stone-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-stone-50/80 border-b border-stone-100 text-[10px] font-bold uppercase tracking-widest text-stone-400">
                            <th class="px-6 py-4">Pesanan</th>
                            <th class="px-6 py-4">Paket</th>
                            <th class="px-6 py-4">Acara</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Pembayaran</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @foreach($orders as $order)
                            <tr class="hover:bg-stone-50/40 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-choco-900">#{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-stone-600">{{ $order->package->name }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-choco-900">{{ $order->event_date->format('d M Y') }}</div>
                                    <div class="text-[10px] text-stone-400 truncate max-w-[120px]">{{ $order->event_location }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-choco-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $stMap = [
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            'confirmed' => 'bg-emerald-50 text-emerald-700',
                                            'in_progress' => 'bg-blue-50 text-blue-700',
                                            'completed' => 'bg-stone-100 text-stone-700',
                                            'cancelled' => 'bg-rose-50 text-rose-600',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest {{ $stMap[$order->status] ?? 'bg-stone-100 text-stone-600' }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->payment_status === 'fully_paid')
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600">Lunas</span>
                                    @elseif($order->payment_status === 'dp_paid')
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600">DP Lunas</span>
                                    @elseif($order->payment_status === 'partially_paid')
                                        @php $paidCount = $order->payments->where('status', 'success')->count(); @endphp
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700">Cicilan {{ $paidCount }}/{{ $order->payment_scheme === 'installment_3x' ? 3 : 2 }}</span>
                                    @elseif($order->payments->where('status', 'pending')->count() > 0)
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700">Menunggu</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-stone-100 text-stone-500">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2 flex-wrap">
                                        <a href="{{ route('customer.orders.show', $order->id) }}" class="px-3 py-1.5 rounded-lg border border-stone-200 text-[10px] font-bold uppercase tracking-widest text-choco-900 hover:bg-stone-50 transition-colors">
                                            Lihat
                                        </a>
                                        @if(!$order->isCancelled() && !$order->isCompleted() && $order->payment_status !== 'fully_paid')
                                            @if($order->payments->where('status', 'pending')->count() > 0)
                                                <a href="{{ route('customer.orders.paymentConfirm', $order->id) }}" class="px-3 py-1.5 rounded-lg bg-amber-100 text-[10px] font-bold uppercase tracking-widest text-amber-800 hover:bg-amber-200 transition-colors">
                                                    Konfirmasi
                                                </a>
                                            @else
                                                <a href="{{ route('customer.orders.payment', $order->id) }}" class="px-3 py-1.5 rounded-lg bg-gold-400 text-[10px] font-bold uppercase tracking-widest text-white hover:bg-gold-500 transition-colors">
                                                    Bayar
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-stone-100">{{ $orders->links() }}</div>
            @endif
        </x-luxury.card>
    @else
        <x-luxury.card class="p-16 text-center border-stone-100">
            <div class="h-16 w-16 bg-stone-50 rounded-3xl mx-auto flex items-center justify-center text-stone-200 mb-4">
                <i class="fas fa-file-invoice text-2xl"></i>
            </div>
            <p class="font-serif text-lg text-stone-400 italic mb-4">Anda belum memiliki pemesanan.</p>
            <x-luxury.button href="{{ route('customer.packages.index') }}" variant="outline" size="sm">Lihat Paket Kami</x-luxury.button>
        </x-luxury.card>
    @endif
</div>
@endsection
