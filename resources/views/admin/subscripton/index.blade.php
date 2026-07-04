@extends('layouts.master')

@section('title', 'Newsletter Subscriptions')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
<style>
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 15px;
    }
    .dt-buttons {
        margin-bottom: 15px;
    }
</style>
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Newsletter Subscriptions</h5>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-primary" id="refreshTable">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" id="bulkDeleteBtn" disabled>
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form id="bulkDeleteForm" action="{{ route('admin.subscriptions.bulk-delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="table-responsive">
                            <table id="subscriptions-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th>ID</th>
                                        <th>Email</th>
                                        <th>Country</th>
                                        <th>City</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subscriptions as $subscription)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_ids[]" value="{{ $subscription->id }}" class="subscription-checkbox">
                                            </td>
                                            <td>{{ $subscription->id }}</td>
                                            <td>{{ $subscription->email }}</td>
                                            <td>{{ $subscription->country ?? 'N/A' }}</td>
                                            <td>{{ $subscription->city ?? 'N/A' }}</td>
                                            <td data-order="{{ $subscription->created_at->timestamp }}">
                                                {{ $subscription->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.subscriptions.toggle-status', $subscription) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $subscription->status ? 'btn-success' : 'btn-danger' }}">
                                                        {{ $subscription->status ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <form action="{{ route('admin.subscriptions.destroy', $subscription) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subscription?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No subscriptions found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.4/js/dataTables.select.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#subscriptions-table').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                },
                'colvis'
            ],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 25,
            order: [[1, 'desc']], // Sort by ID descending by default
            columnDefs: [
                { orderable: false, targets: [0, 7] },     // Checkbox and Actions columns are not sortable
                { responsivePriority: 1, targets: [2, 7] }, // Email and Actions columns have priority
                { responsivePriority: 2, targets: 6 }      // Status column has second priority
            ]
        });
        
        // Select All checkbox functionality
        $('#select-all').on('click', function() {
            $('.subscription-checkbox').prop('checked', this.checked);
            updateBulkDeleteButton();
        });
        
        // Individual checkbox change event
        $(document).on('change', '.subscription-checkbox', function() {
            updateBulkDeleteButton();
            
            // If any checkbox is unchecked, uncheck the "select all" checkbox
            if (!this.checked) {
                $('#select-all').prop('checked', false);
            }
            
            // If all checkboxes are checked, check the "select all" checkbox
            else if ($('.subscription-checkbox:checked').length === $('.subscription-checkbox').length) {
                $('#select-all').prop('checked', true);
            }
        });
        
        // Update bulk delete button state
        function updateBulkDeleteButton() {
            var selectedCount = $('.subscription-checkbox:checked').length;
            $('#bulkDeleteBtn').prop('disabled', selectedCount === 0);
            
            if (selectedCount > 0) {
                $('#bulkDeleteBtn').text('Delete Selected (' + selectedCount + ')');
            } else {
                $('#bulkDeleteBtn').text('Delete Selected');
            }
        }
        
        // Bulk delete button click event
        $('#bulkDeleteBtn').on('click', function() {
            if (confirm('Are you sure you want to delete the selected subscriptions? This action cannot be undone.')) {
                $('#bulkDeleteForm').submit();
            }
        });
        
        // Refresh button functionality
        $('#refreshTable').on('click', function() {
            location.reload();
        });
        
        // Auto-hide alert messages after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });
</script>
@endsection