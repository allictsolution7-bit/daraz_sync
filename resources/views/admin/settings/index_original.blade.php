@extends('layouts.master')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Website Settings</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- General Settings -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">General Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Website Name</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[site_name]" class="form-control mb-2"
                                        value="{{ setting('general', 'site_name', 'My Website') }}">
                                    <small class="form-text text-muted">The name of your website that appears in the browser
                                        title</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Website Logo</label>
                                <div class="col-md-9">
                                    <div class="custom-file mb-2">
                                        <input type="file" name="site_logo" class="custom-file-input" id="site_logo">
                                        <label class="custom-file-label" for="site_logo">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Recommended size: 200x100px. WebP, PNG or SVG
                                        format.</small> <br>
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
                                        <small class="form-text text-muted">Enter the desired width for your logo in pixels
                                            (50-500px)</small>
                                    </div>
                                    <div class="mt-2">
                                        <label class="form-label">Mobile VersionLogo Width (px)</label>
                                        <input type="number" name="settings[mobile_logo_width]" class="form-control"
                                            value="{{ setting('general', 'mobile_logo_width', '150') }}"
                                            min="50" max="500" step="1">
                                        <small class="form-text text-muted">Enter the desired width for your logo in pixels
                                            (50-500px)</small>
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
                                    <small class="form-text text-muted">Recommended size: 32x32px. ICO, WebP, PNG or SVG
                                        format.</small> <br>
                                    @if (setting('general', 'favicon'))
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
                            <div class="card mb-2 mt-2">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Preloader Settings</h5>
                                </div>
                                <div class="card-body">
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
                                            <small class="form-text text-muted">Text shown below the preloader
                                                animation.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">SEO Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Default Meta Title</label>
                                <div class="col-md-9">
                                    <input type="text" name="seo[default_meta_title]" class="form-control mb-2"
                                        value="{{ setting('seo', 'default_meta_title', 'Thikana Shop - Your Ultimate Fashion Destination') }}"
                                        maxlength="60">
                                    <small class="form-text text-muted">Default page title (max 60 characters). Used when
                                        no specific title is set.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Default Meta Description</label>
                                <div class="col-md-9">
                                    <textarea name="seo[default_meta_description]" class="form-control mb-2" rows="3" maxlength="260">{{ $seo['default_meta_description'] ?? 'Discover the latest fashion trends at Thikana Shop. Shop premium quality clothing, accessories, and more at competitive prices. Fast shipping, secure payments, and excellent customer service.' }}</textarea>
                                    <small class="form-text text-muted">Default page description (max 260 characters). Used
                                        when no specific description is set.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Default Meta Keywords</label>
                                <div class="col-md-9">
                                    <input type="text" name="seo[default_meta_keywords]" class="form-control mb-2"
                                        value="{{ $seo['default_meta_keywords'] ?? 'fashion, clothing, accessories, online shopping, thikana shop' }}">
                                    <small class="form-text text-muted">Comma-separated keywords for search
                                        engines.</small>
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
                                    <small class="form-text text-muted">Recommended size: 1200x630px. Used for social media
                                        sharing when no specific image is set.</small>
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
                                    <small class="form-text text-muted">Your Twitter username for social media
                                        cards.</small>
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Google Search Console</label>
                                <div class="col-md-9">
                                    <input type="text" name="seo[google_search_console]" class="form-control mb-2"
                                        value="{{ setting('seo', 'google_search_console', '') }}"
                                        placeholder="meta tag content">
                                    <small class="form-text text-muted">Google Search Console verification meta tag
                                        content.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Bing Webmaster Tools</label>
                                <div class="col-md-9">
                                    <input type="text" name="seo[bing_webmaster]" class="form-control mb-2"
                                        value="{{ setting('seo', 'bing_webmaster', '') }}"
                                        placeholder="meta tag content">
                                    <small class="form-text text-muted">Bing Webmaster Tools verification meta tag
                                        content.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Yandex Webmaster</label>
                                <div class="col-md-9">
                                    <input type="text" name="seo[yandex_webmaster]" class="form-control mb-2"
                                        value="{{ setting('seo', 'yandex_webmaster', '') }}"
                                        placeholder="meta tag content">
                                    <small class="form-text text-muted">Yandex Webmaster verification meta tag
                                        content.</small>
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
                                    <small class="form-text text-muted">Base URL for canonical links (usually your
                                        domain).</small>
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
                                    <small class="form-text text-muted">Enable structured data markup for better search
                                        engine understanding.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Organization Schema</label>
                                <div class="col-md-9">
                                    <textarea name="seo[organization_schema]" class="form-control mb-2" rows="4">{{ setting('seo', 'organization_schema', '') }}</textarea>
                                    <small class="form-text text-muted">JSON-LD organization schema markup
                                        (optional).</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sitemap Settings -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Sitemap Management</h5>
                        </div>
                        <div class="card-body">
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
                    </div>

                    <!-- Product Item Design -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Product Item Design</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Product Card Border Radius</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[product_card_border_radius]"
                                        class="form-control"
                                        value="{{ setting('general', 'product_card_border_radius', '10px') }}"
                                        placeholder="10px">
                                    <small class="form-text text-muted">Border radius for product cards (e.g., 10px, 5px,
                                        0px).</small>
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
                                    <small class="form-text text-muted">Height for product images on desktop (e.g., 240px,
                                        200px).</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Product Image Height (Tablet)</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[product_image_height_tablet]"
                                        class="form-control"
                                        value="{{ setting('general', 'product_image_height_tablet', '200px') }}"
                                        placeholder="200px">
                                    <small class="form-text text-muted">Height for product images on tablets (e.g., 200px,
                                        180px).</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Product Image Height (Mobile)</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[product_image_height_mobile]"
                                        class="form-control"
                                        value="{{ setting('general', 'product_image_height_mobile', 'auto') }}"
                                        placeholder="auto">
                                    <small class="form-text text-muted">Height for product images on mobile (e.g., auto,
                                        150px, 120px).</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Product Image Padding</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[product_image_padding]" class="form-control"
                                        value="{{ setting('general', 'product_image_padding', '5px') }}"
                                        placeholder="5px">
                                    <small class="form-text text-muted">Padding for product images (e.g., 5px,
                                        10px).</small>
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
                                    <small class="form-text text-muted">Show or hide product ratings on product
                                        cards.</small>
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
                                    <small class="form-text text-muted">Show or hide product writer information on product
                                        cards.</small>
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
                                    <small class="form-text text-muted">Show or hide product titles on product
                                        cards.</small>
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
                                    <small class="form-text text-muted">Show or hide product prices on product
                                        cards.</small>
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
                                    <small class="form-text text-muted">Show or hide product action buttons on product
                                        cards.</small>
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
                                    <small class="form-text text-muted">Choose the type of badge to display on product
                                        cards.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Default Badge Text</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[default_badge_text]" class="form-control"
                                        value="{{ setting('general', 'default_badge_text', '১০% ছাড়') }}"
                                        placeholder="১০% ছাড়">
                                    <small class="form-text text-muted">Default text to show on badges when no discount is
                                        available.</small>
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

                        </div>
                    </div>

                    <!-- Section Heading Styles -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Section Heading Styles</h5>
                        </div>
                        <div class="card-body">


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
                                    <small class="form-text text-muted">Padding for section headers (e.g., 2px 5px, 10px
                                        15px).</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Section Header Border Radius</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[section_header_border_radius]"
                                        class="form-control"
                                        value="{{ setting('general', 'section_header_border_radius', '8px') }}"
                                        placeholder="8px">
                                    <small class="form-text text-muted">Border radius for section headers (e.g., 8px, 4px,
                                        0px).</small>
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
                                    <small class="form-text text-muted">Show or hide the colored left bar on section
                                        headers.</small>
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
                    </div>

                    <!-- Website Typography Settings -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Website Typography Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row align-items-center">
                                <label class="col-md-3 col-form-label">Website Font</label>
                                <div class="col-md-6">
                                    <select name="settings[website_font]" class="form-control" id="website_font_select">
                                        <option value="hind-siliguri" {{ setting('general', 'website_font', 'hind-siliguri') == 'hind-siliguri' ? 'selected' : '' }}>Hind Siliguri (Bengali)</option>
                                        <option value="inter" {{ setting('general', 'website_font', 'hind-siliguri') == 'inter' ? 'selected' : '' }}>Inter (English)</option>
                                    </select>
                                    <small class="form-text text-muted">Choose the main font for your website</small>
                                </div>
                                <div class="col-md-3">
                                    <div id="font_preview" class="p-2 border rounded" style="font-family: {{ setting('general', 'website_font', 'hind-siliguri') == 'hind-siliguri' ? 'Hind Siliguri' : 'Inter' }}, sans-serif;">
                                        AaBbCc ১২৩৪৫৬
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Website Colors Settings -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Website Colors Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row align-items-center">
                                <label class="col-md-3 col-form-label">Primary Color</label>
                                <div class="col-md-6">
                                    <input type="text" name="settings[primary_color]"
                                        class="form-control mb-2 color-input" id="primary_color_input"
                                        value="{{ setting('general', 'primary_color', '#F02627') }}"
                                        placeholder="#F02627 or rgb(241,134,47)">
                                    <small class="form-text text-muted">Main brand color (used for buttons, highlights,
                                        etc.)</small>
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
                                    <small class="form-text text-muted">Secondary brand color (used for backgrounds,
                                        etc.)</small>
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
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
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
                                        }
                                    });
                                }
                            }

                            updatePreview('primary_color_input', 'primary_color_preview');
                            updatePreview('secondary_color_input', 'secondary_color_preview');
                            updatePreview('accent_color_input', 'accent_color_preview');
                            updatePreview('section_header_left_bar_color_input', 'section_header_left_bar_color_preview');
                            
                            // Main Navigation color previews
                            updatePreview('main_nav_background_color_input', 'main_nav_background_color_preview');
                            updatePreview('main_nav_text_color_input', 'main_nav_text_color_preview');
                            updatePreview('main_nav_hover_bg_color_input', 'main_nav_hover_bg_color_preview');
                            updatePreview('main_nav_hover_text_color_input', 'main_nav_hover_text_color_preview');
                            updatePreview('main_nav_border_right_color_input', 'main_nav_border_right_color_preview');
                            
                            updateFontPreview();

                            // Box shadow preset functionality
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
                        });
                    </script>

                    {{-- Header --}}

                    <!-- Contact Information -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Contact Information</h5>
                        </div>
                        <div class="card-body">
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

                    <!-- Social Media Links -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Social Media Links</h5>
                        </div>
                        <div class="card-body">
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
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="global_video_url">Global Product Video URL (YouTube)</label>
                        <input type="text" name="settings[global_video_url]" id="global_video_url"
                            class="form-control"
                            value="{{ setting('general', 'global_video_url') }}"
                            placeholder="https://www.youtube.com/watch?v=xxxxxx">
                        <small class="form-text text-muted">This will be used if a product does not have its own video
                            URL.</small>
                    </div>

                    @php
                        $videoUrl = setting('general', 'global_video_url');

                        $embedUrl = \App\Services\SettingsService::getYoutubeEmbedUrl($videoUrl);
                    @endphp

                    @if ($embedUrl)
                        <div class="mt-3">
                            <label><strong>Preview:</strong></label>
                            <div style="width:300px;">
                                <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen
                                    style="width:100%; height:auto; aspect-ratio:16/9;">
                                </iframe>
                            </div>
                        </div>
                    @endif

                    {{-- Global category or subcategory banner image --}}
                    <div class="mb-3">
                        <label for="global_category_bg" class="form-label">Global Category/Subcategory Banner
                            Image</label>
                        <input type="file" class="form-control" id="global_category_bg" name="global_category_bg">
                        @php
                            $globalBg = setting('homepage', 'global_category_bg');
                        @endphp
                        @if ($globalBg)
                            <div class="p-2">
                                <img src="{{ asset($globalBg) }}" style="width:auto;height:200px !Important;"
                                    alt="Current Global Banner">
                            </div>
                        @endif

                        @error('global_category_bg')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Header Customization --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Header Customization</h5>
                        </div>
                        <div class="card-body">
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
                                <label class="col-md-3 col-form-label">Left Content</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[top_header_bar_left]" class="form-control"
                                        value="{{ setting('general', 'top_header_bar_left', 'অনলাইন বই দোকানে আপনাকে স্বাগতম!') }}">
                                    <small class="form-text text-muted">Text shown on the left side of the top header
                                        bar.</small>
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
                                    <input type="text" name="settings[top_header_bar_phone]" class="form-control"
                                        value="{{ setting('general', 'top_header_bar_phone', '+8801723-000000') }}">
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
                                    </select>
                                    <small class="form-text text-muted">Choose which header layout to display on the
                                        frontend.</small>
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
                                    <small class="form-text text-muted">Show or hide the main navigation menu on the
                                        frontend.</small>
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
                        </div>
                    </div>

                    <!-- Footer Customize -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Footer Content</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>About Website (Footer Text)</label>
                                <textarea name="footer[about_website]" class="form-control" rows="4">{{ setting('footer', 'about_website', '') }}</textarea>
                                <small class="form-text text-muted">This text will appear in the footer section of your
                                    website</small>
                            </div>
                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Footer Copyright Text</label>
                                <div class="col-md-9">
                                    <input type="text" name="settings[footer_copyright]" class="form-control"
                                        value="{{ setting('general', 'footer_copyright', '© 2025 Thikana . All Rights Reserved. Developed By SOFTEB.COM') }}">
                                    <small class="form-text text-muted">This text will appear in the footer of your
                                        website.</small>
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
                                    <small class="form-text text-muted">Show or hide the newsletter subscription section in
                                        the footer.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Ecommerce Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Enable Cash on Delivery</label>
                                <div class="col-md-9">
                                    <select name="ecommerce[cod]" class="form-control">
                                        <option value="1"
                                            {{ setting('ecommerce', 'cod', '1') == '1' ? 'selected' : '' }}>
                                            Show</option>
                                        <option value="0"
                                            {{ setting('ecommerce', 'cod', '1') == '0' ? 'selected' : '' }}>
                                            Hide</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Enable Bkash</label>
                                <div class="col-md-9">
                                    <select name="ecommerce[bkash]" class="form-control">
                                        <option value="1"
                                            {{ setting('ecommerce', 'bkash', '1') == '1' ? 'selected' : '' }}>
                                            Show</option>
                                        <option value="0"
                                            {{ setting('ecommerce', 'bkash', '1') == '0' ? 'selected' : '' }}>
                                            Hide</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Enable Nagad</label>
                                <div class="col-md-9">
                                    <select name="ecommerce[nagad]" class="form-control">
                                        <option value="1"
                                            {{ setting('ecommerce', 'nagad', '1') == '1' ? 'selected' : '' }}>
                                            Show</option>
                                        <option value="0"
                                            {{ setting('ecommerce', 'nagad', '1') == '0' ? 'selected' : '' }}>
                                            Hide</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label class="col-md-3 col-form-label">Enable Rocket</label>
                                <div class="col-md-9">
                                    <select name="ecommerce[rocket]" class="form-control">
                                        <option value="1"
                                            {{ setting('ecommerce', 'rocket', '1') == '1' ? 'selected' : '' }}>
                                            Show</option>
                                        <option value="0"
                                            {{ setting('ecommerce', 'rocket', '1') == '0' ? 'selected' : '' }}>
                                            Hide</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-header bg-light">
                            <h6 class="mb-0">Single Product Page</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Show Delivery Info Section?</label>
                                <select name="settings[show_delivery_info]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_delivery_info', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_delivery_info', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                            </div>
                            <div class="form-group mt-2">
                                <label>Delivery Info Content</label>
                                <textarea name="settings[delivery_info]" class="form-control rich-text" rows="8">{{ setting('general', 'delivery_info', '') }}</textarea>
                                <small class="form-text text-muted">You can use lists, bold, links, etc.</small>
                            </div>

                            <div class="form-group">
                                <label>Show Order Timeline?</label>
                                <select name="settings[show_order_timeline]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_order_timeline', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_order_timeline', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                            </div>

                            <hr>
                            <h6 class="text-primary">Bottom Action Buttons</h6>
                            <div class="form-group">
                                <label>Show Bottom Action Buttons Section?</label>
                                <select name="settings[show_bottom_action_buttons]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_bottom_action_buttons', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_bottom_action_buttons', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the WhatsApp and Phone call
                                    buttons at the bottom of product pages.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Show WhatsApp Button?</label>
                                <select name="settings[show_whatsapp_button]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_whatsapp_button', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_whatsapp_button', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the WhatsApp contact button.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Show Phone Call Button?</label>
                                <select name="settings[show_phone_button]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_phone_button', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_phone_button', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the phone call button.</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Product Details Section</h6>
                            <div class="form-group">
                                <label>Show Product Details Section?</label>
                                <select name="settings[show_product_details_section]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_product_details_section', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_product_details_section', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the product details section
                                    (Category, Writer, Publisher, etc.).</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Category Label Text</label>
                                <input type="text" name="settings[category_label_text]" class="form-control"
                                    value="{{ setting('general', 'category_label_text', 'বিষয়') }}"
                                    placeholder="বিষয়">
                                <small class="form-text text-muted">Text label for the category field (e.g., "বিষয়",
                                    "Category", "ক্যাটাগরি").</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Writer Label Text</label>
                                <input type="text" name="settings[writer_label_text]" class="form-control"
                                    value="{{ setting('general', 'writer_label_text', 'লেখক') }}"
                                    placeholder="লেখক">
                                <small class="form-text text-muted">Text label for the writer field (e.g., "লেখক",
                                    "Author", "রাইটার").</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Publisher Label Text</label>
                                <input type="text" name="settings[publisher_label_text]" class="form-control"
                                    value="{{ setting('general', 'publisher_label_text', 'প্রকাশক') }}"
                                    placeholder="প্রকাশক">
                                <small class="form-text text-muted">Text label for the publisher field (e.g., "প্রকাশক",
                                    "Publisher", "পাবলিশার").</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>ISBN Label Text</label>
                                <input type="text" name="settings[isbn_label_text]" class="form-control"
                                    value="{{ setting('general', 'isbn_label_text', 'আইএসবিএন') }}"
                                    placeholder="আইএসবিএন">
                                <small class="form-text text-muted">Text label for the ISBN field (e.g., "আইএসবিএন",
                                    "ISBN").</small>
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
                                    value="{{ setting('general', 'language_label_text', 'ভাষা') }}"
                                    placeholder="ভাষা">
                                <small class="form-text text-muted">Text label for the language field (e.g., "ভাষা",
                                    "Language", "ল্যাঙ্গুয়েজ").</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Short Description Section</h6>
                            <div class="form-group">
                                <label>Show Short Description Section?</label>
                                <select name="settings[show_short_description_section]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_short_description_section', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_short_description_section', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the short description section
                                    with expand/collapse functionality.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>"Read More" Button Text</label>
                                <input type="text" name="settings[read_more_button_text]" class="form-control"
                                    value="{{ setting('general', 'read_more_button_text', 'বিস্তারিত') }}"
                                    placeholder="বিস্তারিত">
                                <small class="form-text text-muted">Text for the "Read More" button (e.g., "বিস্তারিত",
                                    "Read More", "আরও দেখুন").</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Product Meta Information</h6>
                            <div class="form-group">
                                <label>Show Product Meta Section?</label>
                                <select name="settings[show_product_meta_section]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_product_meta_section', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_product_meta_section', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the product meta information
                                    section (SKU, Availability).</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Show SKU Field?</label>
                                <select name="settings[show_sku_field]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_sku_field', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_sku_field', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the SKU field in the product meta
                                    section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Show Availability Field?</label>
                                <select name="settings[show_availability_field]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_availability_field', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_availability_field', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Show or hide the availability field in the product meta
                                    section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Availability Label Text</label>
                                <input type="text" name="settings[availability_label_text]" class="form-control"
                                    value="{{ setting('general', 'availability_label_text', 'Availability') }}"
                                    placeholder="Availability">
                                <small class="form-text text-muted">Text label for the availability field (e.g.,
                                    "Availability", "স্টক", "উপলব্ধতা").</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Product Sections</h6>
                            <div class="form-group">
                                <label>Show Product Description Section?</label>
                                <select name="settings[show_product_description_section]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_product_description_section', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_product_description_section', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the product description
                                    section.</small>
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
                                    value="{{ setting('general', 'description_section_title', 'Product Description') }}"
                                    placeholder="Product Description">
                                <small class="form-text text-muted">Title for the product description section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Ratings Section Title</label>
                                <input type="text" name="settings[ratings_section_title]" class="form-control"
                                    value="{{ setting('general', 'ratings_section_title', 'Customer Ratings & Reviews') }}"
                                    placeholder="Customer Ratings & Reviews">
                                <small class="form-text text-muted">Title for the ratings and reviews section.</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Review Form Section</h6>
                            <div class="form-group">
                                <label>Show Review Form Section?</label>
                                <select name="settings[show_review_form_section]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_review_form_section', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_review_form_section', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the review form section where
                                    customers can submit reviews.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Review Form Header Title</label>
                                <input type="text" name="settings[review_form_header_title]" class="form-control"
                                    value="{{ setting('general', 'review_form_header_title', 'এই পণ্য সম্পর্কে আপনার মূল্যবান মতামত লিখুন') }}"
                                    placeholder="এই পণ্য সম্পর্কে আপনার মূল্যবান মতামত লিখুন">
                                <small class="form-text text-muted">Header title for the review form section.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Review Form Submit Button Text</label>
                                <input type="text" name="settings[review_form_submit_button_text]"
                                    class="form-control"
                                    value="{{ setting('general', 'review_form_submit_button_text', 'আপনার মতামত সাবমিট করুন') }}"
                                    placeholder="আপনার মতামত সাবমিট করুন">
                                <small class="form-text text-muted">Text for the review form submit button.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Review Form Comment Placeholder</label>
                                <input type="text" name="settings[review_form_comment_placeholder]"
                                    class="form-control"
                                    value="{{ setting('general', 'review_form_comment_placeholder', 'Write your comment...') }}"
                                    placeholder="Write your comment...">
                                <small class="form-text text-muted">Placeholder text for the review comment
                                    textarea.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Guest Name Label</label>
                                <input type="text" name="settings[guest_name_label]" class="form-control"
                                    value="{{ setting('general', 'guest_name_label', 'আপনার নাম') }}"
                                    placeholder="আপনার নাম">
                                <small class="form-text text-muted">Label for the guest name field in the review
                                    form.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Guest Email Label</label>
                                <input type="text" name="settings[guest_email_label]" class="form-control"
                                    value="{{ setting('general', 'guest_email_label', 'আপনার ইমেইল') }}"
                                    placeholder="আপনার ইমেইল">
                                <small class="form-text text-muted">Label for the guest email field in the review
                                    form.</small>
                            </div>

                            <hr>
                            <h6 class="text-primary">Related Products Section</h6>
                            <div class="form-group">
                                <label>Show Related Products Section?</label>
                                <select name="settings[show_related_products_section]" class="form-control">
                                    <option value="1"
                                        {{ setting('general', 'show_related_products_section', '1') == '1' ? 'selected' : '' }}>
                                        Show</option>
                                    <option value="0"
                                        {{ setting('general', 'show_related_products_section', '1') == '0' ? 'selected' : '' }}>
                                        Hide</option>
                                </select>
                                <small class="form-text text-muted">Control the visibility of the related products section
                                    in the sidebar.</small>
                            </div>

                            <div class="form-group mt-3">
                                <label>Related Products Section Title</label>
                                <input type="text" name="settings[related_products_section_title]"
                                    class="form-control"
                                    value="{{ setting('general', 'related_products_section_title', 'আরো দেখুন') }}"
                                    placeholder="আরো দেখুন">
                                <small class="form-text text-muted">Title for the related products section (e.g., "আরো
                                    দেখুন", "See More", "Related Products").</small>
                            </div>

                        </div>

                    </div>

                    <!-- Homepage Customization Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Homepage Customization</h5>
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
                                                <input type="hidden"
                                                    name="homepage[enable_products_by_category_section]" value="0">
                                                <input type="checkbox"
                                                    name="homepage[enable_products_by_category_section]" value="1"
                                                    {{ !empty($homepage['enable_products_by_category_section']) && $homepage['enable_products_by_category_section'] ? 'checked' : '' }}>
                                                Enable Products By Category Section

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
                                                            <li class="list-group-item"
                                                                data-id="category-{{ $cat->id }}">
                                                                <div class="d-flex align-items-center">
                                                                    <input type="checkbox"
                                                                        class="mr-2 products-by-category-checkbox"
                                                                        name="homepage[products_by_category_selected][{{ $cat->id }}]"
                                                                        value="1" checked>
                                                                    <span>{{ $cat->name }}</span>
                                                                </div>
                                                                <span class="handle" style="cursor:move;">&#9776;</span>
                                                            </li>
                                                        @endif
                                                    @endforeach

                                                    {{-- Then, show unselected categories (not in order) --}}
                                                    @foreach ($categories as $cat)
                                                        @php $catKey = 'category-' . $cat->id; @endphp
                                                        @if (!isset($selectedMap[$catKey]))
                                                            <li class="list-group-item not-draggable"
                                                                data-id="category-{{ $cat->id }}">
                                                                <div class="d-flex align-items-center">
                                                                    <input type="checkbox"
                                                                        class="mr-2 products-by-category-checkbox"
                                                                        name="homepage[products_by_category_selected][{{ $cat->id }}]"
                                                                        value="1">
                                                                    <span>{{ $cat->name }}</span>
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
                                        <div class="col-md-6">
                                            <hr>
                                            <div class="form-group">
                                                <input type="hidden"
                                                    name="homepage[enable_products_by_category_v2_location1]"
                                                    value="0">
                                                <label>
                                                    <input type="checkbox"
                                                        name="homepage[enable_products_by_category_v2_location1]"
                                                        value="1"
                                                        {{ !empty($homepage['enable_products_by_category_v2_location1']) && $homepage['enable_products_by_category_v2_location1'] ? 'checked' : '' }}>
                                                    Enable Products By Category v2 - Location 1
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Location 1 Categories (Select and drag to reorder)</label>
                                                <ul id="homepage-category-v2-location1-sortable" class="list-group">
                                                    @php
                                                        $selected = isset(
                                                            $homepage['products_by_category_v2_location1_order'],
                                                        )
                                                            ? json_decode(
                                                                $homepage['products_by_category_v2_location1_order'],
                                                                true,
                                                            )
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
                                                            <input type="checkbox"
                                                                class="mr-2 location1-category-checkbox"
                                                                {{ in_array($itemKey, $selected) ? 'checked' : '' }}>
                                                            <span class="flex-grow-1">{{ ucfirst($item['type']) }}:
                                                                {{ $item['name'] }}</span>
                                                            <span class="handle" style="cursor:move;">&#9776;</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <input type="hidden"
                                                    name="homepage[products_by_category_v2_location1_order]"
                                                    id="homepage-category-v2-location1-order"
                                                    value='{{ $homepage['products_by_category_v2_location1_order'] ?? '[]' }}'>
                                                <small class="form-text text-muted">Check to show, drag checked items
                                                    to
                                                    set order.</small>
                                            </div>
                                            <div class="form-group">
                                                <label>Products per Category (Location 1)</label>
                                                <input type="number"
                                                    name="homepage[products_by_category_v2_location1_products_per_category]"
                                                    class="form-control"
                                                    value="{{ $homepage['products_by_category_v2_location1_products_per_category'] ?? 12 }}"
                                                    min="1" max="50" step="1">
                                                <small class="form-text text-muted">Number of products to show per
                                                    category (1-50).</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <hr>
                                            <div class="form-group">
                                                <input type="hidden"
                                                    name="homepage[enable_products_by_category_v2_location2]"
                                                    value="0">
                                                <label>
                                                    <input type="checkbox"
                                                        name="homepage[enable_products_by_category_v2_location2]"
                                                        value="1"
                                                        {{ !empty($homepage['enable_products_by_category_v2_location2']) && $homepage['enable_products_by_category_v2_location2'] ? 'checked' : '' }}>
                                                    Enable Products By Category v2 - Location 2
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Location 2 Categories (Select and drag to reorder)</label>
                                                <ul id="homepage-category-v2-location2-sortable" class="list-group">
                                                    @php
                                                        $selected = isset(
                                                            $homepage['products_by_category_v2_location2_order'],
                                                        )
                                                            ? json_decode(
                                                                $homepage['products_by_category_v2_location2_order'],
                                                                true,
                                                            )
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
                                                            <input type="checkbox"
                                                                class="mr-2 location2-category-checkbox"
                                                                {{ in_array($itemKey, $selected) ? 'checked' : '' }}>
                                                            <span class="flex-grow-1">{{ ucfirst($item['type']) }}:
                                                                {{ $item['name'] }}</span>
                                                            <span class="handle" style="cursor:move;">&#9776;</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <input type="hidden"
                                                    name="homepage[products_by_category_v2_location2_order]"
                                                    id="homepage-category-v2-location2-order"
                                                    value='{{ $homepage['products_by_category_v2_location2_order'] ?? '[]' }}'>
                                                <small class="form-text text-muted">Check to show, drag checked items
                                                    to
                                                    set order.</small>
                                            </div>
                                            <div class="form-group">
                                                <label>Products per Category (Location 2)</label>
                                                <input type="number"
                                                    name="homepage[products_by_category_v2_location2_products_per_category]"
                                                    class="form-control"
                                                    value="{{ $homepage['products_by_category_v2_location2_products_per_category'] ?? 12 }}"
                                                    min="1" max="50" step="1">
                                                <small class="form-text text-muted">Number of products to show per
                                                    category (1-50).</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <hr>
                                            <div class="form-group">
                                                <input type="hidden"
                                                    name="homepage[enable_products_by_category_v2_location3]"
                                                    value="0">
                                                <label>
                                                    <input type="checkbox"
                                                        name="homepage[enable_products_by_category_v2_location3]"
                                                        value="1"
                                                        {{ !empty($homepage['enable_products_by_category_v2_location3']) && $homepage['enable_products_by_category_v2_location3'] ? 'checked' : '' }}>
                                                    Enable Products By Category v2 - Location 3
                                                </label>
                                            </div>
                                            <div class="form-group">
                                                <label>Location 3 Categories (Select and drag to reorder)</label>
                                                <ul id="homepage-category-v2-location3-sortable" class="list-group">
                                                    @php
                                                        $selected = isset(
                                                            $homepage['products_by_category_v2_location3_order'],
                                                        )
                                                            ? json_decode(
                                                                $homepage['products_by_category_v2_location3_order'],
                                                                true,
                                                            )
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
                                                            <input type="checkbox"
                                                                class="mr-2 location3-category-checkbox"
                                                                {{ in_array($itemKey, $selected) ? 'checked' : '' }}>
                                                            <span class="flex-grow-1">{{ ucfirst($item['type']) }}:
                                                                {{ $item['name'] }}</span>
                                                            <span class="handle" style="cursor:move;">&#9776;</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <input type="hidden"
                                                    name="homepage[products_by_category_v2_location3_order]"
                                                    id="homepage-category-v2-location3-order"
                                                    value='{{ $homepage['products_by_category_v2_location3_order'] ?? '[]' }}'>
                                                <small class="form-text text-muted">Check to show, drag checked items
                                                    to
                                                    set order.</small>
                                            </div>
                                            <div class="form-group">
                                                <label>Products per Category (Location 3)</label>
                                                <input type="number"
                                                    name="homepage[products_by_category_v2_location3_products_per_category]"
                                                    class="form-control"
                                                    value="{{ $homepage['products_by_category_v2_location3_products_per_category'] ?? 12 }}"
                                                    min="1" max="50" step="1">
                                                <small class="form-text text-muted">Number of products to show per
                                                    category (1-50).</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <hr>
                                            <div class="form-group">
                                                <label>"View All" Button Text</label>
                                                <input type="text" name="general[view_all_button_text]"
                                                    class="form-control"
                                                    value="{{ setting('general', 'view_all_button_text', 'আ') }}">
                                                <small class="form-text text-muted">Text to display on "View All"
                                                    buttons
                                                    throughout the site.</small>
                                            </div>

                                            <div class="col-md-6">
                                                <hr>
                                                <div class="form-group">
                                                    <input type="hidden"
                                                        name="homepage[enable_customer_reviews_section]" value="0">
                                                    <label>
                                                        <input type="checkbox"
                                                            name="homepage[enable_customer_reviews_section]"
                                                            value="1"
                                                            {{ !empty($homepage['enable_customer_reviews_section']) && $homepage['enable_customer_reviews_section'] ? 'checked' : '' }}>
                                                        Enable Customer Reviews Section In Home page
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <hr>
                                                <div class="form-group">
                                                    <input type="hidden" name="homepage[enable_shop_features_section]"
                                                        value="0">
                                                    <label>
                                                        <input type="checkbox"
                                                            name="homepage[enable_shop_features_section]" value="1"
                                                            {{ !empty($homepage['enable_shop_features_section']) && $homepage['enable_shop_features_section'] ? 'checked' : '' }}>
                                                        Enable Shop Features Section In Home page
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <hr>
                                                <div class="form-group">
                                                    <input type="hidden" name="homepage[enable_category_scrollbar]"
                                                        value="0">
                                                    <label>
                                                        <input type="checkbox"
                                                            name="homepage[enable_category_scrollbar]" value="1"
                                                            {{ !empty($homepage['enable_category_scrollbar']) && $homepage['enable_category_scrollbar'] ? 'checked' : '' }}>
                                                        Enable Category Scrollbar Section In Home page
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <hr>
                                                <div class="form-group">
                                                    <input type="hidden"
                                                        name="homepage[enable_products_by_category_v2]" value="0">
                                                    <label>
                                                        <input type="checkbox"
                                                            name="homepage[enable_products_by_category_v2]"
                                                            value="1"
                                                            {{ !empty($homepage['enable_products_by_category_v2']) && $homepage['enable_products_by_category_v2'] ? 'checked' : '' }}>
                                                        Enable Products By Category v2 Section In Home page
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <hr>
                                                <div class="form-group">
                                                    <input type="hidden" name="homepage[enable_best_author_section]"
                                                        value="0">
                                                    <label>
                                                        <input type="checkbox"
                                                            name="homepage[enable_best_author_section]" value="1"
                                                            {{ !empty($homepage['enable_best_author_section']) && $homepage['enable_best_author_section'] ? 'checked' : '' }}>
                                                        Enable Best Author Section In Home page
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <hr>
                                                <div class="form-group">
                                                    <input type="hidden" name="homepage[enable_best_publisher_section]"
                                                        value="0">
                                                    <label>
                                                        <input type="checkbox"
                                                            name="homepage[enable_best_publisher_section]"
                                                            value="1"
                                                            {{ !empty($homepage['enable_best_publisher_section']) && $homepage['enable_best_publisher_section'] ? 'checked' : '' }}>
                                                        Enable Best Publisher Section In Home page
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <hr>
                                                <div class="form-group">
                                                    <input type="hidden" name="homepage[enable_latest_products_section]"
                                                        value="0">
                                                    <label>
                                                        <input type="checkbox"
                                                            name="homepage[enable_latest_products_section]"
                                                            value="1"
                                                            {{ !empty($homepage['enable_latest_products_section']) && $homepage['enable_latest_products_section'] ? 'checked' : '' }}>
                                                        Enable Latest Products Section In Home page
                                                    </label>
                                                </div>
                                            </div>

                                            <hr>
                                            
                                            <!-- Latest Products Section Configuration -->
                                            <div class="card mt-3">
                                                <div class="card-header bg-light">
                                                    <h6 class="mb-0">Latest Products Section Configuration</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <label class="col-md-3 col-form-label">Section Heading</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="homepage[latest_products_section_heading]" class="form-control"
                                                                value="{{ $homepage['latest_products_section_heading'] ?? 'Latest Products' }}"
                                                                placeholder="Latest Products">
                                                            <small class="form-text text-muted">Title for the Latest Products section.</small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-2">
                                                        <label class="col-md-3 col-form-label">Initial Products Count</label>
                                                        <div class="col-md-9">
                                                            <input type="number" name="homepage[latest_products_initial_count]" class="form-control"
                                                                value="{{ $homepage['latest_products_initial_count'] ?? 12 }}"
                                                                min="1" max="50" step="1">
                                                            <small class="form-text text-muted">Number of products to show initially (1-50).</small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-2">
                                                        <label class="col-md-3 col-form-label">Products Per Page</label>
                                                        <div class="col-md-9">
                                                            <input type="number" name="homepage[latest_products_per_page]" class="form-control"
                                                                value="{{ $homepage['latest_products_per_page'] ?? 12 }}"
                                                                min="1" max="50" step="1">
                                                            <small class="form-text text-muted">Number of products to load per page when loading more (1-50).</small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-2">
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

                                                    <div class="form-group row mt-2">
                                                        <label class="col-md-3 col-form-label">Load More Button Text</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="homepage[latest_products_load_more_text]" class="form-control"
                                                                value="{{ $homepage['latest_products_load_more_text'] ?? 'Load More Products' }}"
                                                                placeholder="Load More Products">
                                                            <small class="form-text text-muted">Text to display on the load more button.</small>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row mt-2">
                                                        <label class="col-md-3 col-form-label">Loading Text</label>
                                                        <div class="col-md-9">
                                                            <input type="text" name="homepage[latest_products_loading_text]" class="form-control"
                                                                value="{{ $homepage['latest_products_loading_text'] ?? 'Loading...' }}"
                                                                placeholder="Loading...">
                                                            <small class="form-text text-muted">Text to show while loading more products.</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <input type="hidden" name="homepage[enable_main_slider_section]"
                                                    value="0">
                                                <label>
                                                    <input type="checkbox"
                                                        name="homepage[enable_main_slider_section]" value="1"
                                                        {{ !empty($homepage['enable_main_slider_section']) && $homepage['enable_main_slider_section'] ? 'checked' : '' }}>
                                                    Enable Main Slider Section In Home page
                                                </label>
                                            </div>
                                                <div class="form-group">
                                                    <label>Slider Height (Desktop, px)</label>
                                                    <input type="number" name="homepage[slider_height]"
                                                        class="form-control"
                                                        value="{{ $homepage['slider_height'] ?? 300 }}" min="100"
                                                        max="1000" step="1">
                                                    <small class="form-text text-muted">Default: 300px. Controls the
                                                        height of
                                                        the main slider on desktop (&gt;992px).</small>
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
                                            
                                            
                                                <div class="form-group">
                                                    <input type="hidden" name="homepage[enable_scroll_to_top]"
                                                        value="0">
                                                    <label>
                                                        <input type="checkbox" name="homepage[enable_scroll_to_top]"
                                                            value="1"
                                                            {{ !empty($homepage['enable_scroll_to_top']) && $homepage['enable_scroll_to_top'] ? 'checked' : '' }}>
                                                        Enable "Scroll to Top" Button
                                                    </label>
                                                </div>

                                                <!-- Scroll to Top Button Settings -->
                                                <div class="card mt-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Scroll to Top Button</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 col-form-label">Show on Desktop?</label>
                                                            <div class="col-md-9">
                                                                <select name="settings[show_scroll_to_top_desktop]" class="form-control">
                                                                    <option value="1"
                                                                        {{ setting('general', 'show_scroll_to_top_desktop', '1') == '1' ? 'selected' : '' }}>
                                                                        Show</option>
                                                                    <option value="0"
                                                                        {{ setting('general', 'show_scroll_to_top_desktop', '1') == '0' ? 'selected' : '' }}>
                                                                        Hide</option>
                                                                </select>
                                                                <small class="form-text text-muted">Show or hide the scroll to top button on desktop devices.</small>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Show on Mobile?</label>
                                                            <div class="col-md-9">
                                                                <select name="settings[show_scroll_to_top_mobile]" class="form-control">
                                                                    <option value="1"
                                                                        {{ setting('general', 'show_scroll_to_top_mobile', '1') == '1' ? 'selected' : '' }}>
                                                                        Show</option>
                                                                    <option value="0"
                                                                        {{ setting('general', 'show_scroll_to_top_mobile', '1') == '0' ? 'selected' : '' }}>
                                                                        Hide</option>
                                                                </select>
                                                                <small class="form-text text-muted">Show or hide the scroll to top button on mobile devices.</small>
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <h6 class="text-primary">General Page Positioning</h6>
                                                        
                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Desktop Position</label>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Bottom (px)</label>
                                                                        <input type="number" name="settings[scroll_to_top_bottom_desktop]" class="form-control"
                                                                            value="{{ setting('general', 'scroll_to_top_bottom_desktop', '20') }}"
                                                                            placeholder="20">
                                                                        <small class="form-text text-muted">Distance from bottom on desktop.</small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Right (px)</label>
                                                                        <input type="number" name="settings[scroll_to_top_right_desktop]" class="form-control"
                                                                            value="{{ setting('general', 'scroll_to_top_right_desktop', '20') }}"
                                                                            placeholder="20">
                                                                        <small class="form-text text-muted">Distance from right on desktop.</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Mobile Position</label>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Bottom (px)</label>
                                                                        <input type="number" name="settings[scroll_to_top_bottom_mobile]" class="form-control"
                                                                            value="{{ setting('general', 'scroll_to_top_bottom_mobile', '20') }}"
                                                                            placeholder="20">
                                                                        <small class="form-text text-muted">Distance from bottom on mobile.</small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Right (px)</label>
                                                                        <input type="number" name="settings[scroll_to_top_right_mobile]" class="form-control"
                                                                            value="{{ setting('general', 'scroll_to_top_right_mobile', '20') }}"
                                                                            placeholder="20">
                                                                        <small class="form-text text-muted">Distance from right on mobile.</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <h6 class="text-primary">Shop Page Specific Positioning</h6>
                                                        
                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Shop Page Desktop Position</label>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Bottom (px)</label>
                                                                        <input type="number" name="settings[scroll_to_top_shop_bottom_desktop]" class="form-control"
                                                                            value="{{ setting('general', 'scroll_to_top_shop_bottom_desktop', '20') }}"
                                                                            placeholder="20">
                                                                        <small class="form-text text-muted">Distance from bottom on shop page desktop.</small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Right (px)</label>
                                                                        <input type="number" name="settings[scroll_to_top_shop_right_desktop]" class="form-control"
                                                                            value="{{ setting('general', 'scroll_to_top_shop_right_desktop', '20') }}"
                                                                            placeholder="20">
                                                                        <small class="form-text text-muted">Distance from right on shop page desktop.</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Shop Page Mobile Position</label>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Bottom (px)</label>
                                                                        <input type="number" name="settings[scroll_to_top_shop_bottom_mobile]" class="form-control"
                                                                            value="{{ setting('general', 'scroll_to_top_shop_bottom_mobile', '20') }}"
                                                                            placeholder="20">
                                                                        <small class="form-text text-muted">Distance from bottom on shop page mobile.</small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Right (px)</label>
                                                                        <input type="number" name="settings[scroll_to_top_shop_right_mobile]" class="form-control"
                                                                            value="{{ setting('general', 'scroll_to_top_shop_right_mobile', '20') }}"
                                                                            placeholder="20">
                                                                        <small class="form-text text-muted">Distance from right on shop page mobile.</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row mt-2">
                                                    <label class="col-md-3 col-form-label">Free Shipping Minimum
                                                        Amount</label>
                                                    <div class="col-md-9">
                                                        <input type="number" name="settings[free_shipping_amount]"
                                                            class="form-control"
                                                            value="{{ setting('general', 'free_shipping_amount', 0) }}"
                                                            min="0" step="1">
                                                        <small class="form-text text-muted">Set the minimum order
                                                            amount
                                                            (in
                                                            your currency) for free shipping. Set to 0 to disable free
                                                            shipping.</small>
                                                    </div>
                                                </div>

                                                <!-- Free Shipping Progress Bar Settings -->
                                                <div class="card mt-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Free Shipping Progress Bar</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 col-form-label">Show Progress Bar?</label>
                                                            <div class="col-md-9">
                                                                <select name="settings[show_free_shipping_progress]" class="form-control">
                                                                    <option value="1"
                                                                        {{ setting('general', 'show_free_shipping_progress', '1') == '1' ? 'selected' : '' }}>
                                                                        Show</option>
                                                                    <option value="0"
                                                                        {{ setting('general', 'show_free_shipping_progress', '1') == '0' ? 'selected' : '' }}>
                                                                        Hide</option>
                                                                </select>
                                                                <small class="form-text text-muted">Show or hide the free shipping progress bar on the frontend.</small>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Show on Desktop?</label>
                                                            <div class="col-md-9">
                                                                <select name="settings[show_free_shipping_progress_desktop]" class="form-control">
                                                                    <option value="1"
                                                                        {{ setting('general', 'show_free_shipping_progress_desktop', '1') == '1' ? 'selected' : '' }}>
                                                                        Show</option>
                                                                    <option value="0"
                                                                        {{ setting('general', 'show_free_shipping_progress_desktop', '1') == '0' ? 'selected' : '' }}>
                                                                        Hide</option>
                                                                </select>
                                                                <small class="form-text text-muted">Show or hide the free shipping progress bar on desktop devices.</small>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Show on Mobile?</label>
                                                            <div class="col-md-9">
                                                                <select name="settings[show_free_shipping_progress_mobile]" class="form-control">
                                                                    <option value="1"
                                                                        {{ setting('general', 'show_free_shipping_progress_mobile', '1') == '1' ? 'selected' : '' }}>
                                                                        Show</option>
                                                                    <option value="0"
                                                                        {{ setting('general', 'show_free_shipping_progress_mobile', '1') == '0' ? 'selected' : '' }}>
                                                                        Hide</option>
                                                                </select>
                                                                <small class="form-text text-muted">Show or hide the free shipping progress bar on mobile devices.</small>
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <h6 class="text-primary">Positioning Settings</h6>
                                                        
                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Desktop Position</label>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Bottom (px)</label>
                                                                        <input type="number" name="settings[free_shipping_progress_bottom_desktop]" class="form-control"
                                                                            value="{{ setting('general', 'free_shipping_progress_bottom_desktop', '0') }}"
                                                                            placeholder="0">
                                                                        <small class="form-text text-muted">Distance from bottom on desktop.</small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Right (px)</label>
                                                                        <input type="number" name="settings[free_shipping_progress_right_desktop]" class="form-control"
                                                                            value="{{ setting('general', 'free_shipping_progress_right_desktop', '69') }}"
                                                                            placeholder="69">
                                                                        <small class="form-text text-muted">Distance from right on desktop.</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Mobile Position</label>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Bottom (px)</label>
                                                                        <input type="number" name="settings[free_shipping_progress_bottom_mobile]" class="form-control"
                                                                            value="{{ setting('general', 'free_shipping_progress_bottom_mobile', '37') }}"
                                                                            placeholder="37">
                                                                        <small class="form-text text-muted">Distance from bottom on mobile.</small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Right (px)</label>
                                                                        <input type="number" name="settings[free_shipping_progress_right_mobile]" class="form-control"
                                                                            value="{{ setting('general', 'free_shipping_progress_right_mobile', '59') }}"
                                                                            placeholder="59">
                                                                        <small class="form-text text-muted">Distance from right on mobile.</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Floating Cart Settings -->
                                                <div class="card mt-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Floating Cart</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="form-group row">
                                                            <label class="col-md-3 col-form-label">Show Floating Cart?</label>
                                                            <div class="col-md-9">
                                                                <select name="settings[show_floating_cart]" class="form-control">
                                                                    <option value="1"
                                                                        {{ setting('general', 'show_floating_cart', '1') == '1' ? 'selected' : '' }}>
                                                                        Show</option>
                                                                    <option value="0"
                                                                        {{ setting('general', 'show_floating_cart', '1') == '0' ? 'selected' : '' }}>
                                                                        Hide</option>
                                                                </select>
                                                                <small class="form-text text-muted">Show or hide the floating cart button on the frontend.</small>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Show on Desktop?</label>
                                                            <div class="col-md-9">
                                                                <select name="settings[show_floating_cart_desktop]" class="form-control">
                                                                    <option value="1"
                                                                        {{ setting('general', 'show_floating_cart_desktop', '1') == '1' ? 'selected' : '' }}>
                                                                        Show</option>
                                                                    <option value="0"
                                                                        {{ setting('general', 'show_floating_cart_desktop', '1') == '0' ? 'selected' : '' }}>
                                                                        Hide</option>
                                                                </select>
                                                                <small class="form-text text-muted">Show or hide the floating cart button on desktop devices.</small>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Show on Mobile?</label>
                                                            <div class="col-md-9">
                                                                <select name="settings[show_floating_cart_mobile]" class="form-control">
                                                                    <option value="1"
                                                                        {{ setting('general', 'show_floating_cart_mobile', '1') == '1' ? 'selected' : '' }}>
                                                                        Show</option>
                                                                    <option value="0"
                                                                        {{ setting('general', 'show_floating_cart_mobile', '1') == '0' ? 'selected' : '' }}>
                                                                        Hide</option>
                                                                </select>
                                                                <small class="form-text text-muted">Show or hide the floating cart button on mobile devices.</small>
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <h6 class="text-primary">Floating Cart Positioning</h6>
                                                        
                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Desktop Position</label>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Bottom (px)</label>
                                                                        <input type="number" name="settings[floating_cart_bottom_desktop]" class="form-control"
                                                                            value="{{ setting('general', 'floating_cart_bottom_desktop', '21') }}"
                                                                            placeholder="21">
                                                                        <small class="form-text text-muted">Distance from bottom on desktop.</small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Right (px)</label>
                                                                        <input type="number" name="settings[floating_cart_right_desktop]" class="form-control"
                                                                            value="{{ setting('general', 'floating_cart_right_desktop', '30') }}"
                                                                            placeholder="30">
                                                                        <small class="form-text text-muted">Distance from right on desktop.</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group row mt-2">
                                                            <label class="col-md-3 col-form-label">Mobile Position</label>
                                                            <div class="col-md-9">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Bottom (px)</label>
                                                                        <input type="number" name="settings[floating_cart_bottom_mobile]" class="form-control"
                                                                            value="{{ setting('general', 'floating_cart_bottom_mobile', '55') }}"
                                                                            placeholder="55">
                                                                        <small class="form-text text-muted">Distance from bottom on mobile.</small>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="form-label">Right (px)</label>
                                                                        <input type="number" name="settings[floating_cart_right_mobile]" class="form-control"
                                                                            value="{{ setting('general', 'floating_cart_right_mobile', '20') }}"
                                                                            placeholder="20">
                                                                        <small class="form-text text-muted">Distance from right on mobile.</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Featured Images Section -->
                                                <div class="card mt-3">
                                                    <div class="card-header bg-light">
                                                        <h6 class="mb-0">Featured Images Section</h6>
                                                    </div>
                                                    <div class="card-body">
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
                                                    <div class="col-md-6">
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
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                    {{-- Single Product Page Settings --}}
                    <div class="row">
                        <h5 class="text-primary pb-2">Single Product Page Settings</h5>
                        <hr>
                        <div class="col-md-6">
                            
                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_bottom_category_slider]"
                                    value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_bottom_category_slider]"
                                        value="1"
                                        {{ !empty($single_product['enable_bottom_category_slider']) && $single_product['enable_bottom_category_slider'] ? 'checked' : '' }}>
                                    Enable Single Product Bottom Category Slider Section
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Section Title</label>
                                <input type="text" name="single_product[bottom_category_slider_title]"
                                    class="form-control"
                                    value="{{ $single_product['bottom_category_slider_title'] ?? 'Related Categories' }}">
                                <small class="form-text text-muted">Title for the bottom category
                                    slider section.</small>
                            </div>
                            <div class="form-group">
                                <label>Bottom Category Slider Categories (Select and drag to
                                    reorder)</label>
                                <ul id="single-product-bottom-category-sortable" class="list-group">
                                    @php
                                        $selected = isset($single_product['bottom_category_slider_categories_order'])
                                            ? json_decode(
                                                $single_product['bottom_category_slider_categories_order'],
                                                true,
                                            )
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
                                        <li class="list-group-item d-flex align-items-center"
                                            data-id="{{ $itemKey }}">
                                            <input type="checkbox" class="mr-2 bottom-category-checkbox"
                                                {{ in_array($itemKey, $selected) ? 'checked' : '' }}>
                                            <span class="flex-grow-1">{{ ucfirst($item['type']) }}:
                                                {{ $item['name'] }}</span>
                                            <span class="handle" style="cursor:move;">&#9776;</span>
                                        </li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="single_product[bottom_category_slider_categories_order]"
                                    id="single-product-bottom-category-order"
                                    value='{{ $single_product['bottom_category_slider_categories_order'] ?? '[]' }}'>
                                <small class="form-text text-muted">Check to show, drag checked
                                    items
                                    to set order.</small>
                            </div>
                            <div class="form-group">
                                <label>Products per Category</label>
                                <input type="number"
                                    name="single_product[bottom_category_slider_products_per_category]"
                                    class="form-control"
                                    value="{{ $single_product['bottom_category_slider_products_per_category'] ?? 12 }}"
                                    min="1" max="50" step="1">
                                <small class="form-text text-muted">Number of products to show per
                                    category (1-50).</small>
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
                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_short_info]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_short_info]" value="1"
                                        {{ !empty($single_product['enable_short_info']) && $single_product['enable_short_info'] ? 'checked' : '' }}>
                                    Enable Short Info Section In Single Product Page
                                </label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_social_share]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_social_share]" value="1"
                                        {{ !empty($single_product['enable_social_share']) && $single_product['enable_social_share'] ? 'checked' : '' }}>
                                    Enable Social Share Section In Single Product Page
                                </label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_related_products]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_related_products]"
                                        value="1"
                                        {{ !empty($single_product['enable_related_products']) && $single_product['enable_related_products'] ? 'checked' : '' }}>
                                    Enable Related Products Section In Single Product Page
                                </label>
                            </div>
                            <hr>
                            <div class="form-group">
                                <input type="hidden" name="single_product[enable_book_sample]" value="0">
                                <label>
                                    <input type="checkbox" name="single_product[enable_book_sample]" value="1"
                                        {{ !empty($single_product['enable_book_sample']) && $single_product['enable_book_sample'] ? 'checked' : '' }}>
                                    Enable Book Sample Section In Single Product Page
                                </label>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Show Product Brand on Single Page</label>
                                <div class="col-md-9">
                                    <select name="settings[show_product_brand_single]" class="form-control">
                                        <option value="1"
                                            {{ setting('general', 'show_product_brand_single', '1') == '1' ? 'selected' : '' }}>
                                            Show</option>
                                        <option value="0"
                                            {{ setting('general', 'show_product_brand_single', '1') == '0' ? 'selected' : '' }}>
                                            Hide</option>
                                    </select>
                                    <small class="form-text text-muted">Show or hide product brand information on product single pages.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <style>
                                input.mr-2.featured-checkbox {
                                    margin-right: 5px;
                                }

                                .list-group-item {
                                    display: flex;
                                    align-items: center;
                                    justify-content: space-between;
                                }

                                input.mr-2 {
                                    margin-right: 5px;
                                }
                            </style>

                            <div>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save mr-2"></i> Save Settings
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <style>
        .not-draggable {
            opacity: 0.6;
            cursor: not-allowed !important;
            background: #f8f9fa;
        }

        .handle {
            cursor: move;
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Show filename in custom file input
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function updateFeaturedOrder() {
            let order = [];
            document.querySelectorAll('#homepage-featured-sortable li').forEach(function(el) {
                if (el.querySelector('.featured-checkbox').checked) {
                    order.push(el.getAttribute('data-id'));
                }
            });
            document.getElementById('homepage-featured-order').value = JSON.stringify(order);
        }

        function moveCheckedToTop() {
            let ul = document.getElementById('homepage-featured-sortable');
            let items = Array.from(ul.children);
            let checked = items.filter(item => item.querySelector('.featured-checkbox').checked);
            let unchecked = items.filter(item => !item.querySelector('.featured-checkbox').checked);
            // Only checked items are draggable
            checked.forEach(item => {
                item.classList.remove('not-draggable');
            });
            unchecked.forEach(item => {
                item.classList.add('not-draggable');
            });
            // Re-append in order: checked first, then unchecked
            [...checked, ...unchecked].forEach(item => ul.appendChild(item));
        }

        // Initialize SortableJS for only checked items
        var homepageFeaturedSortable = new Sortable(document.getElementById('homepage-featured-sortable'), {
            handle: '.handle',
            draggable: 'li:not(.not-draggable)',
            onEnd: function(evt) {
                updateFeaturedOrder();
            }
        });

        // Update order and draggable state when checkboxes are changed
        document.querySelectorAll('.featured-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                moveCheckedToTop();
                updateFeaturedOrder();
            });
        });

        // Initial setup
        moveCheckedToTop();
        updateFeaturedOrder();

        // Bottom Category Slider functionality
        function updateBottomCategoryOrder() {
            let order = [];
            document.querySelectorAll('#single-product-bottom-category-sortable li').forEach(function(el) {
                if (el.querySelector('.bottom-category-checkbox').checked) {
                    order.push(el.getAttribute('data-id'));
                }
            });
            document.getElementById('single-product-bottom-category-order').value = JSON.stringify(order);
        }

        function moveCheckedBottomCategoryToTop() {
            let ul = document.getElementById('single-product-bottom-category-sortable');
            let items = Array.from(ul.children);
            let checked = items.filter(item => item.querySelector('.bottom-category-checkbox').checked);
            let unchecked = items.filter(item => !item.querySelector('.bottom-category-checkbox').checked);
            // Only checked items are draggable
            checked.forEach(item => {
                item.classList.remove('not-draggable');
            });
            unchecked.forEach(item => {
                item.classList.add('not-draggable');
            });
            // Re-append in order: checked first, then unchecked
            [...checked, ...unchecked].forEach(item => ul.appendChild(item));
        }

        // Initialize SortableJS for bottom category slider
        var bottomCategorySortable = new Sortable(document.getElementById('single-product-bottom-category-sortable'), {
            handle: '.handle',
            draggable: 'li:not(.not-draggable)',
            onEnd: function(evt) {
                updateBottomCategoryOrder();
            }
        });

        // Update order and draggable state when checkboxes are changed
        document.querySelectorAll('.bottom-category-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                moveCheckedBottomCategoryToTop();
                updateBottomCategoryOrder();
            });
        });

        // Initial setup for bottom category slider
        moveCheckedBottomCategoryToTop();
        updateBottomCategoryOrder();

        // Homepage Category v2 Location 1 functionality
        function updateLocation1Order() {
            let order = [];
            document.querySelectorAll('#homepage-category-v2-location1-sortable li').forEach(function(el) {
                if (el.querySelector('.location1-category-checkbox').checked) {
                    order.push(el.getAttribute('data-id'));
                }
            });
            document.getElementById('homepage-category-v2-location1-order').value = JSON.stringify(order);
        }

        function moveCheckedLocation1ToTop() {
            let ul = document.getElementById('homepage-category-v2-location1-sortable');
            let items = Array.from(ul.children);
            let checked = items.filter(item => item.querySelector('.location1-category-checkbox').checked);
            let unchecked = items.filter(item => !item.querySelector('.location1-category-checkbox').checked);
            checked.forEach(item => item.classList.remove('not-draggable'));
            unchecked.forEach(item => item.classList.add('not-draggable'));
            [...checked, ...unchecked].forEach(item => ul.appendChild(item));
        }

        var location1Sortable = new Sortable(document.getElementById('homepage-category-v2-location1-sortable'), {
            handle: '.handle',
            draggable: 'li:not(.not-draggable)',
            onEnd: function(evt) {
                updateLocation1Order();
            }
        });

        document.querySelectorAll('.location1-category-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                moveCheckedLocation1ToTop();
                updateLocation1Order();
            });
        });

        // Homepage Category v2 Location 2 functionality
        function updateLocation2Order() {
            let order = [];
            document.querySelectorAll('#homepage-category-v2-location2-sortable li').forEach(function(el) {
                if (el.querySelector('.location2-category-checkbox').checked) {
                    order.push(el.getAttribute('data-id'));
                }
            });
            document.getElementById('homepage-category-v2-location2-order').value = JSON.stringify(order);
        }

        function moveCheckedLocation2ToTop() {
            let ul = document.getElementById('homepage-category-v2-location2-sortable');
            let items = Array.from(ul.children);
            let checked = items.filter(item => item.querySelector('.location2-category-checkbox').checked);
            let unchecked = items.filter(item => !item.querySelector('.location2-category-checkbox').checked);
            checked.forEach(item => item.classList.remove('not-draggable'));
            unchecked.forEach(item => item.classList.add('not-draggable'));
            [...checked, ...unchecked].forEach(item => ul.appendChild(item));
        }

        var location2Sortable = new Sortable(document.getElementById('homepage-category-v2-location2-sortable'), {
            handle: '.handle',
            draggable: 'li:not(.not-draggable)',
            onEnd: function(evt) {
                updateLocation2Order();
            }
        });

        document.querySelectorAll('.location2-category-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                moveCheckedLocation2ToTop();
                updateLocation2Order();
            });
        });

        // Homepage Category v2 Location 3 functionality
        function updateLocation3Order() {
            let order = [];
            document.querySelectorAll('#homepage-category-v2-location3-sortable li').forEach(function(el) {
                if (el.querySelector('.location3-category-checkbox').checked) {
                    order.push(el.getAttribute('data-id'));
                }
            });
            document.getElementById('homepage-category-v2-location3-order').value = JSON.stringify(order);
        }

        function moveCheckedLocation3ToTop() {
            let ul = document.getElementById('homepage-category-v2-location3-sortable');
            let items = Array.from(ul.children);
            let checked = items.filter(item => item.querySelector('.location3-category-checkbox').checked);
            let unchecked = items.filter(item => !item.querySelector('.location3-category-checkbox').checked);
            checked.forEach(item => item.classList.remove('not-draggable'));
            unchecked.forEach(item => item.classList.add('not-draggable'));
            [...checked, ...unchecked].forEach(item => ul.appendChild(item));
        }

        var location3Sortable = new Sortable(document.getElementById('homepage-category-v2-location3-sortable'), {
            handle: '.handle',
            draggable: 'li:not(.not-draggable)',
            onEnd: function(evt) {
                updateLocation3Order();
            }
        });

        document.querySelectorAll('.location3-category-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                moveCheckedLocation3ToTop();
                updateLocation3Order();
            });
        });

        // Initial setup for all location sections
        moveCheckedLocation1ToTop();
        updateLocation1Order();
        moveCheckedLocation2ToTop();
        updateLocation2Order();
        moveCheckedLocation3ToTop();
        updateLocation3Order();

        // Show filename in custom file input
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>
    <script>
        function updateProductsByCategoryOrder() {
            var order = [];
            $('#products-by-category-sortable li').each(function() {
                var $li = $(this);
                var $checkbox = $li.find('input[type=checkbox]');
                if ($checkbox.is(':checked')) {
                    order.push($li.data('id'));
                }
            });
            $('#products-by-category-order').val(JSON.stringify(order));
        }

        function moveCheckedProductsByCategoryToTop() {
            var $ul = $('#products-by-category-sortable');
            var $items = $ul.children('li');
            var $checked = $items.filter(function() {
                return $(this).find('input[type=checkbox]').is(':checked');
            });
            var $unchecked = $items.filter(function() {
                return !$(this).find('input[type=checkbox]').is(':checked');
            });

            // Only checked items are draggable
            $checked.removeClass('not-draggable');
            $unchecked.addClass('not-draggable');

            // Re-append in order: checked first, then unchecked
            $checked.appendTo($ul);
            $unchecked.appendTo($ul);
        }

        $(document).ready(function() {
            var sortable = new Sortable(document.getElementById('products-by-category-sortable'), {
                handle: '.handle',
                draggable: 'li:not(.not-draggable)',
                animation: 150,
                onSort: function() {
                    updateProductsByCategoryOrder();
                }
            });

            // Update order and draggable state when checkboxes are toggled
            $('#products-by-category-sortable').on('change', '.products-by-category-checkbox', function() {
                moveCheckedProductsByCategoryToTop();
                updateProductsByCategoryOrder();
            });

            // Initial setup
            moveCheckedProductsByCategoryToTop();
            updateProductsByCategoryOrder();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sortable = new Sortable(document.getElementById('best-selling-products-sortable'), {
                handle: '.handle',
                animation: 150,
                onSort: updateOrderInput
            });

            function updateOrderInput() {
                var order = [];
                $('#best-selling-products-sortable li').each(function() {
                    var $li = $(this);
                    var $checkbox = $li.find('input[type=checkbox]');
                    if ($checkbox.is(':checked')) {
                        order.push($li.data('id'));
                    }
                });
                $('#best-selling-products-order').val(JSON.stringify(order));
            }

            $('#best-selling-products-sortable').on('change', 'input[type=checkbox]', updateOrderInput);

            updateOrderInput();
        });
    </script>
    <script>
        @foreach ($sections as $key => $label)
            document.addEventListener('DOMContentLoaded', function() {
                var sortable = new Sortable(document.getElementById('{{ $key }}-products-sortable'), {
                    handle: '.handle',
                    animation: 150,
                    onSort: updateOrderInput_{{ $key }}
                });

                function updateOrderInput_{{ $key }}() {
                    var order = [];
                    $('#{{ $key }}-products-sortable li').each(function() {
                        var $li = $(this);
                        var $checkbox = $li.find('input[type=checkbox]');
                        if ($checkbox.is(':checked')) {
                            order.push($li.data('id'));
                        }
                    });
                    $('#{{ $key }}-products-order').val(JSON.stringify(order));
                }

                $('#{{ $key }}-products-sortable').on('change', 'input[type=checkbox]',
                    updateOrderInput_{{ $key }});

                updateOrderInput_{{ $key }}();
            });
        @endforeach
    </script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.rich-text').summernote({
                height: 200
            });
        });
    </script>

    <!-- Sitemap Generation Script -->
    <script>
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
                            sitemapStatus.innerHTML =
                                '<strong>Error!</strong> Failed to generate sitemap. Please try again.';
                            generateSitemapBtn.disabled = false;
                            generateSitemapBtn.innerHTML =
                                '<i class="fas fa-sync-alt"></i> Regenerate Sitemap';
                        });
                });
            }
        });
    </script>
@endsection
