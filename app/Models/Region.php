<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'regional_code',
        'regional_name',
        'province',
        'coordinates',
        'photo_path',
    ];

    public function gardens(): HasMany
    {
        return $this->hasMany(Garden::class, 'regional_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(RegionPhoto::class);
    }

    /**
     * Semua ProductionRealization melalui Gardens di region ini.
     */
    public function productionRealizations(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            ProductionRealization::class,
            Garden::class,
            'regional_id', // FK di gardens
            'kebun_id',    // FK di production_realizations
        );
    }

    /**
     * Semua StrategicAction melalui Gardens di region ini.
     */
    public function strategicActions(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            StrategicAction::class,
            Garden::class,
            'regional_id',
            'kebun_id',
        );
    }
}
