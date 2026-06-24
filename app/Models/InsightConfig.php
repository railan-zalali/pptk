<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class InsightConfig extends Model
{
    protected $fillable = [
        'insight_type',
        'rule_key',
        'rule_value',
        'description',
        'unit',
    ];

    protected $casts = [
        'rule_value' => 'decimal:4',
    ];

    /**
     * Ambil nilai config dari DB (dengan cache 60 menit).
     * Fallback ke nilai default jika config belum ada di DB.
     *
     * @param  string  $insightType
     * @param  string  $ruleKey
     * @param  float   $default
     * @return float
     */
    public static function getValue(string $insightType, string $ruleKey, float $default = 0): float
    {
        $cacheKey = "insight_config_{$insightType}_{$ruleKey}";

        return (float) Cache::remember($cacheKey, now()->addMinutes(60), function () use ($insightType, $ruleKey, $default) {
            $config = static::where('insight_type', $insightType)
                ->where('rule_key', $ruleKey)
                ->first();

            return $config ? $config->rule_value : $default;
        });
    }

    /**
     * Ambil semua config untuk satu insight_type (dengan cache).
     */
    public static function getAll(string $insightType): array
    {
        $cacheKey = "insight_config_all_{$insightType}";

        return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($insightType) {
            return static::where('insight_type', $insightType)
                ->pluck('rule_value', 'rule_key')
                ->toArray();
        });
    }

    /**
     * Hapus cache saat config diubah.
     */
    protected static function booted(): void
    {
        static::saved(function (InsightConfig $config) {
            Cache::forget("insight_config_{$config->insight_type}_{$config->rule_key}");
            Cache::forget("insight_config_all_{$config->insight_type}");
        });

        static::deleted(function (InsightConfig $config) {
            Cache::forget("insight_config_{$config->insight_type}_{$config->rule_key}");
            Cache::forget("insight_config_all_{$config->insight_type}");
        });
    }
}
