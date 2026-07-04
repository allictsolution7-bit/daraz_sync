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

    @media (max-width: 1200px) {
        .product-form-container {
            grid-template-columns: 1fr;
        }
    }

    .form-section {
        background: #fff;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
    }

    #combinationsTable thead th {
        background: #2c3e50 !important;
        color: white;
        font-weight: 600;
        text-align: center;
    }

    #combinationsTable .combination-regular-price,
    #combinationsTable .combination-offer-price,
    #combinationsTable .combination-stock {
        border: 2px solid #e9ecef;
        border-radius: 6px;
        text-align: center;
    }
</style>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4><i class="fas fa-plus-circle"></i> Create New Product</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">Products</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('vendor.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>
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

<form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
    @csrf

    <div class="product-form-container">
        <!-- Main Content -->
        <div class="main-content">
            
            <!-- Basic Information -->
            <div class="form-section">
                <h4><i class="fas fa-info-circle"></i> Basic Information</h4>
                
                <div class="mb-3">
                    <label class="form-label">Product Title *</label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Short Description</label>
                    <textarea name="short_description" class="form-control @error('short_description') is-invalid @enderror" 
                              rows="2">{{ old('short_description') }}</textarea>
                    @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tags (comma separated)</label>
                    <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror" 
                           value="{{ old('tags') }}" placeholder="electronics, smartphone, samsung">
                    @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Product Type & Variations -->
            <div class="form-section">
                <h4><i class="fas fa-boxes"></i> Product Type & Variations</h4>
                
                <div class="mb-3">
                    <label class="form-label">Product Type</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="product_type" id="typeSimple" 
                               value="simple" checked autocomplete="off">
                        <label class="btn btn-outline-primary" for="typeSimple">
                            <i class="fas fa-box"></i> Simple Product
                        </label>

                        <input type="radio" class="btn-check" name="product_type" id="typeVariable" 
                               value="variable" autocomplete="off">
                        <label class="btn btn-outline-primary" for="typeVariable">
                            <i class="fas fa-layer-group"></i> Variable Product
                        </label>
                    </div>
                </div>

                <!-- Variations Section (Hidden by default) -->
                <div id="variationsSection" style="display: none;">
                    <div id="variationsList"></div>
                    
                    <button type="button" class="btn btn-outline-primary w-100 mt-3" id="addVariationBtn">
                        <i class="fas fa-plus"></i> Add Variation
                    </button>

                    <div class="mt-3" id="generateCombinationsBtn-container" style="display: none;">
                        <button type="button" class="btn btn-success w-100" id="generateCombinationsBtn">
                            <i class="fas fa-magic"></i> Generate All Combinations
                        </button>
                    </div>

                    <!-- Combinations Table -->
                    <div id="combinationsTable" class="mt-4" style="display: none;">
                        <h5>Product Combinations</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Combination</th>
                                        <th>Regular Price</th>
                                        <th>Sale Price</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="combinationsTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing (for simple products) -->
            <div class="form-section" id="simplePricingSection">
                <h4><i class="fas fa-dollar-sign"></i> Pricing</h4>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Regular Price *</label>
                            <input type="number" name="old_price" class="form-control @error('old_price') is-invalid @enderror" 
                                   value="{{ old('old_price') }}" step="0.01" min="0" id="regularPrice">
                            @error('old_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Sale Price *</label>
                            <input type="number" name="offer" class="form-control @error('offer') is-invalid @enderror" 
                                   value="{{ old('offer') }}" step="0.01" min="0" id="salePrice">
                            @error('offer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Product Cost (Your Cost)</label>
                            <input type="number" name="product_cost" class="form-control @error('product_cost') is-invalid @enderror" 
                                   value="{{ old('product_cost') }}" step="0.01" min="0">
                            @error('product_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Commission Rate -->
                <div class="mb-3">
                    <label class="form-label">
                        Proposed Commission Rate (%)
                        <small class="text-muted">
                            (Leave empty to use default: {{ $commissionSettings['default'] }}%)
                        </small>
                    </label>
                    <input type="number" name="vendor_proposed_commission" 
                           class="form-control @error('vendor_proposed_commission') is-invalid @enderror" 
                           value="{{ old('vendor_proposed_commission') }}" step="0.01"
                           min="{{ $commissionSettings['min'] }}" max="{{ $commissionSettings['max'] }}"
                           placeholder="e.g., 15.00">
                    <small class="text-muted">
                        Allowed range: {{ $commissionSettings['min'] }}% - {{ $commissionSettings['max'] }}%
                    </small>
                    @error('vendor_proposed_commission')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Inventory (for simple products) -->
            <div class="form-section" id="simpleInventorySection">
                <h4><i class="fas fa-warehouse"></i> Inventory</h4>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                                   value="{{ old('sku') }}" placeholder="e.g., PROD-001">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" 
                                   value="{{ old('barcode') }}">
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                   value="{{ old('quantity', 0) }}" min="0" id="quantity">
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Low Stock Alert</label>
                            <input type="number" name="low_stock_threshold" 
                                   class="form-control @error('low_stock_threshold') is-invalid @enderror" 
                                   value="{{ old('low_stock_threshold', 5) }}" min="0">
                            @error('low_stock_threshold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="manage_stock" class="form-check-input" 
                               id="manage_stock" value="1" {{ old('manage_stock') ? 'checked' : '' }}>
                        <label class="form-check-label" for="manage_stock">
                            Track inventory for this product
                        </label>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="form-section">
                <h4><i class="fas fa-images"></i> Product Images</h4>
                
                <div class="mb-3">
                    <label class="form-label">Thumbnail Image *</label>
                    <input type="file" name="thumb_image" class="form-control @error('thumb_image') is-invalid @enderror" 
                           accept="image/*" id="thumbImage">
                    <div id="thumbPreview" class="mt-2"></div>
                    @error('thumb_image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Gallery Images</label>
                    <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" 
                           accept="image/*" multiple id="galleryImages">
                    <small class="text-muted">You can select multiple images (Max 6)</small>
                    <div id="galleryPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                    @error('images.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Video URL (YouTube, Vimeo)</label>
                    <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror" 
                           value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=...">
                    @error('video_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="form-section">
                <h4><i class="fas fa-truck"></i> Shipping Information</h4>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror" 
                                   value="{{ old('weight') }}" step="0.01" min="0">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Length (cm)</label>
                            <input type="number" name="length" class="form-control @error('length') is-invalid @enderror" 
                                   value="{{ old('length') }}" step="0.01" min="0">
                            @error('length')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Width (cm)</label>
                            <input type="number" name="width" class="form-control @error('width') is-invalid @enderror" 
                                   value="{{ old('width') }}" step="0.01" min="0">
                            @error('width')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" name="height" class="form-control @error('height') is-invalid @enderror" 
                                   value="{{ old('height') }}" step="0.01" min="0">
                            @error('height')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specifications -->
            <div class="form-section">
                <h4><i class="fas fa-list"></i> Specifications</h4>
                
                <div id="specificationsList">
                    <div class="row mb-2 specification-item">
                        <div class="col-md-5">
                            <input type="text" name="spec_keys[]" class="form-control" placeholder="Key (e.g., Color)">
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="spec_values[]" class="form-control" placeholder="Value (e.g., Red)">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-spec" disabled>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="addSpecBtn">
                    <i class="fas fa-plus"></i> Add Specification
                </button>
            </div>

            <!-- SEO Section -->
            <div class="form-section">
                <h4><i class="fas fa-search"></i> SEO Information</h4>
                
                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="seo_title" class="form-control @error('seo_title') is-invalid @enderror" 
                           value="{{ old('seo_title') }}" placeholder="Leave empty to use product title">
                    @error('seo_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="seo_description" class="form-control @error('seo_description') is-invalid @enderror" 
                              rows="3" placeholder="Brief description for search engines">{{ old('seo_description') }}</textarea>
                    @error('seo_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Meta Keywords (comma separated)</label>
                    <input type="text" name="seo_keywords" class="form-control @error('seo_keywords') is-invalid @enderror" 
                           value="{{ old('seo_keywords') }}" placeholder="keyword1, keyword2, keyword3">
                    @error('seo_keywords')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>

        <!-- Side Content -->
        <div class="side-content">
            
            <!-- Publish Section -->
            <div class="form-section">
                <h4><i class="fas fa-cog"></i> Publish</h4>
                
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Create Product
                    </button>
                    <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </div>

            <!-- Category Section -->
            <div class="form-section">
                <h4><i class="fas fa-folder"></i> Organization</h4>
                
                <div class="mb-3">
                    <label class="form-label">Category *</label>
                    <select name="category_id" id="categorySelect" 
                            class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Sub Category</label>
                    <select name="sub_category_id" id="subCategorySelect" 
                            class="form-select @error('sub_category_id') is-invalid @enderror">
                        <option value="">Select Sub Category</option>
                    </select>
                    @error('sub_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Child Category</label>
                    <select name="child_category_id" id="childCategorySelect" 
                            class="form-select @error('child_category_id') is-invalid @enderror">
                        <option value="">Select Child Category</option>
                    </select>
                    @error('child_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Brand</label>
                    <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Summernote
    $('#description').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Product Type Toggle
    const typeSimple = document.getElementById('typeSimple');
    const typeVariable = document.getElementById('typeVariable');
    const variationsSection = document.getElementById('variationsSection');
    const simplePricingSection = document.getElementById('simplePricingSection');
    const simpleInventorySection = document.getElementById('simpleInventorySection');

    typeSimple.addEventListener('change', function() {
        if (this.checked) {
            variationsSection.style.display = 'none';
            simplePricingSection.style.display = 'block';
            simpleInventorySection.style.display = 'block';
            document.getElementById('regularPrice').required = true;
            document.getElementById('salePrice').required = true;
        }
    });

    typeVariable.addEventListener('change', function() {
        if (this.checked) {
            variationsSection.style.display = 'block';
            simplePricingSection.style.display = 'none';
            simpleInventorySection.style.display = 'none';
            document.getElementById('regularPrice').required = false;
            document.getElementById('salePrice').required = false;
        }
    });

    // Variation Management
    let variationCount = 0;
    const variationsList = document.getElementById('variationsList');
    const addVariationBtn = document.getElementById('addVariationBtn');
    const generateCombinationsBtn = document.getElementById('generateCombinationsBtn');

    addVariationBtn.addEventListener('click', function() {
        addVariation();
    });

    function addVariation() {
        variationCount++;
        const variationHtml = `
            <div class="variation" data-variation="${variationCount}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Variation ${variationCount}</h5>
                    <button type="button" class="btn btn-sm btn-danger remove-variation" data-variation="${variationCount}">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Variation Name *</label>
                    <input type="text" name="variations[${variationCount}][name]" class="form-control variation-name" 
                           placeholder="e.g., Size, Color" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Options *</label>
                    <div class="options-list" data-variation="${variationCount}">
                        <div class="option d-flex gap-2 mb-2">
                            <input type="text" name="variations[${variationCount}][values][]" 
                                   class="form-control" placeholder="Option value" required>
                            <button type="button" class="btn btn-sm btn-danger remove-option" disabled>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary add-option" data-variation="${variationCount}">
                        <i class="fas fa-plus"></i> Add Option
                    </button>
                </div>
            </div>
        `;
        
        variationsList.insertAdjacentHTML('beforeend', variationHtml);
        
        // Update remove buttons state
        updateRemoveButtons();
        
        // Show generate combinations button if we have variations
        if (variationCount > 0) {
            document.getElementById('generateCombinationsBtn-container').style.display = 'block';
        }
    }

    // Event delegation for remove variation
    variationsList.addEventListener('click', function(e) {
        if (e.target.closest('.remove-variation')) {
            const btn = e.target.closest('.remove-variation');
            const variation = btn.closest('.variation');
            variation.remove();
            variationCount--;
            
            if (variationCount === 0) {
                document.getElementById('generateCombinationsBtn-container').style.display = 'none';
                document.getElementById('combinationsTable').style.display = 'none';
            }
            
            updateRemoveButtons();
        }
        
        if (e.target.closest('.add-option')) {
            const btn = e.target.closest('.add-option');
            const variationId = btn.getAttribute('data-variation');
            const optionsList = document.querySelector(`.options-list[data-variation="${variationId}"]`);
            
            const optionHtml = `
                <div class="option d-flex gap-2 mb-2">
                    <input type="text" name="variations[${variationId}][values][]" 
                           class="form-control" placeholder="Option value" required>
                    <button type="button" class="btn btn-sm btn-danger remove-option">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            optionsList.insertAdjacentHTML('beforeend', optionHtml);
            updateOptionButtons(variationId);
        }
        
        if (e.target.closest('.remove-option')) {
            const btn = e.target.closest('.remove-option');
            const option = btn.closest('.option');
            const optionsList = option.closest('.options-list');
            const variationId = optionsList.getAttribute('data-variation');
            
            option.remove();
            updateOptionButtons(variationId);
        }
    });

    function updateRemoveButtons() {
        const variations = document.querySelectorAll('.variation');
        variations.forEach(variation => {
            const removeBtn = variation.querySelector('.remove-variation');
            removeBtn.disabled = variations.length === 1;
        });
    }

    function updateOptionButtons(variationId) {
        const optionsList = document.querySelector(`.options-list[data-variation="${variationId}"]`);
        const options = optionsList.querySelectorAll('.option');
        
        options.forEach(option => {
            const removeBtn = option.querySelector('.remove-option');
            removeBtn.disabled = options.length === 1;
        });
    }

    // Generate Combinations
    generateCombinationsBtn.addEventListener('click', function() {
        const variations = [];
        
        document.querySelectorAll('.variation').forEach(variation => {
            const name = variation.querySelector('.variation-name').value.trim();
            if (!name) return;
            
            const values = [];
            variation.querySelectorAll('.options-list input').forEach(input => {
                const value = input.value.trim();
                if (value) values.push(value);
            });
            
            if (values.length > 0) {
                variations.push({ name, values });
            }
        });
        
        if (variations.length === 0) {
            alert('Please add at least one variation with options');
            return;
        }
        
        generateCombinationTable(variations);
    });

    function generateCombinationTable(variations) {
        // Generate all combinations
        const combinations = cartesianProduct(...variations.map(v => v.values));
        const tableBody = document.getElementById('combinationsTableBody');
        tableBody.innerHTML = '';
        
        combinations.forEach((combo, index) => {
            const comboStr = Array.isArray(combo) ? combo.join(' / ') : combo;
            const row = `
                <tr>
                    <td>
                        <strong>${comboStr}</strong>
                        <input type="hidden" name="combinations[${index}][name]" value="${comboStr}">
                    </td>
                    <td>
                        <input type="number" name="combinations[${index}][old_price]" 
                               class="form-control combination-regular-price" step="0.01" min="0" required>
                    </td>
                    <td>
                        <input type="number" name="combinations[${index}][offer]" 
                               class="form-control combination-offer-price" step="0.01" min="0" required>
                    </td>
                    <td>
                        <input type="number" name="combinations[${index}][quantity]" 
                               class="form-control combination-stock" min="0" value="0" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-combination">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
        
        document.getElementById('combinationsTable').style.display = 'block';
    }

    // Helper function for cartesian product
    function cartesianProduct(...arrays) {
        return arrays.reduce((acc, array) => {
            return acc.flatMap(x => array.map(y => [...(Array.isArray(x) ? x : [x]), y]));
        }, [[]]);
    }

    // Remove combination
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-combination')) {
            e.target.closest('tr').remove();
        }
    });

    // Specifications Management
    let specCount = 1;
    document.getElementById('addSpecBtn').addEventListener('click', function() {
        specCount++;
        const specHtml = `
            <div class="row mb-2 specification-item">
                <div class="col-md-5">
                    <input type="text" name="spec_keys[]" class="form-control" placeholder="Key">
                </div>
                <div class="col-md-6">
                    <input type="text" name="spec_values[]" class="form-control" placeholder="Value">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-spec">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        document.getElementById('specificationsList').insertAdjacentHTML('beforeend', specHtml);
        
        // Update first item's remove button
        const firstSpec = document.querySelector('.specification-item .remove-spec');
        if (firstSpec) firstSpec.disabled = false;
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-spec')) {
            const specs = document.querySelectorAll('.specification-item');
            if (specs.length > 1) {
                e.target.closest('.specification-item').remove();
            }
        }
    });

    // Image Preview - Thumbnail
    document.getElementById('thumbImage').addEventListener('change', function(e) {
        const preview = document.getElementById('thumbPreview');
        preview.innerHTML = '';
        
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px;">`;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Image Preview - Gallery
    document.getElementById('galleryImages').addEventListener('change', function(e) {
        const preview = document.getElementById('galleryPreview');
        preview.innerHTML = '';
        
        Array.from(e.target.files).slice(0, 6).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">`;
                preview.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    });

    // Category Cascade - Load Subcategories
    const categorySelect = document.getElementById('categorySelect');
    const subCategorySelect = document.getElementById('subCategorySelect');
    const childCategorySelect = document.getElementById('childCategorySelect');

    categorySelect.addEventListener('change', function() {
        const categoryId = this.value;
        subCategorySelect.innerHTML = '<option value="">Loading...</option>';
        childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';
        
        if (!categoryId) {
            subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
            return;
        }

        fetch(`/vendor/products/subcategories/${categoryId}`)
            .then(response => response.json())
            .then(data => {
                subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
                data.forEach(subcat => {
                    subCategorySelect.innerHTML += `<option value="${subcat.id}">${subcat.name}</option>`;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                subCategorySelect.innerHTML = '<option value="">Error loading subcategories</option>';
            });
    });

    subCategorySelect.addEventListener('change', function() {
        const subCategoryId = this.value;
        childCategorySelect.innerHTML = '<option value="">Loading...</option>';
        
        if (!subCategoryId) {
            childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';
            return;
        }

        fetch(`/vendor/products/childcategories/${subCategoryId}`)
            .then(response => response.json())
            .then(data => {
                childCategorySelect.innerHTML = '<option value="">Select Child Category</option>';
                data.forEach(childcat => {
                    childCategorySelect.innerHTML += `<option value="${childcat.id}">${childcat.name}</option>`;
                });
            })
            .catch(error => {
                console.error('Error:', error);
                childCategorySelect.innerHTML = '<option value="">Error loading child categories</option>';
            });
    });
});
</script>

@endsection
