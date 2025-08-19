<?php

namespace Database\Factories\Cars;

use App\Models\User;
use App\Models\Brands\CarModel;
use App\Models\Cars\FuelType;
use App\Models\Cars\Gearbox;
use App\Enums\Cars\CarStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cars\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraphs(3, true),
            'year' => fake()->numberBetween(2010, 2024),
            'engine_cc' => fake()->numberBetween(1000, 6000),
            'power_hp' => fake()->numberBetween(75, 600),
            'doors' => fake()->numberBetween(1, 5),
            'seats' => fake()->numberBetween(2, 8),
            'mileage_km' => fake()->numberBetween(0, 200000),
            'registration_number' => strtoupper(fake()->lexify('??')) . '-' . 
                                   fake()->numberBetween(100, 999) . '-' . 
                                   strtoupper(fake()->lexify('???')),
            'user_id' => User::factory(),
            'car_model_id' => CarModel::factory(),
            'fuel_type_id' => FuelType::factory(),
            'gearbox_id' => Gearbox::factory(),
        ];
    }

    /**
     * Indicate that the car is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CarStatus::ACTIVE->value,
            'published_at' => now(),
            'hidden_at' => null,
        ]);
    }

    /**
     * Indicate that the car is hidden.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CarStatus::INACTIVE->value,
            'hidden_at' => now(),
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the car is rented.
     */
    public function rented(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CarStatus::RENTED->value,
        ]);
    }

    /**
     * Indicate that the car is reserved.
     */
    public function reserved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CarStatus::RESERVED->value,
        ]);
    }

    /**
     * Indicate that the car is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CarStatus::DRAFT->value,
            'published_at' => null,
            'hidden_at' => null,
        ]);
    }
}
