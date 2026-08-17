@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-slate-800 font-bold" style="font-weight: 700; color: #1e293b;">SaaS Wholesale Products</h1>
            <p class="text-muted mb-0 small">Overview of all wholeselling products from every tenant registered on the platform.</p>
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

    <!-- Filters & Tabs Panel -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <!-- Navigation Tabs -->
                <ul class="nav nav-pills bg-light p-1 rounded-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('admin.saas-tenants.wholesale-products', array_merge(request()->query(), ['tab' => 'wholeseller', 'page' => 1])) }}" 
                           class="nav-link py-2 px-3 rounded-3 font-semibold {{ $currentTab === 'wholeseller' ? 'active shadow-sm' : 'text-slate-600' }}"
                           style="{{ $currentTab === 'wholeseller' ? 'background-color: #4f46e5; color: #fff;' : 'font-weight: 500;' }}">
                            <i class="fas fa-store me-1.5"></i> Wholesellers Products
                            <span class="badge ms-1.5 {{ $currentTab === 'wholeseller' ? 'bg-white text-primary' : 'bg-secondary text-white' }}" style="font-size: 11px;">
                                {{ $totalWholesellerCount }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="{{ route('admin.saas-tenants.wholesale-products', array_merge(request()->query(), ['tab' => 'admin', 'page' => 1])) }}" 
                           class="nav-link py-2 px-3 rounded-3 font-semibold {{ $currentTab === 'admin' ? 'active shadow-sm' : 'text-slate-600' }}"
                           style="{{ $currentTab === 'admin' ? 'background-color: #4f46e5; color: #fff;' : 'font-weight: 500;' }}">
                            <i class="fas fa-user-shield me-1.5"></i> Admin Products
                            <span class="badge ms-1.5 {{ $currentTab === 'admin' ? 'bg-white text-primary' : 'bg-secondary text-white' }}" style="font-size: 11px;">
                                {{ $totalAdminCount }}
                            </span>
                        </a>
                    </li>
                </ul>

                <!-- Filter by Tenant -->
                <form action="{{ route('admin.saas-tenants.wholesale-products') }}" method="GET" class="d-flex align-items-center gap-2 m-0">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label small text-muted font-semibold mb-0 text-nowrap" style="font-weight: 600;">Tenant:</label>
                        <select name="tenant_id" class="form-select form-select-sm rounded-3" style="min-width: 220px;" onchange="this.form.submit()">
                            <option value="">All Tenants (Combined View)</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ $selectedTenantId == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->name }} ({{ $tenant->subdomain }})
                                </option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.saas-tenants.wholesale-products', ['tab' => $currentTab]) }}" class="btn btn-sm btn-light rounded-3 text-nowrap" style="background-color: #f1f5f9; color: #475569; border: none;">
                            <i class="fas fa-redo me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 font-semibold" style="color: #334155; font-weight: 600;">
                    @if($currentTab === 'wholeseller')
                        <i class="fas fa-boxes text-primary me-2"></i>Wholeseller Registered Products
                    @else
                        <i class="fas fa-cube text-primary me-2"></i>Admin Created Products
                    @endif
                </h5>
                <span class="text-muted small">
                    {{ $currentTab === 'wholeseller' ? 'Products supplied by registered wholeseller vendors across tenants' : 'Products created and managed directly by tenant store administrators' }}
                    @if($globalCommission > 0)
                        &bull; <span class="text-primary font-medium" style="font-weight: 500;">Default Platform Commission: <strong>{{ $globalCommission }}%</strong></span>
                    @endif
                </span>
            </div>
            <span class="badge bg-indigo-light text-primary px-3 py-2 rounded-3" style="background-color: rgba(79, 70, 229, 0.08); color: #4f46e5; font-weight: 600;">
                Total in view: {{ $paginatedProducts->total() }}
            </span>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted" style="background-color: #f8fafc;">
                    <tr>
                        <th class="ps-4 py-3" style="width: 70px;">Image</th>
                        <th class="py-3" style="min-width: 180px; max-width: 280px;">Product Name</th>
                        <th class="py-3" style="white-space: nowrap;">Tenant Subdomain</th>
                        @if($currentTab !== 'admin')
                            <th class="py-3" style="white-space: nowrap;">Wholeseller / Vendor</th>
                        @endif
                        <th class="py-3" style="white-space: nowrap;">Retail Price</th>
                        <th class="py-3" style="white-space: nowrap;">
                            {{ $currentTab === 'admin' ? 'Global Price' : 'Wholesale Price' }}
                        </th>
                        <th class="py-3" style="white-space: nowrap;">Stock</th>
                        <th class="py-3" style="white-space: nowrap;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginatedProducts as $product)
                        @php
                            $rowUniqueKey = $product['tenant_subdomain'] . '_' . $product['id'];
                            $hasVariants = !empty($product['has_variants']) && count($product['variants']) > 0;
                            $colspan = ($currentTab === 'admin') ? 7 : 8;
                        @endphp
                        <tr class="{{ $hasVariants ? 'has-variants-row' : '' }}" 
                            style="{{ $hasVariants ? 'cursor: pointer;' : '' }}"
                            @if($hasVariants)
                                onclick="toggleVariantRow('{{ $rowUniqueKey }}')"
                            @endif>
                            <td class="ps-4" style="width: 70px;">
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
                            <td style="max-width: 280px;">
                                <div class="d-flex align-items-center gap-1.5">
                                    <h6 class="mb-0 text-slate-800 font-semibold text-truncate" style="font-weight: 600; color: #1e293b; max-width: 230px;" title="{{ $product['title'] }}">{{ $product['title'] }}</h6>
                                    @if($hasVariants)
                                        <i id="chevron-{{ $rowUniqueKey }}" class="fas fa-chevron-down text-primary fs-7 transition-all" style="font-size: 11px; transition: transform 0.2s ease;"></i>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-0.5">
                                    <span class="text-muted small" style="font-size: 11px;">ID: #{{ $product['id'] }}</span>
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
                            @if($currentTab !== 'admin')
                                <td style="white-space: nowrap;">
                                    <div>
                                        <span class="font-medium text-slate-700 d-block" style="font-weight: 500; color: #334155;">{{ $product['vendor_name'] }}</span>
                                        <span class="text-muted small fs-7">{{ $product['vendor_email'] }}</span>
                                    </div>
                                </td>
                            @endif
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
                                    {{-- Variable product: show price range from variants --}}
                                    @if($varRetailMin === $varRetailMax)
                                        <span class="text-slate-800" style="font-weight: 600;">৳{{ number_format($varRetailMin, 2) }}</span>
                                    @else
                                        <span class="text-slate-800" style="font-weight: 600;">৳{{ number_format($varRetailMin, 2) }} – ৳{{ number_format($varRetailMax, 2) }}</span>
                                    @endif
                                    <div class="text-muted" style="font-size: 10px;">{{ count($product['variants']) }} variants</div>
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
                                    {{-- Variable product: show wholesale price range from variants --}}
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
                                        <span class="text-muted small" style="font-size: 10px;">{{ count($product['variants']) }} variant prices</span>
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
                                    <span class="text-danger font-semibold" style="font-weight: 600;">Out of Stock</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                @if($product['status'] == 1 || $product['status'] == 'active' || $product['status'] === true)
                                    <span class="badge px-2.5 py-1.5 rounded-pill" style="background-color: #ecfdf5; color: #047857; font-weight: 600; font-size: 11px;">Published</span>
                                @else
                                    <span class="badge px-2.5 py-1.5 rounded-pill" style="background-color: #f1f5f9; color: #64748b; font-weight: 600; font-size: 11px;">Draft</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Collapsible Child Variant Details --}}
                        @if($hasVariants)
                            <tr class="p-0 border-0">
                                <td colspan="{{ $colspan }}" class="p-0 border-0">
                                    <div class="collapse" id="variants-{{ $rowUniqueKey }}">
                                        <div class="p-3 bg-light border-bottom" style="background-color: #f8fafc !important;">
                                            <div class="card border rounded-3 shadow-none overflow-hidden bg-white">
                                                <div class="card-header bg-light py-2 px-3 border-bottom d-flex align-items-center justify-content-between" style="background-color: #f1f5f9;">
                                                    <span class="small font-semibold text-slate-700" style="font-weight: 600;">
                                                        <i class="fas fa-sitemap text-primary me-1.5"></i>Available Variants ({{ count($product['variants']) }})
                                                    </span>
                                                    <span class="text-muted small" style="font-size: 11px;">Product ID #{{ $product['id'] }}</span>
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
                                                                <th class="py-2">Stock</th>
                                                                <th class="pe-3 py-2 text-end">Status</th>
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
                                                                                 style="width: 34px; height: 34px; object-fit: cover;" 
                                                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                                            <div class="rounded border bg-light align-items-center justify-content-center" style="width: 34px; height: 34px; display: none;">
                                                                                <i class="fas fa-image text-muted" style="font-size: 0.8rem;"></i>
                                                                            </div>
                                                                        @else
                                                                            <div class="rounded border bg-light d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                                                                <i class="fas fa-image text-muted" style="font-size: 0.8rem;"></i>
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
                                                                            // Sell price = offer if exists, else regular
                                                                            $varDisplaySell = $varOffer > 0 ? $varOffer : ($varSell > 0 ? $varSell : $varReg);
                                                                            // Old price = regular_price (show struck-through when different from sell)
                                                                            $varDisplayOld = $varReg > 0 ? $varReg : 0;
                                                                            $varHasDisc = ($varDisplayOld > 0 && $varDisplaySell > 0 && $varDisplayOld > $varDisplaySell);
                                                                        @endphp
                                                                        <span class="text-slate-800 small font-semibold" style="font-weight: 600;">৳{{ number_format($varDisplaySell, 2) }}</span>
                                                                        @if($varDisplayOld > 0)
                                                                            <del class="text-muted d-block" style="font-size: 9px; {{ !$varHasDisc ? 'opacity: 0.7;' : '' }}">৳{{ number_format($varDisplayOld, 2) }}</del>
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
                                                                    <td class="py-2">
                                                                        @if($variant['stock_quantity'] > 0)
                                                                            <span class="text-slate-700 small" style="font-weight: 500;">{{ $variant['stock_quantity'] }} pcs</span>
                                                                        @else
                                                                            <span class="text-danger small font-semibold" style="font-weight: 600;">Out of Stock</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="pe-3 py-2 text-end">
                                                                        @if($variant['is_active'])
                                                                            <span class="badge px-2 py-1 rounded-pill" style="background-color: #ecfdf5; color: #047857; font-size: 10px;">Active</span>
                                                                        @else
                                                                            <span class="badge px-2 py-1 rounded-pill" style="background-color: #f1f5f9; color: #64748b; font-size: 10px;">Inactive</span>
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
                            <td colspan="{{ $currentTab === 'admin' ? 7 : 8 }}" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-boxes text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <h5 class="text-slate-600 mb-1" style="font-weight: 600;">
                                        {{ $currentTab === 'wholeseller' ? 'No Wholeseller Products Found' : 'No Admin Products Found' }}
                                    </h5>
                                    <p class="text-muted small">
                                        {{ $currentTab === 'wholeseller' 
                                            ? 'No products found from registered wholesellers across the selected tenants.' 
                                            : 'No products found created directly by administrators across the selected tenants.' }}
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

<script>
function toggleVariantRow(uniqueKey) {
    const collapseElem = document.getElementById('variants-' + uniqueKey);
    const chevron = document.getElementById('chevron-' + uniqueKey);

    if (!collapseElem) return;

    // Use Bootstrap Collapse API to toggle cleanly
    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseElem, { toggle: false });

    if (collapseElem.classList.contains('show')) {
        bsCollapse.hide();
        if (chevron) chevron.style.transform = 'rotate(0deg)';
    } else {
        bsCollapse.show();
        if (chevron) chevron.style.transform = 'rotate(180deg)';
    }
}
</script>
@endsection
