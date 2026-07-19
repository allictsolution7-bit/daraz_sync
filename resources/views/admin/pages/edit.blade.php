@extends('layouts.master')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<style>
    #pages-edit {
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        color: #1e293b;
        background-color: #f8fafc;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 16px;
        padding: 32px;
        color: white;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15), 0 8px 10px -6px rgba(15, 23, 42, 0.15);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .breadcrumb-custom {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #94a3b8;
        margin-bottom: 12px;
        padding: 0;
        list-style: none;
    }

    .breadcrumb-custom a {
        color: #cbd5e1;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .breadcrumb-custom a:hover {
        color: #6366f1;
    }

    .breadcrumb-separator {
        color: #64748b;
    }

    .breadcrumb-active {
        color: #94a3b8;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 800;
        letter-spacing: -0.025em;
        margin: 0;
    }

    .form-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 28px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .form-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-card-title i {
        color: #4f46e5;
    }

    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.875rem;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 12px 16px;
        font-size: 0.95rem;
        color: #0f172a;
        transition: all 0.2s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    /* SEO Preview Mockup */
    .seo-preview-mockup {
        background: #ffffff;
        border: 1px solid #dadce0;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        max-width: 600px;
    }

    .google-domain {
        font-size: 0.85rem;
        color: #202124;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .google-domain-icon {
        width: 16px;
        height: 16px;
        background: #f1f3f4;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        color: #5f6368;
    }

    .google-title {
        color: #1a0dab;
        font-size: 1.25rem;
        line-height: 1.3;
        font-weight: 500;
        margin-bottom: 4px;
        text-decoration: none;
        word-break: break-all;
    }

    .google-title:hover {
        text-decoration: underline;
    }

    .google-snippet {
        color: #4d5156;
        font-size: 0.875rem;
        line-height: 1.58;
        word-break: break-all;
    }

    /* Back & Save actions */
    .btn-back {
        background: white;
        border: 1px solid #cbd5e1;
        color: #475569;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .btn-create {
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: white;
        border: none;
        font-weight: 600;
        padding: 10px 24px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
        transition: all 0.2s ease;
    }

    .btn-create:hover {
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.35);
        transform: translateY(-1px);
    }

    /* Summernote customization */
    .note-editor.note-frame {
        border: 1px solid #cbd5e1 !important;
        border-radius: 12px !important;
        overflow: hidden;
    }

    .note-toolbar {
        background: #f8fafc !important;
        border-bottom: 1px solid #cbd5e1 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-4" id="pages-edit">
    <!-- Breadcrumb & Header -->
    <div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title">Edit Custom Page</h1>
        </div>
        <ul class="breadcrumb-custom m-0">
            <li><a href="{{ route('admin') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li class="breadcrumb-separator"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i></li>
            <li><a href="{{ route('admin.pages.index') }}">Pages</a></li>
            <li class="breadcrumb-separator"><i class="fa-solid fa-chevron-right" style="font-size: 0.7rem;"></i></li>
            <li class="breadcrumb-active">Edit Page</li>
        </ul>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.pages.index') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Back to List
        </a>
    </div>

    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST" enctype="multipart/form-data" class="mb-5">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Main Content Area -->
            <div class="col-lg-8">
                <!-- Page Basics -->
                <div class="form-card">
                    <div class="form-card-title">
                        <i class="fa-regular fa-file-lines"></i> Page Essentials
                    </div>
                    
                    <div class="mb-4">
                        <label for="title" class="form-label">Page Title</label>
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror" value="{{ $page->title }}"
                            placeholder="e.g. Terms of Service" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="slug" class="form-label">Custom Slug (URL structure)</label>
                        <div class="input-group">
                            <input type="text" name="slug" id="slug"
                                class="form-control @error('slug') is-invalid @enderror"
                                value="{{ $page->slug }}" placeholder="auto-generated-from-title">
                            <button class="btn btn-outline-secondary px-3" type="button" id="generateSlug">
                                <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Generate
                            </button>
                        </div>
                        <div class="mt-2 text-muted" style="font-size: 0.8rem;">
                            Live URL Preview: <span id="slugPreview" style="color: #4f46e5; font-weight: 500;">{{ url('/pages/') }}/<span id="slugText">{{ $page->slug }}</span></span>
                        </div>
                        @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Page Rich Layout Body</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="12"
                            placeholder="Describe your page markup content here...">{{ $page->content }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="form-card">
                    <div class="form-card-title">
                        <i class="fa-solid fa-search"></i> SEO Optimization
                    </div>
                    <p class="text-muted small">Enhance search indexing performance by declaring search parameters below.</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="seoMetaTitle" class="form-label">Meta Title</label>
                            <input type="text" class="form-control @error('seo.meta_title') is-invalid @enderror" name="seo[meta_title]" id="seoMetaTitle"
                                placeholder="SEO Title tag" value="{{ $page->formatted_seo['meta_title'] ?? $page->meta_title ?? old('seo.meta_title') }}" maxlength="60">
                            <div class="form-text text-end" style="font-size: 0.75rem;">
                                <span id="metaTitleCount" class="font-weight-bold">{{ strlen($page->formatted_seo['meta_title'] ?? $page->meta_title ?? '') }}</span>/60 characters
                            </div>
                            @error('seo.meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="seoMetaKeywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control @error('seo.meta_keywords') is-invalid @enderror" name="seo[meta_keywords]"
                                id="seoMetaKeywords" placeholder="comma, separated, tags" value="{{ $page->formatted_seo['meta_keywords'] ?? $page->meta_keywords ?? old('seo.meta_keywords') }}">
                            @error('seo.meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-4">
                            <label for="seoMetaDescription" class="form-label">Meta Description</label>
                            <textarea class="form-control @error('seo.meta_description') is-invalid @enderror" name="seo[meta_description]" id="seoMetaDescription"
                                placeholder="Summary description displayed in search results..." style="height: 90px;" maxlength="160">{{ $page->formatted_seo['meta_description'] ?? $page->meta_description ?? old('seo.meta_description') }}</textarea>
                            <div class="form-text text-end" style="font-size: 0.75rem;">
                                <span id="metaDescriptionCount" class="font-weight-bold">{{ strlen($page->formatted_seo['meta_description'] ?? $page->meta_description ?? '') }}</span>/160 characters
                            </div>
                            @error('seo.meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="seoCanonicalUrl" class="form-label">Canonical URL</label>
                            <input type="url" class="form-control @error('seo.canonical_url') is-invalid @enderror" name="seo[canonical_url]"
                                id="seoCanonicalUrl" placeholder="https://example.com/canonical-slug" value="{{ $page->formatted_seo['canonical_url'] ?? $page->canonical_url ?? old('seo.canonical_url') }}">
                            @error('seo.canonical_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="seoMetaRobots" class="form-label">Search Crawlers (Robots)</label>
                            @php
                            $currentRobots = $page->formatted_seo['meta_robots'] ?? $page->meta_robots ?? 'index,follow';
                            @endphp
                            <select class="form-select @error('seo.meta_robots') is-invalid @enderror" name="seo[meta_robots]" id="seoMetaRobots">
                                <option value="index,follow" {{ $currentRobots == 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                                <option value="noindex,follow" {{ $currentRobots == 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                                <option value="index,nofollow" {{ $currentRobots == 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                                <option value="noindex,nofollow" {{ $currentRobots == 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                            </select>
                            @error('seo.meta_robots')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-4">
                            <label for="seoSchemaMarkup" class="form-label">Structured Schema Markup (JSON-LD)</label>
                            <textarea class="form-control @error('seo.schema_markup') is-invalid @enderror" name="seo[schema_markup]" id="seoSchemaMarkup" rows="4" placeholder='{"@@context":"https://schema.org","@@type":"WebPage",...}'>{{ $page->formatted_seo['schema_markup'] ?? $page->schema_markup ?? old('seo.schema_markup') }}</textarea>
                            @error('seo.schema_markup')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SEO Search Result Mockup Card -->
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="mb-3 font-weight-bold text-dark"><i class="fa-brands fa-google text-primary me-1"></i> Google Search Snippet Preview</h6>
                                    <div class="seo-preview-mockup">
                                        <div class="google-domain">
                                            <span class="google-domain-icon"><i class="fa-solid fa-globe"></i></span>
                                            <span>{{ url('/') }}</span>
                                        </div>
                                        <div class="google-title" id="previewMetaTitle">{{ $page->effective_meta_title }}</div>
                                        <div class="google-snippet" id="previewMetaDescription">{{ $page->effective_meta_description }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Form Controls -->
            <div class="col-lg-4">
                <div class="form-card" style="position: sticky; top: 20px;">
                    <div class="form-card-title">
                        <i class="fa-solid fa-sliders"></i> Page Parameters
                    </div>
                    
                    <div class="mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1" {{ $page->status === 1 ? 'selected' : '' }}>Active (Visible)</option>
                            <option value="0" {{ $page->status === 0 ? 'selected' : '' }}>Draft (Hidden)</option>
                        </select>
                        @error('status')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Social / OG image Section -->
                    <div class="mb-4">
                        <label for="seoOgImage" class="form-label">Social Media Sharing Image (Open Graph)</label>
                        <input type="file" class="form-control @error('seo.og_image') is-invalid @enderror" name="seo[og_image]" id="seoOgImage" accept="image/*">
                        
                        @if($page->formatted_seo['og_image'] ?? $page->og_image)
                        <div class="mt-3">
                            <label class="form-label text-muted d-block" style="font-size: 0.75rem;">Current Shared Image:</label>
                            @php
                            $ogImagePath = $page->formatted_seo['og_image'] ?? $page->og_image;
                            $fullImagePath = asset($ogImagePath);
                            @endphp
                            <img src="{{ $fullImagePath }}" class="img-thumbnail" style="max-height: 140px; width: 100%; object-fit: cover;">
                        </div>
                        @endif

                        <div id="ogImagePreview" class="mt-3"></div>
                        <input type="hidden" name="seo[existing_og_image]" value="{{ $page->formatted_seo['og_image'] ?? $page->og_image ?? '' }}">
                        @error('seo.og_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="seoOgImageAlt" class="form-label">Social Image Alt Text</label>
                        <input type="text" class="form-control @error('seo.og_image_alt') is-invalid @enderror" name="seo[og_image_alt]"
                            id="seoOgImageAlt" placeholder="Alt description" value="{{ $page->formatted_seo['og_image_alt'] ?? $page->og_image_alt ?? old('seo.og_image_alt') }}">
                        @error('seo.og_image_alt')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-4">

                    <button type="submit" class="btn btn-create w-100 py-3">
                        <i class="fa-regular fa-paper-plane me-1"></i> Update Page
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        // Destroy existing instance if any to prevent duplication
        if ($('#content').hasClass('note-editable')) {
            $('#content').summernote('destroy');
        }

        // Initialize Summernote Description
        $('#content').summernote({
            height: 350,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onInit: function() {
                    var content = $('#content').val();
                    if (content) {
                        $(this).summernote('code', content);
                    }
                }
            }
        });

        // Initialize Character Counter Helper
        function setupCharCount(inputEl, counterEl, maxLen) {
            function update() {
                const len = $(inputEl).val().length;
                $(counterEl).text(len);
                if (len > maxLen) {
                    $(counterEl).addClass('text-danger');
                } else {
                    $(counterEl).removeClass('text-danger');
                }
            }
            $(inputEl).on('input', update);
            update();
        }

        setupCharCount('#seoMetaTitle', '#metaTitleCount', 60);
        setupCharCount('#seoMetaDescription', '#metaDescriptionCount', 160);

        // Update Search Result Preview
        function updateGooglePreview() {
            let metaTitle = $('#seoMetaTitle').val();
            let pageTitle = $('#title').val();
            let metaDesc = $('#seoMetaDescription').val();

            // Fallback rules
            let finalTitle = metaTitle || pageTitle || "Google Search Result Preview Title";
            let finalDesc = metaDesc || "Configure search settings above. Google search results will display description text snippet preview here.";

            $('#previewMetaTitle').text(finalTitle);
            $('#previewMetaDescription').text(finalDesc);
        }

        $('#seoMetaTitle, #title, #seoMetaDescription').on('input', updateGooglePreview);
        updateGooglePreview();

        // Open Graph image preview
        $('#seoOgImage').on('change', function(e) {
            const file = e.target.files[0];
            const preview = $('#ogImagePreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-height: 160px; width: 100%; object-fit: cover;">');
                };
                reader.readAsDataURL(file);
            } else {
                preview.html('');
            }
        });

        // Slug helper string generator
        function generateSlugString(text) {
            return text
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '') // Remove non-word characters
                .replace(/[\s_-]+/g, '-') // Replace spaces or underscores with hyphens
                .replace(/^-+|-+$/g, ''); // Trim hyphens
        }

        function updateSlugPreviewDisplay() {
            const slug = $('#slug').val() || 'auto-generated-slug';
            $('#slugText').text(slug);
        }

        // Automatic slug sync
        $('#title').on('input', function() {
            if (!$('#slug').data('user-custom')) {
                const slug = generateSlugString($(this).val());
                $('#slug').val(slug);
                updateSlugPreviewDisplay();
            }
        });

        // Detect if user modified slug manually
        $('#slug').on('input', function() {
            $(this).data('user-custom', true);
            updateSlugPreviewDisplay();
        });

        // Manual generate override button
        $('#generateSlug').on('click', function() {
            const title = $('#title').val();
            if (title) {
                const slug = generateSlugString(title);
                $('#slug').val(slug);
                updateSlugPreviewDisplay();
            }
        });

        updateSlugPreviewDisplay();
    });
</script>
@endsection