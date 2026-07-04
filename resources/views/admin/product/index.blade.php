@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/datetime/1.5.0/css/dataTables.dateTime.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/colreorder/1.7.0/css/colReorder.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" rel="stylesheet">
    <style>
        .filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap:5px;
            align-items: end;
        }
        .filter-group {
            flex: 1;
            min-width: 150px;
        }
        .filter-group label {
            font-weight: 600;
            margin-bottom: 5px;
            display: block;
        }
        .filter-group select,
        .filter-group input {
            width: 100%;
            height: 38px;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }
        .filter-actions {
            display: flex;
            gap: 10px;
            align-items: end;
        }
        .btn-filter {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        .btn-clear {
            background: #6c757d;
            color: white;
        }
        .btn-apply {
            background: #197A94;
            color: white;
        }
        .btn-apply:hover {
            background: #0056b3;
        }
        .btn-clear:hover {
            background: #545b62;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        .price-high {
            color: #28a745;
            font-weight: 600;
        }
        .price-medium {
            color: #ffc107;
            font-weight: 600;
        }
        .price-low {
            color: #dc3545;
            font-weight: 600;
        }
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 10px;
        }
        .dt-button {
            background: #197A94 !important;
            color: white !important;
            border: none !important;
            padding: 8px 16px !important;
            border-radius: 4px !important;
            margin-right: 5px !important;
        }
        .dt-button:hover {
            background: #0056b3 !important;
        }
        .bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: -10px;
        }
        .dataTables_length{
           margin-top: 10px;
           margin-bottom: 0 !important;
        }
        .dataTables_info{
            margin-top: 0 !important;
        }
        
        /* Action buttons styling */
        .btn-outline-info {
            margin-right: 5px;
        }
        
        .btn-outline-primary {
            margin-right: 5px;
        }
        
        .btn-outline-secondary {
            margin-right: 5px;
        }
        
        .btn-outline-danger {
            margin-right: 5px;
        }
        
        /* Copy link button success state */
        .copy-link-btn.btn-success {
            border-color: #28a745;
            color: #28a745;
        }
        
        .copy-link-btn.btn-success:hover {
            background-color: #28a745;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container-flud mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </nav>
        <h5 class="mb-2">All Products</h5>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-row">
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
                        <option value="">All Status</option>
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
                    <input type="number" id="price-min" placeholder="Min Price" min="0">
                </div>
                <div class="filter-group">
                    <input type="number" id="price-max" placeholder="Max Price" min="0">
                </div>
                <div class="filter-group">
                    <input type="date" id="date-from">
                </div>
                <div class="filter-group">
                    <input type="date" id="date-to">
                </div>
                <div class="filter-actions">
                    <button class="btn-filter btn-apply" id="apply-filters"><i class="fas fa-filter"></i> Apply Filters</button>
                    <button class="btn-filter btn-clear" id="clear-filters"><i class="fas fa-times"></i> Clear All</button>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
            <a href="{{route ("admin.product.create")}}" class="btn btn-primary rounded">Add Product</a>
            <button id="bulk-delete-products" class="btn btn-danger rounded"><i class="fas fa-trash-alt"></i> Bulk Delete</button>
            <button id="bulk-status-toggle" class="btn btn-warning rounded"><i class="fas fa-toggle-on"></i> Toggle Status</button>
            <button id="export-selected" class="btn btn-success rounded"><i class="fas fa-download"></i> Export Selected</button>
        </div>
        
        <table class="table table-striped" id="Products" style="width:100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-products"></th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Views (T/U)</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
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

    <script>
        $(document).ready(function() {
            const selectedProductIds = new Set();
            let customFilters = {};

            const table = $('#Products').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.products.data') }}',
                    type: 'GET',
                    data: function(d) {
                        // Add custom filters to the request
                        return $.extend({}, d, customFilters);
                    }
                },
                columns: [
                    { data: 'checkbox', orderable: false, searchable: false },
                    { data: 'id' },
                    { data: 'title', render: function(data) {
                        if (!data) return '<span class="text-muted">N/A</span>';
                        const maxLength = 30;
                        if (data.length > maxLength) {
                            return `<span title="${data}">${data.substring(0, maxLength)}...</span>`;
                        }
                        return data;
                    }},
                    { data: 'thumb_image', orderable: false, searchable: false, render: function(path){
                        if (!path) return '<span class="text-muted">No Image</span>';
                        const url = path.startsWith('http') ? path : `{{ asset('storage') }}/${path}`;
                        return `<img src="${url}" width="28" class="rounded">`;
                    }},
                    { data: 'category_display', render: function(data) {
                        return data || '<span class="text-muted">N/A</span>';
                    }},
                    { data: 'product_type', render: function(data) {
                        const types = {
                            'simple': '<span class="badge bg-primary">Simple</span>',
                            'variable': '<span class="badge bg-info">Variable</span>',
                            'digital': '<span class="badge bg-success">Digital</span>',
                            'affiliate': '<span class="badge bg-warning">Affiliate</span>'
                        };
                        return types[data] || '<span class="badge bg-secondary">Unknown</span>';
                    }},
                    { data: 'price_display', render: function(data, type, row) {
                        if (!data) return '<span class="text-muted">N/A</span>';
                        
                        // Check if it's a price range (contains '-')
                        if (typeof data === 'string' && data.includes(' - ')) {
                            return `<span class="price-high">৳${data}</span>`;
                        }
                        
                        // Single price
                        const price = parseFloat(data);
                        if (isNaN(price)) return '<span class="text-muted">N/A</span>';
                        
                        return `<span class="price-high">৳${price.toFixed(2)}</span>`;
                    }},
                    { data: 'views', orderable: false, searchable: false, render: function(data) {
                        if (!data) return '<span class="text-muted">0 / 0</span>';
                        return data;
                    }},
                    { data: 'status', render: function(data) {
                        return data == 1 ? 
                            '<span class="status-badge status-active">Active</span>' : 
                            '<span class="status-badge status-inactive">Inactive</span>';
                    }},
                    { data: 'created_at', render: function(data) {
                        if (!data) return '<span class="text-muted">N/A</span>';
                        return new Date(data).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                    }},
                    { data: 'actions', orderable: false, searchable: false }
                ],
                dom: '<"top"Bf>rt<"bottom"lip>',
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy"></i> Copy',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-sm btn-outline-secondary'
                    },
                    {
                        extend: 'colvis',
                        text: '<i class="fas fa-columns"></i> Columns',
                        className: 'btn btn-sm btn-outline-secondary'
                    }
                ],
                responsive: true,
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

                const url = '{{ route("admin.get-product-subcategories", ':id') }}'.replace(':id', categoryId);

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (Array.isArray(data) && data.length) {
                            setSubcategoryOptions(data, 'All Subcategories', false);
                        } else {
                            setSubcategoryOptions(null, 'No subcategories available', true);
                        }
                    })
                    .catch(() => {
                        setSubcategoryOptions(null, 'Failed to load subcategories', true);
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
                        alert('Please select at least one product to delete.');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete the selected products? This action cannot be undone.')) {
                        return;
                    }

                    bulkDeleteBtn.disabled = true;
                    const originalHtml = bulkDeleteBtn.innerHTML;
                    bulkDeleteBtn.innerHTML = 'Deleting...';

                    fetch("{{ route('admin.products.bulk-delete') }}", {
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
                            alert(data.message || 'Selected products deleted successfully!');
                            selectedProductIds.clear();
                            table.ajax.reload();
                        } else {
                            alert(data.message || 'Failed to delete selected products.');
                        }
                    })
                    .catch(() => {
                        alert('An error occurred while deleting products.');
                    })
                    .finally(() => {
                        bulkDeleteBtn.disabled = false;
                        bulkDeleteBtn.innerHTML = originalHtml;
                    });
                });
            }

            // Bulk status toggle
            const bulkStatusToggleBtn = document.getElementById('bulk-status-toggle');
            if (bulkStatusToggleBtn) {
                bulkStatusToggleBtn.addEventListener('click', function() {
                    const selected = Array.from(selectedProductIds);

                    if (selected.length === 0) {
                        alert('Please select at least one product to toggle status.');
                        return;
                    }

                    const newStatus = confirm('Toggle status for selected products? Click OK for Active, Cancel for Inactive.') ? 1 : 0;

                    bulkStatusToggleBtn.disabled = true;
                    const originalHtml = bulkStatusToggleBtn.innerHTML;
                    bulkStatusToggleBtn.innerHTML = 'Updating...';

                    fetch("{{ route('admin.products.bulk-status-toggle') }}", {
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
                            alert(data.message || 'Status updated successfully!');
                            table.ajax.reload();
                        } else {
                            alert(data.message || 'Failed to update status.');
                        }
                    })
                    .catch(() => {
                        alert('An error occurred while updating status.');
                    })
                    .finally(() => {
                        bulkStatusToggleBtn.disabled = false;
                        bulkStatusToggleBtn.innerHTML = originalHtml;
                    });
                });
            }

            // Export selected
            const exportSelectedBtn = document.getElementById('export-selected');
            if (exportSelectedBtn) {
                exportSelectedBtn.addEventListener('click', function() {
                    const selected = Array.from(selectedProductIds);

                    if (selected.length === 0) {
                        alert('Please select at least one product to export.');
                        return;
                    }

                    // Create a temporary form to download the export
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('admin.products.export-selected') }}";
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    
                    const productIds = document.createElement('input');
                    productIds.type = 'hidden';
                    productIds.name = 'product_ids';
                    productIds.value = JSON.stringify(selected);
                    
                    form.appendChild(csrfToken);
                    form.appendChild(productIds);
                    document.body.appendChild(form);
                    form.submit();
                    document.body.removeChild(form);
                });
            }

            // Copy link functionality
            $(document).on('click', '.copy-link-btn', function() {
                const url = $(this).data('url');
                const button = $(this);
                const originalHtml = button.html();
                
                // Copy to clipboard
                navigator.clipboard.writeText(url).then(function() {
                    // Show success feedback
                    button.html('<i class="fas fa-check"></i>');
                    button.removeClass('btn-outline-secondary').addClass('btn-success');
                    
                    // Reset button after 2 seconds
                    setTimeout(function() {
                        button.html(originalHtml);
                        button.removeClass('btn-success').addClass('btn-outline-secondary');
                    }, 2000);
                }).catch(function() {
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    
                    // Show success feedback
                    button.html('<i class="fas fa-check"></i>');
                    button.removeClass('btn-outline-secondary').addClass('btn-success');
                    
                    // Reset button after 2 seconds
                    setTimeout(function() {
                        button.html(originalHtml);
                        button.removeClass('btn-success').addClass('btn-outline-secondary');
                    }, 2000);
                });
            });
        });
    </script>
@endsection
