<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Region extends Model
{
    protected $fillable = [
        'regional_code',
        'regional_name',
        'province',
        'coordinates',
        'photo_path',
    ];

    public function gardens(): HasMany
    {
        return $this->hasMany(Garden::class, 'regional_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(RegionPhoto::class);
    }
}
