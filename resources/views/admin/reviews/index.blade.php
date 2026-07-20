@extends('layouts.master')

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
            background: #0f172a !important;
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
        .text-wrap-column {
            white-space: normal !important;
            min-width: 200px;
            max-width: 320px;
            word-break: break-word;
        }

        /* Reviewer Avatar & Click Trigger */
        .reviewer-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #e2e8f0;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #4f46e5;
            flex-shrink: 0;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .reviewer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .reviewer-avatar:hover {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .reviewer-short {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1e293b;
            cursor: pointer;
            max-width: 130px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            border-bottom: 1px dashed #94a3b8;
        }
        .reviewer-short:hover {
            color: #4f46e5;
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
        .btn-action-edit:hover {
            background: rgba(59, 130, 246, 0.12);
            color: #2563eb;
        }
        .btn-action-delete:hover {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }

        /* Reviewer Modal Styles */
        #reviewerModal .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 25px 60px rgba(0,0,0,0.12);
        }
        #reviewerModal .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 20px 24px;
        }
        #reviewerModal .modal-body {
            padding: 24px;
        }
        .reviewer-modal-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e2e8f0;
        }
        .reviewer-modal-initials {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }
        .reviewer-detail-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .reviewer-detail-row:last-child {
            border-bottom: none;
        }
        .reviewer-detail-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #94a3b8;
            min-width: 110px;
        }
        .reviewer-detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }
    </style>
@endsection

@section('content')
    <div class="container categories-container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="custom-breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin') }}"><i class="fa-solid fa-house me-1"></i> Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reviews</li>
            </ol>
        </nav>

        <div class="glass-card">
            <!-- Header Section -->
            <div class="row align-items-center mb-4">
                <div class="col-md-6">
                    <h1 class="page-header-title">Customer Reviews</h1>
                    <p class="page-header-subtitle">Moderate customer reviews, product ratings, dates, and active publishing status.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="action-toolbar">
                        <a href="{{ route('admin.reviews.create') }}" class="btn-add-category">
                            <i class="fa-solid fa-plus"></i> Add New Review
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Alert -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; font-family: 'Outfit', sans-serif;">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Table Section -->
            <div class="premium-table-wrapper">
                <table class="table premium-table">
                    <thead>
                        <tr>
                            <th width="80">ID</th>
                            <th>Reviewer</th>
                            <th>Product Name</th>
                            <th width="150">Rating</th>
                            <th width="140">Date</th>
                            <th width="120">Status</th>
                            <th width="120" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            @php
                                $nameParts = explode(' ', trim($review->reviewer_name ?? ''));
                                $initials = strtoupper(substr($nameParts[0] ?? '?', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                                $shortName = strlen($review->reviewer_name) > 14
                                    ? substr($review->reviewer_name, 0, 12) . '…'
                                    : $review->reviewer_name;
                            @endphp
                            <tr>
                                <td class="font-monospace text-muted">#{{ $review->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2"
                                         style="cursor:pointer;"
                                         onclick="openReviewerModal({
                                            name: {{ json_encode($review->reviewer_name) }},
                                            image: {{ json_encode($review->reviewer_image ? asset($review->reviewer_image) : null) }},
                                            initials: {{ json_encode($initials) }},
                                            product: {{ json_encode($review->product_name) }},
                                            rating: {{ $review->rating }},
                                            date: {{ json_encode($review->review_date->format('M d, Y')) }},
                                            status: {{ json_encode($review->is_active ? 'Active' : 'Inactive') }}
                                         })">
                                        <div class="reviewer-avatar" title="View {{ $review->reviewer_name }} details">
                                            @if($review->reviewer_image)
                                                <img src="{{ asset($review->reviewer_image) }}" alt="{{ $review->reviewer_name }}">
                                            @else
                                                {{ $initials }}
                                            @endif
                                        </div>
                                        <span class="reviewer-short" title="{{ $review->reviewer_name }}">{{ $shortName }}</span>
                                    </div>
                                </td>
                                <td class="fw-medium text-slate-700 text-wrap-column">{{ $review->product_name }}</td>
                                <td>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fa-solid fa-star"></i>
                                            @elseif($i - 0.5 <= $review->rating)
                                                <i class="fa-solid fa-star-half-stroke"></i>
                                            @else
                                                <i class="fa-regular fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </td>
                                <td class="text-secondary">{{ $review->review_date->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ route('admin.reviews.toggle-status', $review) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="status-pill-btn {{ $review->is_active ? 'status-pill-btn-active' : 'status-pill-btn-inactive' }}" title="Click to Toggle Status">
                                            {{ $review->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1" role="group">
                                        <a href="{{ route('admin.reviews.edit', $review) }}"
                                           class="btn-action-custom btn-action-edit" title="Edit Review">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-custom btn-action-delete"
                                                    title="Delete Review" onclick="return confirm('Are you sure you want to delete this review?');">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-regular fa-comments d-block mb-3" style="font-size: 2rem; color: #cbd5e1;"></i>
                                    No customer reviews found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Wrapper -->
            @if ($reviews->hasPages())
                <div class="mt-4 d-flex justify-content-end">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Reviewer Detail Modal -->
    <div class="modal fade" id="reviewerModal" tabindex="-1" aria-labelledby="reviewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="reviewerModalLabel" style="font-family:'Outfit',sans-serif;">Reviewer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-family:'Outfit',sans-serif;">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div id="modal-avatar-wrapper"></div>
                        <div>
                            <h6 class="mb-0 fw-bold" id="modal-reviewer-name" style="font-size:1.1rem;color:#0f172a;"></h6>
                            <small class="text-muted">Customer</small>
                        </div>
                    </div>
                    <div>
                        <div class="reviewer-detail-row">
                            <span class="reviewer-detail-label"><i class="fas fa-box-open me-1 text-primary"></i> Product</span>
                            <span class="reviewer-detail-value" id="modal-reviewer-product"></span>
                        </div>
                        <div class="reviewer-detail-row">
                            <span class="reviewer-detail-label"><i class="fas fa-star me-1 text-warning"></i> Rating</span>
                            <span class="reviewer-detail-value" id="modal-reviewer-rating"></span>
                        </div>
                        <div class="reviewer-detail-row">
                            <span class="reviewer-detail-label"><i class="fas fa-calendar me-1 text-info"></i> Date</span>
                            <span class="reviewer-detail-value" id="modal-reviewer-date"></span>
                        </div>
                        <div class="reviewer-detail-row">
                            <span class="reviewer-detail-label"><i class="fas fa-circle me-1 text-success"></i> Status</span>
                            <span class="reviewer-detail-value" id="modal-reviewer-status"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function openReviewerModal(data) {
    // Set avatar
    const avatarWrapper = document.getElementById('modal-avatar-wrapper');
    if (data.image) {
        avatarWrapper.innerHTML = `<img src="${data.image}" class="reviewer-modal-avatar" alt="${data.name}">`;
    } else {
        avatarWrapper.innerHTML = `<div class="reviewer-modal-initials">${data.initials}</div>`;
    }

    document.getElementById('modal-reviewer-name').textContent = data.name;
    document.getElementById('modal-reviewer-product').textContent = data.product;
    document.getElementById('modal-reviewer-date').textContent = data.date;
    document.getElementById('modal-reviewer-status').textContent = data.status;

    // Build star rating
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= Math.floor(data.rating)) {
            starsHtml += '<i class="fa-solid fa-star text-warning"></i>';
        } else if (i - 0.5 <= data.rating) {
            starsHtml += '<i class="fa-solid fa-star-half-stroke text-warning"></i>';
        } else {
            starsHtml += '<i class="fa-regular fa-star text-warning"></i>';
        }
    }
    starsHtml += ` <span class="ms-1 text-muted">(${data.rating}/5)</span>`;
    document.getElementById('modal-reviewer-rating').innerHTML = starsHtml;

    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('reviewerModal'));
    modal.show();
}
</script>
@endpush