<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StrategicController;
use App\Http\Controllers\StrategicDashboardController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminRegionController;
use App\Http\Controllers\Admin\AdminGardenController;
use App\Http\Controllers\Admin\AdminProductionController;
use App\Http\Controllers\Admin\AdminInsightController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminVisitController;
use App\Http\Controllers\Admin\AdminAfdelingController;
use App\Http\Controllers\Admin\AdminBlockController;
use App\Http\Controllers\Admin\AdminProductionRealizationController;
use App\Http\Controllers\Admin\AdminStrategicActionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kebun-model', [AboutController::class, 'index'])->name('about');
Route::get('/strategic-action', [StrategicController::class, 'index'])->name('strategic.index');
Route::get('/strategic-action/{region}', [StrategicController::class, 'region'])->name('strategic.region');
Route::get('/strategic-action/{region}/{garden}', [StrategicController::class, 'garden'])->name('strategic.garden');

Route::get('/kunjungan-dinas', [VisitController::class, 'index'])->name('visits.index');
Route::get('/kunjungan-dinas/create', [VisitController::class, 'create'])->name('visits.create');
Route::post('/kunjungan-dinas', [VisitController::class, 'store'])->name('visits.store');
Route::get('/kunjungan-dinas/{visit}', [VisitController::class, 'show'])->name('visits.show');
Route::get('/kunjungan-dinas/{visit}/edit', [VisitController::class, 'edit'])->name('visits.edit');
Route::put('/kunjungan-dinas/{visit}', [VisitController::class, 'update'])->name('visits.update');
Route::delete('/kunjungan-dinas/{visit}', [VisitController::class, 'destroy'])->name('visits.destroy');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');
    Route::get('/dashboard/kebun-model', [StrategicDashboardController::class, 'index'])->name('dashboard.garden');
    Route::get('/dashboard/penelitian', [DashboardController::class, 'research'])->name('dashboard.research');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Pages
    Route::get('/pages/about', [AdminPageController::class, 'editAbout'])->name('pages.about.edit');
    Route::put('/pages/about', [AdminPageController::class, 'updateAbout'])->name('pages.about.update');

    // Regions CRUD
    Route::get('/regions', [AdminRegionController::class, 'index'])->name('regions.index');
    Route::get('/regions/create', [AdminRegionController::class, 'create'])->name('regions.create');
    Route::post('/regions', [AdminRegionController::class, 'store'])->name('regions.store');
    Route::get('/regions/{region}/edit', [AdminRegionController::class, 'edit'])->name('regions.edit');
    Route::put('/regions/{region}', [AdminRegionController::class, 'update'])->name('regions.update');
    Route::delete('/regions/{region}', [AdminRegionController::class, 'destroy'])->name('regions.destroy');

    // Gardens CRUD
    Route::get('/gardens', [AdminGardenController::class, 'index'])->name('gardens.index');
    Route::get('/gardens/create', [AdminGardenController::class, 'create'])->name('gardens.create');
    Route::post('/gardens', [AdminGardenController::class, 'store'])->name('gardens.store');
    Route::get('/gardens/{garden}/edit', [AdminGardenController::class, 'edit'])->name('gardens.edit');
    Route::put('/gardens/{garden}', [AdminGardenController::class, 'update'])->name('gardens.update');
    Route::delete('/gardens/{garden}', [AdminGardenController::class, 'destroy'])->name('gardens.destroy');

    // Production Data CRUD
    Route::get('/production', [AdminProductionController::class, 'index'])->name('production.index');
    Route::get('/production/create', [AdminProductionController::class, 'create'])->name('production.create');
    Route::post('/production', [AdminProductionController::class, 'store'])->name('production.store');
    Route::get('/production/{production}/edit', [AdminProductionController::class, 'edit'])->name('production.edit');
    Route::put('/production/{production}', [AdminProductionController::class, 'update'])->name('production.update');
    Route::delete('/production/{production}', [AdminProductionController::class, 'destroy'])->name('production.destroy');

    // Insights CRUD
    Route::get('/insights', [AdminInsightController::class, 'index'])->name('insights.index');
    Route::get('/insights/create', [AdminInsightController::class, 'create'])->name('insights.create');
    Route::post('/insights', [AdminInsightController::class, 'store'])->name('insights.store');
    Route::get('/insights/{insight}/edit', [AdminInsightController::class, 'edit'])->name('insights.edit');
    Route::put('/insights/{insight}', [AdminInsightController::class, 'update'])->name('insights.update');
    Route::delete('/insights/{insight}', [AdminInsightController::class, 'destroy'])->name('insights.destroy');

    Route::get('/visits', [AdminVisitController::class, 'index'])->name('visits.index');
    Route::get('/visits/create', [AdminVisitController::class, 'create'])->name('visits.create');
    Route::post('/visits', [AdminVisitController::class, 'store'])->name('visits.store');
    Route::get('/visits/{visit}/edit', [AdminVisitController::class, 'edit'])->name('visits.edit');
    Route::put('/visits/{visit}', [AdminVisitController::class, 'update'])->name('visits.update');
    Route::delete('/visits/{visit}', [AdminVisitController::class, 'destroy'])->name('visits.destroy');

    // Afdeling CRUD
    Route::resource('afdelings', AdminAfdelingController::class);

    // Block CRUD
    Route::resource('blocks', AdminBlockController::class);

    // Production Realization CRUD
    Route::resource('production-realizations', AdminProductionRealizationController::class);

    // Strategic Action CRUD
    Route::resource('strategic-actions', AdminStrategicActionController::class);
});
