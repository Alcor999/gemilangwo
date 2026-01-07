<!-- Support Tickets Widget -->
<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">
            <i class="fas fa-ticket-alt"></i> Tiket Dukungan Terbaru
        </h6>
        <a href="{{ route($ticketsRoute) }}" class="btn btn-sm btn-light">
            <i class="fas fa-arrow-right"></i> Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        @if ($tickets->isEmpty())
            <div class="p-3 text-center text-muted">
                <i class="fas fa-inbox"></i> Tidak ada tiket
            </div>
        @else
            <div class="list-group list-group-flush">
                @foreach ($tickets as $ticket)
                    <a href="{{ route($ticketDetailRoute, $ticket->id) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    #{{ $ticket->id }} - {{ Str::limit($ticket->subject, 35) }}
                                </h6>
                                <p class="mb-1 small text-muted">
                                    {{ $ticket->user->name ?? 'Customer' }}
                                </p>
                            </div>
                            <div class="text-end ms-2">
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
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-top">
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> {{ $ticket->created_at->diffForHumans() }}
                                <span class="ms-2">
                                    <i class="fas fa-comments"></i> {{ $ticket->messages()->count() }} pesan
                                </span>
                            </small>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .list-group-item-action:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4rem 0.6rem;
    }
</style>
