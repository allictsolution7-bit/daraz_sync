@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Typography & Layout Animation */
        .post-dashboard-wrapper {
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
            padding: 2rem;
        }

        .premium-card:hover {
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.1), 0 1px 5px rgba(99, 102, 241, 0.03);
            border-color: rgba(99, 102, 241, 0.25);
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

        .gradient-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 10%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 75%);
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

        .premium-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .premium-table tbody tr:hover {
            background-color: rgba(248, 250, 252, 0.8);
        }

        .post-id-badge {
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            background: #f1f5f9;
            padding: 0.25rem 0.6rem;
            border-radius: 8px;
        }

        .post-title-text {
            font-weight: 700;
            color: #0f172a;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        /* Category and Subcategory Badges */
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

        .subcategory-badge {
            background-color: rgba(79, 70, 229, 0.06);
            color: #4338ca;
            border: 1px solid rgba(79, 70, 229, 0.12);
            padding: 0.3rem 0.75rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .date-badge {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 600;
        }

        /* Table Action Buttons */
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
            margin-right: 0.25rem;
        }

        .btn-table-action:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-table-action-view:hover {
            color: #0ea5e9;
            background: rgba(14, 165, 233, 0.06);
            border-color: rgba(14, 165, 233, 0.2);
        }

        .btn-table-action-edit:hover {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.06);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .btn-table-action-delete:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.06);
            border-color: rgba(239, 68, 68, 0.2);
        }

        /* DataTables Custom Styling Overrides */
        .dataTables_wrapper .dataTables_length {
            margin-bottom: 1.5rem;
        }

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

        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1.5rem;
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

        .dataTables_wrapper .dataTables_info {
            padding-top: 1.5rem;
            color: #64748b;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_paginate {
            padding-top: 1.5rem;
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

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important;
            color: #1e293b !important;
            border-color: #cbd5e1 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%) !important;
            color: white !important;
            border-color: transparent !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            color: #94a3b8 !important;
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            cursor: not-allowed !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid post-dashboard-wrapper">
        <!-- Premium Gradient Header -->
        <div class="gradient-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                <div>
                    <h1 class="gradient-header-title">Blog Posts Manager</h1>
                    <p class="gradient-header-subtitle">Create, monitor, categorize, and organize your publication posts.</p>
                </div>
                <div>
                    <a href="{{ route('admin.post.add') }}" class="btn-premium-action btn-premium-primary">
                        <i class="fas fa-plus"></i> Add New Post
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Insights Counter Grid -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-primary">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div>
                        <p class="stat-label">Total Publication Posts</p>
                        <h3 class="stat-value">{{ $posts->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-success">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div>
                        <p class="stat-label">Active Categories</p>
                        <h3 class="stat-value">{{ $posts->pluck('postcategory.name')->filter()->unique()->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-info">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div>
                        <p class="stat-label">Active Subcategories</p>
                        <h3 class="stat-value">{{ $posts->pluck('postsubcategory.name')->filter()->unique()->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Listing Premium Card -->
        <div class="premium-card">
            <div class="premium-table-container">
                <table class="premium-table" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Post Title</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Date Published</th>
                            <th style="width: 150px; text-align: right; padding-right: 2rem;">Action Tools</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>
                                    <span class="post-id-badge">#{{ $post->id }}</span>
                                </td>
                                <td>
                                    <span class="post-title-text" title="{{ $post->title }}">{{ $post->title }}</span>
                                </td>
                                <td>
                                    @if($post->postcategory)
                                        <span class="category-badge">
                                            <i class="fas fa-folder"></i> {{ $post->postcategory->name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->postsubcategory)
                                        <span class="subcategory-badge">
                                            <i class="fas fa-folder-open"></i> {{ $post->postsubcategory->name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="date-badge">
                                        <i class="far fa-calendar-alt text-muted mr-1"></i>
                                        {{ $post->created_at->format('d M, Y') }}
                                    </span>
                                </td>
                                <td style="text-align: right; padding-right: 2rem; white-space: nowrap;">
                                    <a href="{{ route('admin.post.view', $post->id) }}" class="btn-table-action btn-table-action-view" title="View publication">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.post.edit', $post->id) }}" class="btn-table-action btn-table-action-edit" title="Edit publication">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.post.destroy', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-table-action btn-table-action-delete"
                                            onclick="return confirm('Are you sure you want to delete this post?')"
                                            title="Delete publication">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
            $('#dataTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search posts...",
                    lengthMenu: "Show _MENU_ items",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: '<i class="fas fa-chevron-right"></i>',
                        previous: '<i class="fas fa-chevron-left"></i>'
                    }
                }
            });
        });
    </script>
@endsection
