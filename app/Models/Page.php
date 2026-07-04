<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $status
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Page extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'slug',
        'status',
        'content',
        'seo',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'meta_robots',
        'og_image',
        'og_image_alt',
        'schema_markup',
    ];

    protected $casts = [
        'seo' => 'array',
        'status' => 'boolean',
    ];

    /**
     * Get properly formatted SEO data
     */
    public function getFormattedSeoAttribute()
    {
        $seo = $this->seo;
        
        // If SEO is a string, try to decode it
        if (is_string($seo)) {
            $seo = json_decode($seo, true);
        }
        
        // If SEO is null or empty, return empty array
        if (empty($seo) || !is_array($seo)) {
            return [];
        }
        
        return $seo;
    }

    /**
     * Get the effective meta title (use seo field or individual field)
     */
    public function getEffectiveMetaTitleAttribute()
    {
        $seo = $this->formatted_seo;
        return $seo['meta_title'] ?? $this->meta_title ?? $this->title;
    }

    /**
     * Get the effective meta description (use seo field or individual field)
     */
    public function getEffectiveMetaDescriptionAttribute()
    {
        $seo = $this->formatted_seo;
        return $seo['meta_description'] ?? $this->meta_description ?? substr(strip_tags($this->content), 0, 160);
    }

    /**
     * Get the effective meta keywords (use seo field or individual field)
     */
    public function getEffectiveMetaKeywordsAttribute()
    {
        $seo = $this->formatted_seo;
        return $seo['meta_keywords'] ?? $this->meta_keywords ?? '';
    }

    /**
     * Get the effective canonical URL (use seo field or individual field)
     */
    public function getEffectiveCanonicalUrlAttribute()
    {
        $seo = $this->formatted_seo;
        return $seo['canonical_url'] ?? $this->canonical_url ?? url('/pages/' . $this->slug);
    }

    /**
     * Get the effective meta robots (use seo field or individual field)
     */
    public function getEffectiveMetaRobotsAttribute()
    {
        $seo = $this->formatted_seo;
        return $seo['meta_robots'] ?? $this->meta_robots ?? 'index,follow';
    }

    /**
     * Get the effective OG image (use seo field or individual field)
     */
    public function getEffectiveOgImageAttribute()
    {
        $seo = $this->formatted_seo;
        return $seo['og_image'] ?? $this->og_image ?? '';
    }

    /**
     * Get the effective OG image alt (use seo field or individual field)
     */
    public function getEffectiveOgImageAltAttribute()
    {
        $seo = $this->formatted_seo;
        return $seo['og_image_alt'] ?? $this->og_image_alt ?? $this->title;
    }

    /**
     * Get the effective schema markup (use seo field or individual field)
     */
    public function getEffectiveSchemaMarkupAttribute()
    {
        $seo = $this->formatted_seo;
        return $seo['schema_markup'] ?? $this->schema_markup ?? '';
    }

    /**
     * Generate unique slug from title.
     */
    public static function generateSlug($title)
    {
        $slug = \Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = static::generateSlug($page->title);
            }
        });
    }
}
