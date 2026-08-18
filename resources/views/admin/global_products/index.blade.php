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
                        <select name="tenant_id" class="form-select form-select-sm rounded-3" style="min-width: 200px;" onchange="this.form.submit()">
                            <option value="">All SaaS Tenants</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ $selectedTenantId == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }} ({{ $tenant->subdomain }})
                                </option>
                            @endforeach
                        </select>
                        
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
            <button type="button" id="btn-bulk-copy" class="btn btn-success btn-sm rounded-3 px-3 py-2 shadow-sm font-semibold d-inline-flex align-items-center gap-2" style="font-weight: 600;" disabled onclick="executeBulkCopy()">
                <i class="fas fa-cloud-arrow-down"></i> Copy Selected to Store (<span id="selected-count">0</span>)
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
                        <th style="white-space: nowrap;">Source Tenant</th>
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
                            <td style="white-space: nowrap;">
                                <span class="badge rounded-pill px-2.5 py-1.5" style="background-color: #e0e7ff; color: #3730a3; font-weight: 600; font-size: 11px;">
                                    <i class="fas fa-globe me-1"></i> {{ $product['tenant_subdomain'] }}
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                <span class="badge bg-light text-dark border px-2 py-1 rounded-2" style="font-size: 11px; font-weight: 500;">
                                    {{ $product['category_name'] }}
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                @php
                                    $sellPrice = floatval($product['price'] ?? 0);
                                    $oldRegularPrice = floatval($product['old_price'] ?? 0);
                                    $hasDiscount = ($oldRegularPrice > 0 && $sellPrice > 0 && $oldRegularPrice > $sellPrice);
                                    $varRetailMin = floatval($product['variant_retail_min'] ?? 0);
                                    $varRetailMax = floatval($product['variant_retail_max'] ?? 0);
                                    $isVariableWithRetail = ($product['has_variants'] && $varRetailMin > 0 && $sellPrice <= 0 && $oldRegularPrice <= 0);
                                @endphp
                                @if($isVariableWithRetail)
                                    @if($varRetailMin === $varRetailMax)
                                        <span class="text-slate-800" style="font-weight: 600;">৳{{ number_format($varRetailMin, 2) }}</span>
                                    @else
                                        <span class="text-slate-800" style="font-weight: 600;">৳{{ number_format($varRetailMin, 2) }} – ৳{{ number_format($varRetailMax, 2) }}</span>
                                    @endif
                                @elseif($hasDiscount)
                                    <span class="text-slate-800" style="font-weight: 600;">৳{{ number_format($sellPrice, 2) }}</span>
                                    <del class="text-muted small d-block" style="font-size: 10px;">৳{{ number_format($oldRegularPrice, 2) }}</del>
                                @else
                                    <span class="text-slate-800" style="font-weight: 600;">৳{{ number_format($sellPrice > 0 ? $sellPrice : $oldRegularPrice, 2) }}</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                @php
                                    $varWholesaleMin = floatval($product['variant_wholesale_min'] ?? 0);
                                    $varWholesaleMax = floatval($product['variant_wholesale_max'] ?? 0);
                                    $isVariableWithWholesale = ($product['has_variants'] && $varWholesaleMin > 0 && floatval($product['final_wholesale_price'] ?? 0) <= 0);
                                @endphp
                                @if($isVariableWithWholesale)
                                    <span class="text-success font-bold" style="font-weight: 700;">
                                        @if($varWholesaleMin === $varWholesaleMax)
                                            ৳{{ number_format($varWholesaleMin, 2) }}
                                        @else
                                            ৳{{ number_format($varWholesaleMin, 2) }} – ৳{{ number_format($varWholesaleMax, 2) }}
                                        @endif
                                    </span>
                                @else
                                    <span class="text-success font-bold" style="font-weight: 700; font-size: 14px;">
                                        ৳{{ number_format($product['final_wholesale_price'] ?: 0, 2) }}
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
                                @if($product['is_already_copied'])
                                    <span class="badge-in-store">
                                        <i class="fas fa-check-circle"></i> In Store
                                    </span>
                                @else
                                    <span class="badge-available">
                                        <i class="fas fa-plus"></i> Ready to Add
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end" style="white-space: nowrap;" id="action-cell-{{ $rowUniqueKey }}">
                                <button type="button" 
                                        class="btn btn-sm btn-primary rounded-3 px-3 py-1.5 font-semibold d-inline-flex align-items-center gap-1.5 shadow-sm" 
                                        id="btn-action-{{ $rowUniqueKey }}"
                                        style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); border: none; font-size: 0.825rem;"
                                        onclick="openPurchaseModal('{{ $product['tenant_subdomain'] }}', '{{ $product['id'] }}', '{{ addslashes($product['title']) }}', '{{ $product['final_wholesale_price'] }}', '{{ $product['quantity'] }}', '{{ $rowUniqueKey }}')">
                                    <i class="fas fa-cart-shopping"></i> Purchase
                                </button>
                            </td>
                        </tr>

                        {{-- Collapsible Child Variant Details --}}
                        @if($hasVariants)
                            <tr class="p-0 border-0">
                                <td colspan="10" class="p-0 border-0">
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
                                                                    <td class="py-2">
                                                                        <span class="text-success font-bold small" style="font-weight: 700;">
                                                                            ৳{{ number_format($variant['final_wholesale_price'] ?: 0, 2) }}
                                                                        </span>
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
    const checkboxes = document.querySelectorAll('.product-check');
    checkboxes.forEach(cb => {
        cb.checked = masterCheckbox.checked;
    });
    updateBulkButtonState();
}

function updateBulkButtonState() {
    const checked = document.querySelectorAll('.product-check:checked');
    const bulkBtn = document.getElementById('btn-bulk-copy');
    const countSpan = document.getElementById('selected-count');

    countSpan.textContent = checked.length;
    bulkBtn.disabled = (checked.length === 0);
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
function openPurchaseModal(subdomain, productId, title, unitPrice, availableStock, rowKey) {
    const btn = document.getElementById('btn-action-' + rowKey);
    const originalContent = btn ? btn.innerHTML : '';
    const numPrice = parseFloat(unitPrice) || 0;
    const numStock = parseInt(availableStock) || 0;

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

            <!-- Action Mode Selection Cards -->
            <div class="mb-3">
                <label class="form-label small text-uppercase text-muted fw-bold mb-2" style="font-size: 10.5px; letter-spacing: 0.5px;">Choose Fulfillment Action:</label>

                <!-- Option 1: Instant Catalog Sync (Listing Only) - FIRST -->
                <div class="action-mode-box active-copy" id="box-opt-copy" style="margin-bottom: 14px;" onclick="selectActionOption('copy')">
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
                                    Free Sync
                                </span>
                            </div>
                            <div class="text-muted" style="font-size: 11.5px; line-height: 1.35;">
                                Add this product to your store catalog immediately without buying inventory stock.
                            </div>
                        </div>
                        <input class="form-check-input mt-1" type="radio" name="swal_global_action" id="opt_copy" value="copy" checked onchange="selectActionOption('copy')" style="cursor: pointer;">
                    </div>
                </div>

                <!-- Option 2: Purchase Wholesale Stock -->
                <div class="action-mode-box" id="box-opt-purchase" onclick="selectActionOption('purchase')">
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
                                Pay to Super Admin. On approval, supplier (@${subdomain}) ships physical stock to your store.
                            </div>
                        </div>
                        <input class="form-check-input mt-1" type="radio" name="swal_global_action" id="opt_purchase" value="purchase" onchange="selectActionOption('purchase')" style="cursor: pointer;">
                    </div>

                    <!-- Purchase Form Details (Hidden until Purchase selected) -->
                    <div id="purchase-details-section" class="mt-3 pt-3 border-top" style="display: none; border-top-color: #bbf7d0 !important;">
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
        confirmButtonText: '<i class="fas fa-clone me-1.5"></i> Sync Product to Catalog',
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

// Bulk Copy Action
function executeBulkCopy() {
    const checked = document.querySelectorAll('.product-check:checked');
    if (checked.length === 0) return;

    const items = [];
    checked.forEach(cb => {
        items.push({
            subdomain: cb.dataset.subdomain,
            product_id: parseInt(cb.dataset.id)
        });
    });

    Swal.fire({
        title: 'Bulk Copy Products?',
        html: `Are you sure you want to copy <strong>${items.length}</strong> selected global products into your store catalog?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: `<i class="fas fa-cloud-arrow-down me-1"></i> Yes, Copy All ${items.length}`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Copying Products...',
                html: 'Please wait while the products, images, and variations are being imported.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch("{{ route('admin.global-products.bulk-copy') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ items: items })
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
    });
}
</script>
@endsection
