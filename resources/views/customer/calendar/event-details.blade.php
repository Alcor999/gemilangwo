@extends('layouts.app')

@section('title', 'Detail Acara - ' . $event->package->name)

@section('content')
<!-- Header Section -->
<div style="background: #f8fafc; padding: 1.5rem 0; margin-bottom: 2rem; border-bottom: 1px solid #e2e8f0;">
    <h1 style="font-size: 2rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem;">📋 Detail Acara Pernikahan</h1>
    <p style="color: #64748b; margin: 0; font-size: 0.95rem;">Informasi lengkap acara dan status konfirmasi Anda</p>
</div>

<!-- Main Grid -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Main Content -->
    <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden;">
        <!-- Event Header -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e2e8f0; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <h2 style="font-size: 1.75rem; font-weight: 700; margin: 0; margin-bottom: 0.5rem;">{{ $event->package->name }}</h2>
                    <p style="opacity: 0.9; margin: 0;">Acara Pernikahan</p>
                </div>
                <span style="display: inline-block; font-size: 0.75rem; font-weight: 700; color: white; background: rgba(0,0,0,0.2); padding: 0.5rem 1rem; border-radius: 0.375rem;">{{ $event->getStatusLabel() }}</span>
            </div>
        </div>

        <!-- Event Details -->
        <div style="padding: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <!-- Left Column -->
            <div>
                <div style="margin-bottom: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.5rem; text-transform: uppercase; font-weight: 600;">📅 Tanggal Acara</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0;">{{ $event->event_date->locale('id_ID')->format('l, d F Y') }}</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.5rem; text-transform: uppercase; font-weight: 600;">⏰ Waktu</p>
                    <p style="font-size: 1.25rem; font-weight: 600; color: #1e293b; margin: 0;">
                        @if($event->event_start)
                            {{ $event->event_start->format('H:i') }} - {{ $event->event_end->format('H:i') }}
                        @else
                            Waktu belum ditentukan
                        @endif
                    </p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.5rem; text-transform: uppercase; font-weight: 600;">📍 Lokasi</p>
                    <p style="font-size: 0.95rem; color: #1e293b; margin: 0;">{{ $event->package->location ?? 'Lokasi belum ditentukan' }}</p>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div style="margin-bottom: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.5rem; text-transform: uppercase; font-weight: 600;">📦 Paket</p>
                    <p style="font-size: 1.25rem; font-weight: 600; color: #1e293b; margin: 0;">{{ $event->package->name }}</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.5rem; text-transform: uppercase; font-weight: 600;">💰 Harga Paket</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #10b981; margin: 0;">Rp {{ number_format($event->package->base_price, 0, ',', '.') }}</p>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <p style="font-size: 0.875rem; color: #64748b; margin: 0 0 0.5rem; text-transform: uppercase; font-weight: 600;">📊 Status</p>
                    <span style="display: inline-block; padding: 0.5rem 1rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700; {{ $event->is_confirmed ? 'background: #d1fae5; color: #047857;' : 'background: #fef3c7; color: #92400e;' }}">
                        {{ $event->is_confirmed ? '✓ Dikonfirmasi' : '⏳ Menunggu Konfirmasi' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if($event->notes)
            <div style="padding: 1.5rem; border-top: 1px solid #e2e8f0; background: #f8fafc;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0 0 0.75rem; text-transform: uppercase;">📝 Catatan</h3>
                <p style="font-size: 0.95rem; color: #475569; margin: 0; line-height: 1.6;">{{ $event->notes }}</p>
            </div>
        @endif

        <!-- Event Days Info -->
        @if($event->pre_event_days || $event->post_event_days)
            <div style="padding: 1.5rem; border-top: 1px solid #e2e8f0;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0 0 1rem; text-transform: uppercase;">📆 Hari-hari Acara</h3>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
                    @if($event->pre_event_days)
                        <div style="padding: 1rem; background: #f0f9ff; border-radius: 0.375rem; text-align: center;">
                            <p style="font-size: 0.75rem; color: #0369a1; font-weight: 600; margin: 0; text-transform: uppercase;">Sebelum Acara</p>
                            <p style="font-size: 1.75rem; font-weight: 700; color: #0369a1; margin: 0; margin-top: 0.5rem;">{{ $event->pre_event_days }}</p>
                            <p style="font-size: 0.75rem; color: #0369a1; margin: 0.5rem 0 0;">Hari</p>
                        </div>
                    @endif

                    <div style="padding: 1rem; background: #f0fdf4; border-radius: 0.375rem; text-align: center;">
                        <p style="font-size: 0.75rem; color: #047857; font-weight: 600; margin: 0; text-transform: uppercase;">Hari Acara</p>
                        <p style="font-size: 1.75rem; font-weight: 700; color: #047857; margin: 0; margin-top: 0.5rem;">1</p>
                        <p style="font-size: 0.75rem; color: #047857; margin: 0.5rem 0 0;">Hari</p>
                    </div>

                    @if($event->post_event_days)
                        <div style="padding: 1rem; background: #fef3c7; border-radius: 0.375rem; text-align: center;">
                            <p style="font-size: 0.75rem; color: #b45309; font-weight: 600; margin: 0; text-transform: uppercase;">Setelah Acara</p>
                            <p style="font-size: 1.75rem; font-weight: 700; color: #b45309; margin: 0; margin-top: 0.5rem;">{{ $event->post_event_days }}</p>
                            <p style="font-size: 0.75rem; color: #b45309; margin: 0.5rem 0 0;">Hari</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Status Card -->
        <div style="background: {{ $event->is_confirmed ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)' }}; border-radius: 0.5rem; color: white; padding: 1.5rem;">
            <h3 style="font-size: 0.875rem; font-weight: 600; opacity: 0.95; margin: 0 0 1rem; text-transform: uppercase; letter-spacing: 0.05em;">✓ Status Konfirmasi</h3>
            <p style="font-size: 0.75rem; opacity: 0.9; margin: 0; margin-bottom: 0.5rem;">{{ $event->is_confirmed ? 'Acara Sudah Dikonfirmasi' : 'Belum Dikonfirmasi' }}</p>
            @if($event->confirmed_at)
                <p style="font-size: 0.875rem; margin: 0;">Dikonfirmasi pada: {{ $event->confirmed_at->locale('id_ID')->format('d M Y H:i') }}</p>
            @endif
        </div>

        <!-- Action Card -->
        @if(!$event->is_confirmed)
            <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden;">
                <div style="padding: 1.5rem;">
                    <h3 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0 0 1rem;">🎯 Aksi Diperlukan</h3>
                    <form action="{{ route('customer.calendar.confirm-event', $event) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 0.75rem 1rem; background: #10b981; color: white; border: none; border-radius: 0.375rem; font-weight: 600; font-size: 0.95rem; cursor: pointer; transition: background 0.2s;">
                            ✓ Konfirmasi Acara
                        </button>
                    </form>
                    <p style="font-size: 0.75rem; color: #64748b; margin: 1rem 0 0; text-align: center;">Konfirmasi acara Anda sekarang</p>
                </div>
            </div>
        @endif

        <!-- Export Card -->
        <div style="background: white; border-radius: 0.5rem; border: 1px solid #e2e8f0; overflow: hidden;">
            <div style="padding: 1.5rem;">
                <h3 style="font-size: 0.875rem; font-weight: 700; color: #1e293b; margin: 0 0 1rem;">📥 Export</h3>
                <a href="{{ route('customer.calendar.export-event', $event) }}" style="display: block; padding: 0.75rem 1rem; background: #3b82f6; color: white; border-radius: 0.375rem; text-decoration: none; font-weight: 600; font-size: 0.95rem; text-align: center; transition: background 0.2s;">
                    📥 Simpan ke iCal
                </a>
            </div>
        </div>

        <!-- Back Link -->
        <div style="text-align: center;">
            <a href="{{ route('customer.calendar.confirmation') }}" style="color: #6366f1; text-decoration: none; font-weight: 600; font-size: 0.95rem;">
                ← Kembali ke Kalender
            </a>
        </div>
    </div>
</div>

@endsection
