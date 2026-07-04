@extends('layouts.master')

@section('title', 'Sync Logs')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-history"></i> Sync Logs</h4>
            <p class="text-muted mb-0">View sync operation history</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.daraz.sync.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <button type="button" class="btn btn-outline-danger" id="clearLogsBtn">
                <i class="fas fa-trash"></i> Clear Old Logs
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.daraz.sync.logs') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="store_id" class="form-select">
                        <option value="">All Stores</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ $storeId == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="stock_push" {{ $type === 'stock_push' ? 'selected' : '' }}>Stock Push</option>
                        <option value="stock_pull" {{ $type === 'stock_pull' ? 'selected' : '' }}>Stock Pull</option>
                        <option value="bulk_sync" {{ $type === 'bulk_sync' ? 'selected' : '' }}>Bulk Sync</option>
                        <option value="token_refresh" {{ $type === 'token_refresh' ? 'selected' : '' }}>Token Refresh</option>
                        <option value="connection_test" {{ $type === 'connection_test' ? 'selected' : '' }}>Connection Test</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="partial" {{ $status === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('admin.daraz.sync.logs') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="card-body">
            @if($logs->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fs-1 text-muted"></i>
                    <h4 class="mt-3">No Logs Found</h4>
                    <p class="text-muted">Sync operations will appear here.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Store</th>
                                <th>Type</th>
                                <th>Product</th>
                                <th>Stock Change</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                            <tr>
                                <td>
                                    <small>{{ $log->created_at->format('M d, Y') }}</small><br>
                                    <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $log->store->name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $log->type_label }}</span>
                                    @if($log->direction)
                                        <br><small class="text-muted">{{ $log->direction === 'to_daraz' ? 'Push' : 'Pull' }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($log->productMapping)
                                        {{ Str::limit($log->productMapping->product_title ?? 'N/A', 30) }}
                                        <br><small class="text-muted">{{ $log->productMapping->daraz_sku ?? '' }}</small>
                                    @elseif($log->type === 'bulk_sync')
                                        <span class="text-muted">Bulk Operation</span>
                                        @if($log->items_processed)
                                            <br><small>{{ $log->items_processed }} items</small>
                                        @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->quantity_before !== null || $log->quantity_after !== null)
                                        {{ $log->quantity_before ?? '?' }} → {{ $log->quantity_after ?? '?' }}
                                    @elseif($log->items_processed)
                                        <span class="text-success">{{ $log->items_succeeded ?? 0 }} OK</span>
                                        @if($log->items_failed)
                                            / <span class="text-danger">{{ $log->items_failed }} failed</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $log->status_badge_class }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->error_message)
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="popover"
                                                data-bs-trigger="hover"
                                                data-bs-content="{{ $log->error_message }}">
                                            <i class="fas fa-exclamation-circle"></i> Error
                                        </button>
                                    @endif
                                    @if($log->request_data || $log->response_data)
                                        <button type="button"
                                                class="btn btn-sm btn-outline-info view-details"
                                                data-log-id="{{ $log->id }}"
                                                data-request="{{ json_encode($log->request_data) }}"
                                                data-response="{{ json_encode($log->response_data) }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle"></i> Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Request Data</h6>
                        <pre class="bg-light p-2 rounded small" id="requestData" style="max-height: 300px; overflow: auto;">-</pre>
                    </div>
                    <div class="col-md-6">
                        <h6>Response Data</h6>
                        <pre class="bg-light p-2 rounded small" id="responseData" style="max-height: 300px; overflow: auto;">-</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-trash"></i> Clear Old Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Delete logs older than:</p>
                <select id="clearDays" class="form-select">
                    <option value="7">7 days</option>
                    <option value="14">14 days</option>
                    <option value="30" selected>30 days</option>
                    <option value="60">60 days</option>
                    <option value="90">90 days</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmClearLogs">
                    <i class="fas fa-trash"></i> Delete Logs
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(el => new bootstrap.Popover(el));

    // View Details
    document.querySelectorAll('.view-details').forEach(btn => {
        btn.addEventListener('click', function() {
            const request = this.dataset.request;
            const response = this.dataset.response;

            document.getElementById('requestData').textContent =
                request && request !== 'null' ? JSON.stringify(JSON.parse(request), null, 2) : '-';
            document.getElementById('responseData').textContent =
                response && response !== 'null' ? JSON.stringify(JSON.parse(response), null, 2) : '-';

            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        });
    });

    // Clear Logs
    document.getElementById('clearLogsBtn')?.addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('clearLogsModal')).show();
    });

    document.getElementById('confirmClearLogs')?.addEventListener('click', function() {
        const days = document.getElementById('clearDays').value;
        const btn = this;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        btn.disabled = true;

        fetch(`{{ route('admin.daraz.sync.logs.clear') }}?days=${days}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || (data.success ? 'Logs cleared!' : 'Failed to clear logs'));
            if (data.success) location.reload();
        })
        .catch(err => alert('Error: ' + err.message))
        .finally(() => {
            btn.innerHTML = '<i class="fas fa-trash"></i> Delete Logs';
            btn.disabled = false;
        });
    });
});
</script>
@endpush
