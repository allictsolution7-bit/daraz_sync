<?php

return [
    /*
    |--------------------------------------------------------------------------
    | License System Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the license system including RSA signature verification
    | and mother panel communication settings.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Mother Panel URL
    |--------------------------------------------------------------------------
    |
    | The URL of the mother panel (uddoktaecommerce) for license validation.
    | This should be the production URL in live environments.
    |
    */
    // 'mother_panel_url' => env('LICENSE_MOTHER_PANEL_URL', 'http://127.0.0.1:8001/api/licenses/validate'),
    'mother_panel_url' => env('LICENSE_MOTHER_PANEL_URL', 'https://uddoktaecommerce.com/api/licenses/validate'),

    /*
    |--------------------------------------------------------------------------
    | Cache TTL
    |--------------------------------------------------------------------------
    |
    | How long to cache license validation responses in seconds.
    | Default is 24 hours (86400 seconds).
    |
    */
    'cache_ttl' => env('LICENSE_CACHE_TTL', 24 * 60 * 60),

    /*
    |--------------------------------------------------------------------------
    | RSA Signature Verification
    |--------------------------------------------------------------------------
    |
    | Enable or disable RSA signature verification for license responses.
    | This provides additional security by verifying that responses come
    | from the legitimate mother panel.
    |
    */
    'enable_signature_verification' => env('LICENSE_ENABLE_SIGNATURE_VERIFICATION', true),

    /*  
    |--------------------------------------------------------------------------
    | RSA Public Key
    |--------------------------------------------------------------------------
    |
    | The RSA public key used to verify signatures from the mother panel.
    | This should be stored in environment variables for security.
    |
    */
    'public_key' => env('LICENSE_RSA_PUBLIC_KEY', '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAriFPtTXpY92d8tCYGTep
BnfxHn8RMeiuUxNpmO9uqPS0aLE0JIn0Yf2mUhaeQ4evehxTzeh1ontFXXl5xFEi
Q23LJLLnhBSas4B+BienD0OwheiFMeO/Zklh/NzKd9BLth2TItKfRHpZfPqnkk1N
MG3isNQIQdkn0lSSRM+ukYGcHeYRmPYp6nXXHe1thSXLeffQ6qyl3Xa+kiY7wnoo
i4J7cz8WccHso4LHW1ndJ3zd8mMpEYfPbwOybZ0U1aE4uU9KdldpjtmGytKLDWDR
99drWFm8kECG9f81vLmxuOqCFz+aJW9LRGTfT/q9c806dLwTHEey1jfLDmN+8RNo
8QIDAQAB
-----END PUBLIC KEY-----'),

    /*
    |--------------------------------------------------------------------------
    | Signature Algorithm
    |--------------------------------------------------------------------------
    |
    | The OpenSSL algorithm used for signature verification.
    | OPENSSL_ALGO_SHA256 is recommended for security.
    |
    */
    'signature_algorithm' => OPENSSL_ALGO_SHA256,

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Enable debug logging for license operations.
    | This should be disabled in production.
    |
    */
    'debug' => env('LICENSE_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Update API Endpoints
    |--------------------------------------------------------------------------
    |
    | These URLs are used by the update client to check for new releases and
    | report progress back to the mother panel.
    |
    */

    'update' => [
        'check_url' => env('LICENSE_UPDATE_CHECK_URL', 'https://uddoktaecommerce.com/api/updates/check'),
        //'check_url' => env('LICENSE_UPDATE_CHECK_URL', 'http://127.0.0.1:8001/api/updates/check'),
        'report_url' => env('LICENSE_UPDATE_REPORT_URL', 'https://uddoktaecommerce.com/api/updates/report'),
        //'report_url' => env('LICENSE_UPDATE_REPORT_URL', 'http://127.0.0.1:8001/api/updates/report'),
        'summary_url' => env('LICENSE_UPDATE_SUMMARY_URL', 'https://uddoktaecommerce.com/api/updates/latest'),
        //'summary_url' => env('LICENSE_UPDATE_SUMMARY_URL', 'http://127.0.0.1:8001/api/updates/latest'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Strict Security Checks
    |--------------------------------------------------------------------------
    |
    | Enable or disable strict security checks including:
    | - System integrity verification
    | - File tampering detection
    | - Strict tamper attempt monitoring (threshold: 5)
    | - Suspicious request pattern detection
    |
    | When disabled, only basic license validation and module access control
    | are enforced. Tamper monitoring threshold is raised to 100.
    |
    | Security Levels:
    | true  = MAXIMUM SECURITY (blocks all violations immediately)
    | false = RELAXED SECURITY (logs violations but only blocks after 100+ attempts)
    |
    */
    'strict_security_checks' => env('LICENSE_STRICT_SECURITY_CHECKS', true),
];
