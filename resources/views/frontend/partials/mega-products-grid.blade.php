@php
    use Illuminate\Support\Str;
@endphp
@forelse ($products as $product)
    @php
        // Normalize thumb path
        $thumb = $product->thumb_image ?? '';
        $thumbUrl = $thumb;
        if (!Str::startsWith($thumb, ['http://', 'https://'])) {
            $thumbUrl = Str::startsWith($thumb, ['storage/', '/storage/']) ? $thumb : 'storage/' . ltrim($thumb, '/');
            $thumbUrl = asset($thumbUrl);
        }

        // Price calculation (simple + variable)
        $displayPrice = '';
        $displayOldPrice = '';
        $discountPct = 0;

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
                        $discountPct = round((($minRegularPrice - $minOfferPrice) / $minRegularPrice) * 100);
                    }
                }
            }
        } else {
            if ($product->offer && $product->offer > 0) {
                $displayPrice = number_format($product->offer);
                if ($product->old_price && $product->old_price > $product->offer) {
                    $displayOldPrice = number_format($product->old_price);
                    $discountPct = round((($product->old_price - $product->offer) / $product->old_price) * 100);
                }
            } elseif ($product->old_price && $product->old_price > 0) {
                $displayPrice = number_format($product->old_price);
            }
        }
    @endphp
    <a href="{{ route('product.single', ['id' => $product->id, 'slug' => $product->slug]) }}"
        class="mega-product-card">
        <div class="mega-product-img">
            <img src="{{ $thumbUrl }}" alt="{{ $product->title }}" loading="lazy">
            @if($discountPct > 0)
                <span class="mega-card-discount-badge">-{{ $discountPct }}%</span>
            @endif
        </div>
        <div class="mega-product-info">
            <div class="mega-product-title" title="{{ $product->title }}">{{ $product->title }}</div>
            <div class="mega-product-price-row">
                @if ($displayPrice)
                    <span class="mega-product-price">৳{{ $displayPrice }}</span>
                @else
                    <span class="mega-product-price">৳0</span>
                @endif
                @if ($displayOldPrice)
                    <span class="mega-product-oldprice">৳{{ $displayOldPrice }}</span>
                @endif
            </div>
        </div>
    </a>
@empty
    <div class="mega-loading">No products found in this category.</div>
@endforelse
