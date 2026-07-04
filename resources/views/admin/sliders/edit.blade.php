@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Slider</h1>
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Sliders
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Slider Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $slider->title) }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $slider->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="button_text">Button Text</label>
                                <input type="text" class="form-control @error('button_text') is-invalid @enderror"
                                    id="button_text" name="button_text"
                                    value="{{ old('button_text', $slider->button_text) }}">
                                @error('button_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="button_url">Button URL</label>
                                <input type="text" class="form-control @error('button_url') is-invalid @enderror"
                                    id="button_url" name="button_url" value="{{ old('button_url', $slider->button_url) }}">
                                @error('button_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Slider Image</label>
                                <input type="file" class="form-control-file @error('image') is-invalid @enderror"
                                    id="image" name="image">
                                <small class="form-text text-muted">Recommended size: 1920x550 pixels. Leave empty to keep
                                    current image.</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($slider->image)
                                    <div class="mt-2">
                                        <p>Current Image:</p>
                                        <img src="{{ asset($slider->image) }}" alt="{{ $slider->image_alt }}"
                                            class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="image_alt">Image Alt Text</label>
                                <input type="text" class="form-control @error('image_alt') is-invalid @enderror"
                                    id="image_alt" name="image_alt" value="{{ old('image_alt', $slider->image_alt) }}">
                                @error('image_alt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="overlay_color">Overlay Color</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('overlay_color') is-invalid @enderror"
                                        id="overlay_color" name="overlay_color"
                                        value="{{ old('overlay_color', $slider->overlay_color) }}">
                                    <div class="input-group-append">
                                        <span class="input-group-text p-0">
                                            <input type="color" id="color_picker" class="border-0 bg-transparent"
                                                value="#000000">
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label for="opacity_slider">Opacity: <span id="opacity_value">0.4</span></label>
                                    <input type="range" class="form-control-range" id="opacity_slider" min="0"
                                        max="1" step="0.01" value="0.4">
                                </div>
                                <div class="mt-2">
                                    <div id="color_preview"
                                        style="width: 100%; height: 30px; background-color: rgba(0,0,0,0.4); border-radius: 4px;">
                                    </div>
                                </div>
                                <small class="form-text text-muted">Use the color picker and opacity slider to set overlay
                                    color</small>
                                @error('overlay_color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="position">Position</label>
                                <input type="number" class="form-control @error('position') is-invalid @enderror"
                                    id="position" name="position" value="{{ old('position', $slider->position) }}"
                                    min="0">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-section">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" name="status" id="status"
                                        value="1" {{ old('status', $slider->status) ? 'checked' : '' }}
                                        style="margin-top: 7px;">
                                    <label class="form-check-label" for="status">Set as Active Slider</label>
                                    <small class="d-block text-muted">Check this to mark the slider as Live</small>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-group text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Slider
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Parse initial RGBA value
            function parseRgba(rgba) {
                const match = rgba.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([0-9.]+))?\)/);
                if (match) {
                    return {
                        r: parseInt(match[1]),
                        g: parseInt(match[2]),
                        b: parseInt(match[3]),
                        a: match[4] ? parseFloat(match[4]) : 1
                    };
                }
                return {
                    r: 0,
                    g: 0,
                    b: 0,
                    a: 0.4
                };
            }

            // Convert RGB to Hex
            function rgbToHex(r, g, b) {
                return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
            }

            // Convert Hex to RGB
            function hexToRgb(hex) {
                const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
                return result ? {
                    r: parseInt(result[1], 16),
                    g: parseInt(result[2], 16),
                    b: parseInt(result[3], 16)
                } : null;
            }

            // Update color preview and input value
            function updateColorPreview() {
                const hexColor = $('#color_picker').val();
                const opacity = parseFloat($('#opacity_slider').val());
                const rgb = hexToRgb(hexColor);

                if (rgb) {
                    const rgbaValue = `rgba(${rgb.r}, ${rgb.g}, ${rgb.b}, ${opacity})`;
                    $('#overlay_color').val(rgbaValue);
                    $('#color_preview').css('background-color', rgbaValue);
                    $('#opacity_value').text(opacity.toFixed(2));
                }
            }

            // Initialize from current value
            const initialValue = $('#overlay_color').val();
            const rgba = parseRgba(initialValue);

            // Set initial color picker value
            $('#color_picker').val(rgbToHex(rgba.r, rgba.g, rgba.b));

            // Set initial opacity slider value
            $('#opacity_slider').val(rgba.a);
            $('#opacity_value').text(rgba.a.toFixed(2));

            // Update preview
            $('#color_preview').css('background-color', initialValue);

            // Event listeners
            $('#color_picker').on('input', updateColorPreview);
            $('#opacity_slider').on('input', updateColorPreview);

            // Also update when text input changes
            $('#overlay_color').on('change', function() {
                const rgba = parseRgba($(this).val());
                $('#color_picker').val(rgbToHex(rgba.r, rgba.g, rgba.b));
                $('#opacity_slider').val(rgba.a);
                $('#opacity_value').text(rgba.a.toFixed(2));
                $('#color_preview').css('background-color', $(this).val());
            });
        });
    </script>
@endsection
