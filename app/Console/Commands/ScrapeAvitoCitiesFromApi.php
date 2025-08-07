<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\City;
use App\Models\Area;

class ScrapeAvitoCitiesFromApi extends Command
{
    protected $signature = 'scrape:avito-api';
    protected $description = 'Scrape real cities and areas from Avito.ma API and save to database';

    public function handle()
    {
        $this->info('Fetching city/area data from Avito...');

        $response = Http::get('https://services.avito.ma/api/v2/config/adlisting/filters?category_id=0&type=all&lang=fr');
        $json = $response->json();

        $filters = collect($json['steps']['navBarFilters'] ?? []);
        $cityFilter = $filters->firstWhere('id', 'city');

        if (!$cityFilter || !isset($cityFilter['list'])) {
            $this->error('City filter not found.');
            return 1;
        }

        $popularCities = [
            'Casablanca',
            'Marrakech',
            'Rabat',
            'Tanger',
            'Fes',
            'Agadir',
            'Meknes',
            'Oujda',
            'Tetouan',
            'Temara',
            'El-Jadida',
            'Kenitra',
            'Safi',
            'Berkane',
            'Nador',
            'Taourirt',
            'Taza',
            'Beni-Mellal',
            'Khemisset',
            'Khenifra',
            'Settat',
            'Khouribga',
            'Essaouira',
            'Larache',
            'Mohammedia',
            'Dakhla',
            'Laayoune',
            'Azrou',
            'Ifrane',
            'Guercif',
        ];

        $citiesAdded = 0;
        $areasAdded = 0;

        foreach ($cityFilter['list'] as $cityItem) {
            $cityName = $cityItem['label'];
            $citySlug = str($cityName)->slug();

            $isFeatured = in_array($cityName, $popularCities);
            $featuredTimestamp = $isFeatured ? now() : null;

            $city = City::updateOrCreate(
                [
                    'slug' => $citySlug
                ],
                [
                    'name' => $cityName,
                    'slug' => $citySlug,
                    'is_active' => true,
                    'featured' => $featuredTimestamp
                ]
            );

            if ($city->wasRecentlyCreated) $citiesAdded++;

            if (!empty($cityItem['children'])) {
                foreach ($cityItem['children'] as $areaItem) {
                    $areaName = $areaItem['label'];

                    // Skip "Toute la ville"
                    if (strtolower($areaName) === 'toute la ville') continue;

                    // Create unique area slug by combining city and area names
                    $areaSlug = $citySlug . '-' . str($areaName)->slug();

                    $area = Area::updateOrCreate(
                        [
                            'city_id' => $city->id, 
                            'slug' => $areaSlug
                        ],
                        [
                            'name' => $areaName,
                            'slug' => $areaSlug,
                            'is_active' => true
                        ]
                    );

                    if ($area->wasRecentlyCreated) $areasAdded++;
                }
            }
        }

        $this->info("✅ Imported $citiesAdded cities and $areasAdded areas successfully.");
        return 0;
    }
}
