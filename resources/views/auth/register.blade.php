@extends('layouts.auth')

@section('title', 'Register - Gemilang WO')

@section('styles')
<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%);
        padding: 1rem;
    }

    .login-card {
        width: 100%;
        max-width: 450px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(184, 134, 11, 0.2);
        overflow: hidden;
        animation: slideUp 0.4s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-header {
        background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%);
        padding: 2.5rem 2rem;
        text-align: center;
        color: white;
    }

    .login-icon {
        font-size: 3.5rem;
        margin-bottom: 1rem;
    }

    .login-header h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
        letter-spacing: 0.5px;
    }

    .login-header p {
        font-size: 1rem;
        margin: 0;
        opacity: 0.95;
        font-weight: 500;
    }

    .login-body {
        padding: 2.5rem 2rem;
    }

    .alert {
        border-radius: 8px;
        border: none;
        margin-bottom: 1.5rem;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #1f2937;
        display: block;
    }

    .input-group {
        display: flex;
        align-items: center;
        border-radius: 8px;
        background-color: #f9fafb;
        overflow: hidden;
    }

    .input-group-text {
        background-color: #f3f4f6;
        border: none;
        padding: 0 1rem;
        color: #b8860b;
        width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-control {
        border: none;
        background-color: #f9fafb;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        flex: 1;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        background-color: white;
        box-shadow: inset 0 0 0 2px rgba(184, 134, 11, 0.2);
    }

    .form-check {
        margin-bottom: 1.5rem;
    }

    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        border: 2px solid #d1d5db;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 0.3rem;
    }

    .form-check-input:checked {
        background-color: #b8860b;
        border-color: #b8860b;
    }

    .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(184, 134, 11, 0.25);
        border-color: #b8860b;
    }

    .form-check-label {
        font-size: 1rem;
        color: #4b5563;
        cursor: pointer;
        margin-left: 0.5rem;
    }

    .btn-login {
        width: 100%;
        padding: 0.875rem 1.5rem;
        font-size: 1.05rem;
        font-weight: 600;
        background: linear-gradient(135deg, #b8860b 0%, #8b7355 100%);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(184, 134, 11, 0.3);
        margin-bottom: 1.5rem;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(184, 134, 11, 0.4);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .divider {
        height: 1px;
        background-color: #e5e7eb;
        margin: 1.5rem 0;
    }

    .login-register {
        text-align: center;
        font-size: 1rem;
        color: #4b5563;
        margin-bottom: 1.5rem;
    }

    .login-register a {
        color: #b8860b;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .login-register a:hover {
        opacity: 0.8;
    }

    .login-back-link {
        display: block;
        text-align: center;
        color: white;
        text-decoration: none;
        font-weight: 500;
        font-size: 1rem;
        transition: all 0.2s ease;
        margin-top: 1rem;
    }

    .login-back-link:hover {
        opacity: 0.8;
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: block;
    }

    @media (max-width: 640px) {
        .login-container {
            padding: 0.5rem;
        }

        .login-card {
            max-width: 100%;
        }

        .login-header {
            padding: 2rem 1.5rem;
        }

        .login-header h2 {
            font-size: 1.5rem;
        }

        .login-icon {
            font-size: 3rem;
            margin-bottom: 0.75rem;
        }

        .login-body {
            padding: 1.75rem 1.5rem;
        }

        .form-label {
            font-size: 0.95rem;
        }

        .form-control {
            font-size: 0.95rem;
            padding: 0.75rem 0.875rem;
        }

        .btn-login {
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
        }

        .login-register {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .login-header {
            padding: 1.5rem 1rem;
        }

        .login-body {
            padding: 1.5rem 1rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .login-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .login-header h2 {
            font-size: 1.3rem;
        }

        .login-header p {
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">
        <!-- Header -->
        <div class="login-header">
            <div class="login-icon">
                <i class="fas fa-ring"></i>
            </div>
            <h2>Gemilang WO</h2>
            <p>Create Your Account</p>
        </div>

        <!-- Body -->
        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Error!</strong> Please check the form and try again.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Full Name Field -->
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input id="name" type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                               placeholder="Enter your full name">
                    </div>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" required autocomplete="email"
                               placeholder="you@example.com">
                    </div>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password"
                               placeholder="Create a password">
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password_confirmation" type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               name="password_confirmation" required autocomplete="new-password"
                               placeholder="Confirm your password">
                    </div>
                    @error('password_confirmation')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-user-plus me-2"></i> Create Account
                </button>
            </form>

            <!-- Divider -->
            <div class="divider"></div>

            <!-- Login Link -->
            <p class="login-register">
                Already have an account?<br>
                <a href="{{ route('login') }}">Login here</a>
            </p>

            <!-- Back to Home -->
            <a href="{{ route('home') }}" class="login-back-link">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
