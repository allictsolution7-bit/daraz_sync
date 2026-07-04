<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property int $post_category_id
 * @property int|null $post_sub_category_id
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $canonical_url
 * @property string|null $image
 * @property string|null $image_alt
 * @property string|null $tags
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $tags_array
 * @property-read \App\Models\PostCategory $postcategory
 * @property-read \App\Models\PostSubCategory|null $postsubcategory
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCanonicalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereImageAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post wherePostCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post wherePostSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereTags($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_category_id',
        'post_sub_category_id',
        'title',
        'content',
        'slug',
        'meta_title',
        'meta_description',
        'image',
        'image_alt',
        'canonical_url',
        'created_by',
        'tags',
    ];

    /**
     * Get the user who created the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the category associated with the post.
     */
    public function postcategory()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    /**
     * Get the subcategory associated with the post.
     */
    public function postsubcategory()
    {
        return $this->belongsTo(PostSubCategory::class, 'post_sub_category_id');
    }

    /**
     * Get tags as an array
     */
    public function getTagsArrayAttribute()
    {
        if (empty($this->tags)) {
            return [];
        }
        return array_map('trim', explode(',', $this->tags));
    }

    /**
     * Get comments for this post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->where('status', 'approved');
    }



    /**
     * Get all comments count for this post.
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
}
