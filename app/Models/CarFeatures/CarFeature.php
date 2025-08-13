<?php

namespace App\Models\CarFeatures;

use App\Models\Cars\Car;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarFeature extends Model
{
    use HasFactory;

    protected $fillable = ['car_id', 'feature_id'];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
