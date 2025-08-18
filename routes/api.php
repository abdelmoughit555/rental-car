<?php

use App\Http\Controllers\Cars\CarsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('cars')->group(function () {
        Route::post('/', [CarsController::class, 'store'])->name('cars.store');
    });
});