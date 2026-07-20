@extends('layouts.master')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* Root & Typography Variables override */
        .menu-dashboard-wrapper {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #1e293b;
            padding: 1.5rem 0;
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Glassmorphism Cards */
        .premium-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .premium-card:hover {
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.1), 0 1px 5px rgba(99, 102, 241, 0.03);
            border-color: rgba(99, 102, 241, 0.25);
        }

        /* Gradient Header & Buttons */
        .gradient-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #c084fc 100%);
            padding: 2.5rem;
            border-radius: 24px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 2.5rem;
            box-shadow: 0 20px 40px -15px rgba(99, 102, 241, 0.3);
        }

        .gradient-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
        }

        .gradient-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: 10%;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 75%);
        }

        .gradient-header-title {
            font-size: 2.25rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .gradient-header-subtitle {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            margin-top: 0.5rem;
            margin-bottom: 0;
        }

        /* Stats Cards */
        .stat-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.08);
            border-color: rgba(99, 102, 241, 0.2);
        }

        .stat-icon-wrapper {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon-primary {
            background: rgba(99, 102, 241, 0.08);
            color: #4f46e5;
        }

        .stat-icon-success {
            background: rgba(16, 185, 129, 0.08);
            color: #10b981;
        }

        .stat-icon-danger {
            background: rgba(239, 68, 68, 0.08);
            color: #ef4444;
        }

        .stat-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin: 0.25rem 0 0 0;
            line-height: 1.2;
        }

        /* Action Buttons */
        .btn-premium-action {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
            border: none;
            background: #ffffff;
            color: #4f46e5;
        }

        .btn-premium-action:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.3);
            color: #3730a3;
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white !important;
            box-shadow: 0 8px 20px -6px rgba(99, 102, 241, 0.5);
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
            box-shadow: 0 10px 24px -6px rgba(99, 102, 241, 0.6);
        }

        /* Modernized Table styling */
        .premium-table-container {
            overflow-x: auto;
        }

        .premium-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .premium-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            padding: 1.125rem 1.5rem;
            border-bottom: 2px solid #e2e8f0;
            border-top: none;
        }

        .premium-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            color: #334155;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .premium-table tr:last-child td {
            border-bottom: none;
        }

        .premium-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .premium-table tbody tr:hover {
            background-color: rgba(248, 250, 252, 0.8);
        }

        .menu-id-badge {
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            background: #f1f5f9;
            padding: 0.25rem 0.6rem;
            border-radius: 8px;
        }

        .menu-name-wrapper {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .menu-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4f46e5;
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Status Pill Component */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .status-pill-active {
            background-color: rgba(16, 185, 129, 0.08);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.15);
        }

        .status-pill-inactive {
            background-color: rgba(239, 68, 68, 0.08);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.15);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-dot-active {
            background-color: #10b981;
            box-shadow: 0 0 8px #10b981;
        }

        .status-dot-inactive {
            background-color: #ef4444;
            box-shadow: 0 0 8px #ef4444;
        }

        /* Location Badge */
        .location-badge {
            background-color: rgba(124, 58, 237, 0.06);
            color: #6d28d9;
            border: 1px solid rgba(124, 58, 237, 0.12);
            padding: 0.3rem 0.75rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Table Action Buttons */
        .btn-table-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            transition: all 0.2s ease;
            cursor: pointer;
            margin-right: 0.25rem;
        }

        .btn-table-action:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .btn-table-action-edit:hover {
            color: #4f46e5;
            background: rgba(99, 102, 241, 0.06);
            border-color: rgba(99, 102, 241, 0.2);
        }

        .btn-table-action-delete:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.06);
            border-color: rgba(239, 68, 68, 0.2);
        }

        /* Alert styling */
        .alert-toast {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid menu-dashboard-wrapper">
        <!-- Premium Gradient Header -->
        <div class="gradient-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-4">
                <div>
                    <h1 class="gradient-header-title">Menu Customizer</h1>
                    <p class="gradient-header-subtitle">Structure, configure, and customize website navigation elements seamlessly.</p>
                </div>
                <div>
                    <a href="{{ route('admin.menus.create') }}" class="btn-premium-action btn-premium-primary">
                        <i class="fas fa-plus"></i> Create New Menu
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Insights Counter Grid -->
        <div class="row mb-4">
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-primary">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <div>
                        <p class="stat-label">Total Menus</p>
                        <h3 class="stat-value">{{ $menus->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <p class="stat-label">Active Menus</p>
                        <h3 class="stat-value">{{ $menus->where('status', true)->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="stat-card">
                    <div class="stat-icon-wrapper stat-icon-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <p class="stat-label">Inactive Menus</p>
                        <h3 class="stat-value">{{ $menus->where('status', false)->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Session Message Alerts -->
        @if (session('success'))
            <div class="alert alert-toast alert-success d-flex align-items-center gap-3 fade show" role="alert">
                <i class="fas fa-check-circle text-success fs-4"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Menus Listing Card -->
        <div class="premium-card">
            <div class="premium-table-container">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Menu Identification</th>
                            <th>Display Location</th>
                            <th>Status Badge</th>
                            <th style="width: 150px; text-align: right; padding-right: 2rem;">Action Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menus as $menu)
                            <tr>
                                <td>
                                    <span class="menu-id-badge">#{{ $menu->id }}</span>
                                </td>
                                <td>
                                    <div class="menu-name-wrapper">
                                        <div class="menu-avatar">
                                            {{ strtoupper(substr($menu->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span style="font-weight: 700; color: #0f172a;">{{ $menu->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($menu->location)
                                        <span class="location-badge">
                                            <i class="fas fa-map-marker-alt"></i> {{ $menu->location }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Not assigned</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($menu->status)
                                        <span class="status-pill status-pill-active">
                                            <span class="status-dot status-dot-active"></span> Active
                                        </span>
                                    @else
                                        <span class="status-pill status-pill-inactive">
                                            <span class="status-dot status-dot-inactive"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: right; padding-right: 2rem;">
                                    <button type="button" class="btn-table-action btn-table-action-edit edit-menu-btn"
                                        data-url="/admin/menus/{{ $menu->id }}/edit"
                                        title="Edit structure">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-table-action btn-table-action-delete"
                                            onclick="return confirm('Are you sure you want to delete this menu?')"
                                            title="Delete menu">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted mb-3">
                                        <i class="fas fa-folder-open fs-1 text-slate-300"></i>
                                    </div>
                                    <h5 class="fw-bold">No menus created yet</h5>
                                    <p class="text-muted small">Start organizing your website navigation by creating your first menu.</p>
                                    <a href="{{ route('admin.menus.create') }}" class="btn-premium-action btn-premium-primary mt-2">
                                        Create New Menu
                                    </a>
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
<script>
    $(document).ready(function() {
        // Prevent any drag events on the edit buttons
        $('.edit-menu-btn').on('dragstart', function(e) {
            e.preventDefault();
            return false;
        });
        
        // Handle click with stopPropagation to prevent event bubbling
        $('.edit-menu-btn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var url = $(this).data('url');
            // Use setTimeout to ensure this happens after any other event handlers
            setTimeout(function() {
                window.location.href = url;
            }, 10);
            return false;
        });
    });
</script>
@endsection
