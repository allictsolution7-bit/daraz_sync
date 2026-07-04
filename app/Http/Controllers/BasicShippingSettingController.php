<?php

namespace App\Http\Controllers;

use App\Models\BasicShippingSetting;
use Illuminate\Http\Request;

class BasicShippingSettingController extends Controller
{
    public function edit()
    {
        $setting = BasicShippingSetting::first() ?? new BasicShippingSetting([
            'flat_rate' => 80.00,
            'shipping_options' => [
                'inside_dhaka' => ['name' => 'Inside Dhaka', 'cost' => 80.00, 'active' => true, 'position' => 1],
                'outside_dhaka' => ['name' => 'Outside Dhaka', 'cost' => 110.00, 'active' => true, 'position' => 2],
            ],
            'free_shipping_threshold' => 1500.00,
        ]);

        // Work with a copy of shipping_options to avoid direct modification
        $shippingOptions = $setting->shipping_options ?? [];

        // Add default positions if missing and sort
        if (!empty($shippingOptions)) {
            $index = 1;
            $updatedOptions = [];
            foreach ($shippingOptions as $key => $option) {
                $updatedOptions[$key] = $option + ['position' => $option['position'] ?? $index];
                $index++;
            }
            uasort($updatedOptions, fn($a, $b) => ($a['position'] ?? 999) <=> ($b['position'] ?? 999));
            $shippingOptions = $updatedOptions;
        }

        // Pass the sorted options to the view
        return view('admin.basicshipping.shipping_settings', compact('setting', 'shippingOptions'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'flat_rate' => 'required|numeric|min:0',
            'free_shipping_threshold' => 'required|numeric|min:0',
            'shipping_options.*.key' => 'required|string|regex:/^[a-z_]+$/|distinct',
            'shipping_options.*.name' => 'required|string|max:255',
            'shipping_options.*.cost' => 'required|numeric|min:0',
            'shipping_options.*.active' => 'nullable|in:0,1',
            'shipping_options.*.position' => 'required|integer|min:1',
        ]);

        $shippingOptions = [];
        $hasActive = false;
        if (isset($validated['shipping_options'])) {
            foreach ($validated['shipping_options'] as $option) {
                $active = isset($option['active']) && $option['active'] == '1';
                $shippingOptions[$option['key']] = [
                    'name' => $option['name'],
                    'cost' => floatval($option['cost']),
                    'active' => $active,
                    'position' => intval($option['position']),
                ];
                if ($active) {
                    $hasActive = true;
                }
            }
        }

        if (!$hasActive && !empty($shippingOptions)) {
            return back()->withErrors(['shipping_options' => 'At least one shipping option must be active.']);
        }

        $setting = BasicShippingSetting::first() ?? new BasicShippingSetting();
        $setting->update([
            'flat_rate' => floatval($validated['flat_rate']),
            'shipping_options' => $shippingOptions,
            'free_shipping_threshold' => floatval($validated['free_shipping_threshold']),
        ]);

        return redirect()->route('admin.basic.shipping.settings.edit')
            ->with('success', 'BasicShipping settings updated.');
    }
}
