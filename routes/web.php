<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StrategicController;
use App\Http\Controllers\StrategicDashboardController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\CommunityServiceController;
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
use App\Http\Controllers\Admin\AdminCommunityServiceController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Manajemen\ManajemenController;
use App\Http\Controllers\Manajemen\ManajemenProgramController;
use App\Http\Controllers\Manajemen\ManajemenStrategicActionController;
use App\Http\Controllers\Manajemen\ManajemenInsightController;
use App\Http\Controllers\Manajemen\ManajemenVisitController;
use App\Http\Controllers\Manajemen\ManajemenCommunityServiceController;
use App\Http\Controllers\Manajemen\ManajemenResearchController;
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

// Public Dashboard (Executive — no auth required)
Route::get('/dashboard/kebun-model', [StrategicDashboardController::class, 'index'])->name('dashboard.garden');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes (Role-based redirect)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard: redirect sesuai role
    Route::get('/dashboard', [DashboardController::class, 'user'])->name('dashboard');
    Route::get('/dashboard/penelitian', [DashboardController::class, 'research'])->name('dashboard.research');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


/*
|--------------------------------------------------------------------------
| Admin PPTK Routes (Full CRUD - Operational & Master Data)
| Single consolidated group for maintainability
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin_pptk'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');



    // Master Kebun
    Route::resource('regions', AdminRegionController::class);
    Route::resource('gardens', AdminGardenController::class);
    Route::resource('afdelings', AdminAfdelingController::class);
    Route::resource('blocks', AdminBlockController::class);

    // Produksi & Realisasi & Target
    Route::resource('production-realizations', AdminProductionRealizationController::class);
    Route::resource('performance-targets', AdminPerformanceTargetController::class);

    // Manajemen Pengguna
    Route::resource('users', AdminUserController::class);

    // Pages Management
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/about', [AdminPageController::class, 'editAbout'])->name('about.edit');
        Route::put('/about', [AdminPageController::class, 'updateAbout'])->name('about.update');
    });

    // Strategic & Programs & Insights
    Route::resource('programs', AdminProgramController::class);
    Route::resource('strategic-actions', AdminStrategicActionController::class);
    Route::resource('insights', AdminInsightController::class);

    // Kunjungan
    Route::resource('visits', AdminVisitController::class);
});


/*
|--------------------------------------------------------------------------
| Manajemen Routes (Monitoring, Analysis & Penelitian Management)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'manajemen'])->prefix('manajemen')->name('manajemen.')->group(function () {
    Route::get('/', [ManajemenController::class, 'index'])->name('dashboard');

    // Program
    Route::resource('programs', ManajemenProgramController::class);

    // Strategic Action
    Route::resource('strategic-actions', ManajemenStrategicActionController::class);

    // Insight
    Route::get('/insights', [ManajemenInsightController::class, 'index'])->name('insights.index');
    Route::get('/insights/{insight}', [ManajemenInsightController::class, 'show'])->name('insights.show');

    // Kunjungan
    Route::resource('visits', ManajemenVisitController::class);

    // Penelitian (Data Penelitian & Pengabdian Masyarakat)
    Route::get('/penelitian', [ManajemenResearchController::class, 'index'])->name('penelitian.index');
    Route::get('/penelitian/edit', [ManajemenResearchController::class, 'edit'])->name('penelitian.edit');
    Route::put('/penelitian', [ManajemenResearchController::class, 'update'])->name('penelitian.update');
    Route::resource('community-services', \App\Http\Controllers\Admin\AdminCommunityServiceController::class);
    
    // Research Budgets
    Route::get('research-budgets', [\App\Http\Controllers\Admin\AdminResearchBudgetController::class, 'index'])->name('research-budgets.index');
    Route::get('research-budgets/edit', [\App\Http\Controllers\Admin\AdminResearchBudgetController::class, 'edit'])->name('research-budgets.edit');
    Route::put('research-budgets', [\App\Http\Controllers\Admin\AdminResearchBudgetController::class, 'update'])->name('research-budgets.update');
});
