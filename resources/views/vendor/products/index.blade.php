@extends('vendor.layouts.app')

@section('title', 'My Products')

@push('styles')
<style>
    .page-header-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
    }

    .nav-pills-modern {
        background: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
    }

    .nav-pills-modern .nav-link {
        border-radius: 9px;
        padding: 6px 14px;
        font-weight: 600;
        font-size: 0.825rem;
        color: #64748b;
        transition: all 0.2s ease;
    }

    .nav-pills-modern .nav-link.active {
        background: #ffffff;
        color: #4f46e5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .filter-card-modern {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
    }

    .filter-label {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin-bottom: 5px;
    }

    .modern-form-control, .modern-form-select {
        height: 40px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        font-size: 0.85rem;
        color: #1e293b;
        font-weight: 500;
        padding: 0.4rem 0.75rem;
        transition: all 0.2s ease-in-out;
    }

    .modern-form-control:focus, .modern-form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        outline: none;
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
        color: #ffffff;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.15);
        transition: all 0.2s ease;
        padding: 0.45rem 1rem;
        font-size: 0.8rem;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #4338ca 0%, #312e81 100%);
        color: #ffffff;
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.25);
        transform: translateY(-1px);
    }

    .btn-gradient-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15);
        transition: all 0.2s ease;
        padding: 0.45rem 1rem;
        font-size: 0.8rem;
    }

    .btn-gradient-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff;
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.25);
        transform: translateY(-1px);
    }

    .btn-gradient-blue {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: #ffffff;
        border: none;
        font-weight: 600;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
        transition: all 0.2s ease;
        padding: 0.45rem 1rem;
        font-size: 0.8rem;
    }

    .btn-gradient-blue:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: #ffffff;
        box-shadow: 0 6px 15px rgba(37, 99, 235, 0.25);
        transform: translateY(-1px);
    }

    .btn-outline-modern {
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475569;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.2s ease;
        padding: 0.45rem 1rem;
        font-size: 0.8rem;
    }

    .btn-outline-modern:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #1e293b;
    }

    .bulk-action-bar {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem;
    }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table-modern thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 10px 12px;
        border-bottom: 2px solid #e2e8f0;
    }

    .table-modern tbody tr {
        transition: all 0.15s ease-in-out;
    }

    .table-modern tbody tr:hover {
        background-color: #f8fafc !important;
    }

    .table-modern tbody td {
        padding: 10px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 0.8rem;
    }

    .copied-badge-pill {
        background: #ecfdf5;
        color: #059669;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        padding: 6px 12px;
        font-weight: 700;
        font-size: 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .stock-badge-pill {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        border-radius: 20px;
        padding: 3px 10px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .stock-dot {
        width: 7px;
        height: 7px;
        background-color: #22c55e;
        border-radius: 50%;
        display: inline-block;
    }

    .cat-tag {
        background: #f1f5f9;
        color: #475569;
        font-size: 0.725rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
    }
    .table-responsive {
        display: block !important;
        width: 100% !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }
</style>
@endpush

@section('content')
<!-- Page Header -->
<div class="page-header-card p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 p-2" style="width: 42px; height: 42px;">
            <i class="fas fa-boxes fs-5 text-primary"></i>
        </div>
        <div>
            <h4 class="mb-0 fw-bold text-dark fs-5">{{ ($source ?? 'my_products') === 'admin_products' ? 'Parent Admin Catalog' : 'My Products' }}</h4>
            <small class="text-muted">Manage your store inventory & catalog items</small>
        </div>
        
        @if($canAccessAdminProducts ?? false)
        <ul class="nav nav-pills-modern ms-2">
            <li class="nav-item">
                <a class="nav-link {{ ($source ?? 'my_products') === 'my_products' ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index', ['source' => 'my_products']) }}">
                    <i class="fas fa-boxes me-1.5"></i> My Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ ($source ?? 'my_products') === 'admin_products' ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index', ['source' => 'admin_products']) }}">
                    <i class="fas fa-store me-1.5"></i> Parent Admin Catalog
                    <span class="badge bg-success ms-1" style="font-size: 0.65rem;">Shared</span>
                </a>
            </li>
        </ul>
        @endif
    </div>

    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-outline-primary px-3 py-2 btn-image-search-trigger" onclick="event.preventDefault(); window.openImageSearchModal();" title="Search Product by Image or Camera">
            <i class="fas fa-camera me-1.5"></i> Search by Image
        </button>
        @can('vendor.products.create')
        <a href="{{ route('vendor.products.create') }}" class="btn btn-gradient-primary px-3 py-2 text-decoration-none">
            <i class="fas fa-plus-circle me-1.5"></i> Add New Product
        </a>
        @endcan
    </div>
</div>

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-3 py-2.5 small shadow-sm border-warning rounded-3" role="alert">
        <i class="fas fa-clock me-2"></i> <strong>Pending Admin Approval:</strong> {{ session('warning') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3 py-2.5 small shadow-sm border-success rounded-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> <strong>Success:</strong> {{ session('success') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-3 py-2.5 small shadow-sm border-danger rounded-3" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@php
    $normalizeImagesArray = function($imgs) {
        if (is_array($imgs)) {
            return array_values(array_filter($imgs));
        }
        if (empty($imgs)) {
            return [];
        }
        if (is_string($imgs)) {
            $decoded = json_decode($imgs, true);
            if (is_array($decoded)) {
                return array_values(array_filter($decoded));
            }
            if (str_contains($imgs, ',')) {
                return array_values(array_filter(array_map('trim', explode(',', $imgs))));
            }
            if (trim($imgs) !== '') {
                return [trim($imgs)];
            }
        }
        return [];
    };
@endphp

<!-- Top Filter Panel -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form method="GET" action="{{ route('vendor.products.index') }}" id="vendorProductFilterForm">
            <input type="hidden" name="source" value="{{ $source ?? 'my_products' }}">
            <input type="hidden" name="view_mode" id="vendor_view_mode_input" value="{{ request('view_mode', 'table') }}">
            
            <div class="row g-2 align-items-end">
                <div class="col-md-3 col-6">
                    <label for="vendor_category_id" class="form-label small fw-bold mb-1">Category</label>
                    <select name="category_id" id="vendor_category_id" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label for="vendor_sub_category_id" class="form-label small fw-bold mb-1">Subcategory</label>
                    <select name="sub_category_id" id="vendor_sub_category_id" class="form-select form-select-sm" {{ $subCategories->isEmpty() ? 'disabled' : '' }}>
                        <option value="">{{ request('category_id') ? 'All Subcategories' : 'Select Category First' }}</option>
                        @foreach($subCategories as $subCat)
                            <option value="{{ $subCat->id }}" {{ request('sub_category_id') == $subCat->id ? 'selected' : '' }}>
                                {{ $subCat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label for="vendor_third_category_id" class="form-label small fw-bold mb-1">Child Subcategory</label>
                    <select name="third_category_id" id="vendor_third_category_id" class="form-select form-select-sm" {{ $thirdCategories->isEmpty() ? 'disabled' : '' }}>
                        <option value="">{{ request('sub_category_id') ? 'All Child Subcategories' : 'Select Subcategory First' }}</option>
                        @foreach($thirdCategories as $thirdCat)
                            <option value="{{ $thirdCat->id }}" {{ request('third_category_id') == $thirdCat->id ? 'selected' : '' }}>
                                {{ $thirdCat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label for="vendor_status" class="form-label small fw-bold mb-1">Status</label>
                    <select name="status" id="vendor_status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-md-3 col-6">
                    <label for="vendor_product_type" class="form-label small fw-bold mb-1">Product Type</label>
                    <select name="product_type" id="vendor_product_type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="simple" {{ request('product_type') === 'simple' ? 'selected' : '' }}>Simple</option>
                        <option value="variable" {{ request('product_type') === 'variable' ? 'selected' : '' }}>Variable</option>
                        <option value="digital" {{ request('product_type') === 'digital' ? 'selected' : '' }}>Digital</option>
                        <option value="affiliate" {{ request('product_type') === 'affiliate' ? 'selected' : '' }}>Affiliate</option>
                    </select>
                </div>
                <div class="col-md-2 col-6">
                    <label for="vendor_price_min" class="form-label small fw-bold mb-1">Min Price (TK)</label>
                    <input type="number" name="price_min" id="vendor_price_min" class="form-control form-control-sm" placeholder="Min Price" value="{{ request('price_min') }}" min="0">
                </div>
                <div class="col-md-2 col-6">
                    <label for="vendor_price_max" class="form-label small fw-bold mb-1">Max Price (TK)</label>
                    <input type="number" name="price_max" id="vendor_price_max" class="form-control form-control-sm" placeholder="Max Price" value="{{ request('price_max') }}" min="0">
                </div>
                <div class="col-md-2 col-6">
                    <label for="vendor_date_from" class="form-label small fw-bold mb-1">Date From</label>
                    <input type="date" name="date_from" id="vendor_date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3 col-6">
                    <label for="vendor_date_to" class="form-label small fw-bold mb-1">Date To</label>
                    <input type="date" name="date_to" id="vendor_date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>

                <div class="col-md-9 col-12">
                    <label for="vendor_search" class="form-label small fw-bold mb-1">Search Product</label>
                    <input type="text" name="search" id="vendor_search" class="form-control form-control-sm" placeholder="Search by product name or ID..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 col-12 text-end d-flex align-items-end justify-content-end">
                    <div class="d-flex justify-content-end gap-2 w-100 align-items-center">
                        <div class="btn-group btn-group-sm" role="group" style="height: 38px;">
                            <a href="{{ route('vendor.products.index', array_merge(request()->except(['view_mode']), ['view_mode' => 'table', 'source' => $source ?? 'my_products'])) }}" 
                               class="btn {{ request('view_mode', 'table') === 'table' ? 'btn-primary' : 'btn-outline-secondary' }} d-inline-flex align-items-center justify-content-center" title="Table View" style="width: 38px;">
                                <i class="fas fa-list"></i>
                            </a>
                            <a href="{{ route('vendor.products.index', array_merge(request()->except(['view_mode']), ['view_mode' => 'grouped', 'source' => $source ?? 'my_products'])) }}" 
                               class="btn {{ request('view_mode') === 'grouped' ? 'btn-primary' : 'btn-outline-secondary' }} d-inline-flex align-items-center justify-content-center" title="Grouped Category View" style="width: 38px;">
                                <i class="fas fa-layer-group"></i>
                            </a>
                        </div>
                        <a href="{{ route('vendor.products.index', ['source' => $source ?? 'my_products']) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center gap-1" style="height: 38px; padding: 0 12px;">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center gap-1" style="height: 38px; padding: 0 12px;">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Display Grouped Hierarchy View if selected -->
@if(request('view_mode') === 'grouped' && !empty($groupedProducts) && $groupedProducts->isNotEmpty())
    <div class="mb-4">
        @foreach($groupedProducts as $catName => $subGroups)
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-header bg-light d-flex align-items-center justify-content-between py-2">
                    <h5 class="mb-0 text-primary fw-bold">
                        <i class="fas fa-folder me-2 text-warning"></i> Category: {{ $catName }}
                    </h5>
                    <span class="badge bg-primary rounded-pill">
                        {{ $subGroups->flatten()->count() }} Products
                    </span>
                </div>
                <div class="card-body p-0">
                    @foreach($subGroups as $subName => $prods)
                        <div class="p-3 border-bottom bg-white">
                            <h6 class="text-secondary font-weight-bold mb-2">
                                <i class="fas fa-folder-open me-2 text-info"></i> Subcategory: {{ $subName }}
                                <span class="badge bg-secondary ms-1">{{ $prods->count() }}</span>
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;">
                                                <input type="checkbox" class="form-check-input select-all-products" title="Select All">
                                            </th>
                                            <th style="width: 50px;">Image</th>
                                            <th>Product Name</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($prods as $product)
                                            <tr>
                                                <td>
                                                    @if(($source ?? 'my_products') === 'admin_products' && !in_array($product->id, $allocatedProductIds ?? []) && !in_array($product->title, $copiedProductTitles ?? []))
                                                        <input type="checkbox" class="form-check-input product-select-checkbox" value="{{ $product->id }}">
                                                    @else
                                                        <input type="checkbox" class="form-check-input" disabled>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($product->thumb_image)
                                                        <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $product->title }}</strong></td>
                                                <td>
                                                      @if(auth()->user()->hasRole('reseller'))
                                                          @php
                                                              $resellerPriceMode = auth()->user()?->vendorSettings?->reseller_price_mode ?? 'markup';
                                                              $markupPct = (float)(auth()->user()?->vendorSettings?->reseller_markup_pct ?? 10.00);
                                                              $basePrice = (float)($product->reseller_price ?? 0);
                                                              if ($basePrice <= 0) {
                                                                  $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                                                                  $prodCost = (float)($product->product_cost ?? 0);
                                                                  if ($prodCost > 0) {
                                                                      $basePrice = $prodCost + ($prodCost * ($resellerPct / 100));
                                                                  } else {
                                                                      $basePrice = (float)($product->wholesale_price > 0 ? $product->wholesale_price : ($product->offer > 0 ? $product->offer : ($product->old_price ?? 0)));
                                                                  }
                                                              }
                                                              if ($resellerPriceMode === 'admin_selling_price') {
                                                                  $adminSale = (float)($product->offer > 0 ? $product->offer : ($product->old_price ?? 0));
                                                                  $sellingPrice = $adminSale > 0 ? $adminSale : $basePrice;
                                                              } else {
                                                                  $sellingPrice = $basePrice + ceil($basePrice * ($markupPct / 100));
                                                              }
                                                              $profit = $sellingPrice - $basePrice;
                                                          @endphp
                                                          <strong class="text-dark">৳{{ number_format($basePrice, 2) }}</strong>
                                                          <div class="mt-1" style="font-size: 0.72rem;">
                                                              <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-1.5 py-0.5">
                                                                  ৳{{ number_format($sellingPrice, 2) }}
                                                              </span>
                                                              <span class="text-warning fw-bold d-block mt-0.5">+৳{{ number_format($profit, 2) }} profit</span>
                                                          </div>
                                                      @elseif(auth()->user()->hasRole('wholeseller'))
                                                          @php
                                                              $wholesalePrice = (float)($product->wholesale_price ?? 0);
                                                              if ($wholesalePrice <= 0 && $product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty()) {
                                                                  $wPrices = [];
                                                                  foreach ($product->variationCombinations as $comb) {
                                                                      $wp = $comb->wholesale_price > 0 ? $comb->wholesale_price : ($comb->offer_price ?? $comb->regular_price ?? 0);
                                                                      if ($wp > 0) $wPrices[] = (float)$wp;
                                                                  }
                                                                  if (!empty($wPrices)) {
                                                                      $wholesalePrice = min($wPrices);
                                                                  }
                                                              }
                                                          @endphp
                                                          <strong>৳{{ number_format($wholesalePrice, 2) }}</strong>
                                                          @if($product->wholesaleTiers && $product->wholesaleTiers->isNotEmpty())
                                                              <div class="mt-1" style="font-size: 0.7rem; line-height: 1.2;">
                                                                  @foreach($product->wholesaleTiers as $tier)
                                                                      <div class="text-secondary">Buy <strong>{{ $tier->min_quantity }}+</strong>: ৳{{ number_format($tier->price, 2) }}</div>
                                                                  @endforeach
                                                              </div>
                                                          @endif
                                                      @else
                                                          ৳{{ number_format($product->price ?? $product->old_price, 2) }}
                                                      @endif
                                                  </td>
                                                <td>{{ $product->quantity ?? 0 }}</td>
                                                <td>
                                                    @php
                                                        $isCopied = in_array($product->id, $allocatedProductIds ?? []) 
                                                                 || in_array($product->title, $copiedProductTitles ?? [])
                                                                 || in_array($product->id, $allCopiedIds ?? [])
                                                                 || in_array($product->title, $allCopiedTitles ?? []);
                                                    @endphp
                                                    @if(auth()->user()->hasRole('reseller'))
                                                        @php
                                                            $resellerPriceMode = auth()->user()?->vendorSettings?->reseller_price_mode ?? 'markup';
                                                            $markupPct = (float)(auth()->user()?->vendorSettings?->reseller_markup_pct ?? 10.00);
                                                            $baseResellerPrice = (float)($product->reseller_price ?? 0);
                                                            if ($baseResellerPrice <= 0) {
                                                                $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                                                                $prodCost = (float)($product->product_cost ?? 0);
                                                                if ($prodCost > 0) {
                                                                    $baseResellerPrice = $prodCost + ($prodCost * ($resellerPct / 100));
                                                                } else {
                                                                    $baseResellerPrice = (float)($product->wholesale_price > 0 ? $product->wholesale_price : ($product->offer > 0 ? $product->offer : ($product->old_price ?? 0)));
                                                                }
                                                            }
                                                            if ($resellerPriceMode === 'admin_selling_price') {
                                                                $calculatedSellingPrice = (float)($product->offer > 0 ? $product->offer : ($product->old_price ?? $baseResellerPrice));
                                                            } else {
                                                                $calculatedSellingPrice = $baseResellerPrice + ceil($baseResellerPrice * ($markupPct / 100));
                                                            }
                                                        @endphp
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <button type="button" class="btn btn-gradient-success btn-sm font-weight-bold d-inline-flex align-items-center gap-1.5 px-3 py-1.5" 
                                                                    data-product="{{ json_encode([
                                                                        'title' => $product->title,
                                                                        'product_url' => route('product.single', ['id' => $product->id, 'slug' => $product->slug]),
                                                                        'category' => $product->category->name ?? 'N/A',
                                                                        'brand' => $product->brand->name ?? 'N/A',
                                                                        'stock_status' => $product->stock_status,
                                                                        'quantity' => $product->quantity,
                                                                        'description' => preg_replace('/<img[^>]*>/i', '', $product->description ?? ''),
                                                                        'short_description' => preg_replace('/<img[^>]*>/i', '', $product->short_description ?? ''),
                                                                        'old_price' => $product->old_price,
                                                                        'offer' => $calculatedSellingPrice,
                                                                        'reseller_price' => $baseResellerPrice,
                                                                        'sku' => $product->sku ?? '',
                                                                        'thumb_image' => asset('storage/' . $product->thumb_image),
                                                                        'gallery_images' => array_map(fn($img) => asset('storage/' . $img), $normalizeImagesArray($product->images)),
                                                                        'variations' => $product->variationCombinations->map(function($comb) use ($resellerPriceMode, $markupPct) {
                                                                            $combBase = (float)($comb->reseller_price > 0 ? $comb->reseller_price : ($comb->offer_price ?? $comb->regular_price ?? 0));
                                                                            if ($resellerPriceMode === 'admin_selling_price') {
                                                                                $combSelling = (float)($comb->offer_price > 0 ? $comb->offer_price : ($comb->regular_price ?? $combBase));
                                                                            } else {
                                                                                $combSelling = $combBase + ceil($combBase * ($markupPct / 100));
                                                                            }
                                                                            return $comb->combination_string . ': ৳' . number_format($combSelling, 2);
                                                                        })->toArray()
                                                                    ]) }}"
                                                                    onclick="copyProductContentFromBtn(this)">
                                                                <i class="fas fa-copy"></i> Copy Info
                                                            </button>
                                                            <button type="button" class="btn btn-gradient-blue btn-sm font-weight-bold d-inline-flex align-items-center gap-1.5 px-3 py-1.5" 
                                                                    onclick="downloadProductImages('{{ $product->id }}', '{{ addslashes($product->title) }}', '{{ asset('storage/' . $product->thumb_image) }}', {{ json_encode(array_map(fn($img) => asset('storage/' . $img), $normalizeImagesArray($product->images))) }})">
                                                                <i class="fas fa-download"></i> Download Images
                                                            </button>
                                                        </div>
                                                    @elseif(($source ?? 'my_products') === 'admin_products')
                                                        @if($isCopied)
                                                            <div class="d-inline-flex align-items-center gap-1">
                                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 font-weight-bold d-inline-flex align-items-center gap-1" title="Already Copied to Your Store" style="font-size: 0.78rem; border-radius: 6px;">
                                                                    <i class="fas fa-check-circle"></i> Copied
                                                                </span>
                                                                <button type="button" class="btn btn-sm btn-primary font-weight-bold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#copyStockModal_{{ $product->id }}" onclick="calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }})" title="Get More Stock" style="border-radius: 6px;">
                                                                    <i class="fas fa-plus-circle"></i> <span class="d-none d-md-inline">Get More Stock</span>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-success font-weight-bold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#copyStockModal_{{ $product->id }}" onclick="calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }})" style="border-radius: 6px;">
                                                                <i class="fas fa-copy me-1"></i> Copy
                                                            </button>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('vendor.products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endif

<!-- Products Table -->
<div class="card shadow-sm border-0 rounded-4 mb-4" style="border: 1px solid #e2e8f0 !important; overflow: clip;">
    <div class="card-body p-0">
        @if(($source ?? 'my_products') === 'admin_products')
            <!-- Bulk Action Bar -->
            <form id="bulkCopyForm" action="{{ route('vendor.products.bulk-copy') }}" method="POST">
                @csrf
                @if(!auth()->user()?->hasRole('reseller'))
                <div class="bulk-action-bar m-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="form-check m-0">
                            <input type="checkbox" class="form-check-input" id="select_all_header_checkbox" style="cursor: pointer; width: 1.15em; height: 1.15em;">
                            <label class="form-check-label fw-bold text-dark ms-1" for="select_all_header_checkbox" style="cursor: pointer; font-size: 0.875rem;">Select All Available Products</label>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-2.5 py-1.5 rounded-pill" id="selected_count_badge" style="font-size: 0.75rem;">0 Selected</span>
                    </div>
                    <button type="button" class="btn btn-gradient-success d-inline-flex align-items-center gap-1.5 px-3 py-2" id="bulk_copy_btn" onclick="openBulkCopyCartModal()" disabled>
                        <i class="fas fa-shopping-cart"></i> Copy Selected Products
                    </button>
                </div>
                @endif
            <!-- Bulk Stock Purchase Cart Modal -->
            <div class="modal fade text-start" id="bulkCopyCartModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content rounded-4 border-0 shadow-lg">
                        <div class="modal-header bg-primary text-white p-3.5">
                            <h5 class="modal-title font-weight-bold"><i class="fas fa-shopping-cart me-2"></i> Bulk Stock Purchase & Copy Cart</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <!-- Wallet Balance Header -->
                            <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <small class="text-muted font-weight-bold d-block">YOUR WALLET BALANCE</small>
                                    <span class="fs-5 font-weight-bold text-primary">৳{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</span>
                                </div>
                                @if(auth()->user()->canSeeRecharge())
                                <a href="{{ route('vendor.wallet.index') }}" class="btn btn-sm btn-outline-primary font-weight-bold" target="_blank">
                                    <i class="fas fa-plus-circle me-1"></i> Recharge Wallet
                                </a>
                                @endif
                            </div>

                            <h6 class="font-weight-bold text-dark mb-3">Selected Products Stock Configuration:</h6>
                            <div id="bulkCartItemsContainer">
                                <!-- Cart Items Dynamic Insertion -->
                            </div>

                            <!-- Summary Card -->
                            <div class="p-3 bg-primary bg-opacity-10 rounded-3 border border-primary border-opacity-25 mt-4">
                                <div class="row align-items-center">
                                    <div class="col-md-7">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Total Stock Units Purchased:</span>
                                            <strong id="bulk_cart_total_units" class="text-dark">0 units</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Est. Remaining Wallet Balance:</span>
                                            <span id="bulk_cart_remaining_bal" class="small font-weight-bold text-success">৳{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <small class="text-muted font-weight-bold d-block">Total Wallet Deduction:</small>
                                        <span class="fs-3 font-weight-bold text-danger" id="bulk_cart_total_cost">৳0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-gradient-success font-weight-bold px-4" id="submit_bulk_copy_cart_btn" form="bulkCopyForm">
                                <i class="fas fa-check-circle me-1"></i> Confirm & Deduct Wallet Fund
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        @endif

        @if($products->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-box fs-1 text-muted"></i>
                <h4 class="mt-3">No Products Found</h4>
                @if(($source ?? 'my_products') === 'admin_products')
                    <p class="text-muted">No products available in the Parent Admin catalog right now.</p>
                @else
                    <p class="text-muted">Start by creating your first product or copy from Parent Admin Catalog!</p>
                    <a href="{{ route('vendor.products.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus-circle"></i> Create Product
                    </a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-modern align-middle mb-0" style="min-width: 900px;">
                    <thead>
                        <tr>
                            <th style="width: 45px;" class="ps-4">
                                @if(($source ?? 'my_products') === 'admin_products' && !auth()->user()?->hasRole('reseller'))
                                    <input type="checkbox" class="form-check-input select-all-products" title="Select All">
                                @else
                                    #
                                @endif
                            </th>
                            <th style="width: 70px;">Image</th>
                            <th>Product Info</th>
                            <th>Price</th>
                            @if(($source ?? 'my_products') === 'my_products')
                                <th>Commission</th>
                            @endif
                            <th>Stock</th>
                            @if(($source ?? 'my_products') === 'my_products')
                                <th>Status</th>
                            @endif
                            <th>Created</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td class="ps-4">
                            @if(auth()->user()->hasRole('reseller'))
                                <small class="text-muted fw-bold">{{ $loop->iteration }}</small>
                            @elseif(($source ?? 'my_products') === 'admin_products')
                                @if(in_array($product->id, $allocatedProductIds ?? []) || in_array($product->title, $copiedProductTitles ?? []))
                                    <input type="checkbox" class="form-check-input" disabled>
                                @else
                                    <input type="checkbox" class="form-check-input product-select-checkbox" value="{{ $product->id }}" name="product_ids[]" form="bulkCopyForm">
                                @endif
                            @else
                                <small class="text-muted fw-bold">{{ $loop->iteration }}</small>
                            @endif
                        </td>
                        <td>
                            @if($product->thumb_image)
                                <img src="{{ asset('storage/' . $product->thumb_image) }}" 
                                     alt="{{ $product->title }}"
                                     style="width: 52px; height: 52px; object-fit: cover; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                            @else
                                <div class="d-flex align-items-center justify-content-center"
                                     style="width: 52px; height: 52px; border-radius: 12px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); border: 1px dashed #cbd5e1;">
                                    <i class="fas fa-image" style="color: #94a3b8; font-size: 18px;"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark fs-6" style="line-height: 1.3;">{{ $product->title }}</div>
                            @php
                                $catParts = [];
                                if ($product->category) {
                                    $catParts[] = $product->category->name;
                                }
                                if ($product->subCategory) {
                                    $catParts[] = $product->subCategory->name;
                                }
                            @endphp
                            <span class="cat-tag mt-1">
                                <i class="fas fa-tag me-1 text-primary opacity-75"></i>{{ count($catParts) > 0 ? implode(' > ', $catParts) : 'N/A' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $displayPrice = '৳0.00';
                                $displayOldPrice = null;
                                $displayWholesalePrice = null;
                                $displayWholesaleLabel = 'Wholesale Price';

                                if (($source ?? 'my_products') === 'admin_products') {
                                    if ($product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty()) {
                                        $wPrices = [];
                                        foreach ($product->variationCombinations as $comb) {
                                            $wp = $comb->wholesale_price > 0 ? $comb->wholesale_price : ($comb->offer_price ?? $comb->regular_price ?? 0);
                                            if ($wp > 0) $wPrices[] = (float)$wp;
                                        }
                                        if (!empty($wPrices)) {
                                            $minW = min($wPrices);
                                            $maxW = max($wPrices);
                                            if ($minW === $maxW) {
                                                $displayPrice = '৳' . number_format($minW, 2);
                                            } else {
                                                $displayPrice = '৳' . number_format($minW, 2) . ' - ৳' . number_format($maxW, 2);
                                            }
                                        }
                                    } else {
                                        $wp = $product->wholesale_price > 0 ? $product->wholesale_price : ($product->offer > 0 ? $product->offer : ($product->old_price ?? 0));
                                        $displayPrice = '৳' . number_format($wp, 2);
                                    }
                                } else {
                                    // my_products: show selling price + wholesale/cost price
                                    if ($product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty()) {
                                        $prices = [];
                                        $regularPrices = [];
                                        $wPrices = [];
                                        foreach ($product->variationCombinations as $comb) {
                                            $p = $comb->offer_price ?? $comb->regular_price ?? 0;
                                            $reg = $comb->regular_price ?? 0;
                                            $wp = ($comb->wholesale_price > 0) ? $comb->wholesale_price : ($comb->product_cost ?? 0);
                                            if ($p > 0) $prices[] = (float)$p;
                                            if ($reg > 0) $regularPrices[] = (float)$reg;
                                            if ($wp > 0) $wPrices[] = (float)$wp;
                                        }
                                        if (!empty($prices)) {
                                            $minP = min($prices); $maxP = max($prices);
                                            $displayPrice = $minP === $maxP ? '৳' . number_format($minP, 2) : '৳' . number_format($minP, 2) . ' - ৳' . number_format($maxP, 2);
                                        } elseif (!empty($regularPrices)) {
                                            $minP = min($regularPrices); $maxP = max($regularPrices);
                                            $displayPrice = $minP === $maxP ? '৳' . number_format($minP, 2) : '৳' . number_format($minP, 2) . ' - ৳' . number_format($maxP, 2);
                                        }
                                        if (!empty($wPrices)) {
                                            $minW = min($wPrices); $maxW = max($wPrices);
                                            $displayWholesalePrice = $minW === $maxW ? '৳' . number_format($minW, 2) : '৳' . number_format($minW, 2) . ' - ৳' . number_format($maxW, 2);
                                        }
                                    } else {
                                        $offerPrice = (float)($product->offer ?? 0);
                                        $oldPrice   = (float)($product->old_price ?? 0);
                                        $costPrice  = (float)(($product->wholesale_price > 0) ? $product->wholesale_price : ($product->product_cost ?? 0));

                                        if ($offerPrice > 0) {
                                            $displayPrice = '৳' . number_format($offerPrice, 2);
                                            if ($oldPrice > $offerPrice) {
                                                $displayOldPrice = '৳' . number_format($oldPrice, 2);
                                            }
                                        } elseif ($oldPrice > 0) {
                                            $displayPrice = '৳' . number_format($oldPrice, 2);
                                        }
                                        if ($costPrice > 0) {
                                            $displayWholesalePrice = '৳' . number_format($costPrice, 2);
                                        }
                                    }
                                }
                            @endphp
                             @if(!auth()->user()->hasRole('reseller'))
                                 @if($displayOldPrice)
                                     <small class="text-decoration-line-through text-muted d-block" style="font-size: 0.75rem;">{{ $displayOldPrice }}</small>
                                 @endif
                                 <strong class="text-dark fs-6">{{ $displayPrice }}</strong>

                                 @if(($source ?? 'my_products') === 'my_products' && $displayWholesalePrice)
                                     <div class="mt-1">
                                         <small class="text-muted d-block" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;">Wholesale Price</small>
                                         <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.8rem; font-weight: 700; border-radius: 6px;">
                                             {{ $displayWholesalePrice }}
                                         </span>
                                     </div>
                                 @endif
                             @endif

                             @if(auth()->user()->hasRole('reseller'))
                                 <div class="mt-1">
                                      @php
                                           if ($product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty()) {
                                               $firstComb = $product->variationCombinations->first();
                                               $adminResellerPrice = (float)($firstComb->reseller_price ?? 0);
                                               if ($adminResellerPrice <= 0) {
                                                   $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                                                   $prodCost = (float)($firstComb->product_cost > 0 ? $firstComb->product_cost : ($product->product_cost ?? 0));
                                                   if ($prodCost > 0) {
                                                       $basePrice = $prodCost + ($prodCost * ($resellerPct / 100));
                                                   } else {
                                                       $basePrice = (float)($firstComb->wholesale_price > 0 ? $firstComb->wholesale_price : ($firstComb->offer_price ?? $firstComb->regular_price ?? 0));
                                                   }
                                               } else {
                                                   $basePrice = $adminResellerPrice;
                                               }
                                           } else {
                                               $adminResellerPrice = (float)($product->reseller_price ?? 0);
                                               if ($adminResellerPrice <= 0) {
                                                   $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                                                   $prodCost = (float)($product->product_cost ?? 0);
                                                   if ($prodCost > 0) {
                                                       $basePrice = $prodCost + ($prodCost * ($resellerPct / 100));
                                                   } else {
                                                       $basePrice = (float)($product->wholesale_price > 0 ? $product->wholesale_price : ($product->offer > 0 ? $product->offer : ($product->old_price ?? 0)));
                                                   }
                                               } else {
                                                   $basePrice = $adminResellerPrice;
                                               }
                                           }
                                           
                                           $resellerPriceMode = auth()->user()?->vendorSettings?->reseller_price_mode ?? 'markup';
                                           $markupPct = (float)(auth()->user()?->vendorSettings?->reseller_markup_pct ?? 10.00);

                                           if ($resellerPriceMode === 'admin_selling_price') {
                                               if ($product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty()) {
                                                   $firstComb = $product->variationCombinations->first();
                                                   $adminSalePrice = (float)($firstComb->offer_price > 0 ? $firstComb->offer_price : ($firstComb->regular_price ?? 0));
                                                   if ($adminSalePrice <= 0) {
                                                       $adminSalePrice = (float)($product->offer > 0 ? $product->offer : ($product->old_price ?? 0));
                                                   }
                                               } else {
                                                   $adminSalePrice = (float)($product->offer > 0 ? $product->offer : ($product->old_price ?? 0));
                                               }
                                               $sellingPrice = $adminSalePrice > 0 ? $adminSalePrice : $basePrice;
                                           } else {
                                               $sellingPrice = $basePrice + ceil($basePrice * ($markupPct / 100));
                                           }
                                           $profit = $sellingPrice - $basePrice;
                                      @endphp
                                       
                                       <!-- Large price is the Reseller Cost -->
                                       <strong class="text-dark fs-6" title="Reseller Cost">৳{{ number_format($basePrice, 2) }}</strong>
                                       
                                       <div class="mt-2 pt-2 border-top">
                                           <div class="d-flex flex-column gap-1">
                                               <div>
                                                   <small class="text-muted d-block" style="font-size: 0.65rem; font-weight: 700; text-transform: uppercase;">Selling Price</small>
                                                   <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-0.5" style="font-size: 0.8rem; font-weight: 700;">
                                                       ৳{{ number_format($sellingPrice, 2) }}
                                                   </span>
                                               </div>
                                               <div style="font-size: 0.72rem; line-height: 1.2;">
                                                   <span class="text-warning fw-bold d-block">+৳{{ number_format($profit, 2) }} profit</span>
                                               </div>
                                           </div>
                                       </div>
                                  </div>
                             @endif

                             @if(!auth()->user()->hasRole('reseller') && ((($source ?? 'my_products') === 'admin_products') || auth()->user()->hasRole('wholeseller') || auth()->user()->isVendorRetailer() || auth()->user()->hasRole('retailer')))
                                 <div class="mt-1">
                                     <small class="text-muted d-block" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;">Wholesale Price</small>
                                     @php
                                         $wholesalePrice = (float)($product->wholesale_price ?? 0);
                                         if ($wholesalePrice <= 0 && $product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty()) {
                                             $wPrices = [];
                                             foreach ($product->variationCombinations as $comb) {
                                                 $wp = $comb->wholesale_price > 0 ? $comb->wholesale_price : ($comb->offer_price ?? $comb->regular_price ?? 0);
                                                 if ($wp > 0) $wPrices[] = (float)$wp;
                                             }
                                             if (!empty($wPrices)) {
                                                 $wholesalePrice = min($wPrices);
                                             }
                                         }
                                     @endphp
                                     <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-0.5" style="font-size: 0.8rem; font-weight: 700; border-radius: 6px;">
                                         ৳{{ number_format($wholesalePrice, 2) }}
                                     </span>
                                 </div>
                             @endif
                        </td>
                        @if(($source ?? 'my_products') === 'my_products')
                        <td>
                            @if($product->vendor_commission_rate)
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1 rounded-2 fw-bold">{{ $product->vendor_commission_rate }}%</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                            @if($product->vendor_proposed_commission && $product->vendor_proposed_commission != $product->vendor_commission_rate)
                                <br><small class="text-warning fw-bold">Proposed: {{ $product->vendor_proposed_commission }}%</small>
                            @endif
                        </td>
                        @endif
                        <td>
                            @if($product->manage_stock)
                                @if($product->quantity > 0)
                                    <span class="stock-badge-pill">
                                        <span class="stock-dot"></span> {{ $product->quantity }} units
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1 rounded-pill fw-bold">
                                        <i class="fas fa-times-circle me-1"></i> Out of Stock
                                    </span>
                                @endif
                            @else
                                <span class="stock-badge-pill">
                                    <span class="stock-dot"></span> In Stock
                                </span>
                            @endif
                        </td>
                        @if(($source ?? 'my_products') === 'my_products')
                        <td>
                            @php
                                $allocation = $productAllocations[$product->id] ?? null;
                            @endphp
                            @if($allocation)
                                @if($allocation->status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold">
                                        <i class="fas fa-check-circle me-1"></i> Approved
                                    </span>
                                @elseif($allocation->status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold">
                                        <i class="fas fa-clock me-1"></i> Pending Approval
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold">
                                        <i class="fas fa-times-circle me-1"></i> Allocation Rejected
                                    </span>
                                @endif
                            @else
                                @if($product->approval_status === 'approved')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold">
                                        <i class="fas fa-check-circle me-1"></i> Approved
                                    </span>
                                @elseif($product->approval_status === 'pending')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2.5 py-1.5 rounded-pill fw-bold">
                                        <i class="fas fa-times-circle me-1"></i> Rejected
                                    </span>
                                @endif
                            @endif
                            @if(!$product->status)
                                <br><small class="text-muted fw-semibold">Inactive</small>
                            @endif
                        </td>
                        @endif
                        <td>
                            <small class="text-muted fw-semibold">{{ $product->created_at ? $product->created_at->format('d M Y') : 'N/A' }}</small>
                        </td>
                        <td class="text-end pe-4">
                            @php
                                $isCopied = in_array($product->id, $allocatedProductIds ?? []) 
                                         || in_array($product->title, $copiedProductTitles ?? [])
                                         || in_array($product->id, $allCopiedIds ?? [])
                                         || in_array($product->title, $allCopiedTitles ?? []);
                            @endphp
                            @if(auth()->user()->hasRole('reseller'))
                                @php
                                    $resellerPriceMode = auth()->user()?->vendorSettings?->reseller_price_mode ?? 'markup';
                                    $markupPct = (float)(auth()->user()?->vendorSettings?->reseller_markup_pct ?? 10.00);
                                    $baseResellerPrice = (float)($product->reseller_price ?? 0);
                                    if ($baseResellerPrice <= 0) {
                                        $resellerPct = (float)\App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5);
                                        $prodCost = (float)($product->product_cost ?? 0);
                                        if ($prodCost > 0) {
                                            $baseResellerPrice = $prodCost + ($prodCost * ($resellerPct / 100));
                                        } else {
                                            $baseResellerPrice = (float)($product->wholesale_price > 0 ? $product->wholesale_price : ($product->offer > 0 ? $product->offer : ($product->old_price ?? 0)));
                                        }
                                    }
                                    if ($resellerPriceMode === 'admin_selling_price') {
                                        $calculatedSellingPrice = (float)($product->offer > 0 ? $product->offer : ($product->old_price ?? $baseResellerPrice));
                                    } else {
                                        $calculatedSellingPrice = $baseResellerPrice + ceil($baseResellerPrice * ($markupPct / 100));
                                    }
                                @endphp
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-gradient-success btn-sm font-weight-bold d-inline-flex align-items-center gap-1.5 px-3 py-1.5" 
                                            data-product="{{ json_encode([
                                                'title' => $product->title,
                                                'product_url' => route('product.single', ['id' => $product->id, 'slug' => $product->slug]),
                                                'category' => $product->category->name ?? 'N/A',
                                                'brand' => $product->brand->name ?? 'N/A',
                                                'stock_status' => $product->stock_status,
                                                'quantity' => $product->quantity,
                                                'description' => preg_replace('/<img[^>]*>/i', '', $product->description ?? ''),
                                                'short_description' => preg_replace('/<img[^>]*>/i', '', $product->short_description ?? ''),
                                                'old_price' => $product->old_price,
                                                'offer' => $calculatedSellingPrice,
                                                'reseller_price' => $baseResellerPrice,
                                                'sku' => $product->sku ?? '',
                                                'thumb_image' => asset('storage/' . $product->thumb_image),
                                                'gallery_images' => array_map(fn($img) => asset('storage/' . $img), $normalizeImagesArray($product->images)),
                                                'variations' => $product->variationCombinations->map(function($comb) use ($resellerPriceMode, $markupPct) {
                                                    $combBase = (float)($comb->reseller_price > 0 ? $comb->reseller_price : ($comb->offer_price ?? $comb->regular_price ?? 0));
                                                    if ($resellerPriceMode === 'admin_selling_price') {
                                                        $combSelling = (float)($comb->offer_price > 0 ? $comb->offer_price : ($comb->regular_price ?? $combBase));
                                                    } else {
                                                        $combSelling = $combBase + ceil($combBase * ($markupPct / 100));
                                                    }
                                                    return $comb->combination_string . ': ৳' . number_format($combSelling, 2);
                                                })->toArray()
                                            ]) }}"
                                            onclick="copyProductContentFromBtn(this)">
                                        <i class="fas fa-copy"></i> Copy Info
                                    </button>
                                    <button type="button" class="btn btn-gradient-blue btn-sm font-weight-bold d-inline-flex align-items-center gap-1.5 px-3 py-1.5" 
                                            onclick="downloadProductImages('{{ $product->id }}', '{{ addslashes($product->title) }}', '{{ asset('storage/' . $product->thumb_image) }}', {{ json_encode(array_map(fn($img) => asset('storage/' . $img), $normalizeImagesArray($product->images))) }})">
                                        <i class="fas fa-download"></i> Download Images
                                    </button>
                                </div>
                            @elseif(($source ?? 'my_products') === 'admin_products')
                                @can('vendor.products.create')
                                    @if($isCopied)
                                        <div class="d-inline-flex align-items-center gap-2 justify-content-end">
                                            <span class="copied-badge-pill" title="Already Copied to Your Store">
                                                <i class="fas fa-check-circle"></i> Already Copied
                                            </span>
                                            <button type="button" class="btn btn-gradient-blue btn-sm font-weight-bold d-inline-flex align-items-center gap-1.5 px-3 py-1.5" data-bs-toggle="modal" data-bs-target="#copyStockModal_{{ $product->id }}" onclick="calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }})" title="Get More Stock for this product">
                                                <i class="fas fa-plus-circle"></i> Get More Stock
                                            </button>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-gradient-success btn-sm font-weight-bold d-inline-flex align-items-center gap-1.5 px-3 py-1.5" data-bs-toggle="modal" data-bs-target="#copyStockModal_{{ $product->id }}" onclick="calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }})">
                                            <i class="fas fa-copy me-1"></i> Copy to My Products
                                        </button>
                                    @endif
                                @else
                                    <span class="text-muted small">View Only</span>
                                @endcan

                                    <!-- Modal for Stock Allocation & Purchase -->
                                    <div class="modal fade text-start" id="copyStockModal_{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered {{ $product->product_type === 'variable' ? 'modal-lg' : '' }}">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <form action="{{ route('vendor.products.copy', $product) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title font-weight-bold"><i class="fas fa-boxes me-2"></i> {{ $isCopied ? 'Get Additional Stock' : 'Stock Purchase & Copy' }}: {{ $product->title }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-4">
                                                        <!-- Wallet Balance Header -->
                                                         <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center mb-4">
                                                             <div>
                                                                 <small class="text-muted font-weight-bold d-block">YOUR WALLET BALANCE</small>
                                                                 <span class="fs-5 font-weight-bold text-primary">৳{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</span>
                                                             </div>
                                                             @if(auth()->user()->canSeeRecharge())
                                                             <a href="{{ route('vendor.wallet.index') }}" class="btn btn-sm btn-outline-primary font-weight-bold" target="_blank">
                                                                 <i class="fas fa-plus-circle me-1"></i> Recharge Wallet
                                                             </a>
                                                             @endif
                                                         </div>
                                                        <!-- Product Summary Header -->
                                                         <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 border mb-4">
                                                             @if($product->thumb_image)
                                                                 <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}" class="rounded border" style="width: 55px; height: 55px; object-fit: cover;">
                                                             @else
                                                                 <div class="bg-secondary bg-opacity-10 rounded d-flex align-items-center justify-content-center border" style="width: 55px; height: 55px;">
                                                                     <i class="fas fa-image text-muted fs-4"></i>
                                                                 </div>
                                                             @endif
                                                             <div>
                                                                 <h6 class="fw-bold mb-0 text-dark">{{ $product->title }}</h6>
                                                                 <small class="text-muted">Type: {{ ucfirst($product->product_type ?? 'simple') }}</small>
                                                             </div>
                                                         </div>

                                                         @if($product->product_type === 'variable' && $product->relationLoaded('variationCombinations') && $product->variationCombinations->isNotEmpty())
                                                             @php
                                                                 $hasAnyVarTiers = $product->variationCombinations->some(fn($c) => $c->wholesaleTiers && $c->wholesaleTiers->isNotEmpty());
                                                             @endphp

                                                             @if($hasAnyVarTiers)
                                                                 <!-- Variation Tier Discount Notification -->
                                                                 <div class="p-2.5 px-3 rounded-3 mb-3 d-flex align-items-center justify-content-between border" style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border-color: #a7f3d0 !important;">
                                                                     <div class="d-flex align-items-center gap-2">
                                                                         <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">
                                                                             <i class="fas fa-layer-group"></i>
                                                                         </div>
                                                                         <div>
                                                                             <div class="fw-bold text-success-emphasis" style="font-size: 13px;">Bulk Tier Discounts Available on Variations!</div>
                                                                             <small class="text-muted" style="font-size: 11px;">Click any tier button below to quickly select threshold quantities and unlock lower unit prices.</small>
                                                                         </div>
                                                                     </div>
                                                                     <span class="badge bg-success bg-opacity-15 text-success fw-bold px-2 py-1" style="font-size: 11px;">
                                                                         <i class="fas fa-bolt me-1"></i> Volume Savings
                                                                     </span>
                                                                 </div>
                                                             @endif

                                                             <h6 class="font-weight-bold text-dark mb-2 d-flex justify-content-between align-items-center">
                                                                 <span>Select Stock Quantity Per Variation:</span>
                                                                 <small class="text-muted fw-normal" style="font-size: 12px;">{{ $product->variationCombinations->count() }} variation combinations</small>
                                                             </h6>
                                                             <div class="table-responsive mb-3 border rounded-3 shadow-sm bg-white">
                                                                 <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                                                                     <thead class="table-light text-muted border-bottom" style="font-size: 12px; letter-spacing: 0.03em; text-transform: uppercase;">
                                                                         <tr>
                                                                             <th style="min-width: 200px;">Option / Variation</th>
                                                                             <th style="width: 140px;">Unit Cost</th>
                                                                             <th style="width: 100px;">Admin Stock</th>
                                                                             <th style="width: 140px;">Your Quantity</th>
                                                                             <th class="text-end" style="min-width: 140px;">Subtotal</th>
                                                                         </tr>
                                                                     </thead>
                                                                     <tbody>
                                                                         @foreach($product->variationCombinations as $comb)
                                                                              @php
                                                                                  $unitCost = (float)(
                                                                                      ($comb->wholesale_price > 0) ? $comb->wholesale_price : 
                                                                                      (($comb->product_cost > 0) ? $comb->product_cost : 
                                                                                      (($comb->offer_price > 0) ? $comb->offer_price : ($comb->regular_price ?? 0)))
                                                                                  );
                                                                                  $optNames = method_exists($comb, 'getOptionNamesArray') ? $comb->getOptionNamesArray() : [];
                                                                                  $optLabel = !empty($optNames) ? implode(' / ', $optNames) : (is_array($comb->variation_options) ? implode(' / ', $comb->variation_options) : $comb->variation_options);
                                                                                  $combTiers = $comb->wholesaleTiers ? $comb->wholesaleTiers->map(fn($t) => ['min_quantity' => (int)$t->min_quantity, 'price' => (float)$t->price])->values() : collect();
                                                                                  $sortedTiers = $comb->wholesaleTiers ? $comb->wholesaleTiers->sortBy('min_quantity') : collect();
                                                                                  $firstTier = $sortedTiers->first();
                                                                              @endphp
                                                                              <tr class="var-row-{{ $product->id }}-{{ $comb->id }} {{ $sortedTiers->isNotEmpty() ? 'bg-light bg-opacity-25' : '' }}">
                                                                                  <td>
                                                                                      <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                                                          <span class="badge px-2.5 py-1" style="color: #4338ca; background: #eef2ff; font-weight: 700; font-size: 12px; border: 1px solid #c7d2fe; border-radius: 6px;">
                                                                                              {{ $optLabel }}
                                                                                          </span>
                                                                                      </div>

                                                                                      @if($sortedTiers->isNotEmpty())
                                                                                          <!-- Interactive Tier Pills -->
                                                                                          <div class="d-flex flex-wrap gap-1 mt-2 var-tier-badges-{{ $product->id }}-{{ $comb->id }}">
                                                                                              <span class="badge bg-light text-secondary border var-tier-pill" data-min="1" style="font-size: 10px; font-weight: 600; padding: 4px 7px; border-radius: 5px; cursor: pointer;" onclick="setVarQty({{ $product->id }}, {{ $comb->id }}, 1)" title="Base Price">
                                                                                                  1-{{ $firstTier ? $firstTier->min_quantity - 1 : 'any' }}: ৳{{ number_format($unitCost, 0) }}
                                                                                              </span>
                                                                                              @foreach($sortedTiers as $tIdx => $t)
                                                                                                  @php
                                                                                                      $nextT = $sortedTiers->values()->get($tIdx + 1);
                                                                                                      $tierRange = $t->min_quantity . ($nextT ? '-' . ($nextT->min_quantity - 1) : '+');
                                                                                                  @endphp
                                                                                                  <span class="badge bg-white text-dark border border-primary border-opacity-50 var-tier-pill shadow-xs" data-min="{{ $t->min_quantity }}" style="font-size: 10px; font-weight: 600; padding: 4px 7px; border-radius: 5px; cursor: pointer; transition: all 0.2s;" onclick="setVarQty({{ $product->id }}, {{ $comb->id }}, {{ $t->min_quantity }})" title="Click to select {{ $t->min_quantity }} units">
                                                                                                      <i class="fas fa-layer-group text-primary me-1" style="font-size: 9px;"></i>{{ $tierRange }}: <strong class="text-success">৳{{ number_format($t->price, 0) }}</strong>
                                                                                                  </span>
                                                                                              @endforeach
                                                                                          </div>
                                                                                      @endif
                                                                                  </td>
                                                                                  <td id="var_cost_{{ $product->id }}_{{ $comb->id }}">
                                                                                      <div class="fw-bold text-dark">৳{{ number_format($unitCost, 2) }}</div>
                                                                                  </td>
                                                                                  <td>
                                                                                      <span class="badge bg-light text-dark border" style="font-size: 11px;">
                                                                                          {{ $comb->stock_quantity ?? 'Unlimited' }}
                                                                                      </span>
                                                                                  </td>
                                                                                  <td>
                                                                                       <input type="number" name="quantities[{{ $product->id }}][variations][{{ $comb->id }}]" 
                                                                                              class="form-control form-control-sm font-weight-bold var-qty-input-{{ $product->id }}" 
                                                                                              data-base-cost="{{ $unitCost }}"
                                                                                              data-cost="{{ $unitCost }}"
                                                                                              data-tiers="{{ json_encode($combTiers) }}"
                                                                                              min="0" {{ ($comb->stock_quantity !== null && $comb->stock_quantity > 0) ? 'max='.$comb->stock_quantity : '' }} value="0"
                                                                                              style="max-width: 100px; border-radius: 6px;"
                                                                                              oninput="calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }})">
                                                                                   </td>
                                                                                  <td class="text-end var-subtotal-{{ $product->id }}">
                                                                                      <div class="fw-bold text-muted">৳0.00</div>
                                                                                  </td>
                                                                              </tr>
                                                                          @endforeach
                                                                     </tbody>
                                                                 </table>
                                                             </div>
                                                         @else
                                                             @php
                                                                 $unitCost = (float)(
                                                                     ($product->wholesale_price > 0) ? $product->wholesale_price : 
                                                                     (($product->product_cost > 0) ? $product->product_cost : 
                                                                     (($product->offer > 0) ? $product->offer : ($product->old_price ?? 0)))
                                                                 );
                                                                 $simpleTiers = $product->wholesaleTiers ? $product->wholesaleTiers->sortBy('min_quantity') : collect();
                                                                 $firstTier = $simpleTiers->first();
                                                             @endphp

                                                             @if($simpleTiers->isNotEmpty())
                                                                 <!-- Tiered Wholesale Pricing Breakdown -->
                                                                 <div class="mb-3 p-3 rounded-3 border" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                                                                     <div class="d-flex justify-content-between align-items-center mb-2">
                                                                         <span class="fw-bold text-dark d-flex align-items-center gap-1.5" style="font-size: 13px;">
                                                                             <i class="fas fa-layer-group text-primary"></i> Tiered Wholesale Pricing (Buy More & Save)
                                                                         </span>
                                                                         <span class="badge bg-success bg-opacity-15 text-success small fw-bold px-2 py-0.5" style="font-size: 11px;">Bulk Discounts Active</span>
                                                                     </div>
                                                                     <div class="border rounded-2 overflow-hidden bg-white">
                                                                         <table class="table table-sm table-bordered text-center align-middle mb-0 w-100" style="font-size: 12px; table-layout: fixed;">
                                                                             <thead class="table-light text-muted" style="font-size: 11px; text-transform: uppercase;">
                                                                                 <tr>
                                                                                     <th style="width: 38%;">Quantity Range</th>
                                                                                     <th style="width: 31%;">Unit Price</th>
                                                                                     <th style="width: 31%;">Savings / Unit</th>
                                                                                 </tr>
                                                                             </thead>
                                                                             <tbody>
                                                                                 <tr class="tier-row-{{ $product->id }} tier-row-base-{{ $product->id }}" data-min="1" data-price="{{ $unitCost }}" style="cursor: pointer;" onclick="document.getElementById('simple_qty_{{ $product->id }}').value=1; calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }});" title="Click to select 1 unit">
                                                                                     <td class="fw-bold text-dark">1 - {{ ($firstTier ? $firstTier->min_quantity - 1 : 'any') }} units</td>
                                                                                     <td class="fw-bold text-dark">৳{{ number_format($unitCost, 2) }}</td>
                                                                                     <td class="text-muted">-</td>
                                                                                 </tr>
                                                                                 @foreach($simpleTiers as $tIdx => $tier)
                                                                                     @php
                                                                                         $nextTier = $simpleTiers->values()->get($tIdx + 1);
                                                                                         $saving = max(0, $unitCost - $tier->price);
                                                                                     @endphp
                                                                                     <tr class="tier-row-{{ $product->id }}" data-min="{{ $tier->min_quantity }}" data-price="{{ $tier->price }}" style="cursor: pointer;" onclick="document.getElementById('simple_qty_{{ $product->id }}').value={{ $tier->min_quantity }}; calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }});" title="Click to select {{ $tier->min_quantity }} units">
                                                                                         <td class="fw-bold text-dark">{{ $tier->min_quantity }}{{ $nextTier ? ' - ' . ($nextTier->min_quantity - 1) : '+' }} units</td>
                                                                                         <td class="fw-bold text-success">৳{{ number_format($tier->price, 2) }}</td>
                                                                                         <td><span class="badge bg-success bg-opacity-10 text-success fw-bold" style="font-size: 11px;">Save ৳{{ number_format($saving, 2) }}</span></td>
                                                                                     </tr>
                                                                                 @endforeach
                                                                             </tbody>
                                                                         </table>
                                                                     </div>
                                                                 </div>
                                                             @endif

                                                             <div class="mb-3">
                                                                 <label class="form-label font-weight-bold d-flex justify-content-between align-items-center" id="unit_cost_label_{{ $product->id }}">
                                                                     <span>Stock Quantity to Purchase (Unit Cost: ৳{{ number_format($unitCost, 2) }})</span>
                                                                 </label>
                                                                 <input type="number" name="quantities[{{ $product->id }}][quantity]" id="simple_qty_{{ $product->id }}" 
                                                                        class="form-control form-control-lg font-weight-bold" 
                                                                        data-base-cost="{{ $unitCost }}"
                                                                        data-cost="{{ $unitCost }}" 
                                                                        data-tiers="{{ json_encode($simpleTiers->map(fn($t) => ['min_quantity' => (int)$t->min_quantity, 'price' => (float)$t->price])->values()) }}"
                                                                        min="1" {{ ($product->quantity !== null && $product->quantity > 0) ? 'max='.$product->quantity : '' }} value="1" required
                                                                        oninput="calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }})">
                                                             </div>
                                                         @endif

                                                        <!-- Live Total Cost Summary Box -->
                                                        <div class="p-3 rounded-3 border mt-3" style="background: #eef2ff; border-color: #c7d2fe !important;">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <span class="font-weight-bold text-dark">Total Stock Units Purchased:</span>
                                                                <span class="font-weight-bold text-dark fs-6" id="total_qty_display_{{ $product->id }}">1 units</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <span class="font-weight-bold text-dark">Total Wallet Deduction:</span>
                                                                <span class="fs-4 font-weight-extrabold text-danger" id="total_cost_display_{{ $product->id }}">৳0.00</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <span class="small text-muted font-weight-bold">Est. Remaining Wallet Balance:</span>
                                                                <span class="small font-weight-bold text-success" id="remaining_bal_display_{{ $product->id }}">৳0.00</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success font-weight-bold px-4" id="submit_copy_btn_{{ $product->id }}">
                                                            <i class="fas {{ $isCopied ? 'fa-plus-circle' : 'fa-check-circle' }} me-1"></i> {{ $isCopied ? 'Confirm & Purchase Additional Stock' : 'Confirm & Deduct Wallet Fund' }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center gap-2 justify-content-end pe-2">
                                        @php
                                            $hasAllocation = isset($productAllocations[$product->id]) || ($product->parent_product_id && isset($productAllocations[$product->parent_product_id]));
                                            $targetProductId = isset($productAllocations[$product->id]) ? $product->id : ($product->parent_product_id ?? $product->id);
                                        @endphp
                                        @if(($hasAllocation || $product->parent_product_id) && (!auth()->user()->isVendorRetailer() || auth()->user()->isConsignmentEnabled()))
                                            <button type="button"
                                                    title="Return stock & get wallet refund"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#returnModal_{{ $product->id }}"
                                                    style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; font-size:0.8rem; font-weight:700; color:#92400e; background:linear-gradient(135deg,#fef3c7,#fde68a); border:1.5px solid #f59e0b; border-radius:10px; cursor:pointer; transition:all .2s;"
                                                    onmouseover="this.style.background='linear-gradient(135deg,#fde68a,#fbbf24)'"
                                                    onmouseout="this.style.background='linear-gradient(135deg,#fef3c7,#fde68a)'">
                                                <i class="fas fa-rotate-left"></i> Return
                                            </button>

                                            <!-- Partial/Full Return Modal -->
                                            <div class="modal fade text-start" id="returnModal_{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                                        <div class="modal-header border-0 bg-light rounded-top-4">
                                                            <h5 class="modal-title font-weight-bold text-dark">
                                                                <i class="fas fa-undo text-warning me-2"></i> Return Stock & Refund
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        @php
                                                            $alloc = $productAllocations[$product->id] ?? ($product->parent_product_id ? ($productAllocations[$product->parent_product_id] ?? null) : null);
                                                            $allocTotalCost = (float)($alloc->total_cost ?? 0);
                                                            $allocQty = max(1, (int)($alloc->requested_quantity ?? ($product->quantity ?? 1)));
                                                            $unitPrice = $allocTotalCost > 0 ? ($allocTotalCost / $allocQty) : (float)($product->wholesale_price > 0 ? $product->wholesale_price : ($product->product_cost > 0 ? $product->product_cost : ($product->offer > 0 ? $product->offer : ($product->old_price ?? 0))));
                                                            $maxQty = max(1, (int)($product->quantity ?? 1));
                                                        @endphp
                                                        <form action="{{ route('vendor.products.return-allocation', $targetProductId) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body p-4">
                                                                <p class="text-muted mb-3">Return stock units of <strong>{{ $product->title }}</strong> back to Admin catalog. The calculated refund will be credited directly to your wallet balance.</p>
                                                                
                                                                <div class="mb-3">
                                                                    <label class="form-label font-weight-bold">Quantity to Return</label>
                                                                    <input type="number" 
                                                                           name="return_quantity" 
                                                                           id="return_qty_{{ $product->id }}" 
                                                                           class="form-control form-control-lg text-center font-weight-bold" 
                                                                           min="1" 
                                                                           max="{{ $maxQty }}" 
                                                                           value="{{ $maxQty }}" 
                                                                           oninput="updateRefundEstimate_{{ $product->id }}(this.value)"
                                                                           required>
                                                                    <div class="d-flex justify-content-between text-muted small mt-1">
                                                                        <span>Available stock: <strong>{{ $maxQty }} units</strong></span>
                                                                        <span>Unit Cost: <strong>৳{{ number_format($unitPrice, 2) }}</strong></span>
                                                                    </div>
                                                                </div>

                                                                <div class="p-3 rounded-3 text-center" style="background: linear-gradient(135deg, #fef3c7, #fde68a); border: 1.5px solid #f59e0b;">
                                                                    <span class="text-dark small d-block font-weight-bold text-uppercase">Estimated Wallet Refund</span>
                                                                    <span class="fs-3 font-weight-bold text-dark" id="refund_estimate_{{ $product->id }}">
                                                                        ৳{{ number_format($maxQty * $unitPrice, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-0 bg-light rounded-bottom-4">
                                                                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-warning text-dark font-weight-bold px-4">
                                                                    Confirm Return & Refund
                                                                </button>
                                                            </div>
                                                        </form>
                                                        <script>
                                                            function updateRefundEstimate_{{ $product->id }}(qty) {
                                                                let unit = {{ $unitPrice }};
                                                                let maxQ = {{ $maxQty }};
                                                                let num = parseInt(qty) || 0;
                                                                if(num > maxQ) num = maxQ;
                                                                if(num < 0) num = 0;
                                                                let total = num * unit;
                                                                document.getElementById('refund_estimate_{{ $product->id }}').innerText = '৳' + total.toFixed(2);
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @can('vendor.products.edit')
                                        <a href="{{ route('vendor.products.edit', $product) }}"
                                           title="Edit product"
                                           style="display:inline-flex; align-items:center; gap:6px; padding:7px 14px; font-size:0.8rem; font-weight:700; color:#1d4ed8; background:linear-gradient(135deg,#eff6ff,#dbeafe); border:1.5px solid #93c5fd; border-radius:10px; text-decoration:none; transition:all .2s;"
                                           onmouseover="this.style.background='linear-gradient(135deg,#dbeafe,#bfdbfe)'"
                                           onmouseout="this.style.background='linear-gradient(135deg,#eff6ff,#dbeafe)'">
                                            <i class="fas fa-pen-to-square"></i> Edit
                                        </a>
                                        @endcan

                                        @can('vendor.products.delete')
                                        <form action="{{ route('vendor.products.destroy', $product) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    title="Delete product"
                                                    style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; color:#dc2626; background:linear-gradient(135deg,#fff1f2,#fee2e2); border:1.5px solid #fca5a5; border-radius:10px; cursor:pointer; font-size:0.85rem; transition:all .2s;"
                                                    onmouseover="this.style.background='linear-gradient(135deg,#fee2e2,#fecaca)'; this.style.borderColor='#f87171';"
                                                    onmouseout="this.style.background='linear-gradient(135deg,#fff1f2,#fee2e2)'; this.style.borderColor='#fca5a5';">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-3">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Bottom Guidance Information Card -->
<div class="mb-5">
    @if(($source ?? 'my_products') === 'admin_products')
        <div class="card border-0 shadow-sm rounded-4 mt-4 p-3.5 d-flex flex-row align-items-center gap-3" style="background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%); border: 1px solid #bbf7d0 !important; border-left: 5px solid #10b981 !important;">
            <div class="bg-success bg-opacity-15 text-success rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: rgba(16, 185, 129, 0.15);">
                <i class="fas fa-store fs-5"></i>
            </div>
            <div>
                <h6 class="mb-1 fw-bold" style="color: #065f46; font-size: 0.9rem;">Parent Admin Catalog Guide</h6>
                <p class="mb-0 text-muted small" style="color: #047857 !important; line-height: 1.5;">
                    Click <strong>"Copy to My Products"</strong> (or use <strong>"Copy Info"</strong> / <strong>"Download Images"</strong>) on any item to duplicate it into your store inventory. Once copied, it becomes your product and you can customize details or sync it to Daraz.
                </p>
            </div>
        </div>
    @elseif($products->isNotEmpty())
        <div class="card border-0 shadow-sm rounded-4 mt-4 p-3.5 d-flex flex-row align-items-center gap-3" style="background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 100%); border: 1px solid #bae6fd !important; border-left: 5px solid #0284c7 !important;">
            <div class="bg-info bg-opacity-15 text-info rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: rgba(14, 165, 233, 0.15);">
                <i class="fas fa-info-circle fs-5"></i>
            </div>
            <div>
                <h6 class="mb-1 fw-bold" style="color: #0369a1; font-size: 0.9rem;">Store Inventory Note</h6>
                <p class="mb-0 text-muted small" style="color: #0284c7 !important; line-height: 1.5;">
                    Products with <strong>"Pending"</strong> status are awaiting admin approval. Once approved, they will be visible on your storefront and ready to sync with connected sales channels.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
const isConsignmentEnabled = {{ auth()->user()->vendorSettings?->is_consignment ? 'true' : 'false' }};

function showConsignmentError() {
    const msg = "You do not have permission. Consignment Partner Mode is disabled for your store.";
    if (typeof toastr !== 'undefined' && typeof toastr.error === 'function') {
        toastr.error(msg);
    } else {
        alert(msg);
    }
}

function openBulkCopyCartModal() {
    if (!isConsignmentEnabled) {
        showConsignmentError();
        return;
    }
    let checkboxes = document.querySelectorAll('.product-select-checkbox:checked');

    if (checkboxes.length === 0) return;

    let container = document.getElementById('bulkCartItemsContainer');
    if (!container) return;

    container.innerHTML = '';

    checkboxes.forEach(function(chk) {
        let pId = chk.value;
        let singleModal = document.getElementById('copyStockModal_' + pId);
        if (!singleModal) return;

        let cloneBody = singleModal.querySelector('.modal-body').cloneNode(true);

        // Remove the top wallet balance header from cloned body since cart modal has its own top header
        let topWalletHeader = cloneBody.querySelector('.bg-light.rounded-3.border.d-flex.justify-content-between');
        if (topWalletHeader) topWalletHeader.remove();

        // Remove single live summary card from cloned body
        let singleSummary = cloneBody.querySelector('[style*="background: #eef2ff"]');
        if (singleSummary) singleSummary.remove();

        // Ensure inputs inside cloned body bind to bulkCopyForm submit and calculate total
        let inputs = cloneBody.querySelectorAll('input');
        inputs.forEach(function(inp) {
            inp.setAttribute('form', 'bulkCopyForm');
            inp.addEventListener('input', calculateBulkCart);
            inp.addEventListener('change', calculateBulkCart);
        });

        let cardHtml = document.createElement('div');
        cardHtml.className = 'card border rounded-3 mb-4 shadow-sm';
        cardHtml.appendChild(cloneBody);
        container.appendChild(cardHtml);
    });

    calculateBulkCart();

    let cartModalElem = document.getElementById('bulkCopyCartModal');
    if (cartModalElem) {
        if (window.bootstrap && bootstrap.Modal) {
            let bsModal = bootstrap.Modal.getInstance(cartModalElem) || new bootstrap.Modal(cartModalElem);
            bsModal.show();
        } else if (window.jQuery) {
            jQuery('#bulkCopyCartModal').modal('show');
        }
    }
}

function getEffectiveTierPrice(baseCost, tiers, qty) {
    if (!tiers || !Array.isArray(tiers) || tiers.length === 0 || qty <= 0) {
        return { price: baseCost, tierApplied: null };
    }
    // Sort descending by min_quantity
    let sorted = [...tiers].sort((a, b) => b.min_quantity - a.min_quantity);
    for (let t of sorted) {
        if (qty >= t.min_quantity) {
            return { price: parseFloat(t.price), tierApplied: t };
        }
    }
    return { price: baseCost, tierApplied: null };
}

function calculateBulkCart() {
    let currentBalance = {{ auth()->user()->wallet_balance ?? 0 }};
    let container = document.getElementById('bulkCartItemsContainer');
    if (!container) return;

    let totalUnits = 0;
    let totalCost = 0;

    let cards = container.querySelectorAll('.card');
    cards.forEach(function(card) {
        let varInputs = card.querySelectorAll('input[name*="[variations]"]');
        if (varInputs && varInputs.length > 0) {
            varInputs.forEach(function(input) {
                let qty = parseInt(input.value) || 0;
                let baseCost = parseFloat(input.getAttribute('data-base-cost')) || parseFloat(input.getAttribute('data-cost')) || 0;
                let tiers = [];
                try {
                    tiers = JSON.parse(input.getAttribute('data-tiers') || '[]');
                } catch(e) {}

                let res = getEffectiveTierPrice(baseCost, tiers, qty);
                let effectiveCost = res.price;
                let sub = qty * effectiveCost;
                totalUnits += qty;
                totalCost += sub;

                let rowSub = input.closest('tr').querySelector('[class*="var-subtotal-"]');
                if (rowSub) {
                    if (res.tierApplied) {
                        rowSub.innerHTML = `<div>৳${sub.toFixed(2)}</div><small class="text-success fw-bold" style="font-size:10px;">৳${effectiveCost.toFixed(2)}/unit</small>`;
                    } else {
                        rowSub.innerText = '৳' + sub.toFixed(2);
                    }
                }
            });
        } else {
            let simpleInput = card.querySelector('input[name*="[quantity]"]');
            if (simpleInput) {
                let qty = parseInt(simpleInput.value) || 0;
                let baseCost = parseFloat(simpleInput.getAttribute('data-base-cost')) || parseFloat(simpleInput.getAttribute('data-cost')) || 0;
                let tiers = [];
                try {
                    tiers = JSON.parse(simpleInput.getAttribute('data-tiers') || '[]');
                } catch(e) {}

                let res = getEffectiveTierPrice(baseCost, tiers, qty);
                let effectiveCost = res.price;
                totalUnits += qty;
                totalCost += (qty * effectiveCost);

                let costLabel = card.querySelector('[id^="unit_cost_label_"]');
                if (costLabel) {
                    if (res.tierApplied) {
                        costLabel.innerHTML = `<span>Stock Quantity to Purchase (Unit Cost: <span class="text-success fw-bold">৳${effectiveCost.toFixed(2)}</span> <span class="badge bg-success ms-1">Tier ${res.tierApplied.min_quantity}+ Applied</span>)</span>`;
                    } else {
                        costLabel.innerHTML = `<span>Stock Quantity to Purchase (Unit Cost: ৳${baseCost.toFixed(2)})</span>`;
                    }
                }
            }
        }
    });

    let unitsElem = document.getElementById('bulk_cart_total_units');
    let costElem = document.getElementById('bulk_cart_total_cost');
    let remElem = document.getElementById('bulk_cart_remaining_bal');
    let submitBtn = document.getElementById('submit_bulk_copy_cart_btn');

    if (unitsElem) unitsElem.innerText = totalUnits + ' units';
    if (costElem) costElem.innerText = '৳' + totalCost.toFixed(2);

    let remaining = currentBalance - totalCost;
    if (remElem) {
        if (remaining < 0) {
            remElem.className = 'small font-weight-bold text-danger';
            remElem.innerText = '৳' + remaining.toFixed(2) + ' (Insufficient Balance)';
        } else {
            remElem.className = 'small font-weight-bold text-success';
            remElem.innerText = '৳' + remaining.toFixed(2);
        }
    }

    if (submitBtn) {
        if (totalCost > currentBalance || totalUnits <= 0) {
            submitBtn.disabled = true;
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-secondary');
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-success');
        }
    }
}

function setVarQty(productId, combId, qty) {
    let modal = document.getElementById('copyStockModal_' + productId);
    if (!modal) return;
    let input = modal.querySelector(`input[name="quantities[${productId}][variations][${combId}]"]`);
    if (input) {
        input.value = qty;
        calculateProductStockCopy(productId, {{ auth()->user()->wallet_balance ?? 0 }});
    }
}

function calculateProductStockCopy(productId, currentBalance) {
    let modal = document.getElementById('copyStockModal_' + productId);
    if (!modal) return;

    let totalQty = 0;
    let totalCost = 0;

    let varInputs = modal.querySelectorAll('.var-qty-input-' + productId);
    if (varInputs && varInputs.length > 0) {
        varInputs.forEach(function(input) {
            let qty = parseInt(input.value) || 0;
            let baseCost = parseFloat(input.getAttribute('data-base-cost')) || parseFloat(input.getAttribute('data-cost')) || 0;
            let tiers = [];
            try {
                tiers = JSON.parse(input.getAttribute('data-tiers') || '[]');
            } catch(e) {}

            let res = getEffectiveTierPrice(baseCost, tiers, qty);
            let effectiveCost = res.price;
            let sub = qty * effectiveCost;
            totalQty += qty;
            totalCost += sub;

            let unitCostCell = input.closest('tr').querySelector('[id^="var_cost_"]');
            if (unitCostCell) {
                if (res.tierApplied) {
                    unitCostCell.innerHTML = `
                        <div class="text-decoration-line-through text-muted small" style="font-size: 11px;">৳${baseCost.toFixed(2)}</div>
                        <div class="text-success fw-bold">৳${effectiveCost.toFixed(2)}</div>
                        <span class="badge bg-success bg-opacity-15 text-success" style="font-size: 9px; font-weight: 700;">${res.tierApplied.min_quantity}+ Tier</span>
                    `;
                } else {
                    unitCostCell.innerHTML = `<div class="fw-bold text-dark">৳${baseCost.toFixed(2)}</div>`;
                }
            }

            // Highlight active tier pill in the row
            let tierPills = input.closest('tr').querySelectorAll('.var-tier-pill');
            if (tierPills && tierPills.length > 0) {
                tierPills.forEach(pill => {
                    let minQ = parseInt(pill.getAttribute('data-min')) || 0;
                    if (res.tierApplied && res.tierApplied.min_quantity === minQ) {
                        pill.className = 'badge bg-success text-white border-success shadow-sm var-tier-pill';
                    } else if (!res.tierApplied && minQ === 1 && qty > 0) {
                        pill.className = 'badge bg-primary text-white border-primary var-tier-pill';
                    } else {
                        pill.className = 'badge bg-white text-dark border border-primary border-opacity-50 var-tier-pill shadow-xs';
                    }
                });
            }

            let rowSub = input.closest('tr').querySelector('.var-subtotal-' + productId);
            if (rowSub) {
                if (res.tierApplied) {
                    let saved = (baseCost - effectiveCost) * qty;
                    rowSub.innerHTML = `
                        <div class="fw-bold text-dark fs-6">৳${sub.toFixed(2)}</div>
                        <small class="text-success fw-bold d-block" style="font-size: 11px;">
                            <i class="fas fa-check-circle me-1"></i>৳${effectiveCost.toFixed(2)}/unit <span class="text-muted">(Saved ৳${saved.toFixed(2)})</span>
                        </small>
                    `;
                } else if (qty > 0) {
                    rowSub.innerHTML = `<div class="fw-bold text-dark fs-6">৳${sub.toFixed(2)}</div><small class="text-muted" style="font-size: 11px;">৳${baseCost.toFixed(2)}/unit</small>`;
                } else {
                    rowSub.innerHTML = `<div class="fw-bold text-muted">৳0.00</div>`;
                }
            }
        });
    } else {
        let simpleInput = modal.querySelector('#simple_qty_' + productId);
        if (simpleInput) {
            let qty = parseInt(simpleInput.value) || 0;
            let baseCost = parseFloat(simpleInput.getAttribute('data-base-cost')) || parseFloat(simpleInput.getAttribute('data-cost')) || 0;
            let tiers = [];
            try {
                tiers = JSON.parse(simpleInput.getAttribute('data-tiers') || '[]');
            } catch(e) {}

            let res = getEffectiveTierPrice(baseCost, tiers, qty);
            let effectiveCost = res.price;
            totalQty = qty;
            totalCost = qty * effectiveCost;

            let costLabel = modal.querySelector('#unit_cost_label_' + productId);
            if (costLabel) {
                if (res.tierApplied) {
                    costLabel.innerHTML = `<span>Stock Quantity to Purchase (Unit Cost: <span class="text-success fw-bold">৳${effectiveCost.toFixed(2)}</span> <span class="badge bg-success ms-1">Tier ${res.tierApplied.min_quantity}+ Applied</span>)</span>`;
                } else {
                    costLabel.innerHTML = `<span>Stock Quantity to Purchase (Unit Cost: ৳${baseCost.toFixed(2)})</span>`;
                }
            }

            // Highlight active tier row in modal table
            let tierRows = modal.querySelectorAll('.tier-row-' + productId);
            let baseRow = modal.querySelector('.tier-row-base-' + productId);
            if (tierRows.length > 0) {
                tierRows.forEach(row => {
                    let minQ = parseInt(row.getAttribute('data-min')) || 0;
                    if (res.tierApplied && res.tierApplied.min_quantity === minQ) {
                        row.classList.add('table-success', 'fw-bold');
                    } else {
                        row.classList.remove('table-success', 'fw-bold');
                    }
                });
                if (!res.tierApplied && baseRow) {
                    baseRow.classList.add('table-primary', 'fw-bold');
                } else if (baseRow) {
                    baseRow.classList.remove('table-primary', 'fw-bold');
                }
            }
        }
    }

    let remaining = currentBalance - totalCost;

    let qtyElem = modal.querySelector('#total_qty_display_' + productId);
    let costElem = modal.querySelector('#total_cost_display_' + productId);
    let remElem = modal.querySelector('#remaining_bal_display_' + productId);
    let submitBtn = modal.querySelector('#submit_copy_btn_' + productId);

    if (qtyElem) qtyElem.innerText = totalQty + ' units';
    if (costElem) costElem.innerText = '৳' + totalCost.toFixed(2);
    
    if (remElem) {
        if (remaining < 0) {
            remElem.className = 'small font-weight-bold text-danger';
            remElem.innerText = '৳' + remaining.toFixed(2) + ' (Insufficient Balance)';
        } else {
            remElem.className = 'small font-weight-bold text-success';
            remElem.innerText = '৳' + remaining.toFixed(2);
        }
    }

    if (submitBtn) {
        if (totalCost > currentBalance || totalQty <= 0) {
            submitBtn.disabled = true;
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-secondary');
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-success');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Intercept modal show events to prevent opening copy modals if consignment is disabled
    if (!isConsignmentEnabled) {
        document.addEventListener('show.bs.modal', function(e) {
            if (e.target && e.target.id && e.target.id.startsWith('copyStockModal_')) {
                e.preventDefault();
                showConsignmentError();
            }
        }, true);

        if (window.jQuery) {
            jQuery(document).on('show.bs.modal', '[id^="copyStockModal_"]', function(e) {
                e.preventDefault();
                showConsignmentError();
                return false;
            });
        }
    }

    let modals = document.querySelectorAll('[id^="copyStockModal_"]');
    modals.forEach(function(modal) {
        // Trigger both Bootstrap 4 and Bootstrap 5 event listeners
        modal.addEventListener('shown.bs.modal', function() {
            let parts = modal.id.split('_');
            let pId = parts[1];
            calculateProductStockCopy(pId, {{ auth()->user()->wallet_balance ?? 0 }});
        });
    });

    if (window.jQuery) {
        jQuery(document).on('shown.bs.modal', '[id^="copyStockModal_"]', function() {
            let parts = this.id.split('_');
            let pId = parts[1];
            calculateProductStockCopy(pId, {{ auth()->user()->wallet_balance ?? 0 }});
        });
    }
    // Dynamic Subcategory & Child Subcategory Fetching for Vendor Filter
    const catSelect = document.getElementById('vendor_category_id');
    const subCatSelect = document.getElementById('vendor_sub_category_id');
    const thirdCatSelect = document.getElementById('vendor_third_category_id');

    if (catSelect && subCatSelect) {
        catSelect.addEventListener('change', function() {
            const categoryId = this.value;
            subCatSelect.innerHTML = '<option value="">Loading...</option>';
            subCatSelect.disabled = true;

            if (thirdCatSelect) {
                thirdCatSelect.innerHTML = '<option value="">Select Subcategory First</option>';
                thirdCatSelect.disabled = true;
            }

            if (!categoryId) {
                subCatSelect.innerHTML = '<option value="">Select Category First</option>';
                return;
            }

            const rawUrl = '{{ route("vendor.products.subcategories", ":id") }}';
            const url = rawUrl.replace('%3Aid', categoryId).replace(':id', categoryId);

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP error ' + res.status);
                    return res.json();
                })
                .then(data => {
                    subCatSelect.innerHTML = '<option value="">All Subcategories</option>';
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            subCatSelect.appendChild(option);
                        });
                        subCatSelect.disabled = false;
                    } else {
                        subCatSelect.innerHTML = '<option value="">No Subcategories Found</option>';
                    }
                })
                .catch(err => {
                    console.error('Error fetching subcategories:', err);
                    subCatSelect.innerHTML = '<option value="">Failed to load subcategories</option>';
                });
        });
    }

    if (subCatSelect && thirdCatSelect) {
        subCatSelect.addEventListener('change', function() {
            const subCategoryId = this.value;
            thirdCatSelect.innerHTML = '<option value="">Loading...</option>';
            thirdCatSelect.disabled = true;

            if (!subCategoryId) {
                thirdCatSelect.innerHTML = '<option value="">Select Subcategory First</option>';
                return;
            }

            const rawUrl = '{{ route("vendor.products.thirdcategories", ":id") }}';
            const url = rawUrl.replace('%3Aid', subCategoryId).replace(':id', subCategoryId);

            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(res => {
                    if (!res.ok) throw new Error('HTTP error ' + res.status);
                    return res.json();
                })
                .then(data => {
                    thirdCatSelect.innerHTML = '<option value="">All Child Subcategories</option>';
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item.name;
                            thirdCatSelect.appendChild(option);
                        });
                        thirdCatSelect.disabled = false;
                    } else {
                        thirdCatSelect.innerHTML = '<option value="">No Child Subcategories Found</option>';
                    }
                })
                .catch(err => {
                    console.error('Error fetching child subcategories:', err);
                    thirdCatSelect.innerHTML = '<option value="">Failed to load child subcategories</option>';
                });
        });
    }

    // Checkbox & Bulk Selection Logic
    const selectAllHeaderBtn = document.getElementById('select_all_header_checkbox');
    const selectAllTableBtns = document.querySelectorAll('.select-all-products');
    const productCheckboxes = document.querySelectorAll('.product-select-checkbox');
    const selectedBadge = document.getElementById('selected_count_badge');
    const bulkCopyBtn = document.getElementById('bulk_copy_btn');

    function updateBulkCopyUI() {
        const checked = document.querySelectorAll('.product-select-checkbox:checked');
        const count = checked.length;
        if (selectedBadge) selectedBadge.textContent = count + ' Selected';
        if (bulkCopyBtn) bulkCopyBtn.disabled = (count === 0);
        
        const enabledCheckboxes = document.querySelectorAll('.product-select-checkbox');
        if (selectAllHeaderBtn && enabledCheckboxes.length > 0) {
            selectAllHeaderBtn.checked = (checked.length === enabledCheckboxes.length);
        }
        selectAllTableBtns.forEach(btn => {
            if (enabledCheckboxes.length > 0) {
                btn.checked = (checked.length === enabledCheckboxes.length);
            }
        });
    }

    if (selectAllHeaderBtn) {
        selectAllHeaderBtn.addEventListener('change', function() {
            productCheckboxes.forEach(cb => {
                if (!cb.disabled) cb.checked = this.checked;
            });
            updateBulkCopyUI();
        });
    }

    selectAllTableBtns.forEach(tableBtn => {
        tableBtn.addEventListener('change', function() {
            productCheckboxes.forEach(cb => {
                if (!cb.disabled) cb.checked = this.checked;
            });
            updateBulkCopyUI();
        });
    });

    productCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkCopyUI);
    });

    // Copy Product Content Helper
    window.copyProductContentFromBtn = function(btn) {
        try {
            let data = JSON.parse(btn.getAttribute('data-product'));
            window.copyProductContent(data);
        } catch (e) {
            console.error("Failed parsing product data", e);
        }
    };

    window.copyProductContent = function(productData) {
        // Helper to convert HTML to clean plaintext with formatting (newlines for blocks, tabs for table cells)
        function cleanHtml(html) {
            if (!html) return '';
            let temp = document.createElement("div");
            temp.innerHTML = html;
            
            // Format table cells so they don't merge together in plaintext
            temp.querySelectorAll('td, th').forEach(cell => {
                cell.after(' \t '); // Tab separator for columns
            });
            
            // Replace <br> tags with a newline
            temp.querySelectorAll('br').forEach(br => br.replaceWith('\n'));
            
            // Append newlines only for rows, or block tags outside of table cells
            temp.querySelectorAll('p, div, li, h1, h2, h3, h4, h5, h6, tr, ul, ol').forEach(el => {
                if (el.tagName === 'TR') {
                    el.after('\n');
                    return;
                }
                if (!el.closest('td') && !el.closest('th')) {
                    el.after('\n');
                }
            });
            
            // Retrieve text content
            let text = temp.textContent || temp.innerText || "";
            // Replace multiple consecutive newlines with max 2 newlines for clean output
            return text.replace(/\n{3,}/g, '\n\n').trim();
        }

        // Helper to convert relative URLs to absolute URLs
        function makeAbsoluteUrl(url) {
            if (!url) return '';
            if (url.startsWith('/')) {
                return window.location.origin + url;
            }
            return url;
        }

        let mainImg = makeAbsoluteUrl(productData.thumb_image);
        let gallery = productData.gallery_images || [];

        // 1. Build Plain Text version
        let textToCopy = `Title: ${productData.title}\n`;
        if (productData.product_url) {
            textToCopy += `Product Link: ${productData.product_url}\n`;
        }
        if (productData.sku) {
            textToCopy += `SKU: ${productData.sku}\n`;
        }
        if (productData.category && productData.category !== 'N/A') {
            textToCopy += `Category: ${productData.category}\n`;
        }
        if (productData.brand && productData.brand !== 'N/A') {
            textToCopy += `Brand: ${productData.brand}\n`;
        }
        if (productData.stock_status) {
            let stockQty = productData.quantity !== null && productData.quantity !== undefined ? ` (${productData.quantity} units)` : '';
            textToCopy += `Stock Status: ${productData.stock_status}${stockQty}\n`;
        }
        if (productData.old_price && parseFloat(productData.old_price) > 0) {
            textToCopy += `Regular Price: ৳${parseFloat(productData.old_price).toFixed(2)}\n`;
        }
        if (productData.offer && parseFloat(productData.offer) > 0) {
            textToCopy += `Offer Price: ৳${parseFloat(productData.offer).toFixed(2)}\n`;
        }
        if (productData.reseller_price && parseFloat(productData.reseller_price) > 0) {
            textToCopy += `Reseller Price: ৳${parseFloat(productData.reseller_price).toFixed(2)}\n`;
        }
        if (productData.variations && productData.variations.length > 0) {
            textToCopy += `Variations / Options:\n` + productData.variations.map(v => ` - ${v}`).join('\n') + `\n`;
        }
        if (mainImg) {
            textToCopy += `\nMain Image: ${mainImg}\n`;
        }
        if (gallery.length > 0) {
            textToCopy += `Gallery Images:\n` + gallery.map(img => ` - ${makeAbsoluteUrl(img)}`).join('\n') + `\n`;
        }
        
        let desc = cleanHtml(productData.description || '');
        let shortDesc = cleanHtml(productData.short_description || '');
        if (shortDesc) {
            desc = shortDesc + (desc ? '\n\n' + desc : '');
        }
        textToCopy += `\nDescription:\n${desc}\n`;

        // 2. Build Rich HTML version for copy
        let htmlToCopy = `<h3 style="margin-bottom:5px;">${productData.title}</h3>`;
        if (productData.product_url) {
            htmlToCopy += `<p><strong>Product Link:</strong> <a href="${productData.product_url}">${productData.product_url}</a></p>`;
        }
        if (productData.sku) {
            htmlToCopy += `<p><strong>SKU:</strong> ${productData.sku}</p>`;
        }
        if (productData.category && productData.category !== 'N/A') {
            htmlToCopy += `<p><strong>Category:</strong> ${productData.category}</p>`;
        }
        if (productData.brand && productData.brand !== 'N/A') {
            htmlToCopy += `<p><strong>Brand:</strong> ${productData.brand}</p>`;
        }
        if (productData.stock_status) {
            let stockQty = productData.quantity !== null && productData.quantity !== undefined ? ` (${productData.quantity} units)` : '';
            htmlToCopy += `<p><strong>Stock Status:</strong> ${productData.stock_status}${stockQty}</p>`;
        }
        if (productData.old_price && parseFloat(productData.old_price) > 0) {
            htmlToCopy += `<p><strong>Regular Price:</strong> ৳${parseFloat(productData.old_price).toFixed(2)}</p>`;
        }
        if (productData.offer && parseFloat(productData.offer) > 0) {
            htmlToCopy += `<p><strong>Offer Price:</strong> ৳${parseFloat(productData.offer).toFixed(2)}</p>`;
        }
        if (productData.reseller_price && parseFloat(productData.reseller_price) > 0) {
            htmlToCopy += `<p><strong>Reseller Price:</strong> ৳${parseFloat(productData.reseller_price).toFixed(2)}</p>`;
        }
        if (productData.variations && productData.variations.length > 0) {
            htmlToCopy += `<p><strong>Variations / Options:</strong></p><ul>` + productData.variations.map(v => `<li>${v}</li>`).join('') + `</ul>`;
        }
        if (mainImg) {
            htmlToCopy += `<p><strong>Main Image:</strong> <a href="${mainImg}">${mainImg}</a></p>`;
        }
        if (gallery.length > 0) {
            htmlToCopy += `<p><strong>Gallery Images:</strong></p><ul>` + gallery.map(img => `<li><a href="${makeAbsoluteUrl(img)}">${makeAbsoluteUrl(img)}</a></li>`).join('') + `</ul>`;
        }
        
        let descHtml = productData.description || '';
        let shortDescHtml = productData.short_description || '';
        if (shortDescHtml) {
            descHtml = shortDescHtml + '<br><br>' + descHtml;
        }
        htmlToCopy += `<div style="margin-top: 15px;"><strong>Description:</strong><br>${descHtml}</div>`;

        // 3. Write to Clipboard using modern clipboard items if supported
        if (navigator.clipboard && window.ClipboardItem) {
            const blobPlain = new Blob([textToCopy], { type: 'text/plain' });
            const blobHtml = new Blob([htmlToCopy], { type: 'text/html' });
            
            const clipboardData = [new ClipboardItem({
                'text/plain': blobPlain,
                'text/html': blobHtml
            })];
            
            navigator.clipboard.write(clipboardData).then(() => {
                alert('Product details successfully copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy rich text: ', err);
                fallbackCopyToClipboard(textToCopy);
            });
        } else {
            fallbackCopyToClipboard(textToCopy);
        }
        
        function fallbackCopyToClipboard(text) {
            let textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                alert('Product details successfully copied to clipboard!');
            } catch (err) {
                alert('Could not copy product details.');
            }
            document.body.removeChild(textArea);
        }
    };

    // Download Product Images Helper
    window.downloadProductImages = function(productId, title, thumbImage, galleryImages) {
        let urls = [];
        if (thumbImage && !thumbImage.includes('no-image') && !thumbImage.endsWith('/')) {
            urls.push(thumbImage);
        }
        if (Array.isArray(galleryImages)) {
            galleryImages.forEach(url => {
                if (url && !urls.includes(url) && !url.endsWith('/')) {
                    urls.push(url);
                }
            });
        }

        if (urls.length === 0) {
            alert('No valid images available for download.');
            return;
        }

        let delay = 0;
        urls.forEach((url, index) => {
            setTimeout(() => {
                let a = document.createElement('a');
                a.href = url;
                // Extract filename
                let filename = url.split('/').pop() || `${title.replace(/\s+/g, '_')}_image_${index + 1}`;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }, delay);
            delay += 400; // Delay to prevent browser blocking multiple downloads
        });
    };
});
</script>
@endpush
