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
        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.6rem 0.9rem;
            font-size: 0.925rem;
            font-family: 'Outfit', sans-serif;
            transition: all 0.2s ease;
            background-color: #ffffff;
            color: #1e293b;
        }
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
            background-color: #ffffff;
        }
        .form-control::placeholder {
            color: #94a3b8;
        }

        /* Profile Photo Upload Frames */
        .image-preview-frame {
            border-radius: 50%;
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
                <li class="breadcrumb-item"><a href="{{ route('admin.writers.index') }}">Writers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add New Writer</li>
            </ol>
        </nav>

        <div class="glass-card">
            <!-- Header Section -->
            <div class="mb-4">
                <h1 class="page-header-title">Add New Writer</h1>
                <p class="page-header-subtitle">Register store content authors, bio notes, contact emails and popularity scores.</p>
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

            <form action="{{ route('admin.writers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Left Column: Basic Details -->
                    <div class="col-lg-7">
                        <div class="form-section-card">
                            <h3 class="form-section-title">
                                <i class="fa-solid fa-circle-info text-indigo-500"></i> Personal Info
                            </h3>
                            <p class="form-section-subtitle">Specify author profile name, email channels, phone, and upload avatar photo.</p>
                            
                            <hr class="mt-0 mb-4 text-slate-200">

                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" 
                                    value="{{ old('name') }}" placeholder="e.g. Tawfiq Khan" required>
                                @error('name')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" 
                                        value="{{ old('email') }}" placeholder="e.g. tawfiq@example.com">
                                    @error('email')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" name="phone" id="phone" class="form-control" 
                                        value="{{ old('phone') }}" placeholder="e.g. +88017xxxxxxxx">
                                    @error('phone')
                                        <div class="text-error-small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="photo" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                <small class="form-text text-muted mt-1 d-block">Recommended size: 100x100 pixels (Square Avatar).</small>
                                <div id="photoPreviewWrapper" class="image-preview-frame mt-2" style="display: none;">
                                    <img id="photoPreviewImg" src="" alt="Avatar Preview" style="width: 100px; height: 100px;">
                                </div>
                                @error('photo')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Biography & Score -->
                    <div class="col-lg-5">
                        <div class="form-section-card">
                            <h3 class="form-section-title">
                                <i class="fa-solid fa-feather text-indigo-500"></i> Bio & Settings
                            </h3>
                            <p class="form-section-subtitle">Compose brief professional biography notes, contact address, and popularity weighting.</p>
                            
                            <hr class="mt-0 mb-4 text-slate-200">

                            <div class="mb-3">
                                <label for="address" class="form-label">Location Address</label>
                                <input type="text" name="address" id="address" class="form-control" 
                                    value="{{ old('address') }}" placeholder="e.g. Dhaka, Bangladesh">
                                @error('address')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Author Bio</label>
                                <textarea name="bio" id="bio" rows="5" class="form-control" 
                                    placeholder="Write a brief professional background or history..."></textarea>
                                @error('bio')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="popularity_score" class="form-label">Popularity Score</label>
                                <input type="number" name="popularity_score" id="popularity_score" class="form-control" 
                                    value="{{ old('popularity_score', 0) }}" min="0" placeholder="e.g. 95">
                                <small class="form-text text-muted mt-1 d-block">Numeric weight representing author popularity rank.</small>
                                @error('popularity_score')
                                    <div class="text-error-small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="d-flex align-items-center justify-content-between gap-3 mt-4">
                            <a href="{{ route('admin.writers.index') }}" class="btn-back-premium">
                                <i class="fa-solid fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn-submit-premium">
                                <i class="fa-solid fa-floppy-disk"></i> Save Writer
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
    const photoInput = document.getElementById('photo');
    const photoPreviewWrapper = document.getElementById('photoPreviewWrapper');
    const photoPreviewImg = document.getElementById('photoPreviewImg');

    if (photoInput) {
        photoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreviewImg.src = e.target.result;
                    photoPreviewWrapper.style.display = 'inline-block';
                }
                reader.readAsDataURL(this.files[0]);
            } else {
                photoPreviewWrapper.style.display = 'none';
            }
        });
    }
});
</script>
@endsection
