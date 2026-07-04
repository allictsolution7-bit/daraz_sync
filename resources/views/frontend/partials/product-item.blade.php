@php
    // Use the actual slug from database, fallback to generated slug if not available
    $slug = $product->slug ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $product->title)));
    
    // Get badge settings from ViewComposer (cached, no DB call)
    $badgeType = $productSettings['badge_type'] ?? 'starburst';
    $defaultBadgeText = $productSettings['default_badge_text'] ?? '১০% ছাড়';
    
    // Use pre-calculated discount percentage (no calculation needed!)
    $percentOff = $product->discount_percentage;
@endphp
<a href="{{ route('product.single', ['slug' => $slug, 'id' => $product->id]) }}"
    class="product-card" data-product-id="{{ $product->id }}">

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
        <div class="product-image position-relative">
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
                style="display:block;width:100%;height:100%;object-fit:cover;transition:opacity 0.3s ease;opacity:0;">
        </div>
    </div>

    <div class="product-info">

        @if(($productSettings['show_product_title'] ?? '1') == '1')
            <h3 class="product-title">{{ $product->title }}</h3>
        @endif

        {{-- Product Rating Display --}}
        @if(($productSettings['show_product_rating'] ?? '1') == '1')
            @php
                // Use eager-loaded data (already loaded via scope, no query here!)
                $averageRating = $product->active_reviews_avg_rating ?? 0;
                $reviewCount = $product->active_reviews_count ?? 0;
            @endphp
            @if ($reviewCount > 0)
                <div class="product-card-rating">
                    <div class="rating-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $averageRating)
                                <svg class="star-icon filled" viewBox="0 0 24 24" width="14" height="14">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                                        fill="#f59e0b" />
                                </svg>
                            @elseif($i <= $averageRating + 0.5)
                                <svg class="star-icon half-filled" viewBox="0 0 24 24" width="14" height="14">
                                    <defs>
                                        <linearGradient id="halfStarCard{{ $product->id }}{{ $i }}">
                                            <stop offset="50%" stop-color="#f59e0b" />
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
                        // Use pre-calculated price range (no loops needed!)
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

            @if(($productSettings['show_product_button'] ?? '1') == '1')
            <div class="item-action">
                @php
                    // Use cached settings from ViewComposer (no DB calls!)
                    $showViewButton = ($productSettings['show_view_product_button'] ?? '1') == '1';
                    $showBuyNowButton = ($productSettings['show_buy_now_button'] ?? '0') == '1';
                    $showCartIcon = ($productSettings['show_quick_cart_icon'] ?? '1') == '1';
                    $hasButtons = $showViewButton || $showBuyNowButton;
                @endphp
                
                @if($hasButtons)
                    @php
                        $buttonCount = ($showViewButton ? 1 : 0) + ($showBuyNowButton ? 1 : 0);
                        $hasTwoButtons = $buttonCount == 2;
                        $hasOneButton = $buttonCount == 1;
                    @endphp
                    
                    @if($hasTwoButtons && $showCartIcon)
                        {{-- Two buttons + cart: Buttons stacked vertically, cart on the side --}}
                        <div class="button-row-two-buttons">
                            <div class="buttons-column">
                                @if($showViewButton)
                                    <button class="add-to-cart-btn">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="cart-details-icon">
                                            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                        </svg>
                                        {{ $buttonText ?? ($productSettings['product_button_text'] ?? 'View Product') }}
                                    </button>
                                @endif
                                
                                @if($showBuyNowButton)
                                    @php
                                        $buyNowText = $productSettings['buy_now_button_text'] ?? 'Buy Now';
                                        $productPrice = $product->product_type === 'variable' ? ($minOfferPrice ?? $product->offer ?? $product->old_price) : ($product->offer ?? $product->old_price);
                                        $combinationId = $product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty() ? $product->variationCombinations->first()->id : '';
                                    @endphp
                                    
                                    <form action="{{ route('buy.store.post') }}" method="POST" class="buy-now-form" onsubmit="return handleDirectBuySubmit(this, event)">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="price" value="{{ $productPrice }}">
                                        <input type="hidden" name="main_price" value="{{ $productPrice }}">
                                        @if($product->product_type === 'variable' && $combinationId)
                                            <input type="hidden" name="combination_id" value="{{ $combinationId }}">
                                        @endif
                                        <button type="submit" class="buy-now-btn">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M7 2v11h3v9l7-12h-4l4-8z" />
                                            </svg>
                                            {{ $buyNowText }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                            
                            <div class="quickitemcart" data-product-id="{{ $product->id }}"
                                data-product-type="{{ $product->product_type }}">
                                <div class="item-cart-icon add-to-cart-quick" style="cursor:pointer;">
                                    <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z"></path>
                                    </svg>
                                    <!-- Simple Checkmark -->
                                    <div class="simple-check">✓</div>
                                </div>
                                @if (
                                    $product->product_type === 'variable' &&
                                        !empty($product->variationCombinations) &&
                                        $product->variationCombinations->isNotEmpty())
                                    <input type="hidden" name="combination_id"
                                        value="{{ $product->variationCombinations->first()->id }}">
                                @endif
                            </div>
                        </div>
                    @elseif($hasOneButton && $showCartIcon)
                        {{-- One button + cart: Side by side layout --}}
                        <div class="button-row-single">
                            @if($showViewButton)
                                <button class="add-to-cart-btn">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="cart-details-icon">>
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                    {{ $buttonText ?? ($productSettings['product_button_text'] ?? 'View Product') }}
                                </button>
                            @endif
                            
                            @if($showBuyNowButton)
                                @php
                                    $buyNowText = $productSettings['buy_now_button_text'] ?? 'Buy Now';
                                    $productPrice = $product->product_type === 'variable' ? ($minOfferPrice ?? $product->offer ?? $product->old_price) : ($product->offer ?? $product->old_price);
                                    $combinationId = $product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty() ? $product->variationCombinations->first()->id : '';
                                @endphp
                                
                                <form action="{{ route('buy.store.post') }}" method="POST" class="buy-now-form" onsubmit="return handleDirectBuySubmit(this, event)">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="price" value="{{ $productPrice }}">
                                    <input type="hidden" name="main_price" value="{{ $productPrice }}">
                                    @if($product->product_type === 'variable' && $combinationId)
                                        <input type="hidden" name="combination_id" value="{{ $combinationId }}">
                                    @endif
                                    <button type="submit" class="buy-now-btn">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M7 2v11h3v9l7-12h-4l4-8z" />
                                        </svg>
                                        {{ $buyNowText }}
                                    </button>
                                </form>
                            @endif
                            
                            <div class="quickitemcart" data-product-id="{{ $product->id }}"
                                data-product-type="{{ $product->product_type }}">
                                <div class="item-cart-icon add-to-cart-quick" style="cursor:pointer;">
                                    <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z"></path>
                                    </svg>
                                    <!-- Simple Checkmark -->
                                    <div class="simple-check">✓</div>
                                </div>
                                @if (
                                    $product->product_type === 'variable' &&
                                        !empty($product->variationCombinations) &&
                                        $product->variationCombinations->isNotEmpty())
                                    <input type="hidden" name="combination_id"
                                        value="{{ $product->variationCombinations->first()->id }}">
                                @endif
                            </div>
                        </div>
                    @else
                        {{-- Buttons without cart OR two buttons without cart --}}
                        <div class="buttons-only">
                            @if($showViewButton)
                                <button class="add-to-cart-btn">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" class="cart-details-icon">>
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                    </svg>
                                    {{ $buttonText ?? ($productSettings['product_button_text'] ?? 'View Product') }}
                                </button>
                            @endif
                            
                            @if($showBuyNowButton)
                                @php
                                    $buyNowText = $productSettings['buy_now_button_text'] ?? 'Buy Now';
                                    $productPrice = $product->product_type === 'variable' ? ($minOfferPrice ?? $product->offer ?? $product->old_price) : ($product->offer ?? $product->old_price);
                                    $combinationId = $product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty() ? $product->variationCombinations->first()->id : '';
                                @endphp
                                
                                <form action="{{ route('buy.store.post') }}" method="POST" class="buy-now-form" onsubmit="return handleDirectBuySubmit(this, event)">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="price" value="{{ $productPrice }}">
                                    <input type="hidden" name="main_price" value="{{ $productPrice }}">
                                    @if($product->product_type === 'variable' && $combinationId)
                                        <input type="hidden" name="combination_id" value="{{ $combinationId }}">
                                    @endif
                                    <button type="submit" class="buy-now-btn">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M7 2v11h3v9l7-12h-4l4-8z" />
                                        </svg>
                                        {{ $buyNowText }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                @elseif($showCartIcon)
                    {{-- Only cart icon, no buttons --}}
                    <div class="quickitemcart" data-product-id="{{ $product->id }}"
                        data-product-type="{{ $product->product_type }}">
                        <div class="item-cart-icon add-to-cart-quick" style="cursor:pointer;">
                            <svg class="web-primary-color" width="21" height="21" viewBox="0 0 21 21" fill="none" 
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M20.8256 4.51906C20.6831 4.34851 20.4723 4.24996 20.25 4.25H5.12625L4.66781 1.73187C4.53823 1.01862 3.91711 0.500105 3.19219 0.5H1.5C1.08579 0.5 0.75 0.835786 0.75 1.25C0.75 1.66421 1.08579 2 1.5 2H3.1875L5.58375 15.1522C5.65434 15.5422 5.82671 15.9067 6.08344 16.2087C5.09996 17.1273 4.97046 18.6409 5.7836 19.7132C6.59675 20.7855 8.08911 21.0692 9.23899 20.37C10.3889 19.6709 10.8238 18.2154 10.2459 17H14.5041C14.3363 17.3513 14.2495 17.7357 14.25 18.125C14.25 19.5747 15.4253 20.75 16.875 20.75C18.3247 20.75 19.5 19.5747 19.5 18.125C19.5 16.6753 18.3247 15.5 16.875 15.5H7.79719C7.43472 15.4999 7.12417 15.2407 7.05937 14.8841L6.76219 13.25H17.6372C18.7246 13.2498 19.6563 12.4721 19.8506 11.4022L20.9906 5.13406C21.0297 4.91473 20.9692 4.68938 20.8256 4.51906V4.51906ZM9 18.125C9 18.7463 8.49632 19.25 7.875 19.25C7.25368 19.25 6.75 18.7463 6.75 18.125C6.75 17.5037 7.25368 17 7.875 17C8.49632 17 9 17.5037 9 18.125V18.125ZM18 18.125C18 18.7463 17.4963 19.25 16.875 19.25C16.2537 19.25 15.75 18.7463 15.75 18.125C15.75 17.5037 16.2537 17 16.875 17C17.4963 17 18 17.5037 18 18.125V18.125ZM18.375 11.1341C18.31 11.4917 17.9979 11.7513 17.6344 11.75H6.48938L5.39906 5.75H19.3509L18.375 11.1341Z"></path>
                            </svg>
                            <!-- Simple Checkmark -->
                            <div class="simple-check">✓</div>
                        </div>
                        @if (
                            $product->product_type === 'variable' &&
                                !empty($product->variationCombinations) &&
                                $product->variationCombinations->isNotEmpty())
                            <input type="hidden" name="combination_id"
                                value="{{ $product->variationCombinations->first()->id }}">
                        @endif
                    </div>
                @endif
            </div>
            @endif

        </div>

    </div>
</a>

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

{{-- Optimized: Only essential styles for this component --}}
<style>
    /* Button colors - moved to app.blade.php for performance */
    .add-to-cart-btn { background: {{ $productSettings['view_product_button_bg_color'] ?? 'transparent' }}; color: {{ $productSettings['view_product_button_text_color'] ?? 'var(--secondary-color)' }}; }
    .buy-now-btn { background: {{ $productSettings['buy_now_button_bg_color'] ?? '#2ecc71' }}; color: {{ $productSettings['buy_now_button_text_color'] ?? '#ffffff' }}; }
    
    /* Layout styles - only for this component */
    .button-row-two-buttons { display: flex; align-items: center; gap: 5px; width: 100%; }
    .button-row-two-buttons .buttons-column { flex: 1; display: flex; flex-direction: column; gap: 5px; }
    .button-row-two-buttons .buttons-column .add-to-cart-btn,
    .button-row-two-buttons .buttons-column .buy-now-btn,
    .button-row-two-buttons .buttons-column .buy-now-form { width: 100%; margin: 0; }
    .button-row-two-buttons .quickitemcart { flex-shrink: 0; margin: 0; position: absolute; top: 10px; right: 10px;}
    
    .button-row-single { display: flex; align-items: center; gap: 5px; width: 100%; }
    .button-row-single .add-to-cart-btn,
    .button-row-single .buy-now-btn,
    .button-row-single .buy-now-form { flex: 1; margin: 0; }
    .button-row-single .quickitemcart { flex-shrink: 0; margin: 0; }
    
    .buttons-only { display: flex; flex-direction: column; gap: 4px; width: 100%; }
    .buttons-only .add-to-cart-btn,
    .buttons-only .buy-now-btn,
    .buttons-only .buy-now-form { width: 100%; margin: 0; }
    
    .item-action { display: flex; flex-direction: column; align-items: stretch; gap: 8px; }
    .item-action .quickitemcart:only-child { align-self: center; }
    
    .cart-details-icon { margin-right: 4px; }
</style>

{{-- Buy Now JavaScript (Direct Buy Only) --}}
<script>
    // Global function to handle DIRECT buy now (form submission to buynow.blade.php)
    function handleDirectBuySubmit(form, event) {
        // Don't prevent default - let the form submit normally
        // Just add a loading state to the button
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<span>Loading...</span>';
        }
        // Allow form to submit normally
        return true;
    }
</script>
