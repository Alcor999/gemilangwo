<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gemilang WO | Luxury Wedding Organizer</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Great+Vibes&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .lux-text-shadow { text-shadow: 0 4px 20px rgba(0,0,0,0.4); }
        .nav-blur { backdrop-filter: blur(12px); background-color: rgba(255, 253, 249, 0.85); }
    </style>
</head>
<body class="bg-stone-50 font-sans text-choco-900 antialiased h-full" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 transition-all duration-700" 
         :class="scrolled ? 'nav-blur py-3 shadow-xl shadow-stone-900/5 border-b border-stone-100' : 'bg-transparent py-8'">
        <div class="max-w-7xl mx-auto px-8 flex items-center justify-between">
            <a href="/" class="flex items-center gap-4 group">
                <img src="{{ asset('images/logo.png') }}" alt="Gemilang WO" class="h-11 w-auto object-contain">
                <div class="flex flex-col">
                    <span class="font-serif text-2xl font-bold tracking-tight transition-colors" :class="scrolled ? 'text-choco-900' : 'text-white lux-text-shadow'">Gemilang</span>
                    <span class="text-[9px] font-bold uppercase tracking-[0.4em] transition-colors" :class="scrolled ? 'text-gold-500' : 'text-gold-400'">Wedding Organizer</span>
                </div>
            </a>

            <div class="hidden lg:flex items-center gap-12">
                <a href="#about" class="text-[10px] font-bold uppercase tracking-[0.3em] transition-colors hover:text-gold-500" :class="scrolled ? 'text-choco-800' : 'text-white lux-text-shadow'">Tentang</a>
                <a href="#packages" class="text-[10px] font-bold uppercase tracking-[0.3em] transition-colors hover:text-gold-500" :class="scrolled ? 'text-choco-800' : 'text-white lux-text-shadow'">Koleksi</a>
                <a href="#testimonials" class="text-[10px] font-bold uppercase tracking-[0.3em] transition-colors hover:text-gold-500" :class="scrolled ? 'text-choco-800' : 'text-white lux-text-shadow'">Kesan</a>
            </div>

            <div class="flex items-center gap-8">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isOwner() ? route('owner.dashboard') : route('customer.dashboard')) }}" 
                       class="text-[10px] font-bold uppercase tracking-[0.3em] text-gold-500 hover:text-gold-600 transition-colors">
                       Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-[10px] font-bold uppercase tracking-[0.3em] transition-colors hover:text-gold-500" :class="scrolled ? 'text-choco-800' : 'text-white lux-text-shadow'">Masuk</a>
                    <a href="{{ route('register') }}" 
                       class="px-8 py-4 rounded-2xl bg-gold-400 text-white text-[10px] font-bold uppercase tracking-[0.3em] shadow-2xl shadow-gold-400/20 hover:bg-gold-500 transition-all hover:-translate-y-1">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative h-screen min-h-[800px] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                 alt="Luxury Wedding" 
                 class="w-full h-full object-cover scale-105 animate-slow-zoom brightness-[0.7] grayscale-[10%]">
            <div class="absolute inset-0 bg-gradient-to-b from-choco-900/40 via-transparent to-stone-50"></div>
        </div>

        <div class="relative z-10 text-center max-w-5xl px-8 pt-20">
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 500)">
                <p class="text-gold-400 font-cursive text-4xl mb-6 opacity-0 transition-all duration-1000 delay-100" :class="show ? 'opacity-100 translate-y-0' : 'translate-y-8'">Magical Moments</p>
                <h1 class="font-serif text-6xl md:text-[7.5rem] text-white lux-text-shadow mb-12 leading-[1] opacity-0 transition-all duration-1000 delay-300" :class="show ? 'opacity-100 translate-y-0' : 'translate-y-12'">
                    Wujudkan Pernikahan <br> <span class="italic font-light">Impian Anda</span>
                </h1>
                <p class="text-white/80 text-lg md:text-xl font-light leading-relaxed max-w-2xl mx-auto mb-16 opacity-0 transition-all duration-1000 delay-500" :class="show ? 'opacity-100 translate-y-0' : 'translate-y-12'">
                    Layanan perencanaan dan pengelolaan pernikahan premium. Karena setiap kisah cinta layak dirayakan dengan sempurna.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-8 opacity-0 transition-all duration-1000 delay-700" :class="show ? 'opacity-100 translate-y-0' : 'translate-y-12'">
                    @auth
                        <a href="{{ route('customer.packages.index') }}" class="group relative px-12 py-6 rounded-full bg-gold-400 text-white text-[10px] font-bold uppercase tracking-[0.4em] overflow-hidden transition-all hover:scale-105">
                            <span class="relative z-10">Mulai Rencana</span>
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="group relative px-12 py-6 rounded-full bg-gold-400 text-white text-[10px] font-bold uppercase tracking-[0.4em] overflow-hidden transition-all hover:scale-105">
                            <span class="relative z-10">Pesan Sekarang</span>
                            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                        </a>
                    @endauth
                    <a href="#about" class="px-12 py-6 rounded-full border border-white/30 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-[0.4em] hover:bg-white/10 transition-all">
                        Eksplorasi
                    </a>
                </div>
            </div>
        </div>

        <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex flex-col items-center gap-6 group cursor-pointer">
            <span class="text-[9px] text-choco-300 font-bold uppercase tracking-[0.5em] group-hover:text-gold-400 transition-colors">Discover More</span>
            <div class="h-16 w-px bg-gradient-to-b from-gold-400 to-transparent group-hover:h-24 transition-all duration-700"></div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section id="about" class="py-32 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-8">
            <div class="grid lg:grid-cols-2 gap-24 items-center">
                <div class="relative group">
                    <div class="absolute -inset-6 border border-gold-200 rounded-[3rem] rotate-3 group-hover:rotate-0 transition-transform duration-1000"></div>
                    <div class="relative rounded-[3rem] overflow-hidden shadow-2xl z-10">
                        <img src="https://images.unsplash.com/photo-1544531586-fde5298cdd40?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" alt="Our Expertise">
                    </div>
                </div>

                <div class="space-y-12">
                    <div class="space-y-6 text-center lg:text-left">
                        <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.5em]">Layanan Bintang Lima</p>
                        <h2 class="font-serif text-5xl text-choco-900 leading-tight italic">Mengapa <span class="not-italic text-stone-300">Gemilang?</span></h2>
                        <p class="text-stone-400 font-light leading-relaxed text-lg italic">"Kami mendedikasikan perhatian penuh untuk hari paling berharga Anda, mengubah impian menjadi realita yang spektakuler."</p>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-8">
                        <div class="flex gap-6 items-start">
                            <div class="h-14 w-14 shrink-0 rounded-2xl bg-stone-50 flex items-center justify-center text-gold-500">
                                <i class="fas fa-crown text-xl"></i>
                            </div>
                            <div class="space-y-2">
                                <h4 class="font-bold text-choco-900 text-sm uppercase tracking-widest">Kualitas Premium</h4>
                                <p class="text-stone-400 text-xs leading-relaxed font-light">Vendor eksklusif dan perlengkapan berkelas.</p>
                            </div>
                        </div>
                        <div class="flex gap-6 items-start">
                            <div class="h-14 w-14 shrink-0 rounded-2xl bg-stone-50 flex items-center justify-center text-gold-500">
                                <i class="fas fa-magic text-xl"></i>
                            </div>
                            <div class="space-y-2">
                                <h4 class="font-bold text-choco-900 text-sm uppercase tracking-widest">Sentuhan Personal</h4>
                                <p class="text-stone-400 text-xs leading-relaxed font-light">Desain acara yang sangat personal.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section id="packages" class="py-32 bg-stone-50/50">
        <div class="max-w-7xl mx-auto px-8">
            <div class="text-center space-y-6 mb-24">
                <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.5em]">The Collections</p>
                <h2 class="font-serif text-5xl text-choco-900 italic">Pilihan <span class="not-italic text-stone-300">Esensial</span></h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-12">
                @forelse($packages as $package)
                    <div class="group relative bg-white rounded-[3rem] p-12 border border-stone-100 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col h-full">
                        @if($loop->iteration == 2)
                            <div class="absolute -top-4 left-12 px-6 py-2 bg-gold-400 text-white text-[9px] font-bold uppercase tracking-widest rounded-full shadow-lg shadow-gold-400/20">
                                Terpopuler
                            </div>
                        @endif

                        <div class="space-y-8 flex-1">
                            <div class="h-16 w-16 rounded-2xl bg-stone-50 flex items-center justify-center text-gold-400 mb-10">
                                <i class="fas fa-gem text-2xl"></i>
                            </div>

                            <div class="space-y-3">
                                <h3 class="font-serif text-3xl text-choco-900 font-bold">{{ $package->name }}</h3>
                                <p class="text-stone-400 text-sm italic font-light">Eksklusivitas dalam setiap detail perencanaan.</p>
                            </div>

                            @php
                                $discount = $package->getActiveDiscount();
                                $finalPrice = $discount ? $discount->getDiscountedPrice($package->price) : $package->price;
                            @endphp

                            <div class="py-8 border-y border-stone-50">
                                @if($discount)
                                    <div class="mb-2">
                                        <span class="text-stone-300 text-sm line-through decoration-gold-400">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <p class="text-4xl font-serif text-choco-900 tracking-tighter">Rp {{ number_format($finalPrice, 0, ',', '.') }}</p>
                                <p class="text-stone-300 text-[10px] font-bold uppercase tracking-widest mt-2">Mulai Dari</p>
                            </div>

                            <ul class="space-y-5">
                                @foreach(is_array($package->features) ? $package->features : json_decode($package->features ?? '[]', true) as $feature)
                                    <li class="flex items-center gap-4 text-xs text-stone-400 font-light">
                                        <i class="fas fa-check text-gold-400"></i>
                                        <span>{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-12 pt-8 border-t border-stone-50">
                            <a href="{{ route('customer.packages.index') }}" 
                               class="w-full flex items-center justify-center py-5 rounded-2xl border border-stone-100 text-stone-400 text-[10px] font-bold uppercase tracking-widest hover:bg-choco-900 hover:text-gold-400 transition-all hover:border-choco-900">
                                Detail Paket
                            </a>
                        </div>
                    </div>
                @empty
                    <!-- Placeholder cards if no packages -->
                @endforelse
            </div>
        </div>
    </section>

    <!-- Kesan Klien (Testimonials) Section -->
    <section id="testimonials" class="py-32 bg-stone-50/50 relative overflow-hidden" x-data="{ tab: 'text', activeVideo: null, activeVideoType: null, activeVideoTitle: '' }">
        <!-- Decorative Background elements -->
        <div class="absolute top-1/4 -left-64 w-[500px] h-[500px] rounded-full bg-gold-200/10 blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-1/4 -right-64 w-[500px] h-[500px] rounded-full bg-choco-200/10 blur-[120px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center space-y-6 mb-20">
                <p class="text-gold-500 text-[10px] font-bold uppercase tracking-[0.5em]">Kesan Kebahagiaan</p>
                <h2 class="font-serif text-5xl text-choco-900 leading-tight italic">
                    Cerita Cinta <span class="not-italic text-stone-300">Mereka</span>
                </h2>
                <p class="text-stone-400 font-light leading-relaxed text-sm max-w-lg mx-auto">
                    Kisah nyata dari pasangan berbahagia yang telah mempercayakan hari terpenting mereka dalam pengelolaan Gemilang Wedding Organizer.
                </p>

                <!-- Tab Switcher -->
                <div class="inline-flex p-1.5 rounded-full bg-stone-100 border border-stone-200/50 shadow-inner mt-8">
                    <button @click="tab = 'text'" 
                            :class="tab === 'text' ? 'bg-white text-choco-900 shadow-md font-bold' : 'text-stone-500 hover:text-choco-900 font-medium'"
                            class="px-8 py-3 rounded-full text-xs uppercase tracking-wider transition-all duration-300 cursor-pointer">
                        Kesan Klien
                        <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold transition-all" 
                              :class="tab === 'text' ? 'bg-gold-100 text-gold-700' : 'bg-stone-200 text-stone-600'">
                            {{ $reviews->count() }}
                        </span>
                    </button>
                    <button @click="tab = 'video'" 
                            :class="tab === 'video' ? 'bg-white text-choco-900 shadow-md font-bold' : 'text-stone-500 hover:text-choco-900 font-medium'"
                            class="px-8 py-3 rounded-full text-xs uppercase tracking-wider transition-all duration-300 cursor-pointer">
                        Video Testimoni
                        <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold transition-all" 
                              :class="tab === 'video' ? 'bg-gold-100 text-gold-700' : 'bg-stone-200 text-stone-600'">
                            {{ $videoTestimonials->count() }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Tab Content: Kesan Teks -->
            <div x-show="tab === 'text'" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                
                @forelse($reviews as $review)
                    <div class="group bg-white rounded-[2.5rem] p-10 border border-stone-100 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col h-full relative overflow-hidden">
                        <!-- Decorative Quote Icon -->
                        <div class="absolute right-8 top-8 text-stone-50 group-hover:text-gold-50/50 transition-colors duration-500">
                            <i class="fas fa-quote-right text-7xl font-bold"></i>
                        </div>

                        <div class="space-y-6 flex-1 relative z-10">
                            <!-- Star Rating -->
                            <div class="flex gap-1 text-gold-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fas fa-star' : 'far fa-star text-stone-200' }} text-sm"></i>
                                @endfor
                            </div>

                            <div class="space-y-3">
                                <h4 class="font-serif text-xl font-bold text-choco-900">{{ $review->title }}</h4>
                                <p class="text-stone-500 text-sm leading-relaxed font-light italic">
                                    "{{ $review->content }}"
                                </p>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="mt-8 pt-6 border-t border-stone-50 flex items-center gap-4 relative z-10">
                            <div class="h-12 w-12 rounded-full overflow-hidden bg-gradient-to-tr from-gold-100 to-gold-200 flex items-center justify-center text-gold-600 font-bold border border-gold-300 shrink-0">
                                @if($review->user->profile_image)
                                    <img src="{{ asset('storage/' . $review->user->profile_image) }}" class="w-full h-full object-cover" alt="{{ $review->user->name }}">
                                @else
                                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="truncate">
                                <h5 class="font-bold text-choco-900 text-sm tracking-wide truncate">{{ $review->user->name }}</h5>
                                <p class="text-[10px] text-stone-400 font-bold uppercase tracking-wider mt-0.5 truncate">
                                    {{ $review->package ? 'Paket ' . $review->package->name : 'Client Gemilang' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-[2.5rem] p-16 text-center border border-stone-100 shadow-sm">
                        <div class="w-20 h-20 bg-gold-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-gold-400">
                            <i class="fas fa-comment-slash text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-choco-900 italic mb-2">Belum Ada Kesan Teks</h3>
                        <p class="text-stone-400 text-sm max-w-sm mx-auto">Kami akan segera menghadirkan cerita cinta indah para pasangan kami di sini.</p>
                    </div>
                @endforelse
            </div>

            <!-- Tab Content: Kesan Video -->
            <div x-show="tab === 'video'" 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="grid md:grid-cols-2 lg:grid-cols-2 gap-12"
                 x-cloak>

                @forelse($videoTestimonials as $vt)
                    <div @click="activeVideo = '{{ $vt->type === 'youtube' ? $vt->getEmbedUrl() : asset('storage/' . $vt->video_path) }}'; activeVideoType = '{{ $vt->type }}'; activeVideoTitle = '{{ $vt->title }}'" 
                         class="group cursor-pointer relative bg-white rounded-[3rem] overflow-hidden border border-stone-100 shadow-sm hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col h-full">
                        
                        <!-- Thumbnail/Preview Area -->
                        <div class="relative aspect-video w-full bg-stone-900 overflow-hidden">
                            @if($vt->thumbnail_path)
                                <img src="{{ asset('storage/' . $vt->thumbnail_path) }}" alt="{{ $vt->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 brightness-[0.85] group-hover:brightness-[0.75]">
                            @elseif($vt->type === 'youtube')
                                <img src="https://img.youtube.com/vi/{{ $vt->getYoutubeId() }}/hqdefault.jpg" alt="{{ $vt->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 brightness-[0.85] group-hover:brightness-[0.75]">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center bg-stone-900">
                                    <i class="fas fa-video text-white/20 text-5xl"></i>
                                </div>
                            @endif

                            <!-- Glow Ring Play Button Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="relative h-16 w-16 flex items-center justify-center">
                                    <div class="absolute inset-0 rounded-full bg-gold-400/30 scale-100 animate-ping group-hover:scale-110"></div>
                                    <div class="h-16 w-16 rounded-full bg-gold-400 text-white flex items-center justify-center shadow-lg shadow-gold-500/30 transition-transform duration-500 group-hover:scale-110">
                                        <i class="fas fa-play text-lg ml-1"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Duration or Badge Overlay -->
                            <div class="absolute bottom-4 left-6">
                                <span class="px-3 py-1 rounded-full bg-black/40 backdrop-blur-md text-white text-[9px] font-bold uppercase tracking-wider border border-white/10 flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-gold-400"></span>
                                    {{ $vt->type === 'youtube' ? 'YouTube Video' : 'Video Upload' }}
                                </span>
                            </div>
                        </div>

                        <!-- Testimonial Details -->
                        <div class="p-8 flex-1 flex flex-col justify-between">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex gap-0.5 text-gold-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $vt->rating ? 'fas fa-star' : 'far fa-star text-stone-200' }} text-xs"></i>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] text-stone-400 font-bold uppercase tracking-widest">{{ $vt->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                                <h3 class="font-serif text-2xl font-bold text-choco-900 group-hover:text-gold-500 transition-colors duration-300 line-clamp-1">{{ $vt->title }}</h3>
                                <p class="text-stone-400 text-sm font-light leading-relaxed line-clamp-2 italic">"{{ $vt->description }}"</p>
                            </div>

                            <!-- Author details -->
                            <div class="mt-8 pt-6 border-t border-stone-50 flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full overflow-hidden bg-gradient-to-tr from-gold-100 to-gold-200 flex items-center justify-center text-gold-600 font-bold border border-gold-300 shrink-0">
                                    @if($vt->user->profile_image)
                                        <img src="{{ asset('storage/' . $vt->user->profile_image) }}" class="w-full h-full object-cover" alt="{{ $vt->user->name }}">
                                    @else
                                        {{ strtoupper(substr($vt->user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div class="truncate">
                                    <h5 class="font-bold text-choco-900 text-sm tracking-wide truncate">{{ $vt->user->name }}</h5>
                                    <p class="text-[10px] text-stone-400 font-bold uppercase tracking-wider mt-0.5 truncate">
                                        {{ $vt->order && $vt->order->package ? 'Paket ' . $vt->order->package->name : 'Happy Couple' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-[3rem] p-16 text-center border border-stone-100 shadow-sm">
                        <div class="w-20 h-20 bg-gold-50 rounded-2xl flex items-center justify-center mx-auto mb-6 text-gold-400">
                            <i class="fas fa-video-slash text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-serif font-bold text-choco-900 italic mb-2">Belum Ada Video Testimoni</h3>
                        <p class="text-stone-400 text-sm max-w-sm mx-auto">Nantikan rangkuman kemeriahan dan kepuasan hari bahagia klien kami segera.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Video Lightbox Modal -->
        <div x-show="activeVideo" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
             x-cloak>
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-stone-950/80 backdrop-blur-md" @click="activeVideo = null; activeVideoType = null"></div>

            <!-- Modal Content -->
            <div class="relative bg-stone-900 rounded-[2rem] overflow-hidden shadow-2xl w-full max-w-4xl z-10 border border-stone-800">
                <!-- Header -->
                <div class="flex items-center justify-between px-8 py-6 border-b border-stone-800/50">
                    <h4 class="text-white font-serif text-lg font-bold truncate pr-4" x-text="activeVideoTitle">Detail Testimoni Video</h4>
                    <button @click="activeVideo = null; activeVideoType = null" class="h-10 w-10 shrink-0 flex items-center justify-center rounded-full bg-white/5 hover:bg-gold-400 text-white/70 hover:text-white transition-all cursor-pointer">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <!-- Video Player Container -->
                <div class="aspect-video bg-black w-full">
                    <template x-if="activeVideoType === 'youtube' && activeVideo">
                        <iframe :src="activeVideo + '?autoplay=1'" 
                                class="w-full h-full border-0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen></iframe>
                    </template>
                    <template x-if="activeVideoType === 'upload' && activeVideo">
                        <video :src="activeVideo" 
                               class="w-full h-full" 
                               controls 
                               autoplay></video>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-24 bg-choco-900 text-white">
        <div class="max-w-7xl mx-auto px-8 grid lg:grid-cols-4 gap-20">
            <div class="lg:col-span-2 space-y-10">
                <a href="/" class="flex items-center gap-4 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Gemilang WO" class="h-12 w-auto object-contain brightness-0 invert">
                    <span class="font-serif text-3xl font-bold tracking-tight">Gemilang</span>
                </a>
                <p class="text-white/40 text-sm font-light leading-relaxed max-w-sm italic">
                    "Mewujudkan impian setiap pasangan dengan sentuhan eksklusivitas, keanggunan, dan ketulusan hati yang tak tertandingi."
                </p>
                <div class="flex gap-6">
                    <a href="#" class="h-10 w-10 flex items-center justify-center rounded-full bg-white/5 hover:bg-gold-400 transition-all text-white/40 hover:text-white">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="h-10 w-10 flex items-center justify-center rounded-full bg-white/5 hover:bg-gold-400 transition-all text-white/40 hover:text-white">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <div class="space-y-10">
                <h5 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gold-400">Navigation</h5>
                <ul class="space-y-5 text-sm font-light text-white/60">
                    <li><a href="#about" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="#packages" class="hover:text-white transition-colors">Koleksi Paket</a></li>
                    <li><a href="#testimonials" class="hover:text-white transition-colors">Kesan Klien</a></li>
                </ul>
            </div>

            <div class="space-y-10">
                <h5 class="text-[10px] font-bold uppercase tracking-[0.4em] text-gold-400">Newsletter</h5>
                <p class="text-white/40 text-xs font-light tracking-wide">Dapatkan inspirasi pernikahan eksklusif langsung di inbox Anda.</p>
                <div class="relative group">
                    <input type="email" placeholder="email@luxury.com" class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-xs outline-none focus:border-gold-400 transition-colors">
                    <button class="absolute right-2 top-2 h-10 w-10 bg-gold-400 rounded-xl flex items-center justify-center hover:bg-gold-500 transition-colors shadow-lg shadow-gold-400/20">
                        <i class="fas fa-paper-plane text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-8 mt-24 pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-8">
            <p class="text-white/20 text-[10px] font-bold uppercase tracking-[0.3em]">&copy; {{ date('Y') }} Gemilang Wedding Organizer. Excellence in Detail.</p>
            <div class="flex gap-12 text-white/20 text-[10px] font-bold uppercase tracking-[0.3em]">
                <a href="#" class="hover:text-white transition-colors">Privacy</a>
                <a href="#" class="hover:text-white transition-colors">Terms</a>
            </div>
        </div>
    </footer>

</body>
</html>
