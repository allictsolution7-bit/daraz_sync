@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Typography & Layout Animation */
        .category-add-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            padding: 1.5rem 0;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glassmorphism Card styling */
        .premium-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .premium-card:hover {
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.25);
        }

        /* Gradient Header area */
        .gradient-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 50%, #4f46e5 100%);
            padding: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .gradient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        }

        .gradient-header-title {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .gradient-header-subtitle {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            margin-top: 0.35rem;
            margin-bottom: 0;
        }

        .premium-card-body {
            padding: 2rem;
        }

        .premium-card-header-simple {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 2rem;
            background: #f8fafc;
        }

        .premium-card-title-simple {
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        /* Form Group Elements */
        .form-group label {
            font-weight: 700;
            color: #475569;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.6rem;
            display: block;
        }

        .form-control-premium {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: #0f172a;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
            width: 100%;
        }

        .form-control-premium:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
            outline: none;
        }

        .form-control-premium.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.08);
        }

        .invalid-feedback {
            color: #ef4444;
            font-size: 0.825rem;
            font-weight: 600;
            margin-top: 0.35rem;
        }

        /* Buttons styling */
        .btn-premium {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            color: white !important;
            box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.4);
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #0284c7 0%, #1d4ed8 100%);
            box-shadow: 0 10px 24px -6px rgba(37, 99, 235, 0.5);
            transform: translateY(-1px);
        }

        /* Premium Nav Tabs styling */
        .premium-nav-tabs {
            border: none;
            background: #f1f5f9;
            padding: 6px;
            border-radius: 16px;
            display: inline-flex;
            margin-bottom: 2rem;
            gap: 4px;
        }

        .premium-nav-tabs .nav-link {
            border: none !important;
            border-radius: 12px !important;
            color: #475569 !important;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.6rem 1.5rem;
            transition: all 0.2s ease;
        }

        .premium-nav-tabs .nav-link.active {
            background: #ffffff !important;
            color: #2563eb !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Category Tree Directory Styling */
        .tree-container {
            list-style: none;
            padding-left: 0;
        }

        .tree-node-parent {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }

        .tree-node-parent:hover {
            border-color: rgba(37, 99, 235, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }

        .tree-node-parent-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .tree-node-parent-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .tree-parent-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-weight: 700;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .tree-node-title {
            font-weight: 800;
            color: #0f172a;
            font-size: 1.05rem;
            margin: 0;
        }

        .tree-node-meta {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 0.15rem;
        }

        .tree-child-list {
            list-style: none;
            padding-left: 1.5rem;
            margin-top: 1rem;
            border-left: 2px dashed #cbd5e1;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .tree-node-child {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid #f1f5f9;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            position: relative;
        }

        .tree-node-child::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 50%;
            width: 1.5rem;
            border-top: 2px dashed #cbd5e1;
        }

        .tree-child-title {
            font-weight: 700;
            color: #334155;
            font-size: 0.95rem;
        }

        /* Parent Grid Card styling */
        .category-grid-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .category-grid-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.04);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .category-grid-header {
            position: relative;
            height: 100px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(37, 99, 235, 0.05) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-grid-img-wrapper {
            position: absolute;
            bottom: -20px;
            left: 20px;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            border: 2px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .category-grid-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-grid-body {
            padding: 1.75rem 1rem 1rem 1rem;
            flex-grow: 1;
        }

        .category-grid-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.4rem 0;
        }

        .category-grid-desc {
            font-size: 0.825rem;
            color: #64748b;
            line-height: 1.4;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 36px;
        }

        .category-grid-footer {
            border-top: 1px solid #f1f5f9;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
        }

        /* Table actions */
        .btn-table-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-table-action:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-table-action-view:hover { color: #0ea5e9; background: rgba(14, 165, 233, 0.06); border-color: rgba(14, 165, 233, 0.2); }
        .btn-table-action-edit:hover { color: #2563eb; background: rgba(37, 99, 235, 0.06); border-color: rgba(37, 99, 235, 0.2); }
        .btn-table-action-delete:hover { color: #ef4444; background: rgba(239, 68, 68, 0.06); border-color: rgba(239, 68, 68, 0.2); }

        .category-badge {
            background-color: rgba(14, 165, 233, 0.06);
            color: #0369a1;
            border: 1px solid rgba(14, 165, 233, 0.12);
            padding: 0.3rem 0.75rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Custom DataTables wrappers overrides */
        .dataTables_wrapper .dataTables_length select {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.35rem 1.75rem 0.35rem 0.75rem;
            color: #0f172a;
            font-weight: 600;
            outline: none;
            transition: all 0.2s ease;
        }

        .dataTables_wrapper .dataTables_filter input {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.45rem 1rem;
            color: #0f172a;
            font-weight: 500;
            outline: none;
            transition: all 0.2s ease;
            margin-left: 0.5rem;
            min-width: 240px;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.85rem !important;
            margin-left: 0.25rem !important;
            border-radius: 10px !important;
            font-weight: 700 !important;
            font-size: 0.85rem !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            color: #475569 !important;
            transition: all 0.2s ease !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%) !important;
            color: white !important;
            border-color: transparent !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2) !important;
        }

        /* Modernized Table styling */
        .premium-table-container {
            overflow-x: auto;
        }

        .premium-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .premium-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            padding: 1.125rem 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            border-top: none;
        }

        .premium-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #334155;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .premium-table tr:last-child td {
            border-bottom: none;
        }

        .premium-table tbody tr:hover {
            background-color: rgba(248, 250, 252, 0.8);
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid category-add-wrapper">
        <div class="row">
            <!-- Add Category Form -->
            <div class="col-md-6 mb-4">
                <div class="premium-card">
                    <div class="gradient-header">
                        <h1 class="gradient-header-title">Add Parent Category</h1>
                        <p class="gradient-header-subtitle">Create a top-level classification category for blog posts.</p>
                    </div>

                    <div class="premium-card-body">
                        <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Name -->
                            <div class="form-group mb-4">
                                <label for="name">Category Name</label>
                                <input type="text" class="form-control-premium @error('name') is-invalid @enderror"
                                    id="name" name="name" placeholder="e.g., Tech Insights, Lifestyle" value="{{ old('name') }}" required autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div class="form-group mb-4">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control-premium @error('slug') is-invalid @enderror"
                                    id="slug" name="slug" placeholder="auto-generated-slug" value="{{ old('slug') }}" required autocomplete="off">
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="form-text" id="slug-status" style="font-weight:600;"></small>
                                    <span class="text-danger small" id="slug-error" style="font-weight:600;"></span>
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-4">
                                <label for="description">Description</label>
                                <textarea class="form-control-premium @error('description') is-invalid @enderror" id="description" name="description"
                                    placeholder="Write a brief category description..." rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Meta Title -->
                            <div class="form-group mb-4">
                                <label for="meta_title">Meta Title (SEO)</label>
                                <input type="text" class="form-control-premium @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title" placeholder="Meta Title" value="{{ old('meta_title') }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Meta Description -->
                            <div class="form-group mb-4">
                                <label for="meta_description">Meta Description (SEO)</label>
                                <textarea class="form-control-premium @error('meta_description') is-invalid @enderror" id="meta_description"
                                    name="meta_description" placeholder="Write meta description here..." rows="3">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Image file -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="image">Category Image</label>
                                        <input type="file" class="form-control-premium @error('image') is-invalid @enderror"
                                            id="image" name="image">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image Alt -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="image_alt">Image Alt Text (SEO)</label>
                                        <input type="text" class="form-control-premium @error('image_alt') is-invalid @enderror"
                                            id="image_alt" name="image_alt" placeholder="Describe image content" value="{{ old('image_alt') }}">
                                        @error('image_alt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Canonical URL -->
                            <div class="form-group mb-4">
                                <label for="canonical_url">Canonical URL</label>
                                <input type="url" class="form-control-premium @error('canonical_url') is-invalid @enderror"
                                    id="canonical_url" name="canonical_url" placeholder="https://example.com/canonical-url" value="{{ old('canonical_url') }}">
                                @error('canonical_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Is Featured -->
                            <div class="form-group mb-4">
                                <label for="is_featured">Is Featured</label>
                                <select class="form-control-premium @error('is_featured') is-invalid @enderror" id="is_featured"
                                    name="is_featured">
                                    <option value="0" {{ old('is_featured') == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('is_featured') == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('is_featured')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group pt-2">
                                <button type="submit" class="btn-premium btn-premium-primary">
                                    <i class="fas fa-save"></i> Save Parent Category
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add SubCategory Form -->
            <div class="col-md-6 mb-4">
                <div class="premium-card">
                    <div class="gradient-header">
                        <h1 class="gradient-header-title">Add SubCategory</h1>
                        <p class="gradient-header-subtitle">Create a nested child subcategory under a parent category.</p>
                    </div>

                    <div class="premium-card-body">
                        <form action="{{ route('admin.postsubcategory.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Parent Category selection -->
                            <div class="form-group mb-4">
                                <label for="post_category_id">Parent Category</label>
                                <select class="form-control-premium @error('post_category_id') is-invalid @enderror" id="post_category_id"
                                    name="post_category_id" required>
                                    <option value="">Select Parent Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('post_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('post_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sub Category Name -->
                            <div class="form-group mb-4">
                                <label for="subname">Subcategory Name</label>
                                <input type="text" class="form-control-premium @error('name') is-invalid @enderror"
                                    id="subname" name="name" placeholder="e.g., Gadgets, Travel Tips" value="{{ old('name') }}" required autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div class="form-group mb-4">
                                <label for="subslug">Slug</label>
                                <input type="text" class="form-control-premium @error('slug') is-invalid @enderror"
                                    id="subslug" name="slug" placeholder="auto-generated-slug" value="{{ old('slug') }}" required autocomplete="off">
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <small class="form-text" id="subslug-status" style="font-weight:600;"></small>
                                    <span class="text-danger small" id="subslug-error" style="font-weight:600;"></span>
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="form-group mb-4">
                                <label for="subdescription">Description</label>
                                <textarea class="form-control-premium @error('description') is-invalid @enderror" id="subdescription" name="description"
                                    placeholder="Write a brief subcategory description..." rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Meta Title -->
                            <div class="form-group mb-4">
                                <label for="sub_meta_title">Meta Title (SEO)</label>
                                <input type="text" class="form-control-premium @error('meta_title') is-invalid @enderror"
                                    id="sub_meta_title" name="meta_title" placeholder="Meta Title" value="{{ old('meta_title') }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Meta Description -->
                            <div class="form-group mb-4">
                                <label for="sub_meta_description">Meta Description (SEO)</label>
                                <textarea class="form-control-premium @error('meta_description') is-invalid @enderror" id="sub_meta_description"
                                    name="meta_description" placeholder="Write meta description here..." rows="3">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Image file -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="subimage">Category Image</label>
                                        <input type="file" class="form-control-premium @error('image') is-invalid @enderror"
                                            id="subimage" name="image">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Image Alt -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="subimage_alt">Image Alt Text (SEO)</label>
                                        <input type="text" class="form-control-premium @error('image_alt') is-invalid @enderror"
                                            id="subimage_alt" name="image_alt" placeholder="Describe image content" value="{{ old('image_alt') }}">
                                        @error('image_alt')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Canonical URL -->
                            <div class="form-group mb-4">
                                <label for="sub_canonical_url">Canonical URL</label>
                                <input type="url" class="form-control-premium @error('canonical_url') is-invalid @enderror"
                                    id="sub_canonical_url" name="canonical_url" placeholder="https://example.com/canonical-url" value="{{ old('canonical_url') }}">
                                @error('canonical_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group pt-2">
                                <button type="submit" class="btn-premium btn-premium-primary">
                                    <i class="fas fa-save"></i> Save SubCategory
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TABBED CATALOG SECTION -->
            <div class="col-md-12">
                <!-- Navigation Tabs -->
                <ul class="nav premium-nav-tabs" id="catalogTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tree-tab" data-bs-toggle="tab" data-bs-target="#tree-pane" type="button" role="tab">
                            <i class="fas fa-network-wired mr-1"></i> Interactive Tree
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="parents-tab" data-bs-toggle="tab" data-bs-target="#parents-pane" type="button" role="tab">
                            <i class="fas fa-folder mr-1"></i> Parents Grid
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="subs-tab" data-bs-toggle="tab" data-bs-target="#subs-pane" type="button" role="tab">
                            <i class="fas fa-folder-open mr-1"></i> Subcategories List
                        </button>
                    </li>
                </ul>

                <!-- Tab Panes Content -->
                <div class="tab-content" id="catalogTabsContent">
                    <!-- TAB 1: INTERACTIVE TREE -->
                    <div class="tab-pane fade show active" id="tree-pane" role="tabpanel">
                        <div class="premium-card">
                            <div class="premium-card-header-simple">
                                <h6 class="premium-card-title-simple">Interactive Directory Tree</h6>
                            </div>
                            <div class="premium-card-body">
                                <ul class="tree-container">
                                    @forelse ($categories as $category)
                                        <li class="tree-node-parent">
                                            <div class="tree-node-parent-header">
                                                <div class="tree-node-parent-info">
                                                    <div class="tree-parent-avatar">
                                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <h5 class="tree-node-title">{{ $category->name }}</h5>
                                                        <p class="tree-node-meta">
                                                            <code>/{{ $category->slug }}</code> • 
                                                            {{ $category->postsubcategories->count() }} subcategories
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.category.view', $category->id) }}" class="btn-table-action btn-table-action-view" title="View category"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.category.edit', $category->id) }}" class="btn-table-action btn-table-action-edit" title="Edit category"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('admin.category.destroy', $category->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-table-action btn-table-action-delete" onclick="return confirm('Are you sure you want to delete this category? This will also delete all subcategories.')" title="Delete category">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            @if ($category->postsubcategories->isNotEmpty())
                                                <ul class="tree-child-list">
                                                    @foreach ($category->postsubcategories as $subcategory)
                                                        <li class="tree-node-child">
                                                            <div>
                                                                <span class="tree-child-title">{{ $subcategory->name }}</span>
                                                                <span class="text-muted small ml-2"><code>/{{ $subcategory->slug }}</code></span>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <a href="{{ route('admin.postsubcategory.view', $subcategory->id) }}" class="btn-table-action btn-table-action-view" title="View subcategory"><i class="fas fa-eye"></i></a>
                                                                <a href="{{ route('admin.postsubcategory.edit', $subcategory->id) }}" class="btn-table-action btn-table-action-edit" title="Edit subcategory"><i class="fas fa-edit"></i></a>
                                                                <form action="{{ route('admin.postsubcategory.destroy', $subcategory->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn-table-action btn-table-action-delete" onclick="return confirm('Are you sure you want to delete this subcategory?')" title="Delete subcategory">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @empty
                                        <li class="text-center py-5 text-muted">No categories created yet.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: PARENTS GRID -->
                    <div class="tab-pane fade" id="parents-pane" role="tabpanel">
                        <div class="row">
                            @forelse ($categories as $category)
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <div class="category-grid-card">
                                        <div class="category-grid-header">
                                            <div class="category-grid-img-wrapper">
                                                @if ($category->image)
                                                    <img src="{{ asset($category->image) }}" alt="{{ $category->image_alt }}" class="category-grid-img">
                                                @else
                                                    <span class="text-primary font-weight-bold" style="font-size: 1.5rem;">{{ strtoupper(substr($category->name, 0, 1)) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="category-grid-body">
                                            <h4 class="category-grid-title">{{ $category->name }}</h4>
                                            <p class="text-muted small mb-2"><code>/{{ $category->slug }}</code></p>
                                            <p class="category-grid-desc">{{ $category->description ?? 'No description provided.' }}</p>
                                            <span class="badge bg-light text-primary pill-badge px-3 py-2" style="font-weight: 700;">
                                                <i class="fas fa-folder-open mr-1"></i> {{ $category->postsubcategories->count() }} Subcategories
                                            </span>
                                        </div>
                                        <div class="category-grid-footer">
                                            <span class="small text-muted" style="font-weight: 600;">ID: #{{ $category->id }}</span>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.category.view', $category->id) }}" class="btn-table-action btn-table-action-view"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.category.edit', $category->id) }}" class="btn-table-action btn-table-action-edit"><i class="fas fa-edit"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5 text-muted">No parent categories available.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- TAB 3: SUBCATEGORIES LIST -->
                    <div class="tab-pane fade" id="subs-pane" role="tabpanel">
                        <div class="premium-card">
                            <div class="premium-card-header-simple">
                                <h6 class="premium-card-title-simple">Subcategories Listing</h6>
                            </div>
                            <div class="premium-card-body">
                                <div class="premium-table-container">
                                    <table class="premium-table" id="subcategoriesTable" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width: 80px;">#</th>
                                                <th>Subcategory Name</th>
                                                <th>Parent Category Link</th>
                                                <th>Slug URL</th>
                                                <th>Meta Title</th>
                                                <th style="width: 150px; text-align: right; padding-right: 1.5rem;">Action Tools</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $sCounter = 1; @endphp
                                            @foreach ($categories as $category)
                                                @foreach ($category->postsubcategories as $subcategory)
                                                    <tr>
                                                        <td><span class="post-id-badge">#{{ $sCounter++ }}</span></td>
                                                        <td><strong>{{ $subcategory->name }}</strong></td>
                                                        <td>
                                                            <span class="category-badge">
                                                                <i class="fas fa-folder"></i> {{ $category->name }}
                                                            </span>
                                                        </td>
                                                        <td><code class="small text-muted">{{ $subcategory->slug }}</code></td>
                                                        <td>{{ $subcategory->meta_title ?? 'N/A' }}</td>
                                                        <td style="text-align: right; padding-right: 1.5rem; white-space: nowrap;">
                                                            <a href="{{ route('admin.postsubcategory.view', $subcategory->id) }}" class="btn-table-action btn-table-action-view"><i class="fas fa-eye"></i></a>
                                                            <a href="{{ route('admin.postsubcategory.edit', $subcategory->id) }}" class="btn-table-action btn-table-action-edit"><i class="fas fa-edit"></i></a>
                                                            <form action="{{ route('admin.postsubcategory.destroy', $subcategory->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-table-action btn-table-action-delete" onclick="return confirm('Are you sure you want to delete this subcategory?')">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#subcategoriesTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search subcategories...",
                    lengthMenu: "Show _MENU_ items",
                    paginate: {
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                }
            });
        });
    </script>

    <script>
        function generateSlug(inputId, outputId, validateUrl) {
            let title = document.getElementById(inputId).value;
            let slugField = document.getElementById(outputId);

            if (title.trim() !== '') {
                let slug = title.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with dashes
                    .replace(/-+/g, '-'); // Remove duplicate dashes

                slugField.value = slug;
                validateSlug(slug, outputId, validateUrl);
            }
        }

        function validateSlug(slug, outputId, validateUrl) {
            let status = document.getElementById(outputId + '-status');
            let error = document.getElementById(outputId + '-error');

            $.ajax({
                url: validateUrl,
                type: "GET",
                data: {
                    slug: slug
                },
                success: function(response) {
                    if (response.valid) {
                        status.textContent = 'Slug is available.';
                        status.classList.remove('text-danger');
                        status.classList.add('text-success');
                        error.textContent = '';
                    } else {
                        status.textContent = '';
                        error.textContent = 'This slug is already taken. Suggested: ' + response.slug;
                        document.getElementById(outputId).value = response.slug;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error validating slug:', error);
                }
            });
        }

        document.getElementById('name').addEventListener('input', function() {
            generateSlug('name', 'slug', "{{ url('/postcategory/validate-slug') }}");
        });

        document.getElementById('slug').addEventListener('input', function() {
            validateSlug(this.value, 'slug', "{{ url('/postcategory/validate-slug') }}");
        });

        document.getElementById('subname').addEventListener('input', function() {
            generateSlug('subname', 'subslug', "{{ url('/postsubcategory/validate-slug') }}");
        });

        document.getElementById('subslug').addEventListener('input', function() {
            validateSlug(this.value, 'subslug', "{{ url('/postsubcategory/validate-slug') }}");
        });
    </script>
@endsection
