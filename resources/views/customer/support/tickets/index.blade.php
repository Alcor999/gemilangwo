@extends('layouts.app')

@section('title', 'Pusat Bantuan - Gemilang WO')

@section('content')
<div class="space-y-8 pb-12">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.3em] mb-2">Customer Service</p>
            <h1 class="font-serif text-4xl text-choco-900 leading-tight">Pusat <span class="italic text-stone-400">Bantuan</span></h1>
            <p class="text-sm text-stone-500 mt-2 max-w-xl">Ajukan pertanyaan, pengaduan, atau saran. Tim kami siap membantu Anda.</p>
        </div>
        <x-luxury.button href="{{ route('customer.support.tickets.create') }}" variant="primary" size="sm">
            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Buat Tiket Baru
        </x-luxury.button>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-100 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if($tickets->isEmpty())
        <x-luxury.card class="text-center py-16 border-dashed border-stone-200">
            <div class="max-w-md mx-auto space-y-4">
                <div class="w-16 h-16 mx-auto rounded-full bg-stone-50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-stone-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-serif text-xl text-choco-900">Belum ada tiket bantuan</h3>
                <p class="text-sm text-stone-500">Butuh bantuan terkait pesanan atau pembayaran? Buat tiket dan tim kami akan merespons secepatnya.</p>
                <x-luxury.button href="{{ route('customer.support.tickets.create') }}" variant="outline" size="sm">
                    Buat Tiket Pertama
                </x-luxury.button>
            </div>
        </x-luxury.card>
    @else
        <x-luxury.card class="overflow-hidden border-stone-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-stone-50/80 border-b border-stone-100 text-[10px] font-bold uppercase tracking-widest text-stone-400">
                            <th class="px-6 py-4">Tiket</th>
                            <th class="px-6 py-4">Subjek</th>
                            <th class="px-6 py-4">Kategori</th>
                            <th class="px-6 py-4">Prioritas</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Pesan</th>
                            <th class="px-6 py-4">Dibuat</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-50">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-stone-50/40 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-choco-900">#{{ $ticket->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-choco-900">{{ Str::limit($ticket->subject, 40) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-stone-100 text-stone-600 uppercase tracking-wider">{{ $ticket->category_label }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <x-support.priority-badge :priority="$ticket->priority" />
                                </td>
                                <td class="px-6 py-4">
                                    <x-support.status-badge :status="$ticket->status" />
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-stone-500">{{ $ticket->messages_count ?? $ticket->messages()->count() }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs text-stone-500">
                                    {{ $ticket->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('customer.support.tickets.show', $ticket) }}" class="px-3 py-1.5 rounded-lg border border-stone-200 text-[10px] font-bold uppercase tracking-widest text-choco-900 hover:bg-stone-50 transition-colors">
                                        Buka
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($tickets->hasPages())
                <div class="px-6 py-4 border-t border-stone-100 bg-stone-50/30">
                    {{ $tickets->links() }}
                </div>
            @endif
        </x-luxury.card>
    @endif
</div>
@endsection
