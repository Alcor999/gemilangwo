@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Profile Header -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-3">
                        @if($user->profile_image)
                            <img src="{{ Storage::url($user->profile_image) }}" alt="{{ $user->name }}" 
                                 class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=120" 
                                 alt="{{ $user->name }}" class="rounded-circle" width="120" height="120">
                        @endif
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>
                    <a href="{{ route('customer.profile.edit') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="text-muted fw-bold mb-3">STATISTIK</h6>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <strong>Pesanan</strong>
                            <span class="badge bg-primary">{{ $stats['orders'] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <strong>Review</strong>
                            <span class="badge bg-success">{{ $stats['reviews'] }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-1">
                            <strong>Wishlist</strong>
                            <span class="badge bg-warning text-dark">{{ $stats['wishlists'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-bold">Informasi Profil</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted small mb-1">Nama</h6>
                            <p class="mb-3">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small mb-1">Email</h6>
                            <p class="mb-3">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted small mb-1">Telepon</h6>
                            <p class="mb-3">{{ $user->phone ?? 'Belum diisi' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small mb-1">Kota</h6>
                            <p class="mb-3">{{ $user->city ?? 'Belum diisi' }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted small mb-1">Alamat</h6>
                            <p class="mb-3">{{ $user->address ?? 'Belum diisi' }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-muted small mb-1">Bio</h6>
                            <p class="mb-3">{{ $user->bio ?? 'Belum diisi' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted small mb-1">Tanggal Pernikahan</h6>
                            <p>
                                @if($user->wedding_date)
                                    {{ \Carbon\Carbon::parse($user->wedding_date)->format('d F Y') }}
                                @else
                                    Belum diisi
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted small mb-1">Bergabung Sejak</h6>
                            <p>{{ $user->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="row g-3 mt-3">
                <div class="col-md-4">
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-shopping-cart"></i> Pesanan Saya
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('customer.reviews.index') }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-star"></i> Review Saya
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('customer.wishlist.index') }}" class="btn btn-outline-warning w-100">
                        <i class="fas fa-heart"></i> Wishlist Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
