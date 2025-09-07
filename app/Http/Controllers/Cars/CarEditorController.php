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

    public function features(Car $car, Request $request)
    {
        $car->load('features');
        
        return Inertia::render('Cars/Edit/Features', [
            'car' => CarResource::make($car),
            'validation' => (new CarValidator)->featuresValidation($car)
        ]);
    }

    public function images(Car $car, Request $request)
    {
        $car->load('media');

        return Inertia::render('Cars/Edit/Images', [
            'car' => CarResource::make($car),
            'validation' => (new CarValidator)->mediaValidation($car)
        ]);
    }

    public function confirmation(Car $car, Request $request)
    {
        $car->load(['brand', 'carModel', 'fuelType', 'gearbox', 'features.featureCategory', 'media']);
        
        return Inertia::render('Cars/Edit/Confirmation', [
            'car' => CarResource::make($car),
            'validation' => (new CarValidator)->validate($car)
        ]);
    }
}
