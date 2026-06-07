@extends('layouts.app')

@section('title', 'Riwayat Pembayaran - Gemilang WO')

@section('content')
<div class="space-y-8 pb-16">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Payment History</p>
            <h1 class="font-serif text-4xl text-choco-900 leading-tight">Riwayat <span class="italic text-stone-400">Pembayaran</span></h1>
            <p class="text-sm text-stone-400 mt-2">#{{ $order->order_number }} — {{ $order->package->name }}</p>
        </div>
        <x-luxury.button href="{{ route('customer.orders.show', $order->id) }}" variant="ghost" size="sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </x-luxury.button>
    </div>

    {{-- Progress --}}
    <x-luxury.card :padding="'p-6'" class="border-gold-100">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex-1">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-stone-400">Skema: <strong class="text-choco-900">{{ $paymentSummary['scheme_label'] }}</strong></span>
                    <span class="font-bold text-gold-600">{{ $paymentSummary['progress_percent'] }}% terbayar</span>
                </div>
                <div class="h-3 bg-stone-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-gold-400 to-emerald-500 rounded-full" style="width: {{ $paymentSummary['progress_percent'] }}%"></div>
                </div>
                <div class="flex justify-between mt-2 text-xs text-stone-400">
                    <span>Dibayar: <strong class="text-emerald-600">Rp {{ number_format($paymentSummary['total_paid'], 0, ',', '.') }}</strong></span>
                    <span>Sisa: <strong class="text-rose-600">Rp {{ number_format($paymentSummary['remaining_amount'], 0, ',', '.') }}</strong></span>
                </div>
            </div>
            @if($paymentSummary['remaining_amount'] > 0 && !$order->isCancelled())
                <x-luxury.button href="{{ route('customer.orders.payment', $order->id) }}" variant="primary" size="sm">
                    <i class="fas fa-credit-card mr-2"></i> Bayar Berikutnya
                </x-luxury.button>
            @else
                <span class="px-4 py-2 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600"><i class="fas fa-check-circle mr-1"></i> Lunas</span>
            @endif
        </div>
    </x-luxury.card>

    {{-- Jadwal --}}
    <x-luxury.card :padding="'p-0'" class="overflow-hidden border-stone-100">
        <x-slot:header>
            <h2 class="font-serif text-lg text-choco-900"><i class="fas fa-calendar-check text-gold-500 mr-2"></i>Jadwal Pembayaran</h2>
        </x-slot:header>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-stone-100 text-[10px] font-bold uppercase tracking-widest text-stone-400">
                        <th class="px-6 py-4">Tahap</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Jatuh Tempo</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @foreach($paymentSummary['schedule'] as $index => $item)
                        @php
                            $matchedPayment = $order->payments->first(function($p) use ($item, $index) {
                                if (isset($item['payment_type'])) {
                                    if ($item['payment_type'] === 'installment') {
                                        return $p->payment_type === 'installment' && $p->installment_number == ($item['installment_number'] ?? $index + 1);
                                    }
                                    return $p->payment_type === $item['payment_type'];
                                }
                                return false;
                            });
                            $isPaid = $matchedPayment && $matchedPayment->status === 'success';
                            $isPending = $matchedPayment && $matchedPayment->status === 'pending';
                        @endphp
                        <tr class="hover:bg-stone-50/40">
                            <td class="px-6 py-4">
                                <p class="font-bold text-choco-900">{{ $item['label'] }}</p>
                                <p class="text-[10px] text-stone-400">{{ $item['percentage'] }}%</p>
                            </td>
                            <td class="px-6 py-4 font-bold">Rp {{ number_format($item['amount'], 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-stone-600">
                                @if($item['due_date'])
                                    {{ \Carbon\Carbon::parse($item['due_date'])->format('d M Y') }}
                                @else
                                    <span class="text-stone-400">Saat checkout</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($isPaid)
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600">Lunas</span>
                                @elseif($isPending)
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700">Menunggu</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-stone-100 text-stone-500">Belum Bayar</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-luxury.card>

    {{-- Transaksi --}}
    <x-luxury.card :padding="'p-0'" class="overflow-hidden border-stone-100">
        <x-slot:header>
            <h2 class="font-serif text-lg text-choco-900"><i class="fas fa-receipt text-gold-500 mr-2"></i>Semua Transaksi</h2>
        </x-slot:header>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-stone-100 text-[10px] font-bold uppercase tracking-widest text-stone-400">
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Bank</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($order->payments as $payment)
                        <tr class="hover:bg-stone-50/40">
                            <td class="px-6 py-4 font-bold text-choco-900">
                                {{ $payment->type_label }}
                                @if($payment->installment_number)
                                    <span class="text-[10px] text-stone-400">#{{ $payment->installment_number }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-stone-600">{{ $payment->bank?->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($payment->verification_status === 'verified')
                                    <span class="text-[10px] font-bold text-emerald-600">Terverifikasi</span>
                                @elseif($payment->verification_status === 'pending')
                                    <span class="text-[10px] font-bold text-amber-600">Menunggu</span>
                                @else
                                    <span class="text-[10px] font-bold text-rose-600">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-xs text-stone-400">{{ $payment->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-stone-400 italic">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-luxury.card>
</div>
@endsection
