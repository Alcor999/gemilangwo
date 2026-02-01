@extends('layouts.app')

@section('title', 'Buat Pesanan Baru')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Buat Pesanan Wedding</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.orders.store') }}" method="POST" id="orderForm">
                        @csrf

                        <div class="mb-4">
                            <label for="package_id" class="form-label">Pilih Paket *</label>
                            <select class="form-select form-select-lg @error('package_id') is-invalid @enderror" id="package_id" name="package_id" required>
                                <option value="">-- Pilih paket wedding --</option>
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
                                        {{ $pkg->name }} - Rp {{ number_format($displayPrice, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('package_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="vendorSelectionSection" class="mb-4" style="display: none;">
                            <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-store me-2"></i>Pilih Vendor</h6>
                            <p class="text-muted small">Pilih satu vendor untuk setiap kategori di bawah ini. Total biaya akan disesuaikan.</p>
                            <div id="vendorCategoriesContainer"></div>
                            <div id="vendorTotalPreview" class="alert alert-info mt-3" style="display: none;">
                                <strong>Subtotal Vendor:</strong> Rp <span id="vendorTotalAmount">0</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="event_date" class="form-label">Tanggal Acara *</label>
                            <input type="date" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date') }}" required>
                            @error('event_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="event_location" class="form-label">Lokasi Acara *</label>
                            <input type="text" class="form-control @error('event_location') is-invalid @enderror" id="event_location" name="event_location" placeholder="Nama venue/ballroom" value="{{ old('event_location') }}" required>
                            @error('event_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="guest_count" class="form-label">Jumlah Tamu *</label>
                            <input type="number" class="form-control @error('guest_count') is-invalid @enderror" id="guest_count" name="guest_count" min="1" value="{{ old('guest_count') }}" required>
                            @error('guest_count')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="special_request" class="form-label">Permintaan Khusus (Opsional)</label>
                            <textarea class="form-control" id="special_request" name="special_request" rows="4" placeholder="Beritahu kami tentang kebutuhan khusus...">{{ old('special_request') }}</textarea>
                        </div>

                        <div id="totalPreview" class="alert alert-primary mb-4">
                            <h6 class="mb-0">Total Perkiraan: Rp <span id="totalAmount">0</span></h6>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Buat Pesanan
                            </button>
                            <a href="{{ route('customer.packages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $packagesJson = $packages->map(function($p) {
        return [
            'id' => $p->id,
            'price' => (float) $p->price,
            'discounted' => (float) $p->getDiscountedPrice(),
            'requiredVendorCategories' => $p->requiredVendorCategories->map(function($vc) {
                return [
                    'id' => $vc->id,
                    'name' => $vc->name,
                    'vendors' => $vc->vendors->map(function($v) {
                        return ['id' => $v->id, 'name' => $v->name, 'price' => (float) $v->price];
                    })->values()
                ];
            })->values()
        ];
    });
@endphp

<script>
document.addEventListener('DOMContentLoaded', function() {
    const packages = @json($packagesJson);

    const packageSelect = document.getElementById('package_id');
    const vendorSection = document.getElementById('vendorSelectionSection');
    const vendorContainer = document.getElementById('vendorCategoriesContainer');
    const vendorTotalPreview = document.getElementById('vendorTotalPreview');
    const vendorTotalAmount = document.getElementById('vendorTotalAmount');
    const totalAmount = document.getElementById('totalAmount');

    function getSelectedPackage() {
        const id = parseInt(packageSelect.value);
        return packages.find(p => p.id === id);
    }

    function renderVendorSelection(pkg) {
        vendorContainer.innerHTML = '';
        if (!pkg || !pkg.requiredVendorCategories || pkg.requiredVendorCategories.length === 0) {
            vendorSection.style.display = 'none';
            updateTotal(pkg ? pkg.discounted : 0, 0);
            return;
        }

        vendorSection.style.display = 'block';
        let vendorTotal = 0;

        pkg.requiredVendorCategories.forEach(cat => {
            const div = document.createElement('div');
            div.className = 'mb-4';
            div.innerHTML = `
                <label class="form-label fw-bold">${cat.name} *</label>
                <select class="form-select vendor-select" name="vendors[${cat.id}]" data-category-id="${cat.id}" required>
                    <option value="">-- Pilih vendor --</option>
                    ${cat.vendors.map(v => `
                        <option value="${v.id}" data-price="${v.price}">${v.name} - Rp ${v.price.toLocaleString('id-ID')}</option>
                    `).join('')}
                </select>
            `;
            vendorContainer.appendChild(div);

            const sel = div.querySelector('.vendor-select');
            sel.addEventListener('change', updateVendorTotal);
        });

        vendorTotalPreview.style.display = 'block';
        updateVendorTotal();
    }

    function updateVendorTotal() {
        const pkg = getSelectedPackage();
        if (!pkg) return;

        let sum = 0;
        document.querySelectorAll('.vendor-select').forEach(sel => {
            const opt = sel.options[sel.selectedIndex];
            if (opt && opt.value) sum += parseFloat(opt.dataset.price || 0);
        });

        vendorTotalAmount.textContent = sum.toLocaleString('id-ID');
        updateTotal(pkg.discounted, sum);
    }

    function updateTotal(base, vendorSum) {
        const total = base + (vendorSum || 0);
        totalAmount.textContent = total.toLocaleString('id-ID');
    }

    packageSelect.addEventListener('change', function() {
        const pkg = getSelectedPackage();
        renderVendorSelection(pkg);
    });

    // Initial load
    const initialPkg = getSelectedPackage();
    renderVendorSelection(initialPkg);

    // Restore old vendor selections if validation failed
    @if(old('vendors'))
        const oldVendors = @json(old('vendors'));
        Object.entries(oldVendors).forEach(([catId, vendorId]) => {
            const sel = document.querySelector(`select[name="vendors[${catId}]"]`);
            if (sel && vendorId) sel.value = vendorId;
        });
        updateVendorTotal();
    @endif
});
</script>
@endsection
