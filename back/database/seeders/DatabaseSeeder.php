<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in correct order due to foreign key constraints
        $this->call([
            CategorySeeder::class,
            ServiceSeeder::class,
            UserSeeder::class,
            OrganizationSeeder::class,
            SpaceSeeder::class,
        ]);
    }
}
