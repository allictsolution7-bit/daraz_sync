<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorGlobalSetting;
use Illuminate\Http\Request;

class VendorGlobalSettingsController extends Controller
{
    /**
     * Display vendor global settings page
     */
    public function index()
    {
        $vendorSettings = VendorGlobalSetting::getAll();
        
        return view('admin.vendor-settings.global', compact('vendorSettings'));
    }

    /**
     * Update multiple settings at once
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            VendorGlobalSetting::set($key, $value);
        }

        // Clear cache
        VendorGlobalSetting::clearCache();

        return back()->with('success', 'Vendor settings updated successfully!');
    }

    /**
     * Update single setting via AJAX
     */
    public function updateSingle(Request $request, $key)
    {
        $setting = VendorGlobalSetting::where('key', $key)->firstOrFail();

        $validated = $request->validate([
            'value' => 'required',
        ]);

        VendorGlobalSetting::set($key, $validated['value']);

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully',
            'value' => VendorGlobalSetting::get($key),
        ]);
    }

    /**
     * Reset settings to defaults
     */
    public function reset(Request $request)
    {
        $category = $request->input('category');

        if ($category) {
            // Reset specific category
            // You would need to implement default values retrieval
            return back()->with('info', "Reset functionality for category '{$category}' coming soon.");
        }

        // Reset all settings
        return back()->with('info', 'Reset all functionality coming soon.');
    }

    /**
     * Export settings as JSON
     */
    public function export()
    {
        $settings = VendorGlobalSetting::all();
        
        $export = $settings->map(function ($setting) {
            return [
                'key' => $setting->key,
                'value' => $setting->value,
                'type' => $setting->type,
                'category' => $setting->category,
            ];
        });

        return response()->json($export, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="vendor_settings_' . date('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Toggle vendor system on/off
     */
    public function toggleSystem(Request $request)
    {
        $enabled = $request->input('enabled', false);
        
        VendorGlobalSetting::set('vendor_system_enabled', $enabled);
        
        $message = $enabled 
            ? 'Multi-seller system has been ENABLED!' 
            : 'Multi-seller system has been DISABLED!';

        return back()->with('success', $message);
    }
}

