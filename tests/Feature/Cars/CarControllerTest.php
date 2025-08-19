<?php

namespace Tests\Feature\Cars;

use App\Events\Cars\CarUpdated;
use App\Models\Cars\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_update_a_car()
    {
        Event::fake([
            CarUpdated::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
            'description' => 'Original Description'
        ]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => 'Updated Car Title',
            'description' => 'Updated Description'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'title' => 'Updated Car Title',
            'description' => 'Updated Description'
        ]);

        Event::assertDispatched(CarUpdated::class, function ($event) use ($car) {
            return $event->carId === $car->id;
        });
    }

    public function test_car_update_validates_required_fields()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => '', // Empty title should fail
            'description' => '' // Empty description should fail
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description']);
    }

    public function test_car_update_validates_field_lengths()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => str_repeat('a', 256), // Exceeds max:512
            'description' => str_repeat('a', 1001) // Exceeds max:512
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description']);
    }

    public function test_car_update_validates_numeric_fields()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'year' => 'not-a-number',
            'engine_cc' => 'invalid',
            'power_hp' => 'invalid',
            'doors' => 'invalid',
            'seats' => 'invalid',
            'mileage_km' => 'invalid'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year', 'engine_cc', 'power_hp', 'doors', 'seats', 'mileage_km']);
    }

    public function test_car_update_validates_field_ranges()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'year' => 1899, // Below min:1900
            'engine_cc' => 99, // Below min:100
            'power_hp' => 99, // Below min:100
            'doors' => 0, // Below min:1
            'seats' => 0, // Below min:1
            'mileage_km' => -1 // Below min:0
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year', 'engine_cc', 'power_hp', 'doors', 'seats', 'mileage_km']);
    }

    public function test_car_update_validates_foreign_key_constraints()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'brand_id' => 99999, // Non-existent ID
            'car_model_id' => 99999, // Non-existent ID
            'gearbox_id' => 99999, // Non-existent ID
            'fuel_type_id' => 99999 // Non-existent ID
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['brand_id', 'car_model_id', 'gearbox_id', 'fuel_type_id']);
    }

    public function test_car_update_response_structure()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'car' => [
                'id',
                'title',
                'description'
            ]
        ]);
    }

    public function test_car_update_event_is_dispatched()
    {
        Event::fake([
            CarUpdated::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $this->putJson("/api/cars/{$car->id}", [
            'title' => 'Updated Title'
        ])->assertStatus(200);

        Event::assertDispatched(CarUpdated::class, function ($event) use ($car) {
            return $event->carId === $car->id;
        });
    }

    public function test_car_update_event_is_not_dispatched_on_validation_failure()
    {
        Event::fake([
            CarUpdated::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $this->putJson("/api/cars/{$car->id}", [
            'title' => '' // Invalid - empty title
        ])->assertStatus(422);

        Event::assertNotDispatched(CarUpdated::class);
    }

    public function test_car_update_partial_fields()
    {
        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
            'description' => 'Original Description',
            'year' => 2020
        ]);

        // Only update title, leave other fields unchanged
        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => 'Only Title Updated'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'title' => 'Only Title Updated',
            'description' => 'Original Description', // Should remain unchanged
            'year' => 2020 // Should remain unchanged
        ]);
    }

    public function test_unauthenticated_user_cannot_update_car()
    {
        $car = Car::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description'
        ]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => 'Unauthorized Update'
        ]);

        $response->assertStatus(401);
        
        // Verify the car was not updated
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'title' => 'Original Title',
            'description' => 'Original Description'
        ]);
    }
}
