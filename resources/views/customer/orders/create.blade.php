@extends('layouts.app')

@section('title', 'Buat Pesanan Baru - Gemilang WO')

@section('content')
@php
    $coLabel = 'block text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-2';
    $coInput = 'w-full px-4 py-3 rounded-xl border border-stone-200 bg-white text-choco-900 text-sm focus:border-gold-400 focus:ring-2 focus:ring-gold-100 outline-none transition-all';
    $coSelect = $coInput . ' appearance-none cursor-pointer';
    $coTextarea = $coInput . ' resize-none min-h-[100px]';
@endphp

<div class="space-y-8 pb-16 max-w-4xl mx-auto">
    <div>
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Booking Experience</p>
        <h1 class="font-serif text-4xl text-choco-900 leading-tight">Buat <span class="italic text-stone-400">Pesanan Wedding</span></h1>
        <p class="text-stone-400 text-sm mt-2">Lengkapi detail acara dan pilih skema pembayaran yang sesuai.</p>
    </div>

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 text-rose-600 text-sm border border-rose-100">{{ session('error') }}</div>
    @endif

    <x-luxury.card :padding="'p-8'" class="border-stone-100">
        <form action="{{ route('customer.orders.store') }}" method="POST" id="orderForm" class="space-y-8">
            @csrf

            {{-- Paket --}}
            <div>
                <label for="package_id" class="{{ $coLabel }}">Pilih Paket *</label>
                <select id="package_id" name="package_id" required class="{{ $coSelect }} @error('package_id') border-rose-300 ring-rose-100 @enderror">
                    <option value="">— Pilih paket wedding —</option>
                    @foreach($packages as $pkg)
                        @php
                            $discount = $pkg->getActiveDiscount();
                            $displayPrice = $discount ? $discount->getDiscountedPrice($pkg->price) : $pkg->price;
                        @endphp
                        <option value="{{ $pkg->id }}"
                            data-price="{{ $pkg->price }}"
                            data-discounted="{{ $displayPrice }}"
                            data-has-vendors="{{ $pkg->hasVendorSelection() ? '1' : '0' }}"
                            {{ old('package_id', request('package_id')) == $pkg->id ? 'selected' : '' }}>
                            {{ $pkg->name }} — Rp {{ number_format($displayPrice, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @error('package_id')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- Vendor --}}
            <div id="vendorSelectionSection" class="hidden space-y-4 p-6 rounded-2xl bg-stone-50 border border-stone-100">
                <div>
                    <h3 class="font-serif text-lg text-choco-900 mb-1"><i class="fas fa-store text-gold-500 mr-2"></i>Pilih Vendor</h3>
                    <p class="text-xs text-stone-500 leading-relaxed">Vendor <strong>(Default)</strong> sudah termasuk harga paket. Pilihan unggulan lain menyesuaikan total pesanan.</p>
                </div>
                <div id="vendorCategoriesContainer" class="space-y-4"></div>
                <div id="vendorTotalPreview" class="hidden p-4 rounded-xl bg-gold-50/50 border border-gold-100 text-sm">
                    <span class="text-stone-500">Penyesuaian Biaya Vendor:</span>
                    <strong id="vendorTotalAmount" class="ml-1 text-choco-900">+ Rp 0</strong>
                </div>
            </div>

            {{-- Detail Acara --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="event_date" class="{{ $coLabel }}">Tanggal Acara *</label>
                    <input type="date" id="event_date" name="event_date" value="{{ old('event_date') }}" required
                        class="{{ $coInput }} @error('event_date') border-rose-300 @enderror">
                    @error('event_date')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="guest_count" class="{{ $coLabel }}">Jumlah Tamu *</label>
                    <input type="number" id="guest_count" name="guest_count" min="1" value="{{ old('guest_count') }}" required
                        class="{{ $coInput }} @error('guest_count') border-rose-300 @enderror">
                    @error('guest_count')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-2">
                    <label for="event_location" class="{{ $coLabel }}">Lokasi Acara *</label>
                    <input type="text" id="event_location" name="event_location" placeholder="Nama venue / ballroom"
                        value="{{ old('event_location') }}" required
                        class="{{ $coInput }} @error('event_location') border-rose-300 @enderror">
                    @error('event_location')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label for="special_request" class="{{ $coLabel }}">Permintaan Khusus (Opsional)</label>
                <textarea id="special_request" name="special_request" rows="4"
                    placeholder="Beritahu kami tentang kebutuhan khusus..."
                    class="{{ $coTextarea }}">{{ old('special_request') }}</textarea>
            </div>

            {{-- Skema Pembayaran --}}
            <div>
                <label class="{{ $coLabel }} mb-4"><i class="fas fa-credit-card text-gold-500 mr-1"></i> Skema Pembayaran *</label>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach([
                        'full_payment' => ['icon' => 'fa-coins', 'title' => 'Bayar Lunas', 'desc' => '100% lunas di awal'],
                        'dp_20' => ['icon' => 'fa-hourglass-start', 'title' => 'DP 20%', 'desc' => 'DP 20%, sisa H-14'],
                        'dp_30' => ['icon' => 'fa-hourglass-start', 'title' => 'DP 30%', 'desc' => 'DP 30%, sisa H-14'],
                        'dp_40' => ['icon' => 'fa-hourglass-half', 'title' => 'DP 40%', 'desc' => 'DP 40%, sisa H-14'],
                        'dp_50' => ['icon' => 'fa-hourglass-half', 'title' => 'DP 50%', 'desc' => 'DP 50%, sisa H-14'],
                        'installment_3x' => ['icon' => 'fa-calendar-alt', 'title' => 'Cicilan 3x', 'desc' => '40% + 30% + 30%'],
                        'installment_5x' => ['icon' => 'fa-calendar-check', 'title' => 'Cicilan 5x', 'desc' => '30%+20%+20%+15%+15%'],
                    ] as $code => $scheme)
                        <div class="payment-scheme-card cursor-pointer rounded-2xl border-2 border-stone-200 p-4 text-center transition-all hover:border-gold-400 hover:shadow-md {{ $code === 'full_payment' ? 'active border-gold-400 bg-gold-50/50 shadow-sm' : '' }}"
                            data-scheme="{{ $code }}">
                            <div class="text-2xl text-stone-400 mb-2 scheme-icon"><i class="fas {{ $scheme['icon'] }}"></i></div>
                            <h6 class="text-sm font-bold text-choco-900 mb-1">{{ $scheme['title'] }}</h6>
                            <p class="text-[10px] text-stone-400">{{ $scheme['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="payment_scheme" id="payment_scheme" value="{{ old('payment_scheme', 'full_payment') }}">
                @error('payment_scheme')<p class="text-rose-500 text-xs mt-2">{{ $message }}</p>@enderror

                <div id="breakdownPreviewCard" class="hidden mt-4 p-5 rounded-2xl bg-stone-50 border border-stone-100">
                    <h6 class="font-serif text-sm text-choco-900 mb-3"><i class="fas fa-clipboard-list text-gold-500 mr-2"></i>Rincian Skema Pembayaran</h6>
                    <div id="breakdownList" class="text-sm"></div>
                </div>
            </div>

            {{-- Total --}}
            <div id="totalPreview" class="p-5 rounded-2xl bg-gradient-to-r from-gold-50 to-amber-50 border border-gold-100">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gold-600 mb-1">Total Estimasi Pembayaran</p>
                <p class="text-2xl font-serif font-bold text-choco-900">Rp <span id="totalAmount">0</span></p>
            </div>

            <div class="flex flex-wrap gap-3 pt-4 border-t border-stone-100">
                <x-luxury.button type="submit" variant="primary" size="md">
                    <i class="fas fa-check mr-2"></i> Buat Pesanan & Bayar
                </x-luxury.button>
                <x-luxury.button href="{{ route('customer.packages.index') }}" variant="ghost" size="md">Batal</x-luxury.button>
            </div>
        </form>
    </x-luxury.card>
</div>

@php
    $packagesJson = $packages->map(function($p) {
        return [
            'id' => $p->id,
            'price' => (float) $p->price,
            'discounted' => (float) $p->getDiscountedPrice(),
            'requiredVendorCategories' => $p->requiredVendorCategories->map(function($vc) {
                $defaultVendorId = $vc->pivot->default_vendor_id;
                $defaultVendor = $vc->vendors->firstWhere('id', $defaultVendorId);
                $defaultPrice = $defaultVendor ? (float) $defaultVendor->price : 0;
                return [
                    'id' => $vc->id,
                    'name' => $vc->name,
                    'has_default' => !is_null($defaultVendorId),
                    'vendors' => $vc->vendors->map(function($v) use ($defaultVendorId, $defaultPrice) {
                        return [
                            'id' => $v->id,
                            'name' => $v->name,
                            'price' => (float) $v->price,
                            'is_default' => ($v->id == $defaultVendorId),
                            'diff' => (!is_null($defaultVendorId)) ? ((float) $v->price - $defaultPrice) : (float) $v->price,
                        ];
                    })->values()
                ];
            })->values()
        ];
    });
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    const packages = @json($packagesJson);
    const inputClass = @json($coSelect);
    const labelClass = @json($coLabel);

    const packageSelect = document.getElementById('package_id');
    const vendorSection = document.getElementById('vendorSelectionSection');
    const vendorContainer = document.getElementById('vendorCategoriesContainer');
    const vendorTotalPreview = document.getElementById('vendorTotalPreview');
    const vendorTotalAmount = document.getElementById('vendorTotalAmount');
    const totalAmount = document.getElementById('totalAmount');

    function getSelectedPackage() {
        return packages.find(p => p.id === parseInt(packageSelect.value));
    }

    function renderVendorSelection(pkg) {
        vendorContainer.innerHTML = '';
        if (!pkg?.requiredVendorCategories?.length) {
            vendorSection.classList.add('hidden');
            updateTotal(pkg ? pkg.discounted : 0, 0);
            return;
        }
        vendorSection.classList.remove('hidden');
        pkg.requiredVendorCategories.forEach(cat => {
            const div = document.createElement('div');
            div.innerHTML = `
                <label class="${labelClass}">${cat.name} *</label>
                <select class="${inputClass} vendor-select" name="vendors[${cat.id}]" required>
                    <option value="">— Pilih vendor —</option>
                    ${cat.vendors.map(v => {
                        let label = v.name;
                        if (v.is_default) label += ' (Default — Termasuk Paket)';
                        else if (cat.has_default) {
                            if (v.diff > 0) label += ` (+ Rp ${v.diff.toLocaleString('id-ID')})`;
                            else if (v.diff < 0) label += ` (- Rp ${Math.abs(v.diff).toLocaleString('id-ID')})`;
                        } else label += ` (+ Rp ${v.price.toLocaleString('id-ID')})`;
                        return `<option value="${v.id}" data-diff="${v.diff}">${label}</option>`;
                    }).join('')}
                </select>`;
            vendorContainer.appendChild(div);
            div.querySelector('.vendor-select').addEventListener('change', updateVendorTotal);
        });
        vendorTotalPreview.classList.remove('hidden');
        updateVendorTotal();
    }

    function updateVendorTotal() {
        const pkg = getSelectedPackage();
        if (!pkg) return;
        let diffSum = 0;
        document.querySelectorAll('.vendor-select').forEach(sel => {
            const opt = sel.options[sel.selectedIndex];
            if (opt?.value) diffSum += parseFloat(opt.dataset.diff || 0);
        });
        vendorTotalAmount.textContent = (diffSum >= 0 ? '+ Rp ' : '- Rp ') + Math.abs(diffSum).toLocaleString('id-ID');
        vendorTotalAmount.className = diffSum >= 0 ? 'ml-1 text-rose-600 font-bold' : 'ml-1 text-emerald-600 font-bold';
        updateTotal(pkg.discounted, diffSum);
    }

    const schemeCards = document.querySelectorAll('.payment-scheme-card');
    const schemeInput = document.getElementById('payment_scheme');
    const breakdownCard = document.getElementById('breakdownPreviewCard');
    const breakdownList = document.getElementById('breakdownList');
    const eventDateInput = document.getElementById('event_date');

    schemeCards.forEach(card => {
        card.addEventListener('click', function() {
            schemeCards.forEach(c => c.classList.remove('active', 'border-gold-400', 'bg-gold-50/50', 'shadow-sm'));
            this.classList.add('active', 'border-gold-400', 'bg-gold-50/50', 'shadow-sm');
            schemeInput.value = this.dataset.scheme;
            updatePaymentBreakdown();
        });
    });
    eventDateInput.addEventListener('change', updatePaymentBreakdown);

    function updatePaymentBreakdown() {
        const pkg = getSelectedPackage();
        if (!pkg) { breakdownCard.classList.add('hidden'); return; }
        let diffSum = 0;
        document.querySelectorAll('.vendor-select').forEach(sel => {
            const opt = sel.options[sel.selectedIndex];
            if (opt?.value) diffSum += parseFloat(opt.dataset.diff || 0);
        });
        const total = pkg.discounted + diffSum;
        const scheme = schemeInput.value;
        let eventDate = eventDateInput.value ? new Date(eventDateInput.value) : null;
        let items = [];

        if (scheme === 'full_payment') {
            items.push({ label: 'Lunas Penuh (100%)', amount: total, date: 'Saat checkout' });
        } else if (scheme === 'dp_20') {
            items.push({ label: 'DP 1 (20%)', amount: total * 0.2, date: 'Saat checkout' });
            items.push({ label: 'Pelunasan (80%)', amount: total * 0.8, date: fmtDate(eventDate, 14) });
        } else if (scheme === 'dp_30') {
            items.push({ label: 'DP 1 (30%)', amount: total * 0.3, date: 'Saat checkout' });
            items.push({ label: 'Pelunasan (70%)', amount: total * 0.7, date: fmtDate(eventDate, 14) });
        } else if (scheme === 'dp_40') {
            items.push({ label: 'DP 1 (40%)', amount: total * 0.4, date: 'Saat checkout' });
            items.push({ label: 'Pelunasan (60%)', amount: total * 0.6, date: fmtDate(eventDate, 14) });
        } else if (scheme === 'dp_50') {
            items.push({ label: 'DP 1 (50%)', amount: total * 0.5, date: 'Saat checkout' });
            items.push({ label: 'Pelunasan (50%)', amount: total * 0.5, date: fmtDate(eventDate, 14) });
        } else if (scheme === 'installment_3x') {
            items.push({ label: 'Cicilan 1 (40%)', amount: total * 0.4, date: 'Saat checkout' });
            items.push({ label: 'Cicilan 2 (30%)', amount: total * 0.3, date: fmtDate(eventDate, 30) });
            items.push({ label: 'Cicilan 3 (30%)', amount: total * 0.3, date: fmtDate(eventDate, 14) });
        } else if (scheme === 'installment_5x') {
            items.push({ label: 'Cicilan 1 (30%)', amount: total * 0.30, date: 'Saat checkout' });
            items.push({ label: 'Cicilan 2 (20%)', amount: total * 0.20, date: fmtDate(eventDate, 60) });
            items.push({ label: 'Cicilan 3 (20%)', amount: total * 0.20, date: fmtDate(eventDate, 45) });
            items.push({ label: 'Cicilan 4 (15%)', amount: total * 0.15, date: fmtDate(eventDate, 30) });
            items.push({ label: 'Cicilan 5 (15%)', amount: total * 0.15, date: fmtDate(eventDate, 14) });
        }

        breakdownList.innerHTML = items.map((item, i) => `
            <div class="flex justify-between items-center py-2 ${i > 0 ? 'border-t border-stone-100' : ''}">
                <span class="text-choco-900 ${i === 0 ? 'font-bold' : ''}">${item.label}</span>
                <div class="text-right">
                    <div class="font-bold text-choco-900">Rp ${Math.round(item.amount).toLocaleString('id-ID')}</div>
                    <div class="text-[10px] text-stone-400">${item.date}</div>
                </div>
            </div>`).join('');
        breakdownCard.classList.remove('hidden');
    }

    function fmtDate(d, daysBefore) {
        if (!d) return `H-${daysBefore} sebelum acara`;
        const copy = new Date(d);
        copy.setDate(copy.getDate() - daysBefore);
        return copy.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    }

    function updateTotal(base, vendorSum) {
        totalAmount.textContent = (base + (vendorSum || 0)).toLocaleString('id-ID');
        updatePaymentBreakdown();
    }

    packageSelect.addEventListener('change', () => renderVendorSelection(getSelectedPackage()));
    renderVendorSelection(getSelectedPackage());

    @if(old('payment_scheme'))
        schemeInput.value = @json(old('payment_scheme'));
        schemeCards.forEach(c => {
            const active = c.dataset.scheme === schemeInput.value;
            c.classList.toggle('active', active);
            c.classList.toggle('border-gold-400', active);
            c.classList.toggle('bg-gold-50/50', active);
        });
        updatePaymentBreakdown();
    @endif

    @if(old('vendors'))
        setTimeout(() => {
            Object.entries(@json(old('vendors'))).forEach(([catId, vendorId]) => {
                const sel = document.querySelector(`select[name="vendors[${catId}]"]`);
                if (sel && vendorId) sel.value = vendorId;
            });
            updateVendorTotal();
        }, 100);
    @endif
});
</script>
@endsection
