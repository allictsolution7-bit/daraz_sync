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

        /* Image Upload Previews */
        .image-preview-frame {
            overflow: hidden;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: inline-block;
            margin-top: 0.75rem;
            background: #f1f5f9;
        }
        .image-preview-circle {
            border-radius: 50%;
        }
        .image-preview-rect {
            border-radius: 12px;
        }
        .image-preview-frame img {
            display: block;
            object-fit: cover;
        }

        /* Custom Toggle Switch */
        .switch-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 0.5rem;
        }

        /* Feedback Alerts */
        .text-error-small {
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.25rem;
            color: #ef4444;
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
    </style>
@endsection

@section('content')
    <div class="container categories-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Review</li>
            </ol>
        </nav>

        <div class="glass-card">
            <!-- Header Section -->
            <div class="mb-4">
                <h1 class="page-header-title">Add Customer Review</h1>
                <p class="page-header-subtitle">Set up reviewer information, product ratings, review date and active status.</p>
            </div>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 12px; font-family: 'Outfit', sans-serif;">
                    <div class="fw-semibold mb-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Please resolve the following errors:</div>
                    <ul class="mb-0" style="padding-left: 1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.reviews.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left Column: Reviewer Details -->
                    <div class="col-lg-6">
                        <div class="form-section-card">
                            <h3 class="form-section-title">
                                <i class="fa-solid fa-user text-indigo-500"></i> Reviewer Details
                            </h3>
                            <p class="form-section-subtitle">Specify reviewer profile name, rating stars, and upload reviewer avatar photo.</p>
                            
                            <hr class="mt-0 mb-4 text-slate-200">

                            <div class="mb-3">
                                <label for="reviewer_name" class="form-label">Reviewer Name <span class="text-danger">*</span></label>
                                <input type="text" name="reviewer_name" id="reviewer_name" class="form-control" 
                                    value="{{ old('reviewer_name') }}" placeholder="e.g. John Doe" required>
                                @error('reviewer_name')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="rating" class="form-label">Rating Value <span class="text-danger">*</span></label>
                                    <select name="rating" id="rating" class="form-select" required>
                                        <option value="5" {{ old('rating') == 5 ? 'selected' : '' }}>5 Stars</option>
                                        <option value="4" {{ old('rating') == 4 ? 'selected' : '' }}>4 Stars</option>
                                        <option value="3" {{ old('rating') == 3 ? 'selected' : '' }}>3 Stars</option>
                                        <option value="2" {{ old('rating') == 2 ? 'selected' : '' }}>2 Stars</option>
                                        <option value="1" {{ old('rating') == 1 ? 'selected' : '' }}>1 Star</option>
                                    </select>
                                    @error('rating')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="review_date" class="form-label">Review Date <span class="text-danger">*</span></label>
                                    <input type="date" name="review_date" id="review_date" class="form-control" 
                                        value="{{ old('review_date', date('Y-m-d')) }}" required>
                                    @error('review_date')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="reviewer_image" class="form-label">Reviewer Avatar</label>
                                <input type="file" class="form-control" id="reviewer_image" name="reviewer_image" accept="image/*">
                                <small class="form-text text-muted mt-1 d-block">Recommended size: 100x100 pixels (Square Image).</small>
                                <div id="reviewerImagePreview" class="image-preview-frame image-preview-circle mt-2" style="display: none;">
                                    <img src="" alt="Reviewer Avatar Preview" style="width: 100px; height: 100px;">
                                </div>
                                @error('reviewer_image')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Product Details -->
                    <div class="col-lg-6">
                        <div class="form-section-card">
                            <h3 class="form-section-title">
                                <i class="fa-solid fa-box text-indigo-500"></i> Product & Comments
                            </h3>
                            <p class="form-section-subtitle">Specify product name, product photo, review comment notes, and active status.</p>
                            
                            <hr class="mt-0 mb-4 text-slate-200">

                            <div class="mb-3">
                                <label for="product_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="product_name" id="product_name" class="form-control" 
                                    value="{{ old('product_name') }}" placeholder="e.g. Organic Extra Virgin Olive Oil" required>
                                @error('product_name')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="product_image" class="form-label">Product Photo</label>
                                <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*">
                                <small class="form-text text-muted mt-1 d-block">Recommended size: 200x200 pixels.</small>
                                <div id="productImagePreview" class="image-preview-frame image-preview-rect mt-2" style="display: none;">
                                    <img src="" alt="Product Photo Preview" style="width: 120px; height: 120px;">
                                </div>
                                @error('product_image')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="review_text" class="form-label">Review Comment <span class="text-danger">*</span></label>
                                <textarea name="review_text" id="review_text" rows="4" class="form-control" 
                                    placeholder="Write the customer's comment text here..." required>{{ old('review_text') }}</textarea>
                                @error('review_text')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 mt-2">
                                <label class="form-label d-block">Publish Status</label>
                                <div class="switch-wrapper form-check form-switch">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="is_active" style="font-size: 0.9rem;">Mark review as Active and publish live</label>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex align-items-center justify-content-between gap-3 mt-4">
                            <a href="{{ route('admin.reviews.index') }}" class="btn-back-premium">
                                <i class="fa-solid fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn-submit-premium">
                                <i class="fa-solid fa-floppy-disk"></i> Save Review
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
document.addEventListener('DOMContentLoaded', function() {
    // Reviewer Image Preview
    const reviewerInput = document.getElementById('reviewer_image');
    const reviewerPreview = document.getElementById('reviewerImagePreview');
    const reviewerPreviewImg = reviewerPreview.querySelector('img');

    if (reviewerInput) {
        reviewerInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    reviewerPreviewImg.src = e.target.result;
                    reviewerPreview.style.display = 'inline-block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                reviewerPreview.style.display = 'none';
            }
        });
    }

    // Product Image Preview
    const productInput = document.getElementById('product_image');
    const productPreview = document.getElementById('productImagePreview');
    const productPreviewImg = productPreview.querySelector('img');

    if (productInput) {
        productInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    productPreviewImg.src = e.target.result;
                    productPreview.style.display = 'inline-block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                productPreview.style.display = 'none';
            }
        });
    }
});
</script>
@endsection