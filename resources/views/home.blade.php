<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Penyelenggara Pernikahan - Layanan Perencanaan Acara Profesional</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- GLightbox -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600&family=Great+Vibes&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #b8860b;
            --secondary: #8b7355;
            --dark: #2A1F18;
            --light: #FCF9F2;
            --white: #ffffff;
            --border-color: rgba(184, 134, 11, 0.2);
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        h1, h2, h3, h4, h5, h6, .brand-logo {
            font-family: 'Playfair Display', serif;
        }

        .accent-font {
            font-family: 'Great Vibes', cursive;
            color: var(--primary);
            font-size: 2.5rem;
            margin-bottom: -10px;
            display: block;
        }

        body {
            background-color: var(--light);
            color: var(--dark);
            overflow-x: hidden;
        }

        /* Glassmorphism Navbar */
        .navbar {
            background: rgba(252, 249, 242, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(184, 134, 11, 0.15);
            padding: 1rem 0;
            transition: var(--transition);
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--dark) !important;
            letter-spacing: 0.5px;
        }

        .navbar-brand i {
            color: var(--primary);
        }

        .nav-link {
            font-weight: 500;
            margin-left: 1.5rem;
            color: var(--dark) !important;
            transition: var(--transition);
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .btn-login {
            background: transparent;
            color: var(--primary);
            font-weight: 600;
            padding: 0.5rem 1.8rem;
            border-radius: 30px;
            transition: var(--transition);
            border: 1px solid var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
        }

        .btn-login:hover {
            background: var(--primary);
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(184, 134, 11, 0.2);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(42, 31, 24, 0.4), rgba(42, 31, 24, 0.7)), url('https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center/cover;
            color: white;
            padding: 10rem 0 8rem;
            text-align: center;
            position: relative;
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .hero-section h1 {
            font-size: 4.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            animation: elegantFadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 3rem;
            font-weight: 300;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0;
            animation: elegantFadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
        }

        .hero-accent {
            color: var(--white);
            animation: elegantFadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .btn-primary-custom {
            background: var(--primary);
            color: white;
            padding: 1rem 2.5rem;
            font-weight: 500;
            border-radius: 30px;
            border: none;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            animation: elegantFadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) 0.4s forwards;
            text-decoration: none;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(184, 134, 11, 0.3);
            background: #cd9a15;
            color: white;
        }

        @keyframes elegantFadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Packages Section */
        .packages-section {
            padding: 7rem 0;
            background-color: var(--light);
            position: relative;
        }

        .section-title {
            text-align: center;
            margin-bottom: 5rem;
        }

        .section-title h2 {
            font-size: 3rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.1rem;
            color: var(--secondary);
            font-weight: 300;
        }

        /* Package Card */
        .package-card {
            background: white;
            border: 1px solid rgba(184, 134, 11, 0.1);
            border-radius: 24px;
            padding: 3rem 2rem;
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        }

        .package-card:hover {
            transform: translateY(-15px);
            border-color: var(--primary);
            box-shadow: 0 30px 60px rgba(184, 134, 11, 0.1);
        }

        .package-card.featured {
            border-color: var(--primary);
            background: linear-gradient(to bottom, #ffffff, #FCF9F2);
            transform: scale(1.03);
            box-shadow: 0 20px 50px rgba(184, 134, 11, 0.15);
        }

        .package-card.featured:hover {
            transform: scale(1.03) translateY(-10px);
        }

        .featured-badge {
            position: absolute;
            top: 20px;
            right: -35px;
            background: var(--primary);
            color: white;
            padding: 0.5rem 3rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            transform: rotate(45deg);
            z-index: 10;
        }

        .package-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
            opacity: 0.8;
            stroke-width: 1px;
        }

        .package-name {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
        }

        .package-desc {
            color: var(--secondary);
            font-size: 0.95rem;
            margin-bottom: 2rem;
            font-weight: 300;
        }

        .package-price {
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--primary);
            margin: 1.5rem 0;
            font-family: 'Playfair Display', serif;
        }

        .package-price-original {
            font-size: 1.2rem;
            color: #a0aec0;
            text-decoration: line-through;
            margin-bottom: 0.5rem;
            font-weight: 400;
        }

        .discount-badge {
            display: inline-block;
            background: rgba(184, 134, 11, 0.1);
            color: var(--primary);
            padding: 0.4rem 1rem;
            border-radius: 30px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
            border: 1px solid rgba(184, 134, 11, 0.2);
        }

        .package-features {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
            flex-grow: 1;
        }

        .package-features li {
            padding: 0.85rem 0;
            color: #4a5568;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .package-features li i {
            color: var(--primary);
            font-size: 0.8rem;
        }

        .package-guests {
            background: rgba(184, 134, 11, 0.05);
            padding: 1rem;
            border-radius: 12px;
            margin: 1.5rem 0;
            text-align: center;
            border: 1px dashed rgba(184, 134, 11, 0.3);
        }

        .package-btn {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
            padding: 1rem 2rem;
            border-radius: 30px;
            font-weight: 600;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            transition: var(--transition);
            text-decoration: none;
            display: block;
        }

        .package-btn:hover {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 20px rgba(184, 134, 11, 0.2);
            text-decoration: none;
        }

        .featured .package-btn {
            background: var(--primary);
            color: white;
        }
        .featured .package-btn:hover {
            background: var(--secondary);
            border-color: var(--secondary);
        }

        /* Why Choose Us Section */
        .features-section {
            padding: 7rem 0;
            background: white;
            position: relative;
        }

        .feature-item {
            text-align: center;
            padding: 3rem 2rem;
            border-radius: 20px;
            background: var(--light);
            transition: var(--transition);
            height: 100%;
        }

        .feature-item:hover {
            transform: translateY(-10px);
            background: white;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2rem;
            color: var(--primary);
            box-shadow: 0 10px 20px rgba(184, 134, 11, 0.1);
        }

        .feature-item h4 {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1rem;
            font-size: 1.25rem;
        }

        .feature-item p {
            color: var(--secondary);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
            font-weight: 300;
        }

        /* Testimonials Section */
        .testimonials-section {
            padding: 7rem 0;
            background: var(--light);
            border-top: 1px solid rgba(184, 134, 11, 0.1);
        }

        .testimonial-card {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.04);
            border: 1px solid rgba(0,0,0,0.03);
            transition: var(--transition);
            height: 100%;
        }

        .testimonial-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(184, 134, 11, 0.1);
        }

        .video-thumbnail-wrapper {
            position: relative;
            height: 240px;
            overflow: hidden;
            background: #e2e8f0;
        }

        .video-thumbnail-wrapper img {
            transition: transform 0.7s ease;
        }

        .testimonial-card:hover .video-thumbnail-wrapper img {
            transform: scale(1.05);
        }

        .play-btn-overlay {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transition: var(--transition);
            color: var(--primary);
            font-size: 24px;
            padding-left: 6px;
        }

        .testimonial-card:hover .play-btn-overlay {
            background: var(--primary);
            color: white;
            transform: scale(1.1);
        }

        .testimonial-body {
            padding: 2.5rem;
        }

        .testimonial-user-info img {
            border: 2px solid var(--primary);
            padding: 2px;
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: rgba(255,255,255,0.7);
            padding: 5rem 0 2rem;
            position: relative;
        }

        .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: white;
            margin-bottom: 1.5rem;
            display: block;
        }
        
        .footer-brand i {
            color: var(--primary);
        }

        .footer-block h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-block h5::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--primary);
        }

        .footer-block ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-block ul li {
            margin-bottom: 0.8rem;
        }

        .footer-block ul li a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-block ul li a:hover {
            color: var(--primary);
            padding-left: 5px;
        }

        .footer-bottom {
            margin-top: 4rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .hero-section h1 { font-size: 3.5rem; }
            .section-title h2 { font-size: 2.5rem; }
            .packages-section { padding: 5rem 0; }
        }

        @media (max-width: 768px) {
            .hero-section h1 { font-size: 2.5rem; }
            .hero-section p { font-size: 1rem; }
            .section-title h2 { font-size: 2rem; }
            .package-card.featured { transform: scale(1); }
            .navbar { background: rgba(255, 255, 255, 0.98); }
            .nav-link { margin-left: 0; padding: 0.8rem 0; }
            .hero-section { padding: 8rem 0 5rem; min-height: 70vh; }
        }

        /* Utilities */
        .glass-panel {
            background: rgba(252, 249, 242, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-ring"></i> Gemilang <span style="font-weight: 300;">WO</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="border:none; box-shadow:none;">
                <i class="fas fa-bars" style="color: var(--dark); font-size: 1.5rem;"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#packages">Paket</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Mengapa Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimoni</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link btn-login ms-lg-3 px-4" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isOwner() ? route('owner.dashboard') : route('customer.dashboard')) }}">
                                Dasbor
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link shadow-none" style="background: none; border: none; cursor: pointer; color: #e53e3e !important;">Keluar</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                            <a href="{{ route('login') }}" class="btn-login" style="text-decoration:none; display:inline-block;">Masuk / Daftar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container relative" style="z-index: 2;">
            <span class="accent-font hero-accent">Magical moments</span>
            <h1>Wujudkan Pernikahan<br>Impian Anda</h1>
            <p>Layanan perencanaan dan pengelolaan pernikahan premium. Karena setiap kisah cinta layak dirayakan dengan sempurna.</p>
            @auth
                <a href="{{ route('customer.packages.index') }}" class="btn-primary-custom">
                    Mulai Rencanakan <i class="fas fa-arrow-right"></i>
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-primary-custom">
                    Pesan Sekarang <i class="fas fa-arrow-right"></i>
                </a>
            @endauth
        </div>
    </section>

    <!-- Features Section (Moved up for better flow) -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-title">
                <span class="accent-font">Why choose us</span>
                <h2>Layanan Bintang Lima</h2>
                <p>Kami mendedikasikan perhatian penuh untuk hari paling berharga Anda.</p>
            </div>

            <div class="row g-5">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h4>Kualitas Premium</h4>
                        <p>Vendor eksklusif dan perlengkapan berkelas untuk menjamin acara spektakuler.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h4>Sentuhan Personal</h4>
                        <p>Desain acara yang sangat personal menyesuaikan dengan selera estetika Anda.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4>Tim Ahli</h4>
                        <p>Koreografer acara dan koordinator lapangan profesional dengan jam terbang tinggi.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Layanan Andal</h4>
                        <p>Bebas cemas. Kami memastikan segalanya berjalan lancar tanpa celah satu pun.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Packages Section -->
    <section class="packages-section" id="packages">
        <div class="container">
            <div class="section-title">
                <span class="accent-font">Our collections</span>
                <h2>Pilihan Paket Eksklusif</h2>
                <p>Temukan paket yang didesain khusus untuk merayakan cinta Anda</p>
            </div>

            <div class="row g-5 justify-content-center">
                @forelse($packages as $package)
                    <div class="col-md-6 col-lg-4">
                        <div class="package-card {{ $loop->iteration == 2 ? 'featured' : '' }}">
                            @if($loop->iteration == 2)
                            <div class="featured-badge">Terpopuler</div>
                            @endif
                            <div class="package-header text-center">
                                <div class="package-icon">
                                    <i class="fas fa-gem"></i>
                                </div>
                                <h3 class="package-name">{{ $package->name }}</h3>
                                <p class="package-desc">Pilihan tepat untuk merayakan hari bahagia.</p>
                            </div>

                            @php
                                $discount = $package->getActiveDiscount();
                                $originalPrice = $package->price;
                                $finalPrice = $discount ? $discount->getDiscountedPrice($originalPrice) : $originalPrice;
                            @endphp

                            <div class="text-center">
                                @if ($discount)
                                    <div class="discount-badge text-center">
                                        @if ($discount->type === 'percentage')
                                            <i class="fas fa-star"></i> Spesial Diskon {{ $discount->value }}%
                                        @else
                                            <i class="fas fa-star"></i> Promo Hemat
                                        @endif
                                    </div>
                                    <div class="package-price-original">
                                        Rp{{ number_format($originalPrice, 0, ',', '.') }}
                                    </div>
                                @endif

                                <div class="package-price">
                                    Rp{{ number_format($finalPrice, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="package-guests">
                                <i class="fas fa-users" style="color: var(--primary);"></i> Kapasitas: <strong>Hingga {{ $package->max_guests }} Tamu</strong>
                            </div>

                            <ul class="package-features">
                                @forelse(json_decode($package->features ?? '[]') as $feature)
                                    <li><i class="fas fa-check"></i> {{ $feature }}</li>
                                @empty
                                    <li><i class="fas fa-check"></i> Full Wedding Planning</li>
                                    <li><i class="fas fa-check"></i> Premium Venue Selection</li>
                                    <li><i class="fas fa-check"></i> Catering 5 Star</li>
                                @endforelse
                            </ul>

                            <div class="mt-auto pt-4">
                                @auth
                                    <a href="{{ route('customer.orders.create', ['package' => $package->id]) }}" class="package-btn">
                                        Pesan Sekarang
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="package-btn">
                                        Masuk Akun
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-gem" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                            <h4 style="color: var(--secondary);">Koleksi paket sedang disiapkan.</h4>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Video Testimonials Section -->
    <section class="testimonials-section" id="testimonials">
        <div class="container">
            <div class="section-title">
                <span class="accent-font">Love stories</span>
                <h2>Cerita Indah Mereka</h2>
                <p>Bagian kecil dari banyak kebahagiaan yang telah kami ciptakan</p>
            </div>

            @php
                $testimonials = \App\Models\VideoTestimonial::where('is_active', true)->latest()->limit(6)->get();
            @endphp

            @if($testimonials->count() > 0)
                <div class="row g-5">
                    @foreach($testimonials as $testimonial)
                        <div class="col-md-6 col-lg-4">
                            <div class="testimonial-card">
                                <!-- Gambar Miniatur Video -->
                                <div class="video-thumbnail-wrapper" data-glightbox data-gallery="testimonials" 
                                     @if($testimonial->type === 'upload')
                                        href="{{ asset('storage/' . $testimonial->video_path) }}"
                                     @else
                                        href="{{ $testimonial->getEmbedUrl() }}"
                                     @endif>
                                    @if($testimonial->thumbnail_path)
                                        <img src="{{ asset('storage/' . $testimonial->thumbnail_path) }}" 
                                             alt="{{ $testimonial->title }}" 
                                             class="w-100 h-100 object-fit-cover" 
                                             loading="lazy">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                            <i class="fas fa-film text-muted stroke-1" style="font-size: 4rem; opacity:0.2;"></i>
                                        </div>
                                    @endif

                                    <!-- Play Button Overlay -->
                                    <div class="position-absolute inset-0 d-flex align-items-center justify-content-center pointer-events-none" style="top:0;left:0;right:0;bottom:0;">
                                        <div class="play-btn-overlay">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="testimonial-body">
                                    <!-- Title -->
                                    <h5 style="font-weight: 600; color: var(--dark); font-family: 'Playfair Display', serif; font-size: 1.3rem; margin-bottom: 10px;">
                                        {{ $testimonial->title }}
                                    </h5>

                                    <!-- Rating -->
                                    @if($testimonial->rating)
                                        <div class="mb-3">
                                            <div style="color: var(--primary); font-size: 0.9rem;">
                                                @for($i = 0; $i < 5; $i++)
                                                    <i class="fa{{ $i < $testimonial->rating ? 's' : 'r' }} fa-star"></i>
                                                @endfor
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Description -->
                                    <p class="text-muted" style="font-size: 0.95rem; margin-bottom: 2rem; font-weight: 300; line-height: 1.6;">
                                        "{{ Str::limit($testimonial->description, 120) }}"
                                    </p>

                                    <!-- Info Pelanggan -->
                                    <div class="d-flex align-items-center testimonial-user-info" style="gap: 1rem; padding-top: 1.5rem; border-top: 1px solid rgba(0,0,0,0.05);">
                                        @if($testimonial->user->avatar)
                                            <img src="{{ asset('storage/' . $testimonial->user->avatar) }}" 
                                                 alt="{{ $testimonial->user->name }}" 
                                                 class="rounded-circle" 
                                                 style="width: 45px; height: 45px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 45px; height: 45px; background: rgba(184, 134, 11, 0.1); color: var(--primary); font-weight: 600; font-size: 1.1rem;">
                                                {{ strtoupper(substr($testimonial->user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-1" style="font-weight: 600; font-size: 0.95rem; color: var(--dark);">{{ $testimonial->user->name }}</h6>
                                            <span class="text-muted" style="font-size: 0.8rem;">
                                                @if($testimonial->order)
                                                    {{ $testimonial->order->package->name }}
                                                @else
                                                    Klien Gemilang WO
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-5 pt-4">
                    @auth
                        <a href="{{ route('customer.testimonials.create') }}" class="btn-primary-custom" style="padding: 0.8rem 2rem;">
                            Bagikan Cerita Anda <i class="fas fa-heart"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary-custom" style="padding: 0.8rem 2rem;">
                            Bagikan Cerita Anda <i class="fas fa-heart"></i>
                        </a>
                    @endauth
                </div>
            @else
                <div class="text-center py-5">
                    <span class="accent-font" style="font-size: 2rem; color: #a0aec0;">Jadilah yang pertama</span>
                    <p class="mt-3 text-muted">Bagikan kisah manis Anda bersama pengalaman layanan kami.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <a href="#" class="footer-brand" style="text-decoration:none;">
                        <i class="fas fa-ring"></i> Gemilang WO
                    </a>
                    <p style="color: rgba(255,255,255,0.7); font-size:0.95rem; line-height: 1.6; margin-bottom: 2rem;">
                        Penyedia layanan penyelenggara pernikahan mewah dan profesional untuk mewujudkan momen sakral sekali seumur hidup Anda dengan sempurna.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" style="color: white; background: rgba(255,255,255,0.1); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration:none;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: white; background: rgba(255,255,255,0.1); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration:none;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="color: white; background: rgba(255,255,255,0.1); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.3s; text-decoration:none;" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-4 footer-block">
                    <h5>Tautan Cepat</h5>
                    <ul>
                        <li><a href="#packages">Koleksi Paket</a></li>
                        <li><a href="#features">Mengapa Kami</a></li>
                        <li><a href="#testimonials">Testimoni</a></li>
                        <li><a href="#">Galeri</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 footer-block">
                    <h5>Dukungan</h5>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4 py-4 py-lg-0 footer-block">
                    <h5>Kontak Kami</h5>
                    <ul style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">
                        <li class="d-flex gap-3 mb-3"><i class="fas fa-map-marker-alt" style="color: var(--primary); margin-top:5px;"></i> <span>Jl. Kemang Raya No. 12, Jakarta Selatan, 12730</span></li>
                        <li class="d-flex gap-3 mb-3"><i class="fas fa-phone" style="color: var(--primary); margin-top:5px;"></i> <span>+62 812 3456 7890</span></li>
                        <li class="d-flex gap-3"><i class="fas fa-envelope" style="color: var(--primary); margin-top:5px;"></i> <span>hello@gemilangwo.com</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} Gemilang Wedding Organizer. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- GLightbox JS -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    
    <script>
        // Navbar Scrolled Effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.getElementById('mainNav').classList.add('scrolled');
            } else {
                document.getElementById('mainNav').classList.remove('scrolled');
            }
        });

        // Initialize GLightbox
        const glightbox = GLightbox({
            selector: '[data-glightbox]',
            autoplayVideos: true,
            videosWidth: 900,
        });
    </script>
</body>
</html>
