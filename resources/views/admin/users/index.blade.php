@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-12 col-sm-8">
            <h1 class="h3" style="font-size: 1.75rem;">User Management</h1>
        </div>
        <div class="col-12 col-sm-4 text-sm-end mt-2 mt-sm-0">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm w-100 w-sm-auto">Refresh</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-2 p-md-3">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr style="font-size: 0.9rem;">
                            <th>Name</th>
                            <th>Email</th>
                            <th class="d-none d-md-table-cell">Phone</th>
                            <th>Role</th>
                            <th class="d-none d-sm-table-cell">Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr style="font-size: 0.85rem;">
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td class="d-none d-md-table-cell">{{ $user->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'owner' ? 'warning' : 'primary') }}" style="font-size: 0.75rem;">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    @if($user->deleted_at)
                                        <span class="badge bg-danger" style="font-size: 0.75rem;">Inactive</span>
                                    @else
                                        <span class="badge bg-success" style="font-size: 0.75rem;">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.users.deactivate', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger" title="Nonaktifkan"
                                                data-confirm="Apakah Anda yakin ingin menonaktifkan user &quot;{{ $user->name }}&quot;? User tidak akan bisa login."
                                                data-confirm-title="Nonaktifkan User"
                                                data-confirm-btn="Ya, Nonaktifkan"
                                                data-confirm-danger="1">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table-sm {
            font-size: 0.8rem;
        }

        .badge {
            font-size: 0.7rem;
        }

        .btn-sm {
            padding: 0.3rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        h1 {
            font-size: 1.25rem;
        }

        .table-sm {
            font-size: 0.75rem;
        }

        .btn-group-sm > .btn {
            padding: 0.25rem 0.4rem;
        }
    }
</style>
@endsection
