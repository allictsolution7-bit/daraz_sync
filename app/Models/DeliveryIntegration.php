<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DeliveryIntegration extends Model
{
    protected $fillable = ['user_id', 'provider', 'credentials', 'is_active'];

    protected $casts = [
        'credentials' => 'array',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope query to filter integrations for a specific user ID or current logged-in user
     */
    public function scopeForUser($query, $userId = null)
    {
        $userId = $userId ?: Auth::id();
        return $query->where('user_id', $userId);
    }
}
