@extends('layouts.auth')

@section('title', 'Daftar - Gemilang WO')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #b8860b;
        --secondary: #8b7355;
        --dark: #2A1F18;
        --light: #FCF9F2;
    }

    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--light);
        color: var(--dark);
    }

    .auth-wrapper {
        display: flex;
        min-height: 100vh;
        width: 100vw;
    }

    .auth-banner {
        flex: 1;
        display: none;
        background: linear-gradient(rgba(42, 31, 24, 0.4), rgba(42, 31, 24, 0.7)), url('https://images.unsplash.com/photo-1544531586-fde5298cdd40?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;
        position: relative;
    }

    @media (min-width: 992px) {
        .auth-banner {
            display: block;
        }
    }

    .auth-banner-content {
        position: absolute;
        bottom: 10%;
        left: 10%;
        color: white;
        max-width: 80%;
    }

    .auth-banner-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .auth-banner-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 300;
    }

    .auth-form-container {
        width: 100%;
        max-width: 550px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 4rem;
        background: white;
        box-shadow: -10px 0 30px rgba(0,0,0,0.03);
        z-index: 10;
        position: relative;
        overflow-y: auto;
    }

    @media (max-width: 991px) {
        .auth-form-container {
            max-width: 100%;
            padding: 2rem;
            align-items: center;
        }
        .auth-form-wrapper {
            width: 100%;
            max-width: 450px;
        }
    }

    .brand-logo {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--dark);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        margin-bottom: 2rem;
    }

    .brand-logo i {
        color: var(--primary);
        margin-right: 10px;
    }

    .auth-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }

    .auth-subtitle {
        color: #6b7280;
        margin-bottom: 2.5rem;
        font-size: 0.95rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #374151;
        display: block;
    }

    .input-group {
        display: flex;
        align-items: center;
        border-radius: 12px;
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .input-group:focus-within {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(184, 134, 11, 0.1);
        background-color: white;
    }

    .input-group-text {
        background: transparent;
        border: none;
        padding: 0 1.2rem;
        color: #9ca3af;
    }

    .input-group:focus-within .input-group-text {
        color: var(--primary);
    }

    .form-control {
        border: none;
        background: transparent;
        padding: 1rem 1rem 1rem 0;
        font-size: 0.95rem;
        flex: 1;
        width: 100%;
        color: var(--dark);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .form-control:focus {
        outline: none;
    }

    .btn-login {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 2rem;
        margin-top: 1rem;
    }

    .btn-login:hover {
        background: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(184, 134, 11, 0.2);
    }

    .auth-footer {
        text-align: center;
        font-size: 0.95rem;
        color: #4b5563;
    }

    .auth-footer a {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .auth-footer a:hover {
        text-decoration: underline;
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: block;
    }

    .alert-danger {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        padding: 1rem;
        color: #b91c1c;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <!-- Form Section -->
    <div class="auth-form-container">
        <div class="auth-form-wrapper">
            <a href="{{ route('home') }}" class="brand-logo">
                <i class="fas fa-ring"></i> Gemilang WO
            </a>

            <h2 class="auth-title">Daftar Akun Klien</h2>
            <p class="auth-subtitle">Mari bergabung dan mulai rancang setiap detil perayaan Anda.</p>

            @if ($errors->any())
                <div class="alert-danger">
                    <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                    Silakan periksa kembali isian formulir di bawah ini.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Full Name Field -->
                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input id="name" type="text" 
                               class="form-control" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                               placeholder="Contoh: Budi Santoso">
                    </div>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Aktif</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input id="email" type="email" 
                               class="form-control" 
                               name="email" value="{{ old('email') }}" required autocomplete="email"
                               placeholder="you@example.com">
                    </div>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi Akses</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input id="password" type="password" 
                               class="form-control" 
                               name="password" required autocomplete="new-password"
                               placeholder="Minimal 8 karakter">
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                        <input id="password_confirmation" type="password" 
                               class="form-control" 
                               name="password_confirmation" required autocomplete="new-password"
                               placeholder="Ulangi kata sandi Anda">
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn-login">
                    Buat Akun Klien <i class="fas fa-user-plus ms-2"></i>
                </button>
            </form>

            <div class="auth-footer">
                Sudah memiliki ruang akses? <a href="{{ route('login') }}">Masuk Disini</a>
            </div>
            
            <div class="mt-4 pt-4 border-top text-center">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none" style="font-size: 0.85rem;"><i class="fas fa-arrow-left me-1"></i> Kembali ke Beranda Utama</a>
            </div>
        </div>
    </div>

    <!-- Image Banner (Right Side for Register to contrast with Login) -->
    <div class="auth-banner" style="background: linear-gradient(rgba(42, 31, 24, 0.4), rgba(42, 31, 24, 0.7)), url('https://images.unsplash.com/photo-1544531586-fde5298cdd40?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover no-repeat;">
        <div class="auth-banner-content" style="right: 10%; left: auto; text-align: right;">
            <h1>Momen Terindah Di Tangan Yang Tepat</h1>
            <p>Jelajahi paket unggulan kami dan realisasikan konsep magis di hari spesial Anda tanpa stres.</p>
        </div>
    </div>
</div>
@endsection
