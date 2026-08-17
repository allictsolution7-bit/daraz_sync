@extends('layouts.master')
@section('styles')
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
        --font: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    body {
        font-family: var(--font) !important;
        background: radial-gradient(circle at 10% 20%, rgba(243, 244, 246, 1) 0%, rgba(229, 231, 235, 1) 90%);
        color: #334155;
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
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.6);
        padding: 28px;
        margin-bottom: 0 !important; /* Managed by grid gap */
        border-radius: 24px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .form-section:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.07);
        border-color: rgba(255, 255, 255, 0.8);
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
        background-color: rgba(255, 255, 255, 0.8);
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

    /* WooCommerce Style Category Multi-Select Redesign */
    .category-multiselect-container {
        background: rgba(255, 255, 255, 0.8);
        border: 1.5px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
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
        box-shadow: var(--shadow-sm);
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

    /* File Dropzones */
    .dropzone {
        border: 2.5px dashed var(--primary) !important;
        border-radius: 18px !important;
        background: rgba(99, 102, 241, 0.02) !important;
        min-height: 130px !important;
        padding: 20px !important;
        transition: all 0.25s ease;
    }

    .dropzone:hover {
        background: rgba(99, 102, 241, 0.05) !important;
        border-color: var(--primary-hover) !important;
    }

    /* Primary buttons */
    .btn-primary {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
        border: none !important;
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 700;
        letter-spacing: -0.01em;
        box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35) !important;
        transition: all 0.25s !important;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%) !important;
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45) !important;
        transform: translateY(-1px);
    }

    .btn-outline-secondary {
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 700;
        border-width: 1.5px;
    }

    /* Modern Pill Tabs Styling */
    .modern-tabs {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        padding: 6px;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: var(--shadow-sm);
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

    /* Layout Spacing Fixes */
    .tab-content {
        margin-top: 2rem;
    }

    .tab-pane .row {
        --bs-gutter-x: 28px;
        --bs-gutter-y: 28px;
    }

    .form-section {
        margin-bottom: 28px !important;
    }

    .container-fluid {
        max-width: 1440px;
        padding: 0.75rem 2rem;
    }

    /* Page Header */
    .d-flex.justify-content-between.align-items-center.mb-4 {
        margin-bottom: 1rem !important;
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(12px);
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
    }

    .d-flex.justify-content-between.align-items-center.mb-4 h4 {
        font-size: 1.4rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--dark);
        margin-bottom: 0 !important;
    }
    
    .modern-breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
    }
    
    .modern-breadcrumb .breadcrumb {
        margin-bottom: 0 !important;
        background: transparent !important;
        padding: 0 !important;
    }

    /* Mobile Responsive Optimizations */
    @media (max-width: 576px) {
        .d-flex.justify-content-between.align-items-center.mb-4 {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 12px !important;
            padding: 12px !important;
        }
        .d-flex.justify-content-between.align-items-center.mb-4 h4 {
            font-size: 1.15rem !important;
            text-align: center !important;
        }
        .d-flex.justify-content-between.align-items-center.mb-4 .d-flex.align-items-center.gap-3 {
            flex-direction: column !important;
            align-items: center !important;
            gap: 8px !important;
            width: 100% !important;
        }
        .d-flex.justify-content-between.align-items-center.mb-4 .d-flex.align-items-center.gap-3 a.btn {
            font-size: 0.8rem !important;
            padding: 6px 12px !important;
            width: 100% !important;
            text-align: center !important;
        }
        .modern-breadcrumb {
            display: none !important; /* Hide breadcrumbs on small mobile to save space */
        }
        
        /* Floating labels overflow and overlapping fixes */
        .form-floating > label {
            font-size: 11px !important;
            padding: 12px 10px !important;
            text-overflow: ellipsis !important;
            overflow: hidden !important;
            white-space: nowrap !important;
            width: 100% !important;
        }
        .form-floating > .form-control {
            font-size: 13px !important;
            height: 52px !important;
            padding: 22px 10px 6px !important;
        }
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            transform: scale(0.8) translateY(-0.75rem) translateX(0.15rem) !important;
            width: auto !important;
        }

        /* Footer Action Panel */
        .form-section.mt-4.d-flex.flex-wrap.justify-content-between.align-items-center {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 15px !important;
            padding: 15px !important;
        }
        .form-section.mt-4.d-flex.flex-wrap.justify-content-between.align-items-center label {
            font-size: 11px !important;
        }
        .form-section.mt-4.d-flex.flex-wrap.justify-content-between.align-items-center select {
            font-size: 11px !important;
            height: 36px !important;
            padding: 4px 8px !important;
            width: 130px !important;
        }
        .form-section.mt-4.d-flex.flex-wrap.justify-content-between.align-items-center > div:first-child {
            width: 100% !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }
        .form-section.mt-4.d-flex.flex-wrap.justify-content-between.align-items-center > div:last-child {
            width: 100% !important;
            display: flex !important;
            gap: 8px !important;
        }
        .form-section.mt-4.d-flex.flex-wrap.justify-content-between.align-items-center > div:last-child a,
        .form-section.mt-4.d-flex.flex-wrap.justify-content-between.align-items-center > div:last-child button {
            flex: 1 !important;
            text-align: center !important;
            font-size: 11px !important;
            padding: 6px 8px !important;
            height: auto !important;
            white-space: nowrap !important;
        }

        /* Modern Tabs Font and Size Reduction on Mobile */
        .modern-tabs {
            padding: 4px !important;
            border-radius: 12px !important;
            width: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            gap: 4px !important;
        }
        .modern-tabs .nav-link {
            font-size: 0.75rem !important;
            padding: 6px 12px !important;
            border-radius: 8px !important;
            text-align: center !important;
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
        </div>
        <div class="d-flex align-items-center gap-3">
            <nav aria-label="breadcrumb" class="modern-breadcrumb me-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>
            </nav>
            <a href="{{ route('admin.items.index') }}" class="btn btn-sm btn-outline-secondary">← Back to Products</a>
        </div>
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

    <form method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data" id="productForm">
        @csrf        <!-- Tabbed Interface for Product Creation Workspace -->
        <div class="row">
            <div class="col-12">
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
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Brand Section -->
                        <div class="form-section mb-4">
                            <h4>Brand</h4>
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
                                </div>
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
                                <div class="row g-2 mb-3">
                                    <div class="col-12 col-sm-6 col-lg">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" name="product_cost" id="productCost"
                                                placeholder="Enter product cost" value="{{ old('product_cost') }}" step="0.01">
                                            <label for="productCost">Product Cost</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" name="offer" id="productOfferPrice"
                                                placeholder="Enter sale price" value="{{ old('offer') }}" step="0.01">
                                            <label for="productOfferPrice">Sale Price ({{ \App\Services\SettingsService::get('single_product', 'sale_price_percent', 10) }}%)</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" name="old_price" id="productOldPrice"
                                                placeholder="Enter regular price" value="{{ old('old_price') }}" step="0.01" required>
                                            <label for="productOldPrice">Old Price ({{ \App\Services\SettingsService::get('single_product', 'old_price_percent', 20) }}%)</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" name="wholesale_price" id="wholesalePrice"
                                                placeholder="Enter wholesale price" value="{{ old('wholesale_price') }}" step="0.01">
                                            <label for="wholesalePrice">Wholesale Price ({{ \App\Services\SettingsService::get('single_product', 'wholesale_price_percent', 2) }}%)</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" name="reseller_price" id="resellerPrice"
                                                placeholder="Enter reseller price" value="{{ old('reseller_price') }}" step="0.01">
                                            <label for="resellerPrice">Reseller Price ({{ \App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5) }}%)</label>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 col-lg">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" name="global_price" id="globalPrice"
                                                placeholder="Enter global price" value="{{ old('global_price') }}" step="0.01">
                                            <label for="globalPrice">Global Price ({{ \App\Services\SettingsService::get('single_product', 'global_price_percent', 10) }}%)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Wholesale Pricing Tiers -->
                            <div class="mb-3 border rounded p-3 bg-light" id="simpleWholesaleTiersSection" style="display: block;">
                                <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-tags-fill"></i> Tiered Wholesale Pricing (Optional)</h6>
                                <p class="text-muted small mb-3">Add different wholesale prices based on purchase quantity. For example, buying 500+ items can have a lower price than 100+ items.</p>
                                
                                <div id="simpleTiersContainer">
                                    <!-- Dynamic rows loaded here -->
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addSimpleWholesaleTier()"><i class="bi bi-plus-circle"></i> Add Pricing Tier</button>
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

                            <!-- Delivery & Warranty Services -->
                            <div class="mt-4 pt-3 border-top">
                                <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-primary me-2"></i>Delivery & Warranty Services</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Cash on Delivery</label>
                                        <select name="settings[enable_cod_option]" class="form-select">
                                            <option value="1" {{ setting('general', 'enable_cod_option', '1') == '1' ? 'selected' : '' }}>Available</option>
                                            <option value="0" {{ setting('general', 'enable_cod_option', '1') == '0' ? 'selected' : '' }}>Not Available</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Change of Mind Option</label>
                                        <select name="settings[enable_change_of_mind]" class="form-select">
                                            <option value="1" {{ setting('general', 'enable_change_of_mind', '1') == '1' ? 'selected' : '' }}>Available</option>
                                            <option value="0" {{ setting('general', 'enable_change_of_mind', '1') == '0' ? 'selected' : '' }}>Not Available</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Easy Return Period</label>
                                        <div class="input-group">
                                            <input type="number" name="return_period" class="form-control" placeholder="0" min="0" value="{{ old('return_period', 0) }}">
                                            <span class="input-group-text">Days</span>
                                        </div>
                                        <small class="form-text text-muted">Enter the number of days allowed for easy return (e.g. 7 or 14). Enter 0 if return is not allowed.</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label font-weight-bold">Warranty Status</label>
                                        <select name="settings[warranty_status_option]" class="form-select">
                                            <option value="0" {{ setting('general', 'warranty_status_option', '0') == '0' ? 'selected' : '' }}>Warranty not available</option>
                                            <option value="1" {{ setting('general', 'warranty_status_option', '0') == '1' ? 'selected' : '' }}>Warranty available</option>
                                        </select>
                                    </div>
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
                                <i class="bi bi-plus-circle"></i> Add Variation Group
                            </button>

                            <!-- Combinations Preview -->
                            <div id="combinationsPreview" style="display: none; margin-top: 30px;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="bi bi-grid-3x3-gap"></i> Variation Combinations</h5>
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
                                    value="{{ old('video_url', $product->video_url ?? '') }}"
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

                                        @foreach ($categories as $category)
                                        <div class="category-list-item category-level-0" data-category-name="{{ strtolower($category->name) }}" data-level="0" data-category-id="{{ $category->id }}">
                                            <label>
                                                <input type="checkbox" name="additional_categories[]" value="{{ $category->id }}" id="category_{{ $category->id }}" class="category-checkbox" {{ in_array($category->id, $oldAdditionalCategories) ? 'checked' : '' }}>
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

                <!-- Book Details Section (full width below categories and badges) -->
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
                                        <label for="Language" class="form-label">Language</label>
                                        <select class="form-select" name="language">
                                            <option value="">Select Language</option>
                                            <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                            <option value="Bangla" {{ old('language') == 'Bangla' ? 'selected' : '' }}>Bangla</option>
                                            <option value="Hindi" {{ old('language') == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="sample_path" class="form-label">Upload Sample File</label>
                                        <input type="file" class="form-control" name="sample_path" id="sample_path"
                                            placeholder="Upload sample file">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pages" class="form-label">Total Pages</label>
                                        <input type="number" class="form-control" name="pages" id="pages"
                                            placeholder="Enter total pages" value="{{ old('pages') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="Cover" class="form-label">Cover Type</label>
                                        <select class="form-select" name="cover">
                                            <option value="">Select Cover</option>
                                            <option value="Hardcover" {{ old('cover') == 'Hardcover' ? 'selected' : '' }}>Hardcover</option>
                                            <option value="Paperback" {{ old('cover') == 'Paperback' ? 'selected' : '' }}>Paperback</option>
                                            <option value="Ebook" {{ old('cover') == 'Ebook' ? 'selected' : '' }}>Ebook</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="country" class="form-label">Country of Origin</label>
                                        <select class="form-select" name="country">
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
                                        <label for="seoMetaRobots">Robots Meta</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="seoSchemaMarkup" class="form-label">Custom Schema Markup (JSON-LD)</label>
                                        <textarea class="form-control" name="seo[schema_markup]" id="seoSchemaMarkup" rows="4" placeholder="Enter custom schema markup"></textarea>
                                        <small class="form-text text-muted">Optional: Paste custom JSON-LD schema markup. Leave empty to
                                            auto-generate basic product schema.</small>
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
            </div>
        </div>

        <!-- Sticky/Fixed Footer Action Panel at the bottom -->
        <div class="form-section mt-4 d-flex flex-wrap justify-content-between align-items-center" style="background: rgba(255, 255, 255, 0.85); border: 2px solid var(--primary); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.15);">
            <div class="d-flex align-items-center gap-3">
                <label for="productStatus" class="form-label mb-0" style="font-weight: 700; color: var(--dark); text-transform: none; letter-spacing: 0;">Publishing State:</label>
                <select class="form-select" name="status" id="productStatus" style="width: 160px; height: 42px;">
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div>
                <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                <button class="btn btn-primary" type="submit" style="height: 42px;">Create & Publish Product</button>
            </div>
        </div></div>

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
        const simpleWholesaleTiersSection = document.getElementById('simpleWholesaleTiersSection');
        if (type === 'variable') {
            pricingFields.style.display = 'none';
            if (simpleWholesaleTiersSection) simpleWholesaleTiersSection.style.display = 'none';
            // Clear pricing fields for variable products
            document.getElementById('productOldPrice').value = '';
            document.getElementById('productOfferPrice').value = '';
            // Remove name attributes to prevent submission
            document.getElementById('productOldPrice').removeAttribute('name');
            document.getElementById('productOfferPrice').removeAttribute('name');
            document.getElementById('productOldPrice').removeAttribute('required');
        } else {
            pricingFields.style.display = 'block';
            if (type === 'affiliate') {
                if (simpleWholesaleTiersSection) simpleWholesaleTiersSection.style.display = 'none';
            } else {
                if (simpleWholesaleTiersSection) simpleWholesaleTiersSection.style.display = 'block';
            }
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
                    <table class="table table-bordered table-striped align-middle" style="min-width: 1200px;">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>`;
        variationNames.forEach(name => {
            tableHTML += `<th>${name}</th>`;
        });

        tableHTML += `
                                <th>Product Cost (৳)</th>
                                <th>Regular Price (৳)</th>
                                <th>Offer Price (৳)</th>
                                <th>Wholesale Price (৳)</th>
                                <th>Reseller Price (৳)</th>
                                <th>Global Price (৳)</th>
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
                        <div class="input-group input-group-sm" style="width: 110px;">
                            <input type="number" 
                                   step="0.01" 
                                   min="0" 
                                   class="form-control combination-wholesale-price" 
                                   name="combinations[${index}][wholesale_price]" 
                                   value=""
                                   placeholder="Wholesale">
                            <button class="btn btn-outline-secondary px-2" type="button" onclick="openWholesaleTiersModal(${index})" title="Manage Wholesale Tiers">
                                <i class="fas fa-list-ol text-primary"></i>
                            </button>
                        </div>
                        <input type="hidden" name="combinations[${index}][wholesale_tiers]" value="[]">
                        <div class="text-center">
                            <span id="tier-badge-${index}" class="badge bg-success mt-1" style="font-size: 8px; display: none;">0 tier(s)</span>
                        </div>
                    </td>
                    <td>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               class="form-control combination-reseller-price" 
                               name="combinations[${index}][reseller_price]" 
                               value=""
                               placeholder="Reseller"
                               style="width: 90px;">
                    </td>
                    <td>
                        <input type="number" 
                               step="0.01" 
                               min="0" 
                               class="form-control combination-global-price" 
                               name="combinations[${index}][global_price]" 
                               value=""
                               placeholder="Global"
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

    // Product pricing auto-calculation logic based on percentages
    document.addEventListener('DOMContentLoaded', function() {
        const pricingPercentages = {
            sale: {{ \App\Services\SettingsService::get('single_product', 'sale_price_percent', 10) }},
            old: {{ \App\Services\SettingsService::get('single_product', 'old_price_percent', 20) }},
            wholesale: {{ \App\Services\SettingsService::get('single_product', 'wholesale_price_percent', 2) }},
            reseller: {{ \App\Services\SettingsService::get('single_product', 'reseller_price_percent', 5) }},
            global: {{ \App\Services\SettingsService::get('single_product', 'global_price_percent', 10) }}
        };

        const productCostInput = document.getElementById('productCost');
        const salePriceInput = document.getElementById('productOfferPrice');
        const oldPriceInput = document.getElementById('productOldPrice');
        const wholesalePriceInput = document.getElementById('wholesalePrice');
        const resellerPriceInput = document.getElementById('resellerPrice');
        const globalPriceInput = document.getElementById('globalPrice');

        if (productCostInput) {
            productCostInput.addEventListener('input', function() {
                const cost = parseFloat(this.value);
                if (!isNaN(cost) && cost > 0) {
                    if (salePriceInput) salePriceInput.value = (cost * (1 + pricingPercentages.sale / 100)).toFixed(2);
                    if (oldPriceInput) oldPriceInput.value = (cost * (1 + pricingPercentages.old / 100)).toFixed(2);
                    if (wholesalePriceInput) wholesalePriceInput.value = (cost * (1 + pricingPercentages.wholesale / 100)).toFixed(2);
                    if (resellerPriceInput) resellerPriceInput.value = (cost * (1 + pricingPercentages.reseller / 100)).toFixed(2);
                    if (globalPriceInput) globalPriceInput.value = (cost * (1 + pricingPercentages.global / 100)).toFixed(2);
                } else if (this.value === '') {
                    if (salePriceInput) salePriceInput.value = '';
                    if (oldPriceInput) oldPriceInput.value = '';
                    if (wholesalePriceInput) wholesalePriceInput.value = '';
                    if (resellerPriceInput) resellerPriceInput.value = '';
                    if (globalPriceInput) globalPriceInput.value = '';
                }
            });
        }

        // Delegate listener for variation combination table cost inputs
        document.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('combination-product-cost')) {
                const tr = e.target.closest('tr');
                if (tr) {
                    const cost = parseFloat(e.target.value);
                    const salePriceInRow = tr.querySelector('.combination-offer-price');
                    const oldPriceInRow = tr.querySelector('.combination-regular-price');
                    const wholesalePriceInRow = tr.querySelector('.combination-wholesale-price');
                    const resellerPriceInRow = tr.querySelector('.combination-reseller-price');
                    const globalPriceInRow = tr.querySelector('.combination-global-price');

                    if (!isNaN(cost) && cost > 0) {
                        if (salePriceInRow) salePriceInRow.value = (cost * (1 + pricingPercentages.sale / 100)).toFixed(2);
                        if (oldPriceInRow) oldPriceInRow.value = (cost * (1 + pricingPercentages.old / 100)).toFixed(2);
                        if (wholesalePriceInRow) wholesalePriceInRow.value = (cost * (1 + pricingPercentages.wholesale / 100)).toFixed(2);
                        if (resellerPriceInRow) resellerPriceInRow.value = (cost * (1 + pricingPercentages.reseller / 100)).toFixed(2);
                        if (globalPriceInRow) globalPriceInRow.value = (cost * (1 + pricingPercentages.global / 100)).toFixed(2);
                    } else if (e.target.value === '') {
                        if (salePriceInRow) salePriceInRow.value = '';
                        if (oldPriceInRow) oldPriceInRow.value = '';
                        if (wholesalePriceInRow) wholesalePriceInRow.value = '';
                        if (resellerPriceInRow) resellerPriceInRow.value = '';
                        if (globalPriceInRow) globalPriceInRow.value = '';
                    }
                }
            }
        });
    });

    let simpleTierCounter = 0;
    function addSimpleWholesaleTier() {
        const container = document.getElementById('simpleTiersContainer');
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center mb-2 tier-row';
        row.innerHTML = `
            <div class="col-5">
                <input type="number" class="form-control" name="wholesale_tiers[${simpleTierCounter}][min_quantity]" placeholder="Min Qty" required min="1">
            </div>
            <div class="col-5">
                <input type="number" step="0.01" class="form-control" name="wholesale_tiers[${simpleTierCounter}][price]" placeholder="Price (৳)" required min="0">
            </div>
            <div class="col-2 text-end">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.tier-row').remove()" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px;"><i class="fas fa-trash"></i></button>
            </div>
        `;
        container.appendChild(row);
        simpleTierCounter++;
    }

    let currentModalCombinationIndex = null;
    let currentModalTiers = [];

    window.openWholesaleTiersModal = function(index) {
        currentModalCombinationIndex = index;
        const hiddenInput = document.querySelector(`input[name="combinations[${index}][wholesale_tiers]"]`);
        if (hiddenInput) {
            try {
                currentModalTiers = JSON.parse(hiddenInput.value || '[]');
            } catch (e) {
                currentModalTiers = [];
            }
        } else {
            currentModalTiers = [];
        }
        
        renderModalTiers();
        
        const modalEl = document.getElementById('wholesaleTiersModal');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    function renderModalTiers() {
        const container = document.getElementById('modalTiersContainer');
        container.innerHTML = '';
        
        currentModalTiers.forEach((tier, idx) => {
            const row = document.createElement('div');
            row.className = 'row g-2 align-items-center mb-2 modal-tier-row';
            row.innerHTML = `
                <div class="col-5">
                    <input type="number" class="form-control modal-tier-qty" value="${tier.min_quantity || ''}" placeholder="Min Qty" required min="1">
                </div>
                <div class="col-5">
                    <input type="number" step="0.01" class="form-control modal-tier-price" value="${tier.price || ''}" placeholder="Price (৳)" required min="0">
                </div>
                <div class="col-2 text-end">
                    <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.modal-tier-row').remove()" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px;"><i class="fas fa-trash"></i></button>
                </div>
            `;
            container.appendChild(row);
        });
        
        if (currentModalTiers.length === 0) {
            addModalTierRow();
        }
    }

    window.addModalTierRow = function() {
        const container = document.getElementById('modalTiersContainer');
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center mb-2 modal-tier-row';
        row.innerHTML = `
            <div class="col-5">
                <input type="number" class="form-control modal-tier-qty" placeholder="Min Qty" required min="1">
            </div>
            <div class="col-5">
                <input type="number" step="0.01" class="form-control modal-tier-price" placeholder="Price (৳)" required min="0">
            </div>
            <div class="col-2 text-end">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.modal-tier-row').remove()" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px;"><i class="fas fa-trash"></i></button>
            </div>
        `;
        container.appendChild(row);
    }

    window.saveModalTiers = function() {
        const rows = document.querySelectorAll('.modal-tier-row');
        const tiers = [];
        rows.forEach(row => {
            const qtyVal = row.querySelector('.modal-tier-qty').value;
            const priceVal = row.querySelector('.modal-tier-price').value;
            if (qtyVal && priceVal) {
                tiers.push({
                    min_quantity: parseInt(qtyVal),
                    price: parseFloat(priceVal)
                });
            }
        });
        
        tiers.sort((a, b) => a.min_quantity - b.min_quantity);
        
        const hiddenInput = document.querySelector(`input[name="combinations[${currentModalCombinationIndex}][wholesale_tiers]"]`);
        if (hiddenInput) {
            hiddenInput.value = JSON.stringify(tiers);
        }
        
        const badge = document.getElementById(`tier-badge-${currentModalCombinationIndex}`);
        if (badge) {
            if (tiers.length > 0) {
                badge.className = 'badge bg-success mt-1';
                badge.innerText = `${tiers.length} tier(s)`;
                badge.style.display = 'inline-block';
            } else {
                badge.className = 'badge bg-secondary mt-1';
                badge.innerText = 'No tiers';
                badge.style.display = 'none';
            }
        }
        
        const modalEl = document.getElementById('wholesaleTiersModal');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
</script>

<!-- Wholesale Tiers Modal -->
<div class="modal fade" id="wholesaleTiersModal" tabindex="-1" aria-labelledby="wholesaleTiersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wholesaleTiersModalLabel">Manage Wholesale Price Tiers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Set custom wholesale prices for different minimum purchase quantities.</p>
                <div id="modalTiersContainer">
                    <!-- Dynamic Rows Go Here -->
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="addModalTierRow()"><i class="bi bi-plus-circle"></i> Add Tier</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveModalTiers()">Save Tiers</button>
            </div>
        </div>
    </div>
</div>
@endsection