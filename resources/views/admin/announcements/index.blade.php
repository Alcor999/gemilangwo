@extends('layouts.app')

@section('title', 'Kelola Pengumuman - Administrator')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em]">Manajemen Konten</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Kelola <span class="not-italic text-stone-300">Pengumuman</span></h1>
        </div>
        <a href="{{ route('admin.announcements.create') }}"
           class="group flex items-center gap-3 bg-choco-900 text-gold-400 px-6 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-800 transition-all shadow-xl shadow-choco-900/10 active:scale-95">
            <i class="fas fa-plus text-[12px] group-hover:rotate-90 transition-transform"></i>
            Buat Pengumuman
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="p-5 rounded-2xl bg-emerald-50 text-emerald-700 text-sm border border-emerald-100 flex items-center gap-3">
            <i class="fas fa-check-circle text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-5 rounded-2xl bg-rose-50 text-rose-600 text-sm border border-rose-100">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-2 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Total Pengumuman</p>
            <p class="text-3xl font-serif text-choco-900">{{ $total }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Sedang Aktif</p>
            <p class="text-3xl font-serif text-emerald-600">{{ $totalAktif }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Nonaktif</p>
            <p class="text-3xl font-serif text-stone-400">{{ $total - $totalAktif }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Halaman</p>
            <p class="text-3xl font-serif text-gold-600">{{ $announcements->currentPage() }} / {{ $announcements->lastPage() }}</p>
        </div>
    </div>

    <!-- Tabel -->
    <x-luxury.card class="overflow-hidden border-stone-100/50 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/50 border-b border-stone-100">
                        <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Judul</th>
                        <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Kategori</th>
                        <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Periode</th>
                        <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400">Dibuat Oleh</th>
                        <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-center">Status</th>
                        <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse ($announcements as $announcement)
                        <tr class="group hover:bg-stone-50/30 transition-colors">
                            <td class="px-6 py-5">
                                <p class="text-sm font-bold text-choco-900 group-hover:text-gold-600 transition-colors line-clamp-1">
                                    {{ $announcement->judul }}
                                </p>
                                <p class="text-[10px] text-stone-400 mt-0.5 line-clamp-1">{{ Str::limit($announcement->isi, 60) }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border {{ $announcement->warna_badge }}">
                                    {{ $announcement->label_kategori }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-xs text-stone-600">{{ $announcement->tanggal_mulai->format('d M Y') }}</p>
                                <p class="text-[10px] text-stone-400">
                                    {{ $announcement->tanggal_selesai ? 's/d ' . $announcement->tanggal_selesai->format('d M Y') : 'Tanpa batas' }}
                                </p>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-xs text-stone-600">{{ $announcement->pembuat?->name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border
                                    {{ $announcement->is_aktif
                                        ? 'bg-emerald-50 text-emerald-600 border-emerald-100'
                                        : 'bg-stone-100 text-stone-400 border-stone-200' }}">
                                    {{ $announcement->is_aktif ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex justify-end items-center gap-2 text-stone-400">
                                    <!-- Detail -->
                                    <a href="{{ route('admin.announcements.show', $announcement) }}"
                                       class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-transparent hover:border-gold-100"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <!-- Edit -->
                                    <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                       class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-sky-50 hover:text-sky-600 transition-all border border-transparent hover:border-sky-100"
                                       title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <!-- Hapus -->
                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100"
                                                data-confirm="Yakin ingin menghapus pengumuman &quot;{{ $announcement->judul }}&quot;?"
                                                data-confirm-title="Hapus Pengumuman"
                                                data-confirm-btn="Ya, Hapus"
                                                data-confirm-danger="1"
                                                title="Hapus">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-16 text-stone-400">
                                <div class="flex flex-col items-center gap-4">
                                    <div class="h-16 w-16 rounded-3xl bg-stone-50 flex items-center justify-center text-stone-200">
                                        <i class="fas fa-bullhorn text-2xl"></i>
                                    </div>
                                    <p class="italic text-sm">Belum ada pengumuman.</p>
                                    <a href="{{ route('admin.announcements.create') }}"
                                       class="text-gold-600 font-bold text-xs hover:underline">Buat Pengumuman Pertama</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-luxury.card>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $announcements->links() }}
    </div>
</div>
@endsection
