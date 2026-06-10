<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategicAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'kebun_id',
        'program_id',
        'year',
        'realization_date',
        'action_type',
        'status',
        'dosis_n_kg_ha',
        'realized_dosis_n_kg_ha',
        'n_protas_percent',
        'application_frequency',
        'fertilizer_type',
        'technical_note',
        'coverage_target_percent',
        'realization_percent',
        'application_interval',
        'rotation_per_year',
        'method',
        'focus_area',
        'picking_system',
        'cushion_consistency',
        'kandas_risk',
        'total_machine',
        'avg_machine_age',
        'renewal_status',
        'opt_status',
        'tp_normalization',
        'treatment_note',
        'note',
    ];

    protected $casts = [
        'year'                    => 'integer',
        'realization_date'        => 'date',
        'dosis_n_kg_ha'           => 'decimal:2',
        'realized_dosis_n_kg_ha'  => 'decimal:2',
        'n_protas_percent'        => 'decimal:2',
        'coverage_target_percent' => 'decimal:2',
        'realization_percent'     => 'decimal:2',
        'avg_machine_age'         => 'decimal:2',
        'kandas_risk'             => 'boolean',
        'tp_normalization'        => 'boolean',
    ];

    /**
     * Gap antara target dan realisasi cakupan dalam persen.
     * Bernilai positif jika belum tercapai, negatif jika sudah terlampaui.
     */
    public function getCoverageGapAttribute(): float
    {
        $target = (float) ($this->attributes['coverage_target_percent'] ?? 0);
        $actual = (float) ($this->attributes['realization_percent'] ?? 0);

        return $target - $actual;
    }

    /**
     * Apakah status aksi ini sudah selesai.
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->attributes['status'] === 'completed';
    }

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class, 'kebun_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
