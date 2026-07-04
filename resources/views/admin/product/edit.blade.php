@extends('layouts.master')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
    #combinationsTable .table>:not(caption)>*>* {
        padding: 0px 3px !important;
    }

    .product-form-container {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr);
        gap: 10px;
        margin-top: 20px;
    }

    .main-content,
    .side-content {
        margin-bottom: 20px;
    }

    .side-content {
        min-width: 300px;
    }

    /* Responsive behavior for smaller screens */
    @media (max-width: 1200px) {
        .product-form-container {
            grid-template-columns: 1fr;
            gap: 10px;
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

    button.btn.btn-danger.remove-variation {
        margin-bottom: -30px;
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Edit Product</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Edit Product</li>
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

    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="old_thumb" value="{{ $product->thumb_image }}">
        <div class="product-form-container">
            <!-- Main Content Column -->
            <div class="main-content">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <h4>Basic Information</h4>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="productName" placeholder="Enter product name" value="{{ old('title', $product->title) }}" required>
                        <label for="productName">Product Name</label>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="productSlug" placeholder="Enter product slug" value="{{ old('slug', $product->slug) }}" required>
                        <label for="productSlug">Product Slug</label>
                        <small class="form-text text-muted">Auto-generated from product name, but you can edit it manually</small>
                        @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" id="productSku" placeholder="Enter SKU (Stock Keeping Unit)" value="{{ old('sku', $product->sku) }}">
                        <label for="productSku">SKU (Stock Keeping Unit)</label>
                        <small class="form-text text-muted">Unique identifier for inventory tracking. Used for Daraz sync and other marketplace integrations.</small>
                        @error('sku')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="productShortDescription" class="form-label">Short Description</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" id="productShortDescription" name="short_description" rows="3" placeholder="Enter short description">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="productDescription" class="form-label">Detailed Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="productDescription" name="description" rows="8" placeholder="Enter detailed description">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Product Data Section -->
                <div class="form-section">
                    <h4>Product Data</h4>

                    <!-- Product Type Display (Read-only in edit mode) -->
                    <div class="alert alert-info">
                        <strong>Product Type:</strong> {{ ucfirst($product->product_type) }}
                        <input type="hidden" name="product_type" value="{{ $product->product_type }}">
                    </div>

                    <!-- Product Type Specific Fields -->
                    @if ($product->product_type == 'digital')
                    <div class="mb-3">
                        <label for="digitalFile" class="form-label">Digital File</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="digitalFile" name="digital_file">
                            <span class="input-group-text">Current: {{ basename($product->digital_file) }}</span>
                        </div>
                        <input type="hidden" name="old_digital_file" value="{{ $product->digital_file }}">
                    </div>
                    <div class="mb-3">
                        <label for="downloadLimit" class="form-label">Download Limit</label>
                        <input type="number" class="form-control" id="downloadLimit" name="download_limit" value="{{ old('download_limit', $product->download_limit) }}">
                    </div>
                    @elseif($product->product_type == 'affiliate')
                    <div class="mb-3">
                        <label for="externalUrl" class="form-label">External URL</label>
                        <input type="url" class="form-control" id="externalUrl" name="external_url" value="{{ old('external_url', $product->external_url) }}">
                    </div>
                    <div class="mb-3">
                        <label for="affiliateCommission" class="form-label">Affiliate Commission</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="affiliateCommission" name="affiliate_commission" step="0.01" value="{{ old('affiliate_commission', $product->affiliate_commission) }}">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    @endif

                    <!-- Pricing Fields -->
                    <div class="row" id="pricingFields" style="{{ in_array($product->product_type, ['variable', 'affiliate'] ?? []) ? 'display: none;' : '' }}">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="oldPrice" class="form-label">Regular Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" class="form-control @error('old_price') is-invalid @enderror" id="oldPrice" name="old_price" step="0.01" value="{{ old('old_price', $product->old_price) }}" {{ in_array($product->product_type, ['variable', 'affiliate'] ?? []) ? '' : 'required' }}>
                                </div>
                                @error('old_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="offer" class="form-label">Sale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" class="form-control @error('offer') is-invalid @enderror" id="offer" name="offer" step="0.01" value="{{ old('offer', $product->offer) }}">
                                </div>
                                @error('offer')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Pricing Fields -->
                    <div class="row" id="additionalPricingFields" style="{{ in_array($product->product_type, ['variable', 'affiliate'] ?? []) ? 'display: none;' : '' }}">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="productCost" class="form-label">Product Cost</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" class="form-control @error('product_cost') is-invalid @enderror" id="productCost" name="product_cost" step="0.01" value="{{ old('product_cost', $product->product_cost) }}">
                                </div>
                                <div class="form-text">What you pay for this product</div>
                                @error('product_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="wholesalePrice" class="form-label">Wholesale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" class="form-control @error('wholesale_price') is-invalid @enderror" id="wholesalePrice" name="wholesale_price" step="0.01" value="{{ old('wholesale_price', $product->wholesale_price) }}">
                                </div>
                                <div class="form-text">Price for bulk/wholesale customers</div>
                                @error('wholesale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Fields -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Stock Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}">
                                @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="weight" class="form-label">Weight (KG)</label>
                                <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $product->weight ?? 0.5) }}" step="0.001" min="0.001">
                                <small class="form-text text-muted">Weight for courier delivery (minimum 0.5 KG)</small>
                                @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Variations Section -->
                    @if ($product->product_type == 'variable')
                    <div id="variationsSection">
                        <h5 class="mt-4 mb-3">Product Variations</h5>
                        <div class="alert alert-info">
                            <strong>How it works:</strong> Add variation groups (like Size, Color) and their options. The system will automatically generate all possible combinations and let you set individual prices and stock for each combination.
                            <br><small class="text-muted mt-1">
                                <strong>Note:</strong> Even single variations (e.g., Size-only) will create individual combinations for each option.
                            </small>
                        </div>

                        <div id="variations">
                            @foreach ($variations as $index => $variation)
                            <div class="variation">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Variation Group</h5>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariation(this)">
                                        X Remove
                                    </button>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" name="variations[{{ $index }}][name]" value="{{ $variation->name }}" placeholder="Enter variation name" required>
                                    <label>Variation Name (e.g. Size, Color)</label>
                                    <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $variation->id }}">
                                </div>

                                <div class="options">
                                    <h6>Options</h6>
                                    @foreach ($variation->options as $optionIndex => $option)
                                    <div class="option">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control variation-option-input" name="variations[{{ $index }}][options][{{ $optionIndex }}][name]" value="{{ $option->name }}" placeholder="Enter option name" required>
                                            <label>Option Name (e.g. Small, Red)</label>
                                            <input type="hidden" name="variations[{{ $index }}][options][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                                        </div>

                                        <div class="alert alert-info p-2 mb-3">
                                            <small>
                                                <i class="bi bi-info-circle"></i>
                                                <strong>Note:</strong> Only option names are needed here. Pricing, stock, descriptions, and images are managed in the <strong>Combinations table</strong> below.
                                            </small>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addOption(this)">
                                    <i class="bi bi-plus"></i> Add Option
                                </button>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn-add-variation" onclick="addVariation()">
                            <i class="bi bi-plus-circle"></i> Add Variation Group
                        </button>

                        <!-- Combinations Preview -->
                        <div id="combinationsPreview" style="margin-top: 30px;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> Variation Combinations</h5>
                                <span id="combinationsCount" class="badge bg-success fs-6">{{ $combinations->count() }} combinations</span>
                            </div>

                            <!-- Manual Combination Builder -->
                            <div class="alert alert-primary">
                                <h6 class="mb-3"><i class="bi bi-plus-circle"></i> Add New Combination</h6>
                                <p class="small text-muted mb-3">Select one option from each variation group to create a new combination:</p>
                                <div class="row g-2 mb-3" id="combinationBuilderSelects">
                                    @foreach ($variations as $varIndex => $variation)
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">{{ $variation->name }}:</label>
                                        <select class="form-select form-select-sm combination-select" data-variation="{{ $variation->name }}">
                                            <option value="">-- Select {{ $variation->name }} --</option>
                                            @foreach ($variation->options as $option)
                                            <option value="{{ $option->name }}">{{ $option->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-success btn-sm" onclick="addSingleCombination()">
                                    <i class="bi bi-check-circle"></i> Create This Combination
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="generateAllCombinations()">
                                    <i class="bi bi-grid-3x3-gap"></i> Generate All Combinations
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="refreshCombinationBuilder()">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh Options
                                </button>
                            </div>

                            <p class="text-muted mb-3">
                                <i class="bi bi-info-circle"></i>
                                Set individual prices and stock quantities for each combination. These will be used on the frontend when customers select variations.
                            </p>
                            <div id="combinationsTable">
                                @if($combinations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                @foreach ($variations as $variation)
                                                <th>{{ $variation->name }}</th>
                                                @endforeach
                                                <th>Regular Price (৳)</th>
                                                <th>Offer Price (৳)</th>
                                                <th>Product Cost (৳)</th>
                                                <th>Wholesale Price (৳)</th>
                                                <th>Stock</th>
                                                <th>Description</th>
                                                <th>Images</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($combinations as $index => $combination)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                @foreach ($combination->getOptionNamesArray() as $optionName)
                                                <td><span class="badge bg-primary">{{ $optionName }}</span></td>
                                                @endforeach
                                                <td>
                                                    <input type="number"
                                                        step="0.01"
                                                        min="0"
                                                        class="form-control combination-regular-price"
                                                        name="combinations[{{ $index }}][regular_price]"
                                                        value="{{ $combination->regular_price }}"
                                                        placeholder="0.00"
                                                        style="width: 90px;">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        step="0.01"
                                                        min="0"
                                                        class="form-control combination-offer-price"
                                                        name="combinations[{{ $index }}][offer_price]"
                                                        value="{{ $combination->offer_price }}"
                                                        placeholder="Optional"
                                                        style="width: 90px;">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        step="0.01"
                                                        min="0"
                                                        class="form-control combination-product-cost"
                                                        name="combinations[{{ $index }}][product_cost]"
                                                        value="{{ $combination->product_cost }}"
                                                        placeholder="Cost"
                                                        style="width: 90px;">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        step="0.01"
                                                        min="0"
                                                        class="form-control combination-wholesale-price"
                                                        name="combinations[{{ $index }}][wholesale_price]"
                                                        value="{{ $combination->wholesale_price }}"
                                                        placeholder="Wholesale"
                                                        style="width: 90px;">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        min="0"
                                                        class="form-control combination-stock"
                                                        name="combinations[{{ $index }}][stock_quantity]"
                                                        value="{{ $combination->stock_quantity }}"
                                                        placeholder="0"
                                                        style="width: 70px;">
                                                </td>
                                                <td>
                                                    <textarea class="form-control combination-description"
                                                        name="combinations[{{ $index }}][short_description]"
                                                        rows="2"
                                                        placeholder="Brief description..."
                                                        style="width: 200px; resize: vertical;">{{ $combination->short_description }}</textarea>
                                                </td>
                                                <td>
                                                    <div class="mb-2">
                                                        <input type="file"
                                                            class="form-control combination-image"
                                                            name="combinations[{{ $index }}][featured_image]"
                                                            accept="image/*"
                                                            style="width: 150px; font-size: 11px;">
                                                        <small class="text-muted d-block">Featured Image</small>
                                                        @if($combination->featured_image)
                                                        <div class="mt-1">
                                                            <img src="{{ asset('storage/' . $combination->featured_image) }}" class="img-thumbnail" style="height: 40px;">
                                                            <input type="hidden" name="combinations[{{ $index }}][old_featured_image]" value="{{ $combination->featured_image }}">
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <input type="file"
                                                            class="form-control combination-gallery"
                                                            name="combinations[{{ $index }}][gallery_images][]"
                                                            accept="image/*"
                                                            multiple
                                                            style="width: 150px; font-size: 11px;">
                                                        <small class="text-muted d-block">Gallery Images</small>
                                                        @if($combination->gallery_images)
                                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                                            @php
                                                            $combinationImages = is_array($combination->gallery_images) ? $combination->gallery_images : [];
                                                            @endphp
                                                            @foreach ($combinationImages as $img)
                                                            <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail" style="height: 30px;">
                                                            @endforeach
                                                            <input type="hidden" name="combinations[{{ $index }}][old_gallery_images]" value="{{ json_encode($combination->gallery_images) }}">
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <input type="hidden" name="combinations[{{ $index }}][id]" value="{{ $combination->id }}">
                                                    <input type="hidden" name="combinations[{{ $index }}][key]" value="{{ $combination->combination_key }}">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteExistingCombination(this, {{ $combination->id }})">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Side Content Column -->
            <div class="side-content">
                <!-- Publish Section -->
                <div class="form-section publish-section">
                    <h4>Publish</h4>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>
                                Published</option>
                            <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Draft
                            </option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="form-section">
                    <h4>Categories</h4>
                    <div class="alert alert-info mb-3" role="alert">
                        <i class="bi bi-info-circle"></i>
                        <strong>Note:</strong> Primary category and subcategory fields below are optional and maintained for backward compatibility.
                        You can use the multi-select category selector below to assign multiple categories, subcategories, and third-level categories.
                        At least one category must be selected from either the primary field or the multi-select.
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Primary Category <small class="text-muted">(Optional - for backward compatibility)</small></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option value="">Select Category (Optional)</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="sub_category_id" class="form-label">Primary Sub Category <small class="text-muted">(Optional - for backward compatibility)</small></label>
                        <select class="form-select @error('sub_category_id') is-invalid @enderror" id="sub_category_id" name="sub_category_id">
                            <option value="">Select Sub Category (Optional)</option>
                            @foreach ($sub_categories as $sub_category)
                            <option value="{{ $sub_category->id }}" {{ old('sub_category_id', $product->sub_category_id) == $sub_category->id ? 'selected' : '' }}>
                                {{ $sub_category->name }}
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">
                            <i class="bi bi-info-circle"></i>
                            Subcategories will be loaded automatically when you select a primary category
                        </small>
                        @error('sub_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                                $oldAdditionalCategories = old('additional_categories', $product->additionalCategories->pluck('id')->toArray());
                                $oldAdditionalSubcategories = old('additional_subcategories', $product->additionalSubCategories->pluck('id')->toArray());
                                $oldThirdCategories = old('third_categories', $product->thirdCategories->pluck('id')->toArray());
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
                        <label for="brand_id" class="form-label">Brand</label>
                        <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
                            <option value="">Select Brand</option>
                            @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tags Section -->
                <div class="form-section">
                    <h4>Tags</h4>
                    <div class="mb-3">
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" placeholder="Enter tags separated by commas" value="{{ old('tags', $product->tags) }}">
                        @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <!-- Media Section -->
                <div class="form-section">
                    <h4>Product Media</h4>

                    <div class="mb-3">
                        <label for="thumbImage" class="form-label">Thumbnail Image</label>
                        <input type="file" class="form-control @error('thumb_image') is-invalid @enderror" id="thumbImage" name="thumb_image">
                        @error('thumb_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($product->thumb_image)
                        <div class="mt-2">
                            <p>Current Thumbnail:</p>
                            <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="Thumbnail" class="img-thumbnail" style="height: 100px;">
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="productImages" class="form-label">Gallery Images</label>
                        <input type="file" class="form-control @error('images') is-invalid @enderror" id="productImages" name="images[]" multiple>
                        @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($product->images)
                        <div class="mt-2">
                            <p>Current Gallery Images:</p>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                $productImages = is_array($product->images) ? $product->images : [];
                                @endphp
                                @foreach ($productImages as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image" class="img-thumbnail" style="height: 100px;">
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Others Section -->
                <div class="form-section">
                    <h4>Others</h4>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="is_featured" id="isFeatured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} style="margin-top: 7px;">
                        <label class="form-check-label" for="isFeatured">Set as Featured Product</label>
                        <small class="d-block text-muted">Check this to mark the product as featured</small>
                    </div>
                    <div class="form-group">
                        <label for="video_url">Product Video URL (YouTube)</label>
                        <input type="text" name="video_url" id="video_url" class="form-control" value="{{ old('video_url', $product->video_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=xxxxxx">
                        <small class="form-text text-muted">Leave blank to use the global video URL.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <h4 class="mb-3">Book Detail</h4>

            <div class="col">
                <!-- <div class="mb-3">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Enter subject" value="{{ $product?->book?->subject }}">
                    </div> -->
                <div class="mb-3">
                    <label for="edition">Edition</label>
                    <input type="text" class="form-control" name="edition" id="edition" placeholder="Enter edition" value="{{ $product?->book?->edition }}">
                </div>
                <div class="mb-3">
                    <label for="isbn">ISBN</label>
                    <input type="text" class="form-control" name="isbn" id="isbn" placeholder="Enter isbn" value="{{ $product?->book?->isbn }}">
                </div>
                <div class="mb-3">
                    <label for="Language">Language</label>
                    <select class="form-control" name="language">
                        <option value="">Select Language</option>
                        <option value="English" {{ $product?->book?->language == 'English' ? 'selected' : '' }}>English</option>
                        <option value="Bangla" {{ $product?->book?->language == 'Bangla' ? 'selected' : '' }}>Bangla</option>
                        <option value="Hindi" {{ $product?->book?->language == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="sample_path">Upload Sample File</label>
                    <input type="file" class="form-control" name="sample_path" id="sample_path" placeholder="Upload sample file">
                </div>
                <div class="mb-3">
                    <label for="pages">Total Pages</label>
                    <input type="number" class="form-control" name="pages" id="pages" placeholder="Enter total pages" value="{{ $product?->book?->pages }}">
                </div>
                <div class="mb-3">
                    <label for="language">Language</label>
                    <select class="form-control" name="cover">
                        <option value="">Select Cover</option>
                        <option value="Hardcover" {{ $product?->book?->cover == 'Hardcover' ? 'selected' : '' }}>Hardcover</option>
                        <option value="Paperback" {{ $product?->book?->cover == 'Paperback' ? 'selected' : '' }}>Paperback</option>
                        <option value="Ebook" {{ $product?->book?->cover == 'Ebook' ? 'selected' : '' }}>Ebook</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="country">Country</label>
                    <select class="form-control" name="country">
                        <option value="">Select Country</option>
                        <option value="Bangladesh" {{ $product?->book?->country == 'Bangladesh' ? 'selected' : '' }}>Bangladesh</option>
                        <option value="India" {{ $product?->book?->country == 'India' ? 'selected' : '' }}>India</option>
                        <option value="USA" {{ $product?->book?->country == 'USA' ? 'selected' : '' }}>USA</option>
                        <option value="UK" {{ $product?->book?->country == 'UK' ? 'selected' : '' }}>UK</option>
                        <option value="Canada" {{ $product?->book?->country == 'Canada' ? 'selected' : '' }}>Canada</option>
                        <option value="Australia" {{ $product?->book?->country == 'Australia' ? 'selected' : '' }}>Australia</option>
                        <option value="Other" {{ $product?->book?->country == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>

            <div class="col">
                @php
                $writers = \App\Models\Writer::select('id', 'name')->get();
                @endphp
                <div class="form-section">
                    <div class="form-floating1 mb-3"></div>
                    <label for="productWriters">Writers</label>
                    <select class="form-select @error('writers') is-invalid @enderror" id="productWriters" name="writers[]" multiple>
                        @foreach ($writers as $writer)
                        <option value="{{ $writer->id }}" {{ in_array($writer->id, $product?->book?->writers?->pluck('id')?->toArray() ?? []) ? 'selected' : '' }}>
                            {{ $writer->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('writers')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @php
                $publishers = \App\Models\Publisher::select('id', 'name')->get();
                @endphp

                <div class="form-section">
                    <div class="form-floating1 mb-3"></div>
                    <label for="publisher">Publisher</label>
                    <select class="form-select @error('publisher') is-invalid @enderror" id="publisher" name="publisher">
                        @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ $product?->book?->publisher_id == $publisher->id ? 'selected' : '' }}>
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
                                    placeholder="Enter SEO title" value="{{ $product->formatted_seo['meta_title'] ?? '' }}" maxlength="60">
                                <label for="seoMetaTitle">Meta Title</label>
                                <small class="form-text text-muted">
                                    <span id="metaTitleCount">{{ strlen($product->formatted_seo['meta_title'] ?? '') }}</span>/60 characters. Leave empty to auto-generate from product title.
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="seo[meta_description]" id="seoMetaDescription"
                                    placeholder="Enter SEO description" style="height: 100px;" maxlength="160">{{ $product->formatted_seo['meta_description'] ?? '' }}</textarea>
                                <label for="seoMetaDescription">Meta Description</label>
                                <small class="form-text text-muted">
                                    <span id="metaDescriptionCount">{{ strlen($product->formatted_seo['meta_description'] ?? '') }}</span>/160 characters. Leave empty to auto-generate from product description.
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="seo[meta_keywords]"
                                    id="seoMetaKeywords" placeholder="Enter SEO keywords" value="{{ $product->formatted_seo['meta_keywords'] ?? '' }}">
                                <label for="seoMetaKeywords">Meta Keywords</label>
                                <small class="form-text text-muted">Comma-separated keywords. Leave empty to auto-generate from product tags and category.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="url" class="form-control" name="seo[canonical_url]"
                                    id="seoCanonicalUrl" placeholder="Enter canonical URL" value="{{ $product->formatted_seo['canonical_url'] ?? '' }}">
                                <label for="seoCanonicalUrl">Canonical URL</label>
                                <small class="form-text text-muted">Leave empty to use the default product URL.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="seo[meta_robots]" id="seoMetaRobots">
                                    <option value="index,follow" {{ ($product->formatted_seo['meta_robots'] ?? 'index,follow') == 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                                    <option value="noindex,follow" {{ ($product->formatted_seo['meta_robots'] ?? '') == 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                                    <option value="index,nofollow" {{ ($product->formatted_seo['meta_robots'] ?? '') == 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                                    <option value="noindex,nofollow" {{ ($product->formatted_seo['meta_robots'] ?? '') == 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
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
                                <div id="ogImagePreview" class="mt-2" style="max-width: 200px;">
                                    @if(isset($product->formatted_seo['og_image']) && $product->formatted_seo['og_image'])
                                    <img src="{{ asset('storage/' . $product->formatted_seo['og_image']) }}" class="img-fluid rounded" alt="Current OG Image">
                                    @endif
                                </div>
                                <!-- Hidden field to preserve existing OG image path -->
                                <input type="hidden" name="seo[existing_og_image]" value="{{ $product->formatted_seo['og_image'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="seoSchemaMarkup" class="form-label">Schema Markup (JSON-LD)</label>
                                <textarea class="form-control" name="seo[schema_markup]" id="seoSchemaMarkup" rows="6">{{ $product->formatted_seo['schema_markup'] ?? '' }}</textarea>
                                <small class="form-text text-muted">Custom JSON-LD schema markup. Leave empty to auto-generate basic product schema.</small>
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
                                        <p class="seo-title"><strong>Meta Title:</strong> {{ $product->formatted_seo['meta_title'] ?? 'Auto-generated from product name' }}</p>
                                        <p class="seo-description"><strong>Meta Description:</strong> {{ $product->formatted_seo['meta_description'] ?? 'Auto-generated from product description' }}</p>
                                        <p class="seo-description"><strong>Meta Keywords:</strong> {{ $product->formatted_seo['meta_keywords'] ?? 'Auto-generated from product tags and category' }}</p>
                                        <p class="seo-url"><strong>Canonical URL:</strong> {{ $product->formatted_seo['canonical_url'] ?? 'Default product URL' }}</p>
                                        <p class="seo-description"><strong>Meta Robots:</strong> {{ $product->formatted_seo['meta_robots'] ?? 'Default (Index, Follow)' }}</p>
                                        <p class="seo-description"><strong>OG Image:</strong> {{ isset($product->formatted_seo['og_image']) && $product->formatted_seo['og_image'] ? 'Custom image' : 'Product featured image' }}</p>
                                        <p class="seo-description"><strong>Schema Markup:</strong> {{ isset($product->formatted_seo['schema_markup']) && $product->formatted_seo['schema_markup'] ? 'Custom JSON-LD' : 'Auto-generated basic product schema' }}</p>
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
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
    // Existing combinations are handled by the static table in the blade template

    $(document).ready(function() {
        // Initialize slug generation functionality
        initializeSlugGeneration();

        // Initialize dynamic subcategory loading
        initializeSubcategoryLoading();

        // Initialize rich text editor
        $('#productDescription, #productShortDescription').summernote({
            height: 200,
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

        // Show/hide product type specific fields
        const productType = '{{ $product->product_type }}';
        if (productType === 'variable') {
            $('#variationsSection').show();
            $('#pricingFields').hide();
            // Show combinations preview with existing data
            $('#combinationsPreview').show();

            // Initialize combination builder with existing variations
            setTimeout(() => refreshCombinationBuilder(false), 300);
        }

        // Initialize SEO functionality
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
        const metaKeywordsInput = document.getElementById('seoMetaKeywords');
        const canonicalUrlInput = document.getElementById('seoCanonicalUrl');
        const metaRobotsSelect = document.getElementById('seoMetaRobots');
        const schemaMarkupInput = document.getElementById('seoSchemaMarkup');
        const ogImageInput = document.getElementById('seoOgImage');

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

        if (metaKeywordsInput && metaKeywordsInput.value) {
            seoPreviewContent += `<p class="seo-description"><strong>Meta Keywords:</strong> ${metaKeywordsInput.value}</p>`;
        } else {
            seoPreviewContent += `<p class="seo-description"><strong>Meta Keywords:</strong> Auto-generated from product tags and category</p>`;
        }

        if (canonicalUrlInput && canonicalUrlInput.value) {
            seoPreviewContent += `<p class="seo-url"><strong>Canonical URL:</strong> ${canonicalUrlInput.value}</p>`;
        } else {
            seoPreviewContent += `<p class="seo-url"><strong>Canonical URL:</strong> Default product URL</p>`;
        }

        if (metaRobotsSelect && metaRobotsSelect.value) {
            seoPreviewContent += `<p class="seo-description"><strong>Meta Robots:</strong> ${metaRobotsSelect.value}</p>`;
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
            seoPreviewContent += `<p class="seo-description"><strong>Schema Markup:</strong> Auto-generated basic product schema</p>`;
        }

        const seoPreview = document.querySelector('.seo-preview');
        if (seoPreview) {
            seoPreview.innerHTML = seoPreviewContent;
        }
    }

    // Variation functions (same as create.blade.php)
    function addVariation() {
        const variationsDiv = document.getElementById('variations');
        const variationCount = variationsDiv.children.length;

        const variationDiv = document.createElement('div');
        variationDiv.classList.add('variation');

        // Generate unique index for new variation
        const uniqueIndex = Date.now() + Math.random().toString(36).substr(2, 9);

        variationDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Variation Group</h5>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariation(this)">
                        X Remove
                    </button>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="variations[${variationCount}][name]" placeholder="Enter variation name">
                    <label>Variation Name (e.g. Size, Color)</label>
                </div>

                <div class="options">
                    <h6>Options</h6>
                    <div class="option">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control variation-option-input" name="variations[${variationCount}][options][${uniqueIndex}][name]" placeholder="Enter option name" required>
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

        // Auto-refresh the combination builder (silent refresh)
        setTimeout(() => refreshCombinationBuilder(false), 100);
    }

    function removeVariation(button) {
        if (confirm('Are you sure you want to remove this variation group? This will not delete existing combinations.')) {
            button.closest('.variation').remove();

            // Auto-refresh the combination builder (silent refresh)
            setTimeout(() => refreshCombinationBuilder(false), 100);
        }
    }

    function addOption(button) {
        const optionsDiv = button.previousElementSibling;
        const optionDiv = document.createElement('div');
        optionDiv.classList.add('option', 'mt-3');

        const variationDiv = button.closest('.variation');
        const variationIndex = Array.from(document.querySelectorAll('.variation')).indexOf(variationDiv);

        // Generate unique timestamp-based index to avoid conflicts
        const uniqueIndex = Date.now() + Math.random().toString(36).substr(2, 9);
        const optionCount = optionsDiv.querySelectorAll('.option').length;

        optionDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Option ${optionCount + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(this)">
                        X
                    </button>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control variation-option-input" name="variations[${variationIndex}][options][${uniqueIndex}][name]" placeholder="Enter option name" required>
                    <label>Option Name (e.g. Small, Red)</label>
                </div>

                <div class="alert alert-info p-2">
                    <small>
                        <i class="bi bi-info-circle"></i>
                        <strong>Note:</strong> After adding new options, click <strong>"Refresh Options"</strong> button to update the combination builder.
                    </small>
                </div>
            `;

        optionsDiv.appendChild(optionDiv);

        // Auto-refresh the combination builder (silent refresh)
        setTimeout(() => refreshCombinationBuilder(false), 100);
    }

    function removeOption(button) {
        if (confirm('Are you sure you want to remove this option?')) {
            button.closest('.option').remove();

            // Auto-refresh the combination builder (silent refresh)
            setTimeout(() => refreshCombinationBuilder(false), 100);
        }
    }

    // NO AUTO-REGENERATION - Just refresh the builder dropdowns
    function updateCombinationsPreview() {
        // This function is kept for compatibility but does nothing
        // Old behavior was destroying all form data
        console.log('updateCombinationsPreview called but disabled to prevent data loss');
    }

    // Refresh the combination builder dropdowns with current variations/options
    function refreshCombinationBuilder(showAlert = true) {
        const variations = document.querySelectorAll('.variation');
        const builderDiv = document.getElementById('combinationBuilderSelects');

        if (!builderDiv) return;

        let html = '';
        let hasValidOptions = false;

        variations.forEach((variation, index) => {
            const nameInput = variation.querySelector('input[name*="[name]"]');
            const variationName = nameInput ? nameInput.value.trim() : '';

            if (!variationName) return;

            const optionInputs = variation.querySelectorAll('.variation-option-input');
            const options = [];
            optionInputs.forEach(input => {
                const opt = input.value.trim();
                if (opt) options.push(opt);
            });

            if (options.length > 0) {
                hasValidOptions = true;
                html += `
                        <div class="col-md-4">
                            <label class="form-label fw-bold">${variationName}:</label>
                            <select class="form-select form-select-sm combination-select" data-variation="${variationName}">
                                <option value="">-- Select ${variationName} --</option>
                                ${options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}
                            </select>
                        </div>
                    `;
            }
        });

        if (!hasValidOptions) {
            html = '<div class="col-12"><p class="text-muted">Add variation groups and options first to create combinations.</p></div>';
        }

        builderDiv.innerHTML = html;

        if (showAlert && hasValidOptions) {
            // Use a subtle notification instead of alert
            const builderContainer = document.querySelector('.alert.alert-primary');
            if (builderContainer) {
                builderContainer.classList.add('border-success');
                setTimeout(() => builderContainer.classList.remove('border-success'), 1500);
            }
        }
    }

    // Add a single manually-selected combination
    function addSingleCombination() {
        const selects = document.querySelectorAll('.combination-select');
        const selectedValues = {};
        const combinationKey = [];
        let hasError = false;

        selects.forEach(select => {
            const variation = select.dataset.variation;
            const value = select.value;

            if (!value) {
                alert(`Please select an option for ${variation}`);
                hasError = true;
                return;
            }

            selectedValues[variation] = value;
            combinationKey.push(value);
        });

        if (hasError) return;

        const keyString = combinationKey.join('_');

        // Check if combination already exists
        const existingKeys = document.querySelectorAll('input[name*="[key]"]');
        for (let input of existingKeys) {
            if (input.value === keyString) {
                alert('This combination already exists!');
                return;
            }
        }

        // Add the combination row
        addCombinationToTable(selectedValues, keyString);

        // Reset selects
        selects.forEach(s => s.value = '');

        alert('Combination added successfully!');
    }

    // Generate all possible combinations
    function generateAllCombinations() {
        if (!confirm('This will generate ALL possible combinations from current variations. Continue?')) {
            return;
        }

        const variations = document.querySelectorAll('.variation');
        const combinationsPreview = document.getElementById('combinationsPreview');
        const combinationsTable = document.getElementById('combinationsTable');

        if (variations.length === 0) {
            alert('No variations found!');
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
                    options.push({
                        name: optionName,
                        price: 0,
                        stock: 100
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
            alert('No valid variations found!');
            return;
        }

        // Generate combinations using Cartesian product
        const combinations = cartesianProduct(variationData.map(v => v.options));
        const variationNames = variationData.map(v => v.name);

        // Get existing combination keys
        const existingKeys = [];
        document.querySelectorAll('input[name*="[key]"]').forEach(input => {
            existingKeys.push(input.value);
        });

        // Add each new combination
        let addedCount = 0;
        combinations.forEach(combination => {
            const selectedValues = {};
            const combinationKey = [];

            combination.forEach((option, index) => {
                selectedValues[variationNames[index]] = option.name;
                combinationKey.push(option.name);
            });

            const keyString = combinationKey.join('_');

            if (!existingKeys.includes(keyString)) {
                addCombinationToTable(selectedValues, keyString);
                addedCount++;
            }
        });

        alert(`Added ${addedCount} new combinations!`);
        updateCombinationsCount();
    }

    // Add a combination row to the table
    function addCombinationToTable(selectedValues, combinationKey) {
        let tbody = document.querySelector('#combinationsTable tbody');

        if (!tbody) {
            // Create table structure if it doesn't exist
            createCombinationsTableStructure();
            tbody = document.querySelector('#combinationsTable tbody');
        }

        const currentIndex = tbody.querySelectorAll('tr').length;
        const variations = document.querySelectorAll('.variation');
        const variationNames = [];

        variations.forEach(variation => {
            const nameInput = variation.querySelector('input[name*="[name]"]');
            if (nameInput && nameInput.value.trim()) {
                variationNames.push(nameInput.value.trim());
            }
        });

        let rowHTML = `<tr><td>${currentIndex + 1}</td>`;

        // Add variation option badges
        variationNames.forEach(varName => {
            const optionValue = selectedValues[varName] || '-';
            rowHTML += `<td><span class="badge bg-primary">${optionValue}</span></td>`;
        });

        // Add form inputs with all fields
        rowHTML += `
                <td>
                    <input type="number" step="0.01" min="0" class="form-control" 
                           name="combinations[${currentIndex}][regular_price]" value="0.00" 
                           placeholder="0.00" style="width: 90px;">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control" 
                           name="combinations[${currentIndex}][offer_price]" value="" 
                           placeholder="Optional" style="width: 90px;">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control" 
                           name="combinations[${currentIndex}][product_cost]" value="" 
                           placeholder="Cost" style="width: 90px;">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control" 
                           name="combinations[${currentIndex}][wholesale_price]" value="" 
                           placeholder="Wholesale" style="width: 90px;">
                </td>
                <td>
                    <input type="number" min="0" class="form-control" 
                           name="combinations[${currentIndex}][stock_quantity]" value="0" 
                           placeholder="0" style="width: 70px;">
                </td>
                <td>
                    <textarea class="form-control" 
                              name="combinations[${currentIndex}][short_description]" 
                              rows="2" placeholder="Brief description..." 
                              style="width: 200px; resize: vertical;"></textarea>
                </td>
                <td>
                    <div class="mb-2">
                        <input type="file" class="form-control" 
                               name="combinations[${currentIndex}][featured_image]" 
                               accept="image/*" style="width: 150px; font-size: 11px;">
                        <small class="text-muted d-block">Featured Image</small>
                    </div>
                    <div>
                        <input type="file" class="form-control" 
                               name="combinations[${currentIndex}][gallery_images][]" 
                               accept="image/*" multiple style="width: 150px; font-size: 11px;">
                        <small class="text-muted d-block">Gallery Images</small>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteNewCombination(this)">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </td>
                <input type="hidden" name="combinations[${currentIndex}][key]" value="${combinationKey}">
            </tr>`;

        tbody.insertAdjacentHTML('beforeend', rowHTML);
    }

    // Create table structure if it doesn't exist
    function createCombinationsTableStructure() {
        const tableDiv = document.getElementById('combinationsTable');
        const variations = document.querySelectorAll('.variation');
        const variationNames = [];

        variations.forEach(variation => {
            const nameInput = variation.querySelector('input[name*="[name]"]');
            if (nameInput && nameInput.value.trim()) {
                variationNames.push(nameInput.value.trim());
            }
        });

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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>`;

        tableDiv.innerHTML = tableHTML;
    }

    // Delete a newly added combination (not yet saved to DB)
    function deleteNewCombination(button) {
        if (confirm('Delete this combination?')) {
            button.closest('tr').remove();
            reindexCombinations();
            updateCombinationsCount();
        }
    }

    // Delete an existing combination (will be deleted from DB on save)
    function deleteExistingCombination(button, combinationId) {
        if (confirm('Delete this combination? This will be permanent when you save.')) {
            const row = button.closest('tr');

            // Mark for deletion by adding a hidden input
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = `combinations_to_delete[]`;
            deleteInput.value = combinationId;
            row.appendChild(deleteInput);

            // Hide the row
            row.style.display = 'none';
            updateCombinationsCount();
        }
    }

    // Reindex all combination input names after deletions
    function reindexCombinations() {
        const rows = document.querySelectorAll('#combinationsTable tbody tr');
        let visibleIndex = 0;

        rows.forEach((row, index) => {
            if (row.style.display !== 'none') {
                // Update row number
                row.querySelector('td:first-child').textContent = visibleIndex + 1;

                // Update all input names
                const inputs = row.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    if (input.name && input.name.includes('combinations[')) {
                        input.name = input.name.replace(/combinations\[\d+\]/, `combinations[${visibleIndex}]`);
                    }
                });

                visibleIndex++;
            }
        });
    }

    // Update the combinations count badge
    function updateCombinationsCount() {
        const rows = document.querySelectorAll('#combinationsTable tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            if (row.style.display !== 'none') {
                visibleCount++;
            }
        });

        const countBadge = document.getElementById('combinationsCount');
        if (countBadge) {
            countBadge.textContent = `${visibleCount} combination${visibleCount !== 1 ? 's' : ''}`;
        }
    }

    // Helper function for Cartesian product
    function cartesianProduct(arrays) {
        if (arrays.length === 1) {
            return arrays[0].map(option => [option]);
        }

        return arrays.reduce((a, b) =>
            a.flatMap(x => b.map(y => [...(Array.isArray(x) ? x : [x]), y]))
        );
    }

    // END OF Manual Combination Builder Functions

    // Slug generation function
    function generateSlug(text) {
        return text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/[^\w\-]+/g, '') // Remove non-word characters except hyphens
            .replace(/\-\-+/g, '-') // Replace multiple hyphens with single hyphen
            .replace(/^-+/, '') // Remove leading hyphens
            .replace(/-+$/, ''); // Remove trailing hyphens
    }

    function generateSlugFromName() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        if (nameInput && slugInput && !slugInput.value) {
            slugInput.value = generateSlug(nameInput.value);
        }
    }

    // Alternative slug generation (kept for compatibility)
    function slugGenerate(text) {
        return text
            .toString()
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/[^\w\-]+/g, '') // Remove all non-word characters except hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }

    // Dynamic Subcategory Loading Functions
    function initializeSubcategoryLoading() {
        // Category change event for dynamic subcategory loading
        const productCategorySelect = document.getElementById('category_id');
        const productSubCategorySelect = document.getElementById('sub_category_id');

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
    }

    // Function to load subcategories based on selected category
    function loadSubcategories(categoryId) {
        const subCategorySelect = document.getElementById('sub_category_id');

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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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

                        // Check if this subcategory was previously selected
                        const currentProductSubCategoryId = '{{ $product->sub_category_id }}';
                        if (currentProductSubCategoryId && currentProductSubCategoryId == subcategory.id) {
                            option.selected = true;
                        }

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
        const subCategorySelect = document.getElementById('sub_category_id');
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
        const primarySubCategory = document.getElementById('sub_category_id');
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

                // Also include previously saved third categories
                const existingThirdCategoryIds = {!! json_encode($product->thirdCategories->pluck('id')->toArray()) !!};
                existingThirdCategoryIds.forEach(id => {
                    if (!selectedThirdCategoryIds.includes(id.toString())) {
                        selectedThirdCategoryIds.push(id.toString());
                    }
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

    // Slug generation functionality
    function initializeSlugGeneration() {
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
    }

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
        const productName = document.getElementById('productName').value;
        const productShortDescription = document.getElementById('productShortDescription').value;
        const productDescription = document.getElementById('productDescription').value;
        const productCategory = document.getElementById('productCategory').value;
        const productSubCategory = document.getElementById('sub_category_id').value;
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
</script>
@endsection