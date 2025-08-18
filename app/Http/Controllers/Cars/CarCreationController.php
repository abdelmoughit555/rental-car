<?php

namespace App\Http\Controllers\Cars;

use Inertia\Inertia;
use App\Http\Controllers\Controller;

class CarCreationController extends Controller
{
    public function index()
    {
        return Inertia::render('Cars/Create/Index');
    }
}
