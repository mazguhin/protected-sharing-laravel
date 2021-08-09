<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

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
