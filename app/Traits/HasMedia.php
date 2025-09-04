<?php

namespace App\Traits;

use App\Models\Media;

trait HasMedia
{
    public function media()
    {
        return $this->morphMany(Media::class, 'model');
    }

    public function getMediaOfType($type)
    {
        return $this->getMediaOfTypes([$type]);
    }

    public function getMediaOfTypes(array $types)
    {
        return $this->media->whereIn('type', $types);
    }

    public function appendMedia(array $attributes)
    {
        $media = $this->media()->create([
            'model_type' => $this->getMorphClass(),
            'model_id' => $this->id,
            ...$attributes,
        ]);

        return $media;
    }
}
