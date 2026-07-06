@extends('layouts.app')

@section('title', 'Edit Pengumuman - Administrator')

@section('content')
<div class="space-y-8 pb-12 max-w-3xl mx-auto">
    <!-- Header -->
    <div class="space-y-1">
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em]">Manajemen Konten</p>
        <h1 class="font-serif text-4xl text-choco-900 italic">Edit <span class="not-italic text-stone-300">Pengumuman</span></h1>
        <p class="text-stone-400 text-sm">Perbarui informasi pengumuman di bawah ini.</p>
    </div>

    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-stone-400">
        <a href="{{ route('admin.announcements.index') }}" class="hover:text-gold-600 transition-colors">Pengumuman</a>
        <i class="fas fa-chevron-right text-[8px]"></i>
        <a href="{{ route('admin.announcements.show', $announcement) }}" class="hover:text-gold-600 transition-colors line-clamp-1">
            {{ Str::limit($announcement->judul, 30) }}
        </a>
        <i class="fas fa-chevron-right text-[8px]"></i>
        <span class="text-choco-900">Edit</span>
    </nav>

    <!-- Form Card -->
    <x-luxury.card class="p-8 border-stone-100 shadow-sm">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="space-y-7">
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div class="space-y-2">
                <label for="judul" class="block text-[10px] font-bold uppercase tracking-widest text-stone-500">
                    Judul Pengumuman <span class="text-rose-500">*</span>
                </label>
                <input type="text"
                       id="judul"
                       name="judul"
                       value="{{ old('judul', $announcement->judul) }}"
                       placeholder="Masukkan judul pengumuman..."
                       class="w-full px-5 py-4 rounded-2xl border border-stone-200 bg-stone-50/50 text-choco-900 text-sm font-medium
                              placeholder:text-stone-300 focus:outline-none focus:ring-2 focus:ring-gold-300 focus:border-gold-300
                              transition-all @error('judul') border-rose-300 bg-rose-50/50 @enderror">
                @error('judul')
                    <p class="text-rose-500 text-xs flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Isi -->
            <div class="space-y-2">
                <label for="isi" class="block text-[10px] font-bold uppercase tracking-widest text-stone-500">
                    Isi Pengumuman <span class="text-rose-500">*</span>
                </label>
                <textarea id="isi"
                          name="isi"
                          rows="6"
                          placeholder="Tulis isi pengumuman di sini..."
                          class="w-full px-5 py-4 rounded-2xl border border-stone-200 bg-stone-50/50 text-choco-900 text-sm font-light leading-relaxed
                                 placeholder:text-stone-300 focus:outline-none focus:ring-2 focus:ring-gold-300 focus:border-gold-300
                                 transition-all resize-none @error('isi') border-rose-300 bg-rose-50/50 @enderror">{{ old('isi', $announcement->isi) }}</textarea>
                @error('isi')
                    <p class="text-rose-500 text-xs flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="space-y-2">
                <label for="kategori" class="block text-[10px] font-bold uppercase tracking-widest text-stone-500">
                    Kategori <span class="text-rose-500">*</span>
                </label>
                <select id="kategori"
                        name="kategori"
                        class="w-full px-5 py-4 rounded-2xl border border-stone-200 bg-stone-50/50 text-choco-900 text-sm font-medium
                               focus:outline-none focus:ring-2 focus:ring-gold-300 focus:border-gold-300 transition-all
                               @error('kategori') border-rose-300 bg-rose-50/50 @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="info"       {{ old('kategori', $announcement->kategori) == 'info'       ? 'selected' : '' }}>📢 Informasi</option>
                    <option value="promo"      {{ old('kategori', $announcement->kategori) == 'promo'      ? 'selected' : '' }}>🎉 Promosi</option>
                    <option value="peringatan" {{ old('kategori', $announcement->kategori) == 'peringatan' ? 'selected' : '' }}>⚠️ Peringatan</option>
                    <option value="event"      {{ old('kategori', $announcement->kategori) == 'event'      ? 'selected' : '' }}>📅 Event</option>
                </select>
                @error('kategori')
                    <p class="text-rose-500 text-xs flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="tanggal_mulai" class="block text-[10px] font-bold uppercase tracking-widest text-stone-500">
                        Tanggal Mulai <span class="text-rose-500">*</span>
                    </label>
                    <input type="date"
                           id="tanggal_mulai"
                           name="tanggal_mulai"
                           value="{{ old('tanggal_mulai', $announcement->tanggal_mulai?->format('Y-m-d')) }}"
                           class="w-full px-5 py-4 rounded-2xl border border-stone-200 bg-stone-50/50 text-choco-900 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-gold-300 focus:border-gold-300 transition-all
                                  @error('tanggal_mulai') border-rose-300 bg-rose-50/50 @enderror">
                    @error('tanggal_mulai')
                        <p class="text-rose-500 text-xs"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label for="tanggal_selesai" class="block text-[10px] font-bold uppercase tracking-widest text-stone-500">
                        Tanggal Selesai <span class="text-stone-300 font-light normal-case">(opsional)</span>
                    </label>
                    <input type="date"
                           id="tanggal_selesai"
                           name="tanggal_selesai"
                           value="{{ old('tanggal_selesai', $announcement->tanggal_selesai?->format('Y-m-d')) }}"
                           class="w-full px-5 py-4 rounded-2xl border border-stone-200 bg-stone-50/50 text-choco-900 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-gold-300 focus:border-gold-300 transition-all
                                  @error('tanggal_selesai') border-rose-300 bg-rose-50/50 @enderror">
                    @error('tanggal_selesai')
                        <p class="text-rose-500 text-xs"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status Aktif -->
            <div class="flex items-center gap-4 p-5 rounded-2xl bg-stone-50/80 border border-stone-100">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox"
                           id="is_aktif"
                           name="is_aktif"
                           value="1"
                           class="sr-only peer"
                           {{ old('is_aktif', $announcement->is_aktif) ? 'checked' : '' }}>
                    <div class="w-12 h-6 bg-stone-200 peer-focus:outline-none rounded-full peer
                                peer-checked:after:translate-x-full peer-checked:after:border-white
                                after:content-[''] after:absolute after:top-0.5 after:left-[2px]
                                after:bg-white after:border-stone-300 after:border after:rounded-full
                                after:h-5 after:w-5 after:transition-all peer-checked:bg-gold-500"></div>
                </label>
                <div>
                    <p class="text-sm font-bold text-choco-900">Aktifkan Pengumuman</p>
                    <p class="text-[10px] text-stone-400 uppercase tracking-widest">Pengumuman akan ditampilkan jika aktif</p>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex items-center justify-between pt-4 border-t border-stone-100">
                <a href="{{ route('admin.announcements.show', $announcement) }}"
                   class="px-6 py-3 rounded-2xl border border-stone-200 text-stone-500 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-50 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Batal
                </a>
                <button type="submit"
                        class="px-8 py-4 rounded-2xl bg-choco-900 text-gold-400 text-[10px] font-bold uppercase tracking-widest
                               hover:bg-stone-800 transition-all shadow-xl shadow-choco-900/10 active:scale-95 flex items-center gap-3">
                    <i class="fas fa-save"></i> Perbarui Pengumuman
                </button>
            </div>
        </form>
    </x-luxury.card>
</div>
@endsection
