<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorProductAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'product_id',
        'requested_quantity',
        'allocated_quantity',
        'variation_allocations',
        'total_cost',
        'status',
    ];

    protected $casts = [
        'variation_allocations' => 'array',
        'total_cost' => 'decimal:2',
        'requested_quantity' => 'integer',
        'allocated_quantity' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
