<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-luxury-bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Gemilang WO - Luxury Wedding Organizer')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        .luxury-gradient {
            background: linear-gradient(135deg, #C5A059 0%, #D6B87D 100%);
        }
        
        .sidebar-scroller::-webkit-scrollbar { width: 4px; }
        .sidebar-scroller::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroller::-webkit-scrollbar-thumb { background: rgba(197, 160, 89, 0.2); border-radius: 10px; }
    </style>
</head>
<body class="h-full font-sans text-choco-900 antialiased" x-data="{ sidebarOpen: false }">
    
    <div class="min-h-full">
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-choco-900/60 backdrop-blur-sm lg:hidden"
             @click="sidebarOpen = false" x-cloak></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-choco-800 transition-transform duration-300 ease-in-out lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <div class="flex h-16 shrink-0 items-center px-6 border-b border-choco-700/50">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gold-400 group-hover:bg-gold-300 transition-colors">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 00-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.856.12-1.683.342-2.468"></path>
                        </svg>
                    </div>
                    <span class="font-serif text-xl tracking-wide text-gold-100 group-hover:text-white transition-colors">Gemilang <span class="font-light">WO</span></span>
                </a>
            </div>

            <nav class="flex flex-1 flex-col px-4 py-8 sidebar-scroller overflow-y-auto">
                <div class="mb-6 px-2">
                    @auth
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-choco-900/50 border border-choco-700">
                            <div class="h-10 w-10 rounded-full border-2 border-gold-400 overflow-hidden bg-choco-700 flex items-center justify-center">
                                @if(auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="" class="h-full w-full object-cover">
                                @else
                                    <span class="text-gold-400 font-bold uppercase">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[10px] uppercase tracking-widest text-gold-400 font-bold">
                                    {{ auth()->user()->isAdmin() ? 'Administrator' : (auth()->user()->isOwner() ? 'Owner' : 'Klien Prioritas') }}
                                </p>
                            </div>
                        </div>
                    @endauth
                </div>

                <div class="space-y-1">
                    @include('layouts.navigation-links')
                </div>

                <div class="mt-auto px-2 pt-10">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="group flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-stone-400 hover:bg-gold-400 hover:text-white transition-all">
                            <svg class="h-5 w-5 text-stone-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Keluar Sesi
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Workspace -->
        <div class="flex flex-1 flex-col lg:pl-72">
            <!-- Top Navigation Bar -->
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-x-4 border-b border-stone-200 bg-white/80 px-4 backdrop-blur-md sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" class="-m-2.5 p-2.5 text-choco-700 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex flex-1 items-center font-serif text-lg text-choco-800">
                        @yield('header_title', 'Dashboard')
                    </div>
                    
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <!-- Notifications/Search could go here -->
                        <div class="h-6 w-px bg-stone-200 lg:hidden"></div>
                        <div class="text-xs text-stone-400 hidden sm:block italic uppercase tracking-wider">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <!-- Notifications -->
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                             class="mb-6 flex items-center gap-4 rounded-xl border border-green-100 bg-green-50 p-4 text-green-700 shadow-sm transition-all duration-300">
                            <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error') || $errors->any())
                        <div class="mb-6 flex flex-col gap-2 rounded-xl border border-red-100 bg-red-50 p-4 text-red-700 shadow-sm">
                            <div class="flex items-center gap-4">
                                <svg class="h-5 w-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span class="text-sm font-bold">Terjadi Kesalahan</span>
                            </div>
                            @if($errors->any())
                                <ul class="list-disc list-inside mt-2 text-xs opacity-80">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-sm mt-1">{{ session('error') }}</span>
                            @endif
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
    @yield('js')
</body>
</html>
