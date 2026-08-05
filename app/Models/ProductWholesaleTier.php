<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductWholesaleTier extends Model
{
    use HasFactory;

    protected $table = 'product_wholesale_tiers';

    protected $fillable = [
        'product_id',
        'variation_combination_id',
        'min_quantity',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variationCombination()
    {
        return $this->belongsTo(VariationCombination::class, 'variation_combination_id');
    }
}
