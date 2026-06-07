@extends('layouts.app')

@section('title', 'Edit Skema Pembayaran')

@section('content')
<div class="space-y-8 pb-12 max-w-3xl mx-auto">
    <div>
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Payment Configuration</p>
        <h1 class="font-serif text-3xl text-choco-900">Edit <span class="italic text-stone-400">{{ $scheme->name }}</span></h1>
    </div>

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 text-rose-600 text-sm border border-rose-100">{{ session('error') }}</div>
    @endif

    <x-luxury.card class="p-8 border-stone-100">
        <form action="{{ auth()->user()->isOwner() ? route('owner.payment-schemes.update', $scheme) : route('admin.payment-schemes.update', $scheme) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.payment-schemes._form', ['scheme' => $scheme])
            <div class="flex gap-3 pt-4 border-t border-stone-100">
                <x-luxury.button type="submit" variant="primary" size="sm">Perbarui Skema</x-luxury.button>
                <x-luxury.button href="{{ auth()->user()->isOwner() ? route('owner.payment-schemes.index') : route('admin.payment-schemes.index') }}" variant="ghost" size="sm">Batal</x-luxury.button>
            </div>
        </form>

        @if(!in_array($scheme->code, ['full_payment', 'dp_30', 'dp_50', 'installment_3x']))
            <form action="{{ auth()->user()->isOwner() ? route('owner.payment-schemes.destroy', $scheme) : route('admin.payment-schemes.destroy', $scheme) }}" method="POST" class="mt-6 pt-6 border-t border-stone-100" onsubmit="return confirm('Hapus skema ini?')">
                @csrf
                @method('DELETE')
                <x-luxury.button type="submit" variant="ghost" size="sm" class="!text-rose-500 hover:!bg-rose-50">Hapus Skema</x-luxury.button>
            </form>
        @endif
    </x-luxury.card>
</div>
@endsection
