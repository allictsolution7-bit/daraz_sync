<?php

namespace App\Services;

use App\Models\order;
use App\Models\order_item;
use App\Models\PendingPurchaseEvent;
use App\Models\DelayedEventSetting;
use Illuminate\Http\Request;

/**
 * Service for handling pending purchase events for COD orders.
 *
 * COD orders don't fire purchase events immediately because many get cancelled.
 * Instead, we store the event data and fire it when the order is confirmed.
 */
class PendingPurchaseEventService
{
    /**
     * Check if the delayed event feature is enabled for a payment method.
     *
     * @param string $paymentMethod
     * @return bool
     */
    public function isEnabledForPaymentMethod(string $paymentMethod): bool
    {
        return DelayedEventSetting::isPaymentMethodEnabled($paymentMethod);
    }

    /**
     * Check if the delayed event feature is enabled for an order source.
     *
     * @param string $orderSource
     * @return bool
     */
    public function isEnabledForOrderSource(string $orderSource): bool
    {
        return DelayedEventSetting::isOrderSourceEnabled($orderSource);
    }

    /**
     * Check if an order source is offline (non-website).
     *
     * @param string $orderSource
     * @return bool
     */
    public function isOfflineSource(string $orderSource): bool
    {
        return DelayedEventSetting::isOfflineSource($orderSource);
    }

    /**
     * Store a pending purchase event for an order with enabled payment method.
     *
     * @param order $order The order to store the event for
     * @param Request|null $request The original request (for capturing client context)
     * @param array|null $trackingData Optional tracking data from session (for OTP flow)
     * @return PendingPurchaseEvent|null
     */
    public function storePendingEvent(order $order, ?Request $request = null, ?array $trackingData = null): ?PendingPurchaseEvent
    {
        // Check if feature is enabled for this payment method
        if (!$this->isEnabledForPaymentMethod($order->payment_method)) {
            return null;
        }

        // Check if pending event already exists
        $existing = PendingPurchaseEvent::where('order_id', $order->id)->first();
        if ($existing) {
            return $existing;
        }

        // Build the purchase event data
        $eventData = $this->buildEventData($order, $request, $trackingData);

        return PendingPurchaseEvent::create([
            'order_id' => $order->id,
            'event_data' => $eventData,
        ]);
    }

    /**
     * Store a pending purchase event for an offline/POS order.
     * Offline orders don't have browser context (cookies, user agent, IP from browsing).
     *
     * @param order $order The order to store the event for
     * @param bool $force Force creation even if order source is not enabled (for manual admin firing)
     * @return PendingPurchaseEvent|null
     */
    public function storeOfflineEvent(order $order, bool $force = false): ?PendingPurchaseEvent
    {
        // Check if feature is enabled for this order source (unless forced)
        if (!$force && !$this->isEnabledForOrderSource($order->order_source ?? '')) {
            return null;
        }

        // Check if pending event already exists
        $existing = PendingPurchaseEvent::where('order_id', $order->id)->first();
        if ($existing) {
            return $existing;
        }

        // Build the offline purchase event data
        $eventData = $this->buildOfflineEventData($order);

        return PendingPurchaseEvent::create([
            'order_id' => $order->id,
            'event_data' => $eventData,
        ]);
    }

    /**
     * Build the purchase event data for offline/POS orders.
     * Uses physical_store, phone_call, or chat as action_source.
     *
     * @param order $order
     * @return array
     */
    protected function buildOfflineEventData(order $order): array
    {
        // Load order items
        $orderItems = order_item::with(['product', 'variationCombination'])
            ->where('order_id', $order->id)
            ->get();

        // Build items array
        $items = [];
        $contentIds = [];

        foreach ($orderItems as $item) {
            $productId = $item->product_id;
            $productName = $item->product->title ?? 'Unknown';
            $category = $item->product->category->name ?? '';
            $variant = $item->variationCombination?->display_name ?? '';
            $quantity = $item->quantity;
            $unitPrice = $item->price;

            $items[] = [
                'item_id' => (string) $productId,
                'item_name' => $productName,
                'item_category' => $category,
                'item_variant' => $variant,
                'price' => (float) $unitPrice,
                'quantity' => (int) $quantity,
            ];

            $contentIds[] = (string) $productId;
        }

        // Calculate subtotal (without shipping)
        $subtotal = $orderItems->sum('sub_total');

        // Parse name into first/last
        $nameParts = explode(' ', $order->name, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        // Normalize phone (remove +88 or 88 prefix)
        $phone = $order->phone;
        if (str_starts_with($phone, '+88')) {
            $phone = substr($phone, 3);
        } elseif (str_starts_with($phone, '88')) {
            $phone = substr($phone, 2);
        }

        // Build user data - for offline orders, phone is the primary identifier
        $userData = [
            'fn' => $firstName,
            'ln' => $lastName,
            'ph' => $phone,
            'country' => 'BD',
            'external_id' => $phone,
        ];

        // Add city only if provided
        if (!empty($order->city)) {
            $userData['ct'] = $order->city;
        }

        // Add state/region (upazila) only if provided
        if (!empty($order->upazila)) {
            $userData['st'] = $order->upazila;
        }

        // Add email if available
        if (!empty($order->email)) {
            $userData['em'] = strtolower(trim($order->email));
        }

        // Get the correct action_source based on order source
        $actionSource = DelayedEventSetting::getActionSourceForOrderSource($order->order_source ?? 'other');

        // Build the event payload
        $eventTime = time();
        $eventData = [
            'event' => 'purchase',
            'event_id' => 'purchase_offline_' . $order->id . '_' . $eventTime,
            'event_time' => $eventTime,
            'action_source' => $actionSource,
            'value' => (float) $subtotal,
            'currency' => 'BDT',
            'transaction_id' => (string) $order->id,
            'items' => $items,
            'content_ids' => $contentIds,
            'user_data' => $userData,
        ];

        // Add shipping if present
        if ($order->shipping > 0) {
            $eventData['shipping'] = (float) $order->shipping;
        }

        // Add context for offline event
        $eventData['context'] = [
            'user_agent' => 'PixelFly-Server/1.0 (Offline)',
            'is_offline' => true,
            'order_source' => $order->order_source ?? 'unknown',
        ];

        return $eventData;
    }

    /**
     * Build the purchase event data for PixelFly.
     *
     * @param order $order
     * @param Request|null $request
     * @param array|null $trackingData Optional tracking data from session (for OTP flow)
     * @return array
     */
    protected function buildEventData(order $order, ?Request $request = null, ?array $trackingData = null): array
    {
        // Load order items
        $orderItems = order_item::with(['product', 'variationCombination'])
            ->where('order_id', $order->id)
            ->get();

        // Build items array
        $items = [];
        $contentIds = [];

        foreach ($orderItems as $item) {
            $productId = $item->product_id;
            $productName = $item->product->title ?? 'Unknown';
            $category = $item->product->category->name ?? '';
            $variant = $item->variationCombination?->display_name ?? '';
            $quantity = $item->quantity;
            $unitPrice = $item->price;

            $items[] = [
                'item_id' => (string) $productId,
                'item_name' => $productName,
                'item_category' => $category,
                'item_variant' => $variant,
                'price' => (float) $unitPrice,
                'quantity' => (int) $quantity,
            ];

            $contentIds[] = (string) $productId;
        }

        // Calculate subtotal (without shipping)
        $subtotal = $orderItems->sum('sub_total');

        // Parse name into first/last
        $nameParts = explode(' ', $order->name, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        // Normalize phone (remove +88 or 88 prefix)
        $phone = $order->phone;
        if (str_starts_with($phone, '+88')) {
            $phone = substr($phone, 3);
        } elseif (str_starts_with($phone, '88')) {
            $phone = substr($phone, 2);
        }

        // Build user data
        // Only include fields that have values - empty fields should fall back to Cloudflare enrichment
        $userData = [
            'fn' => $firstName,
            'ln' => $lastName,
            'ph' => $phone,
            'country' => 'BD',
            'external_id' => $phone,
        ];

        // Add city only if provided (otherwise skip for delayed events)
        if (!empty($order->city)) {
            $userData['ct'] = $order->city;
        }

        // Add state/region (upazila) only if provided
        if (!empty($order->upazila)) {
            $userData['st'] = $order->upazila;
        }

        // Add email if available
        if (!empty($order->email)) {
            $userData['em'] = strtolower(trim($order->email));
        }

        // Add Facebook tracking IDs from cookies (captured at checkout time)
        // These are critical for Meta CAPI matching quality
        // Priority: trackingData (OTP flow) > request cookies > raw $_COOKIE
        $fbp = $trackingData['fbp'] ?? null;
        $fbc = $trackingData['fbc'] ?? null;

        // Try to get from Laravel request first, then fall back to raw $_COOKIE
        // (Laravel's cookie() may fail if middleware hasn't properly excluded these cookies)
        if (!$fbp && $request) {
            $fbp = $request->cookie('_fbp') ?? ($_COOKIE['_fbp'] ?? null);
        }
        if (!$fbc && $request) {
            $fbc = $request->cookie('_fbc') ?? ($_COOKIE['_fbc'] ?? null);
        }

        // If no _fbc, try to generate from fbclid
        if (!$fbc) {
            $fbclid = $trackingData['fbclid'] ?? ($request ? $request->input('fbclid') : null);
            if (!$fbclid && $request) {
                $referer = $request->header('Referer');
                if ($referer) {
                    $parsedUrl = parse_url($referer);
                    if (isset($parsedUrl['query'])) {
                        parse_str($parsedUrl['query'], $queryParams);
                        $fbclid = $queryParams['fbclid'] ?? null;
                    }
                }
            }
            if ($fbclid) {
                $fbc = 'fb.1.' . (time() * 1000) . '.' . $fbclid;
            }
        }

        if ($fbp) {
            $userData['fbp'] = $fbp;
        }
        if ($fbc) {
            $userData['fbc'] = $fbc;
        }

        // Build the event payload
        $eventTime = time();
        $eventData = [
            'event' => 'purchase',
            'event_id' => 'purchase_' . $order->id . '_' . $eventTime,
            'event_time' => $eventTime,
            'action_source' => 'website',
            'event_source_url' => url('/thank-you/' . $order->id),
            'value' => (float) $subtotal,
            'currency' => 'BDT',
            'transaction_id' => (string) $order->id,
            'items' => $items,
            'content_ids' => $contentIds,
            'user_data' => $userData,
            'url' => url('/thank-you/' . $order->id),
        ];

        // Add shipping if present
        if ($order->shipping > 0) {
            $eventData['shipping'] = (float) $order->shipping;
        }

        // Add client context for proper enrichment when fired from server
        // We pass the original IP so Cloudflare can do geo lookup
        // We DON'T override geo with delivery address since that's not browsing location
        $eventData['context'] = [
            'user_agent' => $trackingData['user_agent'] ?? $request?->userAgent() ?? 'PixelFly-Server/1.0',
            'page_url' => url('/thank-you/' . $order->id),
            'is_delayed' => true, // Flag to indicate this is a delayed server-side event
        ];

        // Add original customer IP for geo enrichment by Cloudflare
        // Priority: trackingData (OTP flow) > request > order
        if (!empty($trackingData['ip_address'])) {
            $eventData['context']['ip'] = $trackingData['ip_address'];
        } elseif ($request) {
            $eventData['context']['ip'] = $request->ip();
        } elseif ($order->ip_address) {
            $eventData['context']['ip'] = $order->ip_address;
        }

        // Capture UTM parameters and click IDs
        // These are important for GA4, TikTok, Google Ads attribution
        $utmParams = [];
        if ($trackingData) {
            // Use tracking data from session (OTP flow)
            $utmKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'fbclid', 'gclid', 'ttclid'];
            foreach ($utmKeys as $key) {
                if (!empty($trackingData[$key])) {
                    $utmParams[$key] = $trackingData[$key];
                }
            }
        } elseif ($request) {
            // Extract from request
            $utmParams = $this->extractUtmParams($request);
        }

        if (!empty($utmParams)) {
            $eventData['context']['utm'] = $utmParams;
        }

        return $eventData;
    }

    /**
     * Fire a pending purchase event to PixelFly.
     *
     * @param PendingPurchaseEvent $pendingEvent
     * @return bool
     */
    public function fireEvent(PendingPurchaseEvent $pendingEvent): bool
    {
        return $pendingEvent->fire();
    }

    /**
     * Fire the pending purchase event for an order.
     *
     * @param order $order
     * @return bool True if fired successfully, false if no pending event or failed
     */
    public function fireEventForOrder(order $order): bool
    {
        $pendingEvent = PendingPurchaseEvent::where('order_id', $order->id)
            ->pending()
            ->first();

        if (!$pendingEvent) {
            return false;
        }

        return $this->fireEvent($pendingEvent);
    }

    /**
     * Check if an order has a pending (unfired) purchase event.
     *
     * @param order $order
     * @return bool
     */
    public function hasPendingEvent(order $order): bool
    {
        return PendingPurchaseEvent::where('order_id', $order->id)
            ->pending()
            ->exists();
    }

    /**
     * Get the pending event for an order.
     *
     * @param order $order
     * @return PendingPurchaseEvent|null
     */
    public function getPendingEvent(order $order): ?PendingPurchaseEvent
    {
        return PendingPurchaseEvent::where('order_id', $order->id)->first();
    }

    /**
     * Extract UTM parameters and click IDs from request.
     * Checks both query params and referer URL.
     *
     * @param Request $request
     * @return array
     */
    protected function extractUtmParams(Request $request): array
    {
        $params = [];

        // UTM parameters
        $utmKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];
        // Click IDs for various ad platforms
        $clickIds = ['fbclid', 'gclid', 'ttclid', 'msclkid', 'dclid'];

        $allKeys = array_merge($utmKeys, $clickIds);

        // First try to get from current request query params
        foreach ($allKeys as $key) {
            $value = $request->input($key);
            if ($value) {
                $params[$key] = $value;
            }
        }

        // If not found, try to extract from referer URL
        if (empty($params)) {
            $referer = $request->header('Referer');
            if ($referer) {
                $parsedUrl = parse_url($referer);
                if (isset($parsedUrl['query'])) {
                    parse_str($parsedUrl['query'], $queryParams);
                    foreach ($allKeys as $key) {
                        if (!empty($queryParams[$key])) {
                            $params[$key] = $queryParams[$key];
                        }
                    }
                }
            }
        }

        return $params;
    }
}
