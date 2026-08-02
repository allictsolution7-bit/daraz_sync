<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'address',
        'city',
        'upazila',
        'latitude',
        'longitude',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
