@extends('layouts.master')

@section('title', 'Daraz Sync Dashboard')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .daraz-dashboard {
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
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .premium-card .card-header {
            background-color: #f8fafc !important;
            border-bottom: 1px solid #f1f5f9 !important;
            padding: 1.25rem 1.5rem !important;
        }
        .premium-card .card-header h5 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
        }
        .premium-card .card-body {
            padding: 1.5rem !important;
        }
        
        /* Stats Grid Redesign */
        .stat-card-modern {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            min-height: 84px;
            box-sizing: border-box;
        }
        .stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.04);
        }
        .stat-card-modern .stat-icon {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }
        .stat-card-modern .stat-count {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }
        .stat-card-modern .stat-label {
            font-size: 10px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            margin-top: 2px;
            white-space: nowrap;
        }
        
        /* Color themes for stats cards */
        .stat-theme-blue { border-left: 4px solid #3b82f6; }
        .stat-theme-blue .stat-icon { background: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; }
        
        .stat-theme-green { border-left: 4px solid #10b981; }
        .stat-theme-green .stat-icon { background: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; }
        
        .stat-theme-purple { border-left: 4px solid #8b5cf6; }
        .stat-theme-purple .stat-icon { background: #f5f3ff; color: #8b5cf6; border: 1px solid #ddd6fe; }
        
        .stat-theme-orange { border-left: 4px solid #f59e0b; }
        .stat-theme-orange .stat-icon { background: #fffbeb; color: #f59e0b; border: 1px solid #fde68a; }
        
        .stat-theme-red { border-left: 4px solid #ef4444; }
        .stat-theme-red .stat-icon { background: #fee2e2; color: #ef4444; border: 1px solid #fca5a5; }

        .store-card-modern {
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            padding: 18px;
            background: #fff;
            transition: all 0.2s ease;
        }
        .store-card-modern:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }
        
        .table-premium-status th {
            font-size: 0.72rem !important;
            text-transform: uppercase;
            font-weight: 700;
            color: #475569;
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px !important;
        }
        .table-premium-status td {
            font-size: 0.85rem;
            padding: 10px !important;
            vertical-align: middle;
        }

        /* Tabbed Navigation Styles */
        .daraz-tabs-wrapper {
            background: #f1f5f9;
            padding: 5px;
            border-radius: 12px;
            display: inline-flex;
            gap: 4px;
            border: 1px solid #e2e8f0;
        }
        .daraz-tabs .nav-link {
            font-weight: 700;
            color: #475569;
            border-radius: 9px;
            padding: 8px 18px;
            font-size: 0.825rem;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .daraz-tabs .nav-link.active {
            background-color: #ffffff !important;
            color: #4338ca !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03) !important;
        }
        .daraz-tabs .nav-link:hover:not(.active) {
            background-color: rgba(255, 255, 255, 0.5);
            color: #0f172a;
        }
        .list-group-item-premium {
            background: transparent !important;
            border-bottom: 1px solid #f1f5f9 !important;
            color: #475569;
            padding: 1rem 1.25rem !important;
        }
        .list-group-item-premium:last-child {
            border-bottom: none !important;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid daraz-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.25rem;"><i class="fas fa-sync me-1 text-primary"></i> Marketplace Inventory Integration Control</h4>
            <p class="text-muted small mb-0">Oversee and execute real-time stock balances across seller accounts</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-success" id="syncAllBtn" style="border-radius: 8px; font-weight: 600; padding: 6px 14px;" {{ $stats['connected_stores'] === 0 ? 'disabled' : '' }}>
                <i class="fas fa-upload me-1"></i> Push All
            </button>
            <button type="button" class="btn btn-sm btn-outline-info" id="pullAllBtn" style="border-radius: 8px; font-weight: 600; padding: 6px 14px;" {{ $stats['connected_stores'] === 0 ? 'disabled' : '' }}>
                <i class="fas fa-download me-1"></i> Pull All
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" style="border-radius: 12px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stat-card-modern stat-theme-blue">
                <div class="stat-icon"><i class="fas fa-store"></i></div>
                <div>
                    <div class="stat-count">{{ $stats['total_stores'] }}</div>
                    <div class="stat-label">Outlets</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stat-card-modern stat-theme-green">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-count">{{ $stats['connected_stores'] }}</div>
                    <div class="stat-label">Linked Outlets</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stat-card-modern stat-theme-purple">
                <div class="stat-icon"><i class="fas fa-link"></i></div>
                <div>
                    <div class="stat-count">{{ $stats['total_mappings'] }}</div>
                    <div class="stat-label">SKU Bridges</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stat-card-modern stat-theme-orange">
                <div class="stat-icon"><i class="fas fa-toggle-on"></i></div>
                <div>
                    <div class="stat-count">{{ $stats['enabled_mappings'] }}</div>
                    <div class="stat-label">Active Syncs</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stat-card-modern stat-theme-blue">
                <div class="stat-icon"><i class="fas fa-history"></i></div>
                <div>
                    <div class="stat-count">{{ $stats['recent_syncs'] }}</div>
                    <div class="stat-label">24h Sync Cycles</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stat-card-modern {{ $stats['failed_syncs'] > 0 ? 'stat-theme-red' : 'stat-theme-green' }}">
                <div class="stat-icon"><i class="fas fa-exclamation-circle"></i></div>
                <div>
                    <div class="stat-count">{{ $stats['failed_syncs'] }}</div>
                    <div class="stat-label">Sync Blockages</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card premium-card mb-4">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div class="daraz-tabs-wrapper">
                        <ul class="nav nav-pills daraz-tabs" id="darazTab" role="tablist" style="border:none;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="stores-tab" data-bs-toggle="tab" data-bs-target="#stores-content" type="button" role="tab" aria-controls="stores-content" aria-selected="true">
                                    <i class="fas fa-store me-2"></i>Configured Channels
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity-content" type="button" role="tab" aria-controls="activity-content" aria-selected="false">
                                    <i class="fas fa-history me-2"></i>System Operations Log
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-actions">
                        <a href="{{ route('admin.daraz.stores.index') }}" class="btn btn-sm btn-outline-primary" id="manage-stores-btn" style="border-radius: 8px; font-weight: 500;">
                            Manage Outlets
                        </a>
                        <a href="{{ route('admin.daraz.sync.logs') }}" class="btn btn-sm btn-outline-primary d-none" id="view-all-logs-btn" style="border-radius: 8px; font-weight: 500;">
                            Review Event Logs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="darazTabContent">
                        <div class="tab-pane fade show active" id="stores-content" role="tabpanel" aria-labelledby="stores-tab">
                            @if($stores->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-store-slash fs-1 text-muted opacity-50 mb-3"></i>
                                    <p class="text-muted">No stores connected yet.</p>
                                    <a href="{{ route('admin.daraz.stores.create') }}" class="btn btn-primary btn-sm" style="border-radius: 8px;">
                                        <i class="fas fa-plus"></i> Add Store
                                    </a>
                                </div>
                            @else
                                <div class="row g-3">
                                    @foreach($stores as $store)
                                        <div class="col-lg-6">
                                            <div class="store-card-modern">
                                                <div class="d-flex justify-content-between align-items-start gap-2">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold text-dark">
                                                            {{ $store->name }}
                                                            <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.7rem; font-weight: 500;">{{ $store->country_code }}</span>
                                                            @if($store->isConnected())
                                                                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1" style="font-size: 0.7rem; font-weight: 500;">Connected</span>
                                                            @else
                                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle ms-1" style="font-size: 0.7rem; font-weight: 500;">Disconnected</span>
                                                            @endif
                                                        </h6>
                                                        <small class="text-muted">
                                                            {{ $store->product_mappings_count ?? 0 }} mappings
                                                            ({{ $store->enabled_mappings_count ?? 0 }} enabled)
                                                            &bull;
                                                            Last sync: {{ $store->last_synced_at?->diffForHumans() ?? 'Never' }}
                                                        </small>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button"
                                                                class="btn btn-outline-success sync-store"
                                                                style="border-top-left-radius: 8px; border-bottom-left-radius: 8px;"
                                                                data-store-id="{{ $store->id }}"
                                                                data-direction="to_daraz"
                                                                {{ !$store->isConnected() ? 'disabled' : '' }}>
                                                            <i class="fas fa-upload me-1"></i> Push
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-outline-info sync-store"
                                                                style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;"
                                                                data-store-id="{{ $store->id }}"
                                                                data-direction="from_daraz"
                                                                {{ !$store->isConnected() ? 'disabled' : '' }}>
                                                            <i class="fas fa-download me-1"></i> Pull
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Store Quick Status -->
                                                <div class="store-status mt-3 small" id="store-status-{{ $store->id }}">
                                                    <button type="button"
                                                            class="btn btn-link btn-sm p-0 load-store-status text-decoration-none"
                                                            style="font-size: 0.8rem; font-weight: 500;"
                                                            data-store-id="{{ $store->id }}"
                                                            {{ !$store->isConnected() ? 'disabled' : '' }}>
                                                        <i class="fas fa-eye me-1"></i> View mapping status
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="activity-content" role="tabpanel" aria-labelledby="activity-tab">
                            @if($recentLogs->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-clipboard-list fs-1 text-muted opacity-50 mb-3"></i>
                                    <p class="text-muted small">No sync activity yet.</p>
                                </div>
                            @else
                                <div class="list-group list-group-flush">
                                    @foreach($recentLogs as $log)
                                        <div class="list-group-item list-group-item-premium">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <span class="badge {{ $log->status_badge_class }}" style="font-size: 0.7rem;">
                                                        {{ ucfirst($log->status) }}
                                                    </span>
                                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle ms-1" style="font-size: 0.7rem;">{{ $log->type_label }}</span>
                                                    <br>
                                                    <small class="text-muted d-inline-block mt-2">
                                                        {{ $log->store->name ?? 'N/A' }}
                                                        @if($log->productMapping)
                                                            - {{ Str::limit($log->productMapping->product_title ?? '', 40) }}
                                                        @endif
                                                    </small>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.75rem;">
                                                    {{ $log->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            @if($log->error_message)
                                                <small class="text-danger d-block mt-2" style="font-size: 0.78rem; background: #fff5f5; padding: 6px 10px; border-radius: 6px; border: 1px solid #fed7d7;">
                                                    {{ Str::limit($log->error_message, 100) }}
                                                </small>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab actions toggle
    const storesTabBtn = document.getElementById('stores-tab');
    const activityTabBtn = document.getElementById('activity-tab');
    const manageStoresBtn = document.getElementById('manage-stores-btn');
    const viewAllLogsBtn = document.getElementById('view-all-logs-btn');

    if (storesTabBtn && activityTabBtn && manageStoresBtn && viewAllLogsBtn) {
        storesTabBtn.addEventListener('shown.bs.tab', function () {
            manageStoresBtn.classList.remove('d-none');
            viewAllLogsBtn.classList.add('d-none');
        });
        activityTabBtn.addEventListener('shown.bs.tab', function () {
            manageStoresBtn.classList.add('d-none');
            viewAllLogsBtn.classList.remove('d-none');
        });
    }

    // Sync All
    document.getElementById('syncAllBtn')?.addEventListener('click', function() {
        if (!confirm('Push stock to all connected Daraz stores?')) return;

        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing...';
        btn.disabled = true;

        fetch('{{ route("admin.daraz.sync.all") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || (data.success ? 'Sync completed!' : 'Sync failed'));
            if (data.success) location.reload();
        })
        .catch(err => alert('Error: ' + err.message))
        .finally(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    });

    // Pull All
    document.getElementById('pullAllBtn')?.addEventListener('click', function() {
        if (!confirm('Pull stock from all connected Daraz stores?')) return;

        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Pulling...';
        btn.disabled = true;

        fetch('{{ route("admin.daraz.sync.pull-all") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message || (data.success ? 'Pull completed!' : 'Pull failed'));
            if (data.success) location.reload();
        })
        .catch(err => alert('Error: ' + err.message))
        .finally(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    });

    // Sync Store
    document.querySelectorAll('.sync-store').forEach(btn => {
        btn.addEventListener('click', function() {
            const storeId = this.dataset.storeId;
            const direction = this.dataset.direction;
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch(`{{ url('admin/daraz/sync/store') }}/${storeId}?direction=${direction}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || (data.success ? 'Done!' : 'Failed'));
                if (data.success) location.reload();
            })
            .catch(err => alert('Error: ' + err.message))
            .finally(() => {
                this.innerHTML = originalHtml;
                this.disabled = false;
            });
        });
    });

    // Load Store Status
    document.querySelectorAll('.load-store-status').forEach(btn => {
        btn.addEventListener('click', function() {
            const storeId = this.dataset.storeId;
            const container = document.getElementById('store-status-' + storeId);

            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            this.disabled = true;

            fetch(`{{ url('admin/daraz/sync/store-status') }}/${storeId}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.mappings) {
                    let html = '<div class="table-responsive mt-2"><table class="table table-sm table-bordered mb-0">';
                    html += '<thead><tr><th>Product</th><th>Stock</th><th>Synced</th><th>Status</th></tr></thead><tbody>';

                    data.mappings.forEach(m => {
                        const needsSyncBadge = m.needs_sync ? '<span class="badge bg-warning">!</span>' : '';
                        html += `<tr>
                            <td>${m.product}</td>
                            <td>${m.thikana_stock} / ${m.effective_stock}</td>
                            <td>${m.last_synced ?? '-'} ${m.last_synced_at ? '(' + m.last_synced_at + ')' : ''}</td>
                            <td>${m.status || '-'} ${needsSyncBadge}</td>
                        </tr>`;
                    });

                    html += '</tbody></table></div>';
                    html += `<small class="text-muted">${data.needs_sync_count} mapping(s) need sync</small>`;
                    container.innerHTML = html;
                } else {
                    container.innerHTML = '<small class="text-danger">Failed to load status</small>';
                }
            })
            .catch(err => {
                container.innerHTML = '<small class="text-danger">Error loading status</small>';
            });
        });
    });
});
</script>
@endpush
