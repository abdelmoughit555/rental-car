<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\Cars\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    protected $model = Media::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_type' => Car::class,
            'model_id' => Car::factory(),
            'name' => $this->faker->uuid(),
            'extension' => $this->faker->randomElement(['jpg', 'jpeg', 'png', 'webp']),
            'directory' => 'car_images/' . $this->faker->randomElement(['front_view', 'interior_dashboard', 'main_seats', 'back_seats_trunk']),
            'type' => 'uploaded',
            'disk' => 's3',
            'metadata' => null,
            'checksum' => null,
            'size' => null,
        ];
    }

    /**
     * Create media for a specific car
     */
    public function forCar(Car $car): static
    {
        return $this->state(fn (array $attributes) => [
            'model_type' => Car::class,
            'model_id' => $car->id,
        ]);
    }

    /**
     * Create media for a specific section
     */
    public function forSection(string $section): static
    {
        return $this->state(fn (array $attributes) => [
            'directory' => "car_images/{$section}",
        ]);
    }

    /**
     * Create media with specific file extension
     */
    public function withExtension(string $extension): static
    {
        return $this->state(fn (array $attributes) => [
            'extension' => $extension,
        ]);
    }

    /**
     * Create media with specific name
     */
    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }

    /**
     * Create media with existing metadata (already processed)
     */
    public function processed(): static
    {
        return $this->state(fn (array $attributes) => [
            'metadata' => [
                'width' => $this->faker->numberBetween(800, 2000),
                'height' => $this->faker->numberBetween(600, 1500),
                'format' => $attributes['extension'] ?? 'jpg',
                'mime_type' => 'image/' . ($attributes['extension'] ?? 'jpeg'),
                'processed_at' => now()->toISOString(),
            ],
            'checksum' => $this->faker->md5(),
            'size' => $this->faker->numberBetween(100000, 5000000),
        ]);
    }

    /**
     * Create media for front view section
     */
    public function frontView(): static
    {
        return $this->forSection('front_view');
    }

    /**
     * Create media for interior dashboard section
     */
    public function interiorDashboard(): static
    {
        return $this->forSection('interior_dashboard');
    }

    /**
     * Create media for main seats section
     */
    public function mainSeats(): static
    {
        return $this->forSection('main_seats');
    }

    /**
     * Create media for back seats trunk section
     */
    public function backSeatsTrunk(): static
    {
        return $this->forSection('back_seats_trunk');
    }

    /**
     * Create media for additional section
     */
    public function additional(): static
    {
        return $this->forSection('additional');
    }
}
