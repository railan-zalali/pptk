<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommunityService extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'garden_id',
        'activity_name',
        'team_name',
        'total_budget',
        'remaining_budget',
        'year',
        'status',
        'start_date',
        'end_date',
        'description',
        'location',
    ];

    protected $casts = [
        'total_budget'     => 'decimal:2',
        'remaining_budget' => 'decimal:2',
        'year'             => 'integer',
        'start_date'       => 'date',
        'end_date'         => 'date',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class);
    }

    /**
     * Serapan anggaran dalam persen.
     */
    public function getBudgetAbsorptionAttribute(): float
    {
        $total = (float) $this->attributes['total_budget'];
        $remaining = (float) $this->attributes['remaining_budget'];

        return $total > 0 ? (($total - $remaining) / $total) * 100 : 0.0;
    }
}
