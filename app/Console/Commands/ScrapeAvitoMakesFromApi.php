<?php

namespace App\Console\Commands;

use App\Models\Brands\CarModel;
use App\Models\Brands\Make;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ScrapeAvitoMakesFromApi extends Command
{
    protected $signature = 'scrape:avito-makes';
    protected $description = 'Scrape car makes & models from Avito.ma API and save to database';

    public function handle()
    {
        $this->info('Fetching make/model data from Avito...');

        $response = Http::get('https://services.avito.ma/api/v2/config/adlisting/filters?category_id=2010&type=sell&lang=fr');

        if ($response->failed()) {
            $this->error('Failed to reach Avito API.');
            return 1;
        }

        $json = $response->json();

        // Find the "brand" (Marque) filter and its child "model"
        $filters = collect($json['steps']['primaryFilters'] ?? []);
        $brandFilter = $filters->firstWhere('id', 'brand');


        if (!$brandFilter || !isset($brandFilter['list'])) {
            $this->error('Brand filter not found.');
            return 1;
        }

        // Optional: mark some makes as featured
        $popularMakes = [
            'Toyota','Dacia','Renault','Volkswagen','Peugeot',
            'Hyundai','Kia','Mercedes-Benz','BMW','Audi'
        ];

        $makesAdded = 0;
        $modelsAdded = 0;

        foreach ($brandFilter['list'] as $makeItem) {
            $makeName = Str::lower(trim($makeItem['label']));
            $makeSlug = Str::slug($makeName);

            $isFeatured = in_array($makeName, $popularMakes);
            $featuredTimestamp = $isFeatured ? now() : null;

            $make = Make::updateOrCreate(
                ['slug' => $makeSlug],
                [
                    'name' => $makeName,
                    'slug' => $makeSlug,
                    'is_active' => true,
                    'featured' => $featuredTimestamp
                ]
            );

            if ($make->wasRecentlyCreated) {
                $makesAdded++;
            }

            foreach (($makeItem['children'] ?? []) as $modelItem) {
                $modelName = Str::lower(trim($modelItem['label']));
                if ($modelName === '' || strcasecmp($modelName, 'Autres') === 0) {
                    // skip "Autres" bucket if present
                    continue;
                }

                $modelSlug = $makeSlug . '-' . Str::slug($modelName);

                $model = CarModel::updateOrCreate(
                    [
                        'make_id' => $make->id,
                        'slug' => $modelSlug,
                    ],
                    [
                        'name' => $modelName,
                        'slug' => $modelSlug,
                        'is_active' => true,
                    ]
                );

                if ($model->wasRecentlyCreated) {
                    $modelsAdded++;
                }
            }
        }

        $this->info("✅ Imported/updated $makesAdded makes and $modelsAdded models successfully.");
        return 0;
    }
}
