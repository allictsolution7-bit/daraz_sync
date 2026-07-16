<?php

namespace App\Services;

use App\Models\TelegramSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramNotificationService
{
    /**
     * Send order notification to Telegram
     */
    public function sendOrderNotification($order, $product, $request = null, $orderSource = 'Unknown')
    {
        try {
            $settings = TelegramSetting::getSettings();

            // Check if notifications are enabled
            if (!$settings->enabled || !$this->shouldNotifyForOrderType($settings, $orderSource)) {
                return false;
            }

            // Validate credentials
            if (!$settings->bot_token || !$settings->chat_id) {
                return false;
            }

            // Build order edit URL
            $orderUrl = route('admin.orders.edit', $order->id);

            // Build message from template or use default
            if ($settings->order_message_template) {
                // Use custom template
                $message = $this->replacePlaceholders($settings->order_message_template, [
                    'order_id' => $order->id,
                    'customer_name' => $order->name,
                    'customer_phone' => $order->phone,
                    'customer_address' => $order->address,
                    'product_title' => $product->title,
                    'quantity' => $request ? $request->quantity : 1,
                    'price' => $request ? number_format((float) $request->price, 2) : number_format((float) $product->offer ?: $product->old_price, 2),
                    'shipping' => number_format((float) $order->shipping, 2),
                    'total' => number_format((float) $order->total, 2),
                    'payment_method' => $order->payment_method,
                    'order_url' => $orderUrl,
                    'order_source' => $orderSource,
                ]);
            } else {
                // Use default template
                $message = $this->getDefaultMessage($order, $product, $request, $orderSource, $orderUrl);
            }

            // Send to Telegram
            $this->sendToTelegram($settings, $message);

            return true;

        } catch (\Throwable $e) {
            Log::warning('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send landing page order notification
     */
    public function sendLandingPageOrderNotification($order, $product, $request)
    {
        return $this->sendOrderNotification($order, $product, $request, 'Landing Page');
    }

    /**
     * Send cart order notification
     */
    public function sendCartOrderNotification($order, $product, $request = null)
    {
        return $this->sendOrderNotification($order, $product, $request, 'Cart');
    }

    /**
     * Send order status change notification
     */
    public function sendOrderStatusChangeNotification($order, $oldStatus, $newStatus)
    {
        try {
            $settings = TelegramSetting::getSettings();

            if (!$settings->enabled || !$settings->notify_order_status_change) {
                return false;
            }

            if (!$settings->bot_token || !$settings->chat_id) {
                return false;
            }

            $orderUrl = route('admin.orders.edit', $order->id);
            $message = "📋 <b>Order Status Updated</b>\n"
                . "🧾 Order ID: <b>{$order->id}</b>\n"
                . "👤 {$order->name}\n"
                . "📞 {$order->phone}\n"
                . "📦 Product: <b>" . ($order->order_items->first()->product->title ?: 'N/A') . "</b>\n"
                . "🔄 Status: <b>{$oldStatus}</b> → <b>{$newStatus}</b>\n"
                . "💰 Total: " . number_format((float) $order->total, 2) . "\n"
                . "🔗 <a href=\"{$orderUrl}\">View Order</a>";

            $this->sendToTelegram($settings, $message);
            return true;

        } catch (\Throwable $e) {
            Log::warning('Telegram status change notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send custom message to Telegram
     */
    public function sendCustomMessage($message, $parseMode = 'HTML')
    {
        try {
            $settings = TelegramSetting::getSettings();

            if (!$settings->enabled || !$settings->bot_token || !$settings->chat_id) {
                return false;
            }

            $this->sendToTelegram($settings, $message, $parseMode);
            return true;

        } catch (\Throwable $e) {
            Log::warning('Telegram custom message failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test Telegram connection
     */
    public function testConnection()
    {
        try {
            $settings = TelegramSetting::getSettings();

            if (!$settings->enabled || !$settings->bot_token || !$settings->chat_id) {
                return [
                    'success' => false,
                    'message' => 'Telegram not configured or disabled'
                ];
            }

            $testUrl = route('admin.orders.index');
            $message = "🧪 <b>Test Message</b>\n"
                . "Your Telegram notification system is working correctly!\n"
                . "⏰ " . now()->format('Y-m-d H:i:s') . "\n"
                . "🔗 <a href=\"{$testUrl}\">View Orders</a>";

            $this->sendToTelegram($settings, $message);

            return [
                'success' => true,
                'message' => 'Test message sent successfully!'
            ];

        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if should notify for specific order type
     */
    private function shouldNotifyForOrderType($settings, $orderSource)
    {
        switch ($orderSource) {
            case 'Landing Page':
                return $settings->notify_landing_page_order;
            case 'Cart':
                return $settings->notify_cart_order;
            default:
                return $settings->notify_new_order;
        }
    }

    /**
     * Get default message template
     */
    private function getDefaultMessage($order, $product, $request, $orderSource, $orderUrl)
    {
        $quantity = $request ? $request->quantity : 1;
        
        return "🚀 <b>New {$orderSource} Order</b>\n"
            . "🧾 Order ID: <b>{$order->id}</b>\n"
            . "👤 " . e($order->name) . "\n"
            . "📞 " . e($order->phone) . "\n"
            . "📦 Product: <b>" . e($product->title) . "</b>\n"
            . "🔢 Qty: " . (int) $quantity . "\n"
            . "🚚 Shipping: " . number_format((float) $order->shipping, 2) . "\n"
            . "💰 Total: " . number_format((float) $order->total, 2) . "\n"
            . "💳 Payment: <b>" . e($order->payment_method) . "</b>\n"
            . "🔗 <a href=\"{$orderUrl}\">View Order</a>";
    }

    /**
     * Replace placeholders in template
     */
    private function replacePlaceholders($template, $data)
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{' . $key . '}', $value, $template);
        }
        return $template;
    }

    /**
     * Send general message to Telegram API using bot configurations
     */
    public function sendGeneralMessage(string $message): bool
    {
        try {
            $settings = TelegramSetting::getSettings();
            if (!$settings->enabled || !$settings->bot_token || !$settings->chat_id) {
                return false;
            }
            $this->sendToTelegram($settings, $message);
            return true;
        } catch (\Throwable $e) {
            Log::warning('Telegram general notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send message to Telegram API
     */
    private function sendToTelegram($settings, $message, $parseMode = 'HTML')
    {
        $url = "https://api.telegram.org/bot{$settings->bot_token}/sendMessage";
        
        Http::timeout($settings->timeout)
            ->post($url, [
                'chat_id' => $settings->chat_id,
                'text' => $message,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => false,
            ]);
    }
}
