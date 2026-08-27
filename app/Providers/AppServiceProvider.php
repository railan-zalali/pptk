<?php

namespace App\Providers;

use App\Models\ProductionRealization;
use App\Models\StrategicAction;
use App\Services\InsightService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-refresh insight saat data produksi disimpan.
        // ponytail: closures di sini lebih ringan dari 2 Observer class.
        ProductionRealization::saved(function (ProductionRealization $model) {
            $service = app(InsightService::class);
            $service->generateProductivityInsight($model->kebun_id, $model->year);
            $service->generateQualityInsight($model->kebun_id, $model->year);
        });

        // Auto-refresh insight saat aksi strategis disimpan.
        StrategicAction::saved(function (StrategicAction $action) {
            app(InsightService::class)->generateStrategicInsight($action);
        });
    }
}
