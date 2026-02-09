@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h3 fw-bold">
                <i class="fas fa-star text-warning"></i> Manajemen Ulasan
            </h2>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Total Ulasan</h6>
                            <h3 class="mb-0">{{ $reviews->total() }}</h3>
                        </div>
                        <i class="fas fa-comments fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Menunggu Persetujuan</h6>
                            <h3 class="mb-0 text-warning">{{ \App\Models\Review::where('is_approved', false)->count() }}</h3>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Disetujui</h6>
                            <h3 class="mb-0 text-success">{{ \App\Models\Review::where('is_approved', true)->count() }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted small mb-1">Unggulan</h6>
                            <h3 class="mb-0 text-info">{{ \App\Models\Review::where('is_featured', true)->count() }}</h3>
                        </div>
                        <i class="fas fa-star fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Penyaringan -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <ul class="nav nav-pills gap-2" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('admin.reviews.index') }}">
                        Semua
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.reviews.index') }}?filter=pending">
                        <span class="badge bg-warning">Menunggu</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.reviews.index') }}?filter=approved">
                        <span class="badge bg-success">Disetujui</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.reviews.index') }}?filter=featured">
                        <span class="badge bg-info">Unggulan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Pelanggan</th>
                        <th>Paket</th>
                        <th>Rating</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Unggulan</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random" 
                                             alt="{{ $review->user->name }}" class="rounded-circle" width="32">
                                    </div>
                                    <div>
                                        <strong class="d-block">{{ $review->user->name }}</strong>
                                        <small class="text-muted">{{ $review->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.packages.show', $review->package) }}" class="text-decoration-none">
                                    {{ $review->package->name }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-50' }}"></i>
                                    @endfor
                                    <span class="ms-2 badge bg-light text-dark">{{ $review->rating }}/5</span>
                                </div>
                            </td>
                            <td>
                                <strong>{{ Str::limit($review->title, 30) }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($review->content, 50) }}</small>
                            </td>
                            <td>
                                @if($review->is_approved)
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @endif
                                @if($review->is_verified)
                                    <span class="badge bg-info ms-1">Terverifikasi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($review->is_featured)
                                    <span class="badge bg-info"><i class="fas fa-star"></i> Unggulan</span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.reviews.show', $review) }}" 
                                       class="btn btn-outline-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(!$review->is_approved)
                                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" title="Setujui">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="button" class="btn btn-outline-danger" title="Tolak"
                                                data-confirm="Apakah Anda yakin ingin menolak ulasan ini?"
                                                data-confirm-title="Tolak Ulasan"
                                                data-confirm-btn="Ya, Tolak"
                                                data-confirm-danger="1">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.reviews.feature', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn {{ $review->is_featured ? 'btn-warning' : 'btn-outline-warning' }}" 
                                                    title="{{ $review->is_featured ? 'Batalkan Unggulan' : 'Jadikan Unggulan' }}">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                data-confirm="Apakah Anda yakin ingin menghapus ulasan ini secara permanen?"
                                                data-confirm-title="Hapus Ulasan"
                                                data-confirm-btn="Ya, Hapus"
                                                data-confirm-danger="1">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-inbox text-muted fa-3x opacity-50 mb-3 d-block"></i>
                                <p class="text-muted">Belum ada ulasan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginasi -->
        @if($reviews->hasPages())
            <div class="card-footer bg-white border-top">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .avatar {
        display: inline-block;
    }
    
    .avatar img {
        width: 100%;
        height: auto;
    }
    
    .btn-group-sm {
        gap: 0.25rem;
    }
</style>
@endsection
