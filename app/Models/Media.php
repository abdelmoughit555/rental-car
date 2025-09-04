<?php

namespace App\Models;

use App\Services\CloudFrontService;
use App\Traits\HasLegacyId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    /** @use HasFactory<\Database\Factories\MediaFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'media';

    protected $casts = [
        'metadata' => 'array',
        'derivatives' => 'array',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function path()
    {
        return $this->directory . '/' . $this->name;
    }

    public function filename()
    {
        return $this->name .'.' .$this->extension;
    }

    public function url()
    {
        if(app()->environment('testing')) {
            return Storage::disk($this->disk)->url($this->path());
        } elseif(app()->environment('local')) {
            return Storage::disk($this->disk)->temporaryUrl($this->path(), now()->addHour());
        }

        return Cache::remember('media.url.' . $this->id, now()->addDays(7), function () {
            return CloudFrontService::sign(
                $this->path(),
                now()->addDays(7)
            );
        });
    }

    public function bucketUrl()
    {
        return Storage::disk($this->disk)->temporaryUrl($this->path(), now()->addHour());
    }

    public function sizeInMb()
    {
        return round($this->size / 1024 / 1024, 2);
    }

    /**
     * Get derivative URL for a specific width and format
     */
    public function getDerivativeUrl(int $width, string $format = 'webp'): ?string
    {
        if (!$this->derivatives) {
            return null;
        }

        $derivative = collect($this->derivatives)->first(function ($d) use ($width, $format) {
            return $d['type'] === 'responsive' && 
                   $d['width'] === $width && 
                   $d['format'] === $format;
        });

        if (!$derivative) {
            return null;
        }

        // Build the derivative URL
        $baseUrl = $this->url();
        $derivativeFilename = basename($derivative['key']);
        return str_replace($this->name, 'derivatives/' . $derivativeFilename, $baseUrl);
    }

    /**
     * Get special crop URL (thumb, card, hero)
     */
    public function getCropUrl(string $cropType, string $format = 'webp'): ?string
    {
        if (!$this->derivatives) {
            return null;
        }

        $derivative = collect($this->derivatives)->first(function ($d) use ($cropType, $format) {
            return $d['type'] === 'special_crop' && 
                   $d['crop'] === $cropType && 
                   $d['format'] === $format;
        });

        if (!$derivative) {
            return null;
        }

        // Build the derivative URL
        $baseUrl = $this->url();
        $derivativeFilename = basename($derivative['key']);
        return str_replace($this->name, 'derivatives/' . $derivativeFilename, $baseUrl);
    }

    /**
     * Check if derivatives have been processed
     */
    public function hasDerivatives(): bool
    {
        return !empty($this->derivatives) && is_array($this->derivatives);
    }
}
