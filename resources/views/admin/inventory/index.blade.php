@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <style>
        .inventory-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stats-card.success {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        }
        .stats-card.warning {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        }
        .stats-card.danger {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
        }
        .stats-card.info {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
        }
        .filter-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .stock-info {
            font-size: 14px;
        }
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
        }
        .btn-group .btn {
            border-radius: 6px !important;
            margin-right: 2px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Inventory Management</li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">📦 Inventory Management</h4>
            <div>
                <a href="{{ route('admin.inventory.history') }}" class="btn btn-outline-info me-2">
                    <i class="fas fa-history"></i> View History
                </a>
                <a href="{{ route('admin.inventory.low-stock') }}" class="btn btn-outline-warning">
                    <i class="fas fa-exclamation-triangle"></i> Low Stock Alert
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1" id="stat-total-products">{{ number_format($stats['total_products']) }}</h3>
                            <p class="mb-0">Total Products</p>
                        </div>
                        <i class="fas fa-boxes fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1" id="stat-in-stock">{{ number_format($stats['in_stock_products']) }}</h3>
                            <p class="mb-0">In Stock</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1" id="stat-low-stock">{{ number_format($stats['low_stock_products']) }}</h3>
                            <p class="mb-0">Low Stock</p>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card danger">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1" id="stat-out-of-stock">{{ number_format($stats['out_of_stock_products']) }}</h3>
                            <p class="mb-0">Out of Stock</p>
                        </div>
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Overview -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-success mb-2" id="stat-retail-value">৳{{ number_format($stats['total_stock_value'], 2) }}</h4>
                        <p class="text-muted mb-0">Total Retail Value</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-info mb-2" id="stat-cost-value">৳{{ number_format($stats['total_cost_value'], 2) }}</h4>
                        <p class="text-muted mb-0">Total Cost Value</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-{{ $stats['total_profit'] > 0 ? 'success' : 'danger' }} mb-2" id="stat-profit">৳{{ number_format($stats['total_profit'], 2) }}</h4>
                        <p class="text-muted mb-0">Potential Profit</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body text-center">
                        <h4 class="text-warning mb-2" id="stat-wholesale-value">৳{{ number_format($stats['total_wholesale_value'], 2) }}</h4>
                        <p class="text-muted mb-0">Wholesale Value</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button type="button" id="clear-filters-btn" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-times"></i> Clear Filters
                </button>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <select id="primary-category-filter" class="form-select">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\ProductCategory::where('status', 'active')->orderBy('name')->get() as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="subcategory-filter" class="form-select" disabled>
                        <option value="">Select a primary category first</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select id="third-category-filter" class="form-select" disabled>
                        <option value="">Select a subcategory first</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-3">
                    <label for="stock-status-filter" class="form-label">Stock Status</label>
                    <select id="stock-status-filter" class="form-select">
                        <option value="">All Status</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                        <option value="on_backorder">On Backorder</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="product-type-filter" class="form-label">Product Type</label>
                    <select id="product-type-filter" class="form-select">
                        <option value="">All Types</option>
                        <option value="simple">Simple</option>
                        <option value="variable">Variable</option>
                        <option value="digital">Digital</option>
                        <option value="affiliate">Affiliate</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="form-check mt-4 pt-2">
                        <input class="form-check-input" type="checkbox" id="low-stock-filter">
                        <label class="form-check-label" for="low-stock-filter">
                            Show Low Stock Only
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="search-filter" class="form-label">Search</label>
                    <input type="text" id="search-filter" class="form-control" placeholder="Search products...">
                </div>
            </div>
        </div>

        <!-- Inventory Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📋 Product Inventory</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="inventory-table" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Stock Info</th>
                                <th>Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="adjustStockModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">📝 Adjust Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="adjust-stock-content">
                        <div class="text-center">
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
                search: $('#search-filter').val()
            };

            $.ajax({
                url: '{{ route("admin.inventory.filtered-stats") }}',
                type: 'GET',
                data: filterData,
                beforeSend: function() {
                    // Add loading state
                    $('.stats-card h3, .stats-card h4').css('opacity', '0.5');
                },
                success: function(stats) {
                    // Update product counts
                    $('#stat-total-products').text(stats.total_products.toLocaleString());
                    $('#stat-in-stock').text(stats.in_stock_products.toLocaleString());
                    $('#stat-low-stock').text(stats.low_stock_products.toLocaleString());
                    $('#stat-out-of-stock').text(stats.out_of_stock_products.toLocaleString());
                    
                    // Update financial values
                    $('#stat-retail-value').text('৳' + stats.total_stock_value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('#stat-cost-value').text('৳' + stats.total_cost_value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    $('#stat-wholesale-value').text('৳' + stats.total_wholesale_value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    
                    // Update profit with color
                    const profitElement = $('#stat-profit');
                    profitElement.text('৳' + stats.total_profit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
                    profitElement.removeClass('text-success text-danger').addClass(stats.total_profit > 0 ? 'text-success' : 'text-danger');
                    
                    // Remove loading state
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
                        // Category filters
                        d.primary_category_id = $('#primary-category-filter').val();
                        d.subcategory_id = $('#subcategory-filter').val();
                        d.third_category_id = $('#third-category-filter').val();
                        
                        // Other filters
                        d.stock_status = $('#stock-status-filter').val();
                        d.product_type = $('#product-type-filter').val();
                        d.low_stock_only = $('#low-stock-filter').is(':checked') ? '1' : '';
                        d.search = $('#search-filter').val();
                    }
                },
                columns: [
                    { data: 'product_info', name: 'title', orderable: true },
                    { data: 'stock_info', name: 'computed_quantity', orderable: true },
                    { data: 'value', name: 'value', orderable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
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

            $('#search-filter').on('keyup', function() {
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(function() {
                    table.draw();
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
                const productType = $(this).data('product-type');
                
                $('#adjustStockModal .modal-title').html('📝 Adjust Stock - ' + productTitle);
                $('#adjust-stock-content').html(`
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
                
                $('#adjustStockModal').modal('show');
                
                // Load adjustment form
                $.get('{{ route("admin.inventory.adjust-form", ":id") }}'.replace(':id', productId))
                    .done(function(response) {
                        $('#adjust-stock-content').html(response);
                    })
                    .fail(function() {
                        $('#adjust-stock-content').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Failed to load adjustment form. Please try again.
                            </div>
                        `);
                    });
            });

            // Clear filters button
            $('#clear-filters-btn').on('click', function() {
                // Reset all filter inputs
                $('#primary-category-filter').val('');
                $('#subcategory-filter').val('').prop('disabled', true).html('<option value="">Select a primary category first</option>');
                $('#third-category-filter').val('').prop('disabled', true).html('<option value="">Select a subcategory first</option>');
                $('#stock-status-filter').val('');
                $('#product-type-filter').val('');
                $('#low-stock-filter').prop('checked', false);
                $('#search-filter').val('');
                
                // Refresh table and statistics
                table.draw();
                updateStatistics();
            });
        });
    </script>
@endsection
