@extends('layouts.master')

@section('title', 'Edit Slider')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #sliders-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
    }

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
    .alert-err-p { background: linear-gradient(135deg,#fef2f2,#fee2e2); border: 1px solid #fca5a5; color: #b91c1c; }
    .alert-premium .close-btn {
        position: absolute; top: 12px; right: 14px;
        background: none; border: none; cursor: pointer;
        color: inherit; opacity: 0.6; font-size: 1rem;
        padding: 0; line-height: 1;
    }

    /* ── HEADER BANNER ── */
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
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        background: #fff; color: #475569; border: 1.5px solid #e2e8f0;
    }
    .btn-hdr:hover { background: #ede9fe; border-color: #a5b4fc; color: #4338ca; }

    /* ── FORM WRAP ── */
    .module-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .module-body { padding: 24px; }

    .section-band {
        padding: 16px 22px;
        display: flex;
        align-items: center;
        gap: 13px;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
    }
    .section-band .band-icon {
        width: 40px; height: 40px;
        border-radius: 11px;
        background: rgba(255,255,255,0.2);
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; color: #fff;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }
    .section-band h2 { font-size: 1rem; font-weight: 800; color: #fff; margin: 0 0 2px; line-height: 1; }
    .section-band span { font-size: 0.78rem; color: rgba(255,255,255,0.72); font-weight: 500; }

    /* ── FIELDS ── */
    .field-label {
        font-size: 0.76rem; font-weight: 700;
        color: #475569; text-transform: uppercase;
        letter-spacing: 0.04em; margin-bottom: 6px; display: block;
    }
    .field-label .req { color: #ef4444; }
    .field-input, .field-select, .field-textarea {
        width: 100%; padding: 10px 13px;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        font-size: 0.875rem; color: #0f172a;
        background: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.2s ease; outline: none;
    }
    .field-textarea { resize: vertical; min-height: 90px; }
    .field-input:focus, .field-select:focus, .field-textarea:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.1);
    }
    .field-input.is-invalid, .field-select.is-invalid, .field-textarea.is-invalid { border-color: #ef4444; }
    .invalid-feedback { font-size: 0.78rem; color: #dc2626; margin-top: 4px; display: block; }
    .field-hint { font-size: 0.76rem; color: #94a3b8; margin-top: 5px; line-height: 1.5; }

    .option-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
    @media (max-width: 768px) { .option-grid-2 { grid-template-columns: 1fr; gap: 16px; } }

    /* ── TOGGLE ROW ── */
    .toggle-row {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }
    .toggle-row .tl { font-size: 0.92rem; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
    .toggle-row .ts { font-size: 0.8rem; color: #64748b; line-height: 1.5; }

    .toggle-wrap { display: flex; align-items: center; gap: 9px; flex-shrink: 0; }
    .premium-toggle {
        appearance: none; -webkit-appearance: none;
        width: 44px; height: 23px;
        border-radius: 99px; background: #cbd5e1;
        cursor: pointer; position: relative;
        transition: background 0.22s ease; flex-shrink: 0;
        border: none; outline: none;
    }
    .premium-toggle::after {
        content: ''; position: absolute;
        top: 2px; left: 2px;
        width: 19px; height: 19px;
        background: #fff; border-radius: 50%;
        transition: left 0.22s ease;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }
    .premium-toggle:checked { background: #6366f1; }
    .premium-toggle:checked::after { left: 23px; }
    .toggle-wrap label { font-size: 0.82rem; font-weight: 600; color: #334155; cursor: pointer; white-space: nowrap; }

    /* ── INPUT GROUP ── */
    .input-group-premium { display: flex; }
    .input-group-premium .field-input { border-radius: 10px 0 0 10px; }
    .input-group-btn {
        padding: 0 14px;
        border: 1.5px solid #e2e8f0; border-left: none;
        border-radius: 0 10px 10px 0;
        background: #f1f5f9; color: #475569;
        display: flex; align-items: center; justify-content: center;
    }

    /* ── ACTION BUTTONS ── */
    .btn-action {
        padding: 11px 24px; border-radius: 10px;
        font-size: 0.875rem; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        text-decoration: none; transition: all 0.2s ease;
    }
    .btn-action.indigo { background: linear-gradient(135deg,#4338ca,#6366f1); color: #fff; box-shadow: 0 3px 10px rgba(99,102,241,0.3); }
    .btn-action.indigo:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(99,102,241,0.4); color: #fff; }
    .btn-action.ghost { background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-action.ghost:hover { background: #e2e8f0; color: #0f172a; }

    .preview-thumbnail {
        max-height: 120px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        object-fit: cover;
    }
</style>
@endsection

@section('content')
<div id="sliders-page" class="container-fluid px-4 py-4">

    {{-- Error summary if any --}}
    @if($errors->any())
        <div class="alert-premium alert-err-p">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Please resolve validation issues:</strong>
                <ul style="margin:6px 0 0;padding-left:16px;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
            <button class="close-btn" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>
        </div>
    @endif

    {{-- Header Banner --}}
    <div class="hdr-panel">
        <div class="hdr-title">
            <h4>Edit Slider</h4>
            <p>Update homepage banners with headings, buttons, links, and opacity overlays</p>
        </div>
        <a href="{{ route('admin.sliders.index') }}" class="btn-hdr">
            <i class="fas fa-arrow-left"></i> Back to Sliders
        </a>
    </div>

    {{-- Form wrap --}}
    <div class="module-wrap">
        <div class="section-band">
            <div class="band-icon"><i class="fas fa-sliders-h"></i></div>
            <div>
                <h2>Edit Slider Settings</h2>
                <span>Modify slide parameters</span>
            </div>
        </div>
        <div class="module-body">
            <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="option-grid-2">
                    {{-- Col Left --}}
                    <div>
                        <div style="margin-bottom:16px;">
                            <label class="field-label">Title <span class="req">*</span></label>
                            <input type="text" class="field-input @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $slider->title) }}" placeholder="e.g. Premium Deals" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="field-hint">Primary heading text displayed on the banner</div>
                        </div>

                        <div style="margin-bottom:16px;">
                            <label class="field-label">Description</label>
                            <textarea class="field-textarea @error('description') is-invalid @enderror" id="description" name="description" placeholder="e.g. Save up to 50% on selected electronic accessories">{{ old('description', $slider->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="field-hint">Subtext description shown below the title</div>
                        </div>

                        <div class="option-grid-2" style="gap:16px; margin-bottom:16px;">
                            <div>
                                <label class="field-label">Button Text</label>
                                <input type="text" class="field-input @error('button_text') is-invalid @enderror" id="button_text" name="button_text" value="{{ old('button_text', $slider->button_text) }}" placeholder="e.g. Shop Now">
                                @error('button_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="field-label">Button URL</label>
                                <input type="text" class="field-input @error('button_url') is-invalid @enderror" id="button_url" name="button_url" value="{{ old('button_url', $slider->button_url) }}" placeholder="e.g. https://domain.com/shop">
                                @error('button_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- Col Right --}}
                    <div>
                        <div style="margin-bottom:16px;">
                            <label class="field-label">Slider Image</label>
                            <input type="file" class="field-input @error('image') is-invalid @enderror" id="image" name="image" style="padding-top:8px;">
                            <div class="field-hint">Recommended size: 1920x550 pixels. Leave empty to keep current image.</div>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror

                            @if ($slider->image)
                                <div style="margin-top:14px; background:#f8fafc; border:1px dashed #cbd5e1; border-radius:10px; padding:12px;">
                                    <span class="field-label" style="margin-bottom:8px;">Current Image Preview:</span>
                                    <img src="{{ asset($slider->image) }}" alt="{{ $slider->image_alt }}" class="preview-thumbnail">
                                </div>
                            @endif
                        </div>

                        <div style="margin-bottom:16px;">
                            <label class="field-label">Image Alt Text</label>
                            <input type="text" class="field-input @error('image_alt') is-invalid @enderror" id="image_alt" name="image_alt" value="{{ old('image_alt', $slider->image_alt) }}" placeholder="e.g. Banner promo image">
                            @error('image_alt')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="field-hint">Improves accessibility and search engine indexing (SEO)</div>
                        </div>

                        <div class="option-grid-2" style="gap:16px; margin-bottom:16px;">
                            <div>
                                <label class="field-label">Overlay Color</label>
                                <div class="input-group-premium">
                                    <input type="text" class="field-input @error('overlay_color') is-invalid @enderror" id="overlay_color" name="overlay_color" value="{{ old('overlay_color', $slider->overlay_color) }}">
                                    <span class="input-group-btn">
                                        <input type="color" id="color_picker" class="border-0 bg-transparent" value="#000000" style="width:28px;height:24px;cursor:pointer;">
                                    </span>
                                </div>
                                @error('overlay_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="field-label">Opacity: <span id="opacity_value" style="color:#6366f1;">0.4</span></label>
                                <input type="range" class="form-range" id="opacity_slider" min="0" max="1" step="0.01" value="0.4" style="margin-top:10px; width:100%;">
                            </div>
                        </div>

                        <div style="margin-bottom:16px;">
                            <div id="color_preview" style="width: 100%; height: 32px; background-color: rgba(0,0,0,0.4); border-radius: 8px; border:1px solid #e2e8f0;"></div>
                            <div class="field-hint">Dynamic opacity overlay applied over the slider banner image</div>
                        </div>

                        <div class="option-grid-2" style="gap:16px; margin-bottom:16px; align-items: center;">
                            <div>
                                <label class="field-label">Position Index</label>
                                <input type="number" class="field-input @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $slider->position) }}" min="0">
                                @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="field-label" style="opacity: 0;">Status Alignment</label>
                                <div class="toggle-row" style="margin-bottom:0; padding:10px 14px;">
                                    <div>
                                        <div class="tl" style="font-size:0.8rem; margin:0;">Active state</div>
                                    </div>
                                    <div class="toggle-wrap">
                                        <input class="premium-toggle" type="checkbox" name="status" id="status" value="1" {{ old('status', $slider->status) ? 'checked' : '' }}>
                                        <label for="status">Live</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:12px;margin-top:20px;">
                    <button type="submit" class="btn-action indigo">
                        <i class="fas fa-save"></i> Update Slider
                    </button>
                    <a href="{{ route('admin.sliders.index') }}" class="btn-action ghost">
                        Cancel
                    </a>
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
