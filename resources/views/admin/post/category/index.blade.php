@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Typography & Layout Animation */
        .category-dashboard-wrapper {
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
            margin-bottom: 2.5rem;
            padding: 2rem;
        }

        /* Gradient Header area */
        .gradient-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 50%, #4f46e5 100%);
            padding: 2.5rem;
            border-radius: 24px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
            box-shadow: 0 20px 40px -15px rgba(37, 99, 235, 0.3);
        }

        .gradient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        }

        .gradient-header-title {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .gradient-header-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            margin-top: 0.5rem;
            margin-bottom: 0;
        }

        /* Stats Cards */
        .stat-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.08);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .stat-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon-primary {
            background: rgba(37, 99, 235, 0.08);
            color: #2563eb;
        }

        .stat-icon-success {
            background: rgba(16, 185, 129, 0.08);
            color: #10b981;
        }

        .stat-icon-info {
            background: rgba(14, 165, 233, 0.08);
            color: #0ea5e9;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0.25rem 0 0 0;
            line-height: 1.2;
        }

        /* Action Buttons */
        .btn-premium-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            border: none;
            background: #ffffff;
            color: #2563eb;
        }

        .btn-premium-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
            color: #1d4ed8;
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            color: white !important;
            box-shadow: 0 8px 20px -6px rgba(37, 99, 235, 0.5);
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #0284c7 0%, #1d4ed8 100%);
            box-shadow: 0 10px 24px -6px rgba(37, 99, 235, 0.6);
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
            height: 130px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(37, 99, 235, 0.05) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .category-grid-img-wrapper {
            position: absolute;
            bottom: -24px;
            left: 20px;
            width: 64px;
            height: 64px;
            border-radius: 16px;
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
            padding: 2.25rem 1.25rem 1.25rem 1.25rem;
            flex-grow: 1;
        }

        .category-grid-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
        }

        .category-grid-desc {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 1.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 42px;
        }

        .category-grid-footer {
            border-top: 1px solid #f1f5f9;
            padding: 1rem 1.25rem;
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

        /* DataTables styles overrides */
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
    <div class="container-fluid category-dashboard-wrapper">
        <!-- Premium Gradient Header -->
        <div class="gradient-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                <div>
                    <h1 class="gradient-header-title">Post Categories Catalog</h1>
                    <p class="gradient-header-subtitle">Manage, edit, monitor, and structure website article classification hierarchies.</p>
                </div>
                <div>
                    <a href="{{ route('admin.category.add') }}" class="btn-premium-action btn-premium-primary">
                        <i class="fas fa-plus"></i> Add Category / SubCategory
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Insights Counter Grid -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-primary">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div>
                        <p class="stat-label">Total Classifications</p>
                        <h3 class="stat-value">
                            {{ $categories->count() + $categories->flatMap->postsubcategories->count() }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-success">
                        <i class="fas fa-folder"></i>
                    </div>
                    <div>
                        <p class="stat-label">Parent Categories</p>
                        <h3 class="stat-value">{{ $categories->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-info">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <div>
                        <p class="stat-label">Subcategories</p>
                        <h3 class="stat-value">{{ $categories->flatMap->postsubcategories->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

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
@endsection
