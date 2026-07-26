@extends(request()->is('vendor/*') ? 'vendor.layouts.app' : 'layouts.master')

@section('title', 'Edit Daraz Store')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #daraz-stores-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* ── HEADER BANNER ── */
    .hdr-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .hdr-title h4 { font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0 0 4px; }
    .hdr-title p { font-size: 0.85rem; color: #64748b; margin: 0; }

    .btn-hdr {
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 0.825rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #fff; color: #475569; border: 1.5px solid #e2e8f0;
    }
    .btn-hdr:hover { background: #ede9fe; border-color: #a5b4fc; color: #4338ca; }

    /* ── FORM MODULES ── */
    .module-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .module-body { padding: 24px; }

    .section-band {
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 13px;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
    }
    .section-band.danger-band {
        background: linear-gradient(135deg, #991b1b 0%, #7f1d1d 60%, #b91c1c 100%);
    }
    .section-band .band-icon {
        width: 40px; height: 40px;
        border-radius: 11px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: #fff;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }
    .section-band h2 { font-size: 1rem; font-weight: 800; color: #fff; margin: 0; line-height: 1; }

    /* ── FIELDS ── */
    .field-label {
        font-size: 0.76rem; font-weight: 700;
        color: #475569; text-transform: uppercase;
        letter-spacing: 0.04em; margin-bottom: 6px; display: block;
    }
    .field-label .req { color: #ef4444; }
    .field-input, .field-select {
        width: 100%; padding: 10px 13px;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        font-size: 0.875rem; color: #0f172a;
        background: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.2s ease; outline: none;
    }
    .field-input:focus, .field-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .field-input.is-invalid, .field-select.is-invalid { border-color: #ef4444; }
    .invalid-feedback { font-size: 0.78rem; color: #dc2626; margin-top: 4px; display: block; }
    .field-hint { font-size: 0.76rem; color: #94a3b8; margin-top: 5px; line-height: 1.5; }

    /* ── TOGGLE BLOCK ── */
    .toggle-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
    @media(max-width: 576px) { .toggle-grid { grid-template-columns: 1fr; } }
    .toggle-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    .toggle-row .tl { font-size: 0.88rem; font-weight: 700; color: #0f172a; }
    .toggle-wrap { display: flex; align-items: center; gap: 9px; flex-shrink: 0; }
    .premium-toggle {
        appearance: none; -webkit-appearance: none;
        width: 44px; height: 23px;
        border-radius: 99px; background: #cbd5e1;
        cursor: pointer; position: relative;
        transition: background 0.22s ease; flex-shrink: 0;
        border: none; outline: none;
    }
    .premium-toggle::after {
        content: ''; position: absolute;
        top: 2px; left: 2px;
        width: 19px; height: 19px;
        background: #fff; border-radius: 50%;
        transition: left 0.22s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }
    .premium-toggle:checked { background: #6366f1; }
    .premium-toggle:checked::after { left: 23px; }

    /* ── INPUT GROUP ── */
    .input-group-premium { display: flex; }
    .input-group-premium .field-input { border-radius: 10px 0 0 10px; }
    .input-group-btn {
        padding: 0 14px;
        border: 1.5px solid #e2e8f0; border-left: none;
        border-radius: 0 10px 10px 0;
        background: #f1f5f9; color: #475569;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background 0.15s ease;
    }
    .input-group-btn:hover { background: #e2e8f0; }

    /* ── ACTION BUTTONS ── */
    .btn-action {
        padding: 11px 24px; border-radius: 10px;
        font-size: 0.875rem; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; transition: all 0.2s ease;
    }
    .btn-action.indigo { background: linear-gradient(135deg,#4338ca,#6366f1); color: #fff; box-shadow: 0 3px 10px rgba(99,102,241,0.3); }
    .btn-action.indigo:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(99,102,241,0.4); color: #fff; }
    .btn-action.ghost { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-action.ghost:hover { background: #e2e8f0; color: #0f172a; }
    .btn-action.danger { background: linear-gradient(135deg, #b91c1c, #dc2626); color: #fff; box-shadow: 0 3px 10px rgba(220,38,38,0.25); }
    .btn-action.danger:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(220,38,38,0.35); color:#fff; }

    .btn-panel-action {
        display: inline-flex; align-items: center; justify-content: center; gap: 6px;
        padding: 8px 14px; border-radius: 8px; border: 1.5px solid #e2e8f0;
        background: #fff; color: #475569; font-size: 0.78rem; font-weight: 700;
        text-decoration: none; cursor: pointer; transition: all 0.15s ease;
    }
    .btn-panel-action:hover { border-color: #a5b4fc; background: #ede9fe; color: #4338ca; }
    .btn-panel-action.bpa-green { background: #10b981; color: #fff; border: none; }
    .btn-panel-action.bpa-green:hover { background: #059669; color: #fff; }

    /* ── META BULLETS ── */
    .stat-list { list-style: none; padding: 0; margin: 0; }
    .stat-list li {
        display: flex; justify-content: space-between; align-items: center;
        padding: 12px 0; border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem; color: #475569; font-weight: 600;
    }
    .stat-list li:last-child { border-bottom: none; }
    .stat-list li strong { color: #0f172a; font-weight: 700; }

    .status-text-green { color: #16a34a; font-weight: 800; font-size: 0.9rem; }
    .status-text-orange { color: #ea580c; font-weight: 800; font-size: 0.9rem; }
</style>
@endsection

@section('content')
@php
    $isVendor = request()->is('vendor/*');
    $routePrefix = $isVendor ? 'vendor.daraz.' : 'admin.daraz.';
    $urlPrefix = $isVendor ? 'vendor/daraz' : 'admin/daraz';
@endphp
<div id="daraz-stores-page" class="container-fluid px-4 py-4">

    {{-- Header Banner --}}
    <div class="hdr-panel">
        <div class="hdr-title">
            <h4>Edit Seller Account</h4>
            <p>{{ $store->name }} &bull; Integration settings and intervals</p>
        </div>
        <a href="{{ route($routePrefix . 'stores.index') }}" class="btn-hdr">
            <i class="fas fa-arrow-left"></i> Back to Stores
        </a>
    </div>

    <div class="row">
        {{-- Configurations --}}
        <div class="col-lg-8">
            <div class="module-wrap">
                <div class="section-band">
                    <div class="band-icon"><i class="fas fa-sliders-h"></i></div>
                    <h2>Channel Configuration</h2>
                </div>
                <div class="module-body">
                    <form action="{{ route($routePrefix . 'stores.update', $store) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row" style="margin-bottom:16px;">
                            <div class="col-md-6">
                                <label class="field-label">Store Name <span class="req">*</span></label>
                                <input type="text" class="field-input @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $store->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="field-label">Country Outlet <span class="req">*</span></label>
                                <select class="field-select @error('country_code') is-invalid @enderror" id="country_code" name="country_code" required>
                                    @foreach(config('daraz.countries') as $code => $country)
                                        <option value="{{ $code }}" {{ old('country_code', $store->country_code) === $code ? 'selected' : '' }}>
                                            {{ $country['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div style="height:1px; background:#e2e8f0; margin:24px 0;"></div>
                        <h6 style="font-size:0.82rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:16px;">API Credentials</h6>

                        <div style="margin-bottom:16px;">
                            <label class="field-label">App Key <span class="req">*</span></label>
                            <input type="text" class="field-input @error('app_key') is-invalid @enderror" id="app_key" name="app_key" value="{{ old('app_key', $store->app_key) }}" required>
                            @error('app_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div style="margin-bottom:16px;">
                            <label class="field-label">App Secret</label>
                            <div class="input-group-premium">
                                <input type="password" class="field-input @error('app_secret') is-invalid @enderror" id="app_secret" name="app_secret" placeholder="Leave blank to keep current secret">
                                <span class="input-group-btn" onclick="togglePassword('app_secret')">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            @error('app_secret')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <div class="field-hint">Leave empty if you do not want to alter the saved secret token</div>
                        </div>

                        <div style="height:1px; background:#e2e8f0; margin:24px 0;"></div>
                        <h6 style="font-size:0.82rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:16px;">Sync Integrations</h6>

                        <div class="toggle-grid">
                            <div class="toggle-row">
                                <span class="tl">Active State</span>
                                <div class="toggle-wrap">
                                    <input class="premium-toggle" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $store->is_active) ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="toggle-row">
                                <span class="tl">Enable Auto-Sync</span>
                                <div class="toggle-wrap">
                                    <input class="premium-toggle" type="checkbox" id="auto_sync" name="auto_sync" value="1" {{ old('auto_sync', $store->auto_sync) ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div style="margin-bottom:24px;">
                            <label class="field-label">Sync Interval</label>
                            <select class="field-select" id="sync_interval" name="sync_interval">
                                <option value="2" {{ old('sync_interval', $store->sync_interval) == 2 ? 'selected' : '' }}>Every 2 minutes</option>
                                <option value="5" {{ old('sync_interval', $store->sync_interval) == 5 ? 'selected' : '' }}>Every 5 minutes</option>
                                <option value="10" {{ old('sync_interval', $store->sync_interval) == 10 ? 'selected' : '' }}>Every 10 minutes</option>
                                <option value="15" {{ old('sync_interval', $store->sync_interval) == 15 ? 'selected' : '' }}>Every 15 minutes</option>
                                <option value="30" {{ old('sync_interval', $store->sync_interval) == 30 ? 'selected' : '' }}>Every 30 minutes</option>
                                <option value="60" {{ old('sync_interval', $store->sync_interval) == 60 ? 'selected' : '' }}>Every hour</option>
                                <option value="120" {{ old('sync_interval', $store->sync_interval) == 120 ? 'selected' : '' }}>Every 2 hours</option>
                                <option value="360" {{ old('sync_interval', $store->sync_interval) == 360 ? 'selected' : '' }}>Every 6 hours</option>
                            </select>
                        </div>

                        <div style="display:flex; align-items:center; gap:12px;">
                            <button type="submit" class="btn-action indigo">
                                <i class="fas fa-save"></i> Update Store
                            </button>
                            <a href="{{ route($routePrefix . 'stores.index') }}" class="btn-action ghost">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Meta & Sync Controls --}}
        <div class="col-lg-4">
            {{-- Connection Panel --}}
            <div class="module-wrap">
                <div class="section-band">
                    <div class="band-icon"><i class="fas fa-link"></i></div>
                    <h2>Connection Status</h2>
                </div>
                <div class="module-body">
                    @if($store->isConnected())
                        <div class="status-text-green mb-2"><i class="fas fa-check-circle"></i> Connected</div>
                        @if($store->token_expires_at)
                            <div style="font-size:0.8rem; color:#64748b; line-height:1.5; margin-bottom:16px;">
                                Expires: {{ $store->token_expires_at->format('M d, Y H:i') }}
                                <br>
                                <span style="font-weight:600; color:#4338ca;">({{ $store->token_expires_at->diffForHumans() }})</span>
                            </div>
                        @endif
                        <div style="display:flex; flex-wrap:wrap; gap:8px;">
                            <button type="button" class="btn-panel-action test-connection" data-store-id="{{ $store->id }}">
                                <i class="fas fa-plug"></i> Test Connection
                            </button>
                            <button type="button" class="btn-panel-action refresh-token" data-store-id="{{ $store->id }}">
                                <i class="fas fa-redo"></i> Refresh Token
                            </button>
                        </div>
                    @else
                        <div class="status-text-orange mb-2"><i class="fas fa-exclamation-triangle"></i> Token Expired</div>
                        <div style="font-size:0.8rem; color:#64748b; margin-bottom:16px;">
                            @if($store->access_token)
                                Token has expired. Please refresh the connection or re-authorize.
                            @else
                                Integration is not authorized. Please authorize with Daraz.
                            @endif
                        </div>
                        <a href="{{ route($routePrefix . 'stores.authorize', $store) }}" class="btn-panel-action bpa-green">
                            <i class="fas fa-key"></i> Authorize Channel
                        </a>
                    @endif
                </div>
            </div>

            {{-- Statistics Panel --}}
            <div class="module-wrap">
                <div class="section-band">
                    <div class="band-icon"><i class="fas fa-chart-pie"></i></div>
                    <h2>Outlet Statistics</h2>
                </div>
                <div class="module-body" style="padding: 10px 20px;">
                    <ul class="stat-list">
                        <li>
                            <span>Product Mappings</span>
                            <strong>{{ $store->productMappings()->count() }}</strong>
                        </li>
                        <li>
                            <span>Enabled Mappings</span>
                            <strong>{{ $store->productMappings()->where('sync_enabled', true)->count() }}</strong>
                        </li>
                        <li>
                            <span>Last Synced</span>
                            <strong>{{ $store->last_synced_at?->diffForHumans() ?? 'Never' }}</strong>
                        </li>
                        <li>
                            <span>Created At</span>
                            <strong>{{ $store->created_at->format('M d, Y') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Danger zone --}}
            <div class="module-wrap" style="border-color:#fca5a5;">
                <div class="section-band danger-band">
                    <div class="band-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <h2>Danger Zone</h2>
                </div>
                <div class="module-body">
                    <p style="font-size:0.8rem; color:#64748b; line-height:1.5; margin-bottom:14px;">Deleting this store will permanently clean all associated SKU links and mapped histories.</p>
                    <form action="{{ route($routePrefix . 'stores.destroy', $store) }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This action cannot be undone.');" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-action danger" style="padding: 8px 18px; font-size: 0.8rem;">
                            <i class="fas fa-trash-alt"></i> Delete Channel
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

            fetch(`{{ url($urlPrefix . '/stores') }}/${storeId}/test`, {
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

            fetch(`{{ url($urlPrefix . '/stores') }}/${storeId}/refresh-token`, {
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
