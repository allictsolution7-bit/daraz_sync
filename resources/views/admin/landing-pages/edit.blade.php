@extends('layouts.master')

@section('title', 'Edit Landing Page')

@section('content')
    <div class="container-fluid landing-builder">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Landing Page</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.landing-pages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Error Display Section -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    Please fix the following errors:
                                </h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> 
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> 
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.landing-pages.update', $landingPage) }}" method="POST" enctype="multipart/form-data" id="landingPageForm">
                            @csrf
                            @method('PUT')
                            <!-- Basic Information -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="mb-0">Basic Information</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="title">Page Title *</label>
                                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                            id="title" name="title" value="{{ old('title', $landingPage->title) }}" required>
                                                        @error('title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="slug">Page Slug *</label>
                                                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                                            id="slug" name="slug" value="{{ old('slug', $landingPage->slug) }}" required>
                                                        <small class="form-text text-muted">URL-friendly version of the title (e.g., "my-landing-page")</small>
                                                        @error('slug')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="product_id">Associated Product *</label>
                                                        <select class="form-control @error('product_id') is-invalid @enderror"
                                                            id="product_id" name="product_id" required>
                                                            <option value="">Select a product</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    {{ old('product_id', $landingPage->product_id) == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->title }}
                                                                    (৳{{ number_format($product->offer ?: $product->old_price, 2) }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('product_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="position">Display Position</label>
                                                        <input type="number"
                                                            class="form-control @error('position') is-invalid @enderror" id="position"
                                                            name="position" value="{{ old('position', $landingPage->position ?? 0) }}" min="0">
                                                        <small class="form-text text-muted">Lower numbers appear first</small>
                                                        @error('position')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="status"
                                                                name="status" value="1" {{ old('status', $landingPage->status) ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="status">Active</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    @php
                                                        $globalPrimaryColor = setting('general', 'primary_color', '#F02627');
                                                        $globalSecondaryColor = setting('general', 'secondary_color', '#113056');
                                                        $globalAccentColor = setting('general', 'accent_color', '#F02627');
                                                    @endphp
                                                    <h5 class="mb-3">Color Settings</h5>
                                                    <div class="form-group">
                                                        <label for="primary_color">Primary Color</label>
                                                        <div class="input-group color-picker-group">
                                                            <span class="input-group-text p-0">
                                                                <input type="color" class="form-control color-chip @error('primary_color') is-invalid @enderror"
                                                                    id="primary_color" name="primary_color" value="{{ old('primary_color', $landingPage->primary_color ?? $globalPrimaryColor) }}">
                                                            </span>
                                                            <input type="text" class="form-control color-text @error('primary_color') is-invalid @enderror"
                                                                id="primary_color_text" value="{{ old('primary_color', $landingPage->primary_color ?? $globalPrimaryColor) }}" placeholder="#007bff">
                                                        </div>
                                                        <small class="form-text text-muted">Main brand color for headings and primary elements</small>
                                                        @error('primary_color')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="secondary_color">Secondary Color</label>
                                                        <div class="input-group color-picker-group">
                                                            <span class="input-group-text p-0">
                                                                <input type="color" class="form-control color-chip @error('secondary_color') is-invalid @enderror"
                                                                    id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $landingPage->secondary_color ?? $globalSecondaryColor) }}">
                                                            </span>
                                                            <input type="text" class="form-control color-text @error('secondary_color') is-invalid @enderror"
                                                                id="secondary_color_text" value="{{ old('secondary_color', $landingPage->secondary_color ?? $globalSecondaryColor) }}" placeholder="#6c757d">
                                                        </div>
                                                        <small class="form-text text-muted">Secondary color for sub-headings and supporting elements</small>
                                                        @error('secondary_color')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="accent_color">Accent Color</label>
                                                        <div class="input-group color-picker-group">
                                                            <span class="input-group-text p-0">
                                                                <input type="color" class="form-control color-chip @error('accent_color') is-invalid @enderror"
                                                                    id="accent_color" name="accent_color" value="{{ old('accent_color', $landingPage->accent_color ?? $globalAccentColor) }}">
                                                            </span>
                                                            <input type="text" class="form-control color-text @error('accent_color') is-invalid @enderror"
                                                                id="accent_color_text" value="{{ old('accent_color', $landingPage->accent_color ?? $globalAccentColor) }}" placeholder="#28a745">
                                                        </div>
                                                        <small class="form-text text-muted">Highlight color for special elements and call-to-actions</small>
                                                        @error('accent_color')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="order_button_color">Order Now Button Color</label>
                                                        <div class="input-group color-picker-group">
                                                            <span class="input-group-text p-0">
                                                                <input type="color" class="form-control color-chip @error('order_button_color') is-invalid @enderror"
                                                                    id="order_button_color" name="order_button_color" value="{{ old('order_button_color', $landingPage->order_button_color ?? '#dc3545') }}">
                                                            </span>
                                                            <input type="text" class="form-control color-text @error('order_button_color') is-invalid @enderror"
                                                                id="order_button_color_text" value="{{ old('order_button_color', $landingPage->order_button_color ?? '#dc3545') }}" placeholder="#dc3545">
                                                        </div>
                                                        <small class="form-text text-muted">Color for the main order/CTA buttons</small>
                                                        @error('order_button_color')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                                            <span class="text-muted me-2">Global palette:</span>
                                                            <button type="button" class="btn btn-sm global-color-swatch" data-target="primary_color" data-value="{{ $globalPrimaryColor }}" style="background: {{ $globalPrimaryColor }};"></button>
                                                            <button type="button" class="btn btn-sm global-color-swatch" data-target="secondary_color" data-value="{{ $globalSecondaryColor }}" style="background: {{ $globalSecondaryColor }};"></button>
                                                            <button type="button" class="btn btn-sm global-color-swatch" data-target="accent_color" data-value="{{ $globalAccentColor }}" style="background: {{ $globalAccentColor }};"></button>
                                                        </div>
                                                        <small class="form-text text-muted">Click to apply saved brand colors or enter any hex value.</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <!-- Button & Form Settings -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="mb-0">Button & Form Settings</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="order_button_text">Order Button Text</label>
                                                        <input type="text"
                                                            class="form-control @error('order_button_text') is-invalid @enderror"
                                                            id="order_button_text" name="order_button_text"
                                                            value="{{ old('order_button_text', $landingPage->order_button_text ?? 'এখনই কিনুন') }}">
                                                        @error('order_button_text')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="order_button_url">Order Button URL</label>
                                                        <input type="text"
                                                            class="form-control @error('order_button_url') is-invalid @enderror"
                                                            id="order_button_url" name="order_button_url"
                                                            value="{{ old('order_button_url', $landingPage->order_button_url ?? '#order-section') }}">
                                                        <small class="form-text text-muted">Use #order-section to scroll to order
                                                            form</small>
                                                        @error('order_button_url')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="order_form_title">Order Form Title</label>
                                                        <input type="text"
                                                            class="form-control @error('order_form_title') is-invalid @enderror"
                                                            id="order_form_title" name="order_form_title"
                                                            value="{{ old('order_form_title', $landingPage->order_form_title ?? 'অর্ডার করুন') }}">
                                                        @error('order_form_title')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="order_place_button_text">Order Place Button Text</label>
                                                        <input type="text"
                                                            class="form-control @error('order_place_button_text') is-invalid @enderror"
                                                            id="order_place_button_text" name="order_place_button_text"
                                                            value="{{ old('order_place_button_text', $landingPage->order_place_button_text ?? 'অর্ডার Confirm করুন') }}">
                                                        @error('order_place_button_text')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <!-- Sections -->
                            <div class="row">
                                <div class="col-12">
                                    <h4>Content Sections</h4>
                                    <p class="text-muted">Add features, reviews, and other content sections to your landing page.</p>
                                    <div id="sections-container">
                                        @foreach($landingPage->sections as $index => $section)
                                            @php
                                                $sectionType = $section->section_type;
                                            @endphp
                                            <div class="card mb-3 section-item">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5 class="mb-0">Section {{ $index + 1 }} <span class="text-white section-label ms-2">({{ ucwords(str_replace('_', ' ', $sectionType)) }})</span></h5>
                                                    <div class="btn-group btn-group-sm section-move-controls">
                                                        <button type="button" class="btn btn-outline-secondary text-white" onclick="toggleSectionBody(this)" title="Collapse/Expand">
                                                            <i class="fas fa-plus"></i>
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
                                                    <input type="hidden" name="sections[{{ $index }}][section_id]" value="{{ $section->id }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Section Type</label>
                                                                <select class="form-control section-type" name="sections[{{ $index }}][section_type]" required>
                                                                    <option value="">Select type</option>
                                                                    <option value="hero" {{ $sectionType == 'hero' ? 'selected' : '' }}>Hero Section</option>
                                                                    <option value="benefit" {{ $sectionType == 'benefit' ? 'selected' : '' }}>Benefit</option>
                                                                    <option value="testimonials" {{ $sectionType == 'testimonials' ? 'selected' : '' }}>Testimonials</option>
                                                                    <option value="feature_list" {{ $sectionType == 'feature_list' ? 'selected' : '' }}>Feature List</option>
                                                                    <option value="pricing" {{ $sectionType == 'pricing' ? 'selected' : '' }}>Pricing</option>
                                                                    <option value="countdown" {{ $sectionType == 'countdown' ? 'selected' : '' }}>Countdown</option>
                                                                                                        <option value="video" {{ $sectionType == 'video' ? 'selected' : '' }}>Video Section</option>
                                    <option value="single_image" {{ $sectionType == 'single_image' ? 'selected' : '' }}>Single Image Section</option>
                                    <option value="image_carousel" {{ $sectionType == 'image_carousel' ? 'selected' : '' }}>Image Carousel</option>
                                    <option value="call_to_action" {{ $sectionType == 'call_to_action' ? 'selected' : '' }}>Call to Action</option>
                                    <option value="header" {{ $sectionType == 'header' ? 'selected' : '' }}>Header Section</option>
                                                                </select>
                                                            </div>
                                                            <!-- Benefit Section Title -->
                                                            <div class="form-group type-field type-benefit" @if($sectionType != 'benefit') style="display: none;" @endif>
                                                                <label>Benefit Section Title</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][benefit_title]" value="{{ $section->benefit_title }}" placeholder="Benefit section title">
                                                            </div>
                                                            
                                                            <!-- Testimonials Section Title -->
                                                            <div class="form-group type-field type-testimonials" @if($sectionType != 'testimonials') style="display: none;" @endif>
                                                                <label>Testimonials Section Title</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][testimonials_title]" value="{{ $section->testimonials_title }}" placeholder="Testimonials section title">
                                                            </div>
                                                            
                                                            <!-- Feature List Section Title -->
                                                            <div class="form-group type-field type-feature_list" @if($sectionType != 'feature_list') style="display: none;" @endif>
                                                                <label>Feature List Section Title</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][feature_list_title]" value="{{ $section->feature_list_title }}" placeholder="Feature list section title">
                                                            </div>
                                                            
                                                            <!-- Pricing Section Title -->
                                                            <div class="form-group type-field type-pricing" @if($sectionType != 'pricing') style="display: none;" @endif>
                                                                <label>Pricing Section Title</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][pricing_title]" value="{{ $section->pricing_title }}" placeholder="Pricing section title">
                                                            </div>
                                                            
                                                            <!-- Countdown Section Title -->
                                                            <div class="form-group type-field type-countdown" @if($sectionType != 'countdown') style="display: none;" @endif>
                                                                <label>Countdown Section Title</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][countdown_title]" value="{{ $section->countdown_title }}" placeholder="Countdown section title">
                                                            </div>

                                                            <!-- Hero Section Fields -->
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Main Heading</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][heading]" value="{{ $section->heading }}" placeholder="Main heading">
                                                            </div>
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Sub Heading</label>
                                                                <textarea class="form-control" name="sections[{{ $index }}][sub_heading]" rows="3" placeholder="Sub heading">{{ $section->sub_heading }}</textarea>
                                                            </div>
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Primary Text</label>
                                                                <textarea class="form-control summernote-editor" name="sections[{{ $index }}][primary_text]" rows="4" placeholder="Primary text">{{ $section->primary_text }}</textarea>
                                                            </div>
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Hero Image</label>
                                                                @if($section->hero_image)
                                                                    <div class="mb-2">
                                                                        <img src="{{ asset($section->hero_image) }}" alt="Hero Image" class="img-fluid rounded" style="max-height: 100px;">
                                                                    </div>
                                                                @endif
                                                                <input type="file" class="form-control" name="sections[{{ $index }}][hero_image]" accept="image/*">
                                                                @if($section->hero_image)
                                                                    <input type="hidden" name="sections[{{ $index }}][old_hero_image]" value="{{ $section->hero_image }}">
                                                                @endif
                                                            </div>
                                                                                    <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                            <label>Hero Image Alt Text</label>
                                                            <input type="text" class="form-control" name="sections[{{ $index }}][hero_image_alt]" value="{{ $section->hero_image_alt }}" placeholder="Alt text for hero image">
                                                            </div>
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>YouTube Video URL (Optional)</label>
                                                                <input type="url" class="form-control" name="sections[{{ $index }}][hero_video_url]" value="{{ $section->hero_video_url }}" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                                                <small class="form-text text-muted">If YouTube video is provided, the hero image will not be displayed</small>
                                                            </div>
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Badge Image</label>
                                                                @if($section->badge_image)
                                                                    <div class="mb-2">
                                                                        <img src="{{ asset($section->badge_image) }}" alt="Current Badge" style="max-height: 40px; max-width: 100px;">
                                                                    </div>
                                                                @endif
                                                                <input type="file" class="form-control" name="sections[{{ $index }}][badge_image]" accept="image/*">
                                                                <small class="form-text text-muted">Upload badge image or logo (recommended: 100x40px or similar ratio)</small>
                                                                @if($section->badge_image)
                                                                    <input type="hidden" name="sections[{{ $index }}][old_badge_image]" value="{{ $section->badge_image }}">
                                                                @endif
                                                            </div>
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Desktop Badge Width (px)</label>
                                                                <input type="number" class="form-control" name="sections[{{ $index }}][badge_desktop_width]" value="{{ $section->badge_desktop_width ?? 100 }}" min="30" max="200">
                                                                <small class="form-text text-muted">Width in pixels for desktop view</small>
                                                            </div>
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Mobile Badge Width (px)</label>
                                                                <input type="number" class="form-control" name="sections[{{ $index }}][badge_mobile_width]" value="{{ $section->badge_mobile_width ?? 80 }}" min="20" max="150">
                                                                <small class="form-text text-muted">Width in pixels for mobile view</small>
                                                            </div>
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Badge Text</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][badge_text]" value="{{ $section->badge_text }}" placeholder="e.g., ১০০% প্রাকৃতিক">
                                                            </div>
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Badge Color</label>
                                                                <input type="color" class="form-control" name="sections[{{ $index }}][badge_color]" value="{{ $section->badge_color ?? '#ffd54f' }}">
                                                            </div>

                                                            <!-- Trust Indicators Section -->
                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>
                                                                    <input type="checkbox" name="sections[{{ $index }}][show_trust_indicators]" value="1" {{ ($section->show_trust_indicators ?? true) ? 'checked' : '' }}> 
                                                                    Show Trust Indicators
                                                                </label>
                                                            </div>

                                                            <div class="form-group type-field type-hero" @if($sectionType != 'hero') style="display: none;" @endif>
                                                                <label>Trust Indicators</label>
                                                                <div class="trust-indicators-container">
                                                                    @php
                                                                        $trustIndicators = $section->trust_indicators ?? [
                                                                            ['icon' => '⭐', 'text1' => '4.9/5', 'text2' => 'গ্রাহক রেটিং'],
                                                                            ['icon' => '👨‍👩‍👧‍👦', 'text1' => '১০০০+', 'text2' => 'সন্তুষ্ট গ্রাহক'],
                                                                            ['icon' => '🔄', 'text1' => '৭ দিন', 'text2' => 'রিটার্ন গ্যারান্টি']
                                                                        ];
                                                                    @endphp
                                                                    @foreach($trustIndicators as $i => $indicator)
                                                                        <div class="trust-indicator-item mb-3">
                                                                            <div class="row">
                                                                                <div class="col-md-2">
                                                                                    <input type="text" class="form-control" name="sections[{{ $index }}][trust_indicators][{{ $i }}][icon]" placeholder="⭐" value="{{ $indicator['icon'] ?? '' }}">
                                                                                </div>
                                                                                <div class="col-md-5">
                                                                                    <input type="text" class="form-control" name="sections[{{ $index }}][trust_indicators][{{ $i }}][text1]" placeholder="4.9/5" value="{{ $indicator['text1'] ?? '' }}">
                                                                                </div>
                                                                                <div class="col-md-5">
                                                                                    <input type="text" class="form-control" name="sections[{{ $index }}][trust_indicators][{{ $i }}][text2]" placeholder="গ্রাহক রেটিং" value="{{ $indicator['text2'] ?? '' }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <small class="form-text text-muted">Add up to 3 trust indicators to build customer confidence</small>
                                                            </div>
                                                            <div class="form-group type-field type-testimonials" @if($sectionType != 'testimonials') style="display: none;" @endif>
                                                                <label>Testimonials</label>
                                                                <div id="testimonials-container-{{ $index }}" class="testimonials-container">
                                                                    @if(isset($section->testimonials) && is_array($section->testimonials))
                                                                        @foreach($section->testimonials as $tIndex => $testimonial)
                                                                            <div class="card mb-2 testimonial-item">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Testimonial Text</label>
                                                                                                <textarea class="form-control" name="sections[{{ $index }}][testimonials][{{ $tIndex }}][text]" rows="3" placeholder="Customer testimonial text">{{ $testimonial['text'] ?? '' }}</textarea>
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Author Name</label>
                                                                                                <input type="text" class="form-control" name="sections[{{ $index }}][testimonials][{{ $tIndex }}][author]" value="{{ $testimonial['author'] ?? '' }}" placeholder="Customer name">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Customer Photo</label>
                                                                                                @if(isset($testimonial['image']) && $testimonial['image'])
                                                                                                    <div class="mb-2">
                                                                                                        <img src="{{ asset($testimonial['image']) }}" alt="Customer Photo" class="img-fluid rounded" style="max-height: 100px;">
                                                                                                    </div>
                                                                                                @endif
                                                                                                <input type="file" class="form-control" name="sections[{{ $index }}][testimonials][{{ $tIndex }}][image]" accept="image/*">
                                                                                                @if(isset($testimonial['image']) && $testimonial['image'])
                                                                                                    <input type="hidden" name="sections[{{ $index }}][testimonials][{{ $tIndex }}][old_image]" value="{{ $testimonial['image'] }}">
                                                                                                @endif
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Rating</label>
                                                                                                <select class="form-control" name="sections[{{ $index }}][testimonials][{{ $tIndex }}][rating]">
                                                                                                    <option value="">Select rating</option>
                                                                                                    <option value="1" {{ (isset($testimonial['rating']) && $testimonial['rating'] == 1) ? 'selected' : '' }}>1 Star</option>
                                                                                                    <option value="2" {{ (isset($testimonial['rating']) && $testimonial['rating'] == 2) ? 'selected' : '' }}>2 Stars</option>
                                                                                                    <option value="3" {{ (isset($testimonial['rating']) && $testimonial['rating'] == 3) ? 'selected' : '' }}>3 Stars</option>
                                                                                                    <option value="4" {{ (isset($testimonial['rating']) && $testimonial['rating'] == 4) ? 'selected' : '' }}>4 Stars</option>
                                                                                                    <option value="5" {{ (isset($testimonial['rating']) && $testimonial['rating'] == 5) ? 'selected' : '' }}>5 Stars</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeTestimonial(this)">
                                                                                        <i class="fas fa-trash"></i> Remove Testimonial
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addTestimonial({{ $index }})">
                                                                    <i class="fas fa-plus"></i> Add Testimonial
                                                                </button>
                                                            </div>
                                                            <div class="form-group type-field type-benefit" @if($sectionType != 'benefit') style="display: none;" @endif>
                                                                <label>Benefits</label>
                                                                <div id="benefits-container-{{ $index }}" class="benefits-container">
                                                                    @if(isset($section->benefits) && is_array($section->benefits))
                                                                        @foreach($section->benefits as $bIndex => $benefit)
                                                                            <div class="card mb-2 benefit-item">
                                                                                <div class="card-body">
                                                                                    <div class="form-group">
                                                                                        <label>Benefit Title</label>
                                                                                        <input type="text" class="form-control" name="sections[{{ $index }}][benefits][{{ $bIndex }}][title]" value="{{ $benefit['title'] ?? '' }}" placeholder="Benefit title">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>Benefit Description</label>
                                                                                        <textarea class="form-control" name="sections[{{ $index }}][benefits][{{ $bIndex }}][description]" rows="2" placeholder="Benefit description">{{ $benefit['description'] ?? '' }}</textarea>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>Benefit Icon</label>
                                                                                        <input type="text" class="form-control" name="sections[{{ $index }}][benefits][{{ $bIndex }}][icon]" value="{{ $benefit['icon'] ?? '' }}" placeholder="🌿 or fas fa-star">
                                                                                    </div>
                                                                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeBenefit(this)">
                                                                                        <i class="fas fa-trash"></i> Remove Benefit
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addBenefit({{ $index }})">
                                                                    <i class="fas fa-plus"></i> Add Benefit
                                                                </button>
                                                            </div>
                                                            <!-- Only show for feature_list section type -->
                                                            <div class="form-group type-field type-feature_list" @if($sectionType != 'feature_list') style="display: none;" @endif>
                                                                <label>Feature Items</label>
                                                                <div id="feature-items-container-{{ $index }}" class="feature-items-container">
                                                                    @if(isset($section->feature_items) && is_array($section->feature_items))
                                                                        @foreach($section->feature_items as $fIndex => $featureItem)
                                                                            <div class="card mb-2 feature-item">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-4">
                                                                                            <div class="form-group">
                                                                                                <label>Emoji</label>
                                                                                                <input type="text" class="form-control" name="sections[{{ $index }}][feature_items][{{ $fIndex }}][emoji]" value="{{ $featureItem['emoji'] ?? '' }}" placeholder="✅ or ❌">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Text</label>
                                                                                                <input type="text" class="form-control" name="sections[{{ $index }}][feature_items][{{ $fIndex }}][text]" value="{{ $featureItem['text'] ?? '' }}" placeholder="Feature description">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-2">
                                                                                            <div class="form-group">
                                                                                                <label>Type</label>
                                                                                                <select class="form-control" name="sections[{{ $index }}][feature_items][{{ $fIndex }}][is_positive]">
                                                                                                    <option value="1" {{ (isset($featureItem['is_positive']) && $featureItem['is_positive'] == 1) ? 'selected' : '' }}>Positive</option>
                                                                                                    <option value="0" {{ (isset($featureItem['is_positive']) && $featureItem['is_positive'] == 0) ? 'selected' : '' }}>Negative</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeFeatureItem(this)">
                                                                                        <i class="fas fa-trash"></i> Remove Item
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addFeatureItem({{ $index }})">
                                                                    <i class="fas fa-plus"></i> Add Feature Item
                                                                </button>
                                                            </div>
                                                            <!-- Only show for pricing section type -->
                                                            <div class="form-group type-field type-pricing" @if($sectionType != 'pricing') style="display: none;" @endif>
                                                                <label>Product Variants</label>
                                                                <div id="pricing-variants-container-{{ $index }}" class="pricing-variants-container">
                                                                    @if(isset($section->pricing_variants) && is_array($section->pricing_variants))
                                                                        @foreach($section->pricing_variants as $pIndex => $pricingVariant)
                                                                            <div class="card mb-2 pricing-variant-item">
                                                                                <div class="card-body">
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Weight/Size</label>
                                                                                                <input type="text" class="form-control" name="sections[{{ $index }}][pricing_variants][{{ $pIndex }}][weight]" value="{{ $pricingVariant['weight'] ?? '' }}" placeholder="450 গ্রাম">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Regular Price (টাকা)</label>
                                                                                                <input type="number" class="form-control" name="sections[{{ $index }}][pricing_variants][{{ $pIndex }}][regular_price]" value="{{ $pricingVariant['regular_price'] ?? '' }}" placeholder="1600">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Offer Price (টাকা)</label>
                                                                                                <input type="number" class="form-control" name="sections[{{ $index }}][pricing_variants][{{ $pIndex }}][offer_price]" value="{{ $pricingVariant['offer_price'] ?? '' }}" placeholder="960">
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-md-6">
                                                                                            <div class="form-group">
                                                                                                <label>Delivery Text</label>
                                                                                                <input type="text" class="form-control" name="sections[{{ $index }}][pricing_variants][{{ $pIndex }}][delivery_text]" value="{{ $pricingVariant['delivery_text'] ?? '' }}" placeholder="সারাদেশ ডেলিভারি চার্জ ফ্রি">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Delivery Icon (Emoji)</label>
                                                                                                <input type="text" class="form-control" name="sections[{{ $index }}][pricing_variants][{{ $pIndex }}][delivery_icon]" value="{{ $pricingVariant['delivery_icon'] ?? '' }}" placeholder="🚚">
                                                                                            </div>
                                                                                            <div class="form-group">
                                                                                                <label>Position</label>
                                                                                                <input type="number" class="form-control" name="sections[{{ $index }}][pricing_variants][{{ $pIndex }}][position]" value="{{ $pricingVariant['position'] ?? $pIndex }}" min="0">
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removePricingVariant(this)">
                                                                                        <i class="fas fa-trash"></i> Remove Variant
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addPricingVariant({{ $index }})">
                                                                    <i class="fas fa-plus"></i> Add Product Variant
                                                                </button>
                                                            </div>
                                                            <div class="form-group type-field type-countdown" @if($sectionType != 'countdown') style="display: none;" @endif>
                                                                <label>Section Title</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][title]" value="{{ $section->title }}" placeholder="Countdown Title">
                                                            </div>
                                                            <div class="form-group type-field type-countdown" @if($sectionType != 'countdown') style="display: none;" @endif>
                                                                <label>Countdown Hours</label>
                                                                <input type="number" class="form-control" name="sections[{{ $index }}][countdown_hours]" value="{{ $section->countdown_hours ?? 4 }}" min="1">
                                                            </div>
                                                            <div class="form-group type-field type-countdown" @if($sectionType != 'countdown') style="display: none;" @endif>
                                                                <label>Repeat Countdown?</label>
                                                                <select class="form-control" name="sections[{{ $index }}][countdown_repeat]">
                                                                    <option value="1" {{ ($section->countdown_repeat ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                                                                    <option value="0" {{ ($section->countdown_repeat ?? 0) == 0 ? 'selected' : '' }}>No</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <!-- Video Section Fields -->
                                                            <div class="form-group type-field type-video" @if($sectionType != 'video') style="display: none;" @endif>
                                                                <label>Video Title</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][video_title]" value="{{ $section->video_title }}" placeholder="Video Title">
                                                            </div>
                                                            <div class="form-group type-field type-video" @if($sectionType != 'video') style="display: none;" @endif>
                                                                <label>Video Description</label>
                                                                <textarea class="form-control" name="sections[{{ $index }}][video_description]" rows="3" placeholder="Video description">{{ $section->video_description }}</textarea>
                                                            </div>
                                                            <div class="form-group type-field type-video" @if($sectionType != 'video') style="display: none;" @endif>
                                                                <label>YouTube Video URL</label>
                                                                <input type="url" class="form-control" name="sections[{{ $index }}][video_url]" value="{{ $section->video_url }}" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                                                <small class="form-text text-muted">Paste the full YouTube URL (e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ)</small>
                                                            </div>
                                                            
                                                            <!-- Single Image Section Fields -->
                                                            <div class="form-group type-field type-single_image" @if($sectionType != 'single_image') style="display: none;" @endif>
                                                                <label>Section Description</label>
                                                                <textarea class="form-control" name="sections[{{ $index }}][sub_heading]" rows="3" placeholder="Section description">{{ $section->sub_heading }}</textarea>
                                                            </div>
                                                            <div class="form-group type-field type-single_image" @if($sectionType != 'single_image') style="display: none;" @endif>
                                                                <label>Image</label>
                                                                @if($section->single_image)
                                                                    <div class="mb-2">
                                                                        <img src="{{ asset($section->single_image) }}" alt="Section Image" class="img-fluid rounded" style="max-height: 100px;">
                                                                    </div>
                                                                @endif
                                                                <input type="file" class="form-control" name="sections[{{ $index }}][single_image]" accept="image/*">
                                                                @if($section->single_image)
                                                                    <input type="hidden" name="sections[{{ $index }}][old_single_image]" value="{{ $section->single_image }}">
                                                                @endif
                                                                <small class="form-text text-muted">Recommended size: 800x600px or larger</small>
                                                            </div>
                                                            <div class="form-group type-field type-single_image" @if($sectionType != 'single_image') style="display: none;" @endif>
                                                                <label>Image Alt Text</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][single_image_alt]" value="{{ $section->single_image_alt }}" placeholder="Alt text for image">
                                                            </div>

                                                            <!-- Image Carousel Section Fields -->
                                                            <div class="form-group type-field type-image_carousel" @if($sectionType != 'image_carousel') style="display: none;" @endif>
                                                                <label>Carousel Title</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][title]" value="{{ $section->title }}" placeholder="Gallery title">
                                                            </div>
                                                            <div class="form-group type-field type-image_carousel" @if($sectionType != 'image_carousel') style="display: none;" @endif>
                                                                <label>Carousel Description</label>
                                                                <textarea class="form-control" name="sections[{{ $index }}][description]" rows="3" placeholder="Short description for this gallery">{{ $section->description }}</textarea>
                                                            </div>
                                                            <div class="form-group type-field type-image_carousel" @if($sectionType != 'image_carousel') style="display: none;" @endif>
                                                                <label>Carousel Images</label>
                                                                <div id="carousel-images-container-{{ $index }}" class="carousel-images-container">
                                                                    @php
                                                                        $carouselImages = $section->carousel_images ?? [];
                                                                    @endphp
                                                                    @if(!empty($carouselImages))
                                                                        @foreach($carouselImages as $cIndex => $carouselImage)
                                                                            <div class="card mb-2 carousel-image-item">
                                                                                <div class="card-body">
                                                                                    @if(!empty($carouselImage['image']))
                                                                                        <div class="mb-2">
                                                                                            <img src="{{ asset($carouselImage['image']) }}" alt="{{ $carouselImage['caption'] ?? 'Carousel image' }}" class="img-fluid rounded" style="max-height: 120px;">
                                                                                        </div>
                                                                                        <input type="hidden" name="sections[{{ $index }}][carousel_images][{{ $cIndex }}][old_image]" value="{{ $carouselImage['image'] }}">
                                                                                    @endif
                                                                                    <div class="form-group">
                                                                                        <label>Image</label>
                                                                                        <input type="file" class="form-control" name="sections[{{ $index }}][carousel_images][{{ $cIndex }}][image]" accept="image/*">
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label>Caption (optional)</label>
                                                                                        <input type="text" class="form-control" name="sections[{{ $index }}][carousel_images][{{ $cIndex }}][caption]" value="{{ $carouselImage['caption'] ?? '' }}" placeholder="Describe this image">
                                                                                    </div>
                                                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeCarouselImage(this)">
                                                                                        <i class="fas fa-trash"></i> Remove Image
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addCarouselImage({{ $index }})">
                                                                    <i class="fas fa-plus"></i> Add Image
                                                                </button>
                                                                <small class="form-text text-muted">Upload one or more images to show inside the carousel.</small>
                                                            </div>
                                                            
                                                            <!-- Call to Action Section Fields -->
                                                            <div class="form-group type-field type-call_to_action" @if($sectionType != 'call_to_action') style="display: none;" @endif>
                                                                <label>Call to Action Title</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][cta_title]" value="{{ $section->cta_title ?? 'প্রয়োজনে কল করুন' }}" placeholder="প্রয়োজনে কল করুন">
                                                            </div>
                                                            <div class="form-group type-field type-call_to_action" @if($sectionType != 'call_to_action') style="display: none;" @endif>
                                                                <label>Call to Action Subtitle (Optional)</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][cta_subtitle]" value="{{ $section->cta_subtitle }}" placeholder="Subtitle text">
                                                            </div>
                                                            <div class="form-group type-field type-call_to_action" @if($sectionType != 'call_to_action') style="display: none;" @endif>
                                                                <label>Button Text</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][cta_button_text]" value="{{ $section->cta_button_text ?? 'কল করুন' }}" placeholder="কল করুন">
                                                            </div>
                                                            <div class="form-group type-field type-call_to_action" @if($sectionType != 'call_to_action') style="display: none;" @endif>
                                                                <label>Phone Number</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][cta_phone_number]" value="{{ $section->cta_phone_number ?? '01611-109447' }}" placeholder="01611-109447">
                                                            </div>
                                                            <div class="form-group type-field type-call_to_action" @if($sectionType != 'call_to_action') style="display: none;" @endif>
                                                                <label>Background Color</label>
                                                                <input type="color" class="form-control" name="sections[{{ $index }}][cta_background_color]" value="{{ $section->cta_background_color ?? '#1D8758' }}">
                                                            </div>
                                                            <div class="form-group type-field type-call_to_action" @if($sectionType != 'call_to_action') style="display: none;" @endif>
                                                                <label>Button Color</label>
                                                                <input type="color" class="form-control" name="sections[{{ $index }}][cta_button_color]" value="{{ $section->cta_button_color ?? '#dc3545' }}">
                                                            </div>
                                                            
                                                            <!-- Header Section Fields -->
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Logo</label>
                                                                @if($section->header_logo)
                                                                    <div class="mb-2">
                                                                        <img src="{{ asset($section->header_logo) }}" alt="Header Logo" class="img-fluid rounded" style="max-height: 60px;">
                                                                    </div>
                                                                @endif
                                                                <input type="file" class="form-control" name="sections[{{ $index }}][header_logo]" accept="image/*">
                                                                @if($section->header_logo)
                                                                    <input type="hidden" name="sections[{{ $index }}][old_header_logo]" value="{{ $section->header_logo }}">
                                                                @endif
                                                                <small class="form-text text-muted">Recommended size: 200x60px or similar ratio</small>
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Logo Alt Text</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][header_logo_alt]" value="{{ $section->header_logo_alt }}" placeholder="Alt text for logo">
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Desktop Logo Width (px)</label>
                                                                <input type="number" class="form-control" name="sections[{{ $index }}][header_desktop_logo_width]" value="{{ $section->header_desktop_logo_width ?? 200 }}" min="50" max="500">
                                                                <small class="form-text text-muted">Width in pixels for desktop view</small>
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Mobile Logo Width (px)</label>
                                                                <input type="number" class="form-control" name="sections[{{ $index }}][header_mobile_logo_width]" value="{{ $section->header_mobile_logo_width ?? 150 }}" min="30" max="300">
                                                                <small class="form-text text-muted">Width in pixels for mobile view</small>
                                                            </div>

                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Logo Alignment</label>
                                                                <select class="form-control" name="sections[{{ $index }}][header_alignment]">
                                                                    <option value="flex-start" {{ ($section->header_alignment ?? 'center') == 'flex-start' ? 'selected' : '' }}>Left</option>
                                                                    <option value="center" {{ ($section->header_alignment ?? 'center') == 'center' ? 'selected' : '' }}>Center</option>
                                                                    <option value="flex-end" {{ ($section->header_alignment ?? 'center') == 'flex-end' ? 'selected' : '' }}>Right</option>
                                                                </select>
                                                            </div>
                                                            
                                                            <!-- Button 1 Fields -->
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 1 Text</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][header_button1_text]" value="{{ $section->header_button1_text ?? 'Call Now' }}" placeholder="Call Now">
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 1 URL</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][header_button1_url]" value="{{ $section->header_button1_url ?? 'tel:01717171717' }}" placeholder="tel:01717171717">
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 1 Background Color</label>
                                                                <input type="color" class="form-control" name="sections[{{ $index }}][header_button1_color]" value="{{ $section->header_button1_color ?? '#007bff' }}">
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 1 Text Color</label>
                                                                <input type="color" class="form-control" name="sections[{{ $index }}][header_button1_text_color]" value="{{ $section->header_button1_text_color ?? '#ffffff' }}">
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 1 Active</label>
                                                                <div class="custom-control custom-switch">
                                                                    <input type="checkbox" class="custom-control-input" name="sections[{{ $index }}][header_button1_active]" value="1" {{ ($section->header_button1_active ?? true) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label">Show Button 1</label>
                                                                </div>
                                                            </div>
                                                            
                                                            <!-- Button 2 Fields -->
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 2 Text</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][header_button2_text]" value="{{ $section->header_button2_text ?? 'এখনই কিনুন' }}" placeholder="এখনই কিনুন">
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 2 URL</label>
                                                                <input type="text" class="form-control" name="sections[{{ $index }}][header_button2_url]" value="{{ $section->header_button2_url ?? '#order-section' }}" placeholder="#order-section">
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 2 Background Color</label>
                                                                <input type="color" class="form-control" name="sections[{{ $index }}][header_button2_color]" value="{{ $section->header_button2_color ?? '#dc3545' }}">
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 2 Text Color</label>
                                                                <input type="color" class="form-control" name="sections[{{ $index }}][header_button2_text_color]" value="{{ $section->header_button2_text_color ?? '#ffffff' }}">
                                                            </div>
                                                            <div class="form-group type-field type-header" @if($sectionType != 'header') style="display: none;" @endif>
                                                                <label>Button 2 Active</label>
                                                                <div class="custom-control custom-switch">
                                                                    <input type="checkbox" class="custom-control-input" name="sections[{{ $index }}][header_button2_active]" value="1" {{ ($section->header_button2_active ?? true) ? 'checked' : '' }}>
                                                                    <label class="custom-control-label">Show Button 2</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group type-field type-hero type-benefit type-testimonials type-feature_list type-pricing type-countdown type-video type-single_image type-image_carousel type-call_to_action type-header">
                                                                <label>Position</label>
                                                                <input type="number" class="form-control" name="sections[{{ $index }}][position]" value="{{ $section->position ?? 0 }}" min="0">
                                                            </div>
                                                            <div class="form-group type-field type-hero type-benefit type-testimonials type-feature_list type-pricing type-countdown type-video type-single_image type-image_carousel type-call_to_action type-header">
                                                                <div class="custom-control custom-switch">
                                                                    <input type="checkbox" class="custom-control-input" id="status_{{ $index }}" name="sections[{{ $index }}][status]" value="1" {{ $section->status ? 'checked' : '' }}>
                                                                    <label class="custom-control-label" for="status_{{ $index }}">Active</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeSection(this)">
                                                        <i class="fas fa-trash"></i> Remove Section
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" onclick="addSection()">
                                        <i class="fas fa-plus"></i> Add Section
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Landing Page
                                </button>
                                <a href="{{ route('admin.landing-pages.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .type-benefit {
            display: none;
        }
        .section-move-controls .btn {
            padding: 4px 8px;
            border-color: rgba(255, 255, 255, 0.8);
        }
        .section-move-controls i {
            color: #fff;
        }
        .landing-builder .color-picker-group .color-chip {
            width: 48px;
            height: 38px;
            border: none;
            padding: 0;
            background: transparent;
        }
        .landing-builder .color-picker-group .color-text {
            font-family: monospace;
        }
        .landing-builder .global-color-swatch {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid rgba(0,0,0,0.08);
            padding: 0;
        }
        .landing-builder {
            background: linear-gradient(120deg, #f4f7ff 0%, #f8fffa 100%);
            min-height: 100vh;
            padding-top: 20px;
        }
        .landing-builder .card {
            border: none;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
            background: #ffffff;
        }
        .landing-builder .card-header {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.85) 0%, rgba(16, 185, 129, 0.78) 100%);
            color: #fff;
            border: none;
        }
        .landing-builder .card-header h3,
        .landing-builder .card-header h4 {
            color: #fff;
        }
        .landing-builder .btn-primary {
            background: linear-gradient(135deg, #2d4ea3 0%, #1fb987 100%);
            border: none;
            box-shadow: 0 6px 14px rgba(31, 185, 135, 0.3);
        }
        .landing-builder .btn-primary:hover {
            filter: brightness(1.03);
        }
        .landing-builder .btn-outline-primary {
            color: #2d4ea3;
            border-color: #2d4ea3;
        }
        .landing-builder .btn-outline-primary:hover {
            background: #2d4ea3;
            color: #fff;
        }
        .landing-builder .card-title {
            color: #1f2f60;
        }
        .landing-builder .form-text.text-muted {
            color: #4b5563 !important;
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
        let sectionCounter = {{ $landingPage->sections->count() }};
        let testimonialCounter = 0;
        let benefitCounter = 0;
        let featureItemCounter = 0;
        let pricingVariantCounter = 0;

        let testimonialCounters = {};
        let benefitCounters = {};
        let featureItemCounters = {};
        let pricingVariantCounters = {};
        let carouselImageCounters = {};

        // Initialize counters for existing sections
        @foreach($landingPage->sections as $index => $section)
            @if(isset($section->testimonials) && is_array($section->testimonials))
                testimonialCounters[{{ $index }}] = {{ count($section->testimonials) }};
            @else
                testimonialCounters[{{ $index }}] = 0;
            @endif
            
            @if(isset($section->benefits) && is_array($section->benefits))
                benefitCounters[{{ $index }}] = {{ count($section->benefits) }};
            @else
                benefitCounters[{{ $index }}] = 0;
            @endif
            
            @if(isset($section->feature_items) && is_array($section->feature_items))
                featureItemCounters[{{ $index }}] = {{ count($section->feature_items) }};
            @else
                featureItemCounters[{{ $index }}] = 0;
            @endif
            
            @if(isset($section->pricing_variants) && is_array($section->pricing_variants))
                pricingVariantCounters[{{ $index }}] = {{ count($section->pricing_variants) }};
            @else
                pricingVariantCounters[{{ $index }}] = 0;
            @endif

            @if(isset($section->carousel_images) && is_array($section->carousel_images))
                carouselImageCounters[{{ $index }}] = {{ count($section->carousel_images) }};
            @else
                carouselImageCounters[{{ $index }}] = 0;
            @endif
        @endforeach

        function removeSection(button) {
            button.closest('.section-item').remove();
            renumberSections();
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

        function collapseAllSectionsInitial() {
            document.querySelectorAll('#sections-container .section-item').forEach(section => {
                const body = section.querySelector('.card-body');
                const icon = section.querySelector('.section-move-controls button[onclick^="toggleSectionBody"] i');
                if (body) body.style.display = 'none';
                if (icon) {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                }
            });
        }

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

        function addSection() {
            const container = document.getElementById('sections-container');
            const sectionDiv = document.createElement('div');
            sectionDiv.className = 'card mb-3 section-item';
            sectionDiv.innerHTML = `
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Section ${sectionCounter + 1} <span class="text-muted section-label ms-2">(Not set)</span></h5>
                <div class="btn-group btn-group-sm section-move-controls">
                    <button type="button" class="btn btn-outline-secondary" onclick="toggleSectionBody(this)" title="Collapse/Expand">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="moveSection(this, 'up')" title="Move Up">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="moveSection(this, 'down')" title="Move Down">
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
                        <!-- Title: shown for all types -->
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
                            <label>Section Title</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][title]" placeholder="Countdown Title">
                        </div>
                        <div class="form-group type-field type-countdown" style="display: none;">
                            <label>Countdown Hours</label>
                            <input type="number" class="form-control" name="sections[${sectionCounter}][countdown_hours]" value="4" min="1">
                        </div>
                        <div class="form-group type-field type-countdown" style="display: none;">
                            <label>Repeat Countdown?</label>
                            <select class="form-control" name="sections[${sectionCounter}][countdown_repeat]">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                        
                        <!-- Video Section Fields -->
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
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="sections[${sectionCounter}][header_phone]" placeholder="01717171717">
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
                            <label>Button 1 Color</label>
                            <input type="color" class="form-control" name="sections[${sectionCounter}][header_button1_color]" value="#007bff">
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
                            <label>Button 2 Color</label>
                            <input type="color" class="form-control" name="sections[${sectionCounter}][header_button2_color]" value="#dc3545">
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
                        <div class="form-group type-field type-hero type-benefit type-testimonials type-feature_list type-pricing type-countdown type-video type-single_image type-image_carousel type-call_to_action type-header">
                            <label>Position</label>
                            <input type="number" class="form-control" name="sections[${sectionCounter}][position]" value="0" min="0">
                        </div>
                        <div class="form-group type-field type-hero type-benefit type-testimonials type-feature_list type-pricing type-countdown type-video type-single_image type-image_carousel type-call_to_action type-header">
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
        }

        $(document).on('change', '.section-type', function() {
            var selectedType = $(this).val();
            var section = $(this).closest('.section-item');

            // Hide all type-specific fields first
            section.find('.type-field').hide();

            // Show fields for the selected type
            if (selectedType) {
                var fieldsToShow = section.find('.type-' + selectedType);
                fieldsToShow.show();
            }
        });

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

        function formatSectionLabel(val) {
            if (!val) return 'Not set';
            return val.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        }

        // Initialize any existing sections on page load
        $(document).ready(function() {
            // Show fields for existing sections based on their type
            $('.section-item').each(function() {
                var section = $(this);
                var selectedType = section.find('.section-type').val();
                if (selectedType) {
                    section.find('.type-field').hide();
                    section.find('.type-' + selectedType).show();
                    const label = section.find('.section-label');
                    if (label.length) label.text('(' + formatSectionLabel(selectedType) + ')');
                }
            });
            
            // Trigger change event for any new sections that might be added
            $('.section-type').trigger('change');
            
            // Initialize Summernote on existing primary text fields
            setTimeout(function() {
                initializeSummernote();
            }, 200);

            // Ensure positions are sequential on load
            renumberSections();

            // Collapse all sections by default
            setTimeout(collapseAllSectionsInitial, 250);
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
                var fieldsToShow = section.find('.type-' + selectedType);
                fieldsToShow.show();
                const label = section.find('.section-label');
                if (label.length) label.text('(' + formatSectionLabel(selectedType) + ')');
                
                // Initialize Summernote on newly shown primary text fields
                setTimeout(function() {
                    initializeSummernote();
                }, 100);
            }
        });
    </script>
@endsection 
