@extends('layouts.app')

@section('title', 'Manajemen Testimoni - Administrator')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em]">Moderasi & Testimoni</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Manajemen <span class="not-italic text-stone-300">Testimoni</span></h1>
        </div>
        <div class="flex items-center gap-3">
            @if($pendingTestimonials->total() > 0)
                <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 rounded-xl">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <span class="text-sm font-bold text-amber-700">{{ $pendingTestimonials->total() }} Menunggu Tinjauan</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Semua Testimoni</p>
            <p class="text-2xl font-serif text-choco-900">{{ $pendingTestimonials->total() + $publishedTestimonials->total() }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-amber-500 text-[9px] font-bold uppercase tracking-widest mb-2">Menunggu Tinjauan</p>
            <p class="text-2xl font-serif text-amber-600">{{ $pendingTestimonials->total() }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-green-500 text-[9px] font-bold uppercase tracking-widest mb-2">Disetujui</p>
            <p class="text-2xl font-serif text-green-600">{{ $publishedTestimonials->total() }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-gold-500 text-[9px] font-bold uppercase tracking-widest mb-2">Unggulan</p>
            <p class="text-2xl font-serif text-gold-600">{{ $publishedTestimonials->where('is_featured', true)->count() }}</p>
        </div>
    </div>

    <!-- Pending Testimonials Section -->
    @if($pendingTestimonials->count() > 0)
    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <h2 class="text-lg font-serif italic text-choco-900">Menunggu Persetujuan</h2>
            <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">{{ $pendingTestimonials->total() }}</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($pendingTestimonials as $testimonial)
                <div class="bg-white/50 backdrop-blur-md rounded-[2rem] overflow-hidden border border-stone-100/50 hover:shadow-lg transition-all duration-300 group">
                    <!-- Thumbnail -->
                    <div class="relative aspect-video bg-choco-900 overflow-hidden">
                        @if($testimonial->type === 'upload' && $testimonial->video_path)
                            <img src="{{ asset('storage/' . $testimonial->video_path) }}"
                                 alt="Gambar Miniatur" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        @elseif($testimonial->type === 'youtube' && $testimonial->youtube_url)
                            <img src="https://img.youtube.com/vi/{{ $testimonial->getYoutubeId() }}/hqdefault.jpg"
                                 alt="Thumbnail YouTube" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-14 h-14 rounded-full bg-white/5 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                    </svg>
                                </div>
                            </div>
                        @endif
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/90 backdrop-blur-sm text-white text-[10px] font-bold shadow-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                Menunggu
                            </span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <!-- Info -->
                        <div>
                            <h3 class="font-bold text-choco-900 text-sm mb-1 line-clamp-1">{{ $testimonial->title }}</h3>
                            <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">{{ $testimonial->description }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-stone-100/50">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gold-500/10 flex items-center justify-center">
                                    <span class="text-[10px] font-bold text-gold-700">{{ substr($testimonial->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-choco-900">{{ $testimonial->user->name }}</p>
                                    <p class="text-[9px] text-stone-400">{{ $testimonial->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <button onclick="document.getElementById('approveModal{{ $testimonial->id }}').classList.remove('hidden')"
                                    class="flex items-center justify-center gap-2 py-2.5 rounded-xl bg-green-50 text-green-600 hover:bg-green-500 hover:text-white text-xs font-bold transition-all duration-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui
                            </button>
                            <button onclick="document.getElementById('rejectModal{{ $testimonial->id }}').classList.remove('hidden')"
                                    class="flex items-center justify-center gap-2 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white text-xs font-bold transition-all duration-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak
                            </button>
                        </div>
                    </div>

                    <!-- Approve Modal -->
                    <div id="approveModal{{ $testimonial->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-choco-900/40 backdrop-blur-sm" onclick="document.getElementById('approveModal{{ $testimonial->id }}').classList.add('hidden')"></div>
                        <div class="relative bg-white/90 backdrop-blur-xl rounded-[2rem] shadow-2xl border border-white w-full max-w-md p-8 z-10 transform transition-all">
                            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-6 transform -rotate-3">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h3 class="text-xl font-serif font-bold text-center text-choco-900 mb-2">Setujui Testimoni?</h3>
                            <p class="text-center text-sm text-stone-500 mb-8 leading-relaxed">
                                Testimoni dari <strong class="text-choco-900">{{ $testimonial->user->name }}</strong> akan dipublikasikan ke halaman utama.
                            </p>
                            <div class="flex gap-3">
                                <button onclick="document.getElementById('approveModal{{ $testimonial->id }}').classList.add('hidden')"
                                        class="flex-1 py-3 rounded-xl border-2 border-stone-100 text-stone-500 text-sm font-bold hover:bg-stone-50 transition-all">
                                    Batal
                                </button>
                                <button onclick="doApproveTestimonial({{ $testimonial->id }})"
                                        class="flex-1 py-3 rounded-xl bg-choco-900 text-white text-sm font-bold hover:bg-choco-800 transition-all shadow-lg shadow-choco-900/20">
                                    Ya, Publikasikan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Reject Modal -->
                    <div id="rejectModal{{ $testimonial->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div class="absolute inset-0 bg-choco-900/40 backdrop-blur-sm" onclick="document.getElementById('rejectModal{{ $testimonial->id }}').classList.add('hidden')"></div>
                        <div class="relative bg-white/90 backdrop-blur-xl rounded-[2rem] shadow-2xl border border-white w-full max-w-md p-8 z-10">
                            <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6 transform -rotate-3">
                                <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </div>
                            <h3 class="text-xl font-serif font-bold text-center text-choco-900 mb-2">Tolak Testimoni?</h3>
                            <p class="text-center text-sm text-stone-500 mb-6">Testimoni dari <strong class="text-choco-900">{{ $testimonial->user->name }}</strong> akan dihapus permanen.</p>
                            <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST">
                                @csrf
                                <div class="mb-6">
                                    <textarea name="reason" rows="3" placeholder="Alasan penolakan (opsional)..."
                                              class="w-full bg-stone-50 rounded-xl border-transparent px-4 py-3 text-sm text-choco-900 focus:ring-2 focus:ring-gold-400 focus:bg-white transition-all resize-none outline-none"></textarea>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" onclick="document.getElementById('rejectModal{{ $testimonial->id }}').classList.add('hidden')"
                                            class="flex-1 py-3 rounded-xl border-2 border-stone-100 text-stone-500 text-sm font-bold hover:bg-stone-50 transition-all">
                                        Batal
                                    </button>
                                    <button type="submit" class="flex-1 py-3 rounded-xl bg-red-500 text-white text-sm font-bold hover:bg-red-600 transition-all shadow-lg shadow-red-500/20">
                                        Tolak & Hapus
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $pendingTestimonials->links() }}
        </div>
    </div>
    @endif

    <!-- Published Testimonials Section -->
    <div class="space-y-4 pt-6">
        <div class="flex items-center gap-3 border-t border-stone-200/50 pt-8">
            <h2 class="text-lg font-serif italic text-choco-900">Testimoni Dipublikasikan</h2>
        </div>

        @if($publishedTestimonials->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($publishedTestimonials as $testimonial)
                <div class="bg-white/50 backdrop-blur-md rounded-[2rem] overflow-hidden border border-stone-100/50 hover:shadow-lg transition-all duration-300 group">
                    <!-- Thumbnail -->
                    <div class="relative aspect-video bg-choco-900 overflow-hidden">
                        @if($testimonial->type === 'upload' && $testimonial->video_path)
                            <img src="{{ asset('storage/' . $testimonial->video_path) }}"
                                 alt="Gambar Miniatur" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        @elseif($testimonial->type === 'youtube' && $testimonial->youtube_url)
                            <img src="https://img.youtube.com/vi/{{ $testimonial->getYoutubeId() }}/hqdefault.jpg"
                                 alt="Thumbnail YouTube" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-14 h-14 rounded-full bg-white/5 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                    </svg>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Badges -->
                        <div class="absolute top-4 right-4 flex flex-col gap-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-500/90 backdrop-blur-sm text-white text-[10px] font-bold shadow-lg">
                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Aktif
                            </span>
                            @if($testimonial->is_featured)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gold-500/90 backdrop-blur-sm text-white text-[10px] font-bold shadow-lg">
                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    Unggulan
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <h3 class="font-bold text-choco-900 text-sm mb-1 line-clamp-1">{{ $testimonial->title }}</h3>
                            <p class="text-xs text-stone-500 line-clamp-2 leading-relaxed">{{ $testimonial->description }}</p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-stone-100/50">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-choco-50 flex items-center justify-center">
                                    <span class="text-[10px] font-bold text-choco-700">{{ substr($testimonial->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-choco-900">{{ $testimonial->user->name }}</p>
                                    <div class="flex items-center gap-1 text-[9px] text-stone-400 mt-0.5">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ $testimonial->views }} tayangan
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            @if(!$testimonial->is_featured)
                                <form action="{{ route('admin.testimonials.feature', $testimonial) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-gold-50 text-gold-600 hover:bg-gold-500 hover:text-white text-xs font-bold transition-all duration-300">
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        Jadikan Unggulan
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.testimonials.unfeature', $testimonial) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-stone-100 text-stone-500 hover:bg-stone-200 text-xs font-bold transition-all duration-300">
                                        Batal Unggulan
                                    </button>
                                </form>
                            @endif
                            <button onclick="document.getElementById('delModal{{ $testimonial->id }}').classList.remove('hidden')"
                                    class="flex items-center justify-center gap-2 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white text-xs font-bold transition-all duration-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </div>

                        <!-- Delete Modal -->
                        <div id="delModal{{ $testimonial->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                            <div class="absolute inset-0 bg-choco-900/40 backdrop-blur-sm" onclick="document.getElementById('delModal{{ $testimonial->id }}').classList.add('hidden')"></div>
                            <div class="relative bg-white/90 backdrop-blur-xl rounded-[2rem] shadow-2xl border border-white w-full max-w-md p-8 z-10">
                                <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-6 transform -rotate-3">
                                    <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </div>
                                <h3 class="text-xl font-serif font-bold text-center text-choco-900 mb-2">Hapus Testimoni?</h3>
                                <p class="text-center text-sm text-stone-500 mb-8 leading-relaxed">
                                    Testimoni <strong>{{ $testimonial->title }}</strong> akan dihapus permanen dan tidak dapat dikembalikan.
                                </p>
                                <div class="flex gap-3">
                                    <button onclick="document.getElementById('delModal{{ $testimonial->id }}').classList.add('hidden')"
                                            class="flex-1 py-3 rounded-xl border-2 border-stone-100 text-stone-500 text-sm font-bold hover:bg-stone-50 transition-all">
                                        Batal
                                    </button>
                                    <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full py-3 rounded-xl bg-red-500 text-white text-sm font-bold hover:bg-red-600 transition-all shadow-lg shadow-red-500/20">
                                            Ya, Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $publishedTestimonials->links() }}
        </div>
        @else
        <div class="bg-white/50 backdrop-blur-md rounded-[2rem] border border-stone-100/50 p-12 text-center">
            <div class="w-20 h-20 bg-stone-100 rounded-[1.5rem] flex items-center justify-center mx-auto mb-6 transform -rotate-3">
                <svg class="h-10 w-10 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
            </div>
            <h3 class="font-serif text-2xl font-bold text-choco-900 mb-2 italic">Belum Ada Publikasi</h3>
            <p class="text-stone-500 max-w-sm mx-auto">Setujui testimoni dari klien untuk mulai menampilkannya di halaman utama Anda.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function doApproveTestimonial(id) {
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
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-8 right-8 z-[9999] flex items-center gap-3 px-6 py-4 bg-choco-900 text-white rounded-2xl shadow-2xl text-sm font-bold animate-fade-in-up border border-white/10';
            notification.innerHTML = '<svg class="h-5 w-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Testimoni berhasil dipublikasikan!';
            document.body.appendChild(notification);
            setTimeout(() => { notification.style.opacity = '0'; setTimeout(() => location.reload(), 300); }, 2000);
        }
    })
    .catch(error => console.error('Kesalahan:', error));
}
</script>
<style>
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
    animation: fade-in-up 0.3s ease-out forwards;
}
</style>
@endpush
@endsection
