<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UpdateHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_id',
        'applied_by_user_id',
        'version',
        'sequence',
        'status',
        'notes',
        'manifest',
        'details',
    ];

    protected $casts = [
        'manifest' => 'array',
        'details' => 'array',
    ];

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function appliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applied_by_user_id');
    }
}
