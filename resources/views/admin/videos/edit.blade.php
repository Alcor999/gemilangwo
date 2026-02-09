@extends('layouts.app')

@section('title', 'Ubah Video - ' . $video->title)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-edit"></i> Ubah Video
                    </h1>
                    <p class="text-muted">Perbarui detail video</p>
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
                    <form action="{{ route('admin.videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Judul -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Video <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $video->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $video->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pilih Jenis -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Jenis Video <span class="text-danger">*</span></label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="type" id="typeUpload" value="upload" 
                                       {{ old('type', $video->type) === 'upload' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-primary" for="typeUpload">
                                    <i class="fas fa-upload"></i> Unggah Video
                                </label>

                                <input type="radio" class="btn-check" name="type" id="typeYoutube" value="youtube" 
                                       {{ old('type', $video->type) === 'youtube' ? 'checked' : '' }} required>
                                <label class="btn btn-outline-danger" for="typeYoutube">
                                    <i class="fab fa-youtube"></i> Tautan YouTube
                                </label>
                            </div>
                        </div>

                        <!-- File Video (unggah) -->
                        <div class="mb-3" id="videoFileDiv" style="display: none;">
                            <label for="video_file" class="form-label">Ganti File Video</label>
                            <div class="input-group">
                                <input type="file" class="form-control @error('video_file') is-invalid @enderror" 
                                       id="video_file" name="video_file" accept="video/*">
                                <span class="input-group-text">
                                    <small class="text-muted">Kosongkan untuk mempertahankan yang sekarang</small>
                                </span>
                            </div>
                            @if($video->video_path)
                                <small class="text-success d-block mt-2">
                                    <i class="fas fa-check"></i> Video saat ini: {{ basename($video->video_path) }}
                                </small>
                            @endif
                            @error('video_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- URL YouTube (tautan) -->
                        <div class="mb-3" id="youtubeUrlDiv" style="display: none;">
                            <label for="youtube_url" class="form-label">URL YouTube</label>
                            <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                   id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $video->youtube_url) }}">
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
                                    <small class="text-muted">Kosongkan untuk mempertahankan yang sekarang</small>
                                </span>
                            </div>
                            @if($video->thumbnail_path)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $video->thumbnail_path) }}" alt="Gambar miniatur" 
                                         class="img-thumbnail" style="max-width: 150px;">
                                </div>
                            @endif
                            @error('thumbnail')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $video->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif (Dipublikasikan)
                                </label>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Perbarui Video
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
                    <h6 class="mb-0"><i class="fas fa-info-circle"></i> Info Video</h6>
                </div>
                <div class="card-body small">
                    <p><strong>Paket:</strong><br>{{ $package->name }}</p>
                    <p><strong>Jenis:</strong><br>{{ ucfirst($video->type) }}</p>
                    <p><strong>Dibuat:</strong><br>{{ $video->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Diperbarui:</strong><br>{{ $video->updated_at->format('M d, Y H:i') }}</p>
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
