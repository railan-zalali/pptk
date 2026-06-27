<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchBudgetBalance extends Model
{
    protected $fillable = [
        'activity_name',
        'year',
        'opening_balance',
    ];

    protected $casts = [
        'year' => 'integer',
        'opening_balance' => 'decimal:2',
    ];
}
