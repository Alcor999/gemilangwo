@extends('layouts.app')

@section('title', 'Tiket Dukungan Saya')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-ticket-alt"></i> Tiket Dukungan Saya
                </h1>
                <a href="{{ route('customer.support.tickets.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Buat Tiket Baru
                </a>
            </div>

            @if ($tickets->isEmpty())
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle"></i>
                    Anda belum memiliki tiket dukungan. 
                    <a href="{{ route('customer.support.tickets.create') }}">Buat tiket baru</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Subjek</th>
                                <th>Kategori</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Pesan</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $ticket)
                                <tr>
                                    <td>
                                        <strong>#{{ $ticket->id }}</strong>
                                    </td>
                                    <td>
                                        {{ Str::limit($ticket->subject, 30) }}
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
                                                <span class="badge bg-info">Sedang Diproses</span>
                                            @break
                                            @case('waiting_customer')
                                                <span class="badge bg-warning">Menunggu Anda</span>
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
                                        <span class="badge bg-light text-dark">{{ $ticket->messages()->count() }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $ticket->created_at->format('d M Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.support.tickets.show', $ticket->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    {{ $tickets->links() }}
                </nav>
            @endif
        </div>
    </div>
</div>

<style>
    .table {
        font-size: 0.95rem;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }

    .btn-sm {
        padding: 0.3rem 0.6rem;
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
            align-items: flex-start;
        }

        .btn {
            margin-top: 10px;
        }
    }
</style>
@endsection
