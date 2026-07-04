<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $product_category_id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property string|null $image
 * @property string|null $background_image
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductCategory $category
 * @property-read \App\Models\ProductCategory $product_category
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereBackgroundImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereProductCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'product_category_id',
        'description',
        'image',
        'background_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status'
    ];

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, "product_category_id", "id");
    }

    // Alias for the category relationship
    public function product_category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->category();
    }

    /**
     * Get all products in this subcategory (primary + additional via pivot)
     */
    public function additionalProducts()
    {
        return $this->belongsToMany(Product::class, 'product_subcategory_pivot', 'sub_category_id', 'product_id')
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    /**
     * Third level categories under this subcategory
     */
    public function thirdCategories()
    {
        return $this->hasMany(ThirdCategory::class)->ordered();
    }
}
