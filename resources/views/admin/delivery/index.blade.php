@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .delivery-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            padding: 1.75rem;
            min-height: 100vh;
        }
        .header-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #2563eb 100%);
            border-radius: 1.25rem;
            padding: 2.25rem;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.1);
            margin-bottom: 2rem;
        }
        .header-banner::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .header-badge {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #60a5fa;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.35rem 0.85rem;
            border-radius: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }
        .metric-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            padding: 1.75rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
            transition: all 0.25s ease-in-out;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
            border-color: #cbd5e1;
        }
        .provider-avatar {
            width: 52px;
            height: 52px;
            border-radius: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .avatar-steadfast {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .avatar-pathao {
            background-color: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }
        .balance-num {
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }
        .action-btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff !important;
            border: none;
            border-radius: 0.65rem;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.6rem 1.25rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
            transition: all 0.2s ease;
        }
        .action-btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }
        .action-btn-subtle {
            background: #f1f5f9;
            color: #475569 !important;
            border: 1px solid #e2e8f0;
            border-radius: 0.65rem;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.55rem 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }
        .action-btn-subtle:hover {
            background: #e2e8f0;
            color: #1e293b !important;
        }
        .table-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            margin-top: 2rem;
        }
        .table-header-box {
            padding: 1.35rem 1.75rem;
            border-bottom: 1px solid #f1f5f9;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .table-modern {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
        }
        .table-modern thead th {
            background: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 1rem 1.75rem;
            border-bottom: 1px solid #e2e8f0;
            border-top: none;
        }
        .table-modern tbody td {
            padding: 1.25rem 1.75rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #1e293b;
            font-size: 0.9rem;
        }
        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }
        .table-modern tbody tr:hover td {
            background-color: #f8fafc;
        }
        .pill-active {
            background-color: #d1fae5;
            color: #047857;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.35rem 0.85rem;
            border-radius: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .pill-active::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25);
        }
        .pill-inactive {
            background-color: #fee2e2;
            color: #b91c1c;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.35rem 0.85rem;
            border-radius: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .pill-inactive::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #ef4444;
        }
        .user-owner-badge {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            padding: 0.3rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
    </style>
@endsection

@section('content')
    <div class="delivery-wrapper">
        <!-- Header Banner -->
        <div class="header-banner">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 position-relative" style="z-index: 2;">
                <div>
                    <div class="header-badge mb-2">
                        <i class="fas fa-shipping-fast"></i> Logistics & Courier Gateway
                    </div>
                    <h2 class="fw-extrabold text-white mb-2" style="font-size: 1.85rem; letter-spacing: -0.02em;">Courier Integrations</h2>
                    <p class="mb-0 text-white-50" style="font-size: 0.95rem; max-width: 650px;">
                        Manage direct API connections, verify realtime wallet balances, and configure delivery parameters for Steadfast & Pathao networks.
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.delivery.integration') }}" class="action-btn-primary">
                        <i class="fas fa-plus-circle"></i> Add New Integration
                    </a>
                </div>
            </div>
        </div>

        @php
            $hasSteadfast = $integrations->where('provider', 'steadfast')->where('is_active', true)->first();
            $hasPathao = $integrations->where('provider', 'pathao')->where('is_active', true)->first();
        @endphp

        <!-- 2 Courier Cards Grid -->
        <div class="row g-4">
            <!-- Steadfast Balance Card -->
            <div class="col-12 col-md-6">
                <div class="metric-card">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="provider-avatar avatar-steadfast">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Steadfast Courier</h5>
                                    <span class="text-muted" style="font-size: 0.82rem;">Merchant Account Balance</span>
                                </div>
                            </div>
                            @if($hasSteadfast)
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 6px;">
                                    API Active
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 6px;">
                                    Not Configured
                                </span>
                            @endif
                        </div>
                        <div class="my-4">
                            <div class="balance-num" id="steadfast-balance-value">
                                @if($hasSteadfast)
                                    <span class="text-muted fs-6"><i class="fas fa-spinner fa-spin me-2 text-primary"></i> Querying balance...</span>
                                @else
                                    <span class="text-muted fs-6 fw-semibold"><i class="fas fa-info-circle text-secondary me-1"></i> Not Configured for this Account</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="pt-3 border-top border-light d-flex align-items-center justify-content-between">
                        <span class="text-muted small" style="font-size: 0.82rem;">
                            <i class="fas fa-satellite-dish me-1 text-primary"></i> Live API Query
                        </span>
                        @if($hasSteadfast)
                            <button id="refresh-steadfast-balance" class="action-btn-subtle py-1.5 px-3" style="font-size: 0.82rem;">
                                <i class="fas fa-sync-alt"></i> Refresh Balance
                            </button>
                        @else
                            <a href="{{ route('admin.delivery.integration') }}" class="action-btn-primary py-1 px-3" style="font-size: 0.78rem;">
                                <i class="fas fa-plus-circle"></i> Configure
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pathao Balance Card -->
            <div class="col-12 col-md-6">
                <div class="metric-card">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="provider-avatar avatar-pathao">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">Pathao Courier</h5>
                                    <span class="text-muted" style="font-size: 0.82rem;">Merchant Account Balance</span>
                                </div>
                            </div>
                            @if($hasPathao)
                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 6px;">
                                    Connected
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary fw-bold" style="font-size: 0.75rem; padding: 5px 10px; border-radius: 6px;">
                                    Not Configured
                                </span>
                            @endif
                        </div>
                        <div class="my-4">
                            <div class="balance-num text-muted" style="font-size: 1.25rem; font-weight: 700; color: #64748b !important;">
                                <i class="fas fa-info-circle text-warning me-1"></i> Not supported by Pathao API
                            </div>
                            <p class="text-muted mb-0 mt-1" style="font-size: 0.82rem;">
                                Pathao API handles billing & wallet balances directly via merchant portal.
                            </p>
                        </div>
                    </div>
                    <div class="pt-3 border-top border-light d-flex align-items-center justify-content-between">
                        <span class="text-muted small" style="font-size: 0.82rem;">Webhook & Auto-dispatch</span>
                        <span class="text-secondary fw-semibold" style="font-size: 0.82rem;">
                            @if($hasPathao)
                                <i class="fas fa-check-circle text-success me-1"></i> Integration Ready
                            @else
                                <a href="{{ route('admin.delivery.integration') }}" class="text-primary fw-bold text-decoration-none" style="font-size: 0.78rem;">
                                    <i class="fas fa-plus-circle me-1"></i> Configure
                                </a>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Listing -->
        <div class="table-card">
            <div class="table-header-box">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3" style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-sliders-h fs-6"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Active Courier Credentials</h5>
                        <p class="text-muted mb-0" style="font-size: 0.8rem;">Direct API keys and dispatch access configured for this admin</p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('admin.delivery.integration') }}" class="action-btn-subtle py-1.5 px-3">
                        <i class="fas fa-plus text-primary"></i> Add Provider
                    </a>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Courier Provider</th>
                            @if($isSuperAdmin ?? false)
                                <th>Assigned Account</th>
                            @endif
                            <th>Integration Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($integrations as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="provider-avatar {{ $item->provider === 'steadfast' ? 'avatar-steadfast' : 'avatar-pathao' }}" style="width: 40px; height: 40px; font-size: 1.1rem;">
                                            <i class="fas {{ $item->provider === 'steadfast' ? 'fa-truck-loading' : 'fa-paper-plane' }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ ucfirst($item->provider) }} Express</div>
                                            <span class="text-muted" style="font-size: 0.78rem;">
                                                {{ $item->provider === 'steadfast' ? 'API Key & Secret Configured' : 'Client ID & Store ID Configured' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                @if($isSuperAdmin ?? false)
                                    <td>
                                        <span class="user-owner-badge">
                                            <i class="fas fa-user-circle"></i> {{ $item->user->name ?? $item->user->email ?? 'System Default' }}
                                        </span>
                                    </td>
                                @endif
                                <td>
                                    @if($item->is_active)
                                        <span class="pill-active">Active</span>
                                    @else
                                        <span class="pill-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-end">
                                        <a href="{{ route('admin.delivery.integration', $item->id) }}" class="action-btn-subtle py-1.5 px-3">
                                            <i class="fas fa-pen-to-square text-primary"></i> Edit Credentials
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ ($isSuperAdmin ?? false) ? 4 : 3 }}" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-truck-loading fs-2 mb-3 opacity-40"></i>
                                        <h6>No Courier Integrations Found</h6>
                                        <p class="small mb-3">Click below to set up your Steadfast or Pathao credentials.</p>
                                        <a href="{{ route('admin.delivery.integration') }}" class="action-btn-primary">
                                            <i class="fas fa-plus-circle"></i> Add Integration
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if($hasSteadfast)
        <script>
            function fetchSteadfastBalance() {
                const balanceDiv = document.getElementById('steadfast-balance-value');
                const btn = document.getElementById('refresh-steadfast-balance');
                
                balanceDiv.innerHTML = '<span class="text-muted fs-6"><i class="fas fa-spinner fa-spin me-2 text-primary"></i> Querying balance...</span>';
                if(btn) btn.classList.add('disabled');

                fetch("{{ route('admin.steadfast.balance') }}")
                    .then(res => res.json())
                    .then(data => {
                        if (data.success && data.balance !== null && data.balance !== undefined) {
                            balanceDiv.innerHTML = '<span class="text-success fw-extrabold">৳' + Number(data.balance).toLocaleString('en-US', {minimumFractionDigits: 2}) + ' <small class="fs-6 text-muted font-weight-normal">BDT</small></span>';
                        } else {
                            balanceDiv.innerHTML = '<span class="text-danger fs-6 fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> ' + (data.message || 'Error fetching balance') + '</span>';
                        }
                    })
                    .catch(() => {
                        balanceDiv.innerHTML = '<span class="text-danger fs-6 fw-bold"><i class="fas fa-exclamation-circle me-1"></i> Error fetching balance</span>';
                    })
                    .finally(() => {
                        if(btn) btn.classList.remove('disabled');
                    });
            }

            document.addEventListener('DOMContentLoaded', function() {
                fetchSteadfastBalance();
                const refreshBtn = document.getElementById('refresh-steadfast-balance');
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', fetchSteadfastBalance);
                }
            });
        </script>
    @endif
@endsection
