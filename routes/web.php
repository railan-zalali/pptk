<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StrategicController;
use App\Http\Controllers\VisitController;
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
    Route::get('/dashboard/kebun-model', [DashboardController::class, 'garden'])->name('dashboard.garden');
    Route::get('/dashboard/penelitian', [DashboardController::class, 'research'])->name('dashboard.research');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
