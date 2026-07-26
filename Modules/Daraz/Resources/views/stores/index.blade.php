@extends(request()->is('vendor/*') ? 'vendor.layouts.app' : 'layouts.master')

@section('title', 'Daraz Stores')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #daraz-stores-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* ── ALERTS ──────────────────────────────────── */
    .alert-premium {
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 10px;
        position: relative;
    }
    .alert-ok-p  { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #86efac; color: #15803d; }
    .alert-err-p { background: linear-gradient(135deg,#fef2f2,#fee2e2); border: 1px solid #fca5a5; color: #b91c1c; }
    .alert-premium .close-btn {
        position: absolute; top: 12px; right: 14px;
        background: none; border: none; cursor: pointer;
        color: inherit; opacity: 0.6; font-size: 1rem;
        padding: 0; line-height: 1;
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

    .header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .btn-hdr {
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 0.825rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-hdr.primary { background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
    .btn-hdr.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(99,102,241,0.4); color: #fff; }

    /* ── STATS CARDS ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 992px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stat-grid { grid-template-columns: 1fr; } }
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .stat-icon.si-indigo { background: #e0e7ff; color: #4338ca; }
    .stat-icon.si-green  { background: #dcfce7; color: #15803d; }
    .stat-icon.si-purple { background: #f5f3ff; color: #8b5cf6; }
    .stat-icon.si-orange { background: #fffbeb; color: #d97706; }
    .stat-label { font-size: 0.76rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .stat-value { font-size: 1.6rem; font-weight: 800; color: #0f172a; line-height: 1; }

    /* ── TABLE CARD ────────────────────────── */
    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }
    .table-card-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .table-card-header h2 {
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .table-card-header .hdr-icon {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.15);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: #fff;
        backdrop-filter: blur(4px);
    }

    /* ── DATA TABLE ─────────────────────────────── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    .data-table thead th {
        padding: 14px 18px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        text-align: left;
        white-space: nowrap;
    }
    .data-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }
    .data-table tbody tr:hover { background: #fafbff; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table td {
        padding: 14px 18px;
        font-size: 0.85rem;
        color: #334155;
        vertical-align: middle;
    }
    .text-title { font-weight: 700; color: #0f172a; }
    .text-subtitle { font-size: 0.78rem; color: #64748b; margin-top: 2px; display: block; }

    /* ── BADGES ── */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }
    .bp-active   { background: #dcfce7; color: #15803d; }
    .bp-inactive { background: #fee2e2; color: #b91c1c; }
    .bp-warning  { background: #fef3c7; color: #b45309; }
    .bp-country  { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }

    /* ── CUSTOM TOGGLE SWITCH ── */
    .premium-toggle {
        appearance: none; -webkit-appearance: none;
        width: 40px; height: 20px;
        border-radius: 99px; background: #cbd5e1;
        cursor: pointer; position: relative;
        transition: background 0.22s ease;
        border: none; outline: none; display: block; margin: 0;
    }
    .premium-toggle::after {
        content: ''; position: absolute;
        top: 2px; left: 2px;
        width: 16px; height: 16px;
        background: #fff; border-radius: 50%;
        transition: left 0.22s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .premium-toggle:checked { background: #f97316; } /* Orange background sync checked color */
    .premium-toggle:checked::after { left: 22px; }
    .premium-toggle:disabled { opacity: 0.5; cursor: not-allowed; }

    /* ── ACTION BUTTONS ── */
    .row-actions { display: flex; gap: 6px; align-items: center; }
    .icon-btn {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #475569;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.825rem;
        cursor: pointer;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .icon-btn.ib-test:hover { border-color: #3b82f6; background: #eff6ff; color: #2563eb; }
    .icon-btn.ib-refresh:hover { border-color: #8b5cf6; background: #f5f3ff; color: #7c3aed; }
    .icon-btn.ib-edit:hover { border-color: #10b981; background: #ecfdf5; color: #059669; }
    .icon-btn.ib-delete:hover { border-color: #fca5a5; background: #fef2f2; color: #dc2626; }
    
    .icon-btn.ib-auth { background: linear-gradient(135deg, #10b981, #059669); color: #fff; border: none; width: auto; padding: 0 14px; font-weight: 700; font-size: 0.78rem; gap: 5px; box-shadow: 0 3px 8px rgba(16,185,129,0.3); }
    .icon-btn.ib-auth:hover { background: linear-gradient(135deg, #059669, #047857); color: #fff; transform: translateY(-1px); box-shadow: 0 5px 12px rgba(16,185,129,0.4); }

    /* ── EMPTY STATE ── */
    .empty-state { padding: 60px 24px; text-align: center; }
    .empty-icon {
        width: 70px; height: 70px;
        border-radius: 50%;
        background: #ede9fe;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 1.8rem;
        color: #6366f1;
    }
    .empty-state h4 { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin-bottom: 6px; }
    .empty-state p  { font-size: 0.875rem; color: #94a3b8; margin-bottom: 20px; }
</style>
@endsection

@section('content')
@php
    $isVendor = request()->is('vendor/*');
    $routePrefix = $isVendor ? 'vendor.daraz.' : 'admin.daraz.';
    $urlPrefix = $isVendor ? 'vendor/daraz' : 'admin/daraz';
@endphp
<div id="daraz-stores-page" class="container-fluid px-4 py-4">

    @if(session('success'))
        <div class="alert-premium alert-ok-p">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-premium alert-err-p">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- Header Banner --}}
    <div class="hdr-panel">
        <div class="hdr-title">
            <h4>Seller Accounts</h4>
            <p>Configure and coordinate active seller accounts</p>
        </div>
        <div class="header-actions">
            <a href="{{ route($routePrefix . 'stores.create') }}" class="btn-hdr primary">
                <i class="fas fa-plus"></i> Integrate Channel
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon si-indigo"><i class="fas fa-store"></i></div>
            <div>
                <div class="stat-label">Configured Channels</div>
                <div class="stat-value">{{ $stores->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-label">Authorized Outlets</div>
                <div class="stat-value">{{ $stores->filter(fn($s) => $s->isConnected())->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-purple"><i class="fas fa-link"></i></div>
            <div>
                <div class="stat-label">Linked SKU Pairs</div>
                <div class="stat-value">{{ $stores->sum('product_mappings_count') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-orange"><i class="fas fa-sync"></i></div>
            <div>
                <div class="stat-label">Background Sync On</div>
                <div class="stat-value">{{ $stores->filter(fn($s) => $s->auto_sync)->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="table-card">
        <div class="table-card-header">
            <h2>
                <span class="hdr-icon"><i class="fas fa-store"></i></span>
                Integrated Store Channels
            </h2>
        </div>

        @if($stores->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fas fa-store"></i></div>
                <h4>No Seller Accounts Configured</h4>
                <p>Integrate your initial seller account to begin mapping items.</p>
                <a href="{{ route($routePrefix . 'stores.create') }}" class="btn-hdr primary" style="margin: 0 auto; text-decoration: none;">
                    <i class="fas fa-plus-circle"></i> Integrate Channel
                </a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Outlet Name</th>
                            @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('super admin') || auth()->user()->id == 1)
                                <th>Account Owner</th>
                            @endif
                            <th>Country</th>
                            <th>Status</th>
                            <th>Bridges</th>
                            <th>Last Synced</th>
                            <th>Background Sync</th>
                            <th style="width: 220px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stores as $store)
                            <tr>
                                <td>
                                    <span class="text-title">{{ $store->name }}</span>
                                    <span class="text-subtitle">App Key: {{ Str::limit($store->app_key, 20) }}</span>
                                </td>
                                @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('super admin') || auth()->user()->id == 1)
                                    <td>
                                        @if($store->vendor)
                                            <span class="text-title" style="font-size:0.85rem; font-weight:700;">{{ $store->vendor->name }}</span>
                                            <span class="text-subtitle">{{ $store->vendor->email }}</span>
                                        @else
                                            <span class="badge-pill bp-country" style="background:#e2e8f0; color:#475569;">System / Global</span>
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <span class="badge-pill bp-country">{{ $store->country_code }}</span>
                                </td>
                                <td>
                                    @if($store->isConnected())
                                        <span class="badge-pill bp-active"><i class="fas fa-check-circle"></i> Connected</span>
                                        @if($store->token_expires_at)
                                            <span class="text-subtitle">Expires: {{ $store->token_expires_at->diffForHumans() }}</span>
                                        @endif
                                    @elseif($store->access_token)
                                        <span class="badge-pill bp-warning"><i class="fas fa-exclamation-triangle"></i> Token Expired</span>
                                    @else
                                        <span class="badge-pill bp-inactive"><i class="fas fa-link-slash"></i> Not Authorized</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-title">{{ $store->product_mappings_count ?? 0 }}</span> <span style="color:#64748b; font-size:0.78rem;">mapped</span>
                                </td>
                                <td>
                                    @if($store->last_synced_at)
                                        <span class="text-title">{{ $store->last_synced_at->diffForHumans() }}</span>
                                    @else
                                        <span style="color:#cbd5e1;">Never</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route($routePrefix . 'stores.toggle', $store) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <input class="premium-toggle" type="checkbox"
                                               {{ $store->auto_sync ? 'checked' : '' }}
                                               onchange="this.form.submit()"
                                               {{ !$store->isConnected() ? 'disabled' : '' }}>
                                    </form>
                                </td>
                                <td>
                                    <div class="row-actions" style="justify-content: flex-end;">
                                        @if(!$store->isConnected())
                                            <a href="{{ route($routePrefix . 'stores.authorize', $store) }}" class="icon-btn ib-auth" title="Authorize Channel">
                                                <i class="fas fa-fingerprint"></i> Authorize
                                            </a>
                                        @else
                                            <button type="button" class="icon-btn ib-test test-connection" data-store-id="{{ $store->id }}" title="Test Connection">
                                                <i class="fas fa-plug"></i>
                                            </button>
                                            <button type="button" class="icon-btn ib-refresh refresh-token" data-store-id="{{ $store->id }}" title="Refresh Token">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route($routePrefix . 'stores.edit', $store) }}" class="icon-btn ib-edit" title="Edit Store Settings">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        <form action="{{ route($routePrefix . 'stores.destroy', $store) }}" method="POST" style="display:inline;"
                                              onsubmit="return confirm('Are you sure? This will remove all product mappings for this store.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="icon-btn ib-delete" title="Delete Channel">
                                                <i class="fas fa-trash-alt"></i>
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
