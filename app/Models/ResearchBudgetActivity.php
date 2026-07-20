<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchBudgetActivity extends Model
{
    protected $fillable = ['name', 'sort_order'];

    protected $casts = ['sort_order' => 'integer'];
}
