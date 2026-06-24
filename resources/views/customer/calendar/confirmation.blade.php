@extends('layouts.app')

@section('title', 'Kalender Acara Saya - Gemilang WO')
@section('header_title', 'Kalender Acara')

@section('content')
<div class="space-y-8">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-serif font-bold text-choco-900 italic">Kalender Acara Pernikahan</h1>
            <p class="mt-1 text-sm text-stone-500">Pantau dan kelola jadwal acara pernikahan Anda</p>
        </div>
        <a href="{{ route('customer.calendar.export-confirmation') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gold-300 text-gold-700 text-sm font-bold hover:bg-gold-50 transition-all">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Ekspor iCal
        </a>
    </div>

    {{-- Stats Row --}}
    @php
        $upcomingEvent = $confirmedOrders->first(fn($o) => $o->calendarEvent && $o->calendarEvent->event_date >= now());
        $totalEvents = $confirmedOrders->count();
        $upcomingCount = $confirmedOrders->filter(fn($o) => $o->calendarEvent && $o->calendarEvent->event_date >= now())->count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-choco-50 rounded-xl flex items-center justify-center">
                <svg class="h-6 w-6 text-choco-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-choco-900">{{ $totalEvents }}</p>
                <p class="text-xs text-stone-500 font-medium uppercase tracking-wider">Total Acara</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-gold-50 rounded-xl flex items-center justify-center">
                <svg class="h-6 w-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-choco-900">{{ $upcomingCount }}</p>
                <p class="text-xs text-stone-500 font-medium uppercase tracking-wider">Acara Mendatang</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-stone-100 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-choco-900">{{ $totalEvents - $upcomingCount }}</p>
                <p class="text-xs text-stone-500 font-medium uppercase tracking-wider">Selesai</p>
            </div>
        </div>
    </div>

    {{-- Main Layout: Calendar + Sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Calendar Panel --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden">
            {{-- Month Navigation --}}
            <div class="px-6 py-4 border-b border-stone-100 bg-gradient-to-r from-choco-800 to-choco-900 flex items-center justify-between">
                <h2 class="text-lg font-serif font-bold text-white italic">
                    {{ now()->createFromDate($year, $month, 1)->locale('id_ID')->isoFormat('MMMM YYYY') }}
                </h2>
                <div class="flex gap-2">
                    <form action="{{ route('customer.calendar.confirmation') }}" method="GET">
                        <input type="hidden" name="month" value="{{ $month == 1 ? 12 : $month - 1 }}">
                        <input type="hidden" name="year" value="{{ $month == 1 ? $year - 1 : $year }}">
                        <button type="submit" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm rounded-lg transition-all">
                            ← Sebelumnya
                        </button>
                    </form>
                    <form action="{{ route('customer.calendar.confirmation') }}" method="GET">
                        <input type="hidden" name="month" value="{{ $month == 12 ? 1 : $month + 1 }}">
                        <input type="hidden" name="year" value="{{ $month == 12 ? $year + 1 : $year }}">
                        <button type="submit" class="px-3 py-1.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-sm rounded-lg transition-all">
                            Selanjutnya →
                        </button>
                    </form>
                </div>
            </div>

            <div class="p-6">
                {{-- Day Headers --}}
                <div class="grid grid-cols-7 gap-1 mb-2">
                    @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                        <div class="text-center text-[10px] font-bold uppercase tracking-wider text-stone-400 py-2">{{ $day }}</div>
                    @endforeach
                </div>

                {{-- Calendar Days --}}
                <div class="grid grid-cols-7 gap-1 mb-6">
                    @php
                        $firstDay = $startOfMonth->copy()->startOfWeek();
                        $lastDay = $endOfMonth->copy()->endOfWeek();
                        $currentDay = $firstDay->copy();
                    @endphp

                    @while($currentDay <= $lastDay)
                        @php
                            $dateStr = $currentDay->toDateString();
                            $isCurrentMonth = $currentDay->month === $startOfMonth->month;
                            $isToday = $currentDay->isToday();
                            $hasEvent = isset($eventsData[$dateStr]);

                            $firstEvent = null;
                            if ($hasEvent && count($eventsData[$dateStr]) > 0) {
                                foreach ($confirmedOrders as $order) {
                                    if ($order->calendarEvent && $order->calendarEvent->event_date->toDateString() === $dateStr) {
                                        $firstEvent = $order->calendarEvent;
                                        break;
                                    }
                                }
                            }
                        @endphp

                        @if($hasEvent && $isCurrentMonth && $firstEvent)
                            <a href="{{ route('customer.calendar.event-details', ['event' => $firstEvent]) }}"
                               class="relative aspect-square rounded-xl flex flex-col items-center justify-center p-1 bg-gradient-to-br from-gold-500 to-gold-600 text-white shadow-lg shadow-gold-500/30 hover:scale-105 transition-all {{ $isToday ? 'ring-2 ring-offset-2 ring-choco-700' : '' }} text-sm font-bold z-10">
                                <span>{{ $currentDay->day }}</span>
                                <span class="text-[8px] font-bold opacity-90 mt-0.5">Acara</span>
                            </a>
                        @elseif($isToday)
                            <div class="relative aspect-square rounded-xl flex items-center justify-center ring-2 ring-choco-700 ring-offset-2 bg-choco-900 text-white text-sm font-bold shadow-md z-10">
                                {{ $currentDay->day }}
                            </div>
                        @else
                            <div class="relative aspect-square rounded-xl flex items-center justify-center text-sm border border-stone-100 {{ $isCurrentMonth ? 'text-choco-700 bg-stone-50 hover:bg-stone-100' : 'text-stone-300 bg-white/50' }} {{ !$isCurrentMonth ? 'opacity-50' : '' }} transition-colors">
                                {{ $currentDay->day }}
                            </div>
                        @endif

                        @php $currentDay->addDay(); @endphp
                    @endwhile
                </div>

                {{-- Legend --}}
                <div class="border-t border-stone-100 pt-4 flex items-center gap-6 flex-wrap">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-md bg-stone-100 border border-stone-200"></div>
                        <span class="text-xs text-stone-500">Tidak Ada Acara</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-md bg-gold-500"></div>
                        <span class="text-xs text-stone-500">Acara Pernikahan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded-md bg-choco-900 ring-2 ring-choco-700 ring-offset-1"></div>
                        <span class="text-xs text-stone-500">Hari Ini</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar: Events List --}}
        <div class="space-y-4">
            {{-- Upcoming Event Card --}}
            @if($upcomingEvent && $upcomingEvent->calendarEvent)
                <div class="bg-gradient-to-br from-choco-800 to-choco-900 rounded-2xl p-5 text-white">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gold-400 mb-3">✨ Acara Mendatang Anda</p>
                    <h3 class="font-serif font-bold text-lg italic mb-1">{{ $upcomingEvent->package->name }}</h3>
                    <p class="text-gold-300 text-2xl font-bold mb-4">{{ $upcomingEvent->calendarEvent->event_date->translatedFormat('d F Y') }}</p>
                    @php
                        $daysLeft = now()->startOfDay()->diffInDays($upcomingEvent->calendarEvent->event_date->copy()->startOfDay());
                    @endphp
                    @if($daysLeft > 0)
                        <div class="bg-white/10 rounded-xl p-3 mb-4 text-center">
                            <p class="text-3xl font-bold text-gold-400">{{ $daysLeft }}</p>
                            <p class="text-xs text-white/70">hari lagi</p>
                        </div>
                    @endif
                    <a href="{{ route('customer.calendar.event-details', $upcomingEvent->calendarEvent) }}"
                       class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-gold-500 hover:bg-gold-600 text-white text-sm font-bold transition-all">
                        Lihat Detail Acara →
                    </a>
                </div>
            @endif

            {{-- Events List --}}
            <div class="bg-white rounded-2xl border border-stone-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-stone-100 bg-stone-50">
                    <h3 class="font-bold text-choco-900 text-sm">Semua Acara</h3>
                    <p class="text-xs text-stone-400 mt-0.5">{{ $totalEvents }} acara terdaftar</p>
                </div>
                <div class="divide-y divide-stone-50 max-h-96 overflow-y-auto">
                    @if($confirmedOrders->count() > 0)
                        @foreach($confirmedOrders as $order)
                            @if($order->calendarEvent)
                                <a href="{{ route('customer.calendar.event-details', $order->calendarEvent) }}"
                                   class="flex items-start gap-3 p-4 hover:bg-stone-50 transition-colors group">
                                    <div class="shrink-0 w-10 h-10 rounded-xl {{ $order->calendarEvent->event_date >= now() ? 'bg-gold-50' : 'bg-stone-100' }} flex flex-col items-center justify-center">
                                        <span class="text-xs font-bold {{ $order->calendarEvent->event_date >= now() ? 'text-gold-700' : 'text-stone-500' }}">
                                            {{ $order->calendarEvent->event_date->format('d') }}
                                        </span>
                                        <span class="text-[8px] font-bold {{ $order->calendarEvent->event_date >= now() ? 'text-gold-500' : 'text-stone-400' }} uppercase">
                                            {{ $order->calendarEvent->event_date->format('M') }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-choco-900 truncate group-hover:text-gold-700 transition-colors">{{ $order->package->name }}</p>
                                        <p class="text-xs text-stone-400 mt-0.5">{{ $order->calendarEvent->event_date->translatedFormat('l, d F Y') }}</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold {{ $order->calendarEvent->event_date >= now() ? 'bg-gold-50 text-gold-700' : 'bg-stone-100 text-stone-500' }}">
                                            {{ $order->calendarEvent->event_date >= now() ? 'Akan Datang' : 'Selesai' }}
                                        </span>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    @else
                        <div class="p-8 text-center">
                            <div class="w-12 h-12 bg-stone-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <svg class="h-6 w-6 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-stone-400">Belum ada acara terdaftar</p>
                            <a href="{{ route('customer.packages.index') }}" class="mt-3 inline-block text-xs font-bold text-gold-600 hover:text-gold-700">
                                Pilih Paket →
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
