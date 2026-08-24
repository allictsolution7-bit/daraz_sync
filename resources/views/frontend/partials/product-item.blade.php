@php
    // Use the actual slug from database, fallback to generated slug if not available
    $slug = $product->slug ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product->title)));
    
    // Get badge settings from ViewComposer (cached, no DB call)
    $badgeType = $productSettings['badge_type'] ?? 'starburst';
    $defaultBadgeText = $productSettings['default_badge_text'] ?? '১০% ছাড়';
    
    // Use pre-calculated discount percentage (no calculation needed!)
    $percentOff = $product->discount_percentage;
@endphp
<div class="product-card" data-product-id="{{ $product->id }}">
    <a href="{{ route('product.single', ['slug' => $slug, 'id' => $product->id]) }}" class="product-card-link-wrap">
        @if($badgeType !== 'none')
            @if($badgeType === 'starburst')
                <div class="product-badge starburst-badge">
                    @if ($percentOff)
                        {{ $percentOff }}%<br>Off!
                    @else
                        {{ $badge ?? $defaultBadgeText }}
                    @endif
                </div>
            @elseif($badgeType === 'simple')
                <div class="product-badge simple-badge">
                    @if ($percentOff)
                        -{{ $percentOff }}% Off!
                    @else
                        {{ $badge ?? $defaultBadgeText }}
                    @endif
                </div>
            @endif
        @endif

        <div class="product-image">
            <div class="position-relative w-100 h-100" style="overflow: hidden; border-radius: 7px;">
                <div class="image-preloader"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:rgba(248,249,250,0.9);z-index:2;transition:opacity 0.3s ease;">
                    <div class="spinner"
                        style="width:40px;height:40px;border:3px solid #e9ecef;border-top:3px solid #FF6B35;border-radius:50%;animation:spin 1s linear infinite;">
                    </div>
                    <div class="image-preloader-text"
                        style="position:absolute;bottom:10px;left:50%;transform:translateX(-50%);font-size:12px;color:#6c757d;background:rgba(255,255,255,0.8);padding:4px 8px;border-radius:4px;">
                        Loading...</div>
                </div>
                <img src="{{ asset('storage/' . $product->thumb_image) }}" alt="{{ $product->title }}" loading="lazy"
                    class="product-image-loaded" data-product-id="{{ $product->id }}"
                    style="opacity:0;">
            </div>
        </div>

        <div class="product-info">
            @if(($productSettings['show_product_title'] ?? '1') == '1')
                <h3 class="product-title">{{ $product->title }}</h3>
            @endif

            {{-- Product Rating Display --}}
            @if(($productSettings['show_product_rating'] ?? '1') == '1')
                @php
                    $averageRating = $product->active_reviews_avg_rating ?? 0;
                    $reviewCount = $product->active_reviews_count ?? 0;
                @endphp
                <div class="product-card-rating">
                    <div class="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $averageRating)
                                <svg class="star-icon filled" viewBox="0 0 24 24" width="14" height="14">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                        fill="#1e3a8a" />
                                </svg>
                            @elseif($i <= $averageRating + 0.5 && $averageRating > 0)
                                <svg class="star-icon half-filled" viewBox="0 0 24 24" width="14" height="14">
                                    <defs>
                                        <linearGradient id="halfStarCard{{ $product->id }}{{ $i }}">
                                            <stop offset="50%" stop-color="#1e3a8a" />
                                            <stop offset="50%" stop-color="#e5e7eb" />
                                        </linearGradient>
                                    </defs>
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                        fill="url(#halfStarCard{{ $product->id }}{{ $i }})" />
                                </svg>
                            @else
                                <svg class="star-icon empty" viewBox="0 0 24 24" width="14" height="14">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                        fill="#e5e7eb" />
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-text">{{ number_format($averageRating, 1) }} ({{ $reviewCount }})</span>
                </div>
            @endif

            @if(($productSettings['show_product_writer'] ?? '1') == '1')
                @if (is_iterable($product?->book?->writers) && count($product?->book?->writers) > 0)
                    <span style="color:grey;" class="loop-writers">
                        @foreach ($product?->book?->writers as $writer)
                            <span>{{ $writer->name }}</span>
                            @if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </span>
                @endif
            @endif

            <div class="product-meta">
                @if(($productSettings['show_product_price'] ?? '1') == '1')
                <div class="product-price">
                    @if ($product->product_type === 'variable')
                        @php
                            $prices = $product->price_range;
                            $minOfferPrice = $prices['min_offer'] ?? 0;
                            $maxOfferPrice = $prices['max_offer'] ?? 0;
                            $minRegularPrice = $prices['min_regular'] ?? 0;
                        @endphp
                        @if ($minOfferPrice > 0)
                            @if ($minOfferPrice === $maxOfferPrice)
                                <span class="current-price">৳ {{ number_format($minOfferPrice) }}</span>
                                @if ($minRegularPrice > $minOfferPrice)
                                    <span class="original-price">৳ {{ number_format($minRegularPrice) }}</span>
                                @endif
                            @else
                                <span class="current-price">৳ {{ number_format($minOfferPrice) }} - ৳
                                    {{ number_format($maxOfferPrice) }}</span>
                                @if ($minRegularPrice > $minOfferPrice)
                                    <span class="original-price">৳ {{ number_format($minRegularPrice) }}</span>
                                @endif
                            @endif
                        @else
                            <span class="current-price">৳
                                {{ number_format($product->offer ?? ($product->old_price ?? 0)) }}</span>
                        @endif
                    @else
                        @if ($product->offer)
                            <span class="current-price">৳ {{ $product->offer }}</span>
                            <span class="original-price">৳ {{ $product->old_price }}</span>
                        @else
                            <span class="current-price">৳ {{ $product->old_price }}</span>
                        @endif
                    @endif
                </div>
                @endif
                @if ($product->product_type === 'variable' && $product->variations->isNotEmpty())
                    @foreach ($product->variations as $variation)
                        @if ($variation->options->isNotEmpty())
                            <input type="hidden" data-variation-id="{{ $variation->id }}"
                                value="{{ $variation->options->first()->id }}">
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </a>

    @if(($productSettings['show_product_button'] ?? '1') == '1')
    <div class="product-hover-action">
        @if ($product->product_type === 'variable')
            <button type="button" class="btn-card-add-to-cart" onclick="window.location.href='{{ route('product.single', ['slug' => $slug, 'id' => $product->id]) }}'">
                <svg class="cart-icon-svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                <span>Add to Cart</span>
            </button>
        @else
            <div class="quickitemcart w-100" data-product-id="{{ $product->id }}"
                data-product-type="{{ $product->product_type }}">
                <button type="button" class="btn-card-add-to-cart add-to-cart-quick">
                    <svg class="cart-icon-svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                    <span>Add to Cart</span>
                </button>
            </div>
        @endif
    </div>
    @endif
</div>

<script>
    // Image Preloader for Product Items
    document.addEventListener('DOMContentLoaded', function() {
        initializeImagePreloaders();
    });

    // Function to initialize image preloaders
    function initializeImagePreloaders() {
        const images = document.querySelectorAll('.product-image-loaded');

        images.forEach(img => {
            const container = img.closest('.product-image');
            const preloader = container.querySelector('.image-preloader');

            // If image is already loaded
            if (img.complete && img.naturalHeight !== 0) {
                showImage(img, preloader);
            } else {
                // Image is still loading
                img.addEventListener('load', () => {
                    showImage(img, preloader);
                });

                img.addEventListener('error', () => {
                    handleImageError(img, preloader);
                });
            }
        });
    }

    function showImage(img, preloader) {
        // Add loaded class to image
        img.classList.add('loaded');

        // Hide preloader with animation
        setTimeout(() => {
            preloader.classList.add('hidden');
        }, 300);
    }

    function handleImageError(img, preloader) {
        // Show error state
        preloader.classList.add('error');
        preloader.querySelector('.image-preloader-text').textContent = 'Image not available';
        setTimeout(() => {
            preloader.classList.add('hidden');
        }, 1000);
    }

    // Function to handle new images after AJAX load (for shop page)
    function handleNewProductImages() {
        const newImages = document.querySelectorAll('.product-image-loaded:not(.loaded)');
        newImages.forEach(img => {
            const container = img.closest('.product-image');
            const preloader = container.querySelector('.image-preloader');

            if (img.complete && img.naturalHeight !== 0) {
                showImage(img, preloader);
            } else {
                img.addEventListener('load', () => {
                    showImage(img, preloader);
                });

                img.addEventListener('error', () => {
                    handleImageError(img, preloader);
                });
            }
        });
    }

    // Make function globally available for shop page AJAX
    window.handleNewProductImages = handleNewProductImages;
</script>

<style>
    /* Card Container & Permanent Template Borders */
    .product-card {
        position: relative !important;
        display: flex !important;
        flex-direction: column !important;
        border-radius: 14px !important;
        background: #ffffff !important;
        border: 1.5px solid #e2e8f0 !important;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06), 0 1px 3px rgba(0, 0, 0, 0.04) !important;
        padding-bottom: 22px !important;
        margin-bottom: 22px !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, border-color 0.3s ease, background 0.3s ease !important;
        text-decoration: none !important;
        overflow: visible !important;
        width: 100%;
    }

    .product-card-link-wrap {
        display: flex !important;
        flex-direction: column !important;
        flex: 1 !important;
        width: 100% !important;
        text-decoration: none !important;
        color: inherit !important;
    }

    .product-card-link-wrap:hover {
        text-decoration: none !important;
        color: inherit !important;
    }
    
    .product-card:hover {
        transform: translateY(-6px) !important;
        box-shadow: 0 14px 34px rgba(0, 0, 0, 0.12) !important;
        border-color: #2563eb !important;
    }

    /* Template 2 (Sky Blue & White — Light) */
    .template-2 .product-card, .t2-page .product-card {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.07), 0 1px 4px rgba(56, 189, 248, 0.08) !important;
    }
    .template-2 .product-card:hover, .t2-page .product-card:hover {
        background: #ffffff !important;
        border-color: #38bdf8 !important;
        box-shadow: 0 14px 34px rgba(56, 189, 248, 0.22), 0 4px 12px rgba(99, 102, 241, 0.10) !important;
    }
    .template-2 .product-card .product-title, .t2-page .product-card .product-title {
        color: #0f172a !important;
    }
    .template-2 .product-card .current-price, .t2-page .product-card .current-price {
        color: #2563eb !important;
    }
    .template-2 .product-card .original-price, .t2-page .product-card .original-price {
        color: #94a3b8 !important;
    }
    .template-2 .product-card .product-card-rating .rating-text, .t2-page .product-card .product-card-rating .rating-text {
        color: #64748b !important;
    }

    /* Template 3 (Neon Tech) */
    .template-3 .product-card, .t3-page .product-card {
        background: #0d1322 !important;
        border: 1px solid rgba(56, 189, 248, 0.25) !important;
    }
    .template-3 .product-card:hover, .t3-page .product-card:hover {
        background: #111a30 !important;
        border-color: #38bdf8 !important;
        box-shadow: 0 14px 36px rgba(56, 189, 248, 0.3) !important;
    }

    /* Template 4 (Dark Gold Luxury) */
    .template-4 .product-card, .t4-page .product-card {
        background: #171a21 !important;
        border: 1px solid rgba(212, 175, 55, 0.3) !important;
    }
    .template-4 .product-card:hover, .t4-page .product-card:hover {
        background: #1e222b !important;
        border-color: #d4af37 !important;
        box-shadow: 0 14px 36px rgba(212, 175, 55, 0.3) !important;
    }

    /* Template 5 & 8 (Fresh Green) */
    .template-5 .product-card, .template-8 .product-card, .t5-page .product-card {
        background: #ffffff !important;
        border: 1px solid #bbf7d0 !important;
    }
    .template-5 .product-card:hover, .template-8 .product-card:hover, .t5-page .product-card:hover {
        border-color: #22c55e !important;
        box-shadow: 0 14px 34px rgba(34, 197, 94, 0.22) !important;
    }

    /* Template 6 (Fashion Black & Gold) */
    .template-6 .product-card {
        background: #151515 !important;
        border: 1px solid rgba(201, 168, 76, 0.3) !important;
    }
    .template-6 .product-card:hover {
        background: #1c1c1c !important;
        border-color: #c9a84c !important;
        box-shadow: 0 14px 36px rgba(201, 168, 76, 0.3) !important;
    }

    /* Template 7 (Beauty Rose) */
    .template-7 .product-card {
        border: 1px solid #fbcfe8 !important;
    }
    .template-7 .product-card:hover {
        border-color: #ec4899 !important;
        box-shadow: 0 14px 34px rgba(236, 72, 153, 0.22) !important;
    }

    /* Interactive Add to Cart Button (Half Inside, Half Below on Hover) */
    .product-hover-action {
        position: absolute;
        bottom: -15px;
        left: 12px;
        right: 12px;
        z-index: 15;
        display: flex;
        justify-content: center;
        opacity: 0;
        transform: translateY(6px);
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }

    .product-card:hover .product-hover-action {
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
    }

    .btn-card-add-to-cart {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        width: 100% !important;
        padding: 7px 14px !important;
        background: linear-gradient(135deg, #1e3a8a, #2563eb) !important;
        color: #ffffff !important;
        border: none !important;
        border-radius: 20px !important;
        font-size: 11.5px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.45) !important;
        transition: all 0.2s ease !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        text-decoration: none !important;
    }

    .btn-card-add-to-cart:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
        transform: scale(1.03) !important;
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.65) !important;
        color: #ffffff !important;
    }

    .template-3 .btn-card-add-to-cart, .t3-page .btn-card-add-to-cart {
        background: linear-gradient(135deg, #0284c7, #2563eb) !important;
        border: 1px solid #38bdf8 !important;
        box-shadow: 0 0 16px rgba(56, 189, 248, 0.5) !important;
    }

    /* Template 2 Add to Cart — Blue Sky gradient */
    .template-2 .btn-card-add-to-cart, .t2-page .btn-card-add-to-cart {
        background: linear-gradient(135deg, #0ea5e9, #6366f1) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 16px rgba(14, 165, 233, 0.4) !important;
    }

    .template-4 .btn-card-add-to-cart, .t4-page .btn-card-add-to-cart, .template-6 .btn-card-add-to-cart {
        background: linear-gradient(135deg, #d4af37, #b8860b) !important;
        color: #0f1115 !important;
        box-shadow: 0 4px 16px rgba(212, 175, 55, 0.4) !important;
    }

    .template-5 .btn-card-add-to-cart, .template-8 .btn-card-add-to-cart, .t5-page .btn-card-add-to-cart {
        background: linear-gradient(135deg, #16a34a, #15803d) !important;
        box-shadow: 0 4px 14px rgba(22, 163, 74, 0.4) !important;
    }

    .template-7 .btn-card-add-to-cart {
        background: linear-gradient(135deg, #db2777, #be185d) !important;
        box-shadow: 0 4px 14px rgba(219, 39, 119, 0.4) !important;
    }

    /* On mobile / touch screens: always visible and cleanly positioned */
    @media (max-width: 900px) {
        .product-card {
            margin-bottom: 26px !important;
            padding-bottom: 24px !important;
        }
        .product-hover-action {
            opacity: 1 !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
            bottom: -13px !important;
            left: 8px !important;
            right: 8px !important;
        }
        .btn-card-add-to-cart {
            padding: 5px 10px !important;
            font-size: 10px !important;
            border-radius: 16px !important;
            gap: 4px !important;
        }
        .btn-card-add-to-cart svg {
            width: 12px !important;
            height: 12px !important;
        }
    }
</style>

{{-- Buy Now JavaScript (Direct Buy Only) --}}
<script>
    // Global function to handle DIRECT buy now (form submission to buynow.blade.php)
    function handleDirectBuySubmit(form, event) {
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<span>Loading...</span>';
        }
        return true;
    }
</script>
