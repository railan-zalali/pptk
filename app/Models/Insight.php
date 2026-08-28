<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Insight extends Model
{
    protected $fillable = [
        'title',
        'description',
        'garden_id',
        'year',
        'insight_type',
        'message',
        'alert_level',
        'recommendations',
        'generated_at',
    ];

    protected $casts = [
        'year'         => 'integer',
        'recommendations' => 'array',
        'generated_at' => 'datetime',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class);
    }
}
