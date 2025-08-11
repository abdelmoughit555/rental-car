<?php

namespace App\Models\Brands;

use Illuminate\Database\Eloquent\Model;

class Make extends Model
{
    protected $fillable = ['name', 'slug', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function models()
    {
        return $this->hasMany(CarModel::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
