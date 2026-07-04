@if ($section && $section->status)
@php
    $carouselSlides = collect($section->carousel_images ?? [])->filter(fn($slide) => !empty($slide['image']))->values();
@endphp
@if ($carouselSlides->count() > 0)
@once
<style>
    .image-carousel-section { padding: 20px 12px; background: #f8fafc; }
    .image-carousel-container { max-width: 1180px; margin: 0 auto; }
    .image-carousel-header { text-align: center; margin-bottom: 18px; }
    .image-carousel-title { font-size: 30px; font-weight: 800; color: var(--primary-color); letter-spacing: -0.5px; }
    .image-carousel-description { margin-top: 6px; color: #475467; font-size: 16px; line-height: 1.6; }
    .image-carousel { position: relative; }
    .image-carousel .swiper-slide { display: flex; justify-content: center; flex: 0 0 auto; }
    .carousel-card { background: #ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); border: 1px solid rgba(0, 123, 255, 0.06); transition: transform 0.25s ease, box-shadow 0.25s ease; position: relative; }
    .carousel-card:hover { transform: translateY(-3px); box-shadow: 0 14px 35px rgba(0, 0, 0, 0.12); }
    .carousel-card img { width: 100%; height: auto; display: block; }
    .carousel-caption { padding: 10px 12px; font-weight: 700; color: #1f2937; background: linear-gradient(135deg, rgba(0, 123, 255, 0.08), rgba(220, 53, 69, 0.08)); border-top: 1px solid rgba(0, 0, 0, 0.05); }
    .image-carousel .swiper-button-next, .image-carousel .swiper-button-prev { color: var(--primary-color); background: #ffffff; width: 32px; height: 32px; border-radius: 50%; box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15); border: 1px solid rgba(0, 0, 0, 0.06); }
    .image-carousel .swiper-button-next::after, .image-carousel .swiper-button-prev::after { font-size: 16px; font-weight: 700; }
    .image-carousel .swiper-pagination-bullet { background: rgba(0, 123, 255, 0.4); }
    .image-carousel .swiper-pagination-bullet-active { background: var(--primary-color); }
    .image-carousel:not(.swiper-initialized) .swiper-wrapper { display: grid !important; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; }
    @media (min-width: 1200px) { .image-carousel:not(.swiper-initialized) .swiper-wrapper { grid-template-columns: repeat(5, 1fr); } }
    @media (max-width: 768px) { .image-carousel-title { font-size: 24px; } .carousel-card { border-radius: 10px; } }
    .image-carousel-thumbs { margin-top: 12px; padding: 6px 4px; gap: 3px; }
    .image-carousel-thumbs .swiper-slide { width: auto; flex: 0 0 auto; opacity: 0.6; transition: opacity 0.2s ease, transform 0.2s ease; }
    .image-carousel-thumbs .swiper-slide-thumb-active { opacity: 1; transform: translateY(-2px); }
    .thumb-card { width: 80px; height: 60px; border-radius: 8px; overflow: hidden; border: 2px solid rgba(0, 123, 255, 0.12); box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); }
    .thumb-card img { width: 100%; height: 100%; object-fit: cover; display: block; }
    @media (min-width: 1024px) { .image-carousel-thumbs { display: none !important; } }
    .carousel-zoom-btn { position: absolute; top: 10px; right: 10px; background: rgba(0, 0, 0, 0.55); color: #fff; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; text-decoration: none; transition: background 0.2s ease, transform 0.2s ease; border: 1px solid rgba(255, 255, 255, 0.25); cursor: pointer; }
    .carousel-zoom-btn:hover { background: rgba(0, 0, 0, 0.7); transform: translateY(-1px); }
    .lightbox-open { overflow: hidden; }
    .lightbox-backdrop { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.75); display: none; align-items: center; justify-content: center; z-index: 9999; padding: 20px; }
    .lightbox-backdrop.active { display: flex; }
    .lightbox-content { position: relative; max-width: 90vw; max-height: 90vh; background: #0b0b0b; border-radius: 10px; overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45); }
    .lightbox-content img { max-width: 100%; max-height: 90vh; display: block; object-fit: contain; }
    .lightbox-close { position: absolute; top: 8px; right: 8px; background: rgba(0, 0, 0, 0.7); color: #fff; border: none; width: 34px; height: 34px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; font-size: 16px; }
    .lightbox-close:hover { background: rgba(0, 0, 0, 0.85); }
</style>
@endonce

<section class="image-carousel-section">
    <div class="image-carousel-container">
        <div class="image-carousel-header">
            @if ($section->title)
                <h2 class="image-carousel-title">{{ $section->title }}</h2>
            @endif
            @if ($section->description)
                <p class="image-carousel-description">{{ $section->description }}</p>
            @endif
        </div>
        <div class="swiper image-carousel" data-carousel-id="{{ $section->id }}">
            <div class="swiper-wrapper">
                @foreach ($carouselSlides as $slide)
                    <div class="swiper-slide">
                        <div class="carousel-card">
                            <img src="{{ asset($slide['image']) }}"
                                alt="{{ $slide['caption'] ?? $section->title ?? 'Gallery image' }}" loading="lazy">
                            <button type="button" class="carousel-zoom-btn" data-image="{{ asset($slide['image']) }}" aria-label="View full size">⤢</button>
                            @if (!empty($slide['caption']))
                                <div class="carousel-caption">{{ $slide['caption'] }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="swiper image-carousel-thumbs" data-carousel-id="{{ $section->id }}">
            <div class="swiper-wrapper">
                @foreach ($carouselSlides as $slide)
                    <div class="swiper-slide">
                        <div class="thumb-card">
                            <img src="{{ asset($slide['image']) }}" alt="thumb" loading="lazy">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@once
    <div id="carousel-lightbox" class="lightbox-backdrop" aria-modal="true" role="dialog">
        <div class="lightbox-content">
            <button type="button" class="lightbox-close" aria-label="Close">✕</button>
            <img id="lightbox-image" src="" alt="Full size image">
        </div>
    </div>
@endonce
@endif
@endif
