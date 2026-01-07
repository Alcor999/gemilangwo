@extends('layouts.app')

@section('title', 'Edit Testimonial')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-edit"></i> Edit Testimonial
                    </h1>
                    <p class="text-muted">Update your video testimonial</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-video"></i> Testimonial Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.testimonials.update', $testimonial->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Testimonial Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $testimonial->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Your Story <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $testimonial->description) }}</textarea>
                            <small class="text-muted">Minimum 10 characters</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type Selection -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Video Type <span class="text-danger">*</span></label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="type" id="typeUpload" value="upload" 
                                       {{ old('type', $testimonial->type) === 'upload' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-primary" for="typeUpload">
                                    <i class="fas fa-upload"></i> Upload Video
                                </label>

                                <input type="radio" class="btn-check" name="type" id="typeYoutube" value="youtube" 
                                       {{ old('type', $testimonial->type) === 'youtube' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-danger" for="typeYoutube">
                                    <i class="fab fa-youtube"></i> YouTube Link
                                </label>
                            </div>
                        </div>

                        <!-- Video File (for upload) -->
                        <div class="mb-3" id="videoFileDiv" style="display: none;">
                            <label for="video_file" class="form-label">Replace Video File</label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('video_file') is-invalid @enderror" 
                                       id="video_file" name="video_file" accept="video/*">
                                <span class="input-group-text">
                                    <small class="text-muted">Leave empty to keep current</small>
                                </span>
                            </div>
                            @if($testimonial->video_path)
                                <small class="text-success d-block mt-2">
                                    <i class="fas fa-check"></i> Current video uploaded
                                </small>
                            @endif
                            @error('video_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- YouTube URL (for YouTube) -->
                        <div class="mb-3" id="youtubeUrlDiv" style="display: none;">
                            <label for="youtube_url" class="form-label">YouTube URL</label>
                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                   id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $testimonial->youtube_url) }}">
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Thumbnail -->
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Thumbnail Image</label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                       id="thumbnail" name="thumbnail" accept="image/*">
                                <span class="input-group-text">
                                    <small class="text-muted">Leave empty to keep current</small>
                                </span>
                            </div>
                            @if($testimonial->thumbnail_path)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $testimonial->thumbnail_path) }}" alt="Thumbnail" 
                                         class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            @endif
                            @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Related Order -->
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Related Wedding</label>
                            <select class="form-select @error('order_id') is-invalid @enderror" id="order_id" name="order_id">
                                <option value="">-- Select a wedding --</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" {{ old('order_id', $testimonial->order_id) == $order->id ? 'selected' : '' }}>
                                        {{ $order->package->name }} - {{ $order->created_at->format('M d, Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('order_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Rating -->
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <div class="d-flex gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" class="btn-check" name="rating" id="rating{{ $i }}" value="{{ $i }}"
                                           {{ old('rating', $testimonial->rating) == $i ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning" for="rating{{ $i }}">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Testimonial
                            </button>
                            <a href="{{ route('customer.testimonials.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>

                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle"></i> Your changes will be reviewed again before publishing.
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Testimonial Info</h6>
                </div>
                <div class="card-body small">
                    <p><strong>Status:</strong><br>
                        @if($testimonial->is_active)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending Review</span>
                        @endif
                    </p>
                    <p><strong>Views:</strong> {{ $testimonial->views }}</p>
                    <p><strong>Created:</strong><br>{{ $testimonial->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Updated:</strong><br>{{ $testimonial->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const typeUpload = document.getElementById('typeUpload');
    const typeYoutube = document.getElementById('typeYoutube');
    const videoFileDiv = document.getElementById('videoFileDiv');
    const youtubeUrlDiv = document.getElementById('youtubeUrlDiv');

    function updateFields() {
        if (typeUpload.checked) {
            videoFileDiv.style.display = 'block';
            youtubeUrlDiv.style.display = 'none';
        } else {
            videoFileDiv.style.display = 'none';
            youtubeUrlDiv.style.display = 'block';
        }
    }

    typeUpload.addEventListener('change', updateFields);
    typeYoutube.addEventListener('change', updateFields);
    updateFields();
</script>
@endsection
