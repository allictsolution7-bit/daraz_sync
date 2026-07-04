<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $email
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $device
 * @property string|null $browser
 * @property string|null $platform
 * @property string|null $country
 * @property string|null $city
 * @property string|null $region
 * @property bool $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Subscription whereUserAgent($value)
 * @mixin \Eloquent
 */
class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'device',
        'browser',
        'platform',
        'country',
        'city',
        'region',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}