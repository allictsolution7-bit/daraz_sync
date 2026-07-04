<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $group
 * @property string $key
 * @property string|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiteSetting whereValue($value)
 * @mixin \Eloquent
 */
class SiteSetting extends Model
{
    protected $fillable = ['group', 'key', 'value'];

    public static function get($group, $key, $default = null)
    {
        $setting = self::where('group', $group)->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($group, $key, $value)
    {
        return self::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value]
        );
    }

    public static function group($group)
    {
        return self::where('group', $group)->pluck('value', 'key')->toArray();
    }

    // Get site logo URL
    public static function getLogo()
    {
        $logo = self::get('general', 'logo');
        return $logo ? asset($logo) : asset('default-logo.png');
    }

    // Get site favicon URL
    public static function getFavicon()
    {
        $favicon = self::get('header', 'favicon') ?: self::get('general', 'favicon');

        if ($favicon) {
            $publicPath = public_path($favicon);
            if (file_exists($publicPath)) {
                return asset($favicon);
            }

            $storagePath = storage_path('app/public/' . $favicon);
            if (file_exists($storagePath)) {
                return asset('storage/' . $favicon);
            }
        }

        return asset('default-favicon.ico');
    }

    public static function getPrimaryColor()
    {
        return self::get('general', 'primary_color', '#F02627');
    }
    public static function getSecondaryColor()
    {
        return self::get('general', 'secondary_color', '#113056');
    }
    public static function getAccentColor()
    {
        return self::get('general', 'accent_color', '#F02627');
    }

    // Get "View All" button text
    public static function getViewAllButtonText()
    {
        return self::get('general', 'view_all_button_text', 'View More');
    }

    // SEO Helper Methods
    public static function getDefaultMetaTitle()
    {
        return self::get('seo', 'default_meta_title', 'Thikana Shop - Your Ultimate Fashion Destination');
    }

    public static function getDefaultMetaDescription()
    {
        return self::get('seo', 'default_meta_description', 'Discover the latest fashion trends at Thikana Shop. Shop premium quality clothing, accessories, and more at competitive prices. Fast shipping, secure payments, and excellent customer service.');
    }

    public static function getDefaultMetaKeywords()
    {
        return self::get('seo', 'default_meta_keywords', 'fashion, clothing, accessories, online shopping, thikana shop');
    }

    public static function getDefaultOgImage()
    {
        $ogImage = self::get('seo', 'default_og_image');
        return $ogImage ? asset($ogImage) : asset('new/logo.png');
    }

    public static function getSiteAuthor()
    {
        return self::get('seo', 'site_author', 'Thikana Shop');
    }

    public static function getTwitterUsername()
    {
        return self::get('seo', 'twitter_username', '@thikanashop');
    }



    public static function getRobotsMeta()
    {
        return self::get('seo', 'robots_meta', 'index, follow');
    }

    public static function getCanonicalBase()
    {
        return self::get('seo', 'canonical_base', url('/'));
    }

    public static function isSchemaEnabled()
    {
        return self::get('seo', 'enable_schema', '1') == '1';
    }

    public static function getOrganizationSchema()
    {
        return self::get('seo', 'organization_schema', '');
    }

    /**
     * Convert YouTube URL to embed URL
     */
    public static function getYoutubeEmbedUrl($url)
    {
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([\w\-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
        return null;
    }
}