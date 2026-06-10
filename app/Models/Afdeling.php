<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Afdeling extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kebun_id',   // FIXED: sebelumnya salah 'garden_id'
        'name',
        'total_area_ha',
        'tm_area_ha',
        'manager_name',
    ];

    protected $casts = [
        'total_area_ha' => 'decimal:2',
        'tm_area_ha'    => 'decimal:2',
    ];

    public function garden(): BelongsTo
    {
        return $this->belongsTo(Garden::class, 'kebun_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(Block::class);
    }
}
