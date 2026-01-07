@extends('layouts.app')

@section('title', 'Video Gallery Management - Admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0">
                <i class="fas fa-video"></i> Video Gallery Management
            </h1>
            <p class="text-muted">Manage package videos and testimonials</p>
        </div>
    </div>

    <!-- Packages with Videos -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-images"></i> Packages Video Gallery</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Package Name</th>
                                <th class="text-center">Videos</th>
                                <th class="text-center">Active</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($packages as $package)
                                <tr>
                                    <td>
                                        <strong>{{ $package->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $package->description ? Str::limit($package->description, 50) : 'No description' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $package->videos()->count() }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $package->videos()->where('is_active', true)->count() }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.videos.show', $package->id) }}" class="btn btn-sm btn-info" title="Manage Videos">
                                            <i class="fas fa-film"></i> Manage
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> No packages found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
