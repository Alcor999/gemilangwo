@extends('layouts.auth')

@section('title', 'Akses Eksklusif - Gemilang WO')

@section('content')
<div class="flex min-h-full">
    <!-- Left: Cinematic Banner -->
    <div class="relative hidden w-0 flex-1 lg:block">
        <img class="absolute inset-0 h-full w-full object-cover grayscale-[20%] brightness-75" 
             src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
             alt="Luxury Wedding Event">
        <div class="absolute inset-0 bg-choco-900/40 backdrop-blur-[2px]"></div>
        
        <div class="absolute inset-0 flex flex-col justify-end p-20 text-white">
            <div class="max-w-xl">
                <p class="text-gold-400 text-xs font-bold uppercase tracking-[0.4em] mb-6">Reserved For Perfectionists</p>
                <h1 class="font-serif text-6xl leading-tight mb-8 italic">Mewujudkan <span class="text-white not-italic">Impian</span> Terindah Anda.</h1>
                <p class="text-white/70 text-lg font-light leading-relaxed">
                    Masuk ke dalam ekosistem perencanaan pernikahan paling eksklusif dan mulailah perjalanan menuju hari yang sempurna.
                </p>
            </div>
            
            <div class="mt-20 flex items-center gap-6">
                <div class="h-[1px] flex-1 bg-white/20"></div>
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/40">Gemilang Wedding Organizer</p>
            </div>
        </div>
    </div>

    <!-- Right: Login Form -->
    <div class="flex flex-1 flex-col justify-center px-8 py-12 sm:px-12 lg:flex-none lg:px-20 xl:px-32 bg-white">
        <div class="mx-auto w-full max-w-sm lg:w-96">
            <div class="mb-12">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Gemilang" class="h-10 w-auto object-contain">
                    <span class="font-serif text-2xl text-choco-900 font-bold tracking-tight">Gemilang</span>
                </a>
            </div>

            <div class="space-y-2">
                <h2 class="text-3xl font-serif text-choco-900 italic font-medium tracking-tight">Selamat Datang</h2>
                <p class="text-stone-400 text-sm font-medium tracking-wide">Silakan masuk untuk mengakses dasbor Anda.</p>
            </div>

            <div class="mt-10">
                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3">
                            <svg class="h-5 w-5 text-rose-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-rose-600 text-[11px] font-bold uppercase tracking-widest leading-relaxed">Credential Error: Silakan periksa kembali email & sandi Anda.</p>
                        </div>
                    @endif

                    <div class="space-y-5">
                        <div class="space-y-2">
                            <label for="email" class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 px-1">Email Address</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-stone-300 group-focus-within:text-gold-500 transition-colors">
                                    <i class="fas fa-envelope text-sm"></i>
                                </div>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                                       class="block w-full pl-11 pr-4 py-3.5 bg-stone-50 border border-stone-100 rounded-2xl text-choco-900 text-sm focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500/50 transition-all placeholder:text-stone-300 outline-none" 
                                       placeholder="example@email.com">
                            </div>
                        </div>

                        <div class="space-y-2 text-right">
                            <label for="password" class="text-[10px] font-bold uppercase tracking-[0.2em] text-stone-400 px-1 float-left">Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-stone-300 group-focus-within:text-gold-500 transition-colors">
                                    <i class="fas fa-lock text-sm"></i>
                                </div>
                                <input id="password" name="password" type="password" required 
                                       class="block w-full pl-11 pr-4 py-3.5 bg-stone-50 border border-stone-100 rounded-2xl text-choco-900 text-sm focus:bg-white focus:ring-4 focus:ring-gold-500/10 focus:border-gold-500/50 transition-all placeholder:text-stone-300 outline-none" 
                                       placeholder="••••••••">
                            </div>
                            <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-gold-500 hover:text-gold-600 transition-colors inline-block mt-2">Lupa Kata Sandi?</a>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-stone-200 text-choco-900 focus:ring-gold-500">
                        <label for="remember" class="ml-3 text-[11px] font-bold uppercase tracking-widest text-stone-400">Ingat Saya</label>
                    </div>

                    <button type="submit" 
                            class="w-full flex justify-center py-4 px-4 bg-choco-900 text-gold-400 text-[10px] font-bold uppercase tracking-[0.3em] rounded-2xl shadow-xl shadow-choco-900/10 hover:bg-choco-800 focus:ring-4 focus:ring-gold-500/20 transition-all">
                        Masuk Ke Dasbor
                    </button>
                    
                    <div class="pt-4 text-center">
                        <p class="text-stone-400 text-[11px] font-bold uppercase tracking-widest">
                            Baru di Gemilang? <a href="{{ route('register') }}" class="text-gold-500 hover:text-gold-600 ml-1">Buat Akun Sekarang</a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Test Accounts Information -->
            <div class="mt-16 p-6 bg-stone-50 rounded-3xl border border-stone-100 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 h-20 w-20 bg-gold-400/5 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
                <h4 class="text-[10px] font-bold uppercase tracking-[0.2em] text-gold-500 mb-4 px-1 flex items-center gap-2">
                    <span class="h-1 w-1 rounded-full bg-gold-500"></span>
                    Test Credentials
                </h4>
                <div class="space-y-2 px-1">
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-stone-400 uppercase tracking-tighter">Admin Account</span>
                        <code class="text-choco-900 font-bold bg-white px-2 py-0.5 rounded shadow-sm">admin@gemilangwo.test</code>
                    </div>
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-stone-400 uppercase tracking-tighter">Owner Account</span>
                        <code class="text-choco-900 font-bold bg-white px-2 py-0.5 rounded shadow-sm">owner@gemilangwo.test</code>
                    </div>
                    <div class="flex justify-between items-center text-[10px]">
                        <span class="font-bold text-stone-400 uppercase tracking-tighter">Default Password</span>
                        <code class="text-choco-900 font-bold bg-white px-2 py-0.5 rounded shadow-sm">password123</code>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-stone-400 hover:text-choco-900 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Halaman Utama</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
