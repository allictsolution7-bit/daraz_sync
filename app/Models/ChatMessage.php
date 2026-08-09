<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean'
    ];

    /**
     * Get the chatroom this message belongs to.
     */
    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'chat_room_id');
    }

    /**
     * Get the sender of the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
