@extends('layouts.master')

@section('styles')
    <!-- Google Fonts for modern typography -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/datetime/1.5.0/css/dataTables.dateTime.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/colreorder/1.7.0/css/colReorder.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Modern Design Tokens */
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #e0e7ff;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --font: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            font-family: var(--font) !important;
            background-color: #f1f5f9;
        }

        .dashboard-container {
            padding: 0.75rem 1.5rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Breadcrumb Styling */
        .modern-breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0;
        }
        .modern-breadcrumb .breadcrumb-item {
            font-size: 0.875rem;
            font-weight: 500;
        }
        .modern-breadcrumb .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
        }
        .modern-breadcrumb .breadcrumb-item a:hover {
            color: var(--primary);
        }
        .modern-breadcrumb .breadcrumb-item.active {
            color: var(--dark);
            font-weight: 600;
        }

        /* Header / Title block */
        .page-header-block {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            margin: 0;
            letter-spacing: -0.02em;
        }
        .page-subtitle {
            font-size: 0.825rem;
            color: #64748b;
            margin-top: 0.15rem;
        }

        /* Sleek Cards */
        .modern-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            padding: 1rem;
            margin-bottom: 1rem;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .modern-card:hover {
            box-shadow: var(--shadow);
        }

        /* Beautiful Filters Panel */
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
        }
        @media (max-width: 992px) {
            .filter-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
        }
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        .filter-group select,
        .filter-group input {
            height: 36px;
            padding: 0.35rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--dark);
            background-color: var(--light);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .filter-group select:focus,
        .filter-group input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            background-color: #ffffff;
        }
        .filter-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .btn-modern {
            height: 42px;
            padding: 0 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }
        .btn-modern-primary {
            background-color: var(--primary);
            color: #ffffff;
        }
        .btn-modern-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }
        .btn-modern-secondary {
            background-color: #e2e8f0;
            color: #475569;
        }
        .btn-modern-secondary:hover {
            background-color: #cbd5e1;
        }
        .btn-modern-danger {
            background-color: var(--danger-light);
            color: var(--danger);
        }
        .btn-modern-danger:hover {
            background-color: var(--danger);
            color: white;
            transform: translateY(-1px);
        }
        .btn-modern-warning {
            background-color: var(--warning-light);
            color: var(--warning);
        }
        .btn-modern-warning:hover {
            background-color: var(--warning);
            color: white;
            transform: translateY(-1px);
        }
        .btn-modern-success {
            background-color: var(--success-light);
            color: var(--success);
        }
        .btn-modern-success:hover {
            background-color: var(--success);
            color: white;
            transform: translateY(-1px);
        }

        /* Action bar */
        .action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .left-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        /* DataTable Customizations */
        .table-responsive-wrapper {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid var(--border);
            padding: 1rem;
            box-shadow: var(--shadow-sm);
            overflow-x: auto !important;
            width: 100%;
        }
        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 0.5rem !important;
            width: 100% !important;
            margin-top: 1rem !important;
        }
        table.dataTable thead th {
            background: #f8fafc !important;
            color: #475569 !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.05em !important;
            border-bottom: 2px solid var(--border) !important;
            padding: 12px 16px !important;
        }
        table.dataTable tbody tr {
            background-color: #ffffff !important;
            transition: all 0.2s ease;
        }
        table.dataTable tbody tr:hover {
            background-color: #f1f5f9 !important;
        }
        table.dataTable tbody td {
            padding: 14px 16px !important;
            border-bottom: 1px solid var(--border) !important;
            font-size: 0.875rem !important;
            color: var(--dark) !important;
            vertical-align: middle !important;
        }

        /* Custom Checkbox */
        .custom-control-input {
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 2px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.2s;
        }
        .custom-control-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* Badges Styling */
        .badge-modern {
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .badge-simple { background: #dbeafe; color: #1e40af; }
        .badge-variable { background: #f3e8ff; color: #6b21a8; }
        .badge-digital { background: #d1fae5; color: #065f46; }
        .badge-affiliate { background: #fef3c7; color: #92400e; }
        .badge-unknown { background: #e2e8f0; color: #475569; }

        .status-badge-modern {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }
        .status-active-modern {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }
        .status-inactive-modern {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        /* Product image */
        .product-thumbnail {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: transform 0.2s;
        }
        .product-thumbnail:hover {
            transform: scale(1.1);
        }

        /* Price text */
        .price-modern {
            font-weight: 700;
            color: var(--dark);
            font-size: 0.95rem;
        }

        /* Views Display */
        .views-display {
            font-size: 0.825rem;
            color: #64748b;
            font-weight: 500;
        }
        .views-icon {
            color: #94a3b8;
            margin-right: 4px;
        }

        /* Datatables controls restyling */
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1.5rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            height: 40px;
            padding: 0.5rem 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            width: 260px;
            outline: none;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
        .dataTables_wrapper .dt-buttons {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        .dt-button-modern {
            background: #ffffff !important;
            color: #475569 !important;
            border: 1px solid var(--border) !important;
            padding: 8px 14px !important;
            border-radius: 8px !important;
            font-size: 0.825rem !important;
            font-weight: 600 !important;
            transition: all 0.2s !important;
            box-shadow: var(--shadow-sm) !important;
        }
        .dt-button-modern:hover {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: var(--dark) !important;
        }
        .bottom-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .dataTables_info {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }
        .dataTables_length select {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 4px 8px;
            font-weight: 500;
            outline: none;
        }

        /* Action Buttons */
        .action-buttons-group {
            display: flex;
            gap: 0.35rem;
        }
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: 1px solid transparent;
            text-decoration: none;
        }
        .action-btn-copy {
            background-color: #f1f5f9;
            color: #475569;
        }
        .action-btn-copy:hover {
            background-color: #cbd5e1;
            color: var(--dark);
        }
        .action-btn-edit {
            background-color: var(--primary-light);
            color: var(--primary);
        }
        .action-btn-edit:hover {
            background-color: var(--primary);
            color: white;
        }
        .action-btn-delete {
            background-color: var(--danger-light);
            color: var(--danger);
        }
        .action-btn-delete:hover {
            background-color: var(--danger);
            color: white;
        }
        .action-btn-view {
            background-color: var(--success-light);
            color: var(--success);
        }
        .action-btn-view:hover {
            background-color: var(--success);
            color: white;
        }

        /* SweetAlert2 Premium Customizations */
        .swal2-popup {
            font-family: var(--font) !important;
            border-radius: 16px !important;
            padding: 2rem !important;
        }
        .swal2-title {
            font-weight: 800 !important;
            color: var(--dark) !important;
            font-size: 1.5rem !important;
        }
        .swal2-html-container {
            font-size: 0.95rem !important;
            color: #475569 !important;
            font-weight: 500 !important;
        }
        .swal2-confirm {
            background-color: var(--primary) !important;
            border-radius: 10px !important;
            font-weight: 600 !important;
            padding: 10px 24px !important;
        }
        .swal2-cancel {
            border-radius: 10px !important;
            font-weight: 600 !important;
            padding: 10px 24px !important;
        }

        /* Mobile Responsive Adjustments */
        @media (max-width: 576px) {
            .dashboard-container {
                padding: 10px 8px !important;
            }
            .modern-card {
                padding: 12px 10px !important;
                margin-bottom: 12px !important;
                border-radius: 8px !important;
            }
            .table-responsive-wrapper {
                padding: 8px !important;
                border-radius: 8px !important;
            }
            
            /* Header fonts */
            .page-title {
                font-size: 1.15rem !important;
            }
            .page-subtitle {
                font-size: 0.75rem !important;
            }
            .page-header-block {
                margin-bottom: 0.75rem !important;
                gap: 8px !important;
            }

            /* Action Buttons 2-column Grid */
            .left-actions {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 6px !important;
                width: 100% !important;
            }
            .left-actions .btn-modern {
                height: 34px !important;
                padding: 0 8px !important;
                font-size: 11px !important;
                border-radius: 6px !important;
                width: 100% !important;
            }
            
            /* Datatables filters and buttons */
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 1rem !important;
            }
            .dataTables_wrapper .dataTables_filter input {
                width: 100% !important;
                font-size: 12px !important;
                height: 34px !important;
                padding: 0 10px !important;
            }
            .dataTables_wrapper .dt-buttons {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 4px !important;
                margin-bottom: 0.75rem !important;
                width: 100% !important;
            }
            .dt-button-modern {
                padding: 6px 2px !important;
                font-size: 10px !important;
                border-radius: 6px !important;
                text-align: center !important;
                width: 100% !important;
            }

            /* Table mobile optimizations */
            table.dataTable tbody td {
                padding: 8px 6px !important;
                font-size: 0.75rem !important;
            }
            table.dataTable thead th {
                padding: 8px 6px !important;
                font-size: 0.7rem !important;
            }
            .product-thumbnail {
                width: 32px !important;
                height: 32px !important;
                border-radius: 4px !important;
            }
            .action-btn {
                width: 26px !important;
                height: 26px !important;
                font-size: 0.75rem !important;
                border-radius: 6px !important;
            }
            .action-buttons-group {
                gap: 0.2rem !important;
            }
            .price-modern {
                font-size: 0.8rem !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="dashboard-container">
        <!-- Page Header Block -->
        <div class="page-header-block">
            <div>
                <h1 class="page-title">Product Inventory</h1>
                <p class="page-subtitle">Manage, filter, and export all digital and physical store products.</p>
            </div>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="modern-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Product Catalog</li>
                </ol>
            </nav>
        </div>

        <!-- Filter Card -->
        <div class="modern-card">
            <div id="filterCollapseBody">
                <div class="filter-grid">
                    <div class="filter-group">
                        <select id="primary-category-filter">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\ProductCategory::where('status', 'active')->orderBy('name')->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <select id="subcategory-filter" disabled>
                            <option value="">Select a primary category first</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select id="third-category-filter" disabled>
                            <option value="">Select a subcategory first</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select id="status-filter">
                            <option value="">All Statuses</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select id="product-type-filter">
                            <option value="">All Types</option>
                            <option value="simple">Simple</option>
                            <option value="variable">Variable</option>
                            <option value="digital">Digital</option>
                            <option value="affiliate">Affiliate</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <input type="number" id="price-min" placeholder="Min Price (TK)" min="0">
                    </div>
                    <div class="filter-group">
                        <input type="number" id="price-max" placeholder="Max Price (TK)" min="0">
                    </div>
                    <div class="filter-group">
                        <input type="date" id="date-from" placeholder="Date From">
                    </div>
                    <div class="filter-group">
                        <input type="date" id="date-to" placeholder="Date To">
                    </div>
                    <div class="filter-actions">
                        <button class="btn-modern btn-modern-secondary w-100" id="clear-filters" style="height:36px; padding:0 10px; font-size:0.8rem;">
                            <i class="fas fa-undo-alt"></i> Reset
                        </button>
                        <button class="btn-modern btn-modern-primary w-100" id="apply-filters" style="height:36px; padding:0 10px; font-size:0.8rem;">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <div class="left-actions">
                <a href="{{route ('admin.items.create')}}" class="btn-modern btn-modern-primary">
                    <i class="fas fa-plus"></i> Add Product
                </a>
                <button type="button" class="btn-modern btn-image-search-trigger" onclick="event.preventDefault(); window.openImageSearchModal();" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff; box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);">
                    <i class="fas fa-camera"></i> Visual Image Search
                </button>
                <a href="{{ route('admin.global-products.index') }}" class="btn-modern" style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%); color: #ffffff; box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);">
                    <i class="fas fa-globe"></i> Browse Global Products
                </a>
                <button id="bulk-status-toggle" class="btn-modern btn-modern-warning">
                    <i class="fas fa-toggle-on"></i> Toggle Status
                </button>
                <button id="export-selected" class="btn-modern btn-modern-success">
                    <i class="fas fa-file-export"></i> Export Selected
                </button>
                <a href="{{ route('admin.google-sheets.index') }}" class="btn-modern" style="background: #10b981; color: white;">
                    <i class="fas fa-file-excel"></i> Google Sheets Sync
                </a>
                <button id="bulk-delete-products" class="btn-modern btn-modern-danger">
                    <i class="fas fa-trash-alt"></i> Bulk Delete
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-responsive-wrapper">
            <table class="table table-hover" id="Products" style="width:100%">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" class="custom-control-input" id="select-all-products"></th>
                        <th class="d-none d-md-table-cell">ID</th>
                        <th>Product Title & Info</th>
                        <th>Preview</th>
                        <th class="d-none d-md-table-cell">Category Group</th>
                        <th class="d-none d-md-table-cell">Stock Type</th>
                        <th>Price</th>
                        <th class="d-none d-lg-table-cell">Product Views</th>
                        <th class="d-none d-sm-table-cell">Visibility</th>
                        <th class="d-none d-lg-table-cell">Date Added</th>
                        <th width="150">Operations</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/datetime/1.5.0/js/dataTables.dateTime.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/1.7.0/js/dataTables.colReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Collapse filter card animation
            $('#toggleFilterBtn').click(function() {
                const grid = $('#filterCollapseBody');
                const chevron = $('#filterChevron');
                if (grid.is(':visible')) {
                    grid.slideUp(200);
                    chevron.css('transform', 'rotate(-90deg)');
                } else {
                    grid.slideDown(200);
                    chevron.css('transform', 'rotate(0deg)');
                }
            });

            const selectedProductIds = new Set();
            let customFilters = {};

            const table = $('#Products').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.items.data') }}',
                    type: 'GET',
                    data: function(d) {
                        return $.extend({}, d, customFilters);
                    }
                },
                columns: [
                    { data: 'checkbox', orderable: false, searchable: false, render: function(data, type, row) {
                        return `<input type="checkbox" class="custom-control-input product-checkbox" value="${row.id}">`;
                    }},
                    { data: 'id', className: 'd-none d-md-table-cell' },
                    { data: 'title', render: function(data, type, row) {
                        if (!data) return '<span class="text-muted">N/A</span>';
                        const maxLength = 40;
                        const titleText = data.length > maxLength ? `${data.substring(0, maxLength)}...` : data;
                        let badge = '';
                        if (row.origin_label) {
                            badge = `<div class="mt-1"><span class="badge rounded-pill" style="background-color: #ede9fe; color: #6366f1; font-size: 10px; font-weight: 600;"><i class="fas fa-file-import me-1"></i> Imported</span></div>`;
                        }
                        return `<span class="fw-semibold" title="${data}">${titleText}</span>${badge}`;
                    }},
                    { data: 'thumb_image', orderable: false, searchable: false, render: function(path){
                        if (!path) return '<span class="text-muted">No Image</span>';
                        const url = path.startsWith('http') ? path : `{{ asset('storage') }}/${path}`;
                        return `<img src="${url}" class="product-thumbnail">`;
                    }},
                    { data: 'category_display', className: 'd-none d-md-table-cell', render: function(data) {
                        return data || '<span class="text-muted">N/A</span>';
                    }},
                    { data: 'product_type', className: 'd-none d-md-table-cell', render: function(data) {
                        const types = {
                            'simple': '<span class="badge-modern badge-simple"><i class="fas fa-cube"></i> Simple</span>',
                            'variable': '<span class="badge-modern badge-variable"><i class="fas fa-boxes"></i> Variable</span>',
                            'digital': '<span class="badge-modern badge-digital"><i class="fas fa-download"></i> Digital</span>',
                            'affiliate': '<span class="badge-modern badge-affiliate"><i class="fas fa-link"></i> Affiliate</span>'
                        };
                        return types[data] || '<span class="badge-modern badge-unknown">Unknown</span>';
                    }},
                    { data: 'price_display', render: function(data, type, row) {
                        if (!data) return '<span class="text-muted">N/A</span>';
                        
                        if (typeof data === 'string' && data.includes(' - ')) {
                            return `<span class="price-modern">TK ${data}</span>`;
                        }
                        
                        const price = parseFloat(data);
                        if (isNaN(price)) return '<span class="text-muted">N/A</span>';
                        
                        return `<span class="price-modern">TK ${price.toFixed(2)}</span>`;
                    }},
                    { data: 'views', className: 'd-none d-lg-table-cell', orderable: false, searchable: false, render: function(data) {
                        if (!data) return '<span class="text-muted">0 / 0</span>';
                        return `<span class="views-display"><i class="far fa-eye views-icon"></i>${data}</span>`;
                    }},
                    { data: 'status', className: 'd-none d-sm-table-cell', render: function(data) {
                        return data == 1 ? 
                            '<span class="status-badge-modern status-active-modern"><i class="fas fa-check-circle me-1"></i> Active</span>' : 
                            '<span class="status-badge-modern status-inactive-modern"><i class="fas fa-times-circle me-1"></i> Inactive</span>';
                    }},
                    { data: 'created_at', className: 'd-none d-lg-table-cell', render: function(data) {
                        if (!data) return '<span class="text-muted">N/A</span>';
                        return new Date(data).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                    }},
                    { data: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                        return data;
                    }}
                ],
                dom: '<"d-flex flex-wrap justify-content-between align-items-center mb-3"Bf>rt<"bottom-controls"lip>',
                buttons: [
                    {
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'dt-button-modern',
                        action: function (e, dt, node, config) {
                            triggerProductExport();
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'dt-button-modern'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'dt-button-modern'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Columns',
                        className: 'dt-button-modern',
                        columns: ':not(:first-child):not(:last-child)'
                    }
                ],
                responsive: false,
                colReorder: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]'
                },
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[1, 'desc']]
            });

            // Filter functionality
            const subcategorySelect = $('#subcategory-filter');
            const thirdCategorySelect = $('#third-category-filter');

            function setSubcategoryOptions(options, placeholder, disabled) {
                subcategorySelect.empty().append(`<option value="">${placeholder}</option>`);
                if (Array.isArray(options)) {
                    options.forEach(option => {
                        subcategorySelect.append(`<option value="${option.id}">${option.name}</option>`);
                    });
                }
                subcategorySelect.prop('disabled', disabled);
            }

            function setThirdCategoryOptions(options, placeholder, disabled) {
                thirdCategorySelect.empty().append(`<option value="">${placeholder}</option>`);
                if (Array.isArray(options)) {
                    options.forEach(option => {
                        thirdCategorySelect.append(`<option value="${option.id}">${option.name}</option>`);
                    });
                }
                thirdCategorySelect.prop('disabled', disabled);
            }

            function loadSubcategories(categoryId) {
                if (!categoryId) {
                    setSubcategoryOptions(null, 'Select a primary category first', true);
                    setThirdCategoryOptions(null, 'Select a subcategory first', true);
                    return;
                }

                setSubcategoryOptions(null, 'Loading...', true);
                setThirdCategoryOptions(null, 'Select a subcategory first', true);

                const rawUrl = '{{ route("admin.get-product-subcategories", ":id") }}';
                const url = rawUrl.replace('%3Aid', categoryId).replace(':id', categoryId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    success: function(data) {
                        if (Array.isArray(data) && data.length) {
                            setSubcategoryOptions(data, 'All Subcategories', false);
                        } else {
                            setSubcategoryOptions(null, 'No subcategories available', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading subcategories:', error, xhr.responseText);
                        setSubcategoryOptions(null, 'Failed to load subcategories', true);
                    }
                });
            }

            function loadThirdCategories(subcategoryId) {
                if (!subcategoryId) {
                    setThirdCategoryOptions(null, 'Select a subcategory first', true);
                    return;
                }

                setThirdCategoryOptions(null, 'Loading...', true);

                fetch('{{ route('admin.third-categories.by-subcategories') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ sub_category_ids: [subcategoryId] })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (Array.isArray(data) && data.length) {
                            setThirdCategoryOptions(data, 'All Third Categories', false);
                        } else {
                            setThirdCategoryOptions(null, 'No third categories available', true);
                        }
                    })
                    .catch(() => {
                        setThirdCategoryOptions(null, 'Failed to load third categories', true);
                    });
            }

            $('#primary-category-filter').on('change', function() {
                const categoryId = $(this).val();
                subcategorySelect.val('');
                thirdCategorySelect.val('');
                loadSubcategories(categoryId);
            });

            $('#subcategory-filter').on('change', function() {
                const subcategoryId = $(this).val();
                thirdCategorySelect.val('');
                loadThirdCategories(subcategoryId);
            });

            setSubcategoryOptions(null, 'Select a primary category first', true);
            setThirdCategoryOptions(null, 'Select a subcategory first', true);

            $('#apply-filters').on('click', function() {
                customFilters = {
                    primary_category_id: $('#primary-category-filter').val(),
                    subcategory_id: $('#subcategory-filter').val(),
                    third_category_id: $('#third-category-filter').val(),
                    status: $('#status-filter').val(),
                    product_type: $('#product-type-filter').val(),
                    price_min: $('#price-min').val(),
                    price_max: $('#price-max').val(),
                    date_from: $('#date-from').val(),
                    date_to: $('#date-to').val()
                };
                table.ajax.reload();
            });

            $('#clear-filters').on('click', function() {
                $('#primary-category-filter, #subcategory-filter, #third-category-filter, #status-filter, #product-type-filter').val('');
                setSubcategoryOptions(null, 'Select a primary category first', true);
                setThirdCategoryOptions(null, 'Select a subcategory first', true);
                $('#price-min, #price-max, #date-from, #date-to').val('');
                customFilters = {};
                table.ajax.reload();
            });

            // Maintain selection across pagination
            $('#Products').on('draw.dt', function () {
                const rows = table.rows({ page: 'current' }).nodes();
                $('input.product-checkbox', rows).each(function() {
                    const id = $(this).val();
                    $(this).prop('checked', selectedProductIds.has(id));
                });
                
                // Add modern button styling to the action buttons loaded dynamically
                $('a.btn-outline-info', rows).removeClass('btn btn-outline-info').addClass('action-btn action-btn-view').html('<i class="fas fa-eye"></i>');
                $('a.btn-outline-primary', rows).removeClass('btn btn-outline-primary').addClass('action-btn action-btn-edit').html('<i class="fas fa-edit"></i>');
                $('button.copy-link-btn', rows).removeClass('btn btn-outline-secondary').addClass('action-btn action-btn-copy');
                $('a.btn-outline-danger', rows).removeClass('btn btn-outline-danger').addClass('action-btn action-btn-delete').html('<i class="fas fa-trash-alt"></i>');
                
                $('input.product-checkbox', rows).off('change').on('change', function() {
                    const id = $(this).val();
                    if (this.checked) selectedProductIds.add(id); else selectedProductIds.delete(id);
                });
            });

            const selectAll = document.getElementById('select-all-products');
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    const rows = table.rows({ page: 'current' }).nodes();
                    $('input.product-checkbox', rows).each((_, el) => {
                        const id = el.value;
                        el.checked = selectAll.checked;
                        if (selectAll.checked) selectedProductIds.add(id); else selectedProductIds.delete(id);
                    });
                });
            }

            // Bulk delete functionality
            const bulkDeleteBtn = document.getElementById('bulk-delete-products');
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function() {
                    const selected = Array.from(selectedProductIds);

                    if (selected.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Selection',
                            text: 'Please select at least one product to delete.',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Are you sure you want to delete the selected products? This action cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Yes, delete them!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            bulkDeleteBtn.disabled = true;
                            const originalHtml = bulkDeleteBtn.innerHTML;
                            bulkDeleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';

                            fetch("{{ route('admin.items.bulk-delete') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ product_ids: selected })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: data.message || 'Selected products deleted successfully!',
                                        confirmButtonColor: '#4f46e5'
                                    });
                                    selectedProductIds.clear();
                                    table.ajax.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed',
                                        text: data.message || 'Failed to delete selected products.',
                                        confirmButtonColor: '#4f46e5'
                                    });
                                }
                            })
                            .catch(() => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while deleting products.',
                                    confirmButtonColor: '#4f46e5'
                                });
                            })
                            .finally(() => {
                                bulkDeleteBtn.disabled = false;
                                bulkDeleteBtn.innerHTML = originalHtml;
                            });
                        }
                    });
                });
            }

            // Bulk status toggle
            const bulkStatusToggleBtn = document.getElementById('bulk-status-toggle');
            if (bulkStatusToggleBtn) {
                bulkStatusToggleBtn.addEventListener('click', function() {
                    const selected = Array.from(selectedProductIds);

                    if (selected.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Selection',
                            text: 'Please select at least one product to toggle status.',
                            confirmButtonColor: '#4f46e5'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Select Status',
                        text: 'Toggle status for selected products. Choose the new state:',
                        icon: 'question',
                        showCancelButton: true,
                        showDenyButton: true,
                        confirmButtonColor: '#10b981',
                        denyButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Active',
                        denyButtonText: 'Inactive',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        let newStatus;
                        if (result.isConfirmed) {
                            newStatus = 1;
                        } else if (result.isDenied) {
                            newStatus = 0;
                        } else {
                            return; // Cancelled
                        }

                        bulkStatusToggleBtn.disabled = true;
                        const originalHtml = bulkStatusToggleBtn.innerHTML;
                        bulkStatusToggleBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

                        fetch("{{ route('admin.items.bulk-status-toggle') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ 
                                product_ids: selected,
                                status: newStatus
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Updated!',
                                    text: data.message || 'Status updated successfully!',
                                    confirmButtonColor: '#4f46e5'
                                });
                                table.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Failed',
                                    text: data.message || 'Failed to update status.',
                                    confirmButtonColor: '#4f46e5'
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                    title: 'Error',
                                    text: 'An error occurred while updating status.',
                                    confirmButtonColor: '#4f46e5'
                            });
                        })
                        .finally(() => {
                            bulkStatusToggleBtn.disabled = false;
                            bulkStatusToggleBtn.innerHTML = originalHtml;
                        });
                    });
                });
            }

            // Product Export Function (Supports both Selected & Filtered All)
            function triggerProductExport(forceExportAll = false) {
                const selected = Array.from(selectedProductIds);
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.items.export-selected') }}";
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                if (!forceExportAll && selected.length > 0) {
                    const productIds = document.createElement('input');
                    productIds.type = 'hidden';
                    productIds.name = 'product_ids';
                    productIds.value = JSON.stringify(selected);
                    form.appendChild(productIds);
                } else {
                    const filters = {
                        primary_category_id: $('#primary-category-filter').val(),
                        subcategory_id: $('#subcategory-filter').val(),
                        third_category_id: $('#third-category-filter').val(),
                        status: $('#status-filter').val(),
                        product_type: $('#product-type-filter').val(),
                        price_min: $('#price-min').val(),
                        price_max: $('#price-max').val(),
                        date_from: $('#date-from').val(),
                        date_to: $('#date-to').val(),
                        search: table.search()
                    };

                    for (const [key, val] of Object.entries(filters)) {
                        if (val !== '' && val !== null && val !== undefined) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key;
                            input.value = val;
                            form.appendChild(input);
                        }
                    }
                }
                
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            }

            // Export selected button handler
            const exportSelectedBtn = document.getElementById('export-selected');
            if (exportSelectedBtn) {
                exportSelectedBtn.addEventListener('click', function() {
                    const selected = Array.from(selectedProductIds);

                    if (selected.length === 0) {
                        Swal.fire({
                            title: 'Export Products',
                            text: 'No products selected. Would you like to export all products matching current filters?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#10b981',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: '<i class="fas fa-file-export me-1"></i> Yes, Export All',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                triggerProductExport(true);
                            }
                        });
                        return;
                    }

                    triggerProductExport(false);
                });
            }

            // Copy link functionality
            $(document).on('click', '.copy-link-btn', function() {
                const url = $(this).data('url');
                const button = $(this);
                const originalHtml = button.html();
                
                navigator.clipboard.writeText(url).then(function() {
                    button.html('<i class="fas fa-check"></i>');
                    button.css('background-color', '#10b981').css('color', '#ffffff');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Product link copied to clipboard.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    setTimeout(function() {
                        button.html(originalHtml);
                        button.css('background-color', '').css('color', '');
                    }, 2000);
                }).catch(function() {
                    const textArea = document.createElement('textarea');
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    
                    button.html('<i class="fas fa-check"></i>');
                    button.css('background-color', '#10b981').css('color', '#ffffff');
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Copied!',
                        text: 'Product link copied to clipboard.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    setTimeout(function() {
                        button.html(originalHtml);
                        button.css('background-color', '').css('color', '');
                    }, 2000);
                });
            });
        });
    </script>
@endsection
