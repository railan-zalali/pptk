<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Garden extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kebun_name',
        'regional_id',
        'luas_total_ha',
        'kebun_type',
        'agro_climate_note',
        'location',
        'photo_path',
        'description',
        'established_at',
    ];

    protected $casts = [
        'established_at' => 'date',
        'luas_total_ha'  => 'decimal:2',
    ];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'regional_id');
    }

    public function strategicActions(): HasMany
    {
        return $this->hasMany(StrategicAction::class, 'kebun_id');
    }

    public function performanceTargets(): HasMany
    {
        return $this->hasMany(PerformanceTarget::class, 'kebun_id');
    }

    public function productionRealizations(): HasMany
    {
        return $this->hasMany(ProductionRealization::class, 'kebun_id');
    }

    public function afdelings(): HasMany
    {
        return $this->hasMany(Afdeling::class, 'kebun_id');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function insights(): HasMany
    {
        return $this->hasMany(Insight::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(GardenPhoto::class);
    }

    public function communityServices(): HasMany
    {
        return $this->hasMany(CommunityService::class);
    }
}
