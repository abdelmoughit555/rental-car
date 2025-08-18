<?php

namespace App\Http\Controllers\Cars;

use App\Http\Controllers\Controller;
use App\Models\Cars\Car;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CarEditorController extends Controller
{
    public function information(Car $car, Request $request)
    {
        return Inertia::render('Cars/Edit/Information', [
        ]);
    }
}
