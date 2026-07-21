@extends('layouts.master')

@section('title', 'Landing Pages')

@section('styles')
<style>
    :root {
        --lp-primary: #4f46e5;
        --lp-primary-hover: #4338ca;
        --lp-secondary: #06b6d4;
        --lp-green: #10b981;
        --lp-danger: #ef4444;
        --lp-dark: #0f172a;
        --lp-surface: #ffffff;
        --lp-border: #e2e8f0;
        --lp-shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --lp-shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --lp-shadow-lg: 0 10px 25px -5px rgba(79,70,229,0.15);
        --lp-radius: 14px;
    }

    .lp-page-wrapper {
        background: #f1f5f9;
        margin: -15px -15px 0 -15px;
        padding: 24px;
        min-height: calc(100vh - 60px);
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* Hero Banner */
    .lp-hero-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
        color: white;
        border-radius: var(--lp-radius);
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: var(--lp-shadow-lg);
        position: relative;
        overflow: hidden;
    }

    .lp-hero-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 320px;
        height: 320px;
        background: radial-gradient(circle, rgba(6,182,212,0.25) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .lp-title-badge {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .lp-title-icon {
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

    .lp-meta-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .lp-chip {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        padding: 6px 14px;
        font-weight: 700;
        font-size: 13px;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .lp-chip:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
    }

    .lp-chip .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .btn-create-lp {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 12px 22px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 14px rgba(16,185,129,0.35);
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .btn-create-lp:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16,185,129,0.45);
        color: white;
    }

    /* Toolbar & Filters */
    .lp-toolbar-card {
        background: white;
        border: 1px solid var(--lp-border);
        border-radius: var(--lp-radius);
        padding: 14px 18px;
        margin-bottom: 20px;
        box-shadow: var(--lp-shadow-sm);
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        align-items: center;
        justify-content: space-between;
    }

    .lp-filter-group {
        display: flex;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 10px;
    }

    .lp-filter-btn {
        border: none;
        background: transparent;
        color: #64748b;
        border-radius: 8px;
        padding: 6px 14px;
        font-weight: 700;
        font-size: 13px;
        transition: all 0.2s ease;
    }

    .lp-filter-btn.active {
        background: white;
        color: var(--lp-primary);
        box-shadow: var(--lp-shadow-sm);
    }

    .lp-search-box {
        position: relative;
        min-width: 260px;
    }

    .lp-search-box input {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 8px 12px 8px 36px;
        font-size: 13px;
    }

    .lp-search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    /* Modern Table Container */
    .lp-table-card {
        background: white;
        border-radius: var(--lp-radius);
        border: 1px solid var(--lp-border);
        box-shadow: var(--lp-shadow-md);
        overflow: hidden;
    }

    .lp-table {
        margin-bottom: 0;
    }

    .lp-table th {
        background: #f8fafc;
        color: #334155;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .lp-table td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 13px;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }

    .lp-table tr:last-child td {
        border-bottom: none;
    }

    .lp-table tr:hover td {
        background: #f8fafc;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-pill.active {
        background: #dcfce7;
        color: #166534;
    }

    .status-pill.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .status-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-pill.active .dot { background: #16a34a; }
    .status-pill.inactive .dot { background: #dc2626; }

    .action-btn-group .btn {
        border-radius: 8px;
        padding: 5px 9px;
        font-size: 12px;
        transition: all 0.2s;
    }

    .action-btn-group .btn:hover {
        transform: translateY(-1px);
    }
</style>
@endsection

@section('content')
@php
$pagesCollection = method_exists($landingPages, 'getCollection')
? $landingPages->getCollection()
: (is_iterable($landingPages) ? collect($landingPages) : collect());
$activeCount = $pagesCollection->where('status', 1)->count();
$inactiveCount = $pagesCollection->where('status', 0)->count();
$totalLandingPages = method_exists($landingPages, 'total') ? $landingPages->total() : $pagesCollection->count();
@endphp

<div class="lp-page-wrapper">
    <!-- Hero Banner Card -->
    <div class="lp-hero-card d-flex justify-content-between align-items-center flex-wrap gap-4">
        <div>
            <div class="lp-title-badge mb-2">
                <div class="lp-title-icon">
                    <i class="fas fa-pager"></i>
                </div>
                <div>
                    <h3 class="mb-0 text-white font-weight-bold">Landing Page Manager</h3>
                    <p class="mb-0 text-white-50 small">Manage high-converting promotional pages & tracking stats</p>
                </div>
            </div>
            
            <div class="lp-meta-grid">
                <span class="lp-chip">
                    <span class="dot" style="background: #38bdf8;"></span>
                    Total: {{ $totalLandingPages }}
                </span>
                <span class="lp-chip">
                    <span class="dot" style="background: #34d399;"></span>
                    Active: {{ $activeCount }}
                </span>
                <span class="lp-chip">
                    <span class="dot" style="background: #f87171;"></span>
                    Inactive: {{ $inactiveCount }}
                </span>
                <span class="lp-chip">
                    <span class="dot" style="background: #a78bfa;"></span>
                    Orders: {{ number_format($landingOrdersCount ?? 0) }}
                </span>
                <span class="lp-chip">
                    <span class="dot" style="background: #34d399;"></span>
                    Sales: ৳{{ number_format($landingOrdersTotal ?? 0, 2) }}
                </span>
                <span class="lp-chip">
                    <span class="dot" style="background: #38bdf8;"></span>
                    Views: {{ number_format($landingViewsTotal ?? 0) }}
                </span>
            </div>
        </div>

        <div>
            <a href="{{ route('admin.landing-pages.create') }}" class="btn-create-lp">
                <i class="fas fa-plus"></i> Create Landing Page
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-3" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Toolbar & Search -->
    <div class="lp-toolbar-card">
        <div class="lp-filter-group">
            <button type="button" class="lp-filter-btn active" data-status="all">All ({{ $totalLandingPages }})</button>
            <button type="button" class="lp-filter-btn" data-status="active">Active ({{ $activeCount }})</button>
            <button type="button" class="lp-filter-btn" data-status="inactive">Inactive ({{ $inactiveCount }})</button>
        </div>
        <div class="lp-search-box">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" id="lp-search" class="form-control" placeholder="Search title, slug or product...">
        </div>
    </div>

    <!-- Table Container Card -->
    <div class="lp-table-card">
        <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle" id="landing-pages-table">
                            <thead>
                                <tr>
                                    <th width="50px"><i class="fas fa-arrows-alt"></i></th>
                                    <th>Title</th>
                                    <th>Product</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Views (T/U)</th>
                                    <th>Orders</th>
                                    <th>Order Amount</th>
                                    <th>Created Info</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-tbody">
                                @forelse($landingPages as $landingPage)
                                <tr class="lp-row lp-row-{{ $landingPage->status ? 'active' : 'inactive' }}"
                                    data-id="{{ $landingPage->id }}"
                                    data-status="{{ $landingPage->status ? 'active' : 'inactive' }}"
                                    data-keywords="{{ Str::lower($landingPage->title.' '.($landingPage->product->title ?? '').' '.$landingPage->slug.' '.($landingPage->heading ?? '')) }}"
                                    style="cursor: move;">
                                    <td class="text-center drag-handle align-middle">
                                        <i class="fas fa-grip-vertical text-muted"></i>
                                    </td>
                                    <td>
                                        <strong class="landing-page-title">{{ $landingPage->title }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $landingPage->heading }}</small>
                                        <div class="d-flex action-btn-group ml-2" style="gap: 5px;">
                                            <a href="{{ route('landing.page', $landingPage->slug) }}" target="_blank"
                                                class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-dark copy-link-btn"
                                                data-url="{{ route('landing.page', $landingPage->slug) }}"
                                                title="Copy Link">
                                                <i class="fas fa-link"></i>
                                            </button>
                                            <a href="{{ route('admin.landing-pages.edit', $landingPage) }}"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.landing-pages.copy', $landingPage) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirm('Are you sure you want to copy this landing page?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary" title="Duplicate Landing Page">
                                                    <i class="fas fa-clone"></i>
                                                </button>
                                            </form>
                                            <button type="button"
                                                class="btn btn-sm btn-{{ $landingPage->status ? 'secondary' : 'success' }} toggle-status"
                                                data-id="{{ $landingPage->id }}"
                                                data-status="{{ $landingPage->status }}"
                                                title="{{ $landingPage->status ? 'Deactivate' : 'Activate' }}">
                                                <i
                                                    class="fas fa-{{ $landingPage->status ? 'pause' : 'play' }}"></i>
                                            </button>
                                            <form action="{{ route('admin.landing-pages.destroy', $landingPage) }}"
                                                method="POST" style="display: inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this landing page?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-limit">
                                        @if ($landingPage->product)
                                        <a href="{{ route('product.single', ['id' => $landingPage->product->id, 'slug' => $landingPage->product->slug]) }}"
                                            target="_blank"
                                            title="{{ $landingPage->product->title }}">
                                            {{ Str::limit($landingPage->product->title, 50) }}
                                        </a>
                                        @else
                                        <span class="text-danger">Product not found</span>
                                        @endif
                                    </td>
                                    <td class="text-limit">
                                        <code title="{{ $landingPage->slug }}">{{ Str::limit($landingPage->slug, 50) }}</code>
                                    </td>
                                    <td>
                                        <span class="status-pill {{ $landingPage->status ? 'active' : 'inactive' }}">
                                            <span class="dot"></span>
                                            <span class="status-label">{{ $landingPage->status ? 'Active' : 'Inactive' }}</span>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ number_format($landingPage->views_total ?? 0) }} / {{ number_format($landingPage->views_unique ?? 0) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $landingPage->orders_count ?? 0 }}
                                    </td>
                                    <td class="text-right">
                                        ৳{{ number_format($landingPage->orders_total_amount ?? 0, 2) }}
                                    </td>

                                    <td>
                                        @if ($landingPage->creator)
                                        {{ $landingPage->creator->name }}
                                        @else
                                        <span class="text-muted">Unknown</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $landingPage->created_at->format('M d, Y H:i') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr id="empty-row">
                                    <td colspan="9" class="text-center">No landing pages found.</td>
                                </tr>
                                @endforelse
                                @if ($pagesCollection->count())
                                <tr id="lp-filter-empty" class="d-none">
                                    <td colspan="9" class="text-center text-muted">No landing pages match the current filters.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    @if ($landingPages->hasPages())
                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $landingPages->links() }}
                    </div>
                    @endif
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery UI for Sortable -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    $(document).ready(function() {
        const $rows = $('#sortable-tbody tr[data-id]');
        const $filterButtons = $('.lp-filter-btn');
        const $searchInput = $('#lp-search');
        const $clearSearch = $('#lp-clear-search');
        const $visibleCount = $('#lp-visible-count');
        const $filterEmptyRow = $('#lp-filter-empty');

        function applyFilters() {
            const activeStatus = $filterButtons.filter('.active').data('status');
            const query = ($searchInput.val() || '').toLowerCase().trim();
            let visible = 0;

            $rows.each(function() {
                const row = $(this);
                const matchesStatus = activeStatus === 'all' || row.data('status') === activeStatus;
                const keywords = (row.data('keywords') || '').toString();
                const matchesQuery = !query || keywords.indexOf(query) !== -1;
                if (matchesStatus && matchesQuery) {
                    row.show();
                    visible++;
                } else {
                    row.hide();
                }
            });

            if ($filterEmptyRow.length) {
                if (visible === 0) {
                    $filterEmptyRow.removeClass('d-none');
                } else {
                    $filterEmptyRow.addClass('d-none');
                }
            }

            $visibleCount.text(visible);
        }

        $filterButtons.on('click', function() {
            $filterButtons.removeClass('active');
            $(this).addClass('active');
            applyFilters();
        });

        $searchInput.on('input', function() {
            applyFilters();
        });

        $clearSearch.on('click', function() {
            $searchInput.val('');
            applyFilters();
        });

        // Initialize sortable for drag and drop
        $('#sortable-tbody').sortable({
            handle: '.drag-handle',
            items: '> tr[data-id]',
            placeholder: 'ui-sortable-placeholder',
            helper: function(e, tr) {
                var $originals = tr.children();
                var $helper = tr.clone();
                $helper.children().each(function(index) {
                    $(this).width($originals.eq(index).width());
                });
                return $helper;
            },
            update: function(event, ui) {
                // Get the new order
                var positions = {};
                var position = 1;

                $('#sortable-tbody tr[data-id]').each(function() {
                    var id = $(this).data('id');
                    positions[id] = position;
                    // Update the position cell display
                    $(this).find('.position-cell').text('#' + position);
                    position++;
                });

                // Send AJAX request to update positions
                $.ajax({
                    url: '/admin/landing-pages/update-positions',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        positions: positions
                    },
                    success: function(response) {
                        if (response.success) {
                            // Optional: Show a brief success notification
                            console.log('Positions updated successfully');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating positions:', error);
                        alert('Error updating positions. Please refresh the page.');
                    }
                });
            }
        });

        // Copy link to clipboard
        $('.copy-link-btn').click(function() {
            const button = $(this);
            const url = button.data('url');
            const icon = button.find('i');
            const originalIconClass = icon.attr('class');

            // Use Clipboard API
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function() {
                    // Add success state
                    button.addClass('copied');
                    icon.removeClass('fa-link').addClass('fa-check');

                    // Reset after 2 seconds
                    setTimeout(function() {
                        button.removeClass('copied');
                        icon.attr('class', originalIconClass);
                    }, 2000);
                }).catch(function(err) {
                    console.error('Failed to copy:', err);
                    alert('Failed to copy link');
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                document.body.appendChild(textArea);
                textArea.select();

                try {
                    document.execCommand('copy');
                    // Add success state
                    button.addClass('copied');
                    icon.removeClass('fa-link').addClass('fa-check');

                    // Reset after 2 seconds
                    setTimeout(function() {
                        button.removeClass('copied');
                        icon.attr('class', originalIconClass);
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                    alert('Failed to copy link');
                }

                document.body.removeChild(textArea);
            }
        });

        // Toggle status
        $('.toggle-status').click(function() {
            const button = $(this);
            const id = button.data('id');

            $.ajax({
                url: `/admin/landing-pages/${id}/toggle-status`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Update button appearance
                        const newStatus = response.status;
                        button.data('status', newStatus);
                        button.removeClass('btn-success btn-secondary').addClass(newStatus ?
                            'btn-secondary' : 'btn-success');
                        button.find('i').removeClass('fa-play fa-pause').addClass(
                            newStatus ? 'fa-pause' : 'fa-play');
                        button.attr('title', newStatus ? 'Deactivate' : 'Activate');

                        // Update status badge and row styling
                        const row = button.closest('tr');
                        const statusCell = row.find('.status-pill');
                        row.attr('data-status', newStatus ? 'active' : 'inactive');
                        row.removeClass('lp-row-active lp-row-inactive').addClass(
                            newStatus ? 'lp-row-active' : 'lp-row-inactive'
                        );
                        statusCell.removeClass('active inactive').addClass(newStatus ? 'active' : 'inactive');
                        statusCell.find('.status-label').text(newStatus ? 'Active' : 'Inactive');

                        // Show success message
                        alert('Status updated successfully!');
                        applyFilters();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Toggle status error:', error);
                    console.error('Response:', xhr.responseText);
                    alert('Error updating status. Please try again.');
                }
            });
        });

        applyFilters();
    });
</script>
@endsection
