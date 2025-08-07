<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'url',
        'region',
        'country',
        'is_active',
        'featured',
        'scraped_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'featured' => 'datetime',
        'scraped_at' => 'datetime',
    ];

    /**
     * Scope to get only active cities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get cities by region
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Get the display name for the city
     */
    public function getDisplayNameAttribute()
    {
        return $this->name;
    }

    /**
     * Get the areas that belong to this city
     */
    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    /**
     * Get the full location string
     */
    public function getFullLocationAttribute()
    {
        $parts = [$this->name];
        
        if ($this->region) {
            $parts[] = $this->region;
        }
        
        $parts[] = $this->country;
        
        return implode(', ', $parts);
    }
}
