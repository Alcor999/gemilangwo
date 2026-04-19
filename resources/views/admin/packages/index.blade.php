@extends('layouts.app')

@section('title', 'Kelola Paket')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light">
        <h1 class="mb-1" style="font-family: \'Playfair Display\', serif; font-size: 2rem; font-weight: 600; color: var(--text-dark);">Kelola Paket</h1>
        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Paket Baru
        </a>
    </div>

    @if($packages->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: rgba(0,0,0,0.02);">
                    <tr>
                        <th>Nama Paket</th>
                        <th>Harga</th>
                        <th>Maks. Tamu</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                        <tr>
                            <td>
                                <strong>{{ $package->name }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                            </td>
                            <td><strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong></td>
                            <td>{{ $package->max_guests ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $package->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $package->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>{{ $package->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-sm btn-warning" title="Ubah">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" 
                                        data-confirm="Apakah Anda yakin ingin menghapus paket &quot;{{ $package->name }}&quot;? Tindakan ini tidak dapat dibatalkan."
                                        data-confirm-title="Hapus Paket"
                                        data-confirm-btn="Ya, Hapus"
                                        data-confirm-danger="1" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            Belum ada paket. <a href="{{ route('admin.packages.create') }}">Buat paket pertama</a>
        </div>
    @endif
</div>
@endsection
