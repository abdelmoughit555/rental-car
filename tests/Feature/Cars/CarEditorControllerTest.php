<?php

namespace Tests\Feature\Cars;

use App\Models\Cars\Car;
use App\Models\Brands\Make;
use App\Models\Brands\CarModel;
use App\Models\CarFeatures\Feature;
use App\Models\CarFeatures\FeatureCategory;
use App\Models\Cars\FuelType;
use App\Models\Cars\Gearbox;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarEditorControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create required related models
        Make::factory()->create();
        CarModel::factory()->create();
        FuelType::factory()->create();
        Gearbox::factory()->create();
    }

    public function test_information_page_renders_with_valid_car()
    {
        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Car',
            'description' => 'Test Description',
            'year' => 2020,
            'engine_cc' => 2000,
            'power_hp' => 150,
            'doors' => 4,
            'seats' => 5,
            'mileage_km' => 50000,
            'registration_number' => 'ABC123',
            'brand_id' => Make::first()->id,
            'car_model_id' => CarModel::first()->id,
            'fuel_type_id' => FuelType::first()->id,
            'gearbox_id' => Gearbox::first()->id,
        ]);

        $response = $this->get("/cars/{$car->id}/information");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Information')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', true)
                ->where('validation.information', [])
        );
    }

    public function test_information_page_shows_validation_errors_for_incomplete_car()
    {
        $user = $this->signIn();
        // Create a car with the factory first, then update it to remove required fields
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        // Update the car to remove required fields for validation testing
        $car->update([
            'title' => '',
            'description' => '',
            'year' => null,
            'engine_cc' => null,
            'power_hp' => null,
            'doors' => null,
            'seats' => null,
            'mileage_km' => null,
            'registration_number' => '',
            'brand_id' => null,
            'car_model_id' => null,
            'fuel_type_id' => null,
            'gearbox_id' => null,
        ]);

        $response = $this->get("/cars/{$car->id}/information");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Information')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', false)
                ->where('validation.information.title', ['Title is required'])
                ->where('validation.information.description', ['Description is required'])
                ->where('validation.information.year', ['Year is required'])
                ->where('validation.information.engine_cc', ['Engine CC is required'])
                ->where('validation.information.power_hp', ['Power HP is required'])
                ->where('validation.information.doors', ['Doors is required'])
                ->where('validation.information.seats', ['Seats is required'])
                ->where('validation.information.mileage_km', ['Mileage is required'])
                ->where('validation.information.registration_number', ['Registration number is required'])
                ->where('validation.information.brand_id', ['Brand is required'])
                ->where('validation.information.car_model_id', ['Car model is required'])
                ->where('validation.information.fuel_type_id', ['Fuel type is required'])
                ->where('validation.information.gearbox_id', ['Gearbox is required'])
        );
    }

    public function test_information_page_shows_partial_validation_errors()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        // Update specific fields to be invalid for partial validation testing
        $car->update([
            'description' => '',
            'engine_cc' => null,
        ]);

        $response = $this->get("/cars/{$car->id}/information");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Information')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', false)
                ->where('validation.information.description', ['Description is required'])
                ->where('validation.information.engine_cc', ['Engine CC is required'])
                ->missing('validation.information.title')
                ->missing('validation.information.year')
                ->missing('validation.information.power_hp')
        );
    }

    public function test_availability_page_renders_with_valid_car()
    {
        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'available_from' => '2025-08-21',
            'available_to' => '2025-10-10',
        ]);

        $response = $this->get("/cars/{$car->id}/availability");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Availability')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', true)
                ->where('validation.availability', [])
        );
    }

    public function test_availability_page_shows_validation_errors_for_missing_dates()
    {
        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'available_from' => null,
            'available_to' => null,
        ]);

        $response = $this->get("/cars/{$car->id}/availability");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Availability')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', false)
                ->where('validation.availability.available_from', ['Available From is required'])
                ->where('validation.availability.available_to', ['Available To is required'])
        );
    }

    public function test_availability_page_shows_partial_validation_errors()
    {
        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'available_from' => '2025-08-21', // Valid
            'available_to' => null, // Invalid
        ]);

        $response = $this->get("/cars/{$car->id}/availability");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Availability')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', false)
                ->where('validation.availability.available_to', ['Available To is required'])
                ->missing('validation.availability.available_from')
        );
    }

    public function test_unauthenticated_user_cannot_access_information_page()
    {
        $car = Car::factory()->create();

        $response = $this->get("/cars/{$car->id}/information");

        $response->assertStatus(302); // Redirects to login
    }

    public function test_unauthenticated_user_cannot_access_availability_page()
    {
        $car = Car::factory()->create();

        $response = $this->get("/cars/{$car->id}/availability");

        $response->assertStatus(302); // Redirects to login
    }

    public function test_user_can_access_other_users_car_information()
    {
        $user1 = $this->signIn();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user2->id]);

        $response = $this->get("/cars/{$car->id}/information");

        $response->assertStatus(200); // No authorization middleware, so access is allowed
    }

    public function test_user_can_access_other_users_car_availability()
    {
        $user1 = $this->signIn();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user2->id]);

        $response = $this->get("/cars/{$car->id}/availability");

        $response->assertStatus(200); // No authorization middleware, so access is allowed
    }

    public function test_car_resource_is_properly_formatted()
    {
        $user = $this->signIn();
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Car',
            'description' => 'Test Description',
        ]);

        $response = $this->get("/cars/{$car->id}/information");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Information')
                ->has('car')
                ->where('car.id', $car->id)
                ->where('car.title', 'Test Car')
                ->where('car.description', 'Test Description')
        );
    }

    public function test_validation_structure_is_correct()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        // Update specific fields to be invalid for validation testing
        $car->update([
            'title' => '', // Invalid
            'available_from' => null, // Invalid
        ]);

        $response = $this->get("/cars/{$car->id}/information");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Information')
                ->has('validation')
                ->where('validation.id', $car->id)
                ->where('validation.valid', false)
                ->has('validation.information')
        );
    }

    public function test_features_page_renders_with_valid_car()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->get("/cars/{$car->id}/features");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Features')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', true)
                ->where('validation.features', [])
        );
    }

    public function test_features_page_loads_car_features_relationship()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $feature1 = Feature::factory()->create();
        $feature2 = Feature::factory()->create();
        
        $car->features()->attach([$feature1->id, $feature2->id]);

        $response = $this->get("/cars/{$car->id}/features");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Features')
                ->has('car')
                ->where('car.features', [$feature1->id, $feature2->id])
        );
    }

    public function test_features_page_shows_validation_errors_for_missing_features()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        // Create feature categories with features
        $category1 = FeatureCategory::factory()->create(['name' => 'Safety']);
        $category2 = FeatureCategory::factory()->create(['name' => 'Comfort']);
        
        $feature1 = Feature::factory()->create(['feature_category_id' => $category1->id]);
        $feature2 = Feature::factory()->create(['feature_category_id' => $category2->id]);
        
        $car->features()->detach();

        $response = $this->get("/cars/{$car->id}/features");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Features')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', false)
                ->has('validation.features')
        );
    }

    public function test_features_page_shows_category_specific_validation_errors()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $safetyCategory = FeatureCategory::factory()->create(['name' => 'Safety']);
        $comfortCategory = FeatureCategory::factory()->create(['name' => 'Comfort']);
        
        $safetyFeature = Feature::factory()->create(['feature_category_id' => $safetyCategory->id]);
        $comfortFeature = Feature::factory()->create(['feature_category_id' => $comfortCategory->id]);
        
        $car->features()->attach([$safetyFeature->id]);

        $response = $this->get("/cars/{$car->id}/features");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Features')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', false)
                ->has('validation.features')
        );
    }

    public function test_features_page_unauthenticated_user_cannot_access()
    {
        $car = Car::factory()->create();

        $response = $this->get("/cars/{$car->id}/features");

        $response->assertStatus(302);
    }

    public function test_features_page_car_resource_includes_features()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $feature1 = Feature::factory()->create();
        $feature2 = Feature::factory()->create();
        
        $car->features()->attach([$feature1->id, $feature2->id]);

        $response = $this->get("/cars/{$car->id}/features");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Features')
                ->has('car')
                ->where('car.id', $car->id)
                ->where('car.features', [$feature1->id, $feature2->id])
        );
    }

    public function test_features_page_validation_structure_for_features()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $category = FeatureCategory::factory()->create(['name' => 'Safety']);
        $feature = Feature::factory()->create(['feature_category_id' => $category->id]);
        
        $car->features()->detach();

        $response = $this->get("/cars/{$car->id}/features");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Features')
                ->has('validation')
                ->where('validation.id', $car->id)
                ->where('validation.valid', false)
                ->has('validation.features')
        );
    }
}
