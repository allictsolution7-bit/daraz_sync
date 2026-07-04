<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $landingPage->title }}</title>
    <meta name="description" content="{{ $landingPage->sub_heading }}">
    <link rel="icon" type="image/x-icon" href="/favicons/tQJvnjUyDsNhvrg0JcxpQykNeyd4sYuxdIz7f2UL.webp">

    <script src="{{ asset('js/landing_analytics.js') }}?v={{ filemtime(public_path('js/landing_analytics.js')) }}" defer></script>
    
    <!-- Swiper.js CSS and JS (Local - Bundle Version) -->
    <link rel="stylesheet" href="{{ asset('css/vendor/swiper-bundle.min.css') }}" />
    <script src="{{ asset('js/vendor/swiper-bundle.min.js') }}"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Tag Manager -->
    @php
        $gtmId = setting('general', 'gtm_id', 'GTM-MMMMMMM');
    @endphp
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ $gtmId }}');
    </script>
    <!-- End Google Tag Manager -->

    {{-- Landing Page Color Variables --}}
    @php
        $primaryColor = $landingPage->primary_color ?? '#007bff';
        $secondaryColor = $landingPage->secondary_color ?? '#6c757d';
        $accentColor = $landingPage->accent_color ?? '#28a745';
        $orderButtonColor = $landingPage->order_button_color ?? '#dc3545';
        $firstHero = isset($heroSections) ? $heroSections->first() : null;

        // Helper function to convert English numbers to Bangla numerals
        function englishToBangla($number)
        {
            $banglaNumbers = [
                '0' => '০',
                '1' => '১',
                '2' => '২',
                '3' => '৩',
                '4' => '৪',
                '5' => '৫',
                '6' => '৬',
                '7' => '৭',
                '8' => '৮',
                '9' => '৯',
            ];
            return str_replace(array_keys($banglaNumbers), array_values($banglaNumbers), $number);
        }
    @endphp

    <style>
        /* ========================================
           GLOBAL STYLES - LANDING PAGE
           ======================================== */

        /* CSS Variables */
        .landing-page-theme {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --accent-color: {{ $accentColor }};
            --order-button-color: {{ $orderButtonColor }};
        }

        /* Dynamic Badge Width Variables */
        .badge-width-vars {
            --badge-desktop-width: {{ optional($firstHero)->badge_desktop_width ?? 100 }}px;
            --badge-mobile-width: {{ optional($firstHero)->badge_mobile_width ?? 80 }}px;
        }

        /* ========================================
           CSS RESET
           ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: inherit;
        }

        /* ========================================
           LAYOUT UTILITIES
           ======================================== */
        .natural-products-showcase {
            padding: 50px 20px;
            background-color: #ffffff;
        }

        .showcase-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .product-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 40px;
        }

        .product-content {
            flex: 1;
            min-width: 300px;
        }

        /* ========================================
           GLOBAL COMPONENTS
           ======================================== */
        .organic-badge {
            display: inline-block;
            background-color: {{ $landingPage->badge_color ?? 'var(--primary-color)' }};
            color: #fff !important;
            font-weight: bold;
            padding: 8px 16px;
            border-radius: 30px;
            margin-bottom: 20px;
        }

        .section-title {
            line-height: 1.3;
            font-size: 36px;
            color: #00000;
        }

        .cta-global {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            font-family: inherit;
        }

        /* ========================================
           DISCOUNT & PRICING
           ======================================== */
        .discount-info {
            display: flex;
            align-items: center;
            gap: 2px;
            margin-bottom: 0px;
        }

        .regular-price {
            color: #000000;
            font-size: 15px;
        }

        .strikethrough {
            text-decoration: line-through;
            font-family: auto !important;
        }

        .discount-badge {
            background-color: #e74c3c;
            color: white;
            padding: 1px 5px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        /* ========================================
           ALERTS
           ======================================== */
        .alert {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            margin: 5px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .blink-alert {
            background: #dc3545;
            color: white;
            animation: blink 1s infinite;
        }

        .alert:hover {
            transform: scale(1.05);
        }

        /* ========================================
           BUTTON UTILITIES
           ======================================== */
        .btn {
            display: inline-block;
            padding: 12px 100px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .primary-btn {
            background-color: var(--accent-color);
            color: white;
        }

        .primary-btn:hover {
            background-color: #641e1d;
        }

        .outline-btn {
            border: 2px solid #6d4c41;
            color: #6d4c41;
        }

        .outline-btn:hover {
            background-color: #6d4c41;
            color: white;
        }

        /* ========================================
           ANIMATIONS
           ======================================== */
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.7; }
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            11.11% { transform: translateX(-5px); }
            22.22% { transform: translateX(5px); }
            33.33% { transform: translateX(-5px); }
            44.44% { transform: translateX(5px); }
            55.55% { transform: translateX(-5px); }
            66.66% { transform: translateX(5px); }
            77.77% { transform: translateX(-5px); }
            88.88% { transform: translateX(5px); }
            100% { transform: translateX(0); }
        }

    </style>

    @include('partials.font-loader')


</head>

<body class="landing-page-theme">

    @if(isset($allSectionsOrdered) && $allSectionsOrdered->count())
        @foreach ($allSectionsOrdered as $section)
            @switch($section->section_type)
                @case('header')
                    @include('frontend.landing-pages.sections.header', ['section' => $section, 'landingPage' => $landingPage])
                    @break
                @case('hero')
                    @include('frontend.landing-pages.sections.hero', ['section' => $section, 'landingPage' => $landingPage, 'product' => $product])
                    @break
                @case('benefit')
                    @include('frontend.landing-pages.sections.benefit', ['section' => $section])
                    @break
                @case('feature_list')
                    @include('frontend.landing-pages.sections.feature_list', ['section' => $section])
                    @break
                @case('pricing')
                    @include('frontend.landing-pages.sections.pricing', ['section' => $section])
                    @break
                @case('countdown')
                    @include('frontend.landing-pages.sections.countdown', ['section' => $section])
                    @break
                @case('video')
                    @include('frontend.landing-pages.sections.video', ['section' => $section])
                    @break
                @case('single_image')
                    @include('frontend.landing-pages.sections.single_image', ['section' => $section])
                    @break
                @case('call_to_action')
                    @include('frontend.landing-pages.sections.call_to_action', ['section' => $section])
                    @break
                @case('image_carousel')
                    @include('frontend.landing-pages.sections.image_carousel', ['section' => $section])
                    @break
                @case('testimonials')
                    @include('frontend.landing-pages.sections.testimonials', ['section' => $section])
                    @break
                @default
                    {{-- Unknown section type: skip --}}
            @endswitch
        @endforeach
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const testimonialEl = document.querySelector('.testimonials-swiper');
            if (testimonialEl) {
                new Swiper(testimonialEl, {
                    breakpoints: {
                        320: { slidesPerView: 1, spaceBetween: 20 },
                        481: { slidesPerView: 2, spaceBetween: 30 },
                        769: { slidesPerView: 3, spaceBetween: 30 }
                    },
                    navigation: {
                        nextEl: testimonialEl.querySelector('.swiper-button-next'),
                        prevEl: testimonialEl.querySelector('.swiper-button-prev'),
                    },
                    autoplay: { delay: 5000, disableOnInteraction: false },
                    loop: true,
                    allowTouchMove: true,
                    simulateTouch: true,
                    touchRatio: 1,
                    touchAngle: 45,
                    speed: 600,
                    pauseOnMouseEnter: true,
                    autoHeight: true,
                    grabCursor: true,
                    effect: 'slide',
                });
            }

            document.querySelectorAll('.image-carousel').forEach(function(carouselEl) {
                const slideCount = carouselEl.querySelectorAll('.swiper-slide').length;
                const perView = slideCount >= 5 ? 5 : Math.max(slideCount, 1);
                const carouselId = carouselEl.getAttribute('data-carousel-id');
                const thumbsEl = document.querySelector(`.image-carousel-thumbs[data-carousel-id="${carouselId}"]`);
                let thumbsSwiper = null;

                if (thumbsEl) {
                    thumbsSwiper = new Swiper(thumbsEl, {
                        spaceBetween: 10,
                        slidesPerView: Math.min(slideCount, 6) || 1,
                        freeMode: true,
                        watchSlidesProgress: true,
                        watchSlidesVisibility: true,
                        slideToClickedSlide: true,
                        allowTouchMove: true,
                        simulateTouch: true,
                        grabCursor: true,
                    });
                }

                new Swiper(carouselEl, {
                    slidesPerView: perView,
                    spaceBetween: 14,
                    loop: slideCount > 1,
                    loopAdditionalSlides: Math.max(0, 5 - slideCount),
                    allowTouchMove: true,
                    simulateTouch: true,
                    touchRatio: 1,
                    touchAngle: 45,
                    grabCursor: true,
                    autoplay: slideCount > 1 ? {
                        delay: 2500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    } : false,
                    navigation: {
                        nextEl: carouselEl.querySelector('.swiper-button-next'),
                        prevEl: carouselEl.querySelector('.swiper-button-prev'),
                    },
                    pagination: {
                        el: carouselEl.querySelector('.swiper-pagination'),
                        clickable: true,
                    },
                    breakpoints: {
                        0: { slidesPerView: Math.min(1.2, Math.max(slideCount, 1)), spaceBetween: 12 },
                        480: { slidesPerView: Math.min(2, Math.max(slideCount, 1)), spaceBetween: 14 },
                        768: { slidesPerView: Math.min(3, Math.max(slideCount, 1)), spaceBetween: 14 },
                        1024: { slidesPerView: Math.min(4, Math.max(slideCount, 1)), spaceBetween: 16 },
                        1280: { slidesPerView: Math.min(5, Math.max(slideCount, 1)), spaceBetween: 18 },
                    },
                    thumbs: thumbsSwiper ? { swiper: thumbsSwiper } : undefined,
                });
            });

            const lightbox = document.getElementById('carousel-lightbox');
            const lightboxImg = document.getElementById('lightbox-image');
            const lightboxClose = document.querySelector('.lightbox-close');

            document.querySelectorAll('.carousel-zoom-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const src = btn.getAttribute('data-image');
                    if (!src || !lightbox || !lightboxImg) return;
                    lightboxImg.src = src;
                    lightbox.classList.add('active');
                    document.body.classList.add('lightbox-open');
                });
            });

            const hideLightbox = () => {
                if (lightbox) {
                    lightbox.classList.remove('active');
                    document.body.classList.remove('lightbox-open');
                }
            };

            if (lightbox) {
                lightbox.addEventListener('click', (e) => {
                    if (e.target === lightbox) {
                        hideLightbox();
                    }
                });
            }

            if (lightboxClose) {
                lightboxClose.addEventListener('click', hideLightbox);
            }

            document.addEventListener('keyup', (e) => {
                if (e.key === 'Escape') {
                    hideLightbox();
                }
            });
        });

        // Unmute helper for hero/video overlays
        function unmuteVideo(iframeId) {
            const iframe = document.getElementById(iframeId);
            if (iframe) {
                let currentSrc = iframe.src;
                currentSrc = currentSrc.replace('&mute=1', '');
                iframe.src = currentSrc;
                const overlay = iframe.parentElement.querySelector('.unmute-overlay');
                if (overlay) {
                    overlay.classList.add('hidden');
                }
            }
        }
    </script>

    @include('frontend.landing-pages.sections.order-form')

    <section class="footer-landing">
        <header class="site-header">
            <div class="header-container">
                <nav class="main-nav">
                    <ul class="nav-menu">
                        <li class="nav-item"><a href="#" class="nav-link">প্রাইভেসি পলিসি</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">যোগাযোগ</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">© {{ date('Y') }} {{ App\Models\SiteSetting::get('general', 'site_name', 'Thikana Shop') }}</a></li>
                    </ul>
                </nav>  
                <div class="copyright-text">
                    
                    <span class="developer-credit"><span style="color: #373737ff;">Developed by</span> <a href="https://uddoktaecommerce.com" target="_blank">Uddokta Ecommerce</a></span>
                </div>
            </div>
        </header>
    </section>

    <script src="{{ asset('js/vendor/jquery.min.js') }}"></script>
    <script src="{{ asset('js/incomplete-landing.js') }}"></script>
    <script>
        // Combination data for JavaScript
        window.combinationData = @json($product->variationCombinations ?? []);
        window.combinationMap = @json($combinationMap ?? []);
        // Shipping settings
        window.shippingSettings = {
            flatRate: {{ $shippingSetting->flat_rate }},
            shippingOptions: @json($activeShippingOptions),
            freeShippingThreshold: {{ $shippingSetting->free_shipping_threshold }},
            specificRules: @json($specificShippingRules)
        };
        

        // Lazy loading animation
        document.addEventListener('DOMContentLoaded', function() {
            const lazyImages = document.querySelectorAll('img[loading="lazy"]');

            // Fallback: show all images after 3 seconds if IntersectionObserver fails
            setTimeout(function() {
                lazyImages.forEach(img => {
                    if (!img.classList.contains('loaded')) {
                        img.classList.add('loaded');
                    }
                });
            }, 3000);

            // Check if IntersectionObserver is supported
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.addEventListener('load', function() {
                                img.classList.add('loaded');
                            });
                            // Also handle error case
                            img.addEventListener('error', function() {
                                img.classList.add('loaded'); // Show anyway
                            });
                            observer.unobserve(img);
                        }
                    });
                });

                lazyImages.forEach(img => imageObserver.observe(img));
            } else {
                // Fallback for browsers without IntersectionObserver
                lazyImages.forEach(img => {
                    img.addEventListener('load', function() {
                        img.classList.add('loaded');
                    });
                    img.addEventListener('error', function() {
                        img.classList.add('loaded');
                    });
                });
            }
        });

        // Lazy video loading
        document.addEventListener('DOMContentLoaded', function() {
            const lazyVideos = document.querySelectorAll('.lazy-video');

            // Load videos after page load with a small delay
            setTimeout(function() {
                lazyVideos.forEach(video => {
                    if (video.dataset.src && !video.src) {
                        video.src = video.dataset.src;
                        video.classList.add('loaded');
                    }
                });
            }, 1000); // 1 second delay after page load

            // Also load videos when they come into view (for better performance)
            if ('IntersectionObserver' in window) {
                const videoObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const video = entry.target;
                            if (video.dataset.src && !video.src) {
                                video.src = video.dataset.src;
                                video.classList.add('loaded');
                            }
                            observer.unobserve(video);
                        }
                    });
                }, {
                    rootMargin: '50px' // Start loading 50px before video comes into view
                });

                lazyVideos.forEach(video => videoObserver.observe(video));
            }
        });

        // Helper function to convert English numbers to Bangla numerals
        function englishToBanglaNumber(number) {
            const banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
            return number.toString().replace(/[0-9]/g, function(digit) {
                return banglaDigits[parseInt(digit)];
            });
        }



        // Quantity controls
        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            quantityInput.value = parseInt(quantityInput.value) + 1;
            updatePrices();
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
                updatePrices();
            }
        }

        function updatePrices() {
            const quantity = parseInt(document.getElementById('quantity').value);
            const price = parseFloat(document.querySelector('input[name="price"]').value) || 0;
            const itemTotal = price * quantity;

            const itemTotalElement = document.getElementById('itemTotal');
            const shippingCostElement = document.getElementById('shippingCost');
            const subtotalElement = document.getElementById('subtotal');

            if (itemTotalElement) {
                itemTotalElement.textContent = englishToBanglaNumber(itemTotal.toFixed(2));
            }

            // Get shipping cost from selected shipping area
            const selectedShipping = document.querySelector('input[name="shipping_area"]:checked');
            let shippingCost = 0;

            if (selectedShipping) {
                const settings = window.shippingSettings;
                if (selectedShipping.value === 'flat_rate') {
                    shippingCost = settings.flatRate;
                } else {
                    // Try both string and numeric keys
                    let selectedOption = settings.shippingOptions[selectedShipping.value];
                    if (!selectedOption) {
                        selectedOption = settings.shippingOptions[parseInt(selectedShipping.value)];
                    }
                    if (selectedOption && (selectedOption.active || selectedOption.rule_type === 'delivery_area')) {
                        shippingCost = parseFloat(selectedOption.cost);
                    }
                }
            } else {
                // If no shipping area selected, use flat rate
                shippingCost = window.shippingSettings.flatRate;
            }

            // Apply free shipping if threshold met
            // First check specific shipping rules
            let freeShippingThreshold = window.shippingSettings.freeShippingThreshold;
            let fallbackCost = null;
            
            // Check for specific free shipping threshold rules
            if (window.shippingSettings.specificRules) {
                window.shippingSettings.specificRules.forEach(rule => {
                    if (rule.rule_type === 'free_shipping' && rule.free_shipping_threshold) {
                        freeShippingThreshold = rule.free_shipping_threshold;
                        fallbackCost = rule.rule_value; // Fallback cost when threshold not met
                    }
                });
            }
            
            if (itemTotal >= freeShippingThreshold) {
                shippingCost = 0;
            } else if (fallbackCost !== null) {
                // Use the fallback cost from the specific rule
                shippingCost = parseFloat(fallbackCost);
            }

            if (shippingCostElement) {
                shippingCostElement.textContent = englishToBanglaNumber(shippingCost.toFixed(2));
            }

            if (subtotalElement) {
                subtotalElement.textContent = englishToBanglaNumber((itemTotal + shippingCost).toFixed(2));
            }

            // Update hidden shipping input
            document.querySelector('input[name="shipping"]').value = shippingCost;
        }

        function updateVariationPrice() {
            var variationGroups = document.querySelectorAll('.variation-btn-group');
            var selectedOptions = {};
            var allSelected = true;

            // Get all selected options from each variation group
            variationGroups.forEach(function(group) {
                var checkedRadio = group.querySelector('.variation-radio:checked');
                if (checkedRadio) {
                    var variationId = group.getAttribute('data-variation-id');
                    selectedOptions[variationId] = {
                        radio: checkedRadio,
                        price: checkedRadio.getAttribute('data-price'),
                        image: checkedRadio.getAttribute('data-image'),
                        combinationId: checkedRadio.getAttribute('data-combination-id')
                    };
                } else {
                    allSelected = false;
                }
            });

            // Only update if we have a complete selection
            if (allSelected && Object.keys(selectedOptions).length > 0) {
                // Find the matching combination to get the correct price
                var selectedOptionIds = Object.values(selectedOptions).map(function(option) {
                    return parseInt(option.radio.value);
                }).sort(function(a, b) {
                    return a - b;
                });

                var matchingCombination = null;
                if (window.combinationData) {
                    for (var i = 0; i < window.combinationData.length; i++) {
                        var combination = window.combinationData[i];
                        var combinationOptions = Array.isArray(combination.variation_options) ?
                            combination.variation_options :
                            JSON.parse(combination.variation_options);

                        combinationOptions.sort(function(a, b) {
                            return a - b;
                        });

                        if (combinationOptions.length === selectedOptionIds.length) {
                            var match = true;
                            for (var j = 0; j < combinationOptions.length; j++) {
                                if (combinationOptions[j] !== selectedOptionIds[j]) {
                                    match = false;
                                    break;
                                }
                            }
                            if (match) {
                                matchingCombination = combination;
                                break;
                            }
                        }
                    }
                }

                var price, image, combinationId;
                if (matchingCombination) {
                    // Use the combination price
                    price = matchingCombination.offer_price || matchingCombination.regular_price || matchingCombination
                        .price || 0;
                    combinationId = matchingCombination.id;
                    // Use combination's featured image if available, otherwise use first selected option's image
                    if (matchingCombination.featured_image) {
                        image = '{{ asset('storage/') }}/' + matchingCombination.featured_image;
                    } else {
                        var firstSelected = Object.values(selectedOptions)[0];
                        image = firstSelected.image;
                    }
                } else {
                    // Fallback to first selected option
                    var firstSelected = Object.values(selectedOptions)[0];
                    price = firstSelected.price;
                    image = firstSelected.image;
                    combinationId = firstSelected.combinationId;
                }

                // Update price fields
                var priceInput = document.querySelector('input[name="price"]');
                if (priceInput) priceInput.value = price;

                var selectedPriceInput = document.getElementById('selected_price');
                if (selectedPriceInput) selectedPriceInput.value = price;

                var priceDisplay = document.querySelector('.product-price');
                if (priceDisplay) priceDisplay.textContent = '৳' + englishToBanglaNumber(parseFloat(price).toFixed(2));

                // Update combination_id
                if (combinationId) {
                    var combinationInput = document.getElementById('selected_combination_id');
                    if (combinationInput) combinationInput.value = combinationId;
                }

                // Update item total and subtotal
                updatePrices();

                // Optionally update product image
                if (image) {
                    updateProductImage(image);
                }
            }
        }

        // Function to handle combination selection
        function handleCombinationSelection() {
            var variationGroups = document.querySelectorAll('.variation-btn-group');
            var selectedOptions = {};
            var allSelected = true;

            // Get all selected options from each variation group
            variationGroups.forEach(function(group) {
                var variationId = group.getAttribute('data-variation-id');
                var checkedRadio = group.querySelector('.variation-radio:checked');

                if (checkedRadio) {
                    selectedOptions[variationId] = checkedRadio.value;
                } else {
                    allSelected = false;
                }
            });



            if (allSelected && Object.keys(selectedOptions).length > 0) {
                // Find the matching combination
                var combinationId = findMatchingCombination(selectedOptions);

                if (combinationId) {
                    document.getElementById('selected_combination_id').value = combinationId;
                }
            }
        }

        // Function to update product image
        function updateProductImage(imagePath) {
            var img = document.querySelector('.item-image img');
            if (img && imagePath) {


                // Create a new image to test loading
                var testImg = new Image();
                testImg.onload = function() {

                    img.src = imagePath;
                    img.style.display = 'block'; // Ensure image is visible
                };
                testImg.onerror = function() {

                    // Fallback to product thumb image
                    img.src = '{{ asset('storage/' . $product->thumb_image) }}';
                    img.style.display = 'block'; // Ensure image is visible
                };
                testImg.src = imagePath;
            } else {

            }
        }

        // Function to ensure initial image is visible
        function ensureInitialImage() {
            var img = document.querySelector('.item-image img');
            if (img) {

                img.style.display = 'block';
                img.style.maxWidth = '100%';
                img.style.height = 'auto';
            }
        }

        // Function to find matching combination
        function findMatchingCombination(selectedOptions) {
            if (!window.combinationData) return null;

            // Convert selected options to array of option IDs
            var selectedOptionIds = Object.values(selectedOptions).map(function(id) {
                return parseInt(id);
            }).sort(function(a, b) {
                return a - b;
            });



            // Find matching combination
            for (var i = 0; i < window.combinationData.length; i++) {
                var combination = window.combinationData[i];
                var combinationOptions = Array.isArray(combination.variation_options) ?
                    combination.variation_options :
                    JSON.parse(combination.variation_options);

                // Sort combination options for comparison
                combinationOptions.sort(function(a, b) {
                    return a - b;
                });



                // Check if this combination matches our selected options
                if (combinationOptions.length === selectedOptionIds.length) {
                    var match = true;
                    for (var j = 0; j < combinationOptions.length; j++) {
                        if (combinationOptions[j] !== selectedOptionIds[j]) {
                            match = false;
                            break;
                        }
                    }
                    if (match) {

                        return combination.id;
                    }
                }
            }

            return null;
        }

    </script>

    @php
        $landingCallEnabled = setting('ecommerce', 'landing_call_button_show', '1') == '1';
        $landingCallNumber = setting('ecommerce', 'landing_call_button_number', '');
        if (blank($landingCallNumber)) {
            $landingCallNumber = setting('general', 'phone_number', '');
        }
        $landingCallHref = $landingCallNumber ? preg_replace('/[^0-9\+]/', '', $landingCallNumber) : '';
    @endphp

    @if ($landingCallEnabled && $landingCallNumber)
        <!-- Call fixed button for chat -->
        <div class="call-button-container">
            <a href="tel:{{ $landingCallHref }}" class="call-button" id="callButton">
                <svg class="phone-icon" viewBox="0 0 24 24">
                    <path
                        d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z" />
                </svg>
            </a>
        </div>
        <style>
            .call-button-container {
                position: fixed;
                bottom: 30px;
                right: 30px;
                z-index: 1000;
            }
    
            .call-button {
                position: relative;
                width: 60px;
                height: 60px;
                background: #027BFF;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 20px rgba(2, 123, 255, 0.4);
                transition: all 0.3s ease;
                text-decoration: none;
            }
    
            .call-button:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 25px rgba(2, 123, 255, 0.6);
            }
    
            .call-button::before {
                content: '';
                position: absolute;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                background: rgba(2, 123, 255, 0.3);
                animation: pulse 2s infinite;
            }
    
            .call-button::after {
                content: '';
                position: absolute;
                width: 140%;
                height: 140%;
                border-radius: 50%;
                background: rgba(2, 123, 255, 0.15);
                animation: pulse 2s infinite 0.5s;
            }
    
            .phone-icon {
                width: 24px;
                height: 24px;
                fill: white;
                z-index: 10;
                position: relative;
            }
    
            @keyframes pulse {
                0% {
                    transform: scale(1);
                    opacity: 0.8;
                }
    
                50% {
                    transform: scale(1.2);
                    opacity: 0.4;
                }
    
                100% {
                    transform: scale(1.4);
                    opacity: 0;
                }
            }
    
            @media (max-width: 768px) {
                .call-button-container {
                    bottom: 185px;
                    right: 20px;
                }
    
                .call-button {
                    width: 50px;
                    height: 50px;
                }
    
                .phone-icon {
                    width: 22px;
                    height: 22px;
                }
            }
    
            .call-button.clicked::before {
                animation: clickRipple 0.6s ease-out;
            }
    
            @keyframes clickRipple {
                0% {
                    transform: scale(1);
                    opacity: 0.8;
                }
    
                100% {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        </style>
    @endif
   
    @if ($landingCallEnabled && $landingCallNumber)
        <script>
            const callButton = document.getElementById('callButton');

            if (callButton) {
                callButton.addEventListener('click', function() {
                    this.classList.add('clicked');
                    setTimeout(() => {
                        this.classList.remove('clicked');
                    }, 600);
                });

                callButton.addEventListener('click', function() {
                    if (navigator.vibrate) {
                        navigator.vibrate(100);
                    }
                });
            }
        </script>
    @endif

    @php
        $showLandingWhatsApp = setting('general', 'show_whatsapp_button_landing', '1') == '1';
        $landingNumberRaw = setting('general', 'landing_whatsapp_number', '') ?: setting('general', 'whatsapp_number', '');
        $landingNumberFormatted = preg_replace('/\D+/', '', $landingNumberRaw);
        $landingMessage = urlencode(setting('general', 'landing_whatsapp_message', setting('general', 'whatsapp_message', "Hello, I'm interested in your services")));
    @endphp

    @if ($showLandingWhatsApp && !empty($landingNumberFormatted))
        <!-- Whatsapp fixed button for chat -->
        <div class="whatsapp-button-container">
            <a href="https://wa.me/{{ $landingNumberFormatted }}?text={{ $landingMessage }}"
                class="whatsapp-button" id="whatsappButton">
                <svg class="whatsapp-icon" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.569-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.465 3.488" />
                </svg>
            </a>
        </div>
        <style>
            .whatsapp-button-container {
                position: fixed;
                bottom: 100px;
                right: 30px;
                z-index: 1000;
            }

            .whatsapp-button {
                position: relative;
                width: 60px;
                height: 60px;
                background: #25d366;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
                transition: all 0.3s ease;
                text-decoration: none;
            }

            .whatsapp-button:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 25px rgba(37, 211, 102, 0.6);
            }

            .whatsapp-button::before {
                content: '';
                position: absolute;
                width: 100%;
                height: 100%;
                border-radius: 50%;
                background: rgba(37, 211, 102, 0.3);
                animation: whatsappPulse 2s infinite;
            }

            .whatsapp-button::after {
                content: '';
                position: absolute;
                width: 140%;
                height: 140%;
                border-radius: 50%;
                background: rgba(37, 211, 102, 0.15);
                animation: whatsappPulse 2s infinite 0.5s;
            }

            .whatsapp-icon {
                width: 30px;
                height: 30px;
                fill: white;
                z-index: 10;
                position: relative;
            }

            @keyframes whatsappPulse {
                0% {
                    transform: scale(1);
                    opacity: 0.8;
                }

                50% {
                    transform: scale(1.2);
                    opacity: 0.4;
                }

                100% {
                    transform: scale(1.4);
                    opacity: 0;
                }
            }

            @media (max-width: 768px) {
                .whatsapp-button-container {
                    bottom: 112px;
                    right: 20px;
                }

                .whatsapp-button {
                    width: 55px;
                    height: 55px;
                }

                .whatsapp-icon {
                    width: 26px;
                    height: 26px;
                }
            }

            .whatsapp-button.clicked::before {
                animation: whatsappRipple 0.6s ease-out;
            }

            @keyframes whatsappRipple {
                0% {
                    transform: scale(1);
                    opacity: 0.8;
                }

                100% {
                    transform: scale(2);
                    opacity: 0;
                }
            }

            .whatsapp-button {
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-5px);
                }
            }
        </style>
        <script>
            const whatsappButton = document.getElementById('whatsappButton');

            whatsappButton.addEventListener('click', function() {
                this.classList.add('clicked');
                setTimeout(() => {
                    this.classList.remove('clicked');
                }, 600);
            });

            whatsappButton.addEventListener('click', function() {
                if (navigator.vibrate) {
                    navigator.vibrate(100);
                }
            });
        </script>
    @endif

    {{-- Messenger fixed button for chat (placeholder) --}}

    <style>
        .messenger-button-container {
            position: fixed;
            bottom: 100px;
            right: 30px;
            z-index: 1000;
        }

        .messenger-button {
            position: relative;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #0084ff, #00a3ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0, 132, 255, 0.4);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .messenger-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(0, 132, 255, 0.6);
        }

        .messenger-button::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(0, 132, 255, 0.3);
            animation: messengerPulse 2s infinite;
        }

        .messenger-button::after {
            content: '';
            position: absolute;
            width: 140%;
            height: 140%;
            border-radius: 50%;
            background: rgba(0, 132, 255, 0.15);
            animation: messengerPulse 2s infinite 0.5s;
        }

        .messenger-icon {
            width: 28px;
            height: 28px;
            fill: white;
            z-index: 10;
            position: relative;
        }

        @keyframes messengerPulse {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.4;
            }

            100% {
                transform: scale(1.4);
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .messenger-button-container {
                bottom: 80px;
                right: 20px;
            }

            .messenger-button {
                width: 55px;
                height: 55px;
            }

            .messenger-icon {
                width: 25px;
                height: 25px;
            }
        }

        .messenger-button.clicked::before {
            animation: messengerRipple 0.6s ease-out;
        }

        @keyframes messengerRipple {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .messenger-button {
            animation: messengerFloat 3s ease-in-out infinite;
        }

        @keyframes messengerFloat {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .messenger-button {
            animation: messengerFloat 3s ease-in-out infinite, shadowPulse 2s ease-in-out infinite;
        }

        @keyframes shadowPulse {

            0%,
            100% {
                box-shadow: 0 4px 20px rgba(0, 132, 255, 0.4);
            }

            50% {
                box-shadow: 0 8px 30px rgba(0, 132, 255, 0.6);
            }
        }
    </style>
    
    <!-- Messenger fixed button for chat -->
    <script>
        const messengerButton = document.getElementById('messengerButton');

        messengerButton.addEventListener('click', function() {
            this.classList.add('clicked');
            setTimeout(() => {
                this.classList.remove('clicked');
            }, 600);
        });

        messengerButton.addEventListener('click', function() {
            if (navigator.vibrate) {
                navigator.vibrate(100);
            }
        });

        messengerButton.addEventListener('click', function() {
        });
    </script>

    <!-- Fraud Protection System -->
    <script src="{{ asset('js/fraud-protection.js') }}"></script>
    <script>
        // Initialize Fraud Protection with settings from backend
        const fraudProtectionSettings = @json(app(\App\Services\FraudProtectionService::class)->getFrontendSettings());
        const fraudProtection = new FraudProtection(fraudProtectionSettings);
        window.fraudProtectionInstance = fraudProtection;
    </script>

    <style>
        .strike-through {
            font-family: initial;
        }

        .trust-value {
            font-family: initial;
        }

        .bangla-number {
                    font-family: initial;
                }

        .product-description {
            font-family: initial;
        }

        span.organic-badge {
            font-family: initial;
        }

        .countdown-container {
            font-family: initial;
        }
        span.item-total { font-family: initial !important; }
        .variation-name { font-family: auto; }
        span#itemTotal { font-family: auto; }

        /* Footer */
        .footer-landing {
            background: #f5f5f5;
            padding: 15px 0;
            border-top: 1px solid #e0e0e0;
            font-family: var(--website-font-family);
        }
        
        .footer-landing a{
            text-decoration: none;
            color: #373737ff;
        }

        .footer-landing .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between !important;
            align-items: center;
            padding: 0 10px;
        }

        .copyright-text {
            font-size: 14px;
            color: #666;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 20px;
        }

        .nav-link {
            color: #2e7d32;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #1b5e20;
            text-decoration: underline;
        }

        .developer-credit {
            margin-left: 8px;
            color: #2e7d32;
        }
        .developer-credit a { color: #2e7d32; }

        @media (max-width: 768px) {
            .footer-landing .header-container {
                flex-direction: column;
                gap: 1px;
                text-align: center;
                padding: 0px 5px;
            }
            .footer-landing {
                padding: 8px 0;
                padding-bottom: 62px;
                margin-bottom: 13px;
            }
            .nav-menu {
                width: 100%;
                justify-content: center;
                gap: 12px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const landingId = {{ $landingPage->id }};
                const key = 'lp_seen_' + landingId;
                const ttlMs = 24 * 60 * 60 * 1000; // 24h
                const now = Date.now();
                const last = parseInt(localStorage.getItem(key) || '0', 10);
                const isUnique = !last || (now - last) > ttlMs;

                localStorage.setItem(key, now.toString());

                const url = "{{ route('landing-pages.view', $landingPage) }}";
                const payload = new FormData();
                payload.append('_token', '{{ csrf_token() }}');
                payload.append('unique', isUnique ? '1' : '0');

                if (navigator.sendBeacon) {
                    navigator.sendBeacon(url, payload);
                } else {
                    fetch(url, {
                        method: 'POST',
                        body: payload,
                        credentials: 'same-origin'
                    });
                }
            } catch (e) {
                // Silent fail: view tracking should never block UX
            }
        });
    </script>
</body>

</html>
