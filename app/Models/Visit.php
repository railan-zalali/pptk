<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'garden_id',
        'title',
        'visit_date',
        'duration',
        'participants_count',
        'participants_list',
        'visitor_name',
        'description',
        'objectives',
        'findings',
        'recommendations',
        'purpose',
        'rating',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'duration'   => 'integer',
        'rating'     => 'integer',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(VisitPhoto::class);
    }
}
