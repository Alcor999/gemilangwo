@extends('layouts.app')

@section('title', 'Pembayaran Terverifikasi - Admin')

@section('content')
<div class="space-y-8 pb-12">
    <div>
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Treasury Control</p>
        <h1 class="font-serif text-4xl text-choco-900 italic">Pembayaran <span class="not-italic text-stone-300">Terverifikasi</span></h1>
    </div>

    @include('admin.payments._nav', ['activeTab' => 'verified'])

    @if($payments->isEmpty())
        <x-luxury.card class="p-16 text-center border-stone-100">
            <div class="h-16 w-16 bg-stone-50 rounded-3xl mx-auto flex items-center justify-center text-stone-200 mb-4">
                <i class="fas fa-receipt text-2xl"></i>
            </div>
            <p class="font-serif text-lg text-stone-400 italic">Belum ada pembayaran terverifikasi.</p>
        </x-luxury.card>
    @else
        <x-luxury.card :padding="'p-0'" class="overflow-hidden border-stone-100/50 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-stone-50/50 border-b border-stone-100">
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Pesanan</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Pelanggan</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Tipe</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Jumlah</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Diverifikasi Oleh</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Tanggal</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-stone-50/40 transition-colors">
                                <td class="px-6 py-5">
                                    <p class="text-[10px] font-bold text-gold-600 uppercase tracking-widest">#{{ $payment->order->order_number }}</p>
                                    <p class="text-xs text-stone-500">{{ $payment->order->package->name }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-choco-900">{{ $payment->order->user->name }}</p>
                                    <p class="text-[10px] text-stone-400">{{ $payment->order->user->email }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-2 py-1 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">{{ $payment->type_label }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-choco-900 whitespace-nowrap">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-5 text-xs text-stone-600">{{ $payment->verifiedBy?->name ?? '—' }}</td>
                                <td class="px-6 py-5">
                                    <p class="text-xs text-stone-600">{{ $payment->paid_at?->format('d M Y') ?? '—' }}</p>
                                    @if($payment->paid_at)
                                        <p class="text-[10px] text-stone-400">{{ $payment->paid_at->format('H:i') }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs text-stone-500 max-w-[160px] truncate" title="{{ $payment->verification_notes }}">
                                        {{ $payment->verification_notes ? Str::limit($payment->verification_notes, 40) : '—' }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-luxury.card>
    @endif
</div>
@endsection
