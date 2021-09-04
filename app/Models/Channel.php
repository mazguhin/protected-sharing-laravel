<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Channel
 * @package App\Models
 * @property string $title
 * @property string $name
 * @property bool $is_active
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @property Recipient[]|null $recipients
 */
class Channel extends Model
{
    use HasFactory;

    protected $table = 'channels';
    protected $fillable = ['title', 'name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function recipients()
    {
        return $this->belongsToMany(Recipient::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
