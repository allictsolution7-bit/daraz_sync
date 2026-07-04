@extends('vendor.layouts.app')

@section('title', 'My Products')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="fas fa-box"></i> My Products</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i> Add New Product
        </a>
    </div>
</div>

<!-- Filter Tabs -->
<div class="card mb-4">
    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index') }}">
                    All Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'approved' ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index', ['status' => 'approved']) }}">
                    Approved
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'pending' ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index', ['status' => 'pending']) }}">
                    Pending
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'rejected' ? 'active' : '' }}" 
                   href="{{ route('vendor.products.index', ['status' => 'rejected']) }}">
                    Rejected
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Products Table -->
<div class="card">
    <div class="card-body">
        @if($products->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-box fs-1 text-muted"></i>
                <h4 class="mt-3">No Products Found</h4>
                <p class="text-muted">Start by creating your first product!</p>
                <a href="{{ route('vendor.products.create') }}" class="btn btn-primary mt-2">
                    <i class="fas fa-plus-circle"></i> Create Product
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Commission</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
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
                                <strong>{{ $product->title }}</strong><br>
                                <small class="text-muted">{{ $product->category->name ?? 'N/A' }}</small>
                            </td>
                            <td>
                                @if($product->old_price && $product->old_price > $product->offer)
                                    <span class="text-decoration-line-through text-muted">৳{{ number_format($product->old_price, 2) }}</span><br>
                                @endif
                                <strong>৳{{ number_format($product->offer, 2) }}</strong>
                            </td>
                            <td>
                                @if($product->vendor_commission_rate)
                                    <span class="badge bg-info">{{ $product->vendor_commission_rate }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                @if($product->vendor_proposed_commission && $product->vendor_proposed_commission != $product->vendor_commission_rate)
                                    <br><small class="text-warning">Proposed: {{ $product->vendor_proposed_commission }}%</small>
                                @endif
                            </td>
                            <td>
                                @if($product->manage_stock)
                                    @if($product->quantity > 0)
                                        <span class="text-success">{{ $product->quantity }}</span>
                                    @else
                                        <span class="text-danger">Out of Stock</span>
                                    @endif
                                @else
                                    <span class="text-muted">Not Managed</span>
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
                                @if(!$product->status)
                                    <br><small class="text-muted">Inactive</small>
                                @endif
                            </td>
                            <td>
                                <small>{{ $product->created_at->format('d M Y') }}</small>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('vendor.products.edit', $product) }}" 
                                       class="btn btn-outline-primary"
                                       title="Edit">
                                        <i class="fas fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('vendor.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-outline-danger"
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

            <!-- Pagination -->
            <div class="mt-3">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Info Alert -->
@if($products->isNotEmpty())
<div class="alert alert-info mt-4">
    <i class="fas fa-info-circle"></i>
    <strong>Note:</strong> Products with "Pending" status are awaiting admin approval. Once approved, they will be visible on your store.
    @if($products->where('approval_status', 'rejected')->isNotEmpty())
        Rejected products can be edited and resubmitted for approval.
    @endif
</div>
@endif
@endsection

