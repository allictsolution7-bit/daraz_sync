@extends('layouts.master')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
    .product-form-container {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    .main-content,
    .side-content {
        margin-bottom: 20px;
    }

    .side-content {
        min-width: 320px;
    }

    /* Responsive behavior for smaller screens */
    @media (max-width: 1200px) {
        .product-form-container {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .side-content {
            min-width: auto;
            max-width: 600px;
            margin: 0 auto;
        }
    }

    /* Ensure side content doesn't get too narrow on very wide screens */
    @media (min-width: 1400px) {
        .product-form-container {
            grid-template-columns: minmax(0, 3fr) minmax(320px, 1fr);
        }

        .side-content {
            min-width: 320px;
        }
    }

    .form-section {
        background: #fff;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .form-section:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .form-section h4 {
        margin-bottom: 15px;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        font-weight: 600;
    }

    .variation {
        background: #f8f9fa;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .option {
        background: #fff;
        padding: 15px;
        margin: 10px 0;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    /* .publish-section {
                                                                            position: sticky;
                                                                            top: 5px;
                                                                        } */

    .dropzone {
        border: 2px dashed #0087F7;
        border-radius: 5px;
        background: #f8fafc;
        min-height: 150px;
        padding: 20px;
        margin-bottom: 15px;
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
        border-radius: 4px;
        overflow: hidden;
        position: relative;
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
        background: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .form-floating {
        margin-bottom: 1rem;
    }

    .form-floating>label {
        padding: 0.5rem 0.75rem;
    }

    .form-floating>.form-control {
        padding: 0.5rem 0.75rem;
        height: calc(3.5rem + 2px);
    }

    .form-floating>textarea.form-control {
        height: auto;
        min-height: 100px;
    }

    .card-header-tabs {
        margin-bottom: -0.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #6c757d;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .nav-tabs .nav-link.active {
        color: #197A94;
        border-bottom: 2px solid #197A94;
        background: transparent;
    }

    .product-type-selector {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .product-type-card {
        flex: 1;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .product-type-card:hover {
        border-color: #197A94;
        background: #f8f9fa;
    }

    .product-type-card.active {
        border-color: #197A94;
        background: #f0f7ff;
    }

    .product-type-card i {
        font-size: 24px;
        margin-bottom: 10px;
        color: #6c757d;
    }

    .product-type-card.active i {
        color: #197A94;
    }

    .btn-add-variation {
        background: #f8f9fa;
        border: 1px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        width: 100%;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-add-variation:hover {
        background: #f0f7ff;
        border-color: #197A94;
    }

    /* WordPress/WooCommerce Style Category Multi-Select */
    .category-multiselect-container {
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04);
    }

    .category-panel-header {
        padding: 12px 16px;
        border-bottom: 1px solid #dcdcde;
        background: #f6f7f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .category-panel-title {
        font-weight: 600;
        font-size: 13px;
        color: #23282d;
        margin: 0;
    }

    .category-controls {
        display: flex;
        gap: 4px;
    }

    .category-control-btn {
        width: 24px;
        height: 24px;
        border: 1px solid #8c8f94;
        background: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #50575e;
    }

    .category-control-btn:hover {
        background: #f0f0f1;
    }

    .category-tabs {
        display: flex;
        border-bottom: 1px solid #dcdcde;
        background: #fff;
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .category-tab {
        padding: 12px 16px;
        margin: 0;
        cursor: pointer;
        border: none;
        background: none;
        color: #2271b1;
        font-size: 13px;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }

    .category-tab:hover {
        color: #135e96;
        background: #f6f7f7;
    }

    .category-tab.active {
        border-bottom-color: #2271b1;
        color: #23282d;
        font-weight: 600;
    }

    .category-search-box {
        padding: 12px 16px;
        border-bottom: 1px solid #dcdcde;
        background: #fff;
    }

    .category-search-box input {
        width: 100%;
        padding: 6px 8px;
        border: 1px solid #8c8f94;
        border-radius: 0;
        font-size: 13px;
        background: #fff;
    }

    .category-search-box input:focus {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        outline: 2px solid transparent;
    }

    .category-list-wrapper {
        max-height: 300px;
        overflow-y: auto;
        padding: 8px 0;
        background: #fff;
    }

    .category-list-item {
        display: flex;
        align-items: center;
        padding: 4px 16px;
        min-height: 28px;
    }

    .category-list-item.hidden {
        display: none;
    }

    .category-list-item label {
        display: flex;
        align-items: center;
        cursor: pointer;
        width: 100%;
        margin: 0;
        font-size: 13px;
        line-height: 1.8;
        user-select: none;
    }

    .category-list-item input[type="checkbox"] {
        margin-right: 8px;
        cursor: pointer;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .category-name-text {
        color: #23282d;
    }

    /* Indentation levels */
    .category-level-0 {
        padding-left: 16px;
    }

    .category-level-1 {
        padding-left: 40px;
    }

    .category-level-2 {
        padding-left: 64px;
    }

    .category-selected-count {
        padding: 12px 16px;
        border-top: 1px solid #dcdcde;
        background: #f6f7f7;
        font-size: 12px;
        color: #646970;
    }

    /* Combination table styling */
    #combinationsTable .table {
        margin-bottom: 0;
    }

    #combinationsTable .combination-regular-price,
    #combinationsTable .combination-offer-price,
    #combinationsTable .combination-stock,
    #combinationsTable .combination-description,
    #combinationsTable .combination-image,
    #combinationsTable .combination-gallery {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
        font-size: 13px;
    }

    #combinationsTable .combination-regular-price,
    #combinationsTable .combination-offer-price,
    #combinationsTable .combination-stock {
        text-align: center;
    }

    #combinationsTable .combination-regular-price:focus,
    #combinationsTable .combination-offer-price:focus,
    #combinationsTable .combination-stock:focus,
    #combinationsTable .combination-description:focus,
    #combinationsTable .combination-image:focus,
    #combinationsTable .combination-gallery:focus {
        border-color: #197A94;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    #combinationsTable .combination-description {
        font-size: 12px;
        line-height: 1.4;
    }

    #combinationsTable .combination-image,
    #combinationsTable .combination-gallery {
        font-size: 11px;
    }

    #combinationsTable .badge {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }

    #combinationsTable thead th {
        background: #2c3e50 !important;
        color: white;
        font-weight: 600;
        text-align: center;
    }

    #combinationsTable tbody td {
        vertical-align: middle;
        text-align: center;
    }

    #combinationsTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* SEO Section Styles */
    .seo-preview {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        font-family: Arial, sans-serif;
    }

    .seo-title {
        color: #1a0dab;
        font-size: 18px;
        font-weight: 400;
        line-height: 1.2;
        margin-bottom: 4px;
        cursor: pointer;
    }

    .seo-title:hover {
        text-decoration: underline;
    }

    .seo-url {
        color: #006621;
        font-size: 14px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .seo-description {
        color: #545454;
        font-size: 13px;
        line-height: 1.4;
    }

    .character-count {
        font-size: 12px;
        margin-top: 2px;
    }

    .character-count.warning {
        color: #ffc107;
    }

    .character-count.danger {
        color: #dc3545;
    }

    .character-count.success {
        color: #28a745;
    }

    /* Subcategory loading states */
    .subcategory-loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .subcategory-loading::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #197A94;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: translateY(-50%) rotate(0deg);
        }

        100% {
            transform: translateY(-50%) rotate(360deg);
        }
    }

    a.pos-menu-item {
        display: none !important;
    }

    /* Book Detail Section Animation */
    #bookDetailSection {
        transition: all 0.3s ease;
    }

    #bookDetailSection.show {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Add New Product</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.product.index') }}" class="btn btn-outline-secondary">← Back to Products</a>
    </div>

    @session('success')
    <div class="alert alert-success" role="alert">
        {{ Session('success') }}
    </div>
    @endsession

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="productForm">
        @csrf
        <div class="product-form-container">
            <!-- Main Content Column -->
            <div class="main-content">
                <!-- Basic Information Section -->
                <div class="form-section">
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
                        <small class="form-text text-muted">Unique identifier for inventory tracking. Used for Daraz sync and other marketplace integrations.</small>
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

                <!-- Product Data Section -->
                <div class="form-section">
                    <h4>Product Data</h4>

                    <!-- Product Type Selector Cards -->
                    <div class="product-type-selector">
                        <div class="product-type-card" data-type="simple" onclick="selectProductType('simple')">
                            <i class="bi bi-box"></i>
                            <h6>Simple Product</h6>
                            <small class="text-muted">Single product with no variations</small>
                        </div>
                        <div class="product-type-card" data-type="variable" onclick="selectProductType('variable')">
                            <i class="bi bi-grid-3x3"></i>
                            <h6>Variable Product</h6>
                            <small class="text-muted">Product with multiple variations</small>
                        </div>
                        <div class="product-type-card" data-type="digital" onclick="selectProductType('digital')">
                            <i class="bi bi-file-earmark-arrow-down"></i>
                            <h6>Digital Product</h6>
                            <small class="text-muted">Downloadable product</small>
                        </div>
                        <div class="product-type-card" data-type="affiliate" onclick="selectProductType('affiliate')">
                            <i class="bi bi-link-45deg"></i>
                            <h6>Affiliate Product</h6>
                            <small class="text-muted">External product with commission</small>
                        </div>
                    </div>

                    <input type="hidden" name="product_type" id="productTypeInput" value="simple">

                    <!-- Digital Product Fields -->
                    <div id="digitalFields" class="product-type-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="digitalFile" class="form-label">Digital File</label>
                            <input type="file" class="form-control" id="digital_file" name="digital_file"
                                accept=".pdf,.zip,.rar,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                            <div id="digitalFilePreview" class="mt-2"></div>
                            <input type="hidden" name="digital_file" id="digitalFileInput">
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
                </div>

                <!-- Variations Section -->
                <div class="form-section" id="variationsSection" style="display: none;">
                    <h4>Product Variations</h4>
                    <div class="alert alert-info">
                        <strong>How it works:</strong> Add variation groups (like Size, Color) and their options. The
                        system will automatically generate all possible combinations and let you set individual prices
                        and stock for each combination.
                        <br><small class="text-muted mt-1">
                            <strong>Note:</strong> Even single variations (e.g., Size-only) will create individual
                            combinations for each option.
                        </small>
                    </div>
                    <div id="variations">
                        <!-- Variations will be added here -->
                    </div>
                    <button type="button" class="btn-add-variation" onclick="addVariation()">
                        <i class="bi bi-plus-circle"></i> Add Variation Group
                    </button>

                    <!-- Combinations Preview -->
                    <div id="combinationsPreview" style="display: none; margin-top: 30px;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> Variation Combinations</h5>
                            <span id="combinationsCount" class="badge bg-success fs-6">0 combinations</span>
                        </div>
                        <p class="text-muted mb-3">
                            <i class="bi bi-info-circle"></i>
                            Set individual prices and stock quantities for each combination. These will be used on the
                            frontend when customers select variations.
                        </p>
                        <div id="combinationsTable"></div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="side-content">
                <!-- Publish Section -->
                <div class="form-section publish-section">
                    <h4>Publish</h4>
                    <div class="mb-3">
                        <label for="productStatus" class="form-label">Status</label>
                        <select class="form-select" name="status" id="productStatus">
                            <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Published</option>
                            <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Create Product</button>
                </div>

                <!-- Product Images Section -->
                <div class="form-section">
                    <h4>Product Images</h4>
                    <div class="mb-3">
                        <label for="thumb_image">Product Featured Image</label>
                        <input type="file" class="form-control" id="thumb_image" name="thumb_image"
                            accept="image/jpeg,image/png,image/jpg,image/gif" required>
                        <div id="thumbnailPreview" class="mt-2" style="max-width: 200px;" required></div>
                        @error('thumb_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="thumb_image">Product Featured Gallery Images</label>
                        <input type="file" class="form-control" id="images" name="images[]"
                            accept="image/jpeg,image/png,image/jpg,image/gif" multiple>
                        <div id="galleryPreview" class="image-preview mt-2"></div>
                        @error('images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <!-- Categories Section -->
                <div class="form-sectione">
                    <h4 style="display: none;">Categories</h4>
                    <div class="alert alert-info mb-3" role="alert" style="display: none;">
                        <i class="bi bi-info-circle"></i>
                        <strong>Note:</strong> Primary category and subcategory fields below are optional and maintained for backward compatibility.
                        You can use the multi-select category selector below to assign multiple categories, subcategories, and third-level categories.
                        At least one category must be selected from either the primary field or the multi-select.
                    </div>
                    <div class="mb-3" style="display: none;">
                        <label for="productCategory" class="form-label">Primary Category <small class="text-muted">(Optional - for backward compatibility)</small></label>
                        <select class="form-select" name="category_id" id="productCategory">
                            <option value="">Select Category (Optional)</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" style="display: none;">
                        <label for="productSubCategory" class="form-label">Primary Sub Category <small class="text-muted">(Optional - for backward compatibility)</small></label>
                        <select class="form-select" name="sub_category_id" id="productSubCategory">
                            <option value="">Select Sub Category (Optional)</option>
                            @foreach ($sub_categories as $sub_category)
                            <option value="{{ $sub_category->id }}"
                                {{ old('sub_category_id') === $sub_category->id ? 'selected' : '' }}>
                                        {{ $sub_category->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle"></i>
                            Subcategories will be loaded automatically when you select a primary category
                        </small>
                    </div>

                    <!-- WordPress/WooCommerce Style Category Multi-Select -->
                    <div class="mb-3">
                        <div class="category-multiselect-container">
                            <div class="category-panel-header">
                                <h3 class="category-panel-title">Product categories</h3>
                                <!-- <div class="category-controls">
                                        <button type="button" class="category-control-btn" title="Toggle panel">
                                            <span>▲</span>
                                        </button>
                                        <button type="button" class="category-control-btn" title="Toggle panel">
                                            <span>▼</span>
                                        </button>
                                    </div> -->
                            </div>
                            <div class="category-tabs">
                                <button type="button" class="category-tab active" data-tab="all">All categories</button>
                                <button type="button" class="category-tab" data-tab="most-used">Most Used</button>
                            </div>
                            <div class="category-search-box">
                                <input type="text"
                                    id="categorySearch"
                                    placeholder="Search categories..."
                                    autocomplete="off">
                            </div>
                            <div class="category-list-wrapper" id="categoryListWrapper">
                                @php
                                $oldAdditionalCategories = old('additional_categories', []);
                                $oldAdditionalSubcategories = old('additional_subcategories', []);
                                $oldThirdCategories = old('third_categories', []);
                                @endphp

                                @foreach ($categories as $category)
                                <!-- Primary Category (Level 0) -->
                                <div class="category-list-item category-level-0"
                                    data-category-name="{{ strtolower($category->name) }}"
                                    data-level="0"
                                    data-category-id="{{ $category->id }}">
                                    <label>
                                        <input type="checkbox"
                                            name="additional_categories[]"
                                            value="{{ $category->id }}"
                                            id="category_{{ $category->id }}"
                                            class="category-checkbox"
                                            {{ in_array($category->id, $oldAdditionalCategories) ? 'checked' : '' }}>
                                        <span class="category-name-text">{{ $category->name }}</span>
                                    </label>
                                </div>

                                @if($category->subCategories && $category->subCategories->count() > 0)
                                @foreach($category->subCategories as $subCategory)
                                <!-- Sub Category (Level 1) -->
                                <div class="category-list-item category-level-1"
                                    data-category-name="{{ strtolower($subCategory->name . ' ' . $category->name) }}"
                                    data-level="1"
                                    data-parent-id="{{ $category->id }}"
                                    data-category-id="{{ $subCategory->id }}">
                                    <label>
                                        <input type="checkbox"
                                            name="additional_subcategories[]"
                                            value="{{ $subCategory->id }}"
                                            id="subcategory_{{ $subCategory->id }}"
                                            class="category-checkbox additional-subcategory-checkbox"
                                            {{ in_array($subCategory->id, $oldAdditionalSubcategories) ? 'checked' : '' }}>
                                        <span class="category-name-text">{{ $subCategory->name }}</span>
                                    </label>
                                </div>

                                @if($subCategory->thirdCategories && $subCategory->thirdCategories->count() > 0)
                                @foreach($subCategory->thirdCategories as $thirdCategory)
                                <!-- Third Level Category (Level 2) -->
                                <div class="category-list-item category-level-2"
                                    data-category-name="{{ strtolower($thirdCategory->name . ' ' . $subCategory->name . ' ' . $category->name) }}"
                                    data-level="2"
                                    data-parent-id="{{ $subCategory->id }}"
                                    data-category-id="{{ $thirdCategory->id }}">
                                    <label>
                                        <input type="checkbox"
                                            name="third_categories[]"
                                            value="{{ $thirdCategory->id }}"
                                            id="thirdcategory_{{ $thirdCategory->id }}"
                                            class="category-checkbox thirdcategory-checkbox"
                                            {{ in_array($thirdCategory->id, $oldThirdCategories) ? 'checked' : '' }}>
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
                        <small class="form-text text-muted mt-2">
                            <i class="bi bi-info-circle"></i>
                            Select categories. The product will appear in all selected categories.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="productBrand" class="form-label">Brand</label>
                        <div class="d-flex gap-2">
                            <select class="form-select" name="brand_id" id="productBrand">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}
                                </option>
                                @endforeach
                            </select>
                            {{-- <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createBrandModal">
                                    <i class="bi bi-plus"></i> New
                                </button> --}}
                        </div>
                        <small class="form-text text-muted">Optional: Select a brand for this product or create a new one</small>
                    </div>
                </div>

                <!-- Tags Section -->
                <div class="form-section">
                    <h4>Tags</h4>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="tags" id="productTags"
                            placeholder="Enter tags separated by commas" value="{{ old('tags') }}">
                        <small class="text-muted">Separate tags with commas</small>
                    </div>
                </div>

                <!-- Others Section -->
                <div class="form-section">
                    <h4>Others</h4>
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
                    <div class="form-group">
                        <label for="video_url">Product Video URL (YouTube)</label>
                        <input type="text" name="video_url" id="video_url" class="form-control"
                            value="{{ old('video_url', $product->video_url ?? '') }}"
                            placeholder="https://www.youtube.com/watch?v=xxxxxx">
                        <small class="form-text text-muted">Leave blank to use the global video URL.</small>
                    </div>
                </div>



            </div>

        </div>

        <div class="row mb-5" id="bookDetailSection" style="display: none;">
            <h4 class="mb-3"><i class="bi bi-book"></i> Book Detail</h4>

            <div class="col">
                <!-- <div class="mb-3">
                                    <label for="subject">Subject</label>
                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="Enter subject" value="{{ old('subject') }}">
                                </div> -->
                <div class="mb-3">
                    <label for="edition">Edition</label>
                    <input type="text" class="form-control" name="edition" id="edition"
                        placeholder="Enter edition" value="{{ old('edition') }}">
                </div>
                <div class="mb-3">
                    <label for="isbn">ISBN</label>
                    <input type="text" class="form-control" name="isbn" id="isbn"
                        placeholder="Enter isbn" value="{{ old('isbn') }}">
                </div>
                <div class="mb-3">
                    <label for="Language">Language</label>
                    <select class="form-control" name="language">
                        <option value="">Select Language</option>
                        <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                        <option value="Bangla" {{ old('language') == 'Bangla' ? 'selected' : '' }}>Bangla</option>
                        <option value="Hindi" {{ old('language') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="sample_path">Upload Sample File</label>
                    <input type="file" class="form-control" name="sample_path" id="sample_path"
                        placeholder="Upload sample file">
                </div>
                <div class="mb-3">
                    <label for="pages">Total Pages</label>
                    <input type="number" class="form-control" name="pages" id="pages"
                        placeholder="Enter total pages" value="{{ old('pages') }}">
                </div>
                <div class="mb-3">
                    <label for="Cover">Cover</label>
                    <select class="form-control" name="cover">
                        <option value="">Select Cover</option>
                        <option value="Hardcover" {{ old('cover') == 'Hardcover' ? 'selected' : '' }}>Hardcover
                        </option>
                        <option value="Paperback" {{ old('cover') == 'Paperback' ? 'selected' : '' }}>Paperback
                        </option>
                        <option value="Ebook" {{ old('cover') == 'Ebook' ? 'selected' : '' }}>Ebook</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="country">Country</label>
                    <select class="form-control" name="country">
                        <option value="">Select Country</option>
                        <option value="Bangladesh" {{ old('country') == 'Bangladesh' ? 'selected' : '' }}>Bangladesh
                        </option>
                        <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                        <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>USA</option>
                        <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>UK</option>
                        <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                        <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia
                        </option>
                        <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="col">

                <div class="form-section">
                    <div class="form-floating1 mb-3"></div>
                    <label for="productWriters">Writers</label>
                    <select class="form-select @error('writers') is-invalid @enderror" id="productWriters"
                        name="writers[]" multiple>
                        @foreach ($writers as $writer)
                        <option value="{{ $writer->id }}"
                            {{ in_array($writer->id, old('writers', [])) ? 'selected' : '' }}>
                            {{ $writer->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('writers')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                <div class="form-section">
                    <div class="form-floating1 mb-3"></div>
                    <label for="publisher">Publisher</label>
                    <select class="form-select @error('publisher') is-invalid @enderror" id="publisher"
                        name="publisher">
                        @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}"
                            {{ old('publisher') == $publisher->id ? 'selected' : '' }}>
                            {{ $publisher->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('publisher')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>

        <!-- SEO Section -->
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="form-section">
                    <h4><i class="bi bi-search"></i> SEO Settings</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="seo[meta_title]" id="seoMetaTitle"
                                    placeholder="Enter SEO title" value="" maxlength="60">
                                <label for="seoMetaTitle">Meta Title</label>
                                <small class="form-text text-muted">
                                    <span id="metaTitleCount">0</span>/60 characters. Leave empty to auto-generate from
                                    product title.
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="seo[meta_description]" id="seoMetaDescription"
                                    placeholder="Enter SEO description" style="height: 100px;" maxlength="160"></textarea>
                                <label for="seoMetaDescription">Meta Description</label>
                                <small class="form-text text-muted">
                                    <span id="metaDescriptionCount">0</span>/160 characters. Leave empty to
                                    auto-generate from product description.
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="seo[meta_keywords]"
                                    id="seoMetaKeywords" placeholder="Enter SEO keywords" value="">
                                <label for="seoMetaKeywords">Meta Keywords</label>
                                <small class="form-text text-muted">Comma-separated keywords. Leave empty to
                                    auto-generate from product tags and category.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="url" class="form-control" name="seo[canonical_url]"
                                    id="seoCanonicalUrl" placeholder="Enter canonical URL" value="">
                                <label for="seoCanonicalUrl">Canonical URL</label>
                                <small class="form-text text-muted">Leave empty to use the default product URL.</small>
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
                                <label for="seoMetaRobots">Meta Robots</label>
                                <small class="form-text text-muted">Search engine crawling instructions.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="seoOgImage" class="form-label">Open Graph Image</label>
                                <input type="file" class="form-control" name="seo[og_image]" id="seoOgImage" accept="image/*">
                                <small class="form-text text-muted">Custom image for social media sharing. Leave empty to use product featured image.</small>
                                <div id="ogImagePreview" class="mt-2" style="max-width: 200px;"></div>
                                <!-- Hidden field for consistency with edit form -->
                                <input type="hidden" name="seo[existing_og_image]" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="seoSchemaMarkup" class="form-label">Schema Markup (JSON-LD)</label>
                                <textarea class="form-control" name="seo[schema_markup]" id="seoSchemaMarkup" rows="6"></textarea>
                                <small class="form-text text-muted">Custom JSON-LD schema markup. Leave empty to
                                    auto-generate basic product schema.</small>
                                <div class="alert alert-info mt-2">
                                    <strong>Example:</strong><br>
                                    <code>{"@@context":"https://schema.org","@@type":"Product","name":"Product Name","description":"Product Description"}</code>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-eye"></i> SEO Preview
                                    </h6>
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

    </form>
</div>

<!-- Create Brand Modal -->
<div class="modal fade" id="createBrandModal" tabindex="-1" aria-labelledby="createBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBrandModalLabel">Create New Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createBrandForm" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="brandName" class="form-label">Brand Name *</label>
                                <input type="text" class="form-control" id="brandName" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="brandSlug" class="form-label">Brand Slug</label>
                                <input type="text" class="form-control" id="brandSlug" name="slug" placeholder="Auto-generated from name">
                                <small class="form-text text-muted">Leave empty to auto-generate</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="brandDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="brandDescription" name="description" rows="3" placeholder="Brief description of the brand"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="brandWebsite" class="form-label">Website</label>
                                <input type="url" class="form-control" id="brandWebsite" name="website" placeholder="https://example.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="brandLogo" class="form-label">Brand Logo</label>
                                <input type="file" class="form-control" id="brandLogo" name="logo" accept="image/*">
                                <small class="form-text text-muted">Optional: Upload brand logo</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="brandStatus" name="status" value="1" checked>
                            <label class="form-check-label" for="brandStatus">Active</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="brandMetaTitle" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="brandMetaTitle" name="meta_title" maxlength="60">
                                <small class="form-text text-muted">Max 60 characters</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="brandMetaKeywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="brandMetaKeywords" name="meta_keywords" placeholder="keyword1, keyword2">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="brandMetaDescription" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="brandMetaDescription" name="meta_description" rows="2" maxlength="160"></textarea>
                                <small class="form-text text-muted">Max 160 characters</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="createBrandBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Create Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

<script>
    // Initialize Summernote for product description
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

    // Adjust height for short description
    $('#productShortDescription').summernote('height', 150);

    // Product Type Selection
    function selectProductType(type) {
        // Update hidden input
        document.getElementById('productTypeInput').value = type;

        // Update UI
        document.querySelectorAll('.product-type-card').forEach(card => {
            card.classList.remove('active');
        });
        document.querySelector(`.product-type-card[data-type="${type}"]`).classList.add('active');

        // Show/hide relevant fields
        const digitalFields = document.getElementById('digitalFields');
        const affiliateFields = document.getElementById('affiliateFields');
        const variationsSection = document.getElementById('variationsSection');

        // Hide all product type specific fields first
        document.querySelectorAll('.product-type-fields').forEach(el => {
            el.style.display = 'none';
        });
        variationsSection.style.display = 'none';

        // Show/hide pricing fields based on product type
        if (type === 'variable') {
            pricingFields.style.display = 'none';
            // Clear pricing fields for variable products
            document.getElementById('productOldPrice').value = '';
            document.getElementById('productOfferPrice').value = '';
            // Remove name attributes to prevent submission
            document.getElementById('productOldPrice').removeAttribute('name');
            document.getElementById('productOfferPrice').removeAttribute('name');
            document.getElementById('productOldPrice').removeAttribute('required');
        } else {
            pricingFields.style.display = 'block';
            // Restore name attributes for non-variable products
            document.getElementById('productOldPrice').setAttribute('name', 'old_price');
            document.getElementById('productOfferPrice').setAttribute('name', 'offer');
        }

        // Show fields based on product type
        if (type === 'digital') {
            digitalFields.style.display = 'block';
        } else if (type === 'affiliate') {
            affiliateFields.style.display = 'block';
        } else if (type === 'variable') {
            variationsSection.style.display = 'block';
        }

        // Clear validation errors for fields that are not relevant to the selected product type
        if (type !== 'affiliate') {
            // Clear affiliate fields if not an affiliate product
            document.getElementById('externalUrl').value = '';
            document.getElementById('affiliateCommission').value = '';
        }
    }

    // Variation functions remain unchanged
    function addVariation() {
        const variationsDiv = document.getElementById('variations');
        const variationCount = variationsDiv.children.length;

        const variationDiv = document.createElement('div');
        variationDiv.classList.add('variation');

        variationDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Variation Group</h5>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariation(this)">
                        X Remove
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
                                <i class="bi bi-info-circle"></i>
                                <strong>Note:</strong> Only option names are needed here. Pricing, stock, descriptions, and images are managed in the <strong>Combinations table</strong> below.
                            </small>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addOption(this)">
                    <i class="bi bi-plus"></i> Add Option
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

        const variationIndex = Array.from(document.querySelectorAll('.variation')).indexOf(button.closest(
            '.variation'));

        optionDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Option ${optionCount + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(this)">
                        X
                    </button>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control variation-option-input" name="variations[${variationIndex}][options][${optionCount}][name]" placeholder="Enter option name" required onchange="updateCombinationsPreview()">
                    <label>Option Name (e.g. Small, Red)</label>
                </div>

                <div class="alert alert-info p-2">
                    <small>
                        <i class="bi bi-info-circle"></i>
                        Option details are managed in the Combinations table.
                    </small>
                </div>
            `;

        optionsDiv.appendChild(optionDiv);
        updateCombinationsPreview();
    }

    function removeOption(button) {
        button.closest('.option').remove();
        updateCombinationsPreview();
    }

    // Update combinations preview
    function updateCombinationsPreview() {
        const variations = document.querySelectorAll('.variation');
        const combinationsPreview = document.getElementById('combinationsPreview');
        const combinationsTable = document.getElementById('combinationsTable');

        if (variations.length === 0) {
            combinationsPreview.style.display = 'none';
            return;
        }

        // Collect all variations and their options
        const variationData = [];
        let hasValidOptions = false;

        variations.forEach((variation, variationIndex) => {
            const variationNameInput = variation.querySelector(`input[name*="[name]"]`);
            const variationName = variationNameInput ? variationNameInput.value.trim() : '';

            if (!variationName) return;

            const options = [];
            const optionInputs = variation.querySelectorAll('.variation-option-input');

            optionInputs.forEach((optionInput, optionIndex) => {
                const optionName = optionInput.value.trim();
                if (optionName) {
                    // Options only have names - pricing and stock are set per combination
                    options.push({
                        name: optionName
                    });
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

        // Generate combinations using Cartesian product
        const combinations = cartesianProduct(variationData.map(v => v.options));
        const variationNames = variationData.map(v => v.name);

        // Build editable preview table
        let tableHTML = `
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
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

            const comboId = `combo_${index}`;

            tableHTML += `
                    <td>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               class="form-control combination-regular-price" 
                               name="combinations[${index}][regular_price]" 
                               value=""
                               placeholder="0.00"
                               style="width: 90px;">
                    </td>
                    <td>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               class="form-control combination-offer-price" 
                               name="combinations[${index}][offer_price]" 
                               value=""
                               placeholder="Optional"
                               style="width: 90px;">
                    </td>
                    <td>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               class="form-control combination-product-cost" 
                               name="combinations[${index}][product_cost]" 
                               value=""
                               placeholder="Cost"
                               style="width: 90px;">
                    </td>
                    <td>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               class="form-control combination-wholesale-price" 
                               name="combinations[${index}][wholesale_price]" 
                               value=""
                               placeholder="Wholesale"
                               style="width: 90px;">
                    </td>
                    <td>
                        <input type="number" 
                               min="0" 
                               class="form-control combination-stock" 
                               name="combinations[${index}][stock_quantity]" 
                               value=""
                               placeholder="0"
                               style="width: 70px;">
                    </td>
                    <td>
                        <textarea class="form-control combination-description" 
                                  name="combinations[${index}][short_description]" 
                                  rows="2" 
                                  placeholder="Brief description..."
                                  style="width: 200px; resize: vertical;"></textarea>
                    </td>
                    <td>
                        <div class="mb-2">
                            <input type="file" 
                                   class="form-control combination-image" 
                                   name="combinations[${index}][featured_image]" 
                                   accept="image/*"
                                   style="width: 150px; font-size: 11px;">
                            <small class="text-muted d-block">Featured Image</small>
                        </div>
                        <div>
                            <input type="file" 
                                   class="form-control combination-gallery" 
                                   name="combinations[${index}][gallery_images][]" 
                                   accept="image/*"
                                   multiple
                                   style="width: 150px; font-size: 11px;">
                            <small class="text-muted d-block">Gallery Images</small>
                        </div>
                    </td>
                    <input type="hidden" name="combinations[${index}][key]" value="${combinationKey.join('_')}">
                </tr>`;
        });

        tableHTML += `
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info mt-3">
                    <strong><i class="bi bi-info-circle"></i> Rich Combination System:</strong>
                    <ul class="mb-0 mt-2">
                        <li><strong>Regular Price:</strong> Base price for this combination - leave empty if not applicable (will default to 0 in backend)</li>
                        <li><strong>Offer Price:</strong> Optional discount price - leave empty if no offer</li>
                        <li><strong>Stock:</strong> Inventory quantity for this specific combination - leave empty if not applicable (will default to 0 in backend)</li>
                        <li><strong>Description:</strong> Optional custom description shown to customers</li>
                        <li><strong>Featured Image:</strong> Main image for this combination</li>
                        <li><strong>Gallery Images:</strong> Additional images for this combination (multiple files supported)</li>
                        <li>Total of <strong>${combinations.length}</strong> combinations will be created</li>
                        <li><strong>Single variations</strong> (e.g., Size-only) create individual combinations (Size-Small, Size-Long, etc.)</li>
                        <li><strong>Multiple variations</strong> (e.g., Size + Color) create combined combinations (Size-Small + Color-Red, etc.)</li>
                    </ul>
                </div>`;

        combinationsTable.innerHTML = tableHTML;
        combinationsPreview.style.display = 'block';

        // Update combinations count
        document.getElementById('combinationsCount').textContent =
            `${combinations.length} combination${combinations.length !== 1 ? 's' : ''}`;
    }

    // Helper function for Cartesian product
    function cartesianProduct(arrays) {
        // Handle single variation group (e.g., only Size)
        if (arrays.length === 1) {
            return arrays[0].map(option => [option]);
        }

        // Handle multiple variation groups using reduce
        return arrays.reduce((a, b) =>
            a.flatMap(x => b.map(y => [...(Array.isArray(x) ? x : [x]), y]))
        );
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Featured Image Preview
        const thumbImageInput = document.getElementById('thumb_image');
        const thumbnailPreview = document.getElementById('thumbnailPreview');

        thumbImageInput.addEventListener('change', function() {
            thumbnailPreview.innerHTML = '';

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-fluid', 'rounded');
                    thumbnailPreview.appendChild(img);
                }

                reader.readAsDataURL(this.files[0]);
            }
        });

        // Gallery Images Preview with proper removal
        const galleryImagesInput = document.getElementById('images');
        const galleryPreview = document.getElementById('galleryPreview');
        let selectedFiles = []; // Array to store selected files

        galleryImagesInput.addEventListener('change', function() {
            // Store the selected files
            const newFiles = Array.from(this.files);
            selectedFiles = selectedFiles.concat(newFiles);

            // Update the preview
            updateGalleryPreview();
        });

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
                    removeBtn.innerHTML = 'x';
                    removeBtn.addEventListener('click', function() {
                        // Remove the file from the selectedFiles array
                        selectedFiles.splice(index, 1);
                        // Update the preview
                        updateGalleryPreview();
                    });

                    previewItem.appendChild(img);
                    previewItem.appendChild(removeBtn);
                    galleryPreview.appendChild(previewItem);
                }

                reader.readAsDataURL(file);
            });
        }

        // Handle form submission to create a new FileList from selectedFiles
        const productForm = document.getElementById('productForm');
        productForm.addEventListener('submit', function(e) {
            if (selectedFiles.length > 0) {
                // Create a new DataTransfer object
                const dataTransfer = new DataTransfer();

                // Add each selected file to the DataTransfer object
                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });

                // Set the new FileList to the file input
                galleryImagesInput.files = dataTransfer.files;
            }
        });

        // SEO Section Functionality
        initializeSeoSection();

        // OG Image Preview
        const ogImageInput = document.getElementById('seoOgImage');
        const ogImagePreview = document.getElementById('ogImagePreview');

        if (ogImageInput) {
            ogImageInput.addEventListener('change', function() {
                ogImagePreview.innerHTML = '';

                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-fluid', 'rounded');
                        ogImagePreview.appendChild(img);
                    }

                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });

    // SEO Section Functions
    function initializeSeoSection() {
        // Character counters
        const metaTitleInput = document.getElementById('seoMetaTitle');
        const metaDescriptionInput = document.getElementById('seoMetaDescription');
        const metaKeywordsInput = document.getElementById('seoMetaKeywords');
        const canonicalUrlInput = document.getElementById('seoCanonicalUrl');
        const metaRobotsSelect = document.getElementById('seoMetaRobots');
        const schemaMarkupInput = document.getElementById('seoSchemaMarkup');

        // Character count updates
        if (metaTitleInput) {
            metaTitleInput.addEventListener('input', function() {
                updateCharacterCount(this, 'metaTitleCount', 60);
            });
        }

        if (metaDescriptionInput) {
            metaDescriptionInput.addEventListener('input', function() {
                updateCharacterCount(this, 'metaDescriptionCount', 160);
            });
        }

        if (metaKeywordsInput) {
            metaKeywordsInput.addEventListener('input', function() {
                // No specific character limit for keywords, just update
            });
        }

        if (canonicalUrlInput) {
            canonicalUrlInput.addEventListener('input', function() {
                // No specific character limit for canonical URL, just update
            });
        }

        if (metaRobotsSelect) {
            metaRobotsSelect.addEventListener('change', function() {
                // No specific character limit for robots, just update
            });
        }

        if (schemaMarkupInput) {
            schemaMarkupInput.addEventListener('input', function() {
                // No specific character limit for schema markup, just update
            });
        }

        // Initial update to set correct counts on page load
        updateCharacterCount(metaTitleInput, 'metaTitleCount', 60);
        updateCharacterCount(metaDescriptionInput, 'metaDescriptionCount', 160);

        // Update SEO preview on input changes
        if (metaTitleInput) {
            metaTitleInput.addEventListener('input', updateSeoPreview);
        }
        if (metaDescriptionInput) {
            metaDescriptionInput.addEventListener('input', updateSeoPreview);
        }
        if (metaKeywordsInput) {
            metaKeywordsInput.addEventListener('input', updateSeoPreview);
        }
        if (canonicalUrlInput) {
            canonicalUrlInput.addEventListener('input', updateSeoPreview);
        }
        if (metaRobotsSelect) {
            metaRobotsSelect.addEventListener('change', updateSeoPreview);
        }
        if (schemaMarkupInput) {
            schemaMarkupInput.addEventListener('input', updateSeoPreview);
        }
        if (ogImageInput) {
            ogImageInput.addEventListener('change', updateSeoPreview);
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

    // Slug generation functionality
    document.addEventListener('DOMContentLoaded', function() {
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

            // Ensure slug is included in form submission by removing the flag before submit
            const form = productSlugInput.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    // Remove the manuallyEdited flag before submission to ensure the slug value is sent
                    productSlugInput.removeAttribute('data-manually-edited');
                });
            }
        }
    });

    // AJAX function to check slug availability
    // AJAX function to check slug availability
    let latestSlugToCheck = '';

    function checkSlugAvailability(slug) {
        latestSlugToCheck = slug;
        if (slug.trim() === '') return;

        // Remove existing feedback
        const existingFeedbacks = document.querySelectorAll('.slug-feedback');
        existingFeedbacks.forEach(el => el.remove());

        // Show loading state
        const slugContainer = document.getElementById('productSlug').parentElement;
        const loadingFeedback = document.createElement('div');
        loadingFeedback.className = 'slug-feedback form-text text-muted';
        loadingFeedback.innerHTML = '<i class="bi bi-hourglass-split"></i> Checking availability...';
        slugContainer.appendChild(loadingFeedback);

        // Make AJAX request
        fetch(`/admin/product-slug-availability?slug=${encodeURIComponent(slug)}`, {
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

                // Remove any other existing feedback just in case
                document.querySelectorAll('.slug-feedback').forEach(el => el.remove());

                const feedback = document.createElement('div');
                feedback.className = 'slug-feedback form-text';

                if (data.available) {
                    feedback.innerHTML = '<i class="bi bi-check-circle text-success"></i> Slug is available!';
                    feedback.classList.add('text-success');
                    document.getElementById('productSlug').classList.remove('is-invalid');
                    document.getElementById('productSlug').classList.add('is-valid');
                } else {
                    feedback.innerHTML = '<i class="bi bi-exclamation-triangle text-warning"></i> Slug already exists. Consider adding a number or different text.';
                    feedback.classList.add('text-warning');
                    document.getElementById('productSlug').classList.remove('is-valid');
                    document.getElementById('productSlug').classList.add('is-invalid');
                }

                slugContainer.appendChild(feedback);
            })
            .catch(error => {
                if (slug !== latestSlugToCheck) return;

                loadingFeedback.remove();
                console.error('Error checking slug availability:', error);

                const feedback = document.createElement('div');
                feedback.className = 'slug-feedback form-text text-muted';
                feedback.innerHTML = '<i class="bi bi-info-circle"></i> Could not verify slug availability.';
                slugContainer.appendChild(feedback);
            });
    }

    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s\u0980-\u09FF-]/g, '') // Remove special characters except spaces, hyphens, and Bengali characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }

    function updateSeoPreview() {
        const productName = document.getElementById('productName').value;
        const productShortDescription = document.getElementById('productShortDescription').value;
        const productDescription = document.getElementById('productDescription').value;
        const productCategory = document.getElementById('productCategory').value;
        const productSubCategory = document.getElementById('productSubCategory').value;
        const productTags = document.getElementById('productTags').value;
        const productWriters = document.getElementById('productWriters').value;
        const productPublisher = document.getElementById('publisher').value;
        const ogImageInput = document.getElementById('seoOgImage');

        const metaTitleInput = document.getElementById('seoMetaTitle');
        const metaDescriptionInput = document.getElementById('seoMetaDescription');
        const metaKeywordsInput = document.getElementById('seoMetaKeywords');
        const canonicalUrlInput = document.getElementById('seoCanonicalUrl');
        const metaRobotsSelect = document.getElementById('seoMetaRobots');
        const schemaMarkupInput = document.getElementById('seoSchemaMarkup');

        let seoPreviewContent = '';

        if (metaTitleInput && metaTitleInput.value) {
            seoPreviewContent += `<p class="seo-title"><strong>Meta Title:</strong> ${metaTitleInput.value}</p>`;
        } else {
            seoPreviewContent +=
                `<p class="seo-title"><strong>Meta Title:</strong> Auto-generated from product name</p>`;
        }

        if (metaDescriptionInput && metaDescriptionInput.value) {
            seoPreviewContent +=
                `<p class="seo-description"><strong>Meta Description:</strong> ${metaDescriptionInput.value}</p>`;
        } else {
            seoPreviewContent +=
                `<p class="seo-description"><strong>Meta Description:</strong> Auto-generated from product description</p>`;
        }

        if (metaKeywordsInput && metaKeywordsInput.value) {
            seoPreviewContent +=
                `<p class="seo-description"><strong>Meta Keywords:</strong> ${metaKeywordsInput.value}</p>`;
        } else {
            seoPreviewContent +=
                `<p class="seo-description"><strong>Meta Keywords:</strong> Auto-generated from product tags and category</p>`;
        }

        if (canonicalUrlInput && canonicalUrlInput.value) {
            seoPreviewContent += `<p class="seo-url"><strong>Canonical URL:</strong> ${canonicalUrlInput.value}</p>`;
        } else {
            seoPreviewContent += `<p class="seo-url"><strong>Canonical URL:</strong> Default product URL</p>`;
        }

        if (metaRobotsSelect && metaRobotsSelect.value) {
            seoPreviewContent +=
                `<p class="seo-description"><strong>Meta Robots:</strong> ${metaRobotsSelect.value}</p>`;
        } else {
            seoPreviewContent += `<p class="seo-description"><strong>Meta Robots:</strong> Default (Index, Follow)</p>`;
        }

        if (ogImageInput && ogImageInput.files && ogImageInput.files[0]) {
            seoPreviewContent += `<p class="seo-description"><strong>OG Image:</strong> Custom image selected</p>`;
        } else {
            seoPreviewContent += `<p class="seo-description"><strong>OG Image:</strong> Product featured image</p>`;
        }

        if (schemaMarkupInput && schemaMarkupInput.value) {
            seoPreviewContent += `<p class="seo-description"><strong>Schema Markup:</strong> Custom JSON-LD</p>`;
        } else {
            seoPreviewContent +=
                `<p class="seo-description"><strong>Schema Markup:</strong> Auto-generated basic product schema</p>`;
        }

        const seoPreview = document.querySelector('.seo-preview');
        if (seoPreview) {
            seoPreview.innerHTML = seoPreviewContent;
        }
    }

    // Dynamic Subcategory Loading
    document.addEventListener('DOMContentLoaded', function() {
        // Category change event for dynamic subcategory loading
        const productCategorySelect = document.getElementById('productCategory');
        const productSubCategorySelect = document.getElementById('productSubCategory');

        if (productCategorySelect && productSubCategorySelect) {
            productCategorySelect.addEventListener('change', function() {
                const selectedCategoryId = this.value;

                // Clear any previously selected subcategory
                productSubCategorySelect.value = '';

                if (selectedCategoryId) {
                    loadSubcategories(selectedCategoryId);
                } else {
                    // Clear subcategories if no category is selected
                    clearSubcategories();
                }
            });

            // If there's a pre-selected category (e.g., from validation errors), load its subcategories
            const preSelectedCategoryId = productCategorySelect.value;
            if (preSelectedCategoryId) {
                loadSubcategories(preSelectedCategoryId);
            }
        }

        // Brand slug generation
        const brandNameInput = document.getElementById('brandName');
        const brandSlugInput = document.getElementById('brandSlug');

        if (brandNameInput && brandSlugInput) {
            brandNameInput.addEventListener('input', function() {
                if (!brandSlugInput.dataset.manuallyEdited) {
                    const slug = generateSlug(this.value);
                    brandSlugInput.value = slug;
                }
            });

            brandSlugInput.addEventListener('input', function() {
                this.dataset.manuallyEdited = 'true';
            });

            brandSlugInput.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.dataset.manuallyEdited = 'false';
                    const slug = generateSlug(brandNameInput.value);
                    this.value = slug;
                }
            });
        }

        // Brand form submission
        const createBrandForm = document.getElementById('createBrandForm');
        if (createBrandForm) {
            createBrandForm.addEventListener('submit', function(e) {
                e.preventDefault();
                createBrand();
            });
        }
    });

    function createBrand() {
        const form = document.getElementById('createBrandForm');
        const formData = new FormData(form);
        const submitBtn = document.getElementById('createBrandBtn');
        const spinner = submitBtn.querySelector('.spinner-border');

        // Show loading state
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        fetch('/admin/brands', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add new brand to select dropdown
                    const brandSelect = document.getElementById('productBrand');
                    const newOption = document.createElement('option');
                    newOption.value = data.brand.id;
                    newOption.textContent = data.brand.name;
                    newOption.selected = true;
                    brandSelect.appendChild(newOption);

                    // Close modal and reset form
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createBrandModal'));
                    modal.hide();
                    form.reset();

                    // Show success message
                    showAlert('Brand created successfully!', 'success');
                } else {
                    showAlert(data.message || 'Failed to create brand', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while creating the brand', 'danger');
            })
            .finally(() => {
                // Reset loading state
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            });
    }

    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

        // Insert alert at the top of the page
        const container = document.querySelector('.container-fluid');
        container.insertBefore(alertDiv, container.firstChild);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Function to load subcategories based on selected category
    function loadSubcategories(categoryId) {
        const subCategorySelect = document.getElementById('productSubCategory');

        // Validate input
        if (!categoryId || !subCategorySelect) {
            return;
        }

        // Show loading state
        subCategorySelect.innerHTML = '<option value="">Loading subcategories...</option>';
        subCategorySelect.disabled = true;
        subCategorySelect.classList.add('subcategory-loading');

        // Fetch subcategories from the server
        fetch(`/admin/get-product-subcategories/${categoryId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Clear loading state
                subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';

                if (data && data.length > 0) {
                    // Add subcategory options
                    data.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        subCategorySelect.appendChild(option);
                    });
                } else {
                    // No subcategories found
                    subCategorySelect.innerHTML = '<option value="">No subcategories available</option>';
                }

                subCategorySelect.disabled = false;
                subCategorySelect.classList.remove('subcategory-loading');
            })
            .catch(error => {
                subCategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
                subCategorySelect.disabled = false;
                subCategorySelect.classList.remove('subcategory-loading');
            });
    }

    // Function to clear subcategories
    function clearSubcategories() {
        const subCategorySelect = document.getElementById('productSubCategory');
        subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
        subCategorySelect.disabled = false;
        subCategorySelect.classList.remove('subcategory-loading');
        // Also clear third categories
        clearThirdCategories();
    }

    // NEW: Function to load third categories based on selected subcategories
    function loadThirdCategories() {
        const thirdCategoriesContainer = document.getElementById('thirdCategoriesContainer');
        if (!thirdCategoriesContainer) return;

        // Get selected subcategories (primary + additional)
        const subCategoryIds = [];

        // Get primary subcategory
        const primarySubCategory = document.getElementById('productSubCategory');
        if (primarySubCategory && primarySubCategory.value) {
            subCategoryIds.push(primarySubCategory.value);
        }

        // Get additional subcategories from checkboxes
        const additionalSubCategoryCheckboxes = document.querySelectorAll('.additional-subcategory-checkbox:checked');
        additionalSubCategoryCheckboxes.forEach(checkbox => {
            if (checkbox.value && !subCategoryIds.includes(checkbox.value)) {
                subCategoryIds.push(checkbox.value);
            }
        });

        if (subCategoryIds.length === 0) {
            thirdCategoriesContainer.innerHTML = '<div class="text-muted text-center py-3">Select subcategories first to see third categories</div>';
            return;
        }

        // Show loading state
        thirdCategoriesContainer.innerHTML = '<div class="text-muted text-center py-3">Loading third categories...</div>';

        // Fetch third categories from server
        fetch('{{ route("admin.third-categories.by-subcategories") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ sub_category_ids: subCategoryIds })
            })
            .then(response => response.json())
            .then(data => {
                // Get currently selected third categories to preserve selection
                const selectedThirdCategoryIds = [];
                const existingCheckboxes = thirdCategoriesContainer.querySelectorAll('input[type="checkbox"]:checked');
                existingCheckboxes.forEach(checkbox => {
                    selectedThirdCategoryIds.push(checkbox.value);
                });

                thirdCategoriesContainer.innerHTML = '';

                if (data && data.length > 0) {
                    data.forEach(thirdCategory => {
                        const checkboxItem = document.createElement('div');
                        checkboxItem.className = 'checkbox-item';

                        const label = document.createElement('label');
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.name = 'third_categories[]';
                        checkbox.value = thirdCategory.id;
                        checkbox.id = 'third_category_' + thirdCategory.id;

                        // Preserve selection if it was previously selected
                        if (selectedThirdCategoryIds.includes(thirdCategory.id.toString())) {
                            checkbox.checked = true;
                        }

                        const span = document.createElement('span');
                        span.textContent = thirdCategory.name;

                        label.appendChild(checkbox);
                        label.appendChild(span);
                        checkboxItem.appendChild(label);
                        thirdCategoriesContainer.appendChild(checkboxItem);
                    });
                } else {
                    thirdCategoriesContainer.innerHTML = '<div class="text-muted text-center py-3">No third categories available</div>';
                }
            })
            .catch(error => {
                console.error('Error loading third categories:', error);
                thirdCategoriesContainer.innerHTML = '<div class="text-muted text-center py-3 text-danger">Error loading third categories</div>';
            });
    }

    // Function to clear third categories
    function clearThirdCategories() {
        const thirdCategoriesContainer = document.getElementById('thirdCategoriesContainer');
        if (thirdCategoriesContainer) {
            thirdCategoriesContainer.innerHTML = '<div class="text-muted text-center py-3">Select subcategories first to see third categories</div>';
        }
    }

    // WordPress/WooCommerce Style Category Multi-Select Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('categorySearch');
        const categoryItems = document.querySelectorAll('.category-list-item');
        const allCheckboxes = document.querySelectorAll('.category-checkbox');
        const selectedCountSpan = document.getElementById('selectedCount');
        const categoryTabs = document.querySelectorAll('.category-tab');
        const listWrapper = document.getElementById('categoryListWrapper');

        // Tab functionality
        categoryTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                categoryTabs.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');

                const tabType = this.dataset.tab;

                // Handle "Most Used" tab (for now, show all - can be enhanced later)
                if (tabType === 'most-used') {
                    // You can implement logic to show most used categories here
                    // For now, we'll just show all
                }
            });
        });

        // Search functionality
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

        // Update selected count
        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
            if (selectedCountSpan) {
                selectedCountSpan.textContent = checkedCount;
            }
        }

        // Listen for checkbox changes
        allCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        // Initial count
        updateSelectedCount();
    });

    // Function to toggle book details section
    function toggleBookDetails() {
        const bookDetailSection = document.getElementById('bookDetailSection');
        const isBookCheckbox = document.getElementById('isBook');

        if (isBookCheckbox.checked) {
            bookDetailSection.style.display = 'block';
            bookDetailSection.classList.add('show');

            // Scroll to book detail section smoothly
            setTimeout(() => {
                    bookDetailSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        } else {
            bookDetailSection.style.display = 'none';
            bookDetailSection.classList.remove('show');
        }
    }

    // Check on page load if checkbox is already checked (e.g., from validation errors)
    document.addEventListener('DOMContentLoaded', function() {
        const isBookCheckbox = document.getElementById('isBook');
        if (isBookCheckbox && isBookCheckbox.checked) {
            toggleBookDetails();
        }
    });
</script>
@endsection