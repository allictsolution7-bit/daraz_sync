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
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>
        <h5>All Categories</h5> <hr>
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                <a href="{{route ("admin.product_categories.create")}}" class="btn btn-primary rounded">Add Category</a>
                <button type="button" id="deleteSelectedCategories" class="btn btn-danger rounded" disabled>
                    Delete Selected
                </button>
            </div>
            <table class="table table-striped" id="categories">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="form-check-input" id="selectAllCategories">
                    </th>
                    <th>Category ID</th>
                    <th>Serial</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input category-checkbox" name="ids[]" value="{{ $category->id }}">
                        </td>
                        <td>{{ $category->id }}</td>
                        <td></td>
                        <td>
                            @if($category->image)
                                <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" 
                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; border: 1px solid #dee2e6;">
                                    <i class="text-muted">No Image</i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>
                            <span class="badge {{ $category->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($category->status) }}
                            </span>
                        </td>
                        <td>{{ $category->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.product_categories.edit', $category->id) }}" 
                                   class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <img src="{{ asset('edit.svg') }}" alt="Edit" width="20">
                                </a>
                                <a href="/shop/{{ $category->slug }}" 
                                   class="btn btn-sm btn-outline-info me-1" title="View Frontend" 
                                   target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                                        title="Copy Full URL" onclick="copyToClipboard('{{ url('/shop/' . $category->slug) }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <form action="{{ route('admin.product_categories.destroy', $category->id) }}" 
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
        <form id="bulkDeleteCategoriesForm" action="{{ route('admin.product_categories.bulk-delete') }}" method="POST" class="d-none">
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
            const categoriesTable = $('#categories').DataTable({
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

            const selectAllCategories = $('#selectAllCategories');
            const deleteSelectedCategories = $('#deleteSelectedCategories');
            const checkboxSelector = '.category-checkbox';

            function updateBulkDeleteState() {
                const total = $(checkboxSelector).length;
                const checked = $(checkboxSelector + ':checked').length;

                deleteSelectedCategories.prop('disabled', checked === 0);

                if (checked === 0) {
                    selectAllCategories.prop('indeterminate', false).prop('checked', false);
                } else if (checked === total) {
                    selectAllCategories.prop('indeterminate', false).prop('checked', true);
                } else {
                    selectAllCategories.prop('indeterminate', true).prop('checked', false);
                }
            }

            selectAllCategories.on('change', function() {
                const isChecked = $(this).is(':checked');
                $(checkboxSelector).prop('checked', isChecked);
                selectAllCategories.prop('indeterminate', false);
                updateBulkDeleteState();
            });

            $('#categories').on('change', checkboxSelector, function() {
                updateBulkDeleteState();
            });

            const bulkDeleteForm = $('#bulkDeleteCategoriesForm');

            deleteSelectedCategories.on('click', function() {
                const selected = $(checkboxSelector + ':checked');

                if (selected.length === 0) {
                    alert('Please select at least one category to delete.');
                    return;
                }

                if (!confirm('Are you sure you want to delete the selected categories?')) {
                    return;
                }

                bulkDeleteForm.find('input[name="ids[]"]').remove();

                selected.each(function() {
                    $('<input>', {
                        type: 'hidden',
                        name: 'ids[]',
                        value: $(this).val()
                    }).appendTo(bulkDeleteForm);
                });

                bulkDeleteForm.trigger('submit');
            });

            $('#categories').on('draw.dt', function() {
                updateBulkDeleteState();
            });

            updateBulkDeleteState();
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
