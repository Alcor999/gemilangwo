@extends('layouts.app')

@section('title', 'Kelola Kalender - ' . $selectedPackage->name)

@section('content')
<!-- Header Section -->
<div style="background: #f8fafc; padding: 1.5rem 0; margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0;">
    <h1 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem;">📅 Kelola Kalender</h1>
    <p style="color: #64748b; margin: 0; font-size: 0.95rem;">Atur tanggal terblokir dan lihat heatmap ketersediaan paket Anda</p>
</div>

<!-- Package Info Card -->
<div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; padding: 1.5rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center;">
        <div>
            <label style="font-size: 0.875rem; font-weight: 600; color: #475569; display: block; margin-bottom: 0.5rem;">Pilih Paket :</label>
            <form action="{{ route('owner.calendar.index') }}" method="GET">
                <select name="package_id" id="package_id" onchange="this.form.submit()" style="width: 100%; padding: 0.75rem 1rem; border: 1px solid #cbd5e1; border-radius: 0.375rem; font-size: 1rem; background: white;">
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ $pkg->id === $selectedPackage->id ? 'selected' : '' }}>
                            {{ $pkg->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div style="text-align: right;">
            <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.5rem;">Paket Terpilih</p>
            <p style="font-size: 1.75rem; font-weight: 700; color: #6366f1; margin: 0;">{{ $selectedPackage->name }}</p>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
    <a href="{{ route('owner.calendar.blocked.create', ['package_id' => $selectedPackage->id]) }}" 
       style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: #ef4444; color: white; border-radius: 0.375rem; text-decoration: none; font-weight: 600; font-size: 0.95rem; border: none; cursor: pointer; transition: background 0.2s;">
        🚫 Blokir Tanggal
    </a>
    
    <div style="position: relative; display: inline-block;" id="exportContainer">
        <button type="button" id="exportBtn" onclick="toggleExportMenu()" style="display: inline-flex; align-items: center; padding: 0.75rem 1.5rem; background: #10b981; color: white; border-radius: 0.375rem; border: none; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: background 0.2s;">
            📥 Export
        </button>
        <div id="exportMenu" style="display: none; position: absolute; top: calc(100% + 0.5rem); left: 0; background: white; border: 1px solid #e2e8f0; border-radius: 0.375rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15); min-width: 220px; z-index: 100;">
            <a href="{{ route('owner.calendar.export', ['package' => $selectedPackage->id, 'type' => 'all']) }}" download style="display: block; padding: 0.75rem 1rem; color: #374151; text-decoration: none; border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">📊 Semua Data</a>
            <a href="{{ route('owner.calendar.export', ['package' => $selectedPackage->id, 'type' => 'events']) }}" download style="display: block; padding: 0.75rem 1rem; color: #374151; text-decoration: none; border-bottom: 1px solid #f3f4f6; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">📅 Acara Saja</a>
            <a href="{{ route('owner.calendar.export', ['package' => $selectedPackage->id, 'type' => 'blocked']) }}" download style="display: block; padding: 0.75rem 1rem; color: #374151; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">🚫 Tanggal Terblokir</a>
        </div>
    </div>
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
                <form action="{{ route('owner.calendar.index') }}" method="GET" style="display: inline;">
                    <input type="hidden" name="package_id" value="{{ $selectedPackage->id }}">
                    <input type="hidden" name="month" value="{{ $month == 1 ? 12 : $month - 1 }}">
                    <input type="hidden" name="year" value="{{ $month == 1 ? $year - 1 : $year }}">
                    <button type="submit" style="padding: 0.5rem 1rem; background: white; border: 1px solid #cbd5e1; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;">← Sebelumnya</button>
                </form>
                <form action="{{ route('owner.calendar.index') }}" method="GET" style="display: inline;">
                    <input type="hidden" name="package_id" value="{{ $selectedPackage->id }}">
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
                        $heatmapStatus = $heatmapData[$dateStr] ?? 0;
                        $isCurrentMonth = $currentDay->month === $startOfMonth->month;
                        $isToday = $currentDay->isToday();
                        
                        $bgColor = match($heatmapStatus) {
                            0 => 'background: #f0fdf4; border: 1px solid #bbf7d0;',
                            1 => 'background: #fef2f2; border: 1px solid #fecaca;',
                            2 => 'background: #fefce8; border: 1px solid #fde68a;',
                            default => 'background: #f8fafc; border: 1px solid #e2e8f0;',
                        };
                        
                        $textColor = !$isCurrentMonth ? 'color: #cbd5e1;' : 'color: #1e293b;';
                    @endphp
                    <div style="aspect-ratio: 1; padding: 0.75rem; border-radius: 0.375rem; {{ $bgColor }} {{ $textColor }} display: flex; flex-direction: column; justify-content: space-between; align-items: center; {{ $isToday ? 'box-shadow: 0 0 0 2px #6366f1; font-weight: 700;' : '' }}">
                        <div style="font-size: 0.95rem; font-weight: 600;">{{ $currentDay->day }}</div>
                        @if($isCurrentMonth && $heatmapStatus === 1)
                            <div style="font-size: 0.625rem; color: #dc2626; font-weight: 700;">🚫</div>
                        @elseif($isCurrentMonth && $heatmapStatus === 2)
                            <div style="font-size: 0.625rem; color: #d97706; font-weight: 700;">📅</div>
                        @endif
                    </div>
                    @php $currentDay->addDay(); @endphp
                @endwhile
            </div>

            <!-- Legend -->
            <div style="padding-top: 1rem; border-top: 1px solid #e2e8f0; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 1rem; height: 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 0.25rem;"></div>
                    <span style="font-size: 0.875rem; color: #475569;">Tersedia</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 1rem; height: 1rem; background: #fefce8; border: 1px solid #fde68a; border-radius: 0.25rem;"></div>
                    <span style="font-size: 0.875rem; color: #475569;">Sibuk</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 1rem; height: 1rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 0.25rem;"></div>
                    <span style="font-size: 0.875rem; color: #475569;">Terblokir</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Stats Card -->
        <div style="background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%); border-radius: 0.5rem; color: white; padding: 1.5rem;">
            <h3 style="font-size: 0.875rem; font-weight: 600; opacity: 0.95; margin: 0 0 1rem; text-transform: uppercase; letter-spacing: 0.05em;">📊 Statistik</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <p style="font-size: 0.75rem; opacity: 0.9; margin: 0; margin-bottom: 0.5rem;">Acara Bulan Ini</p>
                    <p style="font-size: 2rem; font-weight: 700; margin: 0;">{{ $calendarEvents->count() }}</p>
                </div>
                <div>
                    <p style="font-size: 0.75rem; opacity: 0.9; margin: 0; margin-bottom: 0.5rem;">Terblokir</p>
                    <p style="font-size: 2rem; font-weight: 700; margin: 0;">{{ $blockedDates->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Blocked Dates -->
        <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden; display: flex; flex-direction: column;">
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0; margin-bottom: 0.25rem;">🚫 Tanggal Terblokir</h3>
                <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $blockedDates->count() }} item</p>
            </div>
            <div style="flex: 1; max-height: 350px; overflow-y: auto; padding: 1rem;">
                @if($blockedDates->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($blockedDates as $blocked)
                            <div style="padding: 0.75rem; border-left: 3px solid #ef4444; background: #fef2f2; border-radius: 0.25rem;">
                                <p style="font-size: 0.875rem; font-weight: 600; color: #1e293b; margin: 0;">{{ $blocked->getTypeLabel() }}</p>
                                <p style="font-size: 0.75rem; color: #64748b; margin: 0.25rem 0 0;">{{ $blocked->start_date->format('d M Y') }} - {{ $blocked->end_date->format('d M Y') }}</p>
                                @if($blocked->reason)
                                    <p style="font-size: 0.75rem; color: #475569; margin: 0.5rem 0 0;">{{ $blocked->reason }}</p>
                                @endif
                                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                    <a href="{{ route('owner.calendar.blocked.edit', $blocked) }}" style="color: #6366f1; text-decoration: none; font-size: 0.75rem; font-weight: 600;">✏️ Edit</a>
                                    <form action="{{ route('owner.calendar.blocked.destroy', $blocked) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link btn-sm text-danger p-0" style="font-size: 0.75rem; font-weight: 600;"
                                            data-confirm="Apakah Anda yakin ingin menghapus tanggal terblokir ini?"
                                            data-confirm-title="Hapus Tanggal Terblokir"
                                            data-confirm-btn="Ya, Hapus"
                                            data-confirm-danger="1">🗑️ Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="text-align: center; color: #cbd5e1; padding: 2rem 0; margin: 0;">Belum ada</p>
                @endif
            </div>
        </div>

        <!-- Upcoming Events -->
        <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden; display: flex; flex-direction: column;">
            <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0; margin-bottom: 0.25rem;">📅 Acara Bulan Ini</h3>
                <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $calendarEvents->count() }} acara</p>
            </div>
            <div style="flex: 1; max-height: 350px; overflow-y: auto; padding: 1rem;">
                @if($calendarEvents->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($calendarEvents as $event)
                            <div style="padding: 0.75rem; border-left: 3px solid #10b981; background: #f0fdf4; border-radius: 0.25rem;">
                                <p style="font-size: 0.875rem; font-weight: 600; color: #1e293b; margin: 0;">{{ $event->order->user->name }}</p>
                                <p style="font-size: 0.75rem; color: #64748b; margin: 0.25rem 0 0;">📍 {{ $event->event_date->format('d M Y') }}</p>
                                <p style="font-size: 0.75rem; color: #475569; margin: 0.25rem 0 0;">📦 {{ $event->package->name }}</p>
                                <span style="display: inline-block; margin-top: 0.5rem; font-size: 0.625rem; font-weight: 700; color: #047857; background: #d1fae5; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">{{ $event->getStatusLabel() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="text-align: center; color: #cbd5e1; padding: 2rem 0; margin: 0;">Belum ada</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleExportMenu() {
        const menu = document.getElementById('exportMenu');
        const btn = document.getElementById('exportBtn');
        
        if (!menu || !btn) {
            console.error('Export menu or button elements not found');
            return;
        }
        
        const isVisible = menu.style.display !== 'none';
        menu.style.display = isVisible ? 'none' : 'block';
        btn.style.background = isVisible ? '#10b981' : '#059669';
        console.log('Export menu toggled:', !isVisible);
    }
    
    // Initialize export menu listeners when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const menu = document.getElementById('exportMenu');
        const container = document.getElementById('exportContainer');
        const btn = document.getElementById('exportBtn');
        
        console.log('Initializing export menu. Menu:', !!menu, 'Container:', !!container, 'Button:', !!btn);
        
        if (!menu || !container || !btn) {
            console.error('Export menu elements not found in DOM');
            return;
        }
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (container && !container.contains(event.target)) {
                menu.style.display = 'none';
                btn.style.background = '#10b981';
                console.log('Closed export menu (clicked outside)');
            }
        });
        
        // Prevent menu from closing when clicking menu items or button
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Export menu item clicked:', e.target.href || e.target.textContent);
        });
        
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Add download tracking
        const exportLinks = menu.querySelectorAll('a');
        exportLinks.forEach((link, index) => {
            link.addEventListener('click', function(e) {
                console.log('Export link clicked:', this.textContent.trim(), 'URL:', this.href);
            });
        });
    });
</script>
@endpush
@endsection
