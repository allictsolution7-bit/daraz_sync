<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicShippingRule;
use App\Models\Product;
use App\Models\LandingPage;
use Illuminate\Http\Request;

class ShippingRuleController extends Controller
{
    /**
     * Display a listing of shipping rules.
     */
    public function index(Request $request)
    {
        $query = BasicShippingRule::with('ruleable');

        // Filter by type
        if ($request->filled('type')) {
            $query->where('ruleable_type', $request->type);
        }

        // Filter by rule type
        if ($request->filled('rule_type')) {
            $query->where('rule_type', $request->rule_type);
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $shippingRules = $query->orderBy('priority', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(20);

        // Get counts for filters
        $productCount = BasicShippingRule::where('ruleable_type', Product::class)->count();
        $landingPageCount = BasicShippingRule::where('ruleable_type', LandingPage::class)->count();

        return view('admin.shipping-rules.index', compact(
            'shippingRules', 
            'productCount', 
            'landingPageCount'
        ));
    }

    /**
     * Show the form for creating a new shipping rule.
     */
    public function create(Request $request)
    {
        $ruleableType = $request->get('type', 'product');
        $ruleableId = $request->get('id');
        
        $ruleable = null;
        if ($ruleableId) {
            $modelClass = $ruleableType === 'landing_page' ? LandingPage::class : Product::class;
            $ruleable = $modelClass::find($ruleableId);
        }

        $products = Product::where('status', 1)->orderBy('title')->get();
        $landingPages = LandingPage::where('status', 1)->orderBy('title')->get();

        return view('admin.shipping-rules.create', compact(
            'ruleableType', 
            'ruleableId', 
            'ruleable', 
            'products', 
            'landingPages'
        ));
    }

    /**
     * Store a newly created shipping rule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ruleable_type' => 'required|in:product,landing_page',
            'ruleable_id' => 'required|integer',
            'rule_type' => 'required|in:override,free_shipping,custom_cost,percentage,delivery_area,conditional',
            'rule_value' => 'nullable|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'delivery_area_name' => 'nullable|string|max:255',
            'delivery_area_slug' => 'nullable|string|max:255',
            'conditions' => 'nullable|array',
            'priority' => 'required|integer|min:0|max:100',
            'is_active' => 'nullable',
        ]);

        // Convert ruleable_type to full class name
        $validated['ruleable_type'] = $validated['ruleable_type'] === 'landing_page' 
            ? LandingPage::class 
            : Product::class;

        // Validate that the ruleable exists
        $ruleable = $validated['ruleable_type']::find($validated['ruleable_id']);
        if (!$ruleable) {
            return back()->withErrors(['ruleable_id' => 'The selected item does not exist.']);
        }

        // Set default is_active if not provided
        $validated['is_active'] = $request->has('is_active');

        // Handle multiple delivery areas
        if ($validated['rule_type'] === 'delivery_area' && $request->has('delivery_areas')) {
            $deliveryAreas = $request->input('delivery_areas', []);
            
            foreach ($deliveryAreas as $index => $area) {
                $ruleData = $validated;
                $ruleData['delivery_area_name'] = $area['name'];
                $ruleData['delivery_area_slug'] = $area['slug'];
                $ruleData['rule_value'] = $area['cost'];
                $ruleData['priority'] = $validated['priority'] + $index; // Slightly different priority for ordering
                
                BasicShippingRule::create($ruleData);
            }
        } else {
            BasicShippingRule::create($validated);
        }

        return redirect()->route('admin.shipping.rules.index')
            ->with('success', 'Shipping rule created successfully.');
    }

    /**
     * Display the specified shipping rule.
     */
    public function show($id)
    {
        $shippingRule = BasicShippingRule::with('ruleable')->findOrFail($id);
        return view('admin.shipping-rules.show', compact('shippingRule'));
    }

    /**
     * Show the form for editing the specified shipping rule.
     */
    public function edit($id)
    {
        $shippingRule = BasicShippingRule::findOrFail($id);
        $products = Product::where('status', 1)->orderBy('title')->get();
        $landingPages = LandingPage::where('status', 1)->orderBy('title')->get();

        // Convert class name back to simple type for form
        $ruleableType = $shippingRule->ruleable_type === LandingPage::class ? 'landing_page' : 'product';

        return view('admin.shipping-rules.edit', compact(
            'shippingRule', 
            'ruleableType', 
            'products', 
            'landingPages'
        ));
    }

    /**
     * Update the specified shipping rule.
     */
    public function update(Request $request, $id)
    {
        $shippingRule = BasicShippingRule::findOrFail($id);
        
        $validated = $request->validate([
            'ruleable_type' => 'required|in:product,landing_page',
            'ruleable_id' => 'required|integer',
            'rule_type' => 'required|in:override,free_shipping,custom_cost,percentage,delivery_area,conditional',
            'rule_value' => 'nullable|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            'delivery_area_name' => 'nullable|string|max:255',
            'delivery_area_slug' => 'nullable|string|max:255',
            'conditions' => 'nullable|array',
            'priority' => 'required|integer|min:0|max:100',
            'is_active' => 'nullable',
        ]);

        // Convert ruleable_type to full class name
        $validated['ruleable_type'] = $validated['ruleable_type'] === 'landing_page' 
            ? LandingPage::class 
            : Product::class;

        // Validate that the ruleable exists
        $ruleable = $validated['ruleable_type']::find($validated['ruleable_id']);
        if (!$ruleable) {
            return back()->withErrors(['ruleable_id' => 'The selected item does not exist.']);
        }

        // Set default is_active if not provided
        $validated['is_active'] = $request->has('is_active');

        $shippingRule->update($validated);

        return redirect()->route('admin.shipping.rules.index')
            ->with('success', 'Shipping rule updated successfully.');
    }

    /**
     * Remove the specified shipping rule.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $shippingRule = BasicShippingRule::find($id);
            
            if (!$shippingRule) {
                return redirect()->route('admin.shipping.rules.index')
                    ->with('error', 'Shipping rule not found.');
            }

            $shippingRule->delete();

            return redirect()->route('admin.shipping.rules.index')
                ->with('success', 'Shipping rule deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.shipping.rules.index')
                ->with('error', 'An error occurred while deleting the shipping rule.');
        }
    }

    /**
     * Toggle the active status of a shipping rule.
     */
    public function toggle($id)
    {
        $shippingRule = BasicShippingRule::findOrFail($id);
        $shippingRule->update(['is_active' => !$shippingRule->is_active]);

        $status = $shippingRule->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Shipping rule {$status} successfully.");
    }

    /**
     * Get shipping rules for a specific product or landing page.
     */
    public function getRules(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');

        if (!$type || !$id) {
            return response()->json([]);
        }

        $modelClass = $type === 'landing_page' ? LandingPage::class : Product::class;
        $model = $modelClass::find($id);

        if (!$model) {
            return response()->json([]);
        }

        $rules = $model->shippingRules()
                      ->orderBy('priority', 'desc')
                      ->get()
                      ->map(function ($rule) {
                          return [
                              'id' => $rule->id,
                              'rule_type' => $rule->rule_type,
                              'rule_value' => $rule->rule_value,
                              'free_shipping_threshold' => $rule->free_shipping_threshold,
                              'priority' => $rule->priority,
                              'is_active' => $rule->is_active,
                              'created_at' => $rule->created_at->format('Y-m-d H:i:s'),
                          ];
                      });

        return response()->json($rules);
    }

}