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
    
    @yield('styles')
    @stack('styles')
    
    <style>
        :root {
            --v-sidebar-width: 250px;
            --v-primary: #6366f1;
            --v-primary-dark: #4f46e5;
            --v-primary-glow: rgba(99, 102, 241, 0.15);
            --v-bg: #f8fafc;
            --v-card-bg: #ffffff;
            --v-text-main: #0f172a;
            --v-text-muted: #64748b;
            --v-border: #e2e8f0;
            --v-radius: 16px;
            --v-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--v-bg);
            color: var(--v-text-main);
            min-height: 100vh;
            overflow-x: hidden;
            font-size: 0.875rem;
        }

        /* Compact Typography Overrides */
        h1, .h1 { font-size: 1.4rem !important; }
        h2, .h2 { font-size: 1.25rem !important; }
        h3, .h3 { font-size: 1.1rem !important; }
        h4, .h4 { font-size: 1rem !important; }
        h5, .h5 { font-size: 0.9rem !important; }
        h6, .h6 { font-size: 0.825rem !important; }

        /* Sidebar Styling */
        .vendor-sidebar {
            width: var(--v-sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            background: linear-gradient(180deg, #090a16 0%, #111329 50%, #1b1d3a 100%);
            color: #ffffff;
            transition: var(--v-transition);
            box-shadow: 10px 0 30px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }

        .vendor-sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .vendor-sidebar-brand i {
            filter: drop-shadow(0 0 8px rgba(255, 193, 7, 0.4));
        }

        .vendor-sidebar-nav {
            padding: 1rem 0.85rem;
            flex-grow: 1;
            overflow-y: auto;
        }

        .vendor-sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .vendor-sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .vendor-nav-header {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: rgba(255, 255, 255, 0.35);
            padding: 0.85rem 1rem 0.4rem 1rem;
        }

        .vendor-sidebar-link {
            color: rgba(255, 255, 255, 0.65);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: var(--v-transition);
            margin-bottom: 5px;
            position: relative;
        }

        .vendor-sidebar-link i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            opacity: 0.85;
            transition: var(--v-transition);
        }

        .vendor-sidebar-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(4px);
        }
        .vendor-sidebar-link:hover i {
            transform: scale(1.1);
            opacity: 1;
        }

        .vendor-sidebar-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--v-primary) 0%, var(--v-primary-dark) 100%);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }
        .vendor-sidebar-link.active i {
            opacity: 1;
        }

        /* Submenu Styling */
        .vendor-sidebar-group {
            margin-bottom: 5px;
        }
        
        .vendor-sidebar-group .collapse {
            border-left: 2px solid rgba(255, 255, 255, 0.08);
            margin-left: 1.5rem;
            padding-left: 0.5rem;
            transition: var(--v-transition);
        }

        /* Top Header Navigation */
        .vendor-main-wrapper {
            margin-left: var(--v-sidebar-width);
            transition: var(--v-transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .vendor-topbar {
            height: 70px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--v-border);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        /* Wallet Header Widget */
        .wallet-pill {
            background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
            border: 1px solid rgba(99, 102, 241, 0.15);
            border-radius: 50px;
            padding: 5px 6px 5px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.825rem;
            transition: var(--v-transition);
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.05);
        }
        
        .wallet-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.12);
        }

        .wallet-balance-amount {
            font-weight: 800;
            font-size: 0.95rem;
            color: #312e81;
        }

        /* Cards & Styling */
        .v-card {
            background: var(--v-card-bg);
            border-radius: var(--v-radius);
            border: 1px solid var(--v-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01);
            transition: var(--v-transition);
        }
        .v-card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
        }

        /* Badges */
        .badge-approved { background: #dcfce7; color: #15803d; font-weight: 600; padding: 5px 12px; border-radius: 20px; }
        .badge-pending { background: #fef3c7; color: #b45309; font-weight: 600; padding: 5px 12px; border-radius: 20px; }
        .badge-rejected { background: #fee2e2; color: #b91c1c; font-weight: 600; padding: 5px 12px; border-radius: 20px; }

        /* Hero Banner */
        .hero-banner {
            background: linear-gradient(135deg, #090a16 0%, #1e1b4b 60%, #4338ca 100%);
            border-radius: var(--v-radius);
            padding: 2rem;
            color: #ffffff;
            box-shadow: 0 15px 35px rgba(49, 46, 129, 0.2);
            position: relative;
            overflow: hidden;
        }

        /* Stat Icon Custom Styling */
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            transition: var(--v-transition);
        }
        .stat-icon.primary { background: rgba(99, 102, 241, 0.1) !important; color: var(--v-primary) !important; }
        .stat-icon.info { background: rgba(14, 165, 233, 0.1) !important; color: #0ea5e9 !important; }
        .stat-icon.success { background: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }
        .stat-icon.warning { background: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }

        /* Premium Form Controls */
        .form-control, .form-select, .form-control:focus, .form-select:focus {
            border-radius: 12px;
            padding: 0.75rem 1.15rem;
            font-size: 0.875rem;
            border: 1px solid var(--v-border);
            box-shadow: none;
            transition: var(--v-transition);
            background-color: #ffffff;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--v-primary);
            box-shadow: 0 0 0 4px var(--v-primary-glow) !important;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--v-text-main);
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        /* Modern Table Styles */
        .table-responsive {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--v-border);
            background: #ffffff;
        }
        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }
        .table th {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--v-text-muted) !important;
            padding: 1.1rem 1.5rem !important;
            background-color: #f8fafc !important;
            border-bottom: 1px solid var(--v-border) !important;
        }
        .table td {
            padding: 1.1rem 1.5rem !important;
            border-bottom: 1px solid var(--v-border) !important;
            font-size: 0.85rem !important;
            color: var(--v-text-main) !important;
        }
        .table tr:last-child td {
            border-bottom: none !important;
        }
        .table tbody tr {
            transition: var(--v-transition);
        }
        .table tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.02) !important;
        }

        /* Buttons Redesign */
        .btn {
            font-weight: 700;
            border-radius: 10px;
            padding: 0.45rem 1rem;
            transition: var(--v-transition);
            font-size: 0.8rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--v-primary) 0%, var(--v-primary-dark) 100%) !important;
            border: none !important;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.15) !important;
            color: #ffffff !important;
        }
        .btn-primary:hover {
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.25) !important;
            transform: translateY(-1px);
            color: #ffffff !important;
        }
        .btn-warning {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
            border: none !important;
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.15) !important;
            color: #0f172a !important;
        }
        .btn-warning:hover {
            box-shadow: 0 6px 15px rgba(245, 158, 11, 0.25) !important;
            transform: translateY(-1px);
            color: #0f172a !important;
        }

        @media (max-width: 991.98px) {
            .vendor-sidebar {
                margin-left: calc(-1 * var(--v-sidebar-width));
            }
            .vendor-sidebar.show {
                margin-left: 0;
            }
            .vendor-main-wrapper {
                margin-left: 0;
            }
            
            /* Sidebar overlay/backdrop when open on mobile */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0,0,0,0.5);
                z-index: 1035;
                display: none;
                backdrop-filter: blur(4px);
            }
            .sidebar-overlay.show {
                display: block;
            }

            /* Responsive tweaks for wallet pill and topbar on extra small screens */
            .vendor-topbar {
                height: 60px !important;
                padding: 0 0.75rem !important;
            }
            .wallet-pill {
                padding: 3px 5px 3px 8px !important;
                gap: 6px !important;
            }
            .wallet-pill .btn-primary {
                padding: 0.25rem 0.5rem !important;
                font-size: 0.65rem !important;
            }
            .wallet-balance-amount {
                font-size: 0.75rem !important;
            }
            
            /* Hide non-essential buttons in topbar on extra small screens to save space */
            @media (max-width: 575.98px) {
                .vendor-topbar .btn-light.border.rounded-circle,
                .vendor-topbar .dropdown:has(.fa-bell) {
                    display: none !important;
                }
                .wallet-pill {
                    margin-right: 0.25rem;
                }
            }
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="vendor-sidebar" id="vendorSidebar">
        <div class="vendor-sidebar-brand flex-column align-items-start gap-2">
            <a href="{{ route('vendor.dashboard') }}" class="text-decoration-none d-flex align-items-center gap-2 w-100">
                <i class="fas fa-store text-warning fs-3"></i>
                <span class="fw-bold text-white fs-5" style="line-height: 1.2; word-break: break-word;">{{ setting('general', 'site_name', 'Bazarei') }}</span>
            </a>
            @php
                $user = auth()->user();
                $vendorSettings = $user?->vendorSettings;
                $vendorType = $vendorSettings?->additional_config['vendor_type'] ?? null;
                
                $roleLabel = 'VENDOR';
                $badgeClass = 'bg-warning text-dark';
                
                if ($user?->hasRole('reseller')) {
                    $roleLabel = 'RESELLER';
                    $badgeClass = 'bg-success text-white';
                } elseif ($user?->hasRole('wholeseller') || $vendorType === 'wholeseller') {
                    $roleLabel = 'WHOLESELLER';
                    $badgeClass = 'bg-info text-white';
                } elseif ($vendorType === 'retailer') {
                    $roleLabel = 'RETAILER';
                    $badgeClass = 'bg-primary text-white';
                }
            @endphp
            <span class="badge {{ $badgeClass }} fw-bold px-2 py-1" style="font-size: 0.65rem; margin-top: 4px;">{{ $roleLabel }}</span>
        </div>

        <div class="vendor-sidebar-nav">
            <div class="vendor-nav-header">Main Menu</div>
            <a class="vendor-sidebar-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            <a class="vendor-sidebar-link d-flex align-items-center justify-content-between {{ request()->routeIs('chats.index') ? 'active' : '' }}" href="{{ route('chats.index') }}">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-comments text-info"></i> Chats
                </span>
                @php
                    $sidebarUnreadCount = 0;
                    if (auth()->check()) {
                        $user = auth()->user();
                        $sidebarUnreadCount = \App\Models\ChatMessage::where('is_read', false)
                            ->where('sender_id', '!=', $user->id)
                            ->whereHas('chatRoom', function($q) use ($user) {
                                $q->where('customer_id', $user->id)
                                  ->orWhere('vendor_id', $user->id);
                            })
                            ->count();
                    }
                @endphp
                @if($sidebarUnreadCount > 0)
                    <span class="badge bg-danger text-white font-weight-bold px-2 py-1 rounded-pill" style="font-size: 0.72rem;">{{ $sidebarUnreadCount }}</span>
                @endif
            </a>

            <a class="vendor-sidebar-link {{ request()->routeIs('vendor.products.*') ? 'active' : '' }}" href="{{ route('vendor.products.index') }}">
                <i class="fas fa-box"></i> Products
            </a>

            @if(auth()->user()?->hasRole('reseller'))
            <a class="vendor-sidebar-link {{ request()->routeIs('vendor.pos.*') ? 'active' : '' }}" href="{{ route('vendor.pos.index') }}">
                <i class="fas fa-cash-register text-success"></i> Reseller POS
            </a>
            <a class="vendor-sidebar-link d-flex align-items-center justify-content-between {{ request()->routeIs('vendor.orders.reseller') ? 'active' : '' }}" href="{{ route('vendor.orders.reseller') }}">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-receipt" style="color:#a78bfa;"></i> My POS Orders
                </span>
                @php
                    $resellerPosOrderCount = \App\Models\order_item::where('vendor_id', auth()->id())
                        ->whereNotNull('others')
                        ->distinct('order_id')
                        ->count('order_id');
                @endphp
                @if($resellerPosOrderCount > 0)
                    <span class="badge bg-warning text-dark rounded-pill" style="font-size:0.65rem;">{{ $resellerPosOrderCount }}</span>
                @endif
            </a>
            @endif

            @php
                $vUser = auth()->user();
                $vOrderIds = \App\Models\order_item::forVendor($vUser->id)->pluck('order_id')->unique();
                $vTotalOrders = $vOrderIds->count();
                $vPendingOrders = \App\Models\order::whereIn('id', $vOrderIds)->whereIn('status', ['pending', 'processing'])->count();
                $vRecentOrders = \App\Models\order::whereIn('id', $vOrderIds)->with(['customer'])->latest()->take(5)->get();
            @endphp

            @if(auth()->user()->can('vendor.orders.view') || auth()->user()->hasRole('vendor') || auth()->user()->hasRole('wholeseller') || auth()->user()->hasRole('reseller'))
            <a class="vendor-sidebar-link d-flex align-items-center justify-content-between {{ request()->routeIs('vendor.orders.index') || request()->routeIs('vendor.orders.show') ? 'active' : '' }}" href="{{ route('vendor.orders.index') }}">
                <span class="d-flex align-items-center gap-2">
                    <i class="fas fa-shopping-cart"></i> Orders
                </span>
                @if($vTotalOrders > 0)
                    <span class="badge bg-warning text-dark font-weight-bold px-2 py-1 rounded-pill" style="font-size: 0.72rem;">{{ $vTotalOrders }}</span>
                @endif
            </a>
            <a class="vendor-sidebar-link d-flex align-items-center gap-2 {{ request()->routeIs('vendor.orders.earnings') ? 'active' : '' }}" href="{{ route('vendor.orders.earnings') }}">
                <i class="fas fa-chart-line text-success"></i> Earnings
            </a>
            @endif

            @if(auth()->user()?->vendorSettings?->is_consignment && (auth()->user()->can('vendor.balance.view') || auth()->user()->can('vendor.withdrawals.create')))
            <div class="vendor-nav-header">Finance & Wallet</div>
            @endif

            @if(auth()->user()?->vendorSettings?->is_consignment)
                @can('vendor.balance.view')
                <a class="vendor-sidebar-link {{ request()->routeIs('vendor.wallet.*') ? 'active' : '' }}" href="{{ route('vendor.wallet.index') }}">
                    <i class="fas fa-wallet text-warning"></i> My Wallet
                </a>
                @endcan

                @can('vendor.withdrawals.create')
                <a class="vendor-sidebar-link {{ request()->routeIs('vendor.withdrawals.*') ? 'active' : '' }}" href="{{ route('vendor.withdrawals.index') }}">
                    <i class="fas fa-hand-holding-dollar"></i> Withdrawals
                </a>
                @endcan
            @endif

            @if(auth()->user()->can('vendor.profile.edit') || auth()->user()->hasRole('reseller'))
            <div class="vendor-nav-header">Account & Setup</div>
            <a class="vendor-sidebar-link {{ request()->routeIs('vendor.profile') ? 'active' : '' }}" href="{{ route('vendor.profile') }}">
                <i class="fas fa-cog"></i> Store Settings
            </a>
            @endif

            @php
                $user = auth()->user();
                $hasDarazPermission = false;
                if ($user) {
                    if ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->hasRole('admin')) {
                        $hasDarazPermission = true;
                    } elseif (method_exists($user, 'canAny') && $user->canAny(['daraz.view', 'admin.daraz.view', 'daraz_sync.view', 'daraz.index', 'daraz'])) {
                        $hasDarazPermission = true;
                    } elseif (method_exists($user, 'hasAnyPermission') && $user->hasAnyPermission(['daraz.view', 'admin.daraz.view', 'daraz_sync.view', 'daraz.index', 'daraz'])) {
                        $hasDarazPermission = true;
                    } elseif ($user->permissions && $user->permissions->pluck('name')->filter(fn($p) => str_contains(strtolower($p), 'daraz'))->count() > 0) {
                        $hasDarazPermission = true;
                    } elseif ($user->roles && $user->roles->flatMap->permissions->pluck('name')->filter(fn($p) => str_contains(strtolower($p), 'daraz'))->count() > 0) {
                        $hasDarazPermission = true;
                    }
                }
            @endphp

            @if(module_enabled('Daraz') && (Route::has('vendor.daraz.index') || Route::has('admin.daraz.index')) && $hasDarazPermission)
                <div class="vendor-nav-header mt-3">Connected Apps</div>
                <div class="vendor-sidebar-group">
                    <a class="vendor-sidebar-link d-flex align-items-center justify-content-between {{ request()->is('vendor/daraz*') || request()->is('admin/daraz*') ? 'active' : '' }}" 
                       data-bs-toggle="collapse" 
                       href="#darazSyncVendorMenu" 
                       role="button" 
                       aria-expanded="{{ request()->is('vendor/daraz*') || request()->is('admin/daraz*') ? 'true' : 'false' }}">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-rotate text-warning"></i> Daraz Sync
                        </span>
                        <i class="fas fa-chevron-down fs-8"></i>
                    </a>
                    <div class="collapse {{ request()->is('vendor/daraz*') || request()->is('admin/daraz*') ? 'show' : '' }} ps-3 mt-1" id="darazSyncVendorMenu">
                        <a class="vendor-sidebar-link py-1 text-white-50 {{ request()->routeIs('vendor.daraz.index') || request()->routeIs('admin.daraz.index') ? 'active text-white' : '' }}" href="{{ route('vendor.daraz.index') }}">
                            <i class="fas fa-chart-simple fs-7"></i> Dashboard
                        </a>
                        <a class="vendor-sidebar-link py-1 text-white-50 {{ request()->routeIs('vendor.daraz.stores.*') || request()->routeIs('admin.daraz.stores.*') ? 'active text-white' : '' }}" href="{{ route('vendor.daraz.stores.index') }}">
                            <i class="fas fa-store fs-7"></i> Stores
                        </a>
                        <a class="vendor-sidebar-link py-1 text-white-50 {{ request()->routeIs('vendor.daraz.orders.*') || request()->routeIs('admin.daraz.orders.*') ? 'active text-white' : '' }}" href="{{ route('vendor.daraz.orders.index') }}">
                            <i class="fas fa-shopping-cart fs-7"></i> Daraz Orders
                        </a>
                        <a class="vendor-sidebar-link py-1 text-white-50 {{ request()->routeIs('vendor.daraz.mappings.*') || request()->routeIs('admin.daraz.mappings.*') ? 'active text-white' : '' }}" href="{{ route('vendor.daraz.mappings.index') }}">
                            <i class="fas fa-arrows-spin fs-7"></i> Product Mappings
                        </a>
                        <a class="vendor-sidebar-link py-1 text-white-50 {{ request()->routeIs('vendor.daraz.sync.logs') || request()->routeIs('admin.daraz.sync.logs') ? 'active text-white' : '' }}" href="{{ route('vendor.daraz.sync.logs') }}">
                            <i class="fas fa-terminal fs-7"></i> Sync Logs
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar Footer -->
        <div class="p-3 border-top border-white border-opacity-10" style="background: rgba(0,0,0,0.15);">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px; border: 2px solid rgba(255, 193, 7, 0.2); font-size: 1rem; flex-shrink: 0;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'V', 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-white fw-bold fs-7 text-truncate" style="letter-spacing: 0.02em;">{{ auth()->user()->name }}</div>
                    <small class="text-white-50 d-block text-truncate" style="font-size: 0.72rem;">{{ auth()->user()->email }}</small>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="vendor-main-wrapper">
        <!-- Topbar Header -->
        <header class="vendor-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" type="button" id="sidebarToggleBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="fw-bold mb-0 text-dark d-none d-sm-block">@yield('title', 'Vendor Control Hub')</h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Topbar Wallet Widget -->
                @if(!auth()->user()?->hasRole('reseller') && auth()->user()?->vendorSettings?->is_consignment)
                <div class="wallet-pill shadow-sm">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-wallet text-primary fs-5"></i>
                        <div>
                            <small class="text-muted d-block lh-1" style="font-size: 0.68rem; font-weight: 700; text-transform: uppercase;">Wallet Balance</small>
                            <span class="wallet-balance-amount">৳ {{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</span>
                        </div>
                    </div>
                    <a href="{{ route('vendor.wallet.index') }}" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-bold fs-7 d-flex align-items-center gap-1 shadow-sm">
                        <i class="fas fa-plus-circle"></i> Recharge
                    </a>
                </div>
                @endif

                <!-- Storefront Link -->
                @if(!auth()->user()?->hasRole('reseller'))
                <a href="{{ url('/') }}" target="_blank" class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="View Storefront">
                    <i class="fas fa-globe text-secondary"></i>
                </a>

                <!-- Chat Messages Icon -->
                @php
                    $unreadChatCount = 0;
                    if (auth()->check()) {
                        $user = auth()->user();
                        $unreadChatCount = \App\Models\ChatMessage::where('is_read', false)
                            ->where('sender_id', '!=', $user->id)
                            ->whereHas('chatRoom', function($q) use ($user) {
                                $q->where('customer_id', $user->id)
                                  ->orWhere('vendor_id', $user->id);
                            })
                            ->count();
                    }
                @endphp
                <a href="{{ route('chats.index') }}" class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center position-relative" style="width: 40px; height: 40px;" title="Chat Messages">
                    <i class="fas fa-comments text-secondary"></i>
                    @if($unreadChatCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; transform: translate(-30%, 10%) !important;">
                            {{ $unreadChatCount }}
                        </span>
                    @endif
                </a>

                <!-- Order Notifications Bell Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light border rounded-circle d-flex align-items-center justify-content-center position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;" title="Order Notifications">
                        <i class="fas fa-bell text-secondary"></i>
                        @if(($vPendingOrders ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; transform: translate(-30%, 10%) !important;">
                                {{ $vPendingOrders }}
                                <span class="visually-hidden">pending orders</span>
                            </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-0 overflow-hidden" style="width: 340px;">
                        <div class="p-3 bg-primary text-white d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0 text-white"><i class="fas fa-bell me-2"></i> Order Notifications</h6>
                            <span class="badge bg-white text-primary font-weight-bold">{{ $vTotalOrders ?? 0 }} Total</span>
                        </div>
                        <div class="p-2" style="max-height: 310px; overflow-y: auto;">
                            @forelse(($vRecentOrders ?? []) as $rOrder)
                                <a href="{{ route('vendor.orders.show', $rOrder) }}" class="dropdown-item p-2.5 rounded-3 mb-1 d-flex align-items-center gap-3 border-bottom text-wrap">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px;">
                                        <i class="fas fa-shopping-bag fs-6"></i>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong class="text-dark fs-7">#{{ $rOrder->invoice_no ?? $rOrder->order_number ?? $rOrder->id }}</strong>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $rOrder->created_at ? $rOrder->created_at->diffForHumans() : '' }}</small>
                                        </div>
                                        <div class="small text-muted text-truncate">{{ $rOrder->customer->name ?? 'Customer Order' }}</div>
                                        <div class="mt-1">
                                            <span class="badge bg-{{ in_array($rOrder->status, ['completed', 'delivered']) ? 'success' : ($rOrder->status === 'pending' ? 'warning' : 'info') }} bg-opacity-10 text-{{ in_array($rOrder->status, ['completed', 'delivered']) ? 'success' : ($rOrder->status === 'pending' ? 'warning' : 'info') }} border border-{{ in_array($rOrder->status, ['completed', 'delivered']) ? 'success' : ($rOrder->status === 'pending' ? 'warning' : 'info') }} border-opacity-25 px-2 py-0.5" style="font-size: 0.68rem;">
                                                {{ ucfirst($rOrder->status ?? 'New') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-bell-slash fs-3 d-block mb-2 text-muted opacity-50"></i>
                                    <small>No order notifications yet</small>
                                </div>
                            @endforelse
                        </div>
                        @if(($vTotalOrders ?? 0) > 0)
                            <div class="p-2 bg-light text-center border-top">
                                <a href="{{ route('vendor.orders.index') }}" class="fw-bold text-primary text-decoration-none fs-7 d-block py-1">
                                    View All Orders <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-light border rounded-pill px-3 py-1 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle text-primary fs-5"></i>
                        <span class="fw-semibold text-dark fs-7 d-none d-md-inline">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-muted fs-8"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2 p-2" style="min-width: 200px;">
                        <li><a class="dropdown-item rounded-3 py-2 fw-semibold" href="{{ route('vendor.profile') }}"><i class="fas fa-store text-primary me-2"></i> Store Settings</a></li>
                        <li><a class="dropdown-item rounded-3 py-2 fw-semibold" href="{{ route('vendor.wallet.index') }}"><i class="fas fa-wallet text-warning me-2"></i> Wallet & Payments</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item rounded-3 py-2 text-danger fw-semibold"><i class="fas fa-sign-out-alt me-2"></i> Logout Session</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Body View -->
        <main class="flex-grow-1 p-3 p-md-4">
            @if(!auth()->user()->hasRole('reseller') && (!auth()->user()->vendorSettings || !auth()->user()->vendorSettings->is_verified))
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

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm rounded-4 p-3 mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="fas fa-triangle-exclamation fs-5 text-danger"></i>
                        <span class="fw-bold text-danger">Please fix the following validation errors:</span>
                    </div>
                    <ul class="mb-0 ps-4 text-danger small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
    </div>
    
    <!-- Sidebar mobile overlay backdrop -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('vendorSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggleBtn');

        function toggleSidebar() {
            if (sidebar && overlay) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }
        }

        toggleBtn?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);
    </script>
    @stack('scripts')
</body>
</html>
