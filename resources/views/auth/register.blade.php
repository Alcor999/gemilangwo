@extends('layouts.auth')

@section('title', 'Pendaftaran Eksklusif - Gemilang WO')

@section('content')
<div class="flex min-h-full">
    <!-- Left: Register Form -->
    <div class="flex flex-1 flex-col justify-center px-8 py-12 sm:px-12 lg:flex-none lg:px-20 xl:px-32 bg-white">
        <div class="mx-auto w-full max-sm lg:w-96">
            <div class="mb-12 text-center lg:text-left">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-3">
                    <div class="h-10 w-10 bg-choco-900 rounded-xl flex items-center justify-center text-gold-400 group-hover:scale-110 transition-transform">
                        <i class="fas fa-ring text-xl"></i>
                    </div>
                    <span class="font-serif text-2xl text-choco-900 font-bold tracking-tight">Gemilang</span>
                </a>
            </div>

            <div class="space-y-2 text-center lg:text-left">
                <h2 class="text-3xl font-serif text-choco-900 italic font-medium tracking-tight">Buat Profil Klien</h2>
                <p class="text-stone-400 text-sm font-medium tracking-wide px-4 lg:px-0">Mulai merancang setiap detil perayaan Anda bersama tim profesional kami.</p>
            </div>

            <div class="mt-10">
                <form action="{{ route('register') }}" method="POST" class="space-y-5">
                    @csrf

                    @if ($errors->any())
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3">
                            <svg class="h-5 w-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-rose-600 text-[11px] font-bold uppercase tracking-widest leading-relaxed">Validation Error: Silakan lengkapi formulir Anda dengan benar.</p>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <!-- Full Name -->
                        <div class="space-y-1.5">
                            <label for="name" class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 px-1 text-center block lg:text-left">Full Name</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-stone-300 group-focus-within:text-gold-500 transition-colors">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required 
                                       class="block w-full pl-11 pr-4 py-3.5 bg-stone-50 border border-stone-100 rounded-2xl text-choco-900 text-sm focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500/50 transition-all placeholder:text-stone-300 outline-none text-center lg:text-left" 
                                       placeholder="Nama Lengkap Anda">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label for="email" class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 px-1 text-center block lg:text-left">Email Address</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-stone-300 group-focus-within:text-gold-500 transition-colors">
                                    <i class="fas fa-envelope text-sm"></i>
                                </div>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                                       class="block w-full pl-11 pr-4 py-3.5 bg-stone-50 border border-stone-100 rounded-2xl text-choco-900 text-sm focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500/50 transition-all placeholder:text-stone-300 outline-none text-center lg:text-left" 
                                       placeholder="hello@example.com">
                            </div>
                        </div>

                        <!-- Passwords Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="password" class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 px-1 block text-center lg:text-left">Password</label>
                                <div class="relative group">
                                    <input id="password" name="password" type="password" required 
                                           class="block w-full px-4 py-3.5 bg-stone-50 border border-stone-100 rounded-2xl text-choco-900 text-sm focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500/50 transition-all placeholder:text-stone-300 outline-none text-center lg:text-left" 
                                           placeholder="••••••••">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label for="password_confirmation" class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 px-1 block text-center lg:text-left">Confirm</label>
                                <div class="relative group">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                                           class="block w-full px-4 py-3.5 bg-stone-50 border border-stone-100 rounded-2xl text-choco-900 text-sm focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500/50 transition-all placeholder:text-stone-300 outline-none text-center lg:text-left" 
                                           placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center py-2 px-1">
                        <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 rounded border-stone-200 text-choco-900 focus:ring-gold-500">
                        <label for="terms" class="ml-3 text-[10px] font-bold uppercase tracking-widest text-stone-400 leading-relaxed">
                            Saya menyetujui <a href="#" class="text-gold-500">Syarat & Ketentuan</a> Platinum Member.
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full flex justify-center py-4 px-4 bg-choco-900 text-gold-400 text-[10px] font-bold uppercase tracking-[0.3em] rounded-2xl shadow-xl shadow-choco-900/10 hover:bg-choco-800 transition-all">
                        Daftar Sebagai Klien
                    </button>
                    
                    <div class="pt-4 text-center">
                        <p class="text-stone-400 text-[11px] font-bold uppercase tracking-widest">
                            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-gold-500 hover:text-gold-600 ml-1">Masuk Sekarang</a>
                        </p>
                    </div>
                </form>
            </div>
            
            <div class="mt-12 text-center lg:text-left">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-stone-400 hover:text-choco-900 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Batal & Kembali</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Right: Cinematic Banner -->
    <div class="relative hidden w-0 flex-1 lg:block">
        <img class="absolute inset-0 h-full w-full object-cover grayscale-[20%] brightness-75" 
             src="https://images.unsplash.com/photo-1544531586-fde5298cdd40?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
             alt="Luxury Bridal Suite">
        <div class="absolute inset-0 bg-choco-900/30 backdrop-blur-[1px]"></div>
        
        <div class="absolute inset-0 flex flex-col justify-between p-20 text-white">
            <div class="flex justify-end">
                <div class="flex items-center gap-4 bg-white/10 backdrop-blur-md px-6 py-3 rounded-full border border-white/20">
                    <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.3em]">Hanya 3 Slot Tersisa di Bulan Ini</span>
                </div>
            </div>
            
            <div class="max-w-xl text-right ml-auto">
                <p class="text-gold-400 text-xs font-bold uppercase tracking-[0.4em] mb-6">Platinum Experience</p>
                <h1 class="font-serif text-6xl leading-tight mb-8 italic">Momen Terindah Di Tangan Yang <span class="text-white not-italic">Tepat.</span></h1>
                <p class="text-white/70 text-lg font-light leading-relaxed">
                    Kami tidak hanya merencanakan acara; kami merancang warisan kenangan yang akan Anda bawa selamanya. Bergabunglah dengan keluarga eksklusif kami.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
