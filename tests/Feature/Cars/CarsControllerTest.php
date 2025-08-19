<?php

namespace Tests\Feature\Cars;

use App\Events\Cars\CarCreated;
use App\Models\Cars\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CarsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_a_car()
    {
        Event::fake([
            CarCreated::class
        ]);

        $user = $this->signIn();

        $response = $this->postJson('/api/cars', [
            'title' => 'Test Car',
            'description' => 'Test Description'
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Car created successfully'
        ]);

        $this->assertDatabaseHas('cars', [
            'title' => 'Test Car',
            'description' => 'Test Description'
        ]);

        $car = Car::where('title', 'Test Car')->first();

        Event::assertDispatched(CarCreated::class, function ($event) use ($car) {
            return $event->carId === $car->id;
        });
    }

    public function test_car_creation_validates_required_fields()
    {
        $this->signIn();
        
        $response = $this->postJson('/api/cars', []);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description']);
    }

    public function test_car_creation_validates_field_lengths()
    {
        $this->signIn();
        
        $response = $this->postJson('/api/cars', [
            'title' => str_repeat('a', 256), 
            'description' => str_repeat('a', 1001)
        ]);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description']);
    }

    public function test_unauthenticated_user_cannot_create_car()
    {
        $response = $this->postJson('/api/cars', [
            'title' => 'Test Car',
            'description' => 'Test Description'
        ]);
        
        $response->assertStatus(401);
    }

    public function test_car_creation_response_structure()
    {
        $this->signIn();
        
        $response = $this->postJson('/api/cars', [
            'title' => 'Test Car',
            'description' => 'Test Description'
        ]);
        
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'car'
        ]);
    }
}