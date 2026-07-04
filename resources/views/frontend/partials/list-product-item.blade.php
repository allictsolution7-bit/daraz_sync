@php
    // Use eager-loaded data instead of accessors to avoid N+1 queries
    $averageRating = $product->active_reviews_avg_rating ?? 0;
    $reviewCount = $product->active_reviews_count ?? 0;
    
    // Calculate price for variable products
    $displayPrice = '';
    $displayOldPrice = '';
    
    if ($product->product_type === 'variable' && $product->variationCombinations && $product->variationCombinations->isNotEmpty()) {
        $offerPrices = [];
        $regularPrices = [];
        
        foreach ($product->variationCombinations as $combination) {
            $offerPrice = $combination->offer_price ?? $combination->regular_price ?? $combination->price ?? 0;
            $regularPrice = $combination->regular_price ?? $combination->price ?? 0;
            
            if ($offerPrice > 0) {
                $offerPrices[] = $offerPrice;
            }
            if ($regularPrice > 0) {
                $regularPrices[] = $regularPrice;
            }
        }
        
        if (!empty($offerPrices)) {
            $minOfferPrice = min($offerPrices);
            $maxOfferPrice = max($offerPrices);
            $displayPrice = $minOfferPrice === $maxOfferPrice ? number_format($minOfferPrice) : number_format($minOfferPrice) . ' - ' . number_format($maxOfferPrice);
            
            if (!empty($regularPrices)) {
                $minRegularPrice = min($regularPrices);
                $maxRegularPrice = max($regularPrices);
                if ($minRegularPrice > $minOfferPrice) {
                    $displayOldPrice = $minRegularPrice === $maxRegularPrice ? number_format($minRegularPrice) : number_format($minRegularPrice) . ' - ' . number_format($maxRegularPrice);
                }
            }
        }
    } else {
        // Simple product or fallback
        if ($product->offer && $product->offer > 0) {
            $displayPrice = number_format($product->offer);
            if ($product->old_price && $product->old_price > $product->offer) {
                $displayOldPrice = number_format($product->old_price);
            }
        } elseif ($product->old_price && $product->old_price > 0) {
            $displayPrice = number_format($product->old_price);
        }
    }
@endphp

<a href="{{ route('product.single', ['id' => $product->id, 'slug' => $product->slug]) }}"
    style="text-decoration:none; color:inherit;">
    <div class="product-list-sidebar-item">
        <div class="product-list-sidebar-img">
            <img src="{{ asset('storage/' . $product->thumb_image) }}"
                alt="{{ $product->title }}">
        </div>
        <div class="product-list-sidebar-info">
            <div class="product-list-sidebar-title">{{ $product->title }}</div>
            <div class="product-list-sidebar-author">
                {{ $product->author ?? '' }}
            </div>
            <div class="product-list-sidebar-pricing">
                @if ($displayPrice)
                    <span class="product-list-sidebar-price">৳{{ $displayPrice }}</span>
                    @if ($displayOldPrice)
                        <span class="product-list-sidebar-oldprice">৳{{ $displayOldPrice }}</span>
                    @endif
                @else
                    <span class="product-list-sidebar-price">৳0</span>
                @endif
            </div>
            @if ($reviewCount > 0)
                <div class="product-list-sidebar-rating">
                    <span class="product-list-sidebar-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $averageRating)
                                <svg class="star-icon filled" viewBox="0 0 24 24" width="12" height="12">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="#fbbf24"/>
                                </svg>
                            @elseif($i <= $averageRating + 0.5)
                                <svg class="star-icon half-filled" viewBox="0 0 24 24" width="12" height="12">
                                    <defs>
                                        <linearGradient id="halfStarSidebar{{ $product->id }}{{ $i }}">
                                            <stop offset="50%" stop-color="#fbbf24"/>
                                            <stop offset="50%" stop-color="#e5e7eb"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="url(#halfStarSidebar{{ $product->id }}{{ $i }})"/>
                                </svg>
                            @else
                                <svg class="star-icon empty" viewBox="0 0 24 24" width="12" height="12">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="#e5e7eb"/>
                                </svg>
                            @endif
                        @endfor
                    </span>
                    <span class="product-list-sidebar-rating-value">
                        {{ number_format($averageRating, 1) }}({{ $reviewCount }})
                    </span>
                </div>
            @endif
        </div>
    </div>
</a> 