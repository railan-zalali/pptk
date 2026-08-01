<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRealization extends Model
{
    protected $fillable = [
        'kebun_id',
        'month',
        'year',
        'active_picking_area_ha',
        'wet_production_kg',
        'dry_production_kg',
        'capacity_per_ha',
        'avg_capacity',
        'estimated_production',
        'quality_score',
        'assumption_note',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'active_picking_area_ha' => 'decimal:2',
        'wet_production_kg' => 'decimal:2',
        'dry_production_kg' => 'decimal:2',
        'capacity_per_ha' => 'decimal:2',
        'avg_capacity' => 'decimal:2',
        'estimated_production' => 'decimal:2',
        'quality_score' => 'decimal:2',
    ];

    /**
     * Get Protas Basah (Kg/Ha)
     */
    public function getProductivityWetAttribute()
    {
        if ($this->active_picking_area_ha > 0) {
            return $this->wet_production_kg / $this->active_picking_area_ha;
        }
        return 0;
    }

    /**
     * Get Protas Kering (Kg/Ha).
     * Menggunakan kolom dry_production_kg jika ada (data aktual).
     * Fallback ke estimasi 22% dari produksi basah jika kolom null.
     */
    public function getProductivityDryAttribute()
    {
        if ($this->active_picking_area_ha > 0) {
            if ($this->dry_production_kg !== null && $this->dry_production_kg > 0) {
                return $this->dry_production_kg / $this->active_picking_area_ha;
            }
            // Fallback estimasi konversi teh 22% (hanya jika data kering belum ada)
            return $this->productivity_wet * 0.22;
        }
        return 0;
    }

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class, 'kebun_id');
    }
}
