@extends('layouts.master')

@section('title', 'Create Landing Page')

@section('content')
<div class="landing-builder">
    <!-- Header Banner -->
    <div class="lp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="lp-title-icon">
                <i class="fas fa-magic"></i>
            </div>
            <div>
                <h3 class="mb-0 text-white font-weight-bold">Create Landing Page</h3>
                <p class="mb-0 text-white-50 small">Design high-converting promotional pages with custom content sections</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.landing-pages.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Error Display Section -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <h6 class="alert-heading font-weight-bold mb-1">
                <i class="fas fa-exclamation-triangle me-2"></i> 
                Please correct the following errors:
            </h6>
            <ul class="mb-0 small ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.landing-pages.store') }}" method="POST" enctype="multipart/form-data" id="landingPageForm">
        @csrf

        <div class="row g-4">
            <!-- Left Column: Core Setup & Style Settings -->
            <div class="col-lg-5 col-xl-4">
                <!-- Card 1: Page Configuration -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="fas fa-sliders text-primary"></i>
                        <h5 class="mb-0 font-weight-bold text-dark">General Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="title">Page Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title') }}" placeholder="e.g. 5 Premium Item Combo" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="slug">Page Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                id="slug" name="slug" value="{{ old('slug') }}" placeholder="e.g. premium-combo-pack" required>
                            <small class="form-text text-muted">Unique URL path for this promo page.</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="product_id">Associated Product <span class="text-danger">*</span></label>
                            <select class="form-control @error('product_id') is-invalid @enderror"
                                id="product_id" name="product_id" required>
                                <option value="">-- Choose Product --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->title }}
                                        (৳{{ number_format($product->offer ?: $product->old_price, 2) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label for="position">Display Order</label>
                                    <input type="number" class="form-control @error('position') is-invalid @enderror"
                                        id="position" name="position" value="{{ old('position', 0) }}" min="0">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-3">
                                    <label>Status</label>
                                    <div class="form-check form-switch pt-2">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                        <label class="form-check-label font-weight-bold" for="status">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Brand Styling -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="fas fa-palette text-primary"></i>
                        <h5 class="mb-0 font-weight-bold text-dark">Theme & Colors</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $globalPrimaryColor = setting('general', 'primary_color', '#F02627');
                            $globalSecondaryColor = setting('general', 'secondary_color', '#113056');
                            $globalAccentColor = setting('general', 'accent_color', '#F02627');
                        @endphp
                        
                        <div class="form-group mb-3">
                            <label for="primary_color">Primary Color</label>
                            <div class="input-group color-picker-group">
                                <span class="input-group-text p-0">
                                    <input type="color" class="form-control color-chip"
                                        id="primary_color" name="primary_color" value="{{ old('primary_color', $globalPrimaryColor) }}">
                                </span>
                                <input type="text" class="form-control color-text"
                                    id="primary_color_text" value="{{ old('primary_color', $globalPrimaryColor) }}">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="secondary_color">Secondary Color</label>
                            <div class="input-group color-picker-group">
                                <span class="input-group-text p-0">
                                    <input type="color" class="form-control color-chip"
                                        id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $globalSecondaryColor) }}">
                                </span>
                                <input type="text" class="form-control color-text"
                                    id="secondary_color_text" value="{{ old('secondary_color', $globalSecondaryColor) }}">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="accent_color">Accent Color</label>
                            <div class="input-group color-picker-group">
                                <span class="input-group-text p-0">
                                    <input type="color" class="form-control color-chip"
                                        id="accent_color" name="accent_color" value="{{ old('accent_color', $globalAccentColor) }}">
                                </span>
                                <input type="text" class="form-control color-text"
                                    id="accent_color_text" value="{{ old('accent_color', $globalAccentColor) }}">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="order_button_color">CTA Button Color</label>
                            <div class="input-group color-picker-group">
                                <span class="input-group-text p-0">
                                    <input type="color" class="form-control color-chip"
                                        id="order_button_color" name="order_button_color" value="{{ old('order_button_color', '#dc3545') }}">
                                </span>
                                <input type="text" class="form-control color-text"
                                    id="order_button_color_text" value="{{ old('order_button_color', '#dc3545') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Button & Form Text -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center gap-2">
                        <i class="fas fa-font text-primary"></i>
                        <h5 class="mb-0 font-weight-bold text-dark">Call to Action Labels</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="order_button_text">Order Button Text</label>
                            <input type="text" class="form-control" id="order_button_text" name="order_button_text"
                                value="{{ old('order_button_text', 'এখনই কিনুন') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="order_button_url">Order Button Action</label>
                            <input type="text" class="form-control" id="order_button_url" name="order_button_url"
                                value="{{ old('order_button_url', '#order-section') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="order_form_title">Checkout Form Heading</label>
                            <input type="text" class="form-control" id="order_form_title" name="order_form_title"
                                value="{{ old('order_form_title', 'অর্ডার করুন') }}">
                        </div>

                        <div class="form-group mb-3">
                            <label for="order_place_button_text">Confirm Button Text</label>
                            <input type="text" class="form-control" id="order_place_button_text" name="order_place_button_text"
                                value="{{ old('order_place_button_text', 'অর্ডার Confirm করুন') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Content Sections Builder -->
            <div class="col-lg-7 col-xl-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-layer-group text-primary"></i>
                            <h5 class="mb-0 font-weight-bold text-dark">Page Content Sections</h5>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="addSection()">
                            <i class="fas fa-plus me-1"></i> Add Section
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted small mb-4">Build your sales pitch section by section. Select section types like Hero, Video, Product Benefits, or Reviews.</p>

                        <div id="sections-container" class="d-flex flex-column gap-3">
                            <!-- Dynamic section items added via JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Submit Bar -->
                <div class="d-flex align-items-center justify-content-end gap-3 mb-5">
                    <a href="{{ route('admin.landing-pages.index') }}" class="btn btn-secondary px-4">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-5 shadow">
                        <i class="fas fa-check-circle me-1"></i> Save & Publish Landing Page
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

    <style>
        .type-benefit {
            display: none;
        }
        .landing-builder {
            background: #f1f5f9;
            min-height: calc(100vh - 60px);
            margin: -15px -15px 0 -15px;
            padding: 24px;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .landing-builder .card {
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-radius: 14px;
            background: #ffffff;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .landing-builder .lp-hero-card {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
            color: #ffffff;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.15);
            position: relative;
            overflow: hidden;
        }
        .landing-builder .lp-title-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #38bdf8;
        }
        .landing-builder .card-header {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
            color: #ffffff;
            border: none;
            padding: 16px 20px;
        }
        .landing-builder .card-header h3,
        .landing-builder .card-header h4,
        .landing-builder .card-header h5 {
            color: #ffffff !important;
            font-weight: 800;
        }
        .landing-builder .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            font-weight: 800;
            border-radius: 10px;
            padding: 12px 24px;
            transition: all 0.25s ease;
        }
        .landing-builder .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
        }
        .landing-builder .btn-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            font-weight: 700;
            border-radius: 10px;
            padding: 10px 18px;
        }
        .landing-builder .btn-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .landing-builder .form-control, .landing-builder select {
            border-radius: 10px;
            border: 1.5px solid #cbd5e1;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 500;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }
        .landing-builder .form-control:focus, .landing-builder select:focus {
            background-color: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }
        .landing-builder label {
            font-size: 12px;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .landing-builder .section-move-controls .btn {
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border-radius: 6px;
            padding: 4px 8px;
        }
        .landing-builder .color-picker-group .color-chip {
            width: 44px;
            height: 38px;
            border: none;
            padding: 0;
            background: transparent;
            cursor: pointer;
        }
        .landing-builder .color-picker-group .color-text {
            font-family: monospace;
            font-weight: 700;
        }
    </style>

@endsection

@section('styles')
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    
    <style>
        .summernote-editor {
            min-height: 200px;
        }
        
        .note-editor.note-frame {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }
        
        .note-toolbar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ced4da;
        }
        
        .note-editing-area {
            background-color: #fff;
        }
    </style>
@endsection

@section('scripts')
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        let sectionCounter = 0;

        function addSection() {
            const container = document.getElementById('sections-container');
            const sectionDiv = document.createElement('div');
            sectionDiv.className = 'card mb-3 section-item';
            sectionDiv.innerHTML = `
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Section ${sectionCounter + 1} <span class="text-white section-label ms-2">(Not set)</span></h5>
                <div class="btn-group btn-group-sm section-move-controls">
                    <button type="button" class="btn btn-outline-secondary text-white" onclick="toggleSectionBody(this)" title="Collapse/Expand">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary text-white" onclick="moveSection(this, 'up')" title="Move Up">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary text-white" onclick="moveSection(this, 'down')" title="Move Down">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Section Type</label>
                            <select class="form-control section-type" name="sections[${sectionCounter}][section_type]" required>
                                <option value="">Select type</option>
                                <option value="hero">Hero Section</option>
                                <option value="benefit">Benefit</option>
                                <option value="testimonials">Testimonials</option>
                                <option value="feature_list">Feature List</option>
                                <option value="pricing">Pricing</option>
                                <option value="countdown">Countdown</option>
                                <option value="video">Video Section</option>
                                <option value="single_image">Single Image Section</option>
                                <option value="image_carousel">Image Carousel</option>
                                <option value="call_to_action">Call to Action</option>
                                <option value="header">Header Section</option>
                            </select>
                        </div>
                        <!-- Benefit Section Title -->
                        <div class="form-group type-field type-benefit" style="display: none;">
                            <label>Benefit Section Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][benefit_title]" placeholder="Benefit section title">
                        </div>
                        
                        <!-- Testimonials Section Title -->
                        <div class="form-group type-field type-testimonials" style="display: none;">
                            <label>Testimonials Section Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][testimonials_title]" placeholder="Testimonials section title">
                        </div>
                        
                        <!-- Feature List Section Title -->
                        <div class="form-group type-field type-feature_list" style="display: none;">
                            <label>Feature List Section Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][feature_list_title]" placeholder="Feature list section title">
                        </div>
                        
                        <!-- Pricing Section Title -->
                        <div class="form-group type-field type-pricing" style="display: none;">
                            <label>Pricing Section Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][pricing_title]" placeholder="Pricing section title">
                        </div>
                        
                        <!-- Countdown Section Title -->
                        <div class="form-group type-field type-countdown" style="display: none;">
                            <label>Countdown Section Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][countdown_title]" placeholder="Countdown section title">
                        </div>

                        <!-- Hero Section Fields -->
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Main Heading</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][heading]" placeholder="Main heading">
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Sub Heading</label>
                            <textarea class="form-control" name="sections[${sectionCounter}][sub_heading]" rows="3" placeholder="Sub heading"></textarea>
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Primary Text</label>
                            <textarea class="form-control summernote-editor" name="sections[${sectionCounter}][primary_text]" rows="4" placeholder="Primary text"></textarea>
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Hero Image</label>
                            <input type="file" class="form-control" name="sections[${sectionCounter}][hero_image]" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 800x600px or larger</small>
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Hero Image Alt Text</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][hero_image_alt]" placeholder="Alt text for hero image">
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>YouTube Video URL (Optional)</label>
                            <input type="url" class="form-control" name="sections[${sectionCounter}][hero_video_url]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                            <small class="form-text text-muted">If YouTube video is provided, the hero image will not be displayed</small>
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Badge Image</label>
                            <input type="file" class="form-control" name="sections[${sectionCounter}][badge_image]" accept="image/*">
                            <small class="form-text text-muted">Upload badge image or logo (recommended: 100x40px or similar ratio)</small>
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Desktop Badge Width (px)</label>
                            <input type="number" class="form-control" name="sections[${sectionCounter}][badge_desktop_width]" value="100" min="30" max="200">
                            <small class="form-text text-muted">Width in pixels for desktop view</small>
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Mobile Badge Width (px)</label>
                            <input type="number" class="form-control" name="sections[${sectionCounter}][badge_mobile_width]" value="80" min="20" max="150">
                            <small class="form-text text-muted">Width in pixels for mobile view</small>
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Badge Text</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][badge_text]" placeholder="e.g., ১০০% প্রাকৃতিক">
                        </div>
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Badge Color</label>
                            <input type="color" class="form-control" name="sections[${sectionCounter}][badge_color]" value="#ffd54f">
                        </div>

                        <!-- Trust Indicators Section -->
                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>
                                <input type="checkbox" name="sections[${sectionCounter}][show_trust_indicators]" value="1" checked> 
                                Show Trust Indicators
                            </label>
                        </div>

                        <div class="form-group type-field type-hero" style="display: none;">
                            <label>Trust Indicators</label>
                            <div class="trust-indicators-container">
                                <div class="trust-indicator-item mb-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="sections[${sectionCounter}][trust_indicators][0][icon]" placeholder="⭐" value="⭐">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="sections[${sectionCounter}][trust_indicators][0][text1]" placeholder="4.9/5" value="4.9/5">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="sections[${sectionCounter}][trust_indicators][0][text2]" placeholder="গ্রাহক রেটিং" value="গ্রাহক রেটিং">
                                        </div>
                                    </div>
                                </div>
                                <div class="trust-indicator-item mb-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="sections[${sectionCounter}][trust_indicators][1][icon]" placeholder="👨‍👩‍👧‍👦" value="👨‍👩‍👧‍👦">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="sections[${sectionCounter}][trust_indicators][1][text1]" placeholder="১০০০+" value="১০০০+">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="sections[${sectionCounter}][trust_indicators][1][text2]" placeholder="সন্তুষ্ট গ্রাহক" value="সন্তুষ্ট গ্রাহক">
                                        </div>
                                    </div>
                                </div>
                                <div class="trust-indicator-item mb-3">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <input type="text" class="form-control" name="sections[${sectionCounter}][trust_indicators][2][icon]" placeholder="🔄" value="🔄">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="sections[${sectionCounter}][trust_indicators][2][text1]" placeholder="৭ দিন" value="৭ দিন">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="sections[${sectionCounter}][trust_indicators][2][text2]" placeholder="রিটার্ন গ্যারান্টি" value="রিটার্ন গ্যারান্টি">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small class="form-text text-muted">Add up to 3 trust indicators to build customer confidence</small>
                        </div>
                        <!-- Testimonials: only for testimonials -->
                        <div class="form-group type-field type-testimonials" style="display: none;">
                            <label>Testimonials</label>
                            <div id="testimonials-container-${sectionCounter}" class="testimonials-container">
                                <!-- Testimonials will be added here -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addTestimonial(${sectionCounter})">
                                <i class="fas fa-plus"></i> Add Testimonial
                            </button>
                        </div>
                        <!-- Only show for benefit section type -->
                        <div class="form-group type-field type-benefit" style="display: none;">
                            <label>Benefits</label>
                            <div id="benefits-container-${sectionCounter}" class="benefits-container">
                                <!-- Benefit items will be added here -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addBenefit(${sectionCounter})">
                                <i class="fas fa-plus"></i> Add Benefit
                            </button>
                        </div>
                        <!-- Only show for feature_list section type -->
                        <div class="form-group type-field type-feature_list" style="display: none;">
                            <label>Feature Items</label>
                            <div id="feature-items-container-${sectionCounter}" class="feature-items-container">
                                <!-- Feature items will be added here -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFeatureItem(${sectionCounter})">
                                <i class="fas fa-plus"></i> Add Feature Item
                            </button>
                        </div>
                        <!-- Only show for pricing section type -->
                        <div class="form-group type-field type-pricing" style="display: none;">
                            <label>Product Variants</label>
                            <div id="pricing-variants-container-${sectionCounter}" class="pricing-variants-container">
                                <!-- Pricing variants will be added here -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPricingVariant(${sectionCounter})">
                                <i class="fas fa-plus"></i> Add Product Variant
                            </button>
                        </div>

                        <div class="form-group type-field type-countdown" style="display: none;">
                            <label>Countdown Hours</label>
                            <input type="number" class="form-control" name="sections[${sectionCounter}][countdown_hours]" value="4" min="1">
                        </div>
                        <div class="form-group type-field type-countdown" style="display: none;">
                            <label>Repeat Countdown?</label>
                            <select class="form-control" name="sections[${sectionCounter}][countdown_repeat]">
                                <option value="1">Yes</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                        
                        <!-- Only show for video section type -->
                        <div class="form-group type-field type-video" style="display: none;">
                            <label>Video Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][video_title]" placeholder="Video Title">
                        </div>
                        <div class="form-group type-field type-video" style="display: none;">
                            <label>Video Description</label>
                            <textarea class="form-control" name="sections[${sectionCounter}][video_description]" rows="3" placeholder="Video description"></textarea>
                        </div>
                        <div class="form-group type-field type-video" style="display: none;">
                            <label>YouTube Video URL</label>
                            <input type="url" class="form-control" name="sections[${sectionCounter}][video_url]" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                            <small class="form-text text-muted">Paste the full YouTube URL (e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ)</small>
                        </div>
                        
                        <!-- Single Image Section Fields -->
                        <div class="form-group type-field type-single_image" style="display: none;">
                            <label>Section Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][title]" placeholder="Section Title">
                        </div>
                        <div class="form-group type-field type-single_image" style="display: none;">
                            <label>Section Description</label>
                            <textarea class="form-control" name="sections[${sectionCounter}][sub_heading]" rows="3" placeholder="Section description"></textarea>
                        </div>
                        <div class="form-group type-field type-single_image" style="display: none;">
                            <label>Image</label>
                            <input type="file" class="form-control" name="sections[${sectionCounter}][single_image]" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 800x600px or larger</small>
                        </div>
                        <div class="form-group type-field type-single_image" style="display: none;">
                            <label>Image Alt Text</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][single_image_alt]" placeholder="Alt text for image">
                        </div>

                        <!-- Image Carousel Section Fields -->
                        <div class="form-group type-field type-image_carousel" style="display: none;">
                            <label>Carousel Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][title]" placeholder="Gallery title">
                        </div>
                        <div class="form-group type-field type-image_carousel" style="display: none;">
                            <label>Carousel Description</label>
                            <textarea class="form-control" name="sections[${sectionCounter}][description]" rows="3" placeholder="Short description for this gallery"></textarea>
                        </div>
                        <div class="form-group type-field type-image_carousel" style="display: none;">
                            <label>Carousel Images</label>
                            <div id="carousel-images-container-${sectionCounter}" class="carousel-images-container"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCarouselImage(${sectionCounter})">
                                <i class="fas fa-plus"></i> Add Image
                            </button>
                            <small class="form-text text-muted">Upload one or more images to show inside the carousel.</small>
                        </div>
                        
                        <!-- Call to Action Section Fields -->
                        <div class="form-group type-field type-call_to_action" style="display: none;">
                            <label>Call to Action Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][cta_title]" placeholder="প্রয়োজনে কল করুন" value="প্রয়োজনে কল করুন">
                        </div>
                        <div class="form-group type-field type-call_to_action" style="display: none;">
                            <label>Call to Action Subtitle (Optional)</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][cta_subtitle]" placeholder="Subtitle text">
                        </div>
                        <div class="form-group type-field type-call_to_action" style="display: none;">
                            <label>Button Text</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][cta_button_text]" placeholder="কল করুন" value="কল করুন">
                        </div>
                        <div class="form-group type-field type-call_to_action" style="display: none;">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][cta_phone_number]" placeholder="01611-109447" value="01611-109447">
                        </div>
                        <div class="form-group type-field type-call_to_action" style="display: none;">
                            <label>Background Color</label>
                            <input type="color" class="form-control" name="sections[${sectionCounter}][cta_background_color]" value="#1D8758">
                        </div>
                        <div class="form-group type-field type-call_to_action" style="display: none;">
                            <label>Button Color</label>
                            <input type="color" class="form-control" name="sections[${sectionCounter}][cta_button_color]" value="#dc3545">
                        </div>
                        
                        <!-- Header Section Fields -->
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Logo</label>
                            <input type="file" class="form-control" name="sections[${sectionCounter}][header_logo]" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 200x60px or similar ratio</small>
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Logo Alt Text</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][header_logo_alt]" placeholder="Alt text for logo">
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Desktop Logo Width (px)</label>
                            <input type="number" class="form-control" name="sections[${sectionCounter}][header_desktop_logo_width]" value="200" min="50" max="500">
                            <small class="form-text text-muted">Width in pixels for desktop view</small>
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Mobile Logo Width (px)</label>
                            <input type="number" class="form-control" name="sections[${sectionCounter}][header_mobile_logo_width]" value="150" min="30" max="300">
                            <small class="form-text text-muted">Width in pixels for mobile view</small>
                        </div>

                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Logo Alignment</label>
                            <select class="form-control" name="sections[${sectionCounter}][header_alignment]">
                                <option value="flex-start">Left</option>
                                <option value="center" selected>Center</option>
                                <option value="flex-end">Right</option>
                            </select>
                        </div>
                        
                        <!-- Button 1 Fields -->
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 1 Text</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][header_button1_text]" placeholder="Call Now">
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 1 URL</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][header_button1_url]" placeholder="tel:01717171717">
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 1 Background Color</label>
                            <input type="color" class="form-control" name="sections[${sectionCounter}][header_button1_color]" value="#007bff">
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 1 Text Color</label>
                            <input type="color" class="form-control" name="sections[${sectionCounter}][header_button1_text_color]" value="#ffffff">
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 1 Active</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="sections[${sectionCounter}][header_button1_active]" value="1" checked>
                                <label class="custom-control-label">Show Button 1</label>
                            </div>
                        </div>
                        
                        <!-- Button 2 Fields -->
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 2 Text</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][header_button2_text]" placeholder="এখনই কিনুন">
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 2 URL</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][header_button2_url]" placeholder="#order-section">
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 2 Background Color</label>
                            <input type="color" class="form-control" name="sections[${sectionCounter}][header_button2_color]" value="#dc3545">
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 2 Text Color</label>
                            <input type="color" class="form-control" name="sections[${sectionCounter}][header_button2_text_color]" value="#ffffff">
                        </div>
                        <div class="form-group type-field type-header" style="display: none;">
                            <label>Button 2 Active</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="sections[${sectionCounter}][header_button2_active]" value="1" checked>
                                <label class="custom-control-label">Show Button 2</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group type-field type-hero type-benefit type-testimonials type-feature_list type-pricing type-countdown type-video type-single_image type-image_carousel type-call_to_action type-header" style="display: none;">
                            <label>Position</label>
                            <input type="number" class="form-control" name="sections[${sectionCounter}][position]" value="0" min="0">
                        </div>
                        <div class="form-group type-field type-hero type-benefit type-testimonials type-feature_list type-pricing type-countdown type-video type-single_image type-image_carousel type-call_to_action type-header" style="display: none;">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="status_${sectionCounter}" name="sections[${sectionCounter}][status]" value="1" checked>
                                <label class="custom-control-label" for="status_${sectionCounter}">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeSection(this)">
                    <i class="fas fa-trash"></i> Remove Section
                </button>
            </div>
        `;
            container.appendChild(sectionDiv);
            sectionCounter++;
            renumberSections();
        }

        function removeSection(button) {
            button.closest('.card').remove();
            renumberSections();
        }

        function toggleSectionBody(button) {
            const card = button.closest('.card');
            const body = card ? card.querySelector('.card-body') : null;
            const icon = button.querySelector('i');
            if (!body) return;

            const isHidden = body.style.display === 'none';
            body.style.display = isHidden ? '' : 'none';
            if (icon) {
                icon.classList.remove('fa-plus', 'fa-minus');
                icon.classList.add(isHidden ? 'fa-minus' : 'fa-plus');
            }
        }

        function moveSection(button, direction) {
            const section = button.closest('.section-item');
            const container = document.getElementById('sections-container');
            if (!section || !container) return;

            if (direction === 'up' && section.previousElementSibling) {
                container.insertBefore(section, section.previousElementSibling);
            } else if (direction === 'down' && section.nextElementSibling) {
                container.insertBefore(section.nextElementSibling, section);
            }

            renumberSections();
        }

        function formatSectionLabel(val) {
            if (!val) return 'Not set';
            return val.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        }

        function renumberSections() {
            const sections = document.querySelectorAll('#sections-container .section-item');
            sections.forEach((section, idx) => {
                const title = section.querySelector('.card-header h5');
                const typeLabel = section.querySelector('.section-label');
                const typeSelect = section.querySelector('.section-type');
                if (title) {
                    const labelSpan = title.querySelector('.section-label');
                    if (title.firstChild) {
                        title.firstChild.textContent = `Section ${idx + 1} `;
                    }
                    if (labelSpan) {
                        labelSpan.textContent = `(${formatSectionLabel(typeSelect ? typeSelect.value : 'Not set')})`;
                    }
                } else if (typeLabel && typeSelect) {
                    typeLabel.textContent = `(${formatSectionLabel(typeSelect.value || 'Not set')})`;
                }
                const positionInput = section.querySelector('input[name$="[position]"]');
                if (positionInput) positionInput.value = idx;
            });
        }

        $(document).on('change', '.section-type', function() {
            var selectedType = $(this).val();
            var section = $(this).closest('.section-item');

            // Hide all type-specific fields first
            section.find('.type-field').hide();

            // Show fields for the selected type
            if (selectedType) {
                section.find('.type-' + selectedType).show();
                const label = section.find('.section-label');
                if (label.length) label.text('(' + formatSectionLabel(selectedType) + ')');
            }
        });

        // Initialize any existing sections on page load
        $(document).ready(function() {
            $('.section-type').trigger('change');
            
            // Initialize Summernote on existing primary text fields
            initializeSummernote();

            // Ensure positions are sequential on load
            renumberSections();
        });

        ['primary_color', 'secondary_color', 'accent_color', 'order_button_color'].forEach(bindColorPicker);

        function bindColorPicker(field) {
            const colorInput = document.getElementById(field);
            const textInput = document.getElementById(`${field}_text`);
            if (!colorInput || !textInput) return;

            const applyValue = (val) => {
                if (!val) return;
                colorInput.value = val;
                textInput.value = val;
            };

            colorInput.addEventListener('input', (e) => {
                textInput.value = e.target.value;
            });

            textInput.addEventListener('input', (e) => {
                const v = e.target.value.trim();
                const hexPattern = /^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/;
                if (hexPattern.test(v)) {
                    colorInput.value = v;
                }
            });

            document.querySelectorAll(`.global-color-swatch[data-target="${field}"]`).forEach(btn => {
                btn.addEventListener('click', () => applyValue(btn.dataset.value));
            });
        }

        // Function to initialize Summernote on primary text fields
        function initializeSummernote() {
            $('.summernote-editor').each(function() {
                if (!$(this).hasClass('summernote-initialized')) {
                    $(this).addClass('summernote-initialized');
                    $(this).summernote({
                        height: 200,
                        toolbar: [
                            ['style', ['style']],
                            ['font', ['bold', 'underline', 'italic', 'clear']],
                            ['fontname', ['fontname']],
                            ['color', ['color']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['height', ['height']],
                            ['insert', ['link', 'picture', 'video']],
                            ['view', ['fullscreen', 'codeview', 'help']]
                        ],
                        callbacks: {
                            onImageUpload: function(files) {
                                // Handle image upload if needed
                                console.log('Image upload:', files);
                            }
                        }
                    });
                }
            });
        }

        // Initialize Summernote when new sections are added
        $(document).on('change', '.section-type', function() {
            var selectedType = $(this).val();
            var section = $(this).closest('.section-item');

            // Hide all type-specific fields first
            section.find('.type-field').hide();

            // Show fields for the selected type
            if (selectedType) {
                section.find('.type-' + selectedType).show();
                
                // Initialize Summernote on newly shown primary text fields
                setTimeout(function() {
                    initializeSummernote();
                }, 100);
            }
        });

        let testimonialCounters = {};
        let carouselImageCounters = {};

        function addTestimonial(sectionIndex) {
            if (!testimonialCounters[sectionIndex]) {
                testimonialCounters[sectionIndex] = 0;
            }

            const container = document.getElementById(`testimonials-container-${sectionIndex}`);
            const testimonialDiv = document.createElement('div');
            testimonialDiv.className = 'card mb-2 testimonial-item';
            testimonialDiv.innerHTML = `
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Testimonial Text</label>
                            <textarea class="form-control" name="sections[${sectionIndex}][testimonials][${testimonialCounters[sectionIndex]}][text]" rows="3" placeholder="Customer testimonial text"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Author Name</label>
                            <input type="text" class="form-control" name="sections[${sectionIndex}][testimonials][${testimonialCounters[sectionIndex]}][author]" placeholder="Customer name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Photo</label>
                            <input type="file" class="form-control" name="sections[${sectionIndex}][testimonials][${testimonialCounters[sectionIndex]}][image]" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label>Rating</label>
                            <select class="form-control" name="sections[${sectionIndex}][testimonials][${testimonialCounters[sectionIndex]}][rating]">
                                <option value="">Select rating</option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeTestimonial(this)">
                    <i class="fas fa-trash"></i> Remove Testimonial
                </button>
            </div>
        `;
            container.appendChild(testimonialDiv);
            testimonialCounters[sectionIndex]++;
        }

        function removeTestimonial(button) {
            button.closest('.testimonial-item').remove();
        }

        function addCarouselImage(sectionIndex) {
            if (!carouselImageCounters[sectionIndex]) {
                carouselImageCounters[sectionIndex] = 0;
            }

            const container = document.getElementById(`carousel-images-container-${sectionIndex}`);
            const imageDiv = document.createElement('div');
            imageDiv.className = 'card mb-2 carousel-image-item';
            imageDiv.innerHTML = `
                <div class="card-body">
                    <div class="form-group">
                        <label>Image</label>
                        <input type="file" class="form-control" name="sections[${sectionIndex}][carousel_images][${carouselImageCounters[sectionIndex]}][image]" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label>Caption (optional)</label>
                        <input type="text" class="form-control" name="sections[${sectionIndex}][carousel_images][${carouselImageCounters[sectionIndex]}][caption]" placeholder="Describe this image">
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeCarouselImage(this)">
                        <i class="fas fa-trash"></i> Remove Image
                    </button>
                </div>
            `;
            container.appendChild(imageDiv);
            carouselImageCounters[sectionIndex]++;
        }

        function removeCarouselImage(button) {
            button.closest('.carousel-image-item').remove();
        }

        let benefitCounters = {};

        function addBenefit(sectionIndex) {
            if (!benefitCounters[sectionIndex]) {
                benefitCounters[sectionIndex] = 0;
            }

            const container = document.getElementById(`benefits-container-${sectionIndex}`);
            const benefitDiv = document.createElement('div');
            benefitDiv.className = 'card mb-2 benefit-item';
            benefitDiv.innerHTML = `
                <div class="card-body">
                    <div class="form-group">
                        <label>Benefit Title</label>
                        <input type="text" class="form-control" name="sections[${sectionIndex}][benefits][${benefitCounters[sectionIndex]}][title]" placeholder="Benefit title">
                    </div>
                    <div class="form-group">
                        <label>Benefit Description</label>
                        <textarea class="form-control" name="sections[${sectionIndex}][benefits][${benefitCounters[sectionIndex]}][description]" rows="2" placeholder="Benefit description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Benefit Icon</label>
                        <input type="text" class="form-control" name="sections[${sectionIndex}][benefits][${benefitCounters[sectionIndex]}][icon]" placeholder="🌿 or fas fa-star">
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeBenefit(this)">
                        <i class="fas fa-trash"></i> Remove Benefit
                    </button>
                </div>
            `;
            container.appendChild(benefitDiv);
            benefitCounters[sectionIndex]++;
        }

        function removeBenefit(button) {
            button.closest('.benefit-item').remove();
        }

        let featureItemCounters = {};

        function addFeatureItem(sectionIndex) {
            if (!featureItemCounters[sectionIndex]) {
                featureItemCounters[sectionIndex] = 0;
            }

            const container = document.getElementById(`feature-items-container-${sectionIndex}`);
            const featureItemDiv = document.createElement('div');
            featureItemDiv.className = 'card mb-2 feature-item';
            featureItemDiv.innerHTML = `
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Emoji</label>
                                <input type="text" class="form-control" name="sections[${sectionIndex}][feature_items][${featureItemCounters[sectionIndex]}][emoji]" placeholder="✅ or ❌">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Text</label>
                                <input type="text" class="form-control" name="sections[${sectionIndex}][feature_items][${featureItemCounters[sectionIndex]}][text]" placeholder="Feature description">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control" name="sections[${sectionIndex}][feature_items][${featureItemCounters[sectionIndex]}][is_positive]">
                                    <option value="1">Positive</option>
                                    <option value="0">Negative</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeFeatureItem(this)">
                        <i class="fas fa-trash"></i> Remove Item
                    </button>
                </div>
            `;
            container.appendChild(featureItemDiv);
            featureItemCounters[sectionIndex]++;
        }

        function removeFeatureItem(button) {
            button.closest('.feature-item').remove();
        }

        let pricingVariantCounters = {};

        function addPricingVariant(sectionIndex) {
            if (!pricingVariantCounters[sectionIndex]) {
                pricingVariantCounters[sectionIndex] = 0;
            }

            const container = document.getElementById(`pricing-variants-container-${sectionIndex}`);
            const variantDiv = document.createElement('div');
            variantDiv.className = 'card mb-2 pricing-variant-item';
            variantDiv.innerHTML = `
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Weight/Size</label>
                                <input type="text" class="form-control" name="sections[${sectionIndex}][pricing_variants][${pricingVariantCounters[sectionIndex]}][weight]" placeholder="450 গ্রাম">
                            </div>
                            <div class="form-group">
                                <label>Regular Price (টাকা)</label>
                                <input type="number" class="form-control" name="sections[${sectionIndex}][pricing_variants][${pricingVariantCounters[sectionIndex]}][regular_price]" placeholder="1600">
                            </div>
                            <div class="form-group">
                                <label>Offer Price (টাকা)</label>
                                <input type="number" class="form-control" name="sections[${sectionIndex}][pricing_variants][${pricingVariantCounters[sectionIndex]}][offer_price]" placeholder="960">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Delivery Text</label>
                                <input type="text" class="form-control" name="sections[${sectionIndex}][pricing_variants][${pricingVariantCounters[sectionIndex]}][delivery_text]" placeholder="সারাদেশ ডেলিভারি চার্জ ফ্রি">
                            </div>
                            <div class="form-group">
                                <label>Delivery Icon (Emoji)</label>
                                <input type="text" class="form-control" name="sections[${sectionIndex}][pricing_variants][${pricingVariantCounters[sectionIndex]}][delivery_icon]" placeholder="🚚">
                            </div>
                            <div class="form-group">
                                <label>Position</label>
                                <input type="number" class="form-control" name="sections[${sectionIndex}][pricing_variants][${pricingVariantCounters[sectionIndex]}][position]" value="${pricingVariantCounters[sectionIndex]}" min="0">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removePricingVariant(this)">
                        <i class="fas fa-trash"></i> Remove Variant
                    </button>
                </div>
            `;
            container.appendChild(variantDiv);
            pricingVariantCounters[sectionIndex]++;
        }

        function removePricingVariant(button) {
            button.closest('.pricing-variant-item').remove();
        }

        // Auto-generate slug from title (supports Bangla)
        $('#title').on('input', function() {
            var title = $(this).val();
            var slug = transliterateBanglaToSlug(title);
            $('#slug').val(slug);
        });

        // Function to transliterate Bangla characters to English slug
        function transliterateBanglaToSlug(text) {
            // Bangla to English transliteration map
            var banglaMap = {
                'অ': 'o', 'আ': 'a', 'ই': 'i', 'ঈ': 'i', 'উ': 'u', 'ঊ': 'u', 'ঋ': 'ri', 
                'এ': 'e', 'ঐ': 'oi', 'ও': 'o', 'ঔ': 'ou',
                'ক': 'k', 'খ': 'kh', 'গ': 'g', 'ঘ': 'gh', 'ঙ': 'ng',
                'চ': 'ch', 'ছ': 'chh', 'জ': 'j', 'ঝ': 'jh', 'ঞ': 'n',
                'ট': 't', 'ঠ': 'th', 'ড': 'd', 'ঢ': 'dh', 'ণ': 'n',
                'ত': 't', 'থ': 'th', 'দ': 'd', 'ধ': 'dh', 'ন': 'n',
                'প': 'p', 'ফ': 'f', 'ব': 'b', 'ভ': 'bh', 'ম': 'm',
                'য': 'j', 'র': 'r', 'ল': 'l', 'শ': 'sh', 'ষ': 'sh', 'স': 's', 'হ': 'h',
                'ড়': 'r', 'ঢ়': 'rh', 'য়': 'y', 'ৎ': 't', 'ং': 'ng', 'ঃ': 'h', 'ঁ': 'n',
                'া': 'a', 'ি': 'i', 'ী': 'i', 'ু': 'u', 'ূ': 'u', 'ৃ': 'ri',
                'ে': 'e', 'ৈ': 'oi', 'ো': 'o', 'ৌ': 'ou',
                '্': '', 'ৗ': 'ou',
                '০': '0', '১': '1', '২': '2', '৩': '3', '৪': '4',
                '৫': '5', '৬': '6', '৭': '7', '৮': '8', '৯': '9'
            };

            var slug = text.toLowerCase();
            
            // Replace Bangla characters with English equivalents
            for (var bangla in banglaMap) {
                var regex = new RegExp(bangla, 'g');
                slug = slug.replace(regex, banglaMap[bangla]);
            }
            
            // Clean up the slug
            slug = slug
                .replace(/[^a-z0-9\s-]/g, '') // Remove remaining special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
                .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
                
            return slug;
        }
    </script>
@endsection
