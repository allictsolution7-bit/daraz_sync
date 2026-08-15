<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\Product;
use App\Models\BasicShippingSetting;
use App\Services\ShippingCalculationService;
use App\Services\FraudProtectionService;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\order;
use App\Models\order_item;
use App\Models\VariationCombination;
use App\Models\TelegramSetting;
use App\Services\PendingPurchaseEventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LandingPageController extends Controller
{
    protected $pendingPurchaseEventService;

    public function __construct(PendingPurchaseEventService $pendingPurchaseEventService)
    {
        $this->pendingPurchaseEventService = $pendingPurchaseEventService;
    }
    /**
     * Display the specified landing page.
     */
    public function show($slug)
    {
        $landingPage = LandingPage::with([
            'product', 
            'sections' => function($query) {
                $query->where('status', 1)
                      ->orderBy('position', 'asc');
            }
        ])
        ->where('slug', $slug)
        ->where('status', true)
        ->firstOrFail();

        // Get product price
        $product = $landingPage->product;
        $price = $product->offer ?: $product->old_price;
        
        // Use new shipping calculation service
        $shippingService = new ShippingCalculationService();
        
        // Prepare cart items for shipping calculation
        $cartItems = [[
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $price,
            'subtotal' => $price
        ]];
        
        // Calculate shipping using the new service with landing page context
        $shippingResult = $shippingService->calculateShipping($price, $cartItems, $landingPage);
        $shipping = $shippingResult['cost'];
        
        // Get shipping options for display
        $shippingOptions = $shippingService->getShippingOptions($landingPage);
        
        // Fallback to basic shipping settings for compatibility
        $shippingSetting = BasicShippingSetting::first() ?? new BasicShippingSetting([
            'flat_rate' => 80.00,
            'shipping_options' => [
                'inside_dhaka' => ['name' => 'Inside Dhaka', 'cost' => 80.00, 'active' => true, 'position' => 1],
                'outside_dhaka' => ['name' => 'Outside Dhaka', 'cost' => 110.00, 'active' => true, 'position' => 2],
            ],
            'free_shipping_threshold' => 1500.00,
        ]);
        
        // Use shipping options from service or fallback to basic settings
        $activeShippingOptions = !empty($shippingOptions) ? $shippingOptions : 
            array_filter($shippingSetting->shipping_options ?? [], fn($option) => $option['active'] ?? false);
        
        // Get specific shipping rules for this landing page
        $specificShippingRules = $landingPage->shippingRules()->active()->get();

        // Get payment method settings
        $paymentSettings = [
            'cod' => SiteSetting::get('ecommerce', 'cod', '1'),
            'bkash' => SiteSetting::get('ecommerce', 'bkash', '1'),
            'nagad' => SiteSetting::get('ecommerce', 'nagad', '1'),
            'rocket' => SiteSetting::get('ecommerce', 'rocket', '1')
        ];
        
        $codEnabled = $paymentSettings['cod'] == '1';
        $bkashEnabled = $paymentSettings['bkash'] == '1';
        $nagadEnabled = $paymentSettings['nagad'] == '1';
        $rocketEnabled = $paymentSettings['rocket'] == '1';

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

        // Pre-load commonly accessed data to reduce queries
        // Global ordered sections for builder-like rendering
        $allSectionsOrdered = $landingPage->sections()
            ->where('status', 1)
            ->orderBy('position')
            ->get();

        $heroSections = $landingPage->sections->where('section_type', 'hero');
        $videoSections = $landingPage->sections->where('section_type', 'video');
        $allBenefitSections = $landingPage->sections->where('section_type', 'benefit');
        $featureListSections = $landingPage->sections->where('section_type', 'feature_list');
        $testimonialsSection = $landingPage->sections->where('section_type', 'testimonials')->first();
        
        // Set SEO meta data for landing page using the new SEO system
        $seoTitle = $landingPage->title . ' - ' . \App\Models\SiteSetting::get('general', 'site_name', 'Thikana Shop');
        $seoDescription = $landingPage->description ?? 'Special offer on ' . $product->title . '. Limited time deal with fast shipping and secure payment options.';
        $seoKeywords = $product->title . ', special offer, limited time, ' . \App\Models\SiteSetting::getDefaultMetaKeywords();
        $seoImage = $landingPage->hero_image ? asset('storage/' . $landingPage->hero_image) : \App\Models\SiteSetting::getDefaultOgImage();
        $seoData = $this->getSeoData($seoTitle, $seoDescription, $seoKeywords, $seoImage, 'website');

        return view('frontend.landing-pages.show', array_merge(compact(
            'landingPage',
            'product',
            'price',
            'shipping',
            'shippingSetting',
            'activeShippingOptions',
            'specificShippingRules',
            'codEnabled',
            'bkashEnabled',
            'nagadEnabled',
            'rocketEnabled',
            'defaultMethod',
            'heroSections',
            'videoSections',
            'allBenefitSections',
            'featureListSections',
            'testimonialsSection',
            'allSectionsOrdered'
        ), $seoData));
    }

    /**
     * Handle order placement from landing page.
     */
    public function placeOrder(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name'           => 'required|string',
            'address'        => 'required|string',
            'phone'          => 'required|string',
            'message'        => 'nullable|string',
            'landing_page_id' => 'required|exists:landing_pages,id',
            'product_id'     => 'required|exists:products,id',
            'quantity'       => 'required|integer|min:1',
            'price'          => 'required|numeric',
            'shipping'       => 'required|numeric',
            'payment_method' => 'required|string|in:cod,bkash,nagad,rocket,' . implode(',', \App\Models\PaymentGateway::enabled()->pluck('provider')->toArray()),

            // Payment gateway validations
            'bkash_number' => 'required_if:payment_method,bkash|string|nullable',
            'bkash_transaction_id' => 'required_if:payment_method,bkash|string|nullable',
            'bkash_charge' => 'nullable|numeric',

            'nagad_number' => 'required_if:payment_method,nagad|string|nullable',
            'nagad_transaction_id' => 'required_if:payment_method,nagad|string|nullable',
            'nagad_charge' => 'nullable|numeric',

            'rocket_number' => 'required_if:payment_method,rocket|string|nullable',
            'rocket_transaction_id' => 'required_if:payment_method,rocket|string|nullable',
            'rocket_charge' => 'nullable|numeric',
            // Require combination_id for variable products
            'combination_id' => 'nullable|exists:variation_combinations,id',
        ]);

        // ========================================
        // FRAUD PROTECTION CHECK
        // ========================================
        $fraudProtection = new FraudProtectionService();
        $fraudValidation = $fraudProtection->validateOrder($request->all());
        
        if (!$fraudValidation['valid']) {
            return response()->json([
                'success' => false,
                'errors' => $fraudValidation['errors'],
                'message' => $fraudValidation['errors'][0] ?? 'Order validation failed'
            ], 422);
        }
        // ========================================

        try {
            // Get the product
            $product = Product::findOrFail($request->product_id);

            // Authenticate or create a guest user
            if (Auth::check()) {
                $user = Auth::user();
            } else {
                $password = bcrypt('password');
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => User::generateUniqueEmail($request->name, 'guest'),
                    'password' => $password,
                    'phone'    => $request->phone,
                    'address' => $request->address,
                ]);
                Auth::login($user);
            }

            // Calculate total with charge
            $totalWithCharge = $request->price * $request->quantity + $request->shipping +
                ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                ($request->payment_method === 'rocket' ? $request->rocket_charge : 0);
            $isCod = $request->payment_method === 'cod';
            $isAutomatedGateway = \App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($request->payment_method);
            $isPaymentPending = $isCod || $isAutomatedGateway;

            // Create the order
            $order = order::create([
                'name'           => $request->name,
                'address'        => $request->address,
                'phone'          => $request->phone,
                'message'        => $request->message,
                'shipping'       => $request->shipping,
                'total'          => $totalWithCharge,
                'payment_method' => $request->payment_method,
                'user_id'        => $user->id,
                'landing_page_id' => $request->landing_page_id,
                'order_source'   => 'Landing Page',
                'ip_address'     => $request->ip(),

                // Payment gateway fields
                'bkash_number' => $request->payment_method === 'bkash' ? $request->bkash_number : null,
                'bkash_transaction_id' => $request->payment_method === 'bkash' ? $request->bkash_transaction_id : null,
                'bkash_charge' => $request->payment_method === 'bkash' ? $request->bkash_charge : 0,

                'nagad_number' => $request->payment_method === 'nagad' ? $request->nagad_number : null,
                'nagad_transaction_id' => $request->payment_method === 'nagad' ? $request->nagad_transaction_id : null,
                'nagad_charge' => $request->payment_method === 'nagad' ? $request->nagad_charge : 0,

                'rocket_number' => $request->payment_method === 'rocket' ? $request->rocket_number : null,
                'rocket_transaction_id' => $request->payment_method === 'rocket' ? $request->rocket_transaction_id : null,
                'rocket_charge' => $request->payment_method === 'rocket' ? $request->rocket_charge : 0,

                // Calculate total with charge (same as total for consistency)
                'total_with_charge' => $totalWithCharge,

                // Payment type: COD/automated gateway = due (pending), manual online = full_paid
                'payment_type' => $isPaymentPending ? 'due' : 'full_paid',
                'paid_amount' => $isPaymentPending ? 0 : $totalWithCharge,
                'due_amount' => $isPaymentPending ? $totalWithCharge : 0,
                'payment_status' => $isPaymentPending ? 'pending' : 'paid',
            ]);

            // Create order item with COGS snapshot
            $product = Product::find($request->product_id);
            $unitCost = $product->product_cost ?? 0;
            if ($request->combination_id) {
                $combo = VariationCombination::find($request->combination_id);
                $unitCost = $combo->product_cost ?? $unitCost;
            }

            order_item::create([
                'order_id'   => $order->id,
                'product_id' => $request->product_id,
                'combination_id' => $request->combination_id,
                'quantity'   => $request->quantity,
                'price'      => $request->price,
                'sub_total'  => $request->price * $request->quantity,
                'unit_cost'  => $unitCost,
                'total_cost' => $unitCost * $request->quantity,
            ]);

            // Store pending purchase event (service checks if enabled for this payment method)
            $this->pendingPurchaseEventService->storePendingEvent($order, $request);

            // Delete incomplete orders for this phone number (using normalized phone)
            \App\Models\IncompleteOrder::deleteByPhone($order->phone);

            $response = [
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'product_id' => $product->id,
            ];
            if (\App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($request->payment_method)) {
                $response['requires_redirect'] = true;
                $response['provider'] = $request->payment_method;
            }
            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error("Landing Page Order placement failed: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your order. Please try again.',
            ], 500);
        }
    }

    /**
     * Send order notification asynchronously (called from thank you page).
     * Works for all order sources: Landing Page, Cart, Buy Now, etc.
     */
    public function sendOrderNotification(Request $request)
    {
        try {
            $order = order::with('order_items.product')->find($request->order_id);
            
            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 200);
            }
            
            // Get the first product from order items
            $product = $order->order_items->first()->product ?? null;
            
            if ($order && $product) {
                // Use the actual order source from database (Landing Page, Cart, Buy Now, etc.)
                $orderSource = $order->order_source ?? 'Unknown';
                
                (new \App\Services\TelegramNotificationService())
                    ->sendOrderNotification($order, $product, $request, $orderSource);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::warning('Async Telegram notification failed: ' . $e->getMessage());
            return response()->json(['success' => false], 200); // Still return 200 to not show errors to user
        }
    }

    /**
     * Record a landing page view (total + optional unique).
     */
    public function trackView(Request $request, LandingPage $landingPage)
    {
        $isUnique = $request->boolean('unique');

        $landingPage->increment('views_total');

        if ($isUnique) {
            $landingPage->increment('views_unique');
        }

        return response()->noContent();
    }

    /**
     * Get SEO data for any page
     */
    private function getSeoData($title = null, $description = null, $keywords = null, $image = null, $type = 'website')
    {
        return [
            'metaTitle' => $title ?? \App\Models\SiteSetting::getDefaultMetaTitle(),
            'metaDescription' => $description ?? \App\Models\SiteSetting::getDefaultMetaDescription(),
            'metaKeywords' => $keywords ?? \App\Models\SiteSetting::getDefaultMetaKeywords(),
            'ogImage' => $image ?? \App\Models\SiteSetting::getDefaultOgImage(),
            'ogType' => $type,
            'metaRobots' => \App\Models\SiteSetting::getRobotsMeta(),
            'canonicalUrl' => url()->current(),
        ];
    }
} 
