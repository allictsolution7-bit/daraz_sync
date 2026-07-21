@extends('layouts.master')

@section('title', 'Manage Partners')

@section('styles')
<style>
    :root {
        --vp-primary: #4f46e5;
        --vp-primary-hover: #4338ca;
        --vp-secondary: #06b6d4;
        --vp-green: #10b981;
        --vp-amber: #f59e0b;
        --vp-danger: #ef4444;
        --vp-surface: #ffffff;
        --vp-border: #e2e8f0;
        --vp-radius: 14px;
    }

    .vp-page-wrapper {
        background: #f1f5f9;
        margin: -15px -15px 0 -15px;
        padding: 24px;
        min-height: calc(100vh - 60px);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* Hero Card */
    .vp-hero-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
        color: white;
        border-radius: var(--vp-radius);
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.15);
        position: relative;
        overflow: hidden;
    }

    .vp-hero-card::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(6,182,212,0.25) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .vp-title-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #38bdf8;
    }

    .vp-chip {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        padding: 6px 14px;
        font-weight: 700;
        font-size: 13px;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .vp-chip .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .btn-create-vp {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white !important;
        border: none;
        padding: 12px 22px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(16,185,129,0.35);
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .btn-create-vp:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16,185,129,0.45);
    }

    /* Filter Toolbar Card */
    .vp-filter-card {
        background: white;
        border: 1px solid var(--vp-border);
        border-radius: var(--vp-radius);
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .vp-filter-card .form-control, 
    .vp-filter-card .form-select {
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 500;
        background-color: #f8fafc;
    }

    .vp-filter-card .form-control:focus, 
    .vp-filter-card .form-select:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }

    /* Table Container Card */
    .vp-table-card {
        background: white;
        border-radius: var(--vp-radius);
        border: 1px solid var(--vp-border);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .vp-table th {
        background: #f8fafc;
        color: #334155;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 18px;
        border-bottom: 1px solid #e2e8f0;
    }

    .vp-table td {
        padding: 16px 18px;
        vertical-align: middle;
        font-size: 13px;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }

    .vp-table tr:hover td {
        background: #f8fafc;
    }

    .badge-soft-success {
        background: #dcfce7;
        color: #15803d;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
    }

    .badge-soft-warning {
        background: #fef3c7;
        color: #b45309;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
    }

    .badge-soft-danger {
        background: #fee2e2;
        color: #b91c1c;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
    }

    .vp-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
    }

    .action-icon-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #475467;
        transition: all 0.2s;
    }

    .action-icon-btn:hover {
        background: #f1f5f9;
        transform: translateY(-1px);
    }

    .action-icon-btn.btn-view:hover { color: #0284c7; border-color: #38bdf8; }
    .action-icon-btn.btn-edit:hover { color: #d97706; border-color: #f59e0b; }
    .action-icon-btn.btn-verify:hover { color: #16a34a; border-color: #22c55e; }
    .action-icon-btn.btn-delete:hover { color: #dc2626; border-color: #ef4444; }
</style>
@endsection

@section('content')
<div class="vp-page-wrapper">
    <!-- Hero Banner Card -->
    <div class="vp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-4">
        <div>
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="vp-title-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div>
                    <h3 class="mb-0 text-white font-weight-bold">Partners & Vendors Directory</h3>
                    <p class="mb-0 text-white-50 small">Manage marketplace vendor accounts, verification status & products</p>
                </div>
            </div>
            
            <div class="d-flex flex-wrap gap-2">
                <span class="vp-chip">
                    <span class="dot" style="background: #38bdf8;"></span>
                    Total Partners: {{ $vendors->total() }}
                </span>
            </div>
        </div>

        <div>
            <a href="{{ route('admin.vendors.create') }}" class="btn-create-vp">
                <i class="fas fa-plus"></i> Add New Partner
            </a>
        </div>
    </div>

    <!-- Filters Toolbar Card -->
    <div class="vp-filter-card">
        <form action="{{ route('admin.vendors.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="position-relative">
                    <input type="text" 
                           name="search" 
                           class="form-control ps-4" 
                           placeholder="Search partner by name, email, or business..."
                           value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                    <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary rounded-3 w-100 font-weight-bold" style="padding: 9px;">
                    <i class="fas fa-search me-1"></i> Filter
                </button>
            </div>
            <div class="col-md-2 text-end">
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary rounded-3 w-100 font-weight-bold" style="padding: 9px;">
                    <i class="fas fa-redo me-1"></i> Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Table Container Card -->
    <div class="vp-table-card">
        @if($vendors->isEmpty())
            <div class="text-center py-5">
                <div class="vp-avatar mx-auto mb-3" style="width:64px; height:64px; font-size:24px;">
                    <i class="fas fa-handshake"></i>
                </div>
                <h5 class="font-weight-bold text-dark">No Partners Found</h5>
                <p class="text-muted small">Try broadening your filter criteria or register a new partner.</p>
                <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary rounded-3 btn-sm mt-2 px-4">
                    <i class="fas fa-plus me-1"></i> Add Partner
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table vp-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Partner Profile</th>
                            <th>Business Details</th>
                            <th>Contact Email / Phone</th>
                            <th>Catalog Count</th>
                            <th>Status & Verification</th>
                            <th>Joined Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $vendor)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="vp-avatar">
                                        {{ strtoupper(substr($vendor->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold text-dark">{{ $vendor->name }}</h6>
                                        <small class="text-muted">{{ $vendor->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($vendor->vendorSettings)
                                    <span class="font-weight-bold text-dark">{{ $vendor->vendorSettings->business_name }}</span><br>
                                    <small class="text-muted"><i class="fas fa-building me-1"></i>Business</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($vendor->vendorSettings)
                                    <span class="text-dark">{{ $vendor->vendorSettings->business_email }}</span><br>
                                    <small class="text-muted">{{ $vendor->vendorSettings->business_phone }}</small>
                                @else
                                    <span class="text-muted">{{ $vendor->email }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-weight-bold text-dark">{{ $vendor->products->count() }} items</span><br>
                                <small class="text-success font-weight-bold">{{ $vendor->products->where('approval_status', 'approved')->count() }} approved</small>
                            </td>
                            <td>
                                @if($vendor->vendorSettings)
                                    @if($vendor->vendorSettings->is_verified)
                                        <span class="badge-soft-success">
                                            <i class="fas fa-check-circle me-1"></i> Verified
                                        </span>
                                    @else
                                        <span class="badge-soft-warning">
                                            <i class="fas fa-clock me-1"></i> Pending
                                        </span>
                                    @endif
                                    
                                    <div class="mt-1">
                                        @if($vendor->vendorSettings->is_active)
                                            <span class="badge bg-success rounded-pill px-2" style="font-size:10px;">Active</span>
                                        @else
                                            <span class="badge bg-danger rounded-pill px-2" style="font-size:10px;">Inactive</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge-soft-warning">No Settings</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-dark font-weight-bold">{{ $vendor->created_at->format('d M Y') }}</small>
                            </td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.vendors.show', $vendor) }}" 
                                       class="action-icon-btn btn-view"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.vendors.edit', $vendor) }}" 
                                       class="action-icon-btn btn-edit"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($vendor->vendorSettings && !$vendor->vendorSettings->is_verified)
                                        <form action="{{ route('admin.vendors.verify', $vendor) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="action-icon-btn btn-verify"
                                                    title="Verify Vendor">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.vendors.destroy', $vendor) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="action-icon-btn btn-delete"
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

            @if($vendors->hasPages())
            <div class="p-3 border-top d-flex justify-content-center">
                {{ $vendors->appends(request()->query())->links() }}
            </div>
            @endif
        @endif
    </div>
</div>
@endsection

