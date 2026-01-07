@extends('layouts.app')

@section('title', 'Add Video Testimonial')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle"></i> Add Video Testimonial
                    </h1>
                    <p class="text-muted">Share your wedding experience with us</p>
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
                    <form action="{{ route('customer.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Testimonial Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" placeholder="e.g., Our Perfect Wedding Day" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Your Story <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Tell us about your experience..." required>{{ old('description') }}</textarea>
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
                                       {{ old('type') === 'upload' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-primary" for="typeUpload">
                                    <i class="fas fa-upload"></i> Upload Video
                                </label>

                                <input type="radio" class="btn-check" name="type" id="typeYoutube" value="youtube" 
                                       {{ old('type') === 'youtube' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-danger" for="typeYoutube">
                                    <i class="fab fa-youtube"></i> YouTube Link
                                </label>
                            </div>
                        </div>

                        <!-- Video File (for upload) -->
                        <div class="mb-3" id="videoFileDiv" style="display: none;">
                            <label for="video_file" class="form-label">Video File <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('video_file') is-invalid @enderror" 
                                       id="video_file" name="video_file" accept="video/*">
                                <span class="input-group-text">
                                    <small class="text-muted">MP4, AVI, MOV, MKV (Max 500MB)</small>
                                </span>
                            </div>
                            @error('video_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- YouTube URL (for YouTube) -->
                        <div class="mb-3" id="youtubeUrlDiv" style="display: none;">
                            <label for="youtube_url" class="form-label">YouTube URL <span class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                   id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." 
                                   value="{{ old('youtube_url') }}">
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
                                    <small class="text-muted">JPEG, PNG, JPG (Max 2MB)</small>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">Recommended: 1280x720 or 1920x1080</small>
                            @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Related Order -->
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Related Wedding (Optional)</label>
                            <select class="form-select @error('order_id') is-invalid @enderror" id="order_id" name="order_id">
                                <option value="">-- Select a wedding --</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
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
                                           {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label class="btn btn-outline-warning" for="rating{{ $i }}">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            <small class="text-muted">How would you rate your experience?</small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-send"></i> Submit Testimonial
                            </button>
                            <a href="{{ route('customer.testimonials.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>

                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle"></i> Your testimonial will be reviewed by our team before being published on our website.
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Tips for Great Testimonial</h6>
                </div>
                <div class="card-body small">
                    <ul class="small">
                        <li><strong>Be authentic:</strong> Share your genuine experience</li>
                        <li><strong>Be specific:</strong> Mention what you loved most</li>
                        <li><strong>Be concise:</strong> Keep it 30-90 seconds</li>
                        <li><strong>Good lighting:</strong> Film in well-lit areas</li>
                        <li><strong>Clear audio:</strong> Minimize background noise</li>
                        <li><strong>Look at camera:</strong> Make eye contact with viewers</li>
                        <li><strong>Smile:</strong> Show your joy and happiness</li>
                    </ul>

                    <hr>

                    <p class="mb-2"><strong>Video Requirements:</strong></p>
                    <ul class="small">
                        <li>Format: MP4, AVI, MOV, MKV</li>
                        <li>Max 500MB</li>
                        <li>Recommended: 1920x1080 or 1280x720</li>
                    </ul>
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
