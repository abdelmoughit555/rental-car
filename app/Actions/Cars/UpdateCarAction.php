<?php

namespace App\Actions\Cars;

use App\Jobs\Media\ProcessCarImageDerivatives;
use App\Jobs\Media\ProcessImageUploadedMedia;
use App\Models\Cars\Car;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateCarAction
{
    public function execute(Car $car, array $attributes): Car
    {
        DB::transaction(function () use ($car, $attributes) {
            $car->update(Arr::except($attributes, ['features', 'images']));

            $this->syncFeaturesIfPresent($car, $attributes);
            $this->syncImagesIfPresent($car, $attributes);
        });

        return $car->fresh();
    }

    private function syncFeaturesIfPresent(Car $car, array $attributes): void
    {
        if (array_key_exists('features', $attributes)) {
            $car->features()->sync($attributes['features'] ?? []);
        }
    }

    private function syncImagesIfPresent(Car $car, array $attributes): void
    {
        if (empty($attributes['images'])) {
            return;
        }

        foreach ($attributes['images'] as $section => $sectionImages) {
            $directory = "car_images/{$section}";

            $existingMedia = $car->media()->where('directory', $directory)->get();
            $existingFileNames = $existingMedia->pluck('name')->toArray();

            $submittedFileNames = collect($sectionImages)->pluck('file_name')->toArray();

            $existingMedia->whereNotIn('name', $submittedFileNames)->each->delete();

            foreach ($sectionImages as $image) {
                if (!in_array($image['file_name'], $existingFileNames)) {
                    $media = $car->appendMedia([
                        'name' => $image['file_name'],
                        'extension' => $image['file_extension'],
                        'directory' => $directory,
                        'type' => 'uploaded',
                        'disk' => 's3',
                    ]);

                    ProcessImageUploadedMedia::dispatch($media);
                    ProcessCarImageDerivatives::dispatch($media);
                }
            }
        }
    }
}


