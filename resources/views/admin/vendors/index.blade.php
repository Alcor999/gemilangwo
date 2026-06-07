@extends('layouts.app')

@section('title', 'Katalog Vendor - Administrator')

@section('content')
<div class="space-y-12 pb-24">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
        <div class="space-y-2">
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.4em]">Vendor Portfolio</p>
            <h1 class="font-serif text-4xl text-choco-900 italic">Katalog <span class="not-italic text-stone-300">Penyedia</span></h1>
        </div>
        
        <x-luxury.button href="{{ route('admin.vendors.create') }}" variant="primary" class="h-12 px-8">
            <i class="fas fa-plus mr-2 text-[10px]"></i>
            Tambah Rekan Vendor
        </x-luxury.button>
    </div>

    <!-- Filters Area -->
    <div class="bg-white/50 backdrop-blur-md p-6 rounded-3xl border border-stone-100 flex flex-wrap items-center gap-6">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 rounded-lg bg-stone-50 flex items-center justify-center text-stone-300">
                <i class="fas fa-filter text-[10px]"></i>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Filter Portfolio</span>
        </div>

        <form action="{{ route('admin.vendors.index') }}" method="GET" class="flex items-center gap-4">
            <select name="category" class="bg-white border border-stone-100 rounded-xl px-4 py-2 text-[11px] font-bold text-choco-900 focus:ring-2 focus:ring-gold-400/20 outline-none transition-all shadow-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="text-[10px] font-bold uppercase tracking-widest text-gold-500 hover:text-gold-600 transition-colors">Apply</button>
        </form>
    </div>

    <!-- Main Content Table -->
    <x-luxury.card class="overflow-hidden border-stone-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-stone-50/50 border-b border-stone-100">
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400">Identitas Vendor</th>
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400">Klasifikasi</th>
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400">Baseline Harga</th>
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400">Status</th>
                        <th class="px-8 py-6 text-[9px] font-bold uppercase tracking-[0.3em] text-stone-400 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($vendors as $v)
                        <tr class="group hover:bg-stone-50/30 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-5">
                                    <div class="h-14 w-14 rounded-2xl overflow-hidden border border-stone-100 bg-stone-50 shadow-sm flex-shrink-0">
                                        @if($v->image)
                                            <img src="{{ asset('storage/' . $v->image) }}" alt="" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-stone-200">
                                                <i class="fas fa-store text-xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-bold text-choco-900 group-hover:text-gold-600 transition-colors">{{ $v->name }}</p>
                                        <p class="text-[10px] text-stone-400 font-light italic truncate max-w-[200px]">{{ $v->description ?? 'No dossier' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest bg-stone-50 text-stone-500 border border-stone-100">
                                    {{ $v->vendorCategory->name }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-xs font-bold text-choco-900">Rp {{ number_format($v->price, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border {{ $v->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-stone-50 text-stone-400 border-stone-100' }}">
                                    {{ $v->is_active ? 'Active' : 'Archived' }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.vendors.edit', $v) }}" 
                                       class="h-9 w-9 flex items-center justify-center rounded-xl bg-stone-50 text-stone-400 hover:bg-gold-500 hover:text-white transition-all border border-stone-100 hover:border-gold-500" 
                                       title="Edit Vendor">
                                        <i class="fas fa-pen text-[10px]"></i>
                                    </a>
                                    <form action="{{ route('admin.vendors.destroy', $v) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="h-9 w-9 flex items-center justify-center rounded-xl bg-stone-50 text-stone-400 hover:bg-rose-500 hover:text-white transition-all border border-stone-100 hover:border-rose-500"
                                                data-confirm="Hapus data vendor {{ $v->name }} secara permanen?"
                                                data-confirm-title="Hapus Vendor"
                                                data-confirm-btn="Hapus Permanen"
                                                data-confirm-danger="1">
                                            <i class="fas fa-trash-alt text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="space-y-4">
                                    <div class="h-16 w-16 bg-stone-50 rounded-3xl mx-auto flex items-center justify-center text-stone-200">
                                        <i class="fas fa-store-slash text-2xl"></i>
                                    </div>
                                    <p class="text-stone-400 font-serif italic text-sm">Portfolio vendor masih kosong. Mulai kurasi rekanan baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginasi -->
        @if($vendors->hasPages())
            <div class="px-8 py-6 border-t border-stone-50 bg-stone-50/30">
                {{ $vendors->withQueryString()->links() }}
            </div>
        @endif
    </x-luxury.card>
</div>
@endsection
