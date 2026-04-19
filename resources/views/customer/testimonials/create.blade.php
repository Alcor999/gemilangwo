@extends('layouts.app')

@section('title', 'Tambah Testimoni Video')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle"></i> Tambah Testimoni Video
                    </h1>
                    <p class="text-muted">Bagikan pengalaman pernikahan Anda bersama kami</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 px-4">
                    <h6 class="mb-0"><i class="fas fa-video"></i> Detail Testimoni</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Testimoni <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" placeholder="Contoh: Hari Pernikahan Impian Kami" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Cerita Anda <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Ceritakan pengalaman Anda..." required>{{ old('description') }}</textarea>
                            <small class="text-muted">Minimal 10 karakter</small>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type Selection -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipe Video <span class="text-danger">*</span></label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="type" id="typeUpload" value="upload" 
                                       {{ old('type') === 'upload' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-primary" for="typeUpload">
                                    <i class="fas fa-upload"></i> Unggah Video
                                </label>

                                <input type="radio" class="btn-check" name="type" id="typeYoutube" value="youtube" 
                                       {{ old('type') === 'youtube' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-danger" for="typeYoutube">
                                    <i class="fab fa-youtube"></i> Tautan YouTube
                                </label>
                            </div>
                        </div>

                        <!-- Video File (for upload) -->
                        <div class="mb-3" id="videoFileDiv" style="display: none;">
                            <label for="video_file" class="form-label">File Video <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('video_file') is-invalid @enderror" 
                                       id="video_file" name="video_file" accept="video/*">
                                <span class="input-group-text">
                                    <small class="text-muted">MP4, AVI, MOV, MKV (Maks 500MB)</small>
                                </span>
                            </div>
                            @error('video_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- URL YouTube (untuk YouTube) -->
                        <div class="mb-3" id="youtubeUrlDiv" style="display: none;">
                            <label for="youtube_url" class="form-label">URL YouTube <span class="text-danger">*</span></label>
                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                   id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." 
                                   value="{{ old('youtube_url') }}">
                            @error('youtube_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gambar Miniatur -->
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Gambar Miniatur</label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                       id="thumbnail" name="thumbnail" accept="image/*">
                                <span class="input-group-text">
                                    <small class="text-muted">JPEG, PNG, JPG (Maks 2MB)</small>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">Disarankan: 1280x720 atau 1920x1080</small>
                            @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Related Order -->
                        <div class="mb-3">
                            <label for="order_id" class="form-label">Pernikahan Terkait (Opsional)</label>
                            <select class="form-select @error('order_id') is-invalid @enderror" id="order_id" name="order_id">
                                <option value="">-- Pilih pernikahan --</option>
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
                            <small class="text-muted">Bagaimana Anda menilai pengalaman Anda?</small>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-send"></i> Kirim Testimoni
                            </button>
                            <a href="{{ route('customer.testimonials.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>

                        <div class="alert alert-info mt-3" role="alert">
                            <i class="fas fa-info-circle"></i> Testimoni Anda akan ditinjau tim kami sebelum dipublikasikan di website.
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Sidebar -->
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Tips untuk Testimoni yang Bagus</h6>
                </div>
                <div class="card-body small">
                    <ul class="small">
                        <li><strong>Autentik:</strong> Bagikan pengalaman Anda yang sebenarnya</li>
                        <li><strong>Spesifik:</strong> Ceritakan bagian yang paling Anda sukai</li>
                        <li><strong>Ringkas:</strong> Durasi 30–90 detik</li>
                        <li><strong>Pencahayaan:</strong> Rekam di area yang terang</li>
                        <li><strong>Audio jelas:</strong> Minimalkan suara bising</li>
                        <li><strong>Tatap kamera:</strong> Bangun kontak mata dengan penonton</li>
                        <li><strong>Tersenyum:</strong> Tunjukkan kebahagiaan Anda</li>
                    </ul>

                    <hr>

                    <p class="mb-2"><strong>Persyaratan Video:</strong></p>
                    <ul class="small">
                        <li>Format: MP4, AVI, MOV, MKV</li>
                        <li>Maks 500MB</li>
                        <li>Disarankan: 1920x1080 atau 1280x720</li>
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
