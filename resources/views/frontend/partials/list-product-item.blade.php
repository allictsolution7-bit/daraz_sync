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
    class="block group hover:no-underline transition-all">
    <div class="product-list-sidebar-item flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-50 border border-transparent hover:border-slate-200 transition-all">
        <div class="product-list-sidebar-img relative w-14 h-14 rounded-lg overflow-hidden bg-slate-100 shrink-0 border border-slate-200">
            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" src="{{ asset('storage/' . $product->thumb_image) }}"
                alt="{{ $product->title }}">
            @if ($product->offer && $product->old_price && $product->old_price > $product->offer)
            @php
                $itemDiscount = round((($product->old_price - $product->offer) / $product->old_price) * 100);
            @endphp
            <span class="absolute top-0.5 left-0.5 bg-rose-500 text-white text-[9px] font-black px-1 rounded-sm uppercase tracking-tighter">
                {{ $itemDiscount }}% OFF
            </span>
            @endif
        </div>
        <div class="product-list-sidebar-info flex-1 min-w-0">
            @if ($product->category)
            <div class="text-[10px] font-bold text-energy-orange uppercase tracking-wider mb-0.5 truncate">
                {{ $product->category->name }}
            </div>
            @endif
            <div class="product-list-sidebar-title text-xs font-bold text-slate-800 group-hover:text-navy-deep transition-colors truncate">{{ $product->title }}</div>
            <div class="product-list-sidebar-pricing flex items-baseline gap-1.5 mt-0.5">
                @if ($displayPrice)
                    <span class="product-list-sidebar-price text-sm font-extrabold text-navy-deep">৳{{ $displayPrice }}</span>
                    @if ($displayOldPrice)
                        <span class="product-list-sidebar-oldprice text-xs text-slate-400 line-through">৳{{ $displayOldPrice }}</span>
                    @endif
                @else
                    <span class="product-list-sidebar-price text-sm font-extrabold text-navy-deep">৳0</span>
                @endif
            </div>
            @if ($reviewCount > 0)
                <div class="product-list-sidebar-rating flex items-center gap-1 mt-0.5">
                    <span class="product-list-sidebar-stars flex items-center text-amber-400 text-[10px]">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $averageRating)
                            <i class="fa-solid fa-star text-[10px]"></i>
                            @else
                            <i class="fa-regular fa-star text-slate-300 text-[10px]"></i>
                            @endif
                        @endfor
                    </span>
                    <span class="product-list-sidebar-rating-value text-[10px] text-slate-500 font-medium">
                        {{ number_format($averageRating, 1) }} ({{ $reviewCount }})
                    </span>
                </div>
            @endif
        </div>
    </div>
</a> 