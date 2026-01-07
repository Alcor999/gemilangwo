@extends('layouts.app')

@section('title', 'Booking Kalender - ' . $package->name)

@section('content')
<!-- Header Section -->
<div style="background: #f8fafc; padding: 1.5rem 0; margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0;">
    <h1 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem;">📅 Kalender Ketersediaan</h1>
    <p style="color: #64748b; margin: 0; font-size: 0.95rem;">Lihat tanggal tersedia dan pesan acara Anda</p>
</div>

<!-- Package Info Card -->
<div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; padding: 1.5rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center;">
        <div>
            <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.5rem;">Paket yang Anda Lihat</p>
            <h2 style="font-size: 1.75rem; font-weight: 700; color: #1e293b; margin: 0;">{{ $package->name }}</h2>
        </div>
        <div style="text-align: right;">
            <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.5rem;">Harga Mulai</p>
            <p style="font-size: 1.75rem; font-weight: 700; color: #10b981; margin: 0;">Rp {{ number_format($package->base_price, 0, ',', '.') }}</p>
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
                <form action="{{ route('customer.calendar.booking', $package) }}" method="GET" style="display: inline;">
                    <input type="hidden" name="month" value="{{ $month == 1 ? 12 : $month - 1 }}">
                    <input type="hidden" name="year" value="{{ $month == 1 ? $year - 1 : $year }}">
                    <button type="submit" style="padding: 0.5rem 1rem; background: white; border: 1px solid #cbd5e1; border-radius: 0.375rem; cursor: pointer; transition: all 0.2s; font-size: 0.875rem;">← Sebelumnya</button>
                </form>
                <form action="{{ route('customer.calendar.booking', $package) }}" method="GET" style="display: inline;">
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
                        
                        $dateStatus = $heatmapData[$dateStr] ?? null;
                        $status = $dateStatus ? $dateStatus['status'] : 'available';
                        $isBlocked = $status === 'blocked';
                        $isBusy = $status === 'busy';
                        $isAvailable = $status === 'available' && !$dateStatus['isPast'];
                        
                        if ($isBlocked) {
                            $bgColor = 'background: #fef2f2; border: 1px solid #fecaca;';
                            $cursor = 'cursor: not-allowed;';
                        } elseif ($isBusy) {
                            $bgColor = 'background: #fef3c7; border: 1px solid #fcd34d;';
                            $cursor = 'cursor: default;';
                        } elseif ($isAvailable) {
                            $bgColor = 'background: #f0fdf4; border: 1px solid #bbf7d0;';
                            $cursor = 'cursor: pointer; transition: all 0.2s;';
                        } else {
                            $bgColor = 'background: #f8fafc; border: 1px solid #e2e8f0;';
                            $cursor = 'cursor: default;';
                        }
                        
                        $textColor = !$isCurrentMonth ? 'color: #cbd5e1;' : 'color: #1e293b;';
                    @endphp
                    <button type="button" 
                        {{ $isAvailable && $isCurrentMonth ? "onclick=\"selectDate('" . $dateStr . "')\"" : 'disabled' }}
                        style="aspect-ratio: 1; padding: 0.75rem; border-radius: 0.375rem; {{ $bgColor }} {{ $textColor }} display: flex; flex-direction: column; justify-content: space-between; align-items: center; {{ $cursor }} border: none; font-size: 0.95rem; font-weight: 600; {{ $isToday ? 'box-shadow: 0 0 0 2px #10b981; font-weight: 700;' : '' }} {{ !$isAvailable ? 'opacity: 0.6;' : '' }}">
                        {{ $currentDay->day }}
                        @if($isBlocked && $isCurrentMonth)
                            <div style="font-size: 0.625rem; color: #dc2626; font-weight: 700;">🚫</div>
                        @elseif($isAvailable && $isCurrentMonth)
                            <div style="font-size: 0.625rem; color: #10b981; font-weight: 700;">✓</div>
                        @endif
                    </button>
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
                    <div style="width: 1rem; height: 1rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 0.25rem;"></div>
                    <span style="font-size: 0.875rem; color: #475569;">Terblokir</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 1rem; height: 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.25rem;"></div>
                    <span style="font-size: 0.875rem; color: #475569;">Tidak Tersedia</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Available Dates Count -->
        @php
            $availableCount = 0;
            $blockedCount = 0;
            $busyCount = 0;
            foreach ($heatmapData as $date => $data) {
                if ($data['status'] === 'available' && !$data['isPast']) $availableCount++;
                elseif ($data['status'] === 'blocked') $blockedCount++;
                elseif ($data['status'] === 'busy') $busyCount++;
            }
        @endphp
        
        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 0.5rem; color: white; padding: 1.5rem;">
            <h3 style="font-size: 0.875rem; font-weight: 600; opacity: 0.95; margin: 0 0 1rem; text-transform: uppercase; letter-spacing: 0.05em;">📅 Tanggal Tersedia</h3>
            <p style="font-size: 2rem; font-weight: 700; margin: 0; margin-bottom: 0.5rem;">{{ $availableCount }}</p>
            <p style="font-size: 0.75rem; opacity: 0.9; margin: 0;">Tanggal tersedia di bulan ini</p>
        </div>

        <!-- Next Available -->
        @if(count($nextAvailableDates) > 0)
            <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden; padding: 1.5rem;">
                <h3 style="font-size: 0.875rem; font-weight: 600; color: #1e293b; margin: 0 0 1rem;">🎯 Tanggal Tersedia Berikutnya</h3>
                @foreach(array_slice($nextAvailableDates, 0, 1) as $date)
                    <p style="font-size: 1.5rem; font-weight: 700; color: #10b981; margin: 0 0 0.5rem;">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</p>
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0;">{{ \Carbon\Carbon::parse($date)->locale('id_ID')->format('l') }}</p>
                @endforeach
            </div>
        @endif

        <!-- Blocked Info -->
        @if(count($blockedDates) > 0)
            <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden;">
                <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0; margin-bottom: 0.25rem;">🚫 Tanggal Terblokir</h3>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 0;">{{ $blockedCount }} tanggal</p>
                </div>
                <div style="max-height: 300px; overflow-y: auto; padding: 1rem;">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        @foreach($blockedDates as $blocked)
                            <div style="padding: 0.5rem; background: #f9fafb; border-radius: 0.25rem; border-left: 3px solid #ef4444;">
                                <p style="font-size: 0.75rem; color: #1e293b; margin: 0; font-weight: 600;">{{ $blocked->start_date->format('d M') }} - {{ $blocked->end_date->format('d M Y') }}</p>
                                <p style="font-size: 0.625rem; color: #6b7280; margin: 0.25rem 0 0;">{{ $blocked->reason }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Info Pemesanan -->
        <div style="background: #f0f9ff; border-radius: 0.5rem; border: 1px solid #bfdbfe; padding: 1rem;">
            <p style="font-size: 0.875rem; color: #1e40af; margin: 0; line-height: 1.5;">
                <strong>ℹ️ Info:</strong> Pilih tanggal yang tersedia untuk melanjutkan ke proses pemesanan. Hubungi kami jika ada pertanyaan.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function selectDate(date) {
        // Redirect ke booking form atau implementasi pilihan tanggal
        console.log('Selected date:', date);
        // Bisa tambahkan modal atau form untuk input data booking
    }
</script>
@endpush
@endsection
