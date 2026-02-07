<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Block extends Model
{
    protected $fillable = [
        'afdeling_id',
        'name',
        'code',
        'area_ha',
        'population',
        'plant_type',
        'planting_year',
        'initial_class',
        'topography',
    ];

    protected $casts = [
        'planting_year' => 'integer',
    ];

    public function afdeling(): BelongsTo
    {
        return $this->belongsTo(Afdeling::class);
    }

    public function strategicActions(): HasMany
    {
        return $this->hasMany(StrategicAction::class);
    }
}
