@extends('layouts.app')

@section('title', 'Edit Vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Edit Vendor</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.vendors.update', $vendor) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="vendor_category_id" class="form-label">Kategori Vendor *</label>
                            <select class="form-select @error('vendor_category_id') is-invalid @enderror" id="vendor_category_id" name="vendor_category_id" required>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" {{ old('vendor_category_id', $vendor->vendor_category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('vendor_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Vendor *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $vendor->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $vendor->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga (Rp) *</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $vendor->price) }}" min="0" step="1000" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar</label>
                            @if($vendor->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $vendor->image) }}" alt="" style="max-width: 120px; max-height: 120px; object-fit: cover;" class="rounded">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Maks 2MB. Kosongkan untuk tidak mengubah.</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $vendor->contact_phone) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ old('contact_email', $vendor->contact_email) }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $vendor->sort_order) }}" min="0">
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $vendor->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Aktif</label>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
