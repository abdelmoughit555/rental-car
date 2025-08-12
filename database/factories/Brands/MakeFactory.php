<?php

namespace Database\Factories\Brands;

use App\Models\Brands\Make;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brands\Make>
 */
class MakeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Make::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $carMakes = [
            'Toyota', 'Honda', 'Ford', 'Chevrolet', 'Nissan', 'BMW', 'Mercedes-Benz',
            'Audi', 'Volkswagen', 'Hyundai', 'Kia', 'Mazda', 'Subaru', 'Lexus',
            'Infiniti', 'Acura', 'Buick', 'Cadillac', 'Chrysler', 'Dodge'
        ];

        $makeName = fake()->randomElement($carMakes);
        
        return [
            'name' => $makeName,
            'slug' => Str::slug($makeName),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the make is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
