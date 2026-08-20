@extends('layouts.master')

@section('styles')
<style>
    .global-header-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }

    .nav-tabs-modern {
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
        border: none;
        display: inline-flex;
    }

    .nav-tabs-modern .nav-link {
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 0.875rem;
        color: #64748b;
        border: none;
        transition: all 0.2s ease;
    }

    .nav-tabs-modern .nav-link.active {
        background: #4f46e5;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table-modern thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 14px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-modern tbody tr {
        transition: all 0.15s ease-in-out;
    }

    .table-modern tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .table-modern tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .btn-copy-action {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 6px 14px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.2);
    }

    .btn-copy-action:hover {
        background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
    }

    .btn-copy-action:disabled {
        background: #cbd5e1;
        color: #64748b;
        transform: none;
        box-shadow: none;
        cursor: not-allowed;
    }

    .badge-in-store {
        background-color: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-available {
        background-color: #e0e7ff;
        color: #3730a3;
        border: 1px solid #c7d2fe;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Modern Wholesale Modal Styling */
    .swal2-popup.modern-wholesale-popup {
        border-radius: 20px !important;
        padding: 1.5rem 1.75rem !important;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25) !important;
        font-family: inherit !important;
        border: 1px solid #e2e8f0 !important;
    }
    .swal2-popup.modern-wholesale-popup .swal2-title {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        padding: 0 0 1rem 0 !important;
    }
    .swal2-popup.modern-wholesale-popup .swal2-html-container {
        margin: 0 !important;
        padding: 0 !important;
        overflow-x: hidden !important;
    }
    .swal2-popup.modern-wholesale-popup .swal2-actions {
        margin-top: 1.25rem !important;
        gap: 0.75rem !important;
        width: 100% !important;
        display: flex !important;
        justify-content: flex-end !important;
    }
    .swal2-popup.modern-wholesale-popup .swal2-confirm {
        border-radius: 10px !important;
        padding: 10px 22px !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%) !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
        border: none !important;
        margin: 0 !important;
    }
    .swal2-popup.modern-wholesale-popup .swal2-cancel {
        border-radius: 10px !important;
        padding: 10px 20px !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        background: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #cbd5e1 !important;
        margin: 0 !important;
    }
    .swal2-popup.modern-wholesale-popup .swal2-cancel:hover {
        background: #e2e8f0 !important;
        color: #1e293b !important;
    }

    .modal-product-hero {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        margin-bottom: 16px;
    }
    .action-mode-box {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        position: relative;
    }
    .action-mode-box:hover {
        border-color: #cbd5e1;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .action-mode-box.active-purchase {
        border-color: #10b981 !important;
        background-color: #f0fdf4 !important;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.12) !important;
    }
    .action-mode-box.active-copy {
        border-color: #6366f1 !important;
        background-color: #f5f3ff !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.12) !important;
    }
    .action-icon-circle {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }
    .form-control-clean {
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        transition: all 0.2s ease;
        background-color: #ffffff;
        width: 100%;
    }
    .form-control-clean:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        outline: none;
    }
    .gateway-pill {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        background: #ffffff;
        color: #475569;
        transition: all 0.15s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .gateway-pill:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    .gateway-pill.active[data-gw="bkash"] {
        border-color: #d11261 !important;
        background: #fdf2f8 !important;
        color: #d11261 !important;
        box-shadow: 0 2px 8px rgba(209, 18, 97, 0.15);
    }
    .gateway-pill.active[data-gw="nagad"] {
        border-color: #ea580c !important;
        background: #fff7ed !important;
        color: #ea580c !important;
        box-shadow: 0 2px 8px rgba(234, 88, 12, 0.15);
    }
    .gateway-pill.active[data-gw="rocket"] {
        border-color: #8c3494 !important;
        background: #faf5ff !important;
        color: #8c3494 !important;
        box-shadow: 0 2px 8px rgba(140, 52, 148, 0.15);
    }
    .gateway-pill.active[data-gw="bank"] {
        border-color: #059669 !important;
        background: #ecfdf5 !important;
        color: #059669 !important;
        box-shadow: 0 2px 8px rgba(5, 150, 105, 0.15);
    }
</style>
@endsection

@section('content')
@php
    $isSuperAdmin = auth()->user()?->isSuperAdmin() ?? false;
@endphp
<div class="container-fluid py-4" style="max-width: 1600px;">
    <!-- Header Block -->
    <div class="global-header-card d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge rounded-pill px-2.5 py-1" style="background-color: #e0e7ff; color: #4338ca; font-weight: 700; font-size: 11px;">
                    <i class="fas fa-globe me-1"></i> GLOBAL NETWORK
                </span>
                <span class="text-muted small">&bull;</span>
                <span class="text-muted small">SaaS Multi-Store Wholesale Network</span>
            </div>
            <h1 class="h3 mb-0 text-slate-800 font-bold" style="font-weight: 700; color: #1e293b;">
                Global Wholesale Products
            </h1>
            <p class="text-muted mb-0 small mt-1">
                Browse and copy all products from the global SaaS platform network directly into your store's inventory catalog.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.wholesale-orders.index') }}" class="btn btn-outline-primary btn-sm rounded-3 px-3 py-2" style="font-weight: 600;">
                <i class="fas fa-receipt me-1.5"></i> My Wholesale Orders
            </a>
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-2" style="font-weight: 600;">
                <i class="fas fa-arrow-left me-1.5"></i> Back to Store Inventory
            </a>
        </div>
    </div>

    <!-- Error Alerts (Database connections failed) -->
    @if(!empty($errors))
        @foreach($errors as $error)
            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center" role="alert" style="background-color: #fffbeb; color: #92400e;">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <div class="small">{{ $error }}</div>
            </div>
        @endforeach
    @endif

    <!-- Tabs & Filter Bar -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <!-- Navigation Header / Tab -->
                <ul class="nav nav-tabs-modern" role="tablist">
                    <li class="nav-item" role="presentation">
                        <span class="nav-link active">
                            <i class="fas fa-user-shield me-1.5"></i> Tenant Admin Products
                            <span class="badge ms-1.5 bg-white text-primary" style="font-size: 11px;">
                                {{ $totalAdminCount }}
                            </span>
                        </span>
                    </li>
                </ul>

                <!-- Filter Controls -->
                <form action="{{ route('admin.global-products.index') }}" method="GET" class="d-flex flex-wrap align-items-center gap-2 m-0">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    
                    <div class="input-group input-group-sm" style="width: 240px;">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search products..." value="{{ request('search') }}">
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if($isSuperAdmin)
                        <select name="tenant_id" class="form-select form-select-sm rounded-3" style="min-width: 200px;" onchange="this.form.submit()">
                            <option value="">All SaaS Tenants</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ $selectedTenantId == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }} ({{ $tenant->subdomain }})
                                </option>
                            @endforeach
                        </select>
                        @endif
                        
                        <button type="submit" class="btn btn-sm btn-primary rounded-3 text-nowrap">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.global-products.index', ['tab' => $currentTab]) }}" class="btn btn-sm btn-light rounded-3 text-nowrap" style="background-color: #f1f5f9; color: #475569; border: none;">
                            <i class="fas fa-redo me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Action & Status Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2 px-1">
        <div class="d-flex align-items-center gap-2">
            <button type="button" id="btn-bulk-import" class="btn btn-sm btn-light border rounded-3 px-3.5 py-2 shadow-sm font-semibold d-inline-flex align-items-center gap-2" style="font-weight: 600; background-color: #f5f3ff; border-color: #ddd6fe !important; color: #4f46e5 !important;" disabled onclick="openBulkImportModal()">
                <i class="fas fa-file-import"></i> Import Selected (<span id="selected-import-count">0</span>)
            </button>
            <button type="button" id="btn-bulk-purchase" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 shadow-sm font-semibold d-inline-flex align-items-center gap-2" style="font-weight: 600; background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); border: none;" disabled onclick="openBulkPurchaseModal()">
                <i class="fas fa-cart-shopping"></i> Purchase Selected (<span id="selected-purchase-count">0</span>)
            </button>
            <span class="text-muted small ms-2">
                Showing {{ $paginatedProducts->firstItem() ?? 0 }} - {{ $paginatedProducts->lastItem() ?? 0 }} of {{ $paginatedProducts->total() }} global products
            </span>
        </div>

        <div>
            @if($globalCommission > 0)
                <span class="badge px-3 py-2 rounded-3" style="background-color: rgba(16, 185, 129, 0.1); color: #047857; font-weight: 600;">
                    <i class="fas fa-percent me-1"></i> Platform Wholesale Commission: <strong>{{ $globalCommission }}%</strong>
                </span>
            @endif
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-modern">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4" style="width: 40px;">
                            <input type="checkbox" class="form-check-input" id="check-all-global" onchange="toggleSelectAll(this)">
                        </th>
                        <th style="width: 70px;">Image</th>
                        <th style="min-width: 220px; max-width: 320px;">Product Name & Info</th>
                        @if($isSuperAdmin)
                        <th style="white-space: nowrap;">Source Tenant</th>
                        @endif
                        <th style="white-space: nowrap;">Category</th>
                        <th style="white-space: nowrap;">Retail Price</th>
                        <th style="white-space: nowrap;">
                            {{ $currentTab === 'admin' ? 'Global Price' : 'Wholesale Price' }}
                        </th>
                        <th style="white-space: nowrap;">Stock</th>
                        <th style="white-space: nowrap;">Store Status</th>
                        <th class="pe-4 text-end" style="white-space: nowrap;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginatedProducts as $product)
                        @php
                            $rowUniqueKey = $product['tenant_subdomain'] . '_' . $product['id'];
                            $hasVariants = !empty($product['has_variants']) && count($product['variants']) > 0;
                        @endphp
                        <tr id="row-{{ $rowUniqueKey }}">
                            <td class="ps-4" style="width: 40px;">
                                <input type="checkbox" 
                                       class="form-check-input product-check" 
                                       data-subdomain="{{ $product['tenant_subdomain'] }}" 
                                       data-id="{{ $product['id'] }}"
                                       data-title="{{ $product['title'] }}"
                                       data-unit-price="{{ $product['final_wholesale_price'] ?? $product['wholesale_price'] ?? $product['price'] ?? 0 }}"
                                       data-stock="{{ $product['quantity'] ?? 0 }}"
                                       data-image="{{ $product['thumb_image'] ?? '' }}"
                                       data-status-type="{{ $product['store_status_type'] ?? ($product['is_already_copied'] ? 'copied' : 'not_in_store') }}"
                                       data-local-stock="{{ $product['local_stock'] ?? 0 }}"
                                       {{ !empty($product['is_own_product']) ? 'disabled title="Your own store product"' : '' }}
                                       onchange="updateBulkButtonState()">
                            </td>
                            <td style="width: 70px;">
                                @if(!empty($product['thumb_image']))
                                    <div class="position-relative" style="width: 48px; height: 48px;">
                                        <img src="{{ $product['thumb_image'] }}" 
                                             alt="{{ $product['title'] }}" 
                                             class="rounded-3 border shadow-sm" 
                                             style="width: 48px; height: 48px; object-fit: cover;" 
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="rounded-3 border bg-light align-items-center justify-content-center" style="width: 48px; height: 48px; display: none;">
                                            <i class="fas fa-image text-muted" style="font-size: 1.1rem;"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="fas fa-image text-muted" style="font-size: 1.1rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="max-width: 320px;">
                                <div class="d-flex align-items-center gap-1.5">
                                    <h6 class="mb-0 text-slate-800 font-semibold text-truncate" style="font-weight: 600; color: #1e293b; max-width: 250px;" title="{{ $product['title'] }}">{{ $product['title'] }}</h6>
                                    @if($hasVariants)
                                        <button type="button" class="btn btn-link p-0 text-primary border-0" onclick="toggleVariantRow('{{ $rowUniqueKey }}')" title="Toggle Variants">
                                            <i id="chevron-{{ $rowUniqueKey }}" class="fas fa-chevron-down fs-7 transition-all" style="font-size: 11px;"></i>
                                        </button>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-0.5">
                                    <span class="text-muted small" style="font-size: 11px;">ID: #{{ $product['id'] }}</span>
                                    <span class="badge rounded-pill bg-light text-muted border px-2 py-0.5" style="font-size: 10px;">
                                        {{ ucfirst($product['product_type']) }}
                                    </span>
                                    @if($hasVariants)
                                        <span class="badge rounded-pill bg-indigo-subtle text-indigo px-2 py-0.5" style="background-color: #ede9fe; color: #6366f1; font-size: 10px; font-weight: 600;">
                                            <i class="fas fa-layer-group me-1"></i>{{ count($product['variants']) }} variants
                                        </span>
                                    @endif
                                </div>
                            </td>
                            @if($isSuperAdmin)
                            <td style="white-space: nowrap;">
                                <span class="badge rounded-pill px-2.5 py-1.5" style="background-color: #e0e7ff; color: #3730a3; font-weight: 600; font-size: 11px;">
                                    <i class="fas fa-globe me-1"></i> {{ $product['tenant_subdomain'] }}
                                </span>
                            </td>
                            @endif
                            <td style="white-space: nowrap;">
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-2" style="font-size: 11px; font-weight: 500;">
                                    {{ $product['category_name'] }}
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                <div class="d-flex flex-column">
                                    <span class="text-slate-800 font-bold" style="font-weight: 700; font-size: 14px;">
                                        ৳{{ number_format($product['price'], 2) }}
                                    </span>
                                    @if(!empty($product['old_price']) && $product['old_price'] > $product['price'])
                                        <span class="text-muted text-decoration-line-through small" style="font-size: 11px;">
                                            ৳{{ number_format($product['old_price'], 2) }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                @php
                                    $finalPrice = floatval($product['final_wholesale_price'] ?? $product['wholesale_price'] ?? $product['price'] ?? 0);
                                    $basePrice = floatval($product['base_price'] ?? $product['wholesale_price'] ?? $product['price'] ?? 0);
                                    $commPercent = floatval($product['commission_percent'] ?? 0);
                                    $commAmount = floatval($product['commission_amount'] ?? 0);
                                    $hasVariants = !empty($product['has_variants']) && count($product['variants']) > 0;
                                    $varWholesaleMin = floatval($product['variants_wholesale_min'] ?? 0);
                                    $varWholesaleMax = floatval($product['variants_wholesale_max'] ?? 0);
                                @endphp
                                
                                @if($hasVariants && $varWholesaleMin > 0)
                                    <div class="d-flex flex-column">
                                        <span class="text-success font-bold" style="font-weight: 700; font-size: 13px;">
                                            @if($varWholesaleMin != $varWholesaleMax)
                                                ৳{{ number_format($varWholesaleMin, 2) }} - ৳{{ number_format($varWholesaleMax, 2) }}
                                            @else
                                                ৳{{ number_format($varWholesaleMin, 2) }}
                                            @endif
                                            @if($isSuperAdmin && $commPercent > 0)
                                                <span class="badge rounded-pill px-1.5 py-0.5" style="background-color: #ecfdf5; color: #047857; font-size: 9.5px; font-weight: 600;">
                                                    +{{ $commPercent }}% profit
                                                </span>
                                            @endif
                                        </span>
                                        <span class="badge rounded-pill bg-light text-muted border px-1.5 py-0.5 mt-0.5 align-self-start" style="font-size: 9px;">
                                            Variable Wholesale
                                        </span>
                                    </div>
                                @elseif($isSuperAdmin && $commPercent > 0 && $commAmount > 0)
                                    <div class="d-flex flex-column">
                                        <span class="text-success font-bold d-inline-flex align-items-center gap-1.5" style="font-weight: 700; font-size: 14px;">
                                            ৳{{ number_format($finalPrice, 2) }}
                                            <span class="badge rounded-pill px-1.5 py-0.5" style="background-color: #ecfdf5; color: #047857; font-size: 9.5px; font-weight: 600;">
                                                +{{ $commPercent }}% profit
                                            </span>
                                        </span>
                                        <div class="text-muted d-flex align-items-center gap-1 mt-0.5" style="font-size: 10.5px;">
                                            <span>Base: <strong style="color: #475569;">৳{{ number_format($basePrice, 2) }}</strong></span>
                                            <span style="color: #cbd5e1;">&bull;</span>
                                            <span style="color: #059669; font-weight: 600;">+৳{{ number_format($commAmount, 2) }} profit</span>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-success font-bold" style="font-weight: 700; font-size: 14px;">
                                        ৳{{ number_format($finalPrice, 2) }}
                                    </span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                @if($product['quantity'] > 0)
                                    <span class="text-slate-700" style="font-weight: 500;">{{ $product['quantity'] }} pcs</span>
                                @else
                                    <span class="text-danger font-semibold" style="font-weight: 600;">0 pcs</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;" id="status-cell-{{ $rowUniqueKey }}">
                                @if(!empty($product['is_own_product']) || ($product['store_status_type'] ?? '') === 'own_product')
                                    <span class="badge rounded-pill px-2.5 py-1" style="background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-weight: 600; font-size: 11px;">
                                        <i class="fas fa-store me-1"></i> Own Product
                                    </span>
                                @elseif(($product['store_status_type'] ?? '') === 'purchased')
                                    <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-2.5 py-1" style="font-size: 11px; font-weight: 700;">
                                        <i class="fas fa-boxes-stacked me-1"></i> Purchased ({{ $product['local_stock'] ?? 0 }} in store)
                                    </span>
                                @elseif(($product['store_status_type'] ?? '') === 'copied' || !empty($product['is_already_copied']))
                                    <span class="badge rounded-pill px-2.5 py-1" style="background-color: #ede9fe; color: #6366f1; border: 1px solid #c7d2fe; font-size: 11px; font-weight: 700;">
                                        <i class="fas fa-file-import me-1"></i> Imported
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light text-muted border px-2.5 py-1" style="font-size: 11px; font-weight: 600;">
                                        <i class="fas fa-plus me-1"></i> Ready to Add
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end" style="white-space: nowrap;" id="action-cell-{{ $rowUniqueKey }}">
                                @php
                                    $sStatus = $product['store_status_type'] ?? ($product['is_already_copied'] ? 'copied' : 'not_in_store');
                                    $lStock = $product['local_stock'] ?? 0;
                                @endphp
                                @if(!empty($product['is_own_product']) || $sStatus === 'own_product')
                                    <div class="d-inline-flex align-items-center gap-1.5">
                                        <span class="badge rounded-pill px-2.5 py-1.5 font-semibold" style="background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 0.775rem;">
                                            <i class="fas fa-store text-muted me-1"></i> Own Product
                                        </span>
                                        @if(!empty($product['local_product_id']) || !empty($product['id']))
                                            <a href="{{ route('admin.items.edit', $product['local_product_id'] ?? $product['id']) }}" class="btn btn-sm btn-light border rounded-3 px-2 py-1" style="font-size: 0.775rem; color: #475569;" title="Edit Product in Catalog">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @endif
                                    </div>
                                @elseif($sStatus === 'purchased')
                                    <div class="d-inline-flex align-items-center gap-1.5">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-secondary rounded-3 px-2.5 py-1.5 font-semibold d-inline-flex align-items-center gap-1 shadow-sm" 
                                                style="font-size: 0.775rem;"
                                                disabled
                                                title="Already imported into store catalog">
                                            <i class="fas fa-check"></i> Imported
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-success rounded-3 px-2.5 py-1.5 font-semibold d-inline-flex align-items-center gap-1 shadow-sm" 
                                                id="btn-action-{{ $rowUniqueKey }}"
                                                style="font-size: 0.775rem;"
                                                onclick="openPurchaseModal('{{ $product['tenant_subdomain'] }}', '{{ $product['id'] }}', '{{ addslashes($product['title']) }}', '{{ $product['final_wholesale_price'] }}', '{{ $product['quantity'] }}', '{{ $rowUniqueKey }}', 'purchased', '{{ $lStock }}', 'purchase')">
                                            <i class="fas fa-cart-plus"></i> Buy More Stock
                                        </button>
                                    </div>
                                @elseif($sStatus === 'copied')
                                    <div class="d-inline-flex align-items-center gap-1.5">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-secondary rounded-3 px-2.5 py-1.5 font-semibold d-inline-flex align-items-center gap-1 shadow-sm" 
                                                style="font-size: 0.775rem;"
                                                disabled
                                                title="Already imported into store catalog">
                                            <i class="fas fa-check"></i> Imported
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-primary rounded-3 px-2.5 py-1.5 font-semibold d-inline-flex align-items-center gap-1 shadow-sm" 
                                                id="btn-action-{{ $rowUniqueKey }}"
                                                style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); border: none; font-size: 0.775rem;"
                                                onclick="openPurchaseModal('{{ $product['tenant_subdomain'] }}', '{{ $product['id'] }}', '{{ addslashes($product['title']) }}', '{{ $product['final_wholesale_price'] }}', '{{ $product['quantity'] }}', '{{ $rowUniqueKey }}', 'copied', '0', 'purchase')">
                                            <i class="fas fa-cart-shopping"></i> Purchase
                                        </button>
                                    </div>
                                @else
                                    <div class="d-inline-flex align-items-center gap-1.5">
                                        <button type="button" 
                                                class="btn btn-sm btn-light border rounded-3 px-2.5 py-1.5 font-semibold d-inline-flex align-items-center gap-1 shadow-sm" 
                                                id="btn-action-copy-{{ $rowUniqueKey }}"
                                                style="background: #f5f3ff; border-color: #ddd6fe !important; color: #4f46e5 !important; font-size: 0.775rem;"
                                                onclick="openPurchaseModal('{{ $product['tenant_subdomain'] }}', '{{ $product['id'] }}', '{{ addslashes($product['title']) }}', '{{ $product['final_wholesale_price'] }}', '{{ $product['quantity'] }}', '{{ $rowUniqueKey }}', 'not_in_store', '0', 'copy')">
                                            <i class="fas fa-file-import"></i> Import
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-primary rounded-3 px-2.5 py-1.5 font-semibold d-inline-flex align-items-center gap-1 shadow-sm" 
                                                id="btn-action-purchase-{{ $rowUniqueKey }}"
                                                style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); border: none; font-size: 0.775rem;"
                                                onclick="openPurchaseModal('{{ $product['tenant_subdomain'] }}', '{{ $product['id'] }}', '{{ addslashes($product['title']) }}', '{{ $product['final_wholesale_price'] }}', '{{ $product['quantity'] }}', '{{ $rowUniqueKey }}', 'not_in_store', '0', 'purchase')">
                                            <i class="fas fa-cart-shopping"></i> Purchase
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>

                        {{-- Collapsible Child Variant Details --}}
                        @if($hasVariants)
                            <tr class="p-0 border-0">
                                <td colspan="{{ $isSuperAdmin ? 10 : 9 }}" class="p-0 border-0">
                                    <div class="collapse" id="variants-{{ $rowUniqueKey }}">
                                        <div class="p-3 bg-light border-bottom" style="background-color: #f8fafc !important;">
                                            <div class="card border rounded-3 shadow-none overflow-hidden bg-white">
                                                <div class="card-header bg-light py-2 px-3 border-bottom d-flex align-items-center justify-content-between" style="background-color: #f1f5f9;">
                                                    <span class="small font-semibold text-slate-700" style="font-weight: 600;">
                                                        <i class="fas fa-sitemap text-primary me-1.5"></i>Available Variants ({{ count($product['variants']) }})
                                                    </span>
                                                    <span class="text-muted small" style="font-size: 11px;">Source Product ID #{{ $product['id'] }}</span>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-borderless align-middle mb-0">
                                                        <thead class="text-uppercase text-muted" style="font-size: 10px; background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                                            <tr>
                                                                <th class="ps-3 py-2" style="width: 50px;">Image</th>
                                                                <th class="py-2">Variant / Options</th>
                                                                <th class="py-2">SKU</th>
                                                                <th class="py-2">Retail Price</th>
                                                                <th class="py-2">{{ $currentTab === 'admin' ? 'Global Price' : 'Wholesale Price' }}</th>
                                                                <th class="pe-3 py-2 text-end">Stock</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($product['variants'] as $variant)
                                                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                                                    <td class="ps-3 py-2" style="width: 50px;">
                                                                        @if(!empty($variant['image']))
                                                                            <img src="{{ $variant['image'] }}" 
                                                                                 alt="{{ $variant['display_name'] }}" 
                                                                                 class="rounded border" 
                                                                                 style="width: 32px; height: 32px; object-fit: cover;" 
                                                                                 onerror="this.style.display='none';">
                                                                        @else
                                                                            <div class="rounded border bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                                <i class="fas fa-image text-muted" style="font-size: 0.75rem;"></i>
                                                                            </div>
                                                                        @endif
                                                                    </td>
                                                                    <td class="py-2">
                                                                        <span class="font-medium text-slate-800 d-block small" style="font-weight: 600;">
                                                                            {{ $variant['display_name'] }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="py-2">
                                                                        <span class="text-muted small" style="font-family: monospace; font-size: 11px;">
                                                                            {{ $variant['sku'] ?: '—' }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="py-2">
                                                                        @php
                                                                            $varSell = floatval($variant['price'] ?? 0);
                                                                            $varReg = floatval($variant['regular_price'] ?? 0);
                                                                            $varOffer = floatval($variant['offer_price'] ?? 0);
                                                                            $varDisplaySell = $varOffer > 0 ? $varOffer : ($varSell > 0 ? $varSell : $varReg);
                                                                            $varDisplayOld = $varReg > 0 ? $varReg : 0;
                                                                        @endphp
                                                                        <span class="text-slate-800 small font-semibold" style="font-weight: 600;">৳{{ number_format($varDisplaySell, 2) }}</span>
                                                                        @if($varDisplayOld > 0 && $varDisplayOld > $varDisplaySell)
                                                                            <del class="text-muted d-block" style="font-size: 9px;">৳{{ number_format($varDisplayOld, 2) }}</del>
                                                                        @endif
                                                                    </td>
                                                                    <td class="py-2" style="white-space: nowrap;">
                                                                        @php
                                                                            $vFinal = floatval($variant['final_wholesale_price'] ?: 0);
                                                                            $vBase = floatval($variant['base_price'] ?? 0);
                                                                            $vComm = floatval($variant['commission_amount'] ?? 0);
                                                                        @endphp
                                                                        <div class="d-flex flex-column">
                                                                            <span class="text-success font-bold small" style="font-weight: 700;">
                                                                                ৳{{ number_format($vFinal, 2) }}
                                                                            </span>
                                                                            @if($isSuperAdmin && $vComm > 0)
                                                                                <div class="text-muted" style="font-size: 9.5px; margin-top: 1px;">
                                                                                    Base: ৳{{ number_format($vBase, 2) }} <span style="color: #059669; font-weight: 600;">(+৳{{ number_format($vComm, 2) }} profit)</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                    <td class="pe-3 py-2 text-end">
                                                                        @if($variant['stock_quantity'] > 0)
                                                                            <span class="text-slate-700 small" style="font-weight: 500;">{{ $variant['stock_quantity'] }} pcs</span>
                                                                        @else
                                                                            <span class="text-danger small font-semibold" style="font-weight: 600;">0 pcs</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-boxes text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <h5 class="text-slate-600 mb-1" style="font-weight: 600;">No Global Products Found</h5>
                                    <p class="text-muted small">
                                        {{ $currentTab === 'admin' 
                                            ? 'No products found from administrators across active tenants with free promotion enabled.' 
                                            : 'No products found from registered wholesellers across active tenants.' }}
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paginatedProducts->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $paginatedProducts->links() }}
            </div>
        @endif
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function toggleVariantRow(uniqueKey) {
    const collapseElem = document.getElementById('variants-' + uniqueKey);
    const chevron = document.getElementById('chevron-' + uniqueKey);

    if (!collapseElem) return;

    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseElem, { toggle: false });

    if (collapseElem.classList.contains('show')) {
        bsCollapse.hide();
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    } else {
        bsCollapse.show();
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
}

function toggleSelectAll(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.product-check:not(:disabled)');
    checkboxes.forEach(cb => {
        cb.checked = masterCheckbox.checked;
    });
    updateBulkButtonState();
}

function updateBulkButtonState() {
    const checked = document.querySelectorAll('.product-check:checked');
    const count = checked.length;

    const btnImport = document.getElementById('btn-bulk-import');
    const btnPurchase = document.getElementById('btn-bulk-purchase');
    const countImportSpan = document.getElementById('selected-import-count');
    const countPurchaseSpan = document.getElementById('selected-purchase-count');

    if (countImportSpan) countImportSpan.textContent = count;
    if (countPurchaseSpan) countPurchaseSpan.textContent = count;

    if (btnImport) btnImport.disabled = (count === 0);
    if (btnPurchase) btnPurchase.disabled = (count === 0);
}

// Gateway details passed from backend
const GATEWAYS = {
    bkash: {
        name: 'bKash',
        number: '{{ $superAdminBkash }}',
        instruction: 'Send exact payment to Super Admin bKash number <strong>{{ $superAdminBkash }}</strong> and enter your sender number and Transaction ID (TrxID) below.'
    },
    nagad: {
        name: 'Nagad',
        number: '{{ $superAdminNagad }}',
        instruction: 'Send exact payment to Super Admin Nagad number <strong>{{ $superAdminNagad }}</strong> and enter your sender number and Transaction ID (TrxID) below.'
    },
    rocket: {
        name: 'Rocket',
        number: '{{ $superAdminRocket }}',
        instruction: 'Send exact payment to Super Admin Rocket number <strong>{{ $superAdminRocket }}</strong> and enter your sender number and Transaction ID (TrxID) below.'
    },
    bank: {
        name: 'Bank Transfer',
        number: '{{ $superAdminBank }}',
        instruction: 'Transfer payment to Super Admin Bank Account: <strong>{{ $superAdminBank }}</strong> and enter your deposit reference or TrxID below.'
    }
};

// Open Purchase / Copy Modal with Modern Sleek Design
function openPurchaseModal(subdomain, productId, title, unitPrice, availableStock, rowKey, storeStatusType = 'not_in_store', localStock = 0, defaultMode = 'auto') {
    const btn = document.getElementById('btn-action-' + rowKey) || document.getElementById('btn-action-purchase-' + rowKey) || document.getElementById('btn-action-copy-' + rowKey);
    const originalContent = btn ? btn.innerHTML : '';
    const numPrice = parseFloat(unitPrice) || 0;
    const numStock = parseInt(availableStock) || 0;
    const isAlreadyInStore = (storeStatusType === 'purchased' || storeStatusType === 'copied');
    const isPurchaseDefault = (defaultMode === 'purchase' || (defaultMode === 'auto' && isAlreadyInStore));
    const isCopyDefault = !isPurchaseDefault;

    const modalHtml = `
        <div class="text-start" style="font-size: 13px;">
            <!-- Product Header Hero -->
            <div class="modal-product-hero">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge rounded-pill px-2.5 py-1" style="background-color: #e0e7ff; color: #4338ca; font-weight: 700; font-size: 11px;">
                        <i class="fas fa-store me-1"></i> Supplier: @${subdomain}
                    </span>
                    <span class="badge rounded-pill px-2.5 py-1" style="background-color: #ecfdf5; color: #047857; font-weight: 700; font-size: 11px;">
                        <i class="fas fa-cubes me-1"></i> ${numStock} pcs available
                    </span>
                </div>
                <div class="text-slate-800 fw-bold mb-2" style="font-size: 14px; line-height: 1.4; color: #1e293b;">
                    ${title}
                </div>
                <div class="d-flex align-items-center justify-content-between pt-2 border-top" style="border-top-color: #e2e8f0 !important;">
                    <span class="text-muted small" style="font-size: 11.5px;">Wholesale Unit Price:</span>
                    <span class="text-success fw-bold" style="font-size: 15px; color: #059669;">৳${numPrice.toFixed(2)} <small class="text-muted" style="font-size: 11px; font-weight: normal;">/ unit</small></span>
                </div>
            </div>

            ${isAlreadyInStore ? `
                <div class="alert alert-info py-2 px-2.5 rounded-3 mb-3 small d-flex align-items-center justify-content-between" style="font-size: 11.5px; background-color: #f0fdf4; border-color: #bbf7d0; color: #166534;">
                    <span><i class="fas fa-boxes-stacked text-success me-1"></i> <strong>Current Store Stock:</strong> ${localStock} pcs</span>
                    <span class="fw-semibold text-success"><i class="fas fa-plus-circle me-1"></i> Restocking adds to your inventory</span>
                </div>
            ` : ''}

            <!-- Action Mode Selection Cards -->
            <div class="mb-3">
                <label class="form-label small text-uppercase text-muted fw-bold mb-2" style="font-size: 10.5px; letter-spacing: 0.5px;">Choose Fulfillment Action:</label>

                <!-- Option 1: Instant Catalog Sync (Listing Only) -->
                <div class="action-mode-box ${isCopyDefault ? 'active-copy' : ''}" id="box-opt-copy" style="margin-bottom: 14px;" onclick="selectActionOption('copy')">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="action-icon-circle" style="background: #e0e7ff; color: #4338ca;">
                            <i class="fas fa-clone"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-0.5">
                                <span class="fw-bold text-slate-800" style="font-size: 13px; color: #0f172a;">
                                    Instant Catalog Sync (Listing Only)
                                </span>
                                <span class="badge rounded-pill px-2 py-0.5" style="background-color: #e0e7ff; color: #3730a3; font-size: 9.5px; font-weight: 700;">
                                    ${isAlreadyInStore ? 'Synced' : 'Free Sync'}
                                </span>
                            </div>
                            <div class="text-muted" style="font-size: 11.5px; line-height: 1.35;">
                                ${isAlreadyInStore ? 'Already in your store catalog. Select wholesale purchase below to add physical stock.' : 'Add this product to your store catalog immediately without buying inventory stock.'}
                            </div>
                        </div>
                        <input class="form-check-input mt-1" type="radio" name="swal_global_action" id="opt_copy" value="copy" ${isCopyDefault ? 'checked' : ''} onchange="selectActionOption('copy')" style="cursor: pointer;">
                    </div>
                </div>

                <!-- Option 2: Purchase Wholesale Stock -->
                <div class="action-mode-box ${isPurchaseDefault ? 'active-purchase' : ''}" id="box-opt-purchase" onclick="selectActionOption('purchase')">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="action-icon-circle" style="background: #dcfce7; color: #16a34a;">
                            <i class="fas fa-truck-ramp-box"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-0.5">
                                <span class="fw-bold text-slate-800" style="font-size: 13px; color: #0f172a;">
                                    ${isAlreadyInStore ? 'Buy Additional Stock / Restock' : 'Purchase Wholesale Stock (Super Admin Payment)'}
                                </span>
                                <span class="badge rounded-pill px-2 py-0.5" style="background-color: #dcfce7; color: #15803d; font-size: 9.5px; font-weight: 700;">
                                    Physical Delivery
                                </span>
                            </div>
                            <div class="text-muted" style="font-size: 11.5px; line-height: 1.35;">
                                Pay to Super Admin. On delivery, ${numPrice > 0 ? 'the exact ordered units will be added to your store stock.' : 'supplier ships physical stock to your store.'}
                            </div>
                        </div>
                        <input class="form-check-input mt-1" type="radio" name="swal_global_action" id="opt_purchase" value="purchase" ${isPurchaseDefault ? 'checked' : ''} onchange="selectActionOption('purchase')" style="cursor: pointer;">
                    </div>

                    <!-- Purchase Form Details -->
                    <div id="purchase-details-section" class="mt-3 pt-3 border-top" style="${isPurchaseDefault ? 'display: block;' : 'display: none;'} border-top-color: #bbf7d0 !important;">
                        <!-- Quantity & Total -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                    <i class="fas fa-boxes-stacked text-primary me-1"></i> Order Units <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="swal_purchase_qty" class="form-control form-control-clean text-center fw-bold" value="10" min="1" max="${numStock > 0 ? numStock : 99999}" oninput="updatePurchaseTotal(${numPrice})">
                            </div>
                            <div class="col-6">
                                <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                    <i class="fas fa-receipt text-success me-1"></i> Payable Total
                                </label>
                                <div class="form-control form-control-clean text-center fw-bold" id="swal_total_cost" style="background-color: #ecfdf5; color: #059669; font-size: 13.5px; border-color: #a7f3d0;">
                                    ৳${(numPrice * 10).toFixed(2)}
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Destination Address -->
                        <div class="mb-3">
                            <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                <i class="fas fa-location-dot text-danger me-1"></i> Delivery Address (Where supplier will ship) <span class="text-danger">*</span>
                            </label>
                            <textarea id="swal_shipping_address" class="form-control form-control-clean" rows="2" placeholder="Complete address: Street, Area, City, District"></textarea>
                        </div>

                        <!-- Contact Mobile -->
                        <div class="mb-3">
                            <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                <i class="fas fa-phone text-secondary me-1"></i> Contact Mobile Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="swal_contact_phone" class="form-control form-control-clean" placeholder="017xxxxxxxx" value="{{ auth()->user()?->phone ?? '' }}">
                        </div>

                        <!-- Super Admin Payment Gateway -->
                        <div class="mb-3">
                            <label class="small text-slate-700 fw-semibold mb-2 d-block" style="font-size: 11.5px;">
                                <i class="fas fa-wallet text-warning me-1"></i> Select Payment Gateway <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex flex-wrap gap-2 mb-2.5">
                                @if($superAdminBkashEnabled ?? true)
                                <div class="gateway-pill active" data-gw="bkash" onclick="changePayGateway('bkash', this)">
                                    <input type="radio" name="swal_pay_gateway" value="bkash" class="d-none" checked>
                                    <i class="fas fa-bolt text-danger"></i> bKash
                                </div>
                                @endif
                                @if($superAdminNagadEnabled ?? true)
                                <div class="gateway-pill {{ !($superAdminBkashEnabled ?? true) ? 'active' : '' }}" data-gw="nagad" onclick="changePayGateway('nagad', this)">
                                    <input type="radio" name="swal_pay_gateway" value="nagad" class="d-none" {{ !($superAdminBkashEnabled ?? true) ? 'checked' : '' }}>
                                    <i class="fas fa-fire text-warning"></i> Nagad
                                </div>
                                @endif
                                @if($superAdminRocketEnabled ?? true)
                                <div class="gateway-pill {{ !($superAdminBkashEnabled ?? true) && !($superAdminNagadEnabled ?? true) ? 'active' : '' }}" data-gw="rocket" onclick="changePayGateway('rocket', this)">
                                    <input type="radio" name="swal_pay_gateway" value="rocket" class="d-none" {{ !($superAdminBkashEnabled ?? true) && !($superAdminNagadEnabled ?? true) ? 'checked' : '' }}>
                                    <i class="fas fa-paper-plane" style="color: #8c3494;"></i> Rocket
                                </div>
                                @endif
                                @if($superAdminBankEnabled ?? true)
                                <div class="gateway-pill" data-gw="bank" onclick="changePayGateway('bank', this)">
                                    <input type="radio" name="swal_pay_gateway" value="bank" class="d-none">
                                    <i class="fas fa-building-columns text-success"></i> Bank Transfer
                                </div>
                                @endif
                            </div>

                            <!-- Payment Instruction Card -->
                            <div class="p-2.5 rounded-3 mb-2 small border" id="swal_gateway_instruction" style="background-color: #f8fafc; border-color: #e2e8f0; font-size: 11.5px; line-height: 1.45;">
                                ${GATEWAYS.bkash.instruction}
                            </div>
                        </div>

                        <!-- Sender Mobile & TrxID -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                    <i class="fas fa-mobile-screen me-1"></i> Sender Mobile / AC
                                </label>
                                <input type="text" id="swal_sender_phone" class="form-control form-control-clean" placeholder="018xxxxxxxx">
                            </div>
                            <div class="col-6">
                                <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                    <i class="fas fa-key text-primary me-1"></i> Transaction ID (TrxID) <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="swal_trx_id" class="form-control form-control-clean fw-bold" placeholder="e.g. 9J87K12A">
                            </div>
                        </div>

                        <!-- Payment Screenshot / Receipt Proof -->
                        <div class="mb-2">
                            <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                <i class="fas fa-image text-info me-1"></i> Payment Proof Screenshot / Slip <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="file" id="swal_payment_screenshot" class="form-control form-control-clean" accept="image/*" style="padding: 6px 10px; font-size: 12px;" onchange="previewPaymentScreenshot(this)">
                            <div id="swal_screenshot_preview_box" class="mt-2 text-center" style="display: none;">
                                <img id="swal_screenshot_preview_img" src="" alt="Receipt Preview" class="rounded-3 border shadow-sm" style="max-height: 100px; max-width: 100%; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    Swal.fire({
        title: '<i class="fas fa-cart-flatbed text-primary me-2"></i>Wholesale Fulfillment',
        html: modalHtml,
        showCancelButton: true,
        confirmButtonText: isPurchaseDefault ? '<i class="fas fa-check-circle me-1.5"></i> Submit Wholesale Order' : '<i class="fas fa-clone me-1.5"></i> Sync Product to Catalog',
        cancelButtonText: 'Cancel',
        focusConfirm: false,
        width: '640px',
        customClass: {
            popup: 'modern-wholesale-popup'
        },
        didOpen: () => {
            window.selectActionOption = function(mode) {
                const isPurchase = (mode === 'purchase');
                document.getElementById('opt_purchase').checked = isPurchase;
                document.getElementById('opt_copy').checked = !isPurchase;

                const boxPurchase = document.getElementById('box-opt-purchase');
                const boxCopy = document.getElementById('box-opt-copy');
                const detailsSection = document.getElementById('purchase-details-section');
                const confirmBtn = Swal.getConfirmButton();

                if (isPurchase) {
                    boxPurchase.classList.add('active-purchase');
                    boxCopy.classList.remove('active-copy');
                    if (detailsSection) detailsSection.style.display = 'block';
                    if (confirmBtn) confirmBtn.innerHTML = '<i class="fas fa-check-circle me-1.5"></i> Submit Wholesale Order';
                } else {
                    boxPurchase.classList.remove('active-purchase');
                    boxCopy.classList.add('active-copy');
                    if (detailsSection) detailsSection.style.display = 'none';
                    if (confirmBtn) confirmBtn.innerHTML = '<i class="fas fa-clone me-1.5"></i> Sync Product to Catalog';
                }
            };

            window.changePayGateway = function(gatewayKey, pillEl) {
                document.querySelectorAll('input[name="swal_pay_gateway"]').forEach(i => i.checked = false);
                document.querySelectorAll('.gateway-pill').forEach(b => b.classList.remove('active'));
                
                const radio = pillEl.querySelector('input');
                if (radio) radio.checked = true;
                pillEl.classList.add('active');

                const gInfo = GATEWAYS[gatewayKey];
                const instBox = document.getElementById('swal_gateway_instruction');
                if (instBox && gInfo) {
                    instBox.innerHTML = gInfo.instruction;
                }
            };

            window.previewPaymentScreenshot = function(input) {
                const previewBox = document.getElementById('swal_screenshot_preview_box');
                const previewImg = document.getElementById('swal_screenshot_preview_img');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewBox.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    previewBox.style.display = 'none';
                }
            };

            window.updatePurchaseTotal = function(price) {
                const qty = parseInt(document.getElementById('swal_purchase_qty').value) || 0;
                const totalElem = document.getElementById('swal_total_cost');
                if (totalElem) {
                    totalElem.textContent = '৳' + (price * qty).toFixed(2);
                }
            };
        },
        preConfirm: () => {
            const isPurchase = document.getElementById('opt_purchase').checked;
            if (!isPurchase) {
                return { mode: 'copy' };
            }

            const qty = parseInt(document.getElementById('swal_purchase_qty').value) || 0;
            const address = document.getElementById('swal_shipping_address').value.trim();
            const phone = document.getElementById('swal_contact_phone').value.trim();
            const gatewayRadio = document.querySelector('input[name="swal_pay_gateway"]:checked');
            const gateway = gatewayRadio ? gatewayRadio.value : 'bkash';
            const senderPhone = document.getElementById('swal_sender_phone').value.trim();
            const trxId = document.getElementById('swal_trx_id').value.trim();
            const screenshotInput = document.getElementById('swal_payment_screenshot');
            const screenshotFile = (screenshotInput && screenshotInput.files && screenshotInput.files[0]) ? screenshotInput.files[0] : null;

            if (qty <= 0) {
                Swal.showValidationMessage('Please enter a valid purchase quantity (at least 1 unit)');
                return false;
            }
            if (!address) {
                Swal.showValidationMessage('Please enter the delivery shipping address');
                return false;
            }
            if (!phone) {
                Swal.showValidationMessage('Please enter your contact mobile number');
                return false;
            }
            if (!trxId) {
                Swal.showValidationMessage('Please enter the payment Transaction ID (TrxID)');
                return false;
            }

            return {
                mode: 'purchase',
                quantity: qty,
                shipping_address: address,
                contact_phone: phone,
                gateway: gateway,
                sender_phone: senderPhone,
                trx_id: trxId,
                payment_screenshot: screenshotFile
            };
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const data = result.value;

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
            }

            if (data.mode === 'purchase') {
                // Submit Wholesale Purchase Order with FormData (supports screenshot file)
                const formData = new FormData();
                formData.append('subdomain', subdomain);
                formData.append('product_id', productId);
                formData.append('quantity', data.quantity);
                formData.append('shipping_address', data.shipping_address);
                formData.append('contact_phone', data.contact_phone);
                formData.append('gateway', data.gateway);
                formData.append('sender_phone', data.sender_phone);
                formData.append('trx_id', data.trx_id);
                if (data.payment_screenshot) {
                    formData.append('payment_screenshot', data.payment_screenshot);
                }

                fetch("{{ route('admin.wholesale-orders.checkout') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(resData => {
                    if (resData.success) {
                        Swal.fire({
                            title: 'Order Placed & Payment Submitted!',
                            html: `
                                <div class="text-start small">
                                    <div class="alert alert-success border-0 mb-3 p-3 rounded-3">
                                        <div class="fw-bold fs-6 mb-1">Order #${resData.order_number}</div>
                                        <div>Your wholesale order has been submitted and is pending Super Admin payment verification.</div>
                                    </div>
                                    <ul class="text-muted ps-3 mb-3">
                                        <li>Super Admin will verify your <strong>${data.gateway.toUpperCase()}</strong> transaction (<strong>${data.trx_id}</strong>).</li>
                                        <li>Upon acceptance, a delivery order will be dispatched to supplier (<strong>@${subdomain}</strong>) to ship goods to your address.</li>
                                        <li>Purchased units will be allocated into your store catalog.</li>
                                    </ul>
                                </div>
                            `,
                            icon: 'success',
                            showCancelButton: true,
                            confirmButtonColor: '#4f46e5',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: '<i class="fas fa-receipt me-1"></i> View My Orders',
                            cancelButtonText: 'Continue Browsing'
                        }).then((r) => {
                            if (r.isConfirmed) {
                                window.location.href = "{{ route('admin.wholesale-orders.index') }}";
                            }
                        });

                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-check me-1"></i> Order Placed';
                            btn.classList.remove('btn-primary');
                            btn.classList.add('btn-success');
                        }
                    } else {
                        Swal.fire('Error', resData.message || 'Could not place wholesale order.', 'error');
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = originalContent;
                        }
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Network error submitting order.', 'error');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                });

            } else {
                // Just Copy Mode (Catalog Listing)
                fetch("{{ route('admin.global-products.copy') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        subdomain: subdomain,
                        product_id: productId,
                        copy_mode: 'copy',
                        quantity: 0
                    })
                })
                .then(res => res.json())
                .then(resData => {
                    if (resData.success) {
                        Swal.fire({
                            title: 'Product Copied!',
                            text: resData.message,
                            icon: 'success',
                            confirmButtonColor: '#4f46e5'
                        });

                        const statusCell = document.getElementById('status-cell-' + rowKey);
                        if (statusCell) {
                            statusCell.innerHTML = '<span class="badge-in-store"><i class="fas fa-check-circle"></i> In Store</span>';
                        }
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-check me-1"></i> Copied';
                            btn.classList.remove('btn-primary');
                            btn.classList.add('btn-success');
                            setTimeout(() => {
                                btn.classList.remove('btn-success');
                                btn.classList.add('btn-primary');
                                btn.innerHTML = '<i class="fas fa-cart-shopping"></i> Purchase';
                            }, 2500);
                        }
                    } else {
                        Swal.fire('Error', resData.message || 'Failed to copy product', 'error');
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = originalContent;
                        }
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Network error occurred', 'error');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = originalContent;
                    }
                });
            }
        }
    });
}

// Bulk Import Selected (Free Listing - 0 Stock)
function openBulkImportModal() {
    const checked = document.querySelectorAll('.product-check:checked');
    if (checked.length === 0) {
        Swal.fire('No Products Selected', 'Please select one or more products using the checkboxes first.', 'info');
        return;
    }

    const items = [];
    checked.forEach(cb => {
        items.push({
            subdomain: cb.dataset.subdomain,
            product_id: parseInt(cb.dataset.id),
            title: cb.dataset.title
        });
    });

    Swal.fire({
        title: '<i class="fas fa-file-import text-primary me-2"></i>Import Selected Products',
        html: `
            <div class="text-start" style="font-size: 13px;">
                <p class="mb-2">Are you sure you want to import <strong>${items.length} selected product(s)</strong> into your store catalog?</p>
                <div class="alert alert-light border small text-muted p-2.5 rounded-3 mb-0">
                    <i class="fas fa-info-circle text-primary me-1"></i> These products will be added to your catalog immediately with <strong>0 inventory stock</strong> (Listing Only). You can sell on-demand and purchase wholesale stock anytime.
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: `<i class="fas fa-file-import me-1"></i> Yes, Import ${items.length} Products`,
        cancelButtonText: 'Cancel',
        focusConfirm: false
    }).then((res) => {
        if (res.isConfirmed) {
            Swal.fire({
                title: 'Importing Products...',
                html: `Syncing ${items.length} products to your store catalog...`,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            fetch("{{ route('admin.global-products.bulk-copy') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    items: items,
                    copy_mode: 'copy'
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Import Complete!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4f46e5'
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Import Error', data.message || 'Failed to import products.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Network error occurred during bulk import.', 'error');
            });
        }
    });
}

// Bulk Wholesale Purchase Modal
function openBulkPurchaseModal() {
    const checked = document.querySelectorAll('.product-check:checked');
    if (checked.length === 0) {
        Swal.fire('No Products Selected', 'Please select one or more products using the checkboxes first.', 'info');
        return;
    }

    const bulkItems = [];
    checked.forEach((cb, idx) => {
        bulkItems.push({
            subdomain: cb.dataset.subdomain,
            product_id: parseInt(cb.dataset.id),
            title: cb.dataset.title,
            unit_price: parseFloat(cb.dataset.unitPrice) || 0,
            stock: parseInt(cb.dataset.stock) || 0,
            image: cb.dataset.image || '',
            status_type: cb.dataset.statusType || 'not_in_store',
            local_stock: parseInt(cb.dataset.localStock) || 0,
            quantity: 10
        });
    });

    let itemsTableHtml = `
        <div class="table-responsive mb-3 border rounded-3 overflow-hidden" style="max-height: 220px; overflow-y: auto;">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size: 12px;">
                <thead class="table-light">
                    <tr>
                        <th style="width: 45px;">Image</th>
                        <th>Product & Supplier</th>
                        <th class="text-end" style="width: 90px;">Unit Price</th>
                        <th class="text-center" style="width: 100px;">Quantity</th>
                        <th class="text-end" style="width: 100px;">Total (৳)</th>
                    </tr>
                </thead>
                <tbody>
    `;

    let initialGrandTotal = 0;
    bulkItems.forEach((item, idx) => {
        const lineTotal = item.unit_price * item.quantity;
        initialGrandTotal += lineTotal;
        const imgTag = item.image 
            ? `<img src="${item.image}" class="rounded-2 border shadow-xs" style="width: 32px; height: 32px; object-fit: cover;">`
            : `<div class="rounded-2 border bg-light d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;"><i class="fas fa-image text-muted" style="font-size: 10px;"></i></div>`;

        let statusBadge = '';
        if (item.status_type === 'purchased') {
            statusBadge = `<span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 8.5px; font-weight: 700;"><i class="fas fa-box-check me-0.5"></i> ${item.local_stock} in stock</span>`;
        } else if (item.status_type === 'copied') {
            statusBadge = `<span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle ms-1" style="font-size: 8.5px; font-weight: 700;">Catalog Synced</span>`;
        }

        itemsTableHtml += `
            <tr id="bulk-row-${idx}">
                <td>${imgTag}</td>
                <td>
                    <div class="fw-semibold text-slate-800 text-truncate" style="max-width: 190px;" title="${item.title}">${item.title}</div>
                    <div class="d-flex align-items-center gap-1 mt-0.5">
                        <span class="badge rounded-pill" style="background-color: #e0e7ff; color: #4338ca; font-size: 9px; font-weight: 600;">@${item.subdomain}</span>
                        ${statusBadge}
                    </div>
                </td>
                <td class="text-end fw-bold text-slate-700">৳${item.unit_price.toFixed(2)}</td>
                <td class="text-center">
                    <input type="number" class="form-control form-control-sm text-center fw-bold px-1" id="bulk_qty_${idx}" value="${item.quantity}" min="1" max="${item.stock > 0 ? item.stock : 99999}" style="height: 28px; font-size: 12px;" oninput="window.updateBulkItemLineTotal(${idx})">
                </td>
                <td class="text-end fw-bold text-success" id="bulk_line_total_${idx}">৳${lineTotal.toFixed(2)}</td>
            </tr>
        `;
    });

    itemsTableHtml += `
                </tbody>
            </table>
        </div>
    `;

    const modalHtml = `
        <div class="text-start" style="font-size: 13px;">
            <!-- Hero Banner -->
            <div class="modal-product-hero mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge rounded-pill px-2.5 py-1" style="background-color: #e0e7ff; color: #4338ca; font-weight: 700; font-size: 11px;">
                        <i class="fas fa-layer-group me-1"></i> Multi-Product Bulk Fulfillment
                    </span>
                    <span class="badge rounded-pill px-2.5 py-1" style="background-color: #ecfdf5; color: #047857; font-weight: 700; font-size: 11px;">
                        <i class="fas fa-check-double me-1"></i> ${bulkItems.length} Products Selected
                    </span>
                </div>
                <div class="text-muted small" style="font-size: 11.5px;">
                    Select whether you want to sync these products directly into your store catalog for retail listing, or place a bulk wholesale purchase order with physical delivery.
                </div>
            </div>

            <!-- Action Mode Selection Cards -->
            <div class="mb-3">
                <label class="form-label small text-uppercase text-muted fw-bold mb-2" style="font-size: 10.5px; letter-spacing: 0.5px;">Choose Fulfillment Action:</label>

                <!-- Option 1: Instant Catalog Sync -->
                <div class="action-mode-box active-copy" id="box-opt-bulk-copy" style="margin-bottom: 12px;" onclick="selectBulkActionOption('copy')">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="action-icon-circle" style="background: #e0e7ff; color: #4338ca;">
                            <i class="fas fa-clone"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-0.5">
                                <span class="fw-bold text-slate-800" style="font-size: 13px; color: #0f172a;">
                                    Instant Catalog Sync (${bulkItems.length} Products)
                                </span>
                                <span class="badge rounded-pill px-2 py-0.5" style="background-color: #e0e7ff; color: #3730a3; font-size: 9.5px; font-weight: 700;">
                                    Free Sync
                                </span>
                            </div>
                            <div class="text-muted" style="font-size: 11.5px; line-height: 1.35;">
                                Add all ${bulkItems.length} selected products to your catalog immediately with 0 stock. Sell on-demand.
                            </div>
                        </div>
                        <input class="form-check-input mt-1" type="radio" name="swal_bulk_action" id="opt_bulk_copy" value="copy" checked onchange="selectBulkActionOption('copy')" style="cursor: pointer;">
                    </div>
                </div>

                <!-- Option 2: Purchase Wholesale Stock -->
                <div class="action-mode-box" id="box-opt-bulk-purchase" onclick="selectBulkActionOption('purchase')">
                    <div class="d-flex align-items-start gap-2.5">
                        <div class="action-icon-circle" style="background: #dcfce7; color: #16a34a;">
                            <i class="fas fa-truck-ramp-box"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-0.5">
                                <span class="fw-bold text-slate-800" style="font-size: 13px; color: #0f172a;">
                                    Purchase Wholesale Stock (Super Admin Payment)
                                </span>
                                <span class="badge rounded-pill px-2 py-0.5" style="background-color: #dcfce7; color: #15803d; font-size: 9.5px; font-weight: 700;">
                                    Physical Delivery
                                </span>
                            </div>
                            <div class="text-muted" style="font-size: 11.5px; line-height: 1.35;">
                                Purchase physical stock. Pay Super Admin; suppliers will ship goods to your delivery address.
                            </div>
                        </div>
                        <input class="form-check-input mt-1" type="radio" name="swal_bulk_action" id="opt_bulk_purchase" value="purchase" onchange="selectBulkActionOption('purchase')" style="cursor: pointer;">
                    </div>

                    <!-- Bulk Purchase Form (Hidden initially) -->
                    <div id="bulk-purchase-details-section" class="mt-3 pt-3 border-top" style="display: none; border-top-color: #bbf7d0 !important;">
                        <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                            <i class="fas fa-cubes-stacked text-primary me-1"></i> Order Quantities & Items:
                        </label>
                        ${itemsTableHtml}

                        <!-- Grand Total Bar -->
                        <div class="d-flex justify-content-between align-items-center p-2.5 rounded-3 mb-3" style="background-color: #ecfdf5; border: 1px solid #a7f3d0;">
                            <div>
                                <span class="fw-bold text-slate-800 d-block" style="font-size: 12px;">Grand Total Payable:</span>
                                <small class="text-muted" style="font-size: 10.5px;">Includes platform wholesale commission</small>
                            </div>
                            <div class="fw-bold text-success" id="swal_bulk_grand_total" style="font-size: 17px; color: #059669;">
                                ৳${initialGrandTotal.toFixed(2)}
                            </div>
                        </div>

                        <!-- Delivery Destination Address -->
                        <div class="mb-3">
                            <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                <i class="fas fa-location-dot text-danger me-1"></i> Delivery Address (Where suppliers will ship) <span class="text-danger">*</span>
                            </label>
                            <textarea id="swal_bulk_shipping_address" class="form-control form-control-clean" rows="2" placeholder="Complete address: Street, Area, City, District"></textarea>
                        </div>

                        <!-- Contact Mobile -->
                        <div class="mb-3">
                            <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                <i class="fas fa-phone text-secondary me-1"></i> Contact Mobile Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="swal_bulk_contact_phone" class="form-control form-control-clean" placeholder="017xxxxxxxx" value="{{ auth()->user()?->phone ?? '' }}">
                        </div>

                        <!-- Super Admin Payment Gateway -->
                        <div class="mb-3">
                            <label class="small text-slate-700 fw-semibold mb-2 d-block" style="font-size: 11.5px;">
                                <i class="fas fa-wallet text-warning me-1"></i> Select Super Admin Payment Gateway <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex flex-wrap gap-2 mb-2.5">
                                @if($superAdminBkashEnabled ?? true)
                                <div class="gateway-pill active" data-gw="bkash" onclick="changeBulkPayGateway('bkash', this)">
                                    <input type="radio" name="swal_bulk_pay_gateway" value="bkash" class="d-none" checked>
                                    <i class="fas fa-bolt text-danger"></i> bKash
                                </div>
                                @endif
                                @if($superAdminNagadEnabled ?? true)
                                <div class="gateway-pill {{ !($superAdminBkashEnabled ?? true) ? 'active' : '' }}" data-gw="nagad" onclick="changeBulkPayGateway('nagad', this)">
                                    <input type="radio" name="swal_bulk_pay_gateway" value="nagad" class="d-none" {{ !($superAdminBkashEnabled ?? true) ? 'checked' : '' }}>
                                    <i class="fas fa-fire text-warning"></i> Nagad
                                </div>
                                @endif
                                @if($superAdminRocketEnabled ?? true)
                                <div class="gateway-pill {{ !($superAdminBkashEnabled ?? true) && !($superAdminNagadEnabled ?? true) ? 'active' : '' }}" data-gw="rocket" onclick="changeBulkPayGateway('rocket', this)">
                                    <input type="radio" name="swal_bulk_pay_gateway" value="rocket" class="d-none" {{ !($superAdminBkashEnabled ?? true) && !($superAdminNagadEnabled ?? true) ? 'checked' : '' }}>
                                    <i class="fas fa-paper-plane" style="color: #8c3494;"></i> Rocket
                                </div>
                                @endif
                                @if($superAdminBankEnabled ?? true)
                                <div class="gateway-pill" data-gw="bank" onclick="changeBulkPayGateway('bank', this)">
                                    <input type="radio" name="swal_bulk_pay_gateway" value="bank" class="d-none">
                                    <i class="fas fa-building-columns text-success"></i> Bank Transfer
                                </div>
                                @endif
                            </div>

                            <!-- Payment Instruction Card -->
                            <div class="p-2.5 rounded-3 mb-2 small border" id="swal_bulk_gateway_instruction" style="background-color: #f8fafc; border-color: #e2e8f0; font-size: 11.5px; line-height: 1.45;">
                                ${GATEWAYS.bkash.instruction}
                            </div>
                        </div>

                        <!-- Sender Mobile & TrxID -->
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                    <i class="fas fa-mobile-screen me-1"></i> Sender Mobile / AC
                                </label>
                                <input type="text" id="swal_bulk_sender_phone" class="form-control form-control-clean" placeholder="018xxxxxxxx">
                            </div>
                            <div class="col-6">
                                <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                    <i class="fas fa-key text-primary me-1"></i> Transaction ID (TrxID) <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="swal_bulk_trx_id" class="form-control form-control-clean fw-bold" placeholder="e.g. 9J87K12A">
                            </div>
                        </div>

                        <!-- Payment Screenshot / Receipt Proof -->
                        <div class="mb-2">
                            <label class="small text-slate-700 fw-semibold mb-1 d-block" style="font-size: 11.5px;">
                                <i class="fas fa-image text-info me-1"></i> Payment Proof Screenshot / Slip <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="file" id="swal_bulk_payment_screenshot" class="form-control form-control-clean" accept="image/*" style="padding: 6px 10px; font-size: 12px;" onchange="previewBulkPaymentScreenshot(this)">
                            <div id="swal_bulk_screenshot_preview_box" class="mt-2 text-center" style="display: none;">
                                <img id="swal_bulk_screenshot_preview_img" src="" alt="Receipt Preview" class="rounded-3 border shadow-sm" style="max-height: 100px; max-width: 100%; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    Swal.fire({
        title: `<i class="fas fa-cart-flatbed text-primary me-2"></i>Bulk Wholesale (${bulkItems.length} Products)`,
        html: modalHtml,
        showCancelButton: true,
        confirmButtonText: `<i class="fas fa-clone me-1.5"></i> Sync All ${bulkItems.length} Products to Catalog`,
        cancelButtonText: 'Cancel',
        focusConfirm: false,
        width: '720px',
        customClass: {
            popup: 'modern-wholesale-popup'
        },
        didOpen: () => {
            window.selectBulkActionOption = function(mode) {
                const isPurchase = (mode === 'purchase');
                document.getElementById('opt_bulk_purchase').checked = isPurchase;
                document.getElementById('opt_bulk_copy').checked = !isPurchase;

                const boxPurchase = document.getElementById('box-opt-bulk-purchase');
                const boxCopy = document.getElementById('box-opt-bulk-copy');
                const detailsSection = document.getElementById('bulk-purchase-details-section');
                const confirmBtn = Swal.getConfirmButton();

                if (isPurchase) {
                    boxPurchase.classList.add('active-purchase');
                    boxCopy.classList.remove('active-copy');
                    if (detailsSection) detailsSection.style.display = 'block';
                    if (confirmBtn) confirmBtn.innerHTML = `<i class="fas fa-check-circle me-1.5"></i> Submit Wholesale Order (৳${initialGrandTotal.toFixed(2)})`;
                } else {
                    boxPurchase.classList.remove('active-purchase');
                    boxCopy.classList.add('active-copy');
                    if (detailsSection) detailsSection.style.display = 'none';
                    if (confirmBtn) confirmBtn.innerHTML = `<i class="fas fa-clone me-1.5"></i> Sync All ${bulkItems.length} Products to Catalog`;
                }
            };

            window.updateBulkItemLineTotal = function(idx) {
                const qtyInput = document.getElementById(`bulk_qty_${idx}`);
                let qty = parseInt(qtyInput.value) || 1;
                if (qty < 1) { qty = 1; qtyInput.value = 1; }
                bulkItems[idx].quantity = qty;

                const lineTotal = bulkItems[idx].unit_price * qty;
                const lineTotalEl = document.getElementById(`bulk_line_total_${idx}`);
                if (lineTotalEl) lineTotalEl.textContent = `৳${lineTotal.toFixed(2)}`;

                let newGrandTotal = 0;
                bulkItems.forEach(it => {
                    newGrandTotal += (it.unit_price * it.quantity);
                });
                initialGrandTotal = newGrandTotal;

                const grandTotalEl = document.getElementById('swal_bulk_grand_total');
                if (grandTotalEl) grandTotalEl.textContent = `৳${newGrandTotal.toFixed(2)}`;

                const confirmBtn = Swal.getConfirmButton();
                if (confirmBtn && document.getElementById('opt_bulk_purchase').checked) {
                    confirmBtn.innerHTML = `<i class="fas fa-check-circle me-1.5"></i> Submit Wholesale Order (৳${newGrandTotal.toFixed(2)})`;
                }
            };

            window.changeBulkPayGateway = function(gatewayKey, pillEl) {
                document.querySelectorAll('input[name="swal_bulk_pay_gateway"]').forEach(i => i.checked = false);
                document.querySelectorAll('.gateway-pill').forEach(b => b.classList.remove('active'));
                
                const radio = pillEl.querySelector('input');
                if (radio) radio.checked = true;
                pillEl.classList.add('active');

                const gInfo = GATEWAYS[gatewayKey];
                const instBox = document.getElementById('swal_bulk_gateway_instruction');
                if (instBox && gInfo) {
                    instBox.innerHTML = gInfo.instruction;
                }
            };

            window.previewBulkPaymentScreenshot = function(input) {
                const previewBox = document.getElementById('swal_bulk_screenshot_preview_box');
                const previewImg = document.getElementById('swal_bulk_screenshot_preview_img');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewBox.style.display = 'block';
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                    previewBox.style.display = 'none';
                }
            };
        },
        preConfirm: () => {
            const isPurchase = document.getElementById('opt_bulk_purchase').checked;
            if (isPurchase) {
                const shippingAddress = document.getElementById('swal_bulk_shipping_address').value.trim();
                const contactPhone = document.getElementById('swal_bulk_contact_phone').value.trim();
                const trxId = document.getElementById('swal_bulk_trx_id').value.trim();
                const senderPhone = document.getElementById('swal_bulk_sender_phone').value.trim();
                const gatewayChecked = document.querySelector('input[name="swal_bulk_pay_gateway"]:checked');
                const gateway = gatewayChecked ? gatewayChecked.value : 'bkash';
                const screenshotFile = document.getElementById('swal_bulk_payment_screenshot').files[0];

                if (!shippingAddress) {
                    Swal.showValidationMessage('Please provide your complete shipping/delivery address.');
                    return false;
                }
                if (!contactPhone) {
                    Swal.showValidationMessage('Please provide a contact phone number for the delivery courier.');
                    return false;
                }
                if (!trxId) {
                    Swal.showValidationMessage('Please enter your payment Transaction ID (TrxID).');
                    return false;
                }

                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('shipping_address', shippingAddress);
                formData.append('contact_phone', contactPhone);
                formData.append('gateway', gateway);
                formData.append('trx_id', trxId);
                formData.append('sender_phone', senderPhone);
                formData.append('items', JSON.stringify(bulkItems));
                if (screenshotFile) {
                    formData.append('payment_screenshot', screenshotFile);
                }

                return {
                    mode: 'purchase',
                    formData: formData,
                    itemCount: bulkItems.length
                };
            } else {
                return {
                    mode: 'copy',
                    items: bulkItems.map(it => ({ subdomain: it.subdomain, product_id: it.product_id }))
                };
            }
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            if (result.value.mode === 'purchase') {
                Swal.fire({
                    title: 'Processing Wholesale Order...',
                    html: `Submitting purchase orders for ${result.value.itemCount} items...`,
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch("{{ route('admin.wholesale-orders.bulk-checkout') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: result.value.formData
                })
                .then(res => res.json())
                .then(resData => {
                    if (resData.success) {
                        Swal.fire({
                            title: 'Wholesale Order Placed!',
                            text: resData.message || 'Orders submitted successfully!',
                            icon: 'success',
                            confirmButtonText: '<i class="fas fa-receipt me-1.5"></i> View Wholesale Orders',
                            confirmButtonColor: '#4f46e5',
                            showCancelButton: true,
                            cancelButtonText: 'Stay on Page'
                        }).then((choice) => {
                            if (choice.isConfirmed) {
                                window.location.href = "{{ route('admin.wholesale-orders.index') }}";
                            } else {
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire('Order Submission Failed', resData.message || 'Failed to place bulk wholesale order.', 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Network error occurred while submitting order.', 'error');
                });
            } else {
                // Free Instant Catalog Sync
                Swal.fire({
                    title: 'Copying Products...',
                    html: 'Please wait while the products, images, and variations are being imported.',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });

                fetch("{{ route('admin.global-products.bulk-copy') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ items: result.value.items })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Completed!',
                            text: `${data.success_count} products were successfully copied into your catalog!`,
                            icon: 'success',
                            confirmButtonColor: '#4f46e5'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Failed',
                            text: 'Failed to copy products. Please try again.',
                            icon: 'error',
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        title: 'Error',
                        text: 'An unexpected network error occurred during bulk copy.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                });
            }
        }
    });
}
</script>
@endsection
