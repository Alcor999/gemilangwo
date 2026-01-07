@extends('layouts.app')

@section('title', 'Kalender Konfirmasi Acara')

@section('content')
<!-- Header Section -->
<div style="background: #f8fafc; padding: 1.5rem 0; margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0;">
    <h1 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem;">✅ Kalender Konfirmasi Acara</h1>
    <p style="color: #64748b; margin: 0; font-size: 0.95rem;">Kelola dan konfirmasi acara pernikahan Anda</p>
</div>

<!-- Export Button -->
<div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
    <a href="{{ route('customer.calendar.export-confirmation') }}" 
       style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: #10b981; color: white; border-radius: 0.375rem; text-decoration: none; font-weight: 600; font-size: 0.95rem; border: none; cursor: pointer; transition: background 0.2s;">
        📥 Export ke iCal
    </a>
</div>

<!-- Main Grid -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Calendar Panel -->
    <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden;">
        <!-- Month Navigation -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0;">
                📆 {{ now()->createFromDate($year, $month, 1)->locale('id_ID')->format('F Y') }}
            </h2>
            <div style="display: flex; gap: 0.75rem;">
                <form action="{{ route('customer.calendar.confirmation') }}" method="GET" style="display: inline;">
                    <input type="hidden" name="month" value="{{ $month == 1 ? 12 : $month - 1 }}">
                    <input type="hidden" name="year" value="{{ $month == 1 ? $year - 1 : $year }}">
                    <button type="submit" style="padding: 0.5rem 1rem; background: white; border: 1px solid #cbd5e1; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;">← Sebelumnya</button>
                </form>
                <form action="{{ route('customer.calendar.confirmation') }}" method="GET" style="display: inline;">
                    <input type="hidden" name="month" value="{{ $month == 12 ? 1 : $month + 1 }}">
                    <input type="hidden" name="year" value="{{ $month == 12 ? $year + 1 : $year }}">
                    <button type="submit" style="padding: 0.5rem 1rem; background: white; border: 1px solid #cbd5e1; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;">Selanjutnya →</button>
                </form>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div style="padding: 1.5rem;">
            <!-- Day Headers -->
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.5rem; margin-bottom: 1rem;">
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                    <div style="text-align: center; font-weight: 600; color: #64748b; padding: 0.75rem 0; font-size: 0.875rem;">{{ $day }}</div>
                @endforeach
            </div>

            <!-- Calendar Days -->
            <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 0.5rem; margin-bottom: 1.5rem;">
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
                        
                        // Get first event for this date if it exists
                        $firstEvent = null;
                        if ($hasEvent && count($eventsData[$dateStr]) > 0) {
                            // Find the first order with calendar event on this date
                            foreach ($confirmedOrders as $order) {
                                if ($order->calendarEvent && $order->calendarEvent->event_date->toDateString() === $dateStr) {
                                    $firstEvent = $order->calendarEvent;
                                    break;
                                }
                            }
                        }
                        
                        $bgColor = $hasEvent 
                            ? 'background: #f0fdf4; border: 1px solid #bbf7d0;' 
                            : 'background: #f8fafc; border: 1px solid #e2e8f0;';
                        
                        $textColor = !$isCurrentMonth ? 'color: #cbd5e1;' : 'color: #1e293b;';
                    @endphp
                    <a href="{{ $hasEvent && $isCurrentMonth && $firstEvent ? route('customer.calendar.event-details', ['event' => $firstEvent]) : '#' }}" 
                       style="aspect-ratio: 1; padding: 0.75rem; border-radius: 0.375rem; {{ $bgColor }} {{ $textColor }} display: flex; flex-direction: column; justify-content: space-between; align-items: center; text-decoration: none; {{ $isToday ? 'box-shadow: 0 0 0 2px #10b981; font-weight: 700;' : '' }} {{ !$hasEvent ? 'cursor: default;' : 'cursor: pointer; transition: all 0.2s;' }}">
                        <div style="font-size: 0.95rem; font-weight: 600;">{{ $currentDay->day }}</div>
                        @if($hasEvent && $isCurrentMonth)
                            <div style="font-size: 0.625rem; color: #10b981; font-weight: 700;">✓</div>
                        @endif
                    </a>
                    @php $currentDay->addDay(); @endphp
                @endwhile
            </div>

            <!-- Legend -->
            <div style="padding-top: 1rem; border-top: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 1rem; height: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.25rem;"></div>
                    <span style="font-size: 0.875rem; color: #475569;">Tidak ada acara</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 1rem; height: 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.25rem;"></div>
                    <span style="font-size: 0.875rem; color: #475569;">Ada acara</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Stats Card -->
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 0.5rem; color: white; padding: 1.5rem;">
            <h3 style="font-size: 0.875rem; font-weight: 600; opacity: 0.95; margin: 0 0 1rem; text-transform: uppercase; letter-spacing: 0.05em;">📊 Statistik Acara</h3>
            <div>
                <p style="font-size: 0.75rem; opacity: 0.9; margin: 0; margin-bottom: 0.5rem;">Total Acara</p>
                <p style="font-size: 2rem; font-weight: 700; margin: 0;">{{ $confirmedOrders->count() }}</p>
            </div>
        </div>

        <!-- Events List -->
        <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden; display: flex; flex-direction: column;">
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0; margin-bottom: 0.25rem;">📅 Daftar Acara</h3>
                <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $confirmedOrders->count() }} acara</p>
            </div>
            <div style="flex: 1; max-height: 500px; overflow-y: auto; padding: 1rem;">
                @if($confirmedOrders->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($confirmedOrders as $order)
                            @if($order->calendarEvent)
                                <a href="{{ route('customer.calendar.event-details', $order->calendarEvent) }}" style="padding: 0.75rem; border-left: 3px solid #10b981; background: #f0fdf4; border-radius: 0.25rem; text-decoration: none; color: inherit; transition: background 0.2s;">
                                    <p style="font-size: 0.875rem; font-weight: 600; color: #1e293b; margin: 0;">📍 {{ $order->calendarEvent->event_date->format('d M Y') }}</p>
                                    <p style="font-size: 0.75rem; color: #64748b; margin: 0.25rem 0 0;">📦 {{ $order->package->name }}</p>
                                    <span style="display: inline-block; margin-top: 0.5rem; font-size: 0.625rem; font-weight: 700; color: #047857; background: #d1fae5; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">{{ $order->calendarEvent->getStatusLabel() }}</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p style="text-align: center; color: #cbd5e1; padding: 2rem 0; margin: 0;">Belum ada acara</p>
                @endif
            </div>
        </div>

        <!-- Upcoming Event Info -->
        @php
            $upcomingEvent = $confirmedOrders->first(fn($o) => $o->calendarEvent && $o->calendarEvent->event_date >= now());
        @endphp
        @if($upcomingEvent && $upcomingEvent->calendarEvent)
            <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 0.5rem; color: white; padding: 1.5rem;">
                <h3 style="font-size: 0.875rem; font-weight: 600; opacity: 0.95; margin: 0 0 1rem;">🎉 Acara Mendatang</h3>
                <p style="font-size: 0.75rem; opacity: 0.9; margin: 0; margin-bottom: 0.25rem;">{{ $upcomingEvent->package->name }}</p>
                <p style="font-size: 1.25rem; font-weight: 700; margin: 0; margin-bottom: 0.75rem;">{{ $upcomingEvent->calendarEvent->event_date->format('d M Y') }}</p>
                <a href="{{ route('customer.calendar.event-details', $upcomingEvent->calendarEvent) }}" style="display: inline-block; padding: 0.5rem 1rem; background: white; color: #3b82f6; border-radius: 0.375rem; text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: all 0.2s;">
                    Lihat Detail →
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Auto-refresh calendar data
    // Implementasi real-time updates bisa ditambahkan di sini
</script>
@endpush
@endsection
