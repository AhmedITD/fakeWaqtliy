<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\OrganizationLocation;
use App\Models\User;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'TechSpace Hub',
                'owner_email' => 'sarah@techspace.com',
                'description' => 'Modern co-working space for tech professionals with state-of-the-art facilities and high-speed internet.',
                'email' => 'info@techspace.com',
                'image' => '/images/organizations/techspace-hub.jpg',
                'locations' => [
                    [
                        'latitude' => 40.7589,
                        'longitude' => -73.9851,
                        'location_written' => '123 Tech Avenue, New York, NY 10001'
                    ]
                ]
            ],
            [
                'name' => 'Creative Hub',
                'owner_email' => 'mike@creativehub.com',
                'description' => 'Inspiring workspace for creatives, designers, and artists with flexible meeting rooms and event spaces.',
                'email' => 'hello@creativehub.com',
                'image' => '/images/organizations/creative-hub.jpg',
                'locations' => [
                    [
                        'latitude' => 40.7505,
                        'longitude' => -73.9934,
                        'location_written' => '456 Creative Street, New York, NY 10014'
                    ],
                    [
                        'latitude' => 40.7614,
                        'longitude' => -73.9776,
                        'location_written' => '789 Art District, New York, NY 10010'
                    ]
                ]
            ],
            [
                'name' => 'Business Center Downtown',
                'owner_email' => 'sarah@techspace.com',
                'description' => 'Professional business center in the heart of downtown with premium meeting rooms and conference facilities.',
                'email' => 'contact@businesscenter.com',
                'image' => '/images/organizations/business-center.jpg',
                'locations' => [
                    [
                        'latitude' => 40.7074,
                        'longitude' => -74.0113,
                        'location_written' => '100 Wall Street, New York, NY 10005'
                    ]
                ]
            ],
            [
                'name' => 'Startup Incubator',
                'owner_email' => 'ahmed@waqitly.com',
                'description' => 'Dynamic space for startups and entrepreneurs with mentorship programs and networking events.',
                'email' => 'info@startupincubator.com',
                'image' => '/images/organizations/startup-incubator.jpg',
                'locations' => [
                    [
                        'latitude' => 40.7831,
                        'longitude' => -73.9712,
                        'location_written' => '200 Innovation Drive, New York, NY 10025'
                    ]
                ]
            ],
            [
                'name' => 'Green Office Space',
                'owner_email' => 'mike@creativehub.com',
                'description' => 'Eco-friendly workspace with sustainable practices, natural lighting, and green building features.',
                'email' => 'info@greenoffice.com',
                'image' => '/images/organizations/green-office.jpg',
                'locations' => [
                    [
                        'latitude' => 40.7282,
                        'longitude' => -73.7949,
                        'location_written' => '300 Eco Boulevard, Queens, NY 11375'
                    ]
                ]
            ]
        ];

        foreach ($organizations as $orgData) {
            $ownerEmail = $orgData['owner_email'];
            $locations = $orgData['locations'];
            unset($orgData['owner_email'], $orgData['locations']);
            
            $owner = User::where('email', $ownerEmail)->first();
            if (!$owner) continue;
            
            $orgData['owner_id'] = $owner->id;
            $organization = Organization::create($orgData);
            
            // Create locations for this organization
            foreach ($locations as $locationData) {
                $locationData['organization_id'] = $organization->id;
                OrganizationLocation::create($locationData);
            }
        }
    }
}
