<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategicAction extends Model
{
    protected $fillable = [
        'block_id',
        'period',
        'action_type',
        'target_volume',
        'realization_volume',
        'nitrogen_content',
        'notes',
    ];

    protected $casts = [
        'period' => 'date',
        'target_volume' => 'decimal:2',
        'realization_volume' => 'decimal:2',
        'nitrogen_content' => 'decimal:2',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }
}
