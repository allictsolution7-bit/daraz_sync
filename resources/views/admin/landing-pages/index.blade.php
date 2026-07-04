@extends('layouts.master')

@section('title', 'Landing Pages')

@section('styles')
<style>
    :root {
        --lp-primary: #197A94;
        --lp-secondary: #1E3A8A;
        --lp-green: #10B981;
        --lp-danger: #e05353;
        --lp-surface: #f2f8f7;
        --lp-text: #1f2937;
    }

    .lp-card {
        border: none;
        background: transparent;
    }

    .lp-hero {
        background: var(--lp-surface);
        border: 1px solid #d8e7e2;
        border-radius: 14px;
        padding: 16px 18px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 6px 16px rgba(17, 24, 39, 0.06);
    }

    .lp-hero .eyebrow {
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
        color: #475467;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .lp-hero h3 {
        margin: 0 0 4px;
        font-weight: 800;
        color: var(--lp-text);
    }

    .lp-hero p {
        margin-bottom: 8px;
        color: #4b5563;
    }

    .lp-hero-actions {
        text-align: right;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .lp-mini-hint {
        font-size: 12px;
        color: #4b5563;
    }

    .lp-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 6px;
    }

    .lp-chip {
        background: #ffffff;
        border: 1px solid #d8e7e2;
        border-radius: 10px;
        padding: 2px 10px;
        font-weight: 700;
        color: #0f172a;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: none;
    }

    .lp-chip .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .btn-gradient {
        background: var(--lp-primary);
        border: none;
        color: #fff;
        font-weight: 700;
        padding: 10px 16px;
        border-radius: 10px;
        box-shadow: none;
    }

    .btn-gradient:hover {
        color: #fff;
        background: #0f4c75;
    }

    .lp-toolbar {
        background: #ffffff;
        border: 1px solid #d8e7e2;
        border-radius: 10px;
        padding: 10px;
        margin: 12px 0;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
        box-shadow: none;
    }

    .lp-filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .lp-filter-btn {
        border: 1px solid #d8e7e2;
        background: #f8fafa;
        color: #0f4c75;
        border-radius: 7px;
        padding: 4px 10px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .lp-filter-btn.active {
        background: var(--lp-primary);
        color: #fff;
        border-color: var(--lp-primary);
        box-shadow: none;
    }

    .lp-toolbar-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex: 1 1 auto;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .lp-search {
        position: relative;
        flex: 1 1 260px;
        min-width: 220px;
    }

    .lp-search input {
        width: 100%;
        padding: 4px 36px 4px 36px;
        border-radius: 10px;
        border: 1px solid #d8e7e2;
        background: #ffffff;
        font-weight: 600;
        color: #0f172a;
    }

    .lp-search .fa-search {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
    }

    .lp-search .clear-search {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .lp-hint {
        color: #475467;
        font-weight: 600;
        margin: 0 0 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    #landing-pages-table {
        background: #fff;
        border-radius: 7px;
        overflow: hidden;
        border: 1px solid #d8e7e2;
        box-shadow: 0 6px 18px rgba(17, 24, 39, 0.06);
    }

    #landing-pages-table thead th {
        background: #197A94;
        color: #fff;
        border: none;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-size: 0.85rem;
    }

    #landing-pages-table tbody tr {
        transition: all 0.15s ease;
    }

    #landing-pages-table tbody tr:hover {
        background: #f8fbfb;
        box-shadow: none;
    }

    #landing-pages-table tbody td {
        vertical-align: middle;
        border-color: #eef2f7;
    }

    .lp-row-active {
        background: #f6fffb;
        border-left: 3px solid var(--lp-green);
    }

    .lp-row-inactive {
        background: #f9fbfc;
        border-left: 3px solid #cbd5e1;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 3px 12px;
        border-radius: 999px;
        font-weight: 600;
        letter-spacing: 0.01em;
        font-size: 0.9rem;
        border: 1px solid transparent;
    }

    .status-pill .dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .status-pill.active {
        background: #ecfdf3;
        color: #0f915c;
        border-color: #c5f1de;
    }

    .status-pill.active .dot {
        background: #16b77e;
    }

    .status-pill.inactive {
        background: #f9fafb;
        color: #475467;
        border-color: #e5e7eb;
    }

    .status-pill.inactive .dot {
        background: #cbd5e1;
    }

    .position-badge {
        padding: 6px 10px;
        border-radius: 10px;
        background: #f8fafa;
        border: 1px solid #d8e7e2;
        font-weight: 800;
        color: #0f4c75;
    }

    .drag-handle {
        cursor: move;
        color: #94a3b8;
    }

    #sortable-tbody tr:hover .drag-handle i {
        color: #197a94 !important;
    }

    .ui-sortable-helper {
        display: table;
        background-color: #f8f9fa;
        opacity: 0.9;
    }

    .ui-sortable-placeholder {
        background-color: #e9ecef;
        visibility: visible !important;
        height: 50px !important;
    }

    .action-btn-group .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px !important;
        transition: all 0.15s ease;
        border: 1px solid #d8e7e2;
        background: #fff;
        color: #0f172a;
    }

    .action-btn-group .btn i {
        font-size: 0.9rem !important;
    }

    .action-btn-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: none;
        background: #f8fafa;
    }

    .action-btn-group .btn-info {
        background: #eef7ff;
        border-color: #d8e7e2;
        color: #197a94;
    }

    .action-btn-group .btn-dark {
        background: #edf2ff;
        border-color: #d8e7e2;
        color: #1e3a8a;
    }

    .action-btn-group .btn-warning {
        background: #fff7ec;
        border-color: #ffe7c2;
        color: #b45309;
    }

    .action-btn-group .btn-primary {
        background: #f6fbff;
        border-color: #d8e7e2;
        color: #0f4c75;
    }

    .action-btn-group .btn-success {
        background: #ecfdf3;
        border-color: #c5f1de;
        color: #0f915c;
    }

    .action-btn-group .btn-secondary {
        background: #f8fafa;
        border-color: #e2e8f0;
        color: #334155;
    }

    .action-btn-group .btn-danger {
        background: #fdecec;
        border-color: #f9d1d1;
        color: #b91c1c;
    }

    .copy-link-btn.copied {
        background: #dcfce7 !important;
        border-color: #bbf7d0 !important;
        color: #0f766e !important;
    }

    .text-limit {
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
    }

    .landing-page-title {
        font-size: 14px;
        margin-bottom: 5px !important;
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    @media (min-width: 768px) {
        .text-limit {
            max-width: 220px;
        }
    }

    @media (max-width: 767px) {

        .lp-hero,
        .lp-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .lp-hero-actions {
            width: 100%;
            text-align: left;
        }

        .lp-toolbar-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .text-limit {
            max-width: none;
        }

        .landing-page-title {
            max-width: none;
        }
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
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card lp-card">
                <div class="card-body">
                    <div class="lp-hero">
                        <div>
                            <div class="eyebrow">Landing Pages</div>
                            <h3>Conversion-ready pages, organized</h3>
                            <p class="mb-0">Monitor status, copy links, and drag to reprioritize from one clean view.</p>
                            <div class="lp-meta">
                                <span class="lp-chip">
                                    <span class="dot" style="background: var(--lp-primary);"></span>
                                    Total: {{ $totalLandingPages }}
                                </span>
                                <span class="lp-chip">
                                    <span class="dot" style="background: var(--lp-green);"></span>
                                    Active: {{ $activeCount }}
                                </span>
                                <span class="lp-chip">
                                    <span class="dot" style="background: var(--lp-danger);"></span>
                                    Inactive: {{ $inactiveCount }}
                                </span>
                                <span class="lp-chip">
                                    <span class="dot" style="background: var(--lp-secondary);"></span>
                                    Orders: {{ number_format($landingOrdersCount ?? 0) }}
                                </span>
                                <span class="lp-chip">
                                    <span class="dot" style="background: var(--lp-green);"></span>
                                    Sales: ৳{{ number_format($landingOrdersTotal ?? 0, 2) }}
                                </span>
                                <span class="lp-chip">
                                    <span class="dot" style="background: #0ea5e9;"></span>
                                    Views: {{ number_format($landingViewsTotal ?? 0) }}
                                </span>
                                <span class="lp-chip">
                                    <span class="dot" style="background: #8b5cf6;"></span>
                                    Unique: {{ number_format($landingViewsUnique ?? 0) }}
                                </span>
                            </div>
                        </div>
                        <div class="lp-hero-actions">
                            <a href="{{ route('admin.landing-pages.create') }}" class="btn btn-gradient">
                                <i class="fas fa-plus mr-1"></i> Create New Landing Page
                            </a>
                            <div class="lp-mini-hint">
                                Drag the grip icon to reorder. Changes save instantly.
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="lp-toolbar">
                        <div class="lp-filter-group">
                            <button type="button" class="lp-filter-btn active" data-status="all">All</button>
                            <button type="button" class="lp-filter-btn" data-status="active">Active ({{ $activeCount }})</button>
                            <button type="button" class="lp-filter-btn" data-status="inactive">Inactive ({{ $inactiveCount }})</button>
                        </div>
                        <div class="lp-toolbar-actions">
                            <div class="lp-search">
                                <i class="fas fa-search"></i>
                                <input type="text" id="lp-search" placeholder="Search title, slug or product...">
                                <span class="clear-search" id="lp-clear-search">Clear</span>
                            </div>
                        </div>
                    </div>

                    <div class="lp-hint">
                        <span>Showing <strong id="lp-visible-count">{{ $pagesCollection->count() }}</strong> of {{ $pagesCollection->count() }} loaded items</span>
                        <span class="text-muted">Tip: Hover a row for emphasis and use the grip to drag.</span>
                    </div>

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
                    </div>

                    @if ($landingPages->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $landingPages->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
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
