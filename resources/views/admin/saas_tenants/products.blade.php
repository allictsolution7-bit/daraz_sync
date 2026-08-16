@extends('layouts.master')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-slate-800 font-bold" style="font-weight: 700; color: #1e293b;">SaaS Wholesale Products</h1>
            <p class="text-muted mb-0 small">Overview of all wholeselling products from every tenant registered on the platform.</p>
        </div>
    </div>

    <!-- Error Alerts (Database connections failed) -->
    @if(!empty($errors))
        @foreach($errors as $error)
            <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3 d-flex align-items-center" role="alert" style="background-color: #fffbeb; color: #92400e;">
                <i class="fas fa-exclamation-triangle me-2 fs-5"></i>
                <div class="small">{{ $error }}</div>
            </div>
        @endforeach
    @endif

    <!-- Filters Panel -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.saas-tenants.wholesale-products') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <label class="form-label small text-muted font-semibold mb-1" style="font-weight: 600;">Filter by Tenant Subdomain</label>
                    <select name="tenant_id" class="form-select rounded-3" onchange="this.form.submit()">
                        <option value="">All Tenants (Combined View)</option>
                        @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}" {{ $selectedTenantId == $tenant->id ? 'selected' : '' }}>
                                {{ $tenant->name }} ({{ $tenant->subdomain }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end h-100 mt-md-4">
                    <a href="{{ route('admin.saas-tenants.wholesale-products') }}" class="btn btn-light rounded-3 w-100" style="background-color: #f1f5f9; color: #475569; border: none;">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-semibold" style="color: #334155; font-weight: 600;">Wholeseller Products Listing</h5>
            <span class="badge bg-indigo-light text-primary px-3 py-2 rounded-3" style="background-color: rgba(79, 70, 229, 0.08); color: #4f46e5; font-weight: 600;">
                Total Found: {{ $paginatedProducts->total() }}
            </span>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-uppercase fs-7 text-muted" style="background-color: #f8fafc;">
                    <tr>
                        <th class="ps-4 py-3" style="width: 70px;">Image</th>
                        <th class="py-3" style="min-width: 180px; max-width: 280px;">Product Name</th>
                        <th class="py-3" style="white-space: nowrap;">Tenant Subdomain</th>
                        <th class="py-3" style="white-space: nowrap;">Wholeseller / Vendor</th>
                        <th class="py-3" style="white-space: nowrap;">Retail Price</th>
                        <th class="py-3" style="white-space: nowrap;">Wholesale Price</th>
                        <th class="py-3" style="white-space: nowrap;">Stock</th>
                        <th class="py-3" style="white-space: nowrap;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paginatedProducts as $product)
                        <tr>
                            <td class="ps-4" style="width: 70px;">
                                @if(!empty($product['thumb_image']))
                                    <div class="position-relative" style="width: 48px; height: 48px;">
                                        <img src="{{ $product['thumb_image'] }}" 
                                             alt="{{ $product['title'] }}" 
                                             class="rounded-3 border shadow-sm" 
                                             style="width: 48px; height: 48px; object-fit: cover;" 
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="rounded-3 border bg-light align-items-center justify-content-center" style="width: 48px; height: 48px; display: none;">
                                            <i class="fas fa-image text-muted" style="font-size: 1.1rem;"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="rounded-3 border bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                        <i class="fas fa-image text-muted" style="font-size: 1.1rem;"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="max-width: 280px;">
                                <h6 class="mb-0 text-slate-800 font-semibold text-truncate" style="font-weight: 600; color: #1e293b; max-width: 260px;" title="{{ $product['title'] }}">{{ $product['title'] }}</h6>
                                <span class="text-muted small">ID: #{{ $product['id'] }}</span>
                            </td>
                            <td style="white-space: nowrap;">
                                <span class="badge rounded-pill px-2.5 py-1.5" style="background-color: #e0e7ff; color: #3730a3; font-weight: 600; font-size: 11px;">
                                    <i class="fas fa-globe me-1"></i> {{ $product['tenant_subdomain'] }}
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                <div>
                                    <span class="font-medium text-slate-700 d-block" style="font-weight: 500; color: #334155;">{{ $product['vendor_name'] }}</span>
                                    <span class="text-muted small fs-7">{{ $product['vendor_email'] }}</span>
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                @if($product['offer'] > 0)
                                    <span class="text-slate-800" style="font-weight: 600;">৳{{ number_format($product['offer'], 2) }}</span>
                                    <del class="text-muted small d-block" style="font-size: 10px;">৳{{ number_format($product['price'], 2) }}</del>
                                @else
                                    <span class="text-slate-800" style="font-weight: 600;">৳{{ number_format($product['price'], 2) }}</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                <span class="text-success font-bold" style="font-weight: 700;">
                                    ৳{{ number_format($product['wholesale_price'] ?: 0, 2) }}
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                @if($product['quantity'] > 0)
                                    <span class="text-slate-700" style="font-weight: 500;">{{ $product['quantity'] }} pcs</span>
                                @else
                                    <span class="text-danger font-semibold" style="font-weight: 600;">Out of Stock</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                @if($product['status'] == 1 || $product['status'] == 'active' || $product['status'] === true)
                                    <span class="badge px-2.5 py-1.5 rounded-pill" style="background-color: #ecfdf5; color: #047857; font-weight: 600; font-size: 11px;">Published</span>
                                @else
                                    <span class="badge px-2.5 py-1.5 rounded-pill" style="background-color: #f1f5f9; color: #64748b; font-weight: 600; font-size: 11px;">Draft</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-boxes text-muted mb-3" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <h5 class="text-slate-600 mb-1" style="font-weight: 600;">No Wholesale Products Found</h5>
                                    <p class="text-muted small">We couldn't locate any products marked for wholeselling or created by wholesellers across the selected tenants.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paginatedProducts->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                {{ $paginatedProducts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
