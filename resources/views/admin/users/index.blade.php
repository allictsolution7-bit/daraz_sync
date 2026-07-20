@extends('layouts.master')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Modern Premium Portal Design System */
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --success: #10b981;
            --info: #06b6d4;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark-slate: #1e293b;
            --text-main: #334155;
            --text-muted: #64748b;
            --bg-glass: rgba(255, 255, 255, 0.85);
            --border-glass: rgba(226, 232, 240, 0.8);
            --shadow-premium: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }


        /* Nav Tabs Layout */
        .portal-tabs-container {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 8px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-premium);
            display: flex;
            gap: 8px;
        }

        .portal-tab-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-muted);
            border: none;
            background: transparent;
            border-radius: 12px;
            text-decoration: none;
            transition: var(--transition-smooth);
        }

        .portal-tab-btn:hover {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.05);
        }

        .portal-tab-btn.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary) 0%, #3730a3 100%);
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.25);
        }

        /* Stat Cards */
        .stat-card-premium {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-premium);
            transition: var(--transition-smooth);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 140px;
        }

        .stat-card-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 90% 10%, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .stat-card-premium:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.08);
        }

        .stat-card-premium.gradient-1 {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: #ffffff;
            border: none;
        }

        .stat-card-premium.gradient-2 {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
        }

        .stat-card-premium.gradient-3 {
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            color: #ffffff;
            border: none;
        }

        .stat-card-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            opacity: 0.85;
            margin-bottom: 6px;
        }

        .stat-card-value {
            font-size: 32px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-card-icon {
            position: absolute;
            right: 20px;
            bottom: 15px;
            font-size: 4rem;
            opacity: 0.15;
            pointer-events: none;
        }

        /* Action Panel */
        .premium-actions-bar {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 16px;
            padding: 16px 24px;
            box-shadow: var(--shadow-premium);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Table & Container Cards */
        .workspace-card {
            background: var(--bg-glass);
            border: 1px solid var(--border-glass);
            border-radius: 20px;
            box-shadow: var(--shadow-premium);
            padding: 24px;
            margin-bottom: 40px;
        }

        .premium-table {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 8px !important;
        }

        .premium-table thead th {
            background-color: #f1f5f9 !important;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 14px 18px !important;
            border: none !important;
        }

        .premium-table tbody tr {
            background-color: #ffffff;
            transition: var(--transition-smooth);
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.01);
        }

        .premium-table tbody tr:hover {
            background-color: rgba(241, 245, 249, 0.8) !important;
        }

        .premium-table tbody td {
            padding: 16px 18px !important;
            border-top: 1px solid #f1f5f9 !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle;
            color: var(--text-main);
        }

        .premium-table tbody td:first-child {
            border-left: 1px solid #f1f5f9 !important;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .premium-table tbody td:last-child {
            border-right: 1px solid #f1f5f9 !important;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        /* Custom Badges */
        .badge-premium {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-premium-admin {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .badge-premium-info {
            background-color: rgba(6, 182, 212, 0.1);
            color: var(--info);
        }

        .badge-premium-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .badge-premium-secondary {
            background-color: rgba(100, 116, 139, 0.1);
            color: var(--text-muted);
        }

        /* Action Buttons */
        .btn-action-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-smooth);
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: var(--text-muted);
        }

        .btn-action-circle:hover {
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-action-view:hover {
            background-color: var(--info);
            border-color: var(--info);
        }

        .btn-action-edit:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-action-delete:hover {
            background-color: var(--danger);
            border-color: var(--danger);
        }

        /* Admin Packages Pricing Matrix Style overrides */
        .premium-card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-premium);
            transition: var(--transition-smooth);
            overflow: hidden;
            background: #fff;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .premium-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.08);
        }

        .gradient-header {
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            color: #fff;
            padding: 28px;
            position: relative;
        }

        .gradient-header.starter {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }

        .gradient-header.pro {
            background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        }

        .gradient-header.enterprise {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .gradient-header.lifetime {
            background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
        }

        .package-price-badge {
            position: absolute;
            bottom: -15px;
            right: 20px;
            background: var(--success);
            color: #fff;
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 13px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
        }

        .feature-item {
            padding: 12px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-included {
            color: var(--dark-slate);
            font-weight: 500;
        }

        .feature-excluded {
            color: var(--text-muted);
            text-decoration: line-through;
            opacity: 0.65;
        }

        .feature-icon-included {
            color: var(--success);
            margin-right: 12px;
            font-size: 16px;
        }

        .feature-icon-excluded {
            color: var(--danger);
            margin-right: 12px;
            font-size: 16px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4 pt-3">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}" class="text-decoration-none text-muted">Home</a></li>
                @if(request()->get('view') === 'packages')
                    <li class="breadcrumb-item"><a href="{{ route('admin.users') }}" class="text-decoration-none text-muted">Users Directory</a></li>
                    <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">SaaS Pricing Tiers</li>
                @else
                    <li class="breadcrumb-item active text-dark font-weight-bold" aria-current="page">Users Directory</li>
                @endif
            </ol>
        </nav>

        <!-- Dynamic Success Message Alert -->
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4" style="background-color: rgba(16, 185, 129, 0.1); color: var(--success);">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Portal Tabs Navigation -->
        <div class="portal-tabs-container">
            <a href="{{ route('admin.users') }}" class="portal-tab-btn {{ request()->get('view') !== 'packages' ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> Users Accounts Directory
            </a>
            <a href="{{ route('admin.users', ['view' => 'packages']) }}" class="portal-tab-btn {{ request()->get('view') === 'packages' ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i> SaaS Billing Tiers
            </a>
        </div>

        @if(request()->get('view') === 'packages')
            <!-- ADMIN PACKAGES WORKSPACE -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 font-weight-bold" style="color: var(--dark-slate);">SaaS Billing Matrix</h3>
                    <p class="text-muted mb-0">Create, customize, and regulate membership subscription plans and pricing structures</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#manageFeaturesModal">
                        <i class="fas fa-list-ul me-2"></i> Manage Features
                    </button>
                    <button class="btn btn-primary rounded-pill px-4" onclick="openCreateModal()">
                        <i class="fas fa-plus me-2"></i> New Subscription Plan
                    </button>
                </div>
            </div>

            <!-- Stats Dashboard Row -->
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-1">
                        <div>
                            <div class="stat-card-title">Total Subscription Tiers</div>
                            <div class="stat-card-value" id="totalPackagesCount">0</div>
                        </div>
                        <i class="fas fa-cubes stat-card-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-2">
                        <div>
                            <div class="stat-card-title">Active Plans</div>
                            <div class="stat-card-value" id="activePackagesCount">0</div>
                        </div>
                        <i class="fas fa-check-double stat-card-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-3">
                        <div>
                            <div class="stat-card-title">Master Feature Items</div>
                            <div class="stat-card-value" id="totalFeaturesCount">0</div>
                        </div>
                        <i class="fas fa-magic stat-card-icon"></i>
                    </div>
                </div>
            </div>

            <!-- Packages Grid -->
            <div class="row" id="packagesGrid">
                <!-- Dynamic cards populated by script -->
            </div>

            <!-- Create/Edit Package Modal -->
            <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                        <div class="modal-header bg-dark text-white border-0 py-3">
                            <h5 class="modal-title font-weight-bold" id="packageModalLabel">Create New Subscription Plan</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form id="packageForm">
                                <input type="hidden" id="packageId">
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="packageName" class="form-label font-weight-bold">Plan Name</label>
                                        <input type="text" class="form-control" id="packageName" placeholder="e.g. Professional Premium" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="packageTheme" class="form-label font-weight-bold">Visual Card Theme</label>
                                        <select class="form-select" id="packageTheme">
                                            <option value="starter">Blue Glow (Starter)</option>
                                            <option value="pro">Indigo Purple (Pro)</option>
                                            <option value="enterprise">Warm Gold (Enterprise)</option>
                                            <option value="lifetime">Radiant Pink (Lifetime)</option>
                                            <option value="default">Dark Slate (Default)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="packageDetails" class="form-label font-weight-bold">Plan Summary / Subtitle</label>
                                    <textarea class="form-control" id="packageDetails" rows="2" placeholder="Brief tagline or description..." required></textarea>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label for="priceMonthly" class="form-label font-weight-bold">Monthly Rate (TK)</label>
                                        <input type="text" class="form-control" id="priceMonthly" placeholder="e.g. 2500" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="priceYearly" class="form-label font-weight-bold">Yearly Rate (TK)</label>
                                        <input type="text" class="form-control" id="priceYearly" placeholder="e.g. 25000" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="priceLifetime" class="form-label font-weight-bold">Lifetime Rate (TK)</label>
                                        <input type="text" class="form-control" id="priceLifetime" placeholder="e.g. 60000" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="packageStatus" checked>
                                        <label class="form-check-label font-weight-bold text-dark" for="packageStatus">Publish Subscription Plan</label>
                                    </div>
                                </div>

                                <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-tasks text-primary me-2"></i> Features Allocation Matrix</h6>
                                <div class="row" id="modalFeaturesContainer">
                                    <!-- Dynamic feature check list populated by JS -->
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer border-0 bg-light p-3">
                            <button type="button" class="btn btn-outline-secondary px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary px-4 rounded-pill" onclick="savePackage()">Save Subscription Plan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manage Features Pool Modal -->
            <div class="modal fade" id="manageFeaturesModal" tabindex="-1" aria-labelledby="manageFeaturesModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                        <div class="modal-header bg-secondary text-white border-0 py-3">
                            <h5 class="modal-title font-weight-bold" id="manageFeaturesModalLabel">Manage Subscription Features</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form id="newFeatureForm" onsubmit="addFeature(event)" class="mb-4">
                                <label for="newFeatureName" class="form-label font-weight-bold">Create New Feature Item</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="newFeatureName" placeholder="e.g. Premium Support, 24/7 Monitoring" required>
                                    <button class="btn btn-success px-3" type="submit"><i class="fas fa-plus"></i> Add Item</button>
                                </div>
                            </form>

                            <h6 class="border-bottom pb-2 mb-3">Existing Features Matrix</h6>
                            <ul class="list-group list-group-flush" id="featuresListContainer" style="max-height: 250px; overflow-y: auto;">
                                <!-- Populate via JS -->
                            </ul>
                        </div>
                        <div class="modal-footer border-0 bg-light p-3">
                            <button type="button" class="btn btn-secondary px-4 rounded-pill" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- ORIGINAL USERS WORKSPACE (Redesigned Workspace) -->
            @php
                $totalUsersCount = count($users);
                $adminUsersCount = $users->filter(function($u) {
                    return $u->getRoleNames()->contains('admin') || $u->getRoleNames()->contains('super-admin');
                })->count();
                $standardUsersCount = $totalUsersCount - $adminUsersCount;
            @endphp

            <!-- KPI Cards Row -->
            <div class="row mb-4 g-3">
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-1">
                        <div>
                            <div class="stat-card-title">Total Registered Users</div>
                            <div class="stat-card-value">{{ $totalUsersCount }}</div>
                        </div>
                        <i class="fas fa-users stat-card-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-2">
                        <div>
                            <div class="stat-card-title">System Administrators</div>
                            <div class="stat-card-value">{{ $adminUsersCount }}</div>
                        </div>
                        <i class="fas fa-user-shield stat-card-icon"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card-premium gradient-3">
                        <div>
                            <div class="stat-card-title">Customers & Members</div>
                            <div class="stat-card-value">{{ $standardUsersCount }}</div>
                        </div>
                        <i class="fas fa-user-tag stat-card-icon"></i>
                    </div>
                </div>
            </div>

            <!-- Table Workspace Wrapper -->
            <div class="workspace-card">
                <div class="premium-actions-bar">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="mb-0 font-weight-bold" style="color: var(--dark-slate);">Registered Accounts</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-danger rounded-pill px-4" id="deleteSelectedBtn" disabled onclick="deleteSelected()">
                            <i class="fas fa-trash me-2"></i> Delete Selected
                        </button>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-user-plus me-2"></i> Add Account
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table premium-table" id="users">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">
                                    <input type="checkbox" id="selectAll" class="form-check-input" onchange="toggleSelectAll()">
                                </th>
                                <th>UID</th>
                                <th>S/N</th>
                                <th>Full Name</th>
                                <th>Email Address</th>
                                <th>Assigned Role</th>
                                <th>Registration Date</th>
                                <th style="text-align: center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="checkbox" class="user-checkbox form-check-input" value="{{ $user->id }}" onchange="updateDeleteButton()">
                                    </td>
                                    <td><strong>#{{ $user->id }}</strong></td>
                                    <td></td>
                                    <td>{{ $user->name }}</td>
                                    <td><span class="text-muted">{{ $user->email }}</span></td>
                                    <td>
                                        @if($user->getRoleNames()->count())
                                            @foreach($user->getRoleNames() as $role)
                                                @if($role === 'admin' || $role === 'super-admin')
                                                    <span class="badge-premium badge-premium-admin"><i class="fas fa-shield-alt"></i> {{ $role }}</span>
                                                @else
                                                    <span class="badge-premium badge-premium-info"><i class="fas fa-user-circle"></i> {{ $role }}</span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="badge-premium badge-premium-secondary">customer</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.users.show', $user->id) }}" 
                                               class="btn-action-circle btn-action-view" title="View Account Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}" 
                                               class="btn-action-circle btn-action-edit" title="Edit Details">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', ['id' => $user->id]) }}" 
                                                  method="POST" class="d-inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-circle btn-action-delete" 
                                                        title="Remove Account" onclick="return confirm('Are you sure you want to delete this user?')">
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
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>

    @if(request()->get('view') === 'packages')
        <script>
            // MOCKED PERSISTENT PACKAGES MANAGEMENT SYSTEM
            const DEFAULT_FEATURES = [
                "1 Store Dashboard",
                "Unlimited Products",
                "Advanced Sales Reports",
                "Custom Domain Settings",
                "24/7 Priority Support",
                "Fraud Checker Integration",
                "WooCommerce Migration",
                "Custom Payment Gateways"
            ];

            const DEFAULT_PACKAGES = [
                {
                    id: "starter_plan",
                    name: "Starter Plan",
                    theme: "starter",
                    details: "Ideal for fresh startups and hobbyists looking to build their first online storefront.",
                    priceMonthly: "1200",
                    priceYearly: "12000",
                    priceLifetime: "30000",
                    status: true,
                    features: ["1 Store Dashboard", "Unlimited Products"]
                },
                {
                    id: "pro_plan",
                    name: "Professional Plan",
                    theme: "pro",
                    details: "Perfect for growing merchants and professional retailers needing premium tools.",
                    priceMonthly: "3500",
                    priceYearly: "35000",
                    priceLifetime: "80000",
                    status: true,
                    features: ["1 Store Dashboard", "Unlimited Products", "Advanced Sales Reports", "Fraud Checker Integration", "24/7 Priority Support"]
                },
                {
                    id: "enterprise_plan",
                    name: "Enterprise Ultimate",
                    theme: "enterprise",
                    details: "Tailored specifically for large-scale operations requiring absolute maximum horsepower.",
                    priceMonthly: "8500",
                    priceYearly: "85000",
                    priceLifetime: "200000",
                    status: true,
                    features: ["1 Store Dashboard", "Unlimited Products", "Advanced Sales Reports", "Custom Domain Settings", "24/7 Priority Support", "Fraud Checker Integration", "WooCommerce Migration", "Custom Payment Gateways"]
                }
            ];

            // LocalStorage Keys
            const FEATURES_KEY = "admin_packages_features_pool";
            const PACKAGES_KEY = "admin_packages_list";

            // State variables
            let featuresPool = [];
            let packagesList = [];

            // Initialize Data
            function initData() {
                const storedFeatures = localStorage.getItem(FEATURES_KEY);
                const storedPackages = localStorage.getItem(PACKAGES_KEY);

                if (!storedFeatures) {
                    localStorage.setItem(FEATURES_KEY, JSON.stringify(DEFAULT_FEATURES));
                    featuresPool = DEFAULT_FEATURES;
                } else {
                    featuresPool = JSON.parse(storedFeatures);
                }

                if (!storedPackages) {
                    localStorage.setItem(PACKAGES_KEY, JSON.stringify(DEFAULT_PACKAGES));
                    packagesList = DEFAULT_PACKAGES;
                } else {
                    packagesList = JSON.parse(storedPackages);
                }
            }

            // Render Dashboard Stats and Grid
            function renderWorkspace() {
                initData();
                
                // Update Counts
                document.getElementById('totalPackagesCount').textContent = packagesList.length;
                document.getElementById('activePackagesCount').textContent = packagesList.filter(p => p.status).length;
                document.getElementById('totalFeaturesCount').textContent = featuresPool.length;

                // Render Grid
                const grid = document.getElementById('packagesGrid');
                grid.innerHTML = '';

                if (packagesList.length === 0) {
                    grid.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486831.png" alt="Empty" width="100" style="opacity: 0.3;">
                            <h5 class="mt-3 text-muted">No Packages Found</h5>
                            <p class="text-muted">Get started by creating your first SaaS tier!</p>
                            <button class="btn btn-primary rounded-pill px-4 mt-2" onclick="openCreateModal()">Create Package</button>
                        </div>
                    `;
                    return;
                }

                packagesList.forEach(pkg => {
                    const cardCol = document.createElement('div');
                    cardCol.className = 'col-md-4 mb-4';

                    let featuresHTML = '';
                    featuresPool.forEach(feat => {
                        const isIncluded = pkg.features.includes(feat);
                        featuresHTML += `
                            <div class="feature-item ${isIncluded ? 'feature-included' : 'feature-excluded'}">
                                <i class="${isIncluded ? 'fas fa-check-circle feature-icon-included' : 'fas fa-times-circle feature-icon-excluded'}"></i>
                                <span>${feat}</span>
                            </div>
                        `;
                    });

                    cardCol.innerHTML = `
                        <div class="premium-card">
                            <div class="gradient-header ${pkg.theme || 'default'}">
                                <span class="badge ${pkg.status ? 'bg-success' : 'bg-secondary'} mb-2">${pkg.status ? 'Active' : 'Inactive'}</span>
                                <h4 class="mb-1 font-weight-bold" style="color: #fff;">${pkg.name}</h4>
                                <p class="small mb-0 opacity-80" style="color: rgba(255,255,255,0.85); min-height: 40px;">${pkg.details}</p>
                                <div class="package-price-badge">Monthly: TK ${pkg.priceMonthly}</div>
                            </div>
                            <div class="card-body p-4 pt-4 flex-grow-1">
                                <div class="row text-center mb-3 border-bottom pb-3">
                                    <div class="col-6 border-end">
                                        <span class="text-muted d-block small">Yearly Plan</span>
                                        <strong class="text-dark">TK ${pkg.priceYearly}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block small">Lifetime Plan</span>
                                        <strong class="text-dark">TK ${pkg.priceLifetime}</strong>
                                    </div>
                                </div>
                                <div class="features-list-wrapper mb-4">
                                    ${featuresHTML}
                                </div>
                            </div>
                            <div class="card-footer bg-light border-0 p-3 d-flex justify-content-between gap-2">
                                <button class="btn btn-outline-info rounded-pill px-3 flex-grow-1" onclick="openEditModal('${pkg.id}')">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </button>
                                <button class="btn btn-outline-danger rounded-pill px-3" onclick="deletePackage('${pkg.id}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    grid.appendChild(cardCol);
                });

                // Update the feature pool checklist inside Manage Features modal
                renderFeaturesPoolList();
            }

            // Render list inside features pool modal
            function renderFeaturesPoolList() {
                const container = document.getElementById('featuresListContainer');
                container.innerHTML = '';

                featuresPool.forEach((feat, idx) => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center px-0 py-2';
                    li.innerHTML = `
                        <span>${feat}</span>
                        <button class="btn btn-sm btn-link text-danger" onclick="deleteFeature(${idx})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    `;
                    container.appendChild(li);
                });
            }

            // Render features checklist in Package Form Modal
            function renderModalFeaturesChecklist(checkedFeatures = []) {
                const container = document.getElementById('modalFeaturesContainer');
                container.innerHTML = '';

                if (featuresPool.length === 0) {
                    container.innerHTML = `<p class="text-muted col-12">No features created in the features pool yet.</p>`;
                    return;
                }

                featuresPool.forEach((feat, idx) => {
                    const col = document.createElement('div');
                    col.className = 'col-md-6 mb-2';
                    const isChecked = checkedFeatures.includes(feat);
                    col.innerHTML = `
                        <div class="form-check">
                            <input class="form-check-input feature-checkbox" type="checkbox" value="${feat}" id="featCheck_${idx}" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label text-dark" for="featCheck_${idx}">
                                ${feat}
                            </label>
                        </div>
                    `;
                    container.appendChild(col);
                });
            }

            // Add Feature to pool
            function addFeature(event) {
                event.preventDefault();
                const input = document.getElementById('newFeatureName');
                const name = input.value.trim();
                if (!name) return;

                if (featuresPool.includes(name)) {
                    Swal.fire('Error', 'Feature already exists in the pool!', 'error');
                    return;
                }

                featuresPool.push(name);
                localStorage.setItem(FEATURES_KEY, JSON.stringify(featuresPool));
                input.value = '';
                
                // Re-render
                renderWorkspace();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Feature added to pool',
                    showConfirmButton: false,
                    timer: 1500
                });
            }

            // Delete Feature from pool
            function deleteFeature(idx) {
                const featToDelete = featuresPool[idx];
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Removing "${featToDelete}" will also exclude it from all current packages.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        featuresPool.splice(idx, 1);
                        localStorage.setItem(FEATURES_KEY, JSON.stringify(featuresPool));

                        // Clean up package assignments
                        packagesList.forEach(pkg => {
                            pkg.features = pkg.features.filter(f => f !== featToDelete);
                        });
                        localStorage.setItem(PACKAGES_KEY, JSON.stringify(packagesList));

                        renderWorkspace();
                        Swal.fire('Deleted!', 'Feature removed from pool.', 'success');
                    }
                });
            }

            // Open Create Modal
            function openCreateModal() {
                document.getElementById('packageModalLabel').textContent = 'Create New Package';
                document.getElementById('packageId').value = '';
                document.getElementById('packageForm').reset();
                document.getElementById('packageStatus').checked = true;
                
                renderModalFeaturesChecklist([]);
                
                const modal = new bootstrap.Modal(document.getElementById('packageModal'));
                modal.show();
            }

            // Open Edit Modal
            function openEditModal(pkgId) {
                const pkg = packagesList.find(p => p.id === pkgId);
                if (!pkg) return;

                document.getElementById('packageModalLabel').textContent = 'Edit Package';
                document.getElementById('packageId').value = pkg.id;
                document.getElementById('packageName').value = pkg.name;
                document.getElementById('packageTheme').value = pkg.theme || 'default';
                document.getElementById('packageDetails').value = pkg.details;
                document.getElementById('priceMonthly').value = pkg.priceMonthly;
                document.getElementById('priceYearly').value = pkg.priceYearly;
                document.getElementById('priceLifetime').value = pkg.priceLifetime;
                document.getElementById('packageStatus').checked = pkg.status;

                renderModalFeaturesChecklist(pkg.features);

                const modal = new bootstrap.Modal(document.getElementById('packageModal'));
                modal.show();
            }

            // Save Package (Create or Update)
            function savePackage() {
                const idInput = document.getElementById('packageId').value;
                const name = document.getElementById('packageName').value.trim();
                const theme = document.getElementById('packageTheme').value;
                const details = document.getElementById('packageDetails').value.trim();
                const priceMonthly = document.getElementById('priceMonthly').value.trim();
                const priceYearly = document.getElementById('priceYearly').value.trim();
                const priceLifetime = document.getElementById('priceLifetime').value.trim();
                const status = document.getElementById('packageStatus').checked;

                if (!name || !details || !priceMonthly || !priceYearly || !priceLifetime) {
                    Swal.fire('Validation Error', 'Please fill in all required fields.', 'error');
                    return;
                }

                // Gather checked features
                const selectedFeatures = [];
                document.querySelectorAll('.feature-checkbox:checked').forEach(cb => {
                    selectedFeatures.push(cb.value);
                });

                if (idInput) {
                    // Update
                    const pkgIdx = packagesList.findIndex(p => p.id === idInput);
                    if (pkgIdx !== -1) {
                        packagesList[pkgIdx] = {
                            id: idInput,
                            name,
                            theme,
                            details,
                            priceMonthly,
                            priceYearly,
                            priceLifetime,
                            status,
                            features: selectedFeatures
                        };
                    }
                } else {
                    // Create
                    const newId = 'pkg_' + Date.now();
                    packagesList.push({
                        id: newId,
                        name,
                        theme,
                        details,
                        priceMonthly,
                        priceYearly,
                        priceLifetime,
                        status,
                        features: selectedFeatures
                    });
                }

                localStorage.setItem(PACKAGES_KEY, JSON.stringify(packagesList));
                
                // Hide modal
                const modalEl = document.getElementById('packageModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                renderWorkspace();

                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully',
                    text: 'The SaaS tier was recorded successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // Delete Package
            function deletePackage(pkgId) {
                const pkg = packagesList.find(p => p.id === pkgId);
                if (!pkg) return;

                Swal.fire({
                    title: 'Delete Package?',
                    text: `Are you absolutely sure you want to remove "${pkg.name}"? This cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Delete Package'
                }).then((result) => {
                    if (result.isConfirmed) {
                        packagesList = packagesList.filter(p => p.id !== pkgId);
                        localStorage.setItem(PACKAGES_KEY, JSON.stringify(packagesList));
                        renderWorkspace();
                        Swal.fire('Deleted!', 'The package has been removed.', 'success');
                    }
                });
            }

            // Initial render on DOM load
            document.addEventListener('DOMContentLoaded', function() {
                renderWorkspace();
            });
        </script>
        <script>
            window.deleteMockAdmin = function(id) {
                if (confirm('Are you sure you want to delete this mock registered admin?')) {
                    let admins = JSON.parse(localStorage.getItem('registered_admins') || '[]');
                    admins = admins.filter(a => a.id !== id);
                    localStorage.setItem('registered_admins', JSON.stringify(admins));
                    location.reload();
                }
            };

            $(document).ready(function() {
                // Dynamically inject mock registered admins
                const mockAdmins = JSON.parse(localStorage.getItem('registered_admins') || '[]');
                const tbody = document.querySelector('#users tbody');
                if (tbody && mockAdmins.length > 0) {
                    mockAdmins.forEach(adm => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td style="text-align: center;">
                                <input type="checkbox" class="user-checkbox form-check-input" value="${adm.id}" onchange="updateDeleteButton()">
                            </td>
                            <td><strong>#${adm.id}</strong></td>
                            <td></td>
                            <td>${adm.name}</td>
                            <td><span class="text-muted">${adm.email}</span></td>
                            <td>
                                <span class="badge-premium badge-premium-admin mb-1"><i class="fas fa-shield-alt"></i> admin</span>
                                <span class="badge-premium badge-premium-success"><i class="fas fa-gem"></i> ${adm.packageName} (${adm.billingCycle})</span>
                            </td>
                            <td>${adm.created_at}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn-action-circle btn-action-delete" title="Delete Mock Admin" onclick="deleteMockAdmin('${adm.id}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        `;
                        tbody.insertBefore(tr, tbody.firstChild);
                    });
                }

                $('#users').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy','pdf', 'csv', 'excel', 'print'
                    ],
                    order: [[1, 'asc']], // Sort by User ID ascending
                    pageLength: 25,
                    columnDefs: [
                        {
                            targets: 2, // Serial column (index 2)
                            searchable: false,
                            orderable: false,
                            render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            targets: 0, // Checkbox column
                            searchable: false,
                            orderable: false
                        }
                    ]
                });
            });

            function toggleSelectAll() {
                const selectAll = document.getElementById('selectAll');
                const checkboxes = document.querySelectorAll('.user-checkbox');
                
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
                
                updateDeleteButton();
            }

            function updateDeleteButton() {
                const checkboxes = document.querySelectorAll('.user-checkbox:checked');
                const deleteBtn = document.getElementById('deleteSelectedBtn');
                
                if (checkboxes.length > 0) {
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = `Delete Selected (${checkboxes.length})`;
                } else {
                    deleteBtn.disabled = true;
                    deleteBtn.textContent = 'Delete Selected';
                }
            }

            function deleteSelected() {
                const checkboxes = document.querySelectorAll('.user-checkbox:checked');
                const userIds = Array.from(checkboxes).map(cb => cb.value);
                
                if (userIds.length === 0) {
                    alert('Please select users to delete.');
                    return;
                }
                
                if (confirm(`Are you sure you want to delete ${userIds.length} user(s)?`)) {
                    // Create a form to submit multiple user IDs
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("admin.users.bulk-delete") }}';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add method override
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    // Add user IDs
                    userIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'user_ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        </script>
    @endif
@endsection
