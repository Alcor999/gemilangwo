@extends('layouts.app')

@section('title', 'Detail Pengumuman - Administrator')

@section('content')
<div class="space-y-8 pb-12 max-w-3xl mx-auto">
    <!-- Header -->
    <div class="space-y-1">
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em]">Manajemen Konten</p>
        <h1 class="font-serif text-4xl text-choco-900 italic">Detail <span class="not-italic text-stone-300">Pengumuman</span></h1>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-stone-400">
        <a href="{{ route('admin.announcements.index') }}" class="hover:text-gold-600 transition-colors">Pengumuman</a>
        <i class="fas fa-chevron-right text-[8px]"></i>
        <span class="text-choco-900">Detail</span>
    </nav>

    <!-- Action Buttons -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.announcements.edit', $announcement) }}"
           class="flex items-center gap-2 px-5 py-3 rounded-2xl bg-choco-900 text-gold-400 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-800 transition-all shadow-lg">
            <i class="fas fa-edit"></i> Edit
        </a>
        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="button"
                    class="flex items-center gap-2 px-5 py-3 rounded-2xl border border-rose-200 text-rose-500 text-[10px] font-bold uppercase tracking-widest hover:bg-rose-50 transition-all"
                    data-confirm="Yakin ingin menghapus pengumuman ini?"
                    data-confirm-title="Hapus Pengumuman"
                    data-confirm-btn="Ya, Hapus"
                    data-confirm-danger="1">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
        <a href="{{ route('admin.announcements.index') }}"
           class="ml-auto flex items-center gap-2 px-5 py-3 rounded-2xl border border-stone-200 text-stone-500 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-50 transition-all">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Detail Card -->
    <x-luxury.card class="p-8 border-stone-100 shadow-sm space-y-8">
        <!-- Badge Kategori & Status -->
        <div class="flex flex-wrap items-center gap-3">
            <span class="px-4 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-widest border {{ $announcement->warna_badge }}">
                {{ $announcement->label_kategori }}
            </span>
            <span class="px-4 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-widest border
                {{ $announcement->is_aktif
                    ? 'bg-emerald-50 text-emerald-600 border-emerald-100'
                    : 'bg-stone-100 text-stone-400 border-stone-200' }}">
                {{ $announcement->is_aktif ? '● Aktif' : '○ Nonaktif' }}
            </span>
            @if($announcement->sedang_berlangsung)
                <span class="px-4 py-1.5 rounded-full text-[9px] font-bold uppercase tracking-widest border bg-gold-50 text-gold-600 border-gold-100">
                    ✨ Sedang Berlangsung
                </span>
            @endif
        </div>

        <!-- Judul -->
        <div>
            <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-2">Judul Pengumuman</p>
            <h2 class="font-serif text-2xl text-choco-900 italic">{{ $announcement->judul }}</h2>
        </div>

        <!-- Isi -->
        <div>
            <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-3">Isi Pengumuman</p>
            <div class="p-6 rounded-2xl bg-stone-50/80 border border-stone-100">
                <p class="text-sm text-choco-900 leading-relaxed whitespace-pre-line">{{ $announcement->isi }}</p>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-stone-100"></div>

        <!-- Meta Info -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-1">Tanggal Mulai</p>
                <p class="text-sm font-bold text-choco-900">{{ $announcement->tanggal_mulai->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-1">Tanggal Selesai</p>
                <p class="text-sm font-bold text-choco-900">
                    {{ $announcement->tanggal_selesai ? $announcement->tanggal_selesai->format('d M Y') : 'Tanpa Batas' }}
                </p>
            </div>
            <div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-1">Dibuat Oleh</p>
                <p class="text-sm font-bold text-choco-900">{{ $announcement->pembuat?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-1">Dibuat Pada</p>
                <p class="text-sm text-stone-600">{{ $announcement->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-1">Terakhir Diperbarui</p>
                <p class="text-sm text-stone-600">{{ $announcement->updated_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400 mb-1">ID Pengumuman</p>
                <p class="text-sm text-stone-600 font-mono">#{{ $announcement->id }}</p>
            </div>
        </div>
    </x-luxury.card>
</div>
@endsection
