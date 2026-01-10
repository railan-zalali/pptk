<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Afdeling extends Model
{
    protected $fillable = [
        'garden_id',
        'name',
        'total_area_ha',
        'tm_area_ha',
        'manager_name',
    ];

    protected $casts = [
        'total_area_ha' => 'decimal:2',
        'tm_area_ha' => 'decimal:2',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class);
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }

    public function productionRealizations(): HasMany
    {
        return $this->hasMany(ProductionRealization::class);
    }

    public function performanceTargets(): HasMany
    {
        return $this->hasMany(PerformanceTarget::class);
    }
}
