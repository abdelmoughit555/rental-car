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
            'mileage_km' => 'invalid',
            'price_per_day' => 'invalid-price'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year', 'engine_cc', 'power_hp', 'doors', 'seats', 'mileage_km', 'price_per_day']);
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
            'mileage_km' => -1, // Below min:0
            'price_per_day' => 0.00 // Below min:0.01
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year', 'engine_cc', 'power_hp', 'doors', 'seats', 'mileage_km', 'price_per_day']);
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

    public function test_car_update_validates_availability_dates_format()
    {        
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'available_from' => 'invalid-date',
            'available_to' => 'also-invalid'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['available_from', 'available_to']);
    }

    public function test_car_update_validates_available_from_is_after_or_equal_today()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $yesterday = now()->subDay()->format('Y-m-d');

        $response = $this->putJson("/api/cars/{$car->id}", [
            'available_from' => $yesterday
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['available_from']);
    }

    public function test_car_update_validates_available_to_is_after_available_from()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $availableFrom = now()->addWeek()->format('Y-m-d');
        $availableTo = now()->addDays(3)->format('Y-m-d'); // Before available_from

        $response = $this->putJson("/api/cars/{$car->id}", [
            'available_from' => $availableFrom,
            'available_to' => $availableTo
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['available_to']);
    }

    public function test_car_update_validates_available_to_is_at_least_14_days_after_available_from()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $availableFrom = now()->addWeek()->format('Y-m-d');
        $availableTo = now()->addDays(10)->format('Y-m-d'); // Less than 14 days

        $response = $this->putJson("/api/cars/{$car->id}", [
            'available_from' => $availableFrom,
            'available_to' => $availableTo
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['available_to']);
    }

    public function test_car_update_accepts_valid_availability_dates()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $availableFrom = now()->addWeek()->format('Y-m-d');
        $availableTo = now()->addWeeks(3)->format('Y-m-d'); // More than 14 days

        $response = $this->putJson("/api/cars/{$car->id}", [
            'available_from' => $availableFrom,
            'available_to' => $availableTo
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
        ]);
        
        $car->refresh();
        
        $this->assertEquals($availableFrom, $car->available_from->format('Y-m-d'));
        $this->assertEquals($availableTo, $car->available_to->format('Y-m-d'));
    }

    public function test_car_update_validates_price_per_day_is_required_when_provided()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'price_per_day' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price_per_day']);
    }

    public function test_car_update_validates_price_per_day_is_numeric()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'price_per_day' => 'not-a-number'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price_per_day']);
    }

    public function test_car_update_validates_price_per_day_minimum_value()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'price_per_day' => 0.00
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price_per_day']);
    }

    public function test_car_update_validates_price_per_day_maximum_value()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'price_per_day' => 1000000.00
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price_per_day']);
    }

    public function test_car_update_accepts_valid_price_per_day()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $validPrice = 199.99;

        $response = $this->putJson("/api/cars/{$car->id}", [
            'price_per_day' => $validPrice
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
        ]);

        $this->assertEquals($validPrice, $car->fresh()->price_per_day);
    }

    public function test_car_update_accepts_decimal_price_per_day()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $decimalPrice = 150.50;

        $response = $this->putJson("/api/cars/{$car->id}", [
            'price_per_day' => $decimalPrice
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
        ]);
        
        $this->assertEquals($decimalPrice, $car->fresh()->price_per_day);
    }

    public function test_car_update_accepts_minimum_valid_price_per_day()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $minPrice = 0.01;

        $response = $this->putJson("/api/cars/{$car->id}", [
            'price_per_day' => $minPrice
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
        ]);

        $this->assertEquals($minPrice, $car->fresh()->price_per_day);
    }

    public function test_car_update_accepts_maximum_valid_price_per_day()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $maxPrice = 999999.99;

        $response = $this->putJson("/api/cars/{$car->id}", [
            'price_per_day' => $maxPrice
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
        ]);

        $this->assertEquals($maxPrice, $car->fresh()->price_per_day);
    }

    public function test_car_update_price_per_day_with_other_fields()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => 'Updated Title',
            'price_per_day' => 250.00,
            'description' => 'Updated Description'
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ]);
        
        $this->assertEquals(250.00, $car->fresh()->price_per_day);
    }
}
