@extends('layouts.master')

@section('title', 'Vendor Product Approval')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12 mb-3 text-end">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-box-seam-fill"></i> Vendor Product Approval</h4>
                <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Vendors
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.vendor-products.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search products..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="vendor_id" class="form-select">
                        <option value="">All Vendors</option>
                        @foreach(\App\Models\User::role('vendor')->get() as $v)
                            <option value="{{ $v->id }}" {{ request('vendor_id') == $v->id ? 'selected' : '' }}>
                                {{ $v->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Pending Count Badge -->
    @if($products->where('approval_status', 'pending')->count() > 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>{{ $products->where('approval_status', 'pending')->count() }} product(s)</strong> awaiting approval.
        </div>
    @endif

    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            @if($products->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-box-seam fs-1 text-muted"></i>
                    <h4 class="mt-3">No Products Found</h4>
                    <p class="text-muted">No vendor products match your filter criteria.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 60px;">Image</th>
                                <th>Product Name</th>
                                <th>Vendor</th>
                                <th>Price</th>
                                <th>Commission</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr class="{{ $product->approval_status === 'pending' ? 'table-warning' : '' }}">
                                <td>
                                    @if($product->thumb_image)
                                        <img src="{{ asset('storage/' . $product->thumb_image) }}" 
                                             alt="{{ $product->title }}"
                                             class="rounded"
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ Str::limit($product->title, 40) }}</strong><br>
                                    <small class="text-muted">{{ $product->category->name ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $product->vendor->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $product->vendor->vendorSettings->business_name ?? '' }}</small>
                                </td>
                                <td>
                                    <strong>৳{{ number_format($product->offer, 2) }}</strong>
                                    @if($product->old_price && $product->old_price > $product->offer)
                                        <br><small class="text-muted text-decoration-line-through">৳{{ number_format($product->old_price, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($product->vendor_proposed_commission)
                                        <span class="badge bg-warning text-dark">
                                            Proposed: {{ $product->vendor_proposed_commission }}%
                                        </span>
                                    @endif
                                    @if($product->vendor_commission_rate)
                                        <br><span class="badge bg-info">
                                            Approved: {{ $product->vendor_commission_rate }}%
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->approval_status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle"></i> Approved
                                        </span>
                                    @elseif($product->approval_status === 'pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $product->created_at->format('d M Y') }}</small><br>
                                    <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.vendor-products.show', $product) }}" 
                                           class="btn btn-sm btn-outline-info"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($product->approval_status === 'pending')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-success"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal{{ $product->id }}"
                                                    title="Approve">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $product->id }}"
                                                    title="Reject">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.vendor-products.approve', $product) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Approve Product</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to approve <strong>{{ $product->title }}</strong>?</p>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Commission Rate (%)</label>
                                                            <input type="number" 
                                                                   name="vendor_commission_rate" 
                                                                   class="form-control"
                                                                   step="0.01"
                                                                   min="0"
                                                                   max="100"
                                                                   value="{{ $product->vendor_proposed_commission ?? $product->vendor->vendorSettings->getDefaultCommissionRate() }}">
                                                            <small class="text-muted">
                                                                Vendor proposed: {{ $product->vendor_proposed_commission ?? 'N/A' }}% | 
                                                                Default: {{ $product->vendor->vendorSettings->getDefaultCommissionRate() }}%
                                                            </small>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Note (Optional)</label>
                                                            <textarea name="commission_note" class="form-control" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-check-circle"></i> Approve Product
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.vendor-products.reject', $product) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Product</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to reject <strong>{{ $product->title }}</strong>?</p>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Rejection Reason *</label>
                                                            <textarea name="rejection_reason" 
                                                                      class="form-control" 
                                                                      rows="3" 
                                                                      required
                                                                      placeholder="Explain why this product is being rejected..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-times-circle"></i> Reject Product
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

