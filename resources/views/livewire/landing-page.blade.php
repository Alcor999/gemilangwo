<div>
    {{-- Hero Section --}}
    <section class="relative h-screen flex items-center justify-center overflow-hidden bg-premium-dark">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-b from-black/60 to-black/30"></div>
            {{-- Image would go here --}}
            <div class="w-full h-full bg-[url('https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-40"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center" x-data="{ show: false }" x-init="setTimeout(() => show = true, 300)">
            <h1 x-show="show" x-transition.duration.1000ms class="text-5xl md:text-8xl font-serif text-gold-500 mb-6 drop-shadow-2xl">Gemilang Wedding Organizer</h1>
            <p x-show="show" x-transition.delay.500ms.duration.1000ms class="text-xl md:text-2xl text-gold-100/80 mb-12 max-w-2xl mx-auto font-light tracking-wide uppercase">Mewujudkan Momen Bahagia Dengan Sentuhan Kemewahan Yang Tak Terlupakan</p>
            <div x-show="show" x-transition.delay.1000ms.duration.1000ms class="flex flex-col md:flex-row items-center justify-center gap-6">
                <a href="#packages" class="px-10 py-4 bg-gold-500 text-black font-bold rounded-full hover:bg-gold-400 transition-all transform hover:scale-105 shadow-xl">Eksplor Paket</a>
                <a href="{{ route('customer.orders.create') }}" class="px-10 py-4 border-2 border-gold-500 text-gold-500 font-bold rounded-full hover:bg-gold-500 hover:text-black transition-all transform hover:scale-105 shadow-xl">Buat Pesanan</a>
            </div>
        </div>

        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-gold-500 text-2xl"></i>
        </div>
    </section>

    {{-- Why Us Section --}}
    <section class="py-24 bg-premium-cream">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-serif text-brown-950 mb-4 italic">Pelayanan Eksklusif</h2>
                <div class="w-24 h-1 bg-gold-500 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="p-10 bg-white rounded-2xl shadow-lg border-t-4 border-gold-500 group hover:-translate-y-2 transition-all">
                    <div class="w-16 h-16 bg-gold-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-gold-500 transition-colors">
                        <i class="fas fa-gem text-gold-600 group-hover:text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-brown-900 mb-4">Kualitas Premium</h3>
                    <p class="text-brown-700 font-light leading-relaxed">Setiap detail dirancang secara profesional untuk memastikan kemewahan pada hari spesial Anda.</p>
                </div>
                <div class="p-10 bg-white rounded-2xl shadow-lg border-t-4 border-gold-500 group hover:-translate-y-2 transition-all">
                    <div class="w-16 h-16 bg-gold-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-gold-500 transition-colors">
                        <i class="fas fa-magic text-gold-600 group-hover:text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-brown-900 mb-4">Tim Berpengalaman</h3>
                    <p class="text-brown-700 font-light leading-relaxed">Dukungan Wedding Organizer yang telah menangani ratusan perayaan impian.</p>
                </div>
                <div class="p-10 bg-white rounded-2xl shadow-lg border-t-4 border-gold-500 group hover:-translate-y-2 transition-all">
                    <div class="w-16 h-16 bg-gold-50 rounded-full flex items-center justify-center mb-6 group-hover:bg-gold-500 transition-colors">
                        <i class="fas fa-heart text-gold-600 group-hover:text-white text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-brown-900 mb-4">Pelayanan Personal</h3>
                    <p class="text-brown-700 font-light leading-relaxed">Kami mendengarkan keinginan Anda dan mewujudkannya dalam rencana yang sempurna.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Packages Section --}}
    <section id="packages" class="py-24 bg-brown-950 text-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-6">
                <div>
                    <h2 class="text-4xl md:text-5xl font-serif text-gold-500 mb-4">Pilihan Paket Wedding</h2>
                    <p class="text-gold-100/60 max-w-xl">Pilih paket yang paling sesuai dengan impian dan anggaran Anda. Semua paket dapat disesuaikan dengan pilihan vendor terbaik.</p>
                </div>
                <a href="{{ route('customer.packages.index') }}" class="text-gold-500 hover:text-gold-400 font-bold border-b border-gold-500 pb-1">Lihat Semua Paket <i class="fas fa-arrow-right ml-2 text-sm"></i></a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($packages->take(3) as $package)
                    <div class="group bg-premium-dark/50 rounded-2xl overflow-hidden border border-gold-900/30 hover:border-gold-500/50 transition-all shadow-2xl">
                        <div class="relative h-64 overflow-hidden">
                            @if($package->image)
                                <img src="{{ asset('storage/' . $package->image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $package->name }}">
                            @else
                                <div class="w-full h-full bg-brown-900 flex items-center justify-center">
                                    <img src="{{ asset('images/logo.png') }}" class="h-16 w-16 object-contain opacity-30" alt="">
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-gold-500 text-black px-4 py-1 rounded-full font-bold text-sm shadow-lg">
                                Hingga {{ $package->max_guests }} Tamu
                            </div>
                        </div>
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gold-500 mb-2">{{ $package->name }}</h3>
                            <p class="text-gold-100/50 text-sm mb-6 line-clamp-2">{{ $package->description }}</p>
                            <div class="flex items-center justify-between">
                                <div class="text-2xl font-serif text-gold-400">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                <a href="{{ route('customer.orders.create', ['package_id' => $package->id]) }}" class="w-12 h-12 bg-gold-500 text-black rounded-full flex items-center justify-center hover:bg-white transition-colors shadow-lg">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gold-500 overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gold-400/30 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="text-center md:text-left">
                    <h2 class="text-3xl md:text-5xl font-serif text-black mb-4">Siap Memulai Rencana Bahagia Anda?</h2>
                    <p class="text-black/70 text-lg">Konsultasikan kebutuhan Anda bersama tim profesional kami secara gratis.</p>
                </div>
                <div class="flex gap-4">
                    <a href="#" class="px-8 py-4 bg-black text-gold-500 font-bold rounded-xl hover:bg-brown-950 transition-all shadow-2xl text-center">Hubungi Kami</a>
                    <a href="{{ route('customer.orders.create') }}" class="px-8 py-4 bg-white text-black font-bold rounded-xl hover:bg-gold-50 transition-all shadow-2xl text-center">Buat Pesanan</a>
                </div>
            </div>
        </div>
    </section>
</div>
