<?php

namespace App\Models;

use App\Models\PostSubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $image
 * @property string|null $image_alt
 * @property string|null $canonical_url
 * @property int $is_featured
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PostSubCategory> $postsubcategories
 * @property-read int|null $postsubcategories_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereCanonicalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereImageAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereIsFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PostCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PostCategory extends Model
{
    //

    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'slug',
        'meta_title',
        'meta_description',
        'image',
        'image_alt',
        'canonical_url',
        'is_featured',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'post_category_id');
    }

    public function postsubcategories()
    {
        return $this->hasMany(PostSubCategory::class);
    }


}
