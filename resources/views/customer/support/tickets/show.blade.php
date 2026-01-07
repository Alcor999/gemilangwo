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
                            <p class="mb-0 small">{{ $ticket->subject }}</p>
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
                                            @if ($message->sender_id !== auth()->id())
                                                <p class="mb-1 small fw-bold">{{ $message->sender->name }}</p>
                                            @endif
                                            <p class="mb-0">{{ $message->message }}</p>
                                        </div>
                                        <small class="d-block mt-1 text-muted">
                                            {{ $message->created_at->format('d M Y H:i') }}
                                            @if ($message->sender_type === 'admin')
                                                <span class="badge bg-success ms-2">Admin</span>
                                            @endif
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
                                placeholder="Ketik pesan Anda..." autocomplete="off" required>
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

        <!-- Sidebar: Ticket Details -->
        <div class="col-lg-4">
            <!-- Ticket Status Card -->
            <div class="card shadow mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle"></i> Informasi Tiket
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted">STATUS</label>
                        <p class="mb-0">
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
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted">PRIORITAS</label>
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

                    <div class="mb-3">
                        <label class="small fw-bold text-muted">KATEGORI</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">
                                {{ ucfirst(str_replace('_', ' ', $ticket->category)) }}
                            </span>
                        </p>
                    </div>

                    @if ($ticket->assigned_to)
                        <div class="mb-3">
                            <label class="small fw-bold text-muted">DITANGANI OLEH</label>
                            <p class="mb-0">
                                <i class="fas fa-user"></i> {{ $ticket->assignedTo->name }}
                            </p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="small fw-bold text-muted">DIBUAT</label>
                        <p class="mb-0">
                            {{ $ticket->created_at->format('d M Y H:i') }}
                        </p>
                    </div>

                    @if ($ticket->first_response_at)
                        <div class="mb-3">
                            <label class="small fw-bold text-muted">RESPON PERTAMA</label>
                            <p class="mb-0">
                                {{ $ticket->first_response_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    @endif

                    @if ($ticket->resolved_at)
                        <div class="mb-3">
                            <label class="small fw-bold text-muted">DISELESAIKAN</label>
                            <p class="mb-0">
                                {{ $ticket->resolved_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if ($ticket->status !== 'closed')
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-cogs"></i> Aksi
                        </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('customer.support.tickets.close', $ticket->id) }}" class="d-grid gap-2">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-times-circle"></i> Tutup Tiket
                            </button>
                        </form>
                    </div>
                </div>
            @endif
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
    messageInput.addEventListener('focus', function () {
        isUserTyping = true;
        // Stop polling while typing
        if (pollInterval) clearInterval(pollInterval);
    });

    messageInput.addEventListener('blur', function () {
        isUserTyping = false;
        // Resume polling after 1 second
        setTimeout(startPolling, 1000);
    });

    // Scroll to bottom
    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Submit message
    messageForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const message = messageInput.value;
        if (!message.trim()) return;

        try {
            const response = await fetch('{{ route("customer.support.tickets.addMessage", $ticket->id) }}', {
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
            const response = await fetch('{{ route("customer.support.tickets.getNewMessages", $ticket->id) }}?last_message_id=' + lastMessageId);
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
        
        const isOwn = message.is_own;
        const justifyClass = isOwn ? 'justify-content-end' : 'justify-content-start';
        const bgColor = isOwn ? 'background-color: #007bff !important;' : '';
        const textColor = isOwn ? 'text-white' : '';
        
        div.innerHTML = `
            <div class="d-flex ${justifyClass}">
                <div class="d-flex gap-2" style="max-width: 80%;">
                    ${!isOwn ? `
                        <div class="flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender_name)}&background=random"
                                alt="${message.sender_name}" class="rounded-circle" width="35" height="35">
                        </div>
                    ` : ''}
                    
                    <div>
                        <div class="bg-white p-3 rounded-3 border ${isOwn ? 'bg-primary text-white' : ''}" style="${bgColor}">
                            ${!isOwn ? `<p class="mb-1 small fw-bold">${message.sender_name}</p>` : ''}
                            <p class="mb-0">${escapeHtml(message.message)}</p>
                        </div>
                        <small class="d-block mt-1 text-muted">
                            ${message.time}
                            ${message.sender_type === 'admin' ? '<span class="badge bg-success ms-2">Admin</span>' : ''}
                        </small>
                    </div>
                    
                    ${isOwn ? `
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
