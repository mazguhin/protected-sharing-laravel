<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Recipient
 * @package App\Models
 * @property string $title
 * @property string $name
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Recipient extends Model
{
    use HasFactory;

    protected $table = 'recipients';
    protected $fillable = ['name', 'is_active'];
    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function channels()
    {
        return $this->belongsToMany(Channel::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
