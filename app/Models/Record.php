<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    public const DEADLINE_MINUTES_DEFAULT = 5;

    protected $table = 'records';
    protected $fillable = ['recipient_id', 'channel_id', 'deadline_at', 'author_ip'];

    protected $casts = [
        'deadline_at' => 'datetime',
    ];

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}
