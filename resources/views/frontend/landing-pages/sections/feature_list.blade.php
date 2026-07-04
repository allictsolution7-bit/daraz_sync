@if ($section && $section->status)
@php
    $featureItems = $section->feature_items;
    $featureItemsCount = is_array($featureItems) ? count($featureItems) : 0;
@endphp
<style>
    .feature-list-section {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
        margin-bottom: 10px;
    }

    .feature-list-header {
        background: var(--secondary-color);
        padding: 25px 30px;
        text-align: center;
    }

    .feature-list-title {
        color: white;
        font-size: 28px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }
    .feature-list-content {
        padding: 20px 25px;
    }
    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .feature-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .feature-item:last-child {
        border-bottom: none;
    }
    .feature-icon {
        font-size: 20px;
    }
    .feature-text {
        font-size: 16px;
        color: #4a4a4a;
    }
            /* Responsive design */
        @media (max-width: 768px) {
            .feature-list-section {
                max-width: 100%;
                margin: 0 10px;
                margin-top: 10px;
            }

            .feature-list-title {
                font-size: 20px;
            }

            .feature-list-content {
                padding: 30px 20px;
            }

            .feature-text {
                font-size: 17px;
                margin-top: -5px;
            }

            .feature-item {
                margin-bottom: 0px;
                padding: 2px 0;
            }
        }

        @media (max-width: 480px) {
            .feature-list-header {
                padding: 20px 15px;
            }

            .feature-list-title {
                font-size: 18px;
            }

            .feature-list-content {
                padding: 15px 10px;
            }

            .feature-text {
                font-size: 16px;
                margin-top: -5px;
            }

            .feature-icon {
                width: 20px;
                height: 20px;
                margin-right: 12px;
                font-size: 20px;
            }
        }
</style>

<section class="feature-list-section">
    <div class="feature-list-header">
        <h2 class="feature-list-title">
            {{ $section->feature_list_title ?? 'Feature List' }}</h2>
    </div>

    <div class="feature-list-content">
        @if ($featureItems && is_array($featureItems) && $featureItemsCount > 0)
            <ul class="feature-list">
                @foreach ($featureItems as $item)
                    <li class="feature-item">
                        <div class="feature-icon">{{ $item['emoji'] ?? '•' }}</div>
                        <span class="feature-text">{{ $item['text'] ?? '' }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
@endif
