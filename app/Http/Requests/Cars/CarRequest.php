<?php

namespace App\Http\Requests\Cars;

use App\Rules\AtLeastDaysAfter;
use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'max:1000'],
            'brand_id' => ['sometimes', 'required', 'exists:makes,id'],
            'car_model_id' => ['sometimes', 'required', 'exists:car_models,id'],
            'registration_number' => ['sometimes', 'required', 'string', 'max:255'],
            'year' => ['sometimes', 'required', 'integer', 'min:1900', 'max:2025'],
            'engine_cc' => ['sometimes', 'required', 'integer', 'min:100', 'max:10000'],
            'power_hp' => ['sometimes', 'required', 'integer', 'min:100', 'max:10000'],
            'gearbox_id' => ['sometimes', 'required', 'exists:gearboxes,id'],
            'fuel_type_id' => ['sometimes', 'required', 'exists:fuel_types,id'],
            'doors' => ['sometimes', 'required', 'integer', 'min:1', 'max:10'],
            'seats' => ['sometimes', 'required', 'integer', 'min:1', 'max:10'],
            'mileage_km' => ['sometimes', 'required', 'integer', 'min:0', 'max:1000000'],
            'available_from' => ['sometimes','required','date','date_format:Y-m-d','after_or_equal:today'],
            'available_to'   => ['sometimes','required','date','date_format:Y-m-d','after:available_from', new AtLeastDaysAfter('available_from', 14)],
            'price_per_day' => ['sometimes', 'required', 'numeric', 'min:0.01', 'max:999999.99'],
            'features' => ['sometimes', 'array'],
            'features.*' => ['integer', 'exists:features,id'],
            'images' => ['sometimes', 'required', 'array'],
            'images.front_view' => ['sometimes', 'required', 'array', 'min:1'],
            'images.front_view.*.file_name' => ['required', 'string', 'max:255'],
            'images.front_view.*.file_extension' => ['required', 'string', 'in:jpg,jpeg,png,webp'],
            'images.front_view.*.size' => ['required', 'integer', 'min:1'],
            'images.front_view.*.type' => ['required', 'string', 'in:image/jpeg,image/png,image/webp,image/jpg'],
            'images.front_view.*.section' => ['required', 'string', 'in:front_view'],
            
            'images.interior_dashboard' => ['sometimes', 'required', 'array', 'min:1'],
            'images.interior_dashboard.*.file_name' => ['required', 'string', 'max:255'],
            'images.interior_dashboard.*.file_extension' => ['required', 'string', 'in:jpg,jpeg,png,webp'],
            'images.interior_dashboard.*.size' => ['required', 'integer', 'min:1'],
            'images.interior_dashboard.*.type' => ['required', 'string', 'in:image/jpeg,image/png,image/webp,image/jpg'],
            'images.interior_dashboard.*.section' => ['required', 'string', 'in:interior_dashboard'],
            
            'images.main_seats' => ['sometimes', 'required', 'array', 'min:1'],
            'images.main_seats.*.file_name' => ['required', 'string', 'max:255'],
            'images.main_seats.*.file_extension' => ['required', 'string', 'in:jpg,jpeg,png,webp'],
            'images.main_seats.*.size' => ['required', 'integer', 'min:1'],
            'images.main_seats.*.type' => ['required', 'string', 'in:image/jpeg,image/png,image/webp,image/jpg'],
            'images.main_seats.*.section' => ['required', 'string', 'in:main_seats'],
            
            'images.back_seats_trunk' => ['sometimes', 'required', 'array', 'min:1'],
            'images.back_seats_trunk.*.file_name' => ['required', 'string', 'max:255'],
            'images.back_seats_trunk.*.file_extension' => ['required', 'string', 'in:jpg,jpeg,png,webp'],
            'images.back_seats_trunk.*.size' => ['required', 'integer', 'min:1'],
            'images.back_seats_trunk.*.type' => ['required', 'string', 'in:image/jpeg,image/png,image/webp,image/jpg'],
            'images.back_seats_trunk.*.section' => ['required', 'string', 'in:back_seats_trunk']
        ];
    }

    public function messages(): array
    {
        return [
            'images.front_view.required' => 'The Front ¾ View section is required.',
            'images.front_view.min' => 'The Front ¾ View section must have at least 3 images.',
            'images.interior_dashboard.required' => 'The Interior Dashboard section is required.',
            'images.interior_dashboard.min' => 'The Interior Dashboard section must have at least 3 images.',
            'images.main_seats.required' => 'The Main Seats section is required.',
            'images.main_seats.min' => 'The Main Seats section must have at least 3 images.',
            'images.back_seats_trunk.required' => 'The Back Seats or Trunk section is required.',
            'images.back_seats_trunk.min' => 'The Back Seats or Trunk section must have at least 3 images.'
        ];
    }

    // No side effects here; FormRequest is for validation only.
}
