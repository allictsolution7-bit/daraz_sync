@php
$websiteFont = setting('general', 'website_font', 'hind-siliguri');
$websiteFontFamilyMap = [
    'hind-siliguri' => 'Hind Siliguri',
    'inter' => 'Inter',
    'noto-sans-bengali' => 'Noto Sans Bengali',
];
$websiteFontFamily = $websiteFontFamilyMap[$websiteFont] ?? 'Hind Siliguri';
@endphp

{{-- Inline Font CSS to Eliminate Render-Blocking Requests --}}
<style>
@if($websiteFont === 'hind-siliguri')
    {{-- Hind Siliguri Font Faces - Reduced weights + unicode-range for Bengali only --}}
    @font-face {
        font-family: 'Hind Siliguri';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url('/fonts/hind-siliguri/hind-siliguri-v13-bengali-regular.woff2') format('woff2');
        unicode-range: U+0980-09FF, U+200C-200D;
    }
    
    @font-face {
        font-family: 'Hind Siliguri';
        font-style: normal;
        font-weight: 600;
        font-display: swap;
        src: url('/fonts/hind-siliguri/hind-siliguri-v13-bengali-600.woff2') format('woff2');
        unicode-range: U+0980-09FF, U+200C-200D;
    }
@elseif($websiteFont === 'inter')
    {{-- Inter Font Faces - Inlined for Performance --}}
    @font-face {
        font-family: 'Inter';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url('/fonts/inter/Inter-Regular.woff2') format('woff2');
    }
    
    @font-face {
        font-family: 'Inter';
        font-style: normal;
        font-weight: 500;
        font-display: swap;
        src: url('/fonts/inter/Inter-Medium.woff2') format('woff2');
    }
    
    @font-face {
        font-family: 'Inter';
        font-style: normal;
        font-weight: 600;
        font-display: swap;
        src: url('/fonts/inter/Inter-SemiBold.woff2') format('woff2');
    }
    
    @font-face {
        font-family: 'Inter';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url('/fonts/inter/Inter-Bold.woff2') format('woff2');
    }
@elseif($websiteFont === 'noto-sans-bengali')
    {{-- Noto Sans Bengali Font Faces - Bengali subset only --}}
    @font-face {
        font-family: 'Noto Sans Bengali';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url('/fonts/noto-sans-bengali/noto-sans-bengali-v32-bengali.woff2') format('woff2');
        unicode-range: U+0951-0952, U+0964-0965, U+0980-09FE, U+1CD0, U+1CD2, U+1CD5-1CD6, U+1CD8, U+1CE1, U+1CEA, U+1CED, U+1CF2, U+1CF5-1CF7, U+200C-200D, U+20B9, U+25CC, U+A8F1;
    }

    @font-face {
        font-family: 'Noto Sans Bengali';
        font-style: normal;
        font-weight: 600;
        font-display: swap;
        src: url('/fonts/noto-sans-bengali/noto-sans-bengali-v32-bengali.woff2') format('woff2');
        unicode-range: U+0951-0952, U+0964-0965, U+0980-09FE, U+1CD0, U+1CD2, U+1CD5-1CD6, U+1CD8, U+1CE1, U+1CEA, U+1CED, U+1CF2, U+1CF5-1CF7, U+200C-200D, U+20B9, U+25CC, U+A8F1;
    }
@endif

:root {
    --website-font-family: {{ $websiteFontFamily }}, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
}
html { font-family: var(--website-font-family); }
body { font-family: inherit; }
</style>

{{-- Preload only the single critical weight to avoid double downloads --}}
@if($websiteFont === 'hind-siliguri')
<link rel="preload" href="/fonts/hind-siliguri/hind-siliguri-v13-bengali-regular.woff2" as="font" type="font/woff2" crossorigin>
@elseif($websiteFont === 'inter')
<link rel="preload" href="/fonts/inter/Inter-Regular.woff2" as="font" type="font/woff2" crossorigin>
@elseif($websiteFont === 'noto-sans-bengali')
<link rel="preload" href="/fonts/noto-sans-bengali/noto-sans-bengali-v32-bengali.woff2" as="font" type="font/woff2" crossorigin>
@endif

