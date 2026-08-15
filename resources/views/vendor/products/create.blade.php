@extends('vendor.layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
    /* Premium Glassmorphic Design System */
    :root {
        --primary: #6366f1;
        --primary-hover: #4f46e5;
        --primary-light: rgba(99, 102, 241, 0.1);
        --primary-glow: rgba(99, 102, 241, 0.15);
        --success: #10b981;
        --success-light: rgba(16, 185, 129, 0.1);
        --warning: #f59e0b;
        --warning-light: rgba(245, 158, 11, 0.1);
        --danger: #ef4444;
        --danger-light: rgba(239, 68, 68, 0.1);
        --dark: #0f172a;
        --light: #f8fafc;
        --border: #e2e8f0;
        --font: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Form Container Grid */
    .product-form-container {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(360px, 1fr);
        gap: 28px;
    }

    .side-content {
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    /* Glassmorphic Cards */
    .form-section {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        padding: 28px;
        margin-bottom: 0 !important;
        border-radius: 24px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.07);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .form-section h4 {
        margin-top: 0;
        margin-bottom: 24px;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-section h4::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 20px;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border-radius: 3px;
    }

    /* Premium inputs and floating fields */
    .form-control, .form-select {
        height: auto;
        padding: 12px 16px;
        font-size: 0.925rem;
        font-weight: 500;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        background-color: rgba(255, 255, 255, 0.9);
        color: var(--dark);
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px var(--primary-glow);
        background-color: #ffffff;
        outline: none;
    }

    .form-floating {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-floating > .form-control {
        height: 56px;
        padding: 20px 16px 6px 16px;
    }

    .form-floating > label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        padding: 16px;
        pointer-events: none;
        transform-origin: 0 0;
        transition: all .2s ease-in-out;
        color: #64748b;
        font-size: 0.925rem;
        font-weight: 500;
    }

    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        transform: scale(.8) translateY(-10px) translateX(4px);
        color: var(--primary);
        font-weight: 700;
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    /* Product Type Selection Cards */
    .product-type-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .product-type-card {
        border: 2px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px 14px;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.6);
    }

    .product-type-card:hover {
        border-color: var(--primary);
        background: rgba(255, 255, 255, 0.9);
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.08);
    }

    .product-type-card.active {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(79, 70, 229, 0.08) 100%);
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.12);
        transform: translateY(-2px);
    }

    .product-type-card i {
        font-size: 28px;
        margin-bottom: 12px;
        color: #64748b;
        display: block;
        transition: color 0.25s;
    }

    .product-type-card.active i {
        color: var(--primary);
    }

    .product-type-card h6 {
        margin: 0 0 6px 0;
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--dark);
    }

    .product-type-card small {
        font-size: 0.75rem;
        color: #64748b;
        display: block;
        line-height: 1.3;
    }

    /* Category Multi-Select Tree */
    .category-multiselect-container {
        background: rgba(255, 255, 255, 0.8);
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
    }

    .category-panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        background: rgba(248, 250, 252, 0.8);
    }

    .category-panel-title {
        font-weight: 800;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #475569;
        margin: 0;
    }

    .category-tabs {
        display: flex;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
    }

    .category-tab {
        flex: 1;
        padding: 12px 16px;
        cursor: pointer;
        border: none;
        background: none;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 700;
        border-bottom: 3px solid transparent;
        transition: all 0.25s;
        text-align: center;
    }

    .category-tab:hover {
        color: var(--primary);
    }

    .category-tab.active {
        border-bottom-color: var(--primary);
        color: var(--primary);
        background: #ffffff;
    }

    .category-search-box {
        padding: 12px 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .category-search-box input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.875rem;
        background: #ffffff;
    }

    .category-list-wrapper {
        max-height: 260px;
        overflow-y: auto;
        padding: 8px 0;
    }

    .category-list-item {
        display: flex;
        align-items: center;
        padding: 8px 20px;
        min-height: 34px;
        transition: background 0.25s;
    }

    .category-list-item.hidden {
        display: none !important;
    }

    .category-list-item:hover {
        background: rgba(241, 245, 249, 0.6);
    }

    .category-list-item label {
        display: flex;
        align-items: center;
        cursor: pointer;
        width: 100%;
        margin: 0;
        font-size: 0.9rem;
        user-select: none;
    }

    .category-list-item input[type="checkbox"] {
        margin-right: 12px;
        cursor: pointer;
        width: 18px;
        height: 18px;
        border-radius: 6px;
        accent-color: var(--primary);
    }

    .category-name-text {
        color: var(--dark);
        font-weight: 600;
    }

    .category-level-0 { padding-left: 20px; }
    .category-level-1 { padding-left: 40px; border-left: 2px solid var(--primary-light); margin-left: 24px; }
    .category-level-2 { padding-left: 60px; border-left: 2px solid var(--primary-light); margin-left: 24px; }

    .category-selected-count {
        padding: 12px 20px;
        border-top: 1px solid #e2e8f0;
        background: rgba(248, 250, 252, 0.8);
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 700;
    }

    /* Variation and Combinations list styling */
    .variation {
        background: rgba(248, 250, 252, 0.7);
        padding: 24px;
        margin-bottom: 20px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
    }

    .option {
        background: #ffffff;
        padding: 20px;
        margin: 12px 0;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
    }

    .btn-add-variation {
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 16px;
        width: 100%;
        font-weight: 700;
        font-size: 0.9rem;
        color: #475569;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-add-variation:hover {
        background: var(--primary-light);
        border-color: var(--primary);
        color: var(--primary);
    }

    #combinationsTable table {
        border-collapse: separate !important;
        border-spacing: 0 6px !important;
    }

    #combinationsTable thead th {
        background: var(--light) !important;
        color: #475569 !important;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.06em;
        border-bottom: 2px solid var(--border);
        padding: 14px;
    }

    #combinationsTable tbody td {
        vertical-align: middle;
        padding: 12px;
        background: #ffffff;
        border-bottom: 1.5px solid var(--border);
    }

    /* SEO Section */
    .seo-preview {
        background: rgba(248, 250, 252, 0.8);
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        padding: 20px;
        margin-top: 20px;
    }

    .seo-title {
        color: #1a0dab;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .seo-url {
        color: #006621;
        font-size: 0.875rem;
        margin-bottom: 8px;
    }

    .seo-description {
        color: #475569;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 10px;
    }

    .image-preview-item {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
        border: 1px solid #e2e8f0;
    }

    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview-item .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(239, 68, 68, 0.9);
        color: #fff;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
    }

    /* Modern Pill Tabs Styling */
    .modern-tabs {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        padding: 6px;
        border-radius: 18px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        display: inline-flex;
    }

    .modern-tabs .nav-link {
        color: #64748b !important;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 10px 20px;
        border-radius: 14px;
        transition: all 0.25s;
        border: none !important;
    }

    .modern-tabs .nav-link:hover {
        background: rgba(99, 102, 241, 0.05);
        color: var(--primary) !important;
    }

    .modern-tabs .nav-link.active {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.25);
    }

    .tab-content {
        margin-top: 1.5rem;
    }

    .tab-pane .row {
        --bs-gutter-x: 28px;
        --bs-gutter-y: 28px;
    }

    .page-header-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        padding: 1rem 1.5rem;
        border-radius: 16px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    }

    .page-header-card h4 {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--dark);
        margin-bottom: 0 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <!-- Header Card -->
    <div class="d-flex justify-content-between align-items-center mb-4 page-header-card">
        <div>
            <h4 class="mb-1">Add New Product</h4>
        </div>
        <div class="d-flex align-items-center gap-3">
            <nav aria-label="breadcrumb" class="me-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>
            </nav>
            <a href="{{ route('vendor.products.index') }}" class="btn btn-sm btn-outline-secondary fw-bold rounded-3 px-3">← Back to Products</a>
        </div>
    </div>

    <!-- Commission Info Alert -->
    <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4">
        <h5 class="fw-bold mb-2"><i class="fas fa-info-circle me-2"></i> Commission Settings</h5>
        <div class="row text-dark">
            <div class="col-md-4">
                <strong>Your Default Commission:</strong> {{ $commissionSettings['default'] }}%
            </div>
            <div class="col-md-4">
                <strong>Allowed Range:</strong> {{ $commissionSettings['min'] }}% - {{ $commissionSettings['max'] }}%
            </div>
            <div class="col-md-4">
                <strong>Note:</strong> Custom commission requires admin approval
            </div>
        </div>
    </div>

    <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Product Approval Required:</strong> Your product will be pending admin approval before going live on the store.
    </div>

    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data" id="productForm">
        @csrf

        <!-- Tabbed Interface for Product Creation Workspace -->
        <div class="row">
            <div class="col-12 text-center">
                <ul class="nav nav-pills modern-tabs mb-4 justify-content-center" id="productFormTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab"><i class="fas fa-info-circle me-2"></i> General Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing-pane" type="button" role="tab"><i class="fas fa-coins me-2"></i> Pricing & Inventory</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media-pane" type="button" role="tab"><i class="fas fa-images me-2"></i> Media Gallery</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="classifications-tab" data-bs-toggle="tab" data-bs-target="#classifications-pane" type="button" role="tab"><i class="fas fa-folder-open me-2"></i> Classifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab"><i class="fas fa-search me-2"></i> SEO Settings</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="productFormTabsContent">
            <!-- TAB 1: GENERAL INFO -->
            <div class="tab-pane fade show active" id="general-pane" role="tabpanel" aria-labelledby="general-tab">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Basic Information Card -->
                        <div class="form-section mb-4">
                            <h4>Basic Information</h4>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                                    id="productName" placeholder="Enter product name" value="{{ old('title') }}" required>
                                <label for="productName">Product Name</label>
                                @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug"
                                    id="productSlug" placeholder="Enter product slug" value="{{ old('slug') }}" required>
                                <label for="productSlug">Product Slug</label>
                                <small class="form-text text-muted">Auto-generated from product name, but you can edit it manually</small>
                                @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku"
                                    id="productSku" placeholder="Enter SKU (Stock Keeping Unit)" value="{{ old('sku') }}">
                                <label for="productSku">SKU (Stock Keeping Unit)</label>
                                <small class="form-text text-muted">Unique identifier for inventory tracking.</small>
                                @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="productShortDescription" class="form-label">Short Description</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" id="productShortDescription"
                                    name="short_description" rows="3" placeholder="Enter short description">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="productDescription" class="form-label">Detailed Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="productDescription" name="description"
                                    rows="8" placeholder="Enter detailed description">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Brand Section -->
                        <div class="form-section mb-4">
                            <h4>Brand</h4>
                            <div class="mb-3">
                                <label for="productBrand" class="form-label">Brand</label>
                                <select class="form-select" name="brand_id" id="productBrand">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Optional: Select a brand for this product</small>
                            </div>
                        </div>

                        <!-- Tags Section -->
                        <div class="form-section mb-4">
                            <h4>Tags</h4>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="tags" id="productTags"
                                    placeholder="Enter tags separated by commas" value="{{ old('tags') }}">
                                <small class="text-muted">Separate tags with commas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: PRICING & INVENTORY -->
            <div class="tab-pane fade" id="pricing-pane" role="tabpanel" aria-labelledby="pricing-tab">
                <div class="row">
                    <div class="col-12">
                        <!-- Product Data Section -->
                        <div class="form-section mb-4">
                            <h4>Product Data</h4>

                            <!-- Product Type Selector Cards -->
                            <div class="product-type-selector">
                                <div class="product-type-card active" data-type="simple" onclick="selectProductType('simple')">
                                    <i class="fas fa-box fs-3"></i>
                                    <h6>Simple Product</h6>
                                    <small class="text-muted">Single product with no variations</small>
                                </div>
                                <div class="product-type-card" data-type="variable" onclick="selectProductType('variable')">
                                    <i class="fas fa-layer-group fs-3"></i>
                                    <h6>Variable Product</h6>
                                    <small class="text-muted">Product with multiple variations</small>
                                </div>
                                <div class="product-type-card" data-type="digital" onclick="selectProductType('digital')">
                                    <i class="fas fa-file-download fs-3"></i>
                                    <h6>Digital Product</h6>
                                    <small class="text-muted">Downloadable product</small>
                                </div>
                                <div class="product-type-card" data-type="affiliate" onclick="selectProductType('affiliate')">
                                    <i class="fas fa-link fs-3"></i>
                                    <h6>Affiliate Product</h6>
                                    <small class="text-muted">External product with commission</small>
                                </div>
                            </div>

                            <input type="hidden" name="product_type" id="productTypeInput" value="simple">

                            <!-- Digital Product Fields -->
                            <div id="digitalFields" class="product-type-fields" style="display: none;">
                                <div class="mb-3">
                                    <label for="digital_file" class="form-label">Digital File</label>
                                    <input type="file" class="form-control" id="digital_file" name="digital_file"
                                        accept=".pdf,.zip,.rar,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                                    @error('digital_file')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control @error('download_limit') is-invalid @enderror"
                                        id="downloadLimit" name="download_limit" value="{{ old('download_limit') }}"
                                        placeholder="Enter download limit">
                                    <label for="downloadLimit">Download Limit (leave empty for unlimited)</label>
                                    @error('download_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Affiliate Product Fields -->
                            <div id="affiliateFields" class="product-type-fields" style="display: none;">
                                <div class="form-floating mb-3">
                                    <input type="url" class="form-control @error('external_url') is-invalid @enderror"
                                        id="externalUrl" name="external_url" value="{{ old('external_url') }}"
                                        placeholder="Enter external product URL">
                                    <label for="externalUrl">External URL</label>
                                    @error('external_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="number" step="0.01"
                                        class="form-control @error('affiliate_commission') is-invalid @enderror"
                                        id="affiliateCommission" name="affiliate_commission"
                                        value="{{ old('affiliate_commission') }}" placeholder="Enter commission amount">
                                    <label for="affiliateCommission">Affiliate Commission (%)</label>
                                    @error('affiliate_commission')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pricing Fields -->
                            <div id="pricingFields">
                                @php
                                    $vendorSettings = auth()->user()->vendorSettings;
                                    $saleMarkup = floatval($vendorSettings->sale_price_markup_pct ?? 10.00);
                                    $oldMarkup = floatval($vendorSettings->old_price_markup_pct ?? 25.00);
                                    $wsMarkup = floatval($vendorSettings->wholesale_price_markup_pct ?? 5.00);
                                @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="product_cost" id="productCost"
                                                placeholder="Enter product cost" value="{{ old('product_cost') }}" step="0.01">
                                            <label for="productCost">Product Cost</label>
                                            <div class="form-text">What you pay for this product</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="wholesale_price" id="wholesalePrice"
                                                placeholder="Enter wholesale price" value="{{ old('wholesale_price') }}" step="0.01">
                                            <label for="wholesalePrice">Wholesale Price</label>
                                            <div class="form-text">Price for bulk/wholesale customers</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="old_price" id="productOldPrice"
                                                placeholder="Enter regular price" value="{{ old('old_price') }}" step="0.01" required>
                                            <label for="productOldPrice">Regular Price</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3">
                                            <input type="number" class="form-control" name="offer" id="productOfferPrice"
                                                placeholder="Enter sale price" value="{{ old('offer') }}" step="0.01">
                                            <label for="productOfferPrice">Sale Price</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="quantity" id="productQuantity"
                                            placeholder="Enter stock quantity" value="{{ old('quantity') }}">
                                        <label for="productQuantity">Stock Quantity</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="weight" id="productWeight"
                                            placeholder="Enter product weight" value="{{ old('weight', '0.5') }}" step="0.001" min="0.001">
                                        <label for="productWeight">Weight (KG)</label>
                                        <div class="form-text">Weight for courier delivery (minimum 0.5 KG)</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="return_period" id="returnPeriod" value="{{ old('return_period', 0) }}" min="0">
                                        <label for="returnPeriod">Easy Return Period (Days)</label>
                                    </div>
                                    <small class="text-muted d-block ms-2 mb-3" style="margin-top: -10px;">Enter 0 if return is not allowed.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Variations Section -->
                        <div class="form-section mb-4" id="variationsSection" style="display: none;">
                            <h4>Product Variations</h4>
                            <div class="alert alert-info">
                                <strong>How it works:</strong> Add variation groups (like Size, Color) and their options. The
                                system will automatically generate all possible combinations and let you set individual prices
                                and stock for each combination.
                            </div>
                            <div id="variations"></div>
                            <button type="button" class="btn-add-variation" onclick="addVariation()">
                                <i class="fas fa-plus-circle me-2"></i> Add Variation Group
                            </button>

                            <!-- Combinations Preview -->
                            <div id="combinationsPreview" style="display: none; margin-top: 30px;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-bold"><i class="fas fa-th me-2"></i> Variation Combinations</h5>
                                    <span id="combinationsCount" class="badge bg-success fs-6">0 combinations</span>
                                </div>
                                <div id="combinationsTable"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: MEDIA GALLERY -->
            <div class="tab-pane fade" id="media-pane" role="tabpanel" aria-labelledby="media-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Product Images Section -->
                        <div class="form-section mb-4">
                            <h4>Product Images</h4>
                            <div class="mb-4">
                                <label for="thumb_image" class="form-label">Product Featured Image</label>
                                <input type="file" class="form-control" id="thumb_image" name="thumb_image"
                                    accept="image/jpeg,image/png,image/jpg,image/gif" required>
                                <div id="thumbnailPreview" class="mt-2" style="max-width: 200px;"></div>
                                @error('thumb_image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="images" class="form-label">Product Gallery Images</label>
                                <input type="file" class="form-control" id="images" name="images[]"
                                    accept="image/jpeg,image/png,image/jpg,image/gif" multiple>
                                <div id="galleryPreview" class="image-preview mt-2"></div>
                                @error('images')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Video Section -->
                        <div class="form-section mb-4">
                            <h4>Video Integration</h4>
                            <div class="form-group">
                                <label for="video_url" class="form-label">Product Video URL (YouTube)</label>
                                <input type="text" name="video_url" id="video_url" class="form-control"
                                    value="{{ old('video_url') }}"
                                    placeholder="https://www.youtube.com/watch?v=xxxxxx">
                                <small class="form-text text-muted">Leave blank to use the global video URL.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: CLASSIFICATIONS & BOOK DETAILS -->
            <div class="tab-pane fade" id="classifications-pane" role="tabpanel" aria-labelledby="classifications-tab">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Categories Section -->
                        <div class="form-section mb-4">
                            <h4>Product Categories</h4>
                            <div class="mb-3">
                                <div class="category-multiselect-container">
                                    <div class="category-panel-header">
                                        <h3 class="category-panel-title">Categories Tree</h3>
                                    </div>
                                    <div class="category-tabs">
                                        <button type="button" class="category-tab active" data-tab="all">All categories</button>
                                        <button type="button" class="category-tab" data-tab="most-used">Most Used</button>
                                    </div>
                                    <div class="category-search-box">
                                        <input type="text" id="categorySearch" placeholder="Search categories..." autocomplete="off">
                                    </div>
                                    <div class="category-list-wrapper" id="categoryListWrapper">
                                        @php
                                        $oldAdditionalCategories = old('additional_categories', []);
                                        $oldAdditionalSubcategories = old('additional_subcategories', []);
                                        $oldThirdCategories = old('third_categories', []);
                                        @endphp

                                        <input type="hidden" name="category_id" id="primary_category_id_input" value="{{ old('category_id') }}">
                                        @foreach ($categories as $category)
                                        <div class="category-list-item category-level-0" data-category-name="{{ strtolower($category->name) }}" data-level="0" data-category-id="{{ $category->id }}">
                                            <label>
                                                <input type="checkbox" name="additional_categories[]" value="{{ $category->id }}" id="category_{{ $category->id }}" class="category-checkbox" {{ (in_array($category->id, $oldAdditionalCategories) || old('category_id') == $category->id) ? 'checked' : '' }}>
                                                <span class="category-name-text">{{ $category->name }}</span>
                                            </label>
                                        </div>

                                        @if($category->subCategories && $category->subCategories->count() > 0)
                                        @foreach($category->subCategories as $subCategory)
                                        <div class="category-list-item category-level-1" data-category-name="{{ strtolower($subCategory->name . ' ' . $category->name) }}" data-level="1" data-parent-id="{{ $category->id }}" data-category-id="{{ $subCategory->id }}">
                                            <label>
                                                <input type="checkbox" name="additional_subcategories[]" value="{{ $subCategory->id }}" id="subcategory_{{ $subCategory->id }}" class="category-checkbox additional-subcategory-checkbox" {{ in_array($subCategory->id, $oldAdditionalSubcategories) ? 'checked' : '' }}>
                                                <span class="category-name-text">{{ $subCategory->name }}</span>
                                            </label>
                                        </div>

                                        @if($subCategory->thirdCategories && $subCategory->thirdCategories->count() > 0)
                                        @foreach($subCategory->thirdCategories as $thirdCategory)
                                        <div class="category-list-item category-level-2" data-category-name="{{ strtolower($thirdCategory->name . ' ' . $subCategory->name . ' ' . $category->name) }}" data-level="2" data-parent-id="{{ $subCategory->id }}" data-category-id="{{ $thirdCategory->id }}">
                                            <label>
                                                <input type="checkbox" name="third_categories[]" value="{{ $thirdCategory->id }}" id="thirdcategory_{{ $thirdCategory->id }}" class="category-checkbox thirdcategory-checkbox" {{ in_array($thirdCategory->id, $oldThirdCategories) ? 'checked' : '' }}>
                                                <span class="category-name-text">{{ $thirdCategory->name }}</span>
                                            </label>
                                        </div>
                                        @endforeach
                                        @endif
                                        @endforeach
                                        @endif
                                        @endforeach
                                    </div>
                                    <div class="category-selected-count">
                                        <span id="selectedCount">0</span> selected
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Special Badges & Settings -->
                        <div class="form-section mb-4">
                            <h4>Special Badges & Details</h4>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="is_featured" id="isFeatured"
                                    value="1" {{ old('is_featured') ? 'checked' : '' }} style="margin-top: 7px;">
                                <label class="form-check-label" for="isFeatured">Set as Featured Product</label>
                                <small class="d-block text-muted">Check this to mark the product as featured</small>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="is_book" id="isBook"
                                    value="1" {{ old('is_book') ? 'checked' : '' }} style="margin-top: 7px;" onchange="toggleBookDetails()">
                                <label class="form-check-label" for="isBook">Is it Product Type of Book?</label>
                                <small class="d-block text-muted">Check this to add book-specific details</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Book Details Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="form-section mb-4" id="bookDetailSection" style="display: none;">
                            <h4>Book Specifications</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edition" class="form-label">Edition</label>
                                        <input type="text" class="form-control" name="edition" id="edition"
                                            placeholder="Enter edition" value="{{ old('edition') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="isbn" class="form-label">ISBN</label>
                                        <input type="text" class="form-control" name="isbn" id="isbn"
                                            placeholder="Enter isbn" value="{{ old('isbn') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="language" class="form-label">Language</label>
                                        <select class="form-select" name="language" id="language">
                                            <option value="">Select Language</option>
                                            <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                            <option value="Bangla" {{ old('language') == 'Bangla' ? 'selected' : '' }}>Bangla</option>
                                            <option value="Hindi" {{ old('language') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sample_path" class="form-label">Upload Sample File</label>
                                        <input type="file" class="form-control" name="sample_path" id="sample_path">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pages" class="form-label">Total Pages</label>
                                        <input type="number" class="form-control" name="pages" id="pages"
                                            placeholder="Enter total pages" value="{{ old('pages') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="cover" class="form-label">Cover Type</label>
                                        <select class="form-select" name="cover" id="cover">
                                            <option value="">Select Cover</option>
                                            <option value="Hardcover" {{ old('cover') == 'Hardcover' ? 'selected' : '' }}>Hardcover</option>
                                            <option value="Paperback" {{ old('cover') == 'Paperback' ? 'selected' : '' }}>Paperback</option>
                                            <option value="Ebook" {{ old('cover') == 'Ebook' ? 'selected' : '' }}>Ebook</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country of Origin</label>
                                        <select class="form-select" name="country" id="country">
                                            <option value="">Select Country</option>
                                            <option value="Bangladesh" {{ old('country') == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                                            <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                                            <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>USA</option>
                                            <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>UK</option>
                                            <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                                            <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                                            <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="productWriters" class="form-label">Writers</label>
                                    <select class="form-select @error('writers') is-invalid @enderror" id="productWriters" name="writers[]" multiple style="height: 120px;">
                                        @foreach ($writers as $writer)
                                        <option value="{{ $writer->id }}" {{ in_array($writer->id, old('writers', [])) ? 'selected' : '' }}>
                                            {{ $writer->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label for="publisher" class="form-label">Publisher</label>
                                    <select class="form-select @error('publisher') is-invalid @enderror" id="publisher" name="publisher">
                                        <option value="">Select Publisher</option>
                                        @foreach ($publishers as $publisher)
                                        <option value="{{ $publisher->id }}" {{ old('publisher') == $publisher->id ? 'selected' : '' }}>
                                            {{ $publisher->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5: SEO SETTINGS -->
            <div class="tab-pane fade" id="seo-pane" role="tabpanel" aria-labelledby="seo-tab">
                <div class="row">
                    <div class="col-12">
                        <div class="form-section mb-4">
                            <h4>SEO Settings</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="seo[meta_title]" id="seoMetaTitle"
                                            placeholder="Enter SEO title" value="" maxlength="60">
                                        <label for="seoMetaTitle">Meta Title</label>
                                        <small class="form-text text-muted">
                                            <span id="metaTitleCount">0</span>/60 characters. Leave empty to auto-generate from product title.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" name="seo[meta_description]" id="seoMetaDescription"
                                            placeholder="Enter SEO description" style="height: 100px;" maxlength="160"></textarea>
                                        <label for="seoMetaDescription">Meta Description</label>
                                        <small class="form-text text-muted">
                                            <span id="metaDescriptionCount">0</span>/160 characters. Leave empty to auto-generate from product description.
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="seo[meta_keywords]"
                                            id="seoMetaKeywords" placeholder="Enter SEO keywords" value="">
                                        <label for="seoMetaKeywords">Meta Keywords</label>
                                        <small class="form-text text-muted">Comma-separated keywords.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="url" class="form-control" name="seo[canonical_url]"
                                            id="seoCanonicalUrl" placeholder="Enter canonical URL" value="">
                                        <label for="seoCanonicalUrl">Canonical URL</label>
                                        <small class="form-text text-muted">Leave empty to use default product URL.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <select class="form-select" name="seo[meta_robots]" id="seoMetaRobots">
                                            <option value="index,follow">Index, Follow</option>
                                            <option value="noindex,follow">No Index, Follow</option>
                                            <option value="index,nofollow">Index, No Follow</option>
                                            <option value="noindex,nofollow">No Index, No Follow</option>
                                        </select>
                                        <label for="seoMetaRobots">Robots Meta</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="seoSchemaMarkup" class="form-label">Custom Schema Markup (JSON-LD)</label>
                                        <textarea class="form-control" name="seo[schema_markup]" id="seoSchemaMarkup" rows="4" placeholder="Enter custom schema markup"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card border-0 shadow-sm rounded-4">
                                        <div class="card-header bg-light rounded-top-4">
                                            <h6 class="mb-0 fw-bold"><i class="fas fa-eye me-2"></i> SEO Preview</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="seo-preview">
                                                <p class="seo-title"><strong>Meta Title:</strong> Auto-generated from product name</p>
                                                <p class="seo-description"><strong>Meta Description:</strong> Auto-generated from product description</p>
                                                <p class="seo-description"><strong>Meta Keywords:</strong> Auto-generated from product tags and category</p>
                                                <p class="seo-url"><strong>Canonical URL:</strong> Default product URL</p>
                                                <p class="seo-description"><strong>Meta Robots:</strong> Default (Index, Follow)</p>
                                                <p class="seo-description"><strong>OG Image:</strong> Product featured image</p>
                                                <p class="seo-description"><strong>Schema Markup:</strong> Auto-generated basic product schema</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Footer Action Panel -->
        <div class="form-section mt-4 d-flex flex-wrap justify-content-between align-items-center" style="background: rgba(255, 255, 255, 0.95); border: 2px solid var(--primary); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.15);">
            <div class="d-flex align-items-center gap-3">
                <label for="productStatus" class="form-label mb-0" style="font-weight: 700; color: var(--dark); text-transform: none; letter-spacing: 0;">Publishing State:</label>
                <select class="form-select" name="status" id="productStatus" style="width: 160px; height: 42px;">
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div>
                <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary me-2 rounded-3 px-4">Cancel</a>
                <button class="btn btn-primary rounded-3 px-4" type="submit" style="height: 42px;">Create & Submit Product</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    // Initialize Summernote
    $('#productDescription, #productShortDescription').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    $('#productShortDescription').summernote('height', 150);

    // Product Type Selection
    function selectProductType(type) {
        document.getElementById('productTypeInput').value = type;

        document.querySelectorAll('.product-type-card').forEach(card => {
            card.classList.remove('active');
        });
        const activeCard = document.querySelector(`.product-type-card[data-type="${type}"]`);
        if (activeCard) activeCard.classList.add('active');

        const digitalFields = document.getElementById('digitalFields');
        const affiliateFields = document.getElementById('affiliateFields');
        const variationsSection = document.getElementById('variationsSection');
        const pricingFields = document.getElementById('pricingFields');

        document.querySelectorAll('.product-type-fields').forEach(el => {
            el.style.display = 'none';
        });
        variationsSection.style.display = 'none';

        if (type === 'variable') {
            pricingFields.style.display = 'none';
            document.getElementById('productOldPrice').value = '';
            document.getElementById('productOfferPrice').value = '';
            document.getElementById('productOldPrice').removeAttribute('name');
            document.getElementById('productOfferPrice').removeAttribute('name');
            document.getElementById('productOldPrice').removeAttribute('required');
        } else {
            pricingFields.style.display = 'block';
            document.getElementById('productOldPrice').setAttribute('name', 'old_price');
            document.getElementById('productOfferPrice').setAttribute('name', 'offer');
        }

        if (type === 'digital') {
            digitalFields.style.display = 'block';
        } else if (type === 'affiliate') {
            affiliateFields.style.display = 'block';
        } else if (type === 'variable') {
            variationsSection.style.display = 'block';
        }

        if (type !== 'affiliate') {
            document.getElementById('externalUrl').value = '';
            document.getElementById('affiliateCommission').value = '';
        }
    }

    // Variations handling
    function addVariation() {
        const variationsDiv = document.getElementById('variations');
        const variationCount = variationsDiv.children.length;

        const variationDiv = document.createElement('div');
        variationDiv.classList.add('variation');

        variationDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold">Variation Group</h5>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariation(this)">
                    <i class="fas fa-times me-1"></i> Remove
                </button>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="variations[${variationCount}][name]" placeholder="Enter variation name" onchange="updateCombinationsPreview()">
                <label>Variation Name (e.g. Size, Color)</label>
            </div>

            <div class="options">
                <h6>Options</h6>
                <div class="option">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control variation-option-input" name="variations[${variationCount}][options][0][name]" placeholder="Enter option name" required onchange="updateCombinationsPreview()">
                        <label>Option Name (e.g. Small, Red)</label>
                    </div>

                    <div class="alert alert-info p-2 mb-3">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            Option details are managed in the Combinations table below.
                        </small>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm mt-2 fw-bold" onclick="addOption(this)">
                <i class="fas fa-plus me-1"></i> Add Option
            </button>
        `;

        variationsDiv.appendChild(variationDiv);
    }

    function removeVariation(button) {
        button.closest('.variation').remove();
        updateCombinationsPreview();
    }

    function addOption(button) {
        const optionsDiv = button.previousElementSibling;
        const optionCount = optionsDiv.querySelectorAll('.option').length;

        const optionDiv = document.createElement('div');
        optionDiv.classList.add('option', 'mt-3');

        const variationIndex = Array.from(document.querySelectorAll('.variation')).indexOf(button.closest('.variation'));

        optionDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Option ${optionCount + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control variation-option-input" name="variations[${variationIndex}][options][${optionCount}][name]" placeholder="Enter option name" required onchange="updateCombinationsPreview()">
                <label>Option Name (e.g. Small, Red)</label>
            </div>
        `;

        optionsDiv.appendChild(optionDiv);
        updateCombinationsPreview();
    }

    function removeOption(button) {
        button.closest('.option').remove();
        updateCombinationsPreview();
    }

    function updateCombinationsPreview() {
        const variations = document.querySelectorAll('.variation');
        const combinationsPreview = document.getElementById('combinationsPreview');
        const combinationsTable = document.getElementById('combinationsTable');

        if (variations.length === 0) {
            combinationsPreview.style.display = 'none';
            return;
        }

        const variationData = [];
        let hasValidOptions = false;

        variations.forEach((variation) => {
            const variationNameInput = variation.querySelector(`input[name*="[name]"]`);
            const variationName = variationNameInput ? variationNameInput.value.trim() : '';

            if (!variationName) return;

            const options = [];
            const optionInputs = variation.querySelectorAll('.variation-option-input');

            optionInputs.forEach((optionInput) => {
                const optionName = optionInput.value.trim();
                if (optionName) {
                    options.push({ name: optionName });
                    hasValidOptions = true;
                }
            });

            if (options.length > 0) {
                variationData.push({
                    name: variationName,
                    options: options
                });
            }
        });

        if (!hasValidOptions || variationData.length === 0) {
            combinationsPreview.style.display = 'none';
            return;
        }

        const combinations = cartesianProduct(variationData.map(v => v.options));
        const variationNames = variationData.map(v => v.name);

        let tableHTML = `
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>`;

        variationNames.forEach(name => {
            tableHTML += `<th>${name}</th>`;
        });

        tableHTML += `
                            <th>Regular Price (৳)</th>
                            <th>Offer Price (৳)</th>
                            <th>Product Cost (৳)</th>
                            <th>Wholesale Price (৳)</th>
                            <th>Stock</th>
                            <th>Description</th>
                            <th>Images</th>
                        </tr>
                    </thead>
                    <tbody>`;

        combinations.forEach((combination, index) => {
            tableHTML += `<tr><td>${index + 1}</td>`;

            let combinationKey = [];

            combination.forEach(option => {
                tableHTML += `<td><span class="badge bg-primary">${option.name}</span></td>`;
                combinationKey.push(option.name);
            });

            tableHTML += `
                <td>
                    <input type="number" step="0.01" min="0" class="form-control" name="combinations[${index}][regular_price]" placeholder="0.00" style="width: 90px;">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control" name="combinations[${index}][offer_price]" placeholder="Optional" style="width: 90px;">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control" name="combinations[${index}][product_cost]" placeholder="Cost" style="width: 90px;">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control" name="combinations[${index}][wholesale_price]" placeholder="Wholesale" style="width: 90px;">
                </td>
                <td>
                    <input type="number" min="0" class="form-control" name="combinations[${index}][stock_quantity]" placeholder="0" style="width: 70px;">
                </td>
                <td>
                    <textarea class="form-control" name="combinations[${index}][short_description]" rows="2" placeholder="Brief..." style="width: 180px;"></textarea>
                </td>
                <td>
                    <div class="mb-1">
                        <input type="file" class="form-control" name="combinations[${index}][featured_image]" accept="image/*" style="width: 150px; font-size: 11px;">
                    </div>
                    <div>
                        <input type="file" class="form-control" name="combinations[${index}][gallery_images][]" accept="image/*" multiple style="width: 150px; font-size: 11px;">
                    </div>
                </td>
                <input type="hidden" name="combinations[${index}][key]" value="${combinationKey.join('_')}">
            </tr>`;
        });

        tableHTML += `
                    </tbody>
                </table>
            </div>`;

        combinationsTable.innerHTML = tableHTML;
        combinationsPreview.style.display = 'block';
        document.getElementById('combinationsCount').textContent = `${combinations.length} combination${combinations.length !== 1 ? 's' : ''}`;
    }

    function cartesianProduct(arrays) {
        if (arrays.length === 1) {
            return arrays[0].map(option => [option]);
        }
        return arrays.reduce((a, b) =>
            a.flatMap(x => b.map(y => [...(Array.isArray(x) ? x : [x]), y]))
        );
    }

    // File preview logic
    document.addEventListener('DOMContentLoaded', function() {
        const thumbImageInput = document.getElementById('thumb_image');
        const thumbnailPreview = document.getElementById('thumbnailPreview');

        if (thumbImageInput) {
            thumbImageInput.addEventListener('change', function() {
                thumbnailPreview.innerHTML = '';
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-fluid', 'rounded-3', 'shadow-sm');
                        thumbnailPreview.appendChild(img);
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        const galleryImagesInput = document.getElementById('images');
        const galleryPreview = document.getElementById('galleryPreview');
        let selectedFiles = [];

        if (galleryImagesInput) {
            galleryImagesInput.addEventListener('change', function() {
                const newFiles = Array.from(this.files);
                selectedFiles = selectedFiles.concat(newFiles);
                updateGalleryPreview();
            });
        }

        function updateGalleryPreview() {
            galleryPreview.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.classList.add('image-preview-item');
                    previewItem.dataset.index = index;

                    const img = document.createElement('img');
                    img.src = e.target.result;

                    const removeBtn = document.createElement('div');
                    removeBtn.classList.add('remove-btn');
                    removeBtn.innerHTML = '×';
                    removeBtn.addEventListener('click', function() {
                        selectedFiles.splice(index, 1);
                        updateGalleryPreview();
                    });

                    previewItem.appendChild(img);
                    previewItem.appendChild(removeBtn);
                    galleryPreview.appendChild(previewItem);
                }
                reader.readAsDataURL(file);
            });
        }

        const productForm = document.getElementById('productForm');
        if (productForm) {
            productForm.addEventListener('submit', function() {
                if (selectedFiles.length > 0) {
                    const dataTransfer = new DataTransfer();
                    selectedFiles.forEach(file => {
                        dataTransfer.items.add(file);
                    });
                    galleryImagesInput.files = dataTransfer.files;
                }
            });
        }

        // Category Search & Count
        const searchInput = document.getElementById('categorySearch');
        const categoryItems = document.querySelectorAll('.category-list-item');
        const allCheckboxes = document.querySelectorAll('.category-checkbox');
        const selectedCountSpan = document.getElementById('selectedCount');

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                categoryItems.forEach(item => {
                    const categoryName = item.dataset.categoryName || '';
                    if (searchTerm === '' || categoryName.includes(searchTerm)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        }

        function updateSelectedCount() {
            const checkedCategories = document.querySelectorAll('.category-checkbox[name="additional_categories[]"]:checked');
            const primaryInput = document.getElementById('primary_category_id_input');
            if (primaryInput && checkedCategories.length > 0) {
                primaryInput.value = checkedCategories[0].value;
            }
            const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
            if (selectedCountSpan) {
                selectedCountSpan.textContent = checkedCount;
            }
        }

        allCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
        updateSelectedCount();

        // Slug Auto-generation & check
        const productNameInput = document.getElementById('productName');
        const productSlugInput = document.getElementById('productSlug');

        if (productNameInput && productSlugInput) {
            productNameInput.addEventListener('input', function() {
                if (!productSlugInput.dataset.manuallyEdited) {
                    const slug = generateSlug(this.value);
                    productSlugInput.value = slug;
                    checkSlugAvailability(slug);
                }
            });

            productSlugInput.addEventListener('input', function() {
                this.dataset.manuallyEdited = 'true';
                if (this.value.trim() !== '') {
                    checkSlugAvailability(this.value);
                }
            });

            productSlugInput.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.dataset.manuallyEdited = 'false';
                    const slug = generateSlug(productNameInput.value);
                    this.value = slug;
                    checkSlugAvailability(slug);
                }
            });
        }

        // SEO preview updates
        initializeSeoSection();

        // Price Auto-Calculation from Product Cost using Settings Markup Percentages
        const productCostInput = document.getElementById('productCost');
        if (productCostInput) {
            const saleMarkup = {{ $saleMarkup }};
            const oldMarkup = {{ $oldMarkup }};
            const wsMarkup = {{ $wsMarkup }};

            productCostInput.addEventListener('input', function() {
                const cost = parseFloat(this.value);
                if (!isNaN(cost) && cost > 0) {
                    const wholesaleVal = (cost * (1 + wsMarkup / 100)).toFixed(2);
                    const regularVal = (cost * (1 + oldMarkup / 100)).toFixed(2);
                    const saleVal = (cost * (1 + saleMarkup / 100)).toFixed(2);

                    document.getElementById('wholesalePrice').value = wholesaleVal;
                    document.getElementById('productOldPrice').value = regularVal;
                    document.getElementById('productOfferPrice').value = saleVal;
                } else {
                    document.getElementById('wholesalePrice').value = '';
                    document.getElementById('productOldPrice').value = '';
                    document.getElementById('productOfferPrice').value = '';
                }
            });
        }
    });

    let latestSlugToCheck = '';
    function checkSlugAvailability(slug) {
        latestSlugToCheck = slug;
        if (slug.trim() === '') return;

        document.querySelectorAll('.slug-feedback').forEach(el => el.remove());
        const slugContainer = document.getElementById('productSlug').parentElement;
        const loadingFeedback = document.createElement('div');
        loadingFeedback.className = 'slug-feedback form-text text-muted';
        loadingFeedback.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Checking availability...';
        slugContainer.appendChild(loadingFeedback);

        fetch(`/item-slug-availability?slug=${encodeURIComponent(slug)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (slug !== latestSlugToCheck) return;
            loadingFeedback.remove();
            document.querySelectorAll('.slug-feedback').forEach(el => el.remove());

            const feedback = document.createElement('div');
            feedback.className = 'slug-feedback form-text';

            if (data.available) {
                feedback.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i> Slug is available!';
                feedback.classList.add('text-success');
                document.getElementById('productSlug').classList.remove('is-invalid');
                document.getElementById('productSlug').classList.add('is-valid');
            } else {
                feedback.innerHTML = '<i class="fas fa-exclamation-triangle text-warning me-1"></i> Slug already exists.';
                feedback.classList.add('text-warning');
                document.getElementById('productSlug').classList.remove('is-valid');
                document.getElementById('productSlug').classList.add('is-invalid');
            }
            slugContainer.appendChild(feedback);
        })
        .catch(() => {
            if (slug !== latestSlugToCheck) return;
            loadingFeedback.remove();
        });
    }

    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s\u0980-\u09FF-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    function initializeSeoSection() {
        const metaTitleInput = document.getElementById('seoMetaTitle');
        const metaDescriptionInput = document.getElementById('seoMetaDescription');

        if (metaTitleInput) {
            metaTitleInput.addEventListener('input', function() {
                updateCharacterCount(this, 'metaTitleCount', 60);
                updateSeoPreview();
            });
        }

        if (metaDescriptionInput) {
            metaDescriptionInput.addEventListener('input', function() {
                updateCharacterCount(this, 'metaDescriptionCount', 160);
                updateSeoPreview();
            });
        }
    }

    function updateCharacterCount(input, counterId, maxLength) {
        const counter = document.getElementById(counterId);
        if (counter) {
            const currentLength = input.value.length;
            counter.textContent = currentLength;
            if (currentLength > maxLength) {
                counter.classList.add('text-danger');
                counter.classList.remove('text-success');
            } else {
                counter.classList.remove('text-danger');
                counter.classList.add('text-success');
            }
        }
    }

    function updateSeoPreview() {
        const metaTitleInput = document.getElementById('seoMetaTitle');
        const metaDescriptionInput = document.getElementById('seoMetaDescription');
        let seoPreviewContent = '';

        if (metaTitleInput && metaTitleInput.value) {
            seoPreviewContent += `<p class="seo-title"><strong>Meta Title:</strong> ${metaTitleInput.value}</p>`;
        } else {
            seoPreviewContent += `<p class="seo-title"><strong>Meta Title:</strong> Auto-generated from product name</p>`;
        }

        if (metaDescriptionInput && metaDescriptionInput.value) {
            seoPreviewContent += `<p class="seo-description"><strong>Meta Description:</strong> ${metaDescriptionInput.value}</p>`;
        } else {
            seoPreviewContent += `<p class="seo-description"><strong>Meta Description:</strong> Auto-generated from product description</p>`;
        }

        const seoPreview = document.querySelector('.seo-preview');
        if (seoPreview) {
            seoPreview.innerHTML = seoPreviewContent;
        }
    }

    function toggleBookDetails() {
        const bookDetailSection = document.getElementById('bookDetailSection');
        const isBookCheckbox = document.getElementById('isBook');

        if (isBookCheckbox && isBookCheckbox.checked) {
            bookDetailSection.style.display = 'block';
            bookDetailSection.classList.add('show');
            setTimeout(() => {
                bookDetailSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        } else if (bookDetailSection) {
            bookDetailSection.style.display = 'none';
            bookDetailSection.classList.remove('show');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const isBookCheckbox = document.getElementById('isBook');
        if (isBookCheckbox && isBookCheckbox.checked) {
            toggleBookDetails();
        }
    });
</script>
@endpush