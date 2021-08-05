<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'records';
    protected $fillable = ['recipient_id', 'channel_id', 'deadline_at'];

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
