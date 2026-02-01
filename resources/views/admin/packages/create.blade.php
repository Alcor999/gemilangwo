@extends('layouts.app')

@section('title', 'Create Package')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Create New Package</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Package Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (Rp) *</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" step="0.01" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_guests" class="form-label">Max Guests (Optional)</label>
                                    <input type="number" class="form-control @error('max_guests') is-invalid @enderror" id="max_guests" name="max_guests" value="{{ old('max_guests') }}" min="1">
                                    @error('max_guests')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Package Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            <small class="text-muted">Max 2MB, format: JPEG, PNG, JPG, GIF</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Package Features *</label>
                            <div id="features-container">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control feature-input" placeholder="e.g., Dekorasi Mewah" name="features[]">
                                    <button class="btn btn-outline-danger remove-feature" type="button">Remove</button>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-success" type="button" id="add-feature">
                                <i class="fas fa-plus"></i> Add Feature
                            </button>
                            @error('features')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kategori Vendor Wajib Dipilih Customer</label>
                            <p class="text-muted small">Centang kategori vendor yang harus dipilih customer saat memesan paket ini.</p>
                            <div class="border rounded p-3">
                                @forelse(($vendorCategories ?? []) as $vc)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="vendor_category_ids[]" value="{{ $vc->id }}" id="vc{{ $vc->id }}" {{ in_array($vc->id, old('vendor_category_ids', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="vc{{ $vc->id }}">{{ $vc->name }}</label>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Belum ada kategori vendor. <a href="{{ route('admin.vendor-categories.index') }}">Kelola kategori vendor</a></p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Package
                            </button>
                            <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('add-feature').addEventListener('click', function() {
    const container = document.getElementById('features-container');
    const newFeature = document.createElement('div');
    newFeature.className = 'input-group mb-2';
    newFeature.innerHTML = `
        <input type="text" class="form-control feature-input" placeholder="e.g., Catering Berkualitas (3 Menu)" name="features[]">
        <button class="btn btn-outline-danger remove-feature" type="button">Remove</button>
    `;
    container.appendChild(newFeature);
    
    newFeature.querySelector('.remove-feature').addEventListener('click', function() {
        newFeature.remove();
    });
});

// Remove feature functionality
document.querySelectorAll('.remove-feature').forEach(btn => {
    btn.addEventListener('click', function() {
        this.parentElement.remove();
    });
});
</script>
@endsection
