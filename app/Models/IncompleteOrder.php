<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $address
 * @property string $phone
 * @property string|null $upazila
 * @property string|null $city
 * @property string|null $message
 * @property string|null $source
 * @property string|null $ip_address
 * @property array<array-key, mixed>|null $product_details
 * @property string|null $total
 * @property string|null $payment_method
 * @property string|null $shipping_method
 * @property string|null $shipping_cost
 * @property string $status
 * @property string|null $admin_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereAdminNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereProductDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereShippingMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereUpazila($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|IncompleteOrder whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class IncompleteOrder extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'upazila',
        'city',
        'message',
        'source',
        'ip_address',
        'product_details',
        'total',
        'payment_method',
        'shipping_method',
        'shipping_cost',
        'status',
        'admin_note'
    ];

    protected $casts = [
        'product_details' => 'array',
    ];

    /**
     * Normalize phone number to last 11 digits (Bangladesh format)
     * This ensures consistent matching regardless of country code prefix
     *
     * @param string $phone
     * @return string
     */
    public static function normalizePhone(string $phone): string
    {
        // Remove all non-digit characters
        $phone = preg_replace('/[^\d]/', '', $phone);

        // Get the last 11 digits (Bangladesh phone numbers are 11 digits)
        if (strlen($phone) > 11) {
            $phone = substr($phone, -11);
        }

        return $phone;
    }

    /**
     * Delete incomplete orders by phone number with proper normalization
     * This is the preferred method to delete incomplete orders after successful order placement
     *
     * @param string $phone
     * @return int Number of deleted records
     */
    public static function deleteByPhone(string $phone): int
    {
        try {
            $normalizedPhone = self::normalizePhone($phone);
            return self::where('phone', $normalizedPhone)->delete();
        } catch (\Exception $e) {
            \Log::error("Failed to delete incomplete order for phone: " . $e->getMessage());
            return 0;
        }
    }
}
