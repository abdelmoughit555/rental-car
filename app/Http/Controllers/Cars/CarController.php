<?php

namespace App\Http\Controllers\Cars;

use App\Events\Cars\CarUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cars\CarRequest;
use App\Http\Resources\Cars\CarResource;
use App\Models\Cars\Car;

class CarController extends Controller
{
    public function update(CarRequest $request, Car $car)
    {
        $car->update($request->validated());

        $request->handle();
        
        CarUpdated::dispatch($car->id);

        return response()->json([
            'message' => 'Car updated successfully',
            'car' => CarResource::make($car)
        ]);
    }
}