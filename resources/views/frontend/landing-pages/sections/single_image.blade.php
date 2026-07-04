@if ($section && $section->status)
<section class="single-image-section">
    <div class="single-image-wrapper">
        <div class="single-image-content">
            @if ($section->sub_heading)
                <p class="single-image-description">{{ $section->sub_heading }}</p>
            @endif

            @if ($section->single_image)
                <div class="single-image-container">
                    <img src="{{ asset($section->single_image) }}"
                        alt="{{ $section->single_image_alt ?? 'Section Image' }}"
                        class="single-image" loading="lazy">
                </div>
            @endif
        </div>
    </div>
</section>
<style>
    .single-image-section {
        padding: 30px 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        margin-bottom: 10px;
    }

    .single-image-wrapper {
        max-width: 1340px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .single-image-content {
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
    }

    .single-image-title {
        color: var(--primary-color);
        margin-bottom: 20px;
        font-family: 'Hind Siliguri', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
        line-height: 1.2;
    }

    .single-image-description {
        color: var(--secondary-color);
        margin-bottom: 30px;
        font-size: 18px;
        line-height: 1.6;
        font-family: 'Hind Siliguri', sans-serif;
    }

    .single-image-container {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .single-image-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .single-image {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.3s ease;
    }

    .single-image-container:hover .single-image {
        transform: scale(1.02);
    }

    /* Lazy Loading Styles */
    img[loading="lazy"] {
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    img[loading="lazy"].loaded {
        opacity: 1;
    }

    /* Fallback for lazy loading - show images after 2 seconds if JS fails */
    img[loading="lazy"] {
        animation: lazyLoadFallback 2s forwards;
    }

    @keyframes lazyLoadFallback {
        to {
            opacity: 1;
        }
    }
            /* Responsive Design for Single Image Section */
        @media (max-width: 768px) {
            .single-image-section {
                padding: 40px 0;
            }

            .single-image-wrapper {
                padding: 0 15px;
            }

            .single-image-title {
                font-size: 2rem;
                margin-bottom: 15px;
            }

            .single-image-description {
                font-size: 16px;
                margin-bottom: 25px;
            }
        }

        @media (max-width: 480px) {
            .single-image-section {
                padding: 30px 0;
            }

            .single-image-title {
                font-size: 1.75rem;
            }

            .single-image-description {
                font-size: 15px;
            }
        }
</style>
@endif
