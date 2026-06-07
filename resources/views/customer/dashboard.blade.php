@extends('layouts.app')

@section('title', 'Dasbor Saya - Gemilang WO')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-gold-500 text-xs font-bold uppercase tracking-[0.2em] mb-2">Selamat Datang Kembali</p>
            <h1 class="font-serif text-4xl text-choco-900 leading-tight">Persiapan <span class="italic">Hari Bahagia</span></h1>
        </div>
        <div class="bg-white/50 backdrop-blur-md px-6 py-3 rounded-2xl border border-stone-100 shadow-sm hidden md:block">
            <span class="text-stone-400 text-[10px] font-bold uppercase tracking-widest block mb-1">Hari Ini</span>
            <span class="text-choco-800 font-bold text-sm">{{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-luxury.card class="bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Semua Pesanan</p>
                    <p class="text-3xl font-serif text-choco-900">{{ $total_orders }}</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-gold-50 flex items-center justify-center text-gold-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </x-luxury.card>

        <x-luxury.card class="bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Acara Selesai</p>
                    <p class="text-3xl font-serif text-choco-900">{{ $completed_orders }}</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </x-luxury.card>

        <x-luxury.card class="bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-stone-400 text-[10px] font-bold uppercase tracking-widest mb-1">Dalam Proses</p>
                    <p class="text-3xl font-serif text-choco-900">{{ $pending_orders }}</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-gold-400 flex items-center justify-center text-white shadow-lg shadow-gold-400/20">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </x-luxury.card>

        <x-luxury.card class="bg-choco-800 border-none">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gold-400/60 text-[10px] font-bold uppercase tracking-widest mb-1">Status Akun</p>
                    <p class="text-xl font-bold text-white">Platinum Member</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-white/10 flex items-center justify-center text-gold-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                    </svg>
                </div>
            </div>
        </x-luxury.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content: Recent Orders -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between px-2">
                <h2 class="font-serif text-2xl text-choco-900 italic">Pesanan Terbaru</h2>
                <a href="{{ route('customer.orders.index') }}" class="text-[10px] font-bold uppercase tracking-widest text-gold-500 hover:text-gold-600 transition-colors">Lihat Semua</a>
            </div>

            @if($recent_orders->count() > 0)
                <div class="bg-white rounded-3xl border border-stone-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-stone-50/50">
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400">Order ID</th>
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400">Paket</th>
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400">Tanggal Acara</th>
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400">Status</th>
                                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-50">
                                @foreach($recent_orders as $order)
                                    <tr class="group hover:bg-stone-50/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="font-bold text-choco-800 text-sm">#{{ $order->order_number }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-stone-600 text-sm font-medium">{{ Str::limit($order->package->name, 20) }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-stone-500 text-sm">{{ $order->event_date->format('d M Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-gold-50 text-gold-600 border-gold-100',
                                                    'processing' => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                    'cancelled' => 'bg-stone-50 text-stone-500 border-stone-100',
                                                ];
                                                $color = $statusColors[$order->status] ?? 'bg-stone-50 text-stone-500';
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $color }}">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('customer.orders.show', $order->id) }}" 
                                               class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-stone-100 text-choco-800 hover:bg-gold-400 hover:text-white transition-all group-hover:scale-110">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <x-luxury.card class="bg-white py-20 text-center">
                    <div class="h-20 w-20 rounded-full bg-stone-50 flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-stone-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="font-serif text-xl text-choco-900 mb-2">Belum ada Pemesanan</h3>
                    <p class="text-stone-400 text-sm max-w-xs mx-auto mb-8">Mulailah mewujudkan impian Anda dengan memilih salah satu paket eksklusif kami.</p>
                    <x-luxury.button href="{{ route('customer.packages.index') }}" class="w-full sm:w-auto">Eksplor Paket</x-luxury.button>
                </x-luxury.card>
            @endif
        </div>

        <!-- Sidebar: Quick Actions -->
        <div class="space-y-8">
            <h2 class="font-serif text-2xl text-choco-900 italic px-2">Aksi Cepat</h2>
            
            <div class="space-y-4">
                <a href="{{ route('customer.packages.index') }}" class="group block p-6 bg-white rounded-3xl border border-stone-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center gap-6">
                        <div class="h-14 w-14 rounded-2xl bg-gold-50 flex items-center justify-center text-gold-500 group-hover:bg-gold-400 group-hover:text-white transition-colors duration-300">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 16v2m3-6v6m3-3v3M9 9h.01M12 12h.01M15 9h.01M21 12c0 1.657-1.007 3-2.25 3S16.5 13.657 16.5 12s1.007-3 2.25-3S21 10.343 21 12zm-9 3c1.243 0 2.25-1.343 2.25-3s-1.007-3-2.25-3-2.25 1.343-2.25 3 1.007 3 2.25 3zm-9 0c1.243 0 2.25-1.343 2.25-3s-1.007-3-2.25-3-2.25 1.343-2.25 3 1.007 3 2.25 3z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-choco-900 font-bold">Cari Paket</p>
                            <p class="text-stone-400 text-xs mt-1">Temukan konsep impian Anda</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('customer.wishlist.index') }}" class="group block p-6 bg-white rounded-3xl border border-stone-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center gap-6">
                        <div class="h-14 w-14 rounded-2xl bg-stone-50 flex items-center justify-center text-stone-400 group-hover:bg-rose-400 group-hover:text-white transition-colors duration-300">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-choco-900 font-bold">Wishlist</p>
                            <p class="text-stone-400 text-xs mt-1">Paket yang Anda sukai</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('customer.support.tickets.create') }}" class="group block p-6 bg-choco-900 rounded-3xl border-none shadow-xl hover:shadow-choco-900/20 hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center gap-6">
                        <div class="h-14 w-14 rounded-2xl bg-white/10 flex items-center justify-center text-gold-400 group-hover:bg-gold-400 group-hover:text-white transition-colors duration-300">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-bold">Butuh Bantuan?</p>
                            <p class="text-stone-400 text-xs mt-1 text-gold-400/60 font-medium italic">Chat de Concierge</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
