<?php

namespace App\Http\Controllers;

use App\Models\BasicShippingSetting;
use App\Services\ShippingCalculationService;
use App\Services\StockManagementService;
use App\Services\FraudProtectionService;
use App\Models\Cart;
use App\Models\IncompleteOrder;
use App\Models\User;
use App\Models\Product;
use App\Models\order;
use App\Models\order_item;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductVariationOption;
use App\Models\VariationCombination;
use App\Services\SMSService;
use App\Services\PendingPurchaseEventService;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    protected $smsService;
    protected $stockService;
    protected $pendingPurchaseEventService;

    public function __construct(
        SMSService $smsService,
        StockManagementService $stockService,
        PendingPurchaseEventService $pendingPurchaseEventService
    ) {
        $this->smsService = $smsService;
        $this->stockService = $stockService;
        $this->pendingPurchaseEventService = $pendingPurchaseEventService;
    }

    // New Cart Page Shows
    public function index(Request $request)
    {
        if (Auth::check()) {
            // Fetch cart items for authenticated users based on user_id
            $carts = Cart::with(['product', 'variationCombination', 'comboOffer'])
                ->where('user_id', Auth::id())
                ->get();
        } else {
            // Get the guest_id from the cookie
            $guestId = $request->cookie('guest_id');

            // Fetch cart items for guests based on guest_id if it exists
            if ($guestId) {
                $carts = Cart::with(['product', 'variationCombination', 'comboOffer'])
                    ->where('guest_id', $guestId)
                    ->get();
            } else {
                // If no guest ID exists, initialize an empty collection
                $carts = collect();
            }
        }




        // Calculate subtotal
        $sub_total = 0;
        foreach ($carts as $item) {
            if ($item->combo_offer_id) {
                // For combo items, use combo price
                $sub_total += $item->price * $item->qunt;
            } else {
                // For regular items, use individual price
                $sub_total += $item->price * $item->qunt;
            }
        }

        // Calculate subtotal for each item to avoid recalculating in views
        foreach ($carts as $item) {
            $item->calculated_subtotal = $item->price * $item->qunt;
        }
        
        return view('frontend.cart', compact('carts', 'sub_total'));
    }

    public function store(Request $request)
    {

        
        // Get the product to determine its type
        $product = Product::findOrFail($request->product_id);

        // Basic validation for all product types
        $baseValidation = [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];

        // Add combination validation only for variable products
        if ($product->product_type === 'variable') {
            $combinationValidation = [
                'combination_id' => 'required|integer|exists:variation_combinations,id',
            ];
            $validationRules = array_merge($baseValidation, $combinationValidation);

            $validationMessages = [
                'combination_id.required' => 'You must select all required options.',
                'combination_id.exists' => 'The selected variation combination is invalid.',
            ];

            // Validate with custom messages
            $request->validate($validationRules, $validationMessages);

            // Verify the combination belongs to this product
            $combination = VariationCombination::where('id', $request->combination_id)
                ->where('product_id', $product->id)
                ->first();

            if (!$combination) {
                return redirect()->back()->with('error', 'Invalid variation combination for this product.');
            }

            // Check if combination is active and has stock
            if (!$combination->is_active || !$this->stockService->checkStock($product, $combination, $request->quantity)) {
                return redirect()->back()->with('error', 'Selected combination is not available or out of stock.');
            }
        } else {
            // For simple products, just validate the base fields
            $request->validate($baseValidation);
        }

        $userId = Auth::id();
        $guestId = $request->cookie('guest_id') ?? Str::uuid()->toString();

        if (!$userId && !$request->cookie('guest_id')) {
            \Cookie::queue('guest_id', $guestId, 60 * 24 * 30);
        }

        // Handle variable products with combinations
        if ($product->product_type === 'variable' && $request->has('combination_id')) {
            $combination = VariationCombination::find($request->combination_id);
            
            $cartQuery = Cart::where('product_id', $request->product_id)
                ->where('combination_id', $request->combination_id);

            if ($userId) {
                $cartQuery->where('user_id', $userId);
            } else {
                $cartQuery->where('guest_id', $guestId);
            }

            $cartItem = $cartQuery->first();
                            // Use effective price (offer price if available, otherwise regular price, fallback to price)
                $price = $combination->offer_price ?? $combination->regular_price ?? $combination->price;

            if ($cartItem) {
                // Check if increasing quantity would exceed available stock
                if (!$this->stockService->checkStock($product, $combination, $cartItem->qunt + $request->quantity)) {
                    return redirect()->back()->with('error', "Sorry, adding {$request->quantity} more would exceed available stock.");
                }
                $cartItem->increment('qunt', $request->quantity);
            } else {
                Cart::create([
                    'product_id' => $request->product_id,
                    'qunt' => $request->quantity,
                    'price' => $price,
                    'combination_id' => $request->combination_id,
                    'option_id' => null, // Keep for backward compatibility, but use combination_id for variable products
                    'user_id' => $userId,
                    'guest_id' => !$userId ? $guestId : null,
                ]);
            }
        }
        // Handle simple products (no variations)
        else {
            // Check stock availability for simple product
            if (!$this->stockService->checkStock($product, null, $request->quantity)) {
                return redirect()->back()->with('error', "Sorry, {$product->title} is out of stock or doesn't have enough quantity.");
            }

            $cartQuery = Cart::where('product_id', $request->product_id);

            if ($userId) {
                $cartQuery->where('user_id', $userId);
            } else {
                $cartQuery->where('guest_id', $guestId);
            }

            $cartItem = $cartQuery->first();
            $price = $product->offer ?: $product->old_price;

            if ($cartItem) {
                // Check if increasing quantity would exceed available stock
                if (!$this->stockService->checkStock($product, null, $cartItem->qunt + $request->quantity)) {
                    return redirect()->back()->with('error', "Sorry, adding {$request->quantity} more of {$product->title} would exceed available stock.");
                }
                $cartItem->increment('qunt', $request->quantity);
            } else {
                Cart::create([
                    'product_id' => $request->product_id,
                    'qunt' => $request->quantity,
                    'price' => $price,
                    'option_id' => null, // No option for simple products
                    'user_id' => $userId,
                    'guest_id' => !$userId ? $guestId : null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Item(s) added to cart successfully');
    }

    public function storeCombo(Request $request)
    {


        // Validate combo offer request
        $request->validate([
            'combo_offer_id' => 'required|integer|exists:combo_offers,id',
            'quantity' => 'required|integer|min:1',
            'selections' => 'required|array|min:1',
            'selections.*.product_id' => 'required|integer|exists:products,id',
            'selections.*.variation_id' => 'nullable|integer|exists:variation_combinations,id',
            'selections.*.slot_index' => 'required|integer|min:0',
        ]);

        // Get the combo offer
        $comboOffer = \App\Models\ComboOffer::findOrFail($request->combo_offer_id);
        
        if (!$comboOffer->is_active) {
            return redirect()->back()->with('error', 'This combo offer is no longer available.');
        }

        // Validate that all required items are selected
        if (count($request->selections) !== $comboOffer->items_count) {
            return redirect()->back()->with('error', 'Please select all required items for this combo offer.');
        }

        $userId = Auth::id();
        $guestId = $request->cookie('guest_id') ?? Str::uuid()->toString();

        if (!$userId && !$request->cookie('guest_id')) {
            \Cookie::queue('guest_id', $guestId, 60 * 24 * 30);
        }

        // Check if combo is already in cart
        $existingCombo = Cart::where('combo_offer_id', $comboOffer->id);
        
        if ($userId) {
            $existingCombo->where('user_id', $userId);
        } else {
            $existingCombo->where('guest_id', $guestId);
        }
        
        $existingCombo = $existingCombo->first();



        if ($existingCombo) {
            // Update quantity if combo already exists
            $existingCombo->increment('qunt', $request->quantity);
        } else {
            // Create new combo cart item
            
            Cart::create([
                'product_id' => $comboOffer->product_id, // Add the main product ID
                'combo_offer_id' => $comboOffer->id,
                'qunt' => $request->quantity,
                'price' => $comboOffer->combo_price,
                'combo_selections' => json_encode($request->selections),
                'user_id' => $userId,
                'guest_id' => !$userId ? $guestId : null,
            ]);
        }


        
        return redirect()->back()->with('success', 'Combo offer added to cart successfully');
    }

    public function buyComboStore(Request $request)
    {

        
        // Validate combo offer request
        $request->validate([
            'combo_offer_id' => 'required|integer|exists:combo_offers,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'selections' => 'required|array|min:1',
            'selections.*.product_id' => 'required|integer|exists:products,id',
            'selections.*.variation_id' => 'nullable|integer|exists:variation_combinations,id',
            'selections.*.slot_index' => 'required|integer|min:0',
        ]);

        // Get the combo offer
        $comboOffer = \App\Models\ComboOffer::findOrFail($request->combo_offer_id);
        
        if (!$comboOffer->is_active) {
            return redirect()->back()->with('error', 'This combo offer is no longer available.');
        }

        // Validate that all required items are selected
        if (count($request->selections) !== $comboOffer->items_count) {
            return redirect()->back()->with('error', 'Please select all required items for this combo offer.');
        }



        // Redirect to buy now page with combo data as URL parameters
        $selectionsJson = base64_encode(json_encode($request->selections));
        
        return redirect()->route('buy.store', [
            'combo_id' => $comboOffer->id,
            'quantity' => $request->quantity,
            'selections' => $selectionsJson
        ]);
    }

    public function buystore(Request $request)
    {
        // Check if this is a combo purchase from URL parameters
        if ($request->has('combo_id')) {
            // Handle combo purchase from URL parameters
            $comboOffer = \App\Models\ComboOffer::findOrFail($request->combo_id);
            $product = Product::findOrFail($comboOffer->product_id);
            
            // Decode selections from URL parameter (base64 encoded)
            $selections = json_decode(base64_decode($request->selections), true);
            
            // Use combo data for pricing
            $pqty = $request->quantity;
            $price = $comboOffer->combo_price;
            $sub_total = $price * $pqty;
            
            // Use new shipping calculation service
            $shippingService = new ShippingCalculationService();
            
            // Prepare cart items for shipping calculation
            $cartItems = [[
                'product_id' => $product->id,
                'quantity' => $pqty,
                'price' => $comboOffer->combo_price,
                'subtotal' => $sub_total
            ]];
            
            // Calculate shipping using the new service
            $shippingResult = $shippingService->calculateShipping($sub_total, $cartItems, $product);
            $shipping = $shippingResult['cost'];
            
            // Get shipping options for display
            $shippingOptions = $shippingService->getShippingOptions($product);
            
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
            
            // Get specific shipping rules for this product
            $specificShippingRules = $product->shippingRules()->active()->get();

            // Prepare combo data for view
            $comboData = [
                'combo_offer_id' => $comboOffer->id,
                'quantity' => $request->quantity,
                'combo_price' => $comboOffer->combo_price,
                'selections' => $selections,
                'combo_title' => $comboOffer->title,
            ];

            $isComboPurchase = true;

            $locations = auth()->check() ? auth()->user()->deliveryLocations : collect();

            return view('frontend.buynow', compact(
                'product', 'pqty', 'price', 'sub_total', 'shipping',
                'shippingSetting', 'activeShippingOptions', 'specificShippingRules', 'isComboPurchase', 'comboData', 'locations'
            ));
        }
        
        // Handle regular product purchase
        if ($request->isMethod('post')) {
            $product = Product::findOrFail($request->product_id);
            
            $baseValidation = [
                'product_id' => 'required|integer|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'price' => 'required|numeric|min:0',
            ];

            if ($product->product_type === 'variable') {
                $baseValidation['combination_id'] = 'required|integer|exists:variation_combinations,id';
            }

            $request->validate($baseValidation);
        } else {
            // GET request - try to load last product or latest active product
            $productId = $request->input('product_id');
            if ($productId) {
                $product = Product::find($productId);
            }
            if (!isset($product) || !$product) {
                $product = Product::where('status', '1')->latest()->first();
            }
            if (!$product) {
                return redirect()->route('cart.index')->with('error', 'No products available for checkout.');
            }
            $request->merge([
                'quantity' => $request->input('quantity', 1),
                'price' => $product->offer ?: $product->old_price ?: 0
            ]);
        }

        $pqty = $request->quantity;

        // Use new shipping calculation service
        $shippingService = new ShippingCalculationService();
        
        // Prepare cart items for shipping calculation
        $cartItems = [[
            'product_id' => $product->id,
            'quantity' => $pqty,
            'price' => $product->offer ?: $product->old_price,
            'subtotal' => ($product->offer ?: $product->old_price) * $pqty
        ]];
        
        // Calculate shipping using the new service
        $shippingResult = $shippingService->calculateShipping(($product->offer ?: $product->old_price) * $pqty, $cartItems, $product);
        $shipping = $shippingResult['cost'];
        
        // Get shipping options for display
        $shippingOptions = $shippingService->getShippingOptions($product);
        
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
        if (count($shippingOptions) > 0) {
            $activeShippingOptions = $shippingOptions;
        } else {
            $activeShippingOptions = array_filter($shippingSetting->shipping_options ?? [], fn($option) => $option['active'] ?? false);
            uasort($activeShippingOptions, fn($a, $b) => ($a['position'] ?? 999) <=> ($b['position'] ?? 999));
        }
        // Make sure the default shipping rate value matches the selected option
        if (!empty($activeShippingOptions)) {
            $firstOption = reset($activeShippingOptions);
            $shipping = $firstOption['cost'];
        }

        // Handle variable products with combinations
        if ($product->product_type === 'variable' && $request->has('combination_id')) {
            // Verify the combination belongs to this product and is valid
            $combination = VariationCombination::where('id', $request->combination_id)
                ->where('product_id', $product->id)
                ->where('is_active', true)
                ->first();

            if (!$combination) {
                return redirect()->back()->with('error', 'Selected variation combination is not available.');
            }

            // Check stock availability
            if (!$this->stockService->checkStock($product, $combination, $request->quantity)) {
                return redirect()->back()->with('error', 'Selected combination is out of stock or insufficient quantity.');
            }

            // Use combination pricing - frontend sends total price, not unit price
            $price = $request->price;
            $sub_total = $request->price;

            // Prepare cart items for shipping calculation
            $cartItems = [[
                'product_id' => $product->id,
                'quantity' => $pqty,
                'price' => $price,
                'subtotal' => $sub_total
            ]];
            
            // Calculate shipping using the new service
            $shippingResult = $shippingService->calculateShipping($sub_total, $cartItems, $product);
            $shipping = $shippingResult['cost'];
            
            // Get shipping options for display
            $shippingOptions = $shippingService->getShippingOptions($product);
            $activeShippingOptions = !empty($shippingOptions) ? $shippingOptions : $activeShippingOptions;
            
            // Get specific shipping rules for this product
            $specificShippingRules = $product->shippingRules()->active()->get();

            $isComboPurchase = false;
            $locations = auth()->check() ? auth()->user()->deliveryLocations : collect();

            return view('frontend.buynow', compact(
                'product',
                'pqty',
                'price',
                'sub_total',
                'combination',
                'shipping',
                'shippingSetting',
                'activeShippingOptions',
                'specificShippingRules',
                'isComboPurchase',
                'locations'
            ));
        }
        // Handle simple products or legacy option_id approach
        elseif ($request->option_id) {
            // Legacy support for old variation system - frontend sends total price, not unit price
            $productoption = ProductVariationOption::findOrFail($request->option_id);
            $price = $request->price;
            $sub_total = $request->price;
            
            // Prepare cart items for shipping calculation
            $cartItems = [[
                'product_id' => $product->id,
                'quantity' => $pqty,
                'price' => $price,
                'subtotal' => $sub_total
            ]];
            
            // Calculate shipping using the new service
            $shippingResult = $shippingService->calculateShipping($sub_total, $cartItems, $product);
            $shipping = $shippingResult['cost'];
            
            // Get shipping options for display
            $shippingOptions = $shippingService->getShippingOptions($product);
            $activeShippingOptions = !empty($shippingOptions) ? $shippingOptions : $activeShippingOptions;
            
            // Get specific shipping rules for this product
            $specificShippingRules = $product->shippingRules()->active()->get();

            $option_id = $request->option_id;
            $isComboPurchase = false;
            $locations = auth()->check() ? auth()->user()->deliveryLocations : collect();

            return view('frontend.buynow', compact(
                'product',
                'pqty',
                'price',
                'sub_total',
                'option_id',
                'shipping',
                'shippingSetting',
                'activeShippingOptions',
                'specificShippingRules',
                'isComboPurchase',
                'locations'
            ));
        }
        // Handle simple products (no variations)
        else {
            // Check stock availability for simple products
            if (!$this->stockService->checkStock($product, null, $request->quantity)) {
                return redirect()->back()->with('error', 'Product is out of stock or insufficient quantity.');
            }

            // Frontend sends total price, not unit price
            $price = $request->price;
            $sub_total = $request->price;
            
            // Prepare cart items for shipping calculation
            $cartItems = [[
                'product_id' => $product->id,
                'quantity' => $pqty,
                'price' => $price,
                'subtotal' => $sub_total
            ]];
            
            // Calculate shipping using the new service
            $shippingResult = $shippingService->calculateShipping($sub_total, $cartItems, $product);
            $shipping = $shippingResult['cost'];
            
            // Get shipping options for display
            $shippingOptions = $shippingService->getShippingOptions($product);
            $activeShippingOptions = !empty($shippingOptions) ? $shippingOptions : $activeShippingOptions;
            
            // Get specific shipping rules for this product
            $specificShippingRules = $product->shippingRules()->active()->get();

            $isComboPurchase = false;
            $locations = auth()->check() ? auth()->user()->deliveryLocations : collect();

            return view('frontend.buynow', compact(
                'product',
                'pqty',
                'price',
                'sub_total',
                'shipping',
                'shippingSetting',
                'activeShippingOptions',
                'specificShippingRules',
                'isComboPurchase',
                'locations'
            ));
        }
    }


    public function buynoworder(Request $request)
    {
        // Check if this is a combo purchase
        $isComboPurchase = $request->has('is_combo_purchase') && $request->is_combo_purchase == '1';
        
        if ($isComboPurchase) {
            // Handle combo purchase
            return $this->processComboOrder($request);
        } else {
            // Handle regular single product purchase
            return $this->processSingleProductOrder($request);
        }
    }

    private function processComboOrder(Request $request)
    {
        // Validate combo order request
        $request->validate([
            'combo_offer_id' => 'required|integer|exists:combo_offers,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'selections' => 'required|array|min:1',
            'selections.*.product_id' => 'required|integer|exists:products,id',
            'selections.*.variation_id' => 'nullable|integer|exists:variation_combinations,id',
            'selections.*.slot_index' => 'required|integer|min:0',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'payment_method' => 'required|string|in:cod,bkash,nagad,rocket,' . implode(',', \App\Models\PaymentGateway::enabled()->pluck('provider')->toArray()),
        ]);

        // ========================================

        // Check if COD is selected and any product in combo selections requires advance delivery payment
        if ($request->payment_method === 'cod') {
            $hasAdvanceDelivery = false;
            if ($request->has('selections') && is_array($request->selections)) {
                foreach ($request->selections as $sel) {
                    $prod = Product::find($sel['product_id']);
                    if ($prod && $prod->pay_advance_delivery) {
                        $hasAdvanceDelivery = true;
                        break;
                    }
                }
            }
            if ($hasAdvanceDelivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'এই অর্ডারে থাকা কিছু পণ্যের জন্য অগ্রিম ডেলিভারি চার্জ পরিশোধ করা বাধ্যতামূলক। অনুগ্রহ করে পেমেন্ট মেথড হিসেবে বিকাশ, নগদ বা রকেট নির্বাচন করে অর্ডার করুন।'
                ], 422);
            }
        }

        // Get the combo offer
        $comboOffer = \App\Models\ComboOffer::findOrFail($request->combo_offer_id);
        
        if (!$comboOffer->is_active) {
            return response()->json([
                'status' => 'error',
                'message' => 'This combo offer is no longer available.'
            ], 400);
        }

        // Validate that all required items are selected
        if (count($request->selections) !== $comboOffer->items_count) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please select all required items for this combo offer.'
            ], 400);
        }

        // Calculate total
        $subtotal = $request->price * $request->quantity;
        $shipping = $request->shipping ?? 0;
        $total = $subtotal + $shipping;

        // Add payment gateway charges if applicable
        $paymentMethod = $request->payment_method;
        if ($paymentMethod === 'bkash') {
            $bkashCharge = floatval($request->bkash_charge ?? 0);
            $total += $bkashCharge;
        } elseif ($paymentMethod === 'nagad') {
            $nagadCharge = floatval($request->nagad_charge ?? 0);
            $total += $nagadCharge;
        } elseif ($paymentMethod === 'rocket') {
            $rocketCharge = floatval($request->rocket_charge ?? 0);
            $total += $rocketCharge;
        }

        // Create order
        $isCod = $paymentMethod === 'cod';
        $isAutomatedGateway = \App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($paymentMethod);
        $isPaymentPending = $isCod || $isAutomatedGateway;
        $order = order::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'upazila' => $request->upazila ?? '',
            'city' => $request->city ?? '',
            'message' => $request->message ?? '',
            'payment_method' => $paymentMethod,
            'shipping' => $shipping,
            'total' => $total,
            'total_with_charge' => $total,
            'status' => 'pending',
            'order_source' => 'Website',
            'ip_address' => $request->ip(),
            'is_combo_order' => true,
            'combo_offer_id' => $comboOffer->id,
            'combo_selections' => json_encode($request->selections),
            // Add payment gateway fields
            'bkash_number' => $paymentMethod === 'bkash' ? $request->bkash_number : null,
            'bkash_transaction_id' => $paymentMethod === 'bkash' ? $request->bkash_transaction_id : null,
            'bkash_charge' => $paymentMethod === 'bkash' ? $bkashCharge : 0,
            'nagad_number' => $paymentMethod === 'nagad' ? $request->nagad_number : null,
            'nagad_transaction_id' => $paymentMethod === 'nagad' ? $request->nagad_transaction_id : null,
            'nagad_charge' => $paymentMethod === 'nagad' ? $nagadCharge : 0,
            'rocket_number' => $paymentMethod === 'rocket' ? $request->rocket_number : null,
            'rocket_transaction_id' => $paymentMethod === 'rocket' ? $request->rocket_transaction_id : null,
            'rocket_charge' => $paymentMethod === 'rocket' ? $rocketCharge : 0,
            // Payment type: COD/automated gateway = due (pending), manual online = full_paid
            'payment_type' => $isPaymentPending ? 'due' : 'full_paid',
            'paid_amount' => $isPaymentPending ? 0 : $total,
            'due_amount' => $isPaymentPending ? $total : 0,
            'payment_status' => $isPaymentPending ? 'pending' : 'paid',
        ]);



        // Create order items for each selection
        foreach ($request->selections as $selection) {
            $product = \App\Models\Product::find($selection['product_id']);
            if ($product) {
                // Get the correct price based on whether it's a variable product
                $price = 0;
                if ($selection['variation_id']) {
                    // For variable products, get price from variation combination
                    $variation = \App\Models\VariationCombination::find($selection['variation_id']);
                    if ($variation) {
                        $price = $variation->effective_price ?? $variation->offer_price ?? $variation->regular_price ?? 0;
                    }
                } else {
                    // For simple products, use product price
                    $price = $product->offer ?? $product->old_price ?? 0;
                }
                
                // Snapshot COGS at order time
                $unitCost = ($selection['variation_id'] ?? null) && isset($variation)
                    ? ($variation->product_cost ?? $product->product_cost ?? 0)
                    : ($product->product_cost ?? 0);

                order_item::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'combination_id' => $selection['variation_id'] ?? null,
                    'quantity' => $request->quantity,
                    'price' => $price,
                    'sub_total' => $price * $request->quantity,
                    'unit_cost' => $unitCost,
                    'total_cost' => $unitCost * $request->quantity,
                ]);
            }
        }

        // 🔥 CRITICAL FIX: Process stock reduction for combo selections
        $this->stockService->processComboStockReduction($request->selections, $order->id, $request->quantity);

        // Store pending purchase event (service checks if enabled for this payment method)
        $this->pendingPurchaseEventService->storePendingEvent($order, $request);

        // Delete incomplete orders for this phone number (using normalized phone)
        IncompleteOrder::deleteByPhone($order->phone);

        $response = [
            'status' => 'success',
            'message' => 'Combo order placed successfully!',
            'order_id' => $order->id,
        ];
        if ($isAutomatedGateway) {
            $response['requires_redirect'] = true;
            $response['provider'] = $paymentMethod;
        }
        return response()->json($response);
    }

    private function processSingleProductOrder(Request $request)
    {
        // Get product to determine validation rules
        $product = Product::findOrFail($request->product_id);
        
        // Base validation rules
        $validationRules = [
            'name'           => 'required|string',
            'address'        => 'required|string',
            'phone'          => 'required|string',
            'upazila'        => 'nullable|string',
            'city'           => 'nullable|string',
            'message'        => 'nullable|string',
            'product_id'     => 'required|exists:products,id',
            'pqty'           => 'required|integer|min:1',
            'price'          => 'required|numeric',
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
        ];

        // Add variation-specific validation based on product type
        if ($product->product_type === 'variable') {
            $validationRules['combination_id'] = 'required|integer|exists:variation_combinations,id';
        } else {
            // For simple products or legacy system
            $validationRules['option_id'] = 'nullable|integer';
        }

        $validated = $request->validate($validationRules);

        // Check if COD is selected and the product requires advance delivery payment
        if ($request->payment_method === 'cod') {
            if ($product && $product->pay_advance_delivery) {
                return response()->json([
                    'success' => false,
                    'message' => 'এই অর্ডারে থাকা কিছু পণ্যের জন্য অগ্রিম ডেলিভারি চার্জ পরিশোধ করা বাধ্যতামূলক। অনুগ্রহ করে পেমেন্ট মেথড হিসেবে বিকাশ, নগদ বা রকেট নির্বাচন করে অর্ডার করুন।'
                ], 422);
            }
        }
        // ========================================

        try {

            // Get the product
            // $product = Product::findOrFail($request->product_id);

            // Check stock availability
            // if ($request->has('variations') && is_array($request->variations) && count($request->variations) > 0) {
            //     // For variable products with variations
            //     foreach ($request->variations as $variation) {
            //         $variationOption = VariationOption::find($variation['option_id'] ?? null);
            //         if (!$this->stockService->checkStock($product, $variationOption, $request->pqty)) {
            //             return response()->json([
            //                 'success' => false,
            //                 'message' => "Sorry, {$product->title} with selected options is out of stock or doesn't have enough quantity."
            //             ], 422);
            //         }
            //     }
            // } else {
            //     // For simple products or direct option_id
            //     $variationOption = $request->option_id ? VariationOption::find($request->option_id) : null;
            //     if (!$this->stockService->checkStock($product, $variationOption, $request->pqty)) {
            //         return response()->json([
            //             'success' => false,
            //             'message' => "Sorry, {$product->title} is out of stock or doesn't have enough quantity."
            //         ], 422);
            //     }
            // }

            // Check stock availability for buy now orders
            if ($product->product_type === 'variable' && $request->has('combination_id')) {
                // For variable products with combinations
                $combination = VariationCombination::find($request->combination_id);
                if (!$combination || !$this->stockService->checkStock($product, $combination, $request->pqty)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Sorry, {$product->title} with selected options is out of stock or doesn't have enough quantity."
                    ], 422);
                }
            } else {
                // For simple products
                if (!$this->stockService->checkStock($product, null, $request->pqty)) {
                    return response()->json([
                        'success' => false,
                        'message' => "Sorry, {$product->title} is out of stock or doesn't have enough quantity."
                    ], 422);
                }
            }

            // Authenticate or create a guest user
            if (Auth::check()) {
                $user = Auth::user();
            } else {
                $password = bcrypt('password');
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $this->generateUniqueEmail(),
                    'password' => $password,
                    'phone'    => $request->phone,
                    'upazila'    => $request->upazila,
                    'address' => $request->address,
                    'city'    => $request->city,
                ]);
                Auth::login($user);
            }

            $smsEnabled = false;
            // Check if OTP is needed (only if not verified and COD is selected)
            if (!$user->otp_verified && $request->payment_method === 'cod' && $smsEnabled == true) {

                // Normalize the phone number by removing +88 or 88 prefix if present
                $normalizedPhone = $request->phone;
                if (strpos($normalizedPhone, '+88') === 0) {
                    $normalizedPhone = substr($normalizedPhone, 3);
                } else if (strpos($normalizedPhone, '88') === 0) {
                    $normalizedPhone = substr($normalizedPhone, 2);
                }

                // Check if this phone number has been verified by any user
                $verifiedUser = User::where(function ($query) use ($normalizedPhone) {
                    $query->where('phone', $normalizedPhone)
                        ->orWhere('phone', '+88' . $normalizedPhone)
                        ->orWhere('phone', '88' . $normalizedPhone);
                })->where('otp_verified', true)->first();


                // If phone is already verified by another user, mark this user as verified too
                if ($verifiedUser) {
                    $user->update(['otp_verified' => true]);

                    // Calculate total with charge
                    $totalWithCharge = $request->price + ($request->shipping) +
                        ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                        ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                        ($request->payment_method === 'rocket' ? $request->rocket_charge : 0);
                    $isCod = $request->payment_method === 'cod';
                    $isAutomatedGateway = \App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($request->payment_method);
                    $isPaymentPending = $isCod || $isAutomatedGateway;

                    // Proceed to place the order directly
                    $order = order::create([
                        'name'           => $request->name,
                        'address'        => $request->address,
                        'phone'          => $request->phone,
                        'upazila'        => $request->upazila,
                        'city'           => $request->city,
                        'message'        => $request->message,
                        'shipping'       => $request->shipping,
                        'total'          => $totalWithCharge,
                        'payment_method' => $request->payment_method,
                        'user_id'        => $user->id,
                        'order_source'   => 'Website',
                        'ip_address'     => $request->ip(),
                        // Add payment gateway fields
                        'bkash_number' => $request->payment_method === 'bkash' ? $request->bkash_number : null,
                        'bkash_transaction_id' => $request->payment_method === 'bkash' ? $request->bkash_transaction_id : null,
                        'bkash_charge' => $request->payment_method === 'bkash' ? $request->bkash_charge : 0,

                        'nagad_number' => $request->payment_method === 'nagad' ? $request->nagad_number : null,
                        'nagad_transaction_id' => $request->payment_method === 'nagad' ? $request->nagad_transaction_id : null,
                        'nagad_charge' => $request->payment_method === 'nagad' ? $request->nagad_charge : 0,

                        'rocket_number' => $request->payment_method === 'rocket' ? $request->rocket_number : null,
                        'rocket_transaction_id' => $request->payment_method === 'rocket' ? $request->rocket_transaction_id : null,
                        'rocket_charge' => $request->payment_method === 'rocket' ? $request->rocket_charge : 0,

                        // Add total with charge
                        'total_with_charge' => $totalWithCharge,
                        // Payment type: COD/automated gateway = due (pending), manual online = full_paid
                        'payment_type' => $isPaymentPending ? 'due' : 'full_paid',
                        'paid_amount' => $isPaymentPending ? 0 : $totalWithCharge,
                        'due_amount' => $isPaymentPending ? $totalWithCharge : 0,
                        'payment_status' => $isPaymentPending ? 'pending' : 'paid',
                    ]);

                    // Create order items
                    // Snapshot COGS at order time
                    $product = Product::find($request->product_id);
                    $productCost = $product->product_cost ?? 0;

                    if ($request->has('variations') && is_array($request->variations) && count($request->variations) > 0) {
                        // Create an order item for each variation
                        foreach ($request->variations as $variation) {
                            order_item::create([
                                'order_id'   => $order->id,
                                'product_id' => $request->product_id,
                                'option_id'  => $variation['option_id'] ?? null,
                                'quantity'   => $request->pqty,
                                'price'      => $variation['price'] ?? 0,
                                'sub_total'  => ($variation['price'] ?? 0),
                                'unit_cost'  => $productCost,
                                'total_cost' => $productCost * $request->pqty,
                            ]);
                        }
                    } else {
                        // Create a single order item if no variations
                        order_item::create([
                            'order_id'   => $order->id,
                            'product_id' => $request->product_id,
                            'option_id'  => $request->option_id,
                            'quantity'   => $request->pqty,
                            'price'      => $request->price,
                            'sub_total'  => $request->price,
                            'unit_cost'  => $productCost,
                            'total_cost' => $productCost * $request->pqty,
                        ]);
                    }

                    // Delete incomplete orders for this phone number (using normalized phone)
                    IncompleteOrder::deleteByPhone($order->phone);

                    // Store pending purchase event (service checks if enabled for this payment method)
                    $this->pendingPurchaseEventService->storePendingEvent($order, $request);

                    $response = [
                        'success' => true,
                        'message' => 'Order placed successfully!',
                        'order_id' => $order->id,
                    ];
                    if (\App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($request->payment_method)) {
                        $response['requires_redirect'] = true;
                        $response['provider'] = $request->payment_method;
                    }
                    return response()->json($response);
                }

                $otp = rand(1000, 9999);

                // Update OTP and expiration in the database
                $userUpdated = $user->update([
                    'otp_code'       => $otp,
                    'otp_expires_at' => now()->addMinutes(10),
                ]);

                if ($userUpdated) {
                    // Store order details in session
                    session([
                        'pending_buynow_order' => [
                            'name'           => $request->name,
                            'address'        => $request->address,
                            'phone'          => $request->phone,
                            'upazila'        => $request->upazila,
                            'city'           => $request->city,
                            'message'        => $request->message,
                            'shipping'       => $request->shipping,
                            'total'          => $request->price + ($request->shipping) +
                                ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                                ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                                ($request->payment_method === 'rocket' ? $request->rocket_charge : 0),
                            'payment_method' => $request->payment_method,
                            'user_id'        => $user->id,
                            'product_id'     => $request->product_id,
                            'option_id'      => $request->option_id,
                            'pqty'           => $request->pqty,
                            'price'          => $request->price,
                            'variations'     => $request->variations,
                            'bkash_number' => $request->payment_method === 'bkash' ? $request->bkash_number : null,
                            'bkash_transaction_id' => $request->payment_method === 'bkash' ? $request->bkash_transaction_id : null,
                            'bkash_charge' => $request->payment_method === 'bkash' ? $request->bkash_charge : 0,

                            'nagad_number' => $request->payment_method === 'nagad' ? $request->nagad_number : null,
                            'nagad_transaction_id' => $request->payment_method === 'nagad' ? $request->nagad_transaction_id : null,
                            'nagad_charge' => $request->payment_method === 'nagad' ? $request->nagad_charge : 0,

                            'rocket_number' => $request->payment_method === 'rocket' ? $request->rocket_number : null,
                            'rocket_transaction_id' => $request->payment_method === 'rocket' ? $request->rocket_transaction_id : null,
                            'rocket_charge' => $request->payment_method === 'rocket' ? $request->rocket_charge : 0,

                            'total_with_charge' => $request->price + ($request->shipping) +
                                ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                                ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                                ($request->payment_method === 'rocket' ? $request->rocket_charge : 0),

                            // Tracking data for delayed purchase events
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                            'fbp' => $request->cookie('_fbp'),
                            'fbc' => $request->cookie('_fbc'),
                            'utm_source' => $request->input('utm_source'),
                            'utm_medium' => $request->input('utm_medium'),
                            'utm_campaign' => $request->input('utm_campaign'),
                            'utm_content' => $request->input('utm_content'),
                            'utm_term' => $request->input('utm_term'),
                            'fbclid' => $request->input('fbclid'),
                            'gclid' => $request->input('gclid'),
                            'ttclid' => $request->input('ttclid'),
                        ]
                    ]);

                    // Send OTP to the user's phone
                    $this->smsService->sendSMS($user->phone, "Dear {$user->name}\nYour Phone OTP Verification Code is : {$otp}\nThank you from Thikana Shop");


                    return response()->json([
                        'status'  => 'otp_sent',
                        'message' => 'OTP sent to your phone for verification.',
                    ]);
                } else {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Failed to update OTP.',
                    ]);
                }
            }

            // Calculate total with charge
            $totalWithCharge = $request->price + ($request->shipping) +
                ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                ($request->payment_method === 'rocket' ? $request->rocket_charge : 0);
            $isCod = $request->payment_method === 'cod';
            $isAutomatedGateway = \App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($request->payment_method);
            $isPaymentPending = $isCod || $isAutomatedGateway;

            // Proceed to place the order if OTP is already verified or not required
            $order = order::create([
                'name'           => $request->name,
                'address'        => $request->address,
                'phone'          => $request->phone,
                'upazila'        => $request->upazila,
                'city'           => $request->city,
                'message'        => $request->message,
                'shipping'       => $request->shipping,
                'total'          => $totalWithCharge,
                'payment_method' => $request->payment_method,
                'user_id'        => $user->id,
                'order_source'   => 'Website',
                'ip_address'     => $request->ip(),
                // Add payment gateway fields
                'bkash_number' => $request->payment_method === 'bkash' ? $request->bkash_number : null,
                'bkash_transaction_id' => $request->payment_method === 'bkash' ? $request->bkash_transaction_id : null,
                'bkash_charge' => $request->payment_method === 'bkash' ? $request->bkash_charge : 0,

                'nagad_number' => $request->payment_method === 'nagad' ? $request->nagad_number : null,
                'nagad_transaction_id' => $request->payment_method === 'nagad' ? $request->nagad_transaction_id : null,
                'nagad_charge' => $request->payment_method === 'nagad' ? $request->nagad_charge : 0,

                'rocket_number' => $request->payment_method === 'rocket' ? $request->rocket_number : null,
                'rocket_transaction_id' => $request->payment_method === 'rocket' ? $request->rocket_transaction_id : null,
                'rocket_charge' => $request->payment_method === 'rocket' ? $request->rocket_charge : 0,

                // Add total with charge
                'total_with_charge' => $totalWithCharge,
                // Payment type: COD/automated gateway = due (pending), manual online = full_paid
                'payment_type' => $isPaymentPending ? 'due' : 'full_paid',
                'paid_amount' => $isPaymentPending ? 0 : $totalWithCharge,
                'due_amount' => $isPaymentPending ? $totalWithCharge : 0,
                'payment_status' => $isPaymentPending ? 'pending' : 'paid',
            ]);

            if ($order) {
                // Handle variable products with combinations
                if ($product->product_type === 'variable' && $request->has('combination_id')) {
                    // Verify combination is valid and update stock
                    $combination = VariationCombination::where('id', $request->combination_id)
                        ->where('product_id', $product->id)
                        ->where('is_active', true)
                        ->first();

                    if (!$combination || !$this->stockService->checkStock($product, $combination, $request->pqty)) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Selected variation is no longer available with the requested quantity.'
                        ], 422);
                    }

                    // Create order item with combination reference + COGS snapshot
                    $unitCost = $combination->product_cost ?? $product->product_cost ?? 0;
                    order_item::create([
                        'order_id'       => $order->id,
                        'product_id'     => $request->product_id,
                        'combination_id' => $request->combination_id,
                        'option_id'      => null,
                        'quantity'       => $request->pqty,
                        'price'          => $request->price,
                        'sub_total'      => $request->price,
                        'unit_cost'      => $unitCost,
                        'total_cost'     => $unitCost * $request->pqty,
                    ]);

                    // Update combination stock using StockManagementService
                    $this->stockService->updateVariationCombinationStock(
                        $combination,
                        -$request->pqty,
                        'sale',
                        $order->id,
                        'Stock sold for order #' . $order->id,
                        'Order'
                    );
                } else {
                    // Handle simple products or legacy variation system
                    $unitCost = $product->product_cost ?? 0;
                    order_item::create([
                        'order_id'   => $order->id,
                        'product_id' => $request->product_id,
                        'option_id'  => $request->option_id ?? null,
                        'quantity'   => $request->pqty,
                        'price'      => $request->price,
                        'sub_total'  => $request->price,
                        'unit_cost'  => $unitCost,
                        'total_cost' => $unitCost * $request->pqty,
                    ]);

                    // Update product stock for simple products using StockManagementService
                    if ($product->product_type === 'simple') {
                        $this->stockService->updateSimpleProductStock(
                            $product,
                            -$request->pqty,
                            'sale',
                            $order->id,
                            'Stock sold for order #' . $order->id,
                            'Order'
                        );
                    }
                }

                // Mark OTP as verified for future orders
                if (!$user->otp_verified) {
                    $user->update(['otp_verified' => false]);
                }

                // Store pending purchase event (service checks if enabled for this payment method)
                $this->pendingPurchaseEventService->storePendingEvent($order, $request);

                // Delete incomplete orders for this phone number (using normalized phone)
                IncompleteOrder::deleteByPhone($order->phone);

                $response = [
                    'success' => true,
                    'message' => 'Order placed successfully!',
                    'order_id' => $order->id,
                ];
                if (\App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($request->payment_method)) {
                    $response['requires_redirect'] = true;
                    $response['provider'] = $request->payment_method;
                }
                return response()->json($response);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to place the order. Please try again.',
            ], 500);
        } catch (\Exception $e) {
            // Log the exception


            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your order. Please try again.',
            ], 500);
        }
    }

    // Helper method to generate unique email for guest users
    private function generateUniqueEmail()
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?? 'uddoktaecommerce.com';
        do {
            $email = 'guest_' . uniqid() . '@' . $host;
        } while (User::where('email', $email)->exists());

        return $email;
    }

    public function verifyBuynowOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4'
        ]);

        $user = Auth::user();

        // Check if OTP is valid and not expired
        if (
            $user->otp_code == $request->otp &&
            $user->otp_expires_at &&
            now()->lessThan($user->otp_expires_at)
        ) {
            // Retrieve the pending order details from the session
            $orderDetails = session('pending_buynow_order');

            if ($orderDetails) {
                // Payment type: COD/automated gateway = due (pending), manual online = full_paid
                $isCod = $orderDetails['payment_method'] === 'cod';
                $isAutomatedGateway = \App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($orderDetails['payment_method']);
                $isPaymentPending = $isCod || $isAutomatedGateway;

                // Create the order
                $order = order::create([
                    'name'           => $orderDetails['name'],
                    'address'        => $orderDetails['address'],
                    'phone'          => $orderDetails['phone'],
                    'upazila'        => $orderDetails['upazila'],
                    'city'           => $orderDetails['city'],
                    'message'        => $orderDetails['message'],
                    'shipping'       => $orderDetails['shipping'],
                    'total'          => $orderDetails['total'],
                    'payment_method' => $orderDetails['payment_method'],
                    'user_id'        => $orderDetails['user_id'],
                    'order_source'   => 'Website',
                    // Add payment gateway specific fields
                    'bkash_number' => $orderDetails['payment_method'] === 'bkash' ? $orderDetails['bkash_number'] : null,
                    'bkash_transaction_id' => $orderDetails['payment_method'] === 'bkash' ? $orderDetails['bkash_transaction_id'] : null,
                    'bkash_charge' => $orderDetails['payment_method'] === 'bkash' ? $orderDetails['bkash_charge'] : 0,

                    'nagad_number' => $orderDetails['payment_method'] === 'nagad' ? $orderDetails['nagad_number'] : null,
                    'nagad_transaction_id' => $orderDetails['payment_method'] === 'nagad' ? $orderDetails['nagad_transaction_id'] : null,
                    'nagad_charge' => $orderDetails['payment_method'] === 'nagad' ? $orderDetails['nagad_charge'] : 0,

                    'rocket_number' => $orderDetails['payment_method'] === 'rocket' ? $orderDetails['rocket_number'] : null,
                    'rocket_transaction_id' => $orderDetails['payment_method'] === 'rocket' ? $orderDetails['rocket_transaction_id'] : null,
                    'rocket_charge' => $orderDetails['payment_method'] === 'rocket' ? $orderDetails['rocket_charge'] : 0,

                    // Payment type fields
                    'payment_type' => $isPaymentPending ? 'due' : 'full_paid',
                    'paid_amount' => $isPaymentPending ? 0 : $orderDetails['total'],
                    'due_amount' => $isPaymentPending ? $orderDetails['total'] : 0,
                    'payment_status' => $isPaymentPending ? 'pending' : 'paid',
                ]);

                if ($order) {
                    // Snapshot COGS at order time
                    $product = Product::find($orderDetails['product_id']);
                    $productCost = $product->product_cost ?? 0;

                    // Check if we have variations
                    if (isset($orderDetails['variations']) && is_array($orderDetails['variations']) && count($orderDetails['variations']) > 0) {
                        // Create an order item for each variation
                        foreach ($orderDetails['variations'] as $variation) {
                            order_item::create([
                                'order_id'   => $order->id,
                                'product_id' => $orderDetails['product_id'],
                                'option_id'  => $variation['option_id'] ?? null,
                                'quantity'   => $orderDetails['pqty'],
                                'price'      => $variation['price'] ?? 0,
                                'sub_total'  => ($variation['price'] ?? 0),
                                'unit_cost'  => $productCost,
                                'total_cost' => $productCost * $orderDetails['pqty'],
                            ]);
                        }
                    } else {
                        // Create a single order item if no variations
                        order_item::create([
                            'order_id'   => $order->id,
                            'product_id' => $orderDetails['product_id'],
                            'option_id'  => $orderDetails['option_id'],
                            'quantity'   => $orderDetails['pqty'],
                            'price'      => $orderDetails['price'],
                            'sub_total'  => $orderDetails['price'],
                            'unit_cost'  => $productCost,
                            'total_cost' => $productCost * $orderDetails['pqty'],
                        ]);
                    }

                    // Process stock reduction for the order
                    if (isset($orderDetails['combination_id'])) {
                        // Variable product with combination
                        $combination = VariationCombination::find($orderDetails['combination_id']);
                        if ($combination) {
                            $this->stockService->updateVariationCombinationStock(
                                $combination,
                                -$orderDetails['pqty'],
                                'sale',
                                $order->id,
                                'Stock sold for order #' . $order->id,
                                'Order'
                            );
                        }
                    } else {
                        // Simple product
                        $product = Product::find($orderDetails['product_id']);
                        if ($product && $product->product_type === 'simple') {
                            $this->stockService->updateSimpleProductStock(
                                $product,
                                -$orderDetails['pqty'],
                                'sale',
                                $order->id,
                                'Stock sold for order #' . $order->id,
                                'Order'
                            );
                        }
                    }

                    // Mark OTP as verified for future orders
                    $user->update(['otp_verified' => true]);

                    // Store pending purchase event (service checks if enabled for this payment method)
                    // Pass tracking data from session since current request doesn't have original cookies
                    $trackingData = [
                        'ip_address' => $orderDetails['ip_address'] ?? null,
                        'user_agent' => $orderDetails['user_agent'] ?? null,
                        'fbp' => $orderDetails['fbp'] ?? null,
                        'fbc' => $orderDetails['fbc'] ?? null,
                        'utm_source' => $orderDetails['utm_source'] ?? null,
                        'utm_medium' => $orderDetails['utm_medium'] ?? null,
                        'utm_campaign' => $orderDetails['utm_campaign'] ?? null,
                        'utm_content' => $orderDetails['utm_content'] ?? null,
                        'utm_term' => $orderDetails['utm_term'] ?? null,
                        'fbclid' => $orderDetails['fbclid'] ?? null,
                        'gclid' => $orderDetails['gclid'] ?? null,
                        'ttclid' => $orderDetails['ttclid'] ?? null,
                    ];
                    $this->pendingPurchaseEventService->storePendingEvent($order, null, $trackingData);

                    // Clear OTP and session
                    session()->forget('pending_buynow_order');

                    // Delete incomplete orders for this phone number (using normalized phone)
                    IncompleteOrder::deleteByPhone($order->phone);

                    $response = [
                        'status'  => 'success',
                        'message' => 'Order placed successfully!',
                        'order_id' => $order->id,
                    ];
                    if (\App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($orderDetails['payment_method'] ?? '')) {
                        $response['requires_redirect'] = true;
                        $response['provider'] = $orderDetails['payment_method'];
                    }
                    return response()->json($response);
                }
            }
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Invalid or expired OTP'
        ], 400);
    }

    //Destroy Cart
    public function destroy(Request $request, $id)
    {
        // Check if we're removing all items of a product
        if ($request->has('remove_all') && $request->remove_all) {
            // Get the authenticated user's ID or guest ID
            $userId = Auth::id();
            $guestId = $request->cookie('guest_id');

            // Build the query to find all cart items for this product
            $query = Cart::where('product_id', $id);

            if ($userId) {
                $query->where('user_id', $userId);
            } elseif ($guestId) {
                $query->where('guest_id', $guestId);
            }

            // Delete all matching cart items
            $deleted = $query->delete();

            if ($deleted) {
                if ($request->expectsJson()) {
                    return response()->json($this->buildCartSidebarPayload($request) + [
                        'message' => 'All items of this product removed from cart'
                    ]);
                }
                return redirect()->back()->with('success', 'All items of this product removed from cart');
            }
        } else {
            // Find and delete a specific cart item by its ID
            $cart = Cart::find($id);

            if ($cart) {
                $cart->delete();
                if ($request->expectsJson()) {
                    return response()->json($this->buildCartSidebarPayload($request) + [
                        'message' => 'Item removed from cart successfully'
                    ]);
                }
                return redirect()->back()->with('success', 'Item removed from cart successfully');
            }
        }

        // If we get here, nothing was deleted
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found in your cart'
            ], 404);
        }
        return redirect()->back()->with('error', 'Item not found in your cart');
    }

    // Checkout Shows
    public function checkout()
    {
        $sub_total = 0;
        $carts = collect();

        if (Auth::check()) {
            $carts = Cart::with(['product', 'variationCombination', 'comboOffer'])
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $guestId = request()->cookie('guest_id');
            if ($guestId) {
                $carts = Cart::with(['product', 'variationCombination', 'comboOffer'])
                    ->where('guest_id', $guestId)
                    ->get();
            }
        }

        // Check stock before processing
        foreach ($carts as $item) {
            $product = $item->product;
            $combination = $item->combination_id ? VariationCombination::find($item->combination_id) : null;

            if (!$this->stockService->checkStock($product, $combination, $item->qunt)) {
                return back()->with('error', "Sorry, {$product->title} is out of stock or doesn't have enough quantity.");
            }
        }

        // Calculate the subtotal and add calculated_subtotal to each item
        foreach ($carts as $item) {
            $item->calculated_subtotal = $item->price * $item->qunt;
            $sub_total += $item->calculated_subtotal;
        }



        // Group the carts by product after calculations
        $groupedCarts = $carts->groupBy('product_id');
        
        // Use new shipping calculation service
        $shippingService = new ShippingCalculationService();
        
        // Prepare cart items for shipping calculation
        $cartItems = $carts->map(function($cart) {
            return [
                'product_id' => $cart->product_id,
                'quantity' => $cart->qunt,
                'price' => $cart->price,
                'subtotal' => $cart->calculated_subtotal
            ];
        })->toArray();
        
        // Calculate shipping using the new service
        $shippingResult = $shippingService->calculateShipping($sub_total, $cartItems);
        $shipping = $shippingResult['cost'];
        
        // Get shipping options for display (this will now include delivery area rules)
        // We need to create a new service instance to get options with cart items
        $optionsService = new ShippingCalculationService();
        $optionsService->setCartItems($cartItems);
        $shippingOptions = $optionsService->getShippingOptions();
        
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
        // Check if we have delivery area rules from the service
        if (count($shippingOptions) > 0) {
            $activeShippingOptions = $shippingOptions;
        } else {
            $activeShippingOptions = array_filter($shippingSetting->shipping_options ?? [], fn($option) => $option['active'] ?? false);
        }
        
        // Get specific shipping rules for all products in cart
        $specificShippingRules = collect();
        foreach ($carts as $cart) {
            $productRules = $cart->product->shippingRules()->active()->get();
            $specificShippingRules = $specificShippingRules->merge($productRules);
        }

        return view('frontend.checkout.v1', compact(
            'groupedCarts',
            'carts', // Pass individual carts with calculated_subtotal
            'sub_total',
            'shipping',
            'shippingSetting',
            'activeShippingOptions',
            'specificShippingRules'
        ));
    }

    public function cartCount()
    {
        $userId = \Auth::id();
        $guestId = request()->cookie('guest_id');
        $query = \App\Models\Cart::query();

        if ($userId) {
            $query->where('user_id', $userId);
        } elseif ($guestId) {
            $query->where('guest_id', $guestId);
        } else {
            return response()->json([
                'count' => 0,
                'total' => 0,
                'html' => view('frontend.partials.free-shipping-progress', ['cartTotal' => 0])->render()
            ]);
        }

        $carts = $query->with('product')->get();
        $count = $carts->sum('qunt');
        $total = $carts->sum(function($c) { return $c->calculated_subtotal ?? ($c->price * $c->qunt); });

        // Get positioning settings for the partial
        $bottomDesktop = \App\Services\SettingsService::get('general', 'free_shipping_progress_bottom_desktop', '0');
        $rightDesktop = \App\Services\SettingsService::get('general', 'free_shipping_progress_right_desktop', '69');
        $bottomMobile = \App\Services\SettingsService::get('general', 'free_shipping_progress_bottom_mobile', '37');
        $rightMobile = \App\Services\SettingsService::get('general', 'free_shipping_progress_right_mobile', '59');

        return response()->json([
            'count' => $count,
            'total' => $total,
            'html' => view('frontend.partials.free-shipping-progress', [
                'carts' => $carts,
                'cartTotal' => $total,
                'bottomDesktop' => $bottomDesktop,
                'rightDesktop' => $rightDesktop,
                'bottomMobile' => $bottomMobile,
                'rightMobile' => $rightMobile
            ])->render()
        ]);
    }

    /**
     * Return rendered sidebar cart for drawer view
     */
    public function sidebar(Request $request)
    {
        return response()->json($this->buildCartSidebarPayload($request));
    }

    /**
     * Build the sidebar payload with items and totals
     */
    protected function buildCartSidebarPayload(Request $request): array
    {
        $carts = $this->getCurrentCarts($request);
        $subTotal = $carts->sum(function ($item) {
            return $item->calculated_subtotal ?? ($item->price * $item->qunt);
        });

        return [
            'success' => true,
            'count' => (int) $carts->sum('qunt'),
            'total' => $subTotal,
            'html' => view('frontend.partials.cart-sidebar-items', [
                'carts' => $carts,
                'subTotal' => $subTotal,
            ])->render(),
        ];
    }

    /**
     * Fetch current user's/guest's cart with relationships
     */
    protected function getCurrentCarts(Request $request)
    {
        $query = Cart::with(['product', 'variationCombination', 'comboOffer']);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } elseif ($request->cookie('guest_id')) {
            $query->where('guest_id', $request->cookie('guest_id'));
        } else {
            return collect();
        }

        $carts = $query->get();

        foreach ($carts as $item) {
            $item->calculated_subtotal = $item->price * $item->qunt;
        }

        return $carts;
    }

    /**
     * Update cart item quantity with AJAX
     */
    public function updateQuantity(Request $request)
    {
        try {
            $request->validate([
                'cart_id' => 'required|integer|exists:carts,id',
                'action' => 'required|in:increase,decrease,set'
            ]);

            // Only validate quantity when action is 'set'
            if ($request->action === 'set') {
                $request->validate([
                    'quantity' => 'required|integer|min:1'
                ]);
            }

            $cartItem = Cart::with(['product', 'variationCombination'])->findOrFail($request->cart_id);
            
            // Determine new quantity based on action
            $newQuantity = $cartItem->qunt;
            switch ($request->action) {
                case 'increase':
                    $newQuantity = $cartItem->qunt + 1;
                    break;
                case 'decrease':
                    $newQuantity = max(1, $cartItem->qunt - 1);
                    break;
                case 'set':
                    $newQuantity = $request->quantity;
                    break;
            }

            // Check stock availability
            $product = $cartItem->product;
            $combination = $cartItem->variationCombination;
            
            if (!$this->stockService->checkStock($product, $combination, $newQuantity)) {
                $maxAvailable = $combination ? $combination->stock_quantity : $product->quantity;
                return response()->json([
                    'success' => false,
                    'message' => "Sorry, only {$maxAvailable} items available in stock.",
                    'max_quantity' => $maxAvailable
                ], 422);
            }

            // Update quantity
            $cartItem->update(['qunt' => $newQuantity]);

            // Recalculate cart totals
            $userId = Auth::id();
            $guestId = $request->cookie('guest_id');
            
            $query = Cart::query();
            if ($userId) {
                $query->where('user_id', $userId);
            } elseif ($guestId) {
                $query->where('guest_id', $guestId);
            }
            
            $carts = $query->get();
            $subTotal = $carts->sum(function ($item) {
                return $item->price * $item->qunt;
            });

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully',
                'new_quantity' => $newQuantity,
                'new_total' => $cartItem->price * $newQuantity,
                'sub_total' => $subTotal,
                'cart_count' => $carts->sum('qunt')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating quantity: ' . $e->getMessage()
            ], 500);
        }
    }
}
