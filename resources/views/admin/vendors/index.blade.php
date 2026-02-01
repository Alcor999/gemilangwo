@extends('layouts.app')

@section('title', 'Daftar Vendor')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-store"></i> Daftar Vendor</h1>
            <p class="text-muted">Kelola vendor yang bisa dipilih customer untuk setiap kategori.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Vendor
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-3">
        <form action="{{ route('admin.vendors.index') }}" method="GET" class="d-flex gap-2">
            <select name="category" class="form-select" style="max-width: 250px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-outline-primary">Filter</button>
        </form>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Vendor</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $v)
                        <tr>
                            <td>
                                @if($v->image)
                                    <img src="{{ asset('storage/' . $v->image) }}" alt="" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <span class="bg-light rounded d-inline-block me-2 text-center" style="width: 40px; height: 40px; line-height: 40px;"><i class="fas fa-store text-muted"></i></span>
                                @endif
                                <strong>{{ $v->name }}</strong>
                                @if($v->description)
                                    <br><small class="text-muted">{{ Str::limit($v->description, 40) }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-secondary">{{ $v->vendorCategory->name }}</span></td>
                            <td><strong>Rp {{ number_format($v->price, 0, ',', '.') }}</strong></td>
                            <td>
                                <span class="badge {{ $v->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $v->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.vendors.edit', $v) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.vendors.destroy', $v) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                        data-confirm="Hapus vendor {{ $v->name }}?"
                                        data-confirm-title="Hapus Vendor"
                                        data-confirm-btn="Ya, Hapus"
                                        data-confirm-danger="1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada vendor. <a href="{{ route('admin.vendors.create') }}">Tambah pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $vendors->withQueryString()->links() }}</div>
</div>
@endsection
