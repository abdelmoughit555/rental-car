<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CarFeatures\Feature;
use App\Models\CarFeatures\FeatureCategory;

class FeatureCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $featureCategories = [
                [
                    'name' => 'Safety & Driver Assist',
                    'slug' => 'safety-driver-assist',
                    'description' => 'Collision protection, parking aids and advanced driver assistance.',
                    'features' => [
                        [
                            'name'        => 'Airbags',
                            'slug'        => 'airbags',
                            'description' => 'Frontal and side airbags cushion occupants in a crash to reduce injuries.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'ABS',
                            'slug' => 'abs',
                            'description' => 'Anti-lock braking prevents wheel lockup under hard braking to maintain steering control.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Stability control (ESP)',
                            'slug' => 'esp',
                            'description' => 'Helps correct skids by braking individual wheels and balancing the car.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Traction control (TCS)',
                            'slug' => 'tcs',
                            'description' => 'Limits wheel spin on slick surfaces for safer, smoother takeoff.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Tire pressure monitoring',
                            'slug' => 'tpms',
                            'description' => 'Monitors tire pressure and alerts when low to improve safety and efficiency.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Rear camera',
                            'slug' => 'rear-camera',
                            'description' => 'Wide-angle view behind the car for safer reversing and parking.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Front parking sensors',
                            'slug' => 'front-parking-sensors',
                            'description' => 'Proximity beepers detect obstacles ahead to aid tight maneuvers.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Rear parking sensors',
                            'slug' => 'rear-parking-sensors',
                            'description' => 'Audible alerts for obstacles behind when reversing.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Blind-spot monitoring',
                            'slug' => 'blind-spot-monitoring',
                            'description' => 'Warns about vehicles in blind zones during lane changes.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Lane keep assist',
                            'slug' => 'lane-keep-assist',
                            'description' => 'Gently steers or alerts to keep the car centered within lane markings.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Adaptive cruise control',
                            'slug' => 'adaptive-cruise',
                            'description' => 'Maintains set speed and distance from the vehicle ahead automatically.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Automatic emergency braking',
                            'slug' => 'auto-emergency-braking',
                            'description' => 'Detects imminent collisions and brakes automatically to reduce impact.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'ISOFIX / LATCH',
                            'slug' => 'isofix-latch',
                            'description' => 'Integrated child-seat anchors for quick, secure installation of compatible seats.',
                            'is_active'   => true,
                        ],
                        [
                            'name'  => 'Hill start assist',
                            'slug' => 'hill-start-assist',
                            'description' => 'Prevents rollback by holding brakes briefly on inclines.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Hill descent control',
                            'slug' => 'hill-descent-control',
                            'description' => 'Automatically controls speed downhill for stable, low-traction descents.',
                            'is_active'   => true,
                        ],
                    ],
                ],
                [
                    'name' => 'Comfort & Convenience',
                    'slug' => 'comfort-convenience',
                    'description' => 'Climate, seats and everyday comfort touches renters care about.',
                    'features' => [
                        [
                            'name' => 'Climate control (auto)',
                            'slug' => 'climate-control',
                            'description' => 'Sets and maintains cabin temperature automatically with digital controls.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Dual-zone climate',
                            'slug' => 'dual-zone-climate',
                            'description' => 'Separate temperature settings for driver and front passenger.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Rear A/C vents',
                            'slug' => 'rear-ac-vents',
                            'description' => 'Dedicated rear vents improve airflow and comfort for back-seat passengers.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Heated seats',
                            'slug' => 'heated-seats',
                            'description' => 'Electric seat heating provides fast warmth in cold weather.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Ventilated seats',
                            'slug' => 'ventilated-seats',
                            'description' => 'Active cooling channels reduce seat heat and perspiration.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Leather seats',
                            'slug' => 'leather-seats',
                            'description' => 'Leather or leatherette upholstery for premium feel and easier cleaning.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Power driver seat',
                            'slug' => 'power-driver-seat',
                            'description' => 'Electric adjustments for height, recline and lumbar support.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Adjustable steering (tilt/telescopic)',
                            'slug' => 'steering-adjustable',
                            'description' => 'Tilt and telescopic column for a better driving position.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Cruise control',
                            'slug' => 'cruise-control',
                            'description' => 'Maintains a set speed to reduce fatigue on long trips.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Keyless entry & start',
                            'slug' => 'keyless-entry-start',
                            'description' => 'Unlocks and starts while the key fob remains in pocket or bag.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Auto rain-sensing wipers',
                            'slug' => 'rain-sensing-wipers',
                            'description' => 'Wipers start and adjust speed automatically with rainfall intensity.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Auto headlights',
                            'slug' => 'auto-headlights',
                            'description' => 'Headlights switch on or off automatically with ambient light.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Sunroof / Panoramic',
                            'slug' => 'sunroof-panoramic',
                            'description' => 'Glass roof panel adds light and optional open-air driving.',
                            'is_active'   => true,
                        ],
                    ],
                ],
                [
                    'name' => 'Connectivity & Media',
                    'slug' => 'connectivity-media',
                    'description' => 'Phone integration, audio and power for devices.',
                    'features' => [
                        [
                            'name' => 'Apple CarPlay',
                            'slug' => 'apple-carplay',
                            'description' => 'Integrates iPhone apps, calls and maps on the car screen with voice control.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Android Auto',
                            'slug' => 'android-auto',
                            'description' => 'Mirrors Android apps, navigation and calls with hands-free voice control.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Bluetooth audio',
                            'slug' => 'bluetooth',
                            'description' => 'Wireless music streaming and hands-free calling via Bluetooth.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Built-in navigation',
                            'slug' => 'built-in-navigation',
                            'description' => 'Onboard GPS with turn-by-turn guidance without needing a phone.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'USB ports',
                            'slug' => 'usb-ports',
                            'description' => 'Standard USB-A ports for charging devices and media playback.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'USB-C ports',
                            'slug' => 'usb-c-ports',
                            'description' => 'Modern USB-C fast charging and data connectivity.',
                            'is_active'   => true,
                        ],
                        [
                            'name'  => '12V outlet',
                            'slug' => '12v-outlet',
                            'description' => 'Power socket for accessories like coolers, pumps or chargers.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Premium sound system',
                            'slug' => 'premium-audio',
                            'description' => 'Upgraded speakers and amplifier for clearer, richer audio.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Wireless phone charging',
                            'slug' => 'wireless-phone-charging',
                            'description' => 'Qi-compatible pad charges phones without cables.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Wi-Fi hotspot',
                            'slug' => 'wifi-hotspot',
                            'description' => 'In-car Wi-Fi capability to connect passenger devices where supported.',
                            'is_active'   => true,
                        ],
                    ],
                ],
                [
                    'name' => 'Exterior & Lighting',
                    'slug' => 'exterior-lighting',
                    'description' => 'Visibility, durability and useful exterior touches.',
                    'features' => [
                        [
                            'name' => 'LED headlights',
                            'slug' => 'led-headlights',
                            'description' => 'Bright, energy-efficient headlights with long lifespan and improved night visibility.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Daytime running lights',
                            'slug' => 'drl',
                            'description' => 'Automatic lights increase the car\'s visibility during daytime.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Fog lights',
                            'slug' => 'fog-lights',
                            'description' => 'Low-mounted lamps improve visibility in fog, rain and mist.',
                            'is_active'   => true,
                        ],
                        [
                            'name'  => 'Alloy wheels',
                            'slug' => 'alloy-wheels',
                            'description' => 'Lightweight alloy rims enhance appearance and handling.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Power-folding mirrors',
                            'slug' => 'power-folding-mirrors',
                            'description' => 'Mirrors fold electrically to avoid damage in tight spaces.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Heated mirrors',
                            'slug' => 'heated-mirrors',
                            'description' => 'Electrically warmed mirrors clear frost and mist quickly.',
                            'is_active'   => true,
                        ],
                        [
                            'name'  => 'Tinted windows / privacy glass',
                            'slug' => 'tinted-windows',
                            'description' => 'Factory tint reduces glare, heat and visibility into the cabin.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Roof rails',
                            'slug' => 'roof-rails',
                            'description' => 'Built-in rails for mounting roof boxes, racks or crossbars.',
                            'is_active'   => true,
                        ],
                    ],
                ],
                [
                    'name' => 'Seating & Cargo',
                    'slug' => 'seating-cargo',
                    'description' => 'Practical space, access and versatility for passengers and luggage.',
                    'features' => [
                        [
                            'name' => 'Split-fold rear seats (60/40)',
                            'slug' => 'split-fold-6040',
                            'description' => 'Backrests fold separately to balance passenger space and cargo capacity.',
                            'is_active'   => true,
                        ],
                        [
                            'name'  => 'Third-row seats (7-seater)',
                            'slug'  => 'third-row',
                            'description' => 'Additional foldable seats provide space for up to seven passengers.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Rear center armrest',
                            'slug'  => 'rear-armrest',
                            'description' => 'Fold-down armrest with cupholders for rear passenger comfort.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Power tailgate',
                            'slug' => 'power-tailgate',
                            'description' => 'Boot opens and closes electrically via button or key fob.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Flat load floor',
                            'slug' => 'flat-load-floor',
                            'description' => 'Cargo area designed to be flat for easier loading of large items.',
                            'is_active'   => true,
                        ],
                    ],
                ],
                [
                    'name' => 'Powertrain & Handling',
                    'slug' => 'powertrain-handling',
                    'description' => 'Traction, driving modes and components that shape how the car drives.',
                    'features' => [
                        [
                            'name' => 'All-wheel drive (AWD/4x4)',
                            'slug' => 'awd-4x4',
                            'description' => 'Power to all wheels for improved traction on rain, snow and gravel.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Drive mode selector',
                            'slug' => 'drive-modes',
                            'description' => 'Choose modes that adjust throttle, steering and transmission behavior.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Start/Stop system',
                            'slug' => 'start-stop',
                            'description' => 'Automatically shuts off the engine at stops to save fuel and reduce emissions.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Paddle shifters',
                            'slug' => 'paddle-shifters',
                            'description' => 'Manual gear changes via steering-wheel paddles for added control.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Sport suspension',
                            'slug' => 'sport-suspension',
                            'description' => 'Tuned springs and dampers for sharper handling and reduced body roll.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Limited-slip differential',
                            'slug' => 'lsd',
                            'description' => 'Sends torque to the wheel with grip for better corner exit and traction.',
                            'is_active'   => true,
                        ],
                    ],
                ],
                [
                    'name' => 'Security',
                    'slug' => 'security',
                    'description' => 'Anti-theft systems and protections renters expect.',
                    'features' => [
                        [
                            'name' => 'Alarm system',
                            'slug' => 'alarm',
                            'description' => 'Audible alarm and sensors deter unauthorized entry and theft.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Immobilizer',
                            'slug' => 'immobilizer',
                            'description' => 'Electronic system prevents engine start without the correct key.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Remote central locking',
                            'slug' => 'remote-locking',
                            'description' => 'Lock or unlock all doors using the key fob.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'GPS vehicle tracking',
                            'slug' => 'vehicle-tracking',
                            'description' => 'Tracking hardware helps locate the vehicle if stolen.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Spare key available',
                            'slug' => 'spare-key',
                            'description' => 'A second programmed key is provided for convenience and backup.',
                            'is_active'   => true,
                        ],
                    ],
                ],
                [
                    'name' => 'EV & Hybrid Equipment',
                    'slug' => 'ev-hybrid',
                    'description' => 'Charging connectors, fast-charge capability and range-saving hardware.',
                    'features' => [
                        [
                            'name' => 'Charging cable (Mode 2)',
                            'slug' => 'ev-cable-mode2',
                            'description' => 'Portable cable for household sockets—slower but convenient charging.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Type 2 / Mennekes',
                            'slug' => 'ev-type2',
                            'description' => 'European AC charging connector compatible with most public chargers.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'CCS fast-charge',
                            'slug'  => 'ev-ccs',
                            'description' => 'Combined Charging System enables high-power DC fast charging.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'CHAdeMO',
                            'slug' => 'ev-chademo',
                            'description' => 'Legacy DC fast-charging standard used by some EVs and stations.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'DC fast-charging capable',
                            'slug' => 'ev-dc-fast',
                            'description' => 'Vehicle supports rapid DC charging to significantly cut charge time.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Heat pump',
                            'slug' => 'ev-heat-pump',
                            'description' => 'Efficient cabin heating that helps preserve driving range in cold weather.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Adjustable regen braking',
                            'slug' => 'ev-adjustable-regen',
                            'description' => 'Driver-selectable regenerative braking levels to recover energy and control.',
                            'is_active'   => true,
                        ],
                    ],
                ],
                [
                    'name' => 'Off-road & Towing',
                    'slug' => 'offroad-towing',
                    'description' => 'Hardware that helps on trails or when pulling a trailer.',
                    'features' => [
                        [
                            'name' => 'Tow hook / recovery points',
                            'slug' => 'tow-hook',
                            'description' => 'Rated points for safe towing or recovery off-road.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Trailer hitch',
                            'slug' => 'trailer-hitch',
                            'description' => 'Factory or approved hitch to tow trailers within rated limits.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Skid plates',
                            'slug' => 'skid-plates',
                            'description' => 'Underbody protection for engine, transmission and fuel tank off-road.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Locking differential',
                            'slug' => 'locking-diff',
                            'description' => 'Mechanically locks an axle to maintain drive when one wheel slips.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Full-size spare wheel',
                            'slug' => 'full-size-spare',
                            'description' => 'Matching spare tire allows normal driving after a puncture.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'All-terrain tires',
                            'slug' => 'all-terrain-tires',
                            'description' => 'Tread designed for mixed surfaces, improving grip off-road.',
                            'is_active'   => true,
                        ],
                    ],
                ],
                [
                    'name' => 'Weather & Visibility',
                    'slug' => 'weather-visibility',
                    'description' => 'Cold-weather comfort and glass cleaners for clear views.',
                    'features' => [
                        [
                            'name' => 'Heated windshield',
                            'slug' => 'heated-windshield',
                            'description' => 'Embedded heating elements clear ice and fog rapidly.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Rear defroster',
                            'slug' => 'rear-defroster',
                            'description' => 'Heated rear window removes condensation and frost.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Headlight washers',
                            'slug' => 'headlight-washers',
                            'description' => 'Sprayers clean headlamp lenses for better light output.',
                            'is_active'   => true,
                        ],
                        [
                            'name' => 'Heated steering wheel',
                            'slug' => 'heated-steering-wheel',
                            'description' => 'Heats the rim for comfort in cold weather.',
                            'is_active'   => true,
                        ],
                    ],
                ],
            ];

            foreach ($featureCategories as $category) {
                $categoryModel = FeatureCategory::updateOrCreate(
                    [
                        'slug' => $category['slug']
                    ],
                    [
                        'name' => $category['name'],
                        'description' => $category['description']
                    ]
                );

                foreach ($category['features'] as $feature) {
                    Feature::updateOrCreate(
                        [
                            'slug' => $feature['slug'],
                            'feature_category_id'  => $categoryModel->id,
                        ],
                        [
                            'name' => $feature['name'],
                            'description' => $feature['description'],
                            'is_active' => $feature['is_active']
                        ]
                    );
                }
            }
        });
    }
}
