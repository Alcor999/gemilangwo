@extends('layouts.app')

@section('title', 'Testimoni Video Saya')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-star"></i> Testimoni Video Saya
                    </h1>
                    <p class="text-muted">Bagikan pengalaman pernikahan Anda bersama kami</p>
                </div>
                <a href="{{ route('customer.testimonials.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Testimoni
                </a>
            </div>
        </div>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Testimonials -->
    <div class="row">
        @forelse($testimonials as $testimonial)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <!-- Gambar Miniatur -->
                    <div class="position-relative" style="height: 200px; overflow: hidden; background: #f8f9fa;">
                        @if($testimonial->thumbnail_path)
                            <img src="{{ asset('storage/' . $testimonial->thumbnail_path) }}" 
                                 alt="{{ $testimonial->title }}" 
                                 class="card-img-top"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <div class="text-center">
                                    <i class="fas fa-video" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Tidak ada gambar miniatur</p>
                                </div>
                            </div>
                        @endif
                        <div class="position-absolute top-0 end-0 m-2">
                            <span class="badge bg-{{ $testimonial->type === 'youtube' ? 'danger' : 'info' }}">
                                {{ ucfirst($testimonial->type) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Title -->
                        <h5 class="card-title">{{ $testimonial->title }}</h5>

                        <!-- Description -->
                        <p class="card-text text-muted small">
                            {{ Str::limit($testimonial->description, 100) }}
                        </p>

                        <!-- Rating -->
                        @if($testimonial->rating)
                            <div class="mb-2">
                                <div class="text-warning">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star{{ $i < $testimonial->rating ? '' : ' fa-star-o' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        @endif

                        <!-- Status -->
                        <div class="mb-2">
                            @if($testimonial->is_active)
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle"></i> Dipublikasikan
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock"></i> Menunggu Tinjauan
                                </span>
                            @endif
                            @if($testimonial->is_featured)
                                <span class="badge bg-info">
                                    <i class="fas fa-star"></i> Unggulan
                                </span>
                            @endif
                        </div>

                        <!-- Views -->
                        <small class="text-muted">
                            <i class="fas fa-eye"></i> {{ $testimonial->views }} tayangan
                        </small>
                    </div>

                    <div class="card-footer bg-transparent">
                        <div class="d-grid gap-2">
                            <a href="{{ route('customer.testimonials.edit', $testimonial->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Ubah
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $testimonial->id }}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="deleteModal{{ $testimonial->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Hapus Testimoni</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Apakah Anda yakin ingin menghapus testimoni ini?</p>
                                    <p><strong>{{ $testimonial->title }}</strong></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('customer.testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card text-center py-5">
                    <div class="card-body">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="card-title mt-3">Belum Ada Testimoni</h5>
                        <p class="card-text text-muted">Bagikan pengalaman pernikahan Anda lewat testimoni video!</p>
                        <a href="{{ route('customer.testimonials.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Testimoni Pertama Anda
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
