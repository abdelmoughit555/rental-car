<?php

namespace App\Http\Resources\Cars;

use App\Http\Resources\Features\FeatureResource;
use App\Http\Resources\Brands\BrandResource;
use App\Http\Resources\Brands\CarModelResource;
use App\Http\Resources\Cars\FuelTypeResource;
use App\Http\Resources\Cars\GearboxResource;
use App\Http\Resources\Media\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'brand_id' => $this->brand_id,
            'car_model_id' => $this->car_model_id,
            'registration_number' => $this->registration_number,
            'year' => $this->year,
            'engine_cc' => $this->engine_cc,
            'power_hp' => $this->power_hp,
            'doors' => $this->doors,
            'seats' => $this->seats,
            'mileage_km' => $this->mileage_km,
            'fuel_type_id' => $this->fuel_type_id,
            'gearbox_id' => $this->gearbox_id,
            'available_from' => $this->available_from,
            'available_to' => $this->available_to,
            'price_per_day' => $this->price_per_day,
            'images_count' => $this->when(isset($this->images_count), fn () => $this->images_count),
            'brand' => BrandResource::make($this->whenLoaded('brand')),
            'car_model' => CarModelResource::make($this->whenLoaded('carModel')),
            'fuel_type' => FuelTypeResource::make($this->whenLoaded('fuelType')),
            'gearbox' => GearboxResource::make($this->whenLoaded('gearbox')),
            'features' => FeatureResource::collection($this->whenLoaded('features')),
            'front_view_image' => $this->whenLoaded('frontViewImage', fn () => new MediaResource($this->frontViewImage)),
        ];
    }
}
