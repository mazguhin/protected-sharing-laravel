<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelRecipient extends Model
{
    protected $table = 'channel_recipient';
    protected $fillable = ['channel_id', 'recipient_id'];

    public function scopeRecipient($query, $recipientId)
    {
        return $query->where('recipient_id', $recipientId);
    }

    public function scopeChannel($query, $channelId)
    {
        return $query->where('channel_id', $channelId);
    }
}
