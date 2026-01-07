@extends('layouts.app')

@section('title', $package->name . ' - Video Gallery')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-film"></i> {{ $package->name }} - Videos
                    </h1>
                    <p class="text-muted">Manage package videos and media</p>
                </div>
                <a href="{{ route('admin.videos.create', $package->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Video
                </a>
            </div>
        </div>
    </div>

    @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Videos List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-list"></i> Video List ({{ $videos->count() }} videos)</h6>
                </div>
                @if($videos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%"></th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th class="text-center" style="width: 10%">Status</th>
                                    <th class="text-end" style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="videoSortable">
                                @foreach($videos as $video)
                                    <tr data-id="{{ $video->id }}" class="video-row">
                                        <td class="text-center cursor-move">
                                            <i class="fas fa-bars text-muted"></i>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($video->thumbnail_path)
                                                    <img src="{{ asset('storage/' . $video->thumbnail_path) }}" 
                                                         alt="{{ $video->title }}" 
                                                         class="rounded me-2" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-video text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $video->title }}</strong>
                                                    @if($video->description)
                                                        <br><small class="text-muted">{{ Str::limit($video->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $video->type === 'youtube' ? 'danger' : 'info' }}">
                                                <i class="fas fa-{{ $video->type === 'youtube' ? 'brands fa-youtube' : 'video' }}"></i>
                                                {{ ucfirst($video->type) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.videos.toggle', $video->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-{{ $video->is_active ? 'success' : 'secondary' }}" 
                                                        title="{{ $video->is_active ? 'Click to disable' : 'Click to enable' }}">
                                                    <i class="fas fa-{{ $video->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                                    {{ $video->is_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.videos.edit', $video->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $video->id }}" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $video->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Delete Video</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete this video?</p>
                                                            <p><strong>{{ $video->title }}</strong></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Delete Video</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="card-body text-center text-muted py-4">
                        <i class="fas fa-inbox" style="font-size: 2rem; opacity: 0.5;"></i>
                        <p class="mt-2">No videos uploaded yet</p>
                        <a href="{{ route('admin.videos.create', $package->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add First Video
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // Enable drag and drop reordering
    const videoSortable = document.getElementById('videoSortable');
    if (videoSortable) {
        new Sortable(videoSortable, {
            handle: '.cursor-move',
            animation: 150,
            onEnd: function(evt) {
                const orders = [];
                document.querySelectorAll('.video-row').forEach((row, index) => {
                    orders.push({
                        id: row.dataset.id,
                        order: index + 1
                    });
                });

                // Send reorder request
                fetch('{{ route("admin.videos.reorder", $package->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ orders: orders })
                });
            }
        });
    }
</script>
@endsection
