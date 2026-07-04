<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelegramSetting;
use App\Services\TelegramNotificationService;
use Illuminate\Http\Request;

class TelegramSettingController extends Controller
{
    /**
     * Display telegram settings
     */
    public function index()
    {
        $telegramSettings = TelegramSetting::getSettings();
        return view('admin.telegram-settings.index', compact('telegramSettings'));
    }

    /**
     * Update telegram settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'bot_token' => 'nullable|string|max:255',
            'chat_id' => 'nullable|string|max:255',
            'order_message_template' => 'nullable|string',
            'timeout' => 'nullable|integer|min:1|max:30',
        ]);

        $telegramSettings = TelegramSetting::first();
        
        if (!$telegramSettings) {
            $telegramSettings = new TelegramSetting();
        }

        // Handle checkboxes explicitly (unchecked checkboxes don't send values)
        $validated['enabled'] = $request->has('enabled') ? 1 : 0;
        $validated['notify_new_order'] = $request->has('notify_new_order') ? 1 : 0;
        $validated['notify_landing_page_order'] = $request->has('notify_landing_page_order') ? 1 : 0;
        $validated['notify_cart_order'] = $request->has('notify_cart_order') ? 1 : 0;
        $validated['notify_order_status_change'] = $request->has('notify_order_status_change') ? 1 : 0;

        $telegramSettings->fill($validated);
        $telegramSettings->save();

        // Clear cache after update
        TelegramSetting::clearCache();

        return redirect()->route('admin.telegram-settings.index')
            ->with('success', 'Telegram settings updated successfully!');
    }

    /**
     * Test telegram connection
     */
    public function test(Request $request)
    {
        $telegramService = new TelegramNotificationService();
        $result = $telegramService->testConnection();

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'Test message sent successfully! Check your Telegram.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 422);
        }
    }
}

