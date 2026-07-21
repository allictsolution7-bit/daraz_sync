@extends('layouts.master')

@section('title', 'Partner Item Approval - ' . $product->title)

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
    .badge-soft-success {
        background: #dcfce7;
        color: #15803d;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
    }
    .badge-soft-warning {
        background: #fef3c7;
        color: #b45309;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
    }
    .badge-soft-danger {
        background: #fee2e2;
        color: #b91c1c;
        font-weight: 700;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
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

