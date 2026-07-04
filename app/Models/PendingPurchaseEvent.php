<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Http;

/**
 * Pending Purchase Event Model
 *
 * Stores purchase events for COD orders that should only be fired to
 * analytics platforms (Meta, GA4 via PixelFly) after order confirmation.
 *
 * @property int $id
 * @property int $order_id
 * @property array $event_data
 * @property \Illuminate\Support\Carbon|null $fired_at
 * @property bool $fire_failed
 * @property string|null $fire_error
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PendingPurchaseEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'event_data',
        'fired_at',
        'fire_failed',
        'fire_error',
    ];

    protected $casts = [
        'event_data' => 'array',
        'fired_at' => 'datetime',
        'fire_failed' => 'boolean',
    ];

    /**
     * Get the order that owns this pending event.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(order::class);
    }

    /**
     * Check if this event has already been fired.
     */
    public function isFired(): bool
    {
        return $this->fired_at !== null;
    }

    /**
     * Check if this event is pending (not yet fired).
     */
    public function isPending(): bool
    {
        return $this->fired_at === null && !$this->fire_failed;
    }

    /**
     * Fire the purchase event to PixelFly.
     *
     * @return bool True if successful, false otherwise
     */
    public function fireToPixelFly(): bool
    {
        if ($this->isFired()) {
            return true; // Already fired
        }

        // Get API key and endpoint from settings (with fallback to config)
        $apiKey = DelayedEventSetting::getApiKey() ?? config('services.pixelfly.api_key');
        $endpoint = DelayedEventSetting::getEndpoint() ?? config('services.pixelfly.endpoint', 'https://track.pixelfly.io/e');

        if (!$apiKey) {
            $this->update([
                'fire_failed' => true,
                'fire_error' => 'PixelFly API key not configured. Please configure it in Delayed Events Settings.',
            ]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'X-PF-Key' => $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($endpoint, $this->event_data);

            if ($response->successful()) {
                $this->update([
                    'fired_at' => now(),
                    'fire_failed' => false,
                    'fire_error' => null,
                ]);
                return true;
            }

            $this->update([
                'fire_failed' => true,
                'fire_error' => 'HTTP ' . $response->status() . ': ' . $response->body(),
            ]);
            return false;

        } catch (\Exception $e) {
            $this->update([
                'fire_failed' => true,
                'fire_error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Fire the purchase event to sGTM via GA4 Measurement Protocol.
     *
     * Transforms stored event_data into GA4 MP format and POSTs to sGTM.
     *
     * @return bool True if successful, false otherwise
     */
    public function fireToSGTM(): bool
    {
        if ($this->isFired()) {
            return true;
        }

        $endpoint = DelayedEventSetting::getSgtmEndpoint();
        $measurementId = DelayedEventSetting::getMeasurementId();
        $apiSecret = DelayedEventSetting::getSgtmApiSecret();

        if (!$endpoint || !$measurementId || !$apiSecret) {
            $this->update([
                'fire_failed' => true,
                'fire_error' => 'sGTM configuration incomplete. Please configure endpoint, Measurement ID, and API Secret in Delayed Events Settings.',
            ]);
            return false;
        }

        try {
            $payload = $this->transformToGA4Payload();

            $url = rtrim($endpoint, '/') . '/mp/collect?measurement_id=' . urlencode($measurementId) . '&api_secret=' . urlencode($apiSecret);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(10)->post($url, $payload);

            // GA4 MP returns 204 on success, but sGTM may return 200
            if ($response->successful() || $response->status() === 204) {
                $this->update([
                    'fired_at' => now(),
                    'fire_failed' => false,
                    'fire_error' => null,
                ]);
                return true;
            }

            $this->update([
                'fire_failed' => true,
                'fire_error' => 'HTTP ' . $response->status() . ': ' . $response->body(),
            ]);
            return false;

        } catch (\Exception $e) {
            $this->update([
                'fire_failed' => true,
                'fire_error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Fire the event using the configured firing method (PixelFly or sGTM).
     *
     * @return bool
     */
    public function fire(): bool
    {
        if (DelayedEventSetting::isSgtmMode()) {
            return $this->fireToSGTM();
        }

        return $this->fireToPixelFly();
    }

    /**
     * Transform stored event_data into GA4 Measurement Protocol format.
     *
     * @return array
     */
    protected function transformToGA4Payload(): array
    {
        $eventData = $this->event_data;
        $userData = $eventData['user_data'] ?? [];

        // Derive client_id from fbp cookie or generate one
        $clientId = null;
        if (!empty($userData['fbp'])) {
            // _fbp format: fb.1.timestamp.random — extract the last two parts
            $parts = explode('.', $userData['fbp']);
            if (count($parts) >= 4) {
                $clientId = $parts[2] . '.' . $parts[3];
            }
        }
        if (!$clientId) {
            // Generate a stable client_id from phone or order id
            $clientId = $eventData['event_time'] . '.' . crc32($userData['ph'] ?? $eventData['transaction_id'] ?? uniqid());
        }

        // Build event params
        $eventParams = [
            'transaction_id' => $eventData['transaction_id'] ?? '',
            'value' => (float) ($eventData['value'] ?? 0),
            'currency' => $eventData['currency'] ?? 'BDT',
            'engagement_time_msec' => 1,
        ];

        // Add shipping if present
        if (!empty($eventData['shipping'])) {
            $eventParams['shipping'] = (float) $eventData['shipping'];
        }

        // Transform items
        if (!empty($eventData['items'])) {
            $eventParams['items'] = array_map(function ($item) {
                return [
                    'item_id' => $item['item_id'] ?? '',
                    'item_name' => $item['item_name'] ?? '',
                    'item_category' => $item['item_category'] ?? '',
                    'item_variant' => $item['item_variant'] ?? '',
                    'price' => (float) ($item['price'] ?? 0),
                    'quantity' => (int) ($item['quantity'] ?? 1),
                ];
            }, $eventData['items']);
        }

        // Add UTM/click ID params if available
        $context = $eventData['context'] ?? [];
        $utm = $context['utm'] ?? [];
        if (!empty($utm)) {
            foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'] as $key) {
                if (!empty($utm[$key])) {
                    $eventParams[$key] = $utm[$key];
                }
            }
            if (!empty($utm['gclid'])) {
                $eventParams['gclid'] = $utm['gclid'];
            }
        }

        // Enhanced Conversions user_data — SHA-256 hashed PII as event params
        // Google Ads Enhanced Conversions in sGTM reads these fields directly
        $enhancedUserData = [];
        if (!empty($userData['em'])) {
            $enhancedUserData['sha256_email_address'] = hash('sha256', strtolower(trim($userData['em'])));
        }
        if (!empty($userData['ph'])) {
            // Normalize phone: ensure country code prefix for hashing
            $phone = $userData['ph'];
            if (!str_starts_with($phone, '+')) {
                $phone = '+88' . $phone; // Bangladesh country code
            }
            $enhancedUserData['sha256_phone_number'] = hash('sha256', $phone);
        }
        if (!empty($userData['fn']) || !empty($userData['ln'])) {
            $address = [];
            if (!empty($userData['fn'])) {
                $address['sha256_first_name'] = hash('sha256', strtolower(trim($userData['fn'])));
            }
            if (!empty($userData['ln'])) {
                $address['sha256_last_name'] = hash('sha256', strtolower(trim($userData['ln'])));
            }
            if (!empty($userData['ct'])) {
                $address['city'] = $userData['ct'];
            }
            if (!empty($userData['st'])) {
                $address['region'] = $userData['st'];
            }
            if (!empty($userData['country'])) {
                $address['country'] = $userData['country'];
            }
            $enhancedUserData['address'] = $address;
        }

        if (!empty($enhancedUserData)) {
            $eventParams['user_data'] = $enhancedUserData;
        }

        // Build user properties (plain text for GA4 reporting and Facebook CAPI in sGTM)
        $userProperties = [];
        if (!empty($userData['ph'])) {
            $userProperties['phone'] = ['value' => $userData['ph']];
        }
        if (!empty($userData['em'])) {
            $userProperties['email'] = ['value' => $userData['em']];
        }
        if (!empty($userData['fn'])) {
            $userProperties['first_name'] = ['value' => $userData['fn']];
        }
        if (!empty($userData['ln'])) {
            $userProperties['last_name'] = ['value' => $userData['ln']];
        }
        if (!empty($userData['ct'])) {
            $userProperties['city'] = ['value' => $userData['ct']];
        }
        if (!empty($userData['st'])) {
            $userProperties['region'] = ['value' => $userData['st']];
        }
        if (!empty($userData['country'])) {
            $userProperties['country'] = ['value' => $userData['country']];
        }

        $payload = [
            'client_id' => $clientId,
            'events' => [[
                'name' => 'purchase',
                'params' => $eventParams,
            ]],
        ];

        // Add user_id (phone number as identifier)
        if (!empty($userData['ph'])) {
            $payload['user_id'] = $userData['ph'];
        }

        if (!empty($userProperties)) {
            $payload['user_properties'] = $userProperties;
        }

        return $payload;
    }

    /**
     * Scope to get only pending (unfired) events.
     */
    public function scopePending($query)
    {
        return $query->whereNull('fired_at')->where('fire_failed', false);
    }

    /**
     * Scope to get only fired events.
     */
    public function scopeFired($query)
    {
        return $query->whereNotNull('fired_at');
    }

    /**
     * Scope to get failed events.
     */
    public function scopeFailed($query)
    {
        return $query->where('fire_failed', true);
    }
}
