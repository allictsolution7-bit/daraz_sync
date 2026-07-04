<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ThirdCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_category_id',
        'name',
        'slug',
        'description',
        'image',
        'background_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the sub category that owns this third category.
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    /**
     * Get all products in this third category.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_third_category_pivot', 'third_category_id', 'product_id')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('product_third_category_pivot.sort_order');
    }

    /**
     * Scope: Only active third categories
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope: Ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
