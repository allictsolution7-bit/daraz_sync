<?php

namespace App\Http\Controllers;

use App\Models\DelayedEventSetting;
use App\Models\PendingPurchaseEvent;
use Illuminate\Http\Request;

/**
 * Delayed Event Setting Controller
 *
 * Manages the admin settings for the delayed purchase event system.
 * This feature delays firing purchase events for COD/manual payment orders
 * until they are confirmed by admin.
 */
class DelayedEventSettingController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        $delayedSettings = DelayedEventSetting::getSettings();
        $availablePaymentMethods = DelayedEventSetting::getAvailablePaymentMethods();
        $availableOrderSources = DelayedEventSetting::getAvailableOrderSources();

        // Get stats for the dashboard
        $stats = [
            'pending' => PendingPurchaseEvent::pending()->count(),
            'fired' => PendingPurchaseEvent::fired()->count(),
            'failed' => PendingPurchaseEvent::failed()->count(),
            'total' => PendingPurchaseEvent::count(),
        ];

        return view('admin.delayed-events.settings', compact('delayedSettings', 'availablePaymentMethods', 'availableOrderSources', 'stats'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'is_enabled' => 'nullable|boolean',
            'firing_method' => 'required|string|in:pixelfly,sgtm',
            'pixelfly_api_key' => 'nullable|string|max:255',
            'pixelfly_endpoint' => 'nullable|string|max:255|url',
            'sgtm_endpoint' => 'nullable|string|max:255|url',
            'sgtm_measurement_id' => 'nullable|string|max:50',
            'sgtm_api_secret' => 'nullable|string|max:255',
            'enabled_payment_methods' => 'nullable|array',
            'enabled_payment_methods.*' => 'string|in:cod,bkash,nagad,rocket',
            'enabled_order_sources' => 'nullable|array',
            'enabled_order_sources.*' => 'string',
        ]);

        $settings = DelayedEventSetting::first() ?? new DelayedEventSetting();

        $settings->is_enabled = $request->boolean('is_enabled');
        $settings->firing_method = $validated['firing_method'];
        $settings->pixelfly_api_key = $validated['pixelfly_api_key'] ?? null;
        $settings->pixelfly_endpoint = $validated['pixelfly_endpoint'] ?? 'https://track.pixelfly.io/e';
        $settings->sgtm_endpoint = $validated['sgtm_endpoint'] ?? null;
        $settings->sgtm_measurement_id = $validated['sgtm_measurement_id'] ?? null;
        $settings->sgtm_api_secret = $validated['sgtm_api_secret'] ?? null;
        $settings->enabled_payment_methods = $validated['enabled_payment_methods'] ?? [];
        $settings->enabled_order_sources = $validated['enabled_order_sources'] ?? [];

        $settings->save();

        return redirect()->route('admin.delayed-events.settings')
            ->with('success', 'Delayed event settings updated successfully!');
    }

    /**
     * Test the connection for the selected firing method.
     */
    public function testConnection(Request $request)
    {
        $firingMethod = $request->input('firing_method', 'pixelfly');

        if ($firingMethod === 'sgtm') {
            return $this->testSgtmConnection($request);
        }

        return $this->testPixelflyConnection($request);
    }

    /**
     * Test PixelFly connection.
     */
    protected function testPixelflyConnection(Request $request)
    {
        $apiKey = $request->input('api_key') ?? DelayedEventSetting::getApiKey();
        $endpoint = $request->input('endpoint') ?? DelayedEventSetting::getEndpoint();

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API key is required',
            ], 400);
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'X-PF-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($endpoint, [
                'event' => 'test_connection',
                'event_id' => 'test_' . time(),
                'test' => true,
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection successful! PixelFly is responding.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Connection failed: HTTP ' . $response->status(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test sGTM connection via GA4 Measurement Protocol.
     */
    protected function testSgtmConnection(Request $request)
    {
        $endpoint = $request->input('sgtm_endpoint') ?? DelayedEventSetting::getSgtmEndpoint();
        $measurementId = $request->input('sgtm_measurement_id') ?? DelayedEventSetting::getMeasurementId();
        $apiSecret = $request->input('sgtm_api_secret') ?? DelayedEventSetting::getSgtmApiSecret();

        if (!$endpoint || !$measurementId || !$apiSecret) {
            return response()->json([
                'success' => false,
                'message' => 'sGTM Endpoint, Measurement ID, and API Secret are all required.',
            ], 400);
        }

        try {
            $url = rtrim($endpoint, '/') . '/mp/collect?measurement_id=' . urlencode($measurementId) . '&api_secret=' . urlencode($apiSecret);

            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($url, [
                'client_id' => 'test_' . time(),
                'events' => [[
                    'name' => 'test_connection',
                    'params' => [
                        'engagement_time_msec' => 1,
                    ],
                ]],
            ]);

            // GA4 MP returns 204 on success, sGTM may return 200
            if ($response->successful() || $response->status() === 204) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection successful! sGTM is responding.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Connection failed: HTTP ' . $response->status(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show pending events list.
     */
    public function pendingEvents()
    {
        $events = PendingPurchaseEvent::with('order')
            ->pending()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.delayed-events.pending', compact('events'));
    }

    /**
     * Show event history.
     */
    public function eventHistory()
    {
        $events = PendingPurchaseEvent::with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.delayed-events.history', compact('events'));
    }
}
