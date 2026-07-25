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

<!-- Filter Tabs for My Products -->
@if(($source ?? 'my_products') === 'my_products')
<div class="card mb-4">
    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index', ['source' => 'my_products']) }}">
                    All Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'approved' ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index', ['source' => 'my_products', 'status' => 'approved']) }}">
                    Approved
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index', ['source' => 'my_products', 'status' => 'pending']) }}">
                    Pending
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'rejected' ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index', ['source' => 'my_products', 'status' => 'rejected']) }}">
                    Rejected
                </a>
            </li>
        </ul>
    </div>
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
                                    @if(in_array($product->title, $copiedProductTitles ?? []))
                                        <button disabled class="btn btn-outline-secondary btn-sm font-weight-bold d-inline-flex align-items-center gap-1" title="You have already copied this product to your store">
                                            <i class="fas fa-check-circle text-success"></i> Already Copied
                                        </button>
                                    @else
                                        <form action="{{ route('vendor.products.copy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm font-weight-bold d-inline-flex align-items-center gap-1">
                                                <i class="fas fa-copy"></i> Copy to My Products
                                            </button>
                                        </form>
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
