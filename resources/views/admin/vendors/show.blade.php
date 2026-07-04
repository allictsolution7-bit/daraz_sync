@extends('layouts.master')

@section('title', 'Vendor Details - ' . $vendor->name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-person-circle"></i> Vendor Details</h2>
                <div>
                    <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-primary">
                        <i class="fas fa-pencil"></i> Edit Vendor
                    </a>
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                            <p class="mb-0">Total Products</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-box fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                            <p class="mb-0">Total Orders</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cart fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">৳{{ number_format($stats['total_earnings'], 2) }}</h3>
                            <p class="mb-0">Total Earnings</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cash-stack fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="mb-0">৳{{ number_format($stats['current_balance'], 2) }}</h3>
                            <p class="mb-0">Current Balance</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-wallet2 fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Vendor Information -->
        <div class="col-md-8">
            <!-- Account Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-person"></i> Account Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Full Name:</strong></td>
                                    <td>{{ $vendor->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $vendor->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $vendor->phone ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Member Since:</strong></td>
                                    <td>{{ $vendor->created_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Status:</strong></td>
                                    <td>
                                        @if($vendorSettings->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Verification:</strong></td>
                                    <td>
                                        @if($vendorSettings->is_verified)
                                            <span class="badge bg-success">
                                                <i class="fas fa-patch-check"></i> Verified
                                            </span>
                                            <br><small class="text-muted">on {{ $vendorSettings->verified_at->format('d M Y') }}</small>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                            <br>
                                            <form action="{{ route('admin.vendors.verify', $vendor) }}" method="POST" class="mt-2">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check-circle"></i> Verify Now
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Last Login:</strong></td>
                                    <td>{{ $vendor->last_login_at ? $vendor->last_login_at->format('d M Y, h:i A') : 'Never' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Business Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td width="30%"><strong>Business Name:</strong></td>
                            <td>{{ $vendorSettings->business_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Business Email:</strong></td>
                            <td>{{ $vendorSettings->business_email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Business Phone:</strong></td>
                            <td>{{ $vendorSettings->business_phone }}</td>
                        </tr>
                        <tr>
                            <td><strong>Business Address:</strong></td>
                            <td>{{ $vendorSettings->business_address ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tax ID:</strong></td>
                            <td>{{ $vendorSettings->tax_id ?? 'Not provided' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Recent Products</h5>
                        <a href="{{ route('admin.vendor-products.index', ['vendor' => $vendor->id]) }}" class="btn btn-sm btn-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Commission</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProducts as $product)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.vendor-products.show', $product) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @switch($product->approval_status)
                                                    @case('approved')
                                                        <span class="badge bg-success">Approved</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>৳{{ number_format($product->offer ?? $product->old_price, 2) }}</td>
                                            <td>{{ $product->vendor_commission_rate ?? 'N/A' }}%</td>
                                            <td>{{ $product->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No products yet</p>
                    @endif
                </div>
            </div>

            <!-- Recent Withdrawals -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-cash-stack"></i> Recent Withdrawals</h5>
                        <a href="{{ route('admin.vendor-withdrawals.index', ['vendor' => $vendor->id]) }}" class="btn btn-sm btn-primary">
                            View All
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentWithdrawals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentWithdrawals as $withdrawal)
                                        <tr>
                                            <td>{{ $withdrawal->created_at->format('d M Y') }}</td>
                                            <td>৳{{ number_format($withdrawal->amount, 2) }}</td>
                                            <td>
                                                @switch($withdrawal->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                        @break
                                                    @case('approved')
                                                        <span class="badge bg-info">Approved</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">Completed</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger">Rejected</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.vendor-withdrawals.show', $withdrawal) }}" class="btn btn-sm btn-info">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No withdrawals yet</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Settings & Quick Actions -->
        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightning"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(!$vendorSettings->is_verified)
                            <form action="{{ route('admin.vendors.verify', $vendor) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle"></i> Verify Vendor
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.vendors.toggle-status', $vendor) }}" method="POST">
                            @csrf
                            @if($vendorSettings->is_active)
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-pause-circle"></i> Deactivate Account
                                </button>
                            @else
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-play-circle"></i> Activate Account
                                </button>
                            @endif
                        </form>

                        <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-primary">
                            <i class="fas fa-pencil"></i> Edit Vendor
                        </a>
                    </div>
                </div>
            </div>

            <!-- Commission Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-percent"></i> Commission Settings</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Default Rate:</strong></td>
                            <td>{{ $vendorSettings->getDefaultCommissionRate() }}%</td>
                        </tr>
                        <tr>
                            <td><strong>Min Rate:</strong></td>
                            <td>{{ $vendorSettings->getMinCommissionRate() }}%</td>
                        </tr>
                        <tr>
                            <td><strong>Max Rate:</strong></td>
                            <td>{{ $vendorSettings->getMaxCommissionRate() }}%</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Product Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-box"></i> Product Settings</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Product Limit:</strong></td>
                            <td>
                                @if($vendorSettings->product_limit == 0)
                                    Unlimited
                                @else
                                    {{ $stats['total_products'] }} / {{ $vendorSettings->product_limit }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Auto-Approve:</strong></td>
                            <td>
                                @if($vendorSettings->auto_approve_products)
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-secondary">Disabled</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Withdrawal Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cash"></i> Withdrawal Settings</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Min Amount:</strong></td>
                            <td>৳{{ number_format($vendorSettings->getMinWithdrawalAmount(), 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>Payout Method:</strong></td>
                            <td class="text-capitalize">{{ $vendorSettings->payout_method ?? 'Not set' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Payout Details -->
            @if($vendorSettings->payout_method)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-credit-card"></i> Payout Details</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Method:</strong></td>
                                <td class="text-capitalize">{{ $vendorSettings->payout_method }}</td>
                            </tr>
                            <tr>
                                <td><strong>Account:</strong></td>
                                <td>{{ $vendorSettings->payout_account_number }}</td>
                            </tr>
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $vendorSettings->payout_account_name }}</td>
                            </tr>
                            @if($vendorSettings->payout_method === 'bank')
                                <tr>
                                    <td><strong>Bank:</strong></td>
                                    <td>{{ $vendorSettings->payout_bank_name }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

