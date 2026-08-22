@extends('layouts.master')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<style>
    :root {
        --primary: #197A94;
        --primary-gradient: linear-gradient(135deg, #197A94 0%, #0d5c70 100%);
        --primary-hover: #135d71;
        --primary-light: rgba(25, 122, 148, 0.08);
        --success: #10b981;
        --info: #06b6d4;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark-slate: #1e293b;
        --text-main: #334155;
        --text-muted: #64748b;
        --bg-glass: rgba(255, 255, 255, 0.95);
        --border-glass: rgba(226, 232, 240, 0.9);
        --shadow-premium: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 12px -2px rgba(0, 0, 0, 0.03);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Page container styling */
    .container-fluid {
        padding: 30px;
        background-color: #f8fafc;
        min-height: 100vh;
    }

    .settings-container {
        display: flex;
        gap: 30px;
        margin: 20px 0;
        align-items: flex-start;
    }

    /* Modern Premium Sidebar */
    .settings-sidebar {
        width: 320px;
        background: var(--bg-glass);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        padding: 20px;
        box-shadow: var(--shadow-premium);
        position: sticky;
        top: 90px;
        max-height: calc(100vh - 130px);
        overflow-y: auto;
        z-index: 10;
        scrollbar-width: thin;
        scrollbar-color: var(--primary) transparent;
    }

    .settings-sidebar::-webkit-scrollbar {
        width: 4px;
    }

    .settings-sidebar::-webkit-scrollbar-thumb {
        background-color: var(--primary);
        border-radius: 4px;
    }

    .settings-sidebar h6 {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 11px;
        color: var(--text-muted) !important;
        margin-bottom: 15px !important;
        padding-left: 5px;
    }

    /* Navigation tabs */
    .sidebar-tab {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 12px 18px;
        margin: 6px 0;
        text-align: left;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 12px;
        color: var(--text-main);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none !important;
        transition: var(--transition-smooth);
        cursor: pointer;
    }

    .sidebar-tab i {
        font-size: 16px;
        width: 24px;
        text-align: center;
        transition: var(--transition-smooth);
    }

    .sidebar-tab:hover {
        background: var(--primary-light);
        color: var(--primary);
    }

    .sidebar-tab.active {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 15px rgba(25, 122, 148, 0.25);
    }

    .sidebar-tab.active i {
        color: white;
    }

    /* Settings Content Panel */
    .settings-content {
        flex: 1;
        background: var(--bg-glass);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 40px;
        box-shadow: var(--shadow-premium);
        min-height: 500px;
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.4s ease-out;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .tab-header {
        border-bottom: 1px solid var(--border-glass);
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .tab-title {
        color: var(--dark-slate);
        font-size: 24px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.5px;
    }

    /* Save Settings Header Card */
    .card.shadow.mb-4 {
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        box-shadow: var(--shadow-premium) !important;
        background: var(--bg-glass);
        margin-bottom: 25px !important;
        overflow: hidden;
    }

    .card-header {
        background: transparent !important;
        border-bottom: 1px solid var(--border-glass) !important;
        padding: 20px 25px !important;
    }

    .card-header h6 {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark-slate) !important;
    }

    /* Floating Save Button */
    .save-button {
        background: var(--primary-gradient);
        color: white;
        border: none;
        padding: 12px 28px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        box-shadow: 0 4px 15px rgba(25, 122, 148, 0.3);
        transition: var(--transition-smooth);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .save-button:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(25, 122, 148, 0.4);
        color: white;
        text-decoration: none;
    }

    .save-button:active {
        transform: translateY(0);
    }

    /* Form Fields & Controls */
    .form-group {
        margin-bottom: 25px;
        align-items: center;
    }

    .form-group label {
        font-weight: 600;
        color: var(--dark-slate);
        font-size: 14px;
    }

    .form-control {
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 14px;
        color: var(--text-main);
        background-color: #ffffff;
        transition: var(--transition-smooth);
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(25, 122, 148, 0.15);
        color: var(--text-main);
    }

    textarea.form-control {
        min-height: 100px;
        line-height: 1.6;
    }

    .form-text.text-muted {
        font-size: 12px;
        color: var(--text-muted) !important;
        margin-top: 6px;
    }

    /* Custom File Inputs */
    .custom-file {
        position: relative;
        display: inline-block;
        width: 100%;
        height: auto;
    }

    .custom-file-input {
        cursor: pointer;
    }

    .custom-file-label {
        border: 1.5px dashed #cbd5e1;
        border-radius: 10px;
        padding: 12px 16px;
        background: #f8fafc;
        color: var(--text-muted);
        font-weight: 500;
        text-align: center;
        transition: var(--transition-smooth);
        height: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .custom-file-label::after {
        display: none;
    }

    .custom-file-input:focus ~ .custom-file-label {
        border-color: var(--primary);
        background: var(--primary-light);
    }

    /* Section Headings inside Tabs */
    h6.text-primary {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary) !important;
        border-bottom: 2px solid var(--primary-light);
        padding-bottom: 8px;
        margin-top: 35px;
        margin-bottom: 20px;
    }

    hr {
        border-top: 1px solid var(--border-glass);
        margin: 30px 0;
    }

    /* Premium Switches / Checkbox Toggle Styling */
    .custom-switch {
        padding-left: 2.25rem;
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        border-color: var(--primary);
        background-color: var(--primary);
    }

    /* Sortable & Drag Handle Layouts */
    .list-group-item {
        border: 1px solid var(--border-glass) !important;
        border-radius: 12px !important;
        margin-bottom: 8px;
        background: #ffffff;
        padding: 14px 20px;
        transition: var(--transition-smooth);
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .list-group-item:hover {
        background-color: #fafafa;
        border-color: #cbd5e1 !important;
        transform: translateY(-1px);
    }

    .handle {
        color: var(--text-muted);
        cursor: grab;
        padding: 4px;
        transition: var(--transition-smooth);
    }

    .handle:hover {
        color: var(--primary);
    }

    /* Color picker layouts */
    .color-input {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .input-group-append span {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        cursor: pointer;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .settings-container {
            flex-direction: column;
            gap: 15px;
        }

        .settings-sidebar {
            width: 100%;
            position: sticky;
            top: 105px;
            max-height: none;
            z-index: 100;
            padding: 6px 8px;
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            white-space: nowrap;
            gap: 6px;
            scrollbar-width: none;
            -ms-overflow-style: none;
            background: var(--bg-glass);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .settings-sidebar::-webkit-scrollbar {
            display: none;
        }

        .settings-sidebar h6 {
            display: none !important;
        }

        .sidebar-tab {
            display: inline-flex;
            align-items: center;
            width: auto;
            margin: 0;
            padding: 4px 8px;
            font-size: 10px;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .sidebar-tab i {
            margin-right: 4px !important;
            width: auto;
        }

        .settings-content {
            width: 100%;
            padding: 12px;
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 6px !important;
        }

        .card-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start !important;
            padding: 6px 10px !important;
        }

        .save-button {
            width: 100%;
            justify-content: center;
            padding: 4px 10px !important;
            font-size: 10px !important;
        }

        /* Specific overrides to reduce main header height on mobile */
        .card.shadow.mb-4 > .card-header {
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 6px 10px !important;
            gap: 10px;
        }

        .card.shadow.mb-4 > .card-header h6 {
            font-size: 12px !important;
        }

        .card.shadow.mb-4 {
            position: sticky;
            top: 60px;
            z-index: 101;
            margin-bottom: 12px !important;
        }

        .card.shadow.mb-4 .save-button {
            padding: 4px 10px !important;
            font-size: 10px !important;
            width: auto;
            justify-content: center;
        }

        .tab-header {
            padding-bottom: 6px !important;
        }

        .tab-title {
            font-size: 15px !important;
        }

        .form-group.row {
            margin-bottom: 15px;
        }

        .input-group {
            flex-wrap: nowrap;
        }
        
        .input-group .form-control {
            min-width: 0;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Website Settings</h6>
            <button type="submit" form="settingsForm" class="save-button">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>
    </div>

    <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="settings-container">
            <!-- Sidebar Navigation -->
            <div class="settings-sidebar">
                <h6 class="mb-3 text-muted">Settings Categories</h6>

                <a href="#general" class="sidebar-tab active" data-tab="general">
                    <i class="fas fa-info-circle mr-2"></i> General Information
                </a>

                <a href="#preloader" class="sidebar-tab" data-tab="preloader">
                    <i class="fas fa-sync-alt mr-2"></i> Preloader Settings
                </a>

                <a href="#seo" class="sidebar-tab" data-tab="seo">
                    <i class="fas fa-chart-line mr-2"></i> SEO Settings
                </a>

                <a href="#sitemap" class="sidebar-tab" data-tab="sitemap">
                    <i class="fas fa-route mr-2"></i> Sitemap Management
                </a>

                <a href="#product-design" class="sidebar-tab" data-tab="product-design">
                    <i class="fas fa-cubes mr-2"></i> Product Item Design
                </a>

                <a href="#section-headings" class="sidebar-tab" data-tab="section-headings">
                    <i class="fas fa-heading mr-2"></i> Section Heading Styles
                </a>

                <a href="#typography" class="sidebar-tab" data-tab="typography">
                    <i class="fas fa-text-height mr-2"></i> Typography Settings
                </a>

                <a href="#colors" class="sidebar-tab" data-tab="colors">
                    <i class="fas fa-paint-brush mr-2"></i> Colors Settings
                </a>

                <a href="#contact" class="sidebar-tab" data-tab="contact">
                    <i class="fas fa-envelope-open-text mr-2"></i> Contact Information
                </a>

                <a href="#social" class="sidebar-tab" data-tab="social">
                    <i class="fas fa-hashtag mr-2"></i> Social Media Links
                </a>

                <a href="#header" class="sidebar-tab" data-tab="header">
                    <i class="fas fa-window-maximize mr-2"></i> Header Customization
                </a>

                <a href="#footer" class="sidebar-tab" data-tab="footer">
                    <i class="fas fa-window-minimize mr-2"></i> Footer Content
                </a>

                <a href="#ecommerce" class="sidebar-tab" data-tab="ecommerce">
                    <i class="fas fa-store mr-2"></i> Ecommerce Settings
                </a>

                <a href="#homepage" class="sidebar-tab" data-tab="homepage">
                    <i class="fas fa-laptop-code mr-2"></i> Homepage Customization
                </a>

                <a href="#single-product" class="sidebar-tab" data-tab="single-product">
                    <i class="fas fa-box-open mr-2"></i> Advanced Single Product
                </a>

                <a href="#registration" class="sidebar-tab" data-tab="registration">
                    <i class="fas fa-user-shield mr-2"></i> Registration Settings
                </a>

                <a href="#mobile-nav" class="sidebar-tab" data-tab="mobile-nav">
                    <i class="fas fa-mobile-alt mr-2"></i> Mobile Bottom Navigation
                </a>

                <a href="#layout" class="sidebar-tab" data-tab="layout">
                    <i class="fas fa-layer-group mr-2"></i> Layout Settings
                </a>

                <a href="#mega-menu" class="sidebar-tab" data-tab="mega-menu">
                    <i class="fas fa-th-large mr-2"></i> Mega Menu Settings
                </a>
            </div>

            <!-- Content Area -->
            <div class="settings-content">
                <!-- General Information Tab -->
                <div id="general" class="tab-content active">
                    <div class="tab-header">
                        <h2 class="tab-title">General Information</h2>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Website Name</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[site_name]" class="form-control mb-2"
                                value="{{ setting('general', 'site_name', 'My Website') }}">
                            <small class="form-text text-muted">The name of your website that appears in the browser title</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Website Logo</label>
                        <div class="col-md-9">
                            <div class="custom-file mb-2">
                                <input type="file" name="site_logo" class="custom-file-input" id="site_logo">
                                <label class="custom-file-label" for="site_logo">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 200x100px. WebP, PNG or SVG format.</small> <br>
                            @if (setting('general', 'logo'))
                            <div class="mt-2 p-2 border rounded d-inline-block">
                                <img src="{{ \App\Services\SettingsService::getLogo() }}" alt="Site Logo"
                                    style="width: {{ setting('general', 'logo_width', '150') }}px;">
                            </div>
                            @endif
                            <div class="mt-2">
                                <label class="form-label">Logo Width (px)</label>
                                <input type="number" name="settings[logo_width]" class="form-control"
                                    value="{{ setting('general', 'logo_width', '150') }}"
                                    min="50" max="500" step="1">
                                <small class="form-text text-muted">Enter the desired width for your logo in pixels (50-500px)</small>
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Mobile Version Logo Width (px)</label>
                                <input type="number" name="settings[mobile_logo_width]" class="form-control"
                                    value="{{ setting('general', 'mobile_logo_width', '150') }}"
                                    min="50" max="500" step="1">
                                <small class="form-text text-muted">Enter the desired width for your logo in pixels (50-500px)</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Favicon</label>
                        <div class="col-md-9">
                            <div class="custom-file mb-2">
                                <input type="file" name="site_favicon" class="custom-file-input"
                                    id="site_favicon">
                                <label class="custom-file-label" for="site_favicon">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 32x32px. ICO, WebP, PNG or SVG format.</small> <br>
                            @if (setting('header', 'favicon') || setting('general', 'favicon'))
                            <div class="mt-2 p-2 border rounded d-inline-block">
                                <img src="{{ \App\Services\SettingsService::getFavicon() }}" alt="Favicon"
                                    style="width: 32px; height: 32px;">
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Google Tag Manager ID</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[gtm_id]" class="form-control"
                                value="{{ setting('general', 'gtm_id', 'GTM-N6R8CHHV') }}">
                            <small class="form-text text-muted">Example: GTM-XXXXXXX</small>
                        </div>
                    </div>

                    <h6 class="text-primary mt-3">Thank You Page Message</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Thank You Message Text</label>
                        <div class="col-md-9">
                            <textarea name="settings[thankyou_message_text]" class="form-control" rows="6">{{ setting('general', 'thankyou_message_text', "ধন্যবাদ!\nআপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে।\n\n:site_name-এ কেনাকাটার জন্য আপনাকে আন্তরিক ধন্যবাদ। আমরা দ্রুততম সময়ে আপনার পণ্য প্রস্তুত ও ডেলিভারি করবো, ইনশাআল্লাহ।\n\nঅর্ডার সংক্রান্ত যেকোনো প্রয়োজনে কল বা ম্যাসেজ করুন :phone_link নাম্বারে।\n\nআপনার অর্ডারটি ট্র্যাক করতে এখানে ক্লিক করুন: :track_link") }}</textarea>
                            <small class="form-text text-muted">Placeholders: <code>:site_name</code>, <code>:phone</code>, <code>:phone_link</code>, <code>:track_link</code>.</small>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Order Track Link Text</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[thankyou_track_link_text]" class="form-control"
                                value="{{ setting('general', 'thankyou_track_link_text', 'Order Track') }}">
                            <small class="form-text text-muted">Text used for the <code>:track_link</code> placeholder.</small>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Default Return Policy</label>
                        <div class="col-md-9">
                            <textarea name="settings[default_return_policy]" class="form-control" rows="6" placeholder="Enter default return policy instructions and money refund conditions...">{{ setting('general', 'default_return_policy', "If you are not satisfied with your purchase, you can return it within the specified return period. The product must be unused and in its original packaging. Once received, refund will be processed to your payment method.") }}</textarea>
                            <small class="form-text text-muted">This policy will be shown to customers when requesting a return on a product that does not belong to a vendor, or if the vendor has not specified their custom policy.</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Global Settings</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Global Product Video URL (YouTube)</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[global_video_url]" id="global_video_url"
                                class="form-control"
                                value="{{ setting('general', 'global_video_url') }}"
                                placeholder="https://www.youtube.com/watch?v=xxxxxx">
                            <small class="form-text text-muted">This will be used if a product does not have its own video URL.</small>
                        </div>
                    </div>

                    @php
                    $videoUrl = setting('general', 'global_video_url');
                    $embedUrl = \App\Services\SettingsService::getYoutubeEmbedUrl($videoUrl);
                    @endphp

                    @if ($embedUrl)
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Video Preview</label>
                        <div class="col-md-9">
                            <div style="width:300px;">
                                <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen
                                    style="width:100%; height:auto; aspect-ratio:16/9;">
                                </iframe>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Global Category/Subcategory Banner Image</label>
                        <div class="col-md-9">
                            <input type="file" class="form-control" id="global_category_bg" name="global_category_bg">
                            @php
                            $globalBg = setting('homepage', 'global_category_bg');
                            @endphp
                            @if ($globalBg)
                            <div class="mt-2 p-2 border rounded d-inline-block">
                                <img src="{{ asset($globalBg) }}" style="width:100%;height:200px !Important;"
                                    alt="Current Global Banner">
                            </div>
                            @endif
                            <br>
                            <small class="form-text text-muted">Global banner image for all category and subcategory pages.</small>
                        </div>
                    </div>
                </div>

                <!-- Preloader Settings Tab -->
                <div id="preloader" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Preloader Settings</h2>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Preloader?</label>
                        <div class="col-md-9">
                            <select name="settings[preloader_show]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'preloader_show', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'preloader_show', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Preloader Text</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[preloader_text]" class="form-control"
                                value="{{ setting('general', 'preloader_text', 'Thikana Shop') }}">
                            <small class="form-text text-muted">Text shown below the preloader animation.</small>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings Tab -->
                <div id="seo" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">SEO Settings</h2>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Default Meta Title</label>
                        <div class="col-md-9">
                            <input type="text" name="seo[default_meta_title]" class="form-control mb-2"
                                value="{{ setting('seo', 'default_meta_title', 'Thikana Shop - Your Ultimate Fashion Destination') }}"
                                maxlength="60">
                            <small class="form-text text-muted">Default page title (max 60 characters). Used when no specific title is set.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Default Meta Description</label>
                        <div class="col-md-9">
                            <textarea name="seo[default_meta_description]" class="form-control mb-2" rows="3" maxlength="260">{{ $seo['default_meta_description'] ?? 'Discover the latest fashion trends at Thikana Shop. Shop premium quality clothing, accessories, and more at competitive prices. Fast shipping, secure payments, and excellent customer service.' }}</textarea>
                            <small class="form-text text-muted">Default page description (max 260 characters). Used when no specific description is set.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Default Meta Keywords</label>
                        <div class="col-md-9">
                            <input type="text" name="seo[default_meta_keywords]" class="form-control mb-2"
                                value="{{ $seo['default_meta_keywords'] ?? 'fashion, clothing, accessories, online shopping, thikana shop' }}">
                            <small class="form-text text-muted">Comma-separated keywords for search engines.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Default OG Image</label>
                        <div class="col-md-9">
                            <div class="custom-file mb-2">
                                <input type="file" name="seo_og_image" class="custom-file-input"
                                    id="seo_og_image">
                                <label class="custom-file-label" for="seo_og_image">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 1200x630px. Used for social media sharing when no specific image is set.</small>
                            @if (setting('seo', 'default_og_image'))
                            <div class="mt-2 p-2 border rounded d-inline-block">
                                <img src="{{ asset(setting('seo', 'default_og_image')) }}"
                                    alt="Default OG Image" style="max-width: 200px; height: auto;">
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Site Author</label>
                        <div class="col-md-9">
                            <input type="text" name="seo[site_author]" class="form-control mb-2"
                                value="{{ setting('seo', 'site_author', 'Thikana Shop') }}">
                            <small class="form-text text-muted">The author/owner of the website.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Twitter Username</label>
                        <div class="col-md-9">
                            <input type="text" name="seo[twitter_username]" class="form-control mb-2"
                                value="{{ setting('seo', 'twitter_username', '@thikanashop') }}"
                                placeholder="@username">
                            <small class="form-text text-muted">Your Twitter username for social media cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Google Search Console</label>
                        <div class="col-md-9">
                            <input type="text" name="seo[google_search_console]" class="form-control mb-2"
                                value="{{ setting('seo', 'google_search_console', '') }}"
                                placeholder="meta tag content">
                            <small class="form-text text-muted">Google Search Console verification meta tag content.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Bing Webmaster Tools</label>
                        <div class="col-md-9">
                            <input type="text" name="seo[bing_webmaster]" class="form-control mb-2"
                                value="{{ setting('seo', 'bing_webmaster', '') }}"
                                placeholder="meta tag content">
                            <small class="form-text text-muted">Bing Webmaster Tools verification meta tag content.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Yandex Webmaster</label>
                        <div class="col-md-9">
                            <input type="text" name="seo[yandex_webmaster]" class="form-control mb-2"
                                value="{{ setting('seo', 'yandex_webmaster', '') }}"
                                placeholder="meta tag content">
                            <small class="form-text text-muted">Yandex Webmaster verification meta tag content.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Robots Meta</label>
                        <div class="col-md-9">
                            <select name="seo[robots_meta]" class="form-control mb-2">
                                <option value="index, follow"
                                    {{ setting('seo', 'robots_meta', 'index, follow') == 'index, follow' ? 'selected' : '' }}>
                                    index, follow</option>
                                <option value="noindex, follow"
                                    {{ setting('seo', 'robots_meta', 'index, follow') == 'noindex, follow' ? 'selected' : '' }}>
                                    noindex, follow</option>
                                <option value="index, nofollow"
                                    {{ setting('seo', 'robots_meta', 'index, follow') == 'index, nofollow' ? 'selected' : '' }}>
                                    index, nofollow</option>
                                <option value="noindex, nofollow"
                                    {{ setting('seo', 'robots_meta', 'index, follow') == 'noindex, nofollow' ? 'selected' : '' }}>
                                    noindex, nofollow</option>
                            </select>
                            <small class="form-text text-muted">Default robots meta tag for pages.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Canonical URL Base</label>
                        <div class="col-md-9">
                            <input type="url" name="seo[canonical_base]" class="form-control mb-2"
                                value="{{ setting('seo', 'canonical_base', url('/')) }}"
                                placeholder="https://yoursite.com">
                            <small class="form-text text-muted">Base URL for canonical links (usually your domain).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Enable Schema Markup</label>
                        <div class="col-md-9">
                            <select name="seo[enable_schema]" class="form-control mb-2">
                                <option value="1"
                                    {{ setting('seo', 'enable_schema', '1') == '1' ? 'selected' : '' }}>
                                    Enable</option>
                                <option value="0"
                                    {{ setting('seo', 'enable_schema', '1') == '0' ? 'selected' : '' }}>
                                    Disable</option>
                            </select>
                            <small class="form-text text-muted">Enable structured data markup for better search engine understanding.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Organization Schema</label>
                        <div class="col-md-9">
                            <textarea name="seo[organization_schema]" class="form-control mb-2" rows="4">{{ setting('seo', 'organization_schema', '') }}</textarea>
                            <small class="form-text text-muted">JSON-LD organization schema markup (optional).</small>
                        </div>
                    </div>
                </div>

                <!-- Sitemap Management Tab -->
                <div id="sitemap" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Sitemap Management</h2>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Sitemap Status</label>
                        <div class="col-md-9">
                            @php
                            $sitemapPath = public_path('sitemap.xml');
                            $sitemapExists = file_exists($sitemapPath);
                            $sitemapSize = $sitemapExists
                            ? number_format(filesize($sitemapPath) / 1024, 2)
                            : 0;
                            $sitemapModified = $sitemapExists
                            ? date('Y-m-d H:i:s', filemtime($sitemapPath))
                            : 'Never';
                            @endphp

                            <div class="alert {{ $sitemapExists ? 'alert-success' : 'alert-warning' }} mb-3">
                                <strong>Status:</strong> {{ $sitemapExists ? 'Generated' : 'Not Generated' }}<br>
                                @if ($sitemapExists)
                                <strong>Size:</strong> {{ $sitemapSize }} KB<br>
                                <strong>Last Modified:</strong> {{ $sitemapModified }}<br>
                                <strong>URL:</strong> <a href="{{ url('sitemap.xml') }}"
                                    target="_blank">{{ url('sitemap.xml') }}</a>
                                @else
                                <strong>No sitemap found. Generate one to improve SEO.</strong>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Generate Sitemap</label>
                        <div class="col-md-9">
                            <button type="button" id="generateSitemapBtn" class="btn btn-primary mb-2">
                                <i class="fas fa-sync-alt"></i> {{ $sitemapExists ? 'Regenerate' : 'Generate' }}
                                Sitemap
                            </button>
                            <div id="sitemapProgress" class="progress mb-2" style="display: none;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated"
                                    role="progressbar" style="width: 0%"></div>
                            </div>
                            <div id="sitemapStatus" class="alert" style="display: none;"></div>
                            <small class="form-text text-muted">
                                Generate or regenerate your sitemap.xml file. This helps search engines discover and
                                index your pages.
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Auto-Generate Sitemap</label>
                        <div class="col-md-9">
                            <select name="seo[auto_generate_sitemap]" class="form-control mb-2">
                                <option value="1"
                                    {{ setting('seo', 'auto_generate_sitemap', '1') == '1' ? 'selected' : '' }}>
                                    Enable</option>
                                <option value="0"
                                    {{ setting('seo', 'auto_generate_sitemap', '1') == '0' ? 'selected' : '' }}>
                                    Disable</option>
                            </select>
                            <small class="form-text text-muted">Automatically regenerate sitemap when content is
                                updated (recommended).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Sitemap Include Options</label>
                        <div class="col-md-9">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="seo[sitemap_include_products]" value="1"
                                    class="form-check-input"
                                    {{ setting('seo', 'sitemap_include_products', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label">Include Products</label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="seo[sitemap_include_categories]" value="1"
                                    class="form-check-input"
                                    {{ setting('seo', 'sitemap_include_categories', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label">Include Product Categories</label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="seo[sitemap_include_pages]" value="1"
                                    class="form-check-input"
                                    {{ setting('seo', 'sitemap_include_pages', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label">Include Pages</label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="seo[sitemap_include_blog]" value="1"
                                    class="form-check-input"
                                    {{ setting('seo', 'sitemap_include_blog', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label">Include Blog Posts</label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="seo[sitemap_include_blog_categories]" value="1"
                                    class="form-check-input"
                                    {{ setting('seo', 'sitemap_include_blog_categories', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label">Include Blog Categories</label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="seo[sitemap_include_writers]" value="1"
                                    class="form-check-input"
                                    {{ setting('seo', 'sitemap_include_writers', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label">Include Writers</label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="seo[sitemap_include_publishers]" value="1"
                                    class="form-check-input"
                                    {{ setting('seo', 'sitemap_include_publishers', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label">Include Publishers</label>
                            </div>
                            <small class="form-text text-muted">Select which content types to include in your
                                sitemap.</small>
                        </div>
                    </div>
                </div>

                <div id="product-design" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Product Item Design</h2>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Product Card Border Radius</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[product_card_border_radius]"
                                class="form-control"
                                value="{{ setting('general', 'product_card_border_radius', '10px') }}"
                                placeholder="10px">
                            <small class="form-text text-muted">Border radius for product cards (e.g., 10px, 5px, 0px).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Product Card Box Shadow</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[product_card_box_shadow]" class="form-control"
                                value="{{ setting('general', 'product_card_box_shadow', '0 5px 15px rgba(0, 0, 0, 0.05)') }}"
                                placeholder="0 5px 15px rgba(0, 0, 0, 0.05)">
                            <small class="form-text text-muted">Box shadow for product cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Product Card Border</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[product_card_border]" class="form-control"
                                value="{{ setting('general', 'product_card_border', '1px solid #efefef') }}"
                                placeholder="1px solid #efefef">
                            <small class="form-text text-muted">Border for product cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Product Image Height (Desktop)</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[product_image_height]" class="form-control"
                                value="{{ setting('general', 'product_image_height', '240px') }}"
                                placeholder="240px">
                            <small class="form-text text-muted">Height for product images on desktop (e.g., 240px, 200px).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Product Image Height (Tablet)</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[product_image_height_tablet]"
                                class="form-control"
                                value="{{ setting('general', 'product_image_height_tablet', '200px') }}"
                                placeholder="200px">
                            <small class="form-text text-muted">Height for product images on tablets (e.g., 200px, 180px).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Product Image Height (Mobile)</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[product_image_height_mobile]"
                                class="form-control"
                                value="{{ setting('general', 'product_image_height_mobile', 'auto') }}"
                                placeholder="auto">
                            <small class="form-text text-muted">Height for product images on mobile (e.g., auto, 150px, 120px).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Product Image Padding</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[product_image_padding]" class="form-control"
                                value="{{ setting('general', 'product_image_padding', '5px') }}"
                                placeholder="5px">
                            <small class="form-text text-muted">Padding for product images (e.g., 5px, 10px).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Product Rating</label>
                        <div class="col-md-9">
                            <select name="settings[show_product_rating]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'show_product_rating', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'show_product_rating', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide product ratings on product cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Product Writer</label>
                        <div class="col-md-9">
                            <select name="settings[show_product_writer]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'show_product_writer', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'show_product_writer', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide product writer information on product cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Product Title</label>
                        <div class="col-md-9">
                            <select name="settings[show_product_title]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'show_product_title', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'show_product_title', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide product titles on product cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Product Price</label>
                        <div class="col-md-9">
                            <select name="settings[show_product_price]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'show_product_price', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'show_product_price', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide product prices on product cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Product Button</label>
                        <div class="col-md-9">
                            <select name="settings[show_product_button]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'show_product_button', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'show_product_button', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide product action buttons on product cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Product Badge Type</label>
                        <div class="col-md-9">
                            <select name="settings[product_badge_type]" class="form-control">
                                <option value="starburst"
                                    {{ setting('general', 'product_badge_type', 'starburst') == 'starburst' ? 'selected' : '' }}>
                                    Starburst Badge</option>
                                <option value="simple"
                                    {{ setting('general', 'product_badge_type', 'starburst') == 'simple' ? 'selected' : '' }}>
                                    Simple Badge</option>
                                <option value="none"
                                    {{ setting('general', 'product_badge_type', 'starburst') == 'none' ? 'selected' : '' }}>
                                    No Badge</option>
                            </select>
                            <small class="form-text text-muted">Choose the type of badge to display on product cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Default Badge Text</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[default_badge_text]" class="form-control"
                                value="{{ setting('general', 'default_badge_text', '১০% ছাড়') }}"
                                placeholder="১০% ছাড়">
                            <small class="form-text text-muted">Default text to show on badges when no discount is available.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Product Button Text</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[product_button_text]" class="form-control"
                                value="{{ setting('general', 'product_button_text', 'View Product') }}"
                                placeholder="View Product">
                            <small class="form-text text-muted">Default text for the product action button.</small>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-primary">Product Card Action Buttons</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show "View Product" Button</label>
                        <div class="col-md-9">
                            <select name="settings[show_view_product_button]" class="form-control">
                                <option value="1" {{ setting('general', 'show_view_product_button', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_view_product_button', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the "View Product" button on product cards.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">View Product Button Background Color</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="color" name="settings[view_product_button_bg_color]" class="form-control" style="width: 60px;"
                                    value="{{ setting('general', 'view_product_button_bg_color', 'transparent') }}" id="viewProductBgColor">
                                <input type="text" name="settings[view_product_button_bg_color_text]" class="form-control"
                                    value="{{ setting('general', 'view_product_button_bg_color', 'transparent') }}"
                                    placeholder="#F68C20" id="viewProductBgColorText">
                            </div>
                            <small class="form-text text-muted">Background color for the "View Product" button (use transparent for default style).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">View Product Button Text Color</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="color" name="settings[view_product_button_text_color]" class="form-control" style="width: 60px;"
                                    value="{{ setting('general', 'view_product_button_text_color', '#333333') }}" id="viewProductTextColor">
                                <input type="text" name="settings[view_product_button_text_color_text]" class="form-control"
                                    value="{{ setting('general', 'view_product_button_text_color', '#333333') }}"
                                    placeholder="#FFFFFF" id="viewProductTextColorText">
                            </div>
                            <small class="form-text text-muted">Text color for the "View Product" button.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show "Buy Now" Button</label>
                        <div class="col-md-9">
                            <select name="settings[show_buy_now_button]" class="form-control">
                                <option value="1" {{ setting('general', 'show_buy_now_button', '0') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_buy_now_button', '0') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the "Buy Now" button on product cards. When enabled, redirects to the buy now page (buynow.blade.php).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Buy Now Button Text</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[buy_now_button_text]" class="form-control"
                                value="{{ setting('general', 'buy_now_button_text', 'Buy Now') }}"
                                placeholder="Buy Now">
                            <small class="form-text text-muted">Text to display on the "Buy Now" button.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Buy Now Button Background Color</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="color" name="settings[buy_now_button_bg_color]" class="form-control" style="width: 60px;"
                                    value="{{ setting('general', 'buy_now_button_bg_color', '#2ecc71') }}" id="buyNowBgColor">
                                <input type="text" name="settings[buy_now_button_bg_color_text]" class="form-control"
                                    value="{{ setting('general', 'buy_now_button_bg_color', '#2ecc71') }}"
                                    placeholder="#F68C20" id="buyNowBgColorText">
                            </div>
                            <small class="form-text text-muted">Background color for the "Buy Now" button.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Buy Now Button Text Color</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="color" name="settings[buy_now_button_text_color]" class="form-control" style="width: 60px;"
                                    value="{{ setting('general', 'buy_now_button_text_color', '#ffffff') }}" id="buyNowTextColor">
                                <input type="text" name="settings[buy_now_button_text_color_text]" class="form-control"
                                    value="{{ setting('general', 'buy_now_button_text_color', '#ffffff') }}"
                                    placeholder="#FFFFFF" id="buyNowTextColorText">
                            </div>
                            <small class="form-text text-muted">Text color for the "Buy Now" button.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Quick Cart Icon</label>
                        <div class="col-md-9">
                            <select name="settings[show_quick_cart_icon]" class="form-control">
                                <option value="1" {{ setting('general', 'show_quick_cart_icon', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_quick_cart_icon', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the quick add to cart icon on product cards.</small>
                        </div>
                    </div>
                </div>

                <div id="section-headings" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Section Heading Styles</h2>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Custom Border Style</label>
                        <div class="col-md-9">
                            <textarea name="settings[section_header_custom_border]" class="form-control" rows="4"
                                placeholder="Example: border-bottom: 1px solid var(--border-color);">{{ setting('general', 'section_header_custom_border', '') }}</textarea>
                            <small class="form-text text-muted">
                                Enter custom CSS border properties. Examples:<br>
                                • <code>border-bottom: 1px solid var(--border-color);</code> - Bottom border<br>
                                • <code>border: 1px solid #cccccc;</code> - All sides border<br>
                                • <code>border-top: 2px solid #ff0000;</code> - Red top border<br>
                                • <code>border-left: 3px double #0000ff;</code> - Blue double left border<br>
                                • Leave empty for no border
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Section Header Padding</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[section_header_padding]" class="form-control"
                                value="{{ setting('general', 'section_header_padding', '2px 5px') }}"
                                placeholder="2px 5px">
                            <small class="form-text text-muted">Padding for section headers (e.g., 2px 5px, 10px 15px).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Section Header Border Radius</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[section_header_border_radius]"
                                class="form-control"
                                value="{{ setting('general', 'section_header_border_radius', '8px') }}"
                                placeholder="8px">
                            <small class="form-text text-muted">Border radius for section headers (e.g., 8px, 4px, 0px).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Section Header Left Bar</label>
                        <div class="col-md-9">
                            <select name="settings[section_header_left_bar]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'section_header_left_bar', '0') == '1' ? 'selected' : '' }}>
                                    Show Left Bar</option>
                                <option value="0"
                                    {{ setting('general', 'section_header_left_bar', '0') == '0' ? 'selected' : '' }}>
                                    Hide Left Bar</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the colored left bar on section headers.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Section Header Left Bar Color</label>
                        <div class="col-md-6">
                            <input type="text" name="settings[section_header_left_bar_color]"
                                class="form-control color-input" id="section_header_left_bar_color_input"
                                value="{{ setting('general', 'section_header_left_bar_color', 'var(--primary-color)') }}"
                                placeholder="var(--primary-color) or #F02627">
                            <small class="form-text text-muted">Color for the left bar on section headers.</small>
                        </div>
                        <div class="col-md-3">
                            <span id="section_header_left_bar_color_preview"
                                style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'section_header_left_bar_color', 'var(--primary-color)') }};"></span>
                        </div>
                    </div>
                </div>

                <div id="typography" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Typography Settings</h2>
                    </div>

                    @php
                        $websiteFontSetting = setting('general', 'website_font', 'hind-siliguri');
                        $websiteFontPreviewMap = [
                            'hind-siliguri' => 'Hind Siliguri',
                            'inter' => 'Inter',
                            'noto-sans-bengali' => 'Noto Sans Bengali',
                        ];
                        $websiteFontPreviewFamily = $websiteFontPreviewMap[$websiteFontSetting] ?? 'Hind Siliguri';
                    @endphp

                    <div class="form-group row align-items-center">
                        <label class="col-md-3 col-form-label">Website Font</label>
                        <div class="col-md-6">
                            <select name="settings[website_font]" class="form-control" id="website_font_select">
                                <option value="hind-siliguri" {{ $websiteFontSetting == 'hind-siliguri' ? 'selected' : '' }}>Hind Siliguri (Bengali)</option>
                                <option value="inter" {{ $websiteFontSetting == 'inter' ? 'selected' : '' }}>Inter (English)</option>
                                <option value="noto-sans-bengali" {{ $websiteFontSetting == 'noto-sans-bengali' ? 'selected' : '' }}>Noto Sans Bengali (Bengali)</option>
                            </select>
                            <small class="form-text text-muted">Choose the main font for your website</small>
                        </div>
                        <div class="col-md-3">
                            <div id="font_preview" class="p-2 border rounded" style="font-family: {{ $websiteFontPreviewFamily }}, sans-serif;">
                                AaBbCc ১২৩৪৫৬
                            </div>
                        </div>
                    </div>
                </div>

                <div id="colors" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Colors Settings</h2>
                    </div>

                    <div class="form-group row align-items-center">
                        <label class="col-md-3 col-form-label">Primary Color</label>
                        <div class="col-md-6">
                            <input type="text" name="settings[primary_color]"
                                class="form-control mb-2 color-input" id="primary_color_input"
                                value="{{ setting('general', 'primary_color', '#F02627') }}"
                                placeholder="#F02627 or rgb(241,134,47)">
                            <small class="form-text text-muted">Main brand color (used for buttons, highlights, etc.)</small>
                        </div>
                        <div class="col-md-3">
                            <span id="primary_color_preview"
                                style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'primary_color', '#F02627') }};"></span>
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label class="col-md-3 col-form-label">Secondary Color</label>
                        <div class="col-md-6">
                            <input type="text" name="settings[secondary_color]"
                                class="form-control mb-2 color-input" id="secondary_color_input"
                                value="{{ setting('general', 'secondary_color', '#113056') }}"
                                placeholder="#113056 or rgb(17,48,86)">
                            <small class="form-text text-muted">Secondary brand color (used for backgrounds, etc.)</small>
                        </div>
                        <div class="col-md-3">
                            <span id="secondary_color_preview"
                                style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'secondary_color', '#113056') }};"></span>
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <label class="col-md-3 col-form-label">Accent Color</label>
                        <div class="col-md-6">
                            <input type="text" name="settings[accent_color]"
                                class="form-control mb-2 color-input" id="accent_color_input"
                                value="{{ setting('general', 'accent_color', '#F02627') }}"
                                placeholder="#F02627 or rgb(241,38,39)">
                            <small class="form-text text-muted">Accent color for special highlights.</small>
                        </div>
                        <div class="col-md-3">
                            <span id="accent_color_preview"
                                style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'accent_color', '#F02627') }};"></span>
                        </div>
                    </div>
                </div>

                <div id="contact" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Contact Information</h2>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Phone Number</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[phone_number]" class="form-control mb-2"
                                value="{{ setting('general', 'phone_number', '') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">WhatsApp Number</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[whatsapp_number]" class="form-control mb-2"
                                value="{{ setting('general', 'whatsapp_number', '') }}">
                            <small class="form-text text-muted">Include country code (e.g., +1234567890)</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contact Email</label>
                        <div class="col-md-9">
                            <input type="email" name="settings[contact_email]" class="form-control mb-2"
                                value="{{ setting('general', 'contact_email', '') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Address</label>
                        <div class="col-md-9">
                            <textarea name="settings[address]" class="form-control" rows="3">{{ setting('general', 'address', '') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contact Page Heading</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[contact_heading]" class="form-control mb-2"
                                value="{{ setting('general', 'contact_heading', 'Get In Touch') }}">
                            <small class="form-text text-muted">Main heading for the contact page.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contact Location</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[contact_location]" class="form-control mb-2"
                                value="{{ setting('general', 'contact_location', '27 Shaptak Square, Level-7, Holding-02, Road-27, Dhanmondi, Dhaka') }}">
                            <small class="form-text text-muted">Physical address shown on the contact page.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contact Support Email</label>
                        <div class="col-md-9">
                            <input type="email" name="settings[contact_support_email]" class="form-control mb-2"
                                value="{{ setting('general', 'contact_support_email', 'contact@uddoktaecommerce.com') }}">
                            <small class="form-text text-muted">Support email shown on the contact page.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contact WhatsApp</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[contact_whatsapp]" class="form-control mb-2"
                                value="{{ setting('general', 'contact_whatsapp', '+8801304224233') }}">
                            <small class="form-text text-muted">WhatsApp number for contact page.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Contact Map Embed URL</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[contact_map_url]" class="form-control mb-2"
                                value="{{ setting('general', 'contact_map_url', 'https://www.google.com/maps/embed?...') }}">
                            <small class="form-text text-muted">Google Maps embed URL for the contact page map.</small>
                        </div>
                    </div>
                </div>

                <div id="social" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Social Media Links</h2>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            <i class="fab fa-facebook text-primary mr-1"></i> Facebook
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="settings[facebook_url]" class="form-control mb-2"
                                value="{{ setting('general', 'facebook_url', '') }}"
                                placeholder="https://facebook.com/yourpage">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            <i class="fab fa-twitter text-info mr-1"></i> Twitter
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="settings[twitter_url]" class="form-control mb-2"
                                value="{{ setting('general', 'twitter_url', '') }}"
                                placeholder="https://twitter.com/yourhandle">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            <i class="fab fa-instagram text-danger mr-1"></i> Instagram
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="settings[instagram_url]" class="form-control mb-2"
                                value="{{ setting('general', 'instagram_url', '') }}"
                                placeholder="https://instagram.com/yourprofile">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            <i class="fab fa-linkedin text-primary mr-1"></i> LinkedIn
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="settings[linkedin_url]" class="form-control mb-2"
                                value="{{ setting('general', 'linkedin_url', '') }}"
                                placeholder="https://linkedin.com/company/yourcompany">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            <i class="fab fa-youtube text-danger mr-1"></i> YouTube
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="settings[youtube_url]" class="form-control mb-2"
                                value="{{ setting('general', 'youtube_url', '') }}"
                                placeholder="https://youtube.com/c/yourchannel">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            <i class="fab fa-whatsapp text-danger mr-1"></i> WhatsApp
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="settings[whatsapp_url]" class="form-control mb-2"
                                value="{{ setting('general', 'whatsapp_url', '') }}"
                                placeholder="https://wa.me/yournumber">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">
                            <i class="fab fa-tiktok mr-1" style="color: #000;"></i> TikTok
                        </label>
                        <div class="col-md-9">
                            <input type="text" name="settings[tiktok_url]" class="form-control mb-2"
                                value="{{ setting('general', 'tiktok_url', '') }}"
                                placeholder="https://tiktok.com/@yourhandle">
                        </div>
                    </div>
                </div>

                <div id="header" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Header Customization</h2>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Top Header Bar?</label>
                        <div class="col-md-9">
                            <select name="settings[top_header_bar_show]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'top_header_bar_show', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'top_header_bar_show', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Top Header Version</label>
                        <div class="col-md-9">
                            <select name="settings[top_header_bar_version]" class="form-control">
                                <option value="v1"
                                    {{ setting('general', 'top_header_bar_version', 'v1') == 'v1' ? 'selected' : '' }}>
                                    Version 1 (Contact Info)</option>
                                <option value="v2"
                                    {{ setting('general', 'top_header_bar_version', 'v1') == 'v2' ? 'selected' : '' }}>
                                    Version 2 (Dynamic Menus)</option>
                                <option value="v3"
                                    {{ setting('general', 'top_header_bar_version', 'v1') == 'v3' ? 'selected' : '' }}>
                                    Version 3 (Phone + Marquee)</option>
                            </select>
                            <small class="form-text text-muted">Choose between contact info layout (v1), dynamic menu layout (v2), or phone + marquee layout (v3).</small>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Show on Mobile?</label>
                        <div class="col-md-9">
                            <select name="settings[top_header_bar_show_mobile]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'top_header_bar_show_mobile', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'top_header_bar_show_mobile', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                            <small class="form-text text-muted">Choose whether to show the top header on mobile devices.</small>
                        </div>
                    </div>

                    <!-- Top Header v2 Menu Options -->
                    <div class="form-group row mt-3" id="top-header-v2-options" style="display: none;">
                        <label class="col-md-3 col-form-label">Top Menu v2 Left</label>
                        <div class="col-md-9">
                            <select name="settings[top_header_v2_left_menu]" class="form-control">
                                <option value="">Select Menu</option>
                                @php
                                $menus = \App\Models\Menu::where('status', true)->get();
                                $selectedLeftMenu = setting('general', 'top_header_v2_left_menu', '');
                                @endphp
                                @foreach($menus as $menu)
                                <option value="{{ $menu->id }}" {{ $selectedLeftMenu == $menu->id ? 'selected' : '' }}>
                                    {{ $menu->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Select menu for the left side of Top Header v2.</small>
                        </div>
                    </div>

                    <div class="form-group row mt-2" id="top-header-v2-right-options" style="display: none;">
                        <label class="col-md-3 col-form-label">Top Menu v2 Right</label>
                        <div class="col-md-9">
                            <select name="settings[top_header_v2_right_menu]" class="form-control">
                                <option value="">Select Menu</option>
                                @php
                                $selectedRightMenu = setting('general', 'top_header_v2_right_menu', '');
                                @endphp
                                @foreach($menus as $menu)
                                <option value="{{ $menu->id }}" {{ $selectedRightMenu == $menu->id ? 'selected' : '' }}>
                                    {{ $menu->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Select menu for the right side of Top Header v2.</small>
                        </div>
                    </div>

                    <!-- Top Header v3 Options -->
                    <div class="form-group row mt-2" id="top-header-v3-options" style="display: none;">
                        <label class="col-md-3 col-form-label">Marquee Text (v3)</label>
                        <div class="col-md-9">
                            <textarea name="settings[top_header_bar_marquee_text]" class="form-control" rows="2">{{ setting('general', 'top_header_bar_marquee_text', 'অনলাইন শপে আপনাকে স্বাগতম। অনলাইনে অর্ডারে সারা বাংলাদেশে হোম ডেলিভারি।') }}</textarea>
                            <small class="form-text text-muted">Shown in the scrolling text on Top Header v3. Uses the phone number from the contact options for the left side.</small>
                        </div>
                    </div>
                    <div class="form-group row mt-2" id="top-header-v3-phone" style="display: none;">
                        <label class="col-md-3 col-form-label">Phone (v3)</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[top_header_bar_phone]" class="form-control"
                                value="{{ setting('general', 'top_header_bar_phone', '+8801723-000000') }}">
                            <small class="form-text text-muted">Number shown on the left of Top Header v3.</small>
                        </div>
                    </div>
                    <div class="form-group row mt-2" id="top-header-v3-speed" style="display: none;">
                        <label class="col-md-3 col-form-label">Marquee Speed (seconds)</label>
                        <div class="col-md-9">
                            <input type="number" min="4" step="1" name="settings[top_header_bar_marquee_speed]" class="form-control"
                                value="{{ setting('general', 'top_header_bar_marquee_speed', '24') }}">
                            <small class="form-text text-muted">Lower is faster. Minimum 4s is enforced.</small>
                        </div>
                    </div>

                    <!-- Top Header v1 Options -->
                    <div id="top-header-v1-options">
                        <div class="form-group row mt-2">
                            <label class="col-md-3 col-form-label">Left Content</label>
                            <div class="col-md-9">
                                <input type="text" name="settings[top_header_bar_left]" class="form-control"
                                    value="{{ setting('general', 'top_header_bar_left', 'অনলাইন বই দোকানে আপনাকে স্বাগতম!') }}">
                                <small class="form-text text-muted">Text shown on the left side of the top header bar.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Email</label>
                            <div class="col-md-9">
                                <input type="text" name="settings[top_header_bar_email]" class="form-control"
                                    value="{{ setting('general', 'top_header_bar_email', 'example@gmail.com') }}">
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label class="col-md-3 col-form-label">Phone</label>
                            <div class="col-md-9">
                                <input type="text" id="top-header-v1-phone" name="settings[top_header_bar_phone]" class="form-control"
                                    value="{{ setting('general', 'top_header_bar_phone', '+8801723-000000') }}">
                            </div>
                        </div>

                        <!-- Top Header v1 Layout Options -->
                        <div class="form-group row mt-3">
                            <label class="col-md-3 col-form-label">Show Email Link?</label>
                            <div class="col-md-9">
                                <select name="settings[top_header_bar_show_email]" class="form-control">
                                    <option value="1" {{ setting('general', 'top_header_bar_show_email', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'top_header_bar_show_email', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label class="col-md-3 col-form-label">Show Phone Link?</label>
                            <div class="col-md-9">
                                <select name="settings[top_header_bar_show_phone]" class="form-control">
                                    <option value="1" {{ setting('general', 'top_header_bar_show_phone', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'top_header_bar_show_phone', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label class="col-md-3 col-form-label">Show Track Order Link?</label>
                            <div class="col-md-9">
                                <select name="settings[top_header_bar_show_track_order]" class="form-control">
                                    <option value="1" {{ setting('general', 'top_header_bar_show_track_order', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'top_header_bar_show_track_order', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mt-2">
                            <label class="col-md-3 col-form-label">Track Order Text</label>
                            <div class="col-md-9">
                                <input type="text" name="settings[top_header_bar_track_order_text]" class="form-control"
                                    value="{{ setting('general', 'top_header_bar_track_order_text', 'Track Your Order') }}">
                                <small class="form-text text-muted">Customize the text for the track order link.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Top Header Styling Options (Common for both versions) -->
                    <div class="form-group row mt-3">
                        <label class="col-md-3 col-form-label">Background Color</label>
                        <div class="col-md-9">
                            <input type="color" name="settings[top_header_bar_bg_color]" class="form-control"
                                value="{{ setting('general', 'top_header_bar_bg_color', '#2c3e50') }}">
                            <small class="form-text text-muted">Choose the background color for the top header bar.</small>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Text Color</label>
                        <div class="col-md-9">
                            <input type="color" name="settings[top_header_bar_text_color]" class="form-control"
                                value="{{ setting('general', 'top_header_bar_text_color', '#ffffff') }}">
                            <small class="form-text text-muted">Choose the text color for the top header bar.</small>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Font Size</label>
                        <div class="col-md-9">
                            <select name="settings[top_header_bar_font_size]" class="form-control">
                                <option value="12px" {{ setting('general', 'top_header_bar_font_size', '15px') == '12px' ? 'selected' : '' }}>12px</option>
                                <option value="13px" {{ setting('general', 'top_header_bar_font_size', '15px') == '13px' ? 'selected' : '' }}>13px</option>
                                <option value="14px" {{ setting('general', 'top_header_bar_font_size', '15px') == '14px' ? 'selected' : '' }}>14px</option>
                                <option value="15px" {{ setting('general', 'top_header_bar_font_size', '15px') == '15px' ? 'selected' : '' }}>15px</option>
                                <option value="16px" {{ setting('general', 'top_header_bar_font_size', '15px') == '16px' ? 'selected' : '' }}>16px</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Main Header with logo Layout</label>
                        <div class="col-md-9">
                            <select name="settings[header_layout]" class="form-control">
                                <option value="v1"
                                    {{ setting('general', 'header_layout', 'v1') == 'v1' ? 'selected' : '' }}>
                                    Header Layout 1</option>
                                <option value="v2"
                                    {{ setting('general', 'header_layout', 'v1') == 'v2' ? 'selected' : '' }}>
                                    Header Layout 2</option>
                                <option value="v3"
                                    {{ setting('general', 'header_layout', 'v1') == 'v3' ? 'selected' : '' }}>
                                    Header Layout 3</option>
                                <option value="v4"
                                    {{ setting('general', 'header_layout', 'v1') == 'v4' ? 'selected' : '' }}>
                                    Header Layout 4</option>
                                <option value="v5"
                                    {{ setting('general', 'header_layout', 'v1') == 'v5' ? 'selected' : '' }}>
                                    Header Layout 5</option>
                            </select>
                            <small class="form-text text-muted">Choose which header layout to display on the frontend.</small>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Header Icons Color</label>
                        <div class="col-md-6">
                            <input type="text" name="settings[header_icons_color]" class="form-control color-input" id="header_icons_color_input"
                                value="{{ setting('general', 'header_icons_color', '#ffffff') }}"
                                placeholder="#ffffff or rgb(255,255,255)">
                            <small class="form-text text-muted">Color for header icons (cart, user/profile, hamburger menu) - applies to all header layouts.</small>
                        </div>
                        <div class="col-md-3">
                            <span id="header_icons_color_preview"
                                style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'header_icons_color', '#ffffff') }};"></span>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <label class="col-md-3 col-form-label">Custom Header Code</label>
                        <div class="col-md-9">
                            <div class="code-editor-container">
                                <div id="header-code-editor" class="ace-editor"></div>
                                <textarea name="settings[custom_header_code]" class="form-control code-editor" rows="8" placeholder="<!-- Enter custom HTML, CSS, or JavaScript code here -->
                                    <script>
                                    // Your custom JavaScript code
                                    </script>
                                    <style>
                                    /* Your custom CSS code */
                                    </style>">{{ setting('general', 'custom_header_code', '') }}
                                </textarea>
                            </div>
                            <small class="form-text text-muted">Add custom code (HTML, CSS, JavaScript) that will be included in the website header. This can include tracking codes, meta tags, or custom styles.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Main Navigation?</label>
                        <div class="col-md-9">
                            <select name="settings[main_navigation_show]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'main_navigation_show', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'main_navigation_show', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the main navigation menu on the frontend.</small>
                        </div>
                    </div>

                    <!-- Main Navigation Styling Configuration -->
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Main Navigation Styling</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Background Color</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[main_nav_background_color]" class="form-control color-input" id="main_nav_background_color_input"
                                        value="{{ setting('general', 'main_nav_background_color', '#ff6925') }}"
                                        placeholder="#ff6925 or rgb(255,105,37)">
                                    <small class="form-text text-muted">Background color for the main navigation bar.</small>
                                </div>
                                <div class="col-md-3">
                                    <span id="main_nav_background_color_preview"
                                        style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'main_nav_background_color', '#ff6925') }};"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Box Shadow</label>
                                <div class="col-md-9">
                                    <select name="settings[main_nav_box_shadow_preset]" class="form-control mb-2" id="main_nav_box_shadow_preset">
                                        <option value="none" {{ setting('general', 'main_nav_box_shadow_preset', 'default') == 'none' ? 'selected' : '' }}>No Shadow</option>
                                        <option value="default" {{ setting('general', 'main_nav_box_shadow_preset', 'default') == 'default' ? 'selected' : '' }}>Default Shadow</option>
                                        <option value="subtle" {{ setting('general', 'main_nav_box_shadow_preset', 'default') == 'subtle' ? 'selected' : '' }}>Subtle Shadow</option>
                                        <option value="medium" {{ setting('general', 'main_nav_box_shadow_preset', 'default') == 'medium' ? 'selected' : '' }}>Medium Shadow</option>
                                        <option value="strong" {{ setting('general', 'main_nav_box_shadow_preset', 'default') == 'strong' ? 'selected' : '' }}>Strong Shadow</option>
                                        <option value="elegant" {{ setting('general', 'main_nav_box_shadow_preset', 'default') == 'elegant' ? 'selected' : '' }}>Elegant Shadow</option>
                                        <option value="custom" {{ setting('general', 'main_nav_box_shadow_preset', 'default') == 'custom' ? 'selected' : '' }}>Custom Shadow</option>
                                    </select>
                                    <input type="text" name="settings[main_nav_box_shadow]" class="form-control" id="main_nav_box_shadow_custom"
                                        value="{{ setting('general', 'main_nav_box_shadow', '0 3px 5px rgba(57, 63, 72, 0.3)') }}"
                                        placeholder="0 3px 5px rgba(57, 63, 72, 0.3)"
                                        style="display: {{ setting('general', 'main_nav_box_shadow_preset', 'default') == 'custom' ? 'block' : 'none' }};">
                                    <small class="form-text text-muted">Choose a preset shadow or enter custom CSS box-shadow value.</small>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Font Size</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[main_nav_font_size]" class="form-control"
                                        value="{{ setting('general', 'main_nav_font_size', '16px') }}"
                                        placeholder="16px">
                                    <small class="form-text text-muted">Font size for navigation menu items.</small>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Text Color</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[main_nav_text_color]" class="form-control color-input" id="main_nav_text_color_input"
                                        value="{{ setting('general', 'main_nav_text_color', '#fff') }}"
                                        placeholder="#fff or rgb(255,255,255)">
                                    <small class="form-text text-muted">Text color for navigation menu items.</small>
                                </div>
                                <div class="col-md-3">
                                    <span id="main_nav_text_color_preview"
                                        style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'main_nav_text_color', '#fff') }};"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Padding</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[main_nav_padding]" class="form-control"
                                        value="{{ setting('general', 'main_nav_padding', '18px 24px') }}"
                                        placeholder="18px 24px">
                                    <small class="form-text text-muted">Padding for navigation menu items.</small>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Hover Background Color</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[main_nav_hover_bg_color]" class="form-control color-input" id="main_nav_hover_bg_color_input"
                                        value="{{ setting('general', 'main_nav_hover_bg_color', 'var(--light-color)') }}"
                                        placeholder="var(--light-color) or #f8f9fa">
                                    <small class="form-text text-muted">Background color on hover for navigation items.</small>
                                </div>
                                <div class="col-md-3">
                                    <span id="main_nav_hover_bg_color_preview"
                                        style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'main_nav_hover_bg_color', 'var(--light-color)') }};"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Hover Text Color</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[main_nav_hover_text_color]" class="form-control color-input" id="main_nav_hover_text_color_input"
                                        value="{{ setting('general', 'main_nav_hover_text_color', 'var(--primary-color)') }}"
                                        placeholder="var(--primary-color) or #F02627">
                                    <small class="form-text text-muted">Text color on hover for navigation items.</small>
                                </div>
                                <div class="col-md-3">
                                    <span id="main_nav_hover_text_color_preview"
                                        style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'main_nav_hover_text_color', 'var(--primary-color)') }};"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Border Right Color</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[main_nav_border_right_color]" class="form-control color-input" id="main_nav_border_right_color_input"
                                        value="{{ setting('general', 'main_nav_border_right_color', '#ffffffb5') }}"
                                        placeholder="#ffffffb5 or rgba(255,255,255,0.71)">
                                    <small class="form-text text-muted">Color for the right border between navigation items.</small>
                                </div>
                                <div class="col-md-3">
                                    <span id="main_nav_border_right_color_preview"
                                        style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'main_nav_border_right_color', '#ffffffb5') }};"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown/Submenu Styling Configuration -->
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Dropdown/Submenu Styling</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Dropdown Background Color</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[dropdown_bg_color]" class="form-control color-input" id="dropdown_bg_color_input"
                                        value="{{ setting('general', 'dropdown_bg_color', '#ffffff') }}"
                                        placeholder="#ffffff or rgb(255,255,255)">
                                    <small class="form-text text-muted">Background color for dropdown/submenu items.</small>
                                </div>
                                <div class="col-md-3">
                                    <span id="dropdown_bg_color_preview"
                                        style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'dropdown_bg_color', '#ffffff') }};"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Dropdown Text Color</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[dropdown_text_color]" class="form-control color-input" id="dropdown_text_color_input"
                                        value="{{ setting('general', 'dropdown_text_color', '#333333') }}"
                                        placeholder="#333333 or rgb(51,51,51)">
                                    <small class="form-text text-muted">Text color for dropdown/submenu items.</small>
                                </div>
                                <div class="col-md-3">
                                    <span id="dropdown_text_color_preview"
                                        style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'dropdown_text_color', '#333333') }};"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Dropdown Hover Background</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[dropdown_hover_bg_color]" class="form-control color-input" id="dropdown_hover_bg_color_input"
                                        value="{{ setting('general', 'dropdown_hover_bg_color', '#f8f9fa') }}"
                                        placeholder="#f8f9fa or var(--light-color)">
                                    <small class="form-text text-muted">Background color on hover for dropdown items.</small>
                                </div>
                                <div class="col-md-3">
                                    <span id="dropdown_hover_bg_color_preview"
                                        style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'dropdown_hover_bg_color', '#f8f9fa') }};"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Dropdown Hover Text Color</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[dropdown_hover_text_color]" class="form-control color-input" id="dropdown_hover_text_color_input"
                                        value="{{ setting('general', 'dropdown_hover_text_color', 'var(--primary-color)') }}"
                                        placeholder="var(--primary-color) or #F02627">
                                    <small class="form-text text-muted">Text color on hover for dropdown items.</small>
                                </div>
                                <div class="col-md-3">
                                    <span id="dropdown_hover_text_color_preview"
                                        style="display:inline-block;width:32px;height:32px;border-radius:4px;border:1px solid #ccc;background:{{ setting('general', 'dropdown_hover_text_color', 'var(--primary-color)') }};"></span>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Dropdown Font Size</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[dropdown_font_size]" class="form-control"
                                        value="{{ setting('general', 'dropdown_font_size', '14px') }}"
                                        placeholder="14px">
                                    <small class="form-text text-muted">Font size for dropdown/submenu items.</small>
                                </div>
                            </div>

                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Dropdown Padding</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[dropdown_padding]" class="form-control"
                                        value="{{ setting('general', 'dropdown_padding', '10px 15px') }}"
                                        placeholder="10px 15px">
                                    <small class="form-text text-muted">Padding for dropdown/submenu items.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="footer" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Footer Content</h2>
                    </div>

                    <div class="form-group">
                        <label>About Website (Footer Text)</label>
                        <textarea name="settings[about_website]" class="form-control" rows="4">{{ setting('general', 'about_website', '') }}</textarea>
                        <small class="form-text text-muted">This text will appear in the footer section of your website</small>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Footer Copyright Text</label>
                        <div class="col-md-9">
                            <input type="text" name="settings[footer_copyright]" class="form-control"
                                value="{{ setting('general', 'footer_copyright', '© 2025 Thikana . All Rights Reserved. Developed By SOFTEB.COM') }}">
                            <small class="form-text text-muted">This text will appear in the footer of your website.</small>
                        </div>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Show Newsletter Section?</label>
                        <div class="col-md-9">
                            <select name="settings[newsletter_show]" class="form-control">
                                <option value="1"
                                    {{ setting('general', 'newsletter_show', '1') == '1' ? 'selected' : '' }}>
                                    Show</option>
                                <option value="0"
                                    {{ setting('general', 'newsletter_show', '1') == '0' ? 'selected' : '' }}>
                                    Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the newsletter subscription section in the footer.</small>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <label class="col-md-3 col-form-label">Custom Footer Code</label>
                        <div class="col-md-9">
                            <div class="code-editor-container">
                                <div id="footer-code-editor" class="ace-editor"></div>
                                <textarea name="settings[custom_footer_code]" class="form-control code-editor" rows="8" placeholder="<!-- Enter custom HTML, CSS, or JavaScript code here -->
                                        <script>
                                        // Your custom JavaScript code
                                        </script>
                                        <style>
                                        /* Your custom CSS code */
                                        </style>">{{ setting('general', 'custom_footer_code', '') }}
                                </textarea>
                            </div>
                            <small class="form-text text-muted">Add custom code (HTML, CSS, JavaScript) that will be included in the website footer. This can include tracking codes, analytics scripts, or custom elements.</small>
                        </div>
                    </div>
                </div>

                <div id="ecommerce" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Ecommerce Settings</h2>
                    </div>

                    <h6 class="text-primary">Landing Page Call Button</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Landing Call Button?</label>
                        <div class="col-md-9">
                            <select name="ecommerce[landing_call_button_show]" class="form-control">
                                <option value="1" {{ setting('ecommerce', 'landing_call_button_show', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('ecommerce', 'landing_call_button_show', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the floating call button on landing pages.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Landing Call Button Number</label>
                        <div class="col-md-9">
                            <input type="text" name="ecommerce[landing_call_button_number]" class="form-control"
                                value="{{ setting('ecommerce', 'landing_call_button_number', '') }}"
                                placeholder="+8801XXXXXXXXX">
                            <small class="form-text text-muted">Phone number used for the landing page call button. Leave empty to use the primary contact phone number.</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Free Shipping Settings</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Free Shipping Amount</label>
                        <div class="col-md-9">
                            <input type="number" name="settings[free_shipping_amount]" class="form-control"
                                value="{{ setting('general', 'free_shipping_amount', 0) }}" min="0" step="1">
                            <small class="form-text text-muted">Set the minimum order amount (in your currency) for free shipping. Set to 0 to disable free shipping.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Progress Bar?</label>
                        <div class="col-md-9">
                            <select name="settings[show_free_shipping_progress]" class="form-control">
                                <option value="1" {{ setting('general', 'show_free_shipping_progress', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_free_shipping_progress', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the free shipping progress bar on the frontend.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show on Desktop?</label>
                        <div class="col-md-9">
                            <select name="settings[show_free_shipping_progress_desktop]" class="form-control">
                                <option value="1" {{ setting('general', 'show_free_shipping_progress_desktop', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_free_shipping_progress_desktop', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the free shipping progress bar on desktop devices.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show on Mobile?</label>
                        <div class="col-md-9">
                            <select name="settings[show_free_shipping_progress_mobile]" class="form-control">
                                <option value="1" {{ setting('general', 'show_free_shipping_progress_mobile', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_free_shipping_progress_mobile', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the free shipping progress bar on mobile devices.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Desktop Position</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Bottom (px)</label>
                                    <input type="number" name="settings[free_shipping_progress_bottom_desktop]" class="form-control"
                                        value="{{ setting('general', 'free_shipping_progress_bottom_desktop', '0') }}" placeholder="0">
                                    <small class="form-text text-muted">Distance from bottom on desktop.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Right (px)</label>
                                    <input type="number" name="settings[free_shipping_progress_right_desktop]" class="form-control"
                                        value="{{ setting('general', 'free_shipping_progress_right_desktop', '69') }}" placeholder="69">
                                    <small class="form-text text-muted">Distance from right on desktop.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Mobile Position</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Bottom (px)</label>
                                    <input type="number" name="settings[free_shipping_progress_bottom_mobile]" class="form-control"
                                        value="{{ setting('general', 'free_shipping_progress_bottom_mobile', '37') }}" placeholder="37">
                                    <small class="form-text text-muted">Distance from bottom on mobile.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Right (px)</label>
                                    <input type="number" name="settings[free_shipping_progress_right_mobile]" class="form-control"
                                        value="{{ setting('general', 'free_shipping_progress_right_mobile', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from right on mobile.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Floating Cart Settings</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show Floating Cart?</label>
                        <div class="col-md-9">
                            <select name="settings[show_floating_cart]" class="form-control">
                                <option value="1" {{ setting('general', 'show_floating_cart', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_floating_cart', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the floating cart button on the frontend.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show on Desktop?</label>
                        <div class="col-md-9">
                            <select name="settings[show_floating_cart_desktop]" class="form-control">
                                <option value="1" {{ setting('general', 'show_floating_cart_desktop', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_floating_cart_desktop', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the floating cart button on desktop devices.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show on Mobile?</label>
                        <div class="col-md-9">
                            <select name="settings[show_floating_cart_mobile]" class="form-control">
                                <option value="1" {{ setting('general', 'show_floating_cart_mobile', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_floating_cart_mobile', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the floating cart button on mobile devices.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Desktop Position</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Bottom (px)</label>
                                    <input type="number" name="settings[floating_cart_bottom_desktop]" class="form-control"
                                        value="{{ setting('general', 'floating_cart_bottom_desktop', '21') }}" placeholder="21">
                                    <small class="form-text text-muted">Distance from bottom on desktop.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Right (px)</label>
                                    <input type="number" name="settings[floating_cart_right_desktop]" class="form-control"
                                        value="{{ setting('general', 'floating_cart_right_desktop', '30') }}" placeholder="30">
                                    <small class="form-text text-muted">Distance from right on desktop.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Mobile Position</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Bottom (px)</label>
                                    <input type="number" name="settings[floating_cart_bottom_mobile]" class="form-control"
                                        value="{{ setting('general', 'floating_cart_bottom_mobile', '55') }}" placeholder="55">
                                    <small class="form-text text-muted">Distance from bottom on mobile.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Right (px)</label>
                                    <input type="number" name="settings[floating_cart_right_mobile]" class="form-control"
                                        value="{{ setting('general', 'floating_cart_right_mobile', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from right on mobile.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="homepage" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Homepage Customization</h2>
                    </div>

                    <!-- Homepage Template Selection Card -->
                    @php
                        $isSuperAdmin = auth()->check() && auth()->user()->isSuperAdmin();
                        $currentUser = auth()->user();
                        $selectedTemplate = $currentUser && !empty($currentUser->template_id) 
                            ? (string)$currentUser->template_id 
                            : setting('homepage', 'template_id', '1');
                    @endphp
                    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-bottom: 1px solid #edf2f7;">
                            <div>
                                <h5 class="card-title mb-1 font-weight-bold" style="color: #1e293b; font-size: 17px;">
                                    <i class="fas fa-layer-group text-primary mr-2"></i> Homepage Template & Layout
                                </h5>
                                <p class="text-muted mb-0 small">
                                    @if($isSuperAdmin)
                                        Select a pre-designed homepage theme architecture for your store or subdomain.
                                    @else
                                        Your assigned homepage website theme architecture (configured by Super Administrator).
                                    @endif
                                </p>
                            </div>
                            <span class="badge {{ $isSuperAdmin ? 'badge-primary' : 'badge-success' }} px-3 py-2" style="border-radius: 20px; font-weight: 600; letter-spacing: 0.5px;">
                                @if($isSuperAdmin)
                                    Active: Template {{ $selectedTemplate }}
                                @else
                                    <i class="fas fa-shield-alt mr-1"></i> Assigned: Template {{ $selectedTemplate }}
                                @endif
                            </span>
                        </div>
                        <div class="card-body bg-light-50 p-4">
                            <input type="hidden" name="homepage[template_id]" id="selected_homepage_template" value="{{ $selectedTemplate }}">

                            <style>
                                .template-selector-grid {
                                    display: grid;
                                    grid-template-columns: repeat(2, 1fr);
                                    gap: 14px;
                                }
                                @media (max-width: 768px) {
                                    .template-selector-grid {
                                        grid-template-columns: 1fr;
                                    }
                                }
                                .template-card {
                                    background: #ffffff;
                                    border-radius: 12px;
                                    border: 2px solid #e2e8f0;
                                    padding: 12px 14px;
                                    cursor: pointer;
                                    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                                    position: relative;
                                    display: flex;
                                    flex-direction: column;
                                    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
                                }
                                .template-card:hover {
                                    transform: translateY(-2px);
                                    border-color: #93c5fd;
                                    box-shadow: 0 8px 20px rgba(59, 130, 246, 0.1);
                                }
                                .template-card.active-template-card {
                                    border-color: #2563eb !important;
                                    background: #ffffff;
                                    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.16) !important;
                                }
                                .template-card.active-template-card::before {
                                    content: 'ACTIVE';
                                    position: absolute;
                                    top: -9px;
                                    right: 14px;
                                    background: linear-gradient(135deg, #2563eb, #1d4ed8);
                                    color: #ffffff;
                                    font-size: 8px;
                                    font-weight: 800;
                                    letter-spacing: 0.8px;
                                    padding: 2px 8px;
                                    border-radius: 20px;
                                    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35);
                                }
                                .tpl-preview-box {
                                    height: 95px;
                                    border-radius: 8px;
                                    overflow: hidden;
                                    position: relative;
                                    margin-bottom: 8px;
                                    border: 1px solid #e2e8f0;
                                    box-shadow: inset 0 1px 4px rgba(0,0,0,0.03);
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: space-between;
                                    padding: 6px;
                                }
                            </style>

                            <div class="template-selector-grid" style="{{ !$isSuperAdmin ? 'grid-template-columns: 1fr; max-width: 540px;' : '' }}">
                                @if($isSuperAdmin || $selectedTemplate == '1')
                                <!-- Template 1 -->
                                <div class="template-card {{ $selectedTemplate == '1' ? 'active-template-card' : '' }}" data-template="1">
                                    <div class="tpl-preview-box" style="background: #f8fafc; border-color: #fed7aa;">
                                        <div style="height: 10px; background: #ea580c; border-radius: 3px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px;">
                                            <div style="width: 18px; height: 3px; background: #ffffff; border-radius: 1px;"></div>
                                            <div style="display: flex; gap: 3px;">
                                                <div style="width: 14px; height: 3px; background: rgba(255,255,255,0.8); border-radius: 1px;"></div>
                                                <div style="width: 14px; height: 3px; background: rgba(255,255,255,0.8); border-radius: 1px;"></div>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 4px; flex: 1; margin: 4px 0;">
                                            <div style="width: 26%; background: #ffffff; border: 1px solid #fed7aa; border-radius: 4px; padding: 2px; display: flex; flex-direction: column; justify-content: space-around;">
                                                <div style="height: 3px; background: #fdba74; border-radius: 1px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; border-radius: 1px;"></div>
                                                <div style="height: 3px; background: #e2e8f0; border-radius: 1px;"></div>
                                            </div>
                                            <div style="flex: 1; background: linear-gradient(135deg, #ea580c, #f97316); border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; padding: 2px;">
                                                <span style="font-size: 7.5px; font-weight: 800; letter-spacing: 0.3px;">MARKETPLACE</span>
                                                <span style="font-size: 6px; opacity: 0.9;">Drawer + Slider</span>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 4px;">
                                            <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #fed7aa; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #ffedd5; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #ea580c; border-radius: 1px;"></div>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #fed7aa; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #ffedd5; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #ea580c; border-radius: 1px;"></div>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #fed7aa; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #ffedd5; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #ea580c; border-radius: 1px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 1: Classic Marketplace</h6>
                                        <span class="badge badge-warning text-dark font-weight-bold px-2" style="font-size: 9px; border-radius: 4px;">Default</span>
                                    </div>
                                    <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                                        Daraz-style multi-category architecture with category menu drawer & wide carousel.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="badge {{ $selectedTemplate == '1' ? 'badge-success' : 'badge-light text-secondary border' }} px-2 py-1 template-status-badge" style="font-size: 10px; border-radius: 12px;">
                                            {{ $selectedTemplate == '1' ? '✓ Currently Active' : 'Select Template' }}
                                        </span>
                                        <a href="{{ url('/') }}?preview_template=1" target="_blank"
                                           class="btn btn-sm btn-outline-primary px-2 py-0" style="font-size: 10.5px; border-radius: 12px; font-weight: 600;">
                                            <i class="fas fa-external-link-alt mr-1"></i> Preview
                                        </a>
                                    </div>
                                </div>
                                @endif

                                @if($isSuperAdmin || $selectedTemplate == '2')
                                <!-- Template 2 -->
                                <div class="template-card {{ $selectedTemplate == '2' ? 'active-template-card' : '' }}" data-template="2">
                                    <div class="tpl-preview-box" style="background: #0a0a0a; border-color: #d4af37;">
                                        <div style="height: 10px; background: #171717; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px; border-bottom: 1px solid rgba(212,175,55,0.4);">
                                            <div style="width: 18px; height: 3px; background: #d4af37; border-radius: 1px;"></div>
                                            <div style="display: flex; gap: 3px;">
                                                <div style="width: 10px; height: 2px; background: rgba(255,255,255,0.6); border-radius: 1px;"></div>
                                            </div>
                                        </div>
                                        <div style="flex: 1; background: linear-gradient(135deg, #1e1b18, #2a2012); border-radius: 4px; margin: 4px 0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #d4af37; border: 1px solid rgba(212,175,55,0.35);">
                                            <span style="font-size: 7.5px; font-weight: 800; letter-spacing: 0.8px;">MODERN BOUTIQUE</span>
                                            <span style="font-size: 6px; color: rgba(255,255,255,0.7);">Full-Width Canvas • Top Trust Bar</span>
                                        </div>
                                        <div style="display: flex; gap: 4px;">
                                            <div style="flex: 1; height: 20px; background: #141414; border: 1px solid rgba(212,175,55,0.25); border-radius: 3px; display: flex; align-items: center; justify-content: center;">
                                                <span style="font-size: 5.5px; color: #d4af37;">🚚 Free Delivery</span>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #141414; border: 1px solid rgba(212,175,55,0.25); border-radius: 3px; display: flex; align-items: center; justify-content: center;">
                                                <span style="font-size: 5.5px; color: #d4af37;">🔒 Secure</span>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #141414; border: 1px solid rgba(212,175,55,0.25); border-radius: 3px; display: flex; align-items: center; justify-content: center;">
                                                <span style="font-size: 5.5px; color: #d4af37;">⭐ Premium</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 2: Modern Minimal</h6>
                                        <span class="badge badge-dark text-warning font-weight-bold px-2" style="font-size: 9px; border-radius: 4px; border: 1px solid #d4af37;">Luxury</span>
                                    </div>
                                    <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                                        High-end boutique look with gold accents, edge-to-edge slider & top trust strip.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="badge {{ $selectedTemplate == '2' ? 'badge-success' : 'badge-light text-secondary border' }} px-2 py-1 template-status-badge" style="font-size: 10px; border-radius: 12px;">
                                            {{ $selectedTemplate == '2' ? '✓ Currently Active' : 'Select Template' }}
                                        </span>
                                        <a href="{{ url('/') }}?preview_template=2" target="_blank"
                                           class="btn btn-sm btn-outline-dark px-2 py-0" style="font-size: 10.5px; border-radius: 12px; font-weight: 600;">
                                            <i class="fas fa-external-link-alt mr-1"></i> Preview
                                        </a>
                                    </div>
                                </div>
                                @endif

                                @if($isSuperAdmin || $selectedTemplate == '3')
                                <!-- Template 3 -->
                                <div class="template-card {{ $selectedTemplate == '3' ? 'active-template-card' : '' }}" data-template="3">
                                    <div class="tpl-preview-box" style="background: #080c14; border-color: #38bdf8;">
                                        <div style="height: 10px; background: #0d1322; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px; border-bottom: 1px solid rgba(56, 189, 248, 0.4);">
                                            <div style="width: 18px; height: 3px; background: #38bdf8; border-radius: 1px;"></div>
                                            <div style="display: flex; gap: 3px;">
                                                <div style="width: 10px; height: 2px; background: #64748b; border-radius: 1px;"></div>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 4px; flex: 1; margin: 4px 0;">
                                            <div style="flex: 2; background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid #38bdf8;">
                                                <span style="color: #38bdf8; font-size: 7.5px; font-weight: 800;">⚡ TECH HUB</span>
                                                <span style="color: #94a3b8; font-size: 5.5px;">Gadget Spec Grids</span>
                                            </div>
                                            <div style="flex: 1; display: flex; flex-direction: column; gap: 2px;">
                                                <div style="flex: 1; background: #0d1322; border-radius: 2px; border: 1px solid rgba(56,189,248,0.3); display: flex; align-items: center; justify-content: center;">
                                                    <span style="font-size: 5px; color: #38bdf8;">Side 1</span>
                                                </div>
                                                <div style="flex: 1; background: #0d1322; border-radius: 2px; border: 1px solid rgba(56,189,248,0.3); display: flex; align-items: center; justify-content: center;">
                                                    <span style="font-size: 5px; color: #38bdf8;">Side 2</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 4px;">
                                            <div style="flex: 1; height: 20px; background: #0d1322; border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #1e293b; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #38bdf8; border-radius: 1px;"></div>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #0d1322; border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #1e293b; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #38bdf8; border-radius: 1px;"></div>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #0d1322; border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #1e293b; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #38bdf8; border-radius: 1px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 3: Electronic & Tech Hub</h6>
                                        <span class="badge badge-info font-weight-bold px-2" style="font-size: 9px; border-radius: 4px; background: #0284c7; color: #fff;">Cyber Tech</span>
                                    </div>
                                    <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                                        Cyberpunk dark theme, cyan glow, 2.5:1 hero with 2-row rotating promo deals.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="badge {{ $selectedTemplate == '3' ? 'badge-success' : 'badge-light text-secondary border' }} px-2 py-1 template-status-badge" style="font-size: 10px; border-radius: 12px;">
                                            {{ $selectedTemplate == '3' ? '✓ Currently Active' : 'Select Template' }}
                                        </span>
                                        <a href="{{ url('/') }}?preview_template=3" target="_blank"
                                           class="btn btn-sm btn-outline-info px-2 py-0" style="font-size: 10.5px; border-radius: 12px; font-weight: 600;">
                                            <i class="fas fa-external-link-alt mr-1"></i> Preview
                                        </a>
                                    </div>
                                </div>
                                @endif

                                @if($isSuperAdmin || $selectedTemplate == '4')
                                <!-- Template 4 -->
                                <div class="template-card {{ $selectedTemplate == '4' ? 'active-template-card' : '' }}" data-template="4">
                                    <div class="tpl-preview-box" style="background: #0f1115; border-color: #e11d48;">
                                        <div style="height: 10px; background: #1e1117; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px; border-bottom: 1px solid rgba(225, 29, 72, 0.4);">
                                            <div style="width: 20px; height: 3px; background: #e11d48; border-radius: 1px;"></div>
                                            <div style="display: flex; gap: 2px;">
                                                <div style="padding: 1px 3px; background: #e11d48; color: #fff; font-size: 5px; border-radius: 1px; font-weight: 800;">SALE</div>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 4px; flex: 1; margin: 4px 0;">
                                            <div style="flex: 2; background: linear-gradient(135deg, #881337, #be123c); border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; border: 1px solid #f43f5e;">
                                                <span style="font-size: 7.5px; font-weight: 900;">🔥 FLASH SALE</span>
                                                <span style="font-size: 5.5px; color: #fecdd3;">Live Deal Timers</span>
                                            </div>
                                            <div style="flex: 1; display: flex; flex-direction: column; gap: 2px;">
                                                <div style="flex: 1; background: #1e1117; border-radius: 2px; border: 1px solid rgba(244,63,94,0.3); display: flex; align-items: center; justify-content: center;">
                                                    <span style="font-size: 5px; color: #fb7185;">Deal 1</span>
                                                </div>
                                                <div style="flex: 1; background: #1e1117; border-radius: 2px; border: 1px solid rgba(244,63,94,0.3); display: flex; align-items: center; justify-content: center;">
                                                    <span style="font-size: 5px; color: #fb7185;">Deal 2</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 4px;">
                                            <div style="flex: 1; height: 20px; background: #1e1117; border: 1px solid rgba(225, 29, 72, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #33131d; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #e11d48; border-radius: 1px;"></div>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #1e1117; border: 1px solid rgba(225, 29, 72, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #33131d; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #e11d48; border-radius: 1px;"></div>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #1e1117; border: 1px solid rgba(225, 29, 72, 0.25); border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #33131d; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #e11d48; border-radius: 1px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 4: Flash Sale</h6>
                                        <span class="badge badge-danger font-weight-bold px-2" style="font-size: 9px; border-radius: 4px;">Conversion</span>
                                    </div>
                                    <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                                        High-conversion urgency architecture with animated sale marquee & flash deal rows.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="badge {{ $selectedTemplate == '4' ? 'badge-success' : 'badge-light text-secondary border' }} px-2 py-1 template-status-badge" style="font-size: 10px; border-radius: 12px;">
                                            {{ $selectedTemplate == '4' ? '✓ Currently Active' : 'Select Template' }}
                                        </span>
                                        <a href="{{ url('/') }}?preview_template=4" target="_blank"
                                           class="btn btn-sm btn-outline-danger px-2 py-0" style="font-size: 10.5px; border-radius: 12px; font-weight: 600;">
                                            <i class="fas fa-external-link-alt mr-1"></i> Preview
                                        </a>
                                    </div>
                                </div>
                                @endif

                                @if($isSuperAdmin || $selectedTemplate == '5')
                                <!-- Template 5 -->
                                <div class="template-card {{ $selectedTemplate == '5' ? 'active-template-card' : '' }}" data-template="5">
                                    <div class="tpl-preview-box" style="background: #f0fdf4; border-color: #86efac;">
                                        <div style="height: 10px; background: #15803d; border-radius: 2px; display: flex; align-items: center; justify-content: space-between; padding: 0 4px;">
                                            <div style="width: 18px; height: 3px; background: #ffffff; border-radius: 1px;"></div>
                                            <div style="display: gap: 3px;">
                                                <div style="width: 10px; height: 2px; background: rgba(255,255,255,0.7); border-radius: 1px;"></div>
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 3px; margin: 3px 0;">
                                            <div style="flex: 1; height: 9px; background: #dcfce7; border-radius: 6px; border: 1px solid #86efac; display: flex; align-items: center; justify-content: center;">
                                                <span style="font-size: 4.5px; color: #15803d; font-weight: 700;">Organic</span>
                                            </div>
                                            <div style="flex: 1; height: 9px; background: #dcfce7; border-radius: 6px; border: 1px solid #86efac; display: flex; align-items: center; justify-content: center;">
                                                <span style="font-size: 4.5px; color: #15803d; font-weight: 700;">Fruits</span>
                                            </div>
                                            <div style="flex: 1; height: 9px; background: #dcfce7; border-radius: 6px; border: 1px solid #86efac; display: flex; align-items: center; justify-content: center;">
                                                <span style="font-size: 4.5px; color: #15803d; font-weight: 700;">Dairy</span>
                                            </div>
                                        </div>
                                        <div style="flex: 1; background: linear-gradient(135deg, #16a34a, #15803d); border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; margin-bottom: 3px;">
                                            <span style="font-size: 7.5px; font-weight: 800;">🌿 FRESH GROCERY</span>
                                            <span style="font-size: 5.5px; opacity: 0.9;">Farm Fresh Produce</span>
                                        </div>
                                        <div style="display: flex; gap: 4px;">
                                            <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #86efac; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #dcfce7; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #16a34a; border-radius: 1px;"></div>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #86efac; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #dcfce7; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #16a34a; border-radius: 1px;"></div>
                                            </div>
                                            <div style="flex: 1; height: 20px; background: #fff; border: 1px solid #86efac; border-radius: 3px; padding: 2px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="height: 8px; background: #dcfce7; border-radius: 1px;"></div>
                                                <div style="height: 3px; width: 60%; background: #16a34a; border-radius: 1px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="font-weight-bold mb-0 text-dark" style="font-size: 13px;">Template 5: Grocery & Fresh Express</h6>
                                        <span class="badge badge-success font-weight-bold px-2" style="font-size: 9px; border-radius: 4px;">Organic</span>
                                    </div>
                                    <p class="text-muted mb-2" style="line-height: 1.35; font-size: 11px; flex: 1;">
                                        Fresh eco-friendly grocery layout with category pill tabs & full-width carousel.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="badge {{ $selectedTemplate == '5' ? 'badge-success' : 'badge-light text-secondary border' }} px-2 py-1 template-status-badge" style="font-size: 10px; border-radius: 12px;">
                                            {{ $selectedTemplate == '5' ? '✓ Currently Active' : 'Select Template' }}
                                        </span>
                                        <a href="{{ url('/') }}?preview_template=5" target="_blank"
                                           class="btn btn-sm btn-outline-success px-2 py-0" style="font-size: 10.5px; border-radius: 12px; font-weight: 600;">
                                            <i class="fas fa-external-link-alt mr-1"></i> Preview
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Template 4 Flash Sale Offer Settings --}}
                    @if($isSuperAdmin || $selectedTemplate == '4')
                    <div class="card mb-4 border-0 shadow-sm rounded-4" id="template4_settings_card">
                        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0 font-weight-bold text-dark" style="font-size: 15px;">
                                    <i class="fas fa-bolt text-danger mr-2"></i> Template 4: Flash Sale Top Ribbon & Countdown Configuration
                                </h5>
                                <small class="text-muted">Configure the announcement banner text, countdown closing time, and promotional link for Template 4.</small>
                            </div>
                            <span class="badge badge-danger px-3 py-1.5" style="border-radius: 20px; font-weight: 700; font-size: 10px;">
                                Template 4 Exclusive
                            </span>
                        </div>
                        <div class="card-body p-4 bg-light-50">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark" style="font-size: 13px;">
                                        <i class="far fa-clock text-primary mr-1"></i> Offer Closing Date & Time (End Time)
                                    </label>
                                    <input type="datetime-local" name="homepage[template_4_offer_end_time]" class="form-control" 
                                           value="{{ $homepage['template_4_offer_end_time'] ?? '' }}"
                                           placeholder="Select offer closing date & time">
                                    <small class="form-text text-muted">The live timer will count down dynamically to this exact date & time.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark" style="font-size: 13px;">
                                        <i class="fas fa-hourglass-half text-warning mr-1"></i> Countdown Timer Label
                                    </label>
                                    <input type="text" name="homepage[template_4_offer_label]" class="form-control" 
                                           value="{{ $homepage['template_4_offer_label'] ?? 'OFFER CLOSES IN:' }}" 
                                           placeholder="e.g. OFFER CLOSES IN: or FLASH DEAL ENDS IN:">
                                    <small class="form-text text-muted">Prefix text displayed right before the HH:MM:SS timer boxes.</small>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="font-weight-bold text-dark" style="font-size: 13px;">
                                        <i class="fas fa-bullhorn text-info mr-1"></i> Banner Headline Text
                                    </label>
                                    <input type="text" name="homepage[template_4_offer_heading]" class="form-control" 
                                           value="{{ $homepage['template_4_offer_heading'] ?? '✨ EXCLUSIVE CURATED COLLECTION • LIMITED BOUTIQUE EDITIONS' }}" 
                                           placeholder="e.g. ✨ EXCLUSIVE CURATED COLLECTION • LIMITED BOUTIQUE EDITIONS">
                                    <small class="form-text text-muted">Left-side announcement banner text on the top ribbon.</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark" style="font-size: 13px;">
                                        <i class="fas fa-link text-success mr-1"></i> Action Button Text
                                    </label>
                                    <input type="text" name="homepage[template_4_offer_btn_text]" class="form-control" 
                                           value="{{ $homepage['template_4_offer_btn_text'] ?? 'EXPLORE CATALOG →' }}" 
                                           placeholder="e.g. EXPLORE CATALOG → or SHOP FLASH DEALS →">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="font-weight-bold text-dark" style="font-size: 13px;">
                                        <i class="fas fa-external-link-alt text-secondary mr-1"></i> Action Button URL
                                    </label>
                                    <input type="text" name="homepage[template_4_offer_btn_url]" class="form-control" 
                                           value="{{ $homepage['template_4_offer_btn_url'] ?? route('shop') }}" 
                                           placeholder="e.g. /shop or https://...">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Product Category & Products By Category Sections</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="hidden"
                                                name="homepage[enable_product_category_section]" value="0">
                                            <input type="checkbox"
                                                name="homepage[enable_product_category_section]" value="1"
                                                {{ isset($homepage['enable_product_category_section']) && $homepage['enable_product_category_section'] ? 'checked' : '' }}>
                                            Enable Product Category Section
                                        </label>
                                    </div>
                                    @php
                                    $categoryStyleSelection = setting('homepage', 'category_style', '');
                                    $validCategoryStyles = ['1', '2', '3', '4', '5', '6'];
                                    if (!in_array((string) $categoryStyleSelection, $validCategoryStyles, true)) {
                                        if (setting('homepage', 'category_style_6', '0') == '1') {
                                            $categoryStyleSelection = '6';
                                        } elseif (setting('homepage', 'category_style_5', '0') == '1') {
                                            $categoryStyleSelection = '5';
                                        } elseif (setting('homepage', 'category_style_4', '1') == '1') {
                                            $categoryStyleSelection = '4';
                                        } elseif (setting('homepage', 'category_style_3', '0') == '1') {
                                            $categoryStyleSelection = '3';
                                        } elseif (setting('homepage', 'category_style_2', '0') == '1') {
                                            $categoryStyleSelection = '2';
                                        } else {
                                            $categoryStyleSelection = '1';
                                        }
                                    }
                                    @endphp
                                    <div class="form-group">
                                        <label>Category Style</label>
                                        <select name="homepage[category_style]" class="form-control">
                                            <option value="1" {{ $categoryStyleSelection === '1' ? 'selected' : '' }}>Style 1 (Default)</option>
                                            <option value="2" {{ $categoryStyleSelection === '2' ? 'selected' : '' }}>Style 2</option>
                                            <option value="3" {{ $categoryStyleSelection === '3' ? 'selected' : '' }}>Style 3</option>
                                            <option value="4" {{ $categoryStyleSelection === '4' ? 'selected' : '' }}>Style 4</option>
                                            <option value="5" {{ $categoryStyleSelection === '5' ? 'selected' : '' }}>Style 5</option>
                                            <option value="6" {{ $categoryStyleSelection === '6' ? 'selected' : '' }}>Style 6</option>
                                        </select>
                                        <small class="form-text text-muted">Choose one layout for the category cards (1-6).</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Featured Categories/Subcategories (Select and drag to
                                            reorder)</label>
                                        <ul id="homepage-featured-sortable" class="list-group">
                                            @php
                                            $selected = isset($homepage['featured_category_order'])
                                            ? json_decode($homepage['featured_category_order'], true)
                                            : [];
                                            $allItems = collect($categories)
                                            ->map(function ($cat) {
                                            return [
                                            'type' => 'category',
                                            'id' => $cat->id,
                                            'name' => $cat->name,
                                            ];
                                            })
                                            ->merge(
                                            collect($subcategories)->map(function ($sub) {
                                            return [
                                            'type' => 'subcategory',
                                            'id' => $sub->id,
                                            'name' => $sub->name,
                                            ];
                                            }),
                                            );
                                            // Order selected first, then the rest
                                            $orderedItems = collect($selected)
                                            ->map(function ($item) use ($allItems) {
                                            return $allItems->first(function ($i) use ($item) {
                                            return $i['type'] . '-' . $i['id'] === $item;
                                            });
                                            })
                                            ->filter();
                                            $remainingItems = $allItems->filter(function ($i) use (
                                            $selected,
                                            ) {
                                            return !in_array($i['type'] . '-' . $i['id'], $selected);
                                            });
                                            $finalItems = $orderedItems->concat($remainingItems);
                                            @endphp
                                            @foreach ($finalItems as $item)
                                            @php $itemKey = $item['type'] . '-' . $item['id']; @endphp
                                            <li class="list-group-item d-flex align-items-center"
                                                data-id="{{ $itemKey }}">
                                                <input type="checkbox" class="mr-2 featured-checkbox"
                                                    {{ in_array($itemKey, $selected) ? 'checked' : '' }}>
                                                <span class="flex-grow-1">{{ ucfirst($item['type']) }}:
                                                    {{ $item['name'] }}</span>
                                                <span class="handle" style="cursor:move;">&#9776;</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <input type="hidden" name="homepage[featured_category_order]"
                                            id="homepage-featured-order"
                                            value='{{ $homepage['featured_category_order'] ?? '[]' }}'>
                                        <small class="form-text text-muted">Check to show, drag checked items to
                                            set
                                            order.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="hidden"
                                                name="homepage[enable_products_by_category_section]" value="0">
                                            <input type="checkbox"
                                                name="homepage[enable_products_by_category_section]" value="1"
                                                {{ !empty($homepage['enable_products_by_category_section']) && $homepage['enable_products_by_category_section'] ? 'checked' : '' }}>
                                            Enable Products By Category Section
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label>Products By Category (Select and drag to reorder)</label>
                                        <ul id="products-by-category-sortable" class="list-group">
                                            {{-- Parse the saved order or default to empty array --}}
                                            @php
                                            $selectedOrder = !empty($homepage['products_by_category_order'])
                                            ? json_decode($homepage['products_by_category_order'], true)
                                            : [];
                                            $selectedOrder = is_array($selectedOrder) ? $selectedOrder : [];
                                            // Build a map of selected categories for quick lookup
                                            $selectedMap = array_flip($selectedOrder);
                                            @endphp

                                            {{-- First, show selected categories in saved order --}}
                                            @foreach ($selectedOrder as $catKey)
                                            @php
                                            // Extract numeric ID from "category-3"
                                            $catId = null;
                                            if (str_starts_with($catKey, 'category-')) {
                                            $catId = (int) str_replace('category-', '', $catKey);
                                            }
                                            $cat = $categories->firstWhere('id', $catId);
                                            @endphp
                                            @if ($cat)
                                            <li class="list-group-item" style="display: flex
;
    justify-content: space-between;"
                                                data-id="category-{{ $cat->id }}">
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox"
                                                        class="mr-2 products-by-category-checkbox"
                                                        name="homepage[products_by_category_selected][{{ $cat->id }}]"
                                                        value="1" checked>
                                                    <span> {{ $cat->name }}</span>
                                                </div>
                                                <span class="handle" style="cursor:move;">&#9776;</span>
                                            </li>
                                            @endif
                                            @endforeach

                                            {{-- Then, show unselected categories (not in order) --}}
                                            @foreach ($categories as $cat)
                                            @php $catKey = 'category-' . $cat->id; @endphp
                                            @if (!isset($selectedMap[$catKey]))
                                            <li class="list-group-item not-draggable" style="display: flex
;
    justify-content: space-between;"
                                                data-id="category-{{ $cat->id }}">
                                                <div class="d-flex align-items-center">
                                                    <input type="checkbox"
                                                        class="mr-2 products-by-category-checkbox"
                                                        name="homepage[products_by_category_selected][{{ $cat->id }}]"
                                                        value="1">
                                                    <span> {{ $cat->name }}</span>
                                                </div>
                                                <span class="handle" style="cursor:move;">&#9776;</span>
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                        <input type="hidden" name="homepage[products_by_category_order]"
                                            id="products-by-category-order"
                                            value='{{ $homepage['products_by_category_order'] ?? '[]' }}'>
                                        <small class="form-text text-muted">Check to show, drag checked items
                                            to
                                            set
                                            order.</small>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row mt-2">
                        <!-- Best Selling Products Section Controls -->
                        @php
                        $sections = [
                        'best_selling' => 'Best Selling Products',
                        'editors_pick' => "Editor's Picks",
                        'trending' => 'Trending Now',
                        ];
                        @endphp

                        @foreach ($sections as $key => $label)
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="hidden"
                                    name="homepage[enable_{{ $key }}_section]"
                                    value="0">
                                <label>
                                    <input type="checkbox"
                                        name="homepage[enable_{{ $key }}_section]"
                                        value="1"
                                        {{ !empty($homepage['enable_' . $key . '_section']) && $homepage['enable_' . $key . '_section'] ? 'checked' : '' }}>
                                    Enable {{ $label }} Section
                                </label>
                            </div>
                            <div class="form-group">
                                <label>{{ $label }} Heading</label>
                                <input type="text"
                                    name="homepage[{{ $key }}_section_heading]"
                                    class="form-control"
                                    value="{{ $homepage[$key . '_section_heading'] ?? $label }}">
                            </div>
                            <div class="form-group">
                                <label>{{ $label }} (Select and drag to
                                    reorder)</label>
                                <ul id="{{ $key }}-products-sortable"
                                    class="list-group">
                                    @php
                                    $selectedProducts = !empty(
                                    $homepage[$key . '_products_order']
                                    )
                                    ? json_decode(
                                    $homepage[$key . '_products_order'],
                                    true,
                                    )
                                    : [];
                                    $selectedProducts = is_array($selectedProducts)
                                    ? $selectedProducts
                                    : [];
                                    $selectedMap = array_flip($selectedProducts);
                                    @endphp

                                    {{-- Show selected products in saved order --}}
                                    @foreach ($selectedProducts as $prodId)
                                    @php $prod = $products->firstWhere('id', $prodId); @endphp
                                    @if ($prod)
                                    <li class="list-group-item d-flex align-items-center"
                                        data-id="{{ $prod->id }}">
                                        <input type="checkbox"
                                            name="homepage[{{ $key }}_products_selected][{{ $prod->id }}]"
                                            value="1" checked class="mr-2">
                                        <span>{{ $prod->title }}</span>
                                        <span class="handle ml-auto"
                                            style="cursor:move;">&#9776;</span>
                                    </li>
                                    @endif
                                    @endforeach

                                    {{-- Show unselected products --}}
                                    @foreach ($products as $prod)
                                    @if (!isset($selectedMap[$prod->id]))
                                    <li class="list-group-item d-flex align-items-center"
                                        data-id="{{ $prod->id }}">
                                        <input type="checkbox"
                                            name="homepage[{{ $key }}_products_selected][{{ $prod->id }}]"
                                            value="1" class="mr-2">
                                        <span>{{ $prod->title }}</span>
                                        <span class="handle ml-auto"
                                            style="cursor:move;">&#9776;</span>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                                <input type="hidden"
                                    name="homepage[{{ $key }}_products_order]"
                                    id="{{ $key }}-products-order"
                                    value='{{ $homepage[$key . '_products_order'] ?? '[]' }}'>
                                <small class="form-text text-muted">Check to show, drag
                                    checked
                                    items
                                    to set order.</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <h6 class="text-primary mt-2">Latest Products Section</h6>

                    <div class="form-group">
                        <input type="hidden" name="homepage[enable_latest_products_section]" value="0">
                        <label>
                            <input type="checkbox" name="homepage[enable_latest_products_section]" value="1"
                                {{ !empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] ? 'checked' : '' }}>
                            Enable Latest Products Section In Home page
                        </label>
                    </div>

                    <div class="form-group row mt-2">
                        <label class="col-md-3 col-form-label">Section Heading</label>
                        <div class="col-md-9">
                            <input type="text" name="homepage[latest_products_section_heading]" class="form-control"
                                value="{{ $homepage['latest_products_section_heading'] ?? 'Latest Products' }}"
                                placeholder="Latest Products">
                            <small class="form-text text-muted">Title for the Latest Products section.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Initial Products Count</label>
                        <div class="col-md-9">
                            <input type="number" name="homepage[latest_products_initial_count]" class="form-control"
                                value="{{ $homepage['latest_products_initial_count'] ?? 12 }}"
                                min="1" max="50" step="1">
                            <small class="form-text text-muted">Number of products to show initially (1-50).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Products Per Page</label>
                        <div class="col-md-9">
                            <input type="number" name="homepage[latest_products_per_page]" class="form-control"
                                value="{{ $homepage['latest_products_per_page'] ?? 12 }}"
                                min="1" max="50" step="1">
                            <small class="form-text text-muted">Number of products to load per page when loading more (1-50).</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Load More Type</label>
                        <div class="col-md-9">
                            <select name="homepage[latest_products_load_type]" class="form-control">
                                <option value="button" {{ ($homepage['latest_products_load_type'] ?? 'button') == 'button' ? 'selected' : '' }}>
                                    Load More Button
                                </option>
                                <option value="infinite" {{ ($homepage['latest_products_load_type'] ?? 'button') == 'infinite' ? 'selected' : '' }}>
                                    Infinite Scroll
                                </option>
                            </select>
                            <small class="form-text text-muted">Choose how users will load more products.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Load More Button Text</label>
                        <div class="col-md-9">
                            <input type="text" name="homepage[latest_products_load_more_text]" class="form-control"
                                value="{{ $homepage['latest_products_load_more_text'] ?? 'Load More Products' }}"
                                placeholder="Load More Products">
                            <small class="form-text text-muted">Text to display on the load more button.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Loading Text</label>
                        <div class="col-md-9">
                            <input type="text" name="homepage[latest_products_loading_text]" class="form-control"
                                value="{{ $homepage['latest_products_loading_text'] ?? 'Loading...' }}"
                                placeholder="Loading...">
                            <small class="form-text text-muted">Text to show while loading more products.</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Main Slider Section</h6>

                    <div class="form-group">
                        <input type="hidden" name="homepage[enable_main_slider_section]" value="0">
                        <label>
                            <input type="checkbox" name="homepage[enable_main_slider_section]" value="1"
                                {{ !empty($homepage['enable_main_slider_section']) && $homepage['enable_main_slider_section'] ? 'checked' : '' }}>
                            Enable Main Slider Section In Home page
                        </label>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Slider Height (Desktop, px)</label>
                        <div class="col-md-9">
                            <input type="number" name="homepage[slider_height]" class="form-control"
                                value="{{ $homepage['slider_height'] ?? 300 }}" min="100" max="1000" step="1">
                            <small class="form-text text-muted">Default: 300px. Controls the height of the slider.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Slider Layout</label>
                        <div class="col-md-9">
                            @php
                                $sliderLayoutSetting = $homepage['slider_layout'] ?? 'category_slider';
                            @endphp
                            <select name="homepage[slider_layout]" class="form-control">
                                <option value="slider_only" {{ $sliderLayoutSetting === 'slider_only' ? 'selected' : '' }}>Only slider</option>
                                <option value="category_slider" {{ $sliderLayoutSetting === 'category_slider' ? 'selected' : '' }}>Left category + right slider</option>
                                <option value="slider_with_one_image" {{ $sliderLayoutSetting === 'slider_with_one_image' ? 'selected' : '' }}>Left slider + right single image</option>
                                <!-- <option value="slider_with_two_images" {{ $sliderLayoutSetting === 'slider_with_two_images' ? 'selected' : '' }}>Left slider + right two images</option> -->
                            </select>
                            <small class="form-text text-muted">Choose the main slider template. Current layouts support "Only slider" or "Left category + right slider"; other options are reserved for upcoming layouts.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Side Image (for "Left slider + right single image")</label>
                        <div class="col-md-9">
                            <div class="custom-file mb-2">
                                <input type="file" name="homepage[slider_side_image]" class="custom-file-input" id="slider_side_image">
                                <label class="custom-file-label" for="slider_side_image">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Optional. Shown only when layout is "Left slider + right single image". Recommended size: matches slider height ratio.</small>
                            @if (!empty($homepage['slider_side_image']))
                                <div class="mt-2 p-2 border rounded d-inline-block position-relative">
                                    <img src="{{ asset($homepage['slider_side_image']) }}" alt="Slider side image" style="max-width: 220px; max-height: 120px; object-fit: cover;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px;"
                                        onclick="document.getElementById('slider_side_image_delete').value='1'; this.closest('div').style.display='none';"
                                        title="Delete side image">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                            <input type="hidden" name="homepage[slider_side_image_delete]" id="slider_side_image_delete" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Side Image Alt Text</label>
                        <div class="col-md-9">
                            <input type="text" name="homepage[slider_side_image_alt]" class="form-control"
                                value="{{ $homepage['slider_side_image_alt'] ?? '' }}" placeholder="Describe the side image">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Side Image Link</label>
                        <div class="col-md-9">
                            <input type="url" name="homepage[slider_side_image_link]" class="form-control"
                                value="{{ $homepage['slider_side_image_link'] ?? '' }}" placeholder="https://example.com">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Slider Height (Tablet, px)</label>
                        <input type="number" name="homepage[slider_height_tablet]"
                            class="form-control"
                            value="{{ $homepage['slider_height_tablet'] ?? 200 }}"
                            min="80" max="800" step="1">
                        <small class="form-text text-muted">Default: 200px. Controls the
                            height of
                            the main slider on tablets (993px–768px).</small>
                    </div>
                    <div class="form-group">
                        <label>Slider Height (Mobile, px)</label>
                        <input type="number" name="homepage[slider_height_mobile]"
                            class="form-control"
                            value="{{ $homepage['slider_height_mobile'] ?? 170 }}"
                            min="50" max="600" step="1">
                        <small class="form-text text-muted">Default: 170px. Controls the
                            height of
                            the main slider on mobile (&lt;=768px).</small>
                    </div>

                    <hr>
                    <h6 class="text-primary">Scroll to Top Button</h6>

                    <div class="form-group">
                        <input type="hidden" name="homepage[enable_scroll_to_top]" value="0">
                        <label>
                            <input type="checkbox" name="homepage[enable_scroll_to_top]" value="1"
                                {{ !empty($homepage['enable_scroll_to_top']) && $homepage['enable_scroll_to_top'] ? 'checked' : '' }}>
                            Enable "Scroll to Top" Button
                        </label>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show on Desktop?</label>
                        <div class="col-md-9">
                            <select name="settings[show_scroll_to_top_desktop]" class="form-control">
                                <option value="1" {{ setting('general', 'show_scroll_to_top_desktop', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_scroll_to_top_desktop', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the scroll to top button on desktop devices.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Show on Mobile?</label>
                        <div class="col-md-9">
                            <select name="settings[show_scroll_to_top_mobile]" class="form-control">
                                <option value="1" {{ setting('general', 'show_scroll_to_top_mobile', '1') == '1' ? 'selected' : '' }}>Show</option>
                                <option value="0" {{ setting('general', 'show_scroll_to_top_mobile', '1') == '0' ? 'selected' : '' }}>Hide</option>
                            </select>
                            <small class="form-text text-muted">Show or hide the scroll to top button on mobile devices.</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">General Page Positioning</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Desktop Position</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Bottom (px)</label>
                                    <input type="number" name="settings[scroll_to_top_bottom_desktop]" class="form-control"
                                        value="{{ setting('general', 'scroll_to_top_bottom_desktop', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from bottom on desktop.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Right (px)</label>
                                    <input type="number" name="settings[scroll_to_top_right_desktop]" class="form-control"
                                        value="{{ setting('general', 'scroll_to_top_right_desktop', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from right on desktop.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Mobile Position</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Bottom (px)</label>
                                    <input type="number" name="settings[scroll_to_top_bottom_mobile]" class="form-control"
                                        value="{{ setting('general', 'scroll_to_top_bottom_mobile', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from bottom on mobile.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Right (px)</label>
                                    <input type="number" name="settings[scroll_to_top_right_mobile]" class="form-control"
                                        value="{{ setting('general', 'scroll_to_top_right_mobile', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from right on mobile.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Shop Page Specific Positioning</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Shop Page Desktop Position</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Bottom (px)</label>
                                    <input type="number" name="settings[scroll_to_top_shop_bottom_desktop]" class="form-control"
                                        value="{{ setting('general', 'scroll_to_top_shop_bottom_desktop', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from bottom on shop page desktop.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Right (px)</label>
                                    <input type="number" name="settings[scroll_to_top_shop_right_desktop]" class="form-control"
                                        value="{{ setting('general', 'scroll_to_top_shop_right_desktop', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from right on shop page desktop.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Shop Page Mobile Position</label>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Bottom (px)</label>
                                    <input type="number" name="settings[scroll_to_top_shop_bottom_mobile]" class="form-control"
                                        value="{{ setting('general', 'scroll_to_top_shop_bottom_mobile', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from bottom on shop page mobile.</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Right (px)</label>
                                    <input type="number" name="settings[scroll_to_top_shop_right_mobile]" class="form-control"
                                        value="{{ setting('general', 'scroll_to_top_shop_right_mobile', '20') }}" placeholder="20">
                                    <small class="form-text text-muted">Distance from right on shop page mobile.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Featured Images Section</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Enable Featured Images Section?</label>
                        <div class="col-md-9">
                            <input type="hidden" name="homepage[enable_featured_images_section]" value="0">
                            <label>
                                <input type="checkbox" name="homepage[enable_featured_images_section]" value="1"
                                    {{ !empty($homepage['enable_featured_images_section']) && $homepage['enable_featured_images_section'] ? 'checked' : '' }}>
                                Show Featured Images Section on Homepage
                            </label>
                            <small class="form-text text-muted">Display a dynamic image section that can show 1-4 images with responsive layout.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Gap Between Images (px)</label>
                        <div class="col-md-9">
                            <input type="number" name="homepage[featured_images_gap]" class="form-control"
                                value="{{ $homepage['featured_images_gap'] ?? 15 }}" min="0" max="50" step="5">
                            <small class="form-text text-muted">Space between images in pixels (0-50px). 0 = no gap, 15 = default spacing, 50 = maximum spacing.</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Image 1</h6>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Featured Image 1</label>
                        <div class="col-md-9">
                            <div class="custom-file mb-2">
                                <input type="file" name="featured_image_1" class="custom-file-input" id="featured_image_1">
                                <label class="custom-file-label" for="featured_image_1">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 800x400px. WebP, PNG, JPG or JPEG format.</small>
                            @if (!empty($homepage['featured_image_1']))
                            <div class="mt-2 p-2 border rounded d-inline-block position-relative">
                                <img src="{{ asset($homepage['featured_image_1']) }}" alt="Featured Image 1" style="max-width: 200px; max-height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px;"
                                    onclick="deleteFeaturedImage(1, '{{ $homepage['featured_image_1'] }}')"
                                    title="Delete Image 1">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Image 1 Alt Text</label>
                        <div class="col-md-9">
                            <input type="text" name="homepage[featured_image_1_alt]" class="form-control"
                                value="{{ $homepage['featured_image_1_alt'] ?? '' }}"
                                placeholder="Enter descriptive alt text for SEO and accessibility">
                            <small class="form-text text-muted">Describe the image content for better SEO and screen readers. Example: "Modern smartphone with premium design"</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Image 1 Link URL</label>
                        <div class="col-md-9">
                            <input type="url" name="homepage[featured_image_1_link]" class="form-control"
                                value="{{ $homepage['featured_image_1_link'] ?? '' }}"
                                placeholder="https://example.com or leave empty for no link">
                            <small class="form-text text-muted">Enter the URL where this image should link to. Leave empty if you don't want the image to be clickable.</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Image 2</h6>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Featured Image 2</label>
                        <div class="col-md-9">
                            <div class="custom-file mb-2">
                                <input type="file" name="featured_image_2" class="custom-file-input" id="featured_image_2">
                                <label class="custom-file-label" for="featured_image_2">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 800x400px. WebP, PNG, JPG or JPEG format.</small>
                            @if (!empty($homepage['featured_image_2']))
                            <div class="mt-2 p-2 border rounded d-inline-block position-relative">
                                <img src="{{ asset($homepage['featured_image_2']) }}" alt="Featured Image 2" style="max-width: 200px; max-height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px;"
                                    onclick="deleteFeaturedImage(2, '{{ $homepage['featured_image_2'] }}')"
                                    title="Delete Image 2">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Image 2 Alt Text</label>
                        <div class="col-md-9">
                            <input type="text" name="homepage[featured_image_2_alt]" class="form-control"
                                value="{{ $homepage['featured_image_2_alt'] ?? '' }}"
                                placeholder="Enter descriptive alt text for SEO and accessibility">
                            <small class="form-text text-muted">Describe the image content for better SEO and screen readers.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Image 2 Link URL</label>
                        <div class="col-md-9">
                            <input type="url" name="homepage[featured_image_2_link]" class="form-control"
                                value="{{ $homepage['featured_image_2_link'] ?? '' }}"
                                placeholder="https://example.com or leave empty for no link">
                            <small class="form-text text-muted">Enter the URL where this image should link to. Leave empty if you don't want the image to be clickable.</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Image 3</h6>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Featured Image 3</label>
                        <div class="col-md-9">
                            <div class="custom-file mb-2">
                                <input type="file" name="featured_image_3" class="custom-file-input" id="featured_image_3">
                                <label class="custom-file-label" for="featured_image_3">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 800x400px. WebP, PNG, JPG or JPEG format.</small>
                            @if (!empty($homepage['featured_image_3']))
                            <div class="mt-2 p-2 border rounded d-inline-block position-relative">
                                <img src="{{ asset($homepage['featured_image_3']) }}" alt="Featured Image 3" style="max-width: 200px; max-height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px;"
                                    onclick="deleteFeaturedImage(3, '{{ $homepage['featured_image_3'] }}')"
                                    title="Delete Image 3">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Image 3 Alt Text</label>
                        <div class="col-md-9">
                            <input type="text" name="homepage[featured_image_3_alt]" class="form-control"
                                value="{{ $homepage['featured_image_3_alt'] ?? '' }}"
                                placeholder="Enter descriptive alt text for SEO and accessibility">
                            <small class="form-text text-muted">Describe the image content for better SEO and screen readers.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Image 3 Link URL</label>
                        <div class="col-md-9">
                            <input type="url" name="homepage[featured_image_3_link]" class="form-control"
                                value="{{ $homepage['featured_image_3_link'] ?? '' }}"
                                placeholder="https://example.com or leave empty for no link">
                            <small class="form-text text-muted">Enter the URL where this image should link to. Leave empty if you don't want the image to be clickable.</small>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-primary">Image 4</h6>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Featured Image 4</label>
                        <div class="col-md-9">
                            <div class="custom-file mb-2">
                                <input type="file" name="featured_image_4" class="custom-file-input" id="featured_image_4">
                                <label class="custom-file-label" for="featured_image_4">Choose file</label>
                            </div>
                            <small class="form-text text-muted">Recommended size: 800x400px. WebP, PNG, JPG or JPEG format.</small>
                            @if (!empty($homepage['featured_image_4']))
                            <div class="mt-2 p-2 border rounded d-inline-block position-relative">
                                <img src="{{ asset($homepage['featured_image_4']) }}" alt="Featured Image 4" style="max-width: 200px; max-height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 5px; right: 5px;"
                                    onclick="deleteFeaturedImage(4, '{{ $homepage['featured_image_4'] }}')"
                                    title="Delete Image 4">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Image 4 Alt Text</label>
                        <div class="col-md-9">
                            <input type="text" name="homepage[featured_image_4_alt]" class="form-control"
                                value="{{ $homepage['featured_image_4_alt'] ?? '' }}"
                                placeholder="Enter descriptive alt text for SEO and accessibility">
                            <small class="form-text text-muted">Describe the image content for better SEO and screen readers.</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Image 4 Link URL</label>
                        <div class="col-md-9">
                            <input type="url" name="homepage[featured_image_4_link]" class="form-control"
                                value="{{ $homepage['featured_image_4_link'] ?? '' }}"
                                placeholder="https://example.com or leave empty for no link">
                            <small class="form-text text-muted">Enter the URL where this image should link to. Leave empty if you don't want the image to be clickable.</small>
                        </div>
                    </div>

                    <!-- Hidden fields for image deletion -->
                    <input type="hidden" name="homepage[delete_featured_image_1]" id="delete_featured_image_1" value="0">
                    <input type="hidden" name="homepage[delete_featured_image_2]" id="delete_featured_image_2" value="0">
                    <input type="hidden" name="homepage[delete_featured_image_3]" id="delete_featured_image_3" value="0">
                    <input type="hidden" name="homepage[delete_featured_image_4]" id="delete_featured_image_4" value="0">

                    <script>
                        function deleteFeaturedImage(imageNumber, imagePath) {
                            if (confirm('Are you sure you want to delete Featured Image ' + imageNumber + '? This action cannot be undone.')) {
                                // Set the delete flag
                                document.getElementById('delete_featured_image_' + imageNumber).value = '1';

                                // Find and hide the image preview container
                                const currentFormGroup = event.target.closest('.form-group');
                                const imagePreviewContainer = currentFormGroup.querySelector('.mt-2.p-2.border.rounded.d-inline-block.position-relative');
                                if (imagePreviewContainer) {
                                    imagePreviewContainer.style.display = 'none';
                                }

                                // Clear the file input
                                const fileInput = document.getElementById('featured_image_' + imageNumber);
                                if (fileInput) {
                                    fileInput.value = '';
                                    const label = fileInput.nextElementSibling;
                                    if (label) {
                                        label.textContent = 'Choose file';
                                    }
                                }

                                // Clear alt text and link fields
                                const altInput = document.querySelector('input[name="homepage[featured_image_' + imageNumber + '_alt]"]');
                                const linkInput = document.querySelector('input[name="homepage[featured_image_' + imageNumber + '_link]"]');
                                if (altInput) altInput.value = '';
                                if (linkInput) linkInput.value = '';

                                // Show success message
                                showDeleteSuccessMessage(imageNumber);
                            }
                        }

                        function showDeleteSuccessMessage(imageNumber) {
                            // Create a temporary success message
                            const successDiv = document.createElement('div');
                            successDiv.className = 'alert alert-success alert-dismissible fade show mt-2';
                            successDiv.innerHTML = `
                                    <strong>Success!</strong> Featured Image ${imageNumber} has been marked for deletion. 
                                    Click "Save Changes" to complete the deletion.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                `;

                            // Find the form group and insert the message
                            const currentFormGroup = event.target.closest('.form-group');
                            currentFormGroup.appendChild(successDiv);

                            // Auto-hide after 5 seconds
                            setTimeout(() => {
                                if (successDiv.parentNode) {
                                    successDiv.remove();
                                }
                            }, 5000);
                        }
                    </script>

                    <hr>
                    <h6 class="text-primary">Products By Category v2 - Multiple Locations</h6>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Enable Products By Category v2 Section?</label>
                        <div class="col-md-9">
                            <input type="hidden" name="homepage[enable_products_by_category_v2]" value="0">
                            <label>
                                <input type="checkbox" name="homepage[enable_products_by_category_v2]" value="1"
                                    {{ !empty($homepage['enable_products_by_category_v2']) && $homepage['enable_products_by_category_v2'] ? 'checked' : '' }}>
                                Enable Products By Category v2 Section In Home page
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="homepage[enable_products_by_category_v2_location1]" value="0">
                                <label>
                                    <input type="checkbox" name="homepage[enable_products_by_category_v2_location1]" value="1"
                                        {{ !empty($homepage['enable_products_by_category_v2_location1']) && $homepage['enable_products_by_category_v2_location1'] ? 'checked' : '' }}>
                                    Enable Products By Category v2 - Location 1
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Location 1 Categories (Select and drag to reorder)</label>
                                <ul id="homepage-category-v2-location1-sortable" class="list-group">
                                    @php
                                    $selected = isset($homepage['products_by_category_v2_location1_order'])
                                    ? json_decode($homepage['products_by_category_v2_location1_order'], true)
                                    : [];
                                    $allItems = collect($categories)->map(function ($cat) {
                                    return [
                                    'type' => 'category',
                                    'id' => $cat->id,
                                    'name' => $cat->name,
                                    ];
                                    });
                                    // Order selected first, then the rest
                                    $orderedItems = collect($selected)
                                    ->map(function ($item) use ($allItems) {
                                    return $allItems->first(function ($i) use ($item) {
                                    return $i['type'] . '-' . $i['id'] === $item;
                                    });
                                    })
                                    ->filter();
                                    $remainingItems = $allItems->filter(function ($i) use ($selected) {
                                    return !in_array($i['type'] . '-' . $i['id'], $selected);
                                    });
                                    $finalItems = $orderedItems->concat($remainingItems);
                                    @endphp
                                    @foreach ($finalItems as $item)
                                    @php $itemKey = $item['type'] . '-' . $item['id']; @endphp
                                    <li class="list-group-item d-flex align-items-center" data-id="{{ $itemKey }}">
                                        <input type="checkbox" class="mr-2 location1-category-checkbox"
                                            {{ in_array($itemKey, $selected) ? 'checked' : '' }}>
                                        <span class="flex-grow-1">{{ ucfirst($item['type']) }}: {{ $item['name'] }}</span>
                                        <span class="handle" style="cursor:move;">&#9776;</span>
                                    </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="homepage[products_by_category_v2_location1_order]"
                                    id="homepage-category-v2-location1-order"
                                    value='{{ $homepage['products_by_category_v2_location1_order'] ?? '[]' }}'>
                                <small class="form-text text-muted">Check to show, drag checked items to set order.</small>
                            </div>

                            <div class="form-group">
                                <label>Products per Category (Location 1)</label>
                                <input type="number" name="homepage[products_by_category_v2_location1_products_per_category]"
                                    class="form-control"
                                    value="{{ $homepage['products_by_category_v2_location1_products_per_category'] ?? 12 }}"
                                    min="1" max="50" step="1">
                                <small class="form-text text-muted">Number of products to show per category (1-50).</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="homepage[enable_products_by_category_v2_location2]" value="0">
                                <label>
                                    <input type="checkbox" name="homepage[enable_products_by_category_v2_location2]" value="1"
                                        {{ !empty($homepage['enable_products_by_category_v2_location2']) && $homepage['enable_products_by_category_v2_location2'] ? 'checked' : '' }}>
                                    Enable Products By Category v2 - Location 2
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Location 2 Categories (Select and drag to reorder)</label>
                                <ul id="homepage-category-v2-location2-sortable" class="list-group">
                                    @php
                                    $selected = isset($homepage['products_by_category_v2_location2_order'])
                                    ? json_decode($homepage['products_by_category_v2_location2_order'], true)
                                    : [];
                                    $allItems = collect($categories)->map(function ($cat) {
                                    return [
                                    'type' => 'category',
                                    'id' => $cat->id,
                                    'name' => $cat->name,
                                    ];
                                    });
                                    // Order selected first, then the rest
                                    $orderedItems = collect($selected)
                                    ->map(function ($item) use ($allItems) {
                                    return $allItems->first(function ($i) use ($item) {
                                    return $i['type'] . '-' . $i['id'] === $item;
                                    });
                                    })
                                    ->filter();
                                    $remainingItems = $allItems->filter(function ($i) use ($selected) {
                                    return !in_array($i['type'] . '-' . $i['id'], $selected);
                                    });
                                    $finalItems = $orderedItems->concat($remainingItems);
                                    @endphp
                                    @foreach ($finalItems as $item)
                                    @php $itemKey = $item['type'] . '-' . $item['id']; @endphp
                                    <li class="list-group-item d-flex align-items-center" data-id="{{ $itemKey }}">
                                        <input type="checkbox" class="mr-2 location2-category-checkbox"
                                            {{ in_array($itemKey, $selected) ? 'checked' : '' }}>
                                        <span class="flex-grow-1">{{ ucfirst($item['type']) }}: {{ $item['name'] }}</span>
                                        <span class="handle" style="cursor:move;">&#9776;</span>
                                    </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="homepage[products_by_category_v2_location2_order]"
                                    id="homepage-category-v2-location2-order"
                                    value='{{ $homepage['products_by_category_v2_location2_order'] ?? '[]' }}'>
                                <small class="form-text text-muted">Check to show, drag checked items to set order.</small>
                            </div>

                            <div class="form-group">
                                <label>Products per Category (Location 2)</label>
                                <input type="number" name="homepage[products_by_category_v2_location2_products_per_category]"
                                    class="form-control"
                                    value="{{ $homepage['products_by_category_v2_location2_products_per_category'] ?? 12 }}"
                                    min="1" max="50" step="1">
                                <small class="form-text text-muted">Number of products to show per category (1-50).</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="homepage[enable_products_by_category_v2_location3]" value="0">
                                <label>
                                    <input type="checkbox" name="homepage[enable_products_by_category_v2_location3]" value="1"
                                        {{ !empty($homepage['enable_products_by_category_v2_location3']) && $homepage['enable_products_by_category_v2_location3'] ? 'checked' : '' }}>
                                    Enable Products By Category v2 - Location 3
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Location 3 Categories (Select and drag to reorder)</label>
                                <ul id="homepage-category-v2-location3-sortable" class="list-group">
                                    @php
                                    $selected = isset($homepage['products_by_category_v2_location3_order'])
                                    ? json_decode($homepage['products_by_category_v2_location3_order'], true)
                                    : [];
                                    $allItems = collect($categories)->map(function ($cat) {
                                    return [
                                    'type' => 'category',
                                    'id' => $cat->id,
                                    'name' => $cat->name,
                                    ];
                                    });
                                    // Order selected first, then the rest
                                    $orderedItems = collect($selected)
                                    ->map(function ($item) use ($allItems) {
                                    return $allItems->first(function ($i) use ($item) {
                                    return $i['type'] . '-' . $i['id'] === $item;
                                    });
                                    })
                                    ->filter();
                                    $remainingItems = $allItems->filter(function ($i) use ($selected) {
                                    return !in_array($i['type'] . '-' . $i['id'], $selected);
                                    });
                                    $finalItems = $orderedItems->concat($remainingItems);
                                    @endphp
                                    @foreach ($finalItems as $item)
                                    @php $itemKey = $item['type'] . '-' . $item['id']; @endphp
                                    <li class="list-group-item d-flex align-items-center" data-id="{{ $itemKey }}">
                                        <input type="checkbox" class="mr-2 location3-category-checkbox"
                                            {{ in_array($itemKey, $selected) ? 'checked' : '' }}>
                                        <span class="flex-grow-1">{{ ucfirst($item['type']) }}: {{ $item['name'] }}</span>
                                        <span class="handle" style="cursor:move;">&#9776;</span>
                                    </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="homepage[products_by_category_v2_location3_order]"
                                    id="homepage-category-v2-location3-order"
                                    value='{{ $homepage['products_by_category_v2_location3_order'] ?? '[]' }}'>
                                <small class="form-text text-muted">Check to show, drag checked items to set order.</small>
                            </div>

                            <div class="form-group">
                                <label>Products per Category (Location 3)</label>
                                <input type="number" name="homepage[products_by_category_v2_location3_products_per_category]"
                                    class="form-control"
                                    value="{{ $homepage['products_by_category_v2_location3_products_per_category'] ?? 12 }}"
                                    min="1" max="50" step="1">
                                <small class="form-text text-muted">Number of products to show per category (1-50).</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <hr>
                            <div class="form-group">
                                <label>"View All" Button Text</label>
                                <input type="text" name="settings[view_all_button_text]" class="form-control"
                                    value="{{ setting('general', 'view_all_button_text', 'View All') }}">
                                <small class="form-text text-muted">Text to display on "View All" buttons throughout the site (e.g., "View All", "সব দেখুন", "→").</small>
                            </div>

                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="homepage[enable_customer_reviews_section]" value="0">
                                <label>
                                    <input type="checkbox" name="homepage[enable_customer_reviews_section]" value="1"
                                        {{ !empty($homepage['enable_customer_reviews_section']) && $homepage['enable_customer_reviews_section'] ? 'checked' : '' }}>
                                    Enable Customer Reviews Section In Home page
                                </label>
                            </div>

                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="homepage[enable_shop_features_section]" value="0">
                                <label>
                                    <input type="checkbox" name="homepage[enable_shop_features_section]" value="1"
                                        {{ !empty($homepage['enable_shop_features_section']) && $homepage['enable_shop_features_section'] ? 'checked' : '' }}>
                                    Enable Shop Features Section In Home page
                                </label>
                            </div>

                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="homepage[enable_category_scrollbar]" value="0">
                                <label>
                                    <input type="checkbox" name="homepage[enable_category_scrollbar]" value="1"
                                        {{ !empty($homepage['enable_customer_reviews_section']) && $homepage['enable_category_scrollbar'] ? 'checked' : '' }}>
                                    Enable Category Scrollbar Section In Home page
                                </label>
                            </div>

                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="homepage[enable_best_author_section]" value="0">
                                <label>
                                    <input type="checkbox" name="homepage[enable_best_author_section]" value="1"
                                        {{ !empty($homepage['enable_best_author_section']) && $homepage['enable_best_author_section'] ? 'checked' : '' }}>
                                    Enable Best Author Section In Home page
                                </label>
                            </div>

                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="homepage[enable_best_publisher_section]" value="0">
                                <label>
                                    <input type="checkbox" name="homepage[enable_best_publisher_section]" value="1"
                                        {{ !empty($homepage['enable_best_publisher_section']) && $homepage['enable_best_publisher_section'] ? 'checked' : '' }}>
                                    Enable Best Publisher Section In Home page
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="single-product" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Advanced Single Product</h2>
                    </div>

                    <div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 8px; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);">
                        <div class="card-header bg-light" style="padding: 12px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 600;">
                            <h5 class="mb-0"><i class="fas fa-calculator mr-2"></i>Product Price Auto-Calculation Percentages</h5>
                        </div>
                        <div class="card-body" style="padding: 20px;">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Sale Price Markup (%)</label>
                                        <input type="number" name="single_product[sale_price_percent]" class="form-control"
                                            value="{{ $single_product['sale_price_percent'] ?? 10 }}" min="0" step="0.1">
                                        <small class="form-text text-muted">Markup for Sale Price.</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Old Price Markup (%)</label>
                                        <input type="number" name="single_product[old_price_percent]" class="form-control"
                                            value="{{ $single_product['old_price_percent'] ?? 20 }}" min="0" step="0.1">
                                        <small class="form-text text-muted">Markup for Old Price.</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Wholesale Price Markup (%)</label>
                                        <input type="number" name="single_product[wholesale_price_percent]" class="form-control"
                                            value="{{ $single_product['wholesale_price_percent'] ?? 2 }}" min="0" step="0.1">
                                        <small class="form-text text-muted">Markup for Wholesale Price.</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Reseller Price Markup (%)</label>
                                        <input type="number" name="single_product[reseller_price_percent]" class="form-control"
                                            value="{{ $single_product['reseller_price_percent'] ?? 5 }}" min="0" step="0.1">
                                        <small class="form-text text-muted">Markup for Reseller Price.</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Global Price Markup (%)</label>
                                        <input type="number" name="single_product[global_price_percent]" class="form-control"
                                            value="{{ $single_product['global_price_percent'] ?? 10 }}" min="0" step="0.1">
                                        <small class="form-text text-muted">Markup for SaaS Global Price.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_bottom_category_slider]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_bottom_category_slider]" value="1"
                                        {{ !empty($single_product['enable_bottom_category_slider']) && $single_product['enable_bottom_category_slider'] ? 'checked' : '' }}>
                                    Enable Single Product Bottom Category Slider Section
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Section Title</label>
                                <input type="text" name="single_product[bottom_category_slider_title]" class="form-control"
                                    value="{{ $single_product['bottom_category_slider_title'] ?? 'Related Categories' }}">
                                <small class="form-text text-muted">Title for the bottom category slider section.</small>
                            </div>

                            <div class="form-group">
                                <label>Products per Category</label>
                                <input type="number" name="single_product[bottom_category_slider_products_per_category]" class="form-control"
                                    value="{{ $single_product['bottom_category_slider_products_per_category'] ?? 12 }}"
                                    min="1" max="50" step="1">
                                <small class="form-text text-muted">Number of products to show per category (1-50).</small>
                            </div>

                            <div class="form-group">
                                <label>Bottom Category Slider Categories (Select and drag to reorder)</label>
                                <ul id="single-product-bottom-category-sortable" class="list-group">
                                    @php
                                    $selected = isset($single_product['bottom_category_slider_categories_order'])
                                    ? json_decode($single_product['bottom_category_slider_categories_order'], true)
                                    : [];
                                    $allItems = collect($categories)->map(function ($cat) {
                                    return [
                                    'type' => 'category',
                                    'id' => $cat->id,
                                    'name' => $cat->name,
                                    ];
                                    });
                                    // Order selected first, then the rest
                                    $orderedItems = collect($selected)
                                    ->map(function ($item) use ($allItems) {
                                    return $allItems->first(function ($i) use ($item) {
                                    return $i['type'] . '-' . $i['id'] === $item;
                                    });
                                    })
                                    ->filter();
                                    $remainingItems = $allItems->filter(function ($i) use ($selected) {
                                    return !in_array($i['type'] . '-' . $i['id'], $selected);
                                    });
                                    $finalItems = $orderedItems->concat($remainingItems);
                                    @endphp
                                    @foreach ($finalItems as $item)
                                    @php $itemKey = $item['type'] . '-' . $item['id']; @endphp
                                    <li class="list-group-item d-flex align-items-center" data-id="{{ $itemKey }}">
                                        <input type="checkbox" name="single_product[bottom_category_slider_categories_selected][{{ $itemKey }}]"
                                            value="1" class="mr-2" {{ in_array($itemKey, $selected) ? 'checked' : '' }}>
                                        <span class="flex-grow-1">{{ ucfirst($item['type']) }}: {{ $item['name'] }}</span>
                                        <span class="handle" style="cursor:move;">&#9776;</span>
                                    </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="single_product[bottom_category_slider_categories_order]"
                                    id="single-product-bottom-category-order"
                                    value='{{ $single_product['bottom_category_slider_categories_order'] ?? '[]' }}'>
                                <small class="form-text text-muted">Check to show, drag checked items to set order.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_rating_summary]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_rating_summary]" value="1"
                                        {{ !empty($single_product['enable_rating_summary']) && $single_product['enable_rating_summary'] ? 'checked' : '' }}>
                                    Enable Product Rating Summary In Single Product Page
                                </label>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_short_info]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_short_info]" value="1"
                                        {{ !empty($single_product['enable_short_info']) && $single_product['enable_short_info'] ? 'checked' : '' }}>
                                    Enable Short Info Section In Single Product Page
                                </label>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_social_share]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_social_share]" value="1"
                                        {{ !empty($single_product['enable_social_share']) && $single_product['enable_social_share'] ? 'checked' : '' }}>
                                    Enable Social Share Section In Single Product Page
                                </label>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_related_products]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_related_products]" value="1"
                                        {{ !empty($single_product['enable_related_products']) && $single_product['enable_related_products'] ? 'checked' : '' }}>
                                    Enable Related Products Section In Single Product Page
                                </label>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_additional_related_products]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_additional_related_products]" value="1"
                                        {{ !empty($single_product['enable_additional_related_products']) && $single_product['enable_additional_related_products'] ? 'checked' : '' }}>
                                    Enable Additional Related Products Section In Single Product Page (End of the page)
                                </label>
                            </div>


                            <div class="form-group mt-3">
                                <label>Additional Related Products Section Title</label>
                                <input type="text" name="settings[additional_related_products_section_title]" class="form-control"
                                    value="{{ setting('general', 'additional_related_products_section_title', 'আরো দেখুন') }}" placeholder="আরো দেখুন">
                                <small class="form-text text-muted">Title for the additional related products section (e.g., "আরো দেখুন", "See More", "Related Products").</small>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_book_sample]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_book_sample]" value="1"
                                        {{ !empty($single_product['enable_book_sample']) && $single_product['enable_book_sample'] ? 'checked' : '' }}>
                                    Enable Book Sample Section In Single Product Page
                                </label>
                            </div>

                            <div class="form-group">
                                <label>Show Order Timeline?</label>
                                <select name="settings[show_order_timeline]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_order_timeline', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_order_timeline', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the order timeline section on product pages.</small>
                            </div>

                            <div class="form-group">
                                <label>Show Delivery Info Section?</label>
                                <select name="settings[show_delivery_info]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_delivery_info', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_delivery_info', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the delivery information section on product pages.</small>
                            </div>

                            <div class="form-group">
                                <label>Delivery Info Content</label>
                                <textarea name="settings[delivery_info]" class="form-control rich-text" rows="8">{{ setting('general', 'delivery_info', '') }}</textarea>
                                <small class="form-text text-muted">You can use lists, bold, links, etc.</small>
                            </div>

                            <hr>
                            <h6 class="text-primary font-weight-bold mb-3">Delivery & Return Options</h6>
                            
                            <div class="form-group row">
                                <label class="col-md-6 col-form-label">Cash on Delivery Available?</label>
                                <div class="col-md-6">
                                    <select name="settings[enable_cod_option]" class="form-control">
                                        <option value="1" {{ setting('general', 'enable_cod_option', '1') == '1' ? 'selected' : '' }}>Available</option>
                                        <option value="0" {{ setting('general', 'enable_cod_option', '1') == '0' ? 'selected' : '' }}>Not Available</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-form-label">Change of Mind Option?</label>
                                <div class="col-md-6">
                                    <select name="settings[enable_change_of_mind]" class="form-control">
                                        <option value="1" {{ setting('general', 'enable_change_of_mind', '1') == '1' ? 'selected' : '' }}>Available</option>
                                        <option value="0" {{ setting('general', 'enable_change_of_mind', '1') == '0' ? 'selected' : '' }}>Not Available</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-form-label">Easy Return Option</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" name="settings[return_days_option]" class="form-control" placeholder="14" value="{{ setting('general', 'return_days_option', '14') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Days</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Enter 0 to mark return as Not Available.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-form-label">Warranty Status</label>
                                <div class="col-md-6">
                                    <select name="settings[warranty_status_option]" class="form-control">
                                        <option value="0" {{ setting('general', 'warranty_status_option', '0') == '0' ? 'selected' : '' }}>Warranty not available</option>
                                        <option value="1" {{ setting('general', 'warranty_status_option', '0') == '1' ? 'selected' : '' }}>Warranty available</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-6 col-form-label">Show Product Brand on Single Page</label>
                                <div class="col-md-6">
                                    <select name="settings[show_product_brand_single]" class="form-control">
                                        <option value="1" {{ setting('general', 'show_product_brand_single', '1') == '1' ? 'selected' : '' }}>Show</option>
                                        <option value="0" {{ setting('general', 'show_product_brand_single', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                    </select>
                                    <small class="form-text text-muted">Show or hide product brand information on product single pages.</small>
                                </div>
                            </div>

                            <hr>
                            <h6 class="text-primary">Bottom Action Buttons</h6>
                            <div class="form-group">
                                <label>Show Bottom Action Buttons Section?</label>
                                <select name="settings[show_bottom_action_buttons]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_bottom_action_buttons', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_bottom_action_buttons', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the WhatsApp and Phone call buttons at the bottom of product pages.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Show WhatsApp Button?</label>
                                <select name="settings[show_whatsapp_button]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_whatsapp_button', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_whatsapp_button', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the WhatsApp contact button.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Show Phone Call Button?</label>
                                <select name="settings[show_phone_button]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_phone_button', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_phone_button', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the phone call button.</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Product Details Section</h6>
                            <div class="form-group">
                                <label>Show Product Details Section?</label>
                                <select name="settings[show_product_details_section]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_product_details_section', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_product_details_section', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the product details section (Category, Writer, Publisher, etc.).</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Category Label Text</label>
                                <input type="text" name="settings[category_label_text]" class="form-control"
                                    value="{{ setting('general', 'category_label_text', 'বিষয়') }}" placeholder="বিষয়">
                                <small class="form-text text-muted">Text label for the category field (e.g., "বিষয়", "Category", "ক্যাটাগরি").</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Writer Label Text</label>
                                <input type="text" name="settings[writer_label_text]" class="form-control"
                                    value="{{ setting('general', 'writer_label_text', 'লেখক') }}" placeholder="লেখক">
                                <small class="form-text text-muted">Text label for the writer field (e.g., "লেখক", "Author", "রাইটার").</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Publisher Label Text</label>
                                <input type="text" name="settings[publisher_label_text]" class="form-control"
                                    value="{{ setting('general', 'publisher_label_text', 'প্রকাশক') }}" placeholder="প্রকাশক">
                                <small class="form-text text-muted">Text label for the publisher field (e.g., "প্রকাশক", "Publisher", "পাবলিশার").</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>ISBN Label Text</label>
                                <input type="text" name="settings[isbn_label_text]" class="form-control"
                                    value="{{ setting('general', 'isbn_label_text', 'আইএসবিএন') }}" placeholder="আইএসবিএন">
                                <small class="form-text text-muted">Text label for the ISBN field (e.g., "আইএসবিএন", "ISBN").</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Pages Label Text</label>
                                <input type="text" name="settings[pages_label_text]" class="form-control"
                                    value="{{ setting('general', 'pages_label_text', 'পৃষ্ঠা') }}"
                                    placeholder="পৃষ্ঠা">
                                <small class="form-text text-muted">Text label for the pages field (e.g., "পৃষ্ঠা",
                                    "Pages", "পেজ").</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Language Label Text</label>
                                <input type="text" name="settings[language_label_text]" class="form-control"
                                    value="{{ setting('general', 'language_label_text', 'ভাষা') }}" placeholder="ভাষা">
                                <small class="form-text text-muted">Text label for the language field (e.g., "ভাষা", "Language", "ল্যাঙ্গুয়েজ").</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Short Description Section</h6>
                            <div class="form-group">
                                <label>Show Short Description Section?</label>
                                <select name="settings[show_short_description_section]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_short_description_section', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_short_description_section', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the short description section with expand/collapse functionality.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>"Read More" Button Text</label>
                                <input type="text" name="settings[read_more_button_text]" class="form-control"
                                    value="{{ setting('general', 'read_more_button_text', 'বিস্তারিত') }}" placeholder="বিস্তারিত">
                                <small class="form-text text-muted">Text for the "Read More" button (e.g., "বিস্তারিত", "Read More", "আরও দেখুন").</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Product Meta Information</h6>
                            <div class="form-group">
                                <label>Show Product Meta Section?</label>
                                <select name="settings[show_product_meta_section]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_product_meta_section', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_product_meta_section', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the product meta information section (SKU, Availability).</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Show SKU Field?</label>
                                <select name="settings[show_sku_field]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_sku_field', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_sku_field', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the SKU field in the product meta section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Show Availability Field?</label>
                                <select name="settings[show_availability_field]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_availability_field', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_availability_field', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the availability field in the product meta section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Availability Label Text</label>
                                <input type="text" name="settings[availability_label_text]" class="form-control"
                                    value="{{ setting('general', 'availability_label_text', 'Availability') }}" placeholder="Availability">
                                <small class="form-text text-muted">Text label for the availability field (e.g., "Availability", "স্টক", "উপলব্ধতা").</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Product Sections</h6>
                            <div class="form-group">
                                <label>Show Product Description Section?</label>
                                <select name="settings[show_product_description_section]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_product_description_section', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_product_description_section', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the product description section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Show Ratings & Reviews Section?</label>
                                <select name="settings[show_ratings_reviews_section]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_ratings_reviews_section', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_ratings_reviews_section', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the ratings and reviews
                                    section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Description Section Title</label>
                                <input type="text" name="settings[description_section_title]" class="form-control"
                                    value="{{ setting('general', 'description_section_title', 'Product Description') }}" placeholder="Product Description">
                                <small class="form-text text-muted">Title for the product description section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Ratings Section Title</label>
                                <input type="text" name="settings[ratings_section_title]" class="form-control"
                                    value="{{ setting('general', 'ratings_section_title', 'Customer Ratings & Reviews') }}" placeholder="Customer Ratings & Reviews">
                                <small class="form-text text-muted">Title for the ratings and reviews section.</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Review Form Section</h6>
                            <div class="form-group">
                                <label>Show Review Form Section?</label>
                                <select name="settings[show_review_form_section]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_review_form_section', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_review_form_section', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the review form section where customers can submit reviews.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Review Form Header Title</label>
                                <input type="text" name="settings[review_form_header_title]" class="form-control"
                                    value="{{ setting('general', 'review_form_header_title', 'এই পণ্য সম্পর্কে আপনার মূল্যবান মতামত লিখুন') }}" placeholder="এই পণ্য সম্পর্কে আপনার মূল্যবান মতামত লিখুন">
                                <small class="form-text text-muted">Header title for the review form section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Review Form Submit Button Text</label>
                                <input type="text" name="settings[review_form_submit_button_text]" class="form-control"
                                    value="{{ setting('general', 'review_form_submit_button_text', 'আপনার মতামত সাবমিট করুন') }}" placeholder="আপনার মতামত সাবমিট করুন">
                                <small class="form-text text-muted">Text for the review form submit button.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Review Form Comment Placeholder</label>
                                <input type="text" name="settings[review_form_comment_placeholder]" class="form-control"
                                    value="{{ setting('general', 'review_form_comment_placeholder', 'Write your comment...') }}" placeholder="Write your comment...">
                                <small class="form-text text-muted">Placeholder text for the review comment textarea.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Guest Name Label</label>
                                <input type="text" name="settings[guest_name_label]" class="form-control"
                                    value="{{ setting('general', 'guest_name_label', 'আপনার নাম') }}" placeholder="আপনার নাম">
                                <small class="form-text text-muted">Label for the guest name field in the review form.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Guest Email Label</label>
                                <input type="text" name="settings[guest_email_label]" class="form-control"
                                    value="{{ setting('general', 'guest_email_label', 'আপনার ইমেইল') }}" placeholder="আপনার ইমেইল">
                                <small class="form-text text-muted">Label for the guest email field in the review form.</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Related Products Section</h6>
                            <div class="form-group">
                                <label>Show Related Products Section?</label>
                                <select name="settings[show_related_products_section]" class="form-control">
                                    <option value="1" {{ setting('general', 'show_related_products_section', '1') == '1' ? 'selected' : '' }}>Show</option>
                                    <option value="0" {{ setting('general', 'show_related_products_section', '1') == '0' ? 'selected' : '' }}>Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the related products section in the sidebar.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Related Products Section Title</label>
                                <input type="text" name="settings[related_products_section_title]" class="form-control"
                                    value="{{ setting('general', 'related_products_section_title', 'আরো দেখুন') }}" placeholder="আরো দেখুন">
                                <small class="form-text text-muted">Title for the related products section (e.g., "আরো দেখুন", "See More", "Related Products").</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Settings Tab -->
                <div id="registration" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Registration Settings</h2>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Email Field Settings</h6>

                            <div class="form-group">
                                <input type="hidden" name="registration[email_enabled]" value="0">
                                <label>
                                    <input type="checkbox" name="registration[email_enabled]" value="1"
                                        {{ setting('registration', 'email_enabled', '1') == '1' ? 'checked' : '' }}>
                                    Enable Email Field in Registration
                                </label>
                                <small class="form-text text-muted">Show email field in the registration form.</small>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="registration[email_required]" value="0">
                                <label>
                                    <input type="checkbox" name="registration[email_required]" value="1"
                                        {{ setting('registration', 'email_required', '0') == '1' ? 'checked' : '' }}>
                                    Make Email Required
                                </label>
                                <small class="form-text text-muted">If enabled, users must provide an email address to register.</small>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Phone OTP Verification Settings</h6>

                            <div class="form-group">
                                <input type="hidden" name="registration[phone_otp_enabled]" value="0">
                                <label>
                                    <input type="checkbox" name="registration[phone_otp_enabled]" value="1"
                                        {{ setting('registration', 'phone_otp_enabled', '1') == '1' ? 'checked' : '' }}>
                                    Enable Phone OTP Verification
                                </label>
                                <small class="form-text text-muted">Require phone number verification via OTP during registration.</small>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="registration[create_account_after_otp]" value="0">
                                <label>
                                    <input type="checkbox" name="registration[create_account_after_otp]" value="1"
                                        {{ setting('registration', 'create_account_after_otp', '1') == '1' ? 'checked' : '' }}>
                                    Create Account Only After OTP Verification
                                </label>
                                <small class="form-text text-muted">If enabled, user account will be created only after successful phone verification. If disabled, account is created first then verified.</small>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">SMS Settings</h6>

                            <div class="form-group">
                                <label>OTP SMS Template</label>
                                <textarea name="registration[otp_sms_template]" class="form-control" rows="3" placeholder="Dear {name}\nYour Mobile OTP Verification Code is : {otp_code}\nThank you from Thikana Shop">{{ setting('registration', 'otp_sms_template', 'Dear {name}\nYour Mobile OTP Verification Code is : {otp_code}\nThank you from Thikana Shop') }}</textarea>
                                <small class="form-text text-muted">SMS template for OTP. Use {name} for user name and {otp_code} for OTP code.</small>
                            </div>

                            <div class="form-group">
                                <label>OTP Resend SMS Template</label>
                                <textarea name="registration[otp_resend_sms_template]" class="form-control" rows="3" placeholder="Dear {name}\nYour Mobile New OTP Verification Code is : {otp_code}\nThank you from Thikana Shop">{{ setting('registration', 'otp_resend_sms_template', 'Dear {name}\nYour Mobile New OTP Verification Code is : {otp_code}\nThank you from Thikana Shop') }}</textarea>
                                <small class="form-text text-muted">SMS template for OTP resend. Use {name} for user name and {otp_code} for OTP code.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Settings Tab -->
                <div id="mobile-nav" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Mobile Navigation Settings</h2>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">Mobile Floating Navigation Settings</h6>

                            <!-- Master Toggle -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Enable Mobile Navigation</h6>
                                    <small class="text-muted">Master control to show or hide the entire mobile floating navigation section.</small>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="hidden" name="mobile_nav[enabled]" value="0">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" name="mobile_nav[enabled]" value="1"
                                                {{ setting('mobile_nav', 'enabled', '1') == '1' ? 'checked' : '' }} id="mobile_nav_enabled">
                                            <label class="form-check-label" for="mobile_nav_enabled">
                                                <strong>Enable Mobile Floating Navigation</strong>
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">When disabled, the entire mobile navigation section will be hidden from the frontend.</small>
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-primary mb-3">Navigation Items Configuration</h6>
                            <p class="text-muted mb-4">Configure which navigation items to show and their order in the mobile floating navigation bar.</p>

                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Navigation Items Configuration</h6>
                                    <small class="text-muted">Drag items to reorder them. Toggle visibility for each item.</small>
                                </div>
                                <div class="card-body">
                                    <div id="mobile-nav-items" class="sortable-list">
                                        <!-- Home Item -->
                                        <div class="nav-item-config" data-item="home">
                                            <div class="d-flex align-items-center justify-content-between p-2 border rounded mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="drag-handle mr-3" style="cursor: move;">
                                                        <i class="fas fa-grip-vertical text-muted"></i>
                                                    </div>
                                                    <div class="nav-item-icon mr-3">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" style="fill: #e74c3c;">
                                                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label mb-1">Label</label>
                                                            <input type="text" class="form-control form-control-sm" name="mobile_nav[home_label]"
                                                                value="{{ setting('mobile_nav', 'home_label', 'হোম') }}" placeholder="হোম">
                                                        </div>
                                                        <small class="text-muted">Home page link</small>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input type="hidden" name="mobile_nav[home_enabled]" value="0">
                                                    <input type="checkbox" class="form-check-input" name="mobile_nav[home_enabled]" value="1"
                                                        {{ setting('mobile_nav', 'home_enabled', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Show</label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="mobile_nav[home_order]" value="{{ setting('mobile_nav', 'home_order', '1') }}">
                                        </div>

                                        <!-- Cart Item -->
                                        <div class="nav-item-config" data-item="cart">
                                            <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="drag-handle mr-3" style="cursor: move;">
                                                        <i class="fas fa-grip-vertical text-muted"></i>
                                                    </div>
                                                    <div class="nav-item-icon mr-3">
                                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z" style="fill: #2c3e50;" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label mb-1">Label</label>
                                                            <input type="text" class="form-control form-control-sm" name="mobile_nav[cart_label]"
                                                                value="{{ setting('mobile_nav', 'cart_label', 'কার্ট') }}" placeholder="কার্ট">
                                                        </div>
                                                        <small class="text-muted">Shopping cart link</small>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input type="hidden" name="mobile_nav[cart_enabled]" value="0">
                                                    <input type="checkbox" class="form-check-input" name="mobile_nav[cart_enabled]" value="1"
                                                        {{ setting('mobile_nav', 'cart_enabled', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Show</label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="mobile_nav[cart_order]" value="{{ setting('mobile_nav', 'cart_order', '2') }}">
                                        </div>

                                        <!-- Chat Item -->
                                        <div class="nav-item-config" data-item="chat">
                                            <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="drag-handle mr-3" style="cursor: move;">
                                                        <i class="fas fa-grip-vertical text-muted"></i>
                                                    </div>
                                                    <div class="nav-item-icon mr-3">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="fill: #3498db;">
                                                            <path d="M20 2H4C2.9 2 2 2.9 2 4V16C2 17.1 2.9 18 4 18H6L10 22L14 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H13.17L10 19.17L6.83 16H4V4H20V16Z" />
                                                            <circle cx="8" cy="10" r="1" />
                                                            <circle cx="12" cy="10" r="1" />
                                                            <circle cx="16" cy="10" r="1" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label mb-1">Label</label>
                                                            <input type="text" class="form-control form-control-sm" name="mobile_nav[chat_label]"
                                                                value="{{ setting('mobile_nav', 'chat_label', 'চ্যাট') }}" placeholder="চ্যাট">
                                                        </div>
                                                        <small class="text-muted">Chat support link</small>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input type="hidden" name="mobile_nav[chat_enabled]" value="0">
                                                    <input type="checkbox" class="form-check-input" name="mobile_nav[chat_enabled]" value="1"
                                                        {{ setting('mobile_nav', 'chat_enabled', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Show</label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="mobile_nav[chat_order]" value="{{ setting('mobile_nav', 'chat_order', '3') }}">
                                        </div>

                                        <!-- Call Item -->
                                        <div class="nav-item-config" data-item="call">
                                            <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="drag-handle mr-3" style="cursor: move;">
                                                        <i class="fas fa-grip-vertical text-muted"></i>
                                                    </div>
                                                    <div class="nav-item-icon mr-3">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" style="fill: #27ae60;">
                                                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label mb-1">Label</label>
                                                            <input type="text" class="form-control form-control-sm" name="mobile_nav[call_label]"
                                                                value="{{ setting('mobile_nav', 'call_label', 'কল') }}" placeholder="কল">
                                                        </div>
                                                        <small class="text-muted">Phone call link</small>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input type="hidden" name="mobile_nav[call_enabled]" value="0">
                                                    <input type="checkbox" class="form-check-input" name="mobile_nav[call_enabled]" value="1"
                                                        {{ setting('mobile_nav', 'call_enabled', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Show</label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="mobile_nav[call_order]" value="{{ setting('mobile_nav', 'call_order', '4') }}">
                                        </div>

                                        <!-- Profile Item -->
                                        <div class="nav-item-config" data-item="profile">
                                            <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="drag-handle mr-3" style="cursor: move;">
                                                        <i class="fas fa-grip-vertical text-muted"></i>
                                                    </div>
                                                    <div class="nav-item-icon mr-3">
                                                        <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M20.2313 18.375C18.8319 15.9269 16.6716 14.1685 14.1455 13.3374C16.7168 11.8055 17.9799 8.74371 17.1836 5.86726C16.3874 2.99081 13.7903 0.997722 10.5 0.997722C7.20975 0.997722 4.61261 2.99081 3.81637 5.86726C3.02013 8.74371 4.28324 11.8055 6.85453 13.3374C4.32844 14.1675 2.16812 15.9259 0.76875 18.375C0.619540 18.6129 0.614176 18.9107 0.751669 19.1533C0.889162 19.3959 1.14893 19.5453 1.42689 19.5429C1.70486 19.5404 1.96256 19.3861 2.09718 19.1406C3.88774 16.0513 7.06471 14.2031 10.5 14.2031C13.9353 14.2031 17.1123 16.0513 18.9028 19.1406C19.0374 19.3861 19.2951 19.5404 19.5731 19.5429C19.8511 19.5453 20.1108 19.3959 20.2483 19.1533C20.3858 18.9107 20.3805 18.6129 20.2313 18.375V18.375ZM4.9875 7.4531C4.9875 4.60539 7.35229 2.2406 10.5 2.2406C13.6477 2.2406 16.0125 4.60539 16.0125 7.4531C16.0125 10.3008 13.6477 12.6656 10.5 12.6656C7.35356 12.6625 4.99044 10.2994 4.9875 7.4531V7.4531Z" style="fill: #8e44ad;" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label mb-1">Label</label>
                                                            <input type="text" class="form-control form-control-sm" name="mobile_nav[profile_label]"
                                                                value="{{ setting('mobile_nav', 'profile_label', 'প্রোফাইল') }}" placeholder="প্রোফাইল">
                                                        </div>
                                                        <small class="text-muted">User profile link</small>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input type="hidden" name="mobile_nav[profile_enabled]" value="0">
                                                    <input type="checkbox" class="form-check-input" name="mobile_nav[profile_enabled]" value="1"
                                                        {{ setting('mobile_nav', 'profile_enabled', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Show</label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="mobile_nav[profile_order]" value="{{ setting('mobile_nav', 'profile_order', '5') }}">
                                        </div>

                                        <!-- Category Item -->
                                        <div class="nav-item-config" data-item="category">
                                            <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="drag-handle mr-3" style="cursor: move;">
                                                        <i class="fas fa-grip-vertical text-muted"></i>
                                                    </div>
                                                    <div class="nav-item-icon mr-3">
                                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M3 13H11V3H3V13ZM3 21H11V15H3V21ZM13 21H21V11H13V21ZM13 3V9H21V3H13Z" style="fill: #f39c12;" />
                                                        </svg>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="form-group mb-2">
                                                            <label class="form-label mb-1">Label</label>
                                                            <input type="text" class="form-control form-control-sm" name="mobile_nav[category_label]"
                                                                value="{{ setting('mobile_nav', 'category_label', 'ক্যাটাগরি') }}" placeholder="ক্যাটাগরি">
                                                        </div>
                                                        <small class="text-muted">Category browser popup</small>
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input type="hidden" name="mobile_nav[category_enabled]" value="0">
                                                    <input type="checkbox" class="form-check-input" name="mobile_nav[category_enabled]" value="1"
                                                        {{ setting('mobile_nav', 'category_enabled', '1') == '1' ? 'checked' : '' }}>
                                                    <label class="form-check-label">Show</label>
                                                </div>
                                            </div>
                                            <input type="hidden" name="mobile_nav[category_order]" value="{{ setting('mobile_nav', 'category_order', '6') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i> Save Mobile Navigation Settings
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Layout Settings Tab -->
                <div id="layout" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Layout Settings</h2>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Container Width:</strong> Control the maximum width of your website's main content area. This affects all pages.
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Container Max Width (px)</label>
                        <div class="col-md-9">
                            <input type="number" name="layout[container_max_width]"
                                   class="form-control"
                                   value="{{ setting('layout', 'container_max_width', '1340') }}"
                                   min="960" max="1920" step="10">
                            <small class="form-text text-muted">
                                Main container width in pixels. Common values: 1140px (Bootstrap), 1200px, 1340px (default), 1400px, 1600px (wide). Range: 960-1920px.
                            </small>
                            <div class="mt-2">
                                <span class="badge badge-secondary mr-1" style="cursor:pointer;" onclick="document.querySelector('input[name=\'layout[container_max_width]\']').value='1140'">1140</span>
                                <span class="badge badge-secondary mr-1" style="cursor:pointer;" onclick="document.querySelector('input[name=\'layout[container_max_width]\']').value='1200'">1200</span>
                                <span class="badge badge-primary mr-1" style="cursor:pointer;" onclick="document.querySelector('input[name=\'layout[container_max_width]\']').value='1340'">1340 (default)</span>
                                <span class="badge badge-secondary mr-1" style="cursor:pointer;" onclick="document.querySelector('input[name=\'layout[container_max_width]\']').value='1400'">1400</span>
                                <span class="badge badge-secondary mr-1" style="cursor:pointer;" onclick="document.querySelector('input[name=\'layout[container_max_width]\']').value='1600'">1600</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Container Padding (px)</label>
                        <div class="col-md-9">
                            <input type="number" name="layout[container_padding]"
                                   class="form-control"
                                   value="{{ setting('layout', 'container_padding', '15') }}"
                                   min="0" max="50" step="5">
                            <small class="form-text text-muted">
                                Horizontal padding for the container on smaller screens. Default: 15px. Range: 0-50px.
                            </small>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i> Save Layout Settings
                        </button>
                    </div>
                </div>

                <!-- Mega Menu Settings Tab -->
                <div id="mega-menu" class="tab-content">
                    <div class="tab-header">
                        <h2 class="tab-title">Mega Menu Settings</h2>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>How it works:</strong> Enable mega menu globally, then configure individual menu items in the Menu Manager.
                        Go to <a href="{{ route('admin.menus.index') }}" class="alert-link">Menus</a> to configure which top-level menu items should display as mega menus.
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Enable Mega Menu Feature</label>
                        <div class="col-md-9">
                            <select name="navigation[mega_menu_enabled]" class="form-control">
                                <option value="1" {{ setting('navigation', 'mega_menu_enabled', '0') == '1' ? 'selected' : '' }}>
                                    Enabled
                                </option>
                                <option value="0" {{ setting('navigation', 'mega_menu_enabled', '0') == '0' ? 'selected' : '' }}>
                                    Disabled
                                </option>
                            </select>
                            <small class="form-text text-muted">
                                When disabled, mega menu CSS/JS will not be loaded for better performance.
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Hover Delay (ms)</label>
                        <div class="col-md-9">
                            <input type="number" name="navigation[mega_menu_hover_delay]"
                                   class="form-control"
                                   value="{{ setting('navigation', 'mega_menu_hover_delay', '150') }}"
                                   min="0" max="500">
                            <small class="form-text text-muted">
                                Delay before mega menu appears on hover (0-500ms). Lower = faster response.
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Animation Type</label>
                        <div class="col-md-9">
                            <select name="navigation[mega_menu_animation]" class="form-control">
                                <option value="fade" {{ setting('navigation', 'mega_menu_animation', 'fade') == 'fade' ? 'selected' : '' }}>
                                    Fade In
                                </option>
                                <option value="slide" {{ setting('navigation', 'mega_menu_animation', 'slide') == 'slide' ? 'selected' : '' }}>
                                    Slide Down
                                </option>
                                <option value="none" {{ setting('navigation', 'mega_menu_animation', 'none') == 'none' ? 'selected' : '' }}>
                                    None (Instant)
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Mega Menu Background</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" name="navigation[mega_menu_bg_color]"
                                       class="form-control color-input" id="mega_menu_bg_color_input"
                                       value="{{ setting('navigation', 'mega_menu_bg_color', '#ffffff') }}">
                                <div class="input-group-append">
                                    <span id="mega_menu_bg_color_preview"
                                          style="display:inline-block;width:38px;height:38px;border-radius:4px;border:1px solid #ccc;background:{{ setting('navigation', 'mega_menu_bg_color', '#ffffff') }};"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Column Header Color</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" name="navigation[mega_menu_header_color]"
                                       class="form-control color-input" id="mega_menu_header_color_input"
                                       value="{{ setting('navigation', 'mega_menu_header_color', '#333333') }}">
                                <div class="input-group-append">
                                    <span id="mega_menu_header_color_preview"
                                          style="display:inline-block;width:38px;height:38px;border-radius:4px;border:1px solid #ccc;background:{{ setting('navigation', 'mega_menu_header_color', '#333333') }};"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Link Color</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" name="navigation[mega_menu_link_color]"
                                       class="form-control color-input" id="mega_menu_link_color_input"
                                       value="{{ setting('navigation', 'mega_menu_link_color', '#555555') }}">
                                <div class="input-group-append">
                                    <span id="mega_menu_link_color_preview"
                                          style="display:inline-block;width:38px;height:38px;border-radius:4px;border:1px solid #ccc;background:{{ setting('navigation', 'mega_menu_link_color', '#555555') }};"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Link Hover Color</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="text" name="navigation[mega_menu_link_hover_color]"
                                       class="form-control color-input" id="mega_menu_link_hover_color_input"
                                       value="{{ setting('navigation', 'mega_menu_link_hover_color', 'var(--primary-color)') }}">
                                <div class="input-group-append">
                                    <span id="mega_menu_link_hover_color_preview"
                                          style="display:inline-block;width:38px;height:38px;border-radius:4px;border:1px solid #ccc;background:{{ setting('navigation', 'mega_menu_link_hover_color', 'var(--primary-color)') }};"></span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Use var(--primary-color) to match your site's primary color.</small>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i> Save Mega Menu Settings
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/ace.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/mode-html.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/mode-javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/mode-css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/theme-monokai.min.js"></script>
<style>
    .ace-editor {
        border: 1px solid #ddd;
        border-radius: 4px;
        height: 300px;
        width: 100%;
    }

    .code-editor {
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
        font-size: 14px;
        line-height: 1.5;
        display: none !important;
        /* Hide the original textarea */
    }

    .code-editor-container {
        position: relative;
    }
</style>
<script>
    // Simple tab switching functionality with hash support
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.sidebar-tab');
        const tabContents = document.querySelectorAll('.tab-content');

        // Function to switch to a specific tab
        function switchToTab(tabId) {
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));

            // Add active class to target tab and corresponding content
            const targetTab = document.querySelector(`[data-tab="${tabId}"]`);
            const targetContent = document.getElementById(tabId);

            if (targetTab && targetContent) {
                targetTab.classList.add('active');
                targetContent.classList.add('active');

                // Scroll the tab into view horizontally on mobile
                targetTab.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });

                // Reinitialize mobile nav sortable if mobile nav tab is clicked
                if (tabId === 'mobile-nav') {
                    setTimeout(() => {
                        initializeMobileNavSortable();
                    }, 100);
                }
            }
        }

        // Handle tab clicks
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent default anchor behavior
                const targetTab = this.getAttribute('data-tab');

                // Update URL hash
                window.location.hash = targetTab;

                // Switch to the tab
                switchToTab(targetTab);
            });
        });

        // Handle hash change (back/forward buttons)
        window.addEventListener('hashchange', function() {
            const hash = window.location.hash.substring(1); // Remove # from hash
            if (hash) {
                switchToTab(hash);
            }
        });

        // Check for hash or tab parameter on page load
        const hash = window.location.hash.substring(1);
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');

        // Priority: hash first, then tab parameter, then default to general
        const targetTab = hash || tabParam || 'general';
        switchToTab(targetTab);

        // Preserve hash when form is submitted + refresh CSRF token to prevent 419
        const form = document.getElementById('settingsForm');
        if (form) {
            // Store the original clean action URL (without any tab params)
            const baseAction = '{{ route('admin.settings.update') }}';

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Stop default submission

                // Always reset to clean base URL first (prevents duplicate ?tab= bug)
                const currentHash = window.location.hash;
                if (currentHash) {
                    form.action = baseAction + '?tab=' + currentHash.substring(1);
                } else {
                    form.action = baseAction;
                }

                // Fetch a fresh CSRF token before submitting to prevent 419
                fetch('/csrf-token-refresh', { credentials: 'same-origin' })
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (data && data.token) {
                            // Update the hidden _token input in the form
                            var tokenInput = form.querySelector('input[name="_token"]');
                            if (tokenInput) tokenInput.value = data.token;

                            // Also update the meta tag
                            var metaTag = document.querySelector('meta[name="csrf-token"]');
                            if (metaTag) metaTag.setAttribute('content', data.token);
                        }
                        form.submit(); // Now submit with fresh token
                    })
                    .catch(function() {
                        // If token refresh fails, submit anyway (token may still be valid)
                        form.submit();
                    });
            });
        }

        // Initialize Summernote for rich text areas
        $('.rich-text').summernote({
            height: 200
        });

        // Show filename in custom file input
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Initialize mobile navigation drag and drop
        initializeMobileNavSortable();

        // Initialize mobile navigation master toggle
        initializeMobileNavMasterToggle();

        // Initialize top header version switching
        initializeTopHeaderVersionSwitching();

        // Initialize code editors
        initializeCodeEditors();

    });

    // Mobile Navigation Drag and Drop Functionality
    function initializeMobileNavSortable() {
        const sortableList = document.getElementById('mobile-nav-items');

        if (!sortableList) {
            return;
        }

        // Check if sortable is already initialized
        if (sortableList.sortableInstance) {
            sortableList.sortableInstance.destroy();
        }

        const sortableInstance = new Sortable(sortableList, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function(evt) {
                updateMobileNavOrder();
            }
        });

        // Store the instance for future reference
        sortableList.sortableInstance = sortableInstance;

        // Reorder items after sortable is initialized
        reorderMobileNavItems();
    }

    // Update mobile navigation order after drag and drop
    function updateMobileNavOrder() {
        const navItems = document.querySelectorAll('#mobile-nav-items .nav-item-config');

        navItems.forEach((item, index) => {
            const itemName = item.dataset.item;

            // Try different selectors to find the order input
            let orderInput = item.querySelector(`input[name="mobile_nav[${itemName}_order]"]`);
            if (!orderInput) {
                orderInput = item.querySelector('input[name*="_order"]');
            }
            if (!orderInput) {
                orderInput = item.querySelector('input[type="hidden"]');
            }

            if (orderInput) {
                orderInput.value = index + 1;

                // Add visual feedback
                const itemElement = item.querySelector('.d-flex');
                if (itemElement) {
                    itemElement.style.backgroundColor = '#e8f5e8';
                    setTimeout(() => {
                        itemElement.style.backgroundColor = '';
                    }, 1000);
                }
            }
        });

    }

    // Reorder mobile navigation items based on current database order values
    function reorderMobileNavItems() {
        const container = document.getElementById('mobile-nav-items');
        if (!container) return;

        const items = Array.from(container.querySelectorAll('.nav-item-config'));

        // Sort items by their current order values
        items.sort((a, b) => {
            const aInput = a.querySelector('input[name*="_order"]');
            const bInput = b.querySelector('input[name*="_order"]');

            if (!aInput || !bInput) return 0;

            const aOrder = parseInt(aInput.value) || 0;
            const bOrder = parseInt(bInput.value) || 0;

            return aOrder - bOrder;
        });

        // Re-append items in sorted order
        items.forEach(item => {
            container.appendChild(item);
        });
    }

    // Mobile Navigation Master Toggle Functionality
    function initializeMobileNavMasterToggle() {
        const masterToggle = document.getElementById('mobile_nav_enabled');
        const navItemsCard = document.querySelector('#mobile-nav .card:last-child');

        if (!masterToggle || !navItemsCard) return;

        function toggleNavItems(enable) {
            const checkboxes = navItemsCard.querySelectorAll('input[type="checkbox"]');
            const dragHandles = navItemsCard.querySelectorAll('.drag-handle');

            checkboxes.forEach(checkbox => {
                checkbox.disabled = !enable;
            });

            dragHandles.forEach(handle => {
                handle.style.cursor = enable ? 'move' : 'not-allowed';
                handle.style.opacity = enable ? '1' : '0.5';
            });

            if (enable) {
                navItemsCard.style.opacity = '1';
                navItemsCard.querySelector('.card-header small').textContent = 'Drag items to reorder them. Toggle visibility for each item.';
            } else {
                navItemsCard.style.opacity = '0.6';
                navItemsCard.querySelector('.card-header small').textContent = 'Enable mobile navigation above to configure individual items.';
            }
        }

        // Initialize state
        toggleNavItems(masterToggle.checked);

        // Listen for changes
        masterToggle.addEventListener('change', function() {
            toggleNavItems(this.checked);
        });
    }

    // Color preview functionality
    function updatePreview(inputId, previewId) {
        var input = document.getElementById(inputId);
        var preview = document.getElementById(previewId);
        if (input && preview) {
            input.addEventListener('input', function() {
                preview.style.background = input.value;
            });
        }
    }

    // Font preview update
    function updateFontPreview() {
        var fontSelect = document.getElementById('website_font_select');
        var fontPreview = document.getElementById('font_preview');
        if (fontSelect && fontPreview) {
            fontSelect.addEventListener('change', function() {
                var selectedFont = fontSelect.value;
                if (selectedFont === 'hind-siliguri') {
                    fontPreview.style.fontFamily = 'Hind Siliguri, sans-serif';
                } else if (selectedFont === 'inter') {
                    fontPreview.style.fontFamily = 'Inter, sans-serif';
                } else if (selectedFont === 'noto-sans-bengali') {
                    fontPreview.style.fontFamily = 'Noto Sans Bengali, sans-serif';
                }
            });
        }
    }

    // Box shadow preset functionality
    function setupBoxShadowPreset() {
        const boxShadowPreset = document.getElementById('main_nav_box_shadow_preset');
        const boxShadowCustom = document.getElementById('main_nav_box_shadow_custom');

        if (boxShadowPreset && boxShadowCustom) {
            boxShadowPreset.addEventListener('change', function() {
                const selectedValue = this.value;
                const presetShadows = {
                    'none': 'none',
                    'default': '0 3px 5px rgba(57, 63, 72, 0.3)',
                    'subtle': '0 1px 3px rgba(0, 0, 0, 0.1)',
                    'medium': '0 4px 8px rgba(0, 0, 0, 0.15)',
                    'strong': '0 6px 20px rgba(0, 0, 0, 0.25)',
                    'elegant': '0 8px 25px rgba(0, 0, 0, 0.1), 0 4px 10px rgba(0, 0, 0, 0.05)',
                    'custom': ''
                };

                if (selectedValue === 'custom') {
                    boxShadowCustom.style.display = 'block';
                    boxShadowCustom.focus();
                } else {
                    boxShadowCustom.style.display = 'none';
                    boxShadowCustom.value = presetShadows[selectedValue];
                }
            });
        }
    }

    // Initialize all preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Color previews
        updatePreview('primary_color_input', 'primary_color_preview');
        updatePreview('secondary_color_input', 'secondary_color_preview');
        updatePreview('accent_color_input', 'accent_color_preview');
        updatePreview('section_header_left_bar_color_input', 'section_header_left_bar_color_preview');
        updatePreview('main_nav_background_color_input', 'main_nav_background_color_preview');
        updatePreview('main_nav_text_color_input', 'main_nav_text_color_preview');
        updatePreview('main_nav_hover_bg_color_input', 'main_nav_hover_bg_color_preview');
        updatePreview('main_nav_hover_text_color_input', 'main_nav_hover_text_color_preview');
        updatePreview('main_nav_border_right_color_input', 'main_nav_border_right_color_preview');
        updatePreview('header_icons_color_input', 'header_icons_color_preview');

        // Dropdown/Submenu color previews
        updatePreview('dropdown_bg_color_input', 'dropdown_bg_color_preview');
        updatePreview('dropdown_text_color_input', 'dropdown_text_color_preview');
        updatePreview('dropdown_hover_bg_color_input', 'dropdown_hover_bg_color_preview');
        updatePreview('dropdown_hover_text_color_input', 'dropdown_hover_text_color_preview');

        // Mega menu color previews
        updatePreview('mega_menu_bg_color_input', 'mega_menu_bg_color_preview');
        updatePreview('mega_menu_header_color_input', 'mega_menu_header_color_preview');
        updatePreview('mega_menu_link_color_input', 'mega_menu_link_color_preview');
        updatePreview('mega_menu_link_hover_color_input', 'mega_menu_link_hover_color_preview');

        // Font preview
        updateFontPreview();

        // Box shadow preset
        setupBoxShadowPreset();

        // Initialize sortable functionality
        initializeSortables();

        // Initialize color picker sync
        initializeColorPickerSync();
    });

    // Sortable functionality for homepage customization
    function initializeSortables() {
        // Homepage featured categories sortable
        const homepageFeaturedSortable = document.getElementById('homepage-featured-sortable');
        if (homepageFeaturedSortable) {
            new Sortable(homepageFeaturedSortable, {
                handle: '.handle',
                animation: 150,
                onSort: function() {
                    updateHomepageFeaturedOrder();
                }
            });

            // Update order when checkboxes change
            homepageFeaturedSortable.addEventListener('change', function(e) {
                if (e.target.classList.contains('featured-checkbox')) {
                    updateHomepageFeaturedOrder();
                }
            });
        }

        // Single product bottom category sortable
        const singleProductBottomCategorySortable = document.getElementById('single-product-bottom-category-sortable');
        if (singleProductBottomCategorySortable) {
            new Sortable(singleProductBottomCategorySortable, {
                handle: '.handle',
                animation: 150,
                onSort: function() {
                    updateSingleProductBottomCategoryOrder();
                }
            });

            // Update order when checkboxes change
            singleProductBottomCategorySortable.addEventListener('change', function(e) {
                if (e.target.type === 'checkbox') {
                    updateSingleProductBottomCategoryOrder();
                }
            });
        }

        // Products by category sortable
        const productsByCategorySortable = document.getElementById('products-by-category-sortable');
        if (productsByCategorySortable) {
            new Sortable(productsByCategorySortable, {
                handle: '.handle',
                animation: 150,
                onSort: function() {
                    updateProductsByCategoryOrder();
                }
            });

            // Update order when checkboxes change
            productsByCategorySortable.addEventListener('change', function(e) {
                if (e.target.classList.contains('products-by-category-checkbox')) {
                    updateProductsByCategoryOrder();
                }
            });
        }

        // Products by category v2 location 1 sortable
        const productsByCategoryV2Location1Sortable = document.getElementById('homepage-category-v2-location1-sortable');
        if (productsByCategoryV2Location1Sortable) {
            new Sortable(productsByCategoryV2Location1Sortable, {
                handle: '.handle',
                animation: 150,
                onSort: function() {
                    updateProductsByCategoryV2Location1Order();
                }
            });

            // Update order when checkboxes change
            productsByCategoryV2Location1Sortable.addEventListener('change', function(e) {
                if (e.target.classList.contains('location1-category-checkbox')) {
                    updateProductsByCategoryV2Location1Order();
                }
            });
        }

        // Products by category v2 location 2 sortable
        const productsByCategoryV2Location2Sortable = document.getElementById('homepage-category-v2-location2-sortable');
        if (productsByCategoryV2Location2Sortable) {
            new Sortable(productsByCategoryV2Location2Sortable, {
                handle: '.handle',
                animation: 150,
                onSort: function() {
                    updateProductsByCategoryV2Location2Order();
                }
            });

            // Update order when checkboxes change
            productsByCategoryV2Location2Sortable.addEventListener('change', function(e) {
                if (e.target.classList.contains('location2-category-checkbox')) {
                    updateProductsByCategoryV2Location2Order();
                }
            });
        }

        // Products by category v2 location 3 sortable
        const productsByCategoryV2Location3Sortable = document.getElementById('homepage-category-v2-location3-sortable');
        if (productsByCategoryV2Location3Sortable) {
            new Sortable(productsByCategoryV2Location3Sortable, {
                handle: '.handle',
                animation: 150,
                onSort: function() {
                    updateProductsByCategoryV2Location3Order();
                }
            });

            // Update order when checkboxes change
            productsByCategoryV2Location3Sortable.addEventListener('change', function(e) {
                if (e.target.classList.contains('location3-category-checkbox')) {
                    updateProductsByCategoryV2Location3Order();
                }
            });
        }

        // Best Selling, Editor's Pick, and Trending Products sortables
        const sections = ['best_selling', 'editors_pick', 'trending'];
        sections.forEach(function(section) {
            const sortableElement = document.getElementById(section + '-products-sortable');
            if (sortableElement) {
                new Sortable(sortableElement, {
                    handle: '.handle',
                    animation: 150,
                    onSort: function() {
                        updateProductsOrder(section);
                    }
                });

                // Update order when checkboxes change
                sortableElement.addEventListener('change', function(e) {
                    if (e.target.type === 'checkbox') {
                        updateProductsOrder(section);
                    }
                });
            }
        });
    }

    function updateHomepageFeaturedOrder() {
        const order = [];
        document.querySelectorAll('#homepage-featured-sortable li').forEach(function(el) {
            if (el.querySelector('.featured-checkbox').checked) {
                order.push(el.getAttribute('data-id'));
            }
        });
        document.getElementById('homepage-featured-order').value = JSON.stringify(order);
    }

    function updateSingleProductBottomCategoryOrder() {
        const order = [];
        document.querySelectorAll('#single-product-bottom-category-sortable li').forEach(function(el) {
            if (el.querySelector('input[type="checkbox"]').checked) {
                order.push(el.getAttribute('data-id'));
            }
        });
        document.getElementById('single-product-bottom-category-order').value = JSON.stringify(order);
    }

    function updateProductsByCategoryOrder() {
        const order = [];
        document.querySelectorAll('#products-by-category-sortable li').forEach(function(el) {
            if (el.querySelector('.products-by-category-checkbox').checked) {
                order.push(el.getAttribute('data-id'));
            }
        });
        document.getElementById('products-by-category-order').value = JSON.stringify(order);
    }

    function updateProductsByCategoryV2Location1Order() {
        const order = [];
        document.querySelectorAll('#homepage-category-v2-location1-sortable li').forEach(function(el) {
            if (el.querySelector('.location1-category-checkbox').checked) {
                order.push(el.getAttribute('data-id'));
            }
        });
        document.getElementById('homepage-category-v2-location1-order').value = JSON.stringify(order);
    }

    function updateProductsByCategoryV2Location2Order() {
        const order = [];
        document.querySelectorAll('#homepage-category-v2-location2-sortable li').forEach(function(el) {
            if (el.querySelector('.location2-category-checkbox').checked) {
                order.push(el.getAttribute('data-id'));
            }
        });
        document.getElementById('homepage-category-v2-location2-order').value = JSON.stringify(order);
    }

    function updateProductsByCategoryV2Location3Order() {
        const order = [];
        document.querySelectorAll('#homepage-category-v2-location3-sortable li').forEach(function(el) {
            if (el.querySelector('.location3-category-checkbox').checked) {
                order.push(el.getAttribute('data-id'));
            }
        });
        document.getElementById('homepage-category-v2-location3-order').value = JSON.stringify(order);
    }

    // Update products order for Best Selling, Editor's Pick, and Trending sections
    function updateProductsOrder(section) {
        const order = [];
        const sortableElement = document.getElementById(section + '-products-sortable');
        if (sortableElement) {
            sortableElement.querySelectorAll('li').forEach(function(el) {
                const checkbox = el.querySelector('input[type="checkbox"]');
                if (checkbox && checkbox.checked) {
                    order.push(el.getAttribute('data-id'));
                }
            });
            const orderInput = document.getElementById(section + '-products-order');
            if (orderInput) {
                orderInput.value = JSON.stringify(order);
            }
        }
    }

    // Color Picker Sync Functionality
    function initializeColorPickerSync() {
        // View Product Button Background Color
        const viewProductBgColor = document.getElementById('viewProductBgColor');
        const viewProductBgColorText = document.getElementById('viewProductBgColorText');

        if (viewProductBgColor && viewProductBgColorText) {
            viewProductBgColor.addEventListener('input', function() {
                viewProductBgColorText.value = this.value;
            });

            viewProductBgColorText.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                    viewProductBgColor.value = this.value;
                }
            });
        }

        // View Product Button Text Color
        const viewProductTextColor = document.getElementById('viewProductTextColor');
        const viewProductTextColorText = document.getElementById('viewProductTextColorText');

        if (viewProductTextColor && viewProductTextColorText) {
            viewProductTextColor.addEventListener('input', function() {
                viewProductTextColorText.value = this.value;
            });

            viewProductTextColorText.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                    viewProductTextColor.value = this.value;
                }
            });
        }

        // Buy Now Button Background Color
        const buyNowBgColor = document.getElementById('buyNowBgColor');
        const buyNowBgColorText = document.getElementById('buyNowBgColorText');

        if (buyNowBgColor && buyNowBgColorText) {
            buyNowBgColor.addEventListener('input', function() {
                buyNowBgColorText.value = this.value;
            });

            buyNowBgColorText.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                    buyNowBgColor.value = this.value;
                }
            });
        }

        // Buy Now Button Text Color
        const buyNowTextColor = document.getElementById('buyNowTextColor');
        const buyNowTextColorText = document.getElementById('buyNowTextColorText');

        if (buyNowTextColor && buyNowTextColorText) {
            buyNowTextColor.addEventListener('input', function() {
                buyNowTextColorText.value = this.value;
            });

            buyNowTextColorText.addEventListener('input', function() {
                if (this.value.match(/^#[0-9A-F]{6}$/i)) {
                    buyNowTextColor.value = this.value;
                }
            });
        }
    }

    // Top Header Version Switching Functionality
    function initializeTopHeaderVersionSwitching() {
        const versionSelect = document.querySelector('select[name="settings[top_header_bar_version]"]');
        const v1Options = document.getElementById('top-header-v1-options');
        const v2Options = document.getElementById('top-header-v2-options');
        const v2RightOptions = document.getElementById('top-header-v2-right-options');
        const v3Options = document.getElementById('top-header-v3-options');
        const v3Phone = document.getElementById('top-header-v3-phone');
        const v3Speed = document.getElementById('top-header-v3-speed');
        const v1PhoneInput = document.getElementById('top-header-v1-phone');
        const v3PhoneInput = v3Phone ? v3Phone.querySelector('input[name="settings[top_header_bar_phone]"]') : null;

        if (versionSelect && v1Options && v2Options && v2RightOptions) {
            // Function to toggle options visibility based on version
            function toggleVersionOptions() {
                if (versionSelect.value === 'v2') {
                    // Show v2 options, hide v1 options
                    v1Options.style.display = 'none';
                    v2Options.style.display = 'block';
                    v2RightOptions.style.display = 'block';
                    if (v3Options) v3Options.style.display = 'none';
                    if (v3Phone) v3Phone.style.display = 'none';
                    if (v3Speed) v3Speed.style.display = 'none';
                    if (v1PhoneInput) v1PhoneInput.disabled = false;
                    if (v3PhoneInput) v3PhoneInput.disabled = true;
                } else if (versionSelect.value === 'v3') {
                    // Show only v3 options, hide v1 and v2 options
                    v1Options.style.display = 'none';
                    v2Options.style.display = 'none';
                    v2RightOptions.style.display = 'none';
                    if (v3Options) v3Options.style.display = 'block';
                    if (v3Phone) v3Phone.style.display = 'block';
                    if (v3Speed) v3Speed.style.display = 'block';
                    if (v1PhoneInput) v1PhoneInput.disabled = true;
                    if (v3PhoneInput) v3PhoneInput.disabled = false;
                } else {
                    // Show v1 options, hide v2 options
                    v1Options.style.display = 'block';
                    v2Options.style.display = 'none';
                    v2RightOptions.style.display = 'none';
                    if (v3Options) v3Options.style.display = 'none';
                    if (v3Phone) v3Phone.style.display = 'none';
                    if (v3Speed) v3Speed.style.display = 'none';
                    if (v1PhoneInput) v1PhoneInput.disabled = false;
                    if (v3PhoneInput) v3PhoneInput.disabled = true;
                }
            }

            // Initial state
            toggleVersionOptions();

            // Listen for changes
            versionSelect.addEventListener('change', toggleVersionOptions);
        }
    }

    // Ace Editor Initialization
    function initializeCodeEditors() {
        // Initialize header code editor
        const headerEditor = ace.edit("header-code-editor");
        headerEditor.setTheme("ace/theme/monokai");
        headerEditor.session.setMode("ace/mode/html");
        headerEditor.setOptions({
            fontSize: "14px",
            showLineNumbers: true,
            showGutter: true,
            highlightActiveLine: true,
            enableBasicAutocompletion: true,
            enableLiveAutocompletion: true,
            wrap: true,
            tabSize: 2,
            useSoftTabs: true
        });

        // Get initial value from textarea
        const headerTextarea = document.querySelector('textarea[name="settings[custom_header_code]"]');
        headerEditor.setValue(headerTextarea.value || '');

        // Update textarea when editor content changes
        headerEditor.session.on('change', function() {
            headerTextarea.value = headerEditor.getValue();
        });

        // Initialize footer code editor
        const footerEditor = ace.edit("footer-code-editor");
        footerEditor.setTheme("ace/theme/monokai");
        footerEditor.session.setMode("ace/mode/html");
        footerEditor.setOptions({
            fontSize: "14px",
            showLineNumbers: true,
            showGutter: true,
            highlightActiveLine: true,
            enableBasicAutocompletion: true,
            enableLiveAutocompletion: true,
            wrap: true,
            tabSize: 2,
            useSoftTabs: true
        });

        // Get initial value from textarea
        const footerTextarea = document.querySelector('textarea[name="settings[custom_footer_code]"]');
        footerEditor.setValue(footerTextarea.value || '');

        // Update textarea when editor content changes
        footerEditor.session.on('change', function() {
            footerTextarea.value = footerEditor.getValue();
        });
    }

    // Sitemap Generation Script
    document.addEventListener('DOMContentLoaded', function() {
        const generateSitemapBtn = document.getElementById('generateSitemapBtn');
        const sitemapProgress = document.getElementById('sitemapProgress');
        const sitemapStatus = document.getElementById('sitemapStatus');
        const progressBar = sitemapProgress ? sitemapProgress.querySelector('.progress-bar') : null;

        if (generateSitemapBtn && sitemapProgress && sitemapStatus && progressBar) {
            generateSitemapBtn.addEventListener('click', function() {
                // Disable button and show progress
                generateSitemapBtn.disabled = true;
                generateSitemapBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
                sitemapProgress.style.display = 'block';
                sitemapStatus.style.display = 'none';
                progressBar.style.width = '0%';

                // Simulate progress
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = progress + '%';
                }, 200);

                // Make AJAX request to generate sitemap
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                    'content') || '{{ csrf_token() }}';

                    fetch('{{ route('admin.sitemap.generate') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                            }
                        })
                    .then(response => response.json())
                    .then(data => {
                        clearInterval(progressInterval);
                        progressBar.style.width = '100%';

                        setTimeout(() => {
                            sitemapProgress.style.display = 'none';
                            sitemapStatus.style.display = 'block';

                            if (data.success) {
                                sitemapStatus.className = 'alert alert-success';
                                sitemapStatus.innerHTML = `
                                    <strong>Success!</strong> ${data.message}<br>
                                    <strong>URLs Added:</strong> ${data.urls_count}<br>
                                    <strong>File Size:</strong> ${data.file_size} KB<br>
                                    <strong>Generated At:</strong> ${data.generated_at}
                                `;

                                // Update button text
                                generateSitemapBtn.innerHTML =
                                    '<i class="fas fa-sync-alt"></i> Regenerate Sitemap';

                                // Reload page after 3 seconds to update status
                                setTimeout(() => {
                                    location.reload();
                                }, 3000);
                            } else {
                                sitemapStatus.className = 'alert alert-danger';
                                sitemapStatus.innerHTML =
                                    `<strong>Error!</strong> ${data.message}`;
                            }

                            generateSitemapBtn.disabled = false;
                        }, 500);
                    })
                    .catch(error => {
                        clearInterval(progressInterval);
                        sitemapProgress.style.display = 'none';
                        sitemapStatus.style.display = 'block';
                        sitemapStatus.className = 'alert alert-danger';
                        generateSitemapBtn.innerHTML =
                            '<i class="fas fa-sync-alt"></i> Regenerate Sitemap';
                    });
            });
        }

        // Template Card Click Selection
        document.querySelectorAll('.template-card').forEach(function(card) {
            card.addEventListener('click', function(e) {
                if (e.target.closest('a') || e.target.closest('button')) return; // ignore links and buttons
                const templateId = this.getAttribute('data-template');
                const templateInput = document.getElementById('selected_homepage_template');
                if (templateInput) {
                    templateInput.value = templateId;
                }

                // Update UI states
                document.querySelectorAll('.template-card').forEach(function(c) {
                    c.classList.remove('active-template-card');
                    c.style.borderColor = '#e2e8f0';
                    c.style.boxShadow = '0 2px 8px rgba(0,0,0,0.04)';
                    const badge = c.querySelector('.template-status-badge');
                    if (badge) {
                        badge.className = 'badge badge-light text-secondary border px-3 py-1 template-status-badge';
                        badge.textContent = 'Select Template';
                    }
                });

                this.classList.add('active-template-card');
                this.style.borderColor = '#2563eb';
                this.style.boxShadow = '0 12px 32px rgba(37, 99, 235, 0.18)';
                const activeBadge = this.querySelector('.template-status-badge');
                if (activeBadge) {
                    activeBadge.className = 'badge badge-success px-3 py-1 template-status-badge';
                    activeBadge.textContent = '✓ Currently Active';
                }

                // Show subtle toast or notification
                if (typeof toastr !== 'undefined') {
                    toastr.info('Template ' + templateId + ' selected. Remember to click Save Changes to apply on frontend.');
                }
            });
        });
    });

    // Global Modal Helper for Template Details
    window.showTemplateModal = function(id, name) {
        const details = {
            1: {
                title: 'Template 1: Classic Marketplace (Daraz Style)',
                features: [
                    'Multi-vendor category drawer pinned to slider hero',
                    'High density product display sections with customizable columns',
                    'Deal of the day ticker & flash deal cards',
                    'Categorized tab sliders and brand carousels',
                    'Optimized for large inventories & multi-category stores'
                ],
                bestFor: 'General e-commerce, supermarkets, multi-category retailers, and multi-vendor platforms.'
            },
            2: {
                title: 'Template 2: Modern Minimal / Boutique Showcase',
                features: [
                    'Sleek full-width modern hero slider with elevated glass typography',
                    'Clean grid layout with generous white space and subtle shadows',
                    'Floating category pills and minimalist action buttons',
                    'High focus on single high-margin items & trending collections',
                    'Ultra-fast responsive layout with smooth micro-animations'
                ],
                bestFor: 'Fashion boutiques, lifestyle brands, cosmetics, and premium lifestyle shops.'
            },
            3: {
                title: 'Template 3: Electronic & Tech Hub',
                features: [
                    'Tech-oriented dark accent contrast containers',
                    'Side-by-side featured tech banners with specification ribbons',
                    'Multi-tab electronics filter and gadget highlights',
                    'Compact product tiles with discount percentages',
                    'Built-in quick buy buttons and variant indicators'
                ],
                bestFor: 'Gadget stores, computer shops, home electronics, and mobile accessory retailers.'
            },
            4: {
                title: 'Template 4: High-Conversion Flash Sale',
                features: [
                    'Urgency-driven red/amber vibrant conversion accents',
                    'Prominent real-time sale countdown timer header banner',
                    'Stock scarcity progress bars & fire badges',
                    'Instant 1-click checkout buttons directly from product cards',
                    'Sticky mobile bottom buy triggers for maximum conversion'
                ],
                bestFor: 'Single-product campaigns, seasonal clearance, holiday mega sales, and flash deal businesses.'
            },
            5: {
                title: 'Template 5: Grocery & Fresh Express',
                features: [
                    'Eco-friendly organic fresh green accents',
                    'Quick category chip carousel for 1-tap filtering',
                    'Instant quantity selector (+ / -) directly on product cards',
                    'Fast delivery notification badges and express shipping tags',
                    'Compact multi-item cart drawer integration'
                ],
                bestFor: 'Grocery stores, organic food suppliers, fresh markets, and instant delivery shops.'
            }
        };

        const t = details[id] || details[1];
        let featuresHtml = t.features.map(f => `<li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> ${f}</li>`).join('');

        const modalHtml = `
            <div class="modal fade" id="templateOverviewModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                        <div class="modal-header bg-primary text-white py-3">
                            <h5 class="modal-title font-weight-bold" style="font-size: 16px;">
                                <i class="fas fa-palette mr-2"></i> ${t.title}
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <h6 class="font-weight-bold text-dark mb-2">Key Layout Features:</h6>
                            <ul class="list-unstyled pl-0 mb-3" style="font-size: 13.5px; color: #475569;">
                                ${featuresHtml}
                            </ul>
                            <div class="p-3 bg-light rounded" style="border-left: 4px solid #3b82f6;">
                                <small class="d-block font-weight-bold text-dark">Best Suited For:</small>
                                <small class="text-muted">${t.bestFor}</small>
                            </div>
                        </div>
                        <div class="modal-footer bg-light py-2 px-4 justify-content-between">
                            <button type="button" class="btn btn-secondary btn-sm px-3" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary btn-sm px-3 font-weight-bold" onclick="selectAndCloseTemplate(${id})">Apply Template ${id}</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        const existing = document.getElementById('templateOverviewModal');
        if (existing) existing.remove();

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        $('#templateOverviewModal').modal('show');
    };

    window.selectAndCloseTemplate = function(id) {
        const card = document.querySelector(`.template-card[data-template="${id}"]`);
        if (card) {
            card.click();
        }
        $('#templateOverviewModal').modal('hide');
    };
</script>
@endsection
