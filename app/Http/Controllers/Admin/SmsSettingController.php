<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsSetting;
use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmsSettingController extends Controller
{
    /**
     * Display SMS Gateway and Notification settings
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Disallow regular customers
        if (!$user || (!$user->isAdmin() && !$user->isVendor() && !$user->can('settings.view'))) {
            abort(403, 'Unauthorized access to SMS settings.');
        }

        // Determine context: Super Admin can configure central/platform settings or user-specific
        $targetUserId = null;
        if (!$user->isSuperAdmin() && $user->id !== 1) {
            $targetUserId = $user->id;
        } elseif ($request->has('user_id') && is_numeric($request->user_id)) {
            $targetUserId = (int)$request->user_id;
        }

        $smsSetting = SmsSetting::getSettingsForUser($targetUserId);
        $smsSettings = $smsSetting;

        return view('admin.sms-settings.index', compact('smsSetting', 'smsSettings', 'targetUserId', 'user'));
    }

    /**
     * Update SMS Gateway and Notification settings
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user || (!$user->isAdmin() && !$user->isVendor() && !$user->can('settings.update') && !$user->can('sms_settings.update'))) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'default_gateway' => 'required|in:bulksmsbd,awaj',
            'bulksmsbd_url' => 'nullable|string|max:255',
            'bulksmsbd_api_key' => 'nullable|string|max:255',
            'bulksmsbd_sender_id' => 'nullable|string|max:255',
            'awaj_url' => 'nullable|string|max:255',
            'awaj_api_key' => 'nullable|string|max:255',
            'awaj_sender_id' => 'nullable|string|max:255',
            'awaj_client_id' => 'nullable|string|max:255',
            'awaj_secret_key' => 'nullable|string|max:255',
            'admin_expiry_days_before' => 'nullable|integer|min:1|max:30',
            'template_product_sold' => 'nullable|string',
            'template_user_created' => 'nullable|string',
            'template_admin_expiry' => 'nullable|string',
            'template_order_status' => 'nullable|string',
        ]);

        $targetUserId = null;
        if (!$user->isSuperAdmin() && $user->id !== 1) {
            $targetUserId = $user->id;
        } elseif ($request->filled('target_user_id') && is_numeric($request->target_user_id)) {
            $targetUserId = (int)$request->target_user_id;
        }

        // Checkboxes handling
        $validated['is_enabled'] = $request->boolean('is_enabled');
        $validated['notify_product_sold'] = $request->boolean('notify_product_sold');
        $validated['notify_user_created'] = $request->boolean('notify_user_created');
        $validated['notify_admin_expiry'] = $request->boolean('notify_admin_expiry');
        $validated['notify_order_status_change'] = $request->boolean('notify_order_status_change');

        // Apply fallback defaults to templates if left completely empty
        if (empty($validated['template_product_sold'])) {
            $validated['template_product_sold'] = SmsSetting::defaultTemplate('product_sold');
        }
        if (empty($validated['template_user_created'])) {
            $validated['template_user_created'] = SmsSetting::defaultTemplate('user_created');
        }
        if (empty($validated['template_admin_expiry'])) {
            $validated['template_admin_expiry'] = SmsSetting::defaultTemplate('admin_expiry');
        }
        if (empty($validated['template_order_status'])) {
            $validated['template_order_status'] = SmsSetting::defaultTemplate('order_status');
        }

        SmsSetting::updateOrCreate(
            ['user_id' => $targetUserId],
            $validated
        );

        return redirect()->route('admin.sms-settings.index', $targetUserId ? ['user_id' => $targetUserId] : [])
            ->with('success', 'SMS gateway and notification rules updated successfully!');
    }

    /**
     * Test gateway connectivity via AJAX
     */
    public function test(Request $request, SMSService $smsService)
    {
        $request->validate([
            'gateway' => 'required|in:bulksmsbd,awaj',
            'test_number' => 'required|string|min:10|max:20',
            'test_message' => 'nullable|string|max:160',
        ]);

        $gateway = $request->gateway;
        $testNumber = $request->test_number;
        $testMessage = $request->test_message ?: 'This is a test notification from Thikana SMS System.';

        $config = [
            'bulksmsbd_url' => $request->bulksmsbd_url,
            'bulksmsbd_api_key' => $request->bulksmsbd_api_key,
            'bulksmsbd_sender_id' => $request->bulksmsbd_sender_id,
            'awaj_url' => $request->awaj_url,
            'awaj_api_key' => $request->awaj_api_key,
            'awaj_sender_id' => $request->awaj_sender_id,
            'awaj_client_id' => $request->awaj_client_id,
            'awaj_secret_key' => $request->awaj_secret_key,
        ];

        // If credentials are empty in the test request, fall back to saved settings
        $user = Auth::user();
        $targetUserId = (!$user->isSuperAdmin() && $user->id !== 1) ? $user->id : null;
        $savedSettings = SmsSetting::getSettingsForUser($targetUserId);

        if (empty($config['bulksmsbd_api_key'])) {
            $config['bulksmsbd_api_key'] = $savedSettings->bulksmsbd_api_key;
            $config['bulksmsbd_sender_id'] = $savedSettings->bulksmsbd_sender_id;
            $config['bulksmsbd_url'] = $savedSettings->bulksmsbd_url;
        }

        if (empty($config['awaj_api_key'])) {
            $config['awaj_api_key'] = $savedSettings->awaj_api_key;
            $config['awaj_sender_id'] = $savedSettings->awaj_sender_id;
            $config['awaj_url'] = $savedSettings->awaj_url;
            $config['awaj_client_id'] = $savedSettings->awaj_client_id;
            $config['awaj_secret_key'] = $savedSettings->awaj_secret_key;
        }

        $result = $smsService->testConnection($gateway, $config, $testNumber, $testMessage);

        return response()->json($result);
    }
}
