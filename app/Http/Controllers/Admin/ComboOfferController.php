<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ComboOffer;
use App\Models\ComboOfferItem;
use App\Models\Product;
use App\Models\VariationCombination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComboOfferController extends Controller
{
    /**
     * Display a listing of combo offers.
     */
    public function index()
    {
        $comboOffers = ComboOffer::with(['product', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.combo_offers.index', compact('comboOffers'));
    }

    /**
     * Show the form for creating a new combo offer.
     */
    public function create()
    {
        // Get all products regardless of status for admin selection
        $products = Product::orderBy('title')->get();
        return view('admin.combo_offers.create', compact('products'));
    }

    /**
     * Store a newly created combo offer.
     */
    public function store(Request $request)
    {
        // Custom validation messages
        $messages = [
            'product_id.required' => 'Please select a base product.',
            'product_id.exists' => 'The selected base product is invalid.',
            'title.required' => 'Please enter a combo title.',
            'title.max' => 'The combo title cannot exceed 255 characters.',
            'items_count.required' => 'Please specify the number of items.',
            'items_count.integer' => 'The number of items must be a whole number.',
            'items_count.min' => 'The number of items must be at least 1.',
            'items_count.max' => 'The number of items cannot exceed 10.',
            'combo_price.required' => 'Please enter the combo price.',
            'combo_price.numeric' => 'The combo price must be a valid number.',
            'combo_price.min' => 'The combo price cannot be negative.',
            'original_price.required' => 'Please enter the original price.',
            'original_price.numeric' => 'The original price must be a valid number.',
            'original_price.min' => 'The original price cannot be negative.',
            'items.required' => 'Please add at least one combo item.',
            'items.array' => 'Combo items must be properly formatted.',
            'items.min' => 'Please add at least one combo item.',
            'items.*.product_id.required' => 'Please select a product for each combo item.',
            'items.*.product_id.exists' => 'One or more selected products are invalid.',
            'items.*.variation_combination_id.exists' => 'One or more selected variations are invalid.',
            'items.*.sort_order.integer' => 'Sort order must be a whole number.',
            'items.*.sort_order.min' => 'Sort order cannot be negative.',
        ];

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items_count' => 'required|integer|min:1|max:10',
            'combo_price' => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'is_active' => 'nullable|in:0,1',
            'sort_order' => 'integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variation_combination_id' => 'nullable|exists:variation_combinations,id',
            'items.*.is_active' => 'nullable|in:0,1',
            'items.*.sort_order' => 'integer|min:0',
        ], $messages);

        // Additional validation: Check if items_count matches actual items
        $actualItemsCount = count(array_filter($request->items, function($item) {
            return !empty($item['product_id']);
        }));

        if ($actualItemsCount < $request->items_count) {
            return back()->withInput()->withErrors([
                'items_count' => "You specified {$request->items_count} items but only added {$actualItemsCount} products. Please add more products or reduce the items count."
            ]);
        }

        try {
            DB::beginTransaction();

            $comboOffer = ComboOffer::create([
                'product_id' => $request->product_id,
                'title' => $request->title,
                'description' => $request->description,
                'items_count' => $request->items_count,
                'combo_price' => $request->combo_price,
                'original_price' => $request->original_price,
                'is_active' => $request->input('is_active', 0) == 1,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            // Create combo offer items
            foreach ($request->items as $item) {
                // Only create items that have a product selected
                if (!empty($item['product_id'])) {
                    ComboOfferItem::create([
                        'combo_offer_id' => $comboOffer->id,
                        'product_id' => $item['product_id'],
                        'variation_combination_id' => $item['variation_combination_id'] ?? null,
                        'is_active' => isset($item['is_active']) && $item['is_active'] == 1,
                        'sort_order' => $item['sort_order'] ?? 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.combo_offers.index')
                ->with('success', 'Combo offer created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Combo offer creation error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Error creating combo offer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified combo offer.
     */
    public function show(ComboOffer $comboOffer)
    {
        $comboOffer->load(['product', 'items.product', 'items.variationCombination']);
        return view('admin.combo_offers.show', compact('comboOffer'));
    }

    /**
     * Show the form for editing the specified combo offer.
     */
    public function edit(ComboOffer $comboOffer)
    {
        $comboOffer->load(['items']);
        $products = Product::orderBy('title')->get();
        
        return view('admin.combo_offers.edit', compact('comboOffer', 'products'));
    }

    /**
     * Update the specified combo offer.
     */
    public function update(Request $request, ComboOffer $comboOffer)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'items_count' => 'required|integer|min:1|max:10',
            'combo_price' => 'required|numeric|min:0',
            'original_price' => 'required|numeric|min:0',
            'is_active' => 'nullable|in:0,1',
            'sort_order' => 'integer|min:0',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variation_combination_id' => 'nullable|exists:variation_combinations,id',
            'items.*.is_active' => 'nullable|in:0,1',
            'items.*.sort_order' => 'integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $comboOffer->update([
                'product_id' => $request->product_id,
                'title' => $request->title,
                'description' => $request->description,
                'items_count' => $request->items_count,
                'combo_price' => $request->combo_price,
                'original_price' => $request->original_price,
                'is_active' => $request->input('is_active', 0) == 1,
                'sort_order' => $request->sort_order ?? 0,
            ]);

            // Delete existing items
            $comboOffer->items()->delete();

            // Create new items
            foreach ($request->items as $item) {
                ComboOfferItem::create([
                    'combo_offer_id' => $comboOffer->id,
                    'product_id' => $item['product_id'],
                    'variation_combination_id' => $item['variation_combination_id'] ?? null,
                    'is_active' => isset($item['is_active']) && $item['is_active'] == 1,
                    'sort_order' => $item['sort_order'] ?? 0,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.combo_offers.index')
                ->with('success', 'Combo offer updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Error updating combo offer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified combo offer.
     */
    public function destroy(ComboOffer $comboOffer)
    {
        try {
            $comboOffer->delete();
            return redirect()->route('admin.combo_offers.index')
                ->with('success', 'Combo offer deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting combo offer: ' . $e->getMessage());
        }
    }

    /**
     * Get variation combinations for a product.
     */
    public function getVariationCombinations(Request $request)
    {
        $productId = $request->product_id;
        $variations = VariationCombination::where('product_id', $productId)
            ->where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->get();

        return response()->json($variations);
    }

    /**
     * Toggle combo offer status.
     */
    public function toggleStatus(ComboOffer $comboOffer)
    {
        $comboOffer->update(['is_active' => !$comboOffer->is_active]);
        
        return response()->json([
            'success' => true,
            'is_active' => $comboOffer->is_active
        ]);
    }
} 