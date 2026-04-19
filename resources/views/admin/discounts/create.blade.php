@extends('layouts.app')

@section('title', 'Buat Diskon')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-1" style="font-family: \'Playfair Display\', serif; font-size: 2rem; font-weight: 600; color: var(--text-dark);"><i class="fas fa-plus-circle"></i> Buat Diskon Baru</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Diskon
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Kesalahan Validasi!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.discounts.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Diskon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" placeholder="contoh: Diskon Akhir Tahun, Promo Valentine" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Jelaskan diskon ini...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Jenis Diskon <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" required onchange="updateValueLabel()">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">
                                    <span id="valueLabel">Nilai Diskon</span> <span class="text-danger">*</span>
                                </label>
                                <input type="number" step="0.01" class="form-control @error('value') is-invalid @enderror" 
                                       id="value" name="value" placeholder="Masukkan nilai diskon" 
                                       value="{{ old('value') }}" required>
                                <small class="form-text text-muted" id="valueHint">
                                    Untuk persentase: isi 0-100. Untuk nominal tetap: isi dalam Rupiah
                                </small>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                                       id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">Tanggal Berakhir (Opsional)</label>
                                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                       id="end_date" name="end_date" value="{{ old('end_date') }}">
                                <small class="form-text text-muted">Kosongkan jika tidak ada tanggal kedaluwarsa</small>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usage_limit" class="form-label">Batas Penggunaan (Opsional)</label>
                                <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                       id="usage_limit" name="usage_limit" placeholder="e.g., 100" 
                                       value="{{ old('usage_limit') }}">
                                <small class="form-text text-muted">Kosongkan untuk penggunaan tanpa batas</small>
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                                <small class="form-text text-muted d-block mt-2">Hilangkan centang untuk menonaktifkan diskon ini</small>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="packages" class="form-label">Terapkan ke Paket (Opsional)</label>
                            <select class="form-select @error('packages') is-invalid @enderror" 
                                    id="packages" name="packages[]" multiple>
                                @forelse ($packages as $package)
                                    <option value="{{ $package->id }}" 
                                            {{ in_array($package->id, old('packages', [])) ? 'selected' : '' }}>
                                        {{ $package->name }} - Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </option>
                                @empty
                                    <option disabled>Tidak ada paket</option>
                                @endforelse
                            </select>
                            <small class="form-text text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Kosongkan untuk menerapkan diskon ke semua paket
                            </small>
                            @error('packages')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Buat Diskon
                            </button>
                            <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h5 class="mb-0 fw-bold" style="font-family: \'Playfair Display\', serif;"><i class="fas fa-lightbulb"></i> Tips</h5>
                </div>
                <div class="card-body">
                    <h6>Contoh Flash Sale</h6>
                    <ul class="small">
                        <li>Name: "Year End Sale"</li>
                        <li>Type: Persentase</li>
                        <li>Value: 30</li>
                        <li>Start: Hari ini</li>
                        <li>End: Akhir tahun</li>
                    </ul>

                    <hr>

                    <h6>Promo Early Bird</h6>
                    <ul class="small">
                        <li>Name: "Early Booking Discount"</li>
                        <li>Type: Nominal Tetap</li>
                        <li>Value: 500000 (Rp)</li>
                        <li>Tanpa tanggal kedaluwarsa</li>
                        <li>Pilih paket tertentu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
        border-radius: 8px;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .text-danger {
        color: #dc3545 !important;
    }
</style>

<script>
function updateValueLabel() {
    const type = document.getElementById('type').value;
    const label = document.getElementById('valueLabel');
    const hint = document.getElementById('valueHint');
    
    if (type === 'percentage') {
        label.textContent = 'Persentase Diskon (%)';
        hint.textContent = 'Isi 0-100 untuk diskon persentase';
    } else if (type === 'fixed') {
        label.textContent = 'Nominal Diskon (Rp)';
        hint.textContent = 'Isi nominal tetap dalam Rupiah';
    }
}

// Perbarui saat halaman dimuat
document.addEventListener('DOMContentLoaded', updateValueLabel);
</script>
@endsection
