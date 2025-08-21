<?php

namespace App\Models\Cars;

use App\Enums\Cars\CarStatus;
use App\Models\User;
use App\Models\Brands\CarModel;
use App\Models\CarFeatures\Feature;
use App\Models\Cars\FuelType;
use App\Models\Cars\Gearbox;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\Cars\CarFactory> */
    use HasFactory;

    protected $fillable = ['title', 'description', 'year', 'engine_cc', 'power_hp', 'doors', 'seats', 'mileage_km', 'registration_number', 'status', 'published_at', 'hidden_at', 'user_id', 'brand_id', 'car_model_id', 'fuel_type_id', 'gearbox_id', 'available_from', 'available_to', 'price_per_day'
    ];

    protected $casts = [
        'status' => CarStatus::class,
        'published_at' => 'datetime',
        'hidden_at' => 'datetime',
        'available_from' => 'date',
        'available_to' => 'date',
        'year' => 'integer',
        'engine_cc' => 'integer',
        'power_hp' => 'integer',
        'doors' => 'integer',
        'seats' => 'integer',
        'mileage_km' => 'integer',
        'price_per_day' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function fuelType(): BelongsTo
    {
        return $this->belongsTo(FuelType::class);
    }

    public function gearbox(): BelongsTo
    {
        return $this->belongsTo(Gearbox::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'car_features', 'car_id', 'feature_id');
    }
}
