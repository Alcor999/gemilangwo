@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
            <h2>
                <i class="fas fa-star me-2"></i> Review Testimonial
                @if (!$testimonial->is_active)
                    <span class="badge bg-warning">Pending Review</span>
                @else
                    <span class="badge bg-success">Published</span>
                @endif
            </h2>
        </div>
    </div>

    <div class="row">
        <!-- Video Preview -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Video Preview</h5>
                </div>
                <div class="card-body">
                    @if ($testimonial->type === 'youtube' && $testimonial->youtube_url)
                        <div class="ratio ratio-16x9 mb-3">
                            <iframe src="{{ $testimonial->getEmbedUrl() }}" 
                                    title="{{ $testimonial->title }}" 
                                    allowfullscreen="" loading="lazy">
                            </iframe>
                        </div>
                    @elseif ($testimonial->type === 'upload' && $testimonial->video_path)
                        <video width="100%" controls class="mb-3">
                            <source src="{{ asset('storage/' . $testimonial->video_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No video available
                        </div>
                    @endif

                    <div class="mb-3">
                        <small class="text-muted">
                            <strong>Type:</strong> 
                            <span class="badge bg-info">{{ ucfirst($testimonial->type) }}</span>
                        </small>
                    </div>

                    @if ($testimonial->thumbnail_path)
                        <div class="mb-3">
                            <label class="form-label"><strong>Thumbnail:</strong></label>
                            <img src="{{ asset('storage/' . $testimonial->thumbnail_path) }}" 
                                 alt="Thumbnail" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Testimonial Details -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Testimonial Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Title</strong></label>
                        <p class="form-control-plaintext">{{ $testimonial->title }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Description</strong></label>
                        <p class="form-control-plaintext" style="white-space: pre-wrap;">{{ $testimonial->description }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Customer</strong></label>
                            <div class="d-flex align-items-center">
                                @if ($testimonial->user->avatar)
                                    <img src="{{ asset('storage/' . $testimonial->user->avatar) }}" 
                                         alt="{{ $testimonial->user->name }}" 
                                         class="rounded-circle me-2" width="40" height="40">
                                @endif
                                <div>
                                    <p class="mb-0 fw-bold">{{ $testimonial->user->name }}</p>
                                    <p class="mb-0 text-muted small">{{ $testimonial->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Rating</strong></label>
                            <p class="form-control-plaintext">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $testimonial->rating)
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <span class="ms-2">{{ $testimonial->rating }}/5</span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Order</strong></label>
                            @if ($testimonial->order)
                                <p class="form-control-plaintext">
                                    <a href="{{ route('admin.orders.show', $testimonial->order) }}" target="_blank">
                                        Order #{{ $testimonial->order->id }}
                                        <i class="fas fa-external-link-alt small"></i>
                                    </a>
                                </p>
                            @else
                                <p class="form-control-plaintext text-muted">No order linked</p>
                            @endif
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Views</strong></label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-eye me-2"></i> {{ $testimonial->views }} views
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Submitted</strong></label>
                        <p class="form-control-plaintext">
                            {{ $testimonial->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Status</strong></label>
                        <p class="form-control-plaintext">
                            @if ($testimonial->is_active)
                                <span class="badge bg-success">Active / Published</span>
                            @else
                                <span class="badge bg-warning">Pending Review</span>
                            @endif
                            
                            @if ($testimonial->is_featured)
                                <span class="badge bg-warning">Featured</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if (!$testimonial->is_active)
                <div class="card shadow-sm border-0 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Approve or Reject?</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.testimonials.approve', $testimonial) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-check-circle me-2"></i> Approve & Publish
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times-circle me-2"></i> Reject & Delete
                        </button>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Manage Published Testimonial</h5>
                    </div>
                    <div class="card-body">
                        @if (!$testimonial->is_featured)
                            <form action="{{ route('admin.testimonials.feature', $testimonial) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100 mb-2">
                                    <i class="fas fa-star me-2"></i> Mark as Featured
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.testimonials.unfeature', $testimonial) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-secondary w-100 mb-2">
                                    <i class="fas fa-star me-2"></i> Remove from Featured
                                </button>
                            </form>
                        @endif

                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i> Delete
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-danger">
                <h5 class="modal-title">Reject Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to reject and delete this testimonial?</p>
                    <div class="mb-3">
                        <label class="form-label">Reason (optional)</label>
                        <textarea name="reason" class="form-control" rows="3" 
                                  placeholder="Why are you rejecting this testimonial?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Reject & Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-danger">
                <h5 class="modal-title">Delete Testimonial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this testimonial?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
