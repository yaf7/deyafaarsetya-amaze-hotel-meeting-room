<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MeetingRoom;
use App\Models\FoodPackage;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Ruang Meeting
        MeetingRoom::insert([
            [
                'name' => 'Brawijaya Room',
                'capacity' => 200,
                'facilities' => 'LCD projector + screen, Sound system, Flipchart & writing materials, Air mineral',
                'layout' => json_encode([
                    'theater' => 200,
                    'classroom' => 120,
                    'round_table' => 100,
                    'u_shape' => 80
                ]),
                'price' => 0, // harga dihitung dari paket meeting
                'photo' => 'brawijaya.jpg'
            ],
            [
                'name' => 'Jayabaya Room',
                'capacity' => 100,
                'facilities' => 'LCD projector + screen, Sound system, Flipchart & writing materials, Air mineral',
                'layout' => json_encode([
                    'theater' => 100,
                    'classroom' => 60,
                    'round_table' => 60,
                    'u_shape' => 50
                ]),
                'price' => 0,
                'photo' => 'jayabaya.jpg'
            ],
            [
                'name' => 'Kilisuci Room',
                'capacity' => 40,
                'facilities' => 'LCD projector + screen, Sound system, Flipchart & writing materials, Air mineral',
                'layout' => json_encode([
                    'theater' => 40,
                    'classroom' => 40,
                    'round_table' => 40,
                    'u_shape' => 30
                ]),
                'price' => 0,
                'photo' => 'kilisuci.jpg'
            ]
        ]);

        // Paket Makanan (Meeting Packages)
        FoodPackage::insert([
            [
                'name' => 'Half Day Meeting',
                'price' => 195000,
                'description' => '4 jam: 1x Coffee Break + 1x Meal'
            ],
            [
                'name' => 'Full Day Meeting',
                'price' => 235000,
                'description' => '8 jam: 2x Coffee Break + 1x Meal'
            ],
            [
                'name' => 'Full Board Meeting',
                'price' => 380000,
                'description' => '12 jam: 2x Coffee Break + 2x Meal'
            ],
            [
                'name' => 'Residential Full Day Meeting',
                'price' => 550000,
                'description' => '8 jam + Menginap: Superior Room + 1x CB + 1x Meal'
            ],
            [
                'name' => 'Residential Full Board Meeting',
                'price' => 600000,
                'description' => '12 jam + Menginap: Superior Room + 2x CB + 3x Meals'
            ]
        ]);
    }
}
