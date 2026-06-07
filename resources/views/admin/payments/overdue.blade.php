@extends('layouts.app')

@section('title', 'Pembayaran Overdue - Admin')

@section('content')
<div class="space-y-8 pb-12">
    <div>
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Treasury Control</p>
        <h1 class="font-serif text-4xl text-choco-900 italic">Pembayaran <span class="not-italic text-rose-400">Overdue</span></h1>
    </div>

    @include('admin.payments._nav', ['activeTab' => 'overdue'])

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-700 text-sm border border-emerald-100">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 text-rose-600 text-sm border border-rose-100">{{ session('error') }}</div>
    @endif

    @if($payments->isEmpty())
        <x-luxury.card class="p-16 text-center border-emerald-100 bg-emerald-50/30">
            <div class="h-16 w-16 bg-emerald-50 rounded-3xl mx-auto flex items-center justify-center text-emerald-300 mb-4">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
            <p class="font-serif text-lg text-emerald-700 italic">Tidak ada pembayaran lewat jatuh tempo.</p>
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
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Deadline</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Sisa Order</th>
                            <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-rose-50/20 transition-colors">
                                <td class="px-6 py-5">
                                    <p class="text-[10px] font-bold text-gold-600 uppercase tracking-widest">#{{ $payment->order->order_number }}</p>
                                    <p class="text-xs text-stone-500">{{ $payment->order->package->name }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-choco-900">{{ $payment->order->user->name }}</p>
                                    <p class="text-[10px] text-stone-400">{{ $payment->order->user->phone ?? '—' }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-2 py-1 rounded-full text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-100">{{ $payment->type_label }}</span>
                                    @if($payment->installment_number)
                                        <span class="text-[10px] text-stone-400">#{{ $payment->installment_number }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-choco-900 whitespace-nowrap">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs font-bold text-rose-600">{{ $payment->due_date->format('d M Y') }}</p>
                                    <p class="text-[10px] text-rose-400">{{ $payment->due_date->diffForHumans() }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-xs text-stone-600 whitespace-nowrap">Rp {{ number_format($payment->order->remaining_amount, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-end gap-2 flex-wrap">
                                        <a href="{{ route('admin.orders.show', $payment->order_id) }}"
                                           class="px-3 py-1.5 rounded-lg border border-stone-200 text-[10px] font-bold uppercase tracking-widest text-choco-900 hover:bg-stone-50 transition-colors">
                                            Detail
                                        </a>
                                        <form action="{{ route('admin.payments.sendReminder', $payment->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-lg bg-[#25d366] text-white text-[10px] font-bold uppercase tracking-widest hover:bg-[#1ebd56] transition-colors">
                                                <i class="fab fa-whatsapp mr-1"></i> Reminder
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-luxury.card>

        <div class="text-right text-sm text-stone-500">
            Total overdue: <strong class="text-rose-600">Rp {{ number_format($payments->sum('amount'), 0, ',', '.') }}</strong>
            <span class="text-stone-400">({{ $payments->count() }} transaksi)</span>
        </div>
    @endif
</div>
@endsection
