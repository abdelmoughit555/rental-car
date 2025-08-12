<?php

namespace Database\Factories\Cars;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cars\Gearbox>
 */
class GearboxFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $name = fake()->randomElement(['manual', 'automatic', 'semi-automatic']),
            'slug' => Str::slug($name),
        ];
    }
}