@extends('layouts.admin')

@section('title', 'Manajemen Testimoni - Admin')
@section('page_title', 'Manajemen Testimoni Klien')

@section('content')
<div class="space-y-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif font-bold text-brown-950 italic">Manajemen Testimoni</h1>
            <p class="mt-1 text-sm text-brown-500">Tinjau dan kelola testimoni video dari para klien</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-sm font-bold text-amber-700">{{ $pendingTestimonials->total() }} Menunggu Tinjauan</span>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-brown-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-brown-950">{{ $pendingTestimonials->total() }}</p>
                <p class="text-xs text-brown-400 font-medium uppercase tracking-wider">Menunggu Tinjauan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-brown-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-brown-950">{{ $publishedTestimonials->total() }}</p>
                <p class="text-xs text-brown-400 font-medium uppercase tracking-wider">Dipublikasikan</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-brown-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-gold-50 rounded-xl flex items-center justify-center">
                <svg class="h-6 w-6 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-brown-950">{{ $publishedTestimonials->where('is_featured', true)->count() }}</p>
                <p class="text-xs text-brown-400 font-medium uppercase tracking-wider">Ditampilkan Unggulan</p>
            </div>
        </div>
    </div>

    {{-- Pending Testimonials Section --}}
    @if($pendingTestimonials->count() > 0)
    <div class="bg-white rounded-2xl border border-brown-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-brown-100 flex items-center gap-3">
            <div class="w-2 h-6 bg-amber-500 rounded-full"></div>
            <div>
                <h2 class="font-bold text-brown-950">Menunggu Tinjauan</h2>
                <p class="text-xs text-brown-400 mt-0.5">{{ $pendingTestimonials->total() }} testimoni memerlukan persetujuan Anda</p>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($pendingTestimonials as $testimonial)
                    <div class="bg-premium-cream rounded-2xl overflow-hidden border border-brown-100 hover:shadow-md transition-all group">
                        {{-- Thumbnail --}}
                        <div class="relative aspect-video bg-brown-950 overflow-hidden">
                            @if($testimonial->type === 'upload' && $testimonial->video_path)
                                <img src="{{ asset('storage/' . $testimonial->video_path) }}"
                                     alt="Gambar Miniatur" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @elseif($testimonial->type === 'youtube' && $testimonial->youtube_url)
                                <img src="https://img.youtube.com/vi/{{ $testimonial->getYoutubeId() }}/hqdefault.jpg"
                                     alt="Thumbnail YouTube" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-2">
                                            <svg class="h-7 w-7 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs text-white/50">Tidak Ada Thumbnail</p>
                                    </div>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-500 text-white text-[10px] font-bold shadow-lg">
                                    <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Menunggu
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            {{-- Info --}}
                            <h3 class="font-bold text-brown-950 text-sm mb-1 line-clamp-1">{{ $testimonial->title }}</h3>
                            <p class="text-xs text-brown-500 line-clamp-2 mb-3">{{ Str::limit($testimonial->description, 80) }}</p>

                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 rounded-full bg-gold-500/20 flex items-center justify-center">
                                    <span class="text-[10px] font-bold text-gold-700">{{ substr($testimonial->user->name, 0, 1) }}</span>
                                </div>
                                <span class="text-xs font-bold text-brown-700">{{ $testimonial->user->name }}</span>
                                <span class="ml-auto text-xs text-brown-400">{{ $testimonial->created_at->diffForHumans() }}</span>
                            </div>

                            {{-- Rating --}}
                            @if($testimonial->rating)
                                <div class="flex gap-0.5 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-3.5 w-3.5 {{ $i <= $testimonial->rating ? 'text-gold-400' : 'text-brown-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            @endif

                            {{-- Action Buttons --}}
                            <div class="flex gap-2">
                                <a href="{{ route('admin.testimonials.show', $testimonial) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-1 py-2 rounded-xl bg-brown-100 text-brown-700 text-xs font-bold hover:bg-brown-200 transition-all">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Tinjau
                                </a>
                                <button onclick="document.getElementById('approveModal{{ $testimonial->id }}').classList.remove('hidden')"
                                        class="flex-1 inline-flex items-center justify-center gap-1 py-2 rounded-xl bg-green-500 text-white text-xs font-bold hover:bg-green-600 transition-all">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Setujui
                                </button>
                                <button onclick="document.getElementById('rejectModal{{ $testimonial->id }}').classList.remove('hidden')"
                                        class="flex-1 inline-flex items-center justify-center gap-1 py-2 rounded-xl bg-red-500 text-white text-xs font-bold hover:bg-red-600 transition-all">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tolak
                                </button>
                            </div>
                        </div>

                        {{-- Approve Modal --}}
                        <div id="approveModal{{ $testimonial->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('approveModal{{ $testimonial->id }}').classList.add('hidden')"></div>
                            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
                                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-7 w-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-center text-brown-950 mb-2">Setujui Testimoni?</h3>
                                <p class="text-center text-sm text-brown-500 mb-6">
                                    Testimoni <strong>"{{ $testimonial->title }}"</strong> dari <strong>{{ $testimonial->user->name }}</strong> akan dipublikasikan.
                                </p>
                                <div class="flex gap-3">
                                    <button onclick="document.getElementById('approveModal{{ $testimonial->id }}').classList.add('hidden')"
                                            class="flex-1 py-2.5 rounded-xl border border-brown-200 text-brown-600 text-sm font-bold hover:bg-brown-50 transition-all">
                                        Batal
                                    </button>
                                    <button onclick="doApproveTestimonial({{ $testimonial->id }})"
                                            class="flex-1 py-2.5 rounded-xl bg-green-500 text-white text-sm font-bold hover:bg-green-600 transition-all">
                                        Ya, Setujui
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Reject Modal --}}
                        <div id="rejectModal{{ $testimonial->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('rejectModal{{ $testimonial->id }}').classList.add('hidden')"></div>
                            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
                                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-center text-brown-950 mb-2">Tolak Testimoni?</h3>
                                <p class="text-center text-sm text-brown-500 mb-4">Testimoni dari <strong>{{ $testimonial->user->name }}</strong> akan ditolak.</p>
                                <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-xs font-bold text-brown-700 mb-1.5">Alasan Penolakan (opsional)</label>
                                        <textarea name="reason" rows="3" placeholder="Jelaskan mengapa testimoni ini ditolak..."
                                                  class="w-full rounded-xl border border-brown-200 px-4 py-2.5 text-sm text-brown-800 focus:ring-2 focus:ring-gold-400 focus:border-transparent resize-none outline-none"></textarea>
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="document.getElementById('rejectModal{{ $testimonial->id }}').classList.add('hidden')"
                                                class="flex-1 py-2.5 rounded-xl border border-brown-200 text-brown-600 text-sm font-bold hover:bg-brown-50 transition-all">
                                            Batal
                                        </button>
                                        <button type="submit" class="flex-1 py-2.5 rounded-xl bg-red-500 text-white text-sm font-bold hover:bg-red-600 transition-all">
                                            Tolak & Hapus
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $pendingTestimonials->links() }}
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-brown-100 shadow-sm p-8 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center shrink-0">
            <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-brown-950">Semua Bersih!</p>
            <p class="text-sm text-brown-400 mt-0.5">Tidak ada testimoni yang menunggu tinjauan. Semua sudah diproses.</p>
        </div>
    </div>
    @endif

    {{-- Published Testimonials Section --}}
    <div class="bg-white rounded-2xl border border-brown-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-brown-100 flex items-center gap-3">
            <div class="w-2 h-6 bg-green-500 rounded-full"></div>
            <div>
                <h2 class="font-bold text-brown-950">Testimoni Dipublikasikan</h2>
                <p class="text-xs text-brown-400 mt-0.5">{{ $publishedTestimonials->total() }} testimoni aktif ditampilkan</p>
            </div>
        </div>

        @if($publishedTestimonials->count() > 0)
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($publishedTestimonials as $testimonial)
                    <div class="bg-premium-cream rounded-2xl overflow-hidden border border-brown-100 hover:shadow-md transition-all group">
                        {{-- Thumbnail --}}
                        <div class="relative aspect-video bg-brown-950 overflow-hidden">
                            @if($testimonial->type === 'upload' && $testimonial->video_path)
                                <img src="{{ asset('storage/' . $testimonial->video_path) }}"
                                     alt="Gambar Miniatur" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @elseif($testimonial->type === 'youtube' && $testimonial->youtube_url)
                                <img src="https://img.youtube.com/vi/{{ $testimonial->getYoutubeId() }}/hqdefault.jpg"
                                     alt="Thumbnail YouTube" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center">
                                        <svg class="h-7 w-7 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                        </svg>
                                    </div>
                                </div>
                            @endif
                            {{-- Featured Badge --}}
                            @if($testimonial->is_featured)
                                <div class="absolute top-2 right-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gold-500 text-white text-[10px] font-bold shadow-lg">
                                        <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Unggulan
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <h3 class="font-bold text-brown-950 text-sm mb-1 line-clamp-1">{{ $testimonial->title }}</h3>
                            <p class="text-xs text-brown-500 line-clamp-2 mb-3">{{ Str::limit($testimonial->description, 80) }}</p>

                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 rounded-full bg-gold-500/20 flex items-center justify-center">
                                    <span class="text-[10px] font-bold text-gold-700">{{ substr($testimonial->user->name, 0, 1) }}</span>
                                </div>
                                <span class="text-xs font-bold text-brown-700">{{ $testimonial->user->name }}</span>
                                <div class="ml-auto flex items-center gap-1 text-xs text-brown-400">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    {{ $testimonial->views }}
                                </div>
                            </div>

                            {{-- Rating --}}
                            @if($testimonial->rating)
                                <div class="flex gap-0.5 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-3.5 w-3.5 {{ $i <= $testimonial->rating ? 'text-gold-400' : 'text-brown-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            @endif

                            {{-- Action Buttons --}}
                            <div class="flex gap-2">
                                <a href="{{ route('admin.testimonials.show', $testimonial) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-1 py-2 rounded-xl bg-brown-100 text-brown-700 text-xs font-bold hover:bg-brown-200 transition-all">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat
                                </a>
                                @if(!$testimonial->is_featured)
                                    <form action="{{ route('admin.testimonials.feature', $testimonial) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1 py-2 rounded-xl bg-gold-100 text-gold-700 text-xs font-bold hover:bg-gold-200 transition-all border border-gold-200">
                                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            Unggulan
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.testimonials.unfeature', $testimonial) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full inline-flex items-center justify-center gap-1 py-2 rounded-xl bg-stone-100 text-stone-600 text-xs font-bold hover:bg-stone-200 transition-all">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                                <button onclick="document.getElementById('delModal{{ $testimonial->id }}').classList.remove('hidden')"
                                        class="flex-1 inline-flex items-center justify-center gap-1 py-2 rounded-xl bg-red-50 text-red-600 text-xs font-bold hover:bg-red-100 transition-all border border-red-100">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </div>

                        {{-- Delete Modal --}}
                        <div id="delModal{{ $testimonial->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('delModal{{ $testimonial->id }}').classList.add('hidden')"></div>
                            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 z-10">
                                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="h-7 w-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-center text-brown-950 mb-1">Hapus Testimoni?</h3>
                                <p class="text-center text-sm text-brown-500 mb-1">Judul: <strong>{{ $testimonial->title }}</strong></p>
                                <p class="text-center text-sm text-brown-500 mb-6">Oleh: <strong>{{ $testimonial->user->name }}</strong></p>
                                <div class="flex gap-3">
                                    <button onclick="document.getElementById('delModal{{ $testimonial->id }}').classList.add('hidden')"
                                            class="flex-1 py-2.5 rounded-xl border border-brown-200 text-brown-600 text-sm font-bold hover:bg-brown-50 transition-all">
                                        Batal
                                    </button>
                                    <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST" class="flex-1">
                                        @csrf
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

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $publishedTestimonials->links() }}
            </div>
        </div>
        @else
        <div class="p-8 text-center">
            <div class="w-16 h-16 bg-stone-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="h-8 w-8 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
            </div>
            <p class="font-bold text-brown-700">Belum Ada Testimoni Dipublikasikan</p>
            <p class="text-sm text-brown-400 mt-1">Setujui kiriman dari klien untuk mulai menampilkannya</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function doApproveTestimonial(id) {
    // Close the modal first
    const modal = document.getElementById('approveModal' + id);
    if (modal) modal.classList.add('hidden');

    fetch(`/admin/testimonials/${id}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 z-[9999] flex items-center gap-3 px-5 py-3 bg-green-500 text-white rounded-xl shadow-xl text-sm font-bold';
            notification.innerHTML = '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Testimoni berhasil disetujui!';
            document.body.appendChild(notification);
            setTimeout(() => { notification.remove(); location.reload(); }, 1500);
        }
    })
    .catch(error => console.error('Kesalahan:', error));
}
</script>
@endpush
@endsection
