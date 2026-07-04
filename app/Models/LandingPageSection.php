<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $landing_page_id
 * @property string $section_type
 * @property string|null $header_logo
 * @property string|null $header_logo_alt
 * @property string $header_alignment
 * @property string|null $header_button1_text
 * @property string|null $header_button1_url
 * @property string|null $header_button1_color
 * @property string|null $header_button1_text_color
 * @property bool $header_button1_active
 * @property string|null $header_button2_text
 * @property string|null $header_button2_url
 * @property string|null $header_button2_color
 * @property string|null $header_button2_text_color
 * @property bool $header_button2_active
 * @property int $header_desktop_logo_width
 * @property int $header_mobile_logo_width
 * @property string|null $title
 * @property string|null $benefit_title
 * @property string|null $testimonials_title
 * @property string|null $feature_list_title
 * @property string|null $pricing_title
 * @property string|null $countdown_title
 * @property string|null $description
 * @property string|null $heading
 * @property string|null $sub_heading
 * @property string|null $primary_text
 * @property string|null $hero_image
 * @property string|null $hero_image_alt
 * @property string|null $hero_video_url
 * @property string|null $badge_image
 * @property int|null $badge_desktop_width
 * @property int|null $badge_mobile_width
 * @property string|null $single_image
 * @property string|null $single_image_alt
 * @property string|null $cta_title
 * @property string|null $cta_subtitle
 * @property string|null $cta_button_text
 * @property string|null $cta_phone_number
 * @property string|null $cta_background_color
 * @property string|null $cta_button_color
 * @property string|null $badge_text
 * @property string|null $badge_color
 * @property array<array-key, mixed>|null $trust_indicators
 * @property bool $show_trust_indicators
 * @property string|null $icon
 * @property string|null $reviewer_name
 * @property array<array-key, mixed>|null $testimonials
 * @property array<array-key, mixed>|null $benefits
 * @property array<array-key, mixed>|null $feature_items
 * @property array<array-key, mixed>|null $pricing_variants
 * @property string|null $image
 * @property string|null $image_alt
 * @property string|null $background_color
 * @property string|null $text_color
 * @property int|null $countdown_hours
 * @property bool $countdown_repeat
 * @property string|null $video_url
 * @property string|null $video_title
 * @property string|null $video_description
 * @property int $position
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\LandingPage $landingPage
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection byType($type)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereBadgeColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereBadgeDesktopWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereBadgeImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereBadgeMobileWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereBadgeText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereBenefitTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereBenefits($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCountdownHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCountdownRepeat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCountdownTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCtaBackgroundColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCtaButtonColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCtaButtonText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCtaPhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCtaSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereCtaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereFeatureItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereFeatureListTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderAlignment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton1Active($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton1Color($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton1Text($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton1TextColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton1Url($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton2Active($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton2Color($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton2Text($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton2TextColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderButton2Url($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderDesktopLogoWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderLogoAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeaderMobileLogoWidth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeroImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeroImageAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereHeroVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereImageAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereLandingPageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection wherePricingTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection wherePricingVariants($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection wherePrimaryText($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereReviewerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereSectionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereShowTrustIndicators($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereSingleImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereSingleImageAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereSubHeading($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereTestimonials($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereTestimonialsTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereTextColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereTrustIndicators($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereVideoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereVideoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LandingPageSection whereVideoUrl($value)
 * @mixin \Eloquent
 */
class LandingPageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'landing_page_id',
        'section_type',
        'title',
        'description',
        'icon',
        
        'testimonials',       // <-- NEW
        'benefits',
        'feature_items',      // <-- NEW
        'pricing_variants',   // <-- NEW
        'carousel_images',    // <-- NEW for image carousel section
        'heading',
        'sub_heading',
        'primary_text',
        'hero_image',
        'hero_image_alt',
        'hero_video_url',
        'single_image',
        'single_image_alt',
        'badge_image',
        'badge_desktop_width',
        'badge_mobile_width',
        'badge_text',
        'badge_color',
        'trust_indicators',   // <-- NEW
        'show_trust_indicators', // <-- NEW
        'image',
        'image_alt',
        'background_color',
        'text_color',
        'position',
        'status',
        'countdown_hours',
        'countdown_repeat',
        'video_url',          // <-- NEW for video section
        'video_title',        // <-- NEW for video section
        'video_description',  // <-- NEW for video section
        'benefit_title',      // <-- NEW for benefit section
        'testimonials_title', // <-- NEW for testimonials section
        'feature_list_title', // <-- NEW for feature list section
        'pricing_title',      // <-- NEW for pricing section
        'countdown_title',    // <-- NEW for countdown section
        'cta_title',          // <-- NEW for call to action section
        'cta_subtitle',       // <-- NEW for call to action section
        'cta_button_text',    // <-- NEW for call to action section
        'cta_phone_number',   // <-- NEW for call to action section
        'cta_background_color', // <-- NEW for call to action section
        'cta_button_color',   // <-- NEW for call to action section
        'header_logo',        // <-- NEW for header section
        'header_logo_alt',    // <-- NEW for header section
        'header_alignment',   // <-- NEW for header section
        'header_button1_text', // <-- NEW for header section
        'header_button1_url',  // <-- NEW for header section
        'header_button1_color', // <-- NEW for header section
        'header_button1_text_color', // <-- NEW for header section
        'header_button1_active', // <-- NEW for header section
        'header_button2_text', // <-- NEW for header section
        'header_button2_url',  // <-- NEW for header section
        'header_button2_color', // <-- NEW for header section
        'header_button2_text_color', // <-- NEW for header section
        'header_button2_active', // <-- NEW for header section
        'header_desktop_logo_width', // <-- NEW for header section
        'header_mobile_logo_width', // <-- NEW for header section
        // 'content', // (optional, if you keep the column)
    ];

    protected $casts = [
        'testimonials' => 'array', // <-- NEW
        'benefits' => 'array',    // <-- ADD THIS
        'feature_items' => 'array', // <-- NEW
        'pricing_variants' => 'array', // <-- NEW
        'carousel_images' => 'array', // <-- NEW for image carousel section
        'trust_indicators' => 'array', // <-- NEW
        'show_trust_indicators' => 'boolean', // <-- NEW
        'header_button1_active' => 'boolean', // <-- NEW for header section
        'header_button2_active' => 'boolean', // <-- NEW for header section
        'status' => 'boolean',
        'countdown_repeat' => 'boolean',
        // 'content' => 'array', // (optional, if you keep the column)
    ];

    /**
     * Get the landing page that owns this section.
     */
    public function landingPage()
    {
        return $this->belongsTo(LandingPage::class);
    }

    /**
     * Scope to get active sections.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope to get sections by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('section_type', $type);
    }
}