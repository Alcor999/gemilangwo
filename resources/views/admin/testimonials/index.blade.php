@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-0">
                <i class="fas fa-star me-2"></i> Testimonial Approvals
            </h2>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- PENDING TESTIMONIALS TAB -->
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clock me-2"></i> Pending Review
                    <span class="badge bg-danger">{{ $pendingTestimonials->total() }}</span>
                </h5>
            </div>
        </div>
        <div class="card-body">
            @if ($pendingTestimonials->count() > 0)
                <div class="row">
                    @foreach ($pendingTestimonials as $testimonial)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <!-- Thumbnail -->
                                <div class="position-relative" style="height: 160px; background: #f0f0f0; overflow: hidden;">
                                    @if ($testimonial->type === 'upload' && $testimonial->video_path)
                                        <img src="{{ asset('storage/' . $testimonial->video_path) }}" 
                                             alt="Thumbnail" class="w-100 h-100 object-fit-cover">
                                    @elseif ($testimonial->type === 'youtube' && $testimonial->youtube_url)
                                        <img src="https://img.youtube.com/vi/{{ $testimonial->getYoutubeId() }}/hqdefault.jpg" 
                                             alt="YouTube Thumbnail" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <div class="text-center">
                                                <i class="fas fa-video fa-3x text-muted"></i>
                                                <p class="text-muted mt-2">No Thumbnail</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge -->
                                    <span class="badge bg-warning position-absolute top-2 end-2">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                </div>

                                <div class="card-body">
                                    <h6 class="card-title">{{ $testimonial->title }}</h6>
                                    <p class="card-text small text-muted">
                                        {{ Str::limit($testimonial->description, 60) }}
                                    </p>

                                    <div class="mb-3">
                                        <span class="badge bg-info">{{ $testimonial->user->name }}</span>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-star text-warning"></i> {{ $testimonial->rating }}/5
                                        </span>
                                    </div>

                                    <small class="text-muted d-block mb-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $testimonial->created_at->format('d M Y H:i') }}
                                    </small>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 w-100">
                                        <a href="{{ route('admin.testimonials.show', $testimonial) }}" 
                                           class="btn btn-sm btn-primary flex-grow-1">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success flex-grow-1" 
                                                onclick="approveTestimonial({{ $testimonial->id }})">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger flex-grow-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $testimonial->id }}">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $testimonial->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-danger">
                                            <h5 class="modal-title">Reject Testimonial</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Are you sure you want to reject this testimonial from <strong>{{ $testimonial->user->name }}</strong>?</p>
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
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <nav>
                    {{ $pendingTestimonials->links() }}
                </nav>
            @else
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Great!</strong> No pending testimonials. All submissions have been reviewed.
                </div>
            @endif
        </div>
    </div>

    <!-- PUBLISHED TESTIMONIALS TAB -->
    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i> Published Testimonials
                    <span class="badge bg-light text-dark">{{ $publishedTestimonials->total() }}</span>
                </h5>
            </div>
        </div>
        <div class="card-body">
            @if ($publishedTestimonials->count() > 0)
                <div class="row">
                    @foreach ($publishedTestimonials as $testimonial)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <!-- Thumbnail -->
                                <div class="position-relative" style="height: 160px; background: #f0f0f0; overflow: hidden;">
                                    @if ($testimonial->type === 'upload' && $testimonial->video_path)
                                        <img src="{{ asset('storage/' . $testimonial->video_path) }}" 
                                             alt="Thumbnail" class="w-100 h-100 object-fit-cover">
                                    @elseif ($testimonial->type === 'youtube' && $testimonial->youtube_url)
                                        <img src="https://img.youtube.com/vi/{{ $testimonial->getYoutubeId() }}/hqdefault.jpg" 
                                             alt="YouTube Thumbnail" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <div class="text-center">
                                                <i class="fas fa-video fa-3x text-muted"></i>
                                                <p class="text-muted mt-2">No Thumbnail</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Featured Badge -->
                                    @if ($testimonial->is_featured)
                                        <span class="badge bg-warning position-absolute top-2 end-2">
                                            <i class="fas fa-star me-1"></i> Featured
                                        </span>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <h6 class="card-title">{{ $testimonial->title }}</h6>
                                    <p class="card-text small text-muted">
                                        {{ Str::limit($testimonial->description, 60) }}
                                    </p>

                                    <div class="mb-3">
                                        <span class="badge bg-info">{{ $testimonial->user->name }}</span>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-star text-warning"></i> {{ $testimonial->rating }}/5
                                        </span>
                                        <span class="badge bg-dark">
                                            <i class="fas fa-eye me-1"></i> {{ $testimonial->views }}
                                        </span>
                                    </div>

                                    <small class="text-muted d-block mb-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $testimonial->created_at->format('d M Y') }}
                                    </small>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 w-100 flex-wrap">
                                        <a href="{{ route('admin.testimonials.show', $testimonial) }}" 
                                           class="btn btn-sm btn-primary flex-grow-1">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        @if (!$testimonial->is_featured)
                                            <form action="{{ route('admin.testimonials.feature', $testimonial) }}" method="POST" class="d-flex flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning w-100" title="Mark as Featured">
                                                    <i class="fas fa-star me-1"></i> Feature
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.testimonials.unfeature', $testimonial) }}" method="POST" class="d-flex flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-secondary w-100" title="Remove from Featured">
                                                    <i class="fas fa-star me-1"></i> Unfeature
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-danger flex-grow-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $testimonial->id }}">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $testimonial->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-danger">
                                            <h5 class="modal-title">Delete Testimonial</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to permanently delete this testimonial?</p>
                                            <p class="text-muted"><strong>Title:</strong> {{ $testimonial->title }}</p>
                                            <p class="text-muted"><strong>By:</strong> {{ $testimonial->user->name }}</p>
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
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <nav>
                    {{ $publishedTestimonials->links() }}
                </nav>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No published testimonials yet. Approve pending submissions to display them.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function approveTestimonial(id) {
    if (confirm('Approve this testimonial? It will be published immediately.')) {
        fetch(`/admin/testimonials/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
@endsection
