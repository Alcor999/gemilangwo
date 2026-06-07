@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran - Admin')

@section('content')
@php
    $inputClass = 'w-full px-4 py-3 rounded-xl border border-stone-200 bg-white text-choco-900 text-sm focus:border-gold-400 focus:ring-2 focus:ring-gold-100 outline-none transition-all resize-none';
    $labelClass = 'block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2';
@endphp

<div class="space-y-8 pb-16 max-w-4xl mx-auto" x-data="{ showReject: false }">
    <div>
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Payment Review</p>
        <h1 class="font-serif text-4xl text-choco-900 italic">Verifikasi <span class="not-italic text-stone-300">Pembayaran</span></h1>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-700 text-sm border border-emerald-100">{{ session('success') }}</div>
    @endif

    <x-luxury.card :padding="'p-8'" class="border-stone-100">
        {{-- Info pesanan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 pb-8 border-b border-stone-100">
            <div>
                <p class="{{ $labelClass }}">Nomor Pesanan</p>
                <p class="font-serif text-2xl text-choco-900">#{{ $payment->order->order_number }}</p>
            </div>
            <div>
                <p class="{{ $labelClass }}">Pelanggan</p>
                <p class="font-bold text-choco-900">{{ $payment->order->user->name }}</p>
                <p class="text-sm text-stone-400">{{ $payment->order->user->email }}</p>
            </div>
        </div>

        {{-- Detail pembayaran --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="p-5 rounded-2xl bg-stone-50 border border-stone-100">
                <p class="{{ $labelClass }}">Tipe Pembayaran</p>
                <p class="font-bold text-choco-900">{{ $payment->type_label }}
                    @if($payment->installment_number)<span class="text-stone-400">#{{ $payment->installment_number }}</span>@endif
                </p>
                <p class="text-[10px] text-stone-400 mt-1">Skema: {{ $payment->order->scheme_label }}</p>
            </div>
            <div class="p-5 rounded-2xl bg-stone-50 border border-stone-100">
                <p class="{{ $labelClass }}">Bank</p>
                <p class="font-bold text-choco-900">{{ $payment->bank?->name ?? '—' }}</p>
                <p class="text-xs text-stone-400 font-mono">{{ $payment->bank?->account_number ?? '' }}</p>
            </div>
            <div class="p-5 rounded-2xl bg-gradient-to-br from-gold-50 to-amber-50 border border-gold-100">
                <p class="{{ $labelClass }}">Jumlah / Sisa</p>
                <p class="text-xl font-serif font-bold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                <p class="text-xs text-rose-500 mt-1">Sisa: Rp {{ number_format($payment->order->remaining_amount, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($payment->payment_proof_path)
            <div class="mb-8">
                <p class="{{ $labelClass }}">Bukti Transfer</p>
                <a href="{{ asset('storage/'.$payment->payment_proof_path) }}" target="_blank" class="inline-block">
                    <img src="{{ asset('storage/'.$payment->payment_proof_path) }}" alt="Bukti transfer"
                         class="max-h-72 rounded-2xl border border-stone-200 shadow-sm hover:shadow-md transition-shadow">
                </a>
            </div>
        @else
            <div class="mb-8 p-4 rounded-2xl bg-amber-50 border border-amber-100 text-sm text-amber-800">
                <i class="fas fa-exclamation-circle mr-1"></i> Pelanggan belum mengunggah bukti transfer.
            </div>
        @endif

        <div class="mb-8 pb-8 border-b border-stone-100">
            <p class="{{ $labelClass }}">Metode</p>
            <p class="text-sm font-bold text-choco-900">Transfer Bank Manual</p>
        </div>

        {{-- Form verifikasi --}}
        <h2 class="font-serif text-lg text-choco-900 mb-5">Keputusan Verifikasi</h2>

        <form action="{{ route('admin.payments.approve', $payment->id) }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="{{ $labelClass }}">Catatan Verifikasi</label>
                <textarea name="notes" rows="3" placeholder="Contoh: Transfer sesuai mutasi rekening..."
                    class="{{ $inputClass }}"></textarea>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <x-luxury.button type="submit" variant="primary" class="flex-1 justify-center">
                    <i class="fas fa-check mr-2"></i> Setujui Pembayaran
                </x-luxury.button>
                <button type="button" @click="showReject = true"
                    class="flex-1 inline-flex items-center justify-center px-6 py-3.5 rounded-2xl border border-rose-200 text-rose-600 text-[11px] font-bold uppercase tracking-[0.2em] hover:bg-rose-50 transition-colors">
                    <i class="fas fa-times mr-2"></i> Tolak Pembayaran
                </button>
            </div>
        </form>

        <div class="mt-4">
            <x-luxury.button href="{{ route('admin.payments.pending') }}" variant="ghost" class="w-full justify-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Menunggu
            </x-luxury.button>
        </div>
    </x-luxury.card>

    {{-- Modal Tolak --}}
    <div x-show="showReject" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-stone-900/60 backdrop-blur-sm" @click="showReject = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl border border-stone-100 w-full max-w-md overflow-hidden" @click.stop>
            <div class="px-6 py-5 border-b border-stone-100 bg-rose-50/50">
                <h3 class="font-serif text-lg text-choco-900">
                    <i class="fas fa-exclamation-triangle text-rose-500 mr-2"></i>Tolak Pembayaran
                </h3>
            </div>
            <form action="{{ route('admin.payments.reject', $payment->id) }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="{{ $labelClass }}">Alasan Penolakan *</label>
                    <textarea name="reason" rows="4" required placeholder="Mengapa pembayaran ini ditolak?"
                        class="{{ $inputClass }}"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="showReject = false"
                        class="flex-1 py-3 rounded-2xl border border-stone-200 text-[10px] font-bold uppercase tracking-widest text-stone-500 hover:bg-stone-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 rounded-2xl bg-rose-500 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-rose-600 transition-colors">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
