@extends('layouts.master')

@section('title', 'Create Landing Page')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar for Section Templates -->
        <div class="col-md-3">
            <div class="card sticky-sidebar">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-puzzle-piece"></i> Section Templates
                    </h5>
                </div>
                <div class="card-body p-2">
                    <div class="section-templates">
                        <!-- Hero Section Template -->
                        <div class="template-item" draggable="true" data-section-type="hero">
                            <div class="template-content">
                                <i class="fas fa-star text-warning"></i>
                                <span>Hero Section</span>
                            </div>
                        </div>

                        <!-- Header Section Template -->
                        <div class="template-item" draggable="true" data-section-type="header">
                            <div class="template-content">
                                <i class="fas fa-heading text-primary"></i>
                                <span>Header</span>
                            </div>
                        </div>

                        <!-- Benefits Section Template -->
                        <div class="template-item" draggable="true" data-section-type="benefit">
                            <div class="template-content">
                                <i class="fas fa-check-circle text-success"></i>
                                <span>Benefits</span>
                            </div>
                        </div>

                        <!-- Testimonials Section Template -->
                        <div class="template-item" draggable="true" data-section-type="testimonials">
                            <div class="template-content">
                                <i class="fas fa-quote-left text-info"></i>
                                <span>Testimonials</span>
                            </div>
                        </div>

                        <!-- Feature List Section Template -->
                        <div class="template-item" draggable="true" data-section-type="feature_list">
                            <div class="template-content">
                                <i class="fas fa-list text-secondary"></i>
                                <span>Feature List</span>
                            </div>
                        </div>

                        <!-- Pricing Section Template -->
                        <div class="template-item" draggable="true" data-section-type="pricing">
                            <div class="template-content">
                                <i class="fas fa-tags text-danger"></i>
                                <span>Pricing</span>
                            </div>
                        </div>

                        <!-- Countdown Section Template -->
                        <div class="template-item" draggable="true" data-section-type="countdown">
                            <div class="template-content">
                                <i class="fas fa-clock text-warning"></i>
                                <span>Countdown</span>
                            </div>
                        </div>

                        <!-- Video Section Template -->
                        <div class="template-item" draggable="true" data-section-type="video">
                            <div class="template-content">
                                <i class="fas fa-play-circle text-danger"></i>
                                <span>Video</span>
                            </div>
                        </div>

                        <!-- Single Image Section Template -->
                        <div class="template-item" draggable="true" data-section-type="single_image">
                            <div class="template-content">
                                <i class="fas fa-image text-success"></i>
                                <span>Single Image</span>
                            </div>
                        </div>

                        <!-- Image Carousel Section Template -->
                        <div class="template-item" draggable="true" data-section-type="image_carousel">
                            <div class="template-content">
                                <i class="fas fa-images text-info"></i>
                                <span>Image Carousel</span>
                            </div>
                        </div>

                        <!-- Call to Action Section Template -->
                        <div class="template-item" draggable="true" data-section-type="call_to_action">
                            <div class="template-content">
                                <i class="fas fa-phone text-primary"></i>
                                <span>Call to Action</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Landing Page</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-outline-primary me-2" onclick="toggleSettingsPanel()">
                            <i class="fas fa-cog"></i> Settings
                        </button>
                        <a href="{{ route('admin.landing-pages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Settings Panel (Hidden by default) -->
                    <div id="settings-panel" class="settings-panel" style="display: none;">
                        <div class="settings-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cog"></i> Page Settings
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleSettingsPanel()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Basic Information -->
                        <div class="settings-section">
                            <h6 class="settings-section-title" data-bs-toggle="collapse" data-bs-target="#basic-info-collapse" aria-expanded="true">
                                <i class="fas fa-info-circle"></i> Basic Information
                                <i class="fas fa-chevron-down collapse-icon"></i>
                            </h6>
                            <div class="collapse show" id="basic-info-collapse">
                                <div class="settings-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="title">Page Title *</label>
                                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                    id="title" name="title" value="{{ old('title') }}" required>
                                                @error('title')
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

                                            <div class="form-group">
                                                <label for="position">Display Position</label>
                                                <input type="number"
                                                    class="form-control @error('position') is-invalid @enderror" id="position"
                                                    name="position" value="{{ old('position', 0) }}" min="0">
                                                <small class="form-text text-muted">Lower numbers appear first</small>
                                                @error('position')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="status"
                                                        name="status" value="1" {{ old('status', true) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="status">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Color Settings</h6>
                                            <div class="form-group">
                                                <label for="primary_color">Primary Color</label>
                                                <input type="color" class="form-control @error('primary_color') is-invalid @enderror"
                                                    id="primary_color" name="primary_color" value="{{ old('primary_color', '#007bff') }}">
                                                <small class="form-text text-muted">Main brand color for headings and primary elements</small>
                                                @error('primary_color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="secondary_color">Secondary Color</label>
                                                <input type="color" class="form-control @error('secondary_color') is-invalid @enderror"
                                                    id="secondary_color" name="secondary_color" value="{{ old('secondary_color', '#6c757d') }}">
                                                <small class="form-text text-muted">Secondary color for sub-headings and supporting elements</small>
                                                @error('secondary_color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="accent_color">Accent Color</label>
                                                <input type="color" class="form-control @error('accent_color') is-invalid @enderror"
                                                    id="accent_color" name="accent_color" value="{{ old('accent_color', '#28a745') }}">
                                                <small class="form-text text-muted">Highlight color for special elements and call-to-actions</small>
                                                @error('accent_color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="order_button_color">Order Now Button Color</label>
                                                <input type="color" class="form-control @error('order_button_color') is-invalid @enderror"
                                                    id="order_button_color" name="order_button_color" value="{{ old('order_button_color', '#dc3545') }}">
                                                <small class="form-text text-muted">Color for the main order/CTA buttons</small>
                                                @error('order_button_color')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button & Form Settings -->
                        <div class="settings-section">
                            <h6 class="settings-section-title" data-bs-toggle="collapse" data-bs-target="#button-form-collapse" aria-expanded="true">
                                <i class="fas fa-mouse-pointer"></i> Button & Form Settings
                                <i class="fas fa-chevron-down collapse-icon"></i>
                            </h6>
                            <div class="collapse show" id="button-form-collapse">
                                <div class="settings-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="order_button_text">Order Button Text</label>
                                                <input type="text"
                                                    class="form-control @error('order_button_text') is-invalid @enderror"
                                                    id="order_button_text" name="order_button_text"
                                                    value="{{ old('order_button_text', 'এখনই কিনুন') }}">
                                                @error('order_button_text')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="order_button_url">Order Button URL</label>
                                                <input type="text"
                                                    class="form-control @error('order_button_url') is-invalid @enderror"
                                                    id="order_button_url" name="order_button_url"
                                                    value="{{ old('order_button_url', '#order-section') }}">
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
                                                    value="{{ old('order_form_title', 'অর্ডার করুন') }}">
                                                @error('order_form_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="order_place_button_text">Order Place Button Text</label>
                                                <input type="text"
                                                    class="form-control @error('order_place_button_text') is-invalid @enderror"
                                                    id="order_place_button_text" name="order_place_button_text"
                                                    value="{{ old('order_place_button_text', 'অর্ডার Confirm করুন') }}">
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

                    <form action="{{ route('admin.landing-pages.store') }}" method="POST"
                        enctype="multipart/form-data" id="landingPageForm">
                        @csrf


                        <!-- Sections -->
                        <div class="row">
                            <div class="col-12">
                                <h4>Content Sections</h4>
                                <p class="text-muted">Add features, reviews, and other content sections to your landing
                                    page.</p>

                                <div id="sections-container" class="sections-container">
                                    <!-- Sections will be added here dynamically -->
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Landing Page
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

    /* Drag and Drop Styles */
    .section-item {
        cursor: move;
        transition: all 0.3s ease;
    }

    .section-item:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .section-item.sortable-ghost {
        opacity: 0.4;
    }

    .section-item.sortable-chosen {
        transform: rotate(2deg);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .drag-handle {
        position: absolute;
        top: 10px;
        right: 10px;
        color: #6c757d;
        font-size: 16px;
        cursor: move;
        z-index: 10;
        transition: all 0.2s ease;
    }

    .drag-handle:hover {
        color: #197A94;
        transform: scale(1.1);
    }

    .section-item .card-header {
        position: relative;
        padding-right: 50px;
    }

    .sections-container {
        min-height: 100px;
        border: 2px dashed transparent;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .sections-container:empty::after {
        content: "Drag sections from the sidebar to build your landing page";
        display: flex;
        align-items: center;
        justify-content: center;
        height: 120px;
        color: #6c757d;
        font-style: italic;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background: #f8f9fa;
    }

    .sortable-placeholder {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 0.375rem;
        height: 60px;
        margin: 10px 0;
    }

    /* Sidebar Template Styles */
    .section-templates {
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-height: calc(100vh - 200px);
        overflow-y: auto;
        padding-right: 5px;
    }

    /* Sticky Sidebar */
    .sticky-sidebar {
        position: sticky;
        top: 20px;
        z-index: 1000;
    }

    .sticky-sidebar .card {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border: 1px solid #dee2e6;
        transition: box-shadow 0.3s ease;
    }

    .sticky-sidebar .card:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
    }

    .sticky-sidebar .card-header {
        background: linear-gradient(135deg, #197A94 0%, #10B981 100%);
        color: white;
        border-bottom: none;
        padding: 15px;
    }

    .sticky-sidebar .card-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .sticky-sidebar .card-body {
        padding: 15px;
        background: #f8f9fa;
    }

    /* Custom scrollbar for sidebar */
    .section-templates::-webkit-scrollbar {
        width: 6px;
    }

    .section-templates::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .section-templates::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .section-templates::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    .template-item {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 12px;
        cursor: move;
        transition: all 0.2s ease;
        user-select: none;
    }

    .template-item:hover {
        border-color: #197A94;
        box-shadow: 0 2px 4px rgba(0, 123, 255, 0.1);
        transform: translateY(-1px);
    }

    .template-item:active {
        transform: scale(0.98);
    }

    .template-content {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 500;
    }

    .template-content i {
        width: 20px;
        text-align: center;
    }

    /* Drag from sidebar styles */
    .template-item.dragging {
        opacity: 0.5;
        transform: rotate(5deg);
    }

    .sections-container.drag-over {
        background: linear-gradient(45deg, #f8f9fa 25%, transparent 25%),
            linear-gradient(-45deg, #f8f9fa 25%, transparent 25%),
            linear-gradient(45deg, transparent 75%, #f8f9fa 75%),
            linear-gradient(-45deg, transparent 75%, #f8f9fa 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        border: 2px dashed #197A94;
        border-radius: 8px;
        min-height: 120px;
    }

    .drop-zone {
        min-height: 80px;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-style: italic;
        margin: 10px 0;
        transition: all 0.3s ease;
    }

    .drop-zone.drag-over {
        border-color: #197A94;
        background-color: rgba(0, 123, 255, 0.05);
        color: #197A94;
    }

    /* Settings Panel Styles */
    .settings-panel {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .settings-header {
        background: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .settings-section {
        border-bottom: 1px solid #f0f0f0;
    }

    .settings-section:last-child {
        border-bottom: none;
    }

    .settings-section-title {
        background: #f8f9fa;
        padding: 12px 20px;
        margin: 0;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        font-weight: 600;
        color: #495057;
        border-bottom: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }

    .settings-section-title:hover {
        background: #e9ecef;
        color: #197A94;
    }

    .settings-section-title i:first-child {
        margin-right: 8px;
        width: 16px;
        text-align: center;
    }

    .collapse-icon {
        transition: transform 0.2s ease;
        font-size: 12px;
    }

    .settings-section-title[aria-expanded="true"] .collapse-icon {
        transform: rotate(180deg);
    }

    .settings-content {
        padding: 20px;
        background: #fff;
    }

    .settings-content .form-group {
        margin-bottom: 15px;
    }

    .settings-content .form-group:last-child {
        margin-bottom: 0;
    }

    /* Sidebar responsive */
    @media (max-width: 768px) {
        .sticky-sidebar {
            position: relative;
            top: auto;
        }

        .section-templates {
            flex-direction: row;
            flex-wrap: wrap;
            gap: 6px;
            max-height: none;
            overflow-y: visible;
        }

        .template-item {
            flex: 1;
            min-width: calc(50% - 3px);
            padding: 8px;
        }

        .template-content {
            font-size: 12px;
            flex-direction: column;
            text-align: center;
            gap: 4px;
        }

        .settings-content {
            padding: 15px;
        }

        .settings-header {
            padding: 12px 15px;
        }

        .settings-section-title {
            padding: 10px 15px;
        }
    }

    @media (max-width: 992px) and (min-width: 769px) {
        .section-templates {
            max-height: calc(100vh - 150px);
        }
    }
</style>
@endsection

@section('scripts')
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<!-- SortableJS for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    let sectionCounter = 0;

    // Toggle settings panel visibility
    function toggleSettingsPanel() {
        const panel = document.getElementById('settings-panel');
        const isVisible = panel.style.display !== 'none';

        if (isVisible) {
            panel.style.display = 'none';
        } else {
            panel.style.display = 'block';
        }
    }

    function addSection() {
        const container = document.getElementById('sections-container');
        const sectionDiv = document.createElement('div');
        sectionDiv.className = 'card mb-3 section-item';
        sectionDiv.innerHTML = `
            <div class="card-header">
                <h5 class="mb-0">Section ${sectionCounter + 1}</h5>
                <div class="drag-handle">
                    <i class="fas fa-grip-vertical"></i>
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

        // Re-initialize sortable to include the new section
        initializeSortable();

        // Update positions after adding new section
        updateSectionPositions();
    }

    function removeSection(button) {
        button.closest('.card').remove();
        // Update positions after removing a section
        updateSectionPositions();
    }

    $(document).on('change', '.section-type', function() {
        var selectedType = $(this).val();
        var section = $(this).closest('.section-item');

        // Hide all type-specific fields first
        section.find('.type-field').hide();

        // Show fields for the selected type
        if (selectedType) {
            section.find('.type-' + selectedType).show();
        }
    });

    // Initialize any existing sections on page load
    $(document).ready(function() {
        $('.section-type').trigger('change');

        // Initialize Summernote on existing primary text fields
        initializeSummernote();

        // Initialize drag and drop functionality
        initializeSortable();

        // Initialize sidebar drag functionality
        initializeSidebarDrag();
    });

    // Initialize SortableJS for drag and drop
    function initializeSortable() {
        const container = document.getElementById('sections-container');
        if (container) {
            Sortable.create(container, {
                handle: '.drag-handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                placeholder: 'sortable-placeholder',
                onEnd: function(evt) {
                    // Update position values when sections are reordered
                    updateSectionPositions();
                }
            });
        }
    }

    // Update position values for all sections based on their current order
    function updateSectionPositions() {
        const sections = document.querySelectorAll('#sections-container .section-item');
        sections.forEach((section, index) => {
            const positionInput = section.querySelector('input[name*="[position]"]');
            if (positionInput) {
                positionInput.value = index;
            }
        });
    }

    // Initialize sidebar drag functionality
    function initializeSidebarDrag() {
        const templateItems = document.querySelectorAll('.template-item');
        const sectionsContainer = document.getElementById('sections-container');

        templateItems.forEach(item => {
            item.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', this.dataset.sectionType);
                this.classList.add('dragging');
            });

            item.addEventListener('dragend', function(e) {
                this.classList.remove('dragging');
            });
        });

        // Handle drop events on sections container
        sectionsContainer.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('drag-over');
        });

        sectionsContainer.addEventListener('dragleave', function(e) {
            if (!this.contains(e.relatedTarget)) {
                this.classList.remove('drag-over');
            }
        });

        sectionsContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('drag-over');

            const sectionType = e.dataTransfer.getData('text/plain');
            if (sectionType) {
                addSectionFromTemplate(sectionType);
            }
        });
    }

    // Add section from sidebar template
    function addSectionFromTemplate(sectionType) {
        // Use the existing addSection function but pre-select the section type
        addSection();

        // Find the newly added section and pre-select the section type
        const sections = document.querySelectorAll('#sections-container .section-item');
        const lastSection = sections[sections.length - 1];
        const sectionTypeSelect = lastSection.querySelector('.section-type');

        if (sectionTypeSelect) {
            sectionTypeSelect.value = sectionType;
            $(sectionTypeSelect).trigger('change');
        }
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
</script>
@endsection