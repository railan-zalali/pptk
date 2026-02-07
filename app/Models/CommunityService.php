<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityService extends Model
{
    protected $fillable = [
        'activity_name',
        'team_name',
        'total_budget',
        'remaining_budget',
        'year',
        'description',
        'location',
    ];

    protected $casts = [
        'total_budget' => 'decimal:2',
        'remaining_budget' => 'decimal:2',
        'year' => 'integer',
    ];
}
