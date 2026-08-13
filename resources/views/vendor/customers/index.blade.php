@extends('vendor.layouts.app')

@section('title', 'My Customers')

@section('styles')
<style>
    .reseller-customers-hero {
        background: linear-gradient(135deg, #06b6d4 0%, #0891b2 50%, #0e7490 100%);
        border-radius: 20px;
        padding: 28px 32px;
        color: #fff;
        margin-bottom: 28px;
        box-shadow: 0 12px 40px rgba(6, 182, 212, 0.25);
        position: relative;
        overflow: hidden;
    }
    .reseller-customers-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .reseller-customers-hero h2 { font-size: 1.6rem; font-weight: 800; margin: 0; }
    .reseller-customers-hero p { opacity: 0.85; margin: 5px 0 0; }

    .stat-pill {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 12px;
        padding: 12px 16px;
        text-align: center;
        min-width: 120px;
    }
    .stat-pill .val { font-size: 1.4rem; font-weight: 800; }
    .stat-pill .lbl { font-size: 0.72rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.5px; }

    .filter-bar {
        background: #fff;
        border-radius: 16px;
        padding: 18px 20px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        margin-bottom: 20px;
    }

    .customer-table-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .table td {
        padding: 16px 20px;
        vertical-align: middle;
        font-size: 0.875rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .avatar-icon {
        width: 36px;
        height: 36px;
        background: #ecfeff;
        color: #0891b2;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .cname { font-weight: 600; color: #0f172a; }
    .cphone { font-size: 0.825rem; color: #64748b; font-weight: 500; }
    .cemail { font-size: 0.8rem; color: #94a3b8; }
    
    .badge-count {
        background-color: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
    }
    
    .spend-amount {
        color: #0f172a;
        font-weight: 700;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .empty-state i { font-size: 3rem; color: #c7d2fe; margin-bottom: 16px; }
    .empty-state h5 { color: #4b5563; font-weight: 700; }
    .empty-state p { color: #9ca3af; max-width: 320px; margin: 0 auto; }
</style>
@endsection

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    {{-- Hero Header --}}
    <div class="reseller-customers-hero">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h2><i class="fas fa-users me-2"></i>My Customers</h2>
                <p>Manage and track customer users created during POS transactions</p>
            </div>
            <div class="d-flex gap-3">
                <div class="stat-pill">
                    <div class="val">{{ $totalCustomers }}</div>
                    <div class="lbl">Total Customers</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar">
        <form method="GET" action="{{ route('vendor.customers.index') }}" class="row g-3 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Search by name, phone, email, or city..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-cyan text-white w-100 fw-bold" style="background-color:#0891b2; border-color:#0891b2;">
                    Filter
                </button>
            </div>
            @if(request()->filled('search'))
                <div class="col-12 col-md-2">
                    <a href="{{ route('vendor.customers.index') }}" class="btn btn-outline-secondary w-100">
                        Clear
                    </a>
                </div>
            @endif
        </form>
    </div>

    {{-- Main Content --}}
    @if($customers->isEmpty())
        <div class="empty-state">
            <i class="fas fa-users-slash text-cyan mb-3" style="color: #c7d2fe;"></i>
            <h5>No Customers Found</h5>
            <p class="text-muted">You haven't created any customer users yet, or none match your search criteria.</p>
        </div>
    @else
        <div class="customer-table-card">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact Info</th>
                            <th>Location / Address</th>
                            <th class="text-center">Orders Count</th>
                            <th class="text-end">Total Spent</th>
                            <th class="text-center">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-icon">
                                            {{ strtoupper(substr($customer->name ?? 'C', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="cname">{{ $customer->name }}</div>
                                            @if($customer->email && !str_contains($customer->email, '@pos.com'))
                                                <div class="cemail">{{ $customer->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="cphone">
                                        <i class="fas fa-phone-alt me-1 text-cyan fs-9" style="color:#0891b2;"></i>
                                        @if($customer->phone)
                                            @php
                                                $phone = $customer->phone;
                                                $len = strlen($phone);
                                                if ($len > 8) {
                                                    $maskedPhone = substr($phone, 0, 4) . str_repeat('*', $len - 6) . substr($phone, -2);
                                                } elseif ($len > 4) {
                                                    $maskedPhone = substr($phone, 0, 2) . str_repeat('*', $len - 4) . substr($phone, -2);
                                                } else {
                                                    $maskedPhone = str_repeat('*', $len);
                                                }
                                            @endphp
                                            {{ $maskedPhone }}
                                        @else
                                            N/A
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($customer->address || $customer->city)
                                        <span class="text-dark fw-500">{{ $customer->address }}</span>
                                        @if($customer->city)
                                            <span class="badge bg-light text-secondary border ms-1">{{ $customer->city }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted fs-8">Not Specified</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge-count">{{ $customer->orders_count }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="spend-amount">৳{{ number_format($customer->lifetime_spend ?? 0, 2) }}</span>
                                </td>
                                <td class="text-center text-muted" style="font-size: 0.8rem;">
                                    {{ $customer->created_at ? $customer->created_at->format('M d, Y') : 'N/A' }}
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
