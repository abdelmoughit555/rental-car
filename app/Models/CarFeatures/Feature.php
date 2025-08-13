<?php

namespace App\Models\CarFeatures;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = ['feature_category_id', 'name', 'slug', 'description', 'is_active'];

    public function featureCategory(): BelongsTo
    {
        return $this->belongsTo(FeatureCategory::class);
    }
}
