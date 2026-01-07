@extends('layouts.app')

@section('title', 'My Reviews - Gemilang WO')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">My Reviews</h1>

    @if($reviews->count() > 0)
        <div class="row">
            @foreach($reviews as $review)
                <div class="col-12 col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <!-- Package Info -->
                            <div class="mb-3">
                                <h5 class="card-title mb-1">{{ $review->package->name }}</h5>
                                <small class="text-muted">{{ $review->created_at->format('d M Y') }}</small>
                            </div>

                            <!-- Rating -->
                            <div class="mb-3">
                                <div class="mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star" style="color: #ffc107;"></i>
                                        @else
                                            <i class="far fa-star" style="color: #ddd;"></i>
                                        @endif
                                    @endfor
                                    <span class="ms-2 fw-bold">{{ $review->rating }}/5</span>
                                </div>
                            </div>

                            <!-- Title -->
                            <h6 class="mb-2">{{ $review->title }}</h6>

                            <!-- Content -->
                            <p class="card-text text-muted mb-3">{{ Str::limit($review->content, 150) }}</p>

                            <!-- Status Badge -->
                            <div class="mb-3">
                                @if($review->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @elseif($review->is_approved === false)
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning">Pending Review</span>
                                @endif

                                @if($review->is_featured)
                                    <span class="badge bg-info ms-1"><i class="fas fa-star"></i> Featured</span>
                                @endif
                            </div>

                            <!-- Helpful Count -->
                            <small class="text-muted">
                                <i class="fas fa-thumbs-up"></i> {{ $review->helpful_count }} helpful
                            </small>

                            <!-- View Full Link -->
                            <div class="mt-3">
                                <a href="{{ route('customer.packages.show', $review->package) }}" class="btn btn-sm btn-outline-primary">
                                    View Package
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $reviews->links() }}
        </div>
    @else
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            You haven't written any reviews yet. <a href="{{ route('customer.orders.index') }}">Check your completed orders</a> to leave a review!
        </div>
    @endif
</div>
@endsection
