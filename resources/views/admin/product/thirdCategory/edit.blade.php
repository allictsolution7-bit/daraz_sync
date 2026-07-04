@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-5 py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.third-categories.index') }}">Third Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
        <h5>Update Third Level Category</h5>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.third-categories.update', $thirdCategory->id) }}" method="POST" enctype="multipart/form-data" class="mt-1 mb-3">
            @csrf
            @method('PUT')
            <div class="container-fluid border my-2 py-3">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Basic Info</h6>
                        <hr>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name', $thirdCategory->name) }}" placeholder="Third Category name" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                   value="{{ old('slug', $thirdCategory->slug) }}">
                            <small class="form-text text-muted">Auto-generated from category name, but you can edit it manually</small>
                            @error('slug')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="sub_category_id" class="form-label">Sub Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="sub_category_id" name="sub_category_id" required>
                                <option value="">Select Sub Category</option>
                                @foreach($sub_categories as $subCategory)
                                    <option value="{{ $subCategory->id }}" {{ (old('sub_category_id', $thirdCategory->sub_category_id) == $subCategory->id) ? 'selected' : '' }}>
                                        {{ $subCategory->category->name ?? 'N/A' }} > {{ $subCategory->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $thirdCategory->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div id="imagePreview" class="mt-2">
                                @if($thirdCategory->image)
                                    <img src="{{ asset($thirdCategory->image) }}" alt="Current Category Image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                @endif
                            </div>
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="background_image" class="form-label">Background Image</label>
                            <input type="file" class="form-control" id="background_image" name="background_image" accept="image/*">
                            <div id="bannerImagePreview" class="mt-2">
                                @if($thirdCategory->background_image)
                                    <img src="{{ asset($thirdCategory->background_image) }}" alt="Current Background Image" class="img-thumbnail" style="max-width: 300px; max-height: 150px;">
                                @endif
                            </div>
                            @error('background_image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1" {{ (old('status', $thirdCategory->status) == 1 || old('status', $thirdCategory->status) == true) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('status', $thirdCategory->status) == 0 || old('status', $thirdCategory->status) == false) ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order"
                                   value="{{ old('sort_order', $thirdCategory->sort_order ?? 0) }}" min="0">
                            <small class="form-text text-muted">Lower numbers appear first</small>
                            @error('sort_order')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>SEO Info</h6>
                        <hr>
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                   value="{{ old('meta_title', $thirdCategory->meta_title ?? '') }}" maxlength="60">
                            <small class="form-text text-muted">Recommended: 50-60 characters</small>
                            @error('meta_title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3" maxlength="160">{{ old('meta_description', $thirdCategory->meta_description ?? '') }}</textarea>
                            <small class="form-text text-muted">Recommended: 150-160 characters</small>
                            @error('meta_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                   value="{{ old('meta_keywords', $thirdCategory->meta_keywords ?? '') }}" placeholder="keyword1, keyword2, keyword3">
                            <small class="form-text text-muted">Comma-separated keywords</small>
                            @error('meta_keywords')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update Third Category</button>
            <a href="{{ route('admin.third-categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Slug generation functionality
    document.addEventListener('DOMContentLoaded', function() {
        const categoryNameInput = document.getElementById('name');
        const categorySlugInput = document.getElementById('slug');
        const subCategorySelect = document.getElementById('sub_category_id');
        
        if (categoryNameInput && categorySlugInput) {
            categoryNameInput.addEventListener('input', function() {
                if (!categorySlugInput.dataset.manuallyEdited) {
                    const slug = generateSlug(this.value);
                    categorySlugInput.value = slug;
                    if (subCategorySelect && subCategorySelect.value) {
                        checkSlugAvailability(slug, subCategorySelect.value, {{ $thirdCategory->id }});
                    }
                }
            });
            
            categorySlugInput.addEventListener('input', function() {
                this.dataset.manuallyEdited = 'true';
                if (this.value.trim() !== '' && subCategorySelect && subCategorySelect.value) {
                    checkSlugAvailability(this.value, subCategorySelect.value, {{ $thirdCategory->id }});
                }
            });
            
            if (subCategorySelect) {
                subCategorySelect.addEventListener('change', function() {
                    if (categorySlugInput.value.trim() !== '') {
                        checkSlugAvailability(categorySlugInput.value, this.value, {{ $thirdCategory->id }});
                    }
                });
            }
            
            categorySlugInput.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.dataset.manuallyEdited = 'false';
                    const slug = generateSlug(categoryNameInput.value);
                    this.value = slug;
                    if (subCategorySelect && subCategorySelect.value) {
                        checkSlugAvailability(slug, subCategorySelect.value, {{ $thirdCategory->id }});
                    }
                }
            });
        }
        
        // Image preview functionality
        initializeImagePreviews();
    });
    
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
    
    // AJAX function to check slug availability
    function checkSlugAvailability(slug, subCategoryId, currentId = null) {
        if (slug.trim() === '' || !subCategoryId) return;
        
        // Remove existing feedback
        const existingFeedback = document.querySelector('.slug-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        // Show loading state
        const slugContainer = document.getElementById('slug').parentElement;
        const loadingFeedback = document.createElement('div');
        loadingFeedback.className = 'slug-feedback form-text text-muted';
        loadingFeedback.innerHTML = '<i class="bi bi-hourglass-split"></i> Checking availability...';
        slugContainer.appendChild(loadingFeedback);
        
        // Make AJAX request (slug checking will be done server-side considering currentId)
        fetch(`{{ route('admin.check-thirdcategory-slug-availability') }}?slug=${encodeURIComponent(slug)}&sub_category_id=${encodeURIComponent(subCategoryId)}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingFeedback.remove();
            
            const feedback = document.createElement('div');
            feedback.className = 'slug-feedback form-text';
            
            // For edit, we need to check server-side if slug conflicts with current record
            // This is a basic client-side check
            if (data.available || (currentId && slug === '{{ $thirdCategory->slug }}')) {
                feedback.innerHTML = '<i class="bi bi-check-circle text-success"></i> Slug is available!';
                feedback.classList.add('text-success');
                document.getElementById('slug').classList.remove('is-invalid');
                document.getElementById('slug').classList.add('is-valid');
            } else {
                feedback.innerHTML = '<i class="bi bi-exclamation-triangle text-warning"></i> Slug already exists for this subcategory. Consider adding a number or different text.';
                feedback.classList.add('text-warning');
                document.getElementById('slug').classList.remove('is-valid');
                document.getElementById('slug').classList.add('is-invalid');
            }
            
            slugContainer.appendChild(feedback);
        })
        .catch(error => {
            loadingFeedback.remove();
            console.error('Error checking slug availability:', error);
            
            const feedback = document.createElement('div');
            feedback.className = 'slug-feedback form-text text-muted';
            feedback.innerHTML = '<i class="bi bi-info-circle"></i> Could not verify slug availability.';
            slugContainer.appendChild(feedback);
        });
    }
    
    function initializeImagePreviews() {
        // Category image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        let imagePreviewImg = imagePreview.querySelector('img');
                        if (imagePreviewImg) {
                            imagePreviewImg.src = e.target.result;
                        } else {
                            const newImg = document.createElement('img');
                            newImg.src = e.target.result;
                            newImg.alt = 'Category Image Preview';
                            newImg.className = 'img-thumbnail';
                            newImg.style.maxWidth = '200px';
                            newImg.style.maxHeight = '200px';
                            imagePreview.appendChild(newImg);
                        }
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        
        // Background image preview
        const backgroundInput = document.getElementById('background_image');
        const backgroundPreview = document.getElementById('bannerImagePreview');
        
        if (backgroundInput) {
            backgroundInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        let backgroundPreviewImg = backgroundPreview.querySelector('img');
                        if (backgroundPreviewImg) {
                            backgroundPreviewImg.src = e.target.result;
                        } else {
                            const newImg = document.createElement('img');
                            newImg.src = e.target.result;
                            newImg.alt = 'Background Image Preview';
                            newImg.className = 'img-thumbnail';
                            newImg.style.maxWidth = '300px';
                            newImg.style.maxHeight = '150px';
                            backgroundPreview.appendChild(newImg);
                        }
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    }
</script>
@endsection

