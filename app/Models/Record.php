<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Record
 * @package App\Models
 * @property int $recipient_id
 * @property int $channel_id
 * @property string $author_ip
 * @property \DateTime $deadline_at
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property Recipient|null $recipient
 * @property Channel|null $channel
 */
class Record extends Model
{
    public const DEADLINE_MINUTES_DEFAULT = 5;

    protected $table = 'records';
    protected $fillable = ['recipient_id', 'channel_id', 'deadline_at', 'author_ip'];

    protected $casts = [
        'deadline_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', 0);
    }

    public function scopeIdentifier($query, $identifier)
    {
        return $query->where('identifier', $identifier);
    }
}
