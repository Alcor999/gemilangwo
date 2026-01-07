@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('customer.packages.show', $package) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Detail Paket
            </a>
        </div>
    </div>

    <h2 class="h3 fw-bold mb-4">
        <i class="fas fa-images"></i> Galeri - {{ $package->name }}
    </h2>

    @if($images->count() > 0)
        <div class="row g-3">
            @foreach($images as $image)
                <div class="col-md-6 col-lg-4">
                    <div class="gallery-item position-relative" style="overflow: hidden; border-radius: 8px;">
                        <a href="{{ Storage::url($image->image_path) }}" data-lightbox="gallery" data-title="{{ $image->title }}">
                            <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->title }}" 
                                 class="w-100 h-auto" style="object-fit: cover; height: 250px;">
                        </a>
                        @if($image->title)
                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white p-3">
                                <p class="mb-0 small">{{ $image->title }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Lightbox CSS & JS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-image fa-3x mb-3 d-block opacity-50"></i>
            <p class="text-muted">Galeri untuk paket ini masih kosong.</p>
        </div>
    @endif
</div>

<style>
    .gallery-item {
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .gallery-item:hover {
        transform: scale(1.05);
    }

    .gallery-item img {
        transition: brightness 0.3s ease;
    }

    .gallery-item:hover img {
        filter: brightness(0.9);
    }
</style>
@endsection
