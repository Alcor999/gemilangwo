@extends('layouts.app')

@section('title', 'Manajemen Dukungan Pelanggan')

@section('content')
<div class="container-fluid mt-4">
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Tiket Terbuka</h6>
                    <h3 class="mb-0">{{ $stats['open'] ?? 0 }}</h3>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-primary">Membutuhkan perhatian</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Sedang Diproses</h6>
                    <h3 class="mb-0">{{ $stats['in_progress'] ?? 0 }}</h3>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-info">Dalam penanganan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Mendesak</h6>
                    <h3 class="mb-0">{{ $stats['urgent'] ?? 0 }}</h3>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-danger">Prioritas tinggi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <h6 class="card-title text-muted">Total Bulan Ini</h6>
                    <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">Semua tiket</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-ticket-alt"></i> Tiket Dukungan
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.support.tickets.index') }}" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Cari subjek atau ID..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">Semua Status</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Terbuka</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="waiting_customer" {{ request('status') === 'waiting_customer' ? 'selected' : '' }}>Menunggu Pelanggan</option>
                        <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Diselesaikan</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Ditutup</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="priority">
                        <option value="">Semua Prioritas</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Rendah</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Sedang</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Tinggi</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Mendesak</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="category">
                        <option value="">Semua Kategori</option>
                        <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>Umum</option>
                        <option value="order" {{ request('category') === 'order' ? 'selected' : '' }}>Pesanan</option>
                        <option value="payment" {{ request('category') === 'payment' ? 'selected' : '' }}>Pembayaran</option>
                        <option value="complaint" {{ request('category') === 'complaint' ? 'selected' : '' }}>Keluhan</option>
                        <option value="suggestion" {{ request('category') === 'suggestion' ? 'selected' : '' }}>Saran</option>
                        <option value="other" {{ request('category') === 'other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="card shadow">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="10%">#</th>
                        <th width="25%">Subjek</th>
                        <th width="15%">Pelanggan</th>
                        <th width="10%">Kategori</th>
                        <th width="10%">Prioritas</th>
                        <th width="10%">Status</th>
                        <th width="15%">Ditugaskan Ke</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr>
                            <td>
                                <strong>#{{ $ticket->id }}</strong>
                            </td>
                            <td>
                                {{ Str::limit($ticket->subject, 40) }}
                                @if ($ticket->messages()->count() > 0)
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-comments"></i> {{ $ticket->messages()->count() }} pesan
                                    </small>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $ticket->user->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $ticket->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                                </span>
                            </td>
                            <td>
                                @switch($ticket->priority)
                                    @case('low')
                                        <span class="badge bg-success">Rendah</span>
                                    @break
                                    @case('medium')
                                        <span class="badge bg-warning">Sedang</span>
                                    @break
                                    @case('high')
                                        <span class="badge bg-danger">Tinggi</span>
                                    @break
                                    @case('urgent')
                                        <span class="badge bg-dark">Mendesak</span>
                                    @break
                                @endswitch
                            </td>
                            <td>
                                @switch($ticket->status)
                                    @case('open')
                                        <span class="badge bg-primary">Terbuka</span>
                                    @break
                                    @case('in_progress')
                                        <span class="badge bg-info">Diproses</span>
                                    @break
                                    @case('waiting_customer')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @break
                                    @case('resolved')
                                        <span class="badge bg-success">Diselesaikan</span>
                                    @break
                                    @case('closed')
                                        <span class="badge bg-secondary">Ditutup</span>
                                    @break
                                @endswitch
                            </td>
                            <td>
                                @if ($ticket->assigned_to)
                                    <small>{{ $ticket->assignedTo->name }}</small>
                                @else
                                    <small class="text-muted"><em>Belum ditugaskan</em></small>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.support.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> Tidak ada tiket
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tickets->hasPages())
            <div class="card-footer bg-light">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .stat-card {
        border-left: 4px solid #007bff;
    }

    .stat-card:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    .stat-card .card-title {
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-card h3 {
        font-weight: 700;
        color: #2c3e50;
    }

    .table {
        font-size: 0.95rem;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.6rem;
    }

    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.85rem;
        }
    }
</style>
@endsection
