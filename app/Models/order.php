<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LandingPage;
use App\Models\User;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $upazila
 * @property string|null $city
 * @property string $address
 * @property array<array-key, mixed>|null $delivery_data
 * @property int|null $fraud_check_result_id
 * @property bool $fraud_check_completed
 * @property \Illuminate\Support\Carbon|null $fraud_check_at
 * @property string $phone
 * @property string|null $message
 * @property int|null $user_id
 * @property string $status
 * @property int $is_combo_order
 * @property int|null $combo_offer_id
 * @property array<array-key, mixed>|null $combo_selections
 * @property string|null $admin_note
 * @property string|null $courier_note
 * @property int|null $assign
 * @property string $total
 * @property string $discount
 * @property string $shipping
 * @property string $payment_method
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $status_updates
 * @property int|null $assigned_to
 * @property string|null $order_source
 * @property string|null $bkash_number
 * @property string|null $bkash_transaction_id
 * @property string|null $bkash_charge
 * @property string|null $nagad_number
 * @property string|null $nagad_transaction_id
 * @property string|null $nagad_charge
 * @property string|null $rocket_number
 * @property string|null $rocket_transaction_id
 * @property string|null $rocket_charge
 * @property string $total_with_charge
 * @property string $payment_status
 * @property-read \App\Models\ComboOffer|null $comboOffer
 * @property-read \App\Models\FraudCheckResult|null $fraudCheckResult
 * @property-read string $fraud_risk_level
 * @property-read int $fraud_risk_score
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\order_item> $order_items
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Product|null $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereAdminNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereCourierNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereAssign($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereBkashCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereBkashNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereBkashTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereComboOfferId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereComboSelections($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereDeliveryData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereFraudCheckAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereFraudCheckCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereFraudCheckResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereIsComboOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereNagadCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereNagadNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereNagadTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereOrderSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereRocketCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereRocketNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereRocketTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereShipping($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereStatusUpdates($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereTotalWithCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereUpazila($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|order whereUserId($value)
 * @mixin \Eloquent
 */
class order extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            if ($order->isDirty('status') && $order->status === 'delivered') {
                $order->delivered_at = now();
            }
        });
    }

    protected $guarded = [];

    protected $appends = ['order_number'];

    // Automatically cast delivery_data JSON column to array
    protected $casts = [
        'delivery_data' => 'array',
        'combo_selections' => 'array',
        'fraud_check_completed' => 'boolean',
        'fraud_check_at' => 'datetime',
    ];

    // public function order_items()
    // {
    //     return $this->hasMany(order_item::class, 'order_id', 'id');
    // }

    // public function products()
    // {
    //     return $this->hasManyThrough(Product::class, order_item::class, 'order_id', 'id', 'id', 'product_id');
    // }

    // Change this relationship to eager load the product and variation combination
    public function order_items()
    {
        return $this->hasMany(order_item::class)
            ->with(['product', 'variationCombination']);
    }

    // Alias for camelCase (used by observers and other code)
    public function orderItems()
    {
        return $this->order_items();
    }

    // Keep other existing relationships
    public function products()
    {
        return $this->hasManyThrough(Product::class, order_item::class, 'order_id', 'id', 'id', 'product_id');
    }

    // OrderItem.php
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Alias for better semantics (customer is the user who placed the order)
    public function customer()
    {
        return $this->user();
    }

    public function landingPage()
    {
        return $this->belongsTo(LandingPage::class, 'landing_page_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comboOffer()
    {
        return $this->belongsTo(\App\Models\ComboOffer::class, 'combo_offer_id');
    }

    /**
     * Get the fraud check result for this order
     */
    public function fraudCheckResult()
    {
        return $this->belongsTo(FraudCheckResult::class, 'fraud_check_result_id');
    }

    public function pendingPurchaseEvent()
    {
        return $this->hasOne(PendingPurchaseEvent::class);
    }

    /**
     * Get the combo selections as an array.
     */
    public function getComboSelectionsAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }
        return $value ?: [];
    }

    /**
     * Check if order has fraud check data
     */
    public function hasFraudCheck(): bool
    {
        return $this->fraud_check_completed && $this->fraudCheckResult;
    }

    /**
     * Get fraud risk level for this order
     */
    public function getFraudRiskLevelAttribute(): string
    {
        return $this->fraudCheckResult?->risk_level ?? 'unknown';
    }

    /**
     * Get fraud risk score for this order
     */
    public function getFraudRiskScoreAttribute(): int
    {
        return $this->fraudCheckResult?->risk_score ?? 0;
    }

    /**
     * Check if order has been sent to a courier
     */
    public function hasCourierProvider(): bool
    {
        return isset($this->delivery_data['courier_provider']);
    }

    /**
     * Get courier provider name
     */
    public function getCourierProvider(): ?string
    {
        return $this->delivery_data['courier_provider'] ?? null;
    }

    /**
     * Get courier consignment ID
     */
    public function getCourierConsignmentId(): ?string
    {
        return $this->delivery_data['consignment_id'] ?? null;
    }

    /**
     * Check if order has a courier consignment ID
     */
    public function hasCourierConsignmentId(): bool
    {
        return !empty($this->delivery_data['consignment_id']);
    }

    /**
     * Get courier tracking code
     */
    public function getCourierTrackingCode(): ?string
    {
        return $this->delivery_data['tracking_code'] ?? null;
    }

    /**
     * Get full courier response
     */
    public function getCourierResponse(): ?array
    {
        return $this->delivery_data['courier_response'] ?? null;
    }

    /**
     * Update courier consignment ID
     */
    public function updateCourierConsignmentId(string $consignmentId, ?string $trackingCode = null): void
    {
        $deliveryData = $this->delivery_data ?? [];
        $deliveryData['consignment_id'] = $consignmentId;
        $deliveryData['tracking_code'] = $trackingCode ?? $consignmentId;
        $this->delivery_data = $deliveryData;
        $this->save();
    }

    /**
     * Get dynamic category-prefixed order number (used as tracing/order ID)
     */
    public function getOrderNumberAttribute()
    {
        $firstItem = $this->order_items()->first() ?: $this->orderItems()->first();
        if ($firstItem && $firstItem->product) {
            $product = $firstItem->product;
            $category = $product->category;
            if (!$category && $product->parent_product_id) {
                $parent = \App\Models\Product::find($product->parent_product_id);
                $category = $parent ? $parent->category : null;
            }
            $categoryName = $category ? $category->name : '';
        } else {
            $categoryName = '';
        }
        
        $cleanCategory = $categoryName ? preg_replace('/[^a-zA-Z0-9]/', '', $categoryName) : 'General';
        return $cleanCategory . '_' . $this->id;
    }
}
