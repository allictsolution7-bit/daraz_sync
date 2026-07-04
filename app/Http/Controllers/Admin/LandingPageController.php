<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\LandingPageSection;
use App\Models\Product;
use App\Models\BasicShippingSetting;
use App\Models\SiteSetting;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $landingPages = LandingPage::with(['product', 'creator'])
            ->withCount('orders')
            ->withSum('orders as orders_total_amount', 'total')
            ->orderBy('position')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $landingOrdersCount = order::whereNotNull('landing_page_id')->count();
        $landingOrdersTotal = order::whereNotNull('landing_page_id')->sum('total');
        $landingViewsTotal = LandingPage::sum('views_total');
        $landingViewsUnique = LandingPage::sum('views_unique');

        return view('admin.landing-pages.index', compact(
            'landingPages',
            'landingOrdersCount',
            'landingOrdersTotal',
            'landingViewsTotal',
            'landingViewsUnique'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('status', true)->get();
        return view('admin.landing-pages.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
            'title' => 'required|string|max:255',
            'order_button_text' => 'nullable|string|max:255',
            'order_form_title' => 'nullable|string|max:255',
            'order_place_button_text' => 'nullable|string|max:255',
            'order_button_url' => 'nullable|string|max:255',
            'product_id' => 'required|exists:products,id',
            'status' => 'boolean',
            'position' => 'nullable|integer|min:0',
            // Validation for sections
            'sections.*.section_type' => 'required|in:hero,benefit,testimonials,feature_list,pricing,countdown,video,single_image,call_to_action,header,image_carousel',
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.description' => 'nullable|string',
            'sections.*.icon' => 'nullable|string|max:255',
            'sections.*.reviewer_name' => 'nullable|string|max:255',
            'sections.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections.*.image_alt' => 'nullable|string|max:255',
            'sections.*.carousel_images.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'sections.*.carousel_images.*.caption' => 'nullable|string|max:255',
            'sections.*.background_color' => 'nullable|string|max:7',
            'sections.*.text_color' => 'nullable|string|max:7',
            'sections.*.position' => 'nullable|integer|min:0',
            'sections.*.status' => 'boolean',
            'sections.*.countdown_hours' => 'nullable|integer|min:1|max:999',
            'sections.*.countdown_repeat' => 'nullable|boolean',
            'sections.*.show_trust_indicators' => 'nullable|boolean',
            'sections.*.trust_indicators.*.icon' => 'nullable|string|max:10',
            'sections.*.trust_indicators.*.text1' => 'nullable|string|max:50',
            'sections.*.trust_indicators.*.text2' => 'nullable|string|max:100',
            // Validation for testimonials
            'sections.*.testimonials.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections.*.testimonials.*.text' => 'nullable|string',
            'sections.*.testimonials.*.author' => 'nullable|string|max:255',
            'sections.*.testimonials.*.rating' => 'nullable|integer|min:1|max:5',
            // Validation for feature_list items
            'sections.*.feature_items.*.emoji' => 'nullable|string|max:10',
            'sections.*.feature_items.*.text' => 'nullable|string',
            'sections.*.feature_items.*.is_positive' => 'boolean',
            // Validation for pricing variants
            'sections.*.pricing_variants.*.weight' => 'nullable|string|max:255',
            'sections.*.pricing_variants.*.regular_price' => 'nullable|numeric|min:0',
            'sections.*.pricing_variants.*.offer_price' => 'nullable|numeric|min:0',
            'sections.*.pricing_variants.*.delivery_text' => 'nullable|string|max:255',
            'sections.*.pricing_variants.*.delivery_icon' => 'nullable|string|max:10',
            'sections.*.pricing_variants.*.position' => 'nullable|integer|min:0',
            // Validation for hero section fields
            'sections.*.heading' => 'nullable|string|max:255',
            'sections.*.sub_heading' => 'nullable|string',
            'sections.*.primary_text' => 'nullable|string',
            'sections.*.hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections.*.hero_image_alt' => 'nullable|string|max:255',
            'sections.*.hero_video_url' => 'nullable|url|max:500',
            'sections.*.badge_text' => 'nullable|string|max:255',
            'sections.*.badge_color' => 'nullable|string|max:7',
            'sections.*.badge_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections.*.badge_desktop_width' => 'nullable|integer|min:30|max:200',
            'sections.*.badge_mobile_width' => 'nullable|integer|min:20|max:150',
            // Validation for video section fields
            'sections.*.video_url' => 'nullable|url|max:500',
            'sections.*.video_title' => 'nullable|string|max:255',
            'sections.*.video_description' => 'nullable|string',
            // Validation for call to action section fields
            'sections.*.cta_title' => 'nullable|string|max:255',
            'sections.*.cta_subtitle' => 'nullable|string|max:255',
            'sections.*.cta_button_text' => 'nullable|string|max:255',
            'sections.*.cta_phone_number' => 'nullable|string|max:20',
            'sections.*.cta_background_color' => 'nullable|string|max:7',
            'sections.*.cta_button_color' => 'nullable|string|max:7',
            // Validation for header section fields
            'sections.*.header_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections.*.header_logo_alt' => 'nullable|string|max:255',
            'sections.*.header_alignment' => 'nullable|in:flex-start,center,flex-end',
            'sections.*.header_button1_text' => 'nullable|string|max:255',
            'sections.*.header_button1_url' => 'nullable|string|max:500',
            'sections.*.header_button1_color' => 'nullable|string|max:7',
            'sections.*.header_button1_text_color' => 'nullable|string|max:7',
            'sections.*.header_button1_active' => 'nullable|boolean',
            'sections.*.header_button2_text' => 'nullable|string|max:255',
            'sections.*.header_button2_url' => 'nullable|string|max:500',
            'sections.*.header_button2_color' => 'nullable|string|max:7',
            'sections.*.header_button2_text_color' => 'nullable|string|max:7',
            'sections.*.header_button2_active' => 'nullable|boolean',
            'sections.*.header_desktop_logo_width' => 'nullable|integer|min:50|max:500',
            'sections.*.header_mobile_logo_width' => 'nullable|integer|min:30|max:300',
        ]);

        $data = $request->all();
        $data['slug'] = LandingPage::generateSlug($request->title);
        $data['created_by'] = auth()->id();

        $landingPage = LandingPage::create($data);

        // Handle sections if provided
        if ($request->has('sections')) {
            foreach ($request->sections as $sectionIndex => $sectionData) {
                if (!empty($sectionData['section_type'])) {
                    $sectionData['landing_page_id'] = $landingPage->id;
                    
                    // Check if this is an existing section (has section_id)
                    $existingSection = null;
                    if (isset($sectionData['section_id']) && !empty($sectionData['section_id'])) {
                        $existingSection = $landingPage->sections()->find($sectionData['section_id']);
                        if ($existingSection) {
                            $updatedSectionIds[] = $existingSection->id;
                        }
                    }

                    // Handle section image upload for non-testimonials sections
                    if (isset($sectionData['image']) && $sectionData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $sectionData['image']->store('landing-pages/sections', 'public');
                        $sectionData['image'] = $imagePath;
                    }

                    // Handle testimonials section differently
                    if ($sectionData['section_type'] === 'testimonials') {
                        // Prepare testimonials array
                        $testimonialsArray = [];
                        if (isset($sectionData['testimonials']) && is_array($sectionData['testimonials'])) {
                            foreach ($sectionData['testimonials'] as $testimonialIndex => $testimonialData) {
                                $testimonialImagePath = null;
                                if (isset($testimonialData['image']) && $testimonialData['image'] instanceof \Illuminate\Http\UploadedFile) {
                                    $testimonialImagePath = $testimonialData['image']->store('landing-pages/testimonials', 'public');
                                }
                                $testimonialsArray[] = [
                                    'text' => $testimonialData['text'] ?? null,
                                    'author' => $testimonialData['author'] ?? null,
                                    'image' => $testimonialImagePath,
                                    'rating' => $testimonialData['rating'] ?? null,
                                    'position' => $testimonialIndex,
                                ];
                            }
                        }

                        LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'testimonials',
                            'testimonials_title' => $sectionData['testimonials_title'] ?? 'Customer Testimonials',
                            'description' => $sectionData['description'] ?? null,
                            'background_color' => $sectionData['background_color'] ?? '#ffffff',
                            'text_color' => $sectionData['text_color'] ?? '#000000',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                            'testimonials' => $testimonialsArray,
                        ]);
                    } else if ($sectionData['section_type'] === 'benefit') {
                        // Prepare benefits array
                        $benefitsArray = [];
                        if (isset($sectionData['benefits']) && is_array($sectionData['benefits'])) {
                            foreach ($sectionData['benefits'] as $benefitIndex => $benefitData) {
                                $benefitsArray[] = [
                                    'title' => $benefitData['title'] ?? null,
                                    'description' => $benefitData['description'] ?? null,
                                    'icon' => $benefitData['icon'] ?? null,
                                    'position' => $benefitIndex,
                                ];
                            }
                        }

                        LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'benefit',
                            'benefit_title' => $sectionData['benefit_title'] ?? null,
                            'description' => $sectionData['description'] ?? null,
                            'icon' => $sectionData['icon'] ?? null,
                            'background_color' => $sectionData['background_color'] ?? '#ffffff',
                            'text_color' => $sectionData['text_color'] ?? '#000000',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                            'benefits' => $benefitsArray,
                            'image' => $sectionData['image'] ?? null,
                            'image_alt' => $sectionData['image_alt'] ?? null,
                        ]);
                    } else if ($sectionData['section_type'] === 'feature_list') {
                        // Prepare feature items array
                        $featureItemsArray = [];
                        if (isset($sectionData['feature_items']) && is_array($sectionData['feature_items'])) {
                            foreach ($sectionData['feature_items'] as $itemIndex => $itemData) {
                                $featureItemsArray[] = [
                                    'emoji' => $itemData['emoji'] ?? null,
                                    'text' => $itemData['text'] ?? null,
                                    'is_positive' => isset($itemData['is_positive']) ? 1 : 0,
                                    'position' => $itemIndex,
                                ];
                            }
                        }

                        LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'feature_list',
                            'feature_list_title' => $sectionData['feature_list_title'] ?? null,
                            'description' => $sectionData['description'] ?? null,
                            'background_color' => $sectionData['background_color'] ?? '#ffffff',
                            'text_color' => $sectionData['text_color'] ?? '#000000',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                            'feature_items' => $featureItemsArray,
                        ]);
                    } else if ($sectionData['section_type'] === 'pricing') {
                        // Prepare pricing variants array
                        $pricingVariantsArray = [];
                        if (isset($sectionData['pricing_variants']) && is_array($sectionData['pricing_variants'])) {
                            foreach ($sectionData['pricing_variants'] as $variantIndex => $variantData) {
                                $pricingVariantsArray[] = [
                                    'weight' => $variantData['weight'] ?? null,
                                    'regular_price' => $variantData['regular_price'] ?? null,
                                    'offer_price' => $variantData['offer_price'] ?? null,
                                    'delivery_text' => $variantData['delivery_text'] ?? null,
                                    'delivery_icon' => $variantData['delivery_icon'] ?? null,
                                    'position' => $variantIndex,
                                ];
                            }
                        }

                        LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'pricing',
                            'pricing_title' => $sectionData['pricing_title'] ?? null,
                            'description' => $sectionData['description'] ?? null,
                            'background_color' => $sectionData['background_color'] ?? '#ffffff',
                            'text_color' => $sectionData['text_color'] ?? '#000000',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                            'pricing_variants' => $pricingVariantsArray,
                        ]);
                    } else if ($sectionData['section_type'] === 'hero') {
                        // Handle hero image upload
                        $heroImagePath = null;
                        if (isset($sectionData['hero_image']) && $sectionData['hero_image'] instanceof \Illuminate\Http\UploadedFile) {
                            $heroImagePath = $sectionData['hero_image']->store('landing-pages/hero', 'public');
                        }

                        // Handle badge image upload
                        $badgeImagePath = null;
                        if (isset($sectionData['badge_image']) && $sectionData['badge_image'] instanceof \Illuminate\Http\UploadedFile) {
                            $badgeImagePath = $sectionData['badge_image']->store('landing-pages/badge', 'public');
                        }

                        // Process trust indicators
                        $trustIndicators = [];
                        if (isset($sectionData['trust_indicators']) && is_array($sectionData['trust_indicators'])) {
                            foreach ($sectionData['trust_indicators'] as $indicator) {
                                if (!empty($indicator['icon']) || !empty($indicator['text1']) || !empty($indicator['text2'])) {
                                    $trustIndicators[] = [
                                        'icon' => $indicator['icon'] ?? '',
                                        'text1' => $indicator['text1'] ?? '',
                                        'text2' => $indicator['text2'] ?? ''
                                    ];
                                }
                            }
                        }

                        LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'hero',
                            'heading' => $sectionData['heading'] ?? null,
                            'sub_heading' => $sectionData['sub_heading'] ?? null,
                            'primary_text' => $sectionData['primary_text'] ?? null,
                            'hero_image' => $heroImagePath,
                            'hero_image_alt' => $sectionData['hero_image_alt'] ?? null,
                            'hero_video_url' => $sectionData['hero_video_url'] ?? null,
                            'badge_image' => $badgeImagePath,
                            'badge_desktop_width' => $sectionData['badge_desktop_width'] ?? 100,
                            'badge_mobile_width' => $sectionData['badge_mobile_width'] ?? 80,
                            'badge_text' => $sectionData['badge_text'] ?? null,
                            'badge_color' => $sectionData['badge_color'] ?? '#ffd54f',
                            'trust_indicators' => $trustIndicators,
                            'show_trust_indicators' => isset($sectionData['show_trust_indicators']) ? 1 : 0,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ]);
                    } else if ($sectionData['section_type'] === 'countdown') {
                        \App\Models\LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'countdown',
                            'countdown_title' => $sectionData['countdown_title'] ?? 'Offer ends soon!',
                            'countdown_hours' => $sectionData['countdown_hours'] ?? 4,
                            'countdown_repeat' => isset($sectionData['countdown_repeat']) ? (int)$sectionData['countdown_repeat'] : 0,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ]);
                    } else if ($sectionData['section_type'] === 'video') {
                        \App\Models\LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'video',
                            'video_title' => $sectionData['video_title'] ?? null,
                            'video_description' => $sectionData['video_description'] ?? null,
                            'video_url' => $sectionData['video_url'] ?? null,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ]);
                    } else if ($sectionData['section_type'] === 'single_image') {
                        // Handle single image upload
                        $singleImagePath = null;
                        if (isset($sectionData['single_image']) && $sectionData['single_image'] instanceof \Illuminate\Http\UploadedFile) {
                            $singleImagePath = $sectionData['single_image']->store('landing-pages/single-image', 'public');
                        }

                        \App\Models\LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'single_image',
                            'sub_heading' => $sectionData['sub_heading'] ?? null,
                            'single_image' => $singleImagePath,
                            'single_image_alt' => $sectionData['single_image_alt'] ?? null,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ]);
                    } else if ($sectionData['section_type'] === 'image_carousel') {
                        $carouselImages = [];

                        if (isset($sectionData['carousel_images']) && is_array($sectionData['carousel_images'])) {
                            foreach ($sectionData['carousel_images'] as $carouselIndex => $carouselData) {
                                $carouselImagePath = null;

                                if (isset($carouselData['image']) && $carouselData['image'] instanceof \Illuminate\Http\UploadedFile) {
                                    $carouselImagePath = $carouselData['image']->store('landing-pages/carousel', 'public');
                                }

                                if ($carouselImagePath) {
                                    $carouselImages[] = [
                                        'image' => $carouselImagePath,
                                        'caption' => $carouselData['caption'] ?? null,
                                        'position' => $carouselIndex,
                                    ];
                                }
                            }
                        }

                        \App\Models\LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'image_carousel',
                            'title' => $sectionData['title'] ?? null,
                            'description' => $sectionData['description'] ?? null,
                            'carousel_images' => $carouselImages,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ]);
                    } else if ($sectionData['section_type'] === 'call_to_action') {
                        \App\Models\LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'call_to_action',
                            'cta_title' => $sectionData['cta_title'] ?? 'প্রয়োজনে কল করুন',
                            'cta_subtitle' => $sectionData['cta_subtitle'] ?? null,
                            'cta_button_text' => $sectionData['cta_button_text'] ?? 'কল করুন',
                            'cta_phone_number' => $sectionData['cta_phone_number'] ?? '01611-109447',
                            'cta_background_color' => $sectionData['cta_background_color'] ?? '#1D8758',
                            'cta_button_color' => $sectionData['cta_button_color'] ?? '#dc3545',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ]);
                    } else if ($sectionData['section_type'] === 'header') {
                        // Handle header logo upload
                        $headerLogoPath = null;
                        if (isset($sectionData['header_logo']) && $sectionData['header_logo'] instanceof \Illuminate\Http\UploadedFile) {
                            $headerLogoPath = $sectionData['header_logo']->store('landing-pages/header', 'public');
                        }

                        \App\Models\LandingPageSection::create([
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'header',
                            'header_logo' => $headerLogoPath,
                            'header_logo_alt' => $sectionData['header_logo_alt'] ?? null,
                            'header_alignment' => $sectionData['header_alignment'] ?? 'center',
                            'header_button1_text' => $sectionData['header_button1_text'] ?? 'Call Now',
                            'header_button1_url' => $sectionData['header_button1_url'] ?? 'tel:01717171717',
                            'header_button1_color' => $sectionData['header_button1_color'] ?? '#007bff',
                            'header_button1_text_color' => $sectionData['header_button1_text_color'] ?? '#ffffff',
                            'header_button1_active' => isset($sectionData['header_button1_active']) ? 1 : 0,
                            'header_button2_text' => $sectionData['header_button2_text'] ?? 'এখনই কিনুন',
                            'header_button2_url' => $sectionData['header_button2_url'] ?? '#order-section',
                            'header_button2_color' => $sectionData['header_button2_color'] ?? '#dc3545',
                            'header_button2_text_color' => $sectionData['header_button2_text_color'] ?? '#ffffff',
                            'header_button2_active' => isset($sectionData['header_button2_active']) ? 1 : 0,
                            'header_desktop_logo_width' => $sectionData['header_desktop_logo_width'] ?? 200,
                            'header_mobile_logo_width' => $sectionData['header_mobile_logo_width'] ?? 150,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ]);
                    } else {
                            // Handle other section types (if any)
                            if (!empty($sectionData['title']) || !empty($sectionData['description'])) {
                                unset($sectionData['testimonials']);
                                unset($sectionData['benefits']);
                                unset($sectionData['feature_items']);
                                unset($sectionData['pricing_variants']);
                                LandingPageSection::create($sectionData);
                            }
                        }
                }
            }
        }

        return redirect()->route('admin.landing-pages.index')
            ->with('success', 'Landing page created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Landing page creation error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'An error occurred while creating the landing page. Please check the form and try again. Error: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(LandingPage $landingPage)
    {
        $landingPage->load([
            'product', 
            'sections' => function($query) {
                $query->orderBy('position', 'asc');
            },
            'creator'
        ]);

        // Get shipping settings
        $shippingSetting = BasicShippingSetting::first() ?? new BasicShippingSetting([
            'flat_rate' => 80.00,
            'shipping_options' => [
                'inside_dhaka' => ['name' => 'Inside Dhaka', 'cost' => 80.00, 'active' => true, 'position' => 1],
                'outside_dhaka' => ['name' => 'Outside Dhaka', 'cost' => 110.00, 'active' => true, 'position' => 2],
            ],
            'free_shipping_threshold' => 1500.00,
        ]);

        // Filter active shipping options and sort by position
        $activeShippingOptions = array_filter($shippingSetting->shipping_options ?? [], fn($option) => $option['active'] ?? false);
        uasort($activeShippingOptions, fn($a, $b) => ($a['position'] ?? 999) <=> ($b['position'] ?? 999));

        // Get product price
        $product = $landingPage->product;
        $price = $product->offer ?: $product->old_price;
        $shipping = $shippingSetting->flat_rate;

        if (!empty($activeShippingOptions)) {
            $firstOption = reset($activeShippingOptions);
            $shipping = $firstOption['cost'];
        }

        // Get payment method settings
        $codEnabled = SiteSetting::get('ecommerce', 'cod', '1') == '1';
        $bkashEnabled = SiteSetting::get('ecommerce', 'bkash', '1') == '1';
        $nagadEnabled = SiteSetting::get('ecommerce', 'nagad', '1') == '1';
        $rocketEnabled = SiteSetting::get('ecommerce', 'rocket', '1') == '1';

        // Find the first enabled method for default checked
        $methods = [];
        if ($codEnabled) {
            $methods[] = 'cod';
        }
        if ($bkashEnabled) {
            $methods[] = 'bkash';
        }
        if ($nagadEnabled) {
            $methods[] = 'nagad';
        }
        if ($rocketEnabled) {
            $methods[] = 'rocket';
        }
        $defaultMethod = $methods[0] ?? null;

        return view('frontend.landing-pages.show', compact(
            'landingPage',
            'product',
            'price',
            'shipping',
            'shippingSetting',
            'activeShippingOptions',
            'codEnabled',
            'bkashEnabled',
            'nagadEnabled',
            'rocketEnabled',
            'defaultMethod'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LandingPage $landingPage)
    {
        $products = Product::where('status', true)->get();
        $landingPage->load([
            'sections' => function($query) {
                $query->orderBy('position', 'asc');
            }
        ]);
        return view('admin.landing-pages.edit', compact('landingPage', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LandingPage $landingPage)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:landing_pages,slug,' . $landingPage->id,
            'order_button_text' => 'nullable|string|max:255',
            'order_form_title' => 'nullable|string|max:255',
            'order_place_button_text' => 'nullable|string|max:255',
            'order_button_url' => 'nullable|string|max:255',
            'product_id' => 'required|exists:products,id',
            'status' => 'boolean',
            'position' => 'nullable|integer|min:0',
            // Validation for sections
            'sections.*.section_id' => 'nullable|exists:landing_page_sections,id',
            'sections.*.section_type' => 'required|in:hero,benefit,testimonials,feature_list,pricing,countdown,video,single_image,call_to_action,header,image_carousel',
            'sections.*.title' => 'nullable|string|max:255',
            'sections.*.description' => 'nullable|string',
            'sections.*.icon' => 'nullable|string|max:255',
            'sections.*.reviewer_name' => 'nullable|string|max:255',
            'sections.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections.*.image_alt' => 'nullable|string|max:255',
            'sections.*.carousel_images.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'sections.*.carousel_images.*.caption' => 'nullable|string|max:255',
            'sections.*.background_color' => 'nullable|string|max:7',
            'sections.*.text_color' => 'nullable|string|max:7',
            'sections.*.position' => 'nullable|integer|min:0',
            'sections.*.status' => 'boolean',
            'sections.*.countdown_hours' => 'nullable|integer|min:1|max:999',
            'sections.*.countdown_repeat' => 'nullable|boolean',
            // Validation for testimonials
            'sections.*.testimonials.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections.*.testimonials.*.text' => 'nullable|string',
            'sections.*.testimonials.*.author' => 'nullable|string|max:255',
            'sections.*.testimonials.*.rating' => 'nullable|integer|min:1|max:5',
            // Validation for feature_list items
            'sections.*.feature_items.*.emoji' => 'nullable|string|max:10',
            'sections.*.feature_items.*.text' => 'nullable|string',
            'sections.*.feature_items.*.is_positive' => 'boolean',
            // Validation for pricing variants
            'sections.*.pricing_variants.*.weight' => 'nullable|string|max:255',
            'sections.*.pricing_variants.*.regular_price' => 'nullable|numeric|min:0',
            'sections.*.pricing_variants.*.offer_price' => 'nullable|numeric|min:0',
            'sections.*.pricing_variants.*.delivery_text' => 'nullable|string|max:255',
            'sections.*.pricing_variants.*.delivery_icon' => 'nullable|string|max:10',
            'sections.*.pricing_variants.*.position' => 'nullable|integer|min:0',
            // Validation for hero section fields
            'sections.*.heading' => 'nullable|string|max:255',
            'sections.*.sub_heading' => 'nullable|string',
            'sections.*.primary_text' => 'nullable|string',
            'sections.*.hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections.*.hero_image_alt' => 'nullable|string|max:255',
            'sections.*.hero_video_url' => 'nullable|url|max:500',
            'sections.*.badge_text' => 'nullable|string|max:255',
            'sections.*.badge_color' => 'nullable|string|max:7',
            'sections.*.badge_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Validation for video section fields
            'sections.*.video_url' => 'nullable|url|max:500',
            'sections.*.video_title' => 'nullable|string|max:255',
            'sections.*.video_description' => 'nullable|string',
            // Validation for call to action section fields
            'sections.*.cta_title' => 'nullable|string|max:255',
            'sections.*.cta_subtitle' => 'nullable|string|max:255',
            'sections.*.cta_button_text' => 'nullable|string|max:255',
            'sections.*.cta_phone_number' => 'nullable|string|max:20',
            'sections.*.cta_background_color' => 'nullable|string|max:7',
            'sections.*.cta_button_color' => 'nullable|string|max:7',
            // Validation for header section fields
            'sections.*.header_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'sections.*.header_logo_alt' => 'nullable|string|max:255',
            'sections.*.header_alignment' => 'nullable|in:flex-start,center,flex-end',
            'sections.*.header_button1_text' => 'nullable|string|max:255',
            'sections.*.header_button1_url' => 'nullable|string|max:500',
            'sections.*.header_button1_color' => 'nullable|string|max:7',
            'sections.*.header_button1_text_color' => 'nullable|string|max:7',
            'sections.*.header_button1_active' => 'nullable|boolean',
            'sections.*.header_button2_text' => 'nullable|string|max:255',
            'sections.*.header_button2_url' => 'nullable|string|max:500',
            'sections.*.header_button2_color' => 'nullable|string|max:7',
            'sections.*.header_button2_text_color' => 'nullable|string|max:7',
            'sections.*.header_button2_active' => 'nullable|boolean',
            'sections.*.header_desktop_logo_width' => 'nullable|integer|min:50|max:500',
            'sections.*.header_mobile_logo_width' => 'nullable|integer|min:30|max:300',
        ]);

        $data = $request->all();
        
        // Handle slug - use provided slug or generate from title
        if (!empty($data['slug'])) {
            $data['slug'] = strtolower(trim($data['slug']));
            // Replace spaces and special characters with hyphens
            $data['slug'] = preg_replace('/[^a-z0-9\-]/', '-', $data['slug']);
            // Remove multiple consecutive hyphens
            $data['slug'] = preg_replace('/-+/', '-', $data['slug']);
            // Remove leading and trailing hyphens
            $data['slug'] = trim($data['slug'], '-');
        } else {
            $data['slug'] = LandingPage::generateSlug($request->title);
        }

        $landingPage->update($data);

        // Handle sections update
        if ($request->has('sections')) {
            
            // Get existing section IDs to track what should be deleted
            $existingSectionIds = $landingPage->sections->pluck('id')->toArray();
            $updatedSectionIds = [];

            foreach ($request->sections as $sectionIndex => $sectionData) {
                if (!empty($sectionData['section_type'])) {
                    $sectionData['landing_page_id'] = $landingPage->id;
                    
                    // Check if this is an existing section (has section_id)
                    $existingSection = null;
                    if (isset($sectionData['section_id']) && !empty($sectionData['section_id'])) {
                        $existingSection = $landingPage->sections()->find($sectionData['section_id']);
                        if ($existingSection) {
                            $updatedSectionIds[] = $existingSection->id;
                        }
                    }

                    // Handle section image upload for non-testimonials sections
                    if (isset($sectionData['image']) && $sectionData['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $imagePath = $sectionData['image']->store('landing-pages/sections', 'public');
                        $sectionData['image'] = $imagePath;
                    } elseif ($existingSection && $existingSection->image) {
                        // Keep existing image if no new image uploaded
                        $sectionData['image'] = $existingSection->image;
                    }

                    // Handle testimonials section
                    if ($sectionData['section_type'] === 'testimonials') {
                        $testimonialsArray = [];
                        if (isset($sectionData['testimonials']) && is_array($sectionData['testimonials'])) {
                            foreach ($sectionData['testimonials'] as $testimonialIndex => $testimonialData) {
                                $testimonialImagePath = null;
                                if (isset($testimonialData['image']) && $testimonialData['image'] instanceof \Illuminate\Http\UploadedFile) {
                                    $testimonialImagePath = $testimonialData['image']->store('landing-pages/testimonials', 'public');
                                } elseif (!empty($testimonialData['old_image'])) {
                                    $testimonialImagePath = $testimonialData['old_image'];
                                } else {
                                    $testimonialImagePath = null;
                                }
                                $testimonialsArray[] = [
                                    'text' => $testimonialData['text'] ?? null,
                                    'author' => $testimonialData['author'] ?? null,
                                    'image' => $testimonialImagePath,
                                    'rating' => $testimonialData['rating'] ?? null,
                                    'position' => $testimonialIndex,
                                ];
                            }
                        }
                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'testimonials',
                            'testimonials_title' => $sectionData['testimonials_title'] ?? 'Customer Testimonials',
                            'description' => $sectionData['description'] ?? null,
                            'background_color' => $sectionData['background_color'] ?? '#ffffff',
                            'text_color' => $sectionData['text_color'] ?? '#000000',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                            'testimonials' => $testimonialsArray,
                        ];
                        
                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'benefit') {
                        $benefitsArray = [];
                        if (isset($sectionData['benefits']) && is_array($sectionData['benefits'])) {
                            foreach ($sectionData['benefits'] as $benefitIndex => $benefitData) {
                                $benefitsArray[] = [
                                    'title' => $benefitData['title'] ?? null,
                                    'description' => $benefitData['description'] ?? null,
                                    'icon' => $benefitData['icon'] ?? null,
                                    'position' => $benefitIndex,
                                ];
                            }
                        }
                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'benefit',
                            'benefit_title' => $sectionData['benefit_title'] ?? null,
                            'description' => $sectionData['description'] ?? null,
                            'icon' => $sectionData['icon'] ?? null,
                            'background_color' => $sectionData['background_color'] ?? '#ffffff',
                            'text_color' => $sectionData['text_color'] ?? '#000000',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                            'benefits' => $benefitsArray,
                            'image' => $sectionData['image'] ?? null,
                            'image_alt' => $sectionData['image_alt'] ?? null,
                        ];

                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'feature_list') {
                        // Prepare feature items array
                        $featureItemsArray = [];
                        if (isset($sectionData['feature_items']) && is_array($sectionData['feature_items'])) {
                            foreach ($sectionData['feature_items'] as $itemIndex => $itemData) {
                                $featureItemsArray[] = [
                                    'emoji' => $itemData['emoji'] ?? null,
                                    'text' => $itemData['text'] ?? null,
                                    'is_positive' => isset($itemData['is_positive']) ? 1 : 0,
                                    'position' => $itemIndex,
                                ];
                            }
                        }

                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'feature_list',
                            'feature_list_title' => $sectionData['feature_list_title'] ?? null,
                            'description' => $sectionData['description'] ?? null,
                            'background_color' => $sectionData['background_color'] ?? '#ffffff',
                            'text_color' => $sectionData['text_color'] ?? '#000000',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                            'feature_items' => $featureItemsArray,
                        ];
                        


                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'pricing') {
                        // Prepare pricing variants array
                        $pricingVariantsArray = [];
                        if (isset($sectionData['pricing_variants']) && is_array($sectionData['pricing_variants'])) {
                            foreach ($sectionData['pricing_variants'] as $variantIndex => $variantData) {
                                $pricingVariantsArray[] = [
                                    'weight' => $variantData['weight'] ?? null,
                                    'regular_price' => $variantData['regular_price'] ?? null,
                                    'offer_price' => $variantData['offer_price'] ?? null,
                                    'delivery_text' => $variantData['delivery_text'] ?? null,
                                    'delivery_icon' => $variantData['delivery_icon'] ?? null,
                                    'position' => $variantIndex,
                                ];
                            }
                        }

                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'pricing',
                            'pricing_title' => $sectionData['pricing_title'] ?? null,
                            'description' => $sectionData['description'] ?? null,
                            'background_color' => $sectionData['background_color'] ?? '#ffffff',
                            'text_color' => $sectionData['text_color'] ?? '#000000',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                            'pricing_variants' => $pricingVariantsArray,
                        ];

                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'hero') {
                        // Handle hero image upload for dynamic hero section
                        $heroImagePath = null;
                        if (isset($sectionData['hero_image']) && $sectionData['hero_image'] instanceof \Illuminate\Http\UploadedFile) {
                            $heroImagePath = $sectionData['hero_image']->store('landing-pages/hero', 'public');
                        } elseif (!empty($sectionData['old_hero_image'])) {
                            $heroImagePath = $sectionData['old_hero_image'];
                        }

                        // Handle badge image upload
                        $badgeImagePath = null;
                        if (isset($sectionData['badge_image']) && $sectionData['badge_image'] instanceof \Illuminate\Http\UploadedFile) {
                            $badgeImagePath = $sectionData['badge_image']->store('landing-pages/badge', 'public');
                        } elseif (!empty($sectionData['old_badge_image'])) {
                            $badgeImagePath = $sectionData['old_badge_image'];
                        } elseif ($existingSection && $existingSection->badge_image) {
                            $badgeImagePath = $existingSection->badge_image;
                        }

                        // Process trust indicators
                        $trustIndicators = [];
                        if (isset($sectionData['trust_indicators']) && is_array($sectionData['trust_indicators'])) {
                            foreach ($sectionData['trust_indicators'] as $indicator) {
                                if (!empty($indicator['icon']) || !empty($indicator['text1']) || !empty($indicator['text2'])) {
                                    $trustIndicators[] = [
                                        'icon' => $indicator['icon'] ?? '',
                                        'text1' => $indicator['text1'] ?? '',
                                        'text2' => $indicator['text2'] ?? ''
                                    ];
                                }
                            }
                        }

                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'hero',
                            'heading' => $sectionData['heading'] ?? null,
                            'sub_heading' => $sectionData['sub_heading'] ?? null,
                            'primary_text' => $sectionData['primary_text'] ?? null,
                            'hero_image' => $heroImagePath,
                            'hero_image_alt' => $sectionData['hero_image_alt'] ?? null,
                            'hero_video_url' => $sectionData['hero_video_url'] ?? null,
                            'badge_image' => $badgeImagePath,
                            'badge_desktop_width' => $sectionData['badge_desktop_width'] ?? 100,
                            'badge_mobile_width' => $sectionData['badge_mobile_width'] ?? 80,
                            'badge_text' => $sectionData['badge_text'] ?? null,
                            'badge_color' => $sectionData['badge_color'] ?? '#ffd54f',
                            'trust_indicators' => $trustIndicators,
                            'show_trust_indicators' => isset($sectionData['show_trust_indicators']) ? 1 : 0,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ];

                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'countdown') {
                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'countdown',
                            'countdown_title' => $sectionData['countdown_title'] ?? 'Offer ends soon!',
                            'countdown_hours' => $sectionData['countdown_hours'] ?? 4,
                            'countdown_repeat' => isset($sectionData['countdown_repeat']) ? (int)$sectionData['countdown_repeat'] : 0,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ];

                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'video') {
                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'video',
                            'video_title' => $sectionData['video_title'] ?? null,
                            'video_description' => $sectionData['video_description'] ?? null,
                            'video_url' => $sectionData['video_url'] ?? null,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ];

                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'single_image') {
                        // Handle single image upload
                        $singleImagePath = null;
                        if (isset($sectionData['single_image']) && $sectionData['single_image'] instanceof \Illuminate\Http\UploadedFile) {
                            $singleImagePath = $sectionData['single_image']->store('landing-pages/single-image', 'public');
                        } elseif (!empty($sectionData['old_single_image'])) {
                            $singleImagePath = $sectionData['old_single_image'];
                        } elseif ($existingSection && $existingSection->single_image) {
                            $singleImagePath = $existingSection->single_image;
                        }

                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'single_image',
                            'sub_heading' => $sectionData['sub_heading'] ?? null,
                            'single_image' => $singleImagePath,
                            'single_image_alt' => $sectionData['single_image_alt'] ?? null,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ];

                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'image_carousel') {
                        $carouselImages = [];

                        if (isset($sectionData['carousel_images']) && is_array($sectionData['carousel_images'])) {
                            foreach ($sectionData['carousel_images'] as $carouselIndex => $carouselData) {
                                $carouselImagePath = null;

                                if (isset($carouselData['image']) && $carouselData['image'] instanceof \Illuminate\Http\UploadedFile) {
                                    $carouselImagePath = $carouselData['image']->store('landing-pages/carousel', 'public');
                                } elseif (!empty($carouselData['old_image'])) {
                                    $carouselImagePath = $carouselData['old_image'];
                                }

                                if ($carouselImagePath) {
                                    $carouselImages[] = [
                                        'image' => $carouselImagePath,
                                        'caption' => $carouselData['caption'] ?? null,
                                        'position' => $carouselIndex,
                                    ];
                                }
                            }
                        }

                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'image_carousel',
                            'title' => $sectionData['title'] ?? null,
                            'description' => $sectionData['description'] ?? null,
                            'carousel_images' => $carouselImages,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ];

                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'call_to_action') {
                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'call_to_action',
                            'cta_title' => $sectionData['cta_title'] ?? 'প্রয়োজনে কল করুন',
                            'cta_subtitle' => $sectionData['cta_subtitle'] ?? null,
                            'cta_button_text' => $sectionData['cta_button_text'] ?? 'কল করুন',
                            'cta_phone_number' => $sectionData['cta_phone_number'] ?? '01611-109447',
                            'cta_background_color' => $sectionData['cta_background_color'] ?? '#1D8758',
                            'cta_button_color' => $sectionData['cta_button_color'] ?? '#dc3545',
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ];

                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else if ($sectionData['section_type'] === 'header') {
                        // Handle header logo upload
                        $headerLogoPath = null;
                        if (isset($sectionData['header_logo']) && $sectionData['header_logo'] instanceof \Illuminate\Http\UploadedFile) {
                            $headerLogoPath = $sectionData['header_logo']->store('landing-pages/header', 'public');
                        } elseif ($existingSection && $existingSection->header_logo) {
                            // Keep existing logo if no new logo uploaded
                            $headerLogoPath = $existingSection->header_logo;
                        }

                        $sectionDataToSave = [
                            'landing_page_id' => $landingPage->id,
                            'section_type' => 'header',
                            'header_logo' => $headerLogoPath,
                            'header_logo_alt' => $sectionData['header_logo_alt'] ?? null,
                            'header_alignment' => $sectionData['header_alignment'] ?? 'center',
                            'header_button1_text' => $sectionData['header_button1_text'] ?? 'Call Now',
                            'header_button1_url' => $sectionData['header_button1_url'] ?? 'tel:01717171717',
                            'header_button1_color' => $sectionData['header_button1_color'] ?? '#007bff',
                            'header_button1_text_color' => $sectionData['header_button1_text_color'] ?? '#ffffff',
                            'header_button1_active' => isset($sectionData['header_button1_active']) ? 1 : 0,
                            'header_button2_text' => $sectionData['header_button2_text'] ?? 'এখনই কিনুন',
                            'header_button2_url' => $sectionData['header_button2_url'] ?? '#order-section',
                            'header_button2_color' => $sectionData['header_button2_color'] ?? '#dc3545',
                            'header_button2_text_color' => $sectionData['header_button2_text_color'] ?? '#ffffff',
                            'header_button2_active' => isset($sectionData['header_button2_active']) ? 1 : 0,
                            'header_desktop_logo_width' => $sectionData['header_desktop_logo_width'] ?? 200,
                            'header_mobile_logo_width' => $sectionData['header_mobile_logo_width'] ?? 150,
                            'position' => $sectionData['position'] ?? 0,
                            'status' => isset($sectionData['status']) ? 1 : 0,
                        ];
                        
                        if ($existingSection) {
                            $existingSection->update($sectionDataToSave);
                        } else {
                            \App\Models\LandingPageSection::create($sectionDataToSave);
                        }
                    } else {
                            // Handle other section types (if any)
                            if (!empty($sectionData['title']) || !empty($sectionData['description'])) {
                                unset($sectionData['testimonials']);
                                unset($sectionData['benefits']);
                                unset($sectionData['feature_items']);
                                unset($sectionData['pricing_variants']);
                                \App\Models\LandingPageSection::create($sectionData);
                            }
                        }
                }
            }

            // Delete sections that are no longer present in the form
            $sectionsToDelete = array_diff($existingSectionIds, $updatedSectionIds);
            if (!empty($sectionsToDelete)) {
                $landingPage->sections()->whereIn('id', $sectionsToDelete)->delete();
            }
        }

        return redirect()->route('admin.landing-pages.edit', $landingPage->id)
            ->with('success', 'Landing page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LandingPage $landingPage)
    {
        // Delete hero image
        if ($landingPage->hero_image) {
            Storage::disk('public')->delete($landingPage->hero_image);
        }

        // Delete section images
        foreach ($landingPage->sections as $section) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }
        }

        $landingPage->delete();

        return redirect()->route('admin.landing-pages.index')
            ->with('success', 'Landing page deleted successfully.');
    }

    /**
     * Toggle status of landing page.
     */
    public function toggleStatus(LandingPage $landingPage)
    {
        $landingPage->update(['status' => !$landingPage->status]);

        return response()->json([
            'success' => true,
            'status' => $landingPage->status,
            'message' => 'Status updated successfully.'
        ]);
    }

    /**
     * Update positions of landing pages.
     */
    public function updatePositions(Request $request)
    {
        $positions = $request->input('positions', []);

        foreach ($positions as $id => $position) {
            LandingPage::where('id', $id)->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Copy/Duplicate a landing page with all its sections.
     */
    public function copy(LandingPage $landingPage)
    {
        try {
            // Generate unique slug with -copy-1, -copy-2, etc.
            $baseSlug = $landingPage->slug . '-copy';
            $newSlug = $baseSlug;
            $counter = 1;

            while (LandingPage::where('slug', $newSlug)->exists()) {
                $newSlug = $baseSlug . '-' . $counter;
                $counter++;
            }

            // Duplicate the landing page
            $newLandingPage = $landingPage->replicate();
            $newLandingPage->slug = $newSlug;
            $newLandingPage->title = $landingPage->title . ' (Copy)';
            $newLandingPage->status = false; // Set to inactive by default
            $newLandingPage->created_by = auth()->id();
            $newLandingPage->created_at = now();
            $newLandingPage->updated_at = now();
            $newLandingPage->save();

            // Copy all sections
            foreach ($landingPage->sections as $section) {
                $newSection = $section->replicate();
                $newSection->landing_page_id = $newLandingPage->id;
                $newSection->created_at = now();
                $newSection->updated_at = now();
                $newSection->save();
            }

            // Copy shipping rules if any
            foreach ($landingPage->shippingRules as $shippingRule) {
                $newShippingRule = $shippingRule->replicate();
                $newShippingRule->ruleable_id = $newLandingPage->id;
                $newShippingRule->created_at = now();
                $newShippingRule->updated_at = now();
                $newShippingRule->save();
            }

            return redirect()->route('admin.landing-pages.index')
                ->with('success', 'Landing page copied successfully! New slug: ' . $newSlug);
        } catch (\Exception $e) {
            \Log::error("Landing page copy failed: " . $e->getMessage());
            return redirect()->route('admin.landing-pages.index')
                ->with('error', 'Failed to copy landing page. Please try again.');
        }
    }
}
