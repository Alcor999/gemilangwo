@extends('layouts.app')

@section('title', 'Manajemen Ulasan - Administrator')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em]">Moderasi & Ulasan</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Manajemen <span class="not-italic text-stone-300">Ulasan</span></h1>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Total Ulasan</p>
            <p class="text-2xl font-serif text-choco-900">{{ $reviews->total() }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Menunggu Persetujuan</p>
            <p class="text-2xl font-serif text-amber-600">{{ \App\Models\Review::where('is_approved', false)->count() }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Disetujui</p>
            <p class="text-2xl font-serif text-emerald-600">{{ \App\Models\Review::where('is_approved', true)->count() }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Unggulan</p>
            <p class="text-2xl font-serif text-gold-500">{{ \App\Models\Review::where('is_featured', true)->count() }}</p>
        </div>
    </div>

    <!-- Filter Tab -->
    @php
        $currentFilter = request('filter');
    @endphp
    <div class="flex flex-wrap gap-2">
        <a class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ empty($currentFilter) ? 'bg-choco-900 text-gold-400 shadow-md' : 'bg-white text-stone-500 border border-stone-100 hover:bg-stone-50' }}" 
           href="{{ route('admin.reviews.index') }}">
            Semua
        </a>
        <a class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ $currentFilter === 'pending' ? 'bg-choco-900 text-gold-400 shadow-md' : 'bg-white text-stone-500 border border-stone-100 hover:bg-stone-50' }}" 
           href="{{ route('admin.reviews.index') }}?filter=pending">
            Menunggu ({{ \App\Models\Review::where('is_approved', false)->count() }})
        </a>
        <a class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ $currentFilter === 'approved' ? 'bg-choco-900 text-gold-400 shadow-md' : 'bg-white text-stone-500 border border-stone-100 hover:bg-stone-50' }}" 
           href="{{ route('admin.reviews.index') }}?filter=approved">
            Disetujui ({{ \App\Models\Review::where('is_approved', true)->count() }})
        </a>
        <a class="px-4 py-2 rounded-full text-xs font-bold transition-all {{ $currentFilter === 'featured' ? 'bg-choco-900 text-gold-400 shadow-md' : 'bg-white text-stone-500 border border-stone-100 hover:bg-stone-50' }}" 
           href="{{ route('admin.reviews.index') }}?filter=featured">
            Unggulan ({{ \App\Models\Review::where('is_featured', true)->count() }})
        </a>
    </div>

    <!-- Table Section -->
    <x-luxury.card class="overflow-hidden border-stone-100/50 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/50 border-b border-stone-100">
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Pelanggan</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Paket</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Rating</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Judul & Isi Ulasan</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-center">Unggulan</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($reviews as $review)
                        <tr class="group hover:bg-stone-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=E5DCD3&color=5C4033&size=64" 
                                         alt="{{ $review->user->name }}" class="h-10 w-10 rounded-full object-cover border border-stone-100">
                                    <div>
                                        <p class="text-xs font-bold text-choco-900">{{ $review->user->name }}</p>
                                        <p class="text-[10px] text-stone-400">{{ $review->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <a href="{{ route('admin.packages.show', $review->package) }}" class="text-xs font-bold text-gold-600 hover:text-gold-700 hover:underline">
                                    {{ $review->package->name }}
                                </a>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-0.5 text-[10px]">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-gold-400' : 'text-stone-200' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-[9px] font-bold text-stone-400">{{ $review->rating }}/5</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 max-w-xs">
                                <p class="text-xs font-bold text-choco-900 truncate">{{ $review->title }}</p>
                                <p class="text-[10px] text-stone-400 font-light truncate mt-0.5">{{ $review->content }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-wrap justify-center gap-1">
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest {{ $review->is_approved ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-amber-50 text-amber-600 border border-amber-100' }}">
                                        {{ $review->is_approved ? 'Disetujui' : 'Menunggu' }}
                                    </span>
                                    @if($review->is_verified)
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                                            Terverifikasi
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center">
                                    @if($review->is_featured)
                                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest bg-gold-50 text-gold-600 border border-gold-100 flex items-center gap-1">
                                            <i class="fas fa-star text-[8px]"></i> Utama
                                        </span>
                                    @else
                                        <span class="text-stone-300 text-xs">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-end items-center gap-2 text-stone-400">
                                    <a href="{{ route('admin.reviews.show', $review) }}" 
                                       class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-transparent hover:border-gold-100" title="Lihat Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    
                                    @if(!$review->is_approved)
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-emerald-50 hover:text-emerald-600 transition-all border border-transparent hover:border-emerald-100" title="Setujui">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="button" class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100" title="Tolak"
                                                data-confirm="Apakah Anda yakin ingin menolak ulasan ini?"
                                                data-confirm-title="Tolak Ulasan"
                                                data-confirm-btn="Ya, Tolak"
                                                data-confirm-danger="1">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.reviews.feature', $review) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="h-9 w-9 flex items-center justify-center rounded-xl transition-all border border-transparent {{ $review->is_featured ? 'bg-gold-50 text-gold-600 border-gold-100 hover:bg-gold-100' : 'hover:bg-gold-50 hover:text-gold-600 hover:border-gold-100' }}" 
                                                    title="{{ $review->is_featured ? 'Batalkan Unggulan' : 'Jadikan Unggulan' }}">
                                                <i class="fas fa-star text-xs"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100" title="Hapus"
                                                data-confirm="Apakah Anda yakin ingin menghapus ulasan ini secara permanen?"
                                                data-confirm-title="Hapus Ulasan"
                                                data-confirm-btn="Ya, Hapus"
                                                data-confirm-danger="1">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-stone-400 italic">
                                Belum ada ulasan yang sesuai dengan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-luxury.card>

    @if($reviews->hasPages())
        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
