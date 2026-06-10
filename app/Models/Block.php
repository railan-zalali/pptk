<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use HasFactory, SoftDeletes;

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
        'area_ha'       => 'decimal:2',
        'population'    => 'integer',
    ];

    public function afdeling(): BelongsTo
    {
        return $this->belongsTo(Afdeling::class);
    }
}
