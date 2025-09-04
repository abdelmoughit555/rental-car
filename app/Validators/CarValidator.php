<?php

namespace App\Validators;

use App\Contracts\ModelValidator;
use App\Models\CarFeatures\FeatureCategory;
use App\Models\Cars\Car;
use Illuminate\Database\Eloquent\Model;

class CarValidator implements ModelValidator
{
    protected bool $informationValid = true;
    protected bool $availabilityValid = true;
    protected bool $pricingValid = true;
    protected bool $featuresValid = true;

    public function validate(Model $car)
    {
        $information = $this->informationValidation($car);

        $availability = $this->availabilityValidation($car);

        return [
            'id' => $car->id,
            'valid' => $this->informationValid,
            'information' => $information['information'],
            'availability' => $availability['availability'],
        ];
    }

    public function informationValidation(Car $car)
    {
        $errors = [];

        if(!$car->title) {
            $errors['title'][] = 'Title is required';
        }

        if(!$car->description) {
            $errors['description'][] = 'Description is required';
        }

        if(!$car->year) {
            $errors['year'][] = 'Year is required';
        }

        if(!$car->engine_cc) {
            $errors['engine_cc'][] = 'Engine CC is required';
        }

        if(!$car->power_hp) {
            $errors['power_hp'][] = 'Power HP is required';
        }

        if(!$car->doors) {
            $errors['doors'][] = 'Doors is required';
        }

        if(!$car->seats) {
            $errors['seats'][] = 'Seats is required';
        }

        if(!$car->mileage_km) {
            $errors['mileage_km'][] = 'Mileage is required';
        }

        if(!$car->registration_number) {
            $errors['registration_number'][] = 'Registration number is required';
        }

        if(!$car->brand_id) {
            $errors['brand_id'][] = 'Brand is required';
        }

        if(!$car->car_model_id) {
            $errors['car_model_id'][] = 'Car model is required';
        }

        if(!$car->fuel_type_id) {
            $errors['fuel_type_id'][] = 'Fuel type is required';
        }

        if(!$car->gearbox_id) {
            $errors['gearbox_id'][] = 'Gearbox is required';
        }

        if(count($errors) > 0) {
            $this->informationValid = false;
        }

        return [
            'id' => $car->id,
            'information' => $errors,
            'valid' => count($errors) === 0
        ];
    }

    public function availabilityValidation(Car $car)
    {
        $errors = [];

        if(!$car->available_from) {
            $errors['available_from'][] = 'Available From is required';
        }

        if(!$car->available_to) {
            $errors['available_to'][] = 'Available To is required';
        }
        
        if(count($errors) > 0) {
            $this->availabilityValid = false;
        }

        return [
            'id' => $car->id,
            'availability' => $errors,
            'valid' => count($errors) === 0
        ];
    }

    public function pricingValidation(Car $car)
    {
        $errors = [];

        if(!$car->price_per_day) {
            $errors['price_per_day'][] = 'Price per day is required';
        }

        if(count($errors) > 0) {
            $this->pricingValid = false;
        }

        return [
            'id' => $car->id,
            'price' => $errors,
            'valid' => count($errors) === 0
        ];
    }

    public function featuresValidation(Car $car)
    {
        $errors = [];

        if(count($errors) > 0) {
            $this->featuresValid = false;
        }

        // at least each feature category should have at least one feature selected
        $featureCategories = FeatureCategory::with('features')->get();

        foreach($featureCategories as $category) {
            if(count($category->features) > 0) {
                // Get all feature IDs for this category
                $categoryFeatureIds = $category->features->pluck('id')->toArray();
                
                // Check if car has at least one feature from this category
                $hasFeatureFromCategory = $car->features->whereIn('id', $categoryFeatureIds)->count() > 0;
                
                if (!$hasFeatureFromCategory) {
                    $errors[$category->id] = 'At least one feature should be selected for ' . $category->name;
                }
            }
        }

        return [    
            'id' => $car->id,
            'features' => $errors,
            'valid' => count($errors) === 0
        ];
    }

    public function mediaValidation(Car $car)
    {
        $errors = [];

        // Check if car has media for each required section
        $requiredSections = ['front_view', 'interior_dashboard', 'main_seats', 'back_seats_trunk'];
        
        foreach ($requiredSections as $section) {
            $directory = "car_images/{$section}";
            $sectionMedia = $car->media()->where('directory', $directory)->get();
            
            if ($sectionMedia->count() < 3) {
                $errors[$section] = "At least 3 images are required for {$section} section";
            }
        }

        return [
            'id' => $car->id,
            'media' => $errors,
            'valid' => count($errors) === 0
        ];
    }
}
