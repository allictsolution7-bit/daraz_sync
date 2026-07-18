@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
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
            padding: 1.25rem 1.5rem;
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
            margin-bottom: 0.75rem;
        }

        /* ── Premium Stats Cards ── */
        .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.5rem; }
        @media(max-width:992px){ .stats-row { grid-template-columns: repeat(2,1fr); } }
        @media(max-width:576px){ .stats-row { grid-template-columns: 1fr; } }

        .stats-card {
            background: #fff;
            border-radius: 20px;
            padding: 1.4rem 1.5rem;
            box-shadow: 0 2px 20px rgba(0,0,0,0.04);
            border: 1px solid #f0f4f8;
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            display: flex;
            align-items: center;
            gap: 1.1rem;
        }
        .stats-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 5px; height: 100%;
            border-radius: 20px 0 0 20px;
        }
        .stats-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.08); }

        .stats-card .s-icon {
            width: 58px; height: 58px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; flex-shrink: 0;
            transition: transform 0.3s ease;
        }
        .stats-card:hover .s-icon { transform: scale(1.1) rotate(-4deg); }
        .stats-card .s-body { flex: 1; }
        .stats-card .s-value {
            font-size: 2.1rem; font-weight: 800; letter-spacing: -0.04em;
            line-height: 1; margin-bottom: 4px; display: block;
        }
        .stats-card .s-label {
            font-size: 0.78rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.07em; color: #94a3b8;
        }
        .stats-card .s-trend {
            font-size: 0.75rem; font-weight: 600; margin-top: 2px;
        }

        /* Info – blue */
        .sc-info::before { background: linear-gradient(180deg,#6366f1,#818cf8); }
        .sc-info .s-icon { background: linear-gradient(135deg,rgba(99,102,241,.12),rgba(129,140,248,.12)); color: #6366f1; }
        .sc-info .s-value { color: #4f46e5; }

        /* Success – green */
        .sc-success::before { background: linear-gradient(180deg,#10b981,#34d399); }
        .sc-success .s-icon { background: linear-gradient(135deg,rgba(16,185,129,.12),rgba(52,211,153,.12)); color: #059669; }
        .sc-success .s-value { color: #059669; }

        /* Warning – amber */
        .sc-warning::before { background: linear-gradient(180deg,#f59e0b,#fbbf24); }
        .sc-warning .s-icon { background: linear-gradient(135deg,rgba(245,158,11,.12),rgba(251,191,36,.12)); color: #d97706; }
        .sc-warning .s-value { color: #d97706; }

        /* Danger – red */
        .sc-danger::before { background: linear-gradient(180deg,#ef4444,#f87171); }
        .sc-danger .s-icon { background: linear-gradient(135deg,rgba(239,68,68,.12),rgba(248,113,113,.12)); color: #dc2626; }
        .sc-danger .s-value { color: #dc2626; }

        /* ── Premium Financial Bar ── */
        .financial-bar {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 20px;
            padding: 1.4rem 2rem;
            margin-bottom: 1.5rem;
            display: grid;
            grid-template-columns: repeat(4,1fr);
            gap: 0;
            position: relative;
            overflow: hidden;
        }
        .financial-bar::before {
            content: '';
            position: absolute;
            top: -40%; right: -10%;
            width: 250px; height: 250px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .financial-bar::after {
            content: '';
            position: absolute;
            bottom: -30%; left: 5%;
            width: 180px; height: 180px;
            background: radial-gradient(circle, rgba(16,185,129,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        .f-item {
            padding: 0 1.5rem;
            border-right: 1px solid rgba(255,255,255,0.08);
            position: relative; z-index: 1;
        }
        .f-item:first-child { padding-left: 0; }
        .f-item:last-child { border-right: none; }
        .f-item .f-label {
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; color: rgba(255,255,255,0.45); margin-bottom: 6px;
            display: flex; align-items: center; gap: 6px;
        }
        .f-item .f-label i { font-size: 0.85rem; }
        .f-item .f-value {
            font-size: 1.35rem; font-weight: 800; letter-spacing: -0.03em;
            font-family: 'Outfit', sans-serif;
        }
        .f-item .f-sub {
            font-size: 0.72rem; color: rgba(255,255,255,0.35); margin-top: 3px;
        }
        .f-retail .f-value { color: #34d399; }
        .f-cost .f-value { color: #60a5fa; }
        .f-profit .f-value { color: #a78bfa; }
        .f-wholesale .f-value { color: #fbbf24; }
        @media(max-width:992px) { .financial-bar { grid-template-columns: repeat(2,1fr); gap: 1rem; } .f-item { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.08); padding: 0 0 1rem 0; } .f-item:nth-child(2n) { border-bottom: 1px solid rgba(255,255,255,0.08); } .f-item:last-child { border-bottom: none; } }
        @media(max-width:576px) { .financial-bar { grid-template-columns: 1fr; } }

        /* Filter Section Styling */
        .filter-section {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
        }
        .form-select, .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.55rem 0.9rem;
            font-size: 0.9rem;
            font-family: 'Outfit', sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        .form-select:focus, .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .form-label {
            font-weight: 500;
            color: #475569;
            font-size: 0.85rem;
            margin-bottom: 0.35rem;
        }

        /* Action Toolbar buttons */
        .btn-toolbar-outline {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 0.55rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-toolbar-outline:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #94a3b8;
            transform: translateY(-1px);
        }

        /* Modernized Table Design */
        .premium-table-wrapper {
            border-radius: 16px;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.015);
            background: #ffffff;
            padding: 1rem;
        }
        .premium-table-wrapper table {
            width: 100% !important;
            margin: 0 !important;
            border-collapse: collapse;
        }
        .premium-table-wrapper table thead th {
            background: #f8fafc !important; /* Light Slate Header */
            color: #475569 !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
            padding: 1.1rem 1rem !important;
            border-bottom: 2px solid #e2e8f0 !important;
        }
        .premium-table-wrapper table tbody td {
            padding: 1rem !important;
            vertical-align: middle !important;
            color: #334155;
            font-size: 0.925rem;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        /* Override DataTables sorting arrow pseudo-elements & backgrounds */
        table.dataTable thead th.sorting::after,
        table.dataTable thead th.sorting::before,
        table.dataTable thead th.sorting_asc::after,
        table.dataTable thead th.sorting_asc::before,
        table.dataTable thead th.sorting_desc::after,
        table.dataTable thead th.sorting_desc::before,
        .premium-table-wrapper table thead th::before,
        .premium-table-wrapper table thead th::after {
            content: "" !important;
            display: none !important;
        }

        .premium-table-wrapper table thead th.sorting {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12"><path fill="%2364748b" d="M4 0l4 4H0zm0 12L0 8h8z"/></svg>') !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            padding-right: 30px !important;
            cursor: pointer;
        }
        .premium-table-wrapper table thead th.sorting_asc {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12"><path fill="%234f46e5" d="M4 0l4 4H0z"/></svg>') !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            padding-right: 30px !important;
            cursor: pointer;
        }
        .premium-table-wrapper table thead th.sorting_desc {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12"><path fill="%234f46e5" d="M4 12L0 8h8z"/></svg>') !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            padding-right: 30px !important;
            cursor: pointer;
        }

        /* Fixed column widths */
        .col-width-product {
            width: 45% !important;
            min-width: 320px;
            white-space: normal !important;
        }
        .col-width-stock {
            width: 15% !important;
            min-width: 120px;
            white-space: nowrap !important;
        }
        .col-width-value {
            width: 25% !important;
            min-width: 180px;
            white-space: nowrap !important;
        }
        .col-width-actions {
            width: 15% !important;
            min-width: 100px;
            white-space: nowrap !important;
        }

        /* Custom Thumbnail image */
        .inventory-thumb {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        /* Custom Action Icon Buttons */
        .btn-action-custom {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            background: #f8fafc;
            color: #475569;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .btn-action-custom:hover {
            transform: translateY(-2px);
        }
        .btn-action-edit:hover {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }
        .btn-action-history:hover {
            background: rgba(14, 165, 233, 0.12);
            color: #0284c7;
        }
        .btn-action-settings:hover {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
        }
    </style>
@endsection

@section('content')
    <div class="container categories-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory</li>
            </ol>
        </nav>

        <div class="glass-card">
            <!-- Header Section -->
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <div style="width:46px;height:46px;background:linear-gradient(135deg,#6366f1,#818cf8);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;flex-shrink:0;box-shadow:0 8px 20px rgba(99,102,241,.3)">
                            📦
                        </div>
                        <div>
                            <h1 class="page-header-title mb-0">Inventory Management</h1>
                            <p class="page-header-subtitle mb-0">Analyze warehouse stock metrics, financial valuations, and adjust products quantity.</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.inventory.history') }}" class="btn-toolbar-outline">
                        <i class="fa-solid fa-clock-rotate-left"></i> History Logs
                    </a>
                    <a href="{{ route('admin.inventory.low-stock') }}" class="btn-toolbar-outline" style="color:#f59e0b;border-color:#fde68a;background:#fffbeb;">
                        <i class="fa-solid fa-triangle-exclamation"></i> Low Stock Alerts
                        @if($stats['low_stock_products'] > 0)
                        <span style="background:#f59e0b;color:#fff;border-radius:20px;padding:2px 8px;font-size:0.75rem;font-weight:800;margin-left:4px;">{{ $stats['low_stock_products'] }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Premium Stats Cards -->
            <div class="stats-row mb-2">
                <!-- Total Products -->
                <div class="stats-card sc-info">
                    <div class="s-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
                    <div class="s-body">
                        <span class="s-value" id="stat-total-products">{{ number_format($stats['total_products']) }}</span>
                        <span class="s-label">Total Products</span>
                        <div class="s-trend" style="color:#6366f1;"><i class="fa-solid fa-layer-group fa-xs me-1"></i>All catalog items</div>
                    </div>
                </div>
                <!-- In Stock -->
                <div class="stats-card sc-success">
                    <div class="s-icon"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="s-body">
                        <span class="s-value" id="stat-in-stock">{{ number_format($stats['in_stock_products']) }}</span>
                        <span class="s-label">In Stock</span>
                        <div class="s-trend" style="color:#059669;"><i class="fa-solid fa-check fa-xs me-1"></i>Ready to sell</div>
                    </div>
                </div>
                <!-- Low Stock -->
                <div class="stats-card sc-warning">
                    <div class="s-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                    <div class="s-body">
                        <span class="s-value" id="stat-low-stock">{{ number_format($stats['low_stock_products']) }}</span>
                        <span class="s-label">Low Stock</span>
                        <div class="s-trend" style="color:#d97706;"><i class="fa-solid fa-arrow-down fa-xs me-1"></i>Needs reorder</div>
                    </div>
                </div>
                <!-- Out of Stock -->
                <div class="stats-card sc-danger">
                    <div class="s-icon"><i class="fa-solid fa-circle-xmark"></i></div>
                    <div class="s-body">
                        <span class="s-value" id="stat-out-of-stock">{{ number_format($stats['out_of_stock_products']) }}</span>
                        <span class="s-label">Out of Stock</span>
                        <div class="s-trend" style="color:#dc2626;"><i class="fa-solid fa-ban fa-xs me-1"></i>Unavailable</div>
                    </div>
                </div>
            </div>

            <!-- Financial Dark Bar -->
            <div class="financial-bar mb-2">
                <div class="f-item f-retail">
                    <div class="f-label"><i class="fa-solid fa-tags"></i> Total Retail Value</div>
                    <div class="f-value" id="stat-retail-value">৳{{ number_format($stats['total_stock_value'], 2) }}</div>
                    <div class="f-sub">At current selling price</div>
                </div>
                <div class="f-item f-cost">
                    <div class="f-label"><i class="fa-solid fa-receipt"></i> Total Cost Value</div>
                    <div class="f-value" id="stat-cost-value">৳{{ number_format($stats['total_cost_value'], 2) }}</div>
                    <div class="f-sub">Procurement cost</div>
                </div>
                <div class="f-item f-profit">
                    <div class="f-label"><i class="fa-solid fa-chart-line"></i> Potential Profit</div>
                    <div class="f-value" id="stat-profit">৳{{ number_format($stats['total_profit'], 2) }}</div>
                    <div class="f-sub">Retail minus cost</div>
                </div>
                <div class="f-item f-wholesale">
                    <div class="f-label"><i class="fa-solid fa-truck"></i> Wholesale Value</div>
                    <div class="f-value" id="stat-wholesale-value">৳{{ number_format($stats['total_wholesale_value'], 2) }}</div>
                    <div class="f-sub">Bulk pricing total</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filter-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold text-slate-800 mb-0"><i class="fa-solid fa-filter text-indigo-500 me-1"></i> Filter Records</h5>
                    <button type="button" id="clear-filters-btn" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                        <i class="fa-solid fa-times"></i> Clear Filters
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Primary Category</label>
                        <select id="primary-category-filter" class="form-select">
                            <option value="">All Categories</option>
                            @foreach(\App\Models\ProductCategory::where('status', 'active')->orderBy('name')->get() as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Subcategory</label>
                        <select id="subcategory-filter" class="form-select" disabled>
                            <option value="">Select a primary category first</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label">Third Level Category</label>
                        <select id="third-category-filter" class="form-select" disabled>
                            <option value="">Select a subcategory first</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3 mb-2">
                        <label for="stock-status-filter" class="form-label">Stock Status</label>
                        <select id="stock-status-filter" class="form-select">
                            <option value="">All Status</option>
                            <option value="in_stock">In Stock</option>
                            <option value="low_stock">Low Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                            <option value="on_backorder">On Backorder</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label for="product-type-filter" class="form-label">Product Type</label>
                        <select id="product-type-filter" class="form-select">
                            <option value="">All Types</option>
                            <option value="simple">Simple</option>
                            <option value="variable">Variable</option>
                            <option value="digital">Digital</option>
                            <option value="affiliate">Affiliate</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="low-stock-filter">
                            <label class="form-check-label fw-medium text-slate-700" for="low-stock-filter">
                                Show Low Stock Only
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Block -->
            <div class="premium-table-wrapper">
                <table id="inventory-table" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Product Details</th>
                            <th width="180">Stock Info</th>
                            <th width="200">Value (৳)</th>
                            <th width="100" class="text-end">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="adjustStockModal" tabindex="-1" style="font-family: 'Outfit', sans-serif;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div class="modal-header bg-slate-900 text-white" style="background: #0f172a; padding: 1.25rem 1.5rem;">
                    <h5 class="modal-title fw-semibold text-white">📝 Adjust Stock</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <div id="adjust-stock-content">
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    
    <script>
        // Function to update statistics based on filters
        function updateStatistics() {
            const filterData = {
                primary_category_id: $('#primary-category-filter').val(),
                subcategory_id: $('#subcategory-filter').val(),
                third_category_id: $('#third-category-filter').val(),
                stock_status: $('#stock-status-filter').val(),
                product_type: $('#product-type-filter').val(),
                low_stock_only: $('#low-stock-filter').is(':checked') ? '1' : '',
                search: typeof table !== 'undefined' ? table.search() : ''
            };

            $.ajax({
                url: '{{ route("admin.inventory.filtered-stats") }}',
                type: 'GET',
                data: filterData,
                beforeSend: function() {
                    $('.stats-card h3, .stats-card h4').css('opacity', '0.5');
                },
                success: function(stats) {
                    $('#stat-total-products').text(stats.total_products.toLocaleString());
                    $('#stat-in-stock').text(stats.in_stock_products.toLocaleString());
                    $('#stat-low-stock').text(stats.low_stock_products.toLocaleString());
                    $('#stat-out-of-stock').text(stats.out_of_stock_products.toLocaleString());
                    
                    $('#stat-retail-value').text('৳' + stats.total_stock_value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('#stat-cost-value').text('৳' + stats.total_cost_value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('#stat-wholesale-value').text('৳' + stats.total_wholesale_value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    
                    const profitElement = $('#stat-profit');
                    profitElement.text('৳' + stats.total_profit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    profitElement.removeClass('text-success text-danger').addClass(stats.total_profit > 0 ? 'text-success' : 'text-danger');
                    
                    $('.stats-card h3, .stats-card h4').css('opacity', '1');
                },
                error: function() {
                    console.error('Failed to update statistics');
                    $('.stats-card h3, .stats-card h4').css('opacity', '1');
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#inventory-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("admin.inventory.data") }}',
                    data: function(d) {
                        d.primary_category_id = $('#primary-category-filter').val();
                        d.subcategory_id = $('#subcategory-filter').val();
                        d.third_category_id = $('#third-category-filter').val();
                        d.stock_status = $('#stock-status-filter').val();
                        d.product_type = $('#product-type-filter').val();
                        d.low_stock_only = $('#low-stock-filter').is(':checked') ? '1' : '';
                    }
                },
                columns: [
                    { data: 'product_info', name: 'title', orderable: true },
                    { data: 'stock_info', name: 'computed_quantity', orderable: true },
                    { data: 'value', name: 'value', orderable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                columnDefs: [
                    { targets: 0, className: 'col-width-product' },
                    { targets: 1, className: 'col-width-stock' },
                    { targets: 2, className: 'col-width-value' },
                    { targets: 3, className: 'col-width-actions text-end' }
                ],
                order: [[1, 'asc']], // Order by stock quantity
                pageLength: 25,
                responsive: true,
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                }
            });

            // Filter events
            $('#primary-category-filter, #subcategory-filter, #third-category-filter, #stock-status-filter, #product-type-filter').on('change', function() {
                table.draw();
                updateStatistics();
            });

            $('#low-stock-filter').on('change', function() {
                table.draw();
                updateStatistics();
            });

            table.on('search.dt', function() {
                clearTimeout(window.statsSearchTimeout);
                window.statsSearchTimeout = setTimeout(function() {
                    updateStatistics();
                }, 500);
            });

            const subcategoryFilter = $('#subcategory-filter');
            const thirdCategoryFilter = $('#third-category-filter');

            function setSubcategoryOptions(options, placeholder, disabled) {
                subcategoryFilter.empty().append(`<option value="">${placeholder}</option>`);
                if (Array.isArray(options)) {
                    options.forEach(option => {
                        subcategoryFilter.append(`<option value="${option.id}">${option.name}</option>`);
                    });
                }
                subcategoryFilter.prop('disabled', disabled);
            }

            function setThirdCategoryOptions(options, placeholder, disabled) {
                thirdCategoryFilter.empty().append(`<option value="">${placeholder}</option>`);
                if (Array.isArray(options)) {
                    options.forEach(option => {
                        thirdCategoryFilter.append(`<option value="${option.id}">${option.name}</option>`);
                    });
                }
                thirdCategoryFilter.prop('disabled', disabled);
            }

            function loadSubcategories(categoryId) {
                if (!categoryId) {
                    setSubcategoryOptions(null, 'Select a primary category first', true);
                    setThirdCategoryOptions(null, 'Select a subcategory first', true);
                    table.draw();
                    return;
                }

                setSubcategoryOptions(null, 'Loading...', true);
                setThirdCategoryOptions(null, 'Select a subcategory first', true);

                const url = '{{ route("admin.get-product-subcategories", ':id') }}'.replace(':id', categoryId);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (Array.isArray(data) && data.length) {
                            setSubcategoryOptions(data, 'All Subcategories', false);
                        } else {
                            setSubcategoryOptions(null, 'No subcategories available', true);
                        }
                        table.draw();
                    })
                    .catch(() => {
                        setSubcategoryOptions(null, 'Failed to load subcategories', true);
                    });
            }

            function loadThirdCategories(subcategoryId) {
                if (!subcategoryId) {
                    setThirdCategoryOptions(null, 'Select a subcategory first', true);
                    table.draw();
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
                        table.draw();
                    })
                    .catch(() => {
                        setThirdCategoryOptions(null, 'Failed to load third categories', true);
                    });
            }

            $('#primary-category-filter').on('change', function() {
                const categoryId = $(this).val();
                subcategoryFilter.val('');
                thirdCategoryFilter.val('');
                loadSubcategories(categoryId);
            });

            $('#subcategory-filter').on('change', function() {
                const subcategoryId = $(this).val();
                thirdCategoryFilter.val('');
                loadThirdCategories(subcategoryId);
            });

            // Initialize dependent selects
            setSubcategoryOptions(null, 'Select a primary category first', true);
            setThirdCategoryOptions(null, 'Select a subcategory first', true);

            // Adjust stock modal
            $(document).on('click', '.adjust-stock-btn', function() {
                const productId = $(this).data('product-id');
                const productTitle = $(this).data('product-title');
                
                $('#adjustStockModal .modal-title').html('📝 Adjust Stock - ' + productTitle);
                $('#adjust-stock-content').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
                
                $('#adjustStockModal').modal('show');
                
                $.get('{{ route("admin.inventory.adjust-form", ":id") }}'.replace(':id', productId))
                    .done(function(response) {
                        $('#adjust-stock-content').html(response);
                    })
                    .fail(function() {
                        $('#adjust-stock-content').html(`
                            <div class="alert alert-danger" style="border-radius:12px;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                Failed to load adjustment form. Please try again.
                            </div>
                        `);
                    });
            });

            // Clear filters button
            $('#clear-filters-btn').on('click', function() {
                $('#primary-category-filter').val('');
                $('#subcategory-filter').val('').prop('disabled', true).html('<option value="">Select a primary category first</option>');
                $('#third-category-filter').val('').prop('disabled', true).html('<option value="">Select a subcategory first</option>');
                $('#stock-status-filter').val('');
                $('#product-type-filter').val('');
                $('#low-stock-filter').prop('checked', false);
                $('#search-filter').val('');
                
                table.draw();
                updateStatistics();
            });
        });
    </script>
@endsection
