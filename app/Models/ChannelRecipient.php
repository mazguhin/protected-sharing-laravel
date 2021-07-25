<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelRecipient extends Model
{
    protected $table = 'channel_recipient';
    protected $fillable = ['channel_id', 'recipient_id'];
}
