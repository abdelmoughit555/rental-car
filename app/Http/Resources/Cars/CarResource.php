<?php

namespace App\Http\Resources\Cars;

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
            'features' => $this->whenLoaded('features', function () {
                return $this->features->pluck('id');
            }),
            'media' => $this->whenLoaded('media', function () {
                return $this->media->groupBy('directory')->map(function ($mediaGroup) {
                    return MediaResource::collection($mediaGroup);
                });
            }),
        ];
    }
}
