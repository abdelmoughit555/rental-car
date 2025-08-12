<?php

namespace Database\Factories\Cars;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cars\FuelType>
 */
class FuelTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $name = fake()->randomElement(['petrol', 'diesel', 'electric', 'hybrid', 'lpg']),
            'slug' => Str::slug($name),
        ];
    }
}
