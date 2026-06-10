<?php

namespace App\Providers;

use App\Models\Garden;
use App\Models\Region;
use App\Models\Visit;
use App\Observers\GardenObserver;
use App\Observers\RegionObserver;
use App\Policies\VisitPolicy;
use Illuminate\Support\Facades\Gate;
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
        // Register Policies
        Gate::policy(Visit::class, VisitPolicy::class);

        // Register Observers (cascade soft-delete)
        Region::observe(RegionObserver::class);
        Garden::observe(GardenObserver::class);
    }
}
