@php
    $routeName = Route::currentRouteName();
    
    $linkClass = "group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200";
    $activeClass = "bg-gold-400 text-white shadow-lg shadow-gold-400/20";
    $inactiveClass = "text-stone-300 hover:bg-choco-700/50 hover:text-white";
    
    $iconClass = "h-5 w-5 shrink-0 transition-colors";
    $activeIconClass = "text-white";
    $inactiveIconClass = "text-stone-500 group-hover:text-gold-400";
@endphp

@if(auth()->user()->isAdmin())
    <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-500">Dasbor Admin</div>
    
    <a href="{{ route('admin.dashboard') }}" 
       class="{{ $linkClass }} {{ $routeName == 'admin.dashboard' ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ $routeName == 'admin.dashboard' ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Statistik Utama
    </a>

    <a href="{{ route('admin.packages.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.packages') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.packages') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
        Katalog Paket
    </a>

    <a href="{{ route('admin.orders.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.orders') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.orders') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
        </svg>
        Pesanan Aktif
    </a>

    <a href="{{ route('admin.payments.pending') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.payments') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.payments') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
        </svg>
        Verifikasi Pembayaran
    </a>

    <a href="{{ route('admin.payment-schemes.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.payment-schemes') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.payment-schemes') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
        Skema Pembayaran
    </a>

    @php $openSupportCount = \App\Models\SupportTicket::whereIn('status', ['open', 'in_progress', 'waiting_customer'])->count(); @endphp
    <a href="{{ route('admin.support.tickets.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.support') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.support') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <span class="flex-1">Support & Pengaduan</span>
        @if($openSupportCount > 0)
            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-rose-500 text-white">{{ $openSupportCount }}</span>
        @endif
    </a>

    <div class="px-4 py-2 mt-4 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-500">Manajemen</div>
    
    <a href="{{ route('admin.vendors.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.vendors') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.vendors') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        Vendor Partner
    </a>

    <a href="{{ route('admin.discounts.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.discounts') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.discounts') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
        </svg>
        Promo & Diskon
    </a>

    <a href="{{ route('admin.reviews.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.reviews') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.reviews') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
        </svg>
        Moderasi Ulasan
    </a>

    <a href="{{ route('admin.testimonials.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.testimonials') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.testimonials') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
        </svg>
        Testimoni Klien
    </a>

    <a href="{{ route('admin.users.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.users') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.users') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        Basis Pengguna
    </a>

    <a href="{{ route('admin.announcements.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'admin.announcements') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'admin.announcements') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
        </svg>
        Pengumuman
    </a>

@elseif(auth()->user()->isOwner())
    <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-500">Laporan Owner</div>
    
    <a href="{{ route('owner.dashboard') }}" 
       class="{{ $linkClass }} {{ $routeName == 'owner.dashboard' ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ $routeName == 'owner.dashboard' ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        Profit & Analitik
    </a>

    <a href="{{ route('owner.calendar.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'owner.calendar') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'owner.calendar') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
        </svg>
        Jadwal Acara
    </a>

    <a href="{{ route('owner.payments') }}" 
       class="{{ $linkClass }} {{ $routeName == 'owner.payments' ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ $routeName == 'owner.payments' ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
        </svg>
        Arus Kas
    </a>

    <a href="{{ route('owner.payment-schemes.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'owner.payment-schemes') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'owner.payment') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
        </svg>
        Skema Pembayaran
    </a>

@else
    <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-500">Dashboard Anda</div>
    
    <a href="{{ route('customer.dashboard') }}" 
       class="{{ $linkClass }} {{ $routeName == 'customer.dashboard' ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ $routeName == 'customer.dashboard' ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Ringkasan Pernikahan
    </a>

    <a href="{{ route('customer.packages.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'customer.packages') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'customer.packages') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 16v2m3-6v6m3-3v3M9 9h.01M12 12h.01M15 9h.01M21 12c0 1.657-1.007 3-2.25 3S16.5 13.657 16.5 12s1.007-3 2.25-3S21 10.343 21 12zm-9 3c1.243 0 2.25-1.343 2.25-3s-1.007-3-2.25-3-2.25 1.343-2.25 3 1.007 3 2.25 3zm-9 0c1.243 0 2.25-1.343 2.25-3s-1.007-3-2.25-3-2.25 1.343-2.25 3 1.007 3 2.25 3z"></path>
        </svg>
        Pilih Paket
    </a>

    <a href="{{ route('customer.orders.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'customer.orders') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'customer.orders') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
        </svg>
        Pesanan Saya
    </a>

    <a href="{{ route('customer.wishlist.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'customer.wishlist') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'customer.wishlist') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
        Favorit
    </a>

    <a href="{{ route('customer.reviews.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'customer.reviews') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'customer.reviews') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
        </svg>
        Ulasan Saya
    </a>

    <a href="{{ route('customer.testimonials.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'customer.testimonials') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'customer.testimonials') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
        </svg>
        Testimoni Saya
    </a>

    <div class="px-4 py-2 mt-4 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-500">Jadwal & Acara</div>

    <a href="{{ route('customer.calendar.confirmation') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'customer.calendar') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'customer.calendar') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Kalender Acara
    </a>

    <div class="px-4 py-2 mt-4 text-[10px] font-bold uppercase tracking-[0.2em] text-stone-500">Layanan Pelanggan</div>

    @php $myOpenTickets = auth()->user()->supportTickets()->whereIn('status', ['open', 'in_progress', 'waiting_customer'])->count(); @endphp
    <a href="{{ route('customer.support.tickets.index') }}" 
       class="{{ $linkClass }} {{ str_contains($routeName ?? '', 'customer.support') ? $activeClass : $inactiveClass }}">
        <svg class="{{ $iconClass }} {{ str_contains($routeName ?? '', 'customer.support') ? $activeIconClass : $inactiveIconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <span class="flex-1">Pusat Bantuan</span>
        @if($myOpenTickets > 0)
            <span class="px-1.5 py-0.5 rounded-full text-[9px] font-bold bg-gold-400 text-white">{{ $myOpenTickets }}</span>
        @endif
    </a>
@endif
