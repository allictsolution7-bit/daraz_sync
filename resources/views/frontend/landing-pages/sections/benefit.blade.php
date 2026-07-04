<style>
    /* Features Section */
    .honey-features {
        padding: 30px 20px;
        background-color: #fff8e1;
    }

    .features-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .features-title {
        text-align: center;
        font-size: 36px;
        color: #5d4037;
        margin-bottom: 20px;
        font-family: inherit;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .feature-card {
        background-color: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        font-size: 40px;
        margin-bottom: 10px;
    }

    .feature-name {
        font-size: 22px;
        color: var(--secondary-color);
        margin-bottom: 15px;
        font-family: inherit;
    }

    .feature-desc {
        color: var(--secondary-color);
        line-height: 1.6;
        font-family: inherit;
    }

    .product-cta2 {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 30px;
    }
</style>

@if ($section && $section->status)
@php
    $benefits = $section->benefits;
    $benefitsCount = is_array($benefits) ? count($benefits) : 0;
@endphp
@if ($benefitsCount > 0)
<section class="honey-features">
    <div class="features-container">
        <h2 class="features-title">{{ $section->benefit_title ?? 'পণ্যের উপকারিতা' }}</h2>
        <div class="features-grid">
            @foreach ($benefits as $benefit)
                <div class="feature-card">
                    @if (!empty($benefit['icon']))
                        <div class="feature-icon">{{ $benefit['icon'] }}</div>
                    @endif
                    @if (!empty($benefit['title']))
                        <h3 class="feature-name">{{ $benefit['title'] }}</h3>
                    @endif
                    @if (!empty($benefit['description']))
                        <p class="feature-desc">{{ $benefit['description'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endif
