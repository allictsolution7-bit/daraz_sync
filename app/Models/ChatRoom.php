<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatRoom extends Model
{
    protected $fillable = [
        'customer_id',
        'vendor_id'
    ];

    /**
     * Get the customer participant of the chatroom.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the vendor/seller participant of the chatroom.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    /**
     * Get the messages in the chatroom.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_room_id')->orderBy('created_at', 'asc');
    }

    /**
     * Get the last message in the chatroom.
     */
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class, 'chat_room_id')->latestOfMany();
    }
}
