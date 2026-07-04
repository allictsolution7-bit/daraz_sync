@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Manage Sliders</h1>
            <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Slider
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Sliders</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="slidersTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="5%">Position</th>
                                <th width="15%">Image</th>
                                <th width="20%">Title</th>
                                <th width="20%">Description</th>
                                <th width="10%">Button</th>
                                <th width="10%">Status</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sliders-list">
                            @foreach ($sliders as $slider)
                                <tr data-slider-id="{{ $slider->id }}">
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $slider->position }}</span>
                                        <div class="btn-group-vertical mt-2">
                                            <button class="btn btn-sm btn-outline-secondary move-up"><i
                                                    class="fas fa-arrow-up"></i></button>
                                            <button class="btn btn-sm btn-outline-secondary move-down"><i
                                                    class="fas fa-arrow-down"></i></button>
                                        </div>
                                    </td>
                                    <td>
                                        <img src="{{ asset($slider->image) }}" alt="{{ $slider->image_alt }}"
                                            class="img-thumbnail" style="max-height: 70px;">
                                    </td>
                                    <td>{{ $slider->title }}</td>
                                    <td>{{ Str::limit($slider->description, 50) }}</td>
                                    <td>
                                        @if ($slider->button_text)
                                            <a href="{{ $slider->button_url }}" target="_blank"
                                                class="btn btn-sm btn-primary">
                                                {{ $slider->button_text }}
                                            </a>
                                        @else
                                            <span class="text-muted">No button</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $slider->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $slider->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.sliders.show', $slider) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.sliders.edit', $slider) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this slider?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Add loading overlay
            $('<div id="position-update-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999;"><div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); color:white; font-size:20px;"><i class="fas fa-spinner fa-spin"></i> Updating positions...</div></div>')
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
                let currentPosition = parseInt(row.find('.badge').text().trim());
                let swapPosition = parseInt(swapRow.find('.badge').text().trim());

                // If positions are not valid numbers, recalculate them based on index
                if (isNaN(currentPosition) || isNaN(swapPosition) || currentPosition > 9999 ||
                    swapPosition > 9999) {
                    // Reset all positions based on current DOM order
                    $('tr[data-slider-id]').each(function(idx) {
                        $(this).find('.badge').text(idx + 1);
                    });

                    // Get fresh position values
                    currentPosition = parseInt(row.find('.badge').text().trim());
                    swapPosition = parseInt(swapRow.find('.badge').text().trim());
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
