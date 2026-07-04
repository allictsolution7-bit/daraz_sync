<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'code',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'is_active',
        'is_primary',
        'opening_hours',
        'manager_id',
        'image',
        'features',
        'settings',
        'sort_order',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
        'opening_hours' => 'array',
        'features' => 'array',
        'settings' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'sort_order' => 'integer',
    ];

    /**
     * Get the manager of this store
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Check if store is currently open
     */
    public function isOpenNow(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = strtolower(now()->format('l')); // 'saturday', 'sunday', etc.
        $hours = $this->opening_hours[$today] ?? null;

        if (!$hours || $hours === 'Closed') {
            return false;
        }

        [$open, $close] = explode('-', $hours);
        $now = now()->format('H:i');

        return $now >= $open && $now <= $close;
    }

    /**
     * Get active stores
     */
    public static function getActive()
    {
        return self::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get primary store
     */
    public static function getPrimary()
    {
        return self::where('is_primary', true)->first();
    }

    /**
     * Calculate distance to given coordinates (in kilometers)
     */
    public function distanceTo(float $latitude, float $longitude): ?float
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        // Haversine formula
        $earthRadius = 6371; // kilometers

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Find nearest store to given coordinates
     */
    public static function findNearest(float $latitude, float $longitude)
    {
        $stores = self::where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        if ($stores->isEmpty()) {
            return null;
        }

        $nearest = null;
        $minDistance = PHP_FLOAT_MAX;

        foreach ($stores as $store) {
            $distance = $store->distanceTo($latitude, $longitude);
            if ($distance !== null && $distance < $minDistance) {
                $minDistance = $distance;
                $nearest = $store;
            }
        }

        return $nearest;
    }

    /**
     * Check if store has a specific feature
     */
    public function hasFeature(string $feature): bool
    {
        return in_array($feature, $this->features ?? []);
    }
}

