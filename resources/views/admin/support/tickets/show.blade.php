@extends('layouts.app')

@section('title', 'Tiket #' . $ticket->id)

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <!-- Chat Section -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">
                                <i class="fas fa-ticket-alt"></i> Tiket #{{ $ticket->id }}
                            </h5>
                            <p class="mb-0 small">Dari: <strong>{{ $ticket->user->name }}</strong> ({{ $ticket->user->email }})</p>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark">
                                {{ $ticket->messages()->count() }} Pesan
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div class="card-body" id="chatContainer" data-last-message-id="{{ $ticket->messages()->orderBy('created_at', 'desc')->first()?->id ?? 0 }}" style="height: 500px; overflow-y: auto; background-color: #f8f9fa;">
                    @foreach ($ticket->messages()->orderBy('created_at', 'asc')->get() as $message)
                        <div class="mb-3">
                            <div class="d-flex {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="d-flex gap-2" style="max-width: 80%;">
                                    @if ($message->sender_id !== auth()->id())
                                        <div class="flex-shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}&background=random"
                                                alt="{{ $message->sender->name }}" class="rounded-circle" width="35" height="35">
                                        </div>
                                    @endif

                                    <div>
                                        <div class="bg-white p-3 rounded-3 border {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : '' }}"
                                            style="{{ $message->sender_id === auth()->id() ? 'background-color: #007bff !important;' : '' }}">
                                            <p class="mb-1 small fw-bold">
                                                {{ $message->sender->name }}
                                                @if ($message->sender_type === 'admin')
                                                    <span class="badge bg-success ms-2">Admin</span>
                                                @endif
                                            </p>
                                            <p class="mb-0">{{ $message->message }}</p>
                                        </div>
                                        <small class="d-block mt-1 text-muted">
                                            {{ $message->created_at->format('d M Y H:i') }}
                                        </small>
                                    </div>

                                    @if ($message->sender_id === auth()->id())
                                        <div class="flex-shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}&background=random"
                                                alt="{{ $message->sender->name }}" class="rounded-circle" width="35" height="35">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- New Message Form -->
                @if ($ticket->status !== 'closed')
                    <div class="card-footer bg-light">
                        <form id="messageForm" class="d-flex gap-2">
                            @csrf
                            <input type="text" class="form-control" id="messageInput" name="message"
                                placeholder="Ketik respons Anda..." autocomplete="off" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim
                            </button>
                        </form>
                    </div>
                @else
                    <div class="card-footer bg-danger text-white text-center">
                        <i class="fas fa-lock"></i> Tiket ini telah ditutup
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar: Ticket Details & Actions -->
        <div class="col-lg-4">
            <!-- Ticket Info -->
            <div class="card shadow mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i> Informasi Tiket
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted">SUBJEK</label>
                        <p class="mb-0">{{ $ticket->subject }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted">DESKRIPSI</label>
                        <p class="mb-0 small">{{ $ticket->description }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted">KATEGORI</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">
                                {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                            </span>
                        </p>
                    </div>

                    @if ($ticket->order_id)
                        <div class="mb-3">
                            <label class="small fw-bold text-muted">PESANAN TERKAIT</label>
                            <p class="mb-0">
                                <a href="{{ route('admin.orders.show', $ticket->order_id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-shopping-bag"></i> Order #{{ $ticket->order_id }}
                                </a>
                            </p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="small fw-bold text-muted">DIBUAT</label>
                        <p class="mb-0">
                            {{ $ticket->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Status & Priority Management -->
            @if ($ticket->status !== 'closed')
                <div class="card shadow mb-3">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-sliders-h"></i> Manajemen
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Status Update -->
                        <div class="mb-3">
                            <label for="status" class="form-label small fw-bold">Status</label>
                            <form method="POST" action="{{ route('admin.support.tickets.updateStatus', $ticket->id) }}" id="statusForm">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <select class="form-select form-select-sm" name="status" id="status">
                                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Terbuka</option>
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>Sedang Diproses</option>
                                        <option value="waiting_customer" {{ $ticket->status === 'waiting_customer' ? 'selected' : '' }}>Menunggu Pelanggan</option>
                                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>Diselesaikan</option>
                                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-check"></i> Perbarui
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Priority -->
                        <div class="mb-3">
                            <label class="small fw-bold text-muted">PRIORITAS SAAT INI</label>
                            <p class="mb-0">
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
                            </p>
                        </div>

                        <!-- Assignment -->
                        <div class="mb-3">
                            <label for="assigned_to" class="form-label small fw-bold">Tugaskan Ke Admin</label>
                            <form method="POST" action="{{ route('admin.support.tickets.assign', $ticket->id) }}" id="assignForm">
                                @csrf
                                @method('PATCH')
                                <div class="input-group">
                                    <select class="form-select form-select-sm" name="assigned_to" id="assigned_to">
                                        <option value="">- Belum ditugaskan -</option>
                                        @foreach ($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ $ticket->assigned_to === $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-check"></i> Tugaskan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Internal Notes -->
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-sticky-note"></i> Catatan Internal
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.support.tickets.addNotes', $ticket->id) }}" id="notesForm">
                        @csrf
                        @method('PATCH')
                        <textarea class="form-control form-control-sm" name="internal_notes" rows="5"
                            placeholder="Catatan untuk tim admin...">{{ $ticket->internal_notes }}</textarea>
                        <button type="submit" class="btn btn-sm btn-primary mt-2 w-100">
                            <i class="fas fa-save"></i> Simpan Catatan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for real-time message polling -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatContainer = document.getElementById('chatContainer');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    
    let lastMessageId = parseInt(chatContainer.dataset.lastMessageId || 0);
    let isUserTyping = false;
    let pollInterval = null;

    // Track if user is typing
    messageInput?.addEventListener('focus', function () {
        isUserTyping = true;
        // Stop polling while typing
        if (pollInterval) clearInterval(pollInterval);
    });

    messageInput?.addEventListener('blur', function () {
        isUserTyping = false;
        // Resume polling after 1 second
        setTimeout(startPolling, 1000);
    });

    // Scroll to bottom
    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Submit message
    messageForm?.addEventListener('submit', async function (e) {
        e.preventDefault();

        const message = messageInput.value;
        if (!message.trim()) return;

        try {
            const response = await fetch('{{ route("admin.support.tickets.addMessage", $ticket->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({ message }),
            });

            if (response.ok) {
                messageInput.value = '';
                isUserTyping = false;
                loadNewMessages();
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal mengirim pesan');
        }
    });

    // Poll for new messages without page reload
    async function loadNewMessages() {
        try {
            const response = await fetch('{{ route("admin.support.tickets.getNewMessages", $ticket->id) }}?last_message_id=' + lastMessageId);
            const data = await response.json();

            if (data.messages && data.messages.length > 0) {
                // Add new messages to chat
                data.messages.forEach(message => {
                    const messageDiv = createMessageElement(message);
                    chatContainer.appendChild(messageDiv);
                    lastMessageId = message.id;
                    // Update data attribute to prevent duplicates on reload
                    chatContainer.dataset.lastMessageId = lastMessageId;
                });
                
                // Scroll to bottom
                setTimeout(scrollToBottom, 100);
            }
        } catch (error) {
            console.error('Error loading messages:', error);
        }
    }

    // Create message DOM element
    function createMessageElement(message) {
        const div = document.createElement('div');
        div.className = 'mb-3';
        
        const isAdmin = message.is_admin;
        const justifyClass = isAdmin ? 'justify-content-end' : 'justify-content-start';
        const bgColor = isAdmin ? 'background-color: #007bff !important;' : '';
        const textColor = isAdmin ? 'text-white' : '';
        
        div.innerHTML = `
            <div class="d-flex ${justifyClass}">
                <div class="d-flex gap-2" style="max-width: 80%;">
                    ${!isAdmin ? `
                        <div class="flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender_name)}&background=random"
                                alt="${message.sender_name}" class="rounded-circle" width="35" height="35">
                        </div>
                    ` : ''}
                    
                    <div>
                        <div class="bg-white p-3 rounded-3 border ${isAdmin ? 'bg-primary text-white' : ''}" style="${bgColor}">
                            ${!isAdmin ? `<p class="mb-1 small fw-bold">${message.sender_name}</p>` : ''}
                            <p class="mb-0">${escapeHtml(message.message)}</p>
                        </div>
                        <small class="d-block mt-1 text-muted">
                            ${message.time}
                            ${isAdmin ? '<span class="badge bg-success ms-2">Admin</span>' : ''}
                        </small>
                    </div>
                    
                    ${isAdmin ? `
                        <div class="flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender_name)}&background=random"
                                alt="${message.sender_name}" class="rounded-circle" width="35" height="35">
                        </div>
                    ` : ''}
                </div>
            </div>
        `;
        
        return div;
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Start polling
    function startPolling() {
        if (!isUserTyping && !pollInterval) {
            // Poll every 5 seconds when not typing, slower to reduce server load
            pollInterval = setInterval(loadNewMessages, 5000);
        }
    }

    // Stop polling
    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
    }

    // Initial scroll
    scrollToBottom();
    
    // Start polling
    startPolling();
});
</script>

<style>
    #chatContainer {
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
    }

    .rounded-3 {
        border-radius: 15px;
    }

    .bg-white {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
    }

    @media (max-width: 992px) {
        #chatContainer {
            height: 400px !important;
        }
    }
</style>
@endsection
