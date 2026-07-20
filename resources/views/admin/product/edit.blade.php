@extends('layouts.master')
@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
<style>
    /* ─── Premium Design System ─── */
    :root {
        --primary: #6366f1;
        --primary-hover: #4f46e5;
        --primary-light: rgba(99, 102, 241, 0.1);
        --primary-glow: rgba(99, 102, 241, 0.15);
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #0f172a;
        --border: #e2e8f0;
        --font: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    body { font-family: var(--font) !important; background: radial-gradient(circle at 10% 20%, rgba(243,244,246,1) 0%, rgba(229,231,235,1) 90%); color: #334155; }
    .container-fluid { max-width: 1440px; padding: 0.75rem 2rem; }

    /* Glassmorphic Cards */
    .form-section {
        background: rgba(255,255,255,0.75);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.6);
        padding: 28px;
        margin-bottom: 28px;
        border-radius: 24px;
        box-shadow: 0 8px 32px 0 rgba(31,38,135,0.04);
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    }
    .form-section:hover { transform: translateY(-2px); box-shadow: 0 12px 40px 0 rgba(31,38,135,0.07); border-color: rgba(255,255,255,0.8); }
    .form-section h4 { margin-top: 0; margin-bottom: 24px; font-size: 1.2rem; font-weight: 800; letter-spacing: -0.02em; color: var(--dark); display: flex; align-items: center; gap: 12px; }
    .form-section h4::before { content: ''; display: inline-block; width: 6px; height: 20px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 3px; }

    /* Modern Pill Tabs */
    .modern-tabs { background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); padding: 6px; border-radius: 18px; border: 1px solid rgba(255,255,255,0.5); box-shadow: 0 4px 20px rgba(0,0,0,0.04); display: inline-flex; flex-wrap: wrap; gap: 2px; }
    .modern-tabs .nav-link { color: #64748b !important; font-weight: 700; font-size: 0.9rem; padding: 10px 20px; border-radius: 14px; transition: all 0.25s; border: none !important; }
    .modern-tabs .nav-link:hover { background: rgba(99,102,241,0.05); color: var(--primary) !important; }
    .modern-tabs .nav-link.active { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; color: #ffffff !important; box-shadow: 0 4px 14px rgba(99,102,241,0.25); }
    .tab-content { margin-top: 2rem; }

    /* Inputs */
    .form-control, .form-select { height: auto; padding: 12px 16px; font-size: 0.925rem; font-weight: 500; border: 1.5px solid #e2e8f0; border-radius: 12px; background-color: rgba(255,255,255,0.8); color: var(--dark); transition: all 0.2s ease-in-out; }
    .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-glow); background-color: #ffffff; outline: none; }
    .form-floating { position: relative; margin-bottom: 1.5rem; }
    .form-floating > .form-control { height: 56px; padding: 20px 16px 6px 16px; }
    .form-floating > textarea.form-control { height: auto; min-height: 100px; padding: 20px 16px 6px 16px; }
    .form-floating > label { position: absolute; top: 0; left: 0; height: 100%; padding: 16px; pointer-events: none; transform-origin: 0 0; transition: all .2s ease-in-out; color: #64748b; font-size: 0.925rem; font-weight: 500; }
    .form-floating > .form-control:focus ~ label, .form-floating > .form-control:not(:placeholder-shown) ~ label { transform: scale(.8) translateY(-10px) translateX(4px); color: var(--primary); font-weight: 700; }
    .form-label { font-size: 0.875rem; font-weight: 700; color: #334155; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.03em; }

    /* Buttons */
    .btn-primary { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; border: none !important; padding: 12px 24px; border-radius: 14px; font-weight: 700; letter-spacing: -0.01em; box-shadow: 0 4px 14px rgba(99,102,241,0.35) !important; transition: all 0.25s !important; }
    .btn-primary:hover { background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%) !important; box-shadow: 0 6px 20px rgba(99,102,241,0.45) !important; transform: translateY(-1px); }
    .btn-outline-secondary { border-radius: 12px; padding: 10px 20px; font-weight: 700; border-width: 1.5px; }

    /* Sticky Action Bar */
    .sticky-action-bar {
        position: sticky;
        top: 0;
        z-index: 100;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.7);
        border-radius: 18px;
        padding: 14px 20px;
        box-shadow: 0 8px 32px rgba(31,38,135,0.08);
        margin-bottom: 24px;
    }

    /* Page Header */
    .page-header-card { background: rgba(255,255,255,0.75); backdrop-filter: blur(12px); padding: 1rem 1.5rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.5); box-shadow: 0 4px 30px rgba(0,0,0,0.03); margin-bottom: 1rem; }
    .page-header-card h4 { font-size: 1.4rem; font-weight: 800; letter-spacing: -0.03em; color: var(--dark); margin: 0; }
    .modern-breadcrumb .breadcrumb { margin-bottom: 0 !important; background: transparent !important; padding: 0 !important; }

    /* Dropzones */
    .dropzone { border: 2.5px dashed var(--primary) !important; border-radius: 18px !important; background: rgba(99,102,241,0.02) !important; min-height: 130px !important; padding: 20px !important; transition: all 0.25s ease; }
    .dropzone:hover { background: rgba(99,102,241,0.05) !important; border-color: var(--primary-hover) !important; }

    /* Product Type Cards */
    .product-type-selector { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .product-type-card { border: 2px solid #e2e8f0; border-radius: 18px; padding: 20px 14px; text-align: center; cursor: pointer; transition: all 0.25s cubic-bezier(0.4,0,0.2,1); background: rgba(255,255,255,0.6); }
    .product-type-card:hover { border-color: var(--primary); background: rgba(255,255,255,0.9); transform: translateY(-4px); }
    .product-type-card.active { border-color: var(--primary); background: linear-gradient(135deg, rgba(99,102,241,0.08) 0%, rgba(79,70,229,0.08) 100%); }
    .product-type-card i { font-size: 28px; margin-bottom: 12px; color: #64748b; display: block; }
    .product-type-card.active i { color: var(--primary); }
    .product-type-card h6 { margin: 0 0 6px 0; font-size: 0.9rem; font-weight: 800; color: var(--dark); }
    .product-type-card small { font-size: 0.75rem; color: #64748b; display: block; line-height: 1.3; }

    /* Category Multi-Select */
    .category-multiselect-container { background: rgba(255,255,255,0.8); border: 1.5px solid #e2e8f0; border-radius: 18px; overflow: hidden; }
    .category-panel-header { padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: rgba(248,250,252,0.8); }
    .category-panel-title { font-weight: 800; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.06em; color: #475569; margin: 0; }
    .category-tabs { display: flex; border-bottom: 1px solid #e2e8f0; background: #ffffff; }
    .category-tab { flex: 1; padding: 12px 16px; cursor: pointer; border: none; background: none; color: #64748b; font-size: 0.85rem; font-weight: 700; border-bottom: 3px solid transparent; transition: all 0.25s; text-align: center; }
    .category-tab:hover { color: var(--primary); }
    .category-tab.active { border-bottom-color: var(--primary); color: var(--primary); background: #ffffff; }
    .category-search-box { padding: 12px 16px; border-bottom: 1px solid #e2e8f0; }
    .category-search-box input { width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 0.875rem; background: #ffffff; }
    .category-list-wrapper { max-height: 260px; overflow-y: auto; padding: 8px 0; }
    .category-list-item { display: flex; align-items: center; padding: 8px 20px; min-height: 34px; transition: background 0.25s; }
    .category-list-item:hover { background: rgba(241,245,249,0.6); }
    .category-list-item.hidden { display: none; }
    .category-list-item label { display: flex; align-items: center; cursor: pointer; width: 100%; margin: 0; font-size: 0.9rem; user-select: none; }
    .category-list-item input[type="checkbox"] { margin-right: 12px; cursor: pointer; width: 18px; height: 18px; border-radius: 6px; accent-color: var(--primary); }
    .category-name-text { color: var(--dark); font-weight: 600; }
    .category-level-0 { padding-left: 20px; }
    .category-level-1 { padding-left: 40px; border-left: 2px solid var(--primary-light); margin-left: 24px; }
    .category-level-2 { padding-left: 60px; border-left: 2px solid var(--primary-light); margin-left: 24px; }
    .category-selected-count { padding: 12px 20px; border-top: 1px solid #e2e8f0; background: rgba(248,250,252,0.8); font-size: 0.8rem; color: #64748b; font-weight: 700; }

    /* Variation styling */
    .variation { background: rgba(248,250,252,0.7); padding: 24px; margin-bottom: 20px; border-radius: 16px; border: 1px solid #e2e8f0; }
    .option { background: #ffffff; padding: 20px; margin: 12px 0; border: 1.5px solid #e2e8f0; border-radius: 14px; }
    .btn-add-variation { background: #ffffff; border: 2px dashed #cbd5e1; border-radius: 16px; padding: 16px; width: 100%; font-weight: 700; font-size: 0.9rem; color: #475569; cursor: pointer; transition: all 0.25s ease; }
    .btn-add-variation:hover { background: var(--primary-light); border-color: var(--primary); color: var(--primary); }

    /* Combinations Table */
    #combinationsTable .table>:not(caption)>*>* { padding: 0px 3px !important; }
    #combinationsTable table { border-collapse: separate !important; border-spacing: 0 6px !important; }
    #combinationsTable thead th { background: var(--dark) !important; color: white !important; font-weight: 700; text-align: center; font-size: 0.75rem; letter-spacing: 0.06em; padding: 14px; }
    #combinationsTable tbody td { vertical-align: middle; padding: 12px; background: #ffffff; border-bottom: 1.5px solid var(--border); text-align: center; }
    #combinationsTable tbody tr:hover { background-color: #f8f9fa; }
    #combinationsTable .combination-regular-price, #combinationsTable .combination-offer-price, #combinationsTable .combination-stock, #combinationsTable .combination-description, #combinationsTable .combination-image, #combinationsTable .combination-gallery { border: 2px solid #e9ecef; border-radius: 6px; font-weight: 500; transition: all 0.2s ease; font-size: 13px; }

    /* SEO */
    .seo-preview { background: rgba(248,250,252,0.8); border: 1.5px solid #e2e8f0; border-radius: 18px; padding: 20px; margin-top: 20px; }
    .seo-title { color: #1a0dab; font-size: 1.25rem; font-weight: 600; margin-bottom: 6px; }
    .seo-url { color: #006621; font-size: 0.875rem; margin-bottom: 8px; }
    .seo-description { color: #475569; font-size: 0.9rem; line-height: 1.5; }

    /* Image previews */
    .image-preview { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 16px; }
    .image-preview-item { width: 110px; height: 110px; border-radius: 14px; overflow: hidden; position: relative; border: 2px solid #e2e8f0; }
    .image-preview-item img { width: 100%; height: 100%; object-fit: cover; }

    /* Subcategory loading */
    .subcategory-loading { opacity: 0.6; pointer-events: none; }
    @keyframes spin { 0% { transform: translateY(-50%) rotate(0deg); } 100% { transform: translateY(-50%) rotate(360deg); } }

    /* Product type badge */
    .product-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, rgba(99,102,241,0.1) 0%, rgba(79,70,229,0.1) 100%);
        border: 1.5px solid rgba(99,102,241,0.2);
        color: #4f46e5;
        padding: 10px 20px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header-card d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4>Edit Product</h4>
            <nav aria-label="breadcrumb" class="modern-breadcrumb mt-1">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.items.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Edit: {{ Str::limit($product->title, 40) }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.items.index') }}" class="btn btn-sm btn-outline-secondary">← Back to Products</a>
    </div>

    @session('success')
    <div class="alert alert-success" role="alert">{{ Session('success') }}</div>
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

    <form method="POST" action="{{ route('admin.items.update', $product->id) }}" enctype="multipart/form-data" id="productForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="old_thumb" value="{{ $product->thumb_image }}">

        <!-- Sticky Action Bar -->
        <div class="sticky-action-bar d-flex align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <span class="product-type-badge">
                    <i class="bi bi-{{ $product->product_type == 'variable' ? 'grid-3x3' : ($product->product_type == 'digital' ? 'file-earmark-arrow-down' : ($product->product_type == 'affiliate' ? 'link-45deg' : 'box')) }}"></i>
                    {{ ucfirst($product->product_type) }} Product
                </span>
                <input type="hidden" name="product_type" value="{{ $product->product_type }}">
                <select class="form-select" id="status" name="status" style="width:160px; border-radius:12px; padding: 8px 14px; font-size: 0.9rem; font-weight:700;">
                    <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>✅ Published</option>
                    <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>📝 Draft</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i> Update Product
            </button>
        </div>

        <!-- Tab Navigation -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <ul class="nav nav-pills modern-tabs justify-content-center" id="productFormTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>General Info
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing-pane" type="button" role="tab">
                            <i class="fas fa-coins me-2"></i>Pricing & Inventory
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media-pane" type="button" role="tab">
                            <i class="fas fa-images me-2"></i>Media Gallery
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="classifications-tab" data-bs-toggle="tab" data-bs-target="#classifications-pane" type="button" role="tab">
                            <i class="fas fa-folder-open me-2"></i>Classifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab">
                            <i class="fas fa-search me-2"></i>SEO Settings
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="productFormTabsContent">

            <!-- ═══ TAB 1: GENERAL INFO ═══ -->
            <div class="tab-pane fade show active" id="general-pane" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <!-- Basic Information -->
                        <div class="form-section">
                            <h4>Basic Information</h4>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="productName" placeholder="Enter product name" value="{{ old('title', $product->title) }}" required>
                                <label for="productName">Product Name</label>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" id="productSlug" placeholder="Enter product slug" value="{{ old('slug', $product->slug) }}" required>
                                <label for="productSlug">Product Slug</label>
                                <small class="form-text text-muted">Auto-generated from product name, but you can edit it manually</small>
                                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" name="sku" id="productSku" placeholder="Enter SKU" value="{{ old('sku', $product->sku) }}">
                                <label for="productSku">SKU (Stock Keeping Unit)</label>
                                <small class="form-text text-muted">Unique identifier for inventory tracking.</small>
                                @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="productShortDescription" class="form-label">Short Description</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" id="productShortDescription" name="short_description" rows="3" placeholder="Enter short description">{{ old('short_description', $product->short_description) }}</textarea>
                                @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="productDescription" class="form-label">Detailed Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="productDescription" name="description" rows="8" placeholder="Enter detailed description">{{ old('description', $product->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Brand -->
                        <div class="form-section mb-4">
                            <h4>Brand</h4>
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select class="form-select @error('brand_id') is-invalid @enderror" id="brand_id" name="brand_id">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                @error('brand_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="form-section mb-4">
                            <h4>Tags</h4>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" placeholder="Enter tags separated by commas" value="{{ old('tags', $product->tags) }}">
                            <small class="text-muted">Separate tags with commas</small>
                            @error('tags')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Book Details -->
                        @php $writers = \App\Models\Writer::select('id', 'name')->get(); $publishers = \App\Models\Publisher::select('id', 'name')->get(); @endphp
                        <div class="form-section">
                            <h4>Book Details</h4>
                            <div class="mb-3">
                                <label class="form-label">Edition</label>
                                <input type="text" class="form-control" name="edition" placeholder="Enter edition" value="{{ $product?->book?->edition }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">ISBN</label>
                                <input type="text" class="form-control" name="isbn" placeholder="Enter ISBN" value="{{ $product?->book?->isbn }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Language</label>
                                <select class="form-select" name="language">
                                    <option value="">Select Language</option>
                                    <option value="English" {{ $product?->book?->language == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Bangla" {{ $product?->book?->language == 'Bangla' ? 'selected' : '' }}>Bangla</option>
                                    <option value="Hindi" {{ $product?->book?->language == 'Hindi' ? 'selected' : '' }}>Hindi</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Total Pages</label>
                                <input type="number" class="form-control" name="pages" placeholder="Enter total pages" value="{{ $product?->book?->pages }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Cover Type</label>
                                <select class="form-select" name="cover">
                                    <option value="">Select Cover</option>
                                    <option value="Hardcover" {{ $product?->book?->cover == 'Hardcover' ? 'selected' : '' }}>Hardcover</option>
                                    <option value="Paperback" {{ $product?->book?->cover == 'Paperback' ? 'selected' : '' }}>Paperback</option>
                                    <option value="Ebook" {{ $product?->book?->cover == 'Ebook' ? 'selected' : '' }}>Ebook</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Country</label>
                                <select class="form-select" name="country">
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
                            <div class="mb-3">
                                <label class="form-label">Writers</label>
                                <select class="form-select @error('writers') is-invalid @enderror" id="productWriters" name="writers[]" multiple>
                                    @foreach ($writers as $writer)
                                    <option value="{{ $writer->id }}" {{ in_array($writer->id, $product?->book?->writers?->pluck('id')?->toArray() ?? []) ? 'selected' : '' }}>{{ $writer->name }}</option>
                                    @endforeach
                                </select>
                                @error('writers')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Publisher</label>
                                <select class="form-select @error('publisher') is-invalid @enderror" id="publisher" name="publisher">
                                    @foreach ($publishers as $pub)
                                    <option value="{{ $pub->id }}" {{ $product?->book?->publisher_id == $pub->id ? 'selected' : '' }}>{{ $pub->name }}</option>
                                    @endforeach
                                </select>
                                @error('publisher')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Upload Sample File</label>
                                <input type="file" class="form-control" name="sample_path" id="sample_path">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ TAB 2: PRICING & INVENTORY ═══ -->
            <div class="tab-pane fade" id="pricing-pane" role="tabpanel">
                <div class="row">
                    <div class="col-12">
                        <div class="form-section">
                            <h4>Pricing & Inventory</h4>

                            <!-- Type-specific fields -->
                            @if ($product->product_type == 'digital')
                            <div class="mb-4">
                                <label class="form-label">Digital File</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="digitalFile" name="digital_file">
                                    <span class="input-group-text">Current: {{ basename($product->digital_file) }}</span>
                                </div>
                                <input type="hidden" name="old_digital_file" value="{{ $product->digital_file }}">
                            </div>
                            <div class="form-floating mb-4">
                                <input type="number" class="form-control" id="downloadLimit" name="download_limit" value="{{ old('download_limit', $product->download_limit) }}" placeholder="Download Limit">
                                <label for="downloadLimit">Download Limit (leave empty for unlimited)</label>
                            </div>
                            @elseif($product->product_type == 'affiliate')
                            <div class="form-floating mb-4">
                                <input type="url" class="form-control" id="externalUrl" name="external_url" value="{{ old('external_url', $product->external_url) }}" placeholder="External URL">
                                <label for="externalUrl">External URL</label>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Affiliate Commission</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="affiliateCommission" name="affiliate_commission" step="0.01" value="{{ old('affiliate_commission', $product->affiliate_commission) }}">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @endif

                            <!-- Pricing Fields -->
                            <div id="pricingFields" @if(in_array($product->product_type, ['variable','affiliate'])) style="display:none;" @endif>
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Regular Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="number" class="form-control @error('old_price') is-invalid @enderror" id="oldPrice" name="old_price" step="0.01" value="{{ old('old_price', $product->old_price) }}" @if(!in_array($product->product_type, ['variable','affiliate'])) required @endif>
                                        </div>
                                        @error('old_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Sale Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">৳</span>
                                            <input type="number" class="form-control @error('offer') is-invalid @enderror" id="offer" name="offer" step="0.01" value="{{ old('offer', $product->offer) }}">
                                        </div>
                                        @error('offer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div id="additionalPricingFields" @if(in_array($product->product_type, ['variable','affiliate'])) style="display:none;" @endif>
                                    <div class="row g-3 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Product Cost</label>
                                            <div class="input-group">
                                                <span class="input-group-text">৳</span>
                                                <input type="number" class="form-control @error('product_cost') is-invalid @enderror" id="productCost" name="product_cost" step="0.01" value="{{ old('product_cost', $product->product_cost) }}">
                                            </div>
                                            <div class="form-text">What you pay for this product</div>
                                            @error('product_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Wholesale Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">৳</span>
                                                <input type="number" class="form-control @error('wholesale_price') is-invalid @enderror" id="wholesalePrice" name="wholesale_price" step="0.01" value="{{ old('wholesale_price', $product->wholesale_price) }}">
                                            </div>
                                            <div class="form-text">Price for bulk/wholesale customers</div>
                                            @error('wholesale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inventory -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}">
                                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Weight (KG)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $product->weight ?? 0.5) }}" step="0.001" min="0.001">
                                    <small class="form-text text-muted">Weight for courier delivery (minimum 0.5 KG)</small>
                                    @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Variations Section (only for variable products) -->
                        @if ($product->product_type == 'variable')
                        <div class="form-section" id="variationsSection">
                            <h4>Product Variations</h4>
                            <div class="alert alert-primary mb-4">
                                <strong><i class="bi bi-info-circle me-1"></i>How it works:</strong> Add variation groups (like Size, Color) and their options. The system will automatically generate all possible combinations and let you set individual prices and stock for each combination.
                                <br><small class="text-muted mt-1"><strong>Note:</strong> Even single variations (e.g., Size-only) will create individual combinations for each option.</small>
                            </div>

                            <div id="variations">
                                @foreach ($variations as $index => $variation)
                                <div class="variation">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0 fw-bold">Variation Group</h5>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariation(this)">✕ Remove</button>
                                    </div>
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" name="variations[{{ $index }}][name]" value="{{ $variation->name }}" placeholder="Enter variation name" required>
                                        <label>Variation Name (e.g. Size, Color)</label>
                                        <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $variation->id }}">
                                    </div>
                                    <div class="options">
                                        <h6 class="fw-bold mb-3">Options</h6>
                                        @foreach ($variation->options as $optionIndex => $option)
                                        <div class="option">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control variation-option-input" name="variations[{{ $index }}][options][{{ $optionIndex }}][name]" value="{{ $option->name }}" placeholder="Enter option name" required>
                                                <label>Option Name (e.g. Small, Red)</label>
                                                <input type="hidden" name="variations[{{ $index }}][options][{{ $optionIndex }}][id]" value="{{ $option->id }}">
                                            </div>
                                            <div class="alert alert-info p-2 mb-0">
                                                <small><i class="bi bi-info-circle"></i> <strong>Note:</strong> Only option names are needed here. Pricing, stock, descriptions, and images are managed in the <strong>Combinations table</strong> below.</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addOption(this)"><i class="bi bi-plus"></i> Add Option</button>
                                </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn-add-variation" onclick="addVariation()">
                                <i class="bi bi-plus-circle"></i> Add Variation Group
                            </button>

                            <!-- Combinations Preview -->
                            <div id="combinationsPreview" class="mt-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 fw-bold"><i class="bi bi-grid-3x3-gap me-2"></i>Variation Combinations</h5>
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
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="button" class="btn btn-success btn-sm" onclick="addSingleCombination()"><i class="bi bi-check-circle"></i> Create This Combination</button>
                                        <button type="button" class="btn btn-info btn-sm" onclick="generateAllCombinations()"><i class="bi bi-grid-3x3-gap"></i> Generate All Combinations</button>
                                        <button type="button" class="btn btn-secondary btn-sm" onclick="refreshCombinationBuilder()"><i class="bi bi-arrow-clockwise"></i> Refresh Options</button>
                                    </div>
                                </div>

                                <p class="text-muted mb-3"><i class="bi bi-info-circle"></i> Set individual prices and stock quantities for each combination.</p>

                                <div id="combinationsTable">
                                    @if($combinations->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>#</th>
                                                    @foreach ($variations as $variation)<th>{{ $variation->name }}</th>@endforeach
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
                                                    <td><input type="number" step="0.01" min="0" class="form-control combination-regular-price" name="combinations[{{ $index }}][regular_price]" value="{{ $combination->regular_price }}" placeholder="0.00" style="width: 90px;"></td>
                                                    <td><input type="number" step="0.01" min="0" class="form-control combination-offer-price" name="combinations[{{ $index }}][offer_price]" value="{{ $combination->offer_price }}" placeholder="Optional" style="width: 90px;"></td>
                                                    <td><input type="number" step="0.01" min="0" class="form-control combination-product-cost" name="combinations[{{ $index }}][product_cost]" value="{{ $combination->product_cost }}" placeholder="Cost" style="width: 90px;"></td>
                                                    <td><input type="number" step="0.01" min="0" class="form-control combination-wholesale-price" name="combinations[{{ $index }}][wholesale_price]" value="{{ $combination->wholesale_price }}" placeholder="Wholesale" style="width: 90px;"></td>
                                                    <td><input type="number" min="0" class="form-control combination-stock" name="combinations[{{ $index }}][stock_quantity]" value="{{ $combination->stock_quantity }}" placeholder="0" style="width: 70px;"></td>
                                                    <td><textarea class="form-control combination-description" name="combinations[{{ $index }}][short_description]" rows="2" placeholder="Brief description..." style="width: 200px; resize: vertical;">{{ $combination->short_description }}</textarea></td>
                                                    <td>
                                                        <div class="mb-2">
                                                            <input type="file" class="form-control combination-image" name="combinations[{{ $index }}][featured_image]" accept="image/*" style="width: 150px; font-size: 11px;">
                                                            <small class="text-muted d-block">Featured Image</small>
                                                            @if($combination->featured_image)
                                                            <div class="mt-1"><img src="{{ asset('storage/' . $combination->featured_image) }}" class="img-thumbnail" style="height: 40px;">
                                                            <input type="hidden" name="combinations[{{ $index }}][old_featured_image]" value="{{ $combination->featured_image }}"></div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <input type="file" class="form-control combination-gallery" name="combinations[{{ $index }}][gallery_images][]" accept="image/*" multiple style="width: 150px; font-size: 11px;">
                                                            <small class="text-muted d-block">Gallery Images</small>
                                                            @if($combination->gallery_images)
                                                            <div class="d-flex flex-wrap gap-1 mt-1">
                                                                @php $combinationImages = is_array($combination->gallery_images) ? $combination->gallery_images : []; @endphp
                                                                @foreach ($combinationImages as $img)<img src="{{ asset('storage/' . $img) }}" class="img-thumbnail" style="height: 30px;">@endforeach
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

                        <!-- Others -->
                        <div class="form-section">
                            <h4>Additional Settings</h4>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="is_featured" id="isFeatured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} style="margin-top:7px;">
                                <label class="form-check-label" for="isFeatured">Set as Featured Product</label>
                                <small class="d-block text-muted">Check this to mark the product as featured</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Product Video URL (YouTube)</label>
                                <input type="text" name="video_url" class="form-control" value="{{ old('video_url', $product->video_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=xxxxxx">
                                <small class="form-text text-muted">Leave blank to use the global video URL.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ TAB 3: MEDIA GALLERY ═══ -->
            <div class="tab-pane fade" id="media-pane" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="form-section">
                            <h4>Thumbnail Image</h4>
                            <input type="file" class="form-control @error('thumb_image') is-invalid @enderror" id="thumbImage" name="thumb_image" accept="image/*">
                            @error('thumb_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if ($product->thumb_image)
                            <div class="mt-4">
                                <p class="form-label mb-2">Current Thumbnail</p>
                                <div class="image-preview">
                                    <div class="image-preview-item">
                                        <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="Thumbnail">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-section">
                            <h4>Gallery Images</h4>
                            <input type="file" class="form-control @error('images') is-invalid @enderror" id="productImages" name="images[]" multiple accept="image/*">
                            @error('images')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-2">Uploading new images will add to existing gallery.</small>
                            @if ($product->images)
                            <div class="mt-4">
                                <p class="form-label mb-2">Current Gallery</p>
                                @php $productImages = is_array($product->images) ? $product->images : []; @endphp
                                <div class="image-preview">
                                    @foreach ($productImages as $image)
                                    <div class="image-preview-item">
                                        <img src="{{ asset('storage/' . $image) }}" alt="Gallery Image">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ TAB 4: CLASSIFICATIONS ═══ -->
            <div class="tab-pane fade" id="classifications-pane" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="form-section">
                            <h4>Primary Category</h4>
                            <div class="mb-3">
                                <label class="form-label">Primary Category <small class="text-muted">(Optional – for backward compatibility)</small></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                    <option value="">Select Category (Optional)</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Primary Sub Category <small class="text-muted">(Optional)</small></label>
                                <select class="form-select @error('sub_category_id') is-invalid @enderror" id="sub_category_id" name="sub_category_id">
                                    <option value="">Select Sub Category (Optional)</option>
                                    @foreach ($sub_categories as $sub_category)
                                    <option value="{{ $sub_category->id }}" {{ old('sub_category_id', $product->sub_category_id) == $sub_category->id ? 'selected' : '' }}>{{ $sub_category->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted"><i class="bi bi-info-circle"></i> Subcategories will be loaded automatically when you select a primary category</small>
                                @error('sub_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-section">
                            <h4>Product Categories</h4>
                            <div class="alert alert-info mb-3" role="alert">
                                <i class="bi bi-info-circle"></i> <strong>Note:</strong> Use the multi-select below to assign multiple categories, subcategories, and third-level categories to this product.
                            </div>
                            <div class="category-multiselect-container">
                                <div class="category-panel-header">
                                    <h3 class="category-panel-title">Product categories</h3>
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
                                    $oldAdditionalCategories = old('additional_categories', $product->additionalCategories->pluck('id')->toArray());
                                    $oldAdditionalSubcategories = old('additional_subcategories', $product->additionalSubCategories->pluck('id')->toArray());
                                    $oldThirdCategories = old('third_categories', $product->thirdCategories->pluck('id')->toArray());
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
                                <div class="category-selected-count"><span id="selectedCount">0</span> selected</div>
                            </div>
                            <small class="form-text text-muted mt-2"><i class="bi bi-info-circle"></i> Select categories. The product will appear in all selected categories.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ═══ TAB 5: SEO SETTINGS ═══ -->
            <div class="tab-pane fade" id="seo-pane" role="tabpanel">
                <div class="form-section">
                    <h4><i class="bi bi-search me-2"></i>SEO Settings</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="seo[meta_title]" id="seoMetaTitle" placeholder="Enter SEO title" value="{{ $product->formatted_seo['meta_title'] ?? '' }}" maxlength="60">
                                <label for="seoMetaTitle">Meta Title</label>
                                <small class="form-text text-muted"><span id="metaTitleCount">{{ strlen($product->formatted_seo['meta_title'] ?? '') }}</span>/60 characters.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <textarea class="form-control" name="seo[meta_description]" id="seoMetaDescription" placeholder="Enter SEO description" style="height: 100px;" maxlength="160">{{ $product->formatted_seo['meta_description'] ?? '' }}</textarea>
                                <label for="seoMetaDescription">Meta Description</label>
                                <small class="form-text text-muted"><span id="metaDescriptionCount">{{ strlen($product->formatted_seo['meta_description'] ?? '') }}</span>/160 characters.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="seo[meta_keywords]" id="seoMetaKeywords" placeholder="Enter SEO keywords" value="{{ $product->formatted_seo['meta_keywords'] ?? '' }}">
                                <label for="seoMetaKeywords">Meta Keywords</label>
                                <small class="form-text text-muted">Comma-separated keywords.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="url" class="form-control" name="seo[canonical_url]" id="seoCanonicalUrl" placeholder="Enter canonical URL" value="{{ $product->formatted_seo['canonical_url'] ?? '' }}">
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
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Open Graph Image</label>
                                <input type="file" class="form-control" name="seo[og_image]" id="seoOgImage" accept="image/*">
                                <small class="form-text text-muted">Custom image for social media sharing. Leave empty to use product featured image.</small>
                                <div id="ogImagePreview" class="mt-2" style="max-width: 200px;">
                                    @if(isset($product->formatted_seo['og_image']) && $product->formatted_seo['og_image'])
                                    <img src="{{ asset('storage/' . $product->formatted_seo['og_image']) }}" class="img-fluid rounded" alt="Current OG Image">
                                    @endif
                                </div>
                                <input type="hidden" name="seo[existing_og_image]" value="{{ $product->formatted_seo['og_image'] ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Schema Markup (JSON-LD)</label>
                                <textarea class="form-control" name="seo[schema_markup]" id="seoSchemaMarkup" rows="6">{{ $product->formatted_seo['schema_markup'] ?? '' }}</textarea>
                                <small class="form-text text-muted">Custom JSON-LD schema markup. Leave empty to auto-generate basic product schema.</small>
                            </div>
                        </div>
                        <div class="col-md-12">
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

        </div><!-- end tab-content -->
    </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        initializeSlugGeneration();
        initializeSubcategoryLoading();

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

        const productType = '{{ $product->product_type }}';
        if (productType === 'variable') {
            setTimeout(() => refreshCombinationBuilder(false), 300);
        }

        initializeSeoSection();

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
        const metaTitleInput = document.getElementById('seoMetaTitle');
        const metaDescriptionInput = document.getElementById('seoMetaDescription');
        const metaKeywordsInput = document.getElementById('seoMetaKeywords');
        const canonicalUrlInput = document.getElementById('seoCanonicalUrl');
        const metaRobotsSelect = document.getElementById('seoMetaRobots');
        const schemaMarkupInput = document.getElementById('seoSchemaMarkup');

        if (metaTitleInput) metaTitleInput.addEventListener('input', function() { updateCharacterCount(this, 'metaTitleCount', 60); updateSeoPreview(); });
        if (metaDescriptionInput) metaDescriptionInput.addEventListener('input', function() { updateCharacterCount(this, 'metaDescriptionCount', 160); updateSeoPreview(); });
        if (metaKeywordsInput) metaKeywordsInput.addEventListener('input', updateSeoPreview);
        if (canonicalUrlInput) canonicalUrlInput.addEventListener('input', updateSeoPreview);
        if (metaRobotsSelect) metaRobotsSelect.addEventListener('change', updateSeoPreview);
        if (schemaMarkupInput) schemaMarkupInput.addEventListener('input', updateSeoPreview);
    }

    function updateCharacterCount(input, counterId, maxLength) {
        const counter = document.getElementById(counterId);
        if (counter) {
            const currentLength = input.value.length;
            counter.textContent = currentLength;
            counter.classList.toggle('text-danger', currentLength > maxLength);
            counter.classList.toggle('text-success', currentLength <= maxLength);
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
        seoPreviewContent += `<p class="seo-title"><strong>Meta Title:</strong> ${metaTitleInput?.value || 'Auto-generated from product name'}</p>`;
        seoPreviewContent += `<p class="seo-description"><strong>Meta Description:</strong> ${metaDescriptionInput?.value || 'Auto-generated from product description'}</p>`;
        seoPreviewContent += `<p class="seo-description"><strong>Meta Keywords:</strong> ${metaKeywordsInput?.value || 'Auto-generated from product tags and category'}</p>`;
        seoPreviewContent += `<p class="seo-url"><strong>Canonical URL:</strong> ${canonicalUrlInput?.value || 'Default product URL'}</p>`;
        seoPreviewContent += `<p class="seo-description"><strong>Meta Robots:</strong> ${metaRobotsSelect?.value || 'Default (Index, Follow)'}</p>`;
        seoPreviewContent += `<p class="seo-description"><strong>OG Image:</strong> ${(ogImageInput?.files?.[0]) ? 'Custom image selected' : 'Product featured image'}</p>`;
        seoPreviewContent += `<p class="seo-description"><strong>Schema Markup:</strong> ${schemaMarkupInput?.value ? 'Custom JSON-LD' : 'Auto-generated basic product schema'}</p>`;

        const seoPreview = document.querySelector('.seo-preview');
        if (seoPreview) seoPreview.innerHTML = seoPreviewContent;
    }

    // Variation functions
    function addVariation() {
        const variationsDiv = document.getElementById('variations');
        const variationCount = variationsDiv.children.length;
        const uniqueIndex = Date.now() + Math.random().toString(36).substr(2, 9);
        const variationDiv = document.createElement('div');
        variationDiv.classList.add('variation');
        variationDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-bold">Variation Group</h5>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeVariation(this)">✕ Remove</button>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="variations[${variationCount}][name]" placeholder="Enter variation name">
                <label>Variation Name (e.g. Size, Color)</label>
            </div>
            <div class="options">
                <h6 class="fw-bold mb-3">Options</h6>
                <div class="option">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control variation-option-input" name="variations[${variationCount}][options][${uniqueIndex}][name]" placeholder="Enter option name" required>
                        <label>Option Name (e.g. Small, Red)</label>
                    </div>
                    <div class="alert alert-info p-2 mb-0">
                        <small><i class="bi bi-info-circle"></i> <strong>Note:</strong> Only option names are needed here. Pricing, stock, descriptions, and images are managed in the <strong>Combinations table</strong> below.</small>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addOption(this)"><i class="bi bi-plus"></i> Add Option</button>`;
        variationsDiv.appendChild(variationDiv);
        setTimeout(() => refreshCombinationBuilder(false), 100);
    }

    function removeVariation(button) {
        if (confirm('Are you sure you want to remove this variation group? This will not delete existing combinations.')) {
            button.closest('.variation').remove();
            setTimeout(() => refreshCombinationBuilder(false), 100);
        }
    }

    function addOption(button) {
        const optionsDiv = button.previousElementSibling;
        const variationDiv = button.closest('.variation');
        const variationIndex = Array.from(document.querySelectorAll('.variation')).indexOf(variationDiv);
        const uniqueIndex = Date.now() + Math.random().toString(36).substr(2, 9);
        const optionCount = optionsDiv.querySelectorAll('.option').length;
        const optionDiv = document.createElement('div');
        optionDiv.classList.add('option', 'mt-3');
        optionDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Option ${optionCount + 1}</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeOption(this)">✕</button>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control variation-option-input" name="variations[${variationIndex}][options][${uniqueIndex}][name]" placeholder="Enter option name" required>
                <label>Option Name (e.g. Small, Red)</label>
            </div>
            <div class="alert alert-info p-2">
                <small><i class="bi bi-info-circle"></i> After adding new options, click <strong>"Refresh Options"</strong> button to update the combination builder.</small>
            </div>`;
        optionsDiv.appendChild(optionDiv);
        setTimeout(() => refreshCombinationBuilder(false), 100);
    }

    function removeOption(button) {
        if (confirm('Are you sure you want to remove this option?')) {
            button.closest('.option').remove();
            setTimeout(() => refreshCombinationBuilder(false), 100);
        }
    }

    function updateCombinationsPreview() {
        console.log('updateCombinationsPreview called but disabled to prevent data loss');
    }

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
            optionInputs.forEach(input => { const opt = input.value.trim(); if (opt) options.push(opt); });
            if (options.length > 0) {
                hasValidOptions = true;
                html += `<div class="col-md-4"><label class="form-label fw-bold">${variationName}:</label><select class="form-select form-select-sm combination-select" data-variation="${variationName}"><option value="">-- Select ${variationName} --</option>${options.map(opt => `<option value="${opt}">${opt}</option>`).join('')}</select></div>`;
            }
        });
        if (!hasValidOptions) html = '<div class="col-12"><p class="text-muted">Add variation groups and options first to create combinations.</p></div>';
        builderDiv.innerHTML = html;
    }

    function addSingleCombination() {
        const selects = document.querySelectorAll('.combination-select');
        const selectedValues = {};
        const combinationKey = [];
        let hasError = false;
        selects.forEach(select => {
            const variation = select.dataset.variation;
            const value = select.value;
            if (!value) { alert(`Please select an option for ${variation}`); hasError = true; return; }
            selectedValues[variation] = value;
            combinationKey.push(value);
        });
        if (hasError) return;
        const keyString = combinationKey.join('_');
        const existingKeys = document.querySelectorAll('input[name*="[key]"]');
        for (let input of existingKeys) { if (input.value === keyString) { alert('This combination already exists!'); return; } }
        addCombinationToTable(selectedValues, keyString);
        selects.forEach(s => s.value = '');
        alert('Combination added successfully!');
    }

    function generateAllCombinations() {
        if (!confirm('This will generate ALL possible combinations from current variations. Continue?')) return;
        const variations = document.querySelectorAll('.variation');
        if (variations.length === 0) { alert('No variations found!'); return; }
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
                if (optionName) { options.push({ name: optionName, price: 0, stock: 100 }); hasValidOptions = true; }
            });
            if (options.length > 0) variationData.push({ name: variationName, options: options });
        });
        if (!hasValidOptions || variationData.length === 0) { alert('No valid variations found!'); return; }
        const combinations = cartesianProduct(variationData.map(v => v.options));
        const variationNames = variationData.map(v => v.name);
        const existingKeys = [];
        document.querySelectorAll('input[name*="[key]"]').forEach(input => existingKeys.push(input.value));
        let addedCount = 0;
        combinations.forEach(combination => {
            const selectedValues = {};
            const combinationKey = [];
            combination.forEach((option, index) => { selectedValues[variationNames[index]] = option.name; combinationKey.push(option.name); });
            const keyString = combinationKey.join('_');
            if (!existingKeys.includes(keyString)) { addCombinationToTable(selectedValues, keyString); addedCount++; }
        });
        alert(`Added ${addedCount} new combinations!`);
        updateCombinationsCount();
    }

    function addCombinationToTable(selectedValues, combinationKey) {
        let tbody = document.querySelector('#combinationsTable tbody');
        if (!tbody) { createCombinationsTableStructure(); tbody = document.querySelector('#combinationsTable tbody'); }
        const currentIndex = tbody.querySelectorAll('tr').length;
        const variations = document.querySelectorAll('.variation');
        const variationNames = [];
        variations.forEach(variation => { const nameInput = variation.querySelector('input[name*="[name]"]'); if (nameInput && nameInput.value.trim()) variationNames.push(nameInput.value.trim()); });
        let rowHTML = `<tr><td>${currentIndex + 1}</td>`;
        variationNames.forEach(varName => { const optionValue = selectedValues[varName] || '-'; rowHTML += `<td><span class="badge bg-primary">${optionValue}</span></td>`; });
        rowHTML += `<td><input type="number" step="0.01" min="0" class="form-control" name="combinations[${currentIndex}][regular_price]" value="0.00" placeholder="0.00" style="width: 90px;"></td>
            <td><input type="number" step="0.01" min="0" class="form-control" name="combinations[${currentIndex}][offer_price]" value="" placeholder="Optional" style="width: 90px;"></td>
            <td><input type="number" step="0.01" min="0" class="form-control" name="combinations[${currentIndex}][product_cost]" value="" placeholder="Cost" style="width: 90px;"></td>
            <td><input type="number" step="0.01" min="0" class="form-control" name="combinations[${currentIndex}][wholesale_price]" value="" placeholder="Wholesale" style="width: 90px;"></td>
            <td><input type="number" min="0" class="form-control" name="combinations[${currentIndex}][stock_quantity]" value="0" placeholder="0" style="width: 70px;"></td>
            <td><textarea class="form-control" name="combinations[${currentIndex}][short_description]" rows="2" placeholder="Brief description..." style="width: 200px; resize: vertical;"></textarea></td>
            <td>
                <div class="mb-2"><input type="file" class="form-control" name="combinations[${currentIndex}][featured_image]" accept="image/*" style="width: 150px; font-size: 11px;"><small class="text-muted d-block">Featured Image</small></div>
                <div><input type="file" class="form-control" name="combinations[${currentIndex}][gallery_images][]" accept="image/*" multiple style="width: 150px; font-size: 11px;"><small class="text-muted d-block">Gallery Images</small></div>
            </td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="deleteNewCombination(this)"><i class="bi bi-trash"></i> Delete</button></td>
            <input type="hidden" name="combinations[${currentIndex}][key]" value="${combinationKey}">
        </tr>`;
        tbody.insertAdjacentHTML('beforeend', rowHTML);
    }

    function createCombinationsTableStructure() {
        const tableDiv = document.getElementById('combinationsTable');
        const variations = document.querySelectorAll('.variation');
        const variationNames = [];
        variations.forEach(variation => { const nameInput = variation.querySelector('input[name*="[name]"]'); if (nameInput && nameInput.value.trim()) variationNames.push(nameInput.value.trim()); });
        let tableHTML = `<div class="table-responsive"><table class="table table-bordered table-striped"><thead class="table-dark"><tr><th>#</th>`;
        variationNames.forEach(name => tableHTML += `<th>${name}</th>`);
        tableHTML += `<th>Regular Price (৳)</th><th>Offer Price (৳)</th><th>Product Cost (৳)</th><th>Wholesale Price (৳)</th><th>Stock</th><th>Description</th><th>Images</th><th>Actions</th></tr></thead><tbody></tbody></table></div>`;
        tableDiv.innerHTML = tableHTML;
    }

    function deleteNewCombination(button) {
        if (confirm('Delete this combination?')) { button.closest('tr').remove(); reindexCombinations(); updateCombinationsCount(); }
    }

    function deleteExistingCombination(button, combinationId) {
        if (confirm('Delete this combination? This will be permanent when you save.')) {
            const row = button.closest('tr');
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = `combinations_to_delete[]`;
            deleteInput.value = combinationId;
            row.appendChild(deleteInput);
            row.style.display = 'none';
            updateCombinationsCount();
        }
    }

    function reindexCombinations() {
        const rows = document.querySelectorAll('#combinationsTable tbody tr');
        let visibleIndex = 0;
        rows.forEach((row, index) => {
            if (row.style.display !== 'none') {
                row.querySelector('td:first-child').textContent = visibleIndex + 1;
                const inputs = row.querySelectorAll('input, textarea');
                inputs.forEach(input => { if (input.name && input.name.includes('combinations[')) input.name = input.name.replace(/combinations\[\d+\]/, `combinations[${visibleIndex}]`); });
                visibleIndex++;
            }
        });
    }

    function updateCombinationsCount() {
        const rows = document.querySelectorAll('#combinationsTable tbody tr');
        let visibleCount = 0;
        rows.forEach(row => { if (row.style.display !== 'none') visibleCount++; });
        const countBadge = document.getElementById('combinationsCount');
        if (countBadge) countBadge.textContent = `${visibleCount} combination${visibleCount !== 1 ? 's' : ''}`;
    }

    function cartesianProduct(arrays) {
        if (arrays.length === 1) return arrays[0].map(option => [option]);
        return arrays.reduce((a, b) => a.flatMap(x => b.map(y => [...(Array.isArray(x) ? x : [x]), y])));
    }

    function generateSlug(text) {
        return text.toLowerCase().trim()
            .replace(/[^\w\s\u0980-\u09FF-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

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
                if (this.value.trim() !== '') checkSlugAvailability(this.value);
            });
            productSlugInput.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.dataset.manuallyEdited = 'false';
                    const slug = generateSlug(productNameInput.value);
                    this.value = slug;
                    checkSlugAvailability(slug);
                }
            });
            const form = productSlugInput.closest('form');
            if (form) form.addEventListener('submit', function() { productSlugInput.removeAttribute('data-manually-edited'); });
        }
    }

    let latestSlugToCheck = '';
    function checkSlugAvailability(slug) {
        latestSlugToCheck = slug;
        if (slug.trim() === '') return;
        const existingFeedbacks = document.querySelectorAll('.slug-feedback');
        existingFeedbacks.forEach(el => el.remove());
        const slugContainer = document.getElementById('productSlug').parentElement;
        const loadingFeedback = document.createElement('div');
        loadingFeedback.className = 'slug-feedback form-text text-muted';
        loadingFeedback.innerHTML = '<i class="bi bi-hourglass-split"></i> Checking availability...';
        slugContainer.appendChild(loadingFeedback);
        fetch(`/admin/product-slug-availability?slug=${encodeURIComponent(slug)}`, { method: 'GET', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(response => response.json())
            .then(data => {
                if (slug !== latestSlugToCheck) return;
                loadingFeedback.remove();
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
            .catch(error => { loadingFeedback.remove(); });
    }

    function initializeSubcategoryLoading() {
        const productCategorySelect = document.getElementById('category_id');
        const productSubCategorySelect = document.getElementById('sub_category_id');
        if (productCategorySelect && productSubCategorySelect) {
            productCategorySelect.addEventListener('change', function() {
                productSubCategorySelect.value = '';
                if (this.value) loadSubcategories(this.value);
                else clearSubcategories();
            });
            const preSelectedCategoryId = productCategorySelect.value;
            if (preSelectedCategoryId) loadSubcategories(preSelectedCategoryId);
        }
    }

    function loadSubcategories(categoryId) {
        const subCategorySelect = document.getElementById('sub_category_id');
        if (!categoryId || !subCategorySelect) return;
        subCategorySelect.innerHTML = '<option value="">Loading subcategories...</option>';
        subCategorySelect.disabled = true;
        subCategorySelect.classList.add('subcategory-loading');
        fetch(`/admin/get-product-subcategories/${categoryId}`, { method: 'GET', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(response => { if (!response.ok) throw new Error('Network response was not ok'); return response.json(); })
            .then(data => {
                subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
                if (data && data.length > 0) {
                    data.forEach(subcategory => {
                        const option = document.createElement('option');
                        option.value = subcategory.id;
                        option.textContent = subcategory.name;
                        const currentProductSubCategoryId = '{{ $product->sub_category_id }}';
                        if (currentProductSubCategoryId && currentProductSubCategoryId == subcategory.id) option.selected = true;
                        subCategorySelect.appendChild(option);
                    });
                } else { subCategorySelect.innerHTML = '<option value="">No subcategories available</option>'; }
                subCategorySelect.disabled = false;
                subCategorySelect.classList.remove('subcategory-loading');
            })
            .catch(error => { subCategorySelect.innerHTML = '<option value="">Error loading subcategories</option>'; subCategorySelect.disabled = false; subCategorySelect.classList.remove('subcategory-loading'); });
    }

    function clearSubcategories() {
        const subCategorySelect = document.getElementById('sub_category_id');
        subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';
        subCategorySelect.disabled = false;
        subCategorySelect.classList.remove('subcategory-loading');
    }

    // WordPress/WooCommerce Style Category Multi-Select
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('categorySearch');
        const categoryItems = document.querySelectorAll('.category-list-item');
        const allCheckboxes = document.querySelectorAll('.category-checkbox');
        const selectedCountSpan = document.getElementById('selectedCount');
        const categoryTabs = document.querySelectorAll('.category-tab');

        categoryTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                categoryTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                categoryItems.forEach(item => {
                    const categoryName = item.dataset.categoryName || '';
                    item.classList.toggle('hidden', searchTerm !== '' && !categoryName.includes(searchTerm));
                });
            });
        }

        function updateSelectedCount() {
            const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
            if (selectedCountSpan) selectedCountSpan.textContent = checkedCount;
        }

        allCheckboxes.forEach(checkbox => checkbox.addEventListener('change', updateSelectedCount));
        updateSelectedCount();
    });
</script>
@endsection