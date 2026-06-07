@extends('layouts.app')

@section('title', $package->name . ' - Detail Paket Eksklusif')

@section('content')
<div class="space-y-12 pb-24">
    <!-- Header/Breadcrumb -->
    <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400">
        <a href="{{ route('customer.packages.index') }}" class="hover:text-gold-500 transition-colors">Koleksi Paket</a>
        <i class="fas fa-chevron-right text-[8px] opacity-30"></i>
        <span class="text-choco-900">{{ $package->name }}</span>
    </div>

    <div class="grid lg:grid-cols-12 gap-12 items-start">
        <!-- Main Content (Image & Info) -->
        <div class="lg:col-span-8 space-y-12">
            <!-- Featured Image -->
            <div class="relative rounded-[3rem] overflow-hidden shadow-2xl group border border-stone-100">
                @if($package->image)
                    <img src="{{ asset('storage/' . $package->image) }}" 
                         class="w-full h-[500px] object-cover transition-transform duration-1000 group-hover:scale-105" 
                         alt="{{ $package->name }}">
                @else
                    <div class="w-full h-[500px] bg-stone-50 flex items-center justify-center text-stone-200">
                        <i class="fas fa-ring fa-8x opacity-10"></i>
                    </div>
                @endif
                
                <!-- Overlay Card -->
                <div class="absolute bottom-10 left-10 right-10 p-10 bg-white/40 backdrop-blur-3xl rounded-[2.5rem] border border-white/20 shadow-2xl">
                    <div class="flex flex-wrap items-end justify-between gap-6">
                        <div class="space-y-2">
                            <span class="text-choco-900/60 text-[10px] font-bold uppercase tracking-[0.3em]">Signature Series</span>
                            <h1 class="font-serif text-4xl text-choco-900 leading-tight italic">{{ $package->name }}</h1>
                        </div>
                        <div class="text-right">
                            <span class="text-choco-900/40 text-[9px] font-bold uppercase tracking-widest block mb-1">Investasi Kebahagiaan</span>
                            <p class="text-3xl font-serif text-choco-900 tracking-tighter font-bold">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details Section -->
            <div class="grid md:grid-cols-2 gap-12">
                <div class="space-y-8">
                    <div class="space-y-4">
                        <h4 class="text-choco-900 font-serif text-xl italic border-b border-stone-100 pb-4">Filosofi Paket</h4>
                        <p class="text-stone-400 font-light leading-relaxed text-sm italic">
                            "Setiap paket kami dirancang untuk menceritakan kisah unik Anda. Kami menggabungkan estetika modern dengan tradisi yang elegan untuk menciptakan perayaan yang tak terlupakan."
                        </p>
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-choco-900 font-serif text-xl italic border-b border-stone-100 pb-4">Deskripsi Lengkap</h4>
                        <p class="text-stone-500 font-light leading-relaxed text-sm">
                            {{ $package->description }}
                        </p>
                    </div>
                </div>

                <div class="space-y-8">
                    <h4 class="text-choco-900 font-serif text-xl italic border-b border-stone-100 pb-4">Eksklusivitas Layanan</h4>
                    <div class="grid gap-4">
                        @foreach(is_array($package->features) ? $package->features : json_decode($package->features, true) ?? [] as $feature)
                            <div class="flex items-start gap-4 p-5 rounded-2xl bg-white border border-stone-50 group hover:border-gold-200 transition-all">
                                <div class="h-8 w-8 shrink-0 rounded-full bg-stone-50 flex items-center justify-center text-gold-400 group-hover:bg-gold-50 transition-colors">
                                    <i class="fas fa-check text-[10px]"></i>
                                </div>
                                <span class="text-xs text-stone-500 font-light leading-snug">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar (Action) -->
        <div class="lg:col-span-4 space-y-8">
            <x-luxury.card class="p-10 space-y-10 border-gold-200 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gold-50 rounded-full blur-3xl -mr-10 -mt-10 opacity-50"></div>
                
                <div class="space-y-4 relative z-10">
                    <h5 class="font-serif text-2xl text-choco-900 italic">Mulai Rencanakan</h5>
                    <p class="text-stone-400 text-xs font-light leading-relaxed">Pesan slot Anda sekarang untuk konsultasi desain eksklusif dengan tim kurator kami.</p>
                </div>

                <div class="space-y-4 py-8 border-y border-stone-50 relative z-10">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-stone-400 font-light uppercase tracking-widest text-[9px]">Kapasitas</span>
                        <span class="text-choco-900 font-bold uppercase tracking-widest text-[9px]">Hingga {{ $package->max_guests }} Tamu</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-stone-400 font-light uppercase tracking-widest text-[9px]">Ketersediaan</span>
                        <span class="text-emerald-500 font-bold uppercase tracking-widest text-[9px]">Tersedia Sesi</span>
                    </div>
                </div>

                <div class="space-y-4 relative z-10">
                    <a href="{{ route('customer.orders.create', ['package_id' => $package->id]) }}" 
                       class="w-full flex items-center justify-center py-5 rounded-2xl bg-choco-900 text-gold-400 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-800 transition-all shadow-xl shadow-choco-900/20 active:scale-95">
                       Pesan Paket Ini
                    </a>
                    <a href="{{ route('customer.packages.index') }}" 
                       class="w-full flex items-center justify-center py-5 rounded-2xl border border-stone-100 text-stone-400 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-50 transition-all">
                       Kembali ke Koleksi
                    </a>
                </div>
            </x-luxury.card>

            <div class="p-10 rounded-[2.5rem] bg-stone-900 text-white space-y-8 shadow-2xl">
                <div class="space-y-2">
                    <h6 class="text-gold-400 text-[10px] font-bold uppercase tracking-[0.3em]">Butuh Bantuan?</h6>
                    <p class="text-white/60 font-light text-xs italic leading-relaxed">"Konsultan kami siap mendampingi Anda dalam memilih paket yang paling tepat."</p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center gap-4 group cursor-pointer">
                        <div class="h-10 w-10 rounded-xl bg-white/5 flex items-center justify-center text-gold-400 transition-colors group-hover:bg-gold-400 group-hover:text-white">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-widest group-hover:text-gold-400 transition-colors">WhatsApp Consultant</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
