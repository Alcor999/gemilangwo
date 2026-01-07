@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient py-4">
                    <h3 class="mb-1 fw-bold text-white">
                        <i class="fas fa-star"></i> Share Your Review
                    </h3>
                    <p class="mb-0 small text-white opacity-75">Help others make informed decisions</p>
                </div>

                <div class="card-body p-5">
                    <!-- Order & Package Info -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="fw-bold text-muted mb-2">PACKAGE ORDERED</h6>
                                <h5 class="mb-1 fw-bold">{{ $order->package->name }}</h5>
                                <p class="text-muted small mb-0">Order #{{ $order->id }} • Completed on {{ $order->updated_at->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <h6 class="fw-bold text-muted mb-2">TOTAL SPENT</h6>
                                <h5 class="text-primary mb-0">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h5>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('customer.reviews.store', $order) }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold mb-3">
                                How would you rate this package? <span class="text-danger">*</span>
                            </label>
                            <div class="rating-input" id="ratingInput">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                           class="rating-radio d-none" 
                                           {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" class="rating-label">
                                        <i class="fas fa-star"></i>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="form-label fw-bold">
                                Review Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                   id="title" name="title" placeholder="Summarize your experience in a few words"
                                   value="{{ old('title') }}" maxlength="255" required>
                            <small class="text-muted d-block mt-2">
                                <span id="titleCount">0</span>/255 characters
                            </small>
                            @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="form-label fw-bold">
                                Tell us more about your experience <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="6" 
                                      placeholder="Share your thoughts about the package, service quality, professionalism, and overall satisfaction..."
                                      minlength="10" maxlength="2000" required>{{ old('content') }}</textarea>
                            <small class="text-muted d-block mt-2">
                                <span id="contentCount">0</span>/2000 characters (minimum 10)
                            </small>
                            @error('content')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Helpful Tips -->
                        <div class="mb-4 p-3 bg-info bg-opacity-10 border border-info border-opacity-25 rounded">
                            <h6 class="fw-bold text-info mb-2">
                                <i class="fas fa-lightbulb"></i> Tips for a helpful review
                            </h6>
                            <ul class="small text-muted mb-0">
                                <li>Be honest and fair - share your genuine experience</li>
                                <li>Be specific - mention what you liked or didn't like</li>
                                <li>Be constructive - offer suggestions for improvement</li>
                                <li>Be respectful - remember there's a person behind the service</li>
                            </ul>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient {
        background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
    }

    .rating-input {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin: 1rem 0;
    }

    .rating-label {
        cursor: pointer;
        font-size: 2.5rem;
        color: #d1d5db;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
    }

    .rating-label:hover,
    .rating-label:hover ~ .rating-label {
        color: #fbbf24;
        transform: scale(1.1);
    }

    .rating-radio:checked ~ .rating-label {
        color: #f59e0b;
    }

    .rating-radio:checked ~ .rating-label ~ .rating-label {
        color: #d1d5db;
    }

    /* Reverse order for proper star selection */
    .rating-input {
        flex-direction: row-reverse;
        justify-content: center;
    }

    #title,
    #content {
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    #title:focus,
    #content:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
    }
</style>

<script>
    // Character counter for title
    document.getElementById('title').addEventListener('input', function() {
        document.getElementById('titleCount').textContent = this.value.length;
    });

    // Character counter for content
    document.getElementById('content').addEventListener('input', function() {
        document.getElementById('contentCount').textContent = this.value.length;
    });

    // Initialize counters on page load
    document.getElementById('titleCount').textContent = document.getElementById('title').value.length;
    document.getElementById('contentCount').textContent = document.getElementById('content').value.length;

    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var form = document.querySelector('.needs-validation');
            form.addEventListener('submit', function(event) {
                var rating = document.querySelector('input[name="rating"]:checked');
                if (!rating) {
                    event.preventDefault();
                    event.stopPropagation();
                    alert('Please select a rating');
                    return false;
                }
                if (!form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        }, false);
    })();
</script>
@endsection
