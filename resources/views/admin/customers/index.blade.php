@extends('layouts.master')

@section('title', 'Customers Management')

@section('styles')
<style>
    .admin-customers-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        border-radius: 16px;
        padding: 24px 28px;
        color: #fff;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.15);
        position: relative;
        overflow: hidden;
    }
    .admin-customers-hero::before {
        content: '';
        position: absolute;
        top: -30px; right: -30px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }
    .admin-customers-hero h2 { font-size: 1.5rem; font-weight: 700; margin: 0; }
    .admin-customers-hero p { opacity: 0.9; margin: 4px 0 0; font-size: 0.9rem; }

    .stat-card-custom {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s;
    }
    .stat-card-custom:hover {
        transform: translateY(-2px);
    }
    .stat-icon-custom {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .stat-info-custom h4 { font-size: 1.5rem; font-weight: 700; margin: 0; color: #1e293b; }
    .stat-info-custom p { font-size: 0.8rem; margin: 0; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

    .filter-bar-custom {
        background: #fff;
        border-radius: 12px;
        padding: 16px 20px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        margin-bottom: 20px;
    }

    .customer-table-card-custom {
        background: #fff;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    .table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 18px;
        border-bottom: 1px solid #f1f5f9;
    }

    .table td {
        padding: 14px 18px;
        vertical-align: middle;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .avatar-icon-custom {
        width: 38px;
        height: 38px;
        background: #eff6ff;
        color: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .cname { font-weight: 600; color: #1e293b; }
    .cemail { font-size: 0.8rem; color: #64748b; }
    .creator-badge {
        background-color: #f1f5f9;
        color: #334155;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 2px;
    }
    .creator-role-badge {
        background-color: #e0f2fe;
        color: #0369a1;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: inline-block;
    }

    .empty-state-custom {
        text-align: center;
        padding: 50px 20px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .empty-state-custom i { font-size: 2.5rem; color: #cbd5e1; margin-bottom: 12px; }
    .empty-state-custom h5 { color: #475569; font-weight: 600; }
    .empty-state-custom p { color: #94a3b8; max-width: 300px; margin: 0 auto; }
</style>
@endsection

@section('content')
<div class="container-fluid px-3 py-4">

    {{-- Hero Section --}}
    <div class="admin-customers-hero">
        <h2><i class="fas fa-users me-2"></i>Customers Registry</h2>
        <p>Overview of all created customers, their activity, spend history, and creators across the system</p>
    </div>

    {{-- Statistics --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card-custom">
                <div class="stat-icon-custom" style="background: #eef2ff; color: #4f46e5;">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="stat-info-custom">
                    <h4>{{ number_format($totalCustomers) }}</h4>
                    <p>Total Customers</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card-custom">
                <div class="stat-icon-custom" style="background: #ecfdf5; color: #10b981;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-info-custom">
                    <h4>{{ number_format($totalOrders) }}</h4>
                    <p>Total Orders</p>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="stat-card-custom">
                <div class="stat-icon-custom" style="background: #fff7ed; color: #f97316;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stat-info-custom">
                    <h4>৳{{ number_format($totalSpend, 2) }}</h4>
                    <p>Lifetime Spend</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar-custom">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted" style="border-radius: 8px 0 0 8px;">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" style="border-radius: 0 8px 8px 0;"
                           placeholder="Search by name, phone, email, city, or upazila..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 8px; height: 38px;">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
            @if(request()->filled('search'))
                <div class="col-12 col-md-2">
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary w-100" style="border-radius: 8px; height: 38px;">
                        Clear
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Table / Content --}}
    @if($customers->isEmpty())
        <div class="empty-state-custom">
            <i class="fas fa-users-slash text-muted mb-2"></i>
            <h5>No Customers Found</h5>
            <p>No customers exist in the system yet or matches the current filter settings.</p>
        </div>
    @else
        <div class="customer-table-card-custom">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Customer Info</th>
                            <th>Contact Info</th>
                            <th>Location / Address</th>
                            <th class="text-center">Orders</th>
                            <th class="text-end">Total Spent</th>
                            <th>Created By</th>
                            <th class="text-center">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td><span class="text-muted fw-bold">#{{ $customer->id }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-icon-custom">
                                            {{ strtoupper(substr($customer->name ?? 'C', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="cname">{{ $customer->name }}</div>
                                            @if($customer->email)
                                                <div class="cemail">{{ $customer->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->phone)
                                        <div class="fw-bold text-dark"><i class="fas fa-phone-alt me-1 text-muted"></i>{{ $customer->phone }}</div>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->address || $customer->city)
                                        <div>{{ $customer->address }}</div>
                                        @if($customer->city)
                                            <span class="badge bg-light text-secondary border mt-1">{{ $customer->city }}</span>
                                        @endif
                                        @if($customer->upazila)
                                            <span class="badge bg-light text-secondary border mt-1">{{ $customer->upazila }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Not Specified</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary rounded-pill">{{ $customer->orders_count }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold text-dark">৳{{ number_format($customer->lifetime_spend ?? 0, 2) }}</span>
                                </td>
                                <td>
                                    @if($customer->creator)
                                        <div>
                                            <span class="fw-bold text-dark"><i class="fas fa-user-circle me-1 text-muted"></i>{{ $customer->creator->name }}</span>
                                        </div>
                                        <div>
                                            @if($customer->creator->roles->isNotEmpty())
                                                <span class="creator-role-badge">
                                                    {{ $customer->creator->roles->pluck('name')->implode(', ') }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border">No Role</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted"><i class="fas fa-globe me-1 text-muted"></i>Self-Registered</span>
                                    @endif
                                </td>
                                <td class="text-center text-muted" style="font-size: 0.8rem;">
                                    {{ $customer->created_at ? $customer->created_at->format('M d, Y H:i') : 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($customers->hasPages())
                <div class="card-footer bg-white border-0 py-3 d-flex justify-content-center">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
