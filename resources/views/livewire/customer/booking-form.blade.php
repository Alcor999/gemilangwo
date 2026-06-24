<div class="max-w-4xl mx-auto py-12 px-6">
    {{-- Header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl font-serif text-brown-950 mb-2">Rencanakan Momen Bahagia Anda</h1>
        <p class="text-brown-600">Lengkapi detail di bawah ini untuk membuat pesanan pernikahan impian.</p>
    </div>

    {{-- Progress Bar --}}
    <div class="mb-12 relative">
        <div class="flex justify-between items-center relative z-10">
            @for ($i = 1; $i <= 5; $i++)
                <div class="flex flex-col items-center">
                    <div @class([
                        'w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all duration-500',
                        'bg-gold-500 text-black shadow-lg scale-110' => $step === $i,
                        'bg-brown-200 text-brown-500' => $step < $i,
                        'bg-brown-800 text-gold-500' => $step > $i,
                    ])>
                        @if ($step > $i) <i class="fas fa-check"></i> @else {{ $i }} @endif
                    </div>
                    <span class="text-xs mt-2 font-medium {{ $step === $i ? 'text-gold-700' : 'text-brown-400' }}">
                        {{ ['Pilih Paket', 'Detail Acara', 'Kustom Vendor', 'Skema Bayar', 'Konfirmasi'][$i-1] }}
                    </span>
                </div>
            @endfor
        </div>
        <div class="absolute top-5 left-0 w-full h-1 bg-brown-100 -z-0">
            <div class="h-full bg-gold-500 transition-all duration-500" style="width: {{ ($step - 1) / 4 * 100 }}%"></div>
        </div>
    </div>

    {{-- Form Content --}}
    <div class="bg-white rounded-3xl shadow-2xl border border-brown-100 overflow-hidden">
        <div class="p-8 md:p-12">
            
            {{-- Step 1: Package Selection --}}
            @if ($step === 1)
                <div class="animate-fade-in">
                    <h2 class="text-2xl font-bold text-brown-900 mb-8 flex items-center">
                        <span class="w-8 h-8 bg-gold-500 text-black rounded-lg flex items-center justify-center mr-3 text-sm">1</span>
                        Pilih Paket Wedding
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($packages as $pkg)
                            <label class="relative cursor-pointer group">
                                <input type="radio" wire:model.live="package_id" value="{{ $pkg->id }}" class="peer sr-only">
                                <div class="p-6 border-2 rounded-2xl transition-all peer-checked:border-gold-500 peer-checked:bg-gold-50/50 hover:border-gold-300">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="font-bold text-lg text-brown-900">{{ $pkg->name }}</h3>
                                        <div class="w-5 h-5 rounded-full border-2 border-brown-200 peer-checked:border-gold-500 flex items-center justify-center">
                                            <div class="w-2.5 h-2.5 rounded-full bg-gold-500 scale-0 peer-checked:scale-100 transition-transform"></div>
                                        </div>
                                    </div>
                                    <div class="text-2xl font-serif text-gold-600 mb-4">Rp {{ number_format($pkg->price, 0, ',', '.') }}</div>
                                    <ul class="space-y-2 mb-4">
                                        @foreach(array_slice(is_array($pkg->features) ? $pkg->features : json_decode($pkg->features, true) ?? [], 0, 3) as $feature)
                                            <li class="text-xs text-brown-600 flex items-center">
                                                <i class="fas fa-check text-gold-500 mr-2"></i> {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="text-xs text-brown-400">Hingga {{ $pkg->max_guests }} Tamu</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('package_id') <p class="text-red-500 text-xs mt-4">{{ $message }}</p> @enderror
                </div>
            @endif

            {{-- Step 2: Event Details --}}
            @if ($step === 2)
                <div class="animate-fade-in">
                    <h2 class="text-2xl font-bold text-brown-900 mb-8 flex items-center">
                        <span class="w-8 h-8 bg-gold-500 text-black rounded-lg flex items-center justify-center mr-3 text-sm">2</span>
                        Detail Acara & Tamu
                    </h2>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-brown-700 mb-2">Tanggal Acara *</label>
                                <input type="date" wire:model="event_date" class="w-full px-4 py-3 rounded-xl border border-brown-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-200 outline-none transition-all">
                                @error('event_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-brown-700 mb-2">Jumlah Tamu *</label>
                                <input type="number" wire:model.live.debounce.500ms="guest_count" placeholder="Contoh: 500" class="w-full px-4 py-3 rounded-xl border border-brown-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-200 outline-none transition-all">
                                <p class="text-[10px] text-brown-400 mt-1">Kapasitas dasar paket ini: {{ $selectedPackageModel?->max_guests }}</p>
                                @error('guest_count') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @if($extra_guest_charge > 0)
                                    <div class="mt-2 p-3 bg-amber-50 border border-amber-200 rounded-xl animate-fade-in">
                                        <p class="text-xs text-amber-700 font-bold"><i class="fas fa-exclamation-triangle mr-1"></i> Biaya Tamu Tambahan</p>
                                        <p class="text-[10px] text-amber-600 mt-1">Jumlah tamu melebihi {{ config('gemilang.guests.threshold', 1000) }}. Dikenakan biaya tambahan Rp {{ number_format(config('gemilang.guests.charge_per_unit', 1000000), 0, ',', '.') }} per {{ config('gemilang.guests.unit_size', 100) }} tamu tambahan.</p>
                                        <p class="text-xs font-bold text-amber-800 mt-1">Charge: Rp {{ number_format($extra_guest_charge, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-brown-700 mb-2">Lokasi Acara *</label>
                            <input type="text" wire:model="event_location" placeholder="Nama Gedung / Ballroom / Alamat" class="w-full px-4 py-3 rounded-xl border border-brown-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-200 outline-none transition-all">
                            @error('event_location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-brown-700 mb-2">Permintaan Khusus (Opsional)</label>
                            <textarea wire:model="special_request" rows="4" placeholder="Ceritakan keinginan khusus Anda..." class="w-full px-4 py-3 rounded-xl border border-brown-200 focus:border-gold-500 focus:ring-2 focus:ring-gold-200 outline-none transition-all"></textarea>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Step 3: Vendor Customization --}}
            @if ($step === 3)
                <div class="animate-fade-in">
                    <h2 class="text-2xl font-bold text-brown-900 mb-2 flex items-center">
                        <span class="w-8 h-8 bg-gold-500 text-black rounded-lg flex items-center justify-center mr-3 text-sm">3</span>
                        Kustomisasi Vendor
                    </h2>
                    <p class="text-brown-500 mb-8 text-sm italic">Sesuaikan layanan vendor untuk hasil yang lebih personal. Harga akan menyesuaikan pilihan Anda.</p>

                    <div class="space-y-8">
                        @foreach($selectedPackageModel?->requiredVendorCategories ?? [] as $category)
                            <div class="p-6 bg-brown-50/50 rounded-2xl border border-brown-100">
                                <label class="block text-lg font-bold text-brown-900 mb-4">{{ $category->name }}</label>
                                <div class="grid grid-cols-1 gap-4">
                                    @php 
                                        $defaultVendorId = $category->pivot->default_vendor_id;
                                        $defaultVendor = $category->vendors->firstWhere('id', $defaultVendorId);
                                        $defaultPrice = $defaultVendor?->price ?? 0;
                                    @endphp
                                    @foreach($category->vendors as $vendor)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" wire:model.live="selected_vendors.{{ $category->id }}" value="{{ $vendor->id }}" class="peer sr-only">
                                            <div class="p-4 bg-white border-2 rounded-xl transition-all peer-checked:border-gold-500 peer-checked:ring-4 peer-checked:ring-gold-50 hover:border-gold-200 flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-12 h-12 rounded-lg bg-brown-100 flex items-center justify-center text-brown-400">
                                                        <i class="fas fa-store"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-brown-900">{{ $vendor->name }}</div>
                                                        <div class="text-xs text-brown-500">
                                                            @if($vendor->id == $defaultVendorId)
                                                                <span class="text-gold-600 font-bold">Terintegrasi dalam Paket</span>
                                                            @else
                                                                @php $diff = $vendor->price - $defaultPrice; @endphp
                                                                {{ $diff > 0 ? '+ Rp ' . number_format($diff, 0, ',', '.') : '- Rp ' . number_format(abs($diff), 0, ',', '.') }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="w-5 h-5 rounded-full border-2 border-brown-200 peer-checked:border-gold-500 flex items-center justify-center">
                                                    <div class="w-2.5 h-2.5 rounded-full bg-gold-500 scale-0 peer-checked:scale-100 transition-transform"></div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Step 4: Payment Scheme --}}
            @if ($step === 4)
                <div class="animate-fade-in">
                    <h2 class="text-2xl font-bold text-brown-900 mb-2 flex items-center">
                        <span class="w-8 h-8 bg-gold-500 text-black rounded-lg flex items-center justify-center mr-3 text-sm">4</span>
                        Pilih Skema Pembayaran
                    </h2>
                    <p class="text-brown-500 mb-8 text-sm italic">Total estimasi: <strong class="text-gold-600">Rp {{ number_format($total_price, 0, ',', '.') }}</strong></p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        @foreach([
                            'full_payment' => ['icon' => 'fa-coins', 'title' => 'Bayar Lunas', 'desc' => '100% lunas di awal'],
                            'dp_20' => ['icon' => 'fa-hourglass-start', 'title' => 'DP 20%', 'desc' => 'DP 20%, sisa H-14 acara'],
                            'dp_30' => ['icon' => 'fa-hourglass-start', 'title' => 'DP 30%', 'desc' => 'DP 30%, sisa H-14 acara'],
                            'dp_40' => ['icon' => 'fa-hourglass-half', 'title' => 'DP 40%', 'desc' => 'DP 40%, sisa H-14 acara'],
                            'dp_50' => ['icon' => 'fa-hourglass-half', 'title' => 'DP 50%', 'desc' => 'DP 50%, sisa H-14 acara'],
                            'installment_3x' => ['icon' => 'fa-calendar-alt', 'title' => 'Cicilan 3x', 'desc' => '40% + 30% + 30%'],
                            'installment_5x' => ['icon' => 'fa-calendar-check', 'title' => 'Cicilan 5x', 'desc' => '30%+20%+20%+15%+15%'],
                        ] as $code => $scheme)
                            <label class="relative cursor-pointer group">
                                <input type="radio" wire:model.live="payment_scheme" value="{{ $code }}" class="peer sr-only">
                                <div class="p-5 border-2 rounded-2xl transition-all peer-checked:border-gold-500 peer-checked:bg-gold-50/50 hover:border-gold-300 text-center">
                                    <div class="text-2xl text-brown-400 peer-checked:text-gold-600 mb-2"><i class="fas {{ $scheme['icon'] }}"></i></div>
                                    <div class="font-bold text-brown-900">{{ $scheme['title'] }}</div>
                                    <div class="text-xs text-brown-500 mt-1">{{ $scheme['desc'] }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('payment_scheme') <p class="text-red-500 text-xs mb-4">{{ $message }}</p> @enderror

                    @if(count($this->paymentBreakdown) > 0)
                        <div class="bg-brown-50 rounded-2xl p-6 border border-brown-100">
                            <h6 class="font-bold text-brown-900 mb-4 text-sm">Rincian Pembayaran</h6>
                            <p class="text-xs text-brown-500 italic mb-4">Anda dapat menyesuaikan tanggal jatuh tempo cicilan (maksimal H-4 acara).</p>
                            <div class="space-y-4">
                                @foreach($this->paymentBreakdown as $index => $item)
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center text-sm gap-2">
                                        <span class="text-brown-700 font-bold">{{ $item['label'] }}</span>
                                        <div class="flex items-center gap-4 justify-between sm:justify-end">
                                            <div class="font-bold text-brown-900 w-28 text-left sm:text-right">Rp {{ number_format($item['amount'], 0, ',', '.') }}</div>
                                            @if($item['due_date'] && $index > 0)
                                                <input type="date" wire:model.live="custom_due_dates.{{ $index }}" 
                                                    min="{{ now()->addDay()->format('Y-m-d') }}" 
                                                    max="{{ \Carbon\Carbon::parse($event_date)->subDays(4)->format('Y-m-d') }}"
                                                    value="{{ $item['due_date']->format('Y-m-d') }}"
                                                    class="px-2 py-1 text-xs rounded border border-brown-200 focus:border-gold-500 focus:ring-1 focus:ring-gold-500 outline-none w-32 bg-white text-brown-700">
                                            @else
                                                <div class="text-[10px] text-brown-500 bg-brown-200/50 px-2 py-1 rounded w-32 text-center font-bold">
                                                    {{ $item['due_date'] ? $item['due_date']->format('d M Y') : 'Segera' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @error('custom_due_dates.'.$index) <p class="text-red-500 text-[10px] text-right">{{ $message }}</p> @enderror
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Step 5: Summary --}}
            @if ($step === 5)
                <div class="animate-fade-in text-center py-4">
                    <div class="w-20 h-20 bg-gold-100 rounded-full flex items-center justify-center mx-auto mb-6 text-gold-600 text-3xl">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h2 class="text-3xl font-serif text-brown-950 mb-8">Ringkasan Pesanan</h2>

                    <div class="bg-brown-50 rounded-3xl p-8 mb-8 text-left border border-brown-100">
                        <div class="flex justify-between items-start mb-6 pb-6 border-bottom border-brown-200">
                            <div>
                                <div class="text-xs text-brown-400 uppercase tracking-widest mb-1">Paket yang Dipilih</div>
                                <div class="text-xl font-bold text-brown-900">{{ $selectedPackageModel?->name }}</div>
                            </div>
                            <div class="text-xl font-serif text-gold-600">Rp {{ number_format($selectedPackageModel?->price, 0, ',', '.') }}</div>
                        </div>

                        <div class="grid grid-cols-2 gap-y-4 mb-8">
                            <div>
                                <div class="text-xs text-brown-400 mb-1">Tanggal Acara</div>
                                <div class="font-bold text-brown-800">{{ \Carbon\Carbon::parse($event_date)->translatedFormat('d F Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-brown-400 mb-1">Jumlah Tamu</div>
                                <div class="font-bold text-brown-800">{{ $guest_count }} Tamu</div>
                            </div>
                            <div class="col-span-2">
                                <div class="text-xs text-brown-400 mb-1">Lokasi</div>
                                <div class="font-bold text-brown-800">{{ $event_location }}</div>
                            </div>
                        </div>

                        <div class="space-y-2 border-t border-brown-200 pt-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-brown-600">Harga Dasar Paket</span>
                                <span class="text-brown-900 fw-medium">Rp {{ number_format($selectedPackageModel?->price, 0, ',', '.') }}</span>
                            </div>
                            
                            @php $vendorAdjustment = $this->calculateVendorAdjustment(); @endphp
                            @if($vendorAdjustment !== 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-brown-600">Penyesuaian Vendor</span>
                                    <span class="{{ $vendorAdjustment > 0 ? 'text-red-500' : 'text-green-600' }} font-bold">
                                        {{ $vendorAdjustment > 0 ? '+' : '-' }} Rp {{ number_format(abs($vendorAdjustment), 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif

                            @if($extra_guest_charge > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-brown-600">Biaya Tambahan Tamu (>{{ config('gemilang.guests.threshold', 1000) }})</span>
                                    <span class="text-red-500 font-bold">
                                        + Rp {{ number_format($extra_guest_charge, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between text-sm mt-4">
                                <span class="text-brown-600">Skema Pembayaran</span>
                                <span class="text-brown-900 fw-medium">
                                    @switch($payment_scheme)
                                        @case('dp_20') DP 20% + Pelunasan @break
                                        @case('dp_30') DP 30% + Pelunasan @break
                                        @case('dp_40') DP 40% + Pelunasan @break
                                        @case('dp_50') DP 50% + Pelunasan @break
                                        @case('installment_3x') Cicilan 3x @break
                                        @case('installment_5x') Cicilan 5x @break
                                        @default Bayar Lunas @break
                                    @endswitch
                                </span>
                            </div>

                            <div class="flex justify-between text-xl font-serif mt-6 pt-6 border-t-2 border-dashed border-brown-300">
                                <span class="text-brown-900">Total Estimasi</span>
                                <span class="text-gold-600 font-bold">Rp {{ number_format($total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-brown-400 px-6">Dengan menekan tombol "Konfirmasi & Bayar", Anda menyetujui syarat & ketentuan layanan Gemilang Wedding Organizer.</p>
                </div>
            @endif

        </div>

        {{-- Footer Actions --}}
        <div class="bg-brown-50/50 p-8 flex justify-between items-center border-t border-brown-100">
            @if ($step > 1)
                <button wire:click="previousStep" class="px-8 py-3 text-brown-600 font-bold hover:text-brown-950 transition-all flex items-center gap-2">
                    <i class="fas fa-arrow-left text-sm"></i> Kembali
                </button>
            @else
                <div></div>
            @endif

            @if ($step < 5)
                <button wire:click="nextStep" class="px-10 py-4 bg-gold-500 text-black font-bold rounded-2xl hover:bg-gold-400 transition-all shadow-lg flex items-center gap-3 group">
                    Lanjut <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                </button>
            @else
                <button wire:click="submit" wire:loading.attr="disabled" class="px-12 py-4 bg-brown-900 text-gold-500 font-bold rounded-2xl hover:bg-black transition-all shadow-2xl flex items-center gap-3">
                    <span wire:loading.remove>Konfirmasi & Bayar</span>
                    <span wire:loading><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
                </button>
            @endif
        </div>
    </div>
</div>
