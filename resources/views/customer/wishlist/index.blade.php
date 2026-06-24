@extends('layouts.app')

@section('title', 'Favorit Saya - Gemilang WO')

@section('content')
<div class="space-y-12 pb-20">
    <!-- Header Section -->
    <div class="relative py-16 px-8 rounded-[3rem] overflow-hidden text-center">
        <div class="absolute inset-0 bg-stone-900">
            <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 class="w-full h-full object-cover opacity-20 grayscale-[30%]" alt="">
            <div class="absolute inset-0 bg-gradient-to-b from-choco-900/40 via-transparent to-stone-50"></div>
        </div>
        
        <div class="relative z-10 space-y-6 max-w-3xl mx-auto">
            <p class="text-gold-400 text-xs font-bold uppercase tracking-[0.5em]">Favorit Anda</p>
            <h1 class="font-serif text-5xl md:text-6xl text-white italic tracking-tight">Daftar <span class="not-italic">Keinginan</span> Saya</h1>
            <p class="text-white/60 text-lg font-light leading-relaxed px-4">
                Paket pernikahan impian yang telah Anda simpan. Persiapkan momen istimewa Anda dengan pilihan terbaik.
            </p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-6 rounded-2xl bg-emerald-50 text-emerald-600 text-sm border border-emerald-100 flex items-center gap-3">
            <i class="fas fa-check-circle text-lg"></i> 
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Wishlist Grid -->
    @if($wishlists->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 px-4">
            @foreach($wishlists as $wishlist)
                <div class="group relative bg-white rounded-[2.5rem] border border-stone-100 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Image Wrapper -->
                        <div class="relative h-80 overflow-hidden">
                            @if($wishlist->package->image)
                                <img src="{{ asset('storage/' . $wishlist->package->image) }}" 
                                     class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" 
                                     alt="{{ $wishlist->package->name }}">
                            @else
                                <div class="w-full h-full bg-stone-50 flex items-center justify-center text-stone-200">
                                    <img src="{{ asset('images/logo.png') }}" class="h-16 w-16 object-contain opacity-20" alt="">
                                </div>
                            @endif
                            
                            <!-- Delete Button -->
                            <form action="{{ route('customer.wishlist.remove', $wishlist) }}" method="POST" class="absolute top-6 right-6 z-10">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        class="h-10 w-10 flex items-center justify-center rounded-full bg-white/90 backdrop-blur-md text-rose-500 border border-stone-100 hover:bg-rose-500 hover:text-white hover:scale-105 active:scale-95 transition-all shadow-md"
                                        data-confirm="Hapus &quot;{{ $wishlist->package->name }}&quot; dari daftar keinginan?"
                                        data-confirm-title="Hapus dari Daftar Keinginan"
                                        data-confirm-btn="Ya, Hapus"
                                        data-confirm-danger="1" title="Hapus dari Favorit">
                                    <i class="fas fa-heart-broken text-xs"></i>
                                </button>
                            </form>
                            
                            <!-- Overlay on Hover -->
                            <div class="absolute inset-0 bg-choco-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>

                        <!-- Content -->
                        <div class="p-10 space-y-6">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between gap-4">
                                    <h3 class="font-serif text-2xl text-choco-900 font-bold group-hover:text-gold-600 transition-colors">{{ $wishlist->package->name }}</h3>
                                    @if($wishlist->package->getTotalReviews() > 0)
                                        <div class="flex items-center gap-1 shrink-0">
                                            <i class="fas fa-star text-gold-400 text-[10px]"></i>
                                            <span class="text-[11px] font-bold text-choco-900">{{ number_format($wishlist->package->getAverageRating(), 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <p class="text-stone-400 text-sm font-light leading-relaxed line-clamp-3 italic">
                                    "{{ $wishlist->package->description }}"
                                </p>
                            </div>

                            <!-- Meta info -->
                            <div class="py-4 border-y border-stone-50 flex flex-wrap gap-4 text-[10px] text-stone-400 font-medium uppercase tracking-wider justify-between">
                                <span>Ditambahkan: {{ $wishlist->created_at->format('d M Y') }}</span>
                                @if($wishlist->package->max_guests)
                                    <span>Kapasitas: {{ $wishlist->package->max_guests }} Tamu</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Footer/Actions -->
                    <div class="px-10 pb-10 space-y-6">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-stone-300 text-[9px] font-bold uppercase tracking-[0.2em] mb-1">Mulai Dari</p>
                                <p class="text-2xl font-serif text-choco-900 font-bold tracking-tight">
                                    Rp {{ number_format($wishlist->package->getDiscountedPrice(), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('customer.packages.show', $wishlist->package) }}" 
                               class="flex items-center justify-center py-4 rounded-xl border border-stone-100 text-stone-400 text-[10px] font-bold uppercase tracking-widest hover:bg-choco-900 hover:text-gold-400 hover:border-choco-900 transition-all">
                                <i class="fas fa-eye mr-2 text-[11px]"></i> Detail
                            </a>
                            <a href="{{ route('customer.orders.create', ['package_id' => $wishlist->package->id]) }}" 
                               class="flex items-center justify-center py-4 rounded-xl bg-gold-400 text-white text-[10px] font-bold uppercase tracking-widest hover:bg-gold-300 transition-all shadow-lg shadow-gold-400/25">
                                <i class="fas fa-shopping-cart mr-2 text-[11px]"></i> Pesan
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginasi -->
        @if($wishlists->hasPages())
            <div class="mt-8 px-4">
                {{ $wishlists->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="max-w-md mx-auto text-center py-16 px-8 rounded-[2.5rem] bg-white border border-stone-100 shadow-sm space-y-6">
            <div class="h-16 w-16 mx-auto rounded-full bg-stone-50 flex items-center justify-center text-stone-300">
                <i class="fas fa-heart text-2xl"></i>
            </div>
            <div class="space-y-2">
                <h3 class="font-serif text-xl text-choco-900 font-bold">Daftar Keinginan Kosong</h3>
                <p class="text-stone-400 text-sm font-light">
                    Jelajahi berbagai paket eksklusif kami dan simpan yang paling Anda sukai di sini.
                </p>
            </div>
            <a href="{{ route('customer.packages.index') }}" 
               class="inline-block px-8 py-4 bg-choco-900 text-gold-400 text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-stone-800 transition-all shadow-lg">
                Jelajahi Paket
            </a>
        </div>
    @endif
</div>
@endsection
