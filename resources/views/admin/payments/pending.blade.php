@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Admin')

@section('content')
<div class="space-y-8 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Treasury Control</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Verifikasi <span class="not-italic text-stone-300">Pembayaran</span></h1>
        </div>
    </div>

    @include('admin.payments._nav', ['activeTab' => 'pending'])

    {{-- Filter tipe --}}
    <div class="flex flex-wrap gap-2">
        @foreach(['all' => 'Semua', 'dp' => 'DP', 'remaining' => 'Pelunasan', 'installment' => 'Cicilan', 'full' => 'Lunas'] as $key => $label)
            <a href="{{ route('admin.payments.pending', ['type' => $key]) }}"
               class="px-4 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all {{ ($filterType ?? 'all') === $key ? 'bg-choco-900 text-gold-400' : 'bg-white text-stone-400 border border-stone-200 hover:border-gold-300' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-700 text-sm border border-emerald-100">{{ session('success') }}</div>
    @endif

    @if($payments->isEmpty())
        <x-luxury.card class="p-16 text-center border-stone-100">
            <div class="h-16 w-16 bg-stone-50 rounded-3xl mx-auto flex items-center justify-center text-stone-200 mb-4">
                <i class="fas fa-check-double text-2xl"></i>
            </div>
            <p class="font-serif text-lg text-stone-400 italic">Tidak ada pembayaran menunggu verifikasi.</p>
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
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Sisa Order</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Bank</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Status</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Dibuat</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @foreach($payments as $payment)
                            <tr class="group hover:bg-stone-50/40 transition-colors">
                                <td class="px-6 py-5">
                                    <p class="text-[10px] font-bold text-gold-600 uppercase tracking-widest">#{{ $payment->order->order_number }}</p>
                                    <p class="text-xs text-stone-500 mt-0.5">{{ $payment->order->package->name }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-choco-900">{{ $payment->order->user->name }}</p>
                                    <p class="text-[10px] text-stone-400 truncate max-w-[140px]">{{ $payment->order->user->email }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-2 py-1 rounded-full text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100">{{ $payment->type_label }}</span>
                                    @if($payment->installment_number)
                                        <span class="text-[10px] text-stone-400 ml-1">#{{ $payment->installment_number }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-choco-900 whitespace-nowrap">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs text-stone-500 whitespace-nowrap">Rp {{ number_format($payment->order->remaining_amount ?? 0, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs text-stone-600">{{ $payment->bank?->name ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-2 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest bg-amber-50 text-amber-700 border border-amber-100">
                                        <i class="fas fa-clock mr-0.5"></i> Menunggu
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs text-stone-600">{{ $payment->created_at->format('d M Y') }}</p>
                                    <p class="text-[10px] text-stone-400">{{ $payment->created_at->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-end">
                                        <a href="{{ route('admin.payments.verify', $payment->id) }}"
                                           class="px-4 py-2 rounded-xl bg-gold-400 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-gold-500 transition-colors shadow-sm">
                                            Verifikasi
                                        </a>
                                    </div>
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
