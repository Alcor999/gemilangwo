@extends('layouts.app')

@section('title', 'Ulasan Saya - Gemilang WO')
@section('header_title', 'Ulasan Saya')

@section('content')
<div class="space-y-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif font-bold text-choco-900 italic">Ulasan Saya</h1>
            <p class="mt-1 text-sm text-stone-500">Pendapat Anda sangat berharga untuk kami dan calon pelanggan lainnya</p>
        </div>
        <a href="{{ route('customer.orders.index', ['from' => 'review']) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-white text-sm font-bold shadow-lg shadow-gold-500/20 hover:from-gold-600 hover:to-gold-700 transition-all active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tulis Ulasan Baru
        </a>
    </div>

    @if($reviews->count() > 0)
        {{-- Stats Row --}}
        @php
            $avgRating = round($reviews->avg('rating'), 1);
            $approvedCount = $reviews->where('is_approved', true)->count();
            $pendingCount = $reviews->whereNull('is_approved')->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-gold-50 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-choco-900">{{ $avgRating }}</p>
                    <p class="text-xs text-stone-500 font-medium uppercase tracking-wider">Rata-rata Rating</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-choco-900">{{ $approvedCount }}</p>
                    <p class="text-xs text-stone-500 font-medium uppercase tracking-wider">Disetujui</p>
                </div>
            </div>
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-choco-900">{{ $pendingCount }}</p>
                    <p class="text-xs text-stone-500 font-medium uppercase tracking-wider">Menunggu Tinjauan</p>
                </div>
            </div>
        </div>

        {{-- Reviews Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($reviews as $review)
                <div class="bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-md transition-all overflow-hidden group">
                    {{-- Package Header --}}
                    <div class="bg-gradient-to-r from-choco-800 to-choco-900 px-6 py-4 flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gold-400/80 mb-1">Paket Pernikahan</p>
                            <h3 class="text-base font-serif font-bold text-white truncate">{{ $review->package->name }}</h3>
                        </div>
                        <div class="shrink-0 ml-4">
                            @if($review->is_approved === true)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-500/20 text-green-400 text-[10px] font-bold uppercase tracking-wider">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Disetujui
                                </span>
                            @elseif($review->is_approved === false)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/20 text-amber-400 text-[10px] font-bold uppercase tracking-wider">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Menunggu
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Rating Stars --}}
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-5 w-5 {{ $i <= $review->rating ? 'text-gold-400' : 'text-stone-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="text-sm font-bold text-choco-800">{{ $review->rating }}/5</span>
                            @if($review->is_featured)
                                <span class="ml-auto inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gold-50 text-gold-600 text-[10px] font-bold border border-gold-200">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    Unggulan
                                </span>
                            @endif
                        </div>

                        {{-- Review Title --}}
                        <h4 class="text-base font-bold text-choco-900 mb-2">{{ $review->title }}</h4>

                        {{-- Review Content --}}
                        <p class="text-sm text-stone-600 leading-relaxed mb-4">{{ Str::limit($review->content, 150) }}</p>

                        {{-- Footer --}}
                        <div class="flex items-center justify-between pt-4 border-t border-stone-100">
                            <div class="flex items-center gap-4">
                                <span class="text-xs text-stone-400">{{ $review->created_at->translatedFormat('d F Y') }}</span>
                                <span class="flex items-center gap-1 text-xs text-stone-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                                    {{ $review->helpful_count }} membantu
                                </span>
                            </div>
                            <a href="{{ route('customer.packages.show', $review->package) }}"
                               class="text-xs font-bold text-gold-600 hover:text-gold-700 transition-colors">
                                Lihat Paket →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>

    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-16 text-center">
            <div class="w-20 h-20 bg-gold-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="h-10 w-10 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
            </div>
            <h3 class="text-xl font-serif font-bold text-choco-900 mb-2 italic">Belum Ada Ulasan</h3>
            <p class="text-stone-500 text-sm mb-8 max-w-sm mx-auto">Setelah acara pernikahan Anda selesai, bagikan pengalaman indah Anda bersama kami</p>
            <a href="{{ route('customer.orders.index', ['from' => 'review']) }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-white text-sm font-bold shadow-lg shadow-gold-500/20 hover:from-gold-600 hover:to-gold-700 transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Lihat Pesanan Saya
            </a>
        </div>
    @endif
</div>
@endsection
