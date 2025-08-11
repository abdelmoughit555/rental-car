<?php

namespace App\Models\Brands;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    protected $fillable = [
        'make_id',
        'name',
        'slug',
        'is_active',
        'featured'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'featured' => 'datetime',
    ];

    public function make()
    {
        return $this->belongsTo(Make::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
