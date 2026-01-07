@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Reviews
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Review Content -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <!-- Review Header -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h3 class="mb-2">{{ $review->title }}</h3>
                                <div class="d-flex align-items-center gap-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-50' }}"></i>
                                    @endfor
                                    <span class="badge bg-light text-dark ms-2">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                            <div>
                                @if($review->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                                @if($review->is_verified)
                                    <span class="badge bg-info ms-1">Verified</span>
                                @endif
                                @if($review->is_featured)
                                    <span class="badge bg-info ms-1"><i class="fas fa-star"></i> Featured</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Review Content -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Review Content</h5>
                        <p class="text-muted lh-lg">{{ $review->content }}</p>
                    </div>

                    <hr>

                    <!-- Helpfulness Stats -->
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Helpfulness</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <i class="fas fa-thumbs-up fa-2x text-success opacity-50"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted small mb-1">Helpful</h6>
                                        <h3 class="mb-0">{{ $review->helpful_count }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div>
                                        <i class="fas fa-thumbs-down fa-2x text-danger opacity-50"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted small mb-1">Unhelpful</h6>
                                        <h3 class="mb-0">{{ $review->unhelpful_count }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Customer Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-bold">Customer</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name) }}&background=random&size=80" 
                             alt="{{ $review->user->name }}" class="rounded-circle mb-3" width="80">
                        <h6 class="fw-bold mb-1">{{ $review->user->name }}</h6>
                        <small class="text-muted">{{ $review->user->email }}</small>
                    </div>
                    <hr>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Phone:</strong><br>
                            <span class="text-muted">{{ $review->user->phone ?? 'N/A' }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Joined:</strong><br>
                            <span class="text-muted">{{ $review->user->created_at->format('M d, Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Package Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-bold">Package</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-2">{{ $review->package->name }}</h6>
                    <p class="text-muted small mb-3">{{ Str::limit($review->package->description, 100) }}</p>
                    <div class="mb-3">
                        <strong class="d-block mb-1">Price:</strong>
                        <span class="text-primary h6">Rp {{ number_format($review->package->price, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('admin.packages.show', $review->package) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-eye"></i> View Package
                    </a>
                </div>
            </div>

            <!-- Order Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-bold">Order</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Order #{{ $review->order->id }}</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Status:</strong><br>
                            <span class="badge bg-{{ $review->order->status === 'completed' ? 'success' : 'warning' }} text-dark">
                                {{ ucfirst($review->order->status) }}
                            </span>
                        </li>
                        <li class="mb-2">
                            <strong>Total Price:</strong><br>
                            <span class="text-muted">Rp {{ number_format($review->order->total_price, 0, ',', '.') }}</span>
                        </li>
                        <li class="mb-2">
                            <strong>Ordered:</strong><br>
                            <span class="text-muted">{{ $review->order->created_at->format('M d, Y H:i') }}</span>
                        </li>
                    </ul>
                    <a href="{{ route('admin.orders.show', $review->order) }}" class="btn btn-outline-primary btn-sm w-100 mt-2">
                        <i class="fas fa-eye"></i> View Order
                    </a>
                </div>
            </div>

            <!-- Moderation Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0 fw-bold">Actions</h5>
                </div>
                <div class="card-body">
                    @if(!$review->is_approved)
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 btn-sm">
                                <i class="fas fa-check"></i> Approve Review
                            </button>
                        </form>
                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to reject this review?');">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 btn-sm">
                                <i class="fas fa-trash"></i> Reject Review
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.reviews.feature', $review) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn {{ $review->is_featured ? 'btn-warning' : 'btn-outline-warning' }} w-100 btn-sm">
                                <i class="fas fa-star"></i> {{ $review->is_featured ? 'Unfeature' : 'Feature as Testimonial' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this review?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100 btn-sm">
                                <i class="fas fa-trash"></i> Delete Review
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
