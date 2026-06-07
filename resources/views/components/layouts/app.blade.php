<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Gemilang Wedding Organizer - Kemewahan Tak Terlupakan' }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-premium-cream text-brown-950">
    {{-- Navigation --}}
    <nav x-data="{ open: false, scrolled: false }" 
         x-init="window.pageYOffset > 20 ? scrolled = true : scrolled = false"
         @scroll.window="window.pageYOffset > 20 ? scrolled = true : scrolled = false"
         :class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-lg py-3' : 'bg-transparent py-6'"
         class="fixed w-full z-50 transition-all duration-500">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gold-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-ring text-black text-xl"></i>
                </div>
                <span :class="scrolled ? 'text-brown-950' : 'text-gold-500'" class="text-2xl font-serif font-bold tracking-tight transition-colors">Gemilang WO</span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-10">
                <a href="{{ route('home') }}" class="font-medium hover:text-gold-600 transition-colors">Beranda</a>
                <a href="{{ route('customer.packages.index') }}" class="font-medium hover:text-gold-600 transition-colors">Paket</a>
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="px-6 py-2 bg-gold-500 text-black font-bold rounded-full hover:bg-gold-400 transition-all shadow-lg">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-gold-600 hover:text-gold-700 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-black text-gold-500 font-bold rounded-full hover:bg-brown-900 transition-all shadow-lg">Daftar</a>
                @endauth
            </div>

            {{-- Mobile Toggle --}}
            <button @click="open = !open" class="md:hidden text-2xl" :class="scrolled ? 'text-brown-950' : 'text-gold-500'">
                <i :class="open ? 'fas fa-times' : 'fas fa-bars'"></i>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden bg-white border-t border-brown-100 p-6 absolute top-full left-0 w-full shadow-2xl">
            <div class="flex flex-col gap-4">
                <a href="{{ route('home') }}" class="text-lg font-medium py-2 border-b border-brown-50">Beranda</a>
                <a href="{{ route('customer.packages.index') }}" class="text-lg font-medium py-2 border-b border-brown-50">Paket</a>
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="text-lg font-bold text-gold-600 py-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-lg font-medium py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="text-lg font-bold text-gold-600 py-2">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-premium-dark text-white py-20">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gold-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-ring text-black text-2xl"></i>
                        </div>
                        <span class="text-3xl font-serif font-bold text-gold-500">Gemilang WO</span>
                    </div>
                    <p class="text-gold-100/50 max-w-sm leading-relaxed">Mewujudkan pernikahan impian Anda dengan sentuhan kemewahan, profesionalisme, dan detail yang sempurna sejak tahun 2010.</p>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-6 text-gold-500">Tautan Cepat</h4>
                    <ul class="space-y-4 text-gold-100/60 font-light">
                        <li><a href="#" class="hover:text-gold-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('customer.packages.index') }}" class="hover:text-gold-400 transition-colors">Paket Wedding</a></li>
                        <li><a href="#" class="hover:text-gold-400 transition-colors">Galeri Vendor</a></li>
                        <li><a href="#" class="hover:text-gold-400 transition-colors">Kontak Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xl font-bold mb-6 text-gold-500">Hubungi Kami</h4>
                    <ul class="space-y-4 text-gold-100/60 font-light">
                        <li class="flex items-center gap-3"><i class="fas fa-map-marker-alt text-gold-500"></i> Jl. Kebahagiaan No. 88, Jakarta</li>
                        <li class="flex items-center gap-3"><i class="fas fa-phone text-gold-500"></i> +62 812 3456 7890</li>
                        <li class="flex items-center gap-3"><i class="fas fa-envelope text-gold-500"></i> info@gemilangwo.com</li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-gold-900/30 text-center text-gold-100/30 text-sm">
                &copy; {{ date('Y') }} Gemilang Wedding Organizer. All rights reserved.
            </div>
        </div>
    </footer>

    @stack('modals')
    @stack('scripts')
</body>
</html>
