<!DOCTYPE html>
<html lang="id" class="h-full bg-premium-cream">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin - Gemilang WO')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.1); border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: rgba(212, 175, 55, 0.3); }
    </style>
</head>
<body class="h-full font-sans antialiased bg-premium-cream text-brown-950" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar for desktop --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-brown-950 text-white transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl">
            <div class="flex flex-col h-full">
                {{-- Logo Section --}}
                <div class="flex items-center justify-between h-20 px-6 bg-black/20">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gold-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-ring text-black text-xl"></i>
                        </div>
                        <span class="text-xl font-serif font-bold tracking-tight text-white italic">Gemilang <span class="text-gold-500">WO</span></span>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden text-white hover:text-gold-500 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto custom-scrollbar">
                    {{-- Section 1: Dashboard --}}
                    <div>
                        <div class="px-3 mb-2 text-[10px] font-bold text-gold-500/50 uppercase tracking-[2px] leading-loose">Ikhtisar BIsnis</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-th-large w-5 text-sm"></i>
                                <span class="text-sm font-bold">Dashboard Admin</span>
                            </a>
                            <a href="{{ route('admin.analytics.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.analytics.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-chart-pie w-5 text-sm"></i>
                                <span class="text-sm font-bold">Laporan Analitik</span>
                            </a>
                        </div>
                    </div>

                    {{-- Section 2: Operasional --}}
                    <div>
                        <div class="px-3 mb-2 text-[10px] font-bold text-gold-500/50 uppercase tracking-[2px] leading-loose">Operasional</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.packages.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-gem w-5 text-sm"></i>
                                <span class="text-sm font-bold">Koleksi Paket</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.orders.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-file-invoice w-5 text-sm"></i>
                                <span class="text-sm font-bold">Pesanan Klien</span>
                            </a>
                            <a href="{{ route('admin.payments.pending') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.payments.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-money-check-alt w-5 text-sm"></i>
                                <span class="text-sm font-bold">Verifikasi Pembayaran</span>
                            </a>
                            <a href="{{ route('admin.payment-schemes.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.payment-schemes.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-credit-card w-5 text-sm"></i>
                                <span class="text-sm font-bold">Skema Pembayaran</span>
                            </a>
                        </div>
                    </div>

                    {{-- Section 3: Partner --}}
                    <div>
                        <div class="px-3 mb-2 text-[10px] font-bold text-gold-500/50 uppercase tracking-[2px] leading-loose">Partner & Vendor</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.vendors.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.vendors.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-handshake w-5 text-sm"></i>
                                <span class="text-sm font-bold">Master Vendor</span>
                            </a>
                            <a href="{{ route('admin.vendor-categories.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.vendor-categories.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-tags w-5 text-sm"></i>
                                <span class="text-sm font-bold">Kategori Vendor</span>
                            </a>
                        </div>
                    </div>

                    {{-- Section 4: Marketing --}}
                    <div>
                        <div class="px-3 mb-2 text-[10px] font-bold text-gold-500/50 uppercase tracking-[2px] leading-loose">Marketing & Media</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.discounts.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.discounts.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-percentage w-5 text-sm"></i>
                                <span class="text-sm font-bold">Kupon & Promosi</span>
                            </a>
                            <a href="{{ route('admin.videos.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.videos.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-play-circle w-5 text-sm"></i>
                                <span class="text-sm font-bold">Galeri Video (Teaser)</span>
                            </a>
                            <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-star w-5 text-sm"></i>
                                <span class="text-sm font-bold">Review Pelanggan</span>
                            </a>
                            <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.testimonials.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-comment-dots w-5 text-sm"></i>
                                <span class="text-sm font-bold">Testimoni Klien</span>
                            </a>
                        </div>
                    </div>

                    {{-- Section 5: Support & System --}}
                    <div>
                        <div class="px-3 mb-2 text-[10px] font-bold text-gold-500/50 uppercase tracking-[2px] leading-loose">Sistem & Bantuan</div>
                        <div class="space-y-1">
                            <a href="{{ route('admin.support.tickets.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.support.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-headset w-5 text-sm"></i>
                                <span class="text-sm font-bold">Tiket Bantuan</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-gold-500 text-black shadow-lg shadow-gold-500/20' : 'text-gold-100/70 hover:bg-white/5 hover:text-white' }}">
                                <i class="fas fa-user-shield w-5 text-sm"></i>
                                <span class="text-sm font-bold">Manajemen User</span>
                            </a>
                        </div>
                    </div>

                    {{-- Filament Panel Link --}}
                    <div class="pt-4 mt-4 border-t border-white/5">
                        <a href="{{ url('/admin') }}" class="group flex items-center justify-between px-4 py-4 rounded-2xl bg-gradient-to-br from-gold-500/10 to-transparent border border-gold-500/20 hover:from-gold-500 hover:to-gold-600 transition-all shadow-lg active:scale-95">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gold-500 text-black rounded-lg flex items-center justify-center shadow-sm group-hover:bg-black group-hover:text-gold-500 transition-colors">
                                    <i class="fas fa-bolt text-xs"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-gold-500 group-hover:text-black uppercase tracking-widest leading-none mb-1">Coba Panel Baru</span>
                                    <span class="text-xs font-serif font-bold text-white group-hover:text-black italic">Filament V3</span>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-[10px] text-gold-500 group-hover:text-black transition-transform group-hover:translate-x-1"></i>
                        </a>
                    </div>
                </nav>

                {{-- User Profile Mini --}}
                <div class="p-6 bg-black/20">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gold-500/20 border border-gold-500/30 rounded-full flex items-center justify-center text-gold-500 font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[10px] text-gold-500 uppercase tracking-wider">Administrator</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gold-100/50 hover:text-red-400 transition-colors">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content Side --}}
        <div class="flex flex-col flex-1 overflow-hidden">
            {{-- Header --}}
            <header class="bg-white/80 backdrop-blur-md border-b border-brown-100 h-20 flex items-center justify-between px-8 shadow-sm">
                <div class="flex items-center gap-4 lg:hidden">
                    <button @click="sidebarOpen = true" class="text-brown-950">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <span class="text-xl font-serif font-bold text-brown-950">Gemilang WO</span>
                </div>
                
                <div class="hidden lg:block">
                    <h2 class="text-lg font-serif font-bold text-brown-950 italic tracking-wide">@yield('page_title', 'Admin Dashboard')</h2>
                </div>

                <div class="flex items-center gap-6">
                    <div class="hidden md:flex flex-col items-end">
                        <p class="text-[10px] text-brown-500 font-bold uppercase tracking-widest leading-none mb-1">Hari Ini</p>
                        <p class="text-sm font-bold text-brown-950">{{ now()->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="h-8 w-px bg-brown-100"></div>
                    <button class="relative text-brown-400 hover:text-gold-600 transition-colors">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            {{-- Main Scroll Content --}}
            <main class="flex-1 overflow-y-auto bg-premium-cream p-8 custom-scrollbar">
                <div class="max-w-7xl mx-auto space-y-8">
                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm flex items-center justify-between animate-fadeIn">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-500"></i>
                                <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
                            </div>
                            <button class="text-green-800 opacity-50 hover:opacity-100 transition-opacity"><i class="fas fa-times"></i></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
