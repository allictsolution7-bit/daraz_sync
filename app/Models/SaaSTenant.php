<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaaSTenant extends Model
{
    use HasFactory;

    protected $connection = 'central';

    protected $table = 'saas_tenants';

    protected $fillable = [
        'name',
        'subdomain',
        'custom_domain',
        'db_name',
        'is_active',
        'free_promotion',
        'commission_rate',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'free_promotion' => 'boolean',
        'commission_rate' => 'float',
    ];
}
