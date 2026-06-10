<?php

namespace App\Services;

use App\Models\Garden;
use App\Models\ProductionRealization;
use App\Models\StrategicAction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardStatisticsService
{
    private const CACHE_TTL_SECONDS = 300; // 5 menit

    /**
     * Kunci cache dengan format yang konsisten.
     */
    private function cacheKey(string $metric, int $year, ?int $gardenId = null): string
    {
        $suffix = $gardenId ? "_g{$gardenId}" : '_all';
        return "dashboard:{$metric}:y{$year}{$suffix}";
    }

    /**
     * Production YTD (Kg) — dengan caching.
     */
    public function getProductionYtd(int $year, ?int $gardenId = null): float
    {
        return Cache::remember(
            $this->cacheKey('prod_ytd', $year, $gardenId),
            self::CACHE_TTL_SECONDS,
            function () use ($year, $gardenId) {
                return (float) ProductionRealization::where('year', $year)
                    ->when($gardenId, fn ($q) => $q->where('kebun_id', $gardenId))
                    ->sum('wet_production_kg');
            }
        );
    }

    /**
     * Target YTD yang diprorata sesuai progres bulan berjalan.
     */
    public function getTargetYtdProrated(int $year, ?int $gardenId = null): float
    {
        return Cache::remember(
            $this->cacheKey('target_ytd', $year, $gardenId),
            self::CACHE_TTL_SECONDS,
            function () use ($year, $gardenId) {
                $gardens = Garden::with(['performanceTargets' => fn ($q) => $q->where('year', $year)])
                    ->when($gardenId, fn ($q) => $q->where('id', $gardenId))
                    ->get();

                $gardenIds = $gardens->pluck('id');
                $gardenAreas = ProductionRealization::whereIn('kebun_id', $gardenIds)
                    ->where('year', $year)
                    ->selectRaw('kebun_id, AVG(active_picking_area_ha) as avg_area')
                    ->groupBy('kebun_id')
                    ->pluck('avg_area', 'kebun_id');

                $totalTargetYearly = $gardens->sum(function ($garden) use ($gardenAreas) {
                    $targetProtas = $garden->performanceTargets->first()?->target_protas_min ?? 0;
                    $avgArea = $gardenAreas[$garden->id] ?? $garden->luas_total_ha;

                    return (float) $targetProtas * (float) $avgArea;
                });

                $monthProgress = now()->month / 12;

                return $totalTargetYearly * $monthProgress;
            }
        );
    }

    /**
     * Rata-rata Produktivitas (Kg/Ha).
     */
    public function getAvgProductivity(int $year, ?int $gardenId = null): float
    {
        return Cache::remember(
            $this->cacheKey('avg_prod', $year, $gardenId),
            self::CACHE_TTL_SECONDS,
            function () use ($year, $gardenId) {
                $query = ProductionRealization::where('year', $year)
                    ->when($gardenId, fn ($q) => $q->where('kebun_id', $gardenId));

                $totalProduction = (clone $query)->sum('wet_production_kg');

                if ($gardenId) {
                    $avgTotalArea = (clone $query)->avg('active_picking_area_ha') ?? 0;
                } else {
                    $avgTotalArea = (clone $query)
                        ->selectRaw('month, SUM(active_picking_area_ha) as total_area')
                        ->groupBy('month')
                        ->get()
                        ->avg('total_area') ?? 0;
                }

                return $avgTotalArea > 0 ? (float) $totalProduction / (float) $avgTotalArea : 0.0;
            }
        );
    }

    /**
     * Rata-rata Kapasitas Pemetikan.
     */
    public function getPickingCapacity(int $year, ?int $gardenId = null): float
    {
        return Cache::remember(
            $this->cacheKey('picking_cap', $year, $gardenId),
            self::CACHE_TTL_SECONDS,
            fn () => (float) ProductionRealization::where('year', $year)
                ->when($gardenId, fn ($q) => $q->where('kebun_id', $gardenId))
                ->avg('avg_capacity') ?? 0.0
        );
    }

    /**
     * Rata-rata Skor Mutu Pucuk.
     */
    public function getQualityScore(int $year, ?int $gardenId = null): float
    {
        return Cache::remember(
            $this->cacheKey('quality', $year, $gardenId),
            self::CACHE_TTL_SECONDS,
            fn () => (float) ProductionRealization::where('year', $year)
                ->whereNotNull('quality_score')
                ->when($gardenId, fn ($q) => $q->where('kebun_id', $gardenId))
                ->avg('quality_score') ?? 0.0
        );
    }

    /**
     * Progress Aksi Strategis berdasarkan type.
     *
     * @return array{target_area: float, realized_area: float, progress_percent: float, target_value: float, realized_value: float}
     */
    public function getStrategicProgress(string $type, int $year, ?int $gardenId = null): array
    {
        return Cache::remember(
            $this->cacheKey("strategic_{$type}", $year, $gardenId),
            self::CACHE_TTL_SECONDS,
            function () use ($type, $year, $gardenId) {
                $actions = StrategicAction::with('garden')
                    ->where('year', $year)
                    ->where('action_type', $type)
                    ->when($gardenId, fn ($q) => $q->where('kebun_id', $gardenId))
                    ->get();

                $totalTargetArea   = 0.0;
                $totalRealizedArea = 0.0;

                foreach ($actions as $action) {
                    $gardenArea = (float) ($action->garden?->luas_total_ha ?? 0);

                    $targetArea = $gardenArea * ((float) ($action->coverage_target_percent ?? 0) / 100);
                    $totalTargetArea += $targetArea;

                    $realizedArea = $gardenArea * ((float) ($action->realization_percent ?? 0) / 100);
                    $totalRealizedArea += $realizedArea;
                }

                $progressPercent = $totalTargetArea > 0
                    ? ($totalRealizedArea / $totalTargetArea) * 100
                    : 0.0;

                return [
                    'target_area'    => $totalTargetArea,
                    'realized_area'  => $totalRealizedArea,
                    'progress_percent' => $progressPercent,
                    'target_value'   => (float) ($actions->avg('coverage_target_percent') ?? 0),
                    'realized_value' => (float) ($actions->avg('realization_percent') ?? 0),
                ];
            }
        );
    }

    /**
     * Data pemupukan akar (fertilizer_root) agregat.
     *
     * @return array{avg_dosis: float, avg_realized_dosis: float, avg_n_protas_percent: float}
     */
    public function getFertilizerRootData(int $year, ?int $gardenId = null): array
    {
        return Cache::remember(
            $this->cacheKey('fert_root', $year, $gardenId),
            self::CACHE_TTL_SECONDS,
            function () use ($year, $gardenId) {
                $actions = StrategicAction::where('year', $year)
                    ->where('action_type', 'fertilizer_root')
                    ->when($gardenId, fn ($q) => $q->where('kebun_id', $gardenId))
                    ->get();

                return [
                    'avg_dosis'           => (float) ($actions->avg('dosis_n_kg_ha') ?? 0),
                    'avg_realized_dosis'  => (float) ($actions->avg('realized_dosis_n_kg_ha') ?? 0),
                    'avg_n_protas_percent' => (float) ($actions->avg('n_protas_percent') ?? 0),
                ];
            }
        );
    }

    /**
     * Data produksi bulanan untuk chart.
     *
     * @return Collection<int, float>  key=bulan, value=total kg
     */
    public function getMonthlyProduction(int $year, ?int $gardenId = null): Collection
    {
        return Cache::remember(
            $this->cacheKey('monthly_prod', $year, $gardenId),
            self::CACHE_TTL_SECONDS,
            fn () => ProductionRealization::selectRaw('month, SUM(wet_production_kg) as total')
                ->where('year', $year)
                ->when($gardenId, fn ($q) => $q->where('kebun_id', $gardenId))
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
        );
    }

    /**
     * Data produktivitas bulanan untuk chart.
     *
     * @return Collection<int, float>  key=bulan, value=Kg/Ha
     */
    public function getMonthlyProductivity(int $year, ?int $gardenId = null): Collection
    {
        return Cache::remember(
            $this->cacheKey('monthly_prd_rate', $year, $gardenId),
            self::CACHE_TTL_SECONDS,
            fn () => ProductionRealization::selectRaw('month, SUM(wet_production_kg) as total_prod, SUM(active_picking_area_ha) as total_area')
                ->where('year', $year)
                ->when($gardenId, fn ($q) => $q->where('kebun_id', $gardenId))
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->mapWithKeys(fn ($item) => [
                    $item->month => $item->total_area > 0
                        ? (float) $item->total_prod / (float) $item->total_area
                        : 0.0,
                ])
        );
    }

    /**
     * Detail per kebun dengan kalkulasi target dan achievement.
     */
    public function getGardenDetails(int $year): Collection
    {
        return Cache::remember(
            $this->cacheKey('garden_details', $year),
            self::CACHE_TTL_SECONDS,
            function () use ($year) {
                $gardens = Garden::with([
                    'region',
                    'performanceTargets' => fn ($q) => $q->where('year', $year),
                ])->get();

                // Satu query untuk semua produksi — hindari N+1
                $productionData = ProductionRealization::where('year', $year)
                    ->selectRaw('kebun_id, SUM(wet_production_kg) as total_prod, AVG(active_picking_area_ha) as avg_area')
                    ->groupBy('kebun_id')
                    ->get()
                    ->keyBy('kebun_id');

                $monthProgress = now()->month / 12;

                return $gardens->map(function ($garden) use ($productionData, $monthProgress) {
                    $prod        = $productionData[$garden->id] ?? null;
                    $realization = $prod ? (float) $prod->total_prod : 0.0;
                    $avgArea     = $prod ? (float) $prod->avg_area : (float) $garden->luas_total_ha;

                    $protas       = $avgArea > 0 ? $realization / $avgArea : 0.0;
                    $target       = (float) ($garden->performanceTargets->first()?->target_protas_min ?? 0);
                    $targetYtd    = $target * $monthProgress;
                    $achievement  = $targetYtd > 0 ? ($protas / $targetYtd) * 100 : 0.0;

                    return [
                        'id'                    => $garden->id,
                        'name'                  => $garden->kebun_name,
                        'region'                => $garden->region?->regional_name ?? 'N/A',
                        'regional_id'           => $garden->region?->id,
                        'tm_area'               => $avgArea,
                        'production_realization' => $realization,
                        'protas_achievement'    => $protas,
                        'target_protas'         => $target,
                        'achievement_percent'   => $achievement,
                    ];
                })->sortByDesc('protas_achievement')->values();
            }
        );
    }

    /**
     * Perbandingan antar wilayah berdasarkan rata-rata protas.
     */
    public function getRegionalComparison(Collection $gardenDetails): Collection
    {
        return $gardenDetails
            ->groupBy('region')
            ->map(fn ($gardens, $region) => [
                'region'        => $region,
                'avg_protas'    => $gardens->avg('protas_achievement'),
                'garden_count'  => $gardens->count(),
            ])
            ->sortByDesc('avg_protas')
            ->values();
    }

    /**
     * Invalidasi semua cache dashboard (gunakan saat data baru disimpan).
     */
    public function invalidateCache(int $year): void
    {
        Cache::flush(); // Sederhana — flush semua; bisa diganti dengan tag jika menggunakan Redis
    }
}
