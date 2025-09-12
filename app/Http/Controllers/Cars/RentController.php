<?php

namespace App\Http\Controllers\Cars;

use App\Filters\CarFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cars\RentFiltersRequest;
use App\Http\Resources\Cars\CarResource;
use App\Models\Cars\Car;
use Inertia\Inertia;
use Inertia\Response;

class RentController extends Controller
{
    public function index(RentFiltersRequest $request, CarFilter $filters): Response
    {
        $cars = Car::query()->active()->filter($filters)
        ->with(['brand', 'carModel', 'fuelType', 'gearbox', 'frontViewImage', 'features'])
        ->withCount(['media as images_count' => function ($q) {
            $q->where('directory', 'like', 'car_images/%');
        }])->paginate($request->per_page ?? 12)
        ->appends($request->validated());


        return Inertia::render('Rent/Index', [
            'cars' => CarResource::collection($cars),
            'filters' => $request->all(),
        ]);
    }
}


