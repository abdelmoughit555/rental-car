<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'slug',
        'url',
        'type',
        'is_active',
        'scraped_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'scraped_at' => 'datetime',
    ];

    /**
     * Get the city that owns the area
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Scope to get only active areas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get areas by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the full location string
     */
    public function getFullLocationAttribute()
    {
        return $this->name . ', ' . $this->city->name . ', ' . $this->city->country;
    }
}
