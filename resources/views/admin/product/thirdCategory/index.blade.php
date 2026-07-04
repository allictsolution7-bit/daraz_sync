@extends('layouts.master')

@section('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Third Level Categories</li>
            </ol>
        </nav>
        <h5>All Third Level Categories</h5> <hr>
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                <a href="{{ route('admin.third-categories.create') }}" class="btn btn-primary rounded mb-2 mb-sm-0">Add Third Category</a>
                <button type="button" id="deleteSelectedThirdCategories" class="btn btn-danger rounded" disabled>
                    Delete Selected
                </button>
            </div>
            <table class="table table-striped" id="thirdCategories">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" class="form-check-input" id="selectAllThirdCategories">
                </th>
                <th>ID</th>
                <th>Serial</th>
                <th>Image</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Status</th>
                <th>Sort Order</th>
                <th>Created Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($third_categories as $thirdCategory)
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input third-category-checkbox" name="ids[]" value="{{ $thirdCategory->id }}">
                    </td>
                    <td>{{ $thirdCategory->id }}</td>
                    <td></td>
                    <td>
                        @if($thirdCategory->image)
                            <img src="{{ asset($thirdCategory->image) }}" alt="{{ $thirdCategory->name }}" 
                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; border: 1px solid #dee2e6;">
                                <i class="text-muted">No Image</i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $thirdCategory->name }}</td>
                    <td>{{ $thirdCategory->slug }}</td>
                    <td>
                        @php
                            // Collect all unique categories from products using this third category
                            $allCategories = collect();
                            
                            // Use eager loaded products or load them
                            $products = $thirdCategory->relationLoaded('products') 
                                ? $thirdCategory->products 
                                : $thirdCategory->products()->with(['category', 'additionalCategories'])->get();
                            
                            foreach ($products as $product) {
                                // Add primary category if exists
                                if ($product->category) {
                                    $allCategories->push($product->category);
                                }
                                
                                // Add additional categories from pivot table
                                if ($product->additionalCategories && $product->additionalCategories->count() > 0) {
                                    $allCategories = $allCategories->merge($product->additionalCategories);
                                }
                            }
                            
                            // Include subcategory's parent category as fallback if no products found
                            if ($allCategories->isEmpty() && $thirdCategory->subCategory && $thirdCategory->subCategory->category) {
                                $allCategories->push($thirdCategory->subCategory->category);
                            }
                            
                            // Remove duplicates
                            $allCategories = $allCategories->filter()->unique('id');
                        @endphp
                        @if ($allCategories->count() > 0)
                            @foreach ($allCategories as $category)
                                <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>{{ $thirdCategory->subCategory->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $thirdCategory->status ? 'bg-success' : 'bg-danger' }}">
                            {{ $thirdCategory->status ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $thirdCategory->sort_order ?? 0 }}</td>
                    <td>{{ $thirdCategory->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.third-categories.edit', $thirdCategory->id) }}" 
                               class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <img src="{{ asset('edit.svg') }}" alt="Edit" width="20">
                            </a>
                            <form action="{{ route('admin.third-categories.destroy', $thirdCategory->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    title="Delete" onclick="return confirm('Are you sure? This will also remove this category from all products.')">
                                    <img src="{{ asset('delete.svg') }}" alt="Delete" width="20">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <form id="bulkDeleteThirdCategoriesForm" action="{{ route('admin.third-categories.bulk-delete') }}" method="POST" class="d-none">
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
            const thirdCategoriesTable = $('#thirdCategories').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy','pdf', 'csv', 'excel', 'print'
                ],
                order: [[9, 'asc'], [1, 'asc']], // Sort by Sort Order, then ID
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

            const selectAllThirdCategories = $('#selectAllThirdCategories');
            const deleteSelectedThirdCategories = $('#deleteSelectedThirdCategories');
            const thirdCategoryCheckboxSelector = '.third-category-checkbox';

            function updateThirdCategoryBulkDeleteState() {
                const total = $(thirdCategoryCheckboxSelector).length;
                const checked = $(thirdCategoryCheckboxSelector + ':checked').length;

                deleteSelectedThirdCategories.prop('disabled', checked === 0);

                if (checked === 0) {
                    selectAllThirdCategories.prop('indeterminate', false).prop('checked', false);
                } else if (checked === total) {
                    selectAllThirdCategories.prop('indeterminate', false).prop('checked', true);
                } else {
                    selectAllThirdCategories.prop('indeterminate', true).prop('checked', false);
                }
            }

            selectAllThirdCategories.on('change', function() {
                const isChecked = $(this).is(':checked');
                $(thirdCategoryCheckboxSelector).prop('checked', isChecked);
                selectAllThirdCategories.prop('indeterminate', false);
                updateThirdCategoryBulkDeleteState();
            });

            $('#thirdCategories').on('change', thirdCategoryCheckboxSelector, function() {
                updateThirdCategoryBulkDeleteState();
            });

            const bulkDeleteThirdCategoriesForm = $('#bulkDeleteThirdCategoriesForm');

            deleteSelectedThirdCategories.on('click', function() {
                const selected = $(thirdCategoryCheckboxSelector + ':checked');

                if (selected.length === 0) {
                    alert('Please select at least one third category to delete.');
                    return;
                }

                if (!confirm('Are you sure you want to delete the selected third categories?')) {
                    return;
                }

                bulkDeleteThirdCategoriesForm.find('input[name="ids[]"]').remove();

                selected.each(function() {
                    $('<input>', {
                        type: 'hidden',
                        name: 'ids[]',
                        value: $(this).val()
                    }).appendTo(bulkDeleteThirdCategoriesForm);
                });

                bulkDeleteThirdCategoriesForm.trigger('submit');
            });

            $('#thirdCategories').on('draw.dt', function() {
                updateThirdCategoryBulkDeleteState();
            });

            updateThirdCategoryBulkDeleteState();
        });
    </script>
@endsection

