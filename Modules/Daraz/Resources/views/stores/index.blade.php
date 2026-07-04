@extends('layouts.master')

@section('title', 'Daraz Stores')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-store"></i> Daraz Stores</h4>
            <p class="text-muted mb-0">Connect and manage your Daraz seller accounts</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.daraz.stores.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add Store
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="glowcard glowcard1">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-store"></i></span></div>
                    <div>
                        <div class="count">{{ $stores->count() }}</div>
                        <div class="label">Total Stores</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glowcard glowcard2">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-check-circle"></i></span></div>
                    <div>
                        <div class="count">{{ $stores->filter(fn($s) => $s->isConnected())->count() }}</div>
                        <div class="label">Connected</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glowcard glowcard3">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-link"></i></span></div>
                    <div>
                        <div class="count">{{ $stores->sum('product_mappings_count') }}</div>
                        <div class="label">Total Mappings</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="glowcard glowcard4">
                <div class="glowcard-content">
                    <div class="icon"><span><i class="fas fa-sync"></i></span></div>
                    <div>
                        <div class="count">{{ $stores->filter(fn($s) => $s->auto_sync)->count() }}</div>
                        <div class="label">Auto-Sync On</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stores Table -->
    <div class="card">
        <div class="card-body">
            @if($stores->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-store fs-1 text-muted"></i>
                    <h4 class="mt-3">No Daraz Stores Connected</h4>
                    <p class="text-muted">Connect your first Daraz seller account to start syncing inventory.</p>
                    <a href="{{ route('admin.daraz.stores.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus-circle"></i> Add Store
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Store</th>
                                <th>Country</th>
                                <th>Status</th>
                                <th>Mappings</th>
                                <th>Last Synced</th>
                                <th>Auto-Sync</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stores as $store)
                            <tr>
                                <td>
                                    <strong>{{ $store->name }}</strong><br>
                                    <small class="text-muted">App Key: {{ Str::limit($store->app_key, 20) }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $store->country_code }}</span>
                                </td>
                                <td>
                                    @if($store->isConnected())
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Connected
                                        </span>
                                        @if($store->token_expires_at)
                                            <br><small class="text-muted">Expires: {{ $store->token_expires_at->diffForHumans() }}</small>
                                        @endif
                                    @elseif($store->access_token)
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-exclamation-triangle"></i> Token Expired
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-link-slash"></i> Not Authorized
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $store->product_mappings_count ?? 0 }}</strong> mapped
                                </td>
                                <td>
                                    @if($store->last_synced_at)
                                        {{ $store->last_synced_at->diffForHumans() }}
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
                                                   {{ !$store->isConnected() ? 'disabled' : '' }}>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        @if(!$store->isConnected())
                                            <a href="{{ route('admin.daraz.stores.authorize', $store) }}"
                                               class="btn btn-sm btn-success"
                                               title="Authorize">
                                                <i class="fas fa-key"></i> Authorize
                                            </a>
                                        @else
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-info test-connection"
                                                    data-store-id="{{ $store->id }}"
                                                    title="Test Connection">
                                                <i class="fas fa-plug"></i>
                                            </button>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-warning refresh-token"
                                                    data-store-id="{{ $store->id }}"
                                                    title="Refresh Token">
                                                <i class="fas fa-redo"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.daraz.stores.edit', $store) }}"
                                           class="btn btn-sm btn-outline-primary"
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
