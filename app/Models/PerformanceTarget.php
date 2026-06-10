<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerformanceTarget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kebun_id',
        'year',
        'target_protas_min',
        'target_protas_max',
        'note',
    ];

    protected $casts = [
        'year'             => 'integer',
        'target_protas_min' => 'decimal:2',
        'target_protas_max' => 'decimal:2',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class, 'kebun_id');
    }
}
