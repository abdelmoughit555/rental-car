<?php

namespace Database\Factories\Brands;

use App\Models\Brands\CarModel;
use App\Models\Brands\Make;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brands\CarModel>
 */
class CarModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CarModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $carModels = [
            'Camry', 'Corolla', 'Civic', 'Accord', 'F-150', 'Silverado', 'Altima', 'Sentra',
            '3 Series', '5 Series', 'C-Class', 'E-Class', 'A4', 'A6', 'Golf', 'Passat',
            'Elantra', 'Sonata', 'Forte', 'K5', 'CX-5', 'CX-30', 'Impreza', 'Forester',
            'ES', 'RX', 'Q50', 'Q60', 'TLX', 'RDX', 'Enclave', 'Encore', 'XT5', 'XT6',
            '300', 'Pacifica', 'Challenger', 'Charger'
        ];

        $modelName = fake()->randomElement($carModels);
        
        return [
            'make_id' => Make::factory(),
            'name' => $modelName,
            'slug' => Str::slug($modelName),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the car model is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
