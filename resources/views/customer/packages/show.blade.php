@extends('layouts.app')

@section('title', 'Package Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                @if($package->image)
                    <img src="{{ asset('storage/' . $package->image) }}" class="card-img-top" alt="{{ $package->name }}" style="height: 400px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <i class="fas fa-ring fa-8x text-muted"></i>
                    </div>
                @endif
                <div class="card-body">
                    <h1 class="card-title">{{ $package->name }}</h1>
                    <h2 class="text-primary mb-3">Rp {{ number_format($package->price, 0, ',', '.') }}</h2>
                    
                    <h5 class="mt-4 mb-3">Package Description</h5>
                    <p class="card-text">{{ $package->description }}</p>

                    @if($package->features)
                        <h5 class="mt-4 mb-3">Included Features</h5>
                        <ul class="list-group list-group-flush mb-4">
                            @foreach(is_array($package->features) ? $package->features : json_decode($package->features, true) ?? [] as $feature)
                                <li class="list-group-item">
                                    <i class="fas fa-check text-success me-2"></i>{{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if($package->max_guests)
                        <p class="text-muted">
                            <i class="fas fa-users me-2"></i>
                            Maximum {{ $package->max_guests }} guests
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ready to Book?</h5>
                    <a href="{{ route('customer.orders.create') }}" class="btn btn-primary w-100 mb-2" data-package="{{ $package->id }}">
                        <i class="fas fa-calendar"></i> Book This Package
                    </a>
                    <a href="{{ route('customer.packages.index') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Back to Packages
                    </a>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="mb-3">Why Choose Us?</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Professional Service</li>
                        <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>Attention to Detail</li>
                        <li class="mb-2"><i class="fas fa-star text-warning me-2"></i>24/7 Support</li>
                        <li><i class="fas fa-star text-warning me-2"></i>Proven Experience</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    document.querySelector('a[data-package]').addEventListener('click', function(e) {
        // Store selected package ID in session or pass it to booking form
        localStorage.setItem('selected_package', {{ $package->id }});
    });
</script>
@endpush
@endsection
