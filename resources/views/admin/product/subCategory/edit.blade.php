@extends('layouts.master')

@section('content')
    <div class="container-fluid mt-5 py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">SubCategory</li>
            </ol>
        </nav>
        <h5>Update SubCategory</h5>

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

        <form action="{{ route('admin.sub-categories.update',$subCategory->id) }}" method="POST" enctype="multipart/form-data" class="mt-1 mb-3">
            @csrf
            @method('PUT')
            <div class="container-fluid border my-2 py-3">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Basic Info</h6>
                        <hr>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name', $subCategory->name) }}" placeholder="SubCategory name" required>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                   value="{{ old('slug', $subCategory->slug) }}" required>
                            <small class="form-text text-muted">Auto-generated from subcategory name, but you can edit it manually</small>
                            @error('slug')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}" {{ (old('category_id', $subCategory->product_category_id) == $category->id) ? 'selected' : '' }}>{{$category->name}}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description', $subCategory->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <input type="hidden" value="{{ $subCategory->image ?? '' }}" class="form-control" name="old_image">
                            <div id="imagePreview" class="mt-2">
                                @if($subCategory->image ?? false)
                                    <img src="{{asset($subCategory->image)}}" alt="Current SubCategory Image" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                @endif
                            </div>
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="background_image" class="form-label">SubCategory Banner Image</label>
                            <input type="file" class="form-control" id="background_image" name="background_image" accept="image/*">
                            <input type="hidden" value="{{ $subCategory->background_image ?? '' }}" class="form-control" name="old_background_image">
                            <div id="bannerImagePreview" class="mt-2">
                                @if($subCategory->background_image ?? false)
                                    <img src="{{asset($subCategory->background_image)}}" alt="Current Banner Image" class="img-thumbnail" style="max-width: 300px; max-height: 150px;">
                                @endif
                            </div>
                            @error('background_image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1" {{ (old('status', $subCategory->status) == 1) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ (old('status', $subCategory->status) == 0) ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
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
                                   value="{{ old('meta_title', $subCategory->meta_title ?? '') }}">
                            @error('meta_title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description">{{ old('meta_description', $subCategory->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                   value="{{ old('meta_keywords', $subCategory->meta_keywords ?? '') }}">
                            @error('meta_keywords')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update SubCategory</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    // Slug generation functionality
    document.addEventListener('DOMContentLoaded', function() {
        const subcategoryNameInput = document.getElementById('name');
        const subcategorySlugInput = document.getElementById('slug');
        
        if (subcategoryNameInput && subcategorySlugInput) {
            subcategoryNameInput.addEventListener('input', function() {
                if (!subcategorySlugInput.dataset.manuallyEdited) {
                    const slug = generateSlug(this.value);
                    subcategorySlugInput.value = slug;
                    checkSlugAvailability(slug);
                }
            });
            
            subcategorySlugInput.addEventListener('input', function() {
                this.dataset.manuallyEdited = 'true';
                if (this.value.trim() !== '') {
                    checkSlugAvailability(this.value);
                }
            });
            
            subcategorySlugInput.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.dataset.manuallyEdited = 'false';
                    const slug = generateSlug(subcategoryNameInput.value);
                    this.value = slug;
                    checkSlugAvailability(slug);
                }
            });
            
            // Ensure slug is included in form submission
            const form = subcategorySlugInput.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    subcategorySlugInput.removeAttribute('data-manually-edited');
                });
            }
        }
        
        // Image preview functionality
        initializeImagePreviews();
    });
    
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Remove special characters except spaces and hyphens
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }
    
    // AJAX function to check slug availability
    function checkSlugAvailability(slug) {
        if (slug.trim() === '') return;
        
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
        
        // Make AJAX request
        fetch(`/admin/check-subcategory-slug-availability?slug=${encodeURIComponent(slug)}`, {
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
            
            if (data.available) {
                feedback.innerHTML = '<i class="bi bi-check-circle text-success"></i> Slug is available!';
                feedback.classList.add('text-success');
                document.getElementById('slug').classList.remove('is-invalid');
                document.getElementById('slug').classList.add('is-valid');
            } else {
                feedback.innerHTML = '<i class="bi bi-exclamation-triangle text-warning"></i> Slug already exists. Consider adding a number or different text.';
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
        // Subcategory image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewImg = imagePreview.querySelector('img');
        
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // If there's an existing image, replace it
                        if (imagePreviewImg) {
                            imagePreviewImg.src = e.target.result;
                        } else {
                            // Create new image element if none exists
                            const newImg = document.createElement('img');
                            newImg.src = e.target.result;
                            newImg.alt = 'SubCategory Image Preview';
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
        
        // Banner image preview
        const bannerInput = document.getElementById('background_image');
        const bannerPreview = document.getElementById('bannerImagePreview');
        const bannerPreviewImg = bannerPreview.querySelector('img');
        
        if (bannerInput) {
            bannerInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // If there's an existing image, replace it
                        if (bannerPreviewImg) {
                            bannerPreviewImg.src = e.target.result;
                        } else {
                            // Create new image element if none exists
                            const newImg = document.createElement('img');
                            newImg.src = e.target.result;
                            newImg.alt = 'Banner Image Preview';
                            newImg.className = 'img-thumbnail';
                            newImg.style.maxWidth = '300px';
                            newImg.style.maxHeight = '150px';
                            bannerPreview.appendChild(newImg);
                        }
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    }
</script>
@endsection
