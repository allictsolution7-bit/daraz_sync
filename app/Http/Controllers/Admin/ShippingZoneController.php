<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ShippingRule;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::with('shippingRules')->get();
        return view('admin.shipping.zones.index', compact('zones'));
    }

    public function create()
    {
        $cities = City::active()->pluck('name')->toArray();
        return view('admin.shipping.zones.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'regions' => 'required|array',
            'is_active' => 'boolean'
        ]);

        // Ensure regions is stored as JSON string
        $validated['regions'] = json_encode($validated['regions']);

        ShippingZone::create($validated);
        return redirect()->route('admin.shipping.zones.index')->with('success', 'Shipping zone created successfully');
    }

    public function edit(ShippingZone $zone)
    {
        $cities = City::where('is_active', true)
                     ->pluck('name')
                     ->toArray();

        return view('admin.shipping.zones.edit', compact('zone', 'cities'));
    }

    public function update(Request $request, ShippingZone $zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'regions' => 'required|array',
            'is_active' => 'boolean'
        ]);

        // Ensure regions is stored as JSON string
        $validated['regions'] = json_encode($validated['regions']);

        $zone->update($validated);
        return redirect()->route('admin.shipping.zones.index')->with('success', 'Shipping zone updated successfully');
    }

    public function destroy(ShippingZone $shippingZone)
    {
        $shippingZone->delete();
        return redirect()->route('admin.shipping.zones.index')->with('success', 'Shipping zone deleted successfully');
    }
}
