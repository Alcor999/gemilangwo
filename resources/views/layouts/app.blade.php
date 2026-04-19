<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gemilang WO - Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #b8860b;
            --secondary-color: #8b7355;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --bg-body: #f8fafc;
            --bg-sidebar: #ffffff;
            --danger: #ef4444;
            --success: #22c55e;
            --transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-body);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--text-dark);
        }

        /* Navbar */
        .navbar {
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 0.8rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--text-dark) !important;
            letter-spacing: 0px;
        }

        .navbar-brand i {
            color: var(--primary-color);
            margin-right: 8px;
        }

        .navbar-toggler {
            border: none;
            color: var(--text-dark);
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Sidebar */
        .sidebar {
            background: var(--bg-sidebar);
            border-right: 1px solid rgba(0,0,0,0.05);
            position: fixed;
            left: 0;
            width: 280px;
            top: 61px;
            z-index: 100;
            transition: var(--transition);
            padding: 1.5rem 0 !important;
            height: calc(100vh - 61px);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar .nav {
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .sidebar .nav-pills {
            flex-direction: column !important;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(0,0,0,0.2);
        }

        .sidebar .nav-link {
            color: var(--text-muted);
            transition: var(--transition);
            padding: 0.85rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0.2rem 1rem;
            border-radius: 12px;
            white-space: nowrap;
        }

        .sidebar .nav-link i {
            width: 22px;
            font-size: 1.1rem;
            text-align: center;
            transition: var(--transition);
        }

        .sidebar .nav-link:hover {
            background-color: rgba(184, 134, 11, 0.04);
            color: var(--primary-color);
        }

        .sidebar .nav-link.active {
            background: rgba(184, 134, 11, 0.08);
            color: var(--primary-color);
            font-weight: 600;
        }

        .sidebar hr {
            margin: 1rem 1.5rem;
            border-color: rgba(0,0,0,0.05);
        }

        /* Nav Dropdown User */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-radius: 16px;
            padding: 10px;
            margin-top: 10px;
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 500;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .dropdown-item:hover {
            background-color: rgba(184, 134, 11, 0.05);
            color: var(--primary-color);
        }

        .main-content {
            margin-left: 280px;
            padding: 2.5rem;
            flex-grow: 1;
            min-height: calc(100vh - 61px);
            background: var(--bg-body);
        }

        /* Content wrapper for better integration */
        .d-flex {
            display: flex;
            flex: 1;
        }

        /* Cards Design */
        .card {
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03), 0 2px 4px -1px rgba(0,0,0,0.03);
            border-radius: 16px;
            transition: var(--transition);
            background: #ffffff;
            margin-bottom: 24px;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem;
            font-weight: 600;
            border-radius: 16px 16px 0 0 !important;
        }

        .card-body {
            padding: 1.5rem;
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05), 0 4px 6px -2px rgba(0,0,0,0.02);
            transform: translateY(-2px);
        }

        .table {
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        .table th {
            font-weight: 600;
            color: var(--text-muted);
            border-bottom-width: 1px;
            padding: 12px 16px;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }

        .table-responsive {
            border-radius: 12px;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.04);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(184, 134, 11, 0.05));
            pointer-events: none;
        }

        /* Buttons */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border-radius: 12px;
            transition: var(--transition);
        }

        .btn-primary {
            background: var(--primary-color);
            background: linear-gradient(135deg, var(--primary-color) 0%, #d4a32b 100%);
            border: none;
            color: white;
            box-shadow: 0 4px 10px rgba(184, 134, 11, 0.2);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            box-shadow: 0 6px 15px rgba(184, 134, 11, 0.3);
            transform: translateY(-2px);
        }

        /* Badges */
        .badge {
            padding: 0.5em 1em;
            border-radius: 30px;
            font-weight: 600;
            font-size: 0.75em;
        }

        .badge-status {
            padding: 0.5em 1em;
            border-radius: 30px;
            font-weight: 600;
        }

        .badge-pending {
            background-color: #fef3c7;
            color: #d97706;
        }

        .badge-confirmed {
            background-color: #e0f2fe;
            color: #0284c7;
        }

        .badge-in-progress {
            background-color: #dbeafe;
            color: #2563eb;
        }

        .badge-completed {
            background-color: #dcfce3;
            color: #16a34a;
        }

        .badge-cancelled {
            background-color: #fee2e2;
            color: #dc2626;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .main-content {
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Mobile Responsive */
        @media (max-width: 991px) {
            .sidebar {
                width: 280px;
                left: -280px;
                box-shadow: 10px 0 30px rgba(0,0,0,0.05);
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 1.25rem;
            }

            .sidebar {
                width: 85%;
                left: -85%;
            }

            .card {
                border-radius: 12px;
            }

            .card-header, .card-body {
                padding: 1.25rem;
            }
        }

        /* Alerts Styling */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="d-flex flex-column" style="min-height: 100vh;">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg sticky-top">
            <div class="container-fluid px-lg-4">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <i class="fas fa-ring fs-4"></i> Gemilang WO
                </a>
                
                <div class="d-flex align-items-center">
                    <!-- Mobile trigger visible only on small screens -->
                    <button class="navbar-toggler d-lg-none me-2" type="button" id="sidebarToggleMobile">
                        <i class="fas fa-bars fs-icon"></i>
                    </button>
                    
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-dark fw-medium d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; color: var(--primary-color);">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span>{{ auth()->user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-home text-muted me-2 w-15px"></i> Beranda Area Utama</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-sign-out-alt me-2 w-15px"></i> Keluar
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary text-white px-4" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> Masuk</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <div class="d-flex flex-grow-1 position-relative">
            <!-- Sidebar -->
            @auth
                <nav class="sidebar" id="sidebar">
                    <div class="nav flex-column nav-pills">
                        <!-- User Role Badge -->
                        <div class="px-4 mb-3 d-flex align-items-center">
                            @if(auth()->user()->isAdmin())
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 w-100 py-2"><i class="fas fa-user-shield me-1"></i> Administrator</span>
                            @elseif(auth()->user()->isOwner())
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 w-100 py-2"><i class="fas fa-crown me-1"></i> Owner</span>
                            @else
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 w-100 py-2"><i class="fas fa-user me-1"></i> Klien Prioritas</span>
                            @endif
                        </div>

                        @if(auth()->user()->isAdmin())
                        <div class="px-4 text-xs font-weight-bold text-muted text-uppercase tracking-wider mb-2 mt-2" style="font-size: 0.75rem; letter-spacing: 1px;">Menu Utama</div>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.packages.index' ? 'active' : '' }}" href="{{ route('admin.packages.index') }}">
                            <i class="fas fa-box"></i> Kelola Paket
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.discounts.index' ? 'active' : '' }}" href="{{ route('admin.discounts.index') }}">
                            <i class="fas fa-tag"></i> Diskon & Promo
                        </a>
                        <a class="nav-link {{ str_contains(Route::currentRouteName() ?? '', 'admin.vendor') ? 'active' : '' }}" href="{{ route('admin.vendor-categories.index') }}">
                            <i class="fas fa-store"></i> Vendor Partner
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.orders.index' ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-clipboard-list"></i> Pesanan Klien
                        </a>
                        
                        <div class="px-4 text-xs font-weight-bold text-muted text-uppercase tracking-wider mb-2 mt-4" style="font-size: 0.75rem; letter-spacing: 1px;">Sistem & Media</div>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.users.index' ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users"></i> Kelola Pengguna
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.reviews.index' ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                            <i class="fas fa-star text-warning"></i> Kelola Ulasan
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName() ?? '', 'admin.testimonials') !== false ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">
                            <i class="fas fa-comment-dots"></i> Video Testimoni
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName() ?? '', 'admin.videos') !== false ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">
                            <i class="fas fa-video"></i> Galeri Video
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName() ?? '', 'admin.support') !== false ? 'active' : '' }}" href="{{ route('admin.support.tickets.index') }}">
                            <i class="fas fa-headset"></i> Tiket Dukungan
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName() ?? '', 'admin.analytics') !== false ? 'active' : '' }}" href="{{ route('admin.analytics.dashboard') }}">
                            <i class="fas fa-chart-pie"></i> Analitik Data
                        </a>
                        
                        <div class="px-4 text-xs font-weight-bold text-muted text-uppercase tracking-wider mb-2 mt-4" style="font-size: 0.75rem; letter-spacing: 1px;">Testing Tools</div>
                        <a class="nav-link" href="{{ route('email-test.order-confirmation', 1) }}" target="_blank">
                            <i class="fas fa-envelope text-info"></i> Uji Email
                        </a>
                        <a class="nav-link" href="{{ route('sms-test.order-confirmation', 1) }}" target="_blank">
                            <i class="fas fa-sms text-success"></i> Uji SMS
                        </a>

                    @elseif(auth()->user()->isOwner())
                        <div class="px-4 text-xs font-weight-bold text-muted text-uppercase tracking-wider mb-2 mt-2" style="font-size: 0.75rem; letter-spacing: 1px;">Menu Laporan</div>
                        <a class="nav-link {{ Route::currentRouteName() == 'owner.dashboard' ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                            <i class="fas fa-chart-line"></i> Ringkasan Utama
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName() ?? '', 'owner.analytics') !== false ? 'active' : '' }}" href="{{ route('owner.analytics.dashboard') }}">
                            <i class="fas fa-chart-pie text-primary"></i> Laporan Analitik
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'owner.statistics' ? 'active' : '' }}" href="{{ route('owner.statistics') }}">
                            <i class="fas fa-bar-chart"></i> Statistik Bisnis
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'owner.payments' ? 'active' : '' }}" href="{{ route('owner.payments') }}">
                            <i class="fas fa-credit-card text-success"></i> Riwayat Pembayaran
                        </a>
                        <div class="px-4 text-xs font-weight-bold text-muted text-uppercase tracking-wider mb-2 mt-4" style="font-size: 0.75rem; letter-spacing: 1px;">Operasional</div>
                        <a class="nav-link {{ strpos(Route::currentRouteName() ?? '', 'owner.calendar') !== false ? 'active' : '' }}" href="{{ route('owner.calendar.index') }}">
                            <i class="fas fa-calendar-alt"></i> Kalender Pemesanan
                        </a>
                        
                    @else
                        <div class="px-4 text-xs font-weight-bold text-muted text-uppercase tracking-wider mb-2 mt-2" style="font-size: 0.75rem; letter-spacing: 1px;">Eksplorasi</div>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.dashboard' ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                            <i class="fas fa-home"></i> Dasbor Saya
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.packages.index' ? 'active' : '' }}" href="{{ route('customer.packages.index') }}">
                            <i class="fas fa-gift text-primary"></i> Pilihan Paket
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.orders.index' ? 'active' : '' }}" href="{{ route('customer.orders.index') }}">
                            <i class="fas fa-shopping-bag"></i> Pesanan Saya
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName() ?? '', 'customer.calendar') !== false ? 'active' : '' }}" href="{{ route('customer.calendar.confirmation') }}">
                            <i class="fas fa-calendar-check text-success"></i> Kalender Acara
                        </a>

                        <div class="px-4 text-xs font-weight-bold text-muted text-uppercase tracking-wider mb-2 mt-4" style="font-size: 0.75rem; letter-spacing: 1px;">Personal</div>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.profile.show' ? 'active' : '' }}" href="{{ route('customer.profile.show') }}">
                            <i class="fas fa-user-circle"></i> Profil & Pengaturan
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.wishlist.index' ? 'active' : '' }}" href="{{ route('customer.wishlist.index') }}">
                            <i class="fas fa-heart text-danger"></i> Daftar Keinginan
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.reviews.index' ? 'active' : '' }}" href="{{ route('customer.reviews.index') }}">
                            <i class="fas fa-star text-warning"></i> Ulasan Layanan
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName() ?? '', 'customer.testimonials') !== false ? 'active' : '' }}" href="{{ route('customer.testimonials.index') }}">
                            <i class="fas fa-video"></i> Video Testimoni
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName() ?? '', 'customer.support.tickets') !== false ? 'active' : '' }}" href="{{ route('customer.support.tickets.index') }}">
                            <i class="fas fa-headset text-info"></i> Pusat Bantuan
                        </a>
                    @endif
                    </div>
                </nav>
            @endauth

            <!-- Main Content -->
            <main class="main-content w-100">
                <div class="container-fluid p-0">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                            <i class="fas fa-exclamation-circle fs-4 me-3 text-danger"></i> 
                            <div>
                                <h6 class="mb-1 fw-bold">Terdapat Kesalahan!</h6>
                                <ul class="mb-0 ps-3 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert" style="background:#f0fdf4; border-color:#bbf7d0;">
                            <i class="fas fa-check-circle fs-4 me-3 text-success"></i> 
                            <div class="fw-medium text-success">{{ session('success') }}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert" style="background:#fef2f2; border-color:#fecaca;">
                            <i class="fas fa-exclamation-triangle fs-4 me-3 text-danger"></i> 
                            <div class="fw-medium text-danger">{{ session('error') }}</div>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
            
            <!-- Mobile Overlay Background -->
            <div id="sidebarOverlay" class="d-lg-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50" style="z-index: 99; display: none; transition: opacity 0.3s ease;"></div>
        </div>
    </div>

    @include('components.toast-container')
    @include('components.modal-confirm')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app-ui.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleMobile = document.getElementById('sidebarToggleMobile');
            const overlay = document.getElementById('sidebarOverlay');
            
            function toggleSidebar() {
                sidebar?.classList.toggle('show');
                if (sidebar?.classList.contains('show')) {
                    overlay.style.display = 'block';
                    // small delay for transition
                    setTimeout(() => overlay.style.opacity = '1', 10);
                } else {
                    overlay.style.opacity = '0';
                    setTimeout(() => overlay.style.display = 'none', 300);
                }
            }
            
            toggleMobile?.addEventListener('click', toggleSidebar);
            overlay?.addEventListener('click', toggleSidebar);

            // Close sidebar when link is clicked on mobile
            if (window.innerWidth <= 991) {
                document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                    link.addEventListener('click', function() {
                        sidebar?.classList.remove('show');
                        overlay.style.opacity = '0';
                        setTimeout(() => overlay.style.display = 'none', 300);
                    });
                });
            }
        });
    </script>
    @yield('js')
</body>
</html>
