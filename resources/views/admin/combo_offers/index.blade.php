@extends('layouts.master')

@section('title', 'Combo Offers')

@section('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        /* Main Container Styling */
        .categories-container {
            font-family: 'Outfit', sans-serif;
            background: #f8fafc;
            border-radius: 24px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.02);
            margin-top: 1rem;
        }

        /* Sleek Glassmorphic Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.03);
            margin-bottom: 1.25rem;
        }

        /* Custom Breadcrumb Styles */
        .custom-breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 0.75rem;
        }
        .custom-breadcrumb .breadcrumb-item {
            font-size: 0.85rem;
            font-weight: 500;
        }
        .custom-breadcrumb .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .custom-breadcrumb .breadcrumb-item a:hover {
            color: #4f46e5;
        }
        .custom-breadcrumb .breadcrumb-item.active {
            color: #1e293b;
            font-weight: 600;
        }

        /* Typography */
        .page-header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 0.15rem;
        }
        .page-header-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 0.75rem;
        }

        /* Action Toolbar */
        .action-toolbar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        /* Premium Buttons */
        .btn-add-category {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            border: none;
            padding: 0.6rem 1.35rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .btn-add-category:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.45);
            color: #ffffff;
        }

        /* Modernized Table Design */
        .premium-table-wrapper {
            border-radius: 16px;
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.015);
        }
        .premium-table {
            width: 100% !important;
            margin: 0 !important;
            border-collapse: collapse;
            white-space: nowrap;
        }
        .premium-table thead th {
            background: #0f172a !important; /* Premium Slate Dark Header */
            color: #f1f5f9 !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.775rem;
            letter-spacing: 0.06em;
            padding: 1.25rem 1rem !important;
            border-bottom: none !important;
        }
        .premium-table tbody tr {
            background: #ffffff;
            transition: all 0.25s ease;
        }
        .premium-table tbody tr:hover {
            background: #f8fafc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        }
        .premium-table tbody td {
            padding: 1.1rem 1rem !important;
            vertical-align: middle !important;
            color: #334155;
            font-size: 0.95rem;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        /* Text Wrap Utility */
        .text-wrap-column {
            white-space: normal !important;
            min-width: 180px;
            max-width: 260px;
            word-break: break-word;
        }

        /* Status Toggle Button Pill */
        .status-pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .status-pill-btn-active {
            background: rgba(34, 197, 94, 0.12);
            color: #166534;
        }
        .status-pill-btn-active::before {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(34, 197, 94, 0.8);
        }
        .status-pill-btn-inactive {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
        }
        .status-pill-btn-inactive::before {
            content: '';
            display: block;
            width: 6px;
            height: 6px;
            background: #64748b;
            border-radius: 50%;
        }
        .status-pill-btn:hover {
            transform: scale(1.05);
        }

        /* Custom Action Icon Buttons */
        .btn-action-custom {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            background: #f8fafc;
            color: #475569;
            text-decoration: none;
        }
        .btn-action-custom:hover {
            transform: translateY(-2px);
        }
        .btn-action-delete:hover {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }
        .btn-action-edit:hover {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }
    </style>
@endsection

@section('content')
    <div class="container categories-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Combo Offers</li>
            </ol>
        </nav>

        <div class="glass-card">
            <!-- Header Section -->
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h1 class="page-header-title">Combo Offers</h1>
                    <p class="page-header-subtitle">Moderate store bundle promotions, pricing configurations, and active status.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="action-toolbar">
                        <a href="{{ route('admin.combo_offers.create') }}" class="btn-add-category">
                            <i class="fa-solid fa-plus"></i> Create Combo Offer
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success/Error Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; font-family: 'Outfit', sans-serif;">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; font-family: 'Outfit', sans-serif;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Table Section -->
            <div class="premium-table-wrapper">
                <table class="table premium-table">
                    <thead>
                        <tr>
                            <th width="80">ID</th>
                            <th>Base Product</th>
                            <th>Combo Title</th>
                            <th width="120">Items Count</th>
                            <th width="140">Combo Price</th>
                            <th width="140">Original Price</th>
                            <th width="120">Status</th>
                            <th width="140">Created At</th>
                            <th width="100" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comboOffers as $comboOffer)
                            <tr>
                                <td class="font-monospace text-muted">#{{ $comboOffer->id }}</td>
                                <td class="fw-semibold text-slate-800 text-wrap-column">{{ $comboOffer->product->title }}</td>
                                <td class="text-slate-700 text-wrap-column">{{ $comboOffer->title }}</td>
                                <td class="text-center fw-medium text-slate-600">{{ $comboOffer->items_count }}</td>
                                <td class="fw-semibold text-slate-800">৳{{ number_format($comboOffer->combo_price, 2) }}</td>
                                <td class="text-decoration-line-through text-muted" style="font-size: 0.875rem;">৳{{ number_format($comboOffer->original_price, 2) }}</td>
                                <td>
                                    <button type="button" class="status-pill-btn {{ $comboOffer->is_active ? 'status-pill-btn-active' : 'status-pill-btn-inactive' }} toggle-status" 
                                            data-id="{{ $comboOffer->id }}" title="Click to Toggle Status">
                                        {{ $comboOffer->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-secondary">{{ $comboOffer->created_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1" role="group">
                                        <a href="{{ route('admin.combo_offers.edit', $comboOffer) }}" 
                                           class="btn-action-custom btn-action-edit" title="Edit Combo Offer">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.combo_offers.destroy', $comboOffer) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this combo offer?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-custom btn-action-delete" title="Delete Combo Offer">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-gift d-block mb-3" style="font-size: 2rem; color: #cbd5e1;"></i>
                                    No combo offers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Wrapper -->
            @if ($comboOffers->hasPages())
                <div class="mt-4 d-flex justify-content-end">
                    {{ $comboOffers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.toggle-status').click(function() {
        const button = $(this);
        const comboId = button.data('id');
        
        $.ajax({
            url: `/admin/combo_offers/${comboId}/toggle-status`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                alert('Error toggling status');
            }
        });
    });
});
</script>
@endsection