<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Garden extends Model
{
    protected $fillable = [
        'name',
        'location',
        'region_id',
        'area_hectares',
        'description',
        'established_at',
        'address',
        'area',
        'elevation',
        'rainfall',
        'tea_variety',
        'garden_type',
        'coordinates',
        'latitude',
        'longitude',
        'soil_ph',
        'soil_type',
        'drainage',
        'status',
    ];

    protected $casts = [
        'established_at' => 'date',
        'area_hectares' => 'decimal:2',
        'area' => 'decimal:2',
        'latitude' => 'decimal:6',
        'longitude' => 'decimal:6',
        'soil_ph' => 'decimal:1',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function productionData(): HasMany
    {
        return $this->hasMany(ProductionData::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function insights(): HasMany
    {
        return $this->hasMany(Insight::class);
    }
}
