@extends('layouts.master')

@section('title', 'Manage Sliders')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #sliders-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
    }

    /* ── HEADER PANEL ── */
    .hdr-panel {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .hdr-title h4 { font-size: 1.3rem; font-weight: 800; color: #0f172a; margin: 0 0 4px; }
    .hdr-title p { font-size: 0.85rem; color: #64748b; margin: 0; }

    .btn-hdr {
        padding: 9px 18px;
        border-radius: 10px;
        font-size: 0.825rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-hdr.primary { background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
    .btn-hdr.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(99,102,241,0.4); color: #fff; }

    /* ── ALERTS ──────────────────────────────────── */
    .alert-premium {
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 20px;
        display: flex; align-items: flex-start; gap: 10px;
        position: relative;
    }
    .alert-ok-p  { background: linear-gradient(135deg,#f0fdf4,#dcfce7); border: 1px solid #86efac; color: #15803d; }
    .alert-premium .close-btn {
        position: absolute; top: 12px; right: 14px;
        background: none; border: none; cursor: pointer;
        color: inherit; opacity: 0.6; font-size: 1rem;
        padding: 0; line-height: 1;
    }

    /* ── STATS CARDS ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) { .stat-grid { grid-template-columns: 1fr; } }
    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.08); transform: translateY(-2px); }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }
    .stat-icon.si-indigo { background: #e0e7ff; color: #4338ca; }
    .stat-icon.si-green  { background: #dcfce7; color: #15803d; }
    .stat-icon.si-red    { background: #fee2e2; color: #b91c1c; }
    .stat-label { font-size: 0.76rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
    .stat-value { font-size: 1.6rem; font-weight: 800; color: #0f172a; line-height: 1; }

    /* ── TABLE CARD ────────────────────────── */
    .table-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
    }
    .table-card-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
        padding: 18px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .table-card-header h2 {
        font-size: 1rem;
        font-weight: 800;
        color: #fff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .table-card-header .hdr-icon {
        width: 36px; height: 36px;
        background: rgba(255,255,255,0.15);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: #fff;
        backdrop-filter: blur(4px);
    }

    /* ── DATA TABLE ─────────────────────────────── */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f8fafc; border-bottom: 2px solid #e2e8f0; }
    .data-table thead th {
        padding: 14px 18px;
        font-size: 0.72rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        text-align: left;
        white-space: nowrap;
    }
    .data-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }
    .data-table tbody tr:hover { background: #fafbff; }
    .data-table tbody tr:last-child { border-bottom: none; }
    .data-table td {
        padding: 14px 18px;
        font-size: 0.85rem;
        color: #334155;
        vertical-align: middle;
    }

    /* ── BADGES ── */
    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }
    .bp-active   { background: #dcfce7; color: #15803d; }
    .bp-inactive { background: #fee2e2; color: #b91c1c; }
    .bp-pos      { background: #e0e7ff; color: #4338ca; }

    /* ── BUTTON LINK ── */
    .btn-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 99px;
        font-size: 0.76rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #ede9fe; color: #6d28d9;
    }
    .btn-pill:hover { background: #6d28d9; color: #fff; }

    /* ── ACTION BUTTONS ── */
    .row-actions { display: flex; gap: 5px; align-items: center; }
    .icon-btn {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.825rem;
        cursor: pointer;
        transition: all 0.18s ease;
        text-decoration: none;
    }
    .icon-btn:hover { border-color: #a5b4fc; background: #ede9fe; color: #4338ca; }
    .icon-btn.ib-delete:hover { border-color: #fca5a5; background: #fef2f2; color: #dc2626; }

    .position-controls {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        margin-top: 6px;
    }
    .pos-btn {
        width: 26px; height: 26px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #475569;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.72rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .pos-btn:hover { border-color: #a5b4fc; background: #ede9fe; color: #4338ca; }

    .preview-img {
        max-height: 54px;
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div id="sliders-page" class="container-fluid px-4 py-4">

    @if (session('success'))
        <div class="alert-premium alert-ok-p">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- Header Banner --}}
    <div class="hdr-panel">
        <div class="hdr-title">
            <h4>Manage Sliders</h4>
            <p>Customize banner images, text, links, and positioning order on your homepage</p>
        </div>
        <a href="{{ route('admin.sliders.create') }}" class="btn-hdr primary">
            <i class="fas fa-plus"></i> Add New Slider
        </a>
    </div>

    {{-- Stats Row --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon si-indigo"><i class="fas fa-images"></i></div>
            <div>
                <div class="stat-label">Total Sliders</div>
                <div class="stat-value">{{ $sliders->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-label">Active Banners</div>
                <div class="stat-value">{{ $sliders->where('status', 1)->count() }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-red"><i class="fas fa-pause-circle"></i></div>
            <div>
                <div class="stat-label">Inactive</div>
                <div class="stat-value">{{ $sliders->where('status', 0)->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="table-card">
        <div class="table-card-header">
            <h2>
                <span class="hdr-icon"><i class="fas fa-images"></i></span>
                All Sliders
            </h2>
        </div>

        <div style="overflow-x:auto;">
            <table class="data-table" id="slidersTable">
                <thead>
                    <tr>
                        <th style="width:100px;text-align:center;">Position</th>
                        <th style="width:120px;">Image</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Button text</th>
                        <th>Status</th>
                        <th style="width:120px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="sliders-list">
                    @foreach ($sliders as $slider)
                        <tr data-slider-id="{{ $slider->id }}">
                            <td style="text-align:center;">
                                <span class="badge-pill bp-pos">{{ $slider->position }}</span>
                                <div class="position-controls">
                                    <button class="pos-btn move-up" title="Move Up"><i class="fas fa-arrow-up"></i></button>
                                    <button class="pos-btn move-down" title="Move Down"><i class="fas fa-arrow-down"></i></button>
                                </div>
                            </td>
                            <td>
                                <img src="{{ asset($slider->image) }}" alt="{{ $slider->image_alt }}" class="preview-img">
                            </td>
                            <td><span style="font-weight:700;color:#0f172a;">{{ $slider->title }}</span></td>
                            <td><span style="color:#64748b;font-size:0.8rem;">{{ Str::limit($slider->description, 50) }}</span></td>
                            <td>
                                @if ($slider->button_text)
                                    <a href="{{ $slider->button_url }}" target="_blank" class="btn-pill">
                                        {{ $slider->button_text }} <i class="fas fa-external-link-alt" style="font-size:0.68rem;margin-left:2px;"></i>
                                    </a>
                                @else
                                    <span style="color:#cbd5e1;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($slider->status)
                                    <span class="badge-pill bp-active"><i class="fas fa-check"></i> Active</span>
                                @else
                                    <span class="badge-pill bp-inactive"><i class="fas fa-pause"></i> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('admin.sliders.show', $slider) }}" class="icon-btn" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="icon-btn" title="Edit Slider">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="icon-btn ib-delete" title="Delete Slider"
                                            onclick="return confirm('Are you sure you want to delete this slider?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Add loading overlay
            $('<div id="position-update-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;"><div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:white; font-size:20px; font-family:\'Plus Jakarta Sans\',sans-serif; font-weight:700;"><i class="fas fa-spinner fa-spin"></i> Updating positions...</div></div>')
                .appendTo('body');

            $('.move-up, .move-down').click(function(e) {
                e.preventDefault();
                const button = $(this);
                const row = button.closest('tr');
                const sliderId = row.data('slider-id');
                const isUp = button.hasClass('move-up');

                // Get all rows
                const rows = $('tr[data-slider-id]');
                const currentIndex = rows.index(row);

                // Check if movement is possible
                if ((isUp && currentIndex === 0) || (!isUp && currentIndex === rows.length - 1)) {
                    return;
                }

                // Get the row to swap with
                const swapRow = isUp ? rows.eq(currentIndex - 1) : rows.eq(currentIndex + 1);
                const swapSliderId = swapRow.data('slider-id');

                // Get the current position from the badge - ensure it's a clean integer
                let currentPosition = parseInt(row.find('.badge-pill').text().trim());
                let swapPosition = parseInt(swapRow.find('.badge-pill').text().trim());

                // If positions are not valid numbers, recalculate them based on index
                if (isNaN(currentPosition) || isNaN(swapPosition) || currentPosition > 9999 ||
                    swapPosition > 9999) {
                    // Reset all positions based on current DOM order
                    $('tr[data-slider-id]').each(function(idx) {
                        $(this).find('.badge-pill').text(idx + 1);
                    });

                    // Get fresh position values
                    currentPosition = parseInt(row.find('.badge-pill').text().trim());
                    swapPosition = parseInt(swapRow.find('.badge-pill').text().trim());
                }

                // Disable buttons during the update to prevent multiple clicks
                $('.move-up, .move-down').prop('disabled', true);

                // Show loading overlay
                $('#position-update-overlay').fadeIn(200);

                // Prepare positions data with actual position values
                const positions = {};
                positions[sliderId] = swapPosition;
                positions[swapSliderId] = currentPosition;

                // Send AJAX request to update positions
                $.ajax({
                    url: '{{ route('admin.sliders.update-positions') }}',
                    method: 'POST',
                    data: {
                        positions: positions,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Instead of updating the DOM, reload the page
                            window.location.reload();
                        } else {
                            // Show error message if success is false
                            alert('Failed to update positions: ' + (response.message ||
                                'Unknown error'));
                            // Hide loading overlay on error
                            $('#position-update-overlay').fadeOut(200);
                            // Re-enable buttons after error
                            $('.move-up, .move-down').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error updating positions:', xhr);
                        let errorMessage = 'Failed to update positions. Please try again.';

                        // Try to get more specific error message
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        alert(errorMessage);
                        // Hide loading overlay on error
                        $('#position-update-overlay').fadeOut(200);
                        // Re-enable buttons after error
                        $('.move-up, .move-down').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endsection
