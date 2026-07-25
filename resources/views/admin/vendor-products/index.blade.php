@extends('layouts.master')

@section('title', 'Partner Products & Items')

@section('styles')
<style>
    .vp-page-wrapper {
        background: #f1f5f9;
        min-height: calc(100vh - 60px);
        margin: -15px -15px 0 -15px;
        padding: 24px;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }
    .vp-hero-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
        color: #ffffff;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.15);
        margin-bottom: 24px;
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
    .vp-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .vp-card .card-header {
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px;
    }
    .vp-card .card-header h5 {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
        font-size: 15px;
    }
    .vp-page-wrapper .form-control, .vp-page-wrapper .form-select {
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 500;
        background-color: #f8fafc;
    }
    .vp-page-wrapper .form-control:focus, .vp-page-wrapper .form-select:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }
    .vp-table th {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 800;
        color: #64748b;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 16px;
    }
    .vp-table td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
    }
    .badge-soft-success {
        background: #dcfce7;
        color: #15803d;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    .badge-soft-warning {
        background: #fef3c7;
        color: #b45309;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    .badge-soft-danger {
        background: #fee2e2;
        color: #b91c1c;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
    }
    .btn-action-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-action-icon:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="vp-page-wrapper">
    <!-- Header Banner -->
    <div class="vp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="vp-title-icon">
                <i class="fas fa-boxes-stacked"></i>
            </div>
            <div>
                <h3 class="mb-0 text-white font-weight-bold">Partner Catalog & Items</h3>
                <p class="mb-0 text-white-50 small">Review, verify, and approve partner product listings & commissions</p>
            </div>
        </div>
        <div>
            <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary rounded-3 px-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Partners
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="vp-card">
        <div class="card-body p-4">
            <form action="{{ route('admin.vendor-products.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">Search Catalog</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 rounded-start-3"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Product name or SKU..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">Approval Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="text-xs font-weight-bold uppercase text-muted mb-1 d-block">Filter Partner</label>
                    <select name="vendor_id" class="form-select">
                        <option value="">All Partners</option>
                        @foreach(\App\Models\User::role('vendor')->get() as $v)
                            <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 font-weight-bold">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert for pending items -->
    @if($products->where('approval_status', 'pending')->count() > 0)
        <div class="alert alert-warning border-0 rounded-3 shadow-sm d-flex align-items-center gap-2 mb-4" style="background:#fffbe0; color:#b45309;">
            <i class="fas fa-exclamation-triangle fs-5 text-warning me-1"></i>
            <div>
                <strong>{{ $products->where('approval_status', 'pending')->count() }} partner item(s)</strong> require review and approval.
            </div>
        </div>
    @endif

    <!-- Table Card -->
    <div class="vp-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-1 text-primary"></i> Partner Product Catalog</h5>
            <span class="badge bg-light text-dark font-weight-bold px-3 py-2 border">Total: {{ $products->count() }}</span>
        </div>
        <div class="card-body p-0">
            @if($products->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-box-open fs-1 text-muted opacity-50"></i>
                    <h5 class="mt-3 font-weight-bold text-dark">No Partner Items Found</h5>
                    <p class="text-muted small">No items match your active search filters.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table vp-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" style="width: 70px;">Thumbnail</th>
                                <th>Item Title</th>
                                <th>Partner Name</th>
                                <th>Price</th>
                                <th>Commission</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr class="{{ $product->approval_status === 'pending' ? 'bg-light bg-opacity-50' : '' }}">
                                <td class="ps-4">
                                    @if($product->thumb_image)
                                        <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}" class="rounded-3 shadow-sm" style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width: 48px; height: 48px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <h6 class="mb-0 font-weight-bold text-dark text-decoration-none">{{ Str::limit($product->title, 42) }}</h6>
                                    <small class="text-muted">{{ $product->category->name ?? 'Uncategorized' }}</small>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-dark d-block">{{ $product->vendor->name ?? 'N/A' }}</span>
                                    <small class="text-muted">{{ $product->vendor->vendorSettings->business_name ?? '' }}</small>
                                </td>
                                <td>
                                    @php
                                        $displayPrice = '৳0.00';
                                        $displayOldPrice = null;

                                        if ($product->product_type === 'variable' && $product->relationLoaded('variationCombinations') && $product->variationCombinations->isNotEmpty()) {
                                            $prices = [];
                                            $regularPrices = [];
                                            foreach ($product->variationCombinations as $comb) {
                                                $p = $comb->offer_price ?? $comb->regular_price ?? 0;
                                                $reg = $comb->regular_price ?? 0;
                                                if ($p > 0) $prices[] = (float)$p;
                                                if ($reg > 0) $regularPrices[] = (float)$reg;
                                            }
                                            if (!empty($prices)) {
                                                $minP = min($prices);
                                                $maxP = max($prices);
                                                if ($minP === $maxP) {
                                                    $displayPrice = '৳' . number_format($minP, 2);
                                                } else {
                                                    $displayPrice = '৳' . number_format($minP, 2) . ' - ৳' . number_format($maxP, 2);
                                                }
                                            } elseif (!empty($regularPrices)) {
                                                $minP = min($regularPrices);
                                                $maxP = max($regularPrices);
                                                if ($minP === $maxP) {
                                                    $displayPrice = '৳' . number_format($minP, 2);
                                                } else {
                                                    $displayPrice = '৳' . number_format($minP, 2) . ' - ৳' . number_format($maxP, 2);
                                                }
                                            }
                                        } else {
                                            $offerPrice = (float)($product->offer ?? 0);
                                            $oldPrice = (float)($product->old_price ?? 0);

                                            if ($offerPrice > 0) {
                                                $displayPrice = '৳' . number_format($offerPrice, 2);
                                                if ($oldPrice > $offerPrice) {
                                                    $displayOldPrice = '৳' . number_format($oldPrice, 2);
                                                }
                                            } elseif ($oldPrice > 0) {
                                                $displayPrice = '৳' . number_format($oldPrice, 2);
                                            }
                                        }
                                    @endphp
                                    <span class="font-weight-bold text-dark d-block">{{ $displayPrice }}</span>
                                    @if($displayOldPrice)
                                        <small class="text-muted text-decoration-line-through">{{ $displayOldPrice }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($product->vendor_commission_rate !== null)
                                        <span class="badge px-2 py-1 rounded-pill d-inline-block" style="color: #065f46; background: #d1fae5; font-weight: 700; font-size: 11px;">
                                            <i class="fas fa-percent me-1"></i> {{ number_format($product->vendor_commission_rate, 2) }}%
                                        </span>
                                    @elseif($product->vendor_proposed_commission)
                                        <span class="badge px-2 py-1 rounded-pill d-inline-block" style="color: #92400e; background: #fef3c7; font-weight: 700; font-size: 11px;">
                                            <i class="fas fa-hand-holding-dollar me-1"></i> Prop: {{ number_format($product->vendor_proposed_commission, 2) }}%
                                        </span>
                                    @else
                                        @php
                                            $defComm = $product->vendor?->vendorSettings ? $product->vendor->vendorSettings->getDefaultCommissionRate() : 15.0;
                                        @endphp
                                        <span class="badge px-2 py-1 rounded-pill d-inline-block" style="color: #1e40af; background: #dbeafe; font-weight: 700; font-size: 11px;">
                                            <i class="fas fa-store me-1"></i> {{ number_format($defComm, 2) }}% (Default)
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->approval_status === 'approved')
                                        <span class="badge-soft-success"><i class="fas fa-check-circle me-1"></i> Approved</span>
                                    @elseif($product->approval_status === 'pending')
                                        <span class="badge-soft-warning"><i class="fas fa-clock me-1"></i> Pending</span>
                                    @else
                                        <span class="badge-soft-danger"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small font-weight-bold text-dark d-block">{{ $product->created_at->format('d M Y') }}</span>
                                    <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('admin.vendor-products.show', $product) }}" class="btn-action-icon bg-info bg-opacity-10 text-info" title="View & Edit Item">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($product->approval_status === 'pending' || $product->approval_status === 'rejected')
                                            <form action="{{ route('admin.vendor-products.approve', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-action-icon bg-success bg-opacity-10 text-success" title="Approve Item">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($product->approval_status === 'pending' || $product->approval_status === 'approved')
                                            <form action="{{ route('admin.vendor-products.reject', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-action-icon bg-danger bg-opacity-10 text-danger" title="Reject Item" onclick="return confirm('Reject this product item?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(method_exists($products, 'links'))
                    <div class="p-3 border-top d-flex justify-content-end">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
