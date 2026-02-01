<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Wedding Organizer - Professional Event Planning Services</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- GLightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary: #b8860b;
            --secondary: #8b7355;
            --dark: #1f2937;
            --light: #f9fafb;
            --border-color: #e5e7eb;
        }

        body {
            background-color: var(--light);
            color: var(--dark);
        }

        /* Navbar Styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .nav-link {
            font-weight: 500;
            margin-left: 1rem;
            color: white !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }

        .btn-login {
            background-color: white;
            color: var(--primary);
            font-weight: 600;
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 6rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 50%);
            animation: heroShine 15s ease-in-out infinite;
        }
        @keyframes heroShine {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-25%, -25%); }
        }
        .hero-section h1 {
            animation: fadeInUp 0.8s ease-out;
        }
        .hero-section p {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }
        .hero-section .btn-primary-custom {
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .btn-primary-custom {
            background-color: white;
            color: var(--primary);
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.95);
        }

        /* Packages Section */
        .packages-section {
            padding: 5rem 0;
            background-color: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .section-title p {
            font-size: 1.1rem;
            color: #6b7280;
        }

        /* Package Card */
        .package-card {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 2rem;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .package-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
            box-shadow: 0 20px 40px rgba(184, 134, 11, 0.15);
        }
        .packages-section .col-md-6,
        .packages-section .col-lg-4 {
            animation: cardFadeIn 0.6s ease-out both;
        }
        .packages-section .col-md-6:nth-child(1),
        .packages-section .col-lg-4:nth-child(1) { animation-delay: 0.1s; }
        .packages-section .col-md-6:nth-child(2),
        .packages-section .col-lg-4:nth-child(2) { animation-delay: 0.2s; }
        .packages-section .col-md-6:nth-child(3),
        .packages-section .col-lg-4:nth-child(3) { animation-delay: 0.3s; }
        .packages-section .col-md-6:nth-child(4),
        .packages-section .col-lg-4:nth-child(4) { animation-delay: 0.4s; }
        .packages-section .col-md-6:nth-child(5),
        .packages-section .col-lg-4:nth-child(5) { animation-delay: 0.5s; }
        .packages-section .col-md-6:nth-child(6),
        .packages-section .col-lg-4:nth-child(6) { animation-delay: 0.6s; }
        @keyframes cardFadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .package-card.featured {
            border-color: var(--secondary);
            background: linear-gradient(135deg, rgba(139, 115, 85, 0.05) 0%, rgba(184, 134, 11, 0.05) 100%);
            transform: scale(1.05);
        }

        .package-card.featured::before {
            content: "POPULAR";
            position: absolute;
            top: -10px;
            right: -40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.5rem 3rem;
            font-weight: 700;
            font-size: 0.75rem;
            transform: rotate(45deg);
        }

        .package-header {
            margin-bottom: 1.5rem;
        }

        .package-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .package-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .package-desc {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }

        .package-price {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 1.5rem 0;
        }

        .package-price-original {
            font-size: 1.2rem;
            color: #9ca3af;
            text-decoration: line-through;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .discount-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .discount-badge.flash-sale {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .package-features {
            list-style: none;
            padding: 0;
            margin: 1.5rem 0;
            flex-grow: 1;
        }

        .package-features li {
            padding: 0.75rem 0;
            color: #6b7280;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.95rem;
        }

        .package-features li:last-child {
            border-bottom: none;
        }

        .package-features li:before {
            content: "✓ ";
            color: var(--secondary);
            font-weight: 700;
            margin-right: 0.5rem;
        }

        .package-guests {
            background: var(--light);
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            text-align: center;
        }

        .package-guests strong {
            color: var(--primary);
        }

        .package-btn {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .package-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(184, 134, 11, 0.3);
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 3rem 0 1rem;
            text-align: center;
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }

            .hero-section p {
                font-size: 1.1rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .packages-section {
                padding: 3rem 0;
            }
        }

        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.25rem;
            }

            .nav-link {
                margin-left: 0;
                padding: 0.5rem 0;
            }

            .btn-login {
                padding: 0.4rem 1rem;
                font-size: 0.9rem;
                margin-top: 0.5rem;
            }

            .hero-section {
                padding: 3rem 1rem;
            }

            .hero-section h1 {
                font-size: 1.75rem;
                margin-bottom: 1rem;
            }

            .hero-section p {
                font-size: 1rem;
                margin-bottom: 1.5rem;
            }

            .btn-primary-custom {
                padding: 0.6rem 1.5rem;
                font-size: 0.9rem;
            }

            .section-title {
                margin-bottom: 2rem;
            }

            .section-title h2 {
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .section-title p {
                font-size: 0.95rem;
            }

            .package-card {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .package-card.featured {
                transform: scale(1);
            }

            .package-icon {
                font-size: 2rem;
                margin-bottom: 0.75rem;
            }

            .package-name {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }

            .package-price {
                font-size: 1.5rem;
                margin: 1rem 0;
            }

            .package-features li {
                padding: 0.5rem 0;
                font-size: 0.9rem;
            }

            .package-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1rem;
            }

            .hero-section {
                padding: 2rem 0.75rem;
            }

            .hero-section h1 {
                font-size: 1.4rem;
                margin-bottom: 0.75rem;
            }

            .hero-section p {
                font-size: 0.95rem;
                margin-bottom: 1.25rem;
            }

            .btn-primary-custom {
                padding: 0.5rem 1.25rem;
                font-size: 0.85rem;
            }

            .section-title h2 {
                font-size: 1.25rem;
            }

            .package-card {
                padding: 1rem;
                border-radius: 8px;
            }

            .package-icon {
                font-size: 1.75rem;
            }

            .package-name {
                font-size: 1.1rem;
            }

            .package-price {
                font-size: 1.25rem;
            }

            .package-desc {
                font-size: 0.85rem;
                margin-bottom: 1rem;
            }

            .package-features {
                margin: 1rem 0;
            }

            .package-features li {
                padding: 0.4rem 0;
                font-size: 0.85rem;
            }

            footer {
                padding: 2rem 1rem 0.5rem;
            }
        }

        .package-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(184, 134, 11, 0.3);
            color: white;
        }

        .package-btn:active {
            transform: translateY(0);
        }

        /* Why Choose Us Section */
        .features-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, rgba(184, 134, 11, 0.05) 0%, rgba(139, 115, 85, 0.05) 100%);
        }

        .feature-item {
            text-align: center;
            padding: 2rem;
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .feature-item h4 {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .feature-item p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        /* Footer */
        .footer {
            background-color: var(--dark);
            color: white;
            padding: 3rem 0 1rem;
            text-align: center;
        }

        .footer p {
            color: #9ca3af;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .package-card.featured {
                transform: scale(1);
            }
        }

        /* Alert styling */
        .alert-info {
            background-color: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #1e40af;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-ring"></i> Gemilang WO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#packages">Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Why Us</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isOwner() ? route('owner.dashboard') : route('customer.dashboard')) }}">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Create Your Perfect Wedding Day</h1>
            <p>Professional wedding planning and organization services tailored to your dreams</p>
            @auth
                <a href="{{ route('customer.packages.index') }}" class="btn btn-primary-custom">
                    <i class="fas fa-calendar-check"></i> Start Planning
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary-custom">
                    <i class="fas fa-sign-in-alt"></i> Login to Book
                </a>
            @endauth
        </div>
    </section>

    <!-- Packages Section -->
    <section class="packages-section" id="packages">
        <div class="container">
            <div class="section-title">
                <h2>Our Wedding Packages</h2>
                <p>Choose the perfect package for your special day</p>
            </div>

            <div class="row g-4">
                @forelse($packages as $package)
                    <div class="col-md-6 col-lg-4">
                        <div class="package-card {{ $loop->iteration == 4 ? 'featured' : '' }}">
                            <div class="package-header">
                                <div class="package-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <h3 class="package-name">{{ $package->name }}</h3>
                                <p class="package-desc">Perfect for your celebration</p>
                            </div>

                            @php
                                $discount = $package->getActiveDiscount();
                                $originalPrice = $package->price;
                                $finalPrice = $discount ? $discount->getDiscountedPrice($originalPrice) : $originalPrice;
                            @endphp

                            @if ($discount)
                                <div class="discount-badge flash-sale">
                                    @if ($discount->type === 'percentage')
                                        <i class="fas fa-fire"></i> {{ $discount->value }}% OFF
                                    @else
                                        <i class="fas fa-fire"></i> Save Rp {{ number_format($discount->value, 0, ',', '.') }}
                                    @endif
                                </div>
                                <div class="package-price-original">
                                    Rp{{ number_format($originalPrice, 0, ',', '.') }}
                                </div>
                            @endif

                            <div class="package-price">
                                Rp{{ number_format($finalPrice, 0, ',', '.') }}
                            </div>

                            <div class="package-guests">
                                <i class="fas fa-users"></i> <strong>Up to {{ $package->max_guests }} Guests</strong>
                            </div>

                            <ul class="package-features">
                                @forelse(json_decode($package->features ?? '[]') as $feature)
                                    <li>{{ $feature }}</li>
                                @empty
                                    <li>Complete wedding planning</li>
                                @endforelse
                            </ul>

                            @auth
                                <a href="{{ route('customer.orders.create', ['package' => $package->id]) }}" class="package-btn">
                                    <i class="fas fa-check-circle"></i> Book Now
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="package-btn">
                                    <i class="fas fa-lock"></i> Login to Book
                                </a>
                            @endauth
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> No packages available at the moment.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose Us</h2>
                <p>Experience excellence in every detail</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h4>Professional Team</h4>
                        <p>Experienced coordinators dedicated to your success</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4>Personal Touch</h4>
                        <p>Customized packages tailored to your preferences</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-check-double"></i>
                        </div>
                        <h4>Reliable Service</h4>
                        <p>100% commitment to making your day memorable</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4>Premium Quality</h4>
                        <p>Top-tier vendors and equipment for your event</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Testimonials Section -->
    <section class="testimonials-section" style="padding: 4rem 0; background: white; border-top: 1px solid var(--border-color);">
        <div class="container">
            <div class="section-title">
                <h2>Our Couples' Stories</h2>
                <p>See what our clients have to say about their experience</p>
            </div>

            @php
                $testimonials = \App\Models\VideoTestimonial::where('is_active', true)->latest()->limit(6)->get();
            @endphp

            @if($testimonials->count() > 0)
                <div class="row g-4">
                    @foreach($testimonials as $testimonial)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm" style="border: none; border-radius: 12px; overflow: hidden;">
                                <!-- Video Thumbnail -->
                                <div class="position-relative" style="height: 220px; background: #f3f4f6; overflow: hidden; cursor: pointer; display: flex; align-items: center; justify-content: center; group" data-glightbox data-gallery="testimonials" 
                                     @if($testimonial->type === 'upload')
                                        href="{{ asset('storage/' . $testimonial->video_path) }}"
                                     @else
                                        href="{{ $testimonial->getEmbedUrl() }}"
                                     @endif>
                                    @if($testimonial->thumbnail_path)
                                        <img src="{{ asset('storage/' . $testimonial->thumbnail_path) }}" 
                                             alt="{{ $testimonial->title }}" 
                                             class="w-100 h-100" 
                                             loading="lazy"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="text-center">
                                            <i class="fas fa-video" style="font-size: 3rem; color: #d1d5db;"></i>
                                        </div>
                                    @endif

                                    <!-- Play Button Overlay -->
                                    <div class="position-absolute inset-0 d-flex align-items-center justify-content-center" 
                                         style="background: rgba(0,0,0,0.3); opacity: 0; transition: opacity 0.3s ease;">
                                        <div class="btn-play" style="width: 60px; height: 60px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                            <i class="fas fa-play" style="font-size: 24px; color: var(--primary); margin-left: 4px;"></i>
                                        </div>
                                    </div>

                                    <!-- Type Badge -->
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-{{ $testimonial->type === 'youtube' ? 'danger' : 'info' }}">
                                        {{ ucfirst($testimonial->type) }}
                                    </span>
                                </div>

                                <div class="card-body">
                                    <!-- Title -->
                                    <h5 class="card-title" style="font-weight: 600; color: var(--dark);">
                                        {{ $testimonial->title }}
                                    </h5>

                                    <!-- Rating -->
                                    @if($testimonial->rating)
                                        <div class="mb-2">
                                            <div class="text-warning">
                                                @for($i = 0; $i < 5; $i++)
                                                    <i class="fas fa-star{{ $i < $testimonial->rating ? '' : ' fa-star-o' }}"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Description -->
                                    <p class="card-text text-muted" style="font-size: 0.95rem; margin-bottom: 1rem;">
                                        {{ Str::limit($testimonial->description, 100) }}
                                    </p>

                                    <!-- Customer Info -->
                                    <div style="display: flex; align-items: center; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                                        @if($testimonial->user->avatar)
                                            <img src="{{ asset('storage/' . $testimonial->user->avatar) }}" 
                                                 alt="{{ $testimonial->user->name }}" 
                                                 class="rounded-circle" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; color: white; font-weight: 600;">
                                                {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="mb-0" style="font-weight: 600; font-size: 0.9rem;">{{ $testimonial->user->name }}</p>
                                            <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                                @if($testimonial->order)
                                                    {{ $testimonial->order->package->name }}
                                                @else
                                                    Verified Couple
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    @auth
                        <a href="{{ route('customer.testimonials.create') }}" class="btn" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                            <i class="fas fa-star"></i> Share Your Story
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white; padding: 0.75rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600;">
                            <i class="fas fa-star"></i> Share Your Story
                        </a>
                    @endauth
                </div>
            @else
                <div class="alert alert-info" role="alert" style="text-align: center;">
                    <i class="fas fa-info-circle"></i> Be the first to share your wedding story!
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Gemilang WO - Professional Wedding Organizer. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- GLightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    
    <script>
        // Initialize GLightbox for video testimonials
        const glightbox = GLightbox({
            selector: '[data-glightbox]',
            autoplayVideos: true,
            videosWidth: 900,
            videoHeight: 506,
        });

        // Hover effect for play button on testimonials
        document.querySelectorAll('.testimonials-section .card').forEach(card => {
            const thumb = card.querySelector('[data-glightbox]');
            if (thumb) {
                card.addEventListener('mouseenter', () => {
                    const overlay = card.querySelector('[style*="background: rgba"]');
                    if (overlay) overlay.style.opacity = '1';
                });
                card.addEventListener('mouseleave', () => {
                    const overlay = card.querySelector('[style*="background: rgba"]');
                    if (overlay) overlay.style.opacity = '0';
                });
            }
        });
    </script>
</body>
</html>
