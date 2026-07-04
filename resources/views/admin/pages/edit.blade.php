@extends('layouts.master')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .form-section {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
    }

    .form-section h4 {
        color: #495057;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #197A94;
    }

    .seo-preview {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 0.25rem;
        padding: 1rem;
    }

    .seo-preview p {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .seo-title {
        color: #1a73e8;
        font-weight: 600;
    }

    .seo-description {
        color: #5f6368;
    }

    .seo-url {
        color: #137333;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }
</style>
@endsection

@section('content')
<div class="container-fluid mt-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pages</li>
        </ol>
    </nav>
    <h5>Add Page</h5>
    <form action="{{ route('admin.pages.update',$page->id) }}" method="POST"
        enctype="multipart/form-data" class="mt-1 mb-3">
        @csrf
        @method('PUT')
        <div class="container-fluid border my-2 py-3">
            <a href="{{route ("admin.pages.index")}}" class="btn btn-primary mb-1">Back</a>
            <div class="row">
                <div class="col-12 col-md-12 mx-auto">
                    <div class="card p-2">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{$page->title}}" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <div class="input-group">
                                <input type="text" name="slug" id="slug"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    value="{{ $page->slug }}" placeholder="Auto-generated from title">
                                <button class="btn btn-outline-secondary" type="button" id="generateSlug">
                                    <i class="bi bi-arrow-clockwise"></i> Generate
                                </button>
                            </div>
                            <small class="form-text text-muted">Leave empty to auto-generate from title. URL: <span id="slugPreview">{{ url('/pages/') }}/<span id="slugText">{{ $page->slug }}</span></span></small>
                            @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content"
                                name="content" rows="10"
                                placeholder="Enter page content">{{ $page->content }}</textarea>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1" {{ $page->status === 1 ? 'selected' : '' }}>Active
                                </option>
                                <option value="0" {{ $page->status === 0 ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>
                            @error('status')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Section -->
        <div class="row mb-5">
            <div class="col-md-12">
                <div class="form-section">
                    <h4><i class="bi bi-search"></i> SEO Settings</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('seo.meta_title') is-invalid @enderror" name="seo[meta_title]" id="seoMetaTitle"
                                    placeholder="Enter SEO title" value="{{ $page->formatted_seo['meta_title'] ?? $page->meta_title ?? old('seo.meta_title') }}" maxlength="60">
                                <label for="seoMetaTitle">Meta Title</label>
                                <small class="form-text text-muted">
                                    <span id="metaTitleCount">{{ strlen($page->formatted_seo['meta_title'] ?? $page->meta_title ?? '') }}</span>/60 characters. Leave empty to auto-generate from page title.
                                </small>
                                @error('seo.meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <textarea class="form-control @error('seo.meta_description') is-invalid @enderror" name="seo[meta_description]" id="seoMetaDescription"
                                    placeholder="Enter SEO description" style="height: 100px;" maxlength="160">{{ $page->formatted_seo['meta_description'] ?? $page->meta_description ?? old('seo.meta_description') }}</textarea>
                                <label for="seoMetaDescription">Meta Description</label>
                                <small class="form-text text-muted">
                                    <span id="metaDescriptionCount">{{ strlen($page->formatted_seo['meta_description'] ?? $page->meta_description ?? '') }}</span>/160 characters. Leave empty to auto-generate from page content.
                                </small>
                                @error('seo.meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('seo.meta_keywords') is-invalid @enderror" name="seo[meta_keywords]"
                                    id="seoMetaKeywords" placeholder="Enter SEO keywords" value="{{ $page->formatted_seo['meta_keywords'] ?? $page->meta_keywords ?? old('seo.meta_keywords') }}">
                                <label for="seoMetaKeywords">Meta Keywords</label>
                                <small class="form-text text-muted">Comma-separated keywords. Leave empty to auto-generate from page content.</small>
                                @error('seo.meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="url" class="form-control @error('seo.canonical_url') is-invalid @enderror" name="seo[canonical_url]"
                                    id="seoCanonicalUrl" placeholder="Enter canonical URL" value="{{ $page->formatted_seo['canonical_url'] ?? $page->canonical_url ?? old('seo.canonical_url') }}">
                                <label for="seoCanonicalUrl">Canonical URL</label>
                                <small class="form-text text-muted">Leave empty to use the default page URL.</small>
                                @error('seo.canonical_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <select class="form-select @error('seo.meta_robots') is-invalid @enderror" name="seo[meta_robots]" id="seoMetaRobots">
                                    @php
                                    $currentRobots = $page->formatted_seo['meta_robots'] ?? $page->meta_robots ?? 'index,follow';
                                    @endphp
                                    <option value="index,follow" {{ $currentRobots == 'index,follow' ? 'selected' : '' }}>Index, Follow</option>
                                    <option value="noindex,follow" {{ $currentRobots == 'noindex,follow' ? 'selected' : '' }}>No Index, Follow</option>
                                    <option value="index,nofollow" {{ $currentRobots == 'index,nofollow' ? 'selected' : '' }}>Index, No Follow</option>
                                    <option value="noindex,nofollow" {{ $currentRobots == 'noindex,nofollow' ? 'selected' : '' }}>No Index, No Follow</option>
                                </select>
                                <label for="seoMetaRobots">Meta Robots</label>
                                <small class="form-text text-muted">Search engine crawling instructions.</small>
                                @error('seo.meta_robots')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="seoOgImage" class="form-label">Open Graph Image</label>
                                <input type="file" class="form-control @error('seo.og_image') is-invalid @enderror" name="seo[og_image]" id="seoOgImage" accept="image/*">
                                <small class="form-text text-muted">Custom image for social media sharing. Leave empty to use existing image.</small>
                                @if($page->formatted_seo['og_image'] ?? $page->og_image)
                                <div class="mt-2">
                                    <strong>Current Image:</strong><br>
                                    @php
                                    $ogImagePath = $page->formatted_seo['og_image'] ?? $page->og_image;
                                    $fullImagePath = asset($ogImagePath);
                                    @endphp
                                    <img src="{{ $fullImagePath }}"
                                        class="img-thumbnail"
                                        style="max-width: 200px;"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                    <div style="display: none; padding: 10px; border: 1px solid #ddd; background: #f8f9fa; color: #6c757d; text-align: center; max-width: 200px;">
                                        <i class="bi bi-image"></i><br>
                                        <small>No image available</small>
                                    </div>
                                </div>
                                @else
                                <div class="mt-2">
                                    <strong>Current Image:</strong><br>
                                    <div style="padding: 10px; border: 1px solid #ddd; background: #f8f9fa; color: #6c757d; text-align: center; max-width: 200px;">
                                        <i class="bi bi-image"></i><br>
                                        <small>No image set</small>
                                    </div>
                                </div>
                                @endif
                                <div id="ogImagePreview" class="mt-2" style="max-width: 200px;"></div>
                                <input type="hidden" name="seo[existing_og_image]" value="{{ $page->formatted_seo['og_image'] ?? $page->og_image ?? '' }}">
                                @error('seo.og_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control @error('seo.og_image_alt') is-invalid @enderror" name="seo[og_image_alt]"
                                    id="seoOgImageAlt" placeholder="Enter OG image alt text" value="{{ $page->formatted_seo['og_image_alt'] ?? $page->og_image_alt ?? old('seo.og_image_alt') }}">
                                <label for="seoOgImageAlt">OG Image Alt Text</label>
                                <small class="form-text text-muted">Alt text for the Open Graph image.</small>
                                @error('seo.og_image_alt')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="seoSchemaMarkup" class="form-label">Schema Markup (JSON-LD)</label>
                                <textarea class="form-control @error('seo.schema_markup') is-invalid @enderror" name="seo[schema_markup]" id="seoSchemaMarkup" rows="6" placeholder="Enter custom JSON-LD schema markup">{{ $page->formatted_seo['schema_markup'] ?? $page->schema_markup ?? old('seo.schema_markup') }}</textarea>
                                <small class="form-text text-muted">Custom JSON-LD schema markup. Leave empty to auto-generate basic page schema.</small>
                                <div class="alert alert-info mt-2">
                                    <strong>Example:</strong><br>
                                    <code>{"@@context":"https://schema.org","@@type":"WebPage","name":"Page Title","description":"Page Description"}</code>
                                </div>
                                @error('seo.schema_markup')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-eye"></i> SEO Preview
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="seo-preview">
                                        <p class="seo-title"><strong>Meta Title:</strong> <span id="previewMetaTitle">{{ $page->effective_meta_title }}</span></p>
                                        <p class="seo-description"><strong>Meta Description:</strong> <span id="previewMetaDescription">{{ $page->effective_meta_description }}</span></p>
                                        <p class="seo-description"><strong>Meta Keywords:</strong> <span id="previewMetaKeywords">{{ $page->effective_meta_keywords ?: 'Auto-generated from page content' }}</span></p>
                                        <p class="seo-url"><strong>Canonical URL:</strong> <span id="previewCanonicalUrl">{{ $page->effective_canonical_url }}</span></p>
                                        <p class="seo-description"><strong>Meta Robots:</strong> <span id="previewMetaRobots">{{ $page->effective_meta_robots }}</span></p>
                                        <p class="seo-description"><strong>OG Image:</strong> <span id="previewOgImage">{{ $page->effective_og_image ? 'Custom image' : 'Default page image' }}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid border my-2 py-3">
            <div class="row">
                <div class="col-12 col-md-12 mx-auto">
                    <div class="card p-2">
                        <button type="submit" class="btn btn-primary">Update Page</button>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section("scripts")
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    // Initialize Summernote for content
    $(document).ready(function() {
        // Destroy existing instance if any
        if ($('#content').hasClass('note-editable')) {
            $('#content').summernote('destroy');
        }

        $('#content').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onInit: function() {
                    // Ensure content is properly loaded
                    var content = $('#content').val();
                    if (content) {
                        $(this).summernote('code', content);
                    }
                }
            }
        });
    });

    // SEO Character Counters
    function updateCharacterCount(inputId, counterId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);

        if (input && counter) {
            input.addEventListener('input', function() {
                const length = this.value.length;
                counter.textContent = length;

                if (length > maxLength * 0.9) {
                    counter.style.color = '#dc3545';
                } else if (length > maxLength * 0.8) {
                    counter.style.color = '#ffc107';
                } else {
                    counter.style.color = '#6c757d';
                }
            });
        }
    }

    // Initialize character counters
    updateCharacterCount('seoMetaTitle', 'metaTitleCount', 60);
    updateCharacterCount('seoMetaDescription', 'metaDescriptionCount', 160);

    // SEO Preview Updates
    function updateSeoPreview() {
        const title = document.getElementById('seoMetaTitle').value || 'Auto-generated from page title';
        const description = document.getElementById('seoMetaDescription').value || 'Auto-generated from page content';
        const keywords = document.getElementById('seoMetaKeywords').value || 'Auto-generated from page content';
        const canonicalUrl = document.getElementById('seoCanonicalUrl').value || 'Default page URL';
        const robots = document.getElementById('seoMetaRobots').value || 'Index, Follow';
        const ogImage = document.getElementById('seoOgImage').files[0] ? 'Custom image selected' : '{{ $page->effective_og_image ? "Custom image" : "Default page image" }}';

        document.getElementById('previewMetaTitle').textContent = title;
        document.getElementById('previewMetaDescription').textContent = description;
        document.getElementById('previewMetaKeywords').textContent = keywords;
        document.getElementById('previewCanonicalUrl').textContent = canonicalUrl;
        document.getElementById('previewMetaRobots').textContent = robots;
        document.getElementById('previewOgImage').textContent = ogImage;
    }

    // Add event listeners for SEO preview
    ['seoMetaTitle', 'seoMetaDescription', 'seoMetaKeywords', 'seoCanonicalUrl', 'seoMetaRobots', 'seoOgImage'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updateSeoPreview);
            element.addEventListener('change', updateSeoPreview);
        }
    });

    // OG Image Preview
    document.getElementById('seoOgImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('ogImagePreview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">';
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });

    // Initialize preview on page load
    updateSeoPreview();

    // Slug generation functionality
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
            .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
    }

    function updateSlugPreview() {
        const slug = document.getElementById('slug').value || 'auto-generated-slug';
        document.getElementById('slugText').textContent = slug;
    }

    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        const slug = generateSlug(this.value);
        document.getElementById('slug').value = slug;
        updateSlugPreview();
    });

    // Manual slug generation
    document.getElementById('generateSlug').addEventListener('click', function() {
        const title = document.getElementById('title').value;
        if (title) {
            const slug = generateSlug(title);
            document.getElementById('slug').value = slug;
            updateSlugPreview();
        }
    });

    // Update slug preview when slug changes
    document.getElementById('slug').addEventListener('input', updateSlugPreview);

    // Initialize slug preview
    updateSlugPreview();
</script>
@endsection