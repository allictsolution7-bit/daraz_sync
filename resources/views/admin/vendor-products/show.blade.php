@extends('layouts.master')

@section('title', 'Product Approval - ' . $product->title)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-box"></i> Product Approval</h2>
                <div>
                    <a href="{{ route('admin.vendor-products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Product Details -->
        <div class="col-md-8">
            <!-- Status Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $product->title }}</h3>
                            <p class="text-muted mb-0">Category: {{ $product->category->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            @switch($product->approval_status)
                                @case('approved')
                                    <span class="badge bg-success fs-5">
                                        <i class="fas fa-check-circle"></i> Approved
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning text-dark fs-5">
                                        <i class="fas fa-clock"></i> Pending Review
                                    </span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger fs-5">
                                        <i class="fas fa-x-circle"></i> Rejected
                                    </span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vendor Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-person"></i> Vendor Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Vendor Name:</strong></td>
                                    <td>
                                        <a href="{{ route('admin.vendors.show', $product->vendor) }}">
                                            {{ $product->vendor->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Business Name:</strong></td>
                                    <td>{{ $product->vendor->vendorSettings->business_name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Status:</strong></td>
                                    <td>
                                        @if($product->vendor->vendorSettings->is_verified)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Not Verified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Products:</strong></td>
                                    <td>{{ $product->vendor->products()->count() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Product Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Description:</strong>
                            <div class="mt-2">
                                {!! $product->description ?? 'No description provided' !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Category:</strong></td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Brand:</strong></td>
                                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Regular Price:</strong></td>
                                    <td>৳{{ number_format($product->old_price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Offer Price:</strong></td>
                                    <td>৳{{ number_format($product->offer, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Stock Quantity:</strong></td>
                                    <td>{{ $product->quantity ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($product->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Featured:</strong></td>
                                    <td>
                                        @if($product->is_featured)
                                            <span class="badge bg-primary">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Upload Date:</strong></td>
                                    <td>{{ $product->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commission Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-percent"></i> Commission Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="50%"><strong>Vendor's Default Rate:</strong></td>
                                    <td>{{ $product->vendor->vendorSettings->getDefaultCommissionRate() }}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Vendor's Min Rate:</strong></td>
                                    <td>{{ $product->vendor->vendorSettings->getMinCommissionRate() }}%</td>
                                </tr>
                                <tr>
                                    <td><strong>Vendor's Max Rate:</strong></td>
                                    <td>{{ $product->vendor->vendorSettings->getMaxCommissionRate() }}%</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="50%"><strong>Proposed Rate:</strong></td>
                                    <td>
                                        @if($product->vendor_proposed_commission)
                                            <span class="badge bg-info">{{ $product->vendor_proposed_commission }}%</span>
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Final Approved Rate:</strong></td>
                                    <td>
                                        @if($product->vendor_commission_rate)
                                            <span class="badge bg-success">{{ $product->vendor_commission_rate }}%</span>
                                        @else
                                            <span class="text-muted">Not set</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($product->commission_note)
                        <div class="alert alert-info mt-3">
                            <strong><i class="fas fa-info-circle"></i> Commission Note:</strong>
                            <p class="mb-0 mt-2">{{ $product->commission_note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Images -->
            @if($product->thumb_image || $product->images)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-images"></i> Product Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($product->thumb_image)
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-2">
                                        <img src="{{ asset('storage/' . $product->thumb_image) }}" 
                                             alt="Main Image" 
                                             class="img-fluid">
                                        <p class="text-center mb-0 mt-2"><small class="badge bg-primary">Featured</small></p>
                                    </div>
                                </div>
                            @endif
                            
                            @if($product->images)
                                @foreach(json_decode($product->images, true) ?? [] as $image)
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-2">
                                            <img src="{{ asset('storage/' . $image) }}" 
                                                 alt="Product Image" 
                                                 class="img-fluid">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Approval History -->
            @if($product->approved_at || $product->rejection_reason)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock-history"></i> Approval History</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            @if($product->approved_at)
                                <tr>
                                    <td width="30%"><strong>Approved Date:</strong></td>
                                    <td>{{ $product->approved_at->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Approved By:</strong></td>
                                    <td>{{ $product->approvedBy->name ?? 'Admin' }}</td>
                                </tr>
                            @endif
                            
                            @if($product->rejection_reason)
                                <tr>
                                    <td><strong>Rejection Reason:</strong></td>
                                    <td>
                                        <div class="alert alert-danger mb-0">
                                            {{ $product->rejection_reason }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column - Actions -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            @if($product->approval_status == 'pending')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-lightning"></i> Approve Product</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.vendor-products.approve', $product) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Commission Rate (%) *</label>
                                <input type="number" 
                                       name="commission_rate" 
                                       class="form-control" 
                                       value="{{ $product->vendor_proposed_commission ?? $product->vendor->vendorSettings->getDefaultCommissionRate() }}"
                                       min="{{ $product->vendor->vendorSettings->getMinCommissionRate() }}"
                                       max="{{ $product->vendor->vendorSettings->getMaxCommissionRate() }}"
                                       step="0.01"
                                       required>
                                <small class="text-muted">
                                    Range: {{ $product->vendor->vendorSettings->getMinCommissionRate() }}% - {{ $product->vendor->vendorSettings->getMaxCommissionRate() }}%
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Commission Note (Optional)</label>
                                <textarea name="commission_note" 
                                          class="form-control" 
                                          rows="3" 
                                          placeholder="Add notes about the commission rate"></textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Approve this product?')">
                                    <i class="fas fa-check-circle"></i> Approve Product
                                </button>
                            </div>
                        </form>

                        <hr>

                        <button type="button" class="btn btn-danger btn-lg w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-x-circle"></i> Reject Product
                        </button>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.vendor-products.reject', $product) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Product</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Reason for Rejection *</label>
                                                <textarea name="reason" 
                                                          class="form-control" 
                                                          rows="4" 
                                                          required 
                                                          placeholder="Explain why this product is being rejected"></textarea>
                                            </div>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                The vendor will be notified and can resubmit the product.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject Product</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if($product->approval_status == 'approved')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-pencil"></i> Update Commission</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.vendor-products.update-commission', $product) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Commission Rate (%) *</label>
                                <input type="number" 
                                       name="commission_rate" 
                                       class="form-control" 
                                       value="{{ $product->vendor_commission_rate }}"
                                       min="{{ $product->vendor->vendorSettings->getMinCommissionRate() }}"
                                       max="{{ $product->vendor->vendorSettings->getMaxCommissionRate() }}"
                                       step="0.01"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Commission Note (Optional)</label>
                                <textarea name="commission_note" 
                                          class="form-control" 
                                          rows="3">{{ $product->commission_note }}</textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Commission
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Quick Links -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-link"></i> Quick Links</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('product.single', ['id' => $product->id, 'slug' => $product->slug]) }}" 
                           class="btn btn-outline-primary" 
                           target="_blank">
                            <i class="fas fa-eye"></i> View Product Page
                        </a>
                        
                        <a href="{{ route('admin.vendors.show', $product->vendor) }}" 
                           class="btn btn-outline-info">
                            <i class="fas fa-person"></i> View Vendor Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-graph-up"></i> Product Stats</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Views:</strong></td>
                            <td class="text-end">{{ $product->views ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td><strong>Orders:</strong></td>
                            <td class="text-end">{{ $product->order_items_count ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td><strong>Revenue:</strong></td>
                            <td class="text-end">৳{{ number_format($product->order_items_sum_sub_total ?? 0, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

