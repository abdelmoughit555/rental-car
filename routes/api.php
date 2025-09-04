<?php

use App\Http\Controllers\Cars\CarController;
use App\Http\Controllers\Cars\CarsController;
use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    // Image processing routes
    Route::post('/s3/presign', [ImageController::class, 'presign']);
    Route::post('/images/process', [ImageController::class, 'process']);
    
    Route::prefix('cars')->group(function () {
        Route::post('/', [CarsController::class, 'store'])->name('cars.store');


        Route::prefix('{car}')->group(function() {
            Route::put('/', [CarController::class, 'update'])->name('cars.update');
        });
    });
});