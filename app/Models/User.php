<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $address
 * @property string|null $upazila
 * @property string|null $city
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $otp_code
 * @property string|null $otp_expires_at
 * @property int $otp_verified
 * @property int $otp_resend_count
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpResendCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereOtpVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpazila($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'upazila',
        'city',
        'phone',
        'email',
        'password',
        'profile_photo_path',
        'otp_code',
        'otp_expires_at',
        'otp_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(order::class);
    }

    // ==========================================
    // VENDOR RELATIONSHIPS & METHODS
    // ==========================================

    /**
     * Check if user is a vendor
     */
    public function isVendor(): bool
    {
        return $this->hasRole('vendor');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super_admin') || $this->hasRole('super admin');
    }

    /**
     * Get vendor settings
     */
    public function vendorSettings()
    {
        return $this->hasOne(VendorSetting::class, 'vendor_id');
    }

    /**
     * Get products uploaded by vendor
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    /**
     * Get vendor's order items
     */
    public function vendorOrderItems()
    {
        return $this->hasMany(order_item::class, 'vendor_id');
    }

    /**
     * Get orders containing vendor's products (via order items)
     */
    public function vendorOrders()
    {
        return $this->hasManyThrough(
            order::class,
            order_item::class,
            'vendor_id',  // Foreign key on order_items
            'id',         // Foreign key on orders
            'id',         // Local key on users
            'order_id'    // Local key on order_items
        );
    }

    /**
     * Get vendor balance ledger
     */
    public function balanceLedger()
    {
        return $this->hasMany(VendorBalanceLedger::class, 'vendor_id');
    }

    /**
     * Get vendor's withdrawal requests
     */
    public function vendorWithdrawals()
    {
        return $this->hasMany(VendorWithdrawal::class, 'vendor_id');
    }

    /**
     * Get vendor withdrawals
     */
    public function withdrawals()
    {
        return $this->hasMany(VendorBalanceLedger::class, 'vendor_id');
    }
}
