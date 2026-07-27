@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .delivery-container {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            padding: 1.5rem;
            border-radius: 1rem;
        }
        .page-header-premium {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            padding: 2rem;
            border-radius: 1rem;
            color: white;
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.15);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        .page-header-premium::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .card-premium {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            background-color: #ffffff;
            transition: all 0.3s ease;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .card-premium:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }
        .balance-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .btn-premium {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }
        .btn-premium-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        .btn-premium-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
            color: white;
        }
        .btn-premium-secondary {
            background-color: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }
        .btn-premium-secondary:hover {
            background-color: #e2e8f0;
            color: #334155;
        }
        .table-premium {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            width: 100% !important;
        }
        .table-premium thead th {
            background-color: #1e293b !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 0.75rem !important;
            letter-spacing: 0.05em !important;
            padding: 1.1rem 1rem !important;
            border: none !important;
        }
        .table-premium thead th:first-child {
            border-top-left-radius: 0.75rem !important;
        }
        .table-premium thead th:last-child {
            border-top-right-radius: 0.75rem !important;
        }
        .table-premium td {
            background-color: #ffffff;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1rem;
            vertical-align: middle;
        }
        .table-premium tr td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }
        .table-premium tr td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }
        .table-premium tr:hover td {
            background-color: #f8fafc;
        }
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.6rem;
            border-radius: 2rem;
            display: inline-block;
        }
        .status-active { background-color: #d1fae5; color: #065f46; }
        .status-inactive { background-color: #fee2e2; color: #991b1b; }
        .provider-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            font-size: 1.25rem;
            background-color: #eff6ff;
            color: #3b82f6;
        }
    </style>
@endsection

@section('content')
    <div class="delivery-container">
        <!-- Header -->
        <div class="page-header-premium shadow-sm">
            <nav aria-label="breadcrumb" class="breadcrumb-premium mb-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-white-50 text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Delivery Integrations</li>
                </ol>
            </nav>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 font-weight-bold text-white">Delivery Integrations</h3>
                    <p class="mb-0 text-white-50">Manage credentials and verify API balances for Steadfast and Pathao courier networks.</p>
                </div>
            </div>
        </div>

        <!-- Courier Balance Cards -->
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="card-premium">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="provider-icon bg-success-light text-success" style="background-color: #ecfdf5; color: #10b981;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h6 class="font-weight-bold text-muted mb-0">Steadfast Balance</h6>
                    </div>
                    <div class="balance-value" id="steadfast-balance-value">
                        Loading...
                    </div>
                    <button id="refresh-steadfast-balance" class="btn btn-premium btn-premium-primary">
                        <i class="fas fa-sync-alt"></i> Refresh Balance
                    </button>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-premium">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <div class="provider-icon" style="background-color: #fff7ed; color: #f97316;">
                            <i class="fas fa-ban"></i>
                        </div>
                        <h6 class="font-weight-bold text-muted mb-0">Pathao Balance</h6>
                    </div>
                    <div class="balance-value text-muted" style="font-size: 1rem;">
                        Not supported by Pathao API
                    </div>
                    <button id="refresh-pathao-balance" class="btn btn-premium btn-premium-secondary" disabled>
                        <i class="fas fa-sync-alt"></i> Not Available
                    </button>
                </div>
            </div>
        </div>

        <!-- Table Listing -->
        <div class="card-premium mt-3">
            <h5 class="font-weight-bold text-dark mb-4"><i class="fas fa-network-wired text-primary"></i> Configured Integrations</h5>
            <div class="table-responsive">
                <table class="table table-premium">
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
                        @foreach($integrations as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="provider-icon">
                                            <i class="fas fa-truck-loading"></i>
                                        </div>
                                        <div class="font-weight-bold text-dark">{{ ucfirst($item->provider) }}</div>
                                    </div>
                                </td>
                                @if($isSuperAdmin ?? false)
                                    <td>
                                        <span class="badge bg-primary bg-opacity-10 text-primary font-weight-bold" style="font-size: 0.8rem; padding: 4px 10px; border-radius: 6px;">
                                            <i class="fas fa-user-circle me-1"></i> {{ $item->user->name ?? $item->user->email ?? 'System Default' }}
                                        </span>
                                    </td>
                                @endif
                                <td>
                                    <span class="status-badge {{ $item->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-end">
                                        <a href="{{ route('admin.delivery.integration', $item->id) }}" class="btn btn-premium btn-premium-primary btn-sm">
                                            <i class="fas fa-edit"></i> Edit Credentials
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function fetchSteadfastBalance() {
            const balanceDiv = document.getElementById('steadfast-balance-value');
            balanceDiv.textContent = 'Loading...';
            fetch("{{ route('admin.steadfast.balance') }}")
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        balanceDiv.textContent = data.balance !== null ? data.balance + ' BDT' : 'N/A';
                    } else {
                        balanceDiv.textContent = 'Error: ' + (data.message || 'Could not fetch balance');
                    }
                })
                .catch(() => {
                    balanceDiv.textContent = 'Error fetching balance';
                });
        }
        document.addEventListener('DOMContentLoaded', function() {
            fetchSteadfastBalance();
            document.getElementById('refresh-steadfast-balance').addEventListener('click', fetchSteadfastBalance);
        });
    </script>
@endsection
