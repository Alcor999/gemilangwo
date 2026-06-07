@extends('layouts.app')

@section('title', 'Detail Pesanan - Gemilang WO')

@section('content')
@php
    $progressPercent = $order->total_price > 0 ? round(($order->total_paid / $order->total_price) * 100) : 0;
    $statusMap = [
        'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
        'confirmed' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-100',
        'completed' => 'bg-stone-100 text-stone-700 border-stone-200',
        'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
    ];
    $payStatusMap = [
        'fully_paid' => 'bg-emerald-50 text-emerald-600',
        'dp_paid' => 'bg-blue-50 text-blue-600',
        'partially_paid' => 'bg-amber-50 text-amber-700',
        'unpaid' => 'bg-rose-50 text-rose-600',
    ];
@endphp

<div class="space-y-8 pb-16">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Order Detail</p>
            <h1 class="font-serif text-4xl text-choco-900 leading-tight">#{{ $order->order_number }}</h1>
        </div>
        <span class="self-start px-4 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest border {{ $statusMap[$order->status] ?? 'bg-stone-50 text-stone-600' }}">
            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main --}}
        <div class="lg:col-span-2 space-y-6">
            <x-luxury.card class="p-8 border-stone-100">
                <h2 class="font-serif text-xl text-choco-900 mb-6 pb-4 border-b border-stone-100">Informasi Pesanan</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Paket</p>
                        <p class="font-bold text-choco-900">{{ $order->package->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Tanggal Pesanan</p>
                        <p class="text-choco-900">{{ $order->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Tanggal Acara</p>
                        <p class="text-choco-900">{{ $order->event_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Jumlah Tamu</p>
                        <p class="text-choco-900">{{ $order->guest_count }} tamu</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Lokasi</p>
                        <p class="text-choco-900">{{ $order->event_location }}</p>
                    </div>
                    @if($order->special_request)
                        <div class="col-span-2 p-4 rounded-xl bg-stone-50 border border-stone-100">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Permintaan Khusus</p>
                            <p class="text-sm text-stone-600 italic">{{ $order->special_request }}</p>
                        </div>
                    @endif
                </div>

                @if($order->orderVendors->count() > 0)
                    <div class="mt-8 pt-6 border-t border-stone-100">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-4">Vendor Terpilih</p>
                        <div class="space-y-2">
                            @foreach($order->orderVendors as $ov)
                                <div class="flex justify-between items-center p-3 rounded-xl bg-stone-50 text-sm">
                                    <span class="text-choco-900"><strong>{{ $ov->vendor_category_name }}:</strong> {{ $ov->vendor_name }}</span>
                                    <span class="font-bold text-stone-600">Rp {{ number_format($ov->price, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-8 pt-6 border-t border-stone-100 flex justify-between items-end">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Total Pesanan</span>
                    <span class="text-3xl font-serif font-bold text-gold-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </x-luxury.card>

            @if(isset($paymentSummary) && count($paymentSummary['schedule'] ?? []) > 1)
                <x-luxury.card class="p-6 border-stone-100">
                    <h2 class="font-serif text-lg text-choco-900 mb-4"><i class="fas fa-calendar-alt text-gold-500 mr-2"></i>Jadwal Pembayaran</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach($paymentSummary['schedule'] as $item)
                            @php
                                $isCurrent = isset($nextPayment) && ($nextPayment['payment_type'] ?? '') === ($item['payment_type'] ?? 'full') && ($nextPayment['installment_number'] ?? null) == ($item['installment_number'] ?? null);
                            @endphp
                            <div class="p-4 rounded-xl border {{ $isCurrent ? 'border-gold-400 bg-gold-50/50' : 'border-stone-100 bg-stone-50/50' }}">
                                <p class="text-[10px] text-stone-400 mb-1">{{ $item['label'] }}</p>
                                <p class="font-bold text-choco-900">Rp {{ number_format($item['amount'], 0, ',', '.') }}</p>
                                @if($item['due_date'])
                                    <p class="text-[10px] text-stone-400 mt-1">{{ \Carbon\Carbon::parse($item['due_date'])->format('d M Y') }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-luxury.card>
            @endif

            <x-luxury.card :padding="'p-0'" class="overflow-hidden border-stone-100">
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <h2 class="font-serif text-lg text-choco-900"><i class="fas fa-history text-gold-500 mr-2"></i>Riwayat Pembayaran</h2>
                        <a href="{{ route('customer.orders.paymentHistory', $order->id) }}" class="text-[10px] font-bold uppercase tracking-widest text-gold-600 hover:text-gold-700">Lihat Semua</a>
                    </div>
                </x-slot:header>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-stone-100 text-[10px] font-bold uppercase tracking-widest text-stone-400">
                                <th class="px-6 py-3">Pembayaran</th>
                                <th class="px-6 py-3">Jumlah</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50">
                            @forelse($order->payments as $payment)
                                <tr class="hover:bg-stone-50/40">
                                    <td class="px-6 py-3 font-medium text-choco-900">{{ $payment->type_label }}</td>
                                    <td class="px-6 py-3 font-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3">
                                        @if($payment->verification_status === 'verified')
                                            <span class="text-[10px] font-bold text-emerald-600">Terverifikasi</span>
                                        @elseif($payment->verification_status === 'pending')
                                            <span class="text-[10px] font-bold text-amber-600">Menunggu</span>
                                        @else
                                            <span class="text-[10px] font-bold text-rose-600">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right text-xs text-stone-400">{{ $payment->created_at->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-10 text-center text-stone-400 italic">Belum ada pembayaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-luxury.card>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <x-luxury.card class="p-6 border-gold-100 bg-gradient-to-br from-white to-gold-50/20">
                <h2 class="font-serif text-lg text-choco-900 mb-5"><i class="fas fa-wallet text-gold-500 mr-2"></i>Status Pembayaran</h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-stone-400">Skema</span><span class="font-bold text-choco-900">{{ $order->scheme_label }}</span></div>
                    <div class="flex justify-between items-center">
                        <span class="text-stone-400">Progres</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $payStatusMap[$order->payment_status] ?? '' }}">{{ $order->payment_status_label }}</span>
                    </div>
                    <div class="flex justify-between"><span class="text-stone-400">Total</span><span class="font-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-stone-400">Dibayar</span><span class="font-bold text-emerald-600">Rp {{ number_format($order->total_paid, 0, ',', '.') }}</span></div>
                    <div class="flex justify-between"><span class="text-stone-400">Sisa</span><span class="font-bold text-rose-600">Rp {{ number_format($order->remaining_amount, 0, ',', '.') }}</span></div>
                </div>

                <div class="mt-5">
                    <div class="flex justify-between text-[10px] text-stone-400 mb-1">
                        <span>Progress</span><span class="font-bold text-gold-600">{{ $progressPercent }}%</span>
                    </div>
                    <div class="h-2 bg-stone-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-gold-400 to-emerald-500 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>

                @if($order->remaining_amount > 0 && !$order->isCancelled() && !$order->isCompleted())
                    @php $pendingPayment = $order->payments()->where('status', 'pending')->first(); @endphp
                    <div class="mt-6 space-y-3">
                        @if($pendingPayment)
                            <div class="p-3 rounded-xl bg-amber-50 border border-amber-100 text-xs text-amber-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Ada pembayaran menunggu konfirmasi transfer.
                            </div>
                            <x-luxury.button href="{{ route('customer.orders.paymentConfirm', $order->id) }}" variant="secondary" class="w-full justify-center">Konfirmasi Transfer</x-luxury.button>
                        @else
                            <x-luxury.button href="{{ route('customer.orders.payment', $order->id) }}" variant="primary" class="w-full justify-center">
                                <i class="fas fa-credit-card mr-2"></i> Bayar Tahap Berikutnya
                            </x-luxury.button>
                        @endif
                    </div>
                @endif
            </x-luxury.card>

            @if(!$order->isCompleted() && !$order->isCancelled() && ($order->isPending() || $order->isConfirmed()))
                <x-luxury.card class="p-6 border-stone-100">
                    <h2 class="font-serif text-sm text-choco-900 mb-4">Aksi Pesanan</h2>
                    <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST">
                        @csrf
                        <button type="button" class="w-full py-3 rounded-xl border border-rose-200 text-rose-600 text-[10px] font-bold uppercase tracking-widest hover:bg-rose-50 transition-colors"
                            data-confirm="{{ $order->total_paid > 0 ? 'Batalkan pesanan? DP Rp '.number_format($order->total_paid,0,',','.').' hangus.' : 'Batalkan pesanan ini?' }}"
                            data-confirm-title="Batalkan Pesanan"
                            data-confirm-btn="Ya, Batalkan"
                            data-confirm-danger="1">
                            <i class="fas fa-times mr-1"></i> Batalkan Pesanan
                        </button>
                    </form>
                </x-luxury.card>
            @endif
        </div>
    </div>
</div>
@endsection
