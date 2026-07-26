@extends('vendor.layouts.app')

@section('title', 'My Products')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-box"></i> {{ ($source ?? 'my_products') === 'admin_products' ? 'Parent Admin Catalog' : 'My Products' }}</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add New Product
        </a>
    </div>
</div>

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show mb-4 shadow-sm border-warning" role="alert">
        <i class="fas fa-clock me-2"></i> <strong>Pending Admin Approval:</strong> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 shadow-sm border-success" role="alert">
        <i class="fas fa-check-circle me-2"></i> <strong>Success:</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm border-danger" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Source Tabs (My Products vs Parent Admin Catalog) -->
@if($canAccessAdminProducts ?? false)
<div class="mb-3">
    <ul class="nav nav-tabs border-bottom-0">
        <li class="nav-item">
            <a class="nav-link fw-bold px-4 py-2 {{ ($source ?? 'my_products') === 'my_products' ? 'active bg-white text-primary border border-bottom-0' : 'text-secondary' }}" 
               href="{{ route('vendor.products.index', ['source' => 'my_products']) }}">
                <i class="fas fa-boxes me-1"></i> My Products
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link fw-bold px-4 py-2 {{ ($source ?? 'my_products') === 'admin_products' ? 'active bg-white text-success border border-bottom-0' : 'text-secondary' }}" 
               href="{{ route('vendor.products.index', ['source' => 'admin_products']) }}">
                <i class="fas fa-store me-1"></i> Parent Admin Catalog
                <span class="badge bg-success ms-1">Shared</span>
            </a>
        </li>
    </ul>
</div>
@endif

<!-- Top Filter Panel -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-body">
        <form method="GET" action="{{ route('vendor.products.index') }}" id="vendorProductFilterForm">
            <input type="hidden" name="source" value="{{ $source ?? 'my_products' }}">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label for="vendor_search" class="form-label small fw-bold mb-1">Search Product</label>
                    <input type="text" name="search" id="vendor_search" class="form-select-sm form-control" placeholder="Search by name..." value="{{ request('search') }}">
                </div>

                @if(($source ?? 'my_products') === 'my_products')
                <div class="col-md-2">
                    <label for="vendor_status" class="form-label small fw-bold mb-1">Status</label>
                    <select name="status" id="vendor_status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                @endif

                <div class="col-md-1 text-end mt-4">
                    <input type="hidden" name="view_mode" id="vendor_view_mode_input" value="{{ request('view_mode', 'table') }}">
                    <div class="btn-group btn-group-sm w-100" role="group">
                        <a href="{{ route('vendor.products.index', array_merge(request()->except(['view_mode']), ['view_mode' => 'table', 'source' => $source ?? 'my_products'])) }}" 
                           class="btn {{ request('view_mode', 'table') === 'table' ? 'btn-primary' : 'btn-outline-secondary' }}" title="Table View">
                            <i class="fas fa-list"></i>
                        </a>
                        <a href="{{ route('vendor.products.index', array_merge(request()->except(['view_mode']), ['view_mode' => 'grouped', 'source' => $source ?? 'my_products'])) }}" 
                           class="btn {{ request('view_mode') === 'grouped' ? 'btn-primary' : 'btn-outline-secondary' }}" title="Grouped Category View">
                            <i class="fas fa-layer-group"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('vendor.products.index', ['source' => $source ?? 'my_products']) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-undo me-1"></i> Reset Filters
                </a>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-filter me-1"></i> Apply Filters
                </button>
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
                                                    @if($product->thumb_image)
                                                        <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $product->title }}</strong></td>
                                                <td>৳{{ number_format($product->price ?? $product->old_price, 2) }}</td>
                                                <td>{{ $product->quantity ?? 0 }}</td>
                                                <td>
                                                    @if(($source ?? 'my_products') === 'admin_products')
                                                        @if(in_array($product->title, $copiedProductTitles))
                                                            <button class="btn btn-sm btn-outline-secondary" disabled>Already Copied</button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#copyStockModal_{{ $product->id }}">
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
<div class="card">
    <div class="card-body">
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
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            @if(($source ?? 'my_products') === 'my_products')
                                <th>Commission</th>
                            @endif
                            <th>Stock</th>
                            @if(($source ?? 'my_products') === 'my_products')
                                <th>Status</th>
                            @endif
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->thumb_image)
                                    <img src="{{ asset('storage/' . $product->thumb_image) }}" 
                                         alt="{{ $product->title }}"
                                         class="rounded"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $product->title }}</strong><br>
                                @php
                                    $catParts = [];
                                    if ($product->category) {
                                        $catParts[] = $product->category->name;
                                    }
                                    if ($product->subCategory) {
                                        $catParts[] = $product->subCategory->name;
                                    }
                                @endphp
                                <small class="text-muted">{{ count($catParts) > 0 ? implode(' > ', $catParts) : 'N/A' }}</small>
                            </td>
                            <td>
                                @php
                                    $displayPrice = '৳0.00';
                                    $displayOldPrice = null;

                                    if ($product->product_type === 'variable' && $product->relationLoaded('variationCombinations') && $product->variationCombinations->isNotEmpty()) {
                                        $prices = [];
                                        $regularPrices = [];
                                        foreach ($product->variationCombinations as $comb) {
                                            $p = $comb->offer_price ?? $comb->regular_price ?? 0;
                                            $reg = $comb->regular_price ?? 0;
                                            if ($p > 0) $prices[] = (float)$p;
                                            if ($reg > 0) $regularPrices[] = (float)$reg;
                                        }
                                        if (!empty($prices)) {
                                            $minP = min($prices);
                                            $maxP = max($prices);
                                            if ($minP === $maxP) {
                                                $displayPrice = '৳' . number_format($minP, 2);
                                            } else {
                                                $displayPrice = '৳' . number_format($minP, 2) . ' - ৳' . number_format($maxP, 2);
                                            }
                                        } elseif (!empty($regularPrices)) {
                                            $minP = min($regularPrices);
                                            $maxP = max($regularPrices);
                                            if ($minP === $maxP) {
                                                $displayPrice = '৳' . number_format($minP, 2);
                                            } else {
                                                $displayPrice = '৳' . number_format($minP, 2) . ' - ৳' . number_format($maxP, 2);
                                            }
                                        }
                                    } else {
                                        $offerPrice = (float)($product->offer ?? 0);
                                        $oldPrice = (float)($product->old_price ?? 0);

                                        if ($offerPrice > 0) {
                                            $displayPrice = '৳' . number_format($offerPrice, 2);
                                            if ($oldPrice > $offerPrice) {
                                                $displayOldPrice = '৳' . number_format($oldPrice, 2);
                                            }
                                        } elseif ($oldPrice > 0) {
                                            $displayPrice = '৳' . number_format($oldPrice, 2);
                                        }
                                    }
                                @endphp

                                @if($displayOldPrice)
                                    <span class="text-decoration-line-through text-muted" style="font-size: 0.8rem;">{{ $displayOldPrice }}</span><br>
                                @endif
                                <strong class="text-dark">{{ $displayPrice }}</strong>
                            </td>
                            @if(($source ?? 'my_products') === 'my_products')
                            <td>
                                @if($product->vendor_commission_rate)
                                    <span class="badge bg-info">{{ $product->vendor_commission_rate }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                @if($product->vendor_proposed_commission && $product->vendor_proposed_commission != $product->vendor_commission_rate)
                                    <br><small class="text-warning">Proposed: {{ $product->vendor_proposed_commission }}%</small>
                                @endif
                            </td>
                            @endif
                            <td>
                                @if($product->manage_stock)
                                    @if($product->quantity > 0)
                                        <span class="text-success fw-bold">{{ $product->quantity }}</span>
                                    @else
                                        <span class="text-danger fw-bold">Out of Stock</span>
                                    @endif
                                @else
                                    <span class="text-muted">In Stock</span>
                                @endif
                            </td>
                            @if(($source ?? 'my_products') === 'my_products')
                            <td>
                                @if($product->approval_status === 'approved')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </span>
                                @elseif($product->approval_status === 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle"></i> Rejected
                                    </span>
                                @endif
                                @if(!$product->status)
                                    <br><small class="text-muted">Inactive</small>
                                @endif
                            </td>
                            @endif
                            <td>
                                <small>{{ $product->created_at ? $product->created_at->format('d M Y') : 'N/A' }}</small>
                            </td>
                            <td class="text-end">
                                @if(($source ?? 'my_products') === 'admin_products')
                                    @if(in_array($product->id, $allocatedProductIds ?? []) || in_array($product->title, $copiedProductTitles ?? []))
                                        <button disabled class="btn btn-outline-secondary btn-sm font-weight-bold d-inline-flex align-items-center gap-1" title="You have already copied this product to your store">
                                            <i class="fas fa-check-circle text-success"></i> Already Copied
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success btn-sm font-weight-bold d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#copyStockModal_{{ $product->id }}" onclick="calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }})">
                                            <i class="fas fa-copy"></i> Copy to My Products
                                        </button>

                                        <!-- Modal for Stock Allocation & Purchase -->
                                        <div class="modal fade text-start" id="copyStockModal_{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered {{ $product->product_type === 'variable' ? 'modal-lg' : '' }}">
                                                <div class="modal-content rounded-4 border-0 shadow">
                                                    <form action="{{ route('vendor.products.copy', $product) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title font-weight-bold"><i class="fas fa-boxes me-2"></i> Stock Purchase & Copy: {{ $product->title }}</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <!-- Wallet Balance Header -->
                                                            <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center mb-4">
                                                                <div>
                                                                    <small class="text-muted font-weight-bold d-block">YOUR WALLET BALANCE</small>
                                                                    <span class="fs-5 font-weight-bold text-primary">৳{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</span>
                                                                </div>
                                                                <a href="{{ route('vendor.wallet.index') }}" class="btn btn-sm btn-outline-primary font-weight-bold" target="_blank">
                                                                    <i class="fas fa-plus-circle me-1"></i> Recharge Wallet
                                                                </a>
                                                            </div>

                                                            @if($product->product_type === 'variable' && $product->relationLoaded('variationCombinations') && $product->variationCombinations->isNotEmpty())
                                                                <h6 class="font-weight-bold text-dark mb-2">Select Stock Quantity Per Variation:</h6>
                                                                <div class="table-responsive mb-3 border rounded">
                                                                    <table class="table table-sm align-middle mb-0">
                                                                        <thead class="table-light">
                                                                            <tr>
                                                                                <th>Option / Variation</th>
                                                                                <th>Unit Cost</th>
                                                                                <th>Admin Stock</th>
                                                                                <th width="130">Your Quantity</th>
                                                                                <th class="text-end">Subtotal</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($product->variationCombinations as $comb)
                                                                                @php
                                                                                    $unitCost = (float)($comb->product_cost > 0 ? $comb->product_cost : ($comb->offer_price > 0 ? $comb->offer_price : $comb->regular_price ?? 0));
                                                                                    $optNames = method_exists($comb, 'getOptionNamesArray') ? $comb->getOptionNamesArray() : [];
                                                                                    $optLabel = !empty($optNames) ? implode(' / ', $optNames) : (is_array($comb->variation_options) ? implode(' / ', $comb->variation_options) : $comb->variation_options);
                                                                                @endphp
                                                                                <tr>
                                                                                    <td><span class="badge" style="color: #4f46e5; background: #eef2ff; font-weight: 700;">{{ $optLabel }}</span></td>
                                                                                    <td class="font-weight-bold">৳{{ number_format($unitCost, 2) }}</td>
                                                                                    <td class="text-muted small">{{ $comb->stock_quantity ?? 'Unlimited' }}</td>
                                                                                    <td>
                                                                                        <input type="number" name="combinations[{{ $comb->id }}][quantity]" 
                                                                                               class="form-control form-control-sm var-qty-input-{{ $product->id }}" 
                                                                                               data-cost="{{ $unitCost }}"
                                                                                               min="0" max="{{ $comb->stock_quantity ?? 99999 }}" value="1"
                                                                                               oninput="calculateProductStockCopy({{ $product->id }}, {{ auth()->user()->wallet_balance ?? 0 }})">
                                                                                    </td>
                                                                                    <td class="text-end font-weight-bold text-dark var-subtotal-{{ $product->id }}">৳{{ number_format($unitCost, 2) }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                @php
                                                                    $unitCost = (float)($product->product_cost > 0 ? $product->product_cost : ($product->offer > 0 ? $product->offer : $product->old_price ?? 0));
                                                                @endphp
                                                                <div class="mb-3">
                                                                    <label class="form-label font-weight-bold">Stock Quantity to Purchase (Unit Cost: ৳{{ number_format($unitCost, 2) }})</label>
                                                                    <input type="number" name="quantity" id="simple_qty_{{ $product->id }}" 
                                                                           class="form-control form-control-lg font-weight-bold" 
                                                                           data-cost="{{ $unitCost }}" 
                                                                           min="1" max="{{ $product->quantity ?? 99999 }}" value="1" required
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
                                                                <i class="fas fa-check-circle me-1"></i> Confirm & Deduct Wallet Fund
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('vendor.products.edit', $product) }}" 
                                           class="btn btn-outline-primary"
                                           title="Edit">
                                            <i class="fas fa-pencil"></i>
                                        </a>
                                        <form action="{{ route('vendor.products.destroy', $product) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Info Alert -->
@if(($source ?? 'my_products') === 'admin_products')
<div class="alert alert-success mt-4">
    <i class="fas fa-info-circle me-1"></i>
    <strong>Parent Admin Catalog:</strong> Click <strong>"Copy to My Products"</strong> on any item to duplicate it into your store. Once copied, it becomes your product and you can edit details or sync it to Daraz.
</div>
@elseif($products->isNotEmpty())
<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle me-1"></i>
    <strong>Note:</strong> Products with "Pending" status are awaiting admin approval. Once approved, they will be visible on your store and syncable with Daraz.
</div>
@endif
@endsection

@push('scripts')
<script>
function calculateProductStockCopy(productId, currentBalance) {
    let modal = document.getElementById('copyStockModal_' + productId);
    if (!modal) return;

    let totalQty = 0;
    let totalCost = 0;

    let varInputs = modal.querySelectorAll('.var-qty-input-' + productId);
    if (varInputs && varInputs.length > 0) {
        varInputs.forEach(function(input) {
            let qty = parseInt(input.value) || 0;
            let cost = parseFloat(input.getAttribute('data-cost')) || 0;
            let sub = qty * cost;
            totalQty += qty;
            totalCost += sub;

            let rowSub = input.closest('tr').querySelector('.var-subtotal-' + productId);
            if (rowSub) {
                rowSub.innerText = '৳' + sub.toFixed(2);
            }
        });
    } else {
        let simpleInput = modal.querySelector('#simple_qty_' + productId);
        if (simpleInput) {
            let qty = parseInt(simpleInput.value) || 0;
            let cost = parseFloat(simpleInput.getAttribute('data-cost')) || 0;
            totalQty = qty;
            totalCost = qty * cost;
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
    // Dynamic Subcategory Fetching for Vendor Filter
    const catSelect = document.getElementById('vendor_category_id');
    const subCatSelect = document.getElementById('vendor_sub_category_id');

    if (catSelect && subCatSelect) {
        catSelect.addEventListener('change', function() {
            const categoryId = this.value;
            subCatSelect.innerHTML = '<option value="">Loading...</option>';
            subCatSelect.disabled = true;

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
});
</script>
@endpush
