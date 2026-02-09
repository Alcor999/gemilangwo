@extends('layouts.app')

@section('title', 'Tambah Video - ' . $package->name)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle"></i> Tambah Video ke {{ $package->name }}
                    </h1>
                    <p class="text-muted">Unggah video atau tautan YouTube</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-video"></i> Detail Video</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.videos.store', $package->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Video <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" placeholder="Masukkan judul video" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" placeholder="Deskripsi video (opsional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pilih Jenis -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Jenis Video <span class="text-danger">*</span></label>
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

                        <!-- File Video (unggah) -->
                        <div class="mb-3" id="videoFileDiv" style="display: none;">
                            <label for="video_file" class="form-label">File Video <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('video_file') is-invalid @enderror" 
                                       id="video_file" name="video_file" accept="video/*">
                                <span class="input-group-text">
                                    <small class="text-muted">Disarankan MP4 (Maks 40MB - agar cepat dimuat)</small>
                                </span>
                            </div>
                            @error('video_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- URL YouTube (tautan) -->
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
                            <small class="text-muted d-block mt-2">Jika tidak diisi, thumbnail default akan dibuat otomatis</small>
                            @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Publikasikan video ini sekarang
                                </label>
                            </div>
                            <small class="text-muted">Hilangkan centang untuk menyimpan sebagai draf</small>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tambah Video
                            </button>
                            <a href="{{ route('admin.videos.show', $package->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-header bg-transparent">
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Tips</h6>
                </div>
                <div class="card-body small">
                    <p><strong>Unggah Video:</strong></p>
                    <ul class="small">
                        <li>Format yang disarankan: MP4 (H.264)</li>
                        <li>Ukuran file maksimum: 40MB (batas server PHP)</li>
                        <li>Bitrate: 2-4 Mbps agar streaming cepat</li>
                        <li>Resolusi yang disarankan: 720p (1280x720)</li>
                    </ul>

                    <p class="mt-3"><strong>Integrasi YouTube:</strong></p>
                    <ul class="small">
                        <li>Tempel URL YouTube lengkap</li>
                        <li>Example: https://www.youtube.com/watch?v=...</li>
                        <li>Video YouTube tidak memakai ruang penyimpanan</li>
                    </ul>

                    <p class="mt-3"><strong>Praktik Terbaik:</strong></p>
                    <ul class="small">
                        <li>Gunakan judul yang deskriptif</li>
                        <li>Sertakan thumbnail kustom</li>
                        <li>Tulis deskripsi yang membantu</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Tampilkan/sembunyikan input video berdasarkan jenis yang dipilih
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
    updateFields(); // Inisialisasi saat halaman dimuat
</script>
@endsection
