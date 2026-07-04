<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Security Headers Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration for various security headers
    | that should be applied to all responses.
    |
    */

    'headers' => [
        'hsts' => [
            'enabled' => env('SECURITY_HSTS_ENABLED', true),
            'max_age' => env('SECURITY_HSTS_MAX_AGE', 31536000),
            'include_subdomains' => env('SECURITY_HSTS_INCLUDE_SUBDOMAINS', true),
            'preload' => env('SECURITY_HSTS_PRELOAD', true),
        ],

        'csp' => [
            'enabled' => env('SECURITY_CSP_ENABLED', true),
            'report_only' => env('SECURITY_CSP_REPORT_ONLY', false),
            'report_uri' => env('SECURITY_CSP_REPORT_URI', null),
        ],

        'frame_options' => [
            'enabled' => env('SECURITY_FRAME_OPTIONS_ENABLED', true),
            'value' => env('SECURITY_FRAME_OPTIONS_VALUE', 'SAMEORIGIN'),
        ],

        'content_type_options' => [
            'enabled' => env('SECURITY_CONTENT_TYPE_OPTIONS_ENABLED', true),
            'value' => env('SECURITY_CONTENT_TYPE_OPTIONS_VALUE', 'nosniff'),
        ],

        'referrer_policy' => [
            'enabled' => env('SECURITY_REFERRER_POLICY_ENABLED', true),
            'value' => env('SECURITY_REFERRER_POLICY_VALUE', 'strict-origin-when-cross-origin'),
        ],

        'permissions_policy' => [
            'enabled' => env('SECURITY_PERMISSIONS_POLICY_ENABLED', true),
            'value' => env('SECURITY_PERMISSIONS_POLICY_VALUE', 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=(), ambient-light-sensor=(), autoplay=(), encrypted-media=(), picture-in-picture=(), speaker-selection=(), conversion-measurement=(), fullscreen=(self), display-capture=()'),
        ],

        'xss_protection' => [
            'enabled' => env('SECURITY_XSS_PROTECTION_ENABLED', true),
            'value' => env('SECURITY_XSS_PROTECTION_VALUE', '1; mode=block'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTPS Configuration
    |--------------------------------------------------------------------------
    |
    | Force HTTPS for all requests in production.
    |
    */

    'force_https' => env('FORCE_HTTPS', true),

    /*
    |--------------------------------------------------------------------------
    | Session Security
    |--------------------------------------------------------------------------
    |
    | Additional session security settings.
    |
    */

    'session' => [
        'secure' => env('SESSION_SECURE_COOKIE', true),
        'http_only' => env('SESSION_HTTP_ONLY', true),
        'same_site' => env('SESSION_SAME_SITE', 'lax'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Rate limiting configuration for API and web routes.
    |
    */

    'rate_limiting' => [
        'web' => [
            'max_attempts' => env('RATE_LIMIT_WEB_MAX_ATTEMPTS', 60),
            'decay_minutes' => env('RATE_LIMIT_WEB_DECAY_MINUTES', 1),
        ],
        'api' => [
            'max_attempts' => env('RATE_LIMIT_API_MAX_ATTEMPTS', 60),
            'decay_minutes' => env('RATE_LIMIT_API_DECAY_MINUTES', 1),
        ],
    ],

]; 