@extends('vendor.layouts.app')

@section('content')
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

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">Add New Product</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary">← Back to Products</a>
    </div>

    <!-- Commission Info Alert -->
    <div class="alert alert-info mb-4">
        <h5><i class="fas fa-info-circle"></i> Commission Settings</h5>
        <div class="row">
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

    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Product Approval Required:</strong> Your product will be pending admin approval before going live on the store.
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('vendor.products.store') }}" enctype="multipart/form-data" id="productForm">
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
                        <div class="product-type-card active" data-type="simple" onclick="selectProductType('simple')">
                            <i class="fas fa-box"></i>
                            <h6>Simple Product</h6>
                            <small class="text-muted">Single product with no variations</small>
                        </div>
                        <div class="product-type-card" data-type="variable" onclick="selectProductType('variable')">
                            <i class="fas fa-layer-group"></i>
                            <h6>Variable Product</h6>
                            <small class="text-muted">Product with multiple variations</small>
                        </div>
                    </div>

                    <input type="hidden" name="product_type" id="productTypeInput" value="simple">

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

                        <!-- Vendor Commission -->
                        <div class="form-floating mb-3">
                            <input type="number" step="0.01" class="form-control @error('vendor_proposed_commission') is-invalid @enderror"
                                id="vendorCommission" name="vendor_proposed_commission" value="{{ old('vendor_proposed_commission') }}"
                                min="{{ $commissionSettings['min'] }}" max="{{ $commissionSettings['max'] }}"
                                placeholder="Enter commission rate">
                            <label for="vendorCommission">Proposed Commission Rate (%) - Leave empty for default</label>
                            <small class="form-text text-muted">
                                Allowed range: {{ $commissionSettings['min'] }}% - {{ $commissionSettings['max'] }}%
                            </small>
                            @error('vendor_proposed_commission')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        <i class="fas fa-plus-circle"></i> Add Variation Group
                    </button>

                    <!-- Combinations Preview -->
                    <div id="combinationsPreview" style="display: none; margin-top: 30px;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0"><i class="fas fa-layer-group"></i> Variation Combinations</h5>
                            <span id="combinationsCount" class="badge bg-success fs-6">0 combinations</span>
                        </div>
                        <p class="text-muted mb-3">
                            <i class="fas fa-info-circle"></i>
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
                    <h4>Submit Product</h4>
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-clock"></i>
                        <strong>Pending Approval:</strong>
                        <p class="mb-0 mt-2 small">Your product will be submitted for admin review. It will remain <strong>inactive</strong> until approved by the admin.</p>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fas fa-paper-plane"></i> Submit for Approval
                    </button>
                </div>

                <!-- Product Images Section -->
                <div class="form-section">
                    <h4>Product Images</h4>
                    <div class="mb-3">
                        <label for="thumb_image">Product Featured Image</label>
                        <input type="file" class="form-control" id="thumb_image" name="thumb_image"
                            accept="image/jpeg,image/png,image/jpg,image/gif" required>
                        <div id="thumbnailPreview" class="mt-2" style="max-width: 200px;"></div>
                        @error('thumb_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="images">Product Gallery Images</label>
                        <input type="file" class="form-control" id="images" name="images[]"
                            accept="image/jpeg,image/png,image/jpg,image/gif" multiple>
                        <div id="galleryPreview" class="image-preview mt-2"></div>
                        @error('images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="video_url">Product Video URL (YouTube)</label>
                        <input type="text" name="video_url" id="video_url" class="form-control"
                            value="{{ old('video_url') }}" placeholder="https://www.youtube.com/watch?v=xxxxxx">
                        <small class="form-text text-muted">Optional YouTube video link</small>
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="form-section">
                    <h4>Categories</h4>
                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Category</label>
                        <select class="form-select" name="category_id" id="productCategory" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="productSubCategory" class="form-label">Sub Category</label>
                        <select class="form-select" name="sub_category_id" id="productSubCategory">
                            <option value="">Select Sub Category</option>
                        </select>
                        <small class="form-text text-muted">
                            Subcategories will be loaded automatically when you select a primary category
                        </small>
                    </div>
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
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

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
        const variationsSection = document.getElementById('variationsSection');

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
        if (type === 'variable') {
            variationsSection.style.display = 'block';
        }
    }

    // Variation functions
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
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Only option names are needed here. Pricing, stock, descriptions, and images are managed in the <strong>Combinations table</strong> below.
                        </small>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addOption(this)">
                <i class="fas fa-plus"></i> Add Option
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
                    X
                </button>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control variation-option-input" name="variations[${variationIndex}][options][${optionCount}][name]" placeholder="Enter option name" required onchange="updateCombinationsPreview()">
                <label>Option Name (e.g. Small, Red)</label>
            </div>

            <div class="alert alert-info p-2">
                <small>
                    <i class="fas fa-info-circle"></i>
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

            let totalPrice = 0;
            let minStock = Number.MAX_SAFE_INTEGER;
            let combinationKey = [];

            combination.forEach(option => {
                tableHTML += `<td><span class="badge bg-primary">${option.name}</span></td>`;
                totalPrice += option.price;
                minStock = Math.min(minStock, option.stock);
                combinationKey.push(option.name);
            });

            const defaultRegularPrice = totalPrice.toFixed(2);
            const defaultStock = Math.max(0, minStock);

            tableHTML += `
                <td>
                    <input type="number" step="0.01" min="0" class="form-control combination-regular-price" 
                           name="combinations[${index}][regular_price]" value="${defaultRegularPrice}"
                           placeholder="0.00" style="width: 90px;">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control combination-offer-price" 
                           name="combinations[${index}][offer_price]" value=""
                           placeholder="Optional" style="width: 90px;">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control combination-product-cost" 
                           name="combinations[${index}][product_cost]" value=""
                           placeholder="Cost" style="width: 90px;">
                </td>
                <td>
                    <input type="number" step="0.01" min="0" class="form-control combination-wholesale-price" 
                           name="combinations[${index}][wholesale_price]" value=""
                           placeholder="Wholesale" style="width: 90px;">
                </td>
                <td>
                    <input type="number" min="0" class="form-control combination-stock" 
                           name="combinations[${index}][stock_quantity]" value="${defaultStock}"
                           placeholder="0" style="width: 70px;">
                </td>
                <td>
                    <textarea class="form-control combination-description" 
                              name="combinations[${index}][short_description]" rows="2" 
                              placeholder="Brief description..." style="width: 200px; resize: vertical;"></textarea>
                </td>
                <td>
                    <div class="mb-2">
                        <input type="file" class="form-control combination-image" 
                               name="combinations[${index}][featured_image]" accept="image/*"
                               style="width: 150px; font-size: 11px;">
                        <small class="text-muted d-block">Featured Image</small>
                    </div>
                    <div>
                        <input type="file" class="form-control combination-gallery" 
                               name="combinations[${index}][gallery_images][]" accept="image/*" multiple
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
                <strong><i class="fas fa-info-circle"></i> Rich Combination System:</strong>
                <ul class="mb-0 mt-2">
                    <li><strong>Regular Price:</strong> Base price for this combination</li>
                    <li><strong>Offer Price:</strong> Optional discount price - leave empty if no offer</li>
                    <li><strong>Stock:</strong> Inventory quantity for this specific combination</li>
                    <li><strong>Description:</strong> Optional custom description shown to customers</li>
                    <li><strong>Featured Image:</strong> Main image for this combination</li>
                    <li><strong>Gallery Images:</strong> Additional images for this combination (multiple files supported)</li>
                    <li>Total of <strong>${combinations.length}</strong> combinations will be created</li>
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
        // Handle single variation group
        if (arrays.length === 1) {
            return arrays[0].map(option => [option]);
        }

        // Handle multiple variation groups
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

        // Gallery Images Preview
        const galleryImagesInput = document.getElementById('images');
        const galleryPreview = document.getElementById('galleryPreview');
        let selectedFiles = [];

        galleryImagesInput.addEventListener('change', function() {
            const newFiles = Array.from(this.files);
            selectedFiles = selectedFiles.concat(newFiles);
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

        // Handle form submission
        const productForm = document.getElementById('productForm');
        productForm.addEventListener('submit', function(e) {
            if (selectedFiles.length > 0) {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => {
                    dataTransfer.items.add(file);
                });
                galleryImagesInput.files = dataTransfer.files;
            }
        });

        // Dynamic Subcategory Loading
        const productCategorySelect = document.getElementById('productCategory');
        const productSubCategorySelect = document.getElementById('productSubCategory');

        if (productCategorySelect && productSubCategorySelect) {
            productCategorySelect.addEventListener('change', function() {
                const selectedCategoryId = this.value;
                productSubCategorySelect.value = '';

                if (selectedCategoryId) {
                    loadSubcategories(selectedCategoryId);
                } else {
                    clearSubcategories();
                }
            });

            const preSelectedCategoryId = productCategorySelect.value;
            if (preSelectedCategoryId) {
                loadSubcategories(preSelectedCategoryId);
            }
        }
    });

    // Function to load subcategories
    function loadSubcategories(categoryId) {
        const subCategorySelect = document.getElementById('productSubCategory');

        if (!categoryId || !subCategorySelect) return;

        subCategorySelect.innerHTML = '<option value="">Loading subcategories...</option>';
        subCategorySelect.disabled = true;
        subCategorySelect.classList.add('subcategory-loading');

        fetch(`/vendor/products/subcategories/${categoryId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';

                if (data && data.length > 0) {
                    data.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        subCategorySelect.appendChild(option);
                    });
                } else {
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
    }
</script>
@endsection