<?php

namespace Tests\Feature\Cars;

use App\Events\Cars\CarUpdated;
use App\Events\Cars\CarSubmitted;
use App\Models\CarFeatures\Feature;
use App\Models\Cars\Car;
use App\Models\Brands\Make;
use App\Models\Media;
use App\Enums\Cars\CarStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up storage disks for testing
        Storage::fake('local');
        Storage::fake('s3');
    }

    protected function createValidCarForSubmission($user, $status = CarStatus::DRAFT)
    {
        $brand = Make::factory()->create();

        $car = Car::factory()->create([
            'user_id' => $user->id,
            'status' => $status,
            'brand_id' => $brand->id,
            'available_from' => now()->addWeek(),
            'available_to' => now()->addWeeks(3),
            'price_per_day' => 150.00
        ]);

        $features = Feature::factory()->count(2)->create();
        $car->features()->attach($features->pluck('id'));

        $sections = ['front_view', 'interior_dashboard', 'main_seats', 'back_seats_trunk'];
        foreach ($sections as $section) {
            Media::factory()
                ->forCar($car)
                ->forSection($section)
                ->processed()
                ->count(3)
                ->create();
        }

        return $car;
    }

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
            'title' => '', 
            'description' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'description']);
    }

    public function test_car_update_validates_field_lengths()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => str_repeat('a', 256),
            'description' => str_repeat('a', 1001)
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
            'year' => 1899,
            'engine_cc' => 99,
            'power_hp' => 99,
            'doors' => 0,
            'seats' => 0,
            'mileage_km' => -1,
            'price_per_day' => 0.00
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['year', 'engine_cc', 'power_hp', 'doors', 'seats', 'mileage_km', 'price_per_day']);
    }

    public function test_car_update_validates_foreign_key_constraints()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'brand_id' => 99999,
            'car_model_id' => 99999,
            'gearbox_id' => 99999,
            'fuel_type_id' => 99999
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
            'title' => ''
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

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => 'Only Title Updated'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'title' => 'Only Title Updated',
            'description' => 'Original Description',
            'year' => 2020
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
        $availableTo = now()->addDays(3)->format('Y-m-d');

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
        $availableTo = now()->addDays(10)->format('Y-m-d');

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
        $availableTo = now()->addWeeks(3)->format('Y-m-d');

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

    public function test_car_update_validates_features_is_array()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'features' => 'not-an-array'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['features']);
    }

    public function test_car_update_validates_features_contains_valid_feature_ids()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'features' => [99999, 'invalid-id', 88888]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['features.0', 'features.1', 'features.2']);
    }

    public function test_car_update_accepts_empty_features_array()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'features' => []
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $this->assertCount(0, $car->fresh()->features);
    }

    public function test_car_update_syncs_features_correctly()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        // Create some features
        $feature1 = Feature::factory()->create();
        $feature2 = Feature::factory()->create();
        $feature3 = Feature::factory()->create();

        $response = $this->putJson("/api/cars/{$car->id}", [
            'features' => [$feature1->id, $feature2->id]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $car->refresh();
        $this->assertCount(2, $car->features);
        $this->assertTrue($car->features->contains($feature1->id));
        $this->assertTrue($car->features->contains($feature2->id));
        $this->assertFalse($car->features->contains($feature3->id));
    }

    public function test_car_update_features_with_other_fields()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $feature1 = Feature::factory()->create();
        $feature2 = Feature::factory()->create();

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => 'Updated Title',
            'features' => [$feature1->id, $feature2->id],
            'price_per_day' => 150.00
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'title' => 'Updated Title',
        ]);

        $car->refresh();
        $this->assertCount(2, $car->features);
        $this->assertTrue($car->features->contains($feature1->id));
        $this->assertTrue($car->features->contains($feature2->id));
    }

    public function test_car_update_features_replaces_existing_features()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $feature1 = Feature::factory()->create();
        $feature2 = Feature::factory()->create();
        $feature3 = Feature::factory()->create();
        $feature4 = Feature::factory()->create();

        $this->putJson("/api/cars/{$car->id}", [
            'features' => [$feature1->id, $feature2->id]
        ])->assertStatus(200);

        $car->refresh();
        $this->assertCount(2, $car->features);
        $this->assertTrue($car->features->contains($feature1->id));
        $this->assertTrue($car->features->contains($feature2->id));

        $this->putJson("/api/cars/{$car->id}", [
            'features' => [$feature3->id, $feature4->id]
        ])->assertStatus(200);

        $car->refresh();
        $this->assertCount(2, $car->features);
        $this->assertFalse($car->features->contains($feature1->id));
        $this->assertFalse($car->features->contains($feature2->id));
        $this->assertTrue($car->features->contains($feature3->id));
        $this->assertTrue($car->features->contains($feature4->id));
    }

    public function test_car_update_features_removes_all_features_when_empty()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $feature1 = Feature::factory()->create();
        $feature2 = Feature::factory()->create();

        $this->putJson("/api/cars/{$car->id}", [
            'features' => [$feature1->id, $feature2->id]
        ])->assertStatus(200);

        $car->refresh();
        $this->assertCount(2, $car->features);

        // Now remove all features
        $this->putJson("/api/cars/{$car->id}", [
            'features' => []
        ])->assertStatus(200);

        $car->refresh();
        $this->assertCount(0, $car->features);
    }

    public function test_car_update_features_validation_ignores_non_features_fields()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $feature1 = Feature::factory()->create();

        $response = $this->putJson("/api/cars/{$car->id}", [
            'features' => [$feature1->id],
            'title' => 'Updated Title',
            'price_per_day' => 200.00
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);

        $car->refresh();
        $this->assertEquals('Updated Title', $car->title);
        $this->assertEquals(200.00, $car->price_per_day);
        $this->assertCount(1, $car->features);
        $this->assertTrue($car->features->contains($feature1->id));
    }

    public function test_car_update_features_event_is_dispatched()
    {
        Event::fake([
            CarUpdated::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $feature1 = Feature::factory()->create();

        $this->putJson("/api/cars/{$car->id}", [
            'features' => [$feature1->id]
        ])->assertStatus(200);

        Event::assertDispatched(CarUpdated::class, function ($event) use ($car) {
            return $event->carId === $car->id;
        });
    }

    public function test_car_update_features_event_is_not_dispatched_on_validation_failure()
    {
        Event::fake([
            CarUpdated::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $this->putJson("/api/cars/{$car->id}", [
            'features' => ['invalid-feature-id']
        ])->assertStatus(422);

        Event::assertNotDispatched(CarUpdated::class);
    }

    public function test_car_update_validates_images_structure()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => 'not-an-array'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images']);
    }

    public function test_car_update_validates_image_sections_structure()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => 'not-an-array',
                'interior_dashboard' => 'not-an-array'
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view', 'images.interior_dashboard']);
    }

    public function test_car_update_validates_image_file_name_is_required()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_extension' => 'jpg',
                        'size' => 1024,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view.0.file_name']);
    }

    public function test_car_update_validates_image_file_extension_is_required()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'size' => 1024,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view.0.file_extension']);
    }

    public function test_car_update_validates_image_file_extension_allowed_values()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.invalid',
                        'file_extension' => 'invalid',
                        'size' => 1024,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view.0.file_extension']);
    }

    public function test_car_update_validates_image_size_is_required()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'jpg',
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view.0.size']);
    }

    public function test_car_update_validates_image_size_minimum_value()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'jpg',
                        'size' => 0,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view.0.size']);
    }

    public function test_car_update_validates_image_type_is_required()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1024,
                        'section' => 'front_view'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view.0.type']);
    }

    public function test_car_update_validates_image_type_allowed_values()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1024,
                        'type' => 'image/invalid',
                        'section' => 'front_view'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view.0.type']);
    }

    public function test_car_update_validates_image_section_is_required()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1024,
                        'type' => 'image/jpeg'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view.0.section']);
    }

    public function test_car_update_validates_image_section_allowed_values()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1024,
                        'type' => 'image/jpeg',
                        'section' => 'invalid_section'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['images.front_view.0.section']);
    }

    public function test_car_update_accepts_valid_image_data()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test-image-1.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1024000,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ],
                    [
                        'file_name' => 'test-image-2.jpg',
                        'file_extension' => 'jpg',
                        'size' => 2048000,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ]
                ],
                'interior_dashboard' => [
                    [
                        'file_name' => 'test-image-3.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1536000,
                        'type' => 'image/jpeg',
                        'section' => 'interior_dashboard'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);
    }

    public function test_car_update_validates_all_image_sections()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [[
                    'file_name' => 'test.jpg',
                    'file_extension' => 'jpg',
                    'size' => 1024000,
                    'type' => 'image/jpeg',
                    'section' => 'front_view'
                ]],
                'interior_dashboard' => [[
                    'file_name' => 'test.jpg',
                    'file_extension' => 'jpg',
                    'size' => 1024000,
                    'type' => 'image/jpeg',
                    'section' => 'interior_dashboard'
                ]],
                'main_seats' => [[
                    'file_name' => 'test.jpg',
                    'file_extension' => 'jpg',
                    'size' => 1024000,
                    'type' => 'image/jpeg',
                    'section' => 'main_seats'
                ]],
                'back_seats_trunk' => [[
                    'file_name' => 'test.jpg',
                    'file_extension' => 'jpg',
                    'size' => 1024000,
                    'type' => 'image/jpeg',
                    'section' => 'back_seats_trunk'
                ]]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);
    }

    public function test_car_update_validates_additional_images_section()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'additional' => [
                    [
                        'file_name' => 'additional-1.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1024000,
                        'type' => 'image/jpeg',
                        'section' => 'additional'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);
    }

    public function test_car_update_images_with_other_fields()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1024000,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ]
                ]
            ]
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
    }

    public function test_car_update_images_event_is_dispatched()
    {
        Event::fake([
            CarUpdated::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1024000,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ]
                ]
            ]
        ])->assertStatus(200);

        Event::assertDispatched(CarUpdated::class, function ($event) use ($car) {
            return $event->carId === $car->id;
        });
    }

    public function test_car_update_images_event_is_not_dispatched_on_validation_failure()
    {
        Event::fake([
            CarUpdated::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'invalid',
                        'size' => 1024000,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ]
                ]
            ]
        ])->assertStatus(422);

        Event::assertNotDispatched(CarUpdated::class);
    }

    public function test_car_update_accepts_multiple_image_formats()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/cars/{$car->id}", [
            'images' => [
                'front_view' => [
                    [
                        'file_name' => 'test.jpg',
                        'file_extension' => 'jpg',
                        'size' => 1024000,
                        'type' => 'image/jpeg',
                        'section' => 'front_view'
                    ],
                    [
                        'file_name' => 'test.png',
                        'file_extension' => 'png',
                        'size' => 2048000,
                        'type' => 'image/png',
                        'section' => 'front_view'
                    ],
                    [
                        'file_name' => 'test.webp',
                        'file_extension' => 'webp',
                        'size' => 1536000,
                        'type' => 'image/webp',
                        'section' => 'front_view'
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car updated successfully'
        ]);
    }

    public function test_car_submission_successfully_submits_draft_car()
    {
        Event::fake([
            CarSubmitted::class
        ]);

        $user = $this->signIn();
        $car = $this->createValidCarForSubmission($user);

        $response = $this->putJson("/api/cars/{$car->id}/submission");

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Car Submitted',
            'car' => $car->id
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'status' => CarStatus::PENDING_APPROVAL->value
        ]);

        Event::assertDispatched(CarSubmitted::class, function ($event) use ($car) {
            return $event->carId === $car->id;
        });
    }

    public function test_car_submission_fails_when_car_is_not_in_draft_status()
    {
        $this->withoutExceptionHandling();

        Event::fake([
            CarSubmitted::class
        ]);

        $user = $this->signIn();

        $car = Car::factory()->create([
            'user_id' => $user->id,
            'status' => CarStatus::PENDING_APPROVAL
        ]);

        $response = $this->putJson("/api/cars/{$car->id}/submission");

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Car is not in a draft state to be submitted',
            'car' => $car->id
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'status' => CarStatus::PENDING_APPROVAL->value
        ]);

        Event::assertNotDispatched(CarSubmitted::class);
    }

    public function test_car_submission_fails_when_car_validation_fails()
    {
        Event::fake([
            CarSubmitted::class
        ]);

        $user = $this->signIn();

        $car = Car::factory()->create([
            'user_id' => $user->id,
            'status' => CarStatus::DRAFT
        ]);

        $response = $this->putJson("/api/cars/{$car->id}/submission");

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'id',
                'valid'
            ]
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'status' => CarStatus::DRAFT->value
        ]);

        Event::assertNotDispatched(CarSubmitted::class);
    }

    public function test_car_submission_fails_when_car_has_media_validation_errors()
    {
        Event::fake([
            CarSubmitted::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'status' => CarStatus::DRAFT,
            'title' => 'Valid Title',
            'description' => 'Valid Description'
        ]);

        $response = $this->putJson("/api/cars/{$car->id}/submission");

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'id',
                'media',
                'valid'
            ]
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'status' => CarStatus::DRAFT->value
        ]);

        Event::assertNotDispatched(CarSubmitted::class);
    }

    public function test_car_submission_fails_when_car_has_features_validation_errors()
    {
        Event::fake([
            CarSubmitted::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'status' => CarStatus::DRAFT,
            'title' => 'Valid Title',
            'description' => 'Valid Description'
        ]);

        Feature::factory()->count(2)->create();

        $response = $this->putJson("/api/cars/{$car->id}/submission");

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'id',
                'features',
                'valid'
            ]
        ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'status' => CarStatus::DRAFT->value
        ]);

        Event::assertNotDispatched(CarSubmitted::class);
    }

    public function test_car_submission_response_structure()
    {
        Event::fake([
            CarSubmitted::class
        ]);

        $user = $this->signIn();
        $car = $this->createValidCarForSubmission($user);

        $response = $this->putJson("/api/cars/{$car->id}/submission");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'car'
        ]);
    }

    public function test_car_submission_validation_failed_response_structure()
    {
        Event::fake([
            CarSubmitted::class
        ]);

        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'status' => CarStatus::DRAFT
        ]);

        $response = $this->putJson("/api/cars/{$car->id}/submission");

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'id',
                'valid'
            ]
        ]);
    }

    public function test_car_submission_requires_authentication()
    {
        $car = Car::factory()->create([
            'status' => CarStatus::DRAFT
        ]);

        $response = $this->putJson("/api/cars/{$car->id}/submission");

        $response->assertStatus(401);
    }
}
