<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $class
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Social whereUrl($value)
 * @mixin \Eloquent
 */
class Social extends Model
{
    use HasFactory;
    protected $guarded =[];
}
