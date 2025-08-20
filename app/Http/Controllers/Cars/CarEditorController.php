<?php

namespace App\Http\Controllers\Cars;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cars\CarResource;
use App\Models\Cars\Car;
use App\Validators\CarValidator;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarEditorController extends Controller
{
    public function information(Car $car, Request $request)
    {
        return Inertia::render('Cars/Edit/Information', [
            'car' => CarResource::make($car),
            'validation' => (new CarValidator)->informationValidation($car)
        ]);
    }

    public function availability(Car $car, Request $request)
    {
        return Inertia::render('Cars/Edit/Availability', [
            'car' => CarResource::make($car),
            'validation' => (new CarValidator)->availabilityValidation($car)
        ]);
    }

    public function pricing(Car $car, Request $request)
    {
        return Inertia::render('Cars/Edit/Pricing', [
            'car' => CarResource::make($car),
            'validation' => (new CarValidator)->pricingValidation($car)
        ]);
    }
}
