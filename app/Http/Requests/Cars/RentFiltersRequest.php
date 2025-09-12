<?php

namespace App\Http\Requests\Cars;

use Illuminate\Foundation\Http\FormRequest;

class RentFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:255'],
            'brand_id' => ['nullable', 'integer'],
            'car_model_id' => ['nullable', 'integer'],
            'car_model_ids' => ['nullable', 'array'],
            'car_model_ids.*' => ['integer'],
            'fuel_type_id' => ['nullable', 'integer'],
            'gearbox_id' => ['nullable', 'integer'],
            'seats' => ['nullable', 'integer', 'min:1', 'max:10'],
            'doors' => ['nullable', 'integer', 'min:1', 'max:10'],
            'mileage_min' => ['nullable', 'integer', 'min:0', 'max:200000'],
            'mileage_max' => ['nullable', 'integer', 'min:0', 'max:200000'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
            'available_from' => ['nullable', 'date', 'date_format:Y-m-d'],
            'available_to' => ['nullable', 'date', 'date_format:Y-m-d'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'sort' => ['nullable', 'string', 'in:price_asc,price_desc,latest'],
        ];
    }
}


