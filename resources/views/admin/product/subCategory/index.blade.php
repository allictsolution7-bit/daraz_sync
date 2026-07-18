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

        /* Action Toolbar */
        .action-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }

        /* Premium Buttons */
        .btn-add-category {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            padding: 0.6rem 1.35rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .btn-add-category:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
            color: #ffffff;
        }
        .btn-add-category:active {
            transform: translateY(0);
        }

        .btn-delete-selected {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-delete-selected:hover:not(:disabled) {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }
        .btn-delete-selected:disabled {
            background: #f1f5f9;
            color: #94a3b8;
            border-color: #e2e8f0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Modernized Table Design */
        .premium-table-wrapper {
            border-radius: 16px;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.015);
        }
        .premium-table {
            width: 100% !important;
            margin: 0 !important;
            border-collapse: collapse;
            white-space: nowrap;
        }
        
        /* Hide default sorting icons globally for this table to prevent duplicates */
        .premium-table thead th::before,
        .premium-table thead th::after {
            display: none !important;
            content: "" !important;
        }

        /* SVG sorting indicators */
        .premium-table thead th.sorting {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12"><path fill="%2364748b" d="M4 0l4 4H0zm0 12L0 8h8z"/></svg>') !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            padding-right: 32px !important;
            cursor: pointer;
        }
        .premium-table thead th.sorting_asc {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12"><path fill="%234f46e5" d="M4 0l4 4H0z"/></svg>') !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            padding-right: 32px !important;
            cursor: pointer;
        }
        .premium-table thead th.sorting_desc {
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12"><path fill="%234f46e5" d="M4 12L0 8h8z"/></svg>') !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            padding-right: 32px !important;
            cursor: pointer;
        }

        .premium-table thead th {
            background: #0f172a !important; /* Premium Slate Dark Header */
            color: #f1f5f9 !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.775rem;
            letter-spacing: 0.06em;
            padding: 1.25rem 1rem !important;
            border-bottom: none !important;
        }
        .premium-table tbody tr {
            background: #ffffff;
            transition: all 0.25s ease;
        }
        .premium-table tbody tr:hover {
            background: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }
        .premium-table tbody td {
            padding: 1.1rem 1rem !important;
            vertical-align: middle !important;
            color: #334155;
            font-size: 0.95rem;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        /* Elegant Category Image Frame */
        .category-image-frame {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            border: 2px solid #ffffff;
            transition: all 0.3s ease;
        }
        .category-image-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .category-image-frame:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }
        .category-image-frame:hover img {
            transform: scale(1.15);
        }

        .category-no-image {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Glimmer Status Pills */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }
        .status-active {
            background: rgba(34, 197, 94, 0.12);
            color: #166534;
        }
        .status-active::before {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(34, 197, 94, 0.8);
        }
        .status-inactive {
            background: rgba(239, 68, 68, 0.12);
            color: #991b1b;
        }
        .status-inactive::before {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            background: #ef4444;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.8);
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
        }
        .btn-action-custom:hover {
            transform: translateY(-2px);
        }
        .btn-action-edit:hover {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }
        .btn-action-view:hover {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
        }
        .btn-action-copy:hover {
            background: rgba(139, 92, 246, 0.12);
            color: #7c3aed;
        }
        .btn-action-delete:hover {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }

        /* Beautiful Checkbox Styling */
        .custom-checkbox-wrapper {
            position: relative;
            display: inline-block;
        }
        .custom-checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            border-radius: 6px;
            border: 2px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.2s ease;
            accent-color: #4f46e5;
        }

        /* Datatable Styles Overwrite */
        .dataTables_wrapper .dataTables_filter {
            margin-top: 0.75rem !important;
            margin-bottom: 0.75rem !important;
            background: rgba(241, 245, 249, 0.8) !important;
            padding: 6px 12px !important;
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
            display: inline-flex !important;
            align-items: center;
            gap: 8px;
        }
        .dataTables_wrapper .dataTables_filter label {
            margin: 0 !important;
            color: #475569 !important;
            font-weight: 500;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #cbd5e1 !important;
            border-radius: 8px !important;
            padding: 0.35rem 0.75rem !important;
            font-size: 0.875rem !important;
            background: #ffffff !important;
            outline: none !important;
            box-shadow: none !important;
            transition: all 0.2s ease !important;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }
        .dt-buttons {
            display: inline-flex !important;
            gap: 0.5rem;
            margin-top: 0.75rem !important;
            margin-bottom: 0.75rem !important;
            background: rgba(241, 245, 249, 0.8) !important;
            padding: 6px 10px !important;
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
        }
        .dt-buttons .dt-button {
            background: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #475569 !important;
            border-radius: 8px !important;
            padding: 0.45rem 1rem !important;
            font-weight: 600 !important;
            font-size: 0.85rem !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02) !important;
            transition: all 0.2s ease !important;
        }
        .dt-buttons .dt-button:hover {
            background: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #ffffff !important;
            transform: translateY(-1px);
        }

        /* Custom styling for standard bootstrap pagination elements inside dataTables */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f46e5 !important;
            color: #ffffff !important;
            border: 1px solid #4f46e5 !important;
            border-radius: 8px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            font-weight: 500 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            border: 1px solid #cbd5e1 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container categories-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Sub Categories</li>
            </ol>
        </nav>

        <div class="glass-card">
            <!-- Header Section -->
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h1 class="page-header-title">Product Sub Categories</h1>
                    <p class="page-header-subtitle">Manage and organize your sub-level categories, mappings, and metadata.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="action-toolbar justify-content-md-end">
                        <button type="button" id="deleteSelectedSubcategories" class="btn-delete-selected" disabled>
                            <i class="fa-solid fa-trash-can"></i> Delete Selected
                        </button>
                        <a href="{{ route('admin.sub-categories.create') }}" class="btn-add-category">
                            <i class="fa-solid fa-plus"></i> Add SubCategory
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="premium-table-wrapper">
                <table class="table premium-table" id="subcategories">
                    <thead>
                        <tr>
                            <th width="40" class="text-center">
                                <div class="custom-checkbox-wrapper">
                                    <input type="checkbox" class="form-check-input" id="selectAllSubcategories">
                                </div>
                            </th>
                            <th width="120">Category ID</th>
                            <th width="80">Serial</th>
                            <th width="100">Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Parent Category</th>
                            <th width="120">Status</th>
                            <th width="140">Created Date</th>
                            <th width="160" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sub_categories as $subcategory)
                            <tr>
                                <td class="text-center">
                                    <div class="custom-checkbox-wrapper">
                                        <input type="checkbox" class="form-check-input subcategory-checkbox" name="ids[]" value="{{ $subcategory->id }}">
                                    </div>
                                </td>
                                <td class="font-monospace text-muted">#{{ $subcategory->product_category_id }}</td>
                                <td></td>
                                <td>
                                    @if($subcategory->image)
                                        <div class="category-image-frame">
                                            <img src="{{ asset($subcategory->image) }}" alt="{{ $subcategory->name }}">
                                        </div>
                                    @else
                                        <div class="category-no-image">
                                            <i class="fa-regular fa-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-semibold text-slate-800">{{ $subcategory->name }}</td>
                                <td class="text-muted">{{ $subcategory->slug }}</td>
                                <td class="fw-semibold text-indigo-600">{{ $subcategory->category->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-pill {{ $subcategory->status == 1 ? 'status-active' : 'status-inactive' }}">
                                        {{ $subcategory->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-secondary">{{ $subcategory->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1" role="group">
                                        <a href="{{ route('admin.sub-categories.edit', $subcategory->id) }}" 
                                           class="btn-action-custom btn-action-edit" title="Edit Sub Category">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <a href="/shop/{{ $subcategory->category->slug ?? 'category' }}/{{ $subcategory->slug }}" 
                                           class="btn-action-custom btn-action-view" title="View Frontend" 
                                           target="_blank">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn-action-custom btn-action-copy" 
                                                title="Copy Full URL" onclick="copyToClipboard('{{ url('/shop/' . ($subcategory->category->slug ?? 'category') . '/' . $subcategory->slug) }}')">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                        <form action="{{ route('admin.sub-categories.destroy', $subcategory->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-custom btn-action-delete" 
                                                    title="Delete Sub Category" onclick="return confirm('Are you sure you want to delete this sub category?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <form id="bulkDeleteSubcategoriesForm" action="{{ route('admin.sub-categories.bulk-delete') }}" method="POST" class="d-none">
            @csrf
        </form>
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
            const subcategoriesTable = $('#subcategories').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'csv',
                        text: '<i class="fa-solid fa-file-export me-1"></i> Export'
                    },
                    'print'
                ],
                order: [[1, 'asc']], // Sort by Category ID ascending
                pageLength: 25,
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        targets: 2, // Serial column (index 2)
                        searchable: false,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }
                ]
            });

            const selectAllSubcategories = $('#selectAllSubcategories');
            const deleteSelectedSubcategories = $('#deleteSelectedSubcategories');
            const subcategoryCheckboxSelector = '.subcategory-checkbox';

            function updateSubcategoryBulkDeleteState() {
                const total = $(subcategoryCheckboxSelector).length;
                const checked = $(subcategoryCheckboxSelector + ':checked').length;

                deleteSelectedSubcategories.prop('disabled', checked === 0);

                if (checked === 0) {
                    selectAllSubcategories.prop('indeterminate', false).prop('checked', false);
                } else if (checked === total) {
                    selectAllSubcategories.prop('indeterminate', false).prop('checked', true);
                } else {
                    selectAllSubcategories.prop('indeterminate', true).prop('checked', false);
                }
            }

            selectAllSubcategories.on('change', function() {
                const isChecked = $(this).is(':checked');
                $(subcategoryCheckboxSelector).prop('checked', isChecked);
                selectAllSubcategories.prop('indeterminate', false);
                updateSubcategoryBulkDeleteState();
            });

            $('#subcategories').on('change', subcategoryCheckboxSelector, function() {
                updateSubcategoryBulkDeleteState();
            });

            const bulkDeleteSubcategoriesForm = $('#bulkDeleteSubcategoriesForm');

            deleteSelectedSubcategories.on('click', function() {
                const selected = $(subcategoryCheckboxSelector + ':checked');

                if (selected.length === 0) {
                    alert('Please select at least one sub category to delete.');
                    return;
                }

                if (!confirm('Are you sure you want to delete the selected sub categories?')) {
                    return;
                }

                bulkDeleteSubcategoriesForm.find('input[name="ids[]"]').remove();

                selected.each(function() {
                    $('<input>', {
                        type: 'hidden',
                        name: 'ids[]',
                        value: $(this).val()
                    }).appendTo(bulkDeleteSubcategoriesForm);
                });

                bulkDeleteSubcategoriesForm.trigger('submit');
            });

            $('#subcategories').on('draw.dt', function() {
                updateSubcategoryBulkDeleteState();
            });

            updateSubcategoryBulkDeleteState();
        });

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'position-fixed top-0 end-0 p-3';
                toast.style.zIndex = '9999';
                toast.innerHTML = `
                    <div class="toast show" role="alert" style="border-radius: 12px; border: 1px solid #e2e8f0; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(8px); box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                        <div class="toast-header" style="background: transparent; border-bottom: 1px solid #f1f5f9; color: #4f46e5;">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            <strong class="me-auto" style="font-family: 'Outfit', sans-serif;">Copied!</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body" style="font-family: 'Outfit', sans-serif; color: #475569; font-size: 0.9rem;">
                            "${text}" copied to clipboard
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                
                // Remove toast after 3 seconds
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endsection
