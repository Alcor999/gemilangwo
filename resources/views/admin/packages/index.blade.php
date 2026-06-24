@extends('layouts.app')

@section('title', 'Koleksi Paket Pernikahan - Administrator')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em]">Master Records</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Koleksi <span class="not-italic text-stone-300">Paket</span></h1>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.packages.create') }}" 
               class="group flex items-center gap-3 bg-choco-900 text-gold-400 px-6 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-800 transition-all shadow-xl shadow-choco-900/10 active:scale-95">
                <i class="fas fa-plus text-[12px] group-hover:rotate-90 transition-transform"></i>
                Buat Paket Baru
            </a>
        </div>
    </div>

    <!-- Stats Overview (Optional but adds premium feel) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Total Paket</p>
            <p class="text-2xl font-serif text-choco-900">{{ $packages->count() }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Aktif</p>
            <p class="text-2xl font-serif text-emerald-600">{{ $packages->where('status', 'active')->count() }}</p>
        </div>
    </div>

    <!-- Table Section -->
    <x-luxury.card class="overflow-hidden border-stone-100/50 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/50 border-b border-stone-100">
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Informasi Paket</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Harga Base</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Kapasitas</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($packages as $package)
                        <tr class="group hover:bg-stone-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-xl bg-stone-100/50 flex items-center justify-center text-gold-500 group-hover:bg-gold-50 transition-colors">
                                        @if($package->image)
                                            <img src="{{ asset('storage/' . $package->image) }}" class="h-full w-full object-cover rounded-xl" alt="">
                                        @else
                                            <img src="{{ asset('images/logo.png') }}" class="h-8 w-8 object-contain opacity-40" alt="">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-choco-900 group-hover:text-gold-600 transition-colors">{{ $package->name }}</p>
                                        <p class="text-[10px] text-stone-400 font-light truncate max-w-[200px]">{{ $package->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-xs font-bold text-choco-900">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2 text-stone-400">
                                    <i class="fas fa-users text-[10px]"></i>
                                    <span class="text-xs">{{ $package->max_guests ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest {{ $package->status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-stone-100 text-stone-400 border border-stone-200' }}">
                                        {{ $package->status === 'active' ? 'Aktif' : 'Draft' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-end items-center gap-2 text-stone-400">
                                    <a href="{{ route('admin.packages.edit', $package->id) }}" 
                                       class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-transparent hover:border-gold-100" title="Ubah Data">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100"
                                                data-confirm="Hapus paket &quot;{{ $package->name }}&quot;? Tindakan ini permanen."
                                                data-confirm-title="Konfirmasi Penghapusan"
                                                data-confirm-btn="Ya, Hapus"
                                                data-confirm-danger="1" title="Hapus Permanen">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="space-y-4">
                                    <div class="h-16 w-16 bg-stone-50 rounded-3xl mx-auto flex items-center justify-center text-stone-200">
                                        <i class="fas fa-box-open text-2xl"></i>
                                    </div>
                                    <p class="text-stone-400 font-serif italic">Belum ada koleksi paket yang terdaftar.</p>
                                    <a href="{{ route('admin.packages.create') }}" class="inline-block text-gold-500 text-[10px] font-bold uppercase tracking-widest hover:text-gold-600">Buat Koleksi Pertama</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-luxury.card>
</div>
@endsection
