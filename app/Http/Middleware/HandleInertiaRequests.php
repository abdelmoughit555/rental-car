<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Cache;
use App\Models\City;
use App\Models\Area;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'cities' => $this->fetchCities(),
            'areas' => $this->fetchAreas(),
        ];
    }

    /**
     * Fetch cities with caching for 1 hour
     */
    public function fetchCities()
    {
        return Cache::remember('cities', now()->addHour(), function () {
            return City::active()
                ->orderByDesc('featured')
                ->get(['id', 'name']);
        });
    }

    /**
     * Fetch areas with caching for 1 hour
     */
    public function fetchAreas()
    {
        return Cache::remember('areas', now()->addHour(), function () {
            $areas = Area::with('city:id,name')
                ->active()
                ->orderBy('name')
                ->get(['id', 'name','city_id']);
                        
            return $areas->map(function ($area) {
                return [
                    'id' => $area->id,
                    'name' => $area->name,
                    'city_id' => $area->city_id
                ];
            });
        });
    }
}
