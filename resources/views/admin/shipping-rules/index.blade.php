@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .shipping-rules-container {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            padding: 1.5rem;
            border-radius: 1rem;
        }
        .page-header-premium {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            padding: 2rem;
            border-radius: 1rem;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.15);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        .page-header-premium::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .card-premium {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            background-color: #ffffff;
            transition: all 0.3s ease;
            padding: 1.5rem;
        }
        .filter-section {
            background-color: #ffffff;
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }
        .form-select, .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .form-select:focus, .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .btn-premium {
            padding: 0.6rem 1.2rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-premium-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        .btn-premium-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            transform: translateY(-1px);
            color: white;
        }
        .btn-premium-secondary {
            background-color: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }
        .btn-premium-secondary:hover {
            background-color: #e2e8f0;
            color: #334155;
            transform: translateY(-1px);
        }
        .table-premium {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            width: 100% !important;
        }
        .table-premium thead th {
            background-color: #1e293b !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.05em !important;
            padding: 1.1rem 1rem !important;
            border: none !important;
        }
        .table-premium thead th:first-child {
            border-top-left-radius: 0.75rem !important;
        }
        .table-premium thead th:last-child {
            border-top-right-radius: 0.75rem !important;
        }
        .table-premium td {
            background-color: #ffffff;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            padding: 1rem;
            vertical-align: middle;
        }
        .table-premium tr td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }
        .table-premium tr td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }
        .table-premium tr:hover td {
            background-color: #f8fafc;
        }
        .rule-type-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.6rem;
            border-radius: 2rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            display: inline-block;
        }
        .badge-override { background-color: #ecfdf5; color: #059669; }
        .badge-free_shipping { background-color: #eff6ff; color: #2563eb; }
        .badge-custom_cost { background-color: #fffbeb; color: #d97706; }
        .badge-percentage { background-color: #f5f3ff; color: #7c3aed; }
        .badge-delivery_area { background-color: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
        .badge-conditional { background-color: #fff7ed; color: #ea580c; }
        .priority-badge {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.6rem;
            border-radius: 0.375rem;
            display: inline-block;
        }
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.6rem;
            border-radius: 2rem;
            display: inline-block;
        }
        .status-active { background-color: #d1fae5; color: #065f46; }
        .status-inactive { background-color: #fee2e2; color: #991b1b; }
        .action-btn-group {
            display: flex;
            gap: 0.35rem;
        }
        .action-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background-color: white;
            padding: 0;
        }
        .action-btn:hover {
            transform: translateY(-1px);
        }
        .action-btn-toggle { color: #d97706; }
        .action-btn-toggle:hover { background-color: #fffbeb; border-color: #fde68a; }
        .action-btn-edit { color: #2563eb; }
        .action-btn-edit:hover { background-color: #eff6ff; border-color: #bfdbfe; }
        .action-btn-delete { color: #dc2626; }
        .action-btn-delete:hover { background-color: #fee2e2; border-color: #fca5a5; }
        .breadcrumb-premium .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s;
        }
        .breadcrumb-premium .breadcrumb-item a:hover {
            color: white;
        }
        .breadcrumb-premium .breadcrumb-item.active {
            color: white;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6 !important;
            color: white !important;
            border: 1px solid #3b82f6 !important;
            border-radius: 0.375rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important;
            color: #3b82f6 !important;
            border: 1px solid #e2e8f0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="shipping-rules-container">
        <!-- Header Section -->
        <div class="page-header-premium">
            <nav aria-label="breadcrumb" class="breadcrumb-premium">
                <ol class="breadcrumb mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shipping Rules</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 font-weight-bold">Shipping Rules</h3>
                    <p class="mb-0 text-white-50">Create and manage customized delivery rules for products and landing pages.</p>
                </div>
                <div class="d-flex gap-2">
                    <button id="btn-export-csv" class="btn btn-light btn-premium text-dark font-weight-medium">
                        <i class="fas fa-download text-muted"></i> Export to CSV
                    </button>
                    @can('shipping.rules.create')
                    <a href="{{ route('admin.shipping.rules.create') }}" class="btn btn-light btn-premium text-primary">
                        <i class="fas fa-plus"></i> Add New Rule
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-left: 4px solid #10b981; border-radius: 0.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filters Section -->
        <div class="filter-section shadow-sm">
            <form method="GET" action="{{ route('admin.shipping.rules.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted font-weight-medium small">Apply To</label>
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="product" {{ request('type') === 'product' ? 'selected' : '' }}>Products ({{ $productCount }})</option>
                        <option value="landing_page" {{ request('type') === 'landing_page' ? 'selected' : '' }}>Landing Pages ({{ $landingPageCount }})</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted font-weight-medium small">Rule Type</label>
                    <select name="rule_type" class="form-select">
                        <option value="">All Rule Types</option>
                        <option value="override" {{ request('rule_type') === 'override' ? 'selected' : '' }}>Override (Free)</option>
                        <option value="free_shipping" {{ request('rule_type') === 'free_shipping' ? 'selected' : '' }}>Free Shipping</option>
                        <option value="custom_cost" {{ request('rule_type') === 'custom_cost' ? 'selected' : '' }}>Custom Cost</option>
                        <option value="percentage" {{ request('rule_type') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                        <option value="delivery_area" {{ request('rule_type') === 'delivery_area' ? 'selected' : '' }}>Delivery Area</option>
                        <option value="conditional" {{ request('rule_type') === 'conditional' ? 'selected' : '' }}>Conditional</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted font-weight-medium small">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="">All Status</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-premium btn-premium-primary w-100 justify-content-center">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.shipping.rules.index') }}" class="btn btn-premium btn-premium-secondary">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Rules Table Section -->
        <div class="card-premium">
            @if($shippingRules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-premium" id="shippingRules">
                        <thead>
                            <tr>
                                <th>Rule ID</th>
                                <th>Serial</th>
                                <th>Type</th>
                                <th>Item</th>
                                <th>Rule Type</th>
                                <th>Value</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shippingRules as $rule)
                                <tr>
                                    <td><span class="text-muted font-weight-bold">#{{ $rule->id }}</span></td>
                                    <td></td>
                                    <td>
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">
                                            {{ $rule->ruleable_type === 'App\Models\Product' ? 'Product' : 'Landing Page' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="font-weight-semibold text-dark">{{ $rule->ruleable->title ?? 'N/A' }}</div>
                                        @if($rule->ruleable_type === 'App\Models\Product')
                                            <span class="text-muted small">ID: {{ $rule->ruleable_id }}</span>
                                        @else
                                            <span class="text-muted small">ID: {{ $rule->ruleable_id }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="rule-type-badge badge-{{ $rule->rule_type }}">
                                            {{ ucfirst(str_replace('_', ' ', $rule->rule_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($rule->rule_type === 'override')
                                            <span class="text-success font-weight-bold">Free Shipping</span>
                                        @elseif($rule->rule_type === 'free_shipping')
                                            <span class="text-primary font-weight-bold">Min: ৳{{ number_format($rule->free_shipping_threshold, 2) }}</span>
                                        @elseif($rule->rule_type === 'custom_cost')
                                            <span class="text-warning font-weight-bold">৳{{ number_format($rule->rule_value, 2) }}</span>
                                        @elseif($rule->rule_type === 'percentage')
                                            <span class="text-info font-weight-bold">{{ $rule->rule_value }}%</span>
                                        @elseif($rule->rule_type === 'delivery_area')
                                            <div>
                                                <div class="font-weight-medium text-dark">{{ $rule->delivery_area_name ?? 'N/A' }}</div>
                                                <span class="text-primary font-weight-bold">৳{{ number_format($rule->rule_value, 2) }}</span>
                                            </div>
                                        @else
                                            <span class="text-secondary">Conditional</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="priority-badge">{{ $rule->priority }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge {{ $rule->is_active ? 'status-active' : 'status-inactive' }}">
                                            {{ $rule->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td><span class="text-muted small">{{ $rule->created_at->format('M d, Y') }}</span></td>
                                    <td>
                                        <div class="action-btn-group justify-content-end">
                                            <form method="POST" action="{{ route('admin.shipping.rules.toggle', $rule) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="action-btn action-btn-toggle" 
                                                        title="{{ $rule->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas {{ $rule->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                </button>
                                            </form>
                                            @can('shipping.rules.update')
                                            <a href="{{ route('admin.shipping.rules.edit', $rule) }}" 
                                               class="action-btn action-btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @else
                                            <button type="button" class="action-btn" disabled title="No permission to edit">
                                                <i class="fas fa-edit text-muted"></i>
                                            </button>
                                            @endcan
                                            @can('shipping.rules.delete')
                                            <form method="POST" action="{{ route('admin.shipping.rules.destroy', $rule) }}" class="d-inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this shipping rule?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn action-btn-delete" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="action-btn" disabled title="No permission to delete">
                                                <i class="fas fa-trash-alt text-muted"></i>
                                            </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-truck-loading" style="font-size: 3.5rem; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-dark font-weight-bold">No Shipping Rules Found</h5>
                    <p class="text-muted small px-3">Create specific shipping overrides, delivery areas, or thresholds to optimize customer checkout.</p>
                    <a href="{{ route('admin.shipping.rules.create') }}" class="btn btn-premium btn-premium-primary mt-2">
                        <i class="fas fa-plus"></i> Add New Rule
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#shippingRules').DataTable({
                dom: 'lfrtip',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [0, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ],
                order: [[0, 'desc']], // Sort by Rule ID descending for newest first
                pageLength: 25,
                columnDefs: [
                    {
                        targets: 1, // Serial column (index 1)
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }
                ]
            });

            // Handle custom export click
            $('#btn-export-csv').on('click', function(e) {
                e.preventDefault();
                table.button('.buttons-csv').trigger();
            });
        });
    </script>
@endsection
