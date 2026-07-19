@extends('layouts.master')

@section('title', 'Daraz Stores')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .daraz-stores-dashboard {
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
        .premium-card .card-body {
            padding: 1.5rem !important;
        }
        
        /* Stats Grid Redesign */
        .stat-card-modern {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card-modern:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.04);
        }
        .stat-card-modern .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .stat-card-modern .stat-count {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }
        .stat-card-modern .stat-label {
            font-size: 10px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #64748b;
            font-weight: 700;
            margin-top: 2px;
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

        .table-premium {
            margin-bottom: 0;
        }
        .table-premium th {
            font-size: 0.72rem !important;
            text-transform: uppercase;
            font-weight: 700;
            color: #475569;
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px !important;
        }
        .table-premium td {
            font-size: 0.85rem;
            padding: 14px 16px !important;
            vertical-align: middle;
            color: #334155;
        }
        .table-premium tr {
            transition: all 0.2s ease;
        }
        .table-premium tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid daraz-stores-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-2">
        <div>
            <h4 class="mb-0 fw-bold text-dark" style="font-size: 1.25rem;"><i class="fas fa-store me-1 text-primary"></i> Seller Accounts</h4>
            <p class="text-muted small mb-0">Configure and coordinate active seller accounts</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.daraz.stores.create') }}" class="btn btn-sm btn-primary" style="border-radius: 8px; font-weight: 600; padding: 6px 14px;">
                <i class="fas fa-plus me-1"></i> Integrate Channel
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" style="border-radius: 12px;">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" style="border-radius: 12px;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-modern stat-theme-blue">
                <div class="stat-icon"><i class="fas fa-store"></i></div>
                <div>
                    <div class="stat-count">{{ $stores->count() }}</div>
                    <div class="stat-label">Configured Channels</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-modern stat-theme-green">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-count">{{ $stores->filter(fn($s) => $s->isConnected())->count() }}</div>
                    <div class="stat-label">Authorized Outlets</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-modern stat-theme-purple">
                <div class="stat-icon"><i class="fas fa-link"></i></div>
                <div>
                    <div class="stat-count">{{ $stores->sum('product_mappings_count') }}</div>
                    <div class="stat-label">Linked SKU Pairs</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card-modern stat-theme-orange">
                <div class="stat-icon"><i class="fas fa-sync"></i></div>
                <div>
                    <div class="stat-count">{{ $stores->filter(fn($s) => $s->auto_sync)->count() }}</div>
                    <div class="stat-label">Background Sync On</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stores Table -->
    <div class="card premium-card mb-4">
        <div class="card-body p-0">
            @if($stores->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-store fs-1 text-muted opacity-50 mb-3"></i>
                    <h5 class="fw-bold text-dark">No Seller Accounts Configured</h5>
                    <p class="text-muted small">Integrate your initial seller account to begin mapping items.</p>
                    <a href="{{ route('admin.daraz.stores.create') }}" class="btn btn-primary btn-sm mt-2" style="border-radius: 8px;">
                        <i class="fas fa-plus-circle me-1"></i> Integrate Channel
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-premium">
                        <thead>
                            <tr>
                                <th>Outlet Name</th>
                                <th>Country</th>
                                <th>Status</th>
                                <th>Bridges</th>
                                <th>Last Synced</th>
                                <th>Background Sync</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $store->name }}</div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">App Key: {{ Str::limit($store->app_key, 20) }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border" style="font-size: 0.75rem; font-weight: 500;">{{ $store->country_code }}</span>
                                </td>
                                <td>
                                    @if($store->isConnected())
                                        <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.75rem; font-weight: 500;">
                                            <i class="fas fa-check-circle me-1"></i> Connected
                                        </span>
                                        @if($store->token_expires_at)
                                            <div class="text-muted small mt-1" style="font-size: 0.7rem;">Expires: {{ $store->token_expires_at->diffForHumans() }}</div>
                                        @endif
                                    @elseif($store->access_token)
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle" style="font-size: 0.75rem; font-weight: 500;">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Token Expired
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.75rem; font-weight: 500;">
                                            <i class="fas fa-link-slash me-1"></i> Not Authorized
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $store->product_mappings_count ?? 0 }}</span> <span class="text-muted small">mapped</span>
                                </td>
                                <td>
                                    @if($store->last_synced_at)
                                        <span class="text-dark">{{ $store->last_synced_at->diffForHumans() }}</span>
                                    @else
                                        <span class="text-muted">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.daraz.stores.toggle', $store) }}" method="POST" class="d-inline">
                                        @csrf
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   {{ $store->auto_sync ? 'checked' : '' }}
                                                   onchange="this.form.submit()"
                                                   {{ !$store->isConnected() ? 'disabled' : '' }}
                                                   style="cursor: pointer;">
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        @if(!$store->isConnected())
                                            <a href="{{ route('admin.daraz.stores.authorize', $store) }}"
                                               class="btn btn-sm btn-success"
                                               style="border-radius: 6px; font-weight: 500;"
                                               title="Authorize">
                                                <i class="fas fa-key me-1"></i> Authorize
                                            </a>
                                        @else
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-info test-connection"
                                                    style="border-radius: 6px;"
                                                    data-store-id="{{ $store->id }}"
                                                    title="Test Connection">
                                                <i class="fas fa-plug"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-warning refresh-token"
                                                    style="border-radius: 6px;"
                                                    data-store-id="{{ $store->id }}"
                                                    title="Refresh Token">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.daraz.stores.edit', $store) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           style="border-radius: 6px;"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.daraz.stores.destroy', $store) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure? This will remove all product mappings for this store.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    style="border-radius: 6px;"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Test Connection
    document.querySelectorAll('.test-connection').forEach(btn => {
        btn.addEventListener('click', function() {
            const storeId = this.dataset.storeId;
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch(`{{ url('admin/daraz/stores') }}/${storeId}/test`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Connection successful! Seller: ' + (data.seller_name || 'N/A'));
                } else {
                    alert('Connection failed: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => alert('Error: ' + err.message))
            .finally(() => {
                this.innerHTML = originalHtml;
                this.disabled = false;
            });
        });
    });

    // Refresh Token
    document.querySelectorAll('.refresh-token').forEach(btn => {
        btn.addEventListener('click', function() {
            const storeId = this.dataset.storeId;
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            this.disabled = true;

            fetch(`{{ url('admin/daraz/stores') }}/${storeId}/refresh-token`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Token refreshed successfully!');
                    location.reload();
                } else {
                    alert('Failed to refresh token: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => alert('Error: ' + err.message))
            .finally(() => {
                this.innerHTML = originalHtml;
                this.disabled = false;
            });
        });
    });
});
</script>
@endpush
