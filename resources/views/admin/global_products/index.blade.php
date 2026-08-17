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
                                    <div class="d-flex flex-column">
                                        <span class="text-success font-bold d-inline-flex align-items-center gap-1" style="font-weight: 700;">
                                            @if($varWholesaleMin === $varWholesaleMax)
                                                ৳{{ number_format($varWholesaleMin, 2) }}
                                            @else
                                                ৳{{ number_format($varWholesaleMin, 2) }} – ৳{{ number_format($varWholesaleMax, 2) }}
                                            @endif
                                            @if(!empty($product['commission_percent']) && $product['commission_percent'] > 0)
                                                <span class="badge rounded-pill px-1.5 py-0.5" style="background-color: #ecfdf5; color: #047857; font-size: 9px; font-weight: 600;">
                                                    +{{ $product['commission_percent'] }}%
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                @elseif(!empty($product['commission_percent']) && $product['commission_percent'] > 0)
                                    <div class="d-flex flex-column">
                                        <span class="text-success font-bold d-inline-flex align-items-center gap-1" style="font-weight: 700;">
                                            ৳{{ number_format($product['final_wholesale_price'], 2) }}
                                            <span class="badge rounded-pill px-1.5 py-0.5" style="background-color: #ecfdf5; color: #047857; font-size: 9px; font-weight: 600;">
                                                +{{ $product['commission_percent'] }}%
                                            </span>
                                        </span>
                                        <span class="text-muted small" style="font-size: 10px;">
                                            Base: ৳{{ number_format($product['base_price'], 2) }} (+৳{{ number_format($product['commission_amount'], 2) }})
                                        </span>
                                    </div>
                                @else
                                    <span class="text-success font-bold" style="font-weight: 700;">
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
                                        class="btn btn-copy-action btn-sm" 
                                        id="btn-copy-{{ $rowUniqueKey }}"
                                        onclick="copySingleProduct('{{ $product['tenant_subdomain'] }}', '{{ $product['id'] }}', '{{ addslashes($product['title']) }}', '{{ $rowUniqueKey }}')">
                                    <i class="fas fa-cloud-arrow-down me-1"></i> {{ $product['is_already_copied'] ? 'Copy Again' : 'Add to Catalog' }}
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
                                                                        @if(!empty($product['commission_percent']) && $product['commission_percent'] > 0)
                                                                            <div class="d-flex flex-column">
                                                                                <span class="text-success font-bold small d-inline-flex align-items-center gap-1" style="font-weight: 700;">
                                                                                    ৳{{ number_format($variant['final_wholesale_price'], 2) }}
                                                                                    <span class="badge rounded-pill px-1 py-0.2" style="background-color: #ecfdf5; color: #047857; font-size: 8.5px; font-weight: 600;">
                                                                                        +{{ $product['commission_percent'] }}%
                                                                                    </span>
                                                                                </span>
                                                                                <span class="text-muted" style="font-size: 9px;">
                                                                                    Base: ৳{{ number_format($variant['base_price'], 2) }}
                                                                                </span>
                                                                            </div>
                                                                        @else
                                                                            <span class="text-success font-bold small" style="font-weight: 700;">
                                                                                ৳{{ number_format($variant['final_wholesale_price'] ?: 0, 2) }}
                                                                            </span>
                                                                        @endif
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

// Single Copy Action
function copySingleProduct(subdomain, productId, title, rowKey) {
    const btn = document.getElementById('btn-copy-' + rowKey);
    const originalContent = btn.innerHTML;

    Swal.fire({
        title: 'Add to Store Catalog?',
        html: `Do you want to copy <strong>"${title}"</strong> into your store's product inventory?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-cloud-arrow-down me-1"></i> Yes, Copy to Catalog',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Copying...';

            fetch("{{ route('admin.global-products.copy') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    subdomain: subdomain,
                    product_id: productId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#4f46e5'
                    });

                    // Update UI row status
                    const statusCell = document.getElementById('status-cell-' + rowKey);
                    if (statusCell) {
                        statusCell.innerHTML = '<span class="badge-in-store"><i class="fas fa-check-circle"></i> In Store</span>';
                    }
                    btn.innerHTML = '<i class="fas fa-check me-1"></i> Copied!';
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-cloud-arrow-down me-1"></i> Copy Again';
                    }, 2000);
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Could not copy product.',
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
            })
            .catch(err => {
                Swal.fire({
                    title: 'Error',
                    text: 'An unexpected network error occurred.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
                btn.disabled = false;
                btn.innerHTML = originalContent;
            });
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
