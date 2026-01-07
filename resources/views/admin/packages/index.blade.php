@extends('layouts.app')

@section('title', 'Manage Packages')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Packages</h1>
        <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Package
        </a>
    </div>

    @if($packages->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Package Name</th>
                        <th>Price</th>
                        <th>Max Guests</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $package)
                        <tr>
                            <td>
                                <strong>{{ $package->name }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($package->description, 50) }}</small>
                            </td>
                            <td><strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong></td>
                            <td>{{ $package->max_guests ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $package->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($package->status) }}
                                </span>
                            </td>
                            <td>{{ $package->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('admin.packages.edit', $package->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.packages.destroy', $package->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            No packages yet. <a href="{{ route('admin.packages.create') }}">Create the first package</a>
        </div>
    @endif
</div>
@endsection
