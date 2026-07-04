<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\IncompleteOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class IncompleteOrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'name' => 'nullable|string',
            'address' => 'nullable|string',
            'upazila' => 'nullable|string',
            'source' => 'nullable|string',
            'message' => 'nullable|string',
            'product_details' => 'nullable', // Accept as string (JSON)
            'total' => 'nullable|numeric',
            'payment_method' => 'nullable|string',
            'shipping_method' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric',
            'status' => 'nullable|string',
            'admin_note' => 'nullable|string',
            'ip_address' => 'nullable|string',
        ]);

        // Decode product_details if present and is a string
        if (!empty($validated['product_details']) && is_string($validated['product_details'])) {
            $decoded = json_decode($validated['product_details'], true);
            $validated['product_details'] = is_array($decoded) ? $decoded : [];
        }

        // Always capture the current request IP; prefer explicit value if provided for backward compatibility.
        $validated['ip_address'] = $request->input('ip_address', $request->ip());

        // Normalize phone number: remove non-digits, take last 11 digits
        $phone = preg_replace('/[^\d]/', '', $validated['phone']);
        if (strlen($phone) > 11) {
            $phone = substr($phone, -11);
        }

        $order = IncompleteOrder::updateOrCreate(
            ['phone' => $phone, 'source' => $validated['source']],
            array_merge($validated, ['phone' => $phone])
        );

        return response()->json(['success' => true, 'id' => $order->id]);
    }

    public function index()
    {
        $orders = IncompleteOrder::latest()->paginate(30);
        return view('admin.incomplete_orders.index', compact('orders'));
    }

    public function myAssigned()
    {
        $orders = IncompleteOrder::where('assigned_to', auth()->id())->latest()->paginate(30);
        return view('admin.incomplete_orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = \App\Models\IncompleteOrder::findOrFail($id);
        return response()->json($order);
    }

    public function showConvertPage($id)
    {
        $incompleteOrder = IncompleteOrder::findOrFail($id);
        return view('admin.incomplete_orders.convert', compact('incompleteOrder'));
    }

    public function destroy($id)
    {
        $order = IncompleteOrder::findOrFail($id);
        $order->delete();
        return response()->json(['success' => true]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        IncompleteOrder::whereIn('id', $ids)->delete();
        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,contacted,follow_up,converted,cancelled,spam'
        ]);

        $order = IncompleteOrder::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $request->status]);
    }

    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:1000'
        ]);

        $order = IncompleteOrder::findOrFail($id);
        $order->update(['admin_note' => $request->admin_note]);

        return response()->json(['success' => true, 'admin_note' => $request->admin_note]);
    }

    public function data(Request $request)
    {
        $query = IncompleteOrder::query()->orderByDesc('created_at');
        
        // Add fraud check data to each incomplete order
        $query->addSelect([
            'fraud_check_result_id' => \App\Models\FraudCheckResult::select('id')
                ->whereColumn('phone', 'incomplete_orders.phone')
                ->limit(1)
        ]);

        // Apply filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method')) {
            $paymentMethod = $request->payment_method;
            // Handle both 'cod' and 'cash_on_delivery' as the same
            if ($paymentMethod === 'cod') {
                $query->where(function($q) {
                    $q->where('payment_method', 'cod')
                      ->orWhere('payment_method', 'cash_on_delivery');
                });
            } else {
                $query->where('payment_method', $paymentMethod);
            }
        }

        if ($request->filled('total_min')) {
            $query->where('total', '>=', $request->total_min);
        }

        if ($request->filled('total_max')) {
            $query->where('total', '<=', $request->total_max);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by assigned user
        if ($request->filled('assigned_to_me') && $request->assigned_to_me == 1) {
            $query->where('assigned_to', auth()->id());
        }
        
        // If on my-assigned route, filter by assigned user
        if (request()->routeIs('admin.incomplete-orders.my-assigned')) {
            $query->where('assigned_to', auth()->id());
        }

        if ($request->boolean('has_order')) {
            $duplicateIds = $this->getDuplicateIncompleteOrderIds($query);
            if (empty($duplicateIds)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('incomplete_orders.id', $duplicateIds);
            }
        }

        return DataTables::eloquent($query)
            ->addColumn('checkbox', function($order) {
                return '<input type="checkbox" class="order-checkbox" value="' . $order->id . '">';
            })
            ->addColumn('fraud_check_result', function($order) {
                // Get fraud check result for this phone number
                $fraudResult = \App\Models\FraudCheckResult::where('phone', $order->phone)->first();
                if ($fraudResult) {
                    return [
                        'risk_level' => $fraudResult->risk_level,
                        'risk_score' => $fraudResult->risk_score,
                        'delivery_success_rate' => $fraudResult->delivery_success_rate,
                        'has_courier_history' => $fraudResult->has_courier_history,
                        'last_checked_at' => $fraudResult->last_checked_at->format('M d, Y')
                    ];
                }
                return null;
            })
            ->addColumn('actions', function($order) {
                return '<button class="btn btn-primary btn-sm view-incomplete-order" data-id="' . $order->id . '">' .
                    '<i class="fas fa-eye"></i> View</button> ' .
                    '<button class="btn btn-danger btn-sm delete-incomplete-order" data-id="' . $order->id . '">' .
                    '<i class="fas fa-trash"></i> Delete</button>';
            })
            ->filter(function ($query) {
                if (request()->has('search') && !empty(request()->get('search')['value'])) {
                    $search = request()->get('search')['value'];
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%")
                          ->orWhere('product_details', 'like', "%{$search}%");
                    });
                }
            })
            ->rawColumns(['checkbox', 'actions'])
            ->toJson();
    }

    private function getDuplicateIncompleteOrderIds($query): array
    {
        $incompleteOrders = (clone $query)->get(['id', 'phone', 'product_details', 'created_at']);
        if ($incompleteOrders->isEmpty()) {
            return [];
        }

        $entries = [];
        $phoneRanges = [];

        foreach ($incompleteOrders as $order) {
            $phone = $this->normalizePhone($order->phone);
            $signature = $this->buildProductSignature($order->product_details);
            if (!$phone || !$signature || empty($order->created_at)) {
                continue;
            }

            $createdAt = $order->created_at instanceof Carbon
                ? $order->created_at
                : Carbon::parse($order->created_at);

            $entries[] = [
                'id' => $order->id,
                'phone' => $phone,
                'signature' => $signature,
                'created_at' => $createdAt,
            ];

            if (!isset($phoneRanges[$phone])) {
                $phoneRanges[$phone] = ['min' => $createdAt, 'max' => $createdAt];
            } else {
                if ($createdAt->lt($phoneRanges[$phone]['min'])) {
                    $phoneRanges[$phone]['min'] = $createdAt;
                }
                if ($createdAt->gt($phoneRanges[$phone]['max'])) {
                    $phoneRanges[$phone]['max'] = $createdAt;
                }
            }
        }

        if (empty($entries)) {
            return [];
        }

        $globalStart = null;
        $globalEnd = null;
        $phoneWindows = [];

        foreach ($phoneRanges as $phone => $range) {
            $start = $range['min']->copy()->subHours(24);
            $end = $range['max']->copy()->addHours(24);

            $phoneWindows[$phone] = [
                'start' => $start,
                'end' => $end,
            ];

            if (!$globalStart || $start->lt($globalStart)) {
                $globalStart = $start;
            }
            if (!$globalEnd || $end->gt($globalEnd)) {
                $globalEnd = $end;
            }
        }

        if (!$globalStart || !$globalEnd) {
            return [];
        }

        $orderRowsByPhone = $this->getOrderSignatureRowsByPhone(array_keys($phoneWindows), $globalStart, $globalEnd);
        if (empty($orderRowsByPhone)) {
            return [];
        }

        $duplicateIds = [];
        foreach ($entries as $entry) {
            $rows = $orderRowsByPhone[$entry['phone']] ?? [];
            if (empty($rows)) {
                continue;
            }

            $windowStart = $entry['created_at']->copy()->subHours(24);
            $windowEnd = $entry['created_at']->copy()->addHours(24);

            foreach ($rows as $row) {
                $createdAt = $row['created_at'];
                if ($createdAt->lt($windowStart) || $createdAt->gt($windowEnd)) {
                    continue;
                }
                if ($row['signature'] === $entry['signature']) {
                    $duplicateIds[] = $entry['id'];
                    break;
                }
            }
        }

        return $duplicateIds;
    }

    private function getOrderSignatureRowsByPhone(array $phones, Carbon $start, Carbon $end): array
    {
        if (empty($phones)) {
            return [];
        }

        $phones = array_values(array_unique(array_filter($phones)));
        if (empty($phones)) {
            return [];
        }

        $itemsSubquery = \DB::table('order_items')
            ->select('order_id', 'product_id', \DB::raw('SUM(quantity) as qty'))
            ->groupBy('order_id', 'product_id');

        $phoneExpr = "RIGHT(REPLACE(REPLACE(REPLACE(orders.phone, '+', ''), '-', ''), ' ', ''), 11)";

        $rows = \DB::table('orders')
            ->joinSub($itemsSubquery, 'items', function ($join) {
                $join->on('items.order_id', '=', 'orders.id');
            })
            ->selectRaw("$phoneExpr as phone, orders.created_at as created_at, GROUP_CONCAT(CONCAT(items.product_id, 'x', items.qty) ORDER BY items.product_id SEPARATOR '|') as signature")
            ->whereIn(\DB::raw($phoneExpr), $phones)
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('orders.id', 'phone', 'orders.created_at')
            ->get();

        $map = [];
        foreach ($rows as $row) {
            if (!$row->phone || !$row->signature || !$row->created_at) {
                continue;
            }
            $createdAt = $row->created_at instanceof Carbon
                ? $row->created_at
                : Carbon::parse($row->created_at);
            $map[$row->phone][] = [
                'signature' => $row->signature,
                'created_at' => $createdAt,
            ];
        }

        return $map;
    }

    private function buildProductSignature($productDetails): ?string
    {
        if (empty($productDetails)) {
            return null;
        }

        if (is_string($productDetails)) {
            $decoded = json_decode($productDetails, true);
            $productDetails = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($productDetails) || empty($productDetails)) {
            return null;
        }

        $totals = [];
        foreach ($productDetails as $item) {
            if (!is_array($item)) {
                continue;
            }
            $productId = $item['product_id'] ?? null;
            $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 0;
            if (!$productId || $quantity <= 0) {
                continue;
            }
            $key = (string) $productId;
            $totals[$key] = ($totals[$key] ?? 0) + $quantity;
        }

        if (empty($totals)) {
            return null;
        }

        ksort($totals, SORT_NUMERIC);

        $parts = [];
        foreach ($totals as $productId => $quantity) {
            $parts[] = $productId . 'x' . $quantity;
        }

        return implode('|', $parts);
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone);
        if ($digits === '') {
            return null;
        }

        if (strlen($digits) > 11) {
            $digits = substr($digits, -11);
        }

        return $digits;
    }

    public function exportSelected(Request $request)
    {
        $ids = $request->input('ids', []);
        $orders = IncompleteOrder::whereIn('id', $ids)->get();

        $csv = "ID,Name,Phone,Address,Upazila,Source,Status,Message,Total,Payment Method,Shipping Method,Shipping Cost,Admin Note,Created At\n";

        foreach ($orders as $order) {
            $products = $order->product_details;
            if (is_string($products)) {
                $products = json_decode($products, true);
            }
            
            $productNames = [];
            if (is_array($products)) {
                foreach ($products as $product) {
                    $name = $product['name'] ?? '';
                    if (!empty($product['is_combo'])) {
                        $name .= ' (Combo)';
                    }
                    $productNames[] = $name;
                }
            }

            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s"',
                $order->id,
                $order->name ?? '',
                $order->phone ?? '',
                $order->address ?? '',
                $order->upazila ?? '',
                $order->source ?? '',
                $order->status ?? '',
                $order->message ?? '',
                $order->total ?? '',
                $order->payment_method ?? '',
                $order->shipping_method ?? '',
                $order->shipping_cost ?? '',
                $order->admin_note ?? '',
                $order->created_at
            ) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="incomplete_orders_' . date('Y-m-d') . '.csv"');
    }

    public function convert(Request $request)
    {
        $action = $request->input('action');
        
        if ($action === 'save_customer') {
            return $this->saveAsCustomer($request);
        } elseif ($action === 'create_order') {
            return $this->createOrder($request);
        }
        
        return response()->json(['success' => false, 'message' => 'Invalid action']);
    }

    public function getVariationCombinations(Request $request)
    {
        $productId = $request->input('product_id');
        
        if (!$productId) {
            return response()->json(['combinations' => []]);
        }

        try {
            $product = \App\Models\Product::findOrFail($productId);
            $combinations = $product->variationCombinations()->where('is_active', true)->get();
            
            $formattedCombinations = $combinations->map(function($combination) {
                return [
                    'id' => $combination->id,
                    'name' => $combination->display_name,
                    'price' => $combination->effective_price,
                    'stock' => $combination->stock_quantity
                ];
            });
            
            return response()->json(['combinations' => $formattedCombinations]);
        } catch (\Exception $e) {
            return response()->json(['combinations' => [], 'error' => $e->getMessage()]);
        }
    }

    private function saveAsCustomer(Request $request)
    {
        $request->validate([
            'incomplete_order_id' => 'required|exists:incomplete_orders,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_upazila' => 'required|string|max:255',
            'customer_city' => 'required|string|max:255',
            'customer_message' => 'nullable|string',
        ]);

        try {
            // Update the incomplete order with customer information
            $incompleteOrder = IncompleteOrder::findOrFail($request->incomplete_order_id);
            $incompleteOrder->update([
                'name' => $request->customer_name,
                'phone' => $request->customer_phone,
                'address' => $request->customer_address,
                'upazila' => $request->customer_upazila,
                'city' => $request->customer_city,
                'message' => $request->customer_message,
                'status' => 'contacted', // Mark as contacted
                'admin_note' => 'Converted to potential customer on ' . now()->format('Y-m-d H:i:s')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer information saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save customer: ' . $e->getMessage()
            ]);
        }
    }

    private function createOrder(Request $request)
    {
        // Handle products data - it might come as JSON string
        $products = $request->products;
        if (is_string($products)) {
            try {
                $products = json_decode($products, true);
                if (!is_array($products)) {
                    throw new \Exception('Invalid products data format');
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid products data format: ' . $e->getMessage()
                ]);
            }
        }
        
        $request->validate([
            'incomplete_order_id' => 'required|exists:incomplete_orders,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'customer_upazila' => 'required|string|max:255',
            'customer_city' => 'required|string|max:255',
            'customer_message' => 'nullable|string',
            'order_source' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'shipping_method' => 'nullable|string|max:255',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
        ]);
        
        // Validate products data
        if (!is_array($products) || count($products) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'At least one product is required'
            ]);
        }

        // Fetch the incomplete order to reuse captured metadata (including IP)
        $incompleteOrder = IncompleteOrder::findOrFail($request->incomplete_order_id);
        
        foreach ($products as $index => $product) {
            if (empty($product['name']) || empty($product['price']) || empty($product['quantity'])) {
                return response()->json([
                    'success' => false,
                    'message' => "Product " . ($index + 1) . " is missing required fields (name, price, or quantity)"
                ]);
            }
        }

        try {
            \DB::beginTransaction();

            // Find or create user based on phone number or email
            $user = null;
            $phone = trim($request->customer_phone);
            $email = trim($request->customer_email ?? ''); // Add email field to form if needed
            
            // Normalize phone number (remove spaces, dashes, etc.)
            $phone = preg_replace('/[^0-9+]/', '', $phone);
            
            // First try to find by phone number
            if ($phone) {
                // Try exact match first
                $user = \App\Models\User::where('phone', $phone)->first();
                
                // If not found, try with different formats (with/without country code)
                if (!$user && strlen($phone) > 10) {
                    $phoneWithoutCountry = substr($phone, -10); // Get last 10 digits
                    $user = \App\Models\User::where('phone', 'LIKE', '%' . $phoneWithoutCountry)->first();
                }
            }
            
            // If not found by phone, try by email
            if (!$user && $email) {
                $user = \App\Models\User::where('email', $email)->first();
            }
            
            // Track if this is a new user
            $isNewUser = false;
            
            // If still not found, create new user
            if (!$user) {
                // Ensure we have a phone number to create a user
                if (empty($phone)) {
                    throw new \Exception('Phone number is required to create a user account');
                }
                
                // Double-check that no user exists with this phone or email (in case of race conditions)
                $existingUser = \App\Models\User::where('phone', $phone)
                    ->orWhere(function($query) use ($email) {
                        if ($email) {
                            $query->where('email', $email);
                        }
                    })->first();
                
                                if ($existingUser) {
                    $user = $existingUser;
                    $isNewUser = false;
                } else {
                    $isNewUser = true;
                    
                    $userData = [
                        'name' => $request->customer_name,
                        'phone' => $phone,
                        'address' => $request->customer_address,
                        'upazila' => $request->customer_upazila,
                        'city' => $request->customer_city,
                        'password' => bcrypt(\Illuminate\Support\Str::random(10)), // Generate random password
                        'otp_verified' => 1, // Mark as verified since this is admin-created
                    ];
                    
                    // Add email - use provided email or generate unique one
                    if ($email) {
                        $userData['email'] = $email;
                    } else {
                        $userData['email'] = $this->generateUniqueEmail();
                    }
                    
                    $user = \App\Models\User::create($userData);
                }
            } else {
                // Update user information if it has changed
                $updateData = [
                    'name' => $request->customer_name,
                    'address' => $request->customer_address,
                    'upazila' => $request->customer_upazila,
                    'city' => $request->customer_city,
                ];
                
                // Only update email if provided and different
                if ($email && $email !== $user->email) {
                    $updateData['email'] = $email;
                }
                
                $user->update($updateData);
            }

            // Create the order
            $isCod = $request->payment_method === 'cod';
            $orderData = [
                'name' => $request->customer_name,
                'upazila' => $request->customer_upazila,
                'city' => $request->customer_city,
                'address' => $request->customer_address,
                'phone' => $request->customer_phone,
                'message' => $request->customer_message,
                'user_id' => $user->id,
                'status' => 'pending',
                'total' => $request->total_amount,
                'discount' => $request->discount ?? 0,
                'shipping' => $request->shipping_cost ?? 0,
                'payment_method' => $request->payment_method,
                'order_source' => $request->order_source,
                'ip_address' => $incompleteOrder->ip_address ?: $request->ip(),
                // Payment type: COD = due, Online payments = full_paid
                'payment_type' => $isCod ? 'due' : 'full_paid',
                'paid_amount' => $isCod ? 0 : $request->total_amount,
                'due_amount' => $isCod ? $request->total_amount : 0,
                'payment_status' => $isCod ? 'pending' : 'paid',
            ];

            $order = \App\Models\order::create($orderData);

            // Create order products
            foreach ($products as $index => $productData) {
                // COGS snapshot at order time
                $unitCost = 0;
                if (!empty($productData['product_id'])) {
                    $itemProduct = \App\Models\Product::find($productData['product_id']);
                    $unitCost = $itemProduct->product_cost ?? 0;
                    if (!empty($productData['combination_id'])) {
                        $combo = \App\Models\VariationCombination::find($productData['combination_id']);
                        $unitCost = $combo->product_cost ?? $unitCost;
                    }
                }

                $itemData = [
                    'order_id' => $order->id,
                    'product_id' => $productData['product_id'] ?? null,
                    'combination_id' => $productData['combination_id'] ?? null,
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price'],
                    'sub_total' => $productData['price'] * $productData['quantity'],
                    'unit_cost' => $unitCost,
                    'total_cost' => $unitCost * $productData['quantity'],
                    'others' => json_encode([
                        'name' => $productData['name'],
                        'variations' => $productData['variations'] ?? null,
                        'is_combo' => isset($productData['is_combo']) ? 1 : 0,
                    ])
                ];

                \App\Models\order_item::create($itemData);
            }

            // Delete the incomplete order after successful conversion
            // This prevents duplicate data and keeps the incomplete orders table clean
            $incompleteOrder = IncompleteOrder::findOrFail($request->incomplete_order_id);
            
            try {
                $incompleteOrder->delete();
            } catch (\Exception $e) {
                throw new \Exception('Order created but failed to delete incomplete order: ' . $e->getMessage());
            }

            \DB::commit();

            $message = 'Order created successfully and incomplete order deleted';
            if ($isNewUser) {
                $message .= ' (New customer account created with email: ' . $user->email . ')';
            } else {
                $message .= ' (Existing customer account used)';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'order_id' => $order->id,
                'user_id' => $user->id,
                'is_new_user' => $isNewUser,
                'incomplete_order_deleted' => true,
                'user_email' => $user->email
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ]);
        }
    }
    
    // Helper method to generate unique email for new users
    private function generateUniqueEmail()
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?? 'thikana.com';
        do {
            $email = 'customer_' . uniqid() . '@' . $host;
        } while (\App\Models\User::where('email', $email)->exists());

        return $email;
    }
}
