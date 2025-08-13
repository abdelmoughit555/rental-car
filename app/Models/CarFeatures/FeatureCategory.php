<?php

namespace App\Models\CarFeatures;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeatureCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }
}
