<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionData extends Model
{
    protected $fillable = [
        'garden_id',
        'record_date',
        'production',
        'productivity',
        'productivity_kg_ha_year',
        'rkap_percentage',
        'wet_production_kg',
        'quality_score',
        'weather_condition',
        'temperature_avg',
        'rainfall_mm',
        'humidity_percent',
        'soil_moisture_percent',
        'pest_incidence',
        'disease_incidence',
        'fertilizer_used',
        'labor_hours',
        'notes',
        'month',
        'year',
    ];

    protected $casts = [
        'record_date' => 'date',
        'production' => 'decimal:2',
        'productivity' => 'decimal:2',
        'productivity_kg_ha_year' => 'decimal:2',
        'rkap_percentage' => 'decimal:2',
        'wet_production_kg' => 'decimal:2',
        'quality_score' => 'decimal:1',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class);
    }
}
