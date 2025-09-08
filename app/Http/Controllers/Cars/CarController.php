<?php

namespace App\Http\Controllers\Cars;

use App\Actions\Cars\UpdateCarAction;
use App\Enums\Cars\CarStatus;
use App\Events\Cars\CarUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cars\CarRequest;
use App\Http\Resources\Cars\CarResource;
use App\Models\Cars\Car;
use App\Validators\CarValidator;
use App\Events\Cars\CarSubmitted;

class CarController extends Controller
{
    public function update(CarRequest $request, Car $car, UpdateCarAction $action)
    {
        $car = $action->execute(
            car: $car,
            attributes: $request->validated()
        );

        CarUpdated::dispatch($car->id);

        return response()->json([
            'message' => 'Car updated successfully',
            'car' => CarResource::make($car->fresh(['features','media']))
        ]);
    }

    public function submission(Car $car, CarValidator $carValidator)
    {
        if($car->status !== CarStatus::DRAFT) {
            return response()->json([
                'message' => 'Car is not in a draft state to be submitted',
                'car' => $car->id
            ], 422);
        }

        $carValidator = $carValidator->validate($car);

        if(!$carValidator['valid']) {
            return response()->json([
                'message' => 'Car Validation Failed',
                'errors' => $carValidator
            ], 422);
        }

        $car->update([
            'status' => CarStatus::PENDING_APPROVAL,
        ]);

        CarSubmitted::dispatch($car->id);

        return response()->json([
            'message' => 'Car Submitted',
            'car' => $car->id
        ]);
    }
}