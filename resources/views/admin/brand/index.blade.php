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
                <li class="breadcrumb-item active" aria-current="page">Brands</li>
            </ol>
        </nav>
        <h5>All Brands</h5> <hr>
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
            <a href="{{ route('admin.brands.create') }}" class="btn btn-primary rounded">Add Brand</a>
            <button type="button" id="deleteSelectedBrands" class="btn btn-danger rounded" disabled>
                Delete Selected
            </button>
        </div>
        <table class="table table-striped" id="brands">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="form-check-input" id="selectAllBrands">
                    </th>
                    <th>Brand ID</th>
                    <th>Serial</th>
                    <th>Logo</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($brands as $brand)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input brand-checkbox" name="ids[]" value="{{ $brand->id }}">
                        </td>
                        <td>{{ $brand->id }}</td>
                        <td></td>
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" 
                                     class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; border: 1px solid #dee2e6;">
                                    <i class="text-muted">No Logo</i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $brand->name }}</td>
                        <td>{{ $brand->slug }}</td>
                        <td>
                            <span class="badge {{ $brand->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $brand->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $brand->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.brands.edit', $brand) }}" 
                                   class="btn btn-sm btn-outline-primary me-1" title="Edit">
                                    <img src="{{ asset('edit.svg') }}" alt="Edit" width="20">
                                </a>
                                <a href="{{ route('shop') }}?brand={{ $brand->id }}" 
                                   class="btn btn-sm btn-outline-info me-1" title="View Frontend" 
                                   target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-secondary me-1" 
                                        title="Copy Full URL" onclick="copyToClipboard('{{ route('shop') }}?brand={{ $brand->id }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" 
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
        <form id="bulkDeleteBrandsForm" action="{{ route('admin.brands.bulk-delete') }}" method="POST" class="d-none">
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
            const brandsTable = $('#brands').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy','pdf', 'csv', 'excel', 'print'
                ],
                order: [[1, 'asc']], // Sort by Brand ID ascending
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

            const selectAllBrands = $('#selectAllBrands');
            const deleteSelectedBrands = $('#deleteSelectedBrands');
            const brandCheckboxSelector = '.brand-checkbox';
            const bulkDeleteBrandsForm = $('#bulkDeleteBrandsForm');

            function updateBrandBulkDeleteState() {
                const total = $(brandCheckboxSelector).length;
                const checked = $(brandCheckboxSelector + ':checked').length;

                deleteSelectedBrands.prop('disabled', checked === 0);

                if (checked === 0) {
                    selectAllBrands.prop('indeterminate', false).prop('checked', false);
                } else if (checked === total) {
                    selectAllBrands.prop('indeterminate', false).prop('checked', true);
                } else {
                    selectAllBrands.prop('indeterminate', true).prop('checked', false);
                }
            }

            selectAllBrands.on('change', function() {
                const isChecked = $(this).is(':checked');
                $(brandCheckboxSelector).prop('checked', isChecked);
                selectAllBrands.prop('indeterminate', false);
                updateBrandBulkDeleteState();
            });

            $('#brands').on('change', brandCheckboxSelector, function() {
                updateBrandBulkDeleteState();
            });

            deleteSelectedBrands.on('click', function() {
                const selected = $(brandCheckboxSelector + ':checked');

                if (selected.length === 0) {
                    alert('Please select at least one brand to delete.');
                    return;
                }

                if (!confirm('Are you sure you want to delete the selected brands?')) {
                    return;
                }

                bulkDeleteBrandsForm.find('input[name="ids[]"]').remove();

                selected.each(function() {
                    $('<input>', {
                        type: 'hidden',
                        name: 'ids[]',
                        value: $(this).val()
                    }).appendTo(bulkDeleteBrandsForm);
                });

                bulkDeleteBrandsForm.trigger('submit');
            });

            $('#brands').on('draw.dt', function() {
                updateBrandBulkDeleteState();
            });

            updateBrandBulkDeleteState();
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
