<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $fillable = [
        'name',
        'province',
        'coordinates',
    ];

    public function gardens(): HasMany
    {
        return $this->hasMany(Garden::class);
    }
}
