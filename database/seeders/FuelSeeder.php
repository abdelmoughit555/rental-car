<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cars\FuelType;
use Illuminate\Support\Str;

class FuelSeeder extends Seeder
{
    public function run()
    {
        $fuels = [
            'petrol',
            'diesel',
            'electric',
            'hybrid',
            'lpg',
        ];  
        foreach ($fuels as $fuel) {
            FuelType::updateOrCreate([
                'slug' => Str::slug($fuel),
            ], [
                'name' => $fuel,
            ]);
        }
    }
}