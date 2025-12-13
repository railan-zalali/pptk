<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GardenPhoto extends Model
{
    protected $fillable = [
        'garden_id',
        'path',
        'title',
        'description',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class);
    }
}
