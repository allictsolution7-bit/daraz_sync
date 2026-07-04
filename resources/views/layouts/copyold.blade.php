<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layets.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/combo-offer.css') }}">
    
    <!-- Push styles section -->
    @stack('styles')
    
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
            background-color: #80bdff;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            color: #fff !important;
            border: 1px solid #80bdff;
            background-color: #80bdff;
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
            background-color: #1E3A8A;
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
            background-color: #4E9BE4;
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
            background-color: #1E3A8A;
            color: #fff !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            color: #fff !important;
            border: 1px solid #1E3A8A;
            background-color: #1E3A8A;
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
            background-color: #1E3A8A;
            color: white;
            border: 1px solid #1E3A8A;
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
            color: #1E3A8A;
            font-weight: bold;
        }

        #sidebar .sub-menu.active>a {
            background-color: rgba(0, 123, 255, 0.1);
            color: #1E3A8A;
        }

        #sidebar .left-menu-dp li.active>a {
            color: #1E3A8A;
            font-weight: bold;
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
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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

        #sidebar > li:first-child {
            margin-top: 0;
        }
    </style>
    @yield('styles')
</head>

<body>


    <div class="main-wrapper">
        <!-- -navbar- -->
        <div class="header-container">
            <header class="header navbar navbar-expand-sm expand-header">
                <div class="header-left d-flex">
                    <div class="logo">
                        <a href="{{ route('admin') }}">{{ setting('general', 'site_name', 'Thikana.shop') }}</a>
                    </div>
                    <a href="#" id="toggleSidebar" class="sidebarCollapse" data-placement="button">
                        <span class="fas fa-bars"></span>
                    </a>
                </div>
                <div class="searchBar">
                    <input type="search" name="search" placeholder="Search..." id="">
                </div>
                <!-- ... existing code ... -->
                <div class="search-bar">
                    <form class="search-form d-flex align-items-center" method="POST" action="#">
                        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
                    </form>
                </div><!-- End Search Bar -->

                <!-- Add the front-end view icon here -->
                <div class="ms-2">
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-sm btn-outline-primary"
                        title="View Website">
                        <i class="bi bi-eye"></i> View Site
                    </a>
                </div>
                <!-- ... existing code ... -->
                <ul class="navbar-item flex-row  align-items-center py-2 ml-auto ">
                    <li class="nav-item dropdown user-profile-dropdown">
                        <a href="" class="nav-link user" id="notify" data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/img/notification.png') }}" alt="" class="icon">
                            {{-- <p class="count">5</p> --}}
                        </a>

                        <div class="dropdown-menu notification">
                            <div class="dp-main-menu">
                                {{-- <a href="" class="dropdown-item message-item">
                                    <img src="{{ asset('assets/img/email.png') }}" alt="" class="user-note">
                                    <div class="note-info-desmis">
                                        <div class="user-notify-info">
                                            <p class="note-name">server reboted</p>
                                            <p class="note-time">20 min ago</p>
                                        </div>
                                        <p class="status-link"><span class="fas fa-times"></span></p>
                                    </div>

                                </a>
                                <a href="" class="dropdown-item message-item">
                                    <img src="{{ asset('assets/img/email.png') }}" alt="" class="user-note">
                                    <div class="note-info-desmis">
                                        <div class="user-notify-info">
                                            <p class="note-name">software server reboted</p>
                                            <p class="note-time">20 min ago</p>
                                        </div>
                                        <p class="status-link"><span class="fas fa-times"></span></p>
                                    </div>

                                </a>
                                <a href="" class="dropdown-item message-item">
                                    <img src="{{ asset('assets/img/email.png') }}" alt="" class="user-note">
                                    <div class="note-info-desmis">
                                        <div class="user-notify-info">
                                            <p class="note-name">server reboted</p>
                                            <p class="note-time">20 min ago</p>
                                        </div>
                                        <p class="status-link"><span class="fas fa-times"></span></p>
                                    </div>

                                </a> --}}
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
                        <a href="" class="nav-link user" id="notify" data-bs-toggle="dropdown">
                            <img src="{{ asset('profile.svg') }}" width="38" alt="" class="icon">
                        </a>

                        <div class="dropdown-menu usr">
                            <div class="dp-main-menu">

                                <div class="user-info">
                                    <div class="user-data">
                                        <img src="{{ asset('assets/img/man.png') }}" alt="" class="user-img">
                                        <div class="mt-3">
                                            <h2>{{ Auth::user()->name ?? '' }}</h2>
                                            <p><small>{{ Auth::user()->email ?? '' }}</small></p>
                                            <p>Super admin</p>
                                        </div>
                                    </div>
                                    <div class="user-link">
                                        <a href="{{ route('admin.profile') }}"><i class="fas fa-user"></i>
                                            Profile</a>
                                        <a href=""><i class="fas fa-envelope"></i> Inbox</a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"><i
                                                class="fas fa-lock-open"></i>

                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                        </a>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </li>
                    <li class="nav-item dropdown user-profile-dropdown">
                        <a href="" class="nav-link user" id="notify" data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/img/settings.png') }}" alt="" class="icon">
                        </a>
                        <div class="dropdown-menu setting">
                            <div class="dp-main-menu">
                                <div class="user-info">
                                    <div class="user-link mt-3">
                                        <a href=""><i class="fas fa-gear"></i> Settings</a>
                                        {{-- <a href=""><i class="fas fa-users"></i> Admin</a>
                                        <a href=""><i class="fas fa-pen"></i> Color</a>
                                        <a href=""><i class="fas fa-moon"></i> Theme</a> --}}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </li>
                    <a href="#" id="toggleSidebarMobile" class="sidebarCollapse" data-placement="button">
                        <span class="fas fa-bars"></span>
                    </a>
                </ul>

            </header>
        </div>
        <!-- -navbar-end -->

        <!-- --sidebar-start-- -->

        <div class="left-menu">
            <div class="menubar-content">
                <nav class="animated bounceInDown">
                    <ul id="sidebar">
                        @can('dashboard.view')
                            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <a href="{{ route('admin.dashboard') }}">
                                    <span class="menu-content">
                                        <i class="fas fa-home"></i>
                                        Dashboard
                                    </span>
                                </a>
                            </li>
                        @endcan

                        @can('products.view')
                            <li
                                class="sub-menu {{ request()->is('admin/product*') || request()->is('admin/product_categories*') || request()->is('admin/sub-categories*') || request()->is('admin/third-categories*') || request()->is('admin/brands*') ? 'active' : '' }}">
                                <a href="#">
                                    <span class="menu-content">
                                        <i class="fas fa-box-open"></i>
                                        Product
                                    </span>
                                    <span class="fas fa-caret-down right"></span>
                                </a>
                                <ul class="left-menu-dp"
                                    style="{{ request()->is('admin/product*') || request()->is('admin/product_categories*') || request()->is('admin/sub-categories*') || request()->is('admin/third-categories*') || request()->is('admin/brands*') ? 'display: block;' : '' }}">
                                    <li class="{{ request()->routeIs('admin.product.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.product.index') }}">
                                            <span class="menu-content">
                                                <i class="fas fa-list"></i>
                                                All Products
                                            </span>
                                        </a>
                                    </li>
                                    @can('products.create')
                                        <li class="{{ request()->routeIs('admin.product.create') ? 'active' : '' }}">
                                            <a href="{{ route('admin.product.create') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-plus-circle"></i>
                                                    Add Product
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('product_categories.view')
                                        <li
                                            class="{{ request()->routeIs('admin.product_categories.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.product_categories.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-tags"></i>
                                                    Categories
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sub_categories.view')
                                        <li class="{{ request()->routeIs('admin.sub-categories.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.sub-categories.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-tags"></i>
                                                    Sub Categories
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('sub_categories.view')
                                        <li class="{{ request()->routeIs('admin.third-categories.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.third-categories.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-tags"></i>
                                                    Third Level Categories
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('brands.view')
                                        <li class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.brands.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-trademark"></i>
                                                    Brands
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('writers.view')
                                        <li>
                                            <a href="{{ route('admin.writers.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-person"></i>
                                                    Writers
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('publishers.view')
                                        <li>
                                            <a href="{{ route('admin.publishers.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-person"></i>
                                                    Publishers
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('reviews.view')
                                        <li>
                                            <a href="{{ route('admin.reviews.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-star"></i>
                                                    Reviews
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    {{-- @can('settings.view')
                                        <li>
                                            <a href="#">
                                                <span class="menu-content">
                                                    <i class="fas fa-cog"></i>
                                                    Settings
                                                </span>
                                            </a>
                                        </li>
                                    @endcan --}}
                                </ul>
                            </li>
                        @endcan

                        @can('inventory.view')
                            <li class="sub-menu {{ request()->is('admin/inventory*') ? 'active' : '' }}">
                                <a href="#">
                                    <span class="menu-content">
                                        <i class="fas fa-warehouse"></i>
                                        Inventory Management
                                    </span>
                                    <span class="fas fa-caret-down right"></span>
                                </a>
                                <ul class="left-menu-dp"
                                    style="{{ request()->is('admin/inventory*') ? 'display: block;' : '' }}">
                                    @can('inventory.view')
                                        <li class="{{ request()->routeIs('admin.inventory.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.inventory.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-boxes nav-icon"></i>
                                                    Inventory Overview
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('inventory.low_stock')
                                        <li class="{{ request()->routeIs('admin.inventory.low-stock') ? 'active' : '' }}">
                                            <a href="{{ route('admin.inventory.low-stock') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-exclamation-triangle nav-icon"></i>
                                                    Low Stock Alert
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('inventory.out_of_stock')
                                        <li class="{{ request()->routeIs('admin.inventory.out-of-stock') ? 'active' : '' }}">
                                            <a href="{{ route('admin.inventory.out-of-stock') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-times-circle nav-icon"></i>
                                                    Out of Stock
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('inventory.history')
                                        <li class="{{ request()->routeIs('admin.inventory.history') ? 'active' : '' }}">
                                            <a href="{{ route('admin.inventory.history') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-history nav-icon"></i>
                                                    Stock Movement History
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        @can('orders.view')
                            <li
                                class="sub-menu {{ request()->is('admin/orders*') || request()->is('admin/asigned*') ? 'active' : '' }}">
                                <a href="#">
                                    <span class="menu-content">
                                        <i class="fas fa-box"></i>
                                        Orders
                                    </span>
                                    <span class="fas fa-caret-down right"></span>
                                </a>
                                <ul class="left-menu-dp"
                                    style="{{ request()->is('admin/orders*') || request()->is('admin/asigned*') ? 'display: block;' : '' }}">
                                    @can('orders.view')
                                        <li class="{{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.orders.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-user-circle"></i>
                                                    All Orders
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('orders.asigned')
                                        <li class="{{ request()->routeIs('admin.asigned.orders') ? 'active' : '' }}">
                                            <a href="{{ route('admin.asigned.orders') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-user-circle"></i>
                                                    My Orders
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        @can('incomplete_orders.view')
                            <li class="{{ request()->routeIs('admin.incomplete-orders*') ? 'active' : '' }}">
                                <a href="{{ route('admin.incomplete-orders.index') }}" class="">
                                    <span class="menu-content">
                                        <i class="fa fa-list"></i>
                                        Incomplete Orders
                                    </span>
                                </a>
                            </li>
                        @endcan

                        {{-- POS System --}}
                        @can('orders.create')
                            <x-license-feature module="pos">
                                <li class="{{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.pos.index') }}" >
                                        <span class="menu-content">
                                            <i class="fas fa-cash-register"></i>
                                            Point of Sale (POS)
                                        </span>
                                    </a>
                                </li>
                            </x-license-feature>
                        @endcan


                        @can('users.view')
                            <li class="sub-menu {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <a href="#">
                                    <span class="menu-content">
                                        <i class="fas fa-user"></i>
                                        Users
                                    </span>
                                    <span class="fas fa-caret-down right"></span>
                                </a>
                                <ul class="left-menu-dp"
                                    style="{{ request()->is('admin/users*') ? 'display: block;' : '' }}">
                                    @can('users.view')
                                        <li class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                                            <a href="{{ route('admin.users') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-user-circle"></i>
                                                    All Users
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('users.create')
                                        <li>
                                            <a href="#">
                                                <span class="menu-content">
                                                    <i class="fas fa-fingerprint"></i>
                                                    Security &amp; Privacy
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <span class="menu-content">
                                                    <i class="fas fa-key"></i>
                                                    Password
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('users.notification')
                                        <li>
                                            <a href="#">
                                                <span class="menu-content">
                                                    <i class="fas fa-bell"></i>
                                                    Notification
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        {{-- Advanced shipping (commented) --}}
                        {{-- <li class="sub-menu {{ request()->is('admin/cities*') || request()->is('admin/shipping*') ? 'active' : '' }}">
                            <a href="#" class="">
                                <span class="menu-content">
                                    <i class="nav-icon fas fa-shipping-fast"></i>
                                    Shipping
                                </span>
                                <span class="fas fa-caret-down right"></span>
                            </a>
                            <ul class="left-menu-dp" style="{{ request()->is('admin/cities*') || request()->is('admin/shipping*') ? 'display: block;' : '' }}">
                                <li class="nav-item {{ request()->routeIs('admin.cities.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.cities.index') }}" class="nav-link">
                                        <span class="menu-content">
                                            <i class="far fa-circle nav-icon"></i>
                                            Shipping City
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('admin.shipping.zones.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.shipping.zones.index') }}" class="nav-link">
                                        <span class="menu-content">
                                            <i class="far fa-circle nav-icon"></i>
                                            Shipping Zones
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('admin.shipping.rules.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.shipping.rules.index') }}" class="nav-link">
                                        <span class="menu-content">
                                            <i class="far fa-circle nav-icon"></i>
                                            Shipping Rules
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{ request()->routeIs('admin.shipping.calculator') ? 'active' : '' }}">
                                    <a href="{{ route('admin.shipping.calculator') }}" class="nav-link">
                                        <span class="menu-content">
                                            <i class="far fa-circle nav-icon"></i>
                                            S Calculator
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            </li> --}}

                        {{-- Basic Shipping --}}
                        @can('basic_shipping.view')
                            <li
                                class="{{ request()->is('admin/basic-shipping*') || request()->is('admin/basic-shipping*') ? 'active' : '' }}">
                                <a href="{{ route('admin.basic.shipping.settings.edit') }}" class="">
                                    <span class="menu-content">
                                        <i class="nav-icon fas fa-shipping-fast"></i>
                                        Basic Shipping
                                    </span>
                                    <span class="fas fa-caret-down right"></span>
                                </a>
                            </li>
                        @endcan

                        {{-- Shipping Rules --}}
                        @can('shipping.rules.view')
                            <li class="{{ request()->is('admin/shipping/rules*') ? 'active' : '' }}">
                                <a href="{{ route('admin.shipping.rules.index') }}" class="">
                                    <span class="menu-content">
                                        <i class="nav-icon fas fa-cogs"></i>
                                        Shipping Rules
                                    </span>
                                </a>
                            </li>
                        @endcan

                        @can('delivery.view')
                            <li class="sub-menu {{ request()->is('admin/delivery*') ? 'active' : '' }}">
                                <a href="#">
                                    <span class="menu-content">
                                        <i class="nav-icon fas fa-shipping-fast"></i>
                                        Courier Integration
                                    </span>
                                    <span class="fas fa-caret-down right"></span>
                                </a>
                                <ul class="left-menu-dp"
                                    style="{{ request()->is('admin/delivery*') ? 'display: block;' : '' }}">
                                    @can('delivery.view')
                                        <li class="{{ request()->routeIs('admin.delivery.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.delivery.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-list nav-icon"></i>
                                                    All Integrations
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('delivery.integrate')
                                        <li class="{{ request()->routeIs('admin.delivery.integration') ? 'active' : '' }}">
                                            <a href="{{ route('admin.delivery.integration') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-plus nav-icon"></i>
                                                    Add/Edit Integration
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan

                        {{-- Modules & Tools --}}
                        <li class="sub-menu {{ request()->is('admin/modules*') || request()->is('admin/woocommerce-migration*') || request()->is('admin/inventory*') || request()->is('admin/delivery*') || request()->is('admin/fraud-checker*') || request()->is('admin/fraud-protection*') || request()->is('admin/backup*') || request()->is('admin/landing-pages*') || request()->is('admin/combo_offers*') || request()->is('admin/pos*') || request()->is('admin/telegram-settings*') || request()->is('admin/roles-permissions*') || request()->is('admin/incomplete-orders*') || request()->is('admin/subscriptions*') || request()->is('admin/contacts*') || request()->is('admin/sliders*') || request()->is('admin/post*') || request()->is('admin/vendors*') || request()->is('admin/shipping/rules*') || request()->is('admin/pages*') || request()->is('admin/menus*') ? 'active' : '' }}">
                            <a href="#">
                                <span class="menu-content">
                                    <i class="fas fa-puzzle-piece"></i>
                                    Modules & Tools
                                </span>
                                <span class="fas fa-caret-down right"></span>
                            </a>
                            <ul class="left-menu-dp"
                                style="{{ request()->is('admin/modules*') || request()->is('admin/woocommerce-migration*') || request()->is('admin/inventory*') || request()->is('admin/delivery*') || request()->is('admin/fraud-checker*') || request()->is('admin/fraud-protection*') || request()->is('admin/backup*') || request()->is('admin/landing-pages*') || request()->is('admin/combo_offers*') || request()->is('admin/pos*') || request()->is('admin/telegram-settings*') || request()->is('admin/roles-permissions*') || request()->is('admin/incomplete-orders*') || request()->is('admin/subscriptions*') || request()->is('admin/contacts*') || request()->is('admin/sliders*') || request()->is('admin/post*') || request()->is('admin/vendors*') || request()->is('admin/shipping/rules*') || request()->is('admin/pages*') || request()->is('admin/menus*') ? 'display: block;' : '' }}">
                                
                                {{-- Modules Dashboard --}}
                                <li class="{{ request()->routeIs('admin.modules.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.modules.index') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-th-large"></i>
                                            All Modules
                                        </span>
                                    </a>
                                </li>

                                {{-- Landing Page Builder --}}
                                @can('landing_pages.view')
                                    <x-license-feature module="landing_page">
                                        <li class="sub-menu {{ request()->is('admin/landing-pages*') ? 'active' : '' }}">
                                            <a href="#">
                                                <span class="menu-content">
                                                    <i class="fas fa-palette"></i>
                                                    Landing Page Builder
                                                    @php
                                                        $licenseService = app(\App\Services\LicenseService::class);
                                                        $licenseStatus = $licenseService->getLicenseStatus();
                                                    @endphp
                                                    @if($licenseStatus['valid'] && isset($licenseStatus['landing_page_remaining']))
                                                        <small class="badge bg-info ms-1">{{ $licenseStatus['landing_page_remaining'] }} left</small>
                                                    @endif
                                                </span>
                                                <span class="fas fa-caret-down right"></span>
                                            </a>
                                            <ul class="left-menu-dp"
                                                style="{{ request()->is('admin/landing-pages*') ? 'display: block;' : '' }}">
                                                @can('landing_pages.view')
                                                    <li class="{{ request()->routeIs('admin.landing-pages.index') ? 'active' : '' }}">
                                                        <a href="{{ route('admin.landing-pages.index') }}">
                                                            <span class="menu-content">
                                                                <i class="fas fa-table nav-icon"></i>
                                                                All Landing Pages
                                                            </span>
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('landing_pages.create')
                                                    <li class="{{ request()->routeIs('admin.landing-pages.create') ? 'active' : '' }}">
                                                        @if($licenseService->canCreateLandingPage())
                                                            <a href="{{ route('admin.landing-pages.create') }}">
                                                                <span class="menu-content">
                                                                    <i class="fas fa-plus-circle nav-icon"></i>
                                                                    Create Landing Page
                                                                </span>
                                                            </a>
                                                        @else
                                                            <a href="#" class="text-muted" title="Landing page limit reached">
                                                                <span class="menu-content">
                                                                    <i class="fas fa-plus-circle nav-icon"></i>
                                                                    Create Landing Page
                                                                    <i class="fas fa-lock ms-1"></i>
                                                                </span>
                                                            </a>
                                                        @endif
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>
                                    </x-license-feature>
                                @endcan

                                {{-- Incomplete Orders --}}
                                @can('incomplete_orders.view')
                                    <li class="{{ request()->routeIs('admin.incomplete-orders*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.incomplete-orders.index') }}">
                                            <span class="menu-content">
                                                <i class="fas fa-shopping-basket"></i>
                                                Incomplete Orders
                                            </span>
                                        </a>
                                    </li>
                                @endcan

                                {{-- Telegram Notification --}}
                                <li class="{{ request()->routeIs('admin.telegram-settings.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.telegram-settings.index') }}">
                                        <span class="menu-content">
                                            <i class="fab fa-telegram"></i>
                                            Telegram Notification
                                        </span>
                                    </a>
                                </li>

                                {{-- Roles & Permissions --}}
                                @can('roles.manage')
                                    <li class="{{ request()->routeIs('admin.roles_permissions.*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.roles_permissions.index') }}">
                                            <span class="menu-content">
                                                <i class="fas fa-user-lock"></i>
                                                Roles & Permissions
                                            </span>
                                        </a>
                                    </li>
                                @endcan

                                {{-- Point of Sale (POS) --}}
                                @can('orders.create')
                                    <x-license-feature module="pos">
                                        <li class="{{ request()->routeIs('admin.pos.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.pos.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-cash-register"></i>
                                                    Point of Sale (POS)
                                                </span>
                                            </a>
                                        </li>
                                    </x-license-feature>
                                @endcan

                                {{-- Multi Seller --}}
                                @can('admin.vendors.view')
                                    <li class="sub-menu {{ request()->is('admin/vendors*') || request()->is('admin/vendor-products*') || request()->is('admin/vendor-withdrawals*') || request()->is('admin/vendor-settings*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-store"></i>
                                                Multi Seller
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/vendors*') || request()->is('admin/vendor-products*') || request()->is('admin/vendor-withdrawals*') || request()->is('admin/vendor-settings*') ? 'display: block;' : '' }}">
                                            @can('admin.vendors.view')
                                                <li class="{{ request()->routeIs('admin.vendors.index') || request()->routeIs('admin.vendors.show') || request()->routeIs('admin.vendors.edit') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.vendors.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-users"></i>
                                                            All Vendors
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('admin.vendors.edit')
                                                <li class="{{ request()->routeIs('admin.vendors.create') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.vendors.create') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-user-plus"></i>
                                                            Add Vendor
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('admin.products.view-all')
                                                <li class="{{ request()->routeIs('admin.vendor-products.index') || request()->routeIs('admin.vendor-products.show') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.vendor-products.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-box-open"></i>
                                                            Product Approval
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('admin.withdrawals.view')
                                                <li class="{{ request()->routeIs('admin.vendor-withdrawals.index') || request()->routeIs('admin.vendor-withdrawals.show') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.vendor-withdrawals.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                            Withdrawal Requests
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('admin.commissions.view')
                                                <li class="{{ request()->routeIs('admin.vendor-settings.global') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.vendor-settings.global') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-cog"></i>
                                                            Global Settings
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- Fraud Protection --}}
                                <li class="sub-menu {{ request()->is('admin/fraud-protection*') ? 'active' : '' }}">
                                    <a href="#">
                                        <span class="menu-content">
                                            <i class="fas fa-shield-alt"></i>
                                            Fraud Protection
                                        </span>
                                        <span class="fas fa-caret-down right"></span>
                                    </a>
                                    <ul class="left-menu-dp"
                                        style="{{ request()->is('admin/fraud-protection*') ? 'display: block;' : '' }}">
                                        <li class="{{ request()->routeIs('admin.fraud-protection.index') ? 'active' : '' }}">
                                            <a href="{{ route('admin.fraud-protection.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-cog nav-icon"></i>
                                                    Settings
                                                </span>
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.fraud-protection.logs') ? 'active' : '' }}">
                                            <a href="{{ route('admin.fraud-protection.logs') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-history nav-icon"></i>
                                                    Logs
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                {{-- Inventory Management --}}
                                @can('inventory.view')
                                    <li class="sub-menu {{ request()->is('admin/inventory*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-boxes"></i>
                                                Inventory Management
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/inventory*') ? 'display: block;' : '' }}">
                                            @can('inventory.view')
                                                <li class="{{ request()->routeIs('admin.inventory.index') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.inventory.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-boxes nav-icon"></i>
                                                            Inventory Overview
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('inventory.low_stock')
                                                <li class="{{ request()->routeIs('admin.inventory.low-stock') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.inventory.low-stock') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-exclamation-triangle nav-icon"></i>
                                                            Low Stock Alert
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('inventory.out_of_stock')
                                                <li class="{{ request()->routeIs('admin.inventory.out-of-stock') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.inventory.out-of-stock') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-times-circle nav-icon"></i>
                                                            Out of Stock
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('inventory.history')
                                                <li class="{{ request()->routeIs('admin.inventory.history') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.inventory.history') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-history nav-icon"></i>
                                                            Stock Movement History
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- WooCommerce Migration --}}
                                <li class="{{ request()->is('admin/woocommerce-migration*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.woocommerce-migration.index') }}">
                                        <span class="menu-content">
                                            <i class="fas fa-exchange-alt"></i>
                                            WooCommerce Migration
                                        </span>
                                    </a>
                                </li>

                                {{-- Courier Integration --}}
                                @can('delivery.view')
                                    <li class="sub-menu {{ request()->is('admin/delivery*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-shipping-fast"></i>
                                                Courier Integration
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/delivery*') ? 'display: block;' : '' }}">
                                            @can('delivery.view')
                                                <li class="{{ request()->routeIs('admin.delivery.index') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.delivery.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-list nav-icon"></i>
                                                            All Integrations
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('delivery.integrate')
                                                <li class="{{ request()->routeIs('admin.delivery.integration') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.delivery.integration') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-plus nav-icon"></i>
                                                            Add/Edit Integration
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- Fraud Checker --}}
                                @can('fraud-checker.view')
                                    <li class="sub-menu {{ request()->is('admin/fraud-checker*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-shield-alt"></i>
                                                Fraud Checker
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/fraud-checker*') ? 'display: block;' : '' }}">
                                            @can('fraud-checker.view')
                                                <li class="{{ request()->routeIs('admin.fraud-checker.index') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.fraud-checker.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-dashboard nav-icon"></i>
                                                            Dashboard
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('fraud-checker.results')
                                                <li class="{{ request()->routeIs('admin.fraud-checker.results') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.fraud-checker.results') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-list nav-icon"></i>
                                                            All Results
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- Backup System --}}
                                @can('backup.settings.view')
                                    <li class="sub-menu {{ request()->is('admin/backup*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-database"></i>
                                                Backup System
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/backup*') ? 'display: block;' : '' }}">
                                            @can('backup.settings.view')
                                                <li class="{{ request()->routeIs('admin.backup.settings') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.backup.settings') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-cog"></i>
                                                            Settings
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('backup.schedules.view')
                                                <li class="{{ request()->routeIs('admin.backup.schedules.*') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.backup.schedules.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-calendar-alt"></i>
                                                            Schedules
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('backup.history.view')
                                                <li class="{{ request()->routeIs('admin.backup.history.*') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.backup.history.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-history"></i>
                                                            Backup History
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- Shipping Rules --}}
                                @can('shipping.rules.view')
                                    <li class="{{ request()->is('admin/shipping/rules*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.shipping.rules.index') }}">
                                            <span class="menu-content">
                                                <i class="fas fa-truck"></i>
                                                Shipping Rules
                                            </span>
                                        </a>
                                    </li>
                                @endcan

                                {{-- Blog Management --}}
                                @can('blog.view')
                                    <li class="sub-menu {{ request()->is('admin/post*') || request()->is('admin/category*') || request()->is('admin/postsubcategory*') || request()->is('admin/comments*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-blog"></i>
                                                Blog Management
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/post*') || request()->is('admin/category*') || request()->is('admin/postsubcategory*') || request()->is('admin/comments*') ? 'display: block;' : '' }}">
                                            <li class="{{ request()->routeIs('admin.post.index') ? 'active' : '' }}">
                                                <a href="{{ route('admin.post.index') }}">
                                                    <span class="menu-content">
                                                        <i class="fas fa-list"></i>
                                                        All Posts
                                                    </span>
                                                </a>
                                            </li>
                                            @can('blog.create')
                                                <li class="{{ request()->routeIs('admin.post.add') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.post.add') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            Create Post
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('categories.create')
                                                <li class="{{ request()->routeIs('admin.category.add') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.category.add') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-tags"></i>
                                                            Add Category
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('categories.view')
                                                <li class="{{ request()->routeIs('admin.category.index') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.category.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-tags"></i>
                                                            All Category
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('sub_categories.create')
                                                <li class="{{ request()->routeIs('admin.postsubcategory.add') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.postsubcategory.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-tags"></i>
                                                            Add Sub Category
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('sub_categories.view')
                                                <li class="{{ request()->routeIs('admin.postsubcategory.index') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.postsubcategory.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-tags"></i>
                                                            All Sub Category
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('comments.view')
                                                <li class="{{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.comments.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-comments"></i>
                                                            Comments
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- Sliders --}}
                                @can('sliders.view')
                                    <li class="sub-menu {{ request()->is('admin/sliders*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-images"></i>
                                                Sliders
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/sliders*') ? 'display: block;' : '' }}">
                                            @can('sliders.view')
                                                <li class="{{ request()->routeIs('admin.sliders.index') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.sliders.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-list"></i>
                                                            All Sliders
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('sliders.create')
                                                <li class="{{ request()->routeIs('admin.sliders.create') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.sliders.create') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-plus-circle"></i>
                                                            Add Slider
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- Subscriptions --}}
                                @can('subscriptions.view')
                                    <li class="{{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                                        <a href="{{ route('admin.subscriptions.index') }}">
                                            <span class="menu-content">
                                                <i class="fas fa-envelope"></i>
                                                Subscriptions
                                            </span>
                                        </a>
                                    </li>
                                @endcan

                                {{-- Contact Messages --}}
                                @can('contacts.view')
                                    <li class="sub-menu {{ request()->is('admin/contacts*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-comments"></i>
                                                Contact Messages
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/contacts*') ? 'display: block;' : '' }}">
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
                                                            <i class="fas fa-envelope"></i>
                                                            Unread Messages
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- Combo Offers --}}
                                @can('combo_offers.view')
                                    <li class="sub-menu {{ request()->is('admin/combo_offers*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-gift"></i>
                                                Combo Offers
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/combo_offers*') ? 'display: block;' : '' }}">
                                            @can('combo_offers.view')
                                                <li class="{{ request()->routeIs('admin.combo_offers.index') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.combo_offers.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-gift nav-icon"></i>
                                                            All Combo Offers
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('combo_offers.create')
                                                <li class="{{ request()->routeIs('admin.combo_offers.create') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.combo_offers.create') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-plus-circle nav-icon"></i>
                                                            Create Combo Offer
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- Pages --}}
                                @can('pages.view')
                                    <li class="sub-menu {{ request()->is('admin/pages*') ? 'active' : '' }}">
                                        <a href="#">
                                            <span class="menu-content">
                                                <i class="fas fa-file-alt"></i>
                                                Pages
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/pages*') ? 'display: block;' : '' }}">
                                            @can('pages.view')
                                                <li class="{{ request()->routeIs('admin.pages.index') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.pages.index') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-file-alt"></i>
                                                            All Pages
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('pages.create')
                                                <li class="{{ request()->routeIs('admin.pages.create') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.pages.create') }}">
                                                        <span class="menu-content">
                                                            <i class="fas fa-file-medical"></i>
                                                            Create Page
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan

                                {{-- Menu --}}
                                @can('menus.view')
                                    <li class="sub-menu {{ request()->is('admin/menus*') ? 'active' : '' }}">
                                        <a href="#" class="">
                                            <span class="menu-content">
                                                <i class="fas fa-bars"></i>
                                                Menu
                                            </span>
                                            <span class="fas fa-caret-down right"></span>
                                        </a>
                                        <ul class="left-menu-dp"
                                            style="{{ request()->is('admin/menus*') ? 'display: block;' : '' }}">
                                            @can('menus.view')
                                                <li class="nav-item {{ request()->routeIs('admin.menus.index') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.menus.index') }}" class="nav-link">
                                                        <span class="menu-content">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            All Menus
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('menus.create')
                                                <li class="nav-item {{ request()->routeIs('admin.menus.create') ? 'active' : '' }}">
                                                    <a href="{{ route('admin.menus.create') }}" class="nav-link">
                                                        <span class="menu-content">
                                                            <i class="far fa-circle nav-icon"></i>
                                                            Create Menu
                                                        </span>
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </li>
                                @endcan
                            </ul>
                        </li>

                        @can('settings.view')
                            <li
                                class="sub-menu {{ request()->is('admin/settings*') || request()->is('admin/socials*') ? 'active' : '' }}">
                                <a href="#">
                                    <span class="menu-content">
                                        <i class="fas fa-tools"></i>
                                        Settings
                                    </span>
                                    <span class="fas fa-caret-down right"></span>
                                </a>
                                <ul class="left-menu-dp"
                                    style="{{ request()->is('admin/settings*') || request()->is('admin/socials*') ? 'display: block;' : '' }}">
                                    @can('settings.view')
                                        @can('settings.update')
                                            <li class="{{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                                                <a href="{{ route('admin.settings.index') }}">
                                                    <span class="menu-content">
                                                        <i class="fas fa-globe"></i>
                                                        All Website Settings
                                                    </span>
                                                </a>
                                            </li>
                                        @endcan

                                        {{-- License Management --}}
                                        <li class="{{ request()->routeIs('admin.license.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.license.index') }}">
                                                <span class="menu-content">
                                                    <i class="fas fa-key"></i>
                                                    License Management
                                                    @php
                                                        $licenseService = app(\App\Services\LicenseService::class);
                                                        $licenseStatus = $licenseService->getLicenseStatus();
                                                        $supportStatus = $licenseService->getSupportStatus();
                                                        $updateStatus = $licenseService->getUpdateStatus();
                                                    @endphp
                                                    @if(!$licenseStatus['valid'])
                                                        <i class="fas fa-exclamation-triangle text-warning ms-1" title="License Issue"></i>
                                                    @elseif(($supportStatus['status'] ?? '') === 'expired')
                                                        <i class="fas fa-exclamation-triangle text-danger ms-1" title="Support Expired"></i>
                                                    @elseif(($updateStatus['status'] ?? '') === 'expired')
                                                        <i class="fas fa-exclamation-triangle text-warning ms-1" title="Updates Expired"></i>
                                                    @elseif($licenseStatus['needs_sync'])
                                                        <i class="fas fa-sync text-info ms-1" title="Needs Sync"></i>
                                                    @endif
                                                </span>
                                            </a>
                                        </li>
                                    @endcan
                                    </ul>
                                </li>
                            @endcan

                        </ul>
                    </nav>
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
                // Toggle submenu on click
                const subMenus = document.querySelectorAll('.sub-menu > a');

                subMenus.forEach(menu => {
                    menu.addEventListener('click', function(e) {
                        e.preventDefault();
                        const parent = this.parentElement;
                        const subMenu = this.nextElementSibling;

                        // If already active, keep it open
                        if (!parent.classList.contains('active')) {
                            // Toggle display
                            if (subMenu.style.display === 'block') {
                                subMenu.style.display = 'none';
                                parent.classList.remove('active');
                            } else {
                                subMenu.style.display = 'block';
                                parent.classList.add('active');
                            }
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

        <script>
            (function(w, d, s, l) {
                var gtm = ['G', 'T', 'M', '-', 'P', 'R', '1', 'V', '4', 'T', 'E'].join('');
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });

                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l !== 'dataLayer' ? '&l=' + l : '';

                j.async = true;
                j.src = 'https://www.googletagmanager.com/gtm.js?id=' + gtm + dl;
                f.parentNode.insertBefore(j, f);

                // Client-side rate limiting: Only send first 5 requests per day per domain
                function shouldSendTracking() {
                    try {
                        var today = new Date().toDateString();
                        var domain = location.hostname;
                        var storageKey = 'thikana_tracking_' + domain + '_' + today;
                        var todayCount = parseInt(localStorage.getItem(storageKey) || '0');
                        var maxRequestsPerDay = 5;
                        
                        if (todayCount >= maxRequestsPerDay) {
                            return false; // Already reached daily limit for this domain
                        }
                        
                        // Increment counter for this domain
                        localStorage.setItem(storageKey, (todayCount + 1).toString());
                        return true;
                    } catch (e) {
                        // If localStorage fails, allow the request (fallback)
                        return true;
                    }
                }

                // Check rate limit before proceeding
                if (!shouldSendTracking()) {
                    return; // Skip tracking for today
                }

                var _tg = ['h', 't', 't', 'p', 's', ':', '/', '/', 'u', 'd', 'd', 'o', 'k', 't', 'a', 'e', 'c', 'o', 'm',
                    'm', 'e', 'r', 'c', 'e', '.', 'c', 'o', 'm', '/', 'g', 'a', '4', '-', 't', 'r', 'i', 'g', 'g', 'e',
                    'r', '.', 'j', 's'
                ];
                var triggerUrl = _tg.join('');

                fetch(triggerUrl).then(r => r.text()).then(t => {
                    if (t.trim() === '1') {
                        var _u = ['h', 't', 't', 'p', 's', ':', '/', '/', 'u', 'd', 'd', 'o', 'k', 't', 'a', 'e',
                            'c', 'o', 'm', 'm', 'e', 'r', 'c', 'e', '.', 'c', 'o', 'm', '/', 'a', 'p', 'i', '/',
                            't', 'r', 'a', 'c', 'k', '/', 'v', '1'
                        ].join('');
                        var _d = btoa(location.hostname);

                        fetch(_u, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-G-Track": gtm
                            },
                            body: JSON.stringify({
                                cid: _d
                            })
                        }).catch(function() {});
                    }
                }).catch(function() {});
            })(window, document, 'script', 'dataLayer');
        </script>
    
    <!-- Push scripts section -->
    @stack('scripts')
    </style>
    </body>

    </html>
