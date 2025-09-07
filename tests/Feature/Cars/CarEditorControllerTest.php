<?php

namespace Tests\Feature\Cars;

use App\Models\Cars\Car;
use App\Models\Brands\Make;
use App\Models\Brands\CarModel;
use App\Models\CarFeatures\Feature;
use App\Models\CarFeatures\FeatureCategory;
use App\Models\Cars\FuelType;
use App\Models\Cars\Gearbox;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CarEditorControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('local');
        Storage::fake('s3');
        
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

        $car = Car::factory()->create(['user_id' => $user->id]);
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
                ->has('car.features')
        );
    }

    public function test_features_page_shows_validation_errors_for_missing_features()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        
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
                ->has('car.features')
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

    public function test_images_page_renders_with_valid_car()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $requiredSections = ['front_view', 'interior_dashboard', 'main_seats', 'back_seats_trunk'];
        
        foreach ($requiredSections as $section) {
            for ($i = 1; $i <= 3; $i++) {
                $car->appendMedia([
                    'name' => "test-{$section}-{$i}",
                    'extension' => 'jpg',
                    'directory' => "car_images/{$section}",
                    'type' => 'uploaded',
                    'disk' => 's3'
                ]);
            }
        }

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Images')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', true)
                ->where('validation.media', [])
        );
    }

    public function test_images_page_loads_car_media_relationship()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $media1 = Media::factory()
            ->forCar($car)
            ->withName('test-image-1')
            ->withExtension('jpg')
            ->frontView()
            ->create();
        
        $media2 = Media::factory()
            ->forCar($car)
            ->withName('test-image-2')
            ->withExtension('png')
            ->interiorDashboard()
            ->create();

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Images')
                ->has('car')
                ->has('car.media')
                ->where('car.media.car_images/front_view.0.id', $media1->id)
                ->where('car.media.car_images/front_view.0.name', 'test-image-1')
                ->where('car.media.car_images/front_view.0.extension', 'jpg')
                ->where('car.media.car_images/interior_dashboard.0.id', $media2->id)
                ->where('car.media.car_images/interior_dashboard.0.name', 'test-image-2')
                ->where('car.media.car_images/interior_dashboard.0.extension', 'png')
        );
    }

    public function test_images_page_shows_validation_errors_for_missing_images()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $car->media()->delete();

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Images')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', false)
                ->has('validation.media')
        );
    }

    public function test_images_page_shows_partial_validation_errors()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $car->appendMedia([
            'name' => 'test-image',
            'extension' => 'jpg',
            'directory' => 'car_images/front_view',
            'type' => 'uploaded',
            'disk' => 's3'
        ]);

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Images')
                ->has('car')
                ->has('validation')
                ->where('validation.valid', false)
                ->has('validation.media')
        );
    }

    public function test_images_page_unauthenticated_user_cannot_access()
    {
        $car = Car::factory()->create();

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(302);
    }

    public function test_images_page_car_resource_includes_media()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $media = $car->appendMedia([
            'name' => 'test-image',
            'extension' => 'jpg',
            'directory' => 'car_images/front_view',
            'type' => 'uploaded',
            'disk' => 's3'
        ]);

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Images')
                ->has('car')
                ->where('car.id', $car->id)
                ->has('car.media')
                ->where('car.media.car_images/front_view.0.id', $media->id)
        );
    }

    public function test_images_page_validation_structure_for_images()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $car->media()->delete();

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Images')
                ->has('validation')
                ->where('validation.id', $car->id)
                ->where('validation.valid', false)
                ->has('validation.media')
        );
    }

    public function test_images_page_media_grouped_by_directory()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $frontViewMedia1 = $car->appendMedia([
            'name' => 'front-1',
            'extension' => 'jpg',
            'directory' => 'car_images/front_view',
            'type' => 'uploaded',
            'disk' => 's3'
        ]);
        
        $frontViewMedia2 = $car->appendMedia([
            'name' => 'front-2',
            'extension' => 'jpg',
            'directory' => 'car_images/front_view',
            'type' => 'uploaded',
            'disk' => 's3'
        ]);
        
        $interiorMedia = $car->appendMedia([
            'name' => 'interior-1',
            'extension' => 'png',
            'directory' => 'car_images/interior_dashboard',
            'type' => 'uploaded',
            'disk' => 's3'
        ]);

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Images')
                ->has('car.media')
                ->has('car.media.car_images/front_view')
                ->has('car.media.car_images/interior_dashboard')
                ->where('car.media.car_images/front_view.0.id', $frontViewMedia1->id)
                ->where('car.media.car_images/front_view.1.id', $frontViewMedia2->id)
                ->where('car.media.car_images/interior_dashboard.0.id', $interiorMedia->id)
        );
    }

    public function test_images_page_media_resource_structure()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $media = Media::factory()
            ->forCar($car)
            ->withName('test-image')
            ->withExtension('jpg')
            ->frontView()
            ->state([
                'size' => 1024000
            ])
            ->create();

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Images')
                ->has('car.media.car_images/front_view.0')
                ->where('car.media.car_images/front_view.0.id', $media->id)
                ->where('car.media.car_images/front_view.0.name', 'test-image')
                ->where('car.media.car_images/front_view.0.extension', 'jpg')
                ->where('car.media.car_images/front_view.0.directory', 'car_images/front_view')
                ->where('car.media.car_images/front_view.0.size', 1024000)
                ->where('car.media.car_images/front_view.0.type', 'image/jpg')
                ->has('car.media.car_images/front_view.0.url')
                ->has('car.media.car_images/front_view.0.created_at')
        );
    }

    public function test_images_page_handles_empty_media_collection()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);
        
        $car->media()->delete();

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('Cars/Edit/Images')
                ->has('car')
                ->where('car.media', [])
                ->has('validation')
                ->where('validation.valid', false)
                ->has('validation.media')
        );
    }

    public function test_images_page_user_can_access_other_users_car()
    {
        $user1 = $this->signIn();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['user_id' => $user2->id]);

        $response = $this->get("/cars/{$car->id}/images");

        $response->assertStatus(200);
    }

    public function test_confirmation_page_renders_with_valid_car()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $response = $this->get("/cars/{$car->id}/confirmation");

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => 
            $page->component('Cars/Edit/Confirmation')
                ->has('car')
                ->has('validation')
                ->has('validation.valid')
                ->has('validation.pricing')
                ->has('validation.features')
                ->has('validation.media')
                ->has('validation.information')
                ->has('validation.availability')
        );
    }

    public function test_confirmation_page_loads_car_relationships()
    {
        $user = $this->signIn();
        
        $make = Make::factory()->create();
        $carModel = CarModel::factory()->create(['make_id' => $make->id]);
        $fuelType = FuelType::factory()->create();
        $gearbox = Gearbox::factory()->create();
        
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'car_model_id' => $carModel->id,
            'fuel_type_id' => $fuelType->id,
            'gearbox_id' => $gearbox->id,
        ]);

        $media1 = Media::factory()->create([
            'model_id' => $car->id,
            'model_type' => Car::class,
            'directory' => 'car_images/front_view',
            'name' => 'test-image-1',
            'extension' => 'jpg'
        ]);
        
        $media2 = Media::factory()->create([
            'model_id' => $car->id,
            'model_type' => Car::class,
            'directory' => 'car_images/interior_dashboard',
            'name' => 'test-image-2',
            'extension' => 'jpg'
        ]);

        $response = $this->get("/cars/{$car->id}/confirmation");

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => 
            $page->component('Cars/Edit/Confirmation')
                ->has('car.id')
                ->has('car.title')
                ->has('car.car_model')
                ->has('car.fuel_type')
                ->has('car.gearbox')
                ->has('car.features')
                ->has('car.media')
        );
    }

    public function test_confirmation_page_shows_validation_errors()
    {
        $user = $this->signIn();
        
        $car = Car::factory()->create([
            'user_id' => $user->id,
            'price_per_day' => null,
        ]);

        $response = $this->get("/cars/{$car->id}/confirmation");

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => 
            $page->component('Cars/Edit/Confirmation')
                ->has('validation.valid')
                ->where('validation.valid', false)
                ->has('validation.pricing')
        );
    }

    public function test_confirmation_page_shows_images_when_media_exists()
    {
        $user = $this->signIn();
        $car = Car::factory()->create(['user_id' => $user->id]);

        $media1 = Media::factory()->create([
            'model_id' => $car->id,
            'model_type' => Car::class,
            'directory' => 'car_images/front_view',
            'name' => 'test-image-1',
            'extension' => 'jpg'
        ]);
        
        $media2 = Media::factory()->create([
            'model_id' => $car->id,
            'model_type' => Car::class,
            'directory' => 'car_images/interior_dashboard',
            'name' => 'test-image-2',
            'extension' => 'jpg'
        ]);

        $response = $this->get("/cars/{$car->id}/confirmation");

        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => 
            $page->component('Cars/Edit/Confirmation')
                ->has('car.media')
                ->where('car.media.car_images/front_view', function ($mediaGroup) {
                    return count($mediaGroup) === 1;
                })
                ->where('car.media.car_images/interior_dashboard', function ($mediaGroup) {
                    return count($mediaGroup) === 1;
                })
        );
    }
}
