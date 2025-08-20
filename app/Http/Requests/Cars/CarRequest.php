<?php

namespace App\Http\Requests\Cars;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;


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
            'available_to'   => ['sometimes','required','date','date_format:Y-m-d','after:available_from'],
            'price_per_day' => ['sometimes', 'required', 'numeric', 'min:0.01', 'max:999999.99'],
        ];
    }

    public function handle()
    {
        return;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! $this->filled('available_from') || ! $this->filled('available_to')) {
                return;
            }
    
            if ($validator->errors()->has('available_from') || $validator->errors()->has('available_to')) {
                return;
            }
    
            $from = Carbon::createFromFormat('Y-m-d', $this->input('available_from'))->startOfDay();
            $to   = Carbon::createFromFormat('Y-m-d', $this->input('available_to'))->startOfDay();
    
            if ($from->diffInDays($to) < 14) {
                $validator->errors()->add(
                    'available_to', 'Available To must be at least 2 weeks (14 days) after Available From.'
                );
            }
        });
    }
}
