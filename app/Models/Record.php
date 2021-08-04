<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'records';
    protected $fillable = ['recipient_id', 'deadline_at'];

    protected $casts = [
        'deadline_at' => 'datetime',
    ];

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }
}
