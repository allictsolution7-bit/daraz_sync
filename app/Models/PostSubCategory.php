<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property int $post_category_id
 * @property string $name
 * @property string|null $description
 * @property string $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $image
 * @property string|null $image_alt
 * @property string|null $canonical_url
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\PostCategory|null $postcategory
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereCanonicalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereImageAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory wherePostCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostSubCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PostSubCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_category_id',
        'name',
        'description',
        'slug',
        'meta_title',
        'meta_description',
        'image',
        'image_alt',
        'canonical_url',
        'created_by'
    ];

    public function postcategory()
    {
        return $this->belongsTo(PostCategory::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
