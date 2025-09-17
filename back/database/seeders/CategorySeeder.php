<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Meeting Room', 'image' => null],
            ['name' => 'Conference Hall', 'image' => null],
            ['name' => 'Co-working Space', 'image' => null],
            ['name' => 'Private Office', 'image' => null],
            ['name' => 'Event Space', 'image' => null],
            ['name' => 'Training Room', 'image' => null],
            ['name' => 'Hot Desk', 'image' => null],
            ['name' => 'Phone Booth', 'image' => null],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
