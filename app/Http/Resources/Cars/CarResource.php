<?php

namespace App\Http\Resources\Cars;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
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
        ];
    }
}
