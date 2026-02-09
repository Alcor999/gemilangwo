@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="mb-0">
                <i class="fas fa-star me-2"></i> Persetujuan Testimoni
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
                    <i class="fas fa-clock me-2"></i> Menunggu Tinjauan
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
                                <!-- Gambar Miniatur -->
                                <div class="position-relative" style="height: 160px; background: #f0f0f0; overflow: hidden;">
                                    @if ($testimonial->type === 'upload' && $testimonial->video_path)
                                        <img src="{{ asset('storage/' . $testimonial->video_path) }}" 
                                             alt="Gambar miniatur" class="w-100 h-100 object-fit-cover">
                                    @elseif ($testimonial->type === 'youtube' && $testimonial->youtube_url)
                                        <img src="https://img.youtube.com/vi/{{ $testimonial->getYoutubeId() }}/hqdefault.jpg" 
                                             alt="Gambar miniatur YouTube" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <div class="text-center">
                                                <i class="fas fa-video fa-3x text-muted"></i>
                                                <p class="text-muted mt-2">Tidak Ada Gambar Miniatur</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge -->
                                    <span class="badge bg-warning position-absolute top-2 end-2">
                                        <i class="fas fa-clock me-1"></i> Menunggu
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
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                        <button type="button" class="btn btn-sm btn-success flex-grow-1" 
                                                data-bs-toggle="modal" data-bs-target="#approveModal{{ $testimonial->id }}">
                                            <i class="fas fa-check me-1"></i> Setujui
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger flex-grow-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal{{ $testimonial->id }}">
                                            <i class="fas fa-times me-1"></i> Tolak
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $testimonial->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-success">
                                            <h5 class="modal-title">Setujui Testimonial</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menyetujui testimonial <strong>"{{ $testimonial->title }}"</strong> dari <strong>{{ $testimonial->user->name }}</strong>? Testimonial akan dipublikasikan secara langsung.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-success" onclick="doApproveTestimonial({{ $testimonial->id }})">
                                                <i class="fas fa-check me-1"></i> Ya, Setujui
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $testimonial->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-danger">
                                            <h5 class="modal-title">Tolak Testimoni</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menolak testimoni dari <strong>{{ $testimonial->user->name }}</strong>?</p>
                                                <div class="mb-3">
                                                    <label class="form-label">Alasan (opsional)</label>
                                                    <textarea name="reason" class="form-control" rows="3" 
                                                              placeholder="Mengapa Anda menolak testimoni ini?"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash me-1"></i> Tolak & Hapus
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginasi -->
                <nav>
                    {{ $pendingTestimonials->links() }}
                </nav>
            @else
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Bagus!</strong> Tidak ada testimoni yang menunggu. Semua kiriman sudah ditinjau.
                </div>
            @endif
        </div>
    </div>

    <!-- PUBLISHED TESTIMONIALS TAB -->
    <div class="card border-success">
        <div class="card-header bg-success text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-check-circle me-2"></i> Testimoni Dipublikasikan
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
                                <!-- Gambar Miniatur -->
                                <div class="position-relative" style="height: 160px; background: #f0f0f0; overflow: hidden;">
                                    @if ($testimonial->type === 'upload' && $testimonial->video_path)
                                        <img src="{{ asset('storage/' . $testimonial->video_path) }}" 
                                             alt="Gambar miniatur" class="w-100 h-100 object-fit-cover">
                                    @elseif ($testimonial->type === 'youtube' && $testimonial->youtube_url)
                                        <img src="https://img.youtube.com/vi/{{ $testimonial->getYoutubeId() }}/hqdefault.jpg" 
                                             alt="Gambar miniatur YouTube" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <div class="text-center">
                                                <i class="fas fa-video fa-3x text-muted"></i>
                                            <p class="text-muted mt-2">Tidak Ada Gambar Miniatur</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Featured Badge -->
                                    @if ($testimonial->is_featured)
                                        <span class="badge bg-warning position-absolute top-2 end-2">
                                            <i class="fas fa-star me-1"></i> Unggulan
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
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                        @if (!$testimonial->is_featured)
                                            <form action="{{ route('admin.testimonials.feature', $testimonial) }}" method="POST" class="d-flex flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning w-100" title="Tandai sebagai Unggulan">
                                                    <i class="fas fa-star me-1"></i> Jadikan Unggulan
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.testimonials.unfeature', $testimonial) }}" method="POST" class="d-flex flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-secondary w-100" title="Hapus dari Unggulan">
                                                    <i class="fas fa-star me-1"></i> Batalkan Unggulan
                                                </button>
                                            </form>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-danger flex-grow-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $testimonial->id }}">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Hapus -->
                            <div class="modal fade" id="deleteModal{{ $testimonial->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-danger">
                                            <h5 class="modal-title">Hapus Testimoni</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus testimoni ini secara permanen?</p>
                                            <p class="text-muted"><strong>Judul:</strong> {{ $testimonial->title }}</p>
                                            <p class="text-muted"><strong>Oleh:</strong> {{ $testimonial->user->name }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash me-1"></i> Hapus Permanen
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginasi -->
                <nav>
                    {{ $publishedTestimonials->links() }}
                </nav>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Belum ada testimoni yang dipublikasikan. Setujui kiriman yang menunggu untuk menampilkannya.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function doApproveTestimonial(id) {
    const modal = bootstrap.Modal.getInstance(document.getElementById('approveModal' + id));
    if (modal) modal.hide();
    fetch(`/admin/testimonials/${id}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showToast === 'function') showToast('Testimoni berhasil disetujui!', 'success');
            location.reload();
        }
    })
    .catch(error => console.error('Kesalahan:', error));
}
</script>
@endsection
