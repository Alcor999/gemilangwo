@extends('layouts.app')

@section('title', 'Kurasi Paket Baru - Administrator')

@section('content')
<div class="max-w-5xl mx-auto space-y-12 pb-24" x-data="{ 
    features: [''],
    addFeature() { this.features.push('') },
    removeFeature(index) { this.features.splice(index, 1) }
}">
    <!-- Header -->
    <div class="space-y-2">
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.4em]">Administrative Curation</p>
        <h1 class="font-serif text-4xl text-choco-900 italic">Kurasi <span class="not-italic text-stone-300">Paket Baru</span></h1>
    </div>

    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="grid lg:grid-cols-12 gap-12">
        @csrf

        <!-- Main Form Area -->
        <div class="lg:col-span-8 space-y-8">
            <x-luxury.card class="p-10 space-y-8 border-stone-100/50 shadow-sm">
                <div class="space-y-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label for="name" class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Identitas Paket</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full bg-stone-50/50 border border-stone-100 rounded-2xl px-6 py-4 text-sm text-choco-900 placeholder:text-stone-300 focus:ring-2 focus:ring-gold-400/20 focus:border-gold-400 outline-none transition-all"
                               placeholder="e.g. The Royal Eternal Bloom">
                        @error('name') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Filosofi & Deskripsi</label>
                        <textarea id="description" name="description" rows="5" required
                                  class="w-full bg-stone-50/50 border border-stone-100 rounded-2xl px-6 py-4 text-sm text-choco-900 placeholder:text-stone-300 focus:ring-2 focus:ring-gold-400/20 focus:border-gold-400 outline-none transition-all resize-none"
                                  placeholder="Gambarkan eksklusivitas paket ini...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8 pt-8 border-t border-stone-50">
                    <!-- Price -->
                    <div class="space-y-2">
                        <label for="price" class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Investasi Dasar (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-stone-300 text-xs font-bold">IDR</span>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" required
                                   class="w-full bg-stone-50/50 border border-stone-100 rounded-2xl pl-16 pr-6 py-4 text-sm text-choco-900 font-bold focus:ring-2 focus:ring-gold-400/20 focus:border-gold-400 outline-none transition-all">
                        </div>
                        @error('price') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Max Guests -->
                    <div class="space-y-2">
                        <label for="max_guests" class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Kapasitas Tamu</label>
                        <div class="relative">
                            <span class="absolute right-6 top-1/2 -translate-y-1/2 text-stone-300 text-[10px] font-bold uppercase tracking-widest">Tamu</span>
                            <input type="number" id="max_guests" name="max_guests" value="{{ old('max_guests') }}" min="1"
                                   class="w-full bg-stone-50/50 border border-stone-100 rounded-2xl px-6 py-4 text-sm text-choco-900 focus:ring-2 focus:ring-gold-400/20 focus:border-gold-400 outline-none transition-all">
                        </div>
                        @error('max_guests') <p class="text-[10px] text-rose-500 font-bold uppercase mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </x-luxury.card>

            <!-- Dynamic Features -->
            <x-luxury.card class="p-10 space-y-8 border-stone-100/50 shadow-sm">
                <div class="flex items-center justify-between">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Fitur & Layanan Eksklusif</label>
                    <button type="button" @click="addFeature()" 
                            class="text-gold-500 text-[9px] font-bold uppercase tracking-widest hover:text-gold-600 transition-colors flex items-center gap-2">
                        <i class="fas fa-plus-circle text-[11px]"></i> Tambah Fitur
                    </button>
                </div>

                <div class="space-y-4">
                    <template x-for="(feature, index) in features" :key="index">
                        <div class="group relative flex items-center gap-4">
                            <div class="flex-1">
                                <input type="text" name="features[]" x-model="features[index]"
                                       class="w-full bg-stone-50/50 border border-stone-100 rounded-xl px-6 py-3 text-xs text-stone-600 focus:ring-2 focus:ring-gold-400/20 focus:border-gold-400 outline-none transition-all"
                                       placeholder="e.g. Dekorasi Area Pelaminan Exclusive">
                            </div>
                            <button type="button" @click="removeFeature(index)" 
                                    class="h-10 w-10 shrink-0 flex items-center justify-center rounded-xl bg-stone-50 text-stone-300 hover:bg-rose-50 hover:text-rose-500 transition-all opacity-0 group-hover:opacity-100">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                        </div>
                    </template>
                </div>
                @error('features') <p class="text-[10px] text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
            </x-luxury.card>

            <!-- Vendor Requirements -->
            <x-luxury.card class="p-10 space-y-8 border-stone-100/50 shadow-sm">
                <div class="space-y-2">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Persyaratan Kategori Vendor</label>
                    <p class="text-stone-400 text-[10px] font-light italic">Pilih kategori vendor yang wajib ditentukan oleh pelanggan saat pemesanan.</p>
                </div>

                <div class="divide-y divide-stone-50">
                    @forelse(($vendorCategories ?? []) as $vc)
                        <div class="py-6 first:pt-0 last:pb-0 space-y-4" x-data="{ checked: {{ in_array($vc->id, old('vendor_category_ids', [])) ? 'true' : 'false' }} }">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" name="vendor_category_ids[]" value="{{ $vc->id }}" id="vc{{ $vc->id }}" 
                                           x-model="checked"
                                           class="h-5 w-5 rounded-lg border-stone-200 text-gold-500 focus:ring-gold-500/20 transition-all cursor-pointer">
                                    <label for="vc{{ $vc->id }}" class="text-xs font-bold text-choco-900 cursor-pointer select-none">{{ $vc->name }}</label>
                                </div>
                                <span class="text-[9px] font-bold uppercase tracking-widest text-stone-300">{{ $vc->vendors->count() }} Terdaftar</span>
                            </div>

                            <div x-show="checked" x-collapse x-cloak class="pl-9 pt-2">
                                <div class="space-y-2">
                                    <p class="text-[9px] font-bold uppercase tracking-widest text-gold-500/60 mb-2">Vendor Basis Harga (Default)</p>
                                    <select name="default_vendor_ids_{{ $vc->id }}" 
                                            class="w-full bg-white border border-stone-100 rounded-xl px-4 py-3 text-[11px] text-stone-600 focus:ring-2 focus:ring-gold-400/20 focus:border-gold-400 outline-none transition-all shadow-sm">
                                        <option value="">-- Tanpa Vendor Default --</option>
                                        @foreach($vc->vendors as $vendor)
                                            <option value="{{ $vendor->id }}" {{ old('default_vendor_ids_'.$vc->id) == $vendor->id ? 'selected' : '' }}>
                                                {{ $vendor->name }} — Rp {{ number_format($vendor->price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center">
                            <p class="text-[10px] text-stone-300 font-bold uppercase tracking-widest">Belum ada kategori vendor terdefinisi.</p>
                        </div>
                    @endforelse
                </div>
            </x-luxury.card>
        </div>

        <!-- Sidebar / Publishing -->
        <div class="lg:col-span-4 space-y-8">
            <x-luxury.card class="p-8 space-y-8 border-stone-100/50 shadow-sm sticky top-8">
                <!-- Status -->
                <div class="space-y-4">
                    <label for="status" class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Status Publikasi</label>
                    <select id="status" name="status" required
                            class="w-full bg-stone-50/50 border border-stone-100 rounded-2xl px-6 py-4 text-xs text-choco-900 font-bold focus:ring-2 focus:ring-gold-400/20 focus:border-gold-400 outline-none transition-all shadow-sm appearance-none cursor-pointer">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif & Publish</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Draft (Private)</option>
                    </select>
                </div>

                <!-- Featured Image -->
                <div class="space-y-4">
                    <label for="image" class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Media Utama (Cover)</label>
                    <div class="relative group cursor-pointer border-2 border-dashed border-stone-100 rounded-[2rem] p-6 hover:border-gold-300 transition-all text-center">
                        <input type="file" id="image" name="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="space-y-3">
                            <div class="h-12 w-12 bg-stone-50 rounded-2xl mx-auto flex items-center justify-center text-stone-300 group-hover:text-gold-400 transition-colors">
                                <i class="fas fa-cloud-upload-alt text-xl"></i>
                            </div>
                            <p class="text-[9px] font-bold uppercase tracking-widest text-stone-400">Upload Image</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-8 border-t border-stone-50 space-y-3">
                    <button type="submit" 
                            class="w-full flex items-center justify-center py-5 rounded-2xl bg-choco-900 text-gold-400 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-800 transition-all shadow-xl shadow-choco-900/10 active:scale-95">
                        <i class="fas fa-save mr-3"></i> Finalisasi Paket
                    </button>
                    <a href="{{ route('admin.packages.index') }}" 
                       class="w-full flex items-center justify-center py-5 rounded-2xl border border-stone-100 text-stone-400 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-50 transition-all">
                        Batalkan
                    </a>
                </div>
            </x-luxury.card>
        </div>
    </form>
</div>
@endsection
