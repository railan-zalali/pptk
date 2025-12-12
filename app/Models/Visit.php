<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    protected $fillable = [
        'garden_id',
        'title',
        'visit_date',
        'duration',
        'participants_count',
        'participants_list',
        'description',
        'objectives',
        'findings',
        'recommendations',
        'rating',
        'visitor_name',
        'purpose',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class);
    }
}
