@extends('layouts.app')

@section('title', 'Koleksi Paket Eksklusif - Gemilang WO')

@section('content')
<div class="space-y-12 pb-20">
    <!-- Header Section -->
    <div class="relative py-16 px-8 rounded-[3rem] overflow-hidden text-center">
        <div class="absolute inset-0 bg-stone-900">
            <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 class="w-full h-full object-cover opacity-30 grayscale-[30%]" alt="">
            <div class="absolute inset-0 bg-gradient-to-b from-choco-900/40 via-transparent to-stone-50"></div>
        </div>
        
        <div class="relative z-10 space-y-6 max-w-3xl mx-auto">
            <p class="text-gold-400 text-xs font-bold uppercase tracking-[0.5em] animate-fade-in-up">The Curation</p>
            <h1 class="font-serif text-5xl md:text-6xl text-white italic tracking-tight">Koleksi <span class="not-italic">Pernikahan</span> Impian</h1>
            <p class="text-white/60 text-lg font-light leading-relaxed px-4">
                Pilih paket yang mencerminkan esensi cinta Anda. Setiap detil telah dikurasi untuk menciptakan kemewahan yang tak terlupakan.
            </p>
            
            <div class="pt-8 flex flex-wrap justify-center gap-4">
                <div class="px-6 py-2 bg-white/5 backdrop-blur-md rounded-full border border-white/10 text-white/80 text-[10px] font-bold uppercase tracking-widest">
                    Semua Paket
                </div>
                <div class="px-6 py-2 hover:bg-white/10 transition-colors cursor-pointer rounded-full border border-white/5 text-white/40 text-[10px] font-bold uppercase tracking-widest">
                    Platinum Series
                </div>
                <div class="px-6 py-2 hover:bg-white/10 transition-colors cursor-pointer rounded-full border border-white/5 text-white/40 text-[10px] font-bold uppercase tracking-widest">
                    Intimate Royal
                </div>
            </div>
        </div>
    </div>

    <!-- Packages Grid -->
    @if($packages->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 px-4">
            @foreach($packages as $package)
                <div class="group relative bg-white rounded-[2.5rem] border border-stone-100 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                    <!-- Image Wrapper -->
                    <div class="relative h-80 overflow-hidden">
                        @if($package->image)
                            <img src="{{ asset('storage/' . $package->image) }}" 
                                 class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" 
                                 alt="{{ $package->name }}">
                        @else
                            <div class="w-full h-full bg-stone-50 flex items-center justify-center text-stone-200">
                                <i class="fas fa-ring fa-4x opacity-20"></i>
                            </div>
                        @endif
                        
                        <!-- Top Badges -->
                        <div class="absolute top-6 left-6 flex flex-col gap-2">
                            <span class="px-4 py-1.5 bg-black/40 backdrop-blur-md text-[10px] font-bold text-white uppercase tracking-widest rounded-full border border-white/10">
                                Featured
                            </span>
                        </div>
                        
                        <!-- Overlay on Hover -->
                        <div class="absolute inset-0 bg-choco-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>

                    <!-- Content -->
                    <div class="p-10 space-y-8 flex flex-col h-[calc(100%-20rem)]">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="font-serif text-2xl text-choco-900 font-bold group-hover:text-gold-600 transition-colors">{{ $package->name }}</h3>
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-star text-gold-400 text-[10px]"></i>
                                    <span class="text-[11px] font-bold text-choco-900">4.9</span>
                                </div>
                            </div>
                            
                            <p class="text-stone-400 text-sm font-light leading-relaxed line-clamp-2 italic">
                                "{{ Str::limit($package->description, 100) }}"
                            </p>
                        </div>

                        <!-- Highlights -->
                        <div class="flex items-center gap-6 py-4 border-y border-stone-50">
                            @if($package->max_guests)
                                <div class="flex flex-col gap-1">
                                    <span class="text-[9px] font-bold text-stone-300 uppercase tracking-widest">Capacity</span>
                                    <span class="text-xs font-bold text-choco-800 tracking-wide">{{ $package->max_guests }} Guests</span>
                                </div>
                            @endif
                            <div class="flex flex-col gap-1">
                                <span class="text-[9px] font-bold text-stone-300 uppercase tracking-widest">Category</span>
                                <span class="text-xs font-bold text-choco-800 tracking-wide">Signature</span>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="mt-auto pt-6 flex items-center justify-between">
                            <div>
                                <p class="text-stone-300 text-[9px] font-bold uppercase tracking-[0.2em] mb-1">Starting From</p>
                                <p class="text-2xl font-serif text-choco-900 font-bold tracking-tight">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('customer.packages.show', $package->id) }}" 
                                   class="h-12 w-12 flex items-center justify-center rounded-2xl bg-stone-50 text-stone-400 hover:bg-stone-100 hover:text-choco-900 transition-all border border-transparent hover:border-stone-200">
                                    <i class="fas fa-arrow-right -rotate-45"></i>
                                </a>
                                <a href="{{ route('customer.orders.create', ['package_id' => $package->id]) }}" 
                                   class="h-12 px-6 flex items-center justify-center rounded-2xl bg-choco-900 text-gold-400 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-800 transition-all shadow-lg shadow-choco-900/20 active:scale-95">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="p-20 text-center space-y-6">
            <div class="h-20 w-20 mx-auto rounded-full bg-stone-50 flex items-center justify-center text-stone-300 border border-stone-100">
                <i class="fas fa-search fa-2x opacity-20"></i>
            </div>
            <h2 class="font-serif text-2xl text-choco-900">Belum Ada Paket Tersedia</h2>
            <p class="text-stone-400 font-light italic">Tim kurator kami sedang mempersiapkan koleksi eksklusif untuk Anda.</p>
        </div>
    @endif
</div>
@endsection
