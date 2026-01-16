<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategicAction extends Model
{
    protected $fillable = [
        'kebun_id',
        'program_id',
        'year',
        'action_type',
        'dosis_n_kg_ha',
        'n_protas_percent',
        'application_frequency',
        'fertilizer_type',
        'technical_note',
        'coverage_target_percent',
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
        'year' => 'integer',
        'dosis_n_kg_ha' => 'decimal:2',
        'n_protas_percent' => 'decimal:2',
        'coverage_target_percent' => 'decimal:2',
        'avg_machine_age' => 'decimal:2',
        'kandas_risk' => 'boolean',
        'tp_normalization' => 'boolean',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class, 'kebun_id');
    }
}
