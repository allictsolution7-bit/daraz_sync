@extends('layouts.master')

@section('title', 'Slider Details')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    #sliders-page {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background: #f1f5f9;
        min-height: 100vh;
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

    .header-actions { display: flex; gap: 10px; align-items: center; }
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
    }
    .btn-hdr.primary { background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; box-shadow: 0 4px 14px rgba(99,102,241,0.3); }
    .btn-hdr.primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(99,102,241,0.4); color: #fff; }
    .btn-hdr.ghost { background: #fff; color: #475569; border: 1.5px solid #e2e8f0; }
    .btn-hdr.ghost:hover { background: #ede9fe; border-color: #a5b4fc; color: #4338ca; }

    /* ── MODULE CARD ── */
    .module-wrap {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        margin-bottom: 24px;
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
    .section-band h2 { font-size: 1rem; font-weight: 800; color: #fff; margin: 0; line-height: 1; }

    /* ── SLIDER PREVIEW SCREEN ── */
    .preview-box {
        position: relative;
        height: 380px;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: inset 0 0 40px rgba(0,0,0,0.3), 0 4px 18px rgba(0,0,0,0.08);
        border: 1px solid #cbd5e1;
    }
    .preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .preview-overlay {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: 1;
    }
    .preview-content {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: white;
        z-index: 2;
        width: 85%;
        text-shadow: 0 2px 10px rgba(0,0,0,0.4);
    }
    .preview-content h2 {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 12px;
        line-height: 1.25;
    }
    .preview-content p {
        font-size: 0.95rem;
        font-weight: 500;
        opacity: 0.92;
        margin-bottom: 22px;
        line-height: 1.6;
    }
    .preview-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 24px;
        background: linear-gradient(135deg, #4338ca, #6366f1);
        color: #fff;
        font-weight: 800;
        font-size: 0.85rem;
        border-radius: 99px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(99,102,241,0.4);
        transition: all 0.2s ease;
    }
    .preview-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(99,102,241,0.5); color:#fff; text-decoration:none; }

    /* ── STATS TABLE ── */
    .info-table { width: 100%; }
    .info-table tr { border-bottom: 1px solid #f1f5f9; }
    .info-table tr:last-child { border-bottom: none; }
    .info-table th {
        padding: 14px 16px;
        font-size: 0.76rem;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        width: 140px;
        text-align: left;
    }
    .info-table td {
        padding: 14px 16px;
        font-size: 0.875rem;
        color: #1e293b;
        font-weight: 600;
    }

    .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .bp-active   { background: #dcfce7; color: #15803d; }
    .bp-inactive { background: #fee2e2; color: #b91c1c; }
    .btn-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 99px;
        font-size: 0.76rem;
        font-weight: 700;
        text-decoration: none;
        background: #ede9fe; color: #6d28d9;
    }
</style>
@endsection

@section('content')
<div id="sliders-page" class="container-fluid px-4 py-4">

    {{-- Header Banner --}}
    <div class="hdr-panel">
        <div class="hdr-title">
            <h4>Slider Details</h4>
            <p>View current slider configuration and live banner render preview</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn-hdr primary">
                <i class="fas fa-pen"></i> Edit Slider
            </a>
            <a href="{{ route('admin.sliders.index') }}" class="btn-hdr ghost">
                <i class="fas fa-arrow-left"></i> Back to Sliders
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Preview Box --}}
        <div class="col-lg-8">
            <div class="module-wrap">
                <div class="section-band">
                    <div class="band-icon"><i class="fas fa-tv"></i></div>
                    <h2>Banner Rendering</h2>
                </div>
                <div class="module-body">
                    <div class="preview-box">
                        <img src="{{ asset($slider->image) }}" alt="Slider Banner Image">
                        <div class="preview-overlay" style="background-color: {{ $slider->overlay_color }};"></div>
                        <div class="preview-content">
                            <h2>{{ $slider->title }}</h2>
                            <p>{{ $slider->description }}</p>
                            @if($slider->button_text)
                                <a href="{{ $slider->button_url }}" target="_blank" class="preview-btn">
                                    {{ $slider->button_text }} <i class="fas fa-external-link-alt" style="font-size:0.72rem;"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Meta details --}}
        <div class="col-lg-4">
            <div class="module-wrap">
                <div class="section-band">
                    <div class="band-icon"><i class="fas fa-info-circle"></i></div>
                    <h2>Metadata</h2>
                </div>
                <div class="module-body" style="padding: 12px 0;">
                    <table class="info-table">
                        <tr>
                            <th>Database ID</th>
                            <td>#{{ $slider->id }}</td>
                        </tr>
                        <tr>
                            <th>Title text</th>
                            <td><span style="font-weight:700;color:#0f172a;">{{ $slider->title ?? '—' }}</span></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td><span style="color:#64748b;font-weight:500;line-height:1.5;">{{ $slider->description ?? '—' }}</span></td>
                        </tr>
                        <tr>
                            <th>Button text</th>
                            <td>
                                @if($slider->button_text)
                                    <a href="{{ $slider->button_url }}" target="_blank" class="btn-pill">
                                        {{ $slider->button_text }} <i class="fas fa-external-link-alt" style="font-size:0.68rem;"></i>
                                    </a>
                                @else
                                    <span style="color:#94a3b8;">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Position Index</th>
                            <td><span class="badge-pill" style="background:#e0e7ff;color:#4338ca;font-weight:800;">{{ $slider->position }}</span></td>
                        </tr>
                        <tr>
                            <th>Overlay color</th>
                            <td><code>{{ $slider->overlay_color }}</code></td>
                        </tr>
                        <tr>
                            <th>Status state</th>
                            <td>
                                @if($slider->status)
                                    <span class="badge-pill bp-active"><i class="fas fa-check"></i> Active</span>
                                @else
                                    <span class="badge-pill bp-inactive"><i class="fas fa-pause"></i> Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created by</th>
                            <td><i class="far fa-user" style="color:#94a3b8;margin-right:2px;"></i> {{ $slider->creator ? $slider->creator->name : 'System Generated' }}</td>
                        </tr>
                        <tr>
                            <th>Created date</th>
                            <td><i class="far fa-calendar-alt" style="color:#94a3b8;margin-right:2px;"></i> {{ $slider->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last updated</th>
                            <td><i class="far fa-edit" style="color:#94a3b8;margin-right:2px;"></i> {{ $slider->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
