@extends('layouts.master')

@section('title', 'System Operations Log')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        .daraz-logs-dashboard {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            border-radius: 18px;
            padding: 6px;
        }
        .premium-card {
            border: 1px solid #f1f5f9 !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02) !important;
            background: #fff;
            overflow: hidden;
        }
        .form-control, .form-select {
            border-radius: 8px !important;
            border: 1px solid #cbd5e1 !important;
            padding: 8px 12px !important;
            font-size: 0.88rem !important;
            transition: all 0.2s ease !important;
            height: 38px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15) !important;
        }
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            padding: 8px 16px !important;
            font-size: 0.88rem !important;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Timeline Feed Styles */
        .timeline-feed {
            position: relative;
            padding: 10px 0;
        }
        .timeline-feed::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 20px;
            width: 2px;
            background: #e2e8f0;
        }
        .timeline-item {
            position: relative;
            padding-left: 55px;
            margin-bottom: 24px;
            transition: all 0.25s ease;
        }
        .timeline-marker {
            position: absolute;
            left: 8px;
            top: 2px;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #fff;
            border: 2px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            z-index: 1;
            transition: all 0.2s ease;
        }
        .timeline-item.state-success .timeline-marker {
            border-color: #10b981;
            color: #10b981;
            background: #f0fdf4;
        }
        .timeline-item.state-failed .timeline-marker {
            border-color: #ef4444;
            color: #ef4444;
            background: #fef2f2;
        }
        .timeline-item.state-warning .timeline-marker {
            border-color: #f59e0b;
            color: #f59e0b;
            background: #fffbeb;
        }
        
        .log-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #fff;
            padding: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.2s ease;
        }
        .log-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.03), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            border-color: #cbd5e1;
        }
        .log-time {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
        }
        .log-header {
            display: flex;
            justify-content: space-between;
            align-items-start;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 8px;
        }
        .log-body {
            font-size: 0.88rem;
            color: #334155;
        }
        .log-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #e2e8f0;
            font-size: 0.78rem;
            color: #64748b;
        }
        .payload-collapse {
            background: #0f172a;
            border-radius: 12px;
            padding: 16px;
            margin-top: 12px;
        }
        .payload-title {
            font-size: 0.72rem;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }
        .payload-pre {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            color: #38bdf8;
            margin: 0;
            overflow: auto;
            max-height: 250px;
        }
        .payload-pre.response {
            color: #34d399;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid daraz-logs-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.25rem;"><i class="fas fa-history me-1 text-primary"></i> System Operations Log</h4>
            <p class="text-muted small mb-0">Review background sync triggers, API payloads, and integration state changes</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.daraz.sync.index') }}" class="btn btn-sm btn-outline-secondary" style="padding: 6px 14px !important;">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
            <button type="button" class="btn btn-sm btn-outline-danger" id="clearLogsBtn" style="padding: 6px 14px !important;">
                <i class="fas fa-trash me-1"></i> Flush Archive
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card premium-card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.daraz.sync.logs') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <select name="store_id" class="form-select">
                        <option value="">All Outlets</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ $storeId == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select">
                        <option value="">All Operations</option>
                        <option value="stock_push" {{ $type === 'stock_push' ? 'selected' : '' }}>Stock Push</option>
                        <option value="stock_pull" {{ $type === 'stock_pull' ? 'selected' : '' }}>Stock Pull</option>
                        <option value="bulk_sync" {{ $type === 'bulk_sync' ? 'selected' : '' }}>Bulk Sync</option>
                        <option value="token_refresh" {{ $type === 'token_refresh' ? 'selected' : '' }}>Token Refresh</option>
                        <option value="connection_test" {{ $type === 'connection_test' ? 'selected' : '' }}>Connection Test</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All States</option>
                        <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="partial" {{ $status === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.daraz.sync.logs') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-times me-1"></i> Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Timeline Feed -->
    @if($logs->isEmpty())
        <div class="card premium-card mb-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-clipboard-list fs-1 text-muted opacity-50 mb-3"></i>
                <h5 class="fw-bold text-dark">No Operation Logs Found</h5>
                <p class="text-muted small">Activities and communication records will be shown here.</p>
            </div>
        </div>
    @else
        <div class="timeline-feed mb-4">
            @foreach($logs as $log)
                @php
                    $stateClass = $log->status === 'success' ? 'state-success' : ($log->status === 'failed' ? 'state-failed' : 'state-warning');
                    $markerIcon = $log->status === 'success' ? 'fa-check' : ($log->status === 'failed' ? 'fa-times' : 'fa-exclamation');
                @endphp
                <div class="timeline-item {{ $stateClass }}">
                    <div class="timeline-marker">
                        <i class="fas {{ $markerIcon }}"></i>
                    </div>
                    <div class="log-card">
                        <div class="log-header">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $log->status === 'success' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}-subtle text-{{ $log->status === 'success' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }} border border-{{ $log->status === 'success' ? 'success' : ($log->status === 'failed' ? 'danger' : 'warning') }}-subtle" style="font-weight: 600;">
                                    {{ $log->type_label }}
                                </span>
                                <span class="badge bg-light text-secondary border" style="font-weight: 500;">
                                    {{ $log->store->name ?? 'System' }}
                                </span>
                            </div>
                            <div class="log-time">
                                <i class="fas fa-clock me-1 text-muted"></i>
                                {{ $log->created_at->format('M d, Y H:i:s') }} ({{ $log->created_at->diffForHumans() }})
                            </div>
                        </div>

                        <div class="log-body">
                            @if($log->productMapping)
                                <div class="fw-bold text-dark mb-1">{{ $log->productMapping->product_title }}</div>
                                <div class="text-muted small">SKU Bridge: <code class="text-dark bg-light px-2 py-0.5 rounded">{{ $log->productMapping->daraz_sku }}</code></div>
                            @elseif($log->type === 'bulk_sync')
                                <div class="fw-bold text-dark">Bulk Synchronization Queue</div>
                                <div class="text-muted small">Processed {{ $log->items_processed }} items.</div>
                            @else
                                <div class="text-secondary">Global integration state update or token validation check.</div>
                            @endif

                            @if($log->error_message)
                                <div class="alert alert-danger mb-0 mt-3 py-2 px-3 small border-0 d-flex align-items-center gap-2" style="border-radius: 8px;">
                                    <i class="fas fa-circle-exclamation"></i>
                                    <span>{{ $log->error_message }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="log-meta">
                            <div>
                                <i class="fas fa-exchange-alt me-1"></i>
                                Direction: <strong>{{ $log->direction === 'to_daraz' ? 'Outgoing Push' : 'Incoming Pull' }}</strong>
                            </div>
                            @if($log->quantity_before !== null || $log->quantity_after !== null)
                                <div>
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Quantity: <strong>{{ $log->quantity_before ?? '?' }} &rarr; {{ $log->quantity_after ?? '?' }}</strong>
                                </div>
                            @endif
                            @if($log->items_processed)
                                <div>
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    Success: <strong class="text-success">{{ $log->items_succeeded ?? 0 }}</strong>
                                </div>
                                <div>
                                    <i class="fas fa-times-circle text-danger me-1"></i>
                                    Failures: <strong class="text-danger">{{ $log->items_failed ?? 0 }}</strong>
                                </div>
                            @endif
                            
                            @if($log->request_data || $log->response_data)
                                <div class="ms-auto">
                                    <button class="btn btn-sm btn-link p-0 text-decoration-none fw-bold text-primary" 
                                            type="button" 
                                            data-bs-toggle="collapse" 
                                            data-bs-target="#payload-{{ $log->id }}" 
                                            aria-expanded="false" 
                                            style="height: auto; font-size: 0.78rem;">
                                        <i class="fas fa-code me-1"></i> Inspect Payloads
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Collapsible Payloads -->
                        @if($log->request_data || $log->response_data)
                            <div class="collapse" id="payload-{{ $log->id }}">
                                <div class="payload-collapse">
                                    <div class="row g-3">
                                        @if($log->request_data)
                                            <div class="col-md-6">
                                                <div class="payload-title">Outgoing API Request Payload</div>
                                                <pre class="payload-pre">{{ json_encode($log->request_data, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @endif
                                        @if($log->response_data)
                                            <div class="col-md-6">
                                                <div class="payload-title">Incoming Marketplace Response</div>
                                                <pre class="payload-pre response">{{ json_encode($log->response_data, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center p-3">
            {{ $logs->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h5 class="modal-title fw-bold text-dark" style="font-size: 1rem;"><i class="fas fa-trash text-danger me-1"></i> Flush Archive</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small">Delete event records older than:</p>
                <select id="clearDays" class="form-select">
                    <option value="7">7 days</option>
                    <option value="14">14 days</option>
                    <option value="30" selected>30 days</option>
                    <option value="60">60 days</option>
                    <option value="90">90 days</option>
                </select>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-danger" id="confirmClearLogs">
                    <i class="fas fa-trash me-1"></i> Flush Records
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
            btn.innerHTML = '<i class="fas fa-trash"></i> Flush Records';
            btn.disabled = false;
        });
    });
});
</script>
@endpush
