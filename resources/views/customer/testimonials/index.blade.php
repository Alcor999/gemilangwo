@extends('layouts.app')

@section('title', 'Testimoni Saya - Gemilang WO')
@section('header_title', 'Testimoni Saya')

@section('content')
<div class="space-y-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif font-bold text-choco-900 italic">Testimoni Video Saya</h1>
            <p class="mt-1 text-sm text-stone-500">Bagikan momen bahagia pernikahan Anda melalui video testimoni</p>
        </div>
        <a href="{{ route('customer.testimonials.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-white text-sm font-bold shadow-lg shadow-gold-500/20 hover:from-gold-600 hover:to-gold-700 transition-all active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Testimoni
        </a>
    </div>

    @if($testimonials->count() > 0)
        {{-- Stats Row --}}
        @php
            $publishedCount = $testimonials->where('is_active', true)->count();
            $pendingCount = $testimonials->where('is_active', false)->count();
            $featuredCount = $testimonials->where('is_featured', true)->count();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-choco-900">{{ $publishedCount }}</p>
                    <p class="text-xs text-stone-500 font-medium uppercase tracking-wider">Dipublikasikan</p>
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
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 bg-gold-50 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-choco-900">{{ $featuredCount }}</p>
                    <p class="text-xs text-stone-500 font-medium uppercase tracking-wider">Ditampilkan Unggulan</p>
                </div>
            </div>
        </div>

        {{-- Testimonials Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($testimonials as $testimonial)
                <div class="bg-white rounded-2xl border border-stone-100 shadow-sm hover:shadow-md transition-all overflow-hidden group">
                    {{-- Thumbnail --}}
                    <div class="relative aspect-video bg-choco-900 overflow-hidden">
                        @if($testimonial->thumbnail_path)
                            <img src="{{ asset('storage/' . $testimonial->thumbnail_path) }}"
                                 alt="{{ $testimonial->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @elseif($testimonial->type === 'youtube' && $testimonial->youtube_url)
                            <img src="https://img.youtube.com/vi/{{ $testimonial->getYoutubeId() }}/hqdefault.jpg"
                                 alt="Thumbnail YouTube"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-3">
                                        <svg class="h-8 w-8 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-white/50">Tidak Ada Thumbnail</p>
                                </div>
                            </div>
                        @endif

                        {{-- Overlay Badges --}}
                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            @if($testimonial->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-green-500 text-white text-[10px] font-bold shadow-lg">
                                    <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-500 text-white text-[10px] font-bold shadow-lg">
                                    <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Review
                                </span>
                            @endif
                            @if($testimonial->is_featured)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-gold-500 text-white text-[10px] font-bold shadow-lg">
                                    <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    Unggulan
                                </span>
                            @endif
                        </div>

                        {{-- Type Badge --}}
                        <div class="absolute bottom-3 left-3">
                            <span class="px-2 py-0.5 rounded-full bg-black/50 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider">
                                {{ $testimonial->type === 'youtube' ? '▶ YouTube' : '📁 Upload' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        {{-- Title --}}
                        <h3 class="font-bold text-choco-900 text-base mb-2 line-clamp-1">{{ $testimonial->title }}</h3>

                        {{-- Description --}}
                        <p class="text-sm text-stone-500 leading-relaxed mb-4 line-clamp-2">{{ $testimonial->description }}</p>

                        {{-- Rating --}}
                        @if($testimonial->rating)
                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $testimonial->rating ? 'text-gold-400' : 'text-stone-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs font-bold text-stone-500">{{ $testimonial->rating }}/5</span>
                            </div>
                        @endif

                        {{-- Views & Date --}}
                        <div class="flex items-center justify-between text-xs text-stone-400 mb-4 pt-3 border-t border-stone-100">
                            <span class="flex items-center gap-1">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                {{ $testimonial->views }} tayangan
                            </span>
                            <span>{{ $testimonial->created_at->translatedFormat('d M Y') }}</span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-2">
                            <a href="{{ route('customer.testimonials.edit', $testimonial->id) }}"
                               class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-gold-300 text-gold-700 text-xs font-bold hover:bg-gold-50 transition-all">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </a>
                            <button type="button"
                                    onclick="document.getElementById('deleteModal{{ $testimonial->id }}').classList.remove('hidden')"
                                    class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl border border-red-200 text-red-600 text-xs font-bold hover:bg-red-50 transition-all">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </div>
                    </div>

                    {{-- Delete Modal --}}
                    <div id="deleteModal{{ $testimonial->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('deleteModal{{ $testimonial->id }}').classList.add('hidden')"></div>
                        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
                            <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-center text-choco-900 mb-2">Hapus Testimoni?</h3>
                            <p class="text-center text-sm text-stone-500 mb-6">Testimoni <strong>"{{ $testimonial->title }}"</strong> akan dihapus secara permanen.</p>
                            <div class="flex gap-3">
                                <button onclick="document.getElementById('deleteModal{{ $testimonial->id }}').classList.add('hidden')"
                                        class="flex-1 py-2.5 rounded-xl border border-stone-200 text-stone-600 text-sm font-bold hover:bg-stone-50 transition-all">
                                    Batal
                                </button>
                                <form action="{{ route('customer.testimonials.destroy', $testimonial->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2.5 rounded-xl bg-red-500 text-white text-sm font-bold hover:bg-red-600 transition-all">
                                        Hapus Permanen
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-16 text-center">
            <div class="w-20 h-20 bg-gold-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="h-10 w-10 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
            </div>
            <h3 class="text-xl font-serif font-bold text-choco-900 mb-2 italic">Belum Ada Testimoni</h3>
            <p class="text-stone-500 text-sm mb-8 max-w-sm mx-auto">Abadikan kenangan indah pernikahan Anda dan bagikan kepada dunia melalui video testimoni</p>
            <a href="{{ route('customer.testimonials.create') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-gold-500 to-gold-600 text-white text-sm font-bold shadow-lg shadow-gold-500/20 hover:from-gold-600 hover:to-gold-700 transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat Testimoni Pertama
            </a>
        </div>
    @endif
</div>
@endsection
