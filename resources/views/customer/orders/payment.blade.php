@extends('layouts.app')

@section('title', 'Pilih Rekening Pembayaran - Gemilang WO')

@section('content')
@php
    $schemeLabels = [
        'full_payment' => 'Lunas Penuh (100%)',
        'dp_30' => 'Uang Muka (DP 30%)',
        'dp_50' => 'Uang Muka (DP 50%)',
        'installment_3x' => 'Cicilan 3 Termin',
    ];
@endphp

<div class="space-y-8 pb-16 max-w-3xl mx-auto">
    <div>
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Payment</p>
        <h1 class="font-serif text-4xl text-choco-900 leading-tight">Pilih <span class="italic text-stone-400">Rekening Bank</span></h1>
    </div>

    <x-luxury.card :padding="'p-8'" class="border-stone-100">
        <div class="p-4 rounded-2xl bg-gold-50/50 border border-gold-100 text-sm text-choco-900 mb-6">
            <i class="fas fa-info-circle text-gold-500 mr-2"></i>
            Skema <strong>{{ $schemeLabels[$order->payment_scheme] ?? $order->scheme_label }}</strong> — pilih rekening tujuan transfer.
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6 p-5 rounded-2xl bg-stone-50 border border-stone-100 text-sm">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Nomor Pesanan</p>
                <p class="font-bold text-choco-900">#{{ $order->order_number }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Paket</p>
                <p class="font-bold text-choco-900">{{ $order->package->name }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Tahap Pembayaran</p>
                <p class="font-bold text-gold-600">{{ $next_payment['label'] }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">Batas Waktu</p>
                <p class="font-bold text-rose-600">{{ \Carbon\Carbon::parse($next_payment['due_date'])->format('d M Y') }}</p>
            </div>
        </div>

        <div class="text-center p-8 rounded-2xl bg-gradient-to-r from-gold-50 to-amber-50 border border-gold-100 mb-8">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gold-600 mb-2">Jumlah Transfer</p>
            <p class="text-4xl font-serif font-bold text-choco-900">Rp {{ number_format($next_payment['amount'], 0, ',', '.') }}</p>
        </div>

        <form action="{{ route('customer.orders.selectBank', $order->id) }}" method="POST" id="bankForm">
            @csrf
            <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-4"><i class="fas fa-university text-gold-500 mr-1"></i> Pilih Rekening Tujuan</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-6">
                @forelse($banks as $bank)
                    <div class="bank-select-card cursor-pointer rounded-2xl border-2 border-stone-200 p-4 transition-all hover:border-gold-400 hover:shadow-md"
                        data-bank-id="{{ $bank->id }}">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl bg-gold-50 flex items-center justify-center text-gold-500 text-xl">
                                <i class="fas fa-university"></i>
                            </div>
                            <div>
                                <p class="font-bold text-choco-900">{{ $bank->name }}</p>
                                <p class="text-xs text-stone-500 font-mono">{{ $bank->account_number }}</p>
                                <p class="text-[10px] text-stone-400">a/n {{ $bank->account_holder }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 p-6 rounded-2xl bg-amber-50 text-amber-800 text-sm text-center border border-amber-100">
                        Belum ada rekening bank aktif. Hubungi admin.
                    </div>
                @endforelse
            </div>

            <input type="hidden" name="bank_id" id="selected_bank_id" required>
            @error('bank_id')<p class="text-rose-500 text-xs mb-4">{{ $message }}</p>@enderror

            <x-luxury.button type="submit" variant="primary" class="w-full justify-center" id="submitPaymentBtn" disabled>
                <i class="fas fa-arrow-right mr-2"></i> Dapatkan Petunjuk Transfer
            </x-luxury.button>
        </form>
    </x-luxury.card>

    <x-luxury.card :padding="'p-6'" class="border-stone-100">
        <h2 class="font-serif text-lg text-choco-900 mb-4"><i class="fas fa-list-ol text-gold-500 mr-2"></i>Prosedur Konfirmasi</h2>
        <ol class="space-y-3 text-sm text-stone-500 list-decimal list-inside">
            <li><strong class="text-choco-900">Pilih Bank</strong> — pilih rekening lalu lanjutkan.</li>
            <li><strong class="text-choco-900">Transfer</strong> — kirim sesuai nominal yang tertera.</li>
            <li><strong class="text-choco-900">Unggah Bukti</strong> — konfirmasi di halaman pesanan.</li>
            <li><strong class="text-choco-900">Verifikasi</strong> — admin verifikasi maks. 1×24 jam kerja.</li>
        </ol>
    </x-luxury.card>

    <div class="text-center">
        <x-luxury.button href="{{ route('customer.orders.show', $order->id) }}" variant="ghost" size="sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Pesanan
        </x-luxury.button>
    </div>
</div>

<style>
.bank-select-card.active { border-color: #b8860b; background: rgba(184,134,11,0.05); box-shadow: 0 4px 12px rgba(184,134,11,0.15); }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bank-select-card');
    const input = document.getElementById('selected_bank_id');
    const btn = document.getElementById('submitPaymentBtn');
    cards.forEach(card => {
        card.addEventListener('click', function() {
            cards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            input.value = this.dataset.bankId;
            btn.removeAttribute('disabled');
        });
    });
});
</script>
@endsection
