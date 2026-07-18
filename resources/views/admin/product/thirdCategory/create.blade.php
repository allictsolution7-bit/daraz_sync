@extends('layouts.master')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        /* Main Container Styling */
        .categories-container {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            border-radius: 24px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.02);
            margin-top: 1rem;
        }

        /* Sleek Glassmorphic Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            padding: 1.5rem 1.75rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.25rem;
        }

        /* Custom Breadcrumb Styles */
        .custom-breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0.75rem;
        }
        .custom-breadcrumb .breadcrumb-item {
            font-size: 0.85rem;
            font-weight: 500;
        }
        .custom-breadcrumb .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .custom-breadcrumb .breadcrumb-item a:hover {
            color: #4f46e5;
        }
        .custom-breadcrumb .breadcrumb-item.active {
            color: #1e293b;
            font-weight: 600;
        }

        /* Typography */
        .page-header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 0.15rem;
        }
        .page-header-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        /* Form Card Layouts */
        .form-section-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
            transition: transform 0.2s ease;
        }
        .form-section-title {
            font-size: 1.05rem;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-section-subtitle {
            font-size: 0.825rem;
            color: #64748b;
            margin-bottom: 1.25rem;
        }

        /* Modern Form Controls */
        .form-label {
            font-weight: 500;
            color: #334155;
            font-size: 0.9rem;
            margin-bottom: 0.4rem;
        }
        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.6rem 0.9rem;
            font-size: 0.925rem;
            font-family: 'Outfit', sans-serif;
            transition: all 0.2s ease;
            background-color: #ffffff;
            color: #1e293b;
        }
        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            background-color: #ffffff;
        }
        .form-control::placeholder {
            color: #94a3b8;
        }

        /* Image & Banner Upload Previews */
        .image-preview-frame {
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: inline-block;
            margin-top: 0.75rem;
            background: #f1f5f9;
        }
        .image-preview-frame img {
            display: block;
            object-fit: cover;
        }

        /* Form Feedback Alerts */
        .custom-error-alert {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            color: #b91c1c;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .custom-error-alert ul {
            margin: 0;
            padding-left: 1.25rem;
        }
        .text-error-small {
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.25rem;
            color: #ef4444;
        }
        .slug-feedback {
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.35rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Action Buttons */
        .btn-submit-premium {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            padding: 0.7rem 1.75rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }
        .btn-submit-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
            color: #ffffff;
        }
        .btn-submit-premium:active {
            transform: translateY(0);
        }

        .btn-back-premium {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 0.7rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .btn-back-premium:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        /* Input icons & slug checks */
        .slug-input-wrapper {
            position: relative;
        }
        .slug-input-wrapper input.is-valid {
            border-color: #22c55e !important;
            padding-right: 2.5rem;
        }
        .slug-input-wrapper input.is-invalid {
            border-color: #ef4444 !important;
            padding-right: 2.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container categories-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.third-categories.index') }}">Third Categories</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Third Category</li>
            </ol>
        </nav>

        <div class="glass-card">
            <!-- Header Section -->
            <div class="mb-4">
                <h1 class="page-header-title">Create Third Level Category</h1>
                <p class="page-header-subtitle">Set up third-tier classifications, assign sorting weights, and construct SEO tags.</p>
            </div>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="custom-error-alert">
                    <div class="fw-semibold mb-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please resolve the following errors:</div>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.third-categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left Column: Basic Info -->
                    <div class="col-lg-7">
                        <div class="form-section-card">
                            <h3 class="form-section-title">
                                <i class="fa-solid fa-circle-info text-indigo-500"></i> Basic Info
                            </h3>
                            <p class="form-section-subtitle">Specify third category name, mapping to subcategory parent, banner imagery, and ordering weights.</p>
                            
                            <hr class="mt-0 mb-4 text-slate-200">

                            <div class="mb-3">
                                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="e.g., Mechanical Keyboards, USB Type-C Cables" required>
                                @error('name')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Category Slug</label>
                                <div class="slug-input-wrapper">
                                    <input type="text" class="form-control" id="slug" name="slug"
                                        value="{{ old('slug') }}" placeholder="e.g., mechanical-keyboards">
                                </div>
                                <small class="form-text text-muted d-block mt-1">Auto-generated from name. You can customize this slug manually.</small>
                                @error('slug')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="sub_category_id" class="form-label">Parent SubCategory <span class="text-danger">*</span></label>
                                <select class="form-select" id="sub_category_id" name="sub_category_id" required>
                                    <option value="">Select Sub Category</option>
                                    @foreach($sub_categories as $subCategory)
                                        <option value="{{ $subCategory->id }}" {{ old('sub_category_id') == $subCategory->id ? 'selected' : '' }}>
                                            {{ $subCategory->category->name ?? 'N/A' }} &gt; {{ $subCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted mt-1 d-block">Select the sub-category hierarchy this classification belongs to.</small>
                                @error('sub_category_id')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                    placeholder="Enter a description for this low-tier category...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="image" class="form-label">Icon / Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <div id="imagePreview" class="image-preview-frame mt-2" style="display: none;">
                                        <img src="" alt="Icon Preview" style="width: 120px; height: 120px;">
                                    </div>
                                    @error('image')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="background_image" class="form-label">Banner Image</label>
                                    <input type="file" class="form-control" id="background_image" name="background_image" accept="image/*">
                                    <div id="bannerImagePreview" class="image-preview-frame mt-2" style="display: none;">
                                        <img src="" alt="Banner Preview" style="width: 200px; height: 100px;">
                                    </div>
                                    @error('background_image')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Publishing Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active (Publish Live)</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>Inactive (Save Draft)</option>
                                    </select>
                                    @error('status')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order"
                                        value="{{ old('sort_order', 0) }}" min="0">
                                    <small class="form-text text-muted mt-1 d-block">Lower order values appear first in sorting arrays.</small>
                                    @error('sort_order')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: SEO Info & Actions -->
                    <div class="col-lg-5">
                        <div class="form-section-card">
                            <h3 class="form-section-title">
                                <i class="fa-solid fa-magnifying-glass text-indigo-500"></i> SEO Metadata
                            </h3>
                            <p class="form-section-subtitle">Define unique search result keywords and descriptions to improve page index score.</p>
                            
                            <hr class="mt-0 mb-4 text-slate-200">

                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title"
                                    value="{{ old('meta_title') }}" placeholder="Recommended: under 60 characters" maxlength="60">
                                @error('meta_title')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="4" maxlength="160"
                                    placeholder="Brief meta description targeted for search results page..."></textarea>
                                @error('meta_description')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                    value="{{ old('meta_keywords') }}" placeholder="e.g. keyboard accessories, custom switches">
                                @error('meta_keywords')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex align-items-center justify-content-between gap-3 mt-4">
                            <a href="{{ route('admin.third-categories.index') }}" class="btn-back-premium">
                                <i class="fa-solid fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn-submit-premium">
                                <i class="fa-solid fa-floppy-disk"></i> Create Third Category
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
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
                        checkSlugAvailability(slug, subCategorySelect.value);
                    }
                }
            });
            
            categorySlugInput.addEventListener('input', function() {
                this.dataset.manuallyEdited = 'true';
                if (this.value.trim() !== '' && subCategorySelect && subCategorySelect.value) {
                    checkSlugAvailability(this.value, subCategorySelect.value);
                }
            });
            
            if (subCategorySelect) {
                subCategorySelect.addEventListener('change', function() {
                    if (categorySlugInput.value.trim() !== '') {
                        checkSlugAvailability(categorySlugInput.value, this.value);
                    }
                });
            }
            
            categorySlugInput.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.dataset.manuallyEdited = 'false';
                    const slug = generateSlug(categoryNameInput.value);
                    this.value = slug;
                    if (subCategorySelect && subCategorySelect.value) {
                        checkSlugAvailability(slug, subCategorySelect.value);
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
    function checkSlugAvailability(slug, subCategoryId) {
        if (slug.trim() === '' || !subCategoryId) return;
        
        // Remove existing feedback
        const existingFeedback = document.querySelector('.slug-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        // Show loading state
        const slugInput = document.getElementById('slug');
        const slugContainer = slugInput.parentElement;
        const loadingFeedback = document.createElement('div');
        loadingFeedback.className = 'slug-feedback text-muted';
        loadingFeedback.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Checking availability...';
        slugContainer.appendChild(loadingFeedback);
        
        // Make AJAX request
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
            feedback.className = 'slug-feedback';
            
            if (data.available) {
                feedback.innerHTML = '<i class="fa-solid fa-circle-check text-success"></i> Slug is available!';
                feedback.classList.add('text-success');
                slugInput.classList.remove('is-invalid');
                slugInput.classList.add('is-valid');
            } else {
                feedback.innerHTML = '<i class="fa-solid fa-circle-exclamation text-danger"></i> Slug already exists for this subcategory.';
                feedback.classList.add('text-danger');
                slugInput.classList.remove('is-valid');
                slugInput.classList.add('is-invalid');
            }
            
            slugContainer.appendChild(feedback);
        })
        .catch(error => {
            loadingFeedback.remove();
            console.error('Error checking slug availability:', error);
            
            const feedback = document.createElement('div');
            feedback.className = 'slug-feedback text-muted';
            feedback.innerHTML = '<i class="fa-solid fa-circle-info"></i> Could not verify slug availability.';
            slugContainer.appendChild(feedback);
        });
    }
    
    function initializeImagePreviews() {
        // Category image preview
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewImg = imagePreview.querySelector('img');
        
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreviewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                } else {
                    imagePreview.style.display = 'none';
                }
            });
        }
        
        // Background image preview
        const backgroundInput = document.getElementById('background_image');
        const backgroundPreview = document.getElementById('bannerImagePreview');
        const backgroundPreviewImg = backgroundPreview.querySelector('img');
        
        if (backgroundInput) {
            backgroundInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        backgroundPreviewImg.src = e.target.result;
                        backgroundPreview.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                } else {
                    backgroundPreview.style.display = 'none';
                }
            });
        }
    }
</script>
@endsection
