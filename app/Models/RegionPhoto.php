<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegionPhoto extends Model
{
    protected $fillable = [
        'region_id',
        'path',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}

