@extends('layouts.app')

@section('title', 'Kelola Diskon - Administrator')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-1">
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em]">Promosi & Pemasaran</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Kelola <span class="not-italic text-stone-300">Diskon</span></h1>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.discounts.create') }}" 
               class="group flex items-center gap-3 bg-choco-900 text-gold-400 px-6 py-4 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-800 transition-all shadow-xl shadow-choco-900/10 active:scale-95">
                <i class="fas fa-plus text-[12px] group-hover:rotate-90 transition-transform"></i>
                Buat Diskon Baru
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="p-6 rounded-2xl bg-rose-50 text-rose-600 text-sm border border-rose-100">
            <strong>Kesalahan!</strong>
            <ul class="mb-0 mt-2 list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="p-6 rounded-2xl bg-emerald-50 text-emerald-600 text-sm border border-emerald-100 flex items-center gap-3">
            <i class="fas fa-check-circle text-lg"></i> 
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Total Diskon</p>
            <p class="text-2xl font-serif text-choco-900">{{ $discounts->count() }}</p>
        </div>
        <div class="bg-white/50 backdrop-blur-md p-6 rounded-[2rem] border border-stone-100/50 shadow-sm">
            <p class="text-stone-400 text-[9px] font-bold uppercase tracking-widest mb-2">Diskon Aktif</p>
            <p class="text-2xl font-serif text-emerald-600">{{ $discounts->where('is_active', true)->count() }}</p>
        </div>
    </div>

    <!-- Table Section -->
    <x-luxury.card class="overflow-hidden border-stone-100/50 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50/50 border-b border-stone-100">
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Nama Diskon</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Tipe Potongan</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Nilai</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Periode</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400">Paket</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-bold uppercase tracking-widest text-stone-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse ($discounts as $discount)
                        <tr class="group hover:bg-stone-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-choco-900 group-hover:text-gold-600 transition-colors">{{ $discount->name }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs text-stone-600 font-medium">
                                    {{ $discount->type === 'percentage' ? 'Persentase' : 'Nominal Tetap' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs font-bold text-choco-900">
                                    @if ($discount->type === 'percentage')
                                        {{ number_format($discount->value, 0) }}%
                                    @else
                                        Rp {{ number_format($discount->value, 0, ',', '.') }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs text-stone-500 font-light">
                                    {{ $discount->start_date->format('d M Y') }} - 
                                    @if ($discount->end_date)
                                        {{ $discount->end_date->format('d M Y') }}
                                    @else
                                        Tanpa Batas
                                    @endif
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-xs text-stone-500 font-light">
                                    @if ($discount->packages->count() > 0)
                                        {{ $discount->packages->count() }} paket
                                    @else
                                        Semua paket
                                    @endif
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-center">
                                    <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest {{ $discount->isActive() ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-stone-100 text-stone-400 border border-stone-200' }}">
                                        {{ $discount->isActive() ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-end items-center gap-2 text-stone-400">
                                    <a href="{{ route('admin.discounts.show', $discount) }}" 
                                       class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-transparent hover:border-gold-100" title="Lihat">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" 
                                       class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-gold-50 hover:text-gold-600 transition-all border border-transparent hover:border-gold-100" title="Ubah">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="h-9 w-9 flex items-center justify-center rounded-xl hover:bg-rose-50 hover:text-rose-600 transition-all border border-transparent hover:border-rose-100"
                                                data-confirm="Apakah Anda yakin ingin menghapus diskon &quot;{{ $discount->name }}&quot;?"
                                                data-confirm-title="Hapus Diskon"
                                                data-confirm-btn="Ya, Hapus"
                                                data-confirm-danger="1" title="Hapus">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-stone-400 italic">
                                Tidak ada data diskon. <a href="{{ route('admin.discounts.create') }}" class="text-gold-600 font-medium hover:underline">Buat sekarang!</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-luxury.card>

    <div class="mt-4">
        {{ $discounts->links() }}
    </div>
</div>
@endsection
