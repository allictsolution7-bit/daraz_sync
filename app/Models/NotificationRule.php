<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationRule extends Model
{
    protected $fillable = [
        'event',
        'name',
        'is_enabled',
        'channels',
        'recipient_type',
        'conditions',
        'email_template_key',
        'sms_template_key',
        'delay_minutes',
        'priority',
        'additional_config',
        'sort_order'
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'channels' => 'array',
        'conditions' => 'array',
        'additional_config' => 'array',
        'delay_minutes' => 'integer',
        'priority' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Check if this notification rule is applicable
     */
    public function isApplicable(): bool
    {
        return $this->is_enabled;
    }

    /**
     * Get rules by event
     */
    public static function getByEvent(string $event)
    {
        return self::where('event', $event)
            ->where('is_enabled', true)
            ->orderBy('priority', 'desc')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Check if a channel is enabled for this rule
     */
    public function hasChannel(string $channel): bool
    {
        return in_array($channel, $this->channels ?? []);
    }
}

