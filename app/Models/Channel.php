<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channels';
    protected $fillable = ['title', 'name', 'is_active'];

    public function recipients()
    {
        return $this->belongsToMany(Recipient::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
