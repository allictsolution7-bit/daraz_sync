<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class BackupSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'label',
        'description',
    ];

    /**
     * Get setting value
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        if ($setting->type === 'encrypted') {
            try {
                return Crypt::decryptString($setting->value);
            } catch (\Exception $e) {
                return $default;
            }
        }

        if ($setting->type === 'boolean') {
            return (bool) $setting->value;
        }

        if ($setting->type === 'json') {
            return json_decode($setting->value, true);
        }

        return $setting->value ?? $default;
    }

    /**
     * Set setting value
     */
    public static function set($key, $value, $type = 'text', $category = 'general', $label = null, $description = null)
    {
        // Auto-detect type if not provided
        if ($type === 'text') {
            if (is_bool($value)) {
                $type = 'boolean';
            } elseif (is_array($value)) {
                $type = 'json';
                $value = json_encode($value);
            }
        }

        // Encrypt if type is encrypted
        if ($type === 'encrypted' && !empty($value)) {
            $value = Crypt::encryptString($value);
        }

        // Convert boolean to string for storage
        if ($type === 'boolean') {
            $value = $value ? '1' : '0';
        }

        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'category' => $category,
                'label' => $label ?? ucfirst(str_replace('_', ' ', $key)),
                'description' => $description,
            ]
        );
    }

    /**
     * Get all settings by category
     */
    public static function getByCategory($category)
    {
        return static::where('category', $category)->get()->pluck('value', 'key')->toArray();
    }

    /**
     * Get Google Drive credentials
     */
    public static function getGoogleDriveCredentials()
    {
        return [
            'client_id' => static::get('google_drive_client_id'),
            'client_secret' => static::get('google_drive_client_secret'),
            'refresh_token' => static::get('google_drive_refresh_token'),
            'folder_id' => static::get('google_drive_folder_id'),
        ];
    }

    /**
     * Set Google Drive credentials
     */
    public static function setGoogleDriveCredentials(array $credentials)
    {
        static::set('google_drive_client_id', $credentials['client_id'] ?? '', 'encrypted', 'google_drive', 'Google Drive Client ID');
        static::set('google_drive_client_secret', $credentials['client_secret'] ?? '', 'encrypted', 'google_drive', 'Google Drive Client Secret');
        static::set('google_drive_refresh_token', $credentials['refresh_token'] ?? '', 'encrypted', 'google_drive', 'Google Drive Refresh Token');
        
        if (isset($credentials['folder_id'])) {
            static::set('google_drive_folder_id', $credentials['folder_id'], 'text', 'google_drive', 'Google Drive Folder ID');
        }
    }

    /**
     * Check if Google Drive is enabled
     */
    public static function isGoogleDriveEnabled()
    {
        return static::get('google_drive_enabled', false);
    }

    /**
     * Get storage destination
     */
    public static function getStorageDestination()
    {
        return static::get('storage_destination', 'local');
    }

    /**
     * Get retention settings
     */
    public static function getRetentionSettings()
    {
        return [
            'days' => (int) static::get('retention_days', 30),
            'count' => (int) static::get('retention_count', 10),
        ];
    }
}

