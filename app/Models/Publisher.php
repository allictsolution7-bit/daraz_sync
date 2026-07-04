<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $logo
 * @property string|null $website
 * @property string|null $detail
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Publisher whereWebsite($value)
 * @mixin \Eloquent
 */
class Publisher extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'logo',
        'website',
        'detail',
    ];
}
