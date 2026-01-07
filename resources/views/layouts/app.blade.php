<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'Gemilang WO - Wedding Organizer')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #8b5cf6;
            --secondary-color: #ec4899;
        }
        
        * {
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }

        body {
            background-color: #f5f7fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
        }

        .navbar-toggler {
            border: none;
        }

        .navbar-toggler:focus {
            box-shadow: none;
            border: none;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(180deg, #ffffff 0%, #fafbfc 100%);
            border-right: 2px solid #e0e6ed;
            position: fixed;
            left: 0;
            width: 260px;
            top: 60px;
            z-index: 100;
            transition: left 0.3s ease;
            padding: 0 !important;
            max-height: calc(100vh - 60px);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar .nav {
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            padding: 1rem 0 !important;
            overflow: visible !important;
        }

        .sidebar .nav-pills {
            flex-direction: column !important;
        }

        .sidebar .nav-pills .nav-link {
            border-radius: 0 !important;
        }

        .sidebar .nav::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar .nav::-webkit-scrollbar-track {
            background: #f5f7fa;
            border-radius: 10px;
        }

        .sidebar .nav::-webkit-scrollbar-thumb {
            background: #999;
            border-radius: 4px;
        }

        .sidebar .nav::-webkit-scrollbar-thumb:hover {
            background: #666;
        }

        .sidebar .nav-link {
            color: #5a6c7d;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
            padding: 0.85rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 0.25rem 0.5rem;
            border-radius: 0 8px 8px 0;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .sidebar .nav-link i {
            width: 20px;
            min-width: 20px;
            text-align: center;
            opacity: 0.8;
            flex-shrink: 0;
        }

        .sidebar .nav-link:hover {
            background-color: #f0f3f7;
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            padding-left: 1.65rem;
        }

        .sidebar .nav-link.active {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(236, 72, 153, 0.05) 100%);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
            padding-left: 1.65rem;
        }

        .sidebar hr {
            margin: 0.75rem 1rem;
            border-color: #e0e6ed;
            opacity: 0.6;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
            margin-top: 0;
            flex-grow: 1;
            min-height: calc(100vh - 60px);
        }

        /* Content wrapper for better integration */
        .d-flex {
            display: flex;
            flex: 1;
        }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 8px;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .table {
            font-size: 0.95rem;
        }

        .table-responsive {
            border-radius: 8px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #7c3aed 0%, #db2777 100%);
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }

        .badge-confirmed {
            background-color: #17a2b8;
            color: white;
        }

        .badge-in-progress {
            background-color: #007bff;
            color: white;
        }

        .badge-completed {
            background-color: #28a745;
            color: white;
        }

        .badge-cancelled {
            background-color: #dc3545;
            color: white;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70%;
                left: -70%;
                max-height: calc(100vh - 60px);
                height: calc(100vh - 60px);
                position: fixed;
                top: 60px;
                border-right: 2px solid #e0e6ed;
                border-bottom: none;
                box-shadow: 2px 0 8px rgba(0,0,0,0.1);
                overflow-y: auto;
                overflow-x: hidden;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
                margin-top: 0;
                width: 100%;
            }

            .navbar-brand {
                font-size: 1.1rem;
            }

            .card {
                margin-bottom: 1rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .stat-card {
                padding: 15px;
                margin-bottom: 15px;
            }

            .btn {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            h2 {
                font-size: 1.25rem;
            }

            .sidebar .nav-link {
                padding: 0.9rem 1.5rem;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1rem;
            }

            .main-content {
                padding: 15px;
                margin-top: 0;
            }

            .sidebar {
                width: 80%;
            }

            .card {
                margin-bottom: 0.75rem;
            }

            .table {
                font-size: 0.8rem;
            }

            .badge {
                font-size: 0.75rem;
            }

            .btn-sm {
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }

            h1 {
                font-size: 1.25rem;
            }

            h2 {
                font-size: 1rem;
            }

            .col-md-6 {
                margin-bottom: 1rem;
            }

            .sidebar .nav-link {
                padding: 0.8rem 1.5rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex flex-column" style="min-height: 100vh;">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-ring"></i> Gemilang WO
                </a>
                <button class="navbar-toggler" type="button" id="sidebarToggle" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i> {{ auth()->user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('home') }}">Home</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> Login</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <div class="d-flex flex-grow-1">
            <!-- Sidebar -->
            @auth
                <nav class="sidebar" id="sidebar">
                    <div class="nav flex-column nav-pills">
                        @if(auth()->user()->isAdmin())
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.packages.index' ? 'active' : '' }}" href="{{ route('admin.packages.index') }}">
                            <i class="fas fa-box"></i> Manage Packages
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.discounts.index' ? 'active' : '' }}" href="{{ route('admin.discounts.index') }}">
                            <i class="fas fa-tag"></i> Discounts & Promos
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.reviews.index' ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                            <i class="fas fa-star"></i> Reviews
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.orders.index' ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                            <i class="fas fa-clipboard-list"></i> Orders
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'admin.users.index' ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users"></i> Users
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName(), 'admin.support') !== false ? 'active' : '' }}" href="{{ route('admin.support.tickets.index') }}">
                            <i class="fas fa-headset"></i> Support Tickets
                        </a>
                        
                        <!-- Divider -->
                        <hr style="margin: 0.5rem 0; border-color: #e9ecef;">
                        
                        <!-- Media & Content -->
                        <a class="nav-link {{ strpos(Route::currentRouteName(), 'admin.videos') !== false ? 'active' : '' }}" href="{{ route('admin.videos.index') }}">
                            <i class="fas fa-video"></i> Video Gallery
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName(), 'admin.testimonials') !== false ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">
                            <i class="fas fa-star"></i> Testimonial Approvals
                        </a>
                        
                        <!-- Analytics -->
                        <a class="nav-link {{ strpos(Route::currentRouteName(), 'admin.analytics') !== false ? 'active' : '' }}" href="{{ route('admin.analytics.dashboard') }}">
                            <i class="fas fa-chart-pie"></i> Analytics
                        </a>
                        
                        <!-- Divider -->
                        <hr style="margin: 0.5rem 0; border-color: #e9ecef;">
                        
                        <!-- Testing Routes -->
                        <a class="nav-link" href="{{ route('email-test.order-confirmation', 1) }}" target="_blank">
                            <i class="fas fa-envelope"></i> Email Test
                        </a>
                        <a class="nav-link" href="{{ route('sms-test.order-confirmation', 1) }}" target="_blank">
                            <i class="fas fa-sms"></i> SMS Test
                        </a>
                    @elseif(auth()->user()->isOwner())
                        <a class="nav-link {{ Route::currentRouteName() == 'owner.dashboard' ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName(), 'owner.analytics') !== false ? 'active' : '' }}" href="{{ route('owner.analytics.dashboard') }}">
                            <i class="fas fa-chart-pie"></i> Analytics
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'owner.statistics' ? 'active' : '' }}" href="{{ route('owner.statistics') }}">
                            <i class="fas fa-bar-chart"></i> Statistics
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'owner.payments' ? 'active' : '' }}" href="{{ route('owner.payments') }}">
                            <i class="fas fa-credit-card"></i> Payments
                        </a>
                        
                        <!-- Divider -->
                        <hr style="margin: 0.5rem 0; border-color: #e9ecef;">
                        
                        <!-- Calendar Management -->
                        <a class="nav-link {{ strpos(Route::currentRouteName(), 'owner.calendar') !== false ? 'active' : '' }}" href="{{ route('owner.calendar.index') }}">
                            <i class="fas fa-calendar-alt"></i> Calendar & Booking
                        </a>
                    @else
                        <!-- Main Menu -->
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.dashboard' ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.packages.index' ? 'active' : '' }}" href="{{ route('customer.packages.index') }}">
                            <i class="fas fa-gift"></i> Browse Packages
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.orders.index' ? 'active' : '' }}" href="{{ route('customer.orders.index') }}">
                            <i class="fas fa-shopping-cart"></i> My Orders
                        </a>

                        <!-- Divider -->
                        <hr style="margin: 0.5rem 0; border-color: #e9ecef;">

                        <!-- Customer Features -->
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.profile.show' ? 'active' : '' }}" href="{{ route('customer.profile.show') }}">
                            <i class="fas fa-user-circle"></i> My Profile
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.wishlist.index' ? 'active' : '' }}" href="{{ route('customer.wishlist.index') }}">
                            <i class="fas fa-heart"></i> Wishlist
                        </a>
                        <a class="nav-link {{ Route::currentRouteName() == 'customer.reviews.index' ? 'active' : '' }}" href="{{ route('customer.reviews.index') }}">
                            <i class="fas fa-star"></i> My Reviews
                        </a>
                        <a class="nav-link {{ strpos(Route::currentRouteName(), 'customer.testimonials') !== false ? 'active' : '' }}" href="{{ route('customer.testimonials.index') }}">
                            <i class="fas fa-video"></i> My Testimonials
                        </a>
                        
                        <!-- Divider -->
                        <hr style="margin: 0.5rem 0; border-color: #e9ecef;">
                        
                        <!-- Calendar & Events -->
                        <a class="nav-link {{ strpos(Route::currentRouteName(), 'customer.calendar') !== false ? 'active' : '' }}" href="{{ route('customer.calendar.confirmation') }}">
                            <i class="fas fa-calendar-check"></i> My Events
                        </a>
                        
                        <!-- Support & Communication -->
                        <a class="nav-link {{ strpos(Route::currentRouteName(), 'customer.support.tickets') !== false ? 'active' : '' }}" href="{{ route('customer.support.tickets.index') }}">
                            <i class="fas fa-headset"></i> Support Tickets
                        </a>
                    @endif
                    </div>
                </nav>
            @endauth

        <!-- Main Content -->
        <main class="main-content flex-grow-1 w-100">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <strong>Error!</strong>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar?.classList.toggle('show');
        });

        // Close sidebar when a link is clicked on mobile
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    const sidebar = document.getElementById('sidebar');
                    sidebar?.classList.remove('show');
                });
            });
        }
    </script>

    @yield('js')
</body>
</html>
