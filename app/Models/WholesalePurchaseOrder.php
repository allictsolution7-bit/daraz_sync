<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalePurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'wholesale_purchase_orders';

    protected $fillable = [
        'order_number',
        'buyer_tenant_id',
        'buyer_subdomain',
        'buyer_admin_id',
        'buyer_admin_name',
        'buyer_admin_phone',
        'buyer_admin_email',
        'buyer_shipping_address',
        'seller_tenant_id',
        'seller_subdomain',
        'seller_admin_id',
        'seller_admin_name',
        'product_id',
        'product_title',
        'product_thumb_image',
        'unit_price',
        'quantity',
        'total_amount',
        'platform_commission',
        'seller_earnings',
        'payment_gateway',
        'sender_phone',
        'trx_id',
        'payment_status',
        'approved_by_superadmin_id',
        'approved_at',
        'rejection_reason',
        'fulfillment_status',
        'courier_name',
        'tracking_number',
        'seller_notes',
        'buyer_local_product_id',
        'seller_order_id',
        'metadata',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'platform_commission' => 'decimal:2',
        'seller_earnings' => 'decimal:2',
        'approved_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_admin_id');
    }

    public function superAdminApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_superadmin_id');
    }

    public function buyerProduct()
    {
        return $this->belongsTo(Product::class, 'buyer_local_product_id');
    }
}
