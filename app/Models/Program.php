<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_name',
        'year',
        'program_type',
        'status',
    ];

    protected $casts = [
        'year' => 'integer',
        'status' => 'boolean',
    ];
}
