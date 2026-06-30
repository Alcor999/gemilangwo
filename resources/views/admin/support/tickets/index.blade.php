@extends('layouts.app')

@section('title', 'Support & Pengaduan - Admin')

@section('content')
<div class="space-y-8 pb-12">
    <div>
        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Customer Service</p>
        <h1 class="font-serif text-4xl text-choco-900 leading-tight">Support & <span class="italic text-stone-400">Pengaduan</span></h1>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-100 text-sm text-emerald-700">{{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label' => 'Terbuka', 'value' => $stats['open'] ?? 0, 'sub' => 'Perlu ditangani', 'accent' => 'border-l-blue-400'],
            ['label' => 'Diproses', 'value' => $stats['in_progress'] ?? 0, 'sub' => 'Sedang dikerjakan', 'accent' => 'border-l-sky-400'],
            ['label' => 'Mendesak', 'value' => $stats['urgent'] ?? 0, 'sub' => 'Prioritas tinggi', 'accent' => 'border-l-rose-400'],
            ['label' => 'Bulan Ini', 'value' => $stats['total'] ?? 0, 'sub' => 'Total tiket', 'accent' => 'border-l-gold-400'],
        ] as $stat)
            <x-luxury.card class="border-stone-100 border-l-4 {{ $stat['accent'] }}">
                <p class="text-[10px] font-bold uppercase tracking-widest text-stone-400 mb-1">{{ $stat['label'] }}</p>
                <p class="font-serif text-3xl text-choco-900">{{ $stat['value'] }}</p>
                <p class="text-xs text-stone-500 mt-1">{{ $stat['sub'] }}</p>
            </x-luxury.card>
        @endforeach
    </div>

    {{-- Filters --}}
    <x-luxury.card class="border-stone-100">
        <form method="GET" action="{{ route('admin.support.tickets.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari subjek atau ID..."
                class="px-4 py-3 bg-white border border-stone-200 rounded-lg text-sm text-choco-900 placeholder:text-stone-400 focus:ring-2 focus:ring-gold-300 focus:border-gold-400 lg:col-span-2">
            <select name="status" class="px-4 py-3 bg-white border border-stone-200 rounded-lg text-sm text-choco-900 focus:ring-2 focus:ring-gold-300">
                <option value="">Semua Status</option>
                @foreach(['open' => 'Terbuka', 'in_progress' => 'Diproses', 'waiting_customer' => 'Menunggu Pelanggan', 'resolved' => 'Selesai', 'closed' => 'Ditutup'] as $val => $label)
                    <option value="{{ $val }}" @selected($status === $val)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="priority" class="px-4 py-3 bg-white border border-stone-200 rounded-lg text-sm text-choco-900 focus:ring-2 focus:ring-gold-300">
                <option value="">Semua Prioritas</option>
                @foreach(['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi', 'urgent' => 'Mendesak'] as $val => $label)
                    <option value="{{ $val }}" @selected($priority === $val)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="category" class="px-4 py-3 bg-white border border-stone-200 rounded-lg text-sm text-choco-900 focus:ring-2 focus:ring-gold-300">
                <option value="">Semua Kategori</option>
                @foreach(['general' => 'Umum', 'order' => 'Pesanan', 'payment' => 'Pembayaran', 'complaint' => 'Pengaduan', 'suggestion' => 'Saran', 'other' => 'Lainnya'] as $val => $label)
                    <option value="{{ $val }}" @selected($category === $val)>{{ $label }}</option>
                @endforeach
            </select>
            <div class="sm:col-span-2 lg:col-span-5 flex gap-3">
                <x-luxury.button type="submit" variant="primary" size="sm">Terapkan Filter</x-luxury.button>
                <x-luxury.button href="{{ route('admin.support.tickets.index') }}" variant="ghost" size="sm">Reset</x-luxury.button>
            </div>
        </form>
    </x-luxury.card>

    {{-- Table --}}
    <x-luxury.card class="overflow-hidden border-stone-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-stone-50/80 border-b border-stone-100 text-[10px] font-bold uppercase tracking-widest text-stone-400">
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Subjek</th>
                        <th class="px-6 py-4">Pelanggan</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Prioritas</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Ditugaskan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-50">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-stone-50/40 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-choco-900">#{{ $ticket->id }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-choco-900">{{ Str::limit($ticket->subject, 45) }}</div>
                                @if($ticket->messages_count ?? $ticket->messages()->count())
                                    <div class="text-[10px] text-stone-400 mt-0.5">{{ $ticket->messages_count ?? $ticket->messages()->count() }} pesan</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-choco-900">{{ $ticket->user->name }}</div>
                                <div class="text-[10px] text-stone-400">{{ $ticket->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500">{{ $ticket->category_label }}</span>
                            </td>
                            <td class="px-6 py-4"><x-support.priority-badge :priority="$ticket->priority" /></td>
                            <td class="px-6 py-4"><x-support.status-badge :status="$ticket->status" /></td>
                            <td class="px-6 py-4 text-xs text-stone-500">
                                {{ $ticket->assignedTo?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.support.tickets.show', $ticket) }}" class="px-3 py-1.5 rounded-lg border border-stone-200 text-[10px] font-bold uppercase tracking-widest text-choco-900 hover:bg-stone-50 transition-colors">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-stone-400 text-sm">Tidak ada tiket ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tickets->hasPages())
            <div class="px-6 py-4 border-t border-stone-100 bg-stone-50/30">
                {{ $tickets->withQueryString()->links() }}
            </div>
        @endif
    </x-luxury.card>
</div>
@endsection
