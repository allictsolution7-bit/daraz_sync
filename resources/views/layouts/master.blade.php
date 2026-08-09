<!DOCTYPE html>
<html lang="en">

	<head>
	    <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	    <meta name="description" content="Thikana Shop Admin Dashboard & Order Management System">
	    <meta name="csrf-token" content="{{ csrf_token() }}">
	    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layets.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/combo-offer.css') }}">

    <!-- Push styles section -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    @stack('styles')
    @yield('styles')

    <style>
        .glowcard {
            position: relative;
            display: block;

            border-radius: 7px;
            color: white;
            padding: 21px 30px;
            font-family: Arial, sans-serif;
            overflow: hidden;
        }

        .glowcard1 {
            background: linear-gradient(50deg, rgb(30 195 195) 0%, rgb(100 147 255) 100%);
        }

        .glowcard2 {
            background: linear-gradient(50deg, rgb(7, 189, 189) 0%, rgb(163, 214, 24) 100%);
        }

        .glowcard3 {
            background: linear-gradient(50deg, rgb(67, 58, 196) 0%, rgb(100 147 255) 100%);
        }

        .glowcard4 {
            background: linear-gradient(50deg, rgb(30 195 195) 0%, rgb(128, 36, 151) 100%);
        }

        .glowcard-content {
            display: flex;
            align-items: center;
        }

        .glowcard .icon {
            font-size: 1.6em;
            margin-right: 10px;
            background: #f3f3f3;
            padding: 10px 8px;
            border-radius: 4px;
        }

        .glowcard-content .icon span {
            color: var(--main-color);
        }

        .glowcard .count {
            font-size: 1.5em;
            font-weight: bold;
        }

        .glowcard .label {
            font-size: 1.1em;
            margin-top: -5px;
            margin-bottom: -4px;
            font-weight: 500;
        }

        span.currentupdown {
            background: #fff;
            color: #404040;
            font-size: 12px;
            padding: 3px 10px;
            font-weight: 400;
            border-radius: 4px;
            color: green;
        }

        .glowcard::before,
        .glowcard::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            width: 200px;
            height: 200px;
        }

        .glowcard::before {
            top: -105px;
            left: 137px;
        }

        .glowcard::after {
            bottom: -50px;
            right: -50px;
        }

        .glowcard i {
            font-size: 10px;
            ;
        }

        @media (max-width: 768px) {
            .sm-chart-sec .row {
                --bs-gutter-x: 0.5rem !important;
            }

            .glowcard {
                color: white;
                padding: 8px 8px;
            }

            .glowcard .icon {
                font-size: 1em;
                margin-right: 5px;
                padding: 0px 6px;
                margin-bottom: 0px;
            }

            .glowcard .label {
                font-size: 1em;
                margin-top: -5px;
                margin-bottom: -8px;
                font-weight: 500;
            }

            .glowcard .count {
                font-size: 1.2em;
                font-weight: bold;
            }

            span.currentupdown {
                color: #404040;
                font-size: 10px;
                padding: 3px 5px;
            }
        }

        /* Global breadcrumb styling and top space reduction */
        .breadcrumb {
            justify-content: flex-end !important;
            background: transparent !important;
            padding: 0 !important;
            margin-bottom: 0.5rem !important;
        }
        .dashboard-container, .container-fluid, .content-wrapper > div {
            padding-top: 0.75rem !important;
        }
        .page-header-block {
            margin-bottom: 1rem !important;
        }

        /* Premium Profile Dropdown redesign overrides */
        .dropdown-menu.usr {
            border: none !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15), 0 10px 10px -5px rgba(15, 23, 42, 0.05) !important;
            overflow: hidden !important;
            padding: 0 !important;
            width: 300px !important;
            background: #ffffff !important;
        }

        .premium-user-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
            padding: 20px !important;
            color: white !important;
            position: relative;
            overflow: hidden;
            display: flex !important;
            align-items: center !important;
            gap: 16px !important;
        }

        .premium-user-header::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 70%);
            border-radius: 50%;
        }

        .premium-user-img {
            width: 52px !important;
            height: 52px !important;
            border-radius: 50% !important;
            border: 2px solid rgba(255, 255, 255, 0.2) !important;
            object-fit: cover !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.15);
            background: #1e293b;
            flex-shrink: 0;
            z-index: 1;
        }

        .premium-user-info-text {
            z-index: 1;
            overflow: hidden;
            flex-grow: 1;
            text-align: left !important;
        }

        .premium-user-name {
            font-size: 1.05rem !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            margin: 0 0 2px 0 !important;
            letter-spacing: -0.01em;
            line-height: 1.2;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            max-width: 190px !important;
        }

        .premium-user-email {
            font-size: 0.775rem !important;
            color: #94a3b8 !important;
            margin: 0 0 6px 0 !important;
            line-height: 1.2;
            font-weight: 500;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            max-width: 190px !important;
        }

        .premium-user-role {
            display: inline-block;
            background: rgba(99, 102, 241, 0.15) !important;
            color: #c7d2fe !important;
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 3px 8px !important;
            border-radius: 6px !important;
            margin: 0 !important;
            border: 1px solid rgba(99, 102, 241, 0.25) !important;
            line-height: 1 !important;
        }

        .premium-user-links {
            padding: 12px !important;
            background: #ffffff !important;
        }

        .premium-user-link {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 10px 12px !important;
            color: #334155 !important;
            border-radius: 12px !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            margin-bottom: 4px;
            text-align: left !important;
        }

        .premium-user-link:last-child {
            margin-bottom: 0;
        }

        .premium-link-icon-container {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .icon-profile { background: rgba(79, 70, 229, 0.06) !important; color: #4f46e5 !important; }
        .icon-settings { background: rgba(13, 148, 136, 0.06) !important; color: #0d9488 !important; }
        .icon-logout { background: rgba(225, 29, 72, 0.06) !important; color: #e11d48 !important; }

        .premium-link-title {
            font-size: 0.875rem !important;
            font-weight: 700 !important;
            color: #1e293b !important;
            line-height: 1.2;
        }

        .premium-link-desc {
            font-size: 0.75rem !important;
            color: #64748b !important;
            margin-top: 2px;
            line-height: 1.2;
        }

        .premium-user-link:hover {
            background-color: #f8fafc !important;
            transform: translateX(3px);
        }

        .premium-user-link:hover .premium-link-icon-container {
            transform: scale(1.05);
        }

        .premium-user-link-logout:hover {
            background-color: #fef2f2 !important;
        }
    </style>
    <style>
        .dataTables_wrapper tbody tr,
        td {
            border: none !important;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 10px 18px;
            border-bottom: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
            margin-left: 2px;
            border: 1px solid #ddd;
            background-color: #f7f7f7;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #197A94;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            color: #fff !important;
            border: 1px solid #197A94;
            background-color: #197A94;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1em;
            display: flex;
            justify-content: center;
        }

        .dataTables_wrapper .dataTables_filter {
            text-align: right;
        }

        .dataTables_wrapper .dataTables_length {
            margin-bottom: 1em;
        }

        .dataTables_wrapper .dataTables_info {
            margin-top: 1em;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1em;
        }

        table.dataTable {
            border-collapse: collapse !important;
        }

        table.dataTable thead th,
        table.dataTable tfoot th {
            background-color: #197A94;
            color: white;
        }

        table.dataTable tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table.dataTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-primary,
        .btn-danger {
            margin-right: 5px;
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100%;
        }

        table.dataTable thead th,
        table.dataTable tfoot th {
            background-color: #197A94;
            color: white;
        }

        table.dataTable tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        table.dataTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Pagination Button Styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
            margin-left: 2px;
            border: 1px solid #ddd;
            background-color: #f7f7f7;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #197A94;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            color: #fff !important;
            border: 1px solid #197A94;
            background-color: #197A94;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1em;
            display: flex;
            justify-content: center;
        }

        /* Search Box Styling */
        .dataTables_wrapper .dataTables_filter {
            text-align: right;
            margin-bottom: 1em;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0.5em;
        }

        /* Length Menu Styling */
        .dataTables_wrapper .dataTables_length {
            margin-bottom: 1em;
        }

        /* Info Styling */
        .dataTables_wrapper .dataTables_info {
            margin-top: 1em;
        }

        /* Buttons Styling */
        .dt-button {
            background-color: #197A94;
            color: white;
            border: 1px solid #197A94;
            border-radius: 4px;
            padding: 0.5em 1em;
            margin: 0.5em 0.5em 0.5em 0;
            cursor: pointer;
        }

        .dt-button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Action Buttons */
        .btn-primary,
        .btn-danger {
            margin-right: 5px;
        }
    </style>
    <style>
        /* Add this to your existing styles */
        #sidebar li.active>a {
            background-color: rgba(0, 123, 255, 0.1);
            color: #197A94;
            font-weight: bold;
        }

        #sidebar .sub-menu.active>a {
            background-color: rgba(0, 123, 255, 0.1);
            color: #197A94;
        }

        #sidebar .left-menu-dp li.active>a {
            color: #197A94;
            font-weight: bold;
        }

        #sidebar .sidebar-bottom-info {
            margin-top: 24px;
            padding: 0px 8px 8px;
            font-size: 12px;
            line-height: 1.4;
            color: #6c757d;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            margin-bottom: 50px;
        }

        #sidebar .sidebar-bottom-info a {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: inherit;
            text-decoration: none;
            transition: color 0.2s ease-in-out;
        }

        #sidebar .sidebar-bottom-info a:hover {
            color: #197A94;
        }

        /* POS Menu Styling */
        .pos-menu-item {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
            color: white !important;
            border-radius: 8px !important;
            margin: 5px 10px !important;
            padding: 12px 15px !important;
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3) !important;
            transition: all 0.3s ease !important;
        }

        .pos-menu-item:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%) !important;
            color: white !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4) !important;
        }

        .pos-menu-item .menu-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pos-menu-item .fas {
            font-size: 18px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Menu Category Headers */
        .menu-category-header {
            padding: 12px 20px 8px 20px;
            margin-top: 15px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 5px;
        }

        .menu-category-header:first-child {
            margin-top: 0;
        }

        #sidebar>li:first-child {
            margin-top: 0;
        }

        .header-container {
            position: fixed !important;
            z-index: 2000 !important;
        }

        .user-profile-dropdown .dropdown-menu {
            z-index: 3000 !important;
        }

        /* Premium Search Bar */
        .search-bar {
            margin-left: 20px;
            flex-grow: 1;
            max-width: 400px;
        }
        .search-form {
            position: relative;
            width: 100%;
        }
        .search-form input {
            width: 100%;
            height: 40px;
            padding: 10px 20px 10px 45px;
            font-size: 14px;
            color: #334155;
            background-color: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .search-form input:focus {
            background-color: #ffffff;
            border-color: #197A94;
            box-shadow: 0 0 0 3px rgba(25, 122, 148, 0.15);
            outline: none;
        }
        .search-form button {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .search-form button i {
            font-size: 14px;
        }

        /* Mobile Header Responsive Overrides */
        @media (max-width: 768px) {
            .search-bar {
                display: none !important;
            }
            .header-container {
                width: 100% !important;
                left: 0 !important;
            }
            .header {
                padding: 6px 12px !important;
                min-height: 56px !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
            }
            .header-left {
                padding-left: 0 !important;
                gap: 12px !important;
                justify-content: flex-start !important;
                width: auto !important;
            }
            .admin-logo-wrapper img {
                max-height: 28px !important;
            }
            .sidebarCollapse {
                margin-left: 0 !important;
            }
            #toggleSidebar {
                order: -1 !important;
                margin-left: 0 !important;
                font-size: 18px !important;
            }
            .navbar-item {
                margin-left: auto !important;
                padding-top: 0 !important;
                padding-bottom: 0 !important;
                gap: 12px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-end !important;
                width: auto !important;
                flex: none !important;
            }
            .navbar-item .nav-item {
                margin: 0 !important;
                padding: 0 !important;
                width: auto !important;
                flex: none !important;
            }
            .navbar-item .nav-link {
                padding: 6px !important;
                margin: 0 !important;
                display: block !important;
            }
            .navbar-item .nav-link i {
                font-size: 18px !important;
            }

            /* Reduce excessive margins & paddings on mobile view */
            .content-wrapper {
                padding: 12px 10px !important;
                margin-left: 0 !important;
            }
            .container-fluid {
                padding: 5px 4px !important;
            }
            .form-section {
                padding: 16px 14px !important;
                margin-bottom: 16px !important;
                border-radius: 14px !important;
            }
            .row {
                --bs-gutter-x: 10px !important;
                --bs-gutter-y: 10px !important;
            }
            .tab-content {
                margin-top: 1rem !important;
            }
        }
    </style>
    @yield('styles')
</head>

<body>


    <div class="main-wrapper">
        <!-- -navbar- -->
        <div class="header-container">
            <header class="header navbar navbar-expand-sm expand-header">
                <div class="header-left d-flex align-items-center gap-3 ps-3">
                    <div class="admin-logo-wrapper">
                        <a href="{{ route('admin') }}">
                            <img src="{{ \App\Models\SiteSetting::getLogo() }}" alt="Logo" style="max-height: 40px; width: auto; object-fit: contain;">
                        </a>
                    </div>
                    <a href="#" id="toggleSidebar" class="sidebarCollapse ms-2" data-placement="button">
                        <span class="fas fa-bars"></span>
                    </a>
                </div>
                <!-- <div class="searchBar">
                    <input type="search" name="search" placeholder="Search..." id="">
                </div> -->
                <!-- ... existing code ... -->
                <div class="search-bar">
                    <form class="search-form d-flex align-items-center" method="POST" action="#">
                        <input type="text" name="query" placeholder="Search..." title="Enter search keyword">
                        <button type="submit" title="Search"><i class="fas fa-search"></i></button>
                    </form>
                </div><!-- End Search Bar -->

                <ul class="navbar-item flex-row  align-items-center py-2 ml-auto ">
                    <li class="nav-item dropdown user-profile-dropdown">
                        @php
                            $user = auth()->user();
                            $isSuperAdmin = false;
                            if ($user) {
                                if (method_exists($user, 'hasRole') && ($user->hasRole('super_admin') || $user->hasRole('super admin') || $user->hasRole('Super Admin'))) {
                                    $isSuperAdmin = true;
                                } elseif ($user->is_super_admin ?? false) {
                                    $isSuperAdmin = true;
                                }
                            }

                            // 1. Scoped recharge requests
                            if ($isSuperAdmin) {
                                $headerPendingCount = \App\Models\VendorWalletTransaction::where('status', 'pending')
                                    ->where('type', 'recharge_request')
                                    ->count();
                                $headerPendingPayments = \App\Models\VendorWalletTransaction::with('vendor')
                                    ->where('status', 'pending')
                                    ->where('type', 'recharge_request')
                                    ->latest()->limit(5)->get();
                            } else {
                                $adminVendorIds = \App\Models\User::where('created_by', $user->id)->pluck('id')->toArray();
                                $headerPendingCount = \App\Models\VendorWalletTransaction::where('status', 'pending')
                                    ->where('type', 'recharge_request')
                                    ->whereIn('vendor_id', $adminVendorIds)
                                    ->count();
                                $headerPendingPayments = \App\Models\VendorWalletTransaction::with('vendor')
                                    ->where('status', 'pending')
                                    ->where('type', 'recharge_request')
                                    ->whereIn('vendor_id', $adminVendorIds)
                                    ->latest()->limit(5)->get();
                            }

                            // 2. Scoped product approvals
                            if ($isSuperAdmin) {
                                $headerPendingProducts = \App\Models\Product::with('vendor')
                                    ->whereNotNull('vendor_id')
                                    ->where('approval_status', 'pending')
                                    ->latest()->limit(5)->get();
                                $headerPendingProdCount = \App\Models\Product::whereNotNull('vendor_id')
                                    ->where('approval_status', 'pending')
                                    ->count();
                            } else {
                                $adminVendorIds = \App\Models\User::where('created_by', $user->id)->pluck('id')->toArray();
                                $headerPendingProducts = \App\Models\Product::with('vendor')
                                    ->whereIn('vendor_id', $adminVendorIds)
                                    ->where('approval_status', 'pending')
                                    ->latest()->limit(5)->get();
                                $headerPendingProdCount = \App\Models\Product::whereIn('vendor_id', $adminVendorIds)
                                    ->where('approval_status', 'pending')
                                    ->count();
                            }

                            // 3. Scoped vendor orders
                            if ($isSuperAdmin) {
                                $headerVendorOrdersQuery = \App\Models\order::whereHas('orderItems', function ($q) {
                                    $q->whereNotNull('vendor_id');
                                })->where('status', 'pending');
                            } else {
                                $adminVendorIds = \App\Models\User::where('created_by', $user->id)->pluck('id')->toArray();
                                $adminProductIds = \App\Models\Product::where('created_by', $user->id)->pluck('id')->toArray();
                                
                                $headerVendorOrdersQuery = \App\Models\order::where('status', 'pending')
                                    ->whereHas('orderItems', function ($q) use ($adminVendorIds, $adminProductIds) {
                                        $q->where(function ($subQ) use ($adminVendorIds, $adminProductIds) {
                                            $hasCondition = false;
                                            if (!empty($adminVendorIds)) {
                                                $subQ->whereIn('vendor_id', $adminVendorIds)
                                                     ->orWhereHas('product', function ($pq) use ($adminVendorIds) {
                                                         $pq->whereIn('vendor_id', $adminVendorIds);
                                                     });
                                                $hasCondition = true;
                                            }
                                            if (!empty($adminProductIds)) {
                                                if ($hasCondition) {
                                                    $subQ->orWhereHas('product', function ($pq) use ($adminProductIds) {
                                                        $pq->whereIn('parent_product_id', $adminProductIds);
                                                     });
                                                } else {
                                                    $subQ->whereHas('product', function ($pq) use ($adminProductIds) {
                                                        $pq->whereIn('parent_product_id', $adminProductIds);
                                                     });
                                                }
                                            }
                                        });
                                    });
                            }
                            
                            $headerVendorOrders = $headerVendorOrdersQuery->latest()->limit(5)->get();
                            $headerVendorOrderCount = $headerVendorOrdersQuery->count();

                            $headerTotalCount = $headerPendingCount + $headerPendingProdCount + $headerVendorOrderCount;
                        @endphp
                        <a href="#" class="nav-link user position-relative" id="notificationDropdown" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-bell" style="font-size: 20px; color: #ffaa00;"></i>
                            @if($headerTotalCount > 0)
                                <span class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle" style="font-size: 0.65rem;">{{ $headerTotalCount }}</span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2 p-0" style="min-width: 340px;">
                            <div class="p-3 bg-light border-bottom d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold mb-0 text-dark small"><i class="fas fa-bell text-warning me-1"></i> Admin Notifications</h6>
                                <span class="badge bg-warning text-dark rounded-pill">{{ $headerTotalCount }} Pending</span>
                            </div>
                            <div class="list-group list-group-flush" style="max-height: 320px; overflow-y: auto;">
                                <!-- Product Approvals -->
                                @foreach($headerPendingProducts as $nProd)
                                    <a href="{{ route('admin.vendor-products.show', $nProd->id) }}" class="list-group-item list-group-item-action p-3 border-bottom" style="background: #fffdf5;">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fw-bold text-dark small"><i class="fas fa-box text-primary me-1"></i> {{ $nProd->vendor->name ?? 'Vendor' }}</span>
                                            <span class="badge bg-warning text-dark font-weight-bold" style="font-size: 0.7rem;">Product Approval</span>
                                        </div>
                                        <div class="small text-dark fw-semibold mb-1">
                                            Requested approval for: <strong>{{ Str::limit($nProd->title, 28) }}</strong>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            <i class="fas fa-clock me-1"></i> {{ $nProd->created_at ? $nProd->created_at->diffForHumans() : 'Just now' }}
                                        </div>
                                    </a>
                                @endforeach

                                <!-- Payment Recharges -->
                                @foreach($headerPendingPayments as $nTrx)
                                    <a href="{{ route('admin.vendor-payments.index', ['status' => 'pending']) }}" class="list-group-item list-group-item-action p-3 border-bottom">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fw-bold text-dark small"><i class="fas fa-wallet text-warning me-1"></i> {{ $nTrx->vendor->name ?? 'Vendor' }}</span>
                                            <span class="badge bg-success bg-opacity-10 text-success fw-bold">৳{{ number_format($nTrx->amount, 2) }}</span>
                                        </div>
                                        <div class="small text-muted mb-1">
                                            Requested recharge via <strong>{{ $nTrx->payment_method ?? 'Gateway' }}</strong>
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            <i class="fas fa-clock me-1"></i> {{ $nTrx->created_at->diffForHumans() }}
                                        </div>
                                    </a>
                                @endforeach

                                <!-- Vendor Orders -->
                                @foreach($headerVendorOrders as $nVOrder)
                                    <a href="{{ route('admin.vendor-orders.index') }}" class="list-group-item list-group-item-action p-3 border-bottom" style="background: #f0fdf4;">
                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                            <span class="fw-bold text-dark small"><i class="fas fa-shopping-bag text-success me-1"></i> #{{ $nVOrder->invoice_no ?? $nVOrder->order_number ?? $nVOrder->id }}</span>
                                            <span class="badge bg-success text-white font-weight-bold" style="font-size: 0.68rem;">Vendor Order</span>
                                        </div>
                                        <div class="small text-dark fw-semibold mb-1">
                                            New vendor order from: <strong>{{ $nVOrder->name ?? 'Customer' }}</strong> — ৳{{ number_format($nVOrder->total, 2) }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            <i class="fas fa-clock me-1"></i> {{ $nVOrder->created_at ? $nVOrder->created_at->diffForHumans() : 'Just now' }}
                                        </div>
                                    </a>
                                @endforeach

                                @if($headerTotalCount === 0)
                                    <div class="p-4 text-center text-muted small">
                                        <i class="fas fa-bell-slash fs-4 d-block mb-1 opacity-50"></i>
                                        No pending approval, payment, or order requests.
                                    </div>
                                @endif
                            </div>
                            <div class="p-2 text-center bg-light border-top d-flex justify-content-around">
                                <a href="{{ route('admin.vendor-orders.index') }}" class="text-success fw-bold text-decoration-none" style="font-size: 0.75rem;">Vendor Orders ({{ $headerVendorOrderCount }}) &rarr;</a>
                                <a href="{{ route('admin.vendor-products.index') }}" class="text-primary fw-bold text-decoration-none" style="font-size: 0.75rem;">Approvals ({{ $headerPendingProdCount }}) &rarr;</a>
                                <a href="{{ route('admin.vendor-payments.index') }}" class="text-primary fw-bold text-decoration-none" style="font-size: 0.75rem;">Payments ({{ $headerPendingCount }}) &rarr;</a>
                            </div>
                        </div>
                    </li>
                    {{-- <li class="nav-item dropdown user-profile-dropdown">
                        <a href="" class="nav-link user" id="notify" data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/img/email.png') }}" alt="" class="icon">
                    <p class="count">4</p>
                    </a>
                    <div class="dropdown-menu mail">
                        <div class="dp-main-menu">

                            <div class="email-info">
                                <a href="" class="email-item">
                                    <img src="{{ asset('profile.svg') }}" alt="" class="user-email">
                                    <div class="note-info-email">
                                        <h2 class="name">Tawfiq Khan</h2>
                                        <p class="role">Super admin</p>
                                    </div>
                                </a>
                            </div>
                            <div class="email-info">
                                <a href="" class="email-item">
                                    <img src="{{ asset('assets/img/man.png') }}" alt=""
                                        class="user-email">
                                    <div class="note-info-email">
                                        <h2 class="name">Tawfiq Khan</h2>
                                        <p class="role">Super admin</p>
                                    </div>
                                </a>
                            </div>
                            <div class="email-info">
                                <a href="" class="email-item">
                                    <img src="{{ asset('assets/img/man.png') }}" alt=""
                                        class="user-email">
                                    <div class="note-info-email">
                                        <h2 class="name">Tawfiq Khan</h2>
                                        <p class="role">Super admin</p>
                                    </div>
                                </a>
                            </div>

                        </div>
                    </div>
                    </li> --}}

                    <li class="nav-item dropdown user-profile-dropdown">
                        <a href="#" class="nav-link user" id="profileDropdown" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-user" style="font-size: 20px; color: #ff3366;"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end usr">
                            <div class="premium-user-header d-flex align-items-center gap-3">
                                <img src="{{ asset('assets/img/man.png') }}" alt="" class="premium-user-img">
                                <div class="premium-user-info-text text-start">
                                    <h2 class="premium-user-name">{{ Auth::user()->name ?? 'Admin User' }}</h2>
                                    <p class="premium-user-email">{{ Auth::user()->email ?? 'admin@purnobd.com' }}</p>
                                    <span class="premium-user-role">
                                        @if(Auth::user()?->hasRole('super_admin') || Auth::user()?->hasRole('super admin'))
                                            Super Admin
                                        @elseif(Auth::user()?->hasRole('admin') || Auth::user()?->role === 'admin')
                                            Admin
                                        @else
                                            {{ ucfirst(Auth::user()?->roles->first()?->name ?? Auth::user()?->role ?? 'User') }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="premium-user-links">
                                <a href="{{ route('admin.profile') }}" class="premium-user-link">
                                    <div class="premium-link-icon-container icon-profile">
                                        <i class="fa-regular fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="premium-link-title">Account Profile</div>
                                        <div class="premium-link-desc">Manage your personal settings</div>
                                    </div>
                                </a>
                                <a href="{{ url('admin/config') }}" class="premium-user-link">
                                    <div class="premium-link-icon-container icon-settings">
                                        <i class="fa-solid fa-sliders"></i>
                                    </div>
                                    <div>
                                        <div class="premium-link-title">System Settings</div>
                                        <div class="premium-link-desc">Configure store properties</div>
                                    </div>
                                </a>
                                <div class="dropdown-divider my-2"></div>
                                <a href="{{ route('logout') }}" class="premium-user-link premium-user-link-logout"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <div class="premium-link-icon-container icon-logout">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    </div>
                                    <div>
                                        <div class="premium-link-title text-danger">Logout Session</div>
                                        <div class="premium-link-desc text-muted">End active session securely</div>
                                    </div>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>

                    </li>
                    <li class="nav-item dropdown user-profile-dropdown">
                        <a href="{{ url('admin/config') }}" class="nav-link user" id="settingsDropdown">
                            <i class="fa-solid fa-gear" style="font-size: 20px; color: #007bff;"></i>
                        </a>
                    </li>

                </ul>

            </header>
        </div>
        <!-- -navbar-end -->

        <!-- --sidebar-start-- -->

        @php
        $coreShopActive = request()->is('admin/product*') || request()->is('admin/catalog-groups*') || request()->is('admin/catalog-tiers*') || request()->is('admin/catalog-levels*') || request()->is('admin/publishers-mark*') || request()->is('admin/stock-control*') || request()->is('admin/promo-pages*') || request()->is('admin/content-authors*') || request()->is('admin/content-publishers*') || request()->is('admin/feedback*') || request()->is('admin/bundle-deals*') || request()->is('admin/catalog*');
        $ordersSalesActive = request()->is('admin/transactions*') || request()->is('admin/my-assignments*') || request()->is('admin/vendor-orders*') || request()->is('admin/reseller-orders*') || request()->is('admin/pending-queue*') || request()->is('admin/pos*');
        $shippingDeliveryActive = request()->is('admin/shipping-basics*') || request()->is('admin/delivery-zones/rules*') || request()->is('admin/courier-connect*');
        $reportsAnalyticsActive = request()->routeIs('admin.orders.reports*') || request()->routeIs('admin.customers.reports*');
        $integrationsSyncActive = request()->is('admin/daraz*') || request()->is('admin/import-woo*') || request()->routeIs('admin.telegram-settings.*') || request()->is('admin/event-queue*');
        $securityTrustActive = request()->is('admin/trust-scanner*') || request()->is('admin/trust-shield*') || request()->is('admin/snapshots*');
        $contentPagesActive = request()->is('admin/hero-banners*') || request()->is('admin/site-pages*') || request()->is('admin/nav-builder*') || request()->is('admin/articles*') || request()->is('admin/article-topics*') || request()->is('admin/article-subtopics*') || request()->is('admin/comments*');
        $vendorsActive = request()->is('admin/vendors*') || request()->is('admin/vendor-products*') || request()->is('admin/vendor-withdrawals*') || request()->is('admin/vendor-payments*') || request()->is('admin/vendor-settings*') || request()->is('admin/partners*') || request()->is('admin/partner-items*') || request()->is('admin/partner-payouts*') || request()->is('admin/partner-config*');
        $controlSystemActive = request()->is('admin/team-members*') || request()->routeIs('admin.roles_permissions.*') || request()->is('admin/extensions*') || request()->is('admin/config*') || request()->is('admin/social-links*') || request()->is('admin/inquiries*') || request()->routeIs('admin.subscriptions.index');
        @endphp

        <style>
             .left-menu-dp {
                 display: none;
             }
            .left-menu {
                overflow-y: auto !important;
                top: 65px !important;
                height: calc(100vh - 65px) !important;
                padding-bottom: 20px !important;
                z-index: 1050 !important;
            }
            .sidebar-footer {
                display: none !important;
            }
            .menu-section-toggle {
                padding: 10px 18px !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                font-size: 11px !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                color: #5a6a85 !important;
                letter-spacing: 0.8px !important;
                cursor: pointer !important;
                background: #fdfdfd !important;
                border-bottom: 1px solid #f1f3f7 !important;
                transition: background 0.2s, color 0.2s;
            }
            .menu-section-toggle:hover {
                background-color: #f6f8fb !important;
                color: #2b6cb0 !important;
            }
            .menu-section-list {
                border-left: 2px solid #e2e8f0;
                margin-left: 10px !important;
                padding-left: 5px !important;
            }
            .section-caret {
                font-size: 9px;
                transition: transform 0.2s ease;
            }
            .menu-section.collapsed .section-caret {
                transform: rotate(0deg);
            }
            .menu-section.expanded .section-caret {
                transform: rotate(90deg);
            }

            /* Premium Sidebar Sub-menu Redesign */
            ul#sidebar .sub-menu ul {
                border-left: 2px solid #e2e8f0 !important;
                margin-left: 25px !important;
                padding-left: 6px !important;
                margin-top: 4px !important;
                margin-bottom: 4px !important;
            }
            ul#sidebar .sub-menu ul li {
                margin-bottom: 2px !important;
                border-left: none !important;
            }
            ul#sidebar .sub-menu ul li:hover {
                border-left: none !important;
            }
            ul#sidebar .sub-menu ul li a {
                padding: 6px 12px !important;
                border-radius: 8px !important;
                transition: all 0.2s ease-in-out !important;
                color: #4a5568 !important;
                font-weight: 500 !important;
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                background: transparent !important;
            }
            ul#sidebar .sub-menu ul li a:hover {
                background: #f1f5f9 !important;
                color: #1a56db !important;
                /* Removed translateX transform - it shifts elements under cursor causing hover flicker */
            }
            ul#sidebar .sub-menu ul li.active a {
                background: #eff6ff !important;
                color: #1a56db !important;
                font-weight: 600 !important;
            }
            ul#sidebar .sub-menu ul li a i {
                font-size: 13px !important;
                width: 16px !important;
                text-align: center !important;
                opacity: 0.8 !important;
            }
        </style>

        <div class="left-menu">
            <div class="menubar-content">
                <nav class="animated bounceInDown">
                    <ul id="sidebar">
                        @can('dashboard.view')
                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <span class="menu-content">
                                    <i class="fas fa-chart-pie" style="color:#3b82f6;"></i>
                                    Dashboard
                                </span>
                            </a>
                        </li>
                        @endcan

                        <li class="{{ request()->routeIs('chats.index') ? 'active' : '' }}">
                            <a href="{{ route('chats.index') }}">
                                <span class="menu-content">
                                    <i class="fas fa-comments" style="color:#10b981;"></i>
                                    Chats
                                </span>
                            </a>
                        </li>



                        <!-- CORE SHOP SECTION -->
                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->canAny(['products.view', 'inventory.view', 'landing_pages.view', 'product_categories.view', 'brands.view', 'writers.view', 'publishers.view', 'reviews.view', 'combo_offers.view']))
                        <li class="menu-section {{ $coreShopActive ? 'expanded' : 'collapsed' }}">
                            <a class="menu-section-toggle">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-cubes" style="color: #4f46e5; font-size: 13px;"></i>
                                    Product Catalog
                                </span>
                                <i class="fas fa-chevron-right section-caret"></i>
                            </a>
                            <ul class="left-menu-dp menu-section-list" style="{{ $coreShopActive ? 'display: block;' : 'display: none;' }}">
                                @can('products.view')
                                <li
                                    class="sub-menu {{ request()->is('admin/product*') || request()->is('admin/product_categories*') || request()->is('admin/sub-categories*') || request()->is('admin/third-categories*') || request()->is('admin/publishers-mark*') || request()->is('admin/content-authors*') || request()->is('admin/content-publishers*') || request()->is('admin/feedback*') || request()->is('admin/combo_offers*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-boxes-stacked" style="color:#197A94;"></i>
                                            Product & Others
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/catalog*') || request()->is('admin/categories*') || request()->is('admin/sub-categories*') || request()->is('admin/third-categories*') || request()->is('admin/publishers-mark*') || request()->is('admin/content-authors*') || request()->is('admin/content-publishers*') || request()->is('admin/feedback*') || request()->is('admin/combo_offers*') ? 'display: block;' : '' }}">
                                        <li class="{{ request()->routeIs('admin.items.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.items.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-tags"></i>
                                                    All Products
                                                </span>
                                            </a>
                                        </li>
                                        @can('products.create')
                                        <li class="{{ request()->routeIs('admin.items.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.items.create') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-square-plus"></i>
                                                    Add Product
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('product_categories.view')
                                        <li
                                            class="{{ request()->routeIs('admin.product_categories.*') || request()->routeIs('admin.sub-categories.*') || request()->routeIs('admin.third-categories.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.product_categories.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-sitemap"></i>
                                                    Categories
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('brands.view')
                                        <li class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.brands.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-copyright"></i>
                                                    Brands
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('writers.view')
                                        <li class="{{ request()->is('admin/content-authors*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.writers.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-pen-fancy"></i>
                                                    Writers
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('publishers.view')
                                        <li class="{{ request()->is('admin/content-publishers*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.publishers.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-book-open"></i>
                                                    Publishers
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('reviews.view')
                                        <li class="{{ request()->is('admin/feedback*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.reviews.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-star-half-stroke"></i>
                                                    Reviews
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('combo_offers.view')
                                        <li class="{{ request()->routeIs('admin.combo_offers.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.combo_offers.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-gift" style="color:#ff4081;"></i>
                                                    Combo Offers
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
 
                                @can('inventory.view')
                                <li class="sub-menu {{ request()->is('admin/stock-control*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-warehouse" style="color:#795548;"></i>
                                            Inventory & Stock
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/stock-control*') ? 'display: block;' : '' }}">
                                        @can('inventory.view')
                                        <li class="{{ request()->routeIs('admin.inventory.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.inventory.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-dolly nav-icon" style="color:#1d600c;"></i>
                                                    Inventory Overview
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('inventory.low_stock')
                                        <li class="{{ request()->routeIs('admin.inventory.low-stock') ? 'active' : '' }}">
                                            <a href="{{ route('admin.inventory.low-stock') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-triangle-exclamation nav-icon"
                                                        style="color:#1d600c;"></i>
                                                    Low Stock Alert
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('inventory.out_of_stock')
                                        <li class="{{ request()->routeIs('admin.inventory.out-of-stock') ? 'active' : '' }}">
                                            <a href="{{ route('admin.inventory.out-of-stock') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-circle-xmark nav-icon" style="color:#1d600c;"></i>
                                                    Out of Stock
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('inventory.history')
                                        <li class="{{ request()->routeIs('admin.inventory.history') ? 'active' : '' }}">
                                            <a href="{{ route('admin.inventory.history') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-timeline nav-icon" style="color:#1d600c;"></i>
                                                    Stock Movement History
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
 
                                @can('landing_pages.view')
                                <x-license-feature module="landing_page">
                                    <li class="sub-menu {{ request()->is('admin/promo-pages*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-pager" style="color:#ff9800;"></i>
                                                Landing Page
                                                @php
                                                $licenseService = app(\App\Services\LicenseService::class);
                                                $licenseStatus = $licenseService->getLicenseStatus();
                                                @endphp
                                                @if ($licenseStatus['valid'] && isset($licenseStatus['landing_page_remaining']))
                                                <small
                                                    class="badge bg-info ms-1">{{ $licenseStatus['landing_page_remaining'] }}
                                                    left</small>
                                                @endif
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <nav class="left-menu-dp"
                                            style="{{ request()->is('admin/promo-pages*') ? 'display: block;' : '' }}">
                                            @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->can('landing_pages.view'))
                                            <li class="{{ request()->routeIs('admin.landing-pages.index') ? 'active' : '' }}">
                                                <a href="{{ route('admin.landing-pages.index') }}">
                                                    <span class="menu-content">
                                                        <i class="fas fa-list-check nav-icon"></i>
                                                        All Landing Pages
                                                    </span>
                                                </a>
                                            </li>
                                            @endif
                                            @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->can('landing_pages.create'))
                                            <li
                                                class="{{ request()->routeIs('admin.landing-pages.create') ? 'active' : '' }}">
                                                <a href="{{ route('admin.landing-pages.create') }}">
                                                    <span class="menu-content">
                                                        <i class="fas fa-file-circle-plus nav-icon"></i>
                                                        Create Landing Page
                                                    </span>
                                                </a>
                                            </li>
                                            @endif
                                        </nav>
                                    </li>
                                </x-license-feature>
                                @endcan
                            </ul>
                        </li>
                        @endif
 
                        <!-- ORDERS & SALES SECTION -->
                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->canAny(['orders.view', 'orders.asigned', 'incomplete_orders.view', 'orders.create']))
                        <li class="menu-section {{ $ordersSalesActive ? 'expanded' : 'collapsed' }}">
                            <a class="menu-section-toggle">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-cart-shopping" style="color: #0ea5e9; font-size: 13px;"></i>
                                    Sales & Orders
                                </span>
                                <i class="fas fa-chevron-right section-caret"></i>
                            </a>
                            <ul class="left-menu-dp menu-section-list" style="{{ $ordersSalesActive ? 'display: block;' : 'display: none;' }}">
                                @can('orders.view')
                                <li
                                    class="sub-menu {{ request()->is('admin/transactions*') || request()->is('admin/my-assignments*') || request()->is('admin/vendor-orders*') || request()->is('admin/reseller-orders*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-cart-shopping" style="color:#1d600c;"></i>
                                            Orders Management
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                         style="{{ request()->is('admin/transactions*') || request()->is('admin/my-assignments*') || request()->is('admin/vendor-orders*') || request()->is('admin/reseller-orders*') || request()->is('admin/my-assigned-sales*') ? 'display: block;' : '' }}">
                                         @can('orders.view')
                                         <li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                             <a href="{{ route('admin.orders.index') }}">
                                                 <span class="menu-content">
                                                     <i class="fas fa-list-ol" style="color:#1d600c;"></i>
                                                     All Orders
                                                 </span>
                                             </a>
                                         </li>
                                         @endcan
                                         @can('orders.asigned')
                                         <li class="{{ request()->routeIs('admin.asigned.orders') ? 'active' : '' }}">
                                             <a href="{{ route('admin.asigned.orders') }}">
                                                 <span class="menu-content">
                                                     <i class="fas fa-clipboard-user" style="color:#1d600c;"></i>
                                                     My Orders
                                                 </span>
                                             </a>
                                         </li>
                                         @endcan
                                         <li class="{{ request()->routeIs('admin.vendor-orders.*') ? 'active' : '' }}">
                                              <a href="{{ route('admin.vendor-orders.index') }}" class="d-flex align-items-center justify-content-between">
                                                  <span class="menu-content">
                                                      <i class="fas fa-store" style="color:#1d600c;"></i>
                                                      Vendor Orders
                                                  </span>
                                                  @if(($headerVendorOrderCount ?? 0) > 0)
                                                      <span class="badge rounded-pill bg-warning text-dark font-weight-bold ms-auto" style="font-size: 0.7rem; padding: 2px 7px;">{{ $headerVendorOrderCount }}</span>
                                                  @endif
                                              </a>
                                          </li>
                                           <li class="{{ request()->routeIs('admin.reseller-orders.*') ? 'active' : '' }}">
                                               <a href="{{ route('admin.reseller-orders.index') }}" class="d-flex align-items-center justify-content-between">
                                                   <span class="menu-content">
                                                       <i class="fas fa-people-arrows" style="color:#8b5cf6;"></i>
                                                       Reseller Orders
                                                   </span>
                                               </a>
                                           </li>
                                     </ul>
                                </li>
                                @endcan
 
                                @can('incomplete_orders.view')
                                <li class="{{ request()->routeIs('admin.incomplete-orders*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.incomplete-orders.index') }}" class="">
                                        <span class="menu-content">
                                            <i class="fas fa-hourglass-start" style="color:#ff6f61;"></i>
                                            Incomplete Orders
                                        </span>
                                    </a>
                                </li>
                                @endcan
 
                                {{-- POS System --}}
                                @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->can('orders.create'))
                                @if(module_enabled('POS') && Route::has('admin.pos.index'))
                                <x-license-feature module="pos">
                                    <li class="{{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.pos.index') }}">
                                            <span class="menu-content">
                                                <i class="fas fa-cash-register" style="color:#8e44ad;"></i>
                                                Point of Sale (POS)
                                            </span>
                                        </a>
                                    </li>
                                </x-license-feature>
                                @endif
                                @endif
                            </ul>
                        </li>
                        @endif
 
                        <!-- SHIPPING & DELIVERY SECTION -->
                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->canAny(['basic_shipping.view', 'shipping.rules.view', 'delivery.view']))
                        <li class="menu-section {{ $shippingDeliveryActive ? 'expanded' : 'collapsed' }}">
                            <a class="menu-section-toggle">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-truck-fast" style="color: #10b981; font-size: 13px;"></i>
                                    Shipping & Delivery
                                </span>
                                <i class="fas fa-chevron-right section-caret"></i>
                            </a>
                            <ul class="left-menu-dp menu-section-list" style="{{ $shippingDeliveryActive ? 'display: block;' : 'display: none;' }}">
                                {{-- Shipping Settings --}}
                                @if (auth()->user()?->can('basic_shipping.view') || auth()->user()?->can('shipping.rules.view'))
                                <li
                                    class="sub-menu {{ request()->is('admin/shipping-basics*') || request()->is('admin/delivery-zones/rules*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="nav-icon fas fa-truck-fast" style="color:#20c997;"></i>
                                            Shipping Settings
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/shipping-basics*') || request()->is('admin/delivery-zones/rules*') ? 'display: block;' : '' }}">
                                        @can('basic_shipping.view')
                                        <li class="{{ request()->is('admin/shipping-basics*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.basic.shipping.settings.edit') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-earth-americas" style="color:#197A94;"></i>
                                                    Global Shipping
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('shipping.rules.view')
                                        <li class="{{ request()->is('admin/delivery-zones/rules*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.shipping.rules.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-route" style="color:#6f42c1;"></i>
                                                    Advanced Rules
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endif
 
                                @can('delivery.view')
                                <li class="sub-menu {{ request()->is('admin/courier-connect*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="nav-icon fas fa-truck-plane" style="color:#17a2b8;"></i>
                                            Courier Integration
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/courier-connect*') ? 'display: block;' : '' }}">
                                        @can('delivery.view')
                                        <li class="{{ request()->routeIs('admin.delivery.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.delivery.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-plug-circle-bolt nav-icon"></i>
                                                    All Integrations
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('delivery.integrate')
                                        <li class="{{ request()->routeIs('admin.delivery.integration') ? 'active' : '' }}">
                                            <a href="{{ route('admin.delivery.integration') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-circle-plus nav-icon"></i>
                                                    Add/Edit Integration
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
 
                        <!-- REPORTS & ANALYTICS SECTION -->
                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->canAny(['reports.sales.view', 'reports.customers.view']))
                        <li class="menu-section {{ $reportsAnalyticsActive ? 'expanded' : 'collapsed' }}">
                            <a class="menu-section-toggle">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-chart-column" style="color: #f59e0b; font-size: 13px;"></i>
                                    Reports & Insights
                                </span>
                                <i class="fas fa-chevron-right section-caret"></i>
                            </a>
                            <ul class="left-menu-dp menu-section-list" style="{{ $reportsAnalyticsActive ? 'display: block;' : 'display: none;' }}">
                                @can('reports.sales.view')
                                <li class="{{ request()->routeIs('admin.orders.reports*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.orders.reports') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-chart-line" style="color:#197A94;"></i>
                                            Sales Reports
                                        </span>
                                    </a>
                                </li>
                                @endcan
                                @can('reports.customers.view')
                                <li class="{{ request()->routeIs('admin.customers.reports*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.customers.reports') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-user-group" style="color:#197A94;"></i>
                                            Customer Reports
                                        </span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
 
                        <!-- INTEGRATIONS & SYNC SECTION -->
                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || (module_enabled('Daraz') && Route::has('admin.daraz.index') && (auth()->user()?->canAny(['daraz.view', 'admin.daraz.view', 'daraz_sync.view', 'daraz.index', 'daraz']) || (auth()->user()?->permissions && auth()->user()?->permissions->pluck('name')->filter(fn($p) => str_contains(strtolower($p), 'daraz'))->count() > 0))) || auth()->user()?->canAny(['woocommerce_migration.view', 'telegram_settings.view', 'delayed_events.view']))
                        <li class="menu-section {{ $integrationsSyncActive ? 'expanded' : 'collapsed' }}">
                            <a class="menu-section-toggle">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-plug" style="color: #ec4899; font-size: 13px;"></i>
                                    Connected Apps
                                </span>
                                <i class="fas fa-chevron-right section-caret"></i>
                            </a>
                            <ul class="left-menu-dp menu-section-list" style="{{ $integrationsSyncActive ? 'display: block;' : 'display: none;' }}">
                                {{-- Daraz Stock Sync --}}
                                @if(module_enabled('Daraz') && Route::has('admin.daraz.index') && (auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->canAny(['daraz.view', 'admin.daraz.view', 'daraz_sync.view', 'daraz.index', 'daraz']) || (auth()->user()?->permissions && auth()->user()?->permissions->pluck('name')->filter(fn($p) => str_contains(strtolower($p), 'daraz'))->count() > 0)))
                                <li class="sub-menu {{ request()->is('admin/daraz*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-rotate" style="color:#f5af19;"></i>
                                            Daraz Sync
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp" style="{{ request()->is('admin/daraz*') ? 'display: block;' : '' }}">
                                        <li class="{{ request()->routeIs('admin.daraz.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.daraz.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-chart-simple"></i>
                                                    Dashboard
                                                </span>
                                            </a>
                                        </li>
                                         <li class="{{ request()->routeIs('admin.daraz.stores.*') ? 'active' : '' }}">
                                             <a href="{{ route('admin.daraz.stores.index') }}">
                                                 <span class="menu-content">
                                                     <i class="fas fa-store"></i>
                                                     Stores
                                                 </span>
                                             </a>
                                         </li>
                                         <li class="{{ request()->routeIs('admin.daraz.orders.*') ? 'active' : '' }}">
                                             <a href="{{ route('admin.daraz.orders.index') }}">
                                                 <span class="menu-content">
                                                     <i class="fas fa-shopping-bag"></i>
                                                     Daraz Orders
                                                 </span>
                                             </a>
                                         </li>
                                         <li class="{{ request()->routeIs('admin.daraz.mappings.*') ? 'active' : '' }}">
                                             <a href="{{ route('admin.daraz.mappings.index') }}">
                                                 <span class="menu-content">
                                                     <i class="fas fa-arrows-spin"></i>
                                                    Product Mappings
                                                </span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.daraz.sync.logs') ? 'active' : '' }}">
                                            <a href="{{ route('admin.daraz.sync.logs') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-terminal"></i>
                                                    Sync Logs
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endif
 
                                {{-- WooCommerce Migration --}}
                                @can('woocommerce_migration.view')
                                <li class="{{ request()->is('admin/import-woo*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.woocommerce-migration.index') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-arrow-right-arrow-left" style="color:#ff5722;"></i>
                                            WooCommerce Migration
                                        </span>
                                    </a>
                                </li>
                                @endcan
 
                                @can('telegram_settings.view')
                                <li class="{{ request()->routeIs('admin.telegram-settings.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.telegram-settings.index') }}">
                                        <span class="menu-content">
                                            <i class="fab fa-telegram text-info"></i>
                                            Telegram Notifications
                                        </span>
                                    </a>
                                </li>
                                @endcan
 
                                {{-- Delayed Purchase Events --}}
                                @can('delayed_events.view')
                                <li class="sub-menu {{ request()->is('admin/event-queue*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-clock-rotate-left" style="color:#f5576c;"></i>
                                            Delay Purchase Events
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/event-queue*') ? 'display: block;' : '' }}">
                                        <li class="{{ request()->routeIs('admin.delayed-events.settings') ? 'active' : '' }}">
                                            <a href="{{ route('admin.delayed-events.settings') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-sliders nav-icon"></i>
                                                    Settings
                                                </span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.delayed-events.pending') ? 'active' : '' }}">
                                            <a href="{{ route('admin.delayed-events.pending') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-hourglass-start nav-icon"></i>
                                                    Pending Events
                                                </span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.delayed-events.history') ? 'active' : '' }}">
                                            <a href="{{ route('admin.delayed-events.history') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-receipt nav-icon"></i>
                                                    Event History
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
 
                        <!-- SECURITY & TRUST SECTION -->
                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->canAny(['fraud_checker.view', 'fraud_protection.view', 'backup.settings.view']))
                        <li class="menu-section {{ $securityTrustActive ? 'expanded' : 'collapsed' }}">
                            <a class="menu-section-toggle">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-shield-halved" style="color: #ef4444; font-size: 13px;"></i>
                                    Security & Trust
                                </span>
                                <i class="fas fa-chevron-right section-caret"></i>
                            </a>
                            <ul class="left-menu-dp menu-section-list" style="{{ $securityTrustActive ? 'display: block;' : 'display: none;' }}">
                                @can('fraud_checker.view')
                                <li class="sub-menu {{ request()->is('admin/trust-scanner*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-user-secret" style="color:#dc3545;"></i>
                                            Fraud Checker
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/trust-scanner*') ? 'display: block;' : '' }}">
                                        @can('fraud_checker.view')
                                        <li class="{{ request()->routeIs('admin.fraud-checker.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.fraud-checker.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-shield-halved nav-icon"></i>
                                                    Settings
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('fraud_checker.results')
                                        <li class="{{ request()->routeIs('admin.fraud-checker.results') ? 'active' : '' }}">
                                            <a href="{{ route('admin.fraud-checker.results') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-list-check nav-icon"></i>
                                                    All Results
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
 
                                {{-- Fraud Protection --}}
                                @can('fraud_protection.view')
                                <li class="sub-menu {{ request()->is('admin/trust-shield*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="nav-icon fas fa-user-shield text-success"></i>
                                            Fraud Protection
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/trust-shield*') ? 'display: block;' : '' }}">
                                        <li class="{{ request()->routeIs('admin.fraud-protection.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.fraud-protection.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-sliders nav-icon"></i>
                                                    Settings
                                                </span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.fraud-protection.logs') ? 'active' : '' }}">
                                            <a href="{{ route('admin.fraud-protection.logs') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-terminal nav-icon"></i>
                                                    Logs
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endcan
 
                                {{-- Backup System --}}
                                @can('backup.settings.view')
                                <li class="sub-menu {{ request()->is('admin/snapshots*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-database" style="color:#17a2b8;"></i>
                                            Backup System
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/snapshots*') ? 'display: block;' : '' }}">
                                        @can('backup.settings.view')
                                        <li class="{{ request()->routeIs('admin.backup.settings') ? 'active' : '' }}">
                                            <a href="{{ route('admin.backup.settings') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-sliders"></i>
                                                    Settings
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('backup.schedules.view')
                                        <li class="{{ request()->routeIs('admin.backup.schedules.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.backup.schedules.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-calendar-days"></i>
                                                    Schedules
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('backup.history.view')
                                        <li class="{{ request()->routeIs('admin.backup.history.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.backup.history.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-clock-rotate-left"></i>
                                                    Backup History
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
 
                        <!-- CONTENT & PAGES SECTION -->
                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->canAny(['sliders.view', 'pages.view', 'menus.view', 'blog.view']))
                        <li class="menu-section {{ $contentPagesActive ? 'expanded' : 'collapsed' }}">
                            <a class="menu-section-toggle">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-newspaper" style="color: #8b5cf6; font-size: 13px;"></i>
                                    Content & Pages
                                </span>
                                <i class="fas fa-chevron-right section-caret"></i>
                            </a>
                            <ul class="left-menu-dp menu-section-list" style="{{ $contentPagesActive ? 'display: block;' : 'display: none;' }}">
                                @can('sliders.view')
                                <li class="sub-menu {{ request()->is('admin/hero-banners*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-images" style="color:#e83e8c;"></i>
                                            Sliders
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/hero-banners*') ? 'display: block;' : '' }}">
                                        @can('sliders.view')
                                        <li class="{{ request()->routeIs('admin.sliders.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.sliders.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-list-check"></i>
                                                    All Sliders
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('sliders.create')
                                        <li class="{{ request()->routeIs('admin.sliders.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.sliders.create') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-plus"></i>
                                                    Add Slider
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
 
                                @can('pages.view')
                                <li class="sub-menu {{ request()->is('admin/site-pages*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-file-lines" style="color:#28a745;"></i>
                                            Pages
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/site-pages*') ? 'display: block;' : '' }}">
                                        @can('pages.view')
                                        <li class="{{ request()->routeIs('admin.pages.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.pages.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-file-lines"></i>
                                                    All Pages
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('pages.create')
                                        <li class="{{ request()->routeIs('admin.pages.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.pages.create') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-file-circle-plus"></i>
                                                    Create Page
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
 
                                @can('menus.view')
                                <li class="{{ request()->is('admin/nav-builder*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.menus.index') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-bars-staggered" style="color:#197A94;"></i>
                                            Menu Management
                                        </span>
                                    </a>
                                </li>
                                @endcan
 
                                @can('blog.view')
                                <li class="sub-menu {{ request()->is('admin/articles*') || request()->is('admin/article-topics*') || request()->is('admin/comments*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-newspaper" style="color:#f59e0b;"></i>
                                            Blog & Posts
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp" style="{{ request()->is('admin/articles*') || request()->is('admin/article-topics*') || request()->is('admin/comments*') ? 'display: block;' : '' }}">
                                        <li class="{{ request()->routeIs('admin.post.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.post.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-square-rss"></i>
                                                    All Posts
                                                </span>
                                            </a>
                                        </li>
                                        @can('blog.create')
                                        <li class="{{ request()->routeIs('admin.post.add') ? 'active' : '' }}">
                                            <a href="{{ route('admin.post.add') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-pen-nib"></i>
                                                    Create Post
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('categories.view')
                                        <li class="{{ request()->routeIs('admin.category.index') || request()->routeIs('admin.category.add') ? 'active' : '' }}">
                                            <a href="{{ route('admin.category.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-folder-tree"></i>
                                                    Categories
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('comments.view')
                                        <li class="{{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.comments.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-comment-dots"></i>
                                                    Comments
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif
 
                        <!-- VENDORS SECTION -->
                        @if(module_enabled('MultiVendor') && Route::has('admin.vendors.index') && (auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->canAny(['admin.vendors.view', 'admin.vendors.edit', 'admin.products.view-all', 'admin.withdrawals.view', 'admin.commissions.view'])))
                        <li class="menu-section {{ $vendorsActive ? 'expanded' : 'collapsed' }}">
                            <a class="menu-section-toggle">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-store" style="color: #06b6d4; font-size: 13px;"></i>
                                    Vendors
                                </span>
                                <i class="fas fa-chevron-right section-caret"></i>
                            </a>
                            <ul class="left-menu-dp menu-section-list" style="{{ $vendorsActive ? 'display: block;' : 'display: none;' }}">
                                @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->can('admin.vendors.view'))
                                <li
                                    class="{{ request()->routeIs('admin.vendors.index') || request()->routeIs('admin.vendors.show') || request()->routeIs('admin.vendors.edit') ? 'active' : '' }}">
                                    <a href="{{ route('admin.vendors.index') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-shop"></i>
                                            All Vendors
                                        </span>
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->can('admin.vendors.edit'))
                                <li class="{{ request()->routeIs('admin.vendors.create') ? 'active' : '' }}">
                                    <a href="{{ route('admin.vendors.create') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-user-plus"></i>
                                            Add Vendor
                                        </span>
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->can('admin.products.view-all'))
                                <li
                                    class="{{ request()->routeIs('admin.vendor-products.index') || request()->routeIs('admin.vendor-products.show') ? 'active' : '' }}">
                                    <a href="{{ route('admin.vendor-products.index') }}">
                                        <span class="menu-content d-flex align-items-center justify-content-between">
                                            <span><i class="fas fa-square-check"></i> Product Approval</span>
                                            @php
                                                $pendingProdCount = \App\Models\Product::whereNotNull('vendor_id')->where('approval_status', 'pending')->count();
                                            @endphp
                                            @if($pendingProdCount > 0)
                                                <span class="badge bg-warning text-dark rounded-pill ms-2 fw-bold" style="font-size: 0.65rem; padding: 2px 6px;">{{ $pendingProdCount }}</span>
                                            @endif
                                        </span>
                                    </a>
                                </li>
                                @endif
                                 @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->can('admin.vendors.view'))
                                 <li class="{{ request()->routeIs('admin.vendor-payments.*') ? 'active' : '' }}">
                                     <a href="{{ route('admin.vendor-payments.index') }}">
                                         <span class="menu-content d-flex align-items-center justify-content-between">
                                             <span><i class="fas fa-wallet text-warning"></i> Vendor Payments</span>
                                             @php
                                                 $pendingPaymentCount = \App\Models\VendorWalletTransaction::where('status', 'pending')->where('type', 'recharge_request')->count();
                                             @endphp
                                             @if($pendingPaymentCount > 0)
                                                 <span class="badge bg-danger rounded-pill ms-2" style="font-size: 0.65rem; padding: 2px 6px;">{{ $pendingPaymentCount }}</span>
                                             @endif
                                         </span>
                                     </a>
                                 </li>
                                 @endif
                                 @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->can('admin.withdrawals.view'))
                                <li
                                    class="{{ request()->routeIs('admin.vendor-withdrawals.index') || request()->routeIs('admin.vendor-withdrawals.show') ? 'active' : '' }}">
                                    <a href="{{ route('admin.vendor-withdrawals.index') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-hand-holding-dollar"></i>
                                            Withdrawal Requests
                                        </span>
                                    </a>
                                </li>
                                @endif
                                @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->can('admin.commissions.view'))
                                <li class="{{ request()->routeIs('admin.vendor-settings.global') ? 'active' : '' }}">
                                    <a href="{{ route('admin.vendor-settings.global') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-sliders"></i>
                                            Global Settings
                                        </span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
 
                        <!-- CONTROL & SYSTEM SECTION -->
                        @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin') || auth()->user()?->canAny(['users.view', 'contacts.view', 'subscriptions.view', 'roles.manage', 'roles_permissions.view', 'settings.view']))
                        <li class="menu-section {{ $controlSystemActive ? 'expanded' : 'collapsed' }}">
                            <a class="menu-section-toggle">
                                <span style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fas fa-gears" style="color: #64748b; font-size: 13px;"></i>
                                    System Settings
                                </span>
                                <i class="fas fa-chevron-right section-caret"></i>
                            </a>
                            <ul class="left-menu-dp menu-section-list" style="{{ $controlSystemActive ? 'display: block;' : 'display: none;' }}">
                                <li class="{{ request()->is('admin/team-members*') && request()->get('view') === 'packages' ? 'active' : '' }}">
                                     <a href="{{ route('admin.users', ['view' => 'packages']) }}">
                                         <span class="menu-content">
                                             <i class="fas fa-boxes-packing" style="color:#10b981;"></i>
                                             @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin'))
                                                 SaaS Packages & Pricing Config
                                             @else
                                                 My Subscription & Packages
                                             @endif
                                         </span>
                                     </a>
                                 </li>
                                 @can('users.view')
                                 <li class="{{ request()->is('admin/team-members*') && request()->get('view') !== 'packages' ? 'active' : '' }}">
                                     <a href="{{ route('admin.users') }}">
                                         <span class="menu-content">
                                             <i class="fas fa-user-gear" style="color:#20c997;"></i>
                                             Customers & Users
                                         </span>
                                     </a>
                                 </li>
                                 @endcan
 
                                @can('contacts.view')
                                <li class="sub-menu {{ request()->is('admin/inquiries*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-envelope-open-text" style="color:#6610f2;"></i>
                                            Contact Messages
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/inquiries*') ? 'display: block;' : '' }}">
                                        @can('contacts.view')
                                        <li class="{{ request()->routeIs('admin.contacts.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.contacts.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-inbox"></i>
                                                    All Messages
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('contacts.unread')
                                        <li class="{{ request()->routeIs('admin.contacts.unread') ? 'active' : '' }}">
                                            <a href="{{ route('admin.contacts.unread') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-envelope-circle-check"></i>
                                                    Unread Messages
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
 
                                @can('subscriptions.view')
                                <li class="{{ request()->routeIs('admin.subscriptions.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.subscriptions.index') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-bell" style="color:#ffca28;"></i>
                                            News Subscription
                                        </span>
                                    </a>
                                </li>
                                @endcan
 
                                @canany(['roles_permissions.view', 'roles.manage', 'permissions.manage'])
                                <li class="{{ request()->routeIs('admin.roles_permissions.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.roles_permissions.index') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-user-shield" style="color:#ff00a6;"></i>
                                            Roles & Permissions
                                        </span>
                                    </a>
                                </li>
                                @endcanany
 
                                {{-- Modules & Tools --}}
                                <li class="{{ request()->is('admin/extensions*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.modules.index') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-puzzle-piece" style="color:#9c27b0;"></i>
                                            Modules & Tools
                                        </span>
                                    </a>
                                </li>
 
                                @can('settings.view')
                                <li
                                    class="sub-menu {{ request()->is('admin/config*') || request()->is('admin/socials*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-gears"></i>
                                            Settings
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/config*') || request()->is('admin/socials*') ? 'display: block;' : '' }}">
                                        @can('settings.view')
                                        @can('settings.update')
                                        <li class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.settings.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-sliders"></i>
                                                    All Website Settings
                                                </span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.settings.payment-gateway') ? 'active' : '' }}">
                                            <a href="{{ route('admin.settings.payment-gateway') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-wallet"></i>
                                                    Payment Gateway
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
 
                                        {{-- License Management --}}
                                        <li class="{{ request()->routeIs('admin.verification.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.verification.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-key"></i>
                                                    License Management
                                                    @php
                                                    $licenseService = app(\App\Services\LicenseService::class);
                                                    $licenseStatus = $licenseService->getLicenseStatus();
                                                    $supportStatus = $licenseService->getSupportStatus();
                                                    $updateStatus = $licenseService->getUpdateStatus();
                                                    @endphp
                                                    @if (!$licenseStatus['valid'])
                                                    <i class="fas fa-exclamation-triangle text-warning ms-1"
                                                        title="License Issue"></i>
                                                    @elseif(($supportStatus['status'] ?? '') === 'expired')
                                                    <i class="fas fa-exclamation-triangle text-danger ms-1"
                                                        title="Support Expired"></i>
                                                    @elseif(($updateStatus['status'] ?? '') === 'expired')
                                                    <i class="fas fa-exclamation-triangle text-warning ms-1"
                                                        title="Updates Expired"></i>
                                                    @elseif($licenseStatus['needs_sync'])
                                                    <i class="fas fa-sync text-info ms-1" title="Needs Sync"></i>
                                                    @endif
                                                </span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.updates.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.updates.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-rotate"></i>
                                                    System Updates
                                                    @if(($updateStatus['status'] ?? '') === 'expired')
                                                    <i class="fas fa-exclamation-triangle text-danger ms-1"
                                                        title="Updates Expired"></i>
                                                    @elseif(($updateStatus['status'] ?? '') === 'pending')
                                                    <i class="fas fa-clock text-warning ms-1" title="Pending updates"></i>
                                                    @endif
                                                </span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endif

                    </ul>
                </nav>
            </div>
            <!-- Sidebar Brand & Logout Footer -->
            <div class="sidebar-footer">
                <div class="sidebar-brand-block text-center py-2 rounded-3 text-white" style="background: var(--main-gradient); font-weight: 600; font-size: 16px; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);">
                    <a href="{{ url('/') }}" target="_blank" class="text-white text-decoration-none d-block w-100">{{ setting('general', 'site_name', 'Thikana.shop') }}</a>
                </div>
                <a href="{{ route('logout') }}" class="btn btn-outline-danger w-100 rounded-pill d-flex align-items-center justify-content-center gap-2" style="font-weight: 600; font-size: 14px;" onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout Session
                </a>
                <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <!-- --sidebar-end-- -->

        <!-- --Main wrapper-start-- -->
        <div class="content-wrapper">

            @yield('content')

        </div>
        <!-- --Main wrapper-end-- -->


    </div>

    <style>
        .fl-wrapper {
            z-index: 1000000000 !important;
        }
    </style>


    <!-- ----------------------------js---------------------------------------- -->

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}?v={{ filemtime(public_path('assets/js/bootstrap/bootstrap.bundle.min.js')) }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('js/combo-offer.js') }}"></script>
    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bulletproof vanilla JS sidebar toggle to prevent clashes with multiple jQuery instances
            const vanillaToggleBtn = document.getElementById('toggleSidebar');
            const vanillaToggleMobileBtn = document.getElementById('toggleSidebarMobile');
            function toggleSidebarHandler(e) {
                e.preventDefault();
                const leftMenu = document.querySelector('.left-menu');
                const contentWrapper = document.querySelector('.content-wrapper');
                if (leftMenu) leftMenu.classList.toggle('hide');
                if (contentWrapper) contentWrapper.classList.toggle('hide');
            }
            if (vanillaToggleBtn) vanillaToggleBtn.addEventListener('click', toggleSidebarHandler);
            if (vanillaToggleMobileBtn) vanillaToggleMobileBtn.addEventListener('click', toggleSidebarHandler);

            // Toggle sections
            const sectionToggles = document.querySelectorAll('.menu-section-toggle');
            sectionToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const content = this.nextElementSibling;

                    if (content.style.display === 'block' || parent.classList.contains('expanded')) {
                        content.style.display = 'none';
                        parent.classList.remove('expanded');
                        parent.classList.add('collapsed');
                    } else {
                        content.style.display = 'block';
                        parent.classList.remove('collapsed');
                        parent.classList.add('expanded');
                    }
                });
            });

            // Toggle submenu on click
            const subMenus = document.querySelectorAll('.sub-menu > a');

            subMenus.forEach(menu => {
                menu.addEventListener('click', function(e) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const subMenu = this.nextElementSibling;

                    // Toggle display unconditionally on click
                    const isCurrentlyOpen = (subMenu.style.display === 'block' || (window.getComputedStyle(subMenu).display === 'block'));
                    if (isCurrentlyOpen) {
                        subMenu.style.display = 'none';
                        parent.classList.remove('active');
                    } else {
                        subMenu.style.display = 'block';
                        parent.classList.add('active');
                    }
                });
            });

            // Keep submenus open for active items
            const activeSubMenus = document.querySelectorAll('.sub-menu.active');
            activeSubMenus.forEach(menu => {
                const subMenu = menu.querySelector('.left-menu-dp');
                if (subMenu) {
                    subMenu.style.display = 'block';
                }
            });
        });
    </script>


    <!-- SweetAlert2 Global Interceptor -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Override global window.alert
            window.alert = function(message) {
                Swal.fire({
                    text: message,
                    icon: 'info',
                    confirmButtonColor: '#4f46e5',
                    customClass: {
                        popup: 'premium-swal-popup'
                    }
                });
            };

            // Intercept all submit events that contain inline confirm(...)
            document.addEventListener('submit', function(e) {
                let target = e.target;
                let onsubmitAttr = target.getAttribute('onsubmit');
                if (!onsubmitAttr || !onsubmitAttr.includes('confirm(')) return;
                
                if (target.dataset.swalConfirmed === 'true') {
                    delete target.dataset.swalConfirmed;
                    return;
                }
                
                e.preventDefault();
                e.stopImmediatePropagation();
                
                let message = "Are you sure you want to proceed?";
                let match = onsubmitAttr.match(/confirm\s*\(\s*['"`](.*?)['"`]\s*\)/);
                if (match && match[1]) {
                    message = match[1];
                }
                
                Swal.fire({
                    title: 'Confirmation Required',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'premium-swal-popup'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        target.dataset.swalConfirmed = 'true';
                        target.submit();
                    }
                });
            }, true);

            // Intercept all click events that contain inline confirm(...)
            document.addEventListener('click', function(e) {
                let target = e.target.closest('[onclick*="confirm("]');
                if (!target) return;
                
                if (target.dataset.swalConfirmed === 'true') {
                    delete target.dataset.swalConfirmed;
                    return;
                }
                
                e.preventDefault();
                e.stopImmediatePropagation();
                
                let onclickAttr = target.getAttribute('onclick');
                let message = "Are you sure you want to proceed?";
                let match = onclickAttr.match(/confirm\s*\(\s*['"`](.*?)['"`]\s*\)/);
                if (match && match[1]) {
                    message = match[1];
                }
                
                Swal.fire({
                    title: 'Confirmation Required',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'premium-swal-popup'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        target.dataset.swalConfirmed = 'true';
                        target.click();
                    }
                });
            }, true);
        });
    </script>
    <style>
        .premium-swal-popup {
            font-family: 'Outfit', sans-serif !important;
            border-radius: 16px !important;
        }

        /* Premium Global Toast / Notification Styling (PHP Flasher & Toastr) */
        .fl-wrapper,
        #toast-container {
            position: fixed !important;
            top: 24px !important;
            right: 24px !important;
            left: auto !important;
            bottom: auto !important;
            width: 400px !important;
            max-width: calc(100vw - 32px) !important;
            z-index: 999999 !important;
            justify-content: flex-end !important;
            pointer-events: none !important;
        }

        .fl-wrapper[data-position],
        .fl-wrapper[data-position^=top-],
        .fl-wrapper[data-position^=bottom-] {
            top: 24px !important;
            right: 24px !important;
            left: auto !important;
            bottom: auto !important;
        }

        .fl-wrapper .fl-container,
        #toast-container > div {
            pointer-events: auto !important;
            width: 100% !important;
            margin-bottom: 12px !important;
        }

        .fl-wrapper .fl-flasher,
        #toast-container > div {
            width: 100% !important;
            max-width: 100% !important;
            background: #ffffff !important;
            border-radius: 16px !important;
            box-shadow: 0 16px 36px -6px rgba(15, 23, 42, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.05) !important;
            padding: 16px 20px !important;
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            overflow: hidden !important;
            position: relative !important;
            border: none !important;
            opacity: 1 !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        /* Success Theme */
        .fl-wrapper .fl-flasher.fl-success,
        #toast-container > .toast-success {
            border-left: 6px solid #10b981 !important;
            background: linear-gradient(135deg, #ffffff 80%, #f0fdf4 100%) !important;
        }

        /* Error Theme */
        .fl-wrapper .fl-flasher.fl-error,
        #toast-container > .toast-error {
            border-left: 6px solid #ef4444 !important;
            background: linear-gradient(135deg, #ffffff 80%, #fef2f2 100%) !important;
        }

        /* Warning Theme */
        .fl-wrapper .fl-flasher.fl-warning,
        #toast-container > .toast-warning {
            border-left: 6px solid #f59e0b !important;
            background: linear-gradient(135deg, #ffffff 80%, #fffbeb 100%) !important;
        }

        /* Info Theme */
        .fl-wrapper .fl-flasher.fl-info,
        #toast-container > .toast-info {
            border-left: 6px solid #3b82f6 !important;
            background: linear-gradient(135deg, #ffffff 80%, #eff6ff 100%) !important;
        }

        /* Content flex structure */
        .fl-wrapper .fl-flasher .fl-content {
            display: flex !important;
            align-items: center !important;
            gap: 16px !important;
            padding-right: 24px !important;
        }

        /* Icon Styling */
        .fl-wrapper .fl-flasher .fl-icon {
            font-size: 28px !important;
            width: 1.2em !important;
            height: 1.2em !important;
            min-width: 1.2em !important;
            min-height: 1.2em !important;
            border-radius: 50% !important;
            display: inline-block !important;
            position: relative !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25) !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
        }

        /* Typography - Bigger & Modern */
        .fl-wrapper .fl-flasher .fl-title,
        #toast-container .toast-title {
            font-size: 1.05rem !important;
            font-weight: 700 !important;
            display: block !important;
            margin-bottom: 2px !important;
            line-height: 1.3 !important;
            letter-spacing: -0.01em !important;
        }

        .fl-wrapper .fl-flasher.fl-success .fl-title, #toast-container > .toast-success .toast-title { color: #059669 !important; }
        .fl-wrapper .fl-flasher.fl-error .fl-title, #toast-container > .toast-error .toast-title { color: #dc2626 !important; }
        .fl-wrapper .fl-flasher.fl-warning .fl-title, #toast-container > .toast-warning .toast-title { color: #d97706 !important; }
        .fl-wrapper .fl-flasher.fl-info .fl-title, #toast-container > .toast-info .toast-title { color: #2563eb !important; }

        .fl-wrapper .fl-flasher .fl-message,
        #toast-container .toast-message {
            font-size: 0.95rem !important;
            color: #475569 !important;
            font-weight: 500 !important;
            line-height: 1.4 !important;
            display: block !important;
        }

        /* Close button */
        .fl-wrapper .fl-flasher .fl-close,
        #toast-container .toast-close-button {
            font-size: 1.5rem !important;
            color: #94a3b8 !important;
            opacity: 0.7 !important;
            top: 10px !important;
            right: 14px !important;
            position: absolute !important;
            background: transparent !important;
            border: none !important;
            cursor: pointer !important;
            line-height: 1 !important;
            transition: all 0.2s !important;
        }

        .fl-wrapper .fl-flasher .fl-close:hover,
        #toast-container .toast-close-button:hover {
            color: #0f172a !important;
            opacity: 1 !important;
            transform: scale(1.1);
        }

        /* Progress Bar */
        .fl-wrapper .fl-flasher .fl-progress-bar {
            height: 4px !important;
            border-radius: 0 0 16px 16px !important;
            overflow: hidden !important;
        }

        /* Mobile styling for notifications (fl-flasher and toastr) */
        @media (max-width: 768px) {
            .fl-wrapper,
            #toast-container {
                top: 10px !important;
                right: 10px !important;
                width: 280px !important;
            }
            .fl-wrapper[data-position],
            .fl-wrapper[data-position^=top-],
            .fl-wrapper[data-position^=bottom-] {
                top: 10px !important;
                right: 10px !important;
            }
            .fl-wrapper .fl-flasher,
            #toast-container > div {
                padding: 10px 12px !important;
                border-radius: 10px !important;
            }
            .fl-wrapper .fl-flasher .fl-icon {
                font-size: 20px !important;
            }
            .fl-wrapper .fl-flasher .fl-content {
                gap: 10px !important;
            }
            .fl-wrapper .fl-flasher .fl-title,
            #toast-container .toast-title {
                font-size: 14px !important;
            }
            .fl-wrapper .fl-flasher .fl-message,
            #toast-container .toast-message {
                font-size: 12px !important;
            }
        }
    </style>

    <!-- Push scripts section -->
    @stack('scripts')

    <script>
    // ── CSRF Token Auto-Refresh ──────────────────────────────────────────────
    // Refreshes the CSRF token silently every 30 minutes so admin forms never
    // expire and throw a 419 Page Expired error.
    (function() {
        function refreshCsrfToken() {
            fetch('/sanctum/csrf-cookie', { credentials: 'same-origin' })
                .then(function() {
                    // After refreshing the cookie, get the new token from Laravel
                    return fetch('/csrf-token-refresh', {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });
                })
                .then(function(res) {
                    if (res.ok) return res.json();
                })
                .then(function(data) {
                    if (data && data.token) {
                        // Update meta tag
                        var metaTag = document.querySelector('meta[name="csrf-token"]');
                        if (metaTag) metaTag.setAttribute('content', data.token);

                        // Update all hidden CSRF inputs in forms
                        document.querySelectorAll('input[name="_token"]').forEach(function(el) {
                            el.value = data.token;
                        });

                        // Update jQuery AJAX default header if jQuery is loaded
                        if (typeof $ !== 'undefined') {
                            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': data.token } });
                        }
                    }
                })
                .catch(function() {
                    // Silently fail - token refresh is best-effort
                });
        }

        // Refresh every 30 minutes
        setInterval(refreshCsrfToken, 30 * 60 * 1000);

        // Intercept 419 responses globally using fetch override
        var originalFetch = window.fetch;
        window.fetch = function() {
            return originalFetch.apply(this, arguments).then(function(response) {
                if (response.status === 419) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Session Expired',
                            text: 'Your session has expired. The page will reload to refresh your token.',
                            confirmButtonText: 'Reload Now',
                            allowOutsideClick: false
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        alert('Your session expired. Please reload and try again.');
                        location.reload();
                    }
                }
                return response;
            });
        };
    })();
    </script>
</body>

</html>
