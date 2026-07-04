<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function calculator()
    {
        return view('admin.shipping.calculator');
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array',
            'total_weight' => 'nullable|numeric|min:0'
        ]);

        $shippingCost = $this->shippingService->calculateShipping(
            $validated['city'],
            $validated['total_amount'],
            collect($validated['items']),
            $validated['total_weight'] ?? 0
        );

        return response()->json([
            'shipping_cost' => $shippingCost,
            'formatted_cost' => number_format($shippingCost, 2)
        ]);
    }
}