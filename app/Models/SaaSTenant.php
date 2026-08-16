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
        'db_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
