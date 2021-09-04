<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChannelRecipient
 * @package App\Models
 * @property Channel|null $channel_id
 * @property Recipient|null $recipient_id
 */
class ChannelRecipient extends Model
{
    use HasFactory;

    protected $table = 'channel_recipient';
    protected $fillable = ['channel_id', 'recipient_id'];
    public $timestamps = false;

    public function scopeRecipient($query, $recipientId)
    {
        return $query->where('recipient_id', $recipientId);
    }

    public function scopeChannel($query, $channelId)
    {
        return $query->where('channel_id', $channelId);
    }
}
