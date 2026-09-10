<?php

namespace App\Services;

use App\Models\SmsSetting;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected string $url;
    protected ?string $apiKey;
    protected ?string $senderId;

    public function __construct()
    {
        $this->url = env('SMS_API_URL', 'https://bulksmsbd.net/api/smsapi');
        $this->apiKey = env('SMS_API_KEY');
        $this->senderId = env('SMS_SENDER_ID');
    }

    /**
     * Send SMS to a single number.
     * 100% backward-compatible with legacy calls: sendSMS($number, $message)
     *
     * @param string $number Recipient phone number
     * @param string $message SMS message body
     * @param int|null $senderUserId Optional portal owner/admin user ID
     * @param string|null $forcedGateway Optional override gateway ('bulksmsbd' or 'awaj')
     * @return string Response body from provider
     */
    public function sendSMS($number, $message, ?int $senderUserId = null, ?string $forcedGateway = null): string
    {
        $number = $this->formatPhoneNumber($number);
        if (empty($number)) {
            Log::warning("SMSService: Invalid phone number provided: {$number}");
            return json_encode(['success' => false, 'error' => 'Invalid phone number']);
        }

        // Fetch user or central settings
        $settings = SmsSetting::getSettingsForUser($senderUserId);

        // Check if SMS sending is enabled
        if (!$settings->is_enabled) {
            Log::info("SMSService: SMS sending is disabled in settings for user ID " . ($senderUserId ?? 'central'));
            return json_encode(['success' => false, 'message' => 'SMS service is disabled in settings']);
        }

        $gateway = $forcedGateway ?: ($settings->default_gateway ?: 'bulksmsbd');

        try {
            if ($gateway === 'awaj') {
                return $this->sendViaAwaj($number, $message, $settings);
            }

            // Default to BulkSMSBD
            return $this->sendViaBulkSMSBD($number, $message, $settings);
        } catch (\Throwable $e) {
            Log::error("SMSService Error: Failed to send SMS via {$gateway}", [
                'number' => $number,
                'error' => $e->getMessage(),
            ]);

            return json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Send event-based SMS if the event trigger toggle is enabled in settings.
     *
     * @param string $event Event identifier ('product_sold', 'user_created', 'admin_expiry', 'order_status')
     * @param string $recipientNumber Target phone number
     * @param array $placeholders Dynamic variables for template replacements
     * @param int|null $senderUserId Portal/admin ID whose settings and gateway to use
     * @return array Result status ['sent' => bool, 'reason' => string, 'response' => string|null]
     */
    public function sendEventSMS(string $event, string $recipientNumber, array $placeholders = [], ?int $senderUserId = null): array
    {
        $settings = SmsSetting::getSettingsForUser($senderUserId);

        if (!$settings->is_enabled) {
            return ['sent' => false, 'reason' => 'SMS service disabled in settings', 'response' => null];
        }

        // Check event toggle
        $isEventEnabled = match ($event) {
            'product_sold' => (bool)$settings->notify_product_sold,
            'user_created' => (bool)$settings->notify_user_created,
            'admin_expiry' => (bool)$settings->notify_admin_expiry,
            'order_status' => (bool)$settings->notify_order_status_change,
            default => false,
        };

        if (!$isEventEnabled) {
            return ['sent' => false, 'reason' => "Event '{$event}' is disabled in settings", 'response' => null];
        }

        // Get template
        $template = match ($event) {
            'product_sold' => $settings->template_product_sold ?: SmsSetting::defaultTemplate('product_sold'),
            'user_created' => $settings->template_user_created ?: SmsSetting::defaultTemplate('user_created'),
            'admin_expiry' => $settings->template_admin_expiry ?: SmsSetting::defaultTemplate('admin_expiry'),
            'order_status' => $settings->template_order_status ?: SmsSetting::defaultTemplate('order_status'),
            default => '',
        };

        if (empty($template)) {
            return ['sent' => false, 'reason' => 'Empty SMS template for event', 'response' => null];
        }

        // Default placeholders
        if (!isset($placeholders['store_name'])) {
            $placeholders['store_name'] = SiteSetting::get('general', 'site_name', config('app.name', 'Thikana Shop'));
        }

        $renderedMessage = $this->renderTemplate($template, $placeholders);

        $response = $this->sendSMS($recipientNumber, $renderedMessage, $senderUserId);

        return [
            'sent' => true,
            'reason' => 'Delivered to gateway',
            'message' => $renderedMessage,
            'response' => $response,
        ];
    }

    /**
     * Dispatch SMS via BulkSMSBD
     */
    public function sendViaBulkSMSBD(string $number, string $message, ?SmsSetting $settings = null): string
    {
        $url = ($settings && $settings->bulksmsbd_url) ? $settings->bulksmsbd_url : ($this->url ?: 'https://bulksmsbd.net/api/smsapi');
        $apiKey = ($settings && $settings->bulksmsbd_api_key) ? $settings->bulksmsbd_api_key : $this->apiKey;
        $senderId = ($settings && $settings->bulksmsbd_sender_id) ? $settings->bulksmsbd_sender_id : $this->senderId;

        $data = [
            'api_key' => $apiKey,
            'senderid' => $senderId,
            'number' => $number,
            'message' => $message,
        ];

        $response = Http::timeout(30)->asForm()->post($url, $data);
        return $response->body();
    }

    /**
     * Dispatch SMS via Awaj / Awaaz SMS Gateway
     */
    public function sendViaAwaj(string $number, string $message, ?SmsSetting $settings = null): string
    {
        $url = ($settings && $settings->awaj_url) ? $settings->awaj_url : 'https://api.awajdigital.com/api';
        $apiKey = $settings?->awaj_api_key;
        $senderId = $settings?->awaj_sender_id;
        $clientId = $settings?->awaj_client_id;
        $secretKey = $settings?->awaj_secret_key;

        // Ensure endpoint path if only base URL provided
        if (!str_contains($url, '/sms') && !str_contains($url, '/send')) {
            $url = rtrim($url, '/') . '/sms/send';
        }

        $payload = [
            'api_key' => $apiKey,
            'sender_id' => $senderId,
            'senderid' => $senderId,
            'number' => $number,
            'to' => $number,
            'message' => $message,
            'text' => $message,
        ];

        if (!empty($clientId)) {
            $payload['client_id'] = $clientId;
        }
        if (!empty($secretKey)) {
            $payload['secret_key'] = $secretKey;
        }

        $request = Http::timeout(30);

        if (!empty($apiKey)) {
            $request = $request->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
            ]);
        }

        $response = $request->asForm()->post($url, $payload);
        return $response->body();
    }

    /**
     * Test gateway connectivity with specific credentials
     */
    public function testConnection(string $gateway, array $config, string $testNumber, string $testMessage): array
    {
        $testNumber = $this->formatPhoneNumber($testNumber);
        if (empty($testNumber)) {
            return ['success' => false, 'message' => 'Invalid phone number format'];
        }

        try {
            if ($gateway === 'awaj') {
                $dummySetting = new SmsSetting([
                    'awaj_url' => $config['awaj_url'] ?? 'https://api.awajdigital.com/api',
                    'awaj_api_key' => $config['awaj_api_key'] ?? '',
                    'awaj_sender_id' => $config['awaj_sender_id'] ?? '',
                    'awaj_client_id' => $config['awaj_client_id'] ?? '',
                    'awaj_secret_key' => $config['awaj_secret_key'] ?? '',
                ]);
                $res = $this->sendViaAwaj($testNumber, $testMessage, $dummySetting);
            } else {
                $dummySetting = new SmsSetting([
                    'bulksmsbd_url' => $config['bulksmsbd_url'] ?? 'https://bulksmsbd.net/api/smsapi',
                    'bulksmsbd_api_key' => $config['bulksmsbd_api_key'] ?? '',
                    'bulksmsbd_sender_id' => $config['bulksmsbd_sender_id'] ?? '',
                ]);
                $res = $this->sendViaBulkSMSBD($testNumber, $testMessage, $dummySetting);
            }

            return [
                'success' => true,
                'message' => 'Test request dispatched successfully to provider.',
                'raw_response' => $res,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Replace template variables with values
     */
    protected function renderTemplate(string $template, array $placeholders): string
    {
        $search = [];
        $replace = [];

        foreach ($placeholders as $key => $value) {
            $search[] = '{' . $key . '}';
            $replace[] = (string)$value;
        }

        return str_replace($search, $replace, $template);
    }

    /**
     * Clean and standardise phone number (Bangladesh format: 8801xxxxxxxxx)
     */
    protected function formatPhoneNumber(?string $number): string
    {
        if (empty($number)) {
            return '';
        }

        // Strip non-digit characters
        $clean = preg_replace('/[^0-9]/', '', $number);

        // If 11 digits starting with 01, prepend 88
        if (strlen($clean) === 11 && str_starts_with($clean, '01')) {
            return '88' . $clean;
        }

        // If 10 digits starting with 1, prepend 880
        if (strlen($clean) === 10 && str_starts_with($clean, '1')) {
            return '880' . $clean;
        }

        return $clean;
    }
}
