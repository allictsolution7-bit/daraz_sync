<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\User;
use App\Models\order;
use App\Models\Product;
use App\Models\order_item;
use Illuminate\Support\Str;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\IncompleteOrder;
use App\Services\Delivery\DeliveryServiceManager;
use App\Services\StockManagementService;
use App\Services\FraudProtectionService;
use App\Services\PendingPurchaseEventService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }




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

    public function asignedorders(request $request)
    {
        // Retrieve status filter from the request
        $status = $request->get('status');

        $authUserId = Auth::id();

        $assignedOrdersQuery = order::query()
            ->where(function ($query) use ($authUserId) {
                $query->where('assigned_to', $authUserId)
                    ->orWhere(function ($legacy) use ($authUserId) {
                        $legacy->whereNull('assigned_to')
                            ->where('assign', $authUserId);
                    });
            });

        $orders = (clone $assignedOrdersQuery)
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->with('products')
            ->orderBy('updated_at', 'desc')
            ->get();

        $statusCounts = [
            'all' => (clone $assignedOrdersQuery)->count(),
            'pending' => (clone $assignedOrdersQuery)->where('status', 'pending')->count(),
            'phone_not_rcv' => (clone $assignedOrdersQuery)->where('status', 'phone_not_rcv')->count(),
            'follow_up' => (clone $assignedOrdersQuery)->where('status', 'follow_up')->count(),
            'processing' => (clone $assignedOrdersQuery)->where('status', 'processing')->count(),
            'ready_for_delivery' => (clone $assignedOrdersQuery)->where('status', 'ready_for_delivery')->count(),
            'shipped' => (clone $assignedOrdersQuery)->where('status', 'shipped')->count(),
            'delivered' => (clone $assignedOrdersQuery)->where('status', 'delivered')->count(),
            'on_hold' => (clone $assignedOrdersQuery)->where('status', 'on_hold')->count(),
            'cancelled' => (clone $assignedOrdersQuery)->where('status', 'cancelled')->count(),
        ];

        $assignableStaff = User::whereHas('roles', function ($query) {
                $query->where('name', '!=', 'user');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        // Return the orders view with the retrieved orders
        return view('admin.orders', compact('orders', 'statusCounts', 'assignableStaff'));
    }

    public function updateStatus(Request $request)
    {
        $order = order::find($request->order_id);

        if ($order) {
            $order->status = $request->status;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Order status updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Order not found']);
    }

    public function updateNote(Request $request)
    {
        $order = order::find($request->order_id);

        if ($order) {
            $order->admin_note = $request->note;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Note updated successfully']);
        }

        return response()->json(['success' => false, 'message' => 'Order not found']);
    }


    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name'           => 'required|string',
            'address'        => 'required|string',
            'phone'          => 'required|string',
            'upazila'        => 'nullable|string',
            'city'           => 'nullable|string',
            'message'        => 'nullable|string',
            'payment_method' => 'required|string|in:cod,bkash,nagad,rocket,' . implode(',', \App\Models\PaymentGateway::enabled()->pluck('provider')->toArray()),

            // Bkash validation
            'bkash_number' => 'required_if:payment_method,bkash|string|nullable',
            'bkash_transaction_id' => 'required_if:payment_method,bkash|string|nullable',
            'bkash_charge' => 'nullable|numeric',

            // Nagad validation
            'nagad_number' => 'required_if:payment_method,nagad|string|nullable',
            'nagad_transaction_id' => 'required_if:payment_method,nagad|string|nullable',
            'nagad_charge' => 'nullable|numeric',

            // Rocket validation
            'rocket_number' => 'required_if:payment_method,rocket|string|nullable',
            'rocket_transaction_id' => 'required_if:payment_method,rocket|string|nullable',
            'rocket_charge' => 'nullable|numeric',
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

        $total = 0;

        try {
            // Authenticate or create a guest user
            if (Auth::check()) {
                $userId = Auth::id();
                $carts = Cart::with(['product', 'comboOffer'])->where("user_id", $userId)->get();
                $user = Auth::user();
            } else {
                $password = bcrypt('password');
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => User::generateUniqueEmail($request->name, 'guest'),
                    'password' => $password,
                    'phone'    => $request->phone,
                    'upazila'    => $request->upazila,
                    'address' => $request->address,
                    'city'    => $request->city,
                ]);

                $userId = $user->id;
                $guestId = $request->cookie('guest_id');
                $carts = Cart::with(['product', 'comboOffer'])->where("guest_id", $guestId)->get();

                Auth::login($user);
            }

            // Check if any cart items are combo items
            $hasComboItems = $carts->where('combo_offer_id', '!=', null)->count() > 0;

            if ($carts->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart is empty. Cannot place an order.',
                ], 422);
            }

            // Check if COD is selected and any product requires advance delivery payment
            if ($request->payment_method === 'cod') {
                $hasAdvanceDelivery = false;
                foreach ($carts as $cart) {
                    if ($cart->product && $cart->product->pay_advance_delivery) {
                        $hasAdvanceDelivery = true;
                        break;
                    }
                }
                if ($hasAdvanceDelivery) {
                    return response()->json([
                        'success' => false,
                        'message' => 'এই অর্ডারে থাকা কিছু পণ্যের জন্য অগ্রিম ডেলিভারি চার্জ পরিশোধ করা বাধ্যতামূলক। অনুগ্রহ করে পেমেন্ট মেথড হিসেবে বিকাশ, নগদ বা রকেট নির্বাচন করে অর্ডার করুন।'
                    ], 422);
                }
            }

            foreach ($carts as $cart) {
                $total += $cart->price * $cart->qunt;
            }

            $smsEnabled = false;
            // Handling payment method
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

                    // Proceed to place the order directly
                    $orderData = [
                        'name'           => $request->name,
                        'address'        => $request->address,
                        'phone'          => $request->phone,
                        'upazila'        => $request->upazila,
                        'city'           => $request->city,
                        'message'        => $request->message,
                        'shipping'       => $request->shipping,
                        'total'          => $total + ($request->shipping) +
                            ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                            ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                            ($request->payment_method === 'rocket' ? $request->rocket_charge : 0),
                        'payment_method' => $request->payment_method,
                        'user_id'        => $userId,
                        'order_source'   => 'Website',
                        'ip_address'     => $request->ip(),
                    ];

                    // Add combo order fields if there are combo items
                    if ($hasComboItems) {
                        $comboCartItem = $carts->where('combo_offer_id', '!=', null)->first();
                        if ($comboCartItem) {
                            $orderData['is_combo_order'] = true;
                            $orderData['combo_offer_id'] = $comboCartItem->combo_offer_id;
                            $orderData['combo_selections'] = $comboCartItem->combo_selections;
                            

                        }
                    }

                    // Add payment gateway fields to order data
                    $orderData['bkash_number'] = $request->payment_method === 'bkash' ? $request->bkash_number : null;
                    $orderData['bkash_transaction_id'] = $request->payment_method === 'bkash' ? $request->bkash_transaction_id : null;
                    $orderData['bkash_charge'] = $request->payment_method === 'bkash' ? $request->bkash_charge : 0;

                    $orderData['nagad_number'] = $request->payment_method === 'nagad' ? $request->nagad_number : null;
                    $orderData['nagad_transaction_id'] = $request->payment_method === 'nagad' ? $request->nagad_transaction_id : null;
                    $orderData['nagad_charge'] = $request->payment_method === 'nagad' ? $request->nagad_charge : 0;

                    $orderData['rocket_number'] = $request->payment_method === 'rocket' ? $request->rocket_number : null;
                    $orderData['rocket_transaction_id'] = $request->payment_method === 'rocket' ? $request->rocket_transaction_id : null;
                    $orderData['rocket_charge'] = $request->payment_method === 'rocket' ? $request->rocket_charge : 0;

                    // Calculate total with charge
                    $orderData['total_with_charge'] = $total + ($request->shipping) +
                        ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                        ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                        ($request->payment_method === 'rocket' ? $request->rocket_charge : 0);

                    // Payment type: COD/automated gateway = due (pending), manual online = full_paid
                    $isCod = $request->payment_method === 'cod';
                    $isAutomatedGateway = \App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($request->payment_method);
                    $isPaymentPending = $isCod || $isAutomatedGateway;
                    $orderData['payment_type'] = $isPaymentPending ? 'due' : 'full_paid';
                    $orderData['paid_amount'] = $isPaymentPending ? 0 : $orderData['total_with_charge'];
                    $orderData['due_amount'] = $isPaymentPending ? $orderData['total_with_charge'] : 0;
                    $orderData['payment_status'] = $isPaymentPending ? 'pending' : 'paid';

                    $order = order::create($orderData);

                    if ($order) {
                        foreach ($carts as $cart) {
                            // Snapshot COGS at order time
                            $unitCost = 0;
                            if ($cart->product->product_type === 'variable' && $cart->combination_id) {
                                $combo = VariationCombination::find($cart->combination_id);
                                $unitCost = $combo->product_cost ?? $cart->product->product_cost ?? 0;
                            } else {
                                $unitCost = $cart->product->product_cost ?? 0;
                            }

                            $orderItem = [
                                'order_id'   => $order->id,
                                'product_id' => $cart->product_id,
                                'quantity'   => $cart->qunt,
                                'price'      => $cart->price,
                                'sub_total'  => $cart->price * $cart->qunt,
                                'unit_cost'  => $unitCost,
                                'total_cost' => $unitCost * $cart->qunt,
                            ];

                            // Handle variation combinations for variable products
                            if ($cart->product->product_type === 'variable' && $cart->combination_id) {
                                $orderItem['combination_id'] = $cart->combination_id;
                                $orderItem['option_id'] = null;
                            } else {
                                $orderItem['combination_id'] = null;
                                $orderItem['option_id'] = null;
                            }

                            order_item::create($orderItem);
                        }

                        // Process stock reduction for all cart items (including combo items)
                        $this->stockService->processCartStockReduction($carts, $order->id);

                        // Clear the cart
                        Cart::where('user_id', $userId)->orWhere('guest_id', $guestId ?? null)->delete();

                        // Delete incomplete orders for this phone number (using normalized phone)
                        IncompleteOrder::deleteByPhone($order->phone);

                        // Auto-route global dropship items to upstream supplier admin
                        try {
                            app(\App\Services\GlobalProductService::class)->routeDropshipOrdersForCustomerOrder($order);
                        } catch (\Throwable $e) {
                            \Illuminate\Support\Facades\Log::error("Dropship auto-route error: " . $e->getMessage());
                        }
                    }

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
                    // Prepare order details for later use
                    // $orderDetails = [
                    //     'name'           => $request->name,
                    //     'address'        => $request->address,
                    //     'phone'          => $request->phone,
                    //     'upazila'        => $request->upazila,
                    //     'city'           => $request->city,
                    //     'message'        => $request->message,
                    //     'shipping'       => $request->shipping,
                    //     'total'          => $total + ($request->shipping),
                    //     'payment_method' => $request->payment_method,
                    //     'user_id'        => $userId,
                    //     'carts'          => $carts
                    // ];

                    $orderDetails = [
                        'name'           => $request->name,
                        'address'        => $request->address,
                        'phone'          => $request->phone,
                        'upazila'        => $request->upazila,
                        'city'           => $request->city,
                        'message'        => $request->message,
                        'shipping'       => $request->shipping,
                        'total'          => $total + ($request->shipping) +
                            ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                            ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                            ($request->payment_method === 'rocket' ? $request->rocket_charge : 0),
                        'payment_method' => $request->payment_method,
                        'user_id'        => $userId,
                        'carts'          => $carts,
                        'has_combo_items' => $hasComboItems,
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

                        // Calculate total with charge
                        'total_with_charge' => $total + ($request->shipping) +
                            ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                            ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                            ($request->payment_method === 'rocket' ? $request->rocket_charge : 0),
                    ];

                    // Store order details in session for later use
                    session(['pending_order' => $orderDetails]);
                    // Send OTP via SMS (implement your SMS logic here)
                    $this->smsService->sendSMS($request->phone, "Dear {$request->name}\nYour Mobile OTP Verification Code is : {$user->otp_code}\nThank you from Thikana Shop");


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
            } else {

                // Create the order for non-COD methods
                $orderData = [
                    'name'           => $request->name,
                    'address'        => $request->address,
                    'phone'          => $request->phone,
                    'upazila'        => $request->upazila,
                    'city'           => $request->city,
                    'message'        => $request->message,
                    'shipping'       => $request->shipping,
                'total'          => $total + ($request->shipping) +
                    ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                    ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                    ($request->payment_method === 'rocket' ? $request->rocket_charge : 0),
                'payment_method' => $request->payment_method,
                'user_id'        => $userId,
                'order_source'   => 'Website',
                'ip_address'     => $request->ip(),
            ];

                // Add combo order fields if there are combo items
                if ($hasComboItems) {
                    $comboCartItem = $carts->where('combo_offer_id', '!=', null)->first();
                    if ($comboCartItem) {
                        $orderData['is_combo_order'] = true;
                        $orderData['combo_offer_id'] = $comboCartItem->combo_offer_id;
                        $orderData['combo_selections'] = $comboCartItem->combo_selections;


                    }
                }

                // Add payment gateway fields to order data
                $orderData['bkash_number'] = $request->payment_method === 'bkash' ? $request->bkash_number : null;
                $orderData['bkash_transaction_id'] = $request->payment_method === 'bkash' ? $request->bkash_transaction_id : null;
                $orderData['bkash_charge'] = $request->payment_method === 'bkash' ? $request->bkash_charge : 0;

                $orderData['nagad_number'] = $request->payment_method === 'nagad' ? $request->nagad_number : null;
                $orderData['nagad_transaction_id'] = $request->payment_method === 'nagad' ? $request->nagad_transaction_id : null;
                $orderData['nagad_charge'] = $request->payment_method === 'nagad' ? $request->nagad_charge : 0;

                $orderData['rocket_number'] = $request->payment_method === 'rocket' ? $request->rocket_number : null;
                $orderData['rocket_transaction_id'] = $request->payment_method === 'rocket' ? $request->rocket_transaction_id : null;
                $orderData['rocket_charge'] = $request->payment_method === 'rocket' ? $request->rocket_charge : 0;

                // Calculate total with charge
                $orderData['total_with_charge'] = $total + ($request->shipping) +
                    ($request->payment_method === 'bkash' ? $request->bkash_charge : 0) +
                    ($request->payment_method === 'nagad' ? $request->nagad_charge : 0) +
                    ($request->payment_method === 'rocket' ? $request->rocket_charge : 0);

                // Payment type: COD/automated gateway = due (pending), manual online = full_paid
                $isCod = in_array(strtolower($request->payment_method ?? 'cod'), ['cod', 'cash_on_delivery', 'cash']);
                $isAutomatedGateway = \App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($request->payment_method);
                $isPaymentPending = $isCod || $isAutomatedGateway;
                $orderData['payment_type'] = $isPaymentPending ? 'due' : 'full_paid';
                $orderData['paid_amount'] = $isPaymentPending ? 0 : $orderData['total_with_charge'];
                $orderData['due_amount'] = $isPaymentPending ? $orderData['total_with_charge'] : 0;
                $orderData['payment_status'] = $isPaymentPending ? 'pending' : 'paid';

                $order = order::create($orderData);

                if ($order) {
                    foreach ($carts as $cart) {
                        // Snapshot COGS at order time
                        $unitCost = 0;
                        if ($cart->product->product_type === 'variable' && $cart->combination_id) {
                            $combo = VariationCombination::find($cart->combination_id);
                            $unitCost = $combo->product_cost ?? $cart->product->product_cost ?? 0;
                        } else {
                            $unitCost = $cart->product->product_cost ?? 0;
                        }

                        $orderItem = [
                            'order_id'   => $order->id,
                            'product_id' => $cart->product_id,
                            'quantity'   => $cart->qunt,
                            'price'      => $cart->price,
                            'sub_total'  => $cart->price * $cart->qunt,
                            'unit_cost'  => $unitCost,
                            'total_cost' => $unitCost * $cart->qunt,
                        ];

                        // Handle variation combinations for variable products
                        if ($cart->product->product_type === 'variable' && $cart->combination_id) {
                            $orderItem['combination_id'] = $cart->combination_id;
                            $orderItem['option_id'] = null;
                        } else {
                            $orderItem['combination_id'] = null;
                            $orderItem['option_id'] = null;
                        }

                        order_item::create($orderItem);
                    }

                    // Process stock reduction for all cart items (including combo items)
                    $this->stockService->processCartStockReduction($carts, $order->id);

                    // Mark OTP as verified for future orders
                    if (!$user->otp_verified) {
                        $user->update(['otp_verified' => false]);
                    }

                                // Clear the cart
            Cart::where('user_id', $userId)->orWhere('guest_id', $guestId ?? null)->delete();

            // Delete incomplete orders for this phone number (using normalized phone)
            IncompleteOrder::deleteByPhone($order->phone);
        }

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
        } catch (\Exception $e) {
            // Log the exception
            \Log::error("Order placement failed: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your order. Please try again.',
            ], 500);
        }
    }

    // New method for OTP verification
    public function verifyOtp(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate OTP
            if ($user->otp_code !== $request->otp || $user->otp_expires_at < now()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid or expired OTP.',
                ], 400);
            }

            // Retrieve pending order details from session
            $orderDetails = session('pending_order');

            if (!$orderDetails) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No pending order found.',
                ], 400);
            }

            // Create the order
            $orderData = [
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
                'ip_address'     => $request->ip(),
            ];

            // Add combo order fields if there are combo items
            if ($orderDetails['has_combo_items'] ?? false) {
                $comboCartItem = collect($orderDetails['carts'])->where('combo_offer_id', '!=', null)->first();
                if ($comboCartItem) {
                    $orderData['is_combo_order'] = true;
                    $orderData['combo_offer_id'] = $comboCartItem->combo_offer_id;
                    $orderData['combo_selections'] = $comboCartItem->combo_selections;
                    
                    
                }
            }

            // Add payment gateway fields to order data
            $orderData['bkash_number'] = $orderDetails['bkash_number'];
            $orderData['bkash_transaction_id'] = $orderDetails['bkash_transaction_id'];
            $orderData['bkash_charge'] = $orderDetails['bkash_charge'];
            $orderData['nagad_number'] = $orderDetails['nagad_number'];
            $orderData['nagad_transaction_id'] = $orderDetails['nagad_transaction_id'];
            $orderData['nagad_charge'] = $orderDetails['nagad_charge'];
            $orderData['rocket_number'] = $orderDetails['rocket_number'];
            $orderData['rocket_transaction_id'] = $orderDetails['rocket_transaction_id'];
            $orderData['rocket_charge'] = $orderDetails['rocket_charge'];
            $orderData['total_with_charge'] = $orderDetails['total_with_charge'];

            // Payment type: OTP verified orders are COD = due (not paid yet)
            $orderData['payment_type'] = 'due';
            $orderData['paid_amount'] = 0;
            $orderData['due_amount'] = $orderDetails['total_with_charge'];
            $orderData['payment_status'] = 'pending';

            $order = order::create($orderData);

            // Create order items
            foreach ($orderDetails['carts'] as $cart) {
                // Snapshot COGS at order time
                $unitCost = 0;
                if ($cart->product->product_type === 'variable' && $cart->combination_id) {
                    $combo = VariationCombination::find($cart->combination_id);
                    $unitCost = $combo->product_cost ?? $cart->product->product_cost ?? 0;
                } else {
                    $unitCost = $cart->product->product_cost ?? 0;
                }

                $orderItem = [
                    'order_id'   => $order->id,
                    'product_id' => $cart->product_id,
                    'quantity'   => $cart->qunt,
                    'price'      => $cart->price,
                    'sub_total'  => $cart->price * $cart->qunt,
                    'unit_cost'  => $unitCost,
                    'total_cost' => $unitCost * $cart->qunt,
                ];

                // Handle variation combinations for variable products
                if ($cart->product->product_type === 'variable' && $cart->combination_id) {
                    $orderItem['combination_id'] = $cart->combination_id;
                    $orderItem['option_id'] = null;
                } else {
                    $orderItem['combination_id'] = null;
                    $orderItem['option_id'] = null; // Set to null for simple products
                }

                order_item::create($orderItem);
            }

            // Process stock reduction for all cart items (including combo items)
            $this->stockService->processCartStockReduction($orderDetails['carts'], $order->id);

            // Clear the cart
            Cart::where('user_id', $orderDetails['user_id'])->delete();

            // Mark OTP as verified for future orders
            $user->update(['otp_verified' => true]);

            // Clear session
            session()->forget('pending_order');

            // Clear the cart
            $guestId = $request->cookie('guest_id');
            if ($guestId) {
                Cart::where('guest_id', $guestId)->delete();
            }

            // Delete incomplete orders for this phone number (using normalized phone)
            IncompleteOrder::deleteByPhone($order->phone);

            $response = [
                'status' => 'success',
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
            ];
            if (\App\Services\PaymentGateway\PaymentGatewayManager::isAutomatedGateway($orderDetails['payment_method'] ?? '')) {
                $response['requires_redirect'] = true;
                $response['provider'] = $orderDetails['payment_method'];
            }
            return response()->json($response);
        } catch (\Exception $e) {
            \Log::error("OTP verification failed: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during verification. Please try again.',
            ], 500);
        }
    }

    // Resend otp

    public function resendOtp(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not authenticated.'], 401);
        }

        // Check if the user has reached the maximum resend limit
        $maxResendLimit = 5;
        $resendCount = $user->otp_resend_count ?? 0;

        if ($resendCount >= $maxResendLimit) {
            return response()->json(['status' => 'error', 'message' => 'Maximum OTP resend limit reached.'], 429);
        }

        // Increment the resend count
        $user->otp_resend_count = $resendCount + 1;
        $user->otp_code = rand(1000, 9999); // Generate a new OTP
        $user->otp_expires_at = now()->addMinutes(10); // Extend the expiration time
        $user->save();

        // Send the new OTP via SMS (implement your SMS logic here)
        $this->smsService->sendSMS($user->phone, "Dear {$user->name}\nYour Mobile New OTP Verification Code is : {$user->otp_code}\nThank you from Thikana Shop");


        return response()->json(['status' => 'success', 'message' => 'OTP resent successfully.']);
    }

    public function thankYou($orderId)
    {
        $order = order::findOrFail($orderId);

        // Cleanup: Delete any incomplete order associated with this verified phone number (using normalized phone)
        IncompleteOrder::deleteByPhone($order->phone);

        // Check if delayed purchase event system is enabled for this payment method
        // If enabled, store pending event for later firing by admin
        // If disabled, we'll fire purchase event via GTM dataLayer on the thank you page
        $isDelayedEventEnabled = $this->pendingPurchaseEventService->isEnabledForPaymentMethod($order->payment_method);

        if ($isDelayedEventEnabled) {
            // Store pending purchase event - will be fired when order is confirmed by admin
            try {
                $this->pendingPurchaseEventService->storePendingEvent($order, request());
            } catch (\Exception $e) {
                \Log::error("Failed to store pending purchase event: " . $e->getMessage());
            }
        }
        // If not delayed, purchase event will fire via GTM dataLayer on the thank you page

        // Check if this is a combo order
        $isComboOrder = $order->is_combo_order && $order->combo_offer_id;

        // Flag to tell the view whether to fire purchase event via dataLayer
        $firePurchaseEvent = !$isDelayedEventEnabled;

        if ($isComboOrder) {
            // For combo orders, get the combo offer and selections
            $comboOffer = \App\Models\ComboOffer::find($order->combo_offer_id);
            $comboSelections = $order->combo_selections ?? []; // Ensure it's always an array

            // Ensure combo selections is an array
            if (is_string($comboSelections)) {
                $comboSelections = json_decode($comboSelections, true);
            }

            // Get the order items for reference
            $orderItems = order_item::with(['product', 'variationCombination'])
                ->where('order_id', $order->id)
                ->get();

            return view('frontend.thankyou', compact('order', 'orderItems', 'isComboOrder', 'comboOffer', 'comboSelections', 'firePurchaseEvent'));
        } else {
            // For regular orders, group order items by product
            $orderItems = order_item::with(['product', 'variationCombination'])
                ->where('order_id', $order->id)
                ->get()
                ->groupBy('product_id');

            return view('frontend.thankyou', compact('order', 'orderItems', 'isComboOrder', 'firePurchaseEvent'));
        }
    }


    public function downloadOrderPDF($orderId)
    {
        // Fetch the order based on user ID and order ID
        $order = order::where('user_id', Auth::id())
            ->where('id', $orderId)
            ->first();

        // Check if the order exists
        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found!');
        }

        // Fetch order items manually using order_id
        $orderItems = order_item::where('order_id', $order->id)->get();

        // Fetch the products for the order items
        $productIds = $orderItems->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->get();

        // Generate the PDF with the current Blade view
        $pdf = Mpdf::loadView('invoice', compact('order', 'orderItems'));

        // Download the PDF
        return $pdf->download('order_' . $order->id . '.pdf');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function deleteMultiple(Request $request)
    {
        $orderIds = $request->input('order_ids', []);

        if (empty($orderIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No orders selected for deletion.'
            ]);
        }

        try {
            // Delete the orders
            order::whereIn('id', $orderIds)->delete();

            return response()->json([
                'success' => true,
                'message' => count($orderIds) . ' orders have been deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete orders: ' . $e->getMessage()
            ]);
        }
    }

public function addQuick(Request $request)
{
    $product = \App\Models\Product::with('variationCombinations')->findOrFail($request->product_id);
    $quantity = 1;

    $userId = \Auth::id();
    $guestId = $request->cookie('guest_id') ?? \Str::uuid()->toString();

    if (!$userId && !$request->cookie('guest_id')) {
        \Cookie::queue('guest_id', $guestId, 60 * 24 * 30);
    }

    if ($product->product_type === 'variable' && $request->has('combination_id')) {
        $combination = \App\Models\VariationCombination::findOrFail($request->combination_id);
        
        $cartQuery = \App\Models\Cart::where('product_id', $product->id)
            ->where('combination_id', $combination->id);

        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('guest_id', $guestId);
        }

        $cartItem = $cartQuery->first();
        $price = $combination->effective_price; // Using the effective_price accessor

        if ($cartItem) {
            $cartItem->increment('qunt', $quantity);
        } else {
            \App\Models\Cart::create([
                'product_id' => $product->id,
                'qunt' => $quantity,
                'price' => $price,
                'combination_id' => $combination->id,
                'user_id' => $userId,
                'guest_id' => !$userId ? $guestId : null,
            ]);
        }
    } else {
        // Simple product
        $cartQuery = \App\Models\Cart::where('product_id', $product->id);

        if ($userId) {
            $cartQuery->where('user_id', $userId);
        } else {
            $cartQuery->where('guest_id', $guestId);
        }

        $cartItem = $cartQuery->first();
        $price = $product->offer ?: $product->old_price;

        if ($cartItem) {
            $cartItem->increment('qunt', $quantity);
        } else {
            \App\Models\Cart::create([
                'product_id' => $product->id,
                'qunt' => $quantity,
                'price' => $price,
                'combination_id' => null,
                'user_id' => $userId,
                'guest_id' => !$userId ? $guestId : null,
            ]);
        }
    }

    return response()->json(['success' => true, 'message' => 'Product added to cart!']);
}

    public function exportSelected(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        
        if (empty($orderIds)) {
            return redirect()->back()->with('error', 'No orders selected for export.');
        }

        if (is_string($orderIds)) {
            $orderIds = json_decode($orderIds, true);
        }

        $orders = order::with(['products', 'comboOffer'])
            ->whereIn('id', $orderIds)
            ->get([
                'id', 'name', 'phone', 'total', 'status', 'created_at', 
                'is_combo_order', 'combo_offer_id', 'combo_selections', 'admin_note'
            ]);

        $filename = 'orders_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Order ID', 'Customer Name', 'Phone', 'Total Amount', 'Status', 
                'Order Type', 'Products', 'Created Date', 'Admin Note'
            ]);

            // CSV data
            foreach ($orders as $order) {
                $orderType = $order->is_combo_order ? 'Combo Order' : 'Regular Order';
                
                $products = '';
                if ($order->is_combo_order && $order->comboOffer) {
                    $products = $order->comboOffer->title;
                    if ($order->combo_selections) {
                        $selections = is_string($order->combo_selections) ? 
                            json_decode($order->combo_selections, true) : $order->combo_selections;
                        if (is_array($selections)) {
                            $productNames = [];
                            foreach ($selections as $selection) {
                                $product = \App\Models\Product::find($selection['product_id']);
                                if ($product) {
                                    $productNames[] = $product->title;
                                }
                            }
                            $products .= ' (' . implode(', ', $productNames) . ')';
                        }
                    }
                } else {
                    $productNames = $order->products->pluck('title')->toArray();
                    $products = implode(', ', $productNames);
                }

                fputcsv($file, [
                    $order->id,
                    $order->name,
                    $order->phone,
                    '৳' . number_format($order->total, 2),
                    ucfirst(str_replace('_', ' ', $order->status)),
                    $orderType,
                    $products,
                    $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : 'N/A',
                    $order->admin_note ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
