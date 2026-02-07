<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StrategicController;
use App\Http\Controllers\StrategicDashboardController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\CommunityServiceController; // Added
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminRegionController;
use App\Http\Controllers\Admin\AdminGardenController;
use App\Http\Controllers\Admin\AdminInsightController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminVisitController;
use App\Http\Controllers\Admin\AdminAfdelingController;
use App\Http\Controllers\Admin\AdminBlockController;
use App\Http\Controllers\Admin\AdminProductionRealizationController;
use App\Http\Controllers\Admin\AdminStrategicActionController;
use App\Http\Controllers\Admin\AdminProgramController;
use App\Http\Controllers\Admin\AdminPerformanceTargetController;
use App\Http\Controllers\Admin\AdminCommunityServiceController; // Added
use App\Http\Controllers\Admin\AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kebun-model', [AboutController::class, 'index'])->name('about');

// Strategic Action (Public)
Route::prefix('strategic-action')->name('strategic.')->group(function () {
    Route::get('/', [StrategicController::class, 'index'])->name('index');
    Route::get('/{region}', [StrategicController::class, 'region'])->name('region');
    Route::get('/{region}/{garden}', [StrategicController::class, 'garden'])->name('garden');
});

// Visits (Public)
Route::resource('kunjungan-dinas', VisitController::class)->names('visits');

// Community Service (Public)
Route::get('/pengabdian-masyarakat', [CommunityServiceController::class, 'index'])->name('community-services.index');

// Public Dashboard (Executive)
Route::get('/dashboard/kebun-model', [StrategicDashboardController::class, 'index'])->name('dashboard.garden');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');
    Route::get('/dashboard/penelitian', [DashboardController::class, 'research'])->name('dashboard.research');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Pages Management
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/about', [AdminPageController::class, 'editAbout'])->name('about.edit');
        Route::put('/about', [AdminPageController::class, 'updateAbout'])->name('about.update');
        Route::get('/research', [AdminPageController::class, 'editResearch'])->name('research.edit');
        Route::put('/research', [AdminPageController::class, 'updateResearch'])->name('research.update');
    });

    // Master Data
    Route::resource('regions', AdminRegionController::class);
    Route::resource('gardens', AdminGardenController::class);
    Route::resource('afdelings', AdminAfdelingController::class);
    Route::resource('blocks', AdminBlockController::class);
    Route::resource('users', AdminUserController::class);

    // Production & Performance
    Route::resource('production', AdminProductionController::class);
    Route::resource('production-realizations', AdminProductionRealizationController::class);
    Route::resource('performance-targets', AdminPerformanceTargetController::class);

    // Strategic & Programs
    Route::resource('programs', AdminProgramController::class);
    Route::resource('strategic-actions', AdminStrategicActionController::class);
    Route::resource('insights', AdminInsightController::class);

    // Other
    Route::resource('visits', AdminVisitController::class);
    Route::resource('community-services', AdminCommunityServiceController::class); // Added
});
