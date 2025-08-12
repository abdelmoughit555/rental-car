<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cars\Gearbox;
use Illuminate\Support\Str;

class GearboxSeeder extends Seeder
{
    public function run()
    {
        $gearboxes = [
            'manual',
            'automatic',
            'semi-automatic'
        ];  
        
        foreach ($gearboxes as $gearbox) {
            Gearbox::updateOrCreate([
                'slug' => Str::slug($gearbox),
            ], [
                'name' => $gearbox,
            ]);
        }
    }
}