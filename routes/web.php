<?php

use App\Http\Controllers\Cars\CarCreationController;
use App\Http\Controllers\Cars\CarEditorController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('cars')->group(function() {
        Route::get('/create', [CarCreationController::class, 'index'])->name('cars.create');

        Route::prefix('/{car}')->group(function() {
            Route::get('/information', [CarEditorController::class, 'information'])->name('cars.car.information');
        });
    });
});
