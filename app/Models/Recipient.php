<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $table = 'recipients';
    protected $fillable = ['name', 'is_active'];

    public function channels()
    {
        return $this->belongsToMany(Channel::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
