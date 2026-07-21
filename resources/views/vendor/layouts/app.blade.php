<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendor Control Hub') - {{ setting('general', 'site_name', config('app.name')) }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        :root {
            --v-primary: #4f46e5;
            --v-primary-dark: #4338ca;
            --v-secondary: #06b6d4;
            --v-bg: #f8fafc;
            --v-card-bg: #ffffff;
            --v-text-main: #0f172a;
            --v-text-muted: #64748b;
            --v-border: #e2e8f0;
            --v-radius: 16px;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--v-bg);
            color: var(--v-text-main);
            min-height: 100vh;
        }

        /* Top Bar Navigation */
        .vendor-navbar {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            box-shadow: 0 10px 30px rgba(30, 27, 75, 0.25);
            padding: 0.75rem 1.5rem;
        }

        .vendor-brand {
            font-weight: 800;
            font-size: 1.25rem;
            color: #ffffff !important;
            letter-spacing: -0.025em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .vendor-brand-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 20px;
            letter-spacing: 0.05em;
        }

        .vendor-nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.6rem 1rem !important;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .vendor-nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.12);
        }

        .vendor-nav-link.active {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.2);
        }

        /* User Profile Menu */
        .user-dropdown-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff !important;
            border-radius: 12px;
            padding: 6px 14px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .user-dropdown-btn:hover, .user-dropdown-btn:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .dropdown-menu-custom {
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 8px;
        }

        .dropdown-menu-custom .dropdown-item {
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 8px 12px;
            transition: all 0.15s ease;
        }

        /* Cards & Components */
        .v-card {
            background: var(--v-card-bg);
            border-radius: var(--v-radius);
            border: 1px solid var(--v-border);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .v-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }

        /* Stat Icon Badges */
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-icon.primary { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }
        .stat-icon.info { background: rgba(6, 182, 212, 0.1); color: #0891b2; }
        .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #059669; }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #d97706; }

        /* Custom Badges */
        .badge-approved { background: #dcfce7; color: #15803d; font-weight: 600; padding: 5px 10px; border-radius: 20px; }
        .badge-pending { background: #fef3c7; color: #b45309; font-weight: 600; padding: 5px 10px; border-radius: 20px; }
        .badge-rejected { background: #fee2e2; color: #b91c1c; font-weight: 600; padding: 5px 10px; border-radius: 20px; }

        /* Welcome Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #312e81 0%, #4338ca 60%, #6366f1 100%);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            color: #ffffff;
            box-shadow: 0 10px 25px rgba(67, 56, 202, 0.25);
            position: relative;
            overflow: hidden;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg vendor-navbar sticky-top">
        <div class="container-fluid px-lg-4">
            <a class="vendor-brand me-4 text-decoration-none" href="{{ route('vendor.dashboard') }}">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-store text-warning fs-4"></i>
                    <span>{{ setting('general', 'site_name', 'Bazarei') }}</span>
                </div>
                <span class="vendor-brand-badge">Vendor Hub</span>
            </a>
            
            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#vendorNavbarContent">
                <i class="fas fa-bars fs-4"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="vendorNavbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="vendor-nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                            <i class="fas fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="vendor-nav-link {{ request()->routeIs('vendor.products.*') ? 'active' : '' }}" href="{{ route('vendor.products.index') }}">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="vendor-nav-link {{ request()->routeIs('vendor.orders.*') ? 'active' : '' }}" href="{{ route('vendor.orders.index') }}">
                            <i class="fas fa-shopping-bag"></i> Orders
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="vendor-nav-link {{ request()->routeIs('vendor.withdrawals.*') ? 'active' : '' }}" href="{{ route('vendor.withdrawals.index') }}">
                            <i class="fas fa-wallet"></i> Withdrawals
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-sm text-white bg-white bg-opacity-10 border-0 rounded-pill px-3 py-2 fw-semibold d-none d-md-inline-flex align-items-center gap-2" style="backdrop-filter: blur(5px);">
                        <i class="fas fa-globe text-warning"></i> Storefront
                    </a>

                    <div class="dropdown">
                        <button class="btn user-dropdown-btn dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.8rem;">
                                {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
                            </div>
                            <span class="d-none d-sm-inline">{{ auth()->user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom mt-2">
                            <li class="px-3 py-2 border-bottom mb-1">
                                <div class="fw-bold text-dark fs-6">{{ auth()->user()->name }}</div>
                                <small class="text-muted">{{ auth()->user()->email }}</small>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('vendor.profile') }}">
                                    <i class="fas fa-user-gear text-primary"></i> Store Settings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('account.show') }}">
                                    <i class="fas fa-user-circle text-info"></i> Customer Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="fas fa-sign-out-alt"></i> Logout Session
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container-fluid px-lg-4 py-4">
        @if(!auth()->user()->vendorSettings || !auth()->user()->vendorSettings->is_verified)
            <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex align-items-center gap-3 p-3 mb-4" role="alert">
                <div class="bg-warning bg-opacity-20 p-2 rounded-circle text-dark">
                    <i class="fas fa-shield-halved fs-4 text-warning"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Verification Pending</h6>
                    <small class="text-muted">Your vendor account is under review by administrator. Some functionality may be restricted until verified.</small>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 d-flex align-items-center justify-content-between p-3 mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-circle-check fs-5 text-success"></i>
                    <span class="fw-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm rounded-4 d-flex align-items-center justify-content-between p-3 mb-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-circle-exclamation fs-5 text-danger"></i>
                    <span class="fw-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
