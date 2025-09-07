<?php

use App\Http\Controllers\Cars\CarCreationController;
use App\Http\Controllers\Cars\CarEditorController;
use App\Http\Controllers\Storage\S3PresignController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
    ]);
});

Route::post('/s3/presign', S3PresignController::class)->name('storage.s3.presign');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('cars')->group(function() {
        Route::get('/create', [CarCreationController::class, 'index'])->name('cars.create');

        Route::prefix('/{car}')->group(function() {
            Route::get('/information', [CarEditorController::class, 'information'])->name('cars.car.information');
            Route::get('/availability', [CarEditorController::class, 'availability'])->name('cars.car.availability');
            Route::get('/pricing', [CarEditorController::class, 'pricing'])->name('cars.car.pricing');
            Route::get('/features', [CarEditorController::class, 'features'])->name('cars.car.features');
            Route::get('/images', [CarEditorController::class, 'images'])->name('cars.car.images');
            Route::get('/confirmation', [CarEditorController::class, 'confirmation'])->name('cars.car.confirmation');
        });
    });
});
