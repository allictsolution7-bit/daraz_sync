@extends('vendor.layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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

    .modern-tabs {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        padding: 8px;
        border-radius: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        display: inline-flex;
    }

    .modern-tabs .nav-link {
        border-radius: 14px;
        padding: 12px 24px;
        font-weight: 700;
        font-size: 0.9rem;
        color: #64748b;
        transition: all 0.3s ease;
        border: none;
    }

    .modern-tabs .nav-link:hover {
        color: var(--primary);
        background: rgba(99, 102, 241, 0.05);
    }

    .modern-tabs .nav-link.active {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
    }

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

    .category-list-wrapper {
        max-height: 280px;
        overflow-y: auto;
        padding: 12px 16px;
    }

    .category-list-item {
        padding: 8px 12px;
        border-radius: 10px;
        margin-bottom: 4px;
        transition: background-color 0.2s;
    }

    .category-list-item:hover {
        background-color: var(--primary-light);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <!-- Header Card -->
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-4 shadow-sm border">
        <div>
            <h4 class="mb-1 fw-bold text-dark"><i class="fas fa-edit me-2 text-primary"></i> Edit Product</h4>
            <span class="text-muted small">Update details for <strong>{{ $product->title }}</strong></span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('vendor.products.index') }}" class="btn btn-sm btn-outline-secondary fw-bold rounded-3 px-3">← Back to Products</a>
        </div>
    </div>

    <!-- Status Alert -->
    @if($product->approval_status === 'rejected')
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <h5 class="fw-bold mb-1"><i class="fas fa-times-circle me-2"></i> Product Rejected</h5>
            <p class="mb-0"><strong>Reason:</strong> {{ $product->rejection_reason }}</p>
            <small class="d-block mt-1">You can edit and resubmit this product for approval.</small>
        </div>
    @elseif($product->approval_status === 'pending')
        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-clock me-2"></i> This product is currently <strong>pending admin approval</strong>.
        </div>
    @endif

    <form method="POST" action="{{ route('vendor.products.update', $product) }}" enctype="multipart/form-data" id="productEditForm">
        @csrf
        @method('PUT')

        {{-- Laravel validation errors --}}
        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 d-flex align-items-start gap-3" style="background:linear-gradient(135deg,#fef2f2,#fee2e2);border:1.5px solid #fca5a5 !important;">
                <i class="fas fa-circle-exclamation mt-1" style="font-size:1.3rem;color:#dc2626;"></i>
                <div>
                    <strong style="color:#991b1b;">Please fix the following errors before saving:</strong>
                    <ul class="mb-0 mt-1" style="color:#991b1b;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Tabbed Workspace -->
        <div class="row">
            <div class="col-12 text-center">
                <ul class="nav nav-pills modern-tabs mb-4 justify-content-center" id="productEditTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general-pane" type="button" role="tab"><i class="fas fa-info-circle me-2"></i> General Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing-pane" type="button" role="tab"><i class="fas fa-coins me-2"></i> Pricing & Stock</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media-pane" type="button" role="tab"><i class="fas fa-images me-2"></i> Media Gallery</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="classifications-tab" data-bs-toggle="tab" data-bs-target="#classifications-pane" type="button" role="tab"><i class="fas fa-folder-open me-2"></i> Classifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo-pane" type="button" role="tab"><i class="fas fa-magnifying-glass me-2"></i> SEO</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="productEditTabsContent">
            <!-- TAB 1: GENERAL INFO -->
            <div class="tab-pane fade show active" id="general-pane" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-section mb-4">
                            <h4>Basic Information</h4>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" id="productTitle" value="{{ old('title', $product->title) }}" required>
                                <label for="productTitle">Product Name</label>
                                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="productShortDescription" class="form-label font-weight-bold">Short Description</label>
                                <textarea class="form-control" id="productShortDescription" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="productDescription" class="form-label font-weight-bold">Full Description</label>
                                <textarea class="form-control" id="productDescription" name="description" rows="8">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-section mb-4">
                            <h4>Tags</h4>
                            <div class="mb-3">
                                <input type="text" class="form-control" name="tags" value="{{ old('tags', $product->tags) }}" placeholder="e.g. fashion, summer, discount">
                                <small class="text-muted">Comma separated tags</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: PRICING & STOCK -->
            <div class="tab-pane fade" id="pricing-pane" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-section mb-4">
                            <h4>Pricing & Commission</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" step="0.01" class="form-control @error('old_price') is-invalid @enderror" name="old_price" id="oldPrice" value="{{ old('old_price', $product->old_price) }}" required>
                                        <label for="oldPrice">Regular Price (৳)</label>
                                        @error('old_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" step="0.01" class="form-control @error('offer') is-invalid @enderror" name="offer" id="offerPrice" value="{{ old('offer', $product->offer) }}">
                                        <label for="offerPrice">Sale Price (৳)</label>
                                        @error('offer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" step="0.01" class="form-control" name="product_cost" id="productCost" value="{{ old('product_cost', $product->product_cost) }}">
                                        <label for="productCost">Product Cost (৳)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" step="0.01" class="form-control" name="vendor_proposed_commission" id="proposedCommission" value="{{ old('vendor_proposed_commission', $product->vendor_proposed_commission) }}" min="{{ $commissionSettings['min'] }}" max="{{ $commissionSettings['max'] }}">
                                        <label for="proposedCommission">Proposed Commission (%)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-4">
                            <h4>Inventory Management</h4>
                            @if($product->parent_product_id)
                                <div class="alert alert-info border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center gap-2" style="background: rgba(99, 102, 241, 0.08); color: #3730a3;">
                                    <i class="fas fa-info-circle fs-5"></i>
                                    <div>
                                        <strong>Admin Stock Allocated Product:</strong> Inventory for this copied product is allocated from the Parent Admin catalog. To add or return stock, use the <strong>Parent Admin Catalog / Return</strong> action in My Products.
                                    </div>
                                </div>
                            @endif
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" name="manage_stock" id="manage_stock" value="1" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }} {{ $product->parent_product_id ? 'disabled' : '' }}>
                                <label class="form-check-label fw-bold" for="manage_stock">Enable Stock Management</label>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="quantity" id="productQty" value="{{ old('quantity', $product->quantity) }}" min="0" {{ $product->parent_product_id ? 'disabled' : '' }}>
                                        <label for="productQty">Stock Quantity</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" name="low_stock_threshold" id="lowStockThreshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" min="0" {{ $product->parent_product_id ? 'disabled' : '' }}>
                                        <label for="lowStockThreshold">Low Stock Alert Threshold</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 3: MEDIA GALLERY -->
            <div class="tab-pane fade" id="media-pane" role="tabpanel">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-section mb-4">
                            <h4>Product Images</h4>
                            @if($product->thumb_image)
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Current Featured Image:</label>
                                    <div class="p-2 border rounded-3 bg-light d-inline-block">
                                        <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="Current Featured Image" class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Replace Featured Image</label>
                                <input type="file" name="thumb_image" class="form-control" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Add Additional Gallery Images</label>
                                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-section mb-4">
                            <h4>Video Link</h4>
                            <div class="mb-3">
                                <label for="video_url" class="form-label font-weight-bold">YouTube Video URL</label>
                                <input type="url" name="video_url" id="video_url" class="form-control" value="{{ old('video_url', $product->video_url) }}" placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: CLASSIFICATIONS -->
            <div class="tab-pane fade" id="classifications-pane" role="tabpanel">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-section mb-4">
                            <h4>Categories & Brand</h4>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Category</label>
                                <select name="category_id" id="category_id" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Subcategory</label>
                                <select name="sub_category_id" class="form-select">
                                    <option value="">Select Subcategory</option>
                                    @foreach($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ old('sub_category_id', $product->sub_category_id) == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Brand</label>
                                <select name="brand_id" class="form-select">
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 5: SEO -->
            <div class="tab-pane fade" id="seo-pane" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-section mb-4">
                            <h4>SEO Settings</h4>

                            <!-- Google Preview -->
                            <div class="mb-4 p-3 rounded-3 border" style="background:#f8fafc;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span style="font-size:0.78rem; color:#888; font-weight:600; text-transform:uppercase; letter-spacing:.05em;">Search Preview</span>
                                </div>
                                <div style="font-size:1.05rem; color:#1a0dab; font-weight:600;" id="seoPreviewTitle">{{ $product->formatted_seo['meta_title'] ?? $product->title }}</div>
                                <div style="font-size:0.82rem; color:#006621; margin:2px 0;">{{ url('/product/' . $product->id . '/' . $product->slug) }}</div>
                                <div style="font-size:0.88rem; color:#4d5156;" id="seoPreviewDesc">{{ $product->formatted_seo['meta_description'] ?? Str::limit(strip_tags($product->description ?? $product->short_description ?? ''), 155) }}</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Meta Title <span class="text-muted" style="font-weight:400;">(max 60 chars)</span></label>
                                        <input type="text" class="form-control" name="seo[meta_title]" id="seoMetaTitle"
                                               value="{{ old('seo.meta_title', $product->formatted_seo['meta_title'] ?? '') }}"
                                               maxlength="60"
                                               placeholder="Leave empty to auto-generate from product title">
                                        <small class="text-muted"><span id="metaTitleCount">{{ strlen($product->formatted_seo['meta_title'] ?? '') }}</span>/60 characters</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Meta Keywords</label>
                                        <input type="text" class="form-control" name="seo[meta_keywords]" id="seoMetaKeywords"
                                               value="{{ old('seo.meta_keywords', $product->formatted_seo['meta_keywords'] ?? '') }}"
                                               placeholder="keyword1, keyword2, keyword3">
                                        <small class="text-muted">Comma-separated keywords</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Meta Description <span class="text-muted" style="font-weight:400;">(max 160 chars)</span></label>
                                        <textarea class="form-control" name="seo[meta_description]" id="seoMetaDescription"
                                                  rows="3" maxlength="160"
                                                  placeholder="Leave empty to auto-generate from product description">{{ old('seo.meta_description', $product->formatted_seo['meta_description'] ?? '') }}</textarea>
                                        <small class="text-muted"><span id="metaDescriptionCount">{{ strlen($product->formatted_seo['meta_description'] ?? '') }}</span>/160 characters</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Canonical URL</label>
                                        <input type="url" class="form-control" name="seo[canonical_url]"
                                               value="{{ old('seo.canonical_url', $product->formatted_seo['canonical_url'] ?? '') }}"
                                               placeholder="Leave empty to use default product URL">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Meta Robots</label>
                                        <select class="form-select" name="seo[meta_robots]">
                                            <option value="index,follow" {{ (old('seo.meta_robots', $product->formatted_seo['meta_robots'] ?? 'index,follow')) == 'index,follow' ? 'selected' : '' }}>Index, Follow (Default)</option>
                                            <option value="noindex,follow" {{ (old('seo.meta_robots', $product->formatted_seo['meta_robots'] ?? '')) == 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                                            <option value="index,nofollow" {{ (old('seo.meta_robots', $product->formatted_seo['meta_robots'] ?? '')) == 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                                            <option value="noindex,nofollow" {{ (old('seo.meta_robots', $product->formatted_seo['meta_robots'] ?? '')) == 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">OG Image (Social Share)</label>
                                        <input type="file" class="form-control" name="seo[og_image]" accept="image/*">
                                        <small class="text-muted">Custom image for social sharing. Leave empty to use product thumbnail.</small>
                                        @if(isset($product->formatted_seo['og_image']) && $product->formatted_seo['og_image'])
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $product->formatted_seo['og_image']) }}" class="img-thumbnail" style="max-height: 80px;" alt="Current OG Image">
                                            </div>
                                            <input type="hidden" name="seo[existing_og_image]" value="{{ $product->formatted_seo['og_image'] }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="form-section mt-4 d-flex justify-content-between align-items-center" style="background: rgba(255, 255, 255, 0.95); border: 2px solid var(--primary); box-shadow: 0 10px 30px rgba(99, 102, 241, 0.15);">
            <div>
                <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary rounded-3 px-4 me-2">Cancel</a>
                <button type="submit" class="btn btn-primary rounded-3 px-4" style="height: 42px;"><i class="fas fa-save me-1"></i> Update Product</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $('#productDescription, #productShortDescription').summernote({
        height: 250,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']]
        ]
    });

    // SEO character counters + live preview
    const seoMetaTitle = document.getElementById('seoMetaTitle');
    const seoMetaDesc = document.getElementById('seoMetaDescription');
    const seoPreviewTitle = document.getElementById('seoPreviewTitle');
    const seoPreviewDesc = document.getElementById('seoPreviewDesc');

    if (seoMetaTitle) {
        seoMetaTitle.addEventListener('input', function () {
            document.getElementById('metaTitleCount').textContent = this.value.length;
            if (seoPreviewTitle) seoPreviewTitle.textContent = this.value || seoPreviewTitle.dataset.default;
        });
        if (seoPreviewTitle) seoPreviewTitle.dataset.default = seoPreviewTitle.textContent;
    }
    if (seoMetaDesc) {
        seoMetaDesc.addEventListener('input', function () {
            document.getElementById('metaDescriptionCount').textContent = this.value.length;
            if (seoPreviewDesc) seoPreviewDesc.textContent = this.value || seoPreviewDesc.dataset.default;
        });
        if (seoPreviewDesc) seoPreviewDesc.dataset.default = seoPreviewDesc.textContent;
    }

    // ─── Cross-Tab Form Validation ───────────────────────────────────
    (function () {
        const form = document.getElementById('productEditForm');
        if (!form) return;

        // Map: field name → { tabTarget, label }
        const requiredFields = [
            { selector: 'input[name="title"]',       tab: '#general-tab',         label: 'Product Title' },
            { selector: 'input[name="old_price"]',   tab: '#pricing-tab',         label: 'Regular Price' },
            { selector: 'input[name="offer"]',       tab: '#pricing-tab',         label: 'Sale Price' },
            { selector: '#category_id',              tab: '#classifications-tab', label: 'Category' },
        ];

        function showErrorBanner(message) {
            let banner = document.getElementById('vendor-validation-banner');
            if (!banner) {
                banner = document.createElement('div');
                banner.id = 'vendor-validation-banner';
                banner.style.cssText = 'position:fixed;top:70px;left:50%;transform:translateX(-50%);z-index:9999;min-width:340px;max-width:94vw;padding:14px 22px;border-radius:14px;background:linear-gradient(135deg,#fef2f2,#fee2e2);border:1.5px solid #fca5a5;color:#991b1b;font-size:0.95rem;font-weight:600;box-shadow:0 8px 30px rgba(220,38,38,.18);display:flex;align-items:center;gap:10px;';
                banner.innerHTML = '<i class="fas fa-circle-exclamation" style="font-size:1.2rem;"></i><span id="vendor-validation-msg"></span><button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;font-size:1.1rem;color:#991b1b;padding:0 4px;">&times;</button>';
                document.body.appendChild(banner);
            }
            document.getElementById('vendor-validation-msg').textContent = message;
            // Auto-hide after 6 seconds
            clearTimeout(banner._timer);
            banner._timer = setTimeout(() => banner.remove(), 6000);
        }

        form.addEventListener('submit', function (e) {
            for (const field of requiredFields) {
                const el = form.querySelector(field.selector);
                if (!el) continue;
                const val = el.value.trim();
                if (!val) {
                    e.preventDefault();
                    // Switch to the tab containing the error
                    const tabBtn = document.querySelector(field.tab);
                    if (tabBtn) tabBtn.click();
                    // Highlight the field
                    el.style.borderColor = '#ef4444';
                    el.style.boxShadow = '0 0 0 3px rgba(239,68,68,0.2)';
                    el.addEventListener('change', function clear() {
                        el.style.borderColor = '';
                        el.style.boxShadow = '';
                        el.removeEventListener('change', clear);
                    }, { once: true });
                    // Show banner
                    showErrorBanner(field.label + ' is required. Please fill it in before saving.');
                    // Scroll field into view after short delay (tab switch)
                    setTimeout(() => el.scrollIntoView({ behavior: 'smooth', block: 'center' }), 120);
                    return;
                }
            }
        });
    })();
</script>
@endpush
