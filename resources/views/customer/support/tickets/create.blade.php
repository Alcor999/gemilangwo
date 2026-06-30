@extends('layouts.app')

@section('title', 'Buat Tiket Bantuan - Gemilang WO')

@section('content')
<div class="space-y-8 pb-12 max-w-3xl">
    <div>
        <a href="{{ route('customer.support.tickets.index') }}" class="inline-flex items-center text-[10px] font-bold uppercase tracking-widest text-stone-400 hover:text-gold-500 transition-colors mb-4">
            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Pusat Bantuan
        </a>
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Customer Service</p>
        <h1 class="font-serif text-4xl text-choco-900 leading-tight">Buat <span class="italic text-stone-400">Tiket Baru</span></h1>
    </div>

    <x-luxury.card>
        <form method="POST" action="{{ route('customer.support.tickets.store') }}" class="space-y-6">
            @csrf

            <x-luxury.input
                id="subject"
                name="subject"
                label="Subjek"
                type="text"
                value="{{ old('subject') }}"
                placeholder="Ringkasan singkat masalah atau pertanyaan Anda"
                required
                :error="$errors->first('subject')"
            />

            <div class="space-y-1.5">
                <label for="category" class="block text-xs font-semibold text-choco-800 uppercase tracking-wider">Kategori</label>
                <select id="category" name="category" required class="block w-full px-4 py-3 bg-white border border-stone-200 rounded-lg text-choco-900 focus:ring-2 focus:ring-gold-300 focus:border-gold-400 transition-all">
                    <option value="">Pilih kategori...</option>
                    @foreach(['general' => 'Pertanyaan Umum', 'order' => 'Masalah Pesanan', 'payment' => 'Masalah Pembayaran', 'complaint' => 'Pengaduan', 'suggestion' => 'Saran', 'other' => 'Lainnya'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('category') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('category')<p class="text-xs text-red-600 italic mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-1.5">
                <label for="priority" class="block text-xs font-semibold text-choco-800 uppercase tracking-wider">Prioritas</label>
                <select id="priority" name="priority" required class="block w-full px-4 py-3 bg-white border border-stone-200 rounded-lg text-choco-900 focus:ring-2 focus:ring-gold-300 focus:border-gold-400 transition-all">
                    @foreach(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi', 'urgent' => 'Mendesak'] as $val => $label)
                        <option value="{{ $val }}" @selected(old('priority', 'medium') === $val)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('priority')<p class="text-xs text-red-600 italic mt-1">{{ $message }}</p>@enderror
            </div>

            @php $userOrders = auth()->user()->orders()->with('package')->latest()->get(); @endphp
            @if($userOrders->isNotEmpty())
                <div class="space-y-1.5">
                    <label for="order_id" class="block text-xs font-semibold text-choco-800 uppercase tracking-wider">Pesanan Terkait <span class="text-stone-400 font-normal normal-case">(opsional)</span></label>
                    <select id="order_id" name="order_id" class="block w-full px-4 py-3 bg-white border border-stone-200 rounded-lg text-choco-900 focus:ring-2 focus:ring-gold-300 focus:border-gold-400 transition-all">
                        <option value="">Tidak terkait pesanan</option>
                        @foreach($userOrders as $order)
                            <option value="{{ $order->id }}" @selected(old('order_id') == $order->id)>
                                #{{ $order->order_number ?? $order->id }} — {{ $order->package->name }} ({{ $order->created_at->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('order_id')<p class="text-xs text-red-600 italic mt-1">{{ $message }}</p>@enderror
                </div>
            @endif

            <div class="space-y-1.5">
                <label for="description" class="block text-xs font-semibold text-choco-800 uppercase tracking-wider">Deskripsi Detail</label>
                <textarea id="description" name="description" rows="6" required placeholder="Jelaskan masalah atau pertanyaan Anda secara detail..."
                    class="block w-full px-4 py-3 bg-white border border-stone-200 rounded-lg text-choco-900 placeholder:text-stone-400 focus:ring-2 focus:ring-gold-300 focus:border-gold-400 transition-all resize-y">{{ old('description') }}</textarea>
                <p class="text-xs text-stone-500">Semakin detail penjelasan Anda, semakin cepat kami dapat membantu.</p>
                @error('description')<p class="text-xs text-red-600 italic mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <x-luxury.button type="submit" variant="primary" size="sm">Kirim Tiket</x-luxury.button>
                <x-luxury.button href="{{ route('customer.support.tickets.index') }}" variant="ghost" size="sm">Batal</x-luxury.button>
            </div>
        </form>
    </x-luxury.card>

    <x-luxury.card class="bg-stone-50/50 border-stone-100">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gold-600 mb-3">Tips</h3>
        <ul class="space-y-2 text-sm text-stone-600 list-disc list-inside">
            <li>Sertakan nomor pesanan jika masalah terkait pemesanan</li>
            <li>Jelaskan langkah yang sudah Anda coba</li>
            <li>Respon biasanya diberikan dalam 1×24 jam kerja</li>
        </ul>
    </x-luxury.card>
</div>
@endsection
