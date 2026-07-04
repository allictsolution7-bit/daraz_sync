@extends('vendor.layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="fas fa-pencil"></i> Edit Product</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vendor.products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Status Alert -->
@if($product->approval_status === 'rejected')
    <div class="alert alert-danger mb-4">
        <h5><i class="fas fa-times-circle"></i> Product Rejected</h5>
        <p class="mb-0"><strong>Reason:</strong> {{ $product->rejection_reason }}</p>
        <small>You can edit and resubmit for approval.</small>
    </div>
@elseif($product->approval_status === 'pending')
    <div class="alert alert-warning mb-4">
        <i class="fas fa-clock"></i> This product is pending admin approval.
    </div>
@endif

<form action="{{ route('vendor.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Product Title *</label>
                        <input type="text" 
                               name="title" 
                               class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $product->title) }}"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" 
                                  class="form-control @error('short_description') is-invalid @enderror" 
                                  rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Description</label>
                        <textarea name="description" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="6">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tags (comma separated)</label>
                        <input type="text" 
                               name="tags" 
                               class="form-control @error('tags') is-invalid @enderror" 
                               value="{{ old('tags', $product->tags) }}">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Pricing</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Regular Price *</label>
                                <input type="number" 
                                       name="old_price" 
                                       class="form-control @error('old_price') is-invalid @enderror" 
                                       value="{{ old('old_price', $product->old_price) }}"
                                       step="0.01"
                                       required>
                                @error('old_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Sale Price *</label>
                                <input type="number" 
                                       name="offer" 
                                       class="form-control @error('offer') is-invalid @enderror" 
                                       value="{{ old('offer', $product->offer) }}"
                                       step="0.01"
                                       required>
                                @error('offer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Product Cost</label>
                                <input type="number" 
                                       name="product_cost" 
                                       class="form-control @error('product_cost') is-invalid @enderror" 
                                       value="{{ old('product_cost', $product->product_cost) }}"
                                       step="0.01">
                                @error('product_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Current Commission -->
                    @if($product->vendor_commission_rate)
                        <div class="alert alert-info mb-3">
                            <strong>Current Approved Commission:</strong> {{ $product->vendor_commission_rate }}%
                            @if($product->commission_note)
                                <br><small>{{ $product->commission_note }}</small>
                            @endif
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Proposed Commission Rate (%)</label>
                        <input type="number" 
                               name="vendor_proposed_commission" 
                               class="form-control @error('vendor_proposed_commission') is-invalid @enderror" 
                               value="{{ old('vendor_proposed_commission', $product->vendor_proposed_commission) }}"
                               step="0.01"
                               min="{{ $commissionSettings['min'] }}"
                               max="{{ $commissionSettings['max'] }}"
                               placeholder="Leave empty for default">
                        <small class="text-muted">
                            Range: {{ $commissionSettings['min'] }}% - {{ $commissionSettings['max'] }}%
                        </small>
                        @error('vendor_proposed_commission')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Images</h5>
                </div>
                <div class="card-body">
                    <!-- Current Thumbnail -->
                    @if($product->thumb_image)
                        <div class="mb-3">
                            <label class="form-label">Current Thumbnail:</label>
                            <div>
                                <img src="{{ asset('storage/' . $product->thumb_image) }}" 
                                     alt="Current Thumbnail"
                                     class="img-thumbnail"
                                     style="max-width: 200px;">
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Update Thumbnail (Optional)</label>
                        <input type="file" 
                               name="thumb_image" 
                               class="form-control @error('thumb_image') is-invalid @enderror" 
                               accept="image/*">
                        @error('thumb_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Add More Gallery Images (Optional)</label>
                        <input type="file" 
                               name="images[]" 
                               class="form-control @error('images.*') is-invalid @enderror" 
                               accept="image/*"
                               multiple>
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Video URL</label>
                        <input type="url" 
                               name="video_url" 
                               class="form-control @error('video_url') is-invalid @enderror" 
                               value="{{ old('video_url', $product->video_url) }}">
                        @error('video_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-md-4">
            <!-- Category -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Organization</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Category *</label>
                        <select name="category_id" 
                                class="form-select @error('category_id') is-invalid @enderror" 
                                required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                        <select name="sub_category_id" 
                                class="form-select @error('sub_category_id') is-invalid @enderror">
                            <option value="">Select Sub Category</option>
                            @foreach($subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" 
                                        {{ old('sub_category_id', $product->sub_category_id) == $subCategory->id ? 'selected' : '' }}>
                                    {{ $subCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" 
                                class="form-select @error('brand_id') is-invalid @enderror">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" 
                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
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

            <!-- Inventory -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="manage_stock" 
                                   class="form-check-input" 
                                   id="manage_stock"
                                   value="1"
                                   {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
                            <label class="form-check-label" for="manage_stock">
                                Manage Stock
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" 
                               name="quantity" 
                               class="form-control @error('quantity') is-invalid @enderror" 
                               value="{{ old('quantity', $product->quantity) }}"
                               min="0">
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Low Stock Threshold</label>
                        <input type="number" 
                               name="low_stock_threshold" 
                               class="form-control @error('low_stock_threshold') is-invalid @enderror" 
                               value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}"
                               min="0">
                        @error('low_stock_threshold')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle"></i> Update Product
                        </button>
                        <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times-circle"></i> Cancel
                        </a>
                    </div>
                    
                    @if($product->isApproved())
                        <div class="alert alert-warning mt-3 mb-0">
                            <small>
                                <i class="fas fa-info-circle"></i>
                                Editing approved product will set it back to pending status.
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

