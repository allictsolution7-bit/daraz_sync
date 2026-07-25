@extends('layouts.master')

@section('title', 'Partner Product Review - ' . $product->title)

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    
    .pp-container {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #0f172a;
        padding-bottom: 40px;
    }
    
    /* Top Header Styling */
    .pp-header {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05);
    }
    .pp-breadcrumb {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
    }
    .pp-breadcrumb a {
        color: #4f46e5;
        text-decoration: none;
        transition: all 0.2s;
    }
    .pp-breadcrumb a:hover {
        color: #3730a3;
    }
    
    /* Card Styles */
    .pp-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.04);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .pp-card-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 18px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .pp-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pp-card-title i {
        color: #4f46e5;
        font-size: 16px;
    }
    .pp-card-body {
        padding: 24px;
    }
    
    /* Status Badges */
    .pp-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .pp-status-pending {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .pp-status-approved {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .pp-status-rejected {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
    
    /* Image Box */
    .pp-img-wrapper {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        overflow: hidden;
        position: relative;
        text-align: center;
    }
    .pp-img-wrapper img {
        width: 100%;
        max-height: 240px;
        object-fit: contain;
        padding: 12px;
    }
    
    /* Info Meta Items */
    .pp-meta-item {
        margin-bottom: 16px;
    }
    .pp-meta-label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #475569;
        letter-spacing: 0.6px;
        margin-bottom: 5px;
    }
    .pp-meta-value {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
    }
    
    /* Table Styling */
    .pp-table {
        margin: 0;
    }
    .pp-table th {
        background: #f8fafc;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #475569;
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    .pp-table td {
        padding: 14px 16px;
        font-size: 13px;
        color: #1e293b;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    
    /* Commission Box */
    .pp-comm-box {
        background: #eef2ff;
        border-radius: 14px;
        border: 1px solid #c7d2fe;
        padding: 16px;
        margin-bottom: 20px;
    }
    
    /* Action Buttons */
    .btn-pp-approve {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        transition: all 0.2s ease;
    }
    .btn-pp-approve:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
    }
    
    .btn-pp-reject {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff !important;
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        transition: all 0.2s ease;
    }
    .btn-pp-reject:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35);
    }

    .pp-range-bar {
        height: 6px;
        background: #cbd5e1;
        border-radius: 10px;
        overflow: hidden;
        margin-top: 8px;
    }
    .pp-range-fill {
        height: 100%;
        background: linear-gradient(90deg, #4f46e5 0%, #10b981 100%);
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="pp-container">
    <!-- Top Header -->
    <div class="pp-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <div class="pp-breadcrumb mb-1">
                <a href="{{ route('admin.vendor-products.index') }}"><i class="fas fa-arrow-left me-1"></i> Partner Catalog</a>
                <span class="mx-2">/</span>
                <span style="color: #64748b;">Product Review</span>
            </div>
            <h4 class="mb-0 font-weight-bold text-dark">{{ $product->title }}</h4>
        </div>
        <div class="d-flex align-items-center gap-3">
            @if($product->approval_status === 'approved')
                <span class="pp-status-pill pp-status-approved"><i class="fas fa-check-circle"></i> Approved Product</span>
            @elseif($product->approval_status === 'pending')
                <span class="pp-status-pill pp-status-pending"><i class="fas fa-clock"></i> Pending Review</span>
            @else
                <span class="pp-status-pill pp-status-rejected"><i class="fas fa-times-circle"></i> Rejected Product</span>
            @endif
        </div>
    </div>

    @if(isset($stockPurchaseTrx) && $stockPurchaseTrx)
        <div class="alert border-0 shadow-sm rounded-4 mb-4 p-3" style="background: #fffbeb; border-left: 4px solid #f59e0b !important;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h6 class="font-weight-bold text-dark mb-1"><i class="fas fa-boxes text-warning me-2"></i> Parent Stock Purchase & Copy Request</h6>
                    <p class="mb-0 text-muted small">
                        Vendor <strong>{{ $product->vendor->name ?? 'Partner' }}</strong> requested to copy this product with <strong>{{ $product->quantity ?? 0 }} stock units</strong> for <strong>৳{{ number_format($stockPurchaseTrx->amount, 2) }}</strong>. Funds are held in escrow and admin stock will transfer upon approval.
                    </p>
                </div>
                <div>
                    <span class="badge bg-warning text-dark font-weight-bold px-3 py-2 rounded-pill" style="font-size: 13px;">
                        <i class="fas fa-wallet me-1"></i> Funds Held: ৳{{ number_format($stockPurchaseTrx->amount, 2) }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> <strong>Success:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> <strong>Notice:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Main Column -->
        <div class="col-lg-8">
            <!-- Product Identity Card -->
            <div class="pp-card">
                <div class="pp-card-header">
                    <h5 class="pp-card-title"><i class="fas fa-box-open"></i> Product Overview & Identity</h5>
                    <span class="badge bg-light text-dark font-weight-bold px-3 py-2 rounded-pill border">ID: #{{ $product->id }}</span>
                </div>
                <div class="pp-card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="pp-img-wrapper">
                                @if($product->thumb_image)
                                    <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}">
                                @else
                                    <div class="py-5">
                                        <i class="fas fa-image text-muted fs-1 opacity-50"></i>
                                        <p class="small text-muted mb-0 mt-2">No thumbnail uploaded</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="pp-meta-item">
                                        <div class="pp-meta-label">Category Path</div>
                                        <div class="pp-meta-value" style="color: #4f46e5;">
                                            {{ $product->category->name ?? 'Uncategorized' }}
                                            @if($product->subCategory)
                                                <i class="fas fa-chevron-right text-muted small mx-1"></i> {{ $product->subCategory->name }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="pp-meta-item">
                                        <div class="pp-meta-label">Product Structure</div>
                                        <div class="pp-meta-value">
                                            <span class="badge bg-primary bg-opacity-10 text-primary font-weight-bold px-3 py-1 rounded-pill">
                                                {{ ucfirst($product->product_type ?? 'simple') }} Product
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="pp-meta-item">
                                        <div class="pp-meta-label">Partner Vendor</div>
                                        <div class="pp-meta-value d-flex align-items-center gap-2">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold" style="width: 34px; height: 34px; font-size: 13px;">
                                                {{ strtoupper(substr($product->vendor->name ?? 'V', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div style="color: #0f172a; font-weight: 700;">{{ $product->vendor->name ?? 'Vendor' }}</div>
                                                <small style="color: #64748b; font-weight: 500;">{{ $product->vendor->email ?? '' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="pp-meta-item">
                                        <div class="pp-meta-label">Submitted On</div>
                                        <div class="pp-meta-value" style="color: #0f172a;">
                                            {{ $product->created_at ? $product->created_at->format('d M Y, h:i A') : 'N/A' }}
                                            <small class="d-block font-weight-normal" style="color: #64748b;">{{ $product->created_at ? $product->created_at->diffForHumans() : '' }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($product->short_description)
                        <div class="mt-4 pt-3 border-top">
                            <div class="pp-meta-label">Summary / Short Description</div>
                            <p class="p-3 rounded-3 small mb-0" style="background: #f8fafc; color: #334155;">{{ $product->short_description }}</p>
                        </div>
                    @endif

                    @if($product->description)
                        <div class="mt-3">
                            <div class="pp-meta-label">Full Product Description</div>
                            <div class="p-3 rounded-3 small" style="background: #f8fafc; color: #334155;">{!! $product->description !!}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pricing & Variations Card -->
            <div class="pp-card">
                <div class="pp-card-header">
                    <h5 class="pp-card-title"><i class="fas fa-tags"></i> Pricing & Stock Inventory</h5>
                </div>
                <div class="pp-card-body p-0">
                    @if($product->product_type === 'variable' && $product->relationLoaded('variationCombinations') && $product->variationCombinations->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table pp-table align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Combination SKU</th>
                                        <th>Attributes & Options</th>
                                        <th>Regular Price</th>
                                        <th>Offer Price</th>
                                        <th class="pe-4 text-end">Available Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->variationCombinations as $comb)
                                        <tr>
                                            <td class="ps-4">
                                                <code class="bg-light text-dark px-2 py-1 rounded font-weight-bold border">{{ $comb->sku ?? 'NO-SKU' }}</code>
                                            </td>
                                            <td>
                                                @php
                                                    $optNames = method_exists($comb, 'getOptionNamesArray') ? $comb->getOptionNamesArray() : [];
                                                @endphp
                                                @if(!empty($optNames))
                                                    @foreach($optNames as $optName)
                                                        <span class="badge me-1" style="color: #4f46e5; background: #eef2ff; font-weight: 700; font-size: 12px;">{{ $optName }}</span>
                                                    @endforeach
                                                @elseif(is_array($comb->variation_options))
                                                    @foreach($comb->variation_options as $opt)
                                                        <span class="badge me-1" style="color: #4f46e5; background: #eef2ff; font-weight: 700; font-size: 12px;">{{ $opt }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="badge" style="color: #4f46e5; background: #eef2ff; font-weight: 700; font-size: 12px;">{{ $comb->variation_options }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="font-weight-bold text-dark">৳{{ number_format($comb->regular_price ?? 0, 2) }}</span>
                                            </td>
                                            <td>
                                                @if($comb->offer_price && $comb->offer_price > 0)
                                                    <span class="font-weight-bold text-success">৳{{ number_format($comb->offer_price, 2) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end">
                                                @if($comb->stock_quantity > 0)
                                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-1 rounded-pill" style="color: #16a34a !important; background: #f0fdf4;">{{ $comb->stock_quantity }} units</span>
                                                @else
                                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold px-3 py-1 rounded-pill" style="color: #dc2626 !important; background: #fef2f2;">Out of Stock</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-4">
                            <div class="row g-3">
                                <div class="col-sm-6 col-md-3">
                                    <div class="p-3 rounded-3 border" style="background: #f8fafc;">
                                        <div class="pp-meta-label">Offer Price</div>
                                        <div class="fs-5 font-weight-bold text-success">৳{{ number_format($product->offer ?? 0, 2) }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="p-3 rounded-3 border" style="background: #f8fafc;">
                                        <div class="pp-meta-label">Regular Price</div>
                                        <div class="fs-5 font-weight-bold text-muted text-decoration-line-through">৳{{ number_format($product->old_price ?? 0, 2) }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="p-3 rounded-3 border" style="background: #f8fafc;">
                                        <div class="pp-meta-label">Stock Quantity</div>
                                        <div class="fs-5 font-weight-bold text-dark">{{ $product->quantity ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <div class="p-3 rounded-3 border" style="background: #f8fafc;">
                                        <div class="pp-meta-label">Product SKU</div>
                                        <div class="fs-6 font-weight-bold text-dark">{{ $product->sku ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Action Sidebar -->
        <div class="col-lg-4">
            <!-- Approval & Commission Controller -->
            <div class="pp-card">
                <div class="pp-card-header">
                    <h5 class="pp-card-title"><i class="fas fa-sliders-h"></i> Commission & Approval</h5>
                </div>
                <div class="pp-card-body">
                    <!-- Range & Limits Info -->
                    <div class="pp-comm-box">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small font-weight-bold" style="color: #1e1b4b;">Allowed Commission Range:</span>
                            <span class="badge bg-primary text-white fw-bold">{{ $commissionLimits['min'] }}% - {{ $commissionLimits['max'] }}%</span>
                        </div>
                        <div class="pp-range-bar">
                            <div class="pp-range-fill" style="width: {{ (($commissionLimits['default'] - $commissionLimits['min']) / max(1, ($commissionLimits['max'] - $commissionLimits['min']))) * 100 }}%;"></div>
                        </div>
                        <small class="d-block mt-2 font-weight-medium" style="color: #3730a3;">Store default rate is {{ $commissionLimits['default'] }}%</small>
                    </div>

                    @if($product->vendor_proposed_commission)
                        <div class="p-3 mb-4 rounded-3 border" style="background: #fffbeb; border-color: #fde68a !important;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small font-weight-bold" style="color: #78350f;">Vendor Proposed Commission</div>
                                    <small style="color: #b45309;">Requested by seller for this item</small>
                                </div>
                                <span class="fs-4 font-weight-extrabold" style="color: #d97706;">{{ $product->vendor_proposed_commission }}%</span>
                            </div>
                        </div>
                    @endif

                    @if($product->approval_status === 'pending')
                        <!-- APPROVAL FORM -->
                        <form action="{{ route('admin.vendor-products.approve', $product) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label font-weight-bold text-dark small text-uppercase">Approved Commission Rate (%) *</label>
                                <div class="input-group">
                                    <input type="number" name="vendor_commission_rate" class="form-control form-control-lg fw-bold text-dark" 
                                           value="{{ $product->vendor_proposed_commission ?? $commissionLimits['default'] }}" 
                                           min="{{ $commissionLimits['min'] }}" max="{{ $commissionLimits['max'] }}" step="0.01" required>
                                    <span class="input-group-text bg-light fw-bold">%</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-pp-approve w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-check-circle fs-5"></i> Approve & Publish Item
                            </button>
                        </form>

                        <!-- REJECTION FORM -->
                        <div class="border-top pt-3">
                            <form action="{{ route('admin.vendor-products.reject', $product) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold text-dark small text-uppercase">Rejection Reason</label>
                                    <textarea name="rejection_reason" class="form-control small" rows="2" placeholder="State reason for rejecting item..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-pp-reject w-100 d-flex align-items-center justify-content-center gap-2">
                                    <i class="fas fa-times-circle fs-5"></i> Reject Listing Request
                                </button>
                            </form>
                        </div>
                    @elseif($product->approval_status === 'approved')
                        <div class="p-3 mb-4 rounded-3 border" style="background: #f0fdf4; border-color: #bbf7d0 !important;">
                            <div class="d-flex align-items-center gap-2 font-weight-bold" style="color: #166534;">
                                <i class="fas fa-check-circle fs-5" style="color: #16a34a;"></i> Published & Active
                            </div>
                            <small class="d-block mt-1" style="color: #15803d;">Active Commission: <strong>{{ $product->vendor_commission_rate ?? $commissionLimits['default'] }}%</strong></small>
                        </div>

                        <!-- UPDATE COMMISSION FORM -->
                        <form action="{{ route('admin.vendor-products.update-commission', $product) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label font-weight-bold text-dark small text-uppercase">Update Commission Rate (%)</label>
                                <div class="input-group">
                                    <input type="number" name="vendor_commission_rate" class="form-control fw-bold" 
                                           value="{{ $product->vendor_commission_rate ?? $commissionLimits['default'] }}" 
                                           min="{{ $commissionLimits['min'] }}" max="{{ $commissionLimits['max'] }}" step="0.01" required>
                                    <span class="input-group-text bg-light fw-bold">%</span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary text-white w-100 font-weight-bold py-2 rounded-3">
                                <i class="fas fa-save me-1"></i> Save Updated Commission
                            </button>
                        </form>
                    @else
                        <div class="p-3 mb-4 rounded-3 border" style="background: #fef2f2; border-color: #fecaca !important; color: #9f1239;">
                            <i class="fas fa-times-circle me-1 text-danger"></i> Product Listing Rejected
                        </div>
                        <form action="{{ route('admin.vendor-products.approve', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="vendor_commission_rate" value="{{ $commissionLimits['default'] }}">
                            <button type="submit" class="btn btn-outline-success w-100 font-weight-bold py-2 rounded-3">
                                <i class="fas fa-redo me-1"></i> Re-Approve Product
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Store Quick Profile Card -->
            <div class="pp-card">
                <div class="pp-card-header">
                    <h5 class="pp-card-title"><i class="fas fa-store"></i> Partner Store Info</h5>
                </div>
                <div class="pp-card-body">
                    <div class="fw-bold text-dark fs-6">{{ $product->vendor->name ?? 'Vendor' }}</div>
                    <div class="small mb-3" style="color: #64748b;">{{ $product->vendor->email ?? '' }}</div>
                    
                    <a href="{{ route('admin.vendors.show', $product->vendor) }}" class="btn btn-light text-dark w-100 font-weight-bold border btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i> View Partner Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
