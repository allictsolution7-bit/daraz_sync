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
            border-radius: 16px;
            padding: 8px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-premium);
            display: flex;
            gap: 8px;
        }

        .portal-tab-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-muted);
            border: none;
            background: transparent;
            border-radius: 12px;
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
            border-radius: 20px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-premium);
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 140px;
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
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.08);
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
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            opacity: 0.85;
            margin-bottom: 6px;
        }

        .stat-card-value {
            font-size: 32px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card-icon {
            position: absolute;
            right: 20px;
            bottom: 15px;
            font-size: 4rem;
            opacity: 0.15;
            pointer-events: none;
        }

        /* Action Panel */
        .premium-actions-bar {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            padding: 16px 24px;
            box-shadow: var(--shadow-premium);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Table & Container Cards */
        .workspace-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            box-shadow: var(--shadow-premium);
            padding: 24px;
            margin-bottom: 40px;
        }

        .premium-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 8px !important;
        }

        .premium-table thead th {
            background-color: #f1f5f9 !important;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 14px 18px !important;
            border: none !important;
        }

        .premium-table tbody tr {
            background-color: #ffffff;
            transition: var(--transition-smooth);
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.01);
        }

        .premium-table tbody tr:hover {
            background-color: rgba(241, 245, 249, 0.8) !important;
        }

        .premium-table tbody td {
            padding: 16px 18px !important;
            border-top: 1px solid #f1f5f9 !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle;
            color: var(--text-main);
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
            padding: 28px;
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
    <div class="container-fluid px-4 pt-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none text-muted">Home</a></li>
                @if(request()->get('view') === 'packages')
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none text-muted">Users Directory</a></li>
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

        <!-- Portal Tabs Navigation -->
        <div class="portal-tabs-container">
            <a href="{{ route('admin.users') }}" class="portal-tab-btn {{ request()->get('view') !== 'packages' ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> Users Accounts Directory
            </a>
            <a href="{{ route('admin.users', ['view' => 'packages']) }}" class="portal-tab-btn {{ request()->get('view') === 'packages' ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i> SaaS Billing Tiers
            </a>
        </div>

        @if(request()->get('view') === 'packages')
            <!-- ADMIN PACKAGES WORKSPACE -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 font-weight-bold" style="color: var(--dark-slate);">SaaS Billing Matrix</h3>
                    <p class="text-muted mb-0">Create, customize, and regulate membership subscription plans and pricing structures</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#manageFeaturesModal">
                        <i class="fas fa-list-ul me-2"></i> Manage Features
                    </button>
                    <button class="btn btn-primary rounded-pill px-4" onclick="openCreateModal()">
                        <i class="fas fa-plus me-2"></i> New Subscription Plan
                    </button>
                </div>
            </div>

            <!-- Stats Dashboard Row -->
            <div class="row mb-4 g-3">
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

            <!-- Packages Grid -->
            <div class="row" id="packagesGrid">
                <!-- Dynamic cards populated by script -->
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

            // State variables
            let featuresPool = [];
            let packagesList = [];

            // Initialize Data
            function initData() {
                const storedFeatures = localStorage.getItem(FEATURES_KEY);
                const storedPackages = localStorage.getItem(PACKAGES_KEY);

                if (!storedFeatures) {
                    localStorage.setItem(FEATURES_KEY, JSON.stringify(DEFAULT_FEATURES));
                    featuresPool = DEFAULT_FEATURES;
                } else {
                    featuresPool = JSON.parse(storedFeatures);
                }

                if (!storedPackages) {
                    localStorage.setItem(PACKAGES_KEY, JSON.stringify(DEFAULT_PACKAGES));
                    packagesList = DEFAULT_PACKAGES;
                } else {
                    packagesList = JSON.parse(storedPackages);
                }
            }

            // Render Dashboard Stats and Grid
            function renderWorkspace() {
                initData();
                
                // Update Counts
                document.getElementById('totalPackagesCount').textContent = packagesList.length;
                document.getElementById('activePackagesCount').textContent = packagesList.filter(p => p.status).length;
                document.getElementById('totalFeaturesCount').textContent = featuresPool.length;

                // Render Grid
                const grid = document.getElementById('packagesGrid');
                grid.innerHTML = '';

                if (packagesList.length === 0) {
                    grid.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" alt="Empty" width="100" style="opacity: 0.3;">
                            <h5 class="mt-3 text-muted">No Packages Found</h5>
                            <p class="text-muted">Get started by creating your first SaaS tier!</p>
                            <button class="btn btn-primary rounded-pill px-4 mt-2" onclick="openCreateModal()">Create Package</button>
                        </div>
                    `;
                    return;
                }

                packagesList.forEach(pkg => {
                    const cardCol = document.createElement('div');
                    cardCol.className = 'col-md-4 mb-4';

                    let featuresHTML = '';
                    const totalFeaturesCount = featuresPool.length;
                    const includedCount = pkg.features ? pkg.features.length : 0;

                    // Group features by category for display - showing ALL features (included and excluded)
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

                    // Add extra custom pool features not in standard groups if any
                    const customFeatures = featuresPool.filter(f => !DEFAULT_FEATURES.includes(f));
                    if (customFeatures.length > 0) {
                        const customIncludedCount = customFeatures.filter(f => pkg.features && pkg.features.includes(f)).length;
                        featuresHTML += `
                            <div class="mb-3">
                                <div class="small font-weight-bold text-muted text-uppercase mb-1 border-bottom pb-1" style="font-size: 10px; letter-spacing: 0.5px;">
                                    <i class="fas fa-star text-warning me-1"></i> Custom Extensions (${customIncludedCount}/${customFeatures.length})
                                </div>
                        `;
                        customFeatures.forEach(feat => {
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

                    if (includedCount === 0) {
                        featuresHTML = `<div class="text-muted small py-3 text-center">No features allocated to this tier yet.</div>`;
                    }

                    cardCol.innerHTML = `
                        <div class="premium-card h-100 d-flex flex-column">
                            <div class="gradient-header ${pkg.theme || 'default'}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge ${pkg.status ? 'bg-success' : 'bg-secondary'}">${pkg.status ? 'Active' : 'Inactive'}</span>
                                    <span class="badge bg-white text-dark font-weight-bold" style="font-size: 11px;">${includedCount}/${totalFeaturesCount} Features</span>
                                </div>
                                <h4 class="mb-1 font-weight-bold" style="color: #fff;">${pkg.name}</h4>
                                <p class="small mb-0 opacity-80" style="color: rgba(255,255,255,0.85); min-height: 38px;">${pkg.details}</p>
                                <div class="package-price-badge">Monthly: TK ${pkg.priceMonthly}</div>
                            </div>
                            <div class="card-body p-3 flex-grow-1 d-flex flex-column">
                                <div class="row text-center mb-3 border-bottom pb-2">
                                    <div class="col-6 border-end">
                                        <span class="text-muted d-block small">Yearly Rate</span>
                                        <strong class="text-dark">TK ${pkg.priceYearly}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block small">Lifetime Rate</span>
                                        <strong class="text-dark">TK ${pkg.priceLifetime}</strong>
                                    </div>
                                </div>
                                <div class="features-list-wrapper mb-3 flex-grow-1" style="max-height: 280px; overflow-y: auto; padding-right: 5px;">
                                    ${featuresHTML}
                                </div>
                            </div>
                            <div class="card-footer bg-light border-0 p-3 d-flex justify-content-between align-items-center gap-2 mt-auto">
                                <button class="btn btn-primary rounded-pill px-3 py-2 flex-grow-1 font-weight-bold d-flex align-items-center justify-content-center gap-2" onclick="openEditModal('${pkg.id}')">
                                    <i class="fas fa-edit"></i> Edit Plan
                                </button>
                                <button class="btn btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" onclick="deletePackage('${pkg.id}')" title="Delete Plan" style="width: 38px; height: 38px; min-width: 38px; padding: 0;">
                                    <i class="fas fa-trash-alt" style="font-size: 14px;"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    grid.appendChild(cardCol);
                });

                // Update the feature pool checklist inside Manage Features modal
                renderFeaturesPoolList();
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
            document.addEventListener('DOMContentLoaded', function() {
                renderWorkspace();
            });
        </script>
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
                // Dynamically inject mock registered admins
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
            });

            function toggleSelectAll() {
                const selectAll = document.getElementById('selectAll');
                const checkboxes = document.querySelectorAll('.user-checkbox');
                
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
                
                updateDeleteButton();
            }

            function updateDeleteButton() {
                const checkboxes = document.querySelectorAll('.user-checkbox:checked');
                const deleteBtn = document.getElementById('deleteSelectedBtn');
                
                if (checkboxes.length > 0) {
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = `Delete Selected (${checkboxes.length})`;
                } else {
                    deleteBtn.disabled = true;
                    deleteBtn.textContent = 'Delete Selected';
                }
            }

            function deleteSelected() {
                const checkboxes = document.querySelectorAll('.user-checkbox:checked');
                const userIds = Array.from(checkboxes).map(cb => cb.value);
                
                if (userIds.length === 0) {
                    alert('Please select users to delete.');
                    return;
                }
                
                if (confirm(`Are you sure you want to delete ${userIds.length} user(s)?`)) {
                    // Create a form to submit multiple user IDs
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.users.bulk-delete") }}';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add method override
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    // Add user IDs
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
            }
        </script>
    @endif
@endsection
