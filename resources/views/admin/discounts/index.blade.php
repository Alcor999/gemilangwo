@extends('layouts.app')

@section('title', 'Manage Discounts')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-tag"></i> Manage Discounts</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Discount
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><i class="fas fa-tag"></i> Discount Name</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Period</th>
                        <th>Packages</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($discounts as $discount)
                        <tr>
                            <td>
                                <strong>{{ $discount->name }}</strong>
                                @if ($discount->isActive())
                                    <span class="badge bg-success ms-2">Active</span>
                                @else
                                    <span class="badge bg-secondary ms-2">Inactive</span>
                                @endif
                            </td>
                            <td>
                                @if ($discount->type === 'percentage')
                                    <span class="badge bg-info">{{ $discount->value }}%</span>
                                @else
                                    <span class="badge bg-info">Rp {{ number_format($discount->value, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($discount->type) }}</td>
                            <td>
                                <small>
                                    {{ $discount->start_date->format('d M Y') }} - 
                                    @if ($discount->end_date)
                                        {{ $discount->end_date->format('d M Y') }}
                                    @else
                                        No Expiry
                                    @endif
                                </small>
                            </td>
                            <td>
                                @if ($discount->packages->count() > 0)
                                    <small class="text-muted">
                                        {{ $discount->packages->count() }} package(s)
                                    </small>
                                @else
                                    <small class="text-muted">All packages</small>
                                @endif
                            </td>
                            <td>
                                @if ($discount->is_active)
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-danger">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.discounts.show', $discount) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">No discounts found. <a href="{{ route('admin.discounts.create') }}">Create one now!</a></p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $discounts->links() }}
    </div>
</div>

<style>
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        border: none;
        border-radius: 8px;
    }

    table th {
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-sm {
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
    }
</style>
@endsection
