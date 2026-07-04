<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\BasicShippingRule;
use App\Models\order;

/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $heading
 * @property string|null $sub_heading
 * @property string|null $primary_text
 * @property string $order_button_text
 * @property string $order_form_title
 * @property string $order_place_button_text
 * @property string $order_button_url
 * @property int $product_id
 * @property array<array-key, mixed>|null $product_details
 * @property array<array-key, mixed>|null $customer_reviews
 * @property string|null $hero_image
 * @property string|null $hero_image_alt
 * @property string|null $badge_text
 * @property string $badge_color
 * @property bool $status
 * @property string|null $primary_color
 * @property string|null $secondary_color
 * @property string|null $accent_color
 * @property string|null $order_button_color
 * @property int $position
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $creator
 * @property-read mixed $url
 * @property-read \App\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LandingPageSection> $sections
 * @property-read int|null $sections_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereAccentColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereBadgeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereBadgeText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereCustomerReviews($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereHeroImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereHeroImageAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereOrderButtonColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereOrderButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereOrderButtonUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereOrderFormTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereOrderPlaceButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage wherePrimaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage wherePrimaryText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereProductDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereSecondaryColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereSubHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LandingPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'heading',
        'sub_heading',
        'primary_text',
        'order_button_text',
        'order_form_title',
        'order_place_button_text',
        'order_button_url',
        'product_id',
        'product_details',
        'customer_reviews',
        'hero_image',
        'hero_image_alt',
        'badge_text',
        'badge_color',
        'status',
        'position',
        'created_by',
        'primary_color',
        'secondary_color',
        'accent_color',
        'order_button_color',
        'views_total',
        'views_unique',
    ];

    protected $casts = [
        'product_details' => 'array',
        'customer_reviews' => 'array',
        'status' => 'boolean',
        'views_total' => 'integer',
        'views_unique' => 'integer',
    ];

    /**
     * Get the product associated with the landing page.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created the landing page.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the sections for this landing page.
     */
    public function sections()
    {
        return $this->hasMany(LandingPageSection::class)->orderBy('position');
    }

    /**
     * Get active sections for this landing page.
     */
    public function activeSections()
    {
        return $this->sections()->where('status', 1);
    }

    /**
     * Get sections by type.
     */
    public function sectionsByType($type)
    {
        return $this->activeSections()->where('section_type', $type);
    }

    /**
     * Generate slug from title.
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
     * Get the URL for this landing page.
     */
    public function getUrlAttribute()
    {
        return route('landing.page', $this->slug);
    }

    /**
     * Get shipping rules for this landing page.
     */
    public function shippingRules(): MorphMany
    {
        return $this->morphMany(BasicShippingRule::class, 'ruleable');
    }

    /**
     * Orders placed from this landing page.
     */
    public function orders()
    {
        return $this->hasMany(order::class, 'landing_page_id');
    }
}
