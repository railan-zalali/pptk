<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRealization extends Model
{
    protected $fillable = [
        'afdeling_id',
        'date',
        'harvested_area_ha',
        'wet_yield_kg',
        'dry_yield_kg',
        'manpower_count',
        'effective_days',
    ];

    protected $casts = [
        'date' => 'date',
        'harvested_area_ha' => 'decimal:2',
        'wet_yield_kg' => 'decimal:2',
        'dry_yield_kg' => 'decimal:2',
        'manpower_count' => 'integer',
        'effective_days' => 'integer',
    ];

    public function afdeling(): BelongsTo
    {
        return $this->belongsTo(Afdeling::class);
    }
}
