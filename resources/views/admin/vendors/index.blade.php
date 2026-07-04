@extends('layouts.master')

@section('title', 'Manage Vendors')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h4><i class="fas fa-people"></i> Manage Vendors</h4>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add New Vendor
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.vendors.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Search by name, email, or business..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                        <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Unverified</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
                <div class="col-md-3 text-end">
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-x-circle"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Vendors Table -->
    <div class="card">
        <div class="card-body">
            @if($vendors->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-people fs-1 text-muted"></i>
                    <h4 class="mt-3">No Vendors Found</h4>
                    <p class="text-muted">Start by adding your first vendor!</p>
                    <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary mt-2">
                        <i class="fas fa-plus-circle"></i> Add Vendor
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Business Name</th>
                                <th>Contact</th>
                                <th>Products</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendors as $vendor)
                            <tr>
                                <td>
                                    <strong>{{ $vendor->name }}</strong><br>
                                    <small class="text-muted">{{ $vendor->email }}</small>
                                </td>
                                <td>
                                    @if($vendor->vendorSettings)
                                        <strong>{{ $vendor->vendorSettings->business_name }}</strong><br>
                                        <small class="text-muted">{{ $vendor->vendorSettings->business_phone }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vendor->vendorSettings)
                                        {{ $vendor->vendorSettings->business_email }}<br>
                                        <small class="text-muted">{{ $vendor->vendorSettings->business_phone }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $vendor->products->count() }}</strong> products<br>
                                    <small class="text-success">{{ $vendor->products->where('approval_status', 'approved')->count() }} approved</small>
                                </td>
                                <td>
                                    @if($vendor->vendorSettings)
                                        @if($vendor->vendorSettings->is_verified)
                                            <span class="badge bg-success">
                                                <i class="fas fa-patch-check"></i> Verified
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock"></i> Unverified
                                            </span>
                                        @endif
                                        <br>
                                        @if($vendor->vendorSettings->is_active)
                                            <span class="badge bg-success mt-1">Active</span>
                                        @else
                                            <span class="badge bg-danger mt-1">Inactive</span>
                                        @endif
                                    @else
                                        <span class="text-muted">No Settings</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $vendor->created_at->format('d M Y') }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('admin.vendors.show', $vendor) }}" 
                                           class="btn btn-sm btn-outline-info"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.vendors.edit', $vendor) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($vendor->vendorSettings)
                                            @if(!$vendor->vendorSettings->is_verified)
                                                <form action="{{ route('admin.vendors.verify', $vendor) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-success"
                                                            title="Verify Vendor">
                                                        <i class="fas fa-check-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.vendors.toggle-status', $vendor) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-{{ $vendor->vendorSettings->is_active ? 'danger' : 'success' }}"
                                                        title="{{ $vendor->vendorSettings->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $vendor->vendorSettings->is_active ? 'times-circle' : 'check-circle' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.vendors.destroy', $vendor) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this vendor? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-outline-danger"
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
                    {{ $vendors->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

