@extends('layouts.app')

@section('title', 'Kategori Vendor')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-layer-group"></i> Kategori Vendor</h1>
            <p class="text-muted">Kelola kategori vendor (Catering, Dekorasi, dll) yang bisa dipilih customer.</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.vendor-categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah Vendor</th>
                        <th>Status</th>
                        <th>Urutan</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>
                                @if($cat->icon)
                                    <i class="fas {{ $cat->icon }} me-2 text-primary"></i>
                                @endif
                                <strong>{{ $cat->name }}</strong>
                                @if($cat->description)
                                    <br><small class="text-muted">{{ Str::limit($cat->description, 50) }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-primary">{{ $cat->all_vendors_count }}</span></td>
                            <td>
                                <span class="badge {{ $cat->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $cat->sort_order }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.vendors.index', ['category' => $cat->id]) }}" class="btn btn-sm btn-info" title="Lihat Vendor">
                                    <i class="fas fa-store"></i>
                                </a>
                                <a href="{{ route('admin.vendor-categories.edit', $cat) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.vendor-categories.destroy', $cat) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                        data-confirm="Hapus kategori {{ $cat->name }}? Vendor di dalamnya harus dihapus terlebih dahulu."
                                        data-confirm-title="Hapus Kategori"
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
                                Belum ada kategori vendor. <a href="{{ route('admin.vendor-categories.create') }}">Tambah pertama</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $categories->links() }}</div>
</div>
@endsection
