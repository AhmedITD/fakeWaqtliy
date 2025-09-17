<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ['name' => 'WiFi'],
            ['name' => 'Projector'],
            ['name' => 'Whiteboard'],
            ['name' => 'Air Conditioning'],
            ['name' => 'Coffee/Tea'],
            ['name' => 'Printing'],
            ['name' => 'Video Conferencing'],
            ['name' => 'Sound System'],
            ['name' => 'Flipchart'],
            ['name' => 'Parking'],
            ['name' => 'Reception'],
            ['name' => 'Catering'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
