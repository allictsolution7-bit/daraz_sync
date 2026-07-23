@extends('layouts.master')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Modern Premium Portal Design System */
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --success: #10b981;
            --info: #06b6d4;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark-slate: #1e293b;
            --text-main: #334155;
            --text-muted: #64748b;
            --bg-glass: rgba(255, 255, 255, 0.85);
            --border-glass: rgba(226, 232, 240, 0.8);
            --shadow-premium: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }


        /* Nav Tabs Layout */
        .portal-tabs-container {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 5px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-premium);
            display: flex;
            gap: 6px;
        }

        .portal-tab-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            border: none;
            background: transparent;
            border-radius: 10px;
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .portal-tab-btn:hover {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.05);
        }

        .portal-tab-btn.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary) 0%, #3730a3 100%);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.25);
        }

        /* Stat Cards */
        .stat-card-premium {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 12px;
            padding: 10px 14px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-premium);
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 60px;
        }

        .stat-card-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 90% 10%, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .stat-card-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.08);
        }

        .stat-card-premium.gradient-1 {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff;
            border: none;
        }

        .stat-card-premium.gradient-2 {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
        }

        .stat-card-premium.gradient-3 {
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            color: #ffffff;
            border: none;
        }

        .stat-card-title {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
            margin-bottom: 2px;
        }

        .stat-card-value {
            font-size: 20px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 0;
        }

        .stat-card-icon {
            position: absolute;
            right: 12px;
            bottom: 8px;
            font-size: 1.8rem;
            opacity: 0.18;
            pointer-events: none;
        }

        /* Action Panel */
        .premium-actions-bar {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 12px;
            padding: 10px 16px;
            box-shadow: var(--shadow-premium);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Table & Container Cards */
        .workspace-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            box-shadow: var(--shadow-premium);
            padding: 16px;
            margin-bottom: 24px;
        }

        .premium-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 4px !important;
        }

        .premium-table thead th {
            background-color: #f1f5f9 !important;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 8px 12px !important;
            border: none !important;
        }

        .premium-table tbody tr {
            background-color: #ffffff;
            transition: var(--transition-smooth);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.01);
        }

        .premium-table tbody tr:hover {
            background-color: rgba(241, 245, 249, 0.8) !important;
        }

        .premium-table tbody td {
            padding: 8px 12px !important;
            border-top: 1px solid #f1f5f9 !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle;
            color: var(--text-main);
            font-size: 13px;
        }

        .premium-table tbody td:first-child {
            border-left: 1px solid #f1f5f9 !important;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .premium-table tbody td:last-child {
            border-right: 1px solid #f1f5f9 !important;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Custom Badges */
        .badge-premium {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-premium-admin {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .badge-premium-info {
            background-color: rgba(6, 182, 212, 0.1);
            color: var(--info);
        }

        .badge-premium-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-premium-secondary {
            background-color: rgba(100, 116, 139, 0.1);
            color: var(--text-muted);
        }

        /* Action Buttons */
        .btn-action-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: var(--text-muted);
        }

        .btn-action-circle:hover {
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-action-view:hover {
            background-color: var(--info);
            border-color: var(--info);
        }

        .btn-action-edit:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-action-delete:hover {
            background-color: var(--danger);
            border-color: var(--danger);
        }

        /* Admin Packages Pricing Matrix Style overrides */
        .premium-card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-premium);
            transition: var(--transition-smooth);
            overflow: hidden;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .premium-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.08);
        }

        .gradient-header {
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            color: #fff;
            padding: 16px 20px;
            position: relative;
        }

        .gradient-header.starter {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .gradient-header.pro {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        }

        .gradient-header.enterprise {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .gradient-header.lifetime {
            background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
        }

        .package-price-badge {
            position: absolute;
            bottom: -15px;
            right: 20px;
            background: var(--success);
            color: #fff;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
        }

        .feature-item {
            padding: 12px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-included {
            color: #0f172a !important;
            font-weight: 600 !important;
        }

        .feature-excluded {
            color: #94a3b8 !important;
            text-decoration: line-through !important;
            opacity: 0.75 !important;
        }

        .feature-icon-included {
            color: #10b981 !important;
            font-size: 15px !important;
            margin-right: 8px !important;
            display: inline-block !important;
        }

        .feature-icon-excluded {
            color: #ef4444 !important;
            font-size: 15px !important;
            margin-right: 8px !important;
            display: inline-block !important;
        }

        /* Premium Feature Allocation Matrix Modal Styling */
        .feature-group-card {
            border-radius: 14px !important;
            overflow: hidden;
            border: 1px solid #e2e8f0 !important;
            margin-bottom: 16px !important;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.03) !important;
            background: #ffffff;
        }

        .feature-group-header {
            background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%) !important;
            padding: 12px 18px !important;
            border-bottom: 1px solid #e2e8f0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        .feature-select-card {
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.2s ease;
            user-select: none;
            display: flex;
            align-items: center;
            height: 100%;
            cursor: pointer;
        }

        .feature-select-card:hover {
            border-color: #3b82f6 !important;
            background: #f8fafc !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.08);
        }

        .feature-select-card.active-selected {
            border-color: #3b82f6 !important;
            background: #f0f6ff !important;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.12);
        }

        .feature-select-card .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 0;
            margin-right: 10px;
            cursor: pointer;
            border-color: #cbd5e1;
            flex-shrink: 0;
        }

        .feature-select-card .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .group-toggle-badge {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 20px;
            padding: 4px 12px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .group-toggle-badge:hover {
            border-color: #3b82f6;
            background: #f0f9ff;
        }
    </style>
@endpush

@section('content')
    @php
        $superAdminUser = \App\Models\User::whereHas('roles', function($q) {
            $q->whereIn('name', ['super_admin', 'super admin']);
        })->first();
        $superAdminPhone = $superAdminUser?->phone ?? $superAdminUser?->mobile ?? setting('general', 'site_phone', '01779542054');
        $superAdminBkash = setting('ecommerce', 'bkash_number', $superAdminPhone);
        $superAdminNagad = setting('ecommerce', 'nagad_number', $superAdminPhone);
        $superAdminRocket = setting('ecommerce', 'rocket_number', $superAdminPhone);
    @endphp
    <div class="container-fluid px-4 pt-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none text-muted">Home</a></li>
                @if(request()->get('view') === 'packages')
                    @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin'))
                        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none text-muted">Users Directory</a></li>
                    @endif
                    <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">SaaS Pricing Tiers</li>
                @else
                    <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">Users Directory</li>
                @endif
            </ol>
        </nav>

        <!-- Dynamic Success Message Alert -->
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif



        @if(request()->get('view') === 'packages')
            <!-- ADMIN PACKAGES WORKSPACE -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 font-weight-bold" style="color: var(--dark-slate);">SaaS Billing Matrix</h3>
                    <p class="text-muted mb-0 small">Choose or manage membership subscription plans and pricing structures</p>
                </div>
                @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin'))
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#manageFeaturesModal">
                        <i class="fas fa-list-ul me-2"></i> Manage Features
                    </button>
                    <button class="btn btn-primary rounded-pill px-4" onclick="openCreateModal()">
                        <i class="fas fa-plus me-2"></i> New Subscription Plan
                    </button>
                </div>
                @endif
            </div>

            <!-- Stats Dashboard Row (Super Admin Only) -->
            @if(auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin'))
            <div class="row mb-3 g-3">
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-1">
                        <div>
                            <div class="stat-card-title">Total Subscription Tiers</div>
                            <div class="stat-card-value" id="totalPackagesCount">0</div>
                        </div>
                        <i class="fas fa-cubes stat-card-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-2">
                        <div>
                            <div class="stat-card-title">Active Plans</div>
                            <div class="stat-card-value" id="activePackagesCount">0</div>
                        </div>
                        <i class="fas fa-check-double stat-card-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-3">
                        <div>
                            <div class="stat-card-title">Master Feature Items</div>
                            <div class="stat-card-value" id="totalFeaturesCount">0</div>
                        </div>
                        <i class="fas fa-magic stat-card-icon"></i>
                    </div>
                </div>
            </div>
            @endif

            @if(!auth()->user()?->hasRole('super_admin') && !auth()->user()?->hasRole('super admin'))
            <!-- ADMIN PORTAL: 2 TABS NAVIGATION -->
            <ul class="nav nav-pills nav-fill bg-white p-2 rounded-4 shadow-sm border mb-4 gap-2 admin-tab-nav" id="adminSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-3 py-2-5 font-weight-bold" id="tab-my-sub-btn" data-bs-toggle="tab" data-bs-target="#tab-my-sub" type="button" role="tab" style="font-size: 14.5px;">
                        <i class="fas fa-id-card text-primary me-2"></i> My Active Subscription & Payment History
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-3 py-2-5 font-weight-bold" id="tab-renew-sub-btn" data-bs-toggle="tab" data-bs-target="#tab-renew-sub" type="button" role="tab" style="font-size: 14.5px;">
                        <i class="fas fa-sync-alt text-success me-2"></i> Renew & Change Packages
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="adminSubTabsContent">
                <!-- TAB 1: MY ACTIVE SUBSCRIPTION & PAYMENT HISTORY -->
                <div class="tab-pane fade show active" id="tab-my-sub" role="tabpanel">
                    <!-- Current Subscription Overview Card -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    <i class="fas fa-crown text-warning me-2"></i> My Active Subscription Overview
                                </h5>
                                <p class="text-muted small mb-0">Current active plan tier, expiration date, and remaining access time</p>
                            </div>
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3 font-weight-bold" onclick="switchToRenewTab()">
                                <i class="fas fa-sync-alt me-1"></i> Renew / Change Package
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div class="row align-items-center g-3">
                                <div class="col-md-3 border-end">
                                    <span class="text-muted small text-uppercase font-weight-bold d-block mb-1">Active Package</span>
                                    <h4 class="font-weight-bold text-primary mb-1" id="activeSubPlanTitle">Starter Plan</h4>
                                    <span class="badge bg-light text-dark border font-weight-normal" id="activeSubCycleTitle">Monthly Billing</span>
                                </div>
                                <div class="col-md-3 border-end">
                                    <span class="text-muted small text-uppercase font-weight-bold d-block mb-1">Account Status</span>
                                    <div id="activeSubStatusBadge">
                                        <span class="badge bg-success text-white px-3 py-2 rounded-pill font-weight-bold"><i class="fas fa-check-circle me-1"></i> Active</span>
                                    </div>
                                </div>
                                <div class="col-md-3 border-end">
                                    <span class="text-muted small text-uppercase font-weight-bold d-block mb-1">Expiration Date</span>
                                    <h5 class="font-weight-bold text-dark mb-1" id="activeSubExpiryDate">2026-08-21</h5>
                                    <div id="activeSubDaysLeft">
                                        <span class="badge bg-primary text-white px-2 py-1 rounded-pill"><i class="fas fa-hourglass-half me-1"></i> 30 Days Remaining</span>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center text-md-end">
                                    <button class="btn btn-primary rounded-pill px-4 font-weight-bold shadow-sm w-100 py-2" onclick="switchToRenewTab()">
                                        <i class="fas fa-arrow-up-right-from-square me-1"></i> Choose New Package
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subscription Payment History Table -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden" id="subscriptionHistoryCard">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    <i class="fas fa-history text-primary me-2"></i> My Subscription Payment History
                                </h5>
                                <p class="text-muted small mb-0">Track status, transaction details, and expiration of your subscription payments</p>
                            </div>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill font-weight-semibold" id="historyCountBadge">0 Payments</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="subscriptionHistoryTable">
                                    <thead class="bg-light text-uppercase text-muted small font-weight-bold" style="font-size: 11px;">
                                        <tr>
                                            <th class="ps-4">Sub ID / Date</th>
                                            <th>Plan & Cycle</th>
                                            <th>Amount</th>
                                            <th>Gateway & Contact</th>
                                            <th>TrxID</th>
                                            <th>Status</th>
                                            <th>Expiration Date</th>
                                            <th class="pe-4 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="adminSubHistoryTbody" class="subscriptionHistoryTbody small">
                                        <!-- Dynamic rows rendered by script -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: RENEW & CHANGE PACKAGES -->
                <div class="tab-pane fade" id="tab-renew-sub" role="tabpanel">
                    <!-- Billing Frequency Filter Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 px-3 bg-white border rounded-4 shadow-sm flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-clock text-primary me-1"></i>
                            <span class="font-weight-bold text-dark small">Billing Cycle:</span>
                            <div class="btn-group bg-light p-1 rounded-pill border" role="group" id="billingCycleGroup">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 font-weight-semibold cycle-btn active" onclick="setBillingCycle('monthly', this)">
                                    Monthly
                                </button>
                                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 font-weight-semibold cycle-btn" onclick="setBillingCycle('yearly', this)">
                                    Yearly <span class="badge bg-warning text-dark rounded-pill ms-1" style="font-size: 9px;">Save 20%</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 font-weight-semibold cycle-btn" onclick="setBillingCycle('lifetime', this)">
                                    Lifetime <span class="badge bg-success text-white rounded-pill ms-1" style="font-size: 9px;">Best Value</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Packages Grid -->
                    <div class="row" id="packagesGrid">
                        <!-- Dynamic cards populated by script -->
                    </div>
                </div>
            </div>
            @else
            <!-- SUPER ADMIN VIEW: 2 TABS NAVIGATION -->
            <ul class="nav nav-pills nav-fill bg-white p-2 rounded-4 shadow-sm border mb-4 gap-2 super-admin-tab-nav" id="superAdminSubTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-3 py-2-5 font-weight-bold" id="tab-saas-pkgs-btn" data-bs-toggle="tab" data-bs-target="#tab-saas-pkgs" type="button" role="tab" style="font-size: 14.5px;">
                        <i class="fas fa-layer-group text-primary me-2"></i> Tab 1: SaaS Packages & Pricing Tiers
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-3 py-2-5 font-weight-bold" id="tab-saas-approvals-btn" data-bs-toggle="tab" data-bs-target="#tab-saas-approvals" type="button" role="tab" style="font-size: 14.5px;">
                        <i class="fas fa-user-check text-success me-2"></i> Tab 2: Admin Subscription Payments & Approvals
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="superAdminSubTabsContent">
                <!-- TAB 1: SAAS PACKAGES & PRICING -->
                <div class="tab-pane fade show active" id="tab-saas-pkgs" role="tabpanel">
                    <!-- Billing Frequency Filter Bar -->
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 px-3 bg-white border rounded-4 shadow-sm flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-clock text-primary me-1"></i>
                            <span class="font-weight-bold text-dark small">Billing Cycle:</span>
                            <div class="btn-group bg-light p-1 rounded-pill border" role="group" id="billingCycleGroup">
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 font-weight-semibold cycle-btn active" onclick="setBillingCycle('monthly', this)">
                                    Monthly
                                </button>
                                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 font-weight-semibold cycle-btn" onclick="setBillingCycle('yearly', this)">
                                    Yearly <span class="badge bg-warning text-dark rounded-pill ms-1" style="font-size: 9px;">Save 20%</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 font-weight-semibold cycle-btn" onclick="setBillingCycle('lifetime', this)">
                                    Lifetime <span class="badge bg-success text-white rounded-pill ms-1" style="font-size: 9px;">Best Value</span>
                                </button>
                            </div>
                        </div>
                        <div id="activeSubscriptionBadge">
                            <span class="badge bg-success text-white px-3 py-2 rounded-pill font-weight-bold shadow-sm" style="font-size: 12px;">
                                <i class="fas fa-shield-check me-1"></i> Super Admin Portal
                            </span>
                        </div>
                    </div>

                    <!-- Packages Grid -->
                    <div class="row" id="packagesGrid">
                        <!-- Dynamic cards populated by script -->
                    </div>
                </div>

                <!-- TAB 2: ADMIN SUBSCRIPTION PAYMENTS & APPROVALS -->
                <div class="tab-pane fade" id="tab-saas-approvals" role="tabpanel">
                    <!-- Subscription Payment History & Approvals Table -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white overflow-hidden" id="subscriptionHistoryCard">
                        <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="font-weight-bold text-dark mb-0">
                                    <i class="fas fa-history text-primary me-2"></i> Admin Subscription Payments & Approvals
                                </h5>
                                <p class="text-muted small mb-0">
                                    Review, approve or modify expiration dates (+30 days default) for admin subscription payments
                                </p>
                            </div>
                            <span class="badge bg-light text-dark border px-3 py-2 rounded-pill font-weight-semibold" id="historyCountBadge">0 Payments</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="subscriptionHistoryTable">
                                    <thead class="bg-light text-uppercase text-muted small font-weight-bold" style="font-size: 11px;">
                                        <tr>
                                            <th class="ps-4">Sub ID / Date</th>
                                            <th>Plan & Cycle</th>
                                            <th>Amount</th>
                                            <th>Gateway & Contact</th>
                                            <th>TrxID</th>
                                            <th>Status</th>
                                            <th>Expiration Date</th>
                                            <th class="pe-4 text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="superAdminSubHistoryTbody" class="subscriptionHistoryTbody small">
                                        <!-- Dynamic rows rendered by script -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Modal for Super Admin to Edit Expiration Date -->
            <div class="modal fade" id="editExpiryModal" tabindex="-1" aria-labelledby="editExpiryModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                        <div class="modal-header bg-primary text-white py-3">
                            <h5 class="modal-title font-weight-bold text-white mb-0" id="editExpiryModalLabel">
                                <i class="fas fa-calendar-alt me-2"></i> Manage Subscription Expiration
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <input type="hidden" id="editExpirySubId">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold text-dark">Plan Name</label>
                                <input type="text" class="form-control bg-light" id="editExpiryPlanName" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold text-dark">Subscription Expiration Date</label>
                                <input type="date" class="form-control" id="editExpiryDateInput">
                                <div class="form-text small">Upon approval, expiration automatically increases by 30 days. You can also pick a custom date.</div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="addDaysToExpiryInput(30)">+ 30 Days</button>
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="addDaysToExpiryInput(60)">+ 60 Days</button>
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="addDaysToExpiryInput(365)">+ 1 Year</button>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0">
                            <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary rounded-pill px-4" onclick="saveUpdatedExpiryDate()">Save Expiry Date</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Modal for Admin Subscription Checkout -->
            <div class="modal fade" id="paySubscriptionModal" tabindex="-1" aria-labelledby="paySubscriptionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                        <div class="modal-header text-white border-0 py-3" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-primary text-white p-2.5 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                    <i class="fas fa-credit-card fs-5"></i>
                                </div>
                                <div>
                                    <h5 class="modal-title font-weight-bold text-white mb-0" id="paySubscriptionModalLabel">Subscribe & Checkout Payment</h5>
                                    <p class="text-white-50 mb-0 small">Select payment gateway to activate your admin SaaS subscription</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4 bg-light">
                            <!-- Selected Plan Summary Card -->
                            <div class="card border-0 shadow-sm rounded-3 mb-4 p-3 bg-white">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <span class="text-muted small text-uppercase font-weight-bold">Selected Subscription Plan</span>
                                        <h4 class="mb-0 font-weight-bold text-primary" id="payPlanName">Starter Plan</h4>
                                        <span class="badge bg-info text-white rounded-pill mt-1" id="payPlanCycle">Monthly Billing</span>
                                    </div>
                                    <div class="text-end">
                                        <span class="text-muted small d-block">Total Payable Amount</span>
                                        <h3 class="mb-0 font-weight-bold text-success" id="payPlanPrice">TK 1,200</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Gateways Selection -->
                            <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-wallet text-warning me-2"></i> Choose Payment Method</h6>
                            
                            <div class="row g-3 mb-4">
                                <div class="col-md-3 col-6">
                                    <label class="payment-method-card p-3 border rounded-3 text-center d-block bg-white shadow-sm cursor-pointer" onclick="selectPayGateway('bkash', this)">
                                        <input type="radio" name="pay_gateway" value="bkash" class="d-none" checked>
                                        <i class="fas fa-mobile-screen-button text-pink fs-3 d-block mb-1" style="color: #e2136e;"></i>
                                        <span class="font-weight-bold text-dark d-block small">bKash</span>
                                    </label>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="payment-method-card p-3 border rounded-3 text-center d-block bg-white shadow-sm cursor-pointer" onclick="selectPayGateway('nagad', this)">
                                        <input type="radio" name="pay_gateway" value="nagad" class="d-none">
                                        <i class="fas fa-wallet text-danger fs-3 d-block mb-1" style="color: #f7941d;"></i>
                                        <span class="font-weight-bold text-dark d-block small">Nagad</span>
                                    </label>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="payment-method-card p-3 border rounded-3 text-center d-block bg-white shadow-sm cursor-pointer" onclick="selectPayGateway('rocket', this)">
                                        <input type="radio" name="pay_gateway" value="rocket" class="d-none">
                                        <i class="fas fa-rocket text-primary fs-3 d-block mb-1"></i>
                                        <span class="font-weight-bold text-dark d-block small">Rocket</span>
                                    </label>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="payment-method-card p-3 border rounded-3 text-center d-block bg-white shadow-sm cursor-pointer" onclick="selectPayGateway('card', this)">
                                        <input type="radio" name="pay_gateway" value="card" class="d-none">
                                        <i class="fas fa-credit-card text-success fs-3 d-block mb-1"></i>
                                        <span class="font-weight-bold text-dark d-block small">Card / Bank</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Gateway Instructions & Form -->
                            <div class="card border p-3 rounded-3 bg-white mb-3">
                                <div class="alert bg-light border p-2.5 rounded-3 mb-3 small text-dark" id="payInstructions">
                                    <strong>bKash Payment (Super Admin: {{ $superAdminBkash }})</strong><br>
                                    Send exact payment to Super Admin bKash number <strong>{{ $superAdminBkash }}</strong> and fill in your sender mobile number and Transaction ID (TrxID) below.
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="paySenderPhone" class="form-label font-weight-semibold text-dark small">Sender Mobile / Account Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="paySenderPhone" placeholder="01712345678" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="payTrxId" class="form-label font-weight-semibold text-dark small">Transaction ID (TrxID) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="payTrxId" placeholder="e.g. TRX9823H12" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-white p-3">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-success rounded-pill px-5 font-weight-bold shadow py-2" onclick="confirmSubscriptionPayment()">
                                <i class="fas fa-check-circle me-1"></i> Confirm & Activate Subscription
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create/Edit Package Modal -->
            <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                        <div class="modal-header bg-dark text-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="modal-title font-weight-bold" id="packageModalLabel"><i class="fas fa-cubes text-info me-2"></i> Create New Subscription Plan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                            <form id="packageForm">
                                <input type="hidden" id="packageId">
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="packageName" class="form-label font-weight-bold">Plan Name</label>
                                        <input type="text" class="form-control" id="packageName" placeholder="e.g. Professional Premium" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="packageTheme" class="form-label font-weight-bold">Visual Card Theme</label>
                                        <select class="form-select" id="packageTheme">
                                            <option value="starter">Blue Glow (Starter)</option>
                                            <option value="pro">Indigo Purple (Pro)</option>
                                            <option value="enterprise">Warm Gold (Enterprise)</option>
                                            <option value="lifetime">Radiant Pink (Lifetime)</option>
                                            <option value="default">Dark Slate (Default)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="packageDetails" class="form-label font-weight-bold">Plan Summary / Subtitle</label>
                                    <textarea class="form-control" id="packageDetails" rows="2" placeholder="Brief tagline or description..." required></textarea>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="priceMonthly" class="form-label font-weight-bold">Monthly Rate (TK)</label>
                                        <input type="text" class="form-control" id="priceMonthly" placeholder="e.g. 2500" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="priceYearly" class="form-label font-weight-bold">Yearly Rate (TK)</label>
                                        <input type="text" class="form-control" id="priceYearly" placeholder="e.g. 25000" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="priceLifetime" class="form-label font-weight-bold">Lifetime Rate (TK)</label>
                                        <input type="text" class="form-control" id="priceLifetime" placeholder="e.g. 60000" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="packageStatus" checked>
                                        <label class="form-check-label font-weight-bold text-dark" for="packageStatus">Publish Subscription Plan</label>
                                    </div>
                                </div>

                                <h6 class="mb-3 font-weight-bold text-dark"><i class="fas fa-tasks text-primary me-2"></i> Features Allocation Matrix (<span id="selectedFeaturesCount">0</span> selected)</h6>

                                <div class="p-3 mb-4 rounded-3 border d-flex align-items-center gap-2 flex-wrap shadow-sm" style="background-color: #f1f5f9; border-color: #e2e8f0 !important;">
                                    <div class="position-relative" style="min-width: 220px;">
                                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 12px;"></i>
                                        <input type="text" id="packageFeatureSearch" class="form-control form-control-sm ps-5 bg-white border" placeholder="Search permissions..." style="border-radius: 8px; border-color: #cbd5e1;">
                                    </div>
                                    <button type="button" class="btn btn-sm bg-white border text-dark font-weight-semibold px-3 py-1.5 shadow-sm" onclick="selectAllModalFeatures(true)" style="border-radius: 8px; border-color: #cbd5e1 !important; font-size: 13px;">
                                        <i class="fas fa-check-double text-primary me-1"></i> Select all
                                    </button>
                                    <button type="button" class="btn btn-sm bg-white border text-dark font-weight-semibold px-3 py-1.5 shadow-sm" onclick="selectAllModalFeatures(false)" style="border-radius: 8px; border-color: #cbd5e1 !important; font-size: 13px;">
                                        <i class="fas fa-times text-danger me-1"></i> Clear
                                    </button>
                                    <button type="button" class="btn btn-sm bg-white border text-dark font-weight-semibold px-3 py-1.5 shadow-sm" onclick="toggleModalSections(true)" style="border-radius: 8px; border-color: #cbd5e1 !important; font-size: 13px;">
                                        <i class="fas fa-chevron-down text-info me-1"></i> Expand
                                    </button>
                                    <button type="button" class="btn btn-sm bg-white border text-dark font-weight-semibold px-3 py-1.5 shadow-sm" onclick="toggleModalSections(false)" style="border-radius: 8px; border-color: #cbd5e1 !important; font-size: 13px;">
                                        <i class="fas fa-chevron-up text-info me-1"></i> Collapse
                                    </button>
                                </div>

                                <div id="modalFeaturesContainer" class="accordion">
                                    <!-- Dynamic feature check list populated by JS -->
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer border-0 bg-light p-3">
                            <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary px-4 rounded-pill" onclick="savePackage()">Save Subscription Plan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manage Features Pool Modal -->
            <div class="modal fade" id="manageFeaturesModal" tabindex="-1" aria-labelledby="manageFeaturesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                        <div class="modal-header bg-dark text-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="modal-title font-weight-bold" id="manageFeaturesModalLabel"><i class="fas fa-list-check text-info me-2"></i> Manage Subscription Features Pool</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form id="newFeatureForm" onsubmit="addFeature(event)" class="mb-4 bg-light p-3 rounded-3 border">
                                <label for="newFeatureName" class="form-label font-weight-bold text-dark mb-2">Create Custom Feature Item</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="newFeatureName" placeholder="e.g. Dedicated Account Manager, Custom API Access" required>
                                    <button class="btn btn-success px-4 font-weight-bold" type="submit"><i class="fas fa-plus me-1"></i> Add Feature Item</button>
                                </div>
                            </form>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-cubes text-primary me-2"></i> Master Feature Pool (<span id="featuresPoolCount">0</span> items)</h6>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="resetToDefaultSidebarFeatures()" title="Reset to all default sidebar features">
                                    <i class="fas fa-rotate-left me-1"></i> Reset Sidebar Defaults
                                </button>
                            </div>

                            <div class="p-3 mb-4 rounded-3 border d-flex align-items-center gap-2 flex-wrap shadow-sm" style="background-color: #f1f5f9; border-color: #e2e8f0 !important;">
                                <div class="position-relative" style="min-width: 220px;">
                                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 12px;"></i>
                                    <input type="text" id="featuresPoolSearch" class="form-control form-control-sm ps-5 bg-white border" placeholder="Search pool..." oninput="filterFeaturesPoolList()" style="border-radius: 8px; border-color: #cbd5e1;">
                                </div>
                                <button type="button" class="btn btn-sm bg-white border text-dark font-weight-semibold px-3 py-1.5 shadow-sm" onclick="togglePoolSections(true)" style="border-radius: 8px; border-color: #cbd5e1 !important; font-size: 13px;">
                                    <i class="fas fa-chevron-down text-info me-1"></i> Expand
                                </button>
                                <button type="button" class="btn btn-sm bg-white border text-dark font-weight-semibold px-3 py-1.5 shadow-sm" onclick="togglePoolSections(false)" style="border-radius: 8px; border-color: #cbd5e1 !important; font-size: 13px;">
                                    <i class="fas fa-chevron-up text-info me-1"></i> Collapse
                                </button>
                            </div>

                            <div id="featuresListContainer" class="accordion">
                                <!-- Grouped Category Cards Populated by JS -->
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light p-3">
                            <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- ORIGINAL USERS WORKSPACE (Redesigned Workspace) -->
            @php
                $totalUsersCount = count($users);
                $adminUsersCount = $users->filter(function($u) {
                    return $u->getRoleNames()->contains('admin') || $u->getRoleNames()->contains('super-admin');
                })->count();
                $standardUsersCount = $totalUsersCount - $adminUsersCount;
            @endphp

            <!-- KPI Cards Row -->
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-1">
                        <div>
                            <div class="stat-card-title">Total Registered Users</div>
                            <div class="stat-card-value">{{ $totalUsersCount }}</div>
                        </div>
                        <i class="fas fa-users stat-card-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-2">
                        <div>
                            <div class="stat-card-title">System Administrators</div>
                            <div class="stat-card-value">{{ $adminUsersCount }}</div>
                        </div>
                        <i class="fas fa-user-shield stat-card-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-3">
                        <div>
                            <div class="stat-card-title">Customers & Members</div>
                            <div class="stat-card-value">{{ $standardUsersCount }}</div>
                        </div>
                        <i class="fas fa-user-tag stat-card-icon"></i>
                    </div>
                </div>
            </div>

            <!-- Table Workspace Wrapper -->
            <div class="workspace-card">
                <div class="premium-actions-bar">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="mb-0 font-weight-bold" style="color: var(--dark-slate);">Registered Accounts</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-danger rounded-pill px-4" id="deleteSelectedBtn" disabled onclick="deleteSelected()">
                            <i class="fas fa-trash me-2"></i> Delete Selected
                        </button>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-user-plus me-2"></i> Add Account
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table premium-table" id="users">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">
                                    <input type="checkbox" id="selectAll" class="form-check-input" onchange="toggleSelectAll()">
                                </th>
                                <th>UID</th>
                                <th>S/N</th>
                                <th>Full Name</th>
                                <th>Email Address</th>
                                <th>Assigned Role</th>
                                <th>Registration Date</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="checkbox" class="user-checkbox form-check-input" value="{{ $user->id }}" onchange="updateDeleteButton()">
                                    </td>
                                    <td><strong>#{{ $user->id }}</strong></td>
                                    <td></td>
                                    <td>{{ $user->name }}</td>
                                    <td><span class="text-muted">{{ $user->email }}</span></td>
                                    <td>
                                        @if($user->getRoleNames()->count())
                                            @foreach($user->getRoleNames() as $role)
                                                @if($role === 'admin' || $role === 'super-admin')
                                                    <span class="badge-premium badge-premium-admin"><i class="fas fa-shield-alt"></i> {{ $role }}</span>
                                                @else
                                                    <span class="badge-premium badge-premium-info"><i class="fas fa-user-circle"></i> {{ $role }}</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="badge-premium badge-premium-secondary">customer</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.users.show', $user->id) }}" 
                                               class="btn-action-circle btn-action-view" title="View Account Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}" 
                                               class="btn-action-circle btn-action-edit" title="Edit Details">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" 
                                                  method="POST" class="d-inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-circle btn-action-delete" 
                                                        title="Remove Account" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

    @if(request()->get('view') === 'packages')
        @php
            $rawDbPermissions = \Spatie\Permission\Models\Permission::all()->pluck('name')->toArray();
            // Transform permission names into clean display names while maintaining mapping
            $dbFeatureList = array_map(function($perm) {
                return str_replace(['.', '_', '-'], [': ', ' ', ' '], ucwords($perm, '.'));
            }, $rawDbPermissions);
        @endphp
        <script>
            // DIRECTLY FETCHED DATABASE PERMISSIONS & SIDEBAR FEATURES
            const DIRECT_DB_PERMISSIONS = @json($rawDbPermissions);
            const DIRECT_DB_FORMATTED_FEATURES = @json($dbFeatureList);
            
            const SIDEBAR_FEATURE_GROUPS = [
                {
                    category: "Main Dashboard",
                    icon: "fas fa-chart-pie text-primary",
                    items: [
                        "Dashboard Overview"
                    ]
                },
                {
                    category: "Product Catalog",
                    icon: "fas fa-cubes text-info",
                    items: [
                        "All Products Management",
                        "Add New Product",
                        "Product Categories & Subcategories",
                        "Brands Management",
                        "Writers & Authors",
                        "Publishers Management",
                        "Product Reviews & Feedback",
                        "Combo Offers & Bundles"
                    ]
                },
                {
                    category: "Inventory & Stock",
                    icon: "fas fa-warehouse text-success",
                    items: [
                        "Inventory Overview",
                        "Low Stock Alerts",
                        "Out of Stock Items",
                        "Stock Movement History"
                    ]
                },
                {
                    category: "Landing Pages",
                    icon: "fas fa-pager text-warning",
                    items: [
                        "Landing Pages Directory",
                        "Create & Design Landing Page"
                    ]
                },
                {
                    category: "Sales & Orders",
                    icon: "fas fa-cart-shopping text-primary",
                    items: [
                        "All Orders Management",
                        "My Assigned Orders",
                        "Incomplete & Abandoned Orders",
                        "Point of Sale (POS) Terminal"
                    ]
                },
                {
                    category: "Shipping & Delivery",
                    icon: "fas fa-truck-fast text-emerald",
                    items: [
                        "Global Shipping Settings",
                        "Advanced Shipping Rules & Delivery Zones",
                        "Courier Integrations (Pathao/Steadfast)"
                    ]
                },
                {
                    category: "Reports & Insights",
                    icon: "fas fa-chart-column text-warning",
                    items: [
                        "Sales & Revenue Reports",
                        "Customer Analytics Reports"
                    ]
                },
                {
                    category: "Connected Apps & Integrations",
                    icon: "fas fa-plug text-danger",
                    items: [
                        "Daraz Marketplace Sync",
                        "WooCommerce Data Migration",
                        "Telegram Notifications Bot",
                        "Delayed Purchase Event Queue"
                    ]
                },
                {
                    category: "Security & Trust",
                    icon: "fas fa-shield-halved text-danger",
                    items: [
                        "Fraud Checker & Risk Scanner",
                        "Fraud Protection Shield",
                        "System Backup & Schedules"
                    ]
                },
                {
                    category: "Content & Pages",
                    icon: "fas fa-newspaper text-secondary",
                    items: [
                        "Hero Sliders Management",
                        "Custom Web Pages",
                        "Navigation Menu Builder",
                        "Blog Posts Management",
                        "Blog Categories & Topics",
                        "Post Comments Moderation"
                    ]
                },
                {
                    category: "Multi-Vendor Management",
                    icon: "fas fa-store text-info",
                    items: [
                        "Vendor Directory & Approvals",
                        "Add New Vendor",
                        "Vendor Product Approval",
                        "Vendor Withdrawal Requests",
                        "Global Vendor Commission Settings"
                    ]
                },
                {
                    category: "System Settings & Control",
                    icon: "fas fa-gears text-secondary",
                    items: [
                        "Users & Team Members Management",
                        "Contact Messages Inbox",
                        "Newsletter Subscriptions",
                        "Roles & Permissions Matrix",
                        "System Extensions & Modules",
                        "All Website Settings",
                        "Payment Gateway Setup",
                        "License Key Management",
                        "System Updates & Version Control"
                    ]
                }
            ];

            // Clean sidebar items pool
            const SIDEBAR_ITEMS = SIDEBAR_FEATURE_GROUPS.flatMap(g => g.items);
            const DEFAULT_FEATURES = SIDEBAR_ITEMS;

            const DEFAULT_PACKAGES = [
                {
                    id: "starter_plan",
                    name: "Starter Plan",
                    theme: "starter",
                    details: "Ideal for fresh startups and small stores requiring core ecommerce functionality.",
                    priceMonthly: "1200",
                    priceYearly: "12000",
                    priceLifetime: "30000",
                    status: true,
                    features: [
                        "Dashboard Overview",
                        "All Products Management",
                        "Add New Product",
                        "Product Categories & Subcategories",
                        "Inventory Overview",
                        "All Orders Management",
                        "Global Shipping Settings",
                        "All Website Settings"
                    ]
                },
                {
                    id: "pro_plan",
                    name: "Professional Plan",
                    theme: "pro",
                    details: "Perfect for growing merchants and professional retailers needing advanced tools.",
                    priceMonthly: "3500",
                    priceYearly: "35000",
                    priceLifetime: "80000",
                    status: true,
                    features: [
                        "Dashboard Overview",
                        "All Products Management",
                        "Add New Product",
                        "Product Categories & Subcategories",
                        "Brands Management",
                        "Product Reviews & Feedback",
                        "Inventory Overview",
                        "Low Stock Alerts",
                        "Landing Pages Directory",
                        "All Orders Management",
                        "Point of Sale (POS) Terminal",
                        "Global Shipping Settings",
                        "Courier Integrations (Pathao/Steadfast)",
                        "Sales & Revenue Reports",
                        "Fraud Checker & Risk Scanner",
                        "Roles & Permissions Matrix",
                        "Payment Gateway Setup"
                    ]
                },
                {
                    id: "enterprise_plan",
                    name: "Enterprise Ultimate",
                    theme: "enterprise",
                    details: "Tailored specifically for large-scale multi-vendor operations and enterprise networks.",
                    priceMonthly: "8500",
                    priceYearly: "85000",
                    priceLifetime: "200000",
                    status: true,
                    features: DEFAULT_FEATURES
                }
            ];

            // LocalStorage Keys
            const FEATURES_KEY = "admin_packages_features_pool_v3";
            const PACKAGES_KEY = "admin_packages_list_v3";

            window.IS_SUPER_ADMIN = {{ (auth()->user()?->hasRole('super_admin') || auth()->user()?->hasRole('super admin')) ? 'true' : 'false' }};
            let featuresPool = [];
            let packagesList = [];
            let currentBillingCycle = 'monthly';
            let selectedCheckoutPackage = null;

            // Initialize Data
            function initData() {
                const storedFeatures = localStorage.getItem(FEATURES_KEY);
                const storedPackages = localStorage.getItem(PACKAGES_KEY);

                try {
                    featuresPool = storedFeatures ? JSON.parse(storedFeatures) : [];
                } catch(e) {
                    featuresPool = [];
                }

                if (!Array.isArray(featuresPool) || featuresPool.length === 0) {
                    featuresPool = DEFAULT_FEATURES;
                    localStorage.setItem(FEATURES_KEY, JSON.stringify(DEFAULT_FEATURES));
                }

                try {
                    packagesList = storedPackages ? JSON.parse(storedPackages) : [];
                } catch(e) {
                    packagesList = [];
                }

                if (!Array.isArray(packagesList) || packagesList.length === 0) {
                    packagesList = DEFAULT_PACKAGES;
                    localStorage.setItem(PACKAGES_KEY, JSON.stringify(DEFAULT_PACKAGES));
                }
            }

            window.setBillingCycle = function(cycle, btnEl) {
                currentBillingCycle = cycle;
                document.querySelectorAll('#billingCycleGroup .cycle-btn').forEach(b => {
                    b.classList.remove('btn-primary', 'text-white', 'active');
                    b.classList.add('btn-light');
                });
                btnEl.classList.remove('btn-light');
                btnEl.classList.add('btn-primary', 'text-white', 'active');
                renderWorkspace();
            };

            const SUPER_ADMIN_BKASH = "{{ $superAdminBkash }}";
            const SUPER_ADMIN_NAGAD = "{{ $superAdminNagad }}";
            const SUPER_ADMIN_ROCKET = "{{ $superAdminRocket }}";

            window.selectPayGateway = function(gateway, labelEl) {
                document.querySelectorAll('.payment-method-card').forEach(c => {
                    c.style.borderColor = '#e2e8f0';
                    c.style.backgroundColor = '#ffffff';
                });
                labelEl.style.borderColor = '#2563eb';
                labelEl.style.backgroundColor = '#eff6ff';
                const radio = labelEl.querySelector('input[type="radio"]');
                if (radio) radio.checked = true;

                const instr = document.getElementById('payInstructions');
                if (gateway === 'bkash') {
                    instr.innerHTML = `<strong>bKash Payment (Super Admin: ${SUPER_ADMIN_BKASH})</strong><br>Send exact payment to Super Admin bKash number <strong>${SUPER_ADMIN_BKASH}</strong> and fill in your sender mobile number and Transaction ID (TrxID) below.`;
                } else if (gateway === 'nagad') {
                    instr.innerHTML = `<strong>Nagad Payment (Super Admin: ${SUPER_ADMIN_NAGAD})</strong><br>Send exact payment to Super Admin Nagad number <strong>${SUPER_ADMIN_NAGAD}</strong> and fill in your sender mobile number and Transaction ID (TrxID) below.`;
                } else if (gateway === 'rocket') {
                    instr.innerHTML = `<strong>Rocket Payment (Super Admin: ${SUPER_ADMIN_ROCKET})</strong><br>Send exact payment to Super Admin Rocket number <strong>${SUPER_ADMIN_ROCKET}</strong> and fill in your account number and Transaction ID below.`;
                } else {
                    instr.innerHTML = `<strong>Credit / Debit Card (SSLCommerz)</strong><br>Click 'Confirm & Activate Subscription' to be redirected to our SSLCommerz secure payment gateway window.`;
                }
            };

            window.openCheckoutModal = function(pkgId, pkgName, price, cycleText) {
                selectedCheckoutPackage = { id: pkgId, name: pkgName, price: price, cycle: cycleText };
                document.getElementById('payPlanName').textContent = pkgName;
                document.getElementById('payPlanCycle').textContent = cycleText;
                document.getElementById('payPlanPrice').textContent = 'TK ' + Number(price).toLocaleString();
                
                const modal = new bootstrap.Modal(document.getElementById('paySubscriptionModal'));
                modal.show();
            };

            // ─── Subscription Payments: Real DB API ────────────────────────────────
            const SUB_API_INDEX  = '{{ route("admin.subscription-payments.index") }}';
            const SUB_API_STORE  = '{{ route("admin.subscription-payments.store") }}';
            const CSRF_TOKEN     = '{{ csrf_token() }}';

            // In-memory cache so the table renders instantly; refreshed on every page load
            window._subPaymentsCache = [];

            function apiFetch(url, method, body) {
                const opts = {
                    method: method || 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    }
                };
                if (body) {
                    opts.headers['Content-Type'] = 'application/json';
                    opts.body = JSON.stringify(body);
                }
                return fetch(url, opts).then(function(r) {
                    if (!r.ok) {
                        return r.text().then(function(text) {
                            console.error('[API Error] ' + method + ' ' + url, r.status, text.substring(0, 500));
                            throw new Error('HTTP ' + r.status + ': ' + text.substring(0, 200));
                        });
                    }
                    const contentType = r.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        return r.json();
                    }
                    return r.text().then(function(text) {
                        console.warn('[API] Non-JSON response from ' + url, text.substring(0, 200));
                        return {};
                    });
                });
            }

            // Load all payments from DB and re-render table
            function loadSubscriptionHistory(callback) {
                apiFetch(SUB_API_INDEX)
                    .then(data => {
                        window._subPaymentsCache = Array.isArray(data) ? data : [];
                        renderSubscriptionHistoryTable();
                        if (callback) callback(window._subPaymentsCache);
                    })
                    .catch(err => {
                        console.error('Failed to load payments:', err);
                        renderSubscriptionHistoryTable();
                    });
            }

            window.confirmSubscriptionPayment = function() {
                const phone   = document.getElementById('paySenderPhone')?.value || '';
                const trxId   = document.getElementById('payTrxId')?.value || 'TRX' + Math.floor(Math.random()*900000 + 100000);
                const gateway = document.querySelector('input[name="pay_gateway"]:checked')?.value || 'bKash';

                if (!selectedCheckoutPackage) return;

                const subId = 'SUB-' + Math.floor(Math.random()*90000 + 10000);

                apiFetch(SUB_API_STORE, 'POST', {
                    sub_id:  subId,
                    plan:    selectedCheckoutPackage.name || 'Subscription Plan',
                    cycle:   selectedCheckoutPackage.cycle || 'Monthly',
                    price:   String(selectedCheckoutPackage.price || '0'),
                    gateway: gateway,
                    phone:   phone,
                    trx_id:  trxId,
                })
                .then(() => {
                    const modalEl = document.getElementById('paySubscriptionModal');
                    const modal   = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    if (typeof toastr !== 'undefined') {
                        toastr.info('Payment submitted! Pending Super Admin approval.', 'Payment Pending');
                    }
                    loadSubscriptionHistory();
                })
                .catch(err => {
                    console.error('Store payment failed:', err);
                    alert('Failed to save payment: ' + (err.message || 'Unknown error'));
                });
            };

            window.approveSubscriptionItem = function(subId, customExpiry = null) {
                let expiryDateStr = customExpiry;
                if (!expiryDateStr) {
                    const d = new Date();
                    d.setDate(d.getDate() + 30);
                    expiryDateStr = d.toISOString().split('T')[0];
                }

                apiFetch(`{{ url('admin/subscription-payments') }}/${subId}`, 'PATCH', {
                    status:      'Approved',
                    expiry_date: expiryDateStr,
                    _token:      CSRF_TOKEN,
                })
                .then(() => {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(`Payment ${subId} approved! Expires ${expiryDateStr}.`, 'Approved');
                    }
                    loadSubscriptionHistory();
                })
                .catch(err => console.error('Approve failed:', err));
            };

            window.rejectSubscriptionItem = function(subId) {
                if (!confirm(`Reject payment ${subId}?`)) return;

                apiFetch(`{{ url('admin/subscription-payments') }}/${subId}`, 'PATCH', {
                    status: 'Rejected',
                    _token: CSRF_TOKEN,
                })
                .then(() => {
                    if (typeof toastr !== 'undefined') {
                        toastr.warning(`Payment ${subId} rejected.`, 'Rejected');
                    }
                    loadSubscriptionHistory();
                })
                .catch(err => console.error('Reject failed:', err));
            };

            window.openEditExpiryModal = function(subId) {
                const item = window._subPaymentsCache.find(h => h.id === subId);
                if (!item) return;

                document.getElementById('editExpirySubId').value = item.id;
                document.getElementById('editExpiryPlanName').value = (item.plan || 'Plan') + ' (' + item.cycle + ')';

                let initialDate = item.expiryDate;
                if (!initialDate) {
                    const d = new Date();
                    d.setDate(d.getDate() + 30);
                    initialDate = d.toISOString().split('T')[0];
                }
                document.getElementById('editExpiryDateInput').value = initialDate;

                const modal = new bootstrap.Modal(document.getElementById('editExpiryModal'));
                modal.show();
            };

            window.addDaysToExpiryInput = function(days) {
                const currentVal = document.getElementById('editExpiryDateInput').value;
                const baseDate   = currentVal ? new Date(currentVal) : new Date();
                baseDate.setDate(baseDate.getDate() + days);
                document.getElementById('editExpiryDateInput').value = baseDate.toISOString().split('T')[0];
            };

            window.saveUpdatedExpiryDate = function() {
                const subId    = document.getElementById('editExpirySubId').value;
                const newExpiry = document.getElementById('editExpiryDateInput').value;
                if (!subId || !newExpiry) return;

                apiFetch(`{{ url('admin/subscription-payments') }}/${subId}`, 'PATCH', {
                    status:      'Approved',
                    expiry_date: newExpiry,
                    _token:      CSRF_TOKEN,
                })
                .then(() => {
                    const modalEl = document.getElementById('editExpiryModal');
                    const modal   = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();

                    if (typeof toastr !== 'undefined') {
                        toastr.success(`Expiration set to ${newExpiry}.`, 'Expiry Updated');
                    }
                    loadSubscriptionHistory();
                })
                .catch(err => console.error('Save expiry failed:', err));
            };

            function renderSubscriptionHistoryTable() {
                const history    = window._subPaymentsCache;

                const tbodies = document.querySelectorAll('.subscriptionHistoryTbody');
                const countBadges = document.querySelectorAll('#historyCountBadge');
                if (!tbodies || tbodies.length === 0) return;

                countBadges.forEach(b => {
                    b.textContent = history.length + ' Payments';
                });

                tbodies.forEach(tbody => {
                    tbody.innerHTML = '';

                    if (history.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted"><i class="fas fa-inbox me-2"></i> No subscription payments recorded yet.</td></tr>`;
                        return;
                    }

                    history.forEach(item => {
                        const tr = document.createElement('tr');

                        let statusBadgeHTML = '';
                        if (item.status === 'Approved') {
                            statusBadgeHTML = `<span class="badge bg-success text-white px-2.5 py-1 rounded-pill"><i class="fas fa-check-circle me-1"></i> Approved</span>`;
                        } else if (item.status === 'Pending') {
                            statusBadgeHTML = `<span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill"><i class="fas fa-clock me-1"></i> Pending Approval</span>`;
                        } else {
                            statusBadgeHTML = `<span class="badge bg-danger text-white px-2.5 py-1 rounded-pill"><i class="fas fa-times-circle me-1"></i> Rejected</span>`;
                        }

                        const printUrl = `{{ url('admin/subscription-payments') }}/${item.id}/print-invoice`;
                        const downloadUrl = `{{ url('admin/subscription-payments') }}/${item.id}/download-invoice`;

                        const invoiceButtonsHTML = `
                            <a href="${printUrl}" target="_blank" class="btn btn-sm btn-info text-white rounded-pill px-2.5 py-1 font-weight-bold shadow-sm" title="Print Invoice">
                                <i class="fas fa-print me-1"></i> Print
                            </a>
                            <a href="${downloadUrl}" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 font-weight-bold shadow-sm" title="Download PDF Invoice">
                                <i class="fas fa-download me-1"></i> PDF
                            </a>
                        `;

                        let actionButtonsHTML = '';
                        if (window.IS_SUPER_ADMIN) {
                            if (item.status === 'Pending') {
                                actionButtonsHTML = `
                                    <div class="d-flex gap-1 justify-content-end align-items-center">
                                        <button class="btn btn-sm btn-success rounded-pill px-2 py-1 font-weight-bold" onclick="approveSubscriptionItem('${item.id}')" title="Approve Payment (+30 days default)">
                                            <i class="fas fa-check me-1"></i> Approve
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 font-weight-bold" onclick="rejectSubscriptionItem('${item.id}')" title="Reject Payment">
                                            <i class="fas fa-times me-1"></i> Reject
                                        </button>
                                        ${invoiceButtonsHTML}
                                    </div>
                                `;
                            } else {
                                actionButtonsHTML = `
                                    <div class="d-flex gap-1 justify-content-end align-items-center">
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 font-weight-semibold" onclick="openEditExpiryModal('${item.id}')">
                                            <i class="fas fa-calendar-alt me-1"></i> Edit Expiry
                                        </button>
                                        ${invoiceButtonsHTML}
                                    </div>
                                `;
                            }
                        } else {
                            actionButtonsHTML = `
                                <div class="d-flex gap-2 justify-content-end align-items-center">
                                    <span class="text-muted small">${item.status === 'Pending' ? 'Awaiting Super Admin' : 'Completed'}</span>
                                    ${invoiceButtonsHTML}
                                </div>
                            `;
                        }

                        const expiryDisplay = item.expiryDate 
                            ? `<span class="fw-bold text-dark"><i class="fas fa-calendar-check text-success me-1"></i>${item.expiryDate}</span>` 
                            : `<span class="text-warning small italic"><i class="fas fa-hourglass-start me-1"></i>Awaiting Approval</span>`;

                        const adminBadgeHTML = window.IS_SUPER_ADMIN && item.adminName
                            ? `<span class="d-block text-primary small font-weight-bold"><i class="fas fa-user-shield me-1"></i>${item.adminName}</span>`
                            : '';

                        tr.innerHTML = `
                            <td class="ps-4">
                                <strong class="text-dark d-block">${item.id}</strong>
                                ${adminBadgeHTML}
                                <span class="text-muted small">${item.date}</span>
                            </td>
                            <td>
                                <strong class="text-primary d-block">${item.plan}</strong>
                                <span class="badge bg-light text-dark border font-weight-normal">${item.cycle}</span>
                            </td>
                            <td class="font-weight-bold text-success">
                                TK ${Number(item.price).toLocaleString()}
                            </td>
                            <td>
                                <span class="badge bg-secondary text-white">${item.gateway}</span>
                                <span class="d-block text-muted small">${item.phone}</span>
                            </td>
                            <td>
                                <code class="text-dark bg-light px-2 py-1 rounded border">${item.trxId}</code>
                            </td>
                            <td>${statusBadgeHTML}</td>
                            <td>${expiryDisplay}</td>
                            <td class="pe-4 text-end">${actionButtonsHTML}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                });

                // Update Tab 1 Active Subscription Overview Card for Admin
                updateActiveSubCard(history);
            }

            window.switchToRenewTab = function() {
                const renewBtn = document.getElementById('tab-renew-sub-btn');
                if (renewBtn) {
                    const tab = new bootstrap.Tab(renewBtn);
                    tab.show();
                }
            };

            function updateActiveSubCard(history) {
                const planTitleEl   = document.getElementById('activeSubPlanTitle');
                const cycleTitleEl  = document.getElementById('activeSubCycleTitle');
                const statusBadgeEl = document.getElementById('activeSubStatusBadge');
                const expiryDateEl  = document.getElementById('activeSubExpiryDate');
                const daysLeftEl    = document.getElementById('activeSubDaysLeft');

                if (!planTitleEl) return;

                const approved = history.find(h => h.status === 'Approved');
                const pending  = history.find(h => h.status === 'Pending');
                const activeItem = approved || pending || (history.length > 0 ? history[0] : null);

                if (!activeItem) {
                    planTitleEl.textContent = 'Free Trial / Starter';
                    if (cycleTitleEl) cycleTitleEl.textContent = 'No Active Paid Subscription';
                    if (statusBadgeEl) statusBadgeEl.innerHTML = `<span class="badge bg-secondary text-white px-3 py-2 rounded-pill font-weight-bold"><i class="fas fa-info-circle me-1"></i> Inactive</span>`;
                    if (expiryDateEl) expiryDateEl.textContent = 'N/A';
                    if (daysLeftEl) daysLeftEl.innerHTML = `<span class="text-muted small">No active plan recorded</span>`;
                    return;
                }

                planTitleEl.textContent = activeItem.plan || 'Starter Plan';
                if (cycleTitleEl) cycleTitleEl.textContent = activeItem.cycle || 'Monthly Billing';

                if (activeItem.status === 'Approved') {
                    if (statusBadgeEl) statusBadgeEl.innerHTML = `<span class="badge bg-success text-white px-3 py-2 rounded-pill font-weight-bold"><i class="fas fa-check-circle me-1"></i> Active</span>`;

                    if (activeItem.expiryDate) {
                        if (expiryDateEl) expiryDateEl.textContent = activeItem.expiryDate;
                        
                        const expParts = activeItem.expiryDate.split('-');
                        const expiry = new Date(expParts[0], expParts[1] - 1, expParts[2]);
                        const today  = new Date();
                        today.setHours(0,0,0,0);
                        const diffDays = Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));

                        if (daysLeftEl) {
                            if (diffDays > 0) {
                                daysLeftEl.innerHTML = `<span class="badge bg-primary text-white px-2 py-1 rounded-pill"><i class="fas fa-hourglass-half me-1"></i> ${diffDays} Days Remaining</span>`;
                            } else if (diffDays === 0) {
                                daysLeftEl.innerHTML = `<span class="badge bg-warning text-dark px-2 py-1 rounded-pill"><i class="fas fa-exclamation-triangle me-1"></i> Expires Today</span>`;
                            } else {
                                daysLeftEl.innerHTML = `<span class="badge bg-danger text-white px-2 py-1 rounded-pill"><i class="fas fa-times-circle me-1"></i> Expired</span>`;
                            }
                        }
                    } else if (activeItem.cycle && activeItem.cycle.toLowerCase().includes('lifetime')) {
                        if (expiryDateEl) expiryDateEl.textContent = 'Never Expires';
                        if (daysLeftEl) daysLeftEl.innerHTML = `<span class="badge bg-success text-white px-2 py-1 rounded-pill"><i class="fas fa-infinity me-1"></i> Lifetime Access</span>`;
                    }
                } else if (activeItem.status === 'Pending') {
                    if (statusBadgeEl) statusBadgeEl.innerHTML = `<span class="badge bg-warning text-dark px-3 py-2 rounded-pill font-weight-bold"><i class="fas fa-clock me-1"></i> Pending Approval</span>`;
                    if (expiryDateEl) expiryDateEl.textContent = 'Awaiting Super Admin';
                    if (daysLeftEl) daysLeftEl.innerHTML = `<span class="text-muted small">Submitted: ${activeItem.date || 'Today'}</span>`;
                } else {
                    if (statusBadgeEl) statusBadgeEl.innerHTML = `<span class="badge bg-danger text-white px-3 py-2 rounded-pill font-weight-bold"><i class="fas fa-times-circle me-1"></i> Rejected</span>`;
                    if (expiryDateEl) expiryDateEl.textContent = 'Payment Rejected';
                    if (daysLeftEl) daysLeftEl.innerHTML = `<span class="text-muted small">Please resubmit payment</span>`;
                }
            }

            // Render Dashboard Stats and Grid
            function renderWorkspace() {
                initData();
                
                // Update Counts safely if elements exist in DOM (super admin view)
                const totalPkgEl = document.getElementById('totalPackagesCount');
                if (totalPkgEl) totalPkgEl.textContent = packagesList.length + 1;
                const activePkgEl = document.getElementById('activePackagesCount');
                if (activePkgEl) activePkgEl.textContent = packagesList.filter(p => p.status).length + 1;
                const totalFeatEl = document.getElementById('totalFeaturesCount');
                if (totalFeatEl) totalFeatEl.textContent = featuresPool.length;

                // Render Grid
                const grid = document.getElementById('packagesGrid');
                grid.innerHTML = '';

                // Active Subscription Status
                const activeSub = JSON.parse(localStorage.getItem('active_admin_subscription_v3') || 'null');
                const activePlanDisp = document.getElementById('activePlanNameDisplay');
                if (activePlanDisp && activeSub) {
                    let text = activeSub.plan + ' (' + activeSub.cycle + ')';
                    if (activeSub.expiryDate) {
                        text += ' | Expires: ' + activeSub.expiryDate;
                    }
                    activePlanDisp.textContent = text;
                }

                packagesList.forEach(pkg => {
                    const cardCol = document.createElement('div');
                    cardCol.className = 'col-md-4 mb-4';

                    let featuresHTML = '';
                    const totalFeaturesCount = featuresPool.length;
                    const includedCount = pkg.features ? pkg.features.length : 0;

                    let displayPrice = pkg.priceMonthly;
                    let cycleText = 'Monthly Billing';
                    if (currentBillingCycle === 'yearly') {
                        displayPrice = pkg.priceYearly;
                        cycleText = 'Yearly Billing (20% Off)';
                    } else if (currentBillingCycle === 'lifetime') {
                        displayPrice = pkg.priceLifetime;
                        cycleText = 'Lifetime Access';
                    }

                    const isCurrentSub = activeSub && activeSub.plan === pkg.name;

                    // Group features by category for display
                    SIDEBAR_FEATURE_GROUPS.forEach(group => {
                        const poolCategoryItems = group.items.filter(item => featuresPool.includes(item));
                        if (poolCategoryItems.length > 0) {
                            const groupIncludedItems = poolCategoryItems.filter(item => pkg.features && pkg.features.includes(item));
                            featuresHTML += `
                                <div class="mb-3">
                                    <div class="small font-weight-bold text-muted text-uppercase mb-1 border-bottom pb-1" style="font-size: 10px; letter-spacing: 0.5px;">
                                        <i class="${group.icon} me-1"></i> ${group.category} (${groupIncludedItems.length}/${poolCategoryItems.length})
                                    </div>
                            `;
                            poolCategoryItems.forEach(feat => {
                                const isIncluded = pkg.features && pkg.features.includes(feat);
                                const iconHTML = isIncluded 
                                    ? `<i class="fas fa-check-circle me-2" style="color: #10b981 !important; font-size: 14px; flex-shrink: 0;"></i>` 
                                    : `<i class="fas fa-times-circle me-2" style="color: #ef4444 !important; font-size: 14px; flex-shrink: 0;"></i>`;
                                featuresHTML += `
                                    <div class="feature-item py-1 d-flex align-items-center">
                                        ${iconHTML}
                                        <span style="font-size: 12px; ${isIncluded ? 'color: #0f172a; font-weight: 600;' : 'color: #94a3b8; text-decoration: line-through;'}">${feat}</span>
                                    </div>
                                `;
                            });
                            featuresHTML += `</div>`;
                        }
                    });

                    let cardFooterHTML = '';
                    if (window.IS_SUPER_ADMIN) {
                        cardFooterHTML = `
                            <div class="card-footer bg-light border-0 p-3 d-flex justify-content-between align-items-center gap-2 mt-auto">
                                <button class="btn btn-primary rounded-pill px-3 py-2 flex-grow-1 font-weight-bold d-flex align-items-center justify-content-center gap-2" onclick="openEditModal('${pkg.id}')">
                                    <i class="fas fa-edit"></i> Edit Plan
                                </button>
                                <button class="btn btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" onclick="deletePackage('${pkg.id}')" title="Delete Plan" style="width: 38px; height: 38px; min-width: 38px; padding: 0;">
                                    <i class="fas fa-trash-alt" style="font-size: 14px;"></i>
                                </button>
                            </div>
                        `;
                    } else {
                        cardFooterHTML = `
                            <div class="card-footer bg-light border-0 p-3 d-flex flex-column gap-2 mt-auto">
                                <button class="btn btn-success rounded-pill w-100 font-weight-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="openCheckoutModal('${pkg.id}', '${pkg.name}', '${displayPrice}', '${cycleText}')">
                                    <i class="fas fa-shopping-cart"></i> ${isCurrentSub ? 'Renew / Upgrade Plan' : 'Subscribe & Pay Now'}
                                </button>
                            </div>
                        `;
                    }

                    cardCol.innerHTML = `
                        <div class="premium-card h-100 d-flex flex-column ${isCurrentSub ? 'border border-2 border-success shadow-lg' : ''}">
                            <div class="gradient-header ${pkg.theme || 'default'}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    ${isCurrentSub ? '<span class="badge bg-warning text-dark font-weight-bold"><i class="fas fa-star me-1"></i> Current Active Plan</span>' : `<span class="badge ${pkg.status ? 'bg-success' : 'bg-secondary'}">${pkg.status ? 'Active' : 'Inactive'}</span>`}
                                    <span class="badge bg-white text-dark font-weight-bold" style="font-size: 11px;">${includedCount}/${totalFeaturesCount} Features</span>
                                </div>
                                <h4 class="mb-1 font-weight-bold" style="color: #fff;">${pkg.name}</h4>
                                <p class="small mb-0 opacity-80" style="color: rgba(255,255,255,0.85); min-height: 38px;">${pkg.details}</p>
                                <div class="package-price-badge">TK ${Number(displayPrice).toLocaleString()} / ${currentBillingCycle}</div>
                            </div>
                            <div class="card-body p-3 flex-grow-1 d-flex flex-column">
                                <div class="row text-center mb-3 border-bottom pb-2">
                                    <div class="col-4 border-end">
                                        <span class="text-muted d-block small" style="font-size: 10px;">Monthly</span>
                                        <strong class="text-dark small">TK ${pkg.priceMonthly}</strong>
                                    </div>
                                    <div class="col-4 border-end">
                                        <span class="text-muted d-block small" style="font-size: 10px;">Yearly</span>
                                        <strong class="text-dark small">TK ${pkg.priceYearly}</strong>
                                    </div>
                                    <div class="col-4">
                                        <span class="text-muted d-block small" style="font-size: 10px;">Lifetime</span>
                                        <strong class="text-dark small">TK ${pkg.priceLifetime}</strong>
                                    </div>
                                </div>
                                <div class="features-list-wrapper mb-3 flex-grow-1" style="max-height: 250px; overflow-y: auto; padding-right: 5px;">
                                    ${featuresHTML}
                                </div>
                            </div>
                            ${cardFooterHTML}
                        </div>
                    `;
                    grid.appendChild(cardCol);
                });

                // Append Custom Package Card for standard admins only
                if (!window.IS_SUPER_ADMIN) {
                    const customCardCol = document.createElement('div');
                    customCardCol.className = 'col-md-4 mb-4';
                    customCardCol.innerHTML = `
                        <div class="premium-card h-100 d-flex flex-column border border-2 border-primary">
                            <div class="gradient-header pro">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-warning text-dark font-weight-bold"><i class="fas fa-wand-magic-sparkles me-1"></i> Custom Plan</span>
                                    <span class="badge bg-white text-dark font-weight-bold" style="font-size: 11px;">Flexible Features</span>
                                </div>
                                <h4 class="mb-1 font-weight-bold" style="color: #fff;">Custom Package Builder</h4>
                                <p class="small mb-0 opacity-80" style="color: rgba(255,255,255,0.85); min-height: 38px;">Choose exact features you want and calculate your custom price quote.</p>
                                <div class="package-price-badge">Custom Rate</div>
                            </div>
                            <div class="card-body p-3 flex-grow-1 d-flex flex-column align-items-center justify-content-center text-center">
                                <div class="rounded-circle bg-light p-4 mb-3 border">
                                    <i class="fas fa-sliders text-primary fs-1"></i>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-1">Build Your Tailored Subscription</h6>
                                <p class="text-muted small mb-3">Pick specific sidebar permissions and module items to construct a personalized plan tailored for your store.</p>
                                <button class="btn btn-outline-primary rounded-pill px-4 font-weight-bold" data-bs-toggle="modal" data-bs-target="#manageFeaturesModal">
                                    <i class="fas fa-list-check me-1"></i> Customize Features
                                </button>
                            </div>
                            <div class="card-footer bg-light border-0 p-3 mt-auto">
                                <button class="btn btn-primary rounded-pill w-100 font-weight-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="openCheckoutModal('custom_plan', 'Custom Tailored Plan', '4500', 'Custom Billing')">
                                    <i class="fas fa-cash-register me-1"></i> Subscribe Custom Package
                                </button>
                            </div>
                        </div>
                    `;
                    grid.appendChild(customCardCol);
                }

                // Update the feature pool checklist inside Manage Features modal
                renderFeaturesPoolList();

                // Render Subscription History Table
                renderSubscriptionHistoryTable();
            }

            // Render list inside features pool modal grouped by section categories matching package modal UI
            function renderFeaturesPoolList() {
                const container = document.getElementById('featuresListContainer');
                if (!container) return;
                container.innerHTML = '';

                const searchVal = (document.getElementById('featuresPoolSearch')?.value || '').toLowerCase().trim();
                const filteredPool = featuresPool.filter(f => f.toLowerCase().includes(searchVal));

                document.getElementById('featuresPoolCount').textContent = featuresPool.length;

                if (filteredPool.length === 0) {
                    container.innerHTML = `<div class="text-muted text-center py-4">No matching features found in pool.</div>`;
                    return;
                }

                let sectionIndex = 0;
                SIDEBAR_FEATURE_GROUPS.forEach(group => {
                    const groupItemsInFilteredPool = group.items.filter(item => filteredPool.includes(item));
                    if (groupItemsInFilteredPool.length === 0) return;

                    sectionIndex++;
                    const groupSlug = 'pool_sec_' + sectionIndex;
                    const totalCategoryItemsInPool = group.items.filter(item => featuresPool.includes(item)).length;

                    const card = document.createElement('div');
                    card.className = 'card border mb-3 shadow-sm rounded-3 feature-group-card';

                    let itemsHTML = '';
                    groupItemsInFilteredPool.forEach(feat => {
                        const originalIdx = featuresPool.indexOf(feat);
                        itemsHTML += `
                            <div class="col-md-6 mb-2">
                                <div class="feature-select-card d-flex align-items-center justify-content-between" style="border: 1.5px solid #e5e7eb; border-radius: 12px; padding: 12px 16px; background: #ffffff;">
                                    <span class="font-weight-medium text-dark" style="font-size: 13.5px;">
                                        <i class="fas fa-cube me-2" style="color: #0ea5e9;"></i>${feat}
                                    </span>
                                    <button class="btn btn-sm btn-outline-danger rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; min-width: 28px;" onclick="deleteFeature(${originalIdx})" title="Delete Feature">
                                        <i class="fas fa-trash-alt" style="font-size: 11px;"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });

                    card.innerHTML = `
                        <div class="card-header bg-light border-0 py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 14px 14px 0 0;">
                            <div class="d-flex align-items-center gap-2" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#collapse_${groupSlug}">
                                <i class="${group.icon} fs-5 me-1" style="color: #0ea5e9;"></i>
                                <span class="font-weight-bold text-dark fs-6 me-1">${group.category}</span>
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1" style="font-size: 11px; font-weight: 700;">${totalCategoryItemsInPool} items</span>
                            </div>
                        </div>
                        <div id="collapse_${groupSlug}" class="collapse show">
                            <div class="card-body p-4 bg-white">
                                <div class="row g-3">
                                    ${itemsHTML}
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                });

                // Custom features pool not in standard sidebar groups
                const customPoolItems = filteredPool.filter(feat => !SIDEBAR_ITEMS.includes(feat));
                if (customPoolItems.length > 0) {
                    sectionIndex++;
                    const groupSlug = 'pool_sec_custom';

                    const card = document.createElement('div');
                    card.className = 'card border mb-3 shadow-sm rounded-3 feature-group-card';

                    let itemsHTML = '';
                    customPoolItems.forEach(feat => {
                        const originalIdx = featuresPool.indexOf(feat);
                        itemsHTML += `
                            <div class="col-md-6 mb-2">
                                <div class="feature-select-card d-flex align-items-center justify-content-between" style="border: 1.5px solid #e5e7eb; border-radius: 12px; padding: 12px 16px; background: #ffffff;">
                                    <span class="font-weight-medium text-dark" style="font-size: 13.5px;">
                                        <i class="fas fa-star text-warning me-2"></i>${feat}
                                    </span>
                                    <button class="btn btn-sm btn-outline-danger rounded-circle p-0 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px; min-width: 28px;" onclick="deleteFeature(${originalIdx})" title="Delete Feature">
                                        <i class="fas fa-trash-alt" style="font-size: 11px;"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });

                    card.innerHTML = `
                        <div class="card-header bg-light border-0 py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 14px 14px 0 0;">
                            <div class="d-flex align-items-center gap-2" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#collapse_${groupSlug}">
                                <i class="fas fa-star text-warning fs-5 me-1"></i>
                                <span class="font-weight-bold text-dark fs-6 me-1">Custom / Additional Items</span>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1" style="font-size: 11px; font-weight: 700;">${customPoolItems.length} items</span>
                            </div>
                        </div>
                        <div id="collapse_${groupSlug}" class="collapse show">
                            <div class="card-body p-4 bg-white">
                                <div class="row g-3">
                                    ${itemsHTML}
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                }
            }

            function filterFeaturesPoolList() {
                renderFeaturesPoolList();
            }

            function resetToDefaultSidebarFeatures() {
                Swal.fire({
                    title: 'Reset to All Sidebar Features?',
                    text: 'This will restore the master pool to include every page and section discovered from the system sidebar.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Reset Defaults'
                }).then((result) => {
                    if (result.isConfirmed) {
                        featuresPool = [...DEFAULT_FEATURES];
                        localStorage.setItem(FEATURES_KEY, JSON.stringify(featuresPool));
                        renderWorkspace();
                        Swal.fire('Reset!', 'Master feature pool reset to all sidebar pages.', 'success');
                    }
                });
            }

            // Render features checklist grouped in Package Form Modal
            function renderModalFeaturesChecklist(checkedFeatures = []) {
                const container = document.getElementById('modalFeaturesContainer');
                if (!container) return;
                container.innerHTML = '';

                if (featuresPool.length === 0) {
                    container.innerHTML = `<p class="text-muted col-12 py-3 text-center">No features created in the features pool yet.</p>`;
                    return;
                }

                let sectionIndex = 0;
                SIDEBAR_FEATURE_GROUPS.forEach((group) => {
                    // Filter pool items belonging to this category
                    const poolCategoryItems = group.items.filter(item => featuresPool.includes(item));
                    if (poolCategoryItems.length === 0) return;

                    sectionIndex++;
                    const groupSlug = 'sec_' + sectionIndex;
                    const allCategoryChecked = poolCategoryItems.every(item => checkedFeatures.includes(item));

                    const card = document.createElement('div');
                    card.className = 'card border mb-3 shadow-sm rounded-3 feature-group-card';
                    card.setAttribute('data-category', group.category.toLowerCase());

                    let itemsHTML = '';
                    poolCategoryItems.forEach((feat) => {
                        const isChecked = checkedFeatures.includes(feat);
                        const safeId = 'feat_' + feat.replace(/[^a-zA-Z0-9]/g, '_');
                        itemsHTML += `
                            <div class="col-md-6 mb-2 feature-checkbox-item" data-feature-name="${feat.toLowerCase()}">
                                <label class="feature-select-card ${isChecked ? 'active-selected' : ''}" for="${safeId}">
                                    <input class="form-check-input feature-checkbox group-cb-${groupSlug}" type="checkbox" value="${feat}" id="${safeId}" ${isChecked ? 'checked' : ''} onchange="onFeatureCardToggle(this)">
                                    <span class="text-dark font-weight-medium" style="font-size: 13px;">${feat}</span>
                                </label>
                            </div>
                        `;
                    });

                    card.innerHTML = `
                        <div class="card-header bg-light border-0 py-3 px-4 d-flex justify-content-between align-items-center" style="border-radius: 14px 14px 0 0;">
                            <div class="d-flex align-items-center gap-2" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#collapse_${groupSlug}">
                                <i class="${group.icon} fs-5 me-1" style="color: #0ea5e9;"></i>
                                <span class="font-weight-bold text-dark fs-6 me-1">${group.category}</span>
                                <span class="badge bg-primary text-white rounded-pill px-3 py-1" style="font-size: 11px; font-weight: 700;">${poolCategoryItems.length} items</span>
                            </div>
                            <div class="bg-white border rounded-pill px-3 py-1 d-flex align-items-center gap-2 shadow-sm" onclick="event.stopPropagation()" style="cursor: pointer;">
                                <input class="form-check-input select-all-section-cb m-0" type="checkbox" id="sec_cb_${groupSlug}" ${allCategoryChecked ? 'checked' : ''} onchange="selectAllSectionFeatures('${groupSlug}', this.checked)" style="cursor: pointer; width: 16px; height: 16px;">
                                <label for="sec_cb_${groupSlug}" class="text-secondary small font-weight-bold mb-0" style="cursor: pointer; font-size: 12px; white-space: nowrap;">Select Group</label>
                            </div>
                        </div>
                        <div id="collapse_${groupSlug}" class="collapse show">
                            <div class="card-body p-4 bg-white">
                                <div class="row g-3">
                                    ${itemsHTML}
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                });

                // Add any custom extra features in pool not in default sidebar groups
                const extraPoolItems = featuresPool.filter(feat => !DEFAULT_FEATURES.includes(feat));
                if (extraPoolItems.length > 0) {
                    sectionIndex++;
                    const groupSlug = 'sec_custom';
                    const allCustomChecked = extraPoolItems.every(item => checkedFeatures.includes(item));

                    const card = document.createElement('div');
                    card.className = 'card border mb-3 shadow-sm rounded-3 feature-group-card';
                    card.setAttribute('data-category', 'custom extensions');

                    let itemsHTML = '';
                    extraPoolItems.forEach((feat) => {
                        const isChecked = checkedFeatures.includes(feat);
                        const safeId = 'feat_' + feat.replace(/[^a-zA-Z0-9]/g, '_');
                        itemsHTML += `
                            <div class="col-md-6 mb-2 feature-checkbox-item" data-feature-name="${feat.toLowerCase()}">
                                <label class="feature-select-card ${isChecked ? 'active-selected' : ''}" for="${safeId}">
                                    <input class="form-check-input feature-checkbox group-cb-${groupSlug}" type="checkbox" value="${feat}" id="${safeId}" ${isChecked ? 'checked' : ''} onchange="onFeatureCardToggle(this)">
                                    <span class="text-dark font-weight-medium" style="font-size: 13px;">${feat}</span>
                                </label>
                            </div>
                        `;
                    });

                    card.innerHTML = `
                        <div class="feature-group-header">
                            <div class="d-flex align-items-center gap-2" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#collapse_${groupSlug}">
                                <i class="fas fa-star text-warning fs-6 me-1"></i>
                                <span class="feature-group-title">Custom Features Pool</span>
                                <span class="badge bg-warning text-dark rounded-pill px-2 py-1 ms-1" style="font-size: 10px; font-weight: 700;">${extraPoolItems.length} items</span>
                            </div>
                            <div class="group-toggle-badge" onclick="event.stopPropagation()">
                                <input class="form-check-input select-all-section-cb m-0" type="checkbox" id="sec_cb_${groupSlug}" ${allCustomChecked ? 'checked' : ''} onchange="selectAllSectionFeatures('${groupSlug}', this.checked)" style="cursor: pointer;">
                                <label for="sec_cb_${groupSlug}" class="text-secondary">Select Group</label>
                            </div>
                        </div>
                        <div id="collapse_${groupSlug}" class="collapse show">
                            <div class="card-body p-3 bg-white">
                                <div class="row g-2">
                                    ${itemsHTML}
                                </div>
                            </div>
                        </div>
                    `;
                    container.appendChild(card);
                }

                updateSelectedCountBadge();

                // Attach search listener
                const searchInput = document.getElementById('packageFeatureSearch');
                if (searchInput) {
                    searchInput.value = '';
                    searchInput.oninput = function() {
                        const term = this.value.toLowerCase().trim();
                        document.querySelectorAll('.feature-group-card').forEach(groupCard => {
                            const categoryText = groupCard.getAttribute('data-category') || '';
                            let visibleItemsCount = 0;
                            groupCard.querySelectorAll('.feature-checkbox-item').forEach(item => {
                                const featName = item.getAttribute('data-feature-name') || '';
                                if (term === '' || featName.includes(term) || categoryText.includes(term)) {
                                    item.style.display = '';
                                    visibleItemsCount++;
                                } else {
                                    item.style.display = 'none';
                                }
                            });
                            if (term === '') {
                                groupCard.style.display = '';
                            } else if (visibleItemsCount > 0) {
                                groupCard.style.display = '';
                                const collapseEl = groupCard.querySelector('.collapse');
                                if (collapseEl && typeof bootstrap !== 'undefined') {
                                    const bsCollapse = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap.Collapse(collapseEl, { toggle: false });
                                    bsCollapse.show();
                                }
                            } else {
                                groupCard.style.display = 'none';
                            }
                        });
                    };
                }
            }

            function onFeatureCardToggle(cb) {
                const cardLabel = cb.closest('.feature-select-card');
                if (cardLabel) {
                    if (cb.checked) cardLabel.classList.add('active-selected');
                    else cardLabel.classList.remove('active-selected');
                }
                updateSelectedCountBadge();
            }

            function updateSelectedCountBadge() {
                const checkedCount = document.querySelectorAll('.feature-checkbox:checked').length;
                const badge = document.getElementById('selectedFeaturesCount');
                if (badge) badge.textContent = checkedCount;
            }

            function selectAllSectionFeatures(groupSlug, isChecked) {
                document.querySelectorAll(`.group-cb-${groupSlug}`).forEach(cb => {
                    cb.checked = isChecked;
                    onFeatureCardToggle(cb);
                });
                updateSelectedCountBadge();
            }

            function selectAllModalFeatures(checkState) {
                document.querySelectorAll('.feature-checkbox').forEach(cb => {
                    cb.checked = checkState;
                    onFeatureCardToggle(cb);
                });
                document.querySelectorAll('.select-all-section-cb').forEach(cb => {
                    cb.checked = checkState;
                });
                updateSelectedCountBadge();
            }

            function toggleModalSections(expand) {
                document.querySelectorAll('#modalFeaturesContainer .collapse').forEach(collapseEl => {
                    if (typeof bootstrap !== 'undefined') {
                        const bsCollapse = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap.Collapse(collapseEl, { toggle: false });
                        if (expand) bsCollapse.show();
                        else bsCollapse.hide();
                    }
                });
            }

            function togglePoolSections(expand) {
                document.querySelectorAll('#featuresListContainer .collapse').forEach(collapseEl => {
                    if (typeof bootstrap !== 'undefined') {
                        const bsCollapse = bootstrap.Collapse.getInstance(collapseEl) || new bootstrap.Collapse(collapseEl, { toggle: false });
                        if (expand) bsCollapse.show();
                        else bsCollapse.hide();
                    }
                });
            }

            // Add Feature to pool
            function addFeature(event) {
                event.preventDefault();
                const input = document.getElementById('newFeatureName');
                const name = input.value.trim();
                if (!name) return;

                if (featuresPool.includes(name)) {
                    Swal.fire('Error', 'Feature already exists in the pool!', 'error');
                    return;
                }

                featuresPool.push(name);
                localStorage.setItem(FEATURES_KEY, JSON.stringify(featuresPool));
                input.value = '';
                
                // Re-render
                renderWorkspace();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Feature added to pool',
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            // Delete Feature from pool
            function deleteFeature(idx) {
                const featToDelete = featuresPool[idx];
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Removing "${featToDelete}" will also exclude it from all current packages.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        featuresPool.splice(idx, 1);
                        localStorage.setItem(FEATURES_KEY, JSON.stringify(featuresPool));

                        // Clean up package assignments
                        packagesList.forEach(pkg => {
                            if (pkg.features) {
                                pkg.features = pkg.features.filter(f => f !== featToDelete);
                            }
                        });
                        localStorage.setItem(PACKAGES_KEY, JSON.stringify(packagesList));

                        renderWorkspace();
                        Swal.fire('Deleted!', 'Feature removed from pool.', 'success');
                    }
                });
            }

            // Open Create Modal
            function openCreateModal() {
                document.getElementById('packageModalLabel').textContent = 'Create New Package';
                document.getElementById('packageId').value = '';
                document.getElementById('packageForm').reset();
                document.getElementById('packageStatus').checked = true;
                
                renderModalFeaturesChecklist([]);
                
                const modal = new bootstrap.Modal(document.getElementById('packageModal'));
                modal.show();
            }

            // Open Edit Modal
            function openEditModal(pkgId) {
                const pkg = packagesList.find(p => p.id === pkgId);
                if (!pkg) return;

                document.getElementById('packageModalLabel').textContent = 'Edit Package Plan';
                document.getElementById('packageId').value = pkg.id;
                document.getElementById('packageName').value = pkg.name;
                document.getElementById('packageTheme').value = pkg.theme || 'default';
                document.getElementById('packageDetails').value = pkg.details;
                document.getElementById('priceMonthly').value = pkg.priceMonthly;
                document.getElementById('priceYearly').value = pkg.priceYearly;
                document.getElementById('priceLifetime').value = pkg.priceLifetime;
                document.getElementById('packageStatus').checked = pkg.status;

                renderModalFeaturesChecklist(pkg.features || []);

                const modal = new bootstrap.Modal(document.getElementById('packageModal'));
                modal.show();
            }

            // Save Package (Create or Update)
            function savePackage() {
                const idInput = document.getElementById('packageId').value;
                const name = document.getElementById('packageName').value.trim();
                const theme = document.getElementById('packageTheme').value;
                const details = document.getElementById('packageDetails').value.trim();
                const priceMonthly = document.getElementById('priceMonthly').value.trim();
                const priceYearly = document.getElementById('priceYearly').value.trim();
                const priceLifetime = document.getElementById('priceLifetime').value.trim();
                const status = document.getElementById('packageStatus').checked;

                if (!name || !details || !priceMonthly || !priceYearly || !priceLifetime) {
                    Swal.fire('Validation Error', 'Please fill in all required fields.', 'error');
                    return;
                }

                // Gather checked features
                const selectedFeatures = [];
                document.querySelectorAll('.feature-checkbox:checked').forEach(cb => {
                    selectedFeatures.push(cb.value);
                });

                if (idInput) {
                    // Update
                    const pkgIdx = packagesList.findIndex(p => p.id === idInput);
                    if (pkgIdx !== -1) {
                        packagesList[pkgIdx] = {
                            id: idInput,
                            name,
                            theme,
                            details,
                            priceMonthly,
                            priceYearly,
                            priceLifetime,
                            status,
                            features: selectedFeatures
                        };
                    }
                } else {
                    // Create
                    const newId = 'pkg_' + Date.now();
                    packagesList.push({
                        id: newId,
                        name,
                        theme,
                        details,
                        priceMonthly,
                        priceYearly,
                        priceLifetime,
                        status,
                        features: selectedFeatures
                    });
                }

                localStorage.setItem(PACKAGES_KEY, JSON.stringify(packagesList));
                
                // Hide modal
                const modalEl = document.getElementById('packageModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                renderWorkspace();

                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully',
                    text: 'The SaaS tier was recorded successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // Delete Package
            function deletePackage(pkgId) {
                const pkg = packagesList.find(p => p.id === pkgId);
                if (!pkg) return;

                Swal.fire({
                    title: 'Delete Package?',
                    text: `Are you absolutely sure you want to remove "${pkg.name}"? This cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Delete Package'
                }).then((result) => {
                    if (result.isConfirmed) {
                        packagesList = packagesList.filter(p => p.id !== pkgId);
                        localStorage.setItem(PACKAGES_KEY, JSON.stringify(packagesList));
                        renderWorkspace();
                        Swal.fire('Deleted!', 'The package has been removed.', 'success');
                    }
                });
            }

            // Initial render on DOM load
            function bootWorkspace() {
                if (typeof renderWorkspace === 'function') {
                    renderWorkspace();
                }
                // Load subscription payments from real DB table
                if (typeof loadSubscriptionHistory === 'function') {
                    loadSubscriptionHistory();
                }
            }

            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                setTimeout(bootWorkspace, 50);
            }
            document.addEventListener('DOMContentLoaded', bootWorkspace);
            if (typeof $ !== 'undefined') {
                $(document).ready(bootWorkspace);
            }
        </script>
    @endif

    <script>
        window.deleteMockAdmin = function(id) {
            if (confirm('Are you sure you want to delete this mock registered admin?')) {
                let admins = JSON.parse(localStorage.getItem('registered_admins') || '[]');
                admins = admins.filter(a => a.id !== id);
                localStorage.setItem('registered_admins', JSON.stringify(admins));
                location.reload();
            }
        };

        $(document).ready(function() {
            // Dynamically inject mock registered admins if present
            const mockAdmins = JSON.parse(localStorage.getItem('registered_admins') || '[]');
            const tbody = document.querySelector('#users tbody');
            if (tbody && mockAdmins.length > 0) {
                mockAdmins.forEach(adm => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td style="text-align: center;">
                            <input type="checkbox" class="user-checkbox form-check-input" value="${adm.id}" onchange="updateDeleteButton()">
                        </td>
                        <td><strong>#${adm.id}</strong></td>
                        <td></td>
                        <td>${adm.name}</td>
                        <td><span class="text-muted">${adm.email}</span></td>
                        <td>
                            <span class="badge-premium badge-premium-admin mb-1"><i class="fas fa-shield-alt"></i> admin</span>
                            <span class="badge-premium badge-premium-success"><i class="fas fa-gem"></i> ${adm.packageName} (${adm.billingCycle})</span>
                        </td>
                        <td>${adm.created_at}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <button class="btn-action-circle btn-action-delete" title="Delete Mock Admin" onclick="deleteMockAdmin('${adm.id}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.insertBefore(tr, tbody.firstChild);
                });
            }

            if ($('#users').length) {
                $('#users').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy','pdf', 'csv', 'excel', 'print'
                    ],
                    order: [[1, 'asc']], // Sort by User ID ascending
                    pageLength: 25,
                    columnDefs: [
                        {
                            targets: 2, // Serial column (index 2)
                            searchable: false,
                            orderable: false,
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            targets: 0, // Checkbox column
                            searchable: false,
                            orderable: false
                        }
                    ]
                });
            }
        });

        window.toggleSelectAll = function() {
            const selectAll = document.getElementById('selectAll');
            const isChecked = selectAll ? selectAll.checked : false;
            
            if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#users')) {
                const table = $('#users').DataTable();
                const rows = table.rows({ 'search': 'applied' }).nodes();
                $('input.user-checkbox', rows).prop('checked', isChecked);
            } else {
                const checkboxes = document.querySelectorAll('.user-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
            }
            
            window.updateDeleteButton();
        };

        window.updateDeleteButton = function() {
            let checkedCount = 0;
            if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#users')) {
                const table = $('#users').DataTable();
                const rows = table.rows().nodes();
                checkedCount = $(rows).find('.user-checkbox:checked').length;
            } else {
                checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
            }
            
            const deleteBtn = document.getElementById('deleteSelectedBtn');
            if (deleteBtn) {
                if (checkedCount > 0) {
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = `<i class="fas fa-trash me-2"></i> Delete Selected (${checkedCount})`;
                } else {
                    deleteBtn.disabled = true;
                    deleteBtn.innerHTML = `<i class="fas fa-trash me-2"></i> Delete Selected`;
                }
            }
        };

        window.deleteSelected = function() {
            let userIds = [];
            if (window.jQuery && $.fn.DataTable && $.fn.DataTable.isDataTable('#users')) {
                const table = $('#users').DataTable();
                const rows = table.rows().nodes();
                userIds = $(rows).find('.user-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
            } else {
                const checkboxes = document.querySelectorAll('.user-checkbox:checked');
                userIds = Array.from(checkboxes).map(cb => cb.value);
            }
            
            if (userIds.length === 0) {
                alert('Please select users to delete.');
                return;
            }
            
            if (confirm(`Are you sure you want to delete ${userIds.length} user(s)?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.users.bulk-delete") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                userIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'user_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
        };
    </script>
@endsection
