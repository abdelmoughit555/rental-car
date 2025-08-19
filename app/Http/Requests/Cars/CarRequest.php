<?php

namespace App\Http\Requests\Cars;

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
        ];
    }

    public function handle()
    {
        return;
    }
}
