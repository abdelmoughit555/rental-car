<?php

namespace App\Filters;

use Carbon\Carbon;

class CarFilter extends QueryFilter
{
    public function q($term = null)
    {
        if (! $term) {
            return;
        }

        $this->builder->where(function($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function brand_id($id)
    {
        $this->builder->where('brand_id', $id);
    }

    public function car_model_id($id)
    {
        $this->builder->where('car_model_id', $id);
    }

    public function car_model_ids($ids)
    {
        if (! is_array($ids)) {
            $ids = [$ids];
        }
        $this->builder->whereIn('car_model_id', $ids);
    }

    public function fuel_type_id($id)
    {
        $this->builder->where('fuel_type_id', $id);
    }

    public function gearbox_id($id)
    {
        $this->builder->where('gearbox_id', $id);
    }

    public function seats($min)
    {
        $this->builder->where('seats', '>=', (int) $min);
    }

    public function doors($min)
    {
        $this->builder->where('doors', '>=', (int) $min);
    }

    public function mileage_min($min)
    {
        $this->builder->where('mileage_km', '>=', (int) $min);
    }

    public function mileage_max($max)
    {
        $this->builder->where('mileage_km', '<=', (int) $max);
    }

    public function id($ids)
    {
        if (! is_array($ids)) {
            $ids = [$ids];
        }

        $this->builder->whereIn('id', $ids);
    }

    public function statuses($statuses)
    {
        if (! is_array($statuses)) {
            $statuses = [$statuses];
        }

        $this->builder->whereIn('status', $statuses);
    }

    public function formats($formats)
    {
        if (! is_array($formats)) {
            $formats = [$formats];
        }

        $this->builder->whereIn('format', $formats);
    }

    public function upcs($upcs)
    {
        if (! is_array($upcs)) {
            $upcs = [$upcs];
        }

        $this->builder->whereIn('upc', $upcs);
    }

    public function isrcs($isrcs)
    {
        if (! is_array($isrcs)) {
            $isrcs = [$isrcs];
        }

        $this->builder->whereHas('tracks', function($query) use ($isrcs) {
            $query->whereIn('isrc', $isrcs);
        });
    }

    public function grids($grids)
    {
        if (! is_array($grids)) {
            $grids = [$grids];
        }

        $this->builder->whereIn('grid', $grids);
    }

    public function recordLabels($recordLabels)
    {
        if (! is_array($recordLabels)) {
            $recordLabels = [$recordLabels];
        }

        $this->builder->whereIn('record_label_id', $recordLabels);
    }

    public function genres($genres)
    {
        if (! is_array($genres)) {
            $genres = [$genres];
        }

        $this->builder->whereHas('genres', function($query) use ($genres) {
            $query->whereIn('genre_id', $genres);
        });
    }

    public function releaseDateSince($releaseDate)
    {
        return $this->builder->where('release_date', '>=', 
            Carbon::parse($releaseDate)->startOfDay()->toDateTimeString()
        );
    }

    public function releaseDateUntil($releaseDate)
    {
        return $this->builder->where('release_date', '<=', 
            Carbon::parse($releaseDate)->endOfDay()->toDateTimeString()
        );
    }

    public function createdSince($createdSince)
    {
        return $this->builder->where('created_at', '>=', 
            Carbon::parse($createdSince)->startOfDay()->toDateTimeString()
        );
    }

    public function createdUntil($createdUntil)
    {
        return $this->builder->where('created_at', '<=', 
            Carbon::parse($createdUntil)->endOfDay()->toDateTimeString()
        );
    }

    public function artists($artists)
    {
        if (! is_array($artists)) {
            $artists = [$artists];
        }

        $this->builder->whereHas('contributors', function($query) use ($artists) {
            $query->whereIn('profile_id', $artists);
            $query->whereIn('role_id', [1, 2]);
        });
    }
}