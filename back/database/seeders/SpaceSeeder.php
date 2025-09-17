<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Space;
use App\Models\SpaceLocation;
use App\Models\SpaceImage;
use App\Models\SpaceService;
use App\Models\Organization;
use App\Models\Category;
use App\Models\Service;

class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = Organization::all();
        $categories = Category::all();
        $services = Service::all();
        
        $spaces = [
            [
                'name' => 'Executive Meeting Room',
                'description' => 'Premium meeting room with panoramic city views, perfect for executive meetings and client presentations.',
                'size' => 25,
                'capacity' => 8,
                'floor' => '15th Floor',
                'price_per_hour' => 7500, // $75.00
                'thumbnail' => '/images/spaces/executive-meeting-room.jpg',
                'organization_name' => 'TechSpace Hub',
                'category_name' => 'Meeting Room',
                'location' => [
                    'latitude' => 40.7589,
                    'longitude' => -73.9851,
                    'location_written' => '123 Tech Avenue, Floor 15, Room 1501'
                ],
                'images' => [
                    '/images/spaces/executive-meeting-1.jpg',
                    '/images/spaces/executive-meeting-2.jpg'
                ],
                'services' => ['WiFi', 'Projector', 'Video Conferencing', 'Coffee/Tea']
            ],
            [
                'name' => 'Creative Workshop Studio',
                'description' => 'Open creative space with natural lighting, whiteboards, and flexible furniture for brainstorming sessions.',
                'size' => 50,
                'capacity' => 15,
                'floor' => '2nd Floor',
                'price_per_hour' => 5000, // $50.00
                'thumbnail' => '/images/spaces/creative-workshop.jpg',
                'organization_name' => 'Creative Hub',
                'category_name' => 'Event Space',
                'location' => [
                    'latitude' => 40.7505,
                    'longitude' => -73.9934,
                    'location_written' => '456 Creative Street, Floor 2, Studio A'
                ],
                'images' => [
                    '/images/spaces/creative-workshop-1.jpg',
                    '/images/spaces/creative-workshop-2.jpg',
                    '/images/spaces/creative-workshop-3.jpg'
                ],
                'services' => ['WiFi', 'Whiteboard', 'Flipchart', 'Sound System']
            ],
            [
                'name' => 'Private Office Suite',
                'description' => 'Fully furnished private office with dedicated phone line and premium amenities for long-term use.',
                'size' => 20,
                'capacity' => 4,
                'floor' => '8th Floor',
                'price_per_hour' => 10000, // $100.00
                'thumbnail' => '/images/spaces/private-office.jpg',
                'organization_name' => 'Business Center Downtown',
                'category_name' => 'Private Office',
                'location' => [
                    'latitude' => 40.7074,
                    'longitude' => -74.0113,
                    'location_written' => '100 Wall Street, Floor 8, Suite 801'
                ],
                'images' => [
                    '/images/spaces/private-office-1.jpg'
                ],
                'services' => ['WiFi', 'Printing', 'Reception', 'Coffee/Tea']
            ],
            [
                'name' => 'Conference Hall Alpha',
                'description' => 'Large conference hall with theater-style seating and professional AV equipment for presentations.',
                'size' => 100,
                'capacity' => 50,
                'floor' => '1st Floor',
                'price_per_hour' => 15000, // $150.00
                'thumbnail' => '/images/spaces/conference-hall.jpg',
                'organization_name' => 'TechSpace Hub',
                'category_name' => 'Conference Hall',
                'location' => [
                    'latitude' => 40.7589,
                    'longitude' => -73.9851,
                    'location_written' => '123 Tech Avenue, Floor 1, Hall Alpha'
                ],
                'images' => [
                    '/images/spaces/conference-hall-1.jpg',
                    '/images/spaces/conference-hall-2.jpg'
                ],
                'services' => ['WiFi', 'Projector', 'Sound System', 'Video Conferencing', 'Catering']
            ],
            [
                'name' => 'Hot Desk Area',
                'description' => 'Flexible hot desk space in open co-working area with access to shared amenities.',
                'size' => 5,
                'capacity' => 1,
                'floor' => '3rd Floor',
                'price_per_hour' => 2000, // $20.00
                'thumbnail' => '/images/spaces/hot-desk.jpg',
                'organization_name' => 'Startup Incubator',
                'category_name' => 'Hot Desk',
                'location' => [
                    'latitude' => 40.7831,
                    'longitude' => -73.9712,
                    'location_written' => '200 Innovation Drive, Floor 3, Desk Area'
                ],
                'images' => [
                    '/images/spaces/hot-desk-1.jpg'
                ],
                'services' => ['WiFi', 'Printing', 'Coffee/Tea']
            ],
            [
                'name' => 'Training Room Beta',
                'description' => 'Modern training room with interactive whiteboard and comfortable seating for workshops.',
                'size' => 40,
                'capacity' => 20,
                'floor' => '4th Floor',
                'price_per_hour' => 6000, // $60.00
                'thumbnail' => '/images/spaces/training-room.jpg',
                'organization_name' => 'Creative Hub',
                'category_name' => 'Training Room',
                'location' => [
                    'latitude' => 40.7614,
                    'longitude' => -73.9776,
                    'location_written' => '789 Art District, Floor 4, Room Beta'
                ],
                'images' => [
                    '/images/spaces/training-room-1.jpg',
                    '/images/spaces/training-room-2.jpg'
                ],
                'services' => ['WiFi', 'Whiteboard', 'Projector', 'Coffee/Tea']
            ],
            [
                'name' => 'Phone Booth Pod',
                'description' => 'Private soundproof phone booth for confidential calls and video meetings.',
                'size' => 2,
                'capacity' => 1,
                'floor' => '5th Floor',
                'price_per_hour' => 1500, // $15.00
                'thumbnail' => '/images/spaces/phone-booth.jpg',
                'organization_name' => 'Green Office Space',
                'category_name' => 'Phone Booth',
                'location' => [
                    'latitude' => 40.7282,
                    'longitude' => -73.7949,
                    'location_written' => '300 Eco Boulevard, Floor 5, Pod 1'
                ],
                'images' => [
                    '/images/spaces/phone-booth-1.jpg'
                ],
                'services' => ['WiFi', 'Video Conferencing']
            ],
            [
                'name' => 'Co-working Open Space',
                'description' => 'Large open co-working area with flexible seating and collaborative atmosphere.',
                'size' => 200,
                'capacity' => 30,
                'floor' => '2nd Floor',
                'price_per_hour' => 3000, // $30.00
                'thumbnail' => '/images/spaces/coworking-space.jpg',
                'organization_name' => 'Business Center Downtown',
                'category_name' => 'Co-working Space',
                'location' => [
                    'latitude' => 40.7074,
                    'longitude' => -74.0113,
                    'location_written' => '100 Wall Street, Floor 2, Open Area'
                ],
                'images' => [
                    '/images/spaces/coworking-1.jpg',
                    '/images/spaces/coworking-2.jpg'
                ],
                'services' => ['WiFi', 'Printing', 'Coffee/Tea', 'Reception']
            ]
        ];
        
        foreach ($spaces as $spaceData) {
            $organizationName = $spaceData['organization_name'];
            $categoryName = $spaceData['category_name'];
            $location = $spaceData['location'];
            $images = $spaceData['images'];
            $serviceNames = $spaceData['services'];
            
            unset($spaceData['organization_name'], $spaceData['category_name'], $spaceData['location'], $spaceData['images'], $spaceData['services']);
            
            $organization = $organizations->where('name', $organizationName)->first();
            $category = $categories->where('name', $categoryName)->first();
            
            if (!$organization || !$category) continue;
            
            $spaceData['organization_id'] = $organization->id;
            $spaceData['category_id'] = $category->id;
            
            $space = Space::create($spaceData);
            
            // Create location
            $location['space_id'] = $space->id;
            SpaceLocation::create($location);
            
            // Create images
            foreach ($images as $imagePath) {
                SpaceImage::create([
                    'space_id' => $space->id,
                    'image' => $imagePath,
                    'low_res_image' => str_replace('.jpg', '_thumb.jpg', $imagePath)
                ]);
            }
            
            // Create space services
            foreach ($serviceNames as $serviceName) {
                $service = $services->where('name', $serviceName)->first();
                if ($service) {
                    SpaceService::create([
                        'space_id' => $space->id,
                        'service_id' => $service->id,
                        'price' => rand(500, 2000) // Random price between $5-20
                    ]);
                }
            }
        }
    }
}
