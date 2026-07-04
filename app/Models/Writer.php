<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $photo
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $bio
 * @property int|null $popularity_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer wherePopularityScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Writer whereUserId($value)
 * @mixin \Eloquent
 */
class Writer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'photo',
        'email',
        'phone',
        'address',
        'bio',
        'popularity_score',
    ];
}
