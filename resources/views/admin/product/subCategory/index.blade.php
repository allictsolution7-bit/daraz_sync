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
                <li class="breadcrumb-item active" aria-current="page">Sub Categories</li>
            </ol>
        </nav>
        <h5>All Sub Categories</h5> <hr>
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                <a href="{{route ("admin.sub-categories.create")}}" class="btn btn-primary rounded">Add SubCategory</a>
                <button type="button" id="deleteSelectedSubcategories" class="btn btn-danger rounded" disabled>
                    Delete Selected
                </button>
            </div>
            <table class="table table-striped" id="subcategories">
            <thead>
            <tr>
                <th>
                    <input type="checkbox" class="form-check-input" id="selectAllSubcategories">
                </th>
                <th>Category ID</th>
                <th>Serial</th>
                <th>Image</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Category</th>
                <th>Status</th>
                <th>Created Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($sub_categories as $subcategory)
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input subcategory-checkbox" name="ids[]" value="{{ $subcategory->id }}">
                    </td>
                    <td>{{ $subcategory->product_category_id }}</td>
                    <td></td>
                    <td>
                        @if($subcategory->image)
                            <img src="{{ asset($subcategory->image) }}" alt="{{ $subcategory->name }}" 
                                 class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px; border: 1px solid #dee2e6;">
                                <i class="text-muted">No Image</i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $subcategory->name }}</td>
                    <td>{{ $subcategory->slug }}</td>
                    <td>{{ $subcategory->category->name ?? 'N/A' }}</td>
                    <td>
                        <span class="badge {{ $subcategory->status == 1 ? 'bg-success' : 'bg-danger' }}">
                            {{ $subcategory->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $subcategory->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.sub-categories.edit', $subcategory->id) }}" 
                               class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                <img src="{{ asset('edit.svg') }}" alt="Edit" width="20">
                            </a>
                            <a href="/shop/{{ $subcategory->category->slug ?? 'category' }}/{{ $subcategory->slug }}" 
                               class="btn btn-sm btn-outline-info me-1" title="View Frontend" 
                               target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                                    title="Copy Full URL" onclick="copyToClipboard('{{ url('/shop/' . ($subcategory->category->slug ?? 'category') . '/' . $subcategory->slug) }}')">
                                <i class="fas fa-copy"></i>
                            </button>
                            <form action="{{ route('admin.sub-categories.destroy', $subcategory->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    title="Delete" onclick="return confirm('Are you sure?')">
                                    <img src="{{ asset('delete.svg') }}" alt="Delete" width="20">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>

    <script>
        $(document).ready(function() {
            const subcategoriesTable = $('#subcategories').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy','pdf', 'csv', 'excel', 'print'
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
                    <div class="toast show" role="alert">
                        <div class="toast-header">
                            <strong class="me-auto">Copied!</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                        </div>
                        <div class="toast-body">
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
