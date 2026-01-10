<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceTarget extends Model
{
    protected $fillable = [
        'year',
        'afdeling_id',
        'target_protas_kg_ha',
        'target_yield_kg',
    ];

    protected $casts = [
        'year' => 'integer',
        'target_protas_kg_ha' => 'decimal:2',
        'target_yield_kg' => 'decimal:2',
    ];

    public function afdeling(): BelongsTo
    {
        return $this->belongsTo(Afdeling::class);
    }
}
