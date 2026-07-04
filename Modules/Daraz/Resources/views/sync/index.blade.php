@extends('layouts.master')

@section('title', 'Daraz Sync Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-sync"></i> Daraz Sync Dashboard</h4>
            <p class="text-muted mb-0">Monitor and manage stock synchronization</p>
        </div>
        <div class="col-md-6 text-end">
            <button type="button" class="btn btn-success" id="syncAllBtn" {{ $stats['connected_stores'] === 0 ? 'disabled' : '' }}>
                <i class="fas fa-upload"></i> Push All to Daraz
            </button>
            <button type="button" class="btn btn-info" id="pullAllBtn" {{ $stats['connected_stores'] === 0 ? 'disabled' : '' }}>
                <i class="fas fa-download"></i> Pull All from Daraz
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="glowcard glowcard1">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-store"></i></span></div>
                    <div>
                        <div class="count">{{ $stats['total_stores'] }}</div>
                        <div class="label">Stores</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="glowcard glowcard2">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-check-circle"></i></span></div>
                    <div>
                        <div class="count">{{ $stats['connected_stores'] }}</div>
                        <div class="label">Connected</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="glowcard glowcard3">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-link"></i></span></div>
                    <div>
                        <div class="count">{{ $stats['total_mappings'] }}</div>
                        <div class="label">Mappings</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="glowcard glowcard4">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-toggle-on"></i></span></div>
                    <div>
                        <div class="count">{{ $stats['enabled_mappings'] }}</div>
                        <div class="label">Enabled</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="glowcard glowcard1">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-history"></i></span></div>
                    <div>
                        <div class="count">{{ $stats['recent_syncs'] }}</div>
                        <div class="label">24h Syncs</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="glowcard {{ $stats['failed_syncs'] > 0 ? 'bg-danger' : 'glowcard2' }}">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-exclamation-circle"></i></span></div>
                    <div>
                        <div class="count">{{ $stats['failed_syncs'] }}</div>
                        <div class="label">Failed</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Store Status -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-store"></i> Connected Stores</h5>
                    <a href="{{ route('admin.daraz.stores.index') }}" class="btn btn-sm btn-outline-primary">
                        Manage Stores
                    </a>
                </div>
                <div class="card-body">
                    @if($stores->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-store-slash fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">No stores connected yet.</p>
                            <a href="{{ route('admin.daraz.stores.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Store
                            </a>
                        </div>
                    @else
                        @foreach($stores as $store)
                            <div class="store-card border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">
                                            {{ $store->name }}
                                            <span class="badge bg-info">{{ $store->country_code }}</span>
                                            @if($store->isConnected())
                                                <span class="badge bg-success">Connected</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Disconnected</span>
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
                                                data-store-id="{{ $store->id }}"
                                                data-direction="to_daraz"
                                                {{ !$store->isConnected() ? 'disabled' : '' }}>
                                            <i class="fas fa-upload"></i> Push
                                        </button>
                                        <button type="button"
                                                class="btn btn-outline-info sync-store"
                                                data-store-id="{{ $store->id }}"
                                                data-direction="from_daraz"
                                                {{ !$store->isConnected() ? 'disabled' : '' }}>
                                            <i class="fas fa-download"></i> Pull
                                        </button>
                                    </div>
                                </div>

                                <!-- Store Quick Status -->
                                <div class="store-status mt-2 small" id="store-status-{{ $store->id }}">
                                    <button type="button"
                                            class="btn btn-link btn-sm p-0 load-store-status"
                                            data-store-id="{{ $store->id }}"
                                            {{ !$store->isConnected() ? 'disabled' : '' }}>
                                        <i class="fas fa-eye"></i> View mapping status
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Logs -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Recent Activity</h5>
                    <a href="{{ route('admin.daraz.sync.logs') }}" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentLogs->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fs-1 text-muted"></i>
                            <p class="mt-2 text-muted small">No sync activity yet.</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($recentLogs as $log)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge {{ $log->status_badge_class }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                            <span class="badge bg-secondary">{{ $log->type_label }}</span>
                                            <br>
                                            <small class="text-muted">
                                                {{ $log->store->name ?? 'N/A' }}
                                                @if($log->productMapping)
                                                    - {{ Str::limit($log->productMapping->product_title ?? '', 20) }}
                                                @endif
                                            </small>
                                        </div>
                                        <small class="text-muted">
                                            {{ $log->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    @if($log->error_message)
                                        <small class="text-danger d-block mt-1">
                                            {{ Str::limit($log->error_message, 50) }}
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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
