@extends('layouts.app')

@section('title', 'Browse Packages')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4" style="font-size: 2rem;">Our Wedding Organizer Packages</h1>

    @if($packages->count() > 0)
        <div class="row">
            @foreach($packages as $package)
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        @if($package->image)
                            <img src="{{ asset('storage/' . $package->image) }}" class="card-img-top" alt="{{ $package->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-ring fa-3x fa-lg text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title" style="font-size: 1.1rem;">{{ $package->name }}</h5>
                            <p class="card-text text-muted" style="font-size: 0.9rem;">{{ Str::limit($package->description, 100) }}</p>
                            <div class="mb-3">
                                <h3 class="text-primary mb-0" style="font-size: 1.5rem;">Rp {{ number_format($package->price, 0, ',', '.') }}</h3>
                                @if($package->max_guests)
                                    <small class="text-muted">Up to {{ $package->max_guests }} guests</small>
                                @endif
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('customer.packages.show', $package->id) }}" class="btn btn-primary btn-sm w-100">
                                    View Details & Book
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            No packages available at the moment. Please check back later.
        </div>
    @endif
</div>

<style>
    @media (max-width: 768px) {
        h1 {
            font-size: 1.5rem;
        }

        .card-title {
            font-size: 1rem !important;
        }

        .card-text {
            font-size: 0.85rem !important;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.4rem 0.75rem;
        }
    }

    @media (max-width: 576px) {
        h1 {
            font-size: 1.25rem;
        }

        .card {
            margin-bottom: 0.5rem;
        }

        .card-img-top {
            height: 150px !important;
        }

        .card-body {
            padding: 0.75rem !important;
        }

        .card-title {
            font-size: 0.95rem !important;
        }

        h3 {
            font-size: 1.25rem !important;
        }
    }
</style>
@endsection
