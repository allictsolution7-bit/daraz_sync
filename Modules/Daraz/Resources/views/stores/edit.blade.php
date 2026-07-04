@extends('layouts.master')

@section('title', 'Edit Daraz Store')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-edit"></i> Edit Daraz Store</h4>
            <p class="text-muted mb-0">{{ $store->name }}</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.daraz.stores.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Stores
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Store Configuration</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.daraz.stores.update', $store) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Store Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $store->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="country_code" class="form-label">Country <span class="text-danger">*</span></label>
                            <select class="form-select @error('country_code') is-invalid @enderror"
                                    id="country_code"
                                    name="country_code"
                                    required>
                                @foreach(config('daraz.countries') as $code => $country)
                                    <option value="{{ $code }}" {{ old('country_code', $store->country_code) === $code ? 'selected' : '' }}>
                                        {{ $country['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('country_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">API Credentials</h6>

                        <div class="mb-3">
                            <label for="app_key" class="form-label">App Key <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('app_key') is-invalid @enderror"
                                   id="app_key"
                                   name="app_key"
                                   value="{{ old('app_key', $store->app_key) }}"
                                   required>
                            @error('app_key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="app_secret" class="form-label">App Secret</label>
                            <div class="input-group">
                                <input type="password"
                                       class="form-control @error('app_secret') is-invalid @enderror"
                                       id="app_secret"
                                       name="app_secret"
                                       placeholder="Leave blank to keep current secret">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('app_secret')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('app_secret')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Leave blank to keep the current secret</small>
                        </div>

                        <hr class="my-4">
                        <h6 class="mb-3">Sync Settings</h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $store->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Store Active</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               id="auto_sync" name="auto_sync" value="1"
                                               {{ old('auto_sync', $store->auto_sync) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auto_sync">Enable Auto-Sync</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sync_interval" class="form-label">Sync Interval (minutes)</label>
                            <select class="form-select" id="sync_interval" name="sync_interval">
                                <option value="15" {{ old('sync_interval', $store->sync_interval) == 15 ? 'selected' : '' }}>Every 15 minutes</option>
                                <option value="30" {{ old('sync_interval', $store->sync_interval) == 30 ? 'selected' : '' }}>Every 30 minutes</option>
                                <option value="60" {{ old('sync_interval', $store->sync_interval) == 60 ? 'selected' : '' }}>Every hour</option>
                                <option value="120" {{ old('sync_interval', $store->sync_interval) == 120 ? 'selected' : '' }}>Every 2 hours</option>
                                <option value="360" {{ old('sync_interval', $store->sync_interval) == 360 ? 'selected' : '' }}>Every 6 hours</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Store
                            </button>
                            <a href="{{ route('admin.daraz.stores.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Connection Status -->
            <div class="card {{ $store->isConnected() ? 'border-success' : 'border-warning' }}">
                <div class="card-header {{ $store->isConnected() ? 'bg-success text-white' : 'bg-warning' }}">
                    <h6 class="mb-0">
                        <i class="fas fa-{{ $store->isConnected() ? 'check-circle' : 'exclamation-triangle' }}"></i>
                        Connection Status
                    </h6>
                </div>
                <div class="card-body">
                    @if($store->isConnected())
                        <p class="text-success mb-2"><strong>Connected</strong></p>
                        @if($store->token_expires_at)
                            <p class="small mb-2">
                                Token expires: {{ $store->token_expires_at->format('M d, Y H:i') }}
                                <br>
                                <span class="text-muted">({{ $store->token_expires_at->diffForHumans() }})</span>
                            </p>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-info test-connection" data-store-id="{{ $store->id }}">
                            <i class="fas fa-plug"></i> Test Connection
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning refresh-token" data-store-id="{{ $store->id }}">
                            <i class="fas fa-redo"></i> Refresh Token
                        </button>
                    @else
                        <p class="text-warning mb-2">
                            @if($store->access_token)
                                <strong>Token Expired</strong><br>
                                <small>Please re-authorize or refresh the token.</small>
                            @else
                                <strong>Not Authorized</strong><br>
                                <small>Click below to authorize with Daraz.</small>
                            @endif
                        </p>
                        <a href="{{ route('admin.daraz.stores.authorize', $store) }}" class="btn btn-success">
                            <i class="fas fa-key"></i> Authorize Store
                        </a>
                    @endif
                </div>
            </div>

            <!-- Store Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Store Statistics</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex justify-content-between">
                            <span>Product Mappings:</span>
                            <strong>{{ $store->productMappings()->count() }}</strong>
                        </li>
                        <li class="mb-2 d-flex justify-content-between">
                            <span>Enabled Mappings:</span>
                            <strong>{{ $store->productMappings()->where('sync_enabled', true)->count() }}</strong>
                        </li>
                        <li class="mb-2 d-flex justify-content-between">
                            <span>Last Synced:</span>
                            <strong>{{ $store->last_synced_at?->diffForHumans() ?? 'Never' }}</strong>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span>Created:</span>
                            <strong>{{ $store->created_at->format('M d, Y') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="card mt-3 border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Danger Zone</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Deleting this store will remove all product mappings and sync history.</p>
                    <form action="{{ route('admin.daraz.stores.destroy', $store) }}"
                          method="POST"
                          onsubmit="return confirm('Are you absolutely sure? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete Store
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

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
