@extends('layouts.app')

@section('title', 'Tiket #' . $ticket->id . ' - Admin')

@section('content')
@php $lastMessageId = $messages->last()?->id ?? 0; @endphp

<div class="space-y-6 pb-12">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <a href="{{ route('admin.support.tickets.index') }}" class="inline-flex items-center text-[10px] font-bold uppercase tracking-widest text-stone-400 hover:text-gold-500 transition-colors mb-3">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Daftar Tiket
            </a>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-1">Tiket #{{ $ticket->id }}</p>
            <h1 class="font-serif text-2xl sm:text-3xl text-choco-900">{{ $ticket->subject }}</h1>
            <p class="text-sm text-stone-500 mt-1">
                Dari <strong class="text-choco-900">{{ $ticket->user->name }}</strong> · {{ $ticket->user->email }}
            </p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <x-support.status-badge :status="$ticket->status" />
            <x-support.priority-badge :priority="$ticket->priority" />
        </div>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-100 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <x-luxury.card padding="p-0" class="flex flex-col border-stone-100">
                <div class="px-6 py-4 border-b border-stone-100 bg-stone-50/50 flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-stone-400">Percakapan dengan Pelanggan</span>
                    <span class="text-xs text-stone-500">{{ $messages->count() }} pesan</span>
                </div>

                <div id="chatContainer"
                     data-last-message-id="{{ $lastMessageId }}"
                     data-perspective="admin"
                     class="flex-1 h-[420px] sm:h-[480px] overflow-y-auto px-4 sm:px-6 py-4 space-y-4 bg-stone-50/30">
                    @foreach($messages as $message)
                        @php $isAdmin = $message->sender_type === 'admin'; @endphp
                        <div class="flex {{ $isAdmin ? 'justify-end' : 'justify-start' }}">
                            <div class="flex gap-2 max-w-[85%] {{ $isAdmin ? 'flex-row-reverse' : '' }}">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}&background={{ $isAdmin ? 'D4AF37' : '78716c' }}&color=fff&size=64"
                                     alt="" class="w-8 h-8 rounded-full flex-shrink-0 ring-2 ring-white shadow-sm">
                                <div>
                                    <div class="px-4 py-3 rounded-2xl text-sm {{ $isAdmin ? 'bg-choco-900 text-gold-100 rounded-tr-sm' : 'bg-white border border-stone-100 text-choco-900 rounded-tl-sm shadow-sm' }}">
                                        @if(!$isAdmin)
                                            <p class="text-[10px] font-bold uppercase tracking-wider mb-1 text-stone-400">{{ $message->sender->name }} · Pelanggan</p>
                                        @endif
                                        <p class="leading-relaxed whitespace-pre-wrap">{{ $message->message }}</p>
                                    </div>
                                    <p class="text-[10px] text-stone-400 mt-1 {{ $isAdmin ? 'text-right' : '' }}">{{ $message->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($ticket->status !== 'closed')
                    <div class="px-4 sm:px-6 py-4 border-t border-stone-100 bg-white">
                        <form id="messageForm" class="flex gap-2">
                            @csrf
                            <input type="text" id="messageInput" name="message" placeholder="Ketik balasan untuk pelanggan..."
                                class="flex-1 px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm text-choco-900 placeholder:text-stone-400 focus:ring-2 focus:ring-gold-300 focus:border-gold-400 transition-all"
                                autocomplete="off" required>
                            <x-luxury.button type="submit" variant="primary" size="sm" class="flex-shrink-0">Kirim</x-luxury.button>
                        </form>
                    </div>
                @else
                    <div class="px-6 py-4 border-t border-stone-100 bg-stone-100 text-center text-sm text-stone-500">Tiket ditutup</div>
                @endif
            </x-luxury.card>
        </div>

        <div class="space-y-4">
            <x-luxury.card class="border-stone-100">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-4">Detail Tiket</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Deskripsi Awal</dt>
                        <dd class="text-stone-600 mt-1 leading-relaxed">{{ $ticket->description }}</dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Kategori</dt>
                        <dd class="text-choco-900">{{ $ticket->category_label }}</dd>
                    </div>
                    @if($ticket->order_id)
                        <div>
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Pesanan Terkait</dt>
                            <dd class="mt-1">
                                <a href="{{ route('admin.orders.show', $ticket->order_id) }}" class="text-gold-600 hover:text-gold-700 text-sm font-medium">
                                    Pesanan #{{ $ticket->order_id }} →
                                </a>
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-stone-400">Dibuat</dt>
                        <dd class="text-stone-600">{{ $ticket->created_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
            </x-luxury.card>

            @if($ticket->status !== 'closed')
                <x-luxury.card class="border-stone-100">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-4">Manajemen</h3>

                    <form method="POST" action="{{ route('admin.support.tickets.updateStatus', $ticket) }}" class="space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="status" class="block text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-1.5">Status</label>
                            <div class="flex gap-2">
                                <select id="status" name="status" class="flex-1 px-3 py-2.5 bg-white border border-stone-200 rounded-lg text-sm focus:ring-2 focus:ring-gold-300">
                                    @foreach(['open' => 'Terbuka', 'in_progress' => 'Diproses', 'waiting_customer' => 'Menunggu Pelanggan', 'resolved' => 'Selesai', 'closed' => 'Ditutup'] as $val => $label)
                                        <option value="{{ $val }}" @selected($ticket->status === $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-luxury.button type="submit" variant="secondary" size="sm">Simpan</x-luxury.button>
                            </div>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.support.tickets.assign', $ticket) }}" class="pt-4 border-t border-stone-100">
                        @csrf
                        @method('PATCH')
                        <label for="assigned_to" class="block text-[10px] font-bold uppercase tracking-wider text-stone-400 mb-1.5">Tugaskan Admin</label>
                        <div class="flex gap-2">
                            <select id="assigned_to" name="assigned_to" required class="flex-1 px-3 py-2.5 bg-white border border-stone-200 rounded-lg text-sm focus:ring-2 focus:ring-gold-300">
                                <option value="">Pilih admin...</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" @selected($ticket->assigned_to === $admin->id)>{{ $admin->name }}</option>
                                @endforeach
                            </select>
                            <x-luxury.button type="submit" variant="outline" size="sm">Tugaskan</x-luxury.button>
                        </div>
                    </form>
                </x-luxury.card>
            @endif

            <x-luxury.card class="border-stone-100">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-3">Catatan Internal</h3>
                <p class="text-xs text-stone-400 mb-3">Hanya terlihat oleh tim admin.</p>
                <form method="POST" action="{{ route('admin.support.tickets.addNotes', $ticket) }}">
                    @csrf
                    @method('PATCH')
                    <textarea name="internal_notes" rows="4" placeholder="Catatan untuk tim..."
                        class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-sm text-choco-900 focus:ring-2 focus:ring-gold-300 resize-y">{{ $ticket->internal_notes }}</textarea>
                    <x-luxury.button type="submit" variant="ghost" size="sm" class="w-full mt-3">Simpan Catatan</x-luxury.button>
                </form>
            </x-luxury.card>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatContainer = document.getElementById('chatContainer');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    if (!chatContainer) return;

    let lastMessageId = parseInt(chatContainer.dataset.lastMessageId || 0);
    let isUserTyping = false;
    let pollInterval = null;

    messageInput?.addEventListener('focus', () => {
        isUserTyping = true;
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = null;
    });

    messageInput?.addEventListener('blur', () => {
        isUserTyping = false;
        setTimeout(startPolling, 1000);
    });

    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    messageForm?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;

        try {
            const response = await fetch('{{ route("admin.support.tickets.addMessage", $ticket) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({ message }),
            });

            if (response.ok) {
                messageInput.value = '';
                isUserTyping = false;
                await loadNewMessages();
            }
        } catch (error) {
            alert('Gagal mengirim pesan.');
        }
    });

    async function loadNewMessages() {
        try {
            const response = await fetch('{{ route("admin.support.tickets.getNewMessages", $ticket) }}?last_message_id=' + lastMessageId);
            const data = await response.json();

            if (data.messages?.length) {
                data.messages.forEach(message => {
                    chatContainer.appendChild(createMessageElement(message));
                    lastMessageId = message.id;
                    chatContainer.dataset.lastMessageId = lastMessageId;
                });
                setTimeout(scrollToBottom, 100);
            }
        } catch (error) {
            console.error(error);
        }
    }

    function createMessageElement(message) {
        const isAdmin = message.is_admin;
        const wrapper = document.createElement('div');
        wrapper.className = 'flex ' + (isAdmin ? 'justify-end' : 'justify-start');

        wrapper.innerHTML = `
            <div class="flex gap-2 max-w-[85%] ${isAdmin ? 'flex-row-reverse' : ''}">
                <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender_name)}&background=${isAdmin ? 'D4AF37' : '78716c'}&color=fff&size=64"
                     alt="" class="w-8 h-8 rounded-full flex-shrink-0 ring-2 ring-white shadow-sm">
                <div>
                    <div class="px-4 py-3 rounded-2xl text-sm ${isAdmin ? 'bg-choco-900 text-gold-100 rounded-tr-sm' : 'bg-white border border-stone-100 text-choco-900 rounded-tl-sm shadow-sm'}">
                        ${!isAdmin ? `<p class="text-[10px] font-bold uppercase tracking-wider mb-1 text-stone-400">${escapeHtml(message.sender_name)} · Pelanggan</p>` : ''}
                        <p class="leading-relaxed whitespace-pre-wrap">${escapeHtml(message.message)}</p>
                    </div>
                    <p class="text-[10px] text-stone-400 mt-1 ${isAdmin ? 'text-right' : ''}">${message.time}</p>
                </div>
            </div>`;

        return wrapper;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function startPolling() {
        if (!isUserTyping && !pollInterval) {
            pollInterval = setInterval(loadNewMessages, 5000);
        }
    }

    scrollToBottom();
    startPolling();
});
</script>
@endpush
@endsection
