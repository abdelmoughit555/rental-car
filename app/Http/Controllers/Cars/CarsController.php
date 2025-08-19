<?php

namespace App\Http\Controllers\Cars;

use App\Events\Cars\CarCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cars\CarCreationRequest;
use App\Models\Cars\Car;

class CarsController extends Controller
{
    public function store(CarCreationRequest $request)
    {
        // Todo: add policy for creating a car
        $car = Car::create(
            array_merge($request->validated(), 
            [
                'user_id' => auth()->user()->id
            ])
        );

        CarCreated::dispatch($car->id);

        return response()->json([
            'message' => 'Car created successfully',
            'car' => $car->id
        ], 201);
    }
}