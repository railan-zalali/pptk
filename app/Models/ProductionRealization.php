<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionRealization extends Model
{
    use HasFactory;

    protected $fillable = [
        'kebun_id',
        'month',
        'year',
        'active_picking_area_ha',
        'wet_production_kg',
        'capacity_per_ha',
        'avg_capacity',
        'estimated_production',
        'quality_score',
        'assumption_note',
    ];

    protected $casts = [
        'year'                   => 'integer',
        'month'                  => 'integer',
        'active_picking_area_ha' => 'decimal:2',
        'wet_production_kg'      => 'decimal:2',
        'capacity_per_ha'        => 'decimal:2',
        'avg_capacity'           => 'decimal:2',
        'estimated_production'   => 'decimal:2',
        'quality_score'          => 'decimal:2',
    ];

    /**
     * Nilai yang ingin ditampilkan via $model->month_name.
     */
    protected $appends = ['productivity_wet', 'productivity_dry', 'month_name', 'rkap_percentage'];

    /**
     * Protas Basah (Kg/Ha): produksi basah / luas petik aktif.
     */
    public function getProductivityWetAttribute(): float
    {
        $area = (float) $this->attributes['active_picking_area_ha'];
        $prod = (float) $this->attributes['wet_production_kg'];

        return $area > 0 ? $prod / $area : 0.0;
    }

    /**
     * Protas Kering (Kg/Ha): menggunakan rasio konversi standar teh 22%.
     */
    public function getProductivityDryAttribute(): float
    {
        return $this->productivity_wet * 0.22;
    }

    /**
     * Persentase realisasi terhadap RKAP / estimasi produksi.
     */
    public function getRkapPercentageAttribute(): float
    {
        $estimated = (float) ($this->attributes['estimated_production'] ?? 0);
        $actual    = (float) ($this->attributes['wet_production_kg'] ?? 0);

        return $estimated > 0 ? ($actual / $estimated) * 100 : 0.0;
    }

    /**
     * Nama bulan dalam Bahasa Indonesia.
     */
    public function getMonthNameAttribute(): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April',   5 => 'Mei',       6 => 'Juni',
            7 => 'Juli',    8 => 'Agustus',   9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $months[(int) $this->attributes['month']] ?? '-';
    }

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class, 'kebun_id');
    }
}
