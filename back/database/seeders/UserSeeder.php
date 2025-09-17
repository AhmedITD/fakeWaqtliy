<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\LocalAuth;
use App\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'full_name' => 'Ahmed Admin',
                'username' => 'ahmed_admin',
                'email' => 'ahmed@waqitly.com',
                'role' => Role::SuperAdmin,
                'bio' => 'Super Administrator of Waqitly platform',
                'gender' => 'male',
                'birth_date' => '1990-01-15',
                'phone' => '+1234567890',
                'password' => 'password123'
            ],
            [
                'full_name' => 'Sarah Manager',
                'username' => 'sarah_manager',
                'email' => 'sarah@techspace.com',
                'role' => Role::Admin,
                'bio' => 'Space manager at TechSpace Hub',
                'gender' => 'female',
                'birth_date' => '1988-05-22',
                'phone' => '+1234567891',
                'password' => 'password123'
            ],
            [
                'full_name' => 'Mike Johnson',
                'username' => 'mike_johnson',
                'email' => 'mike@creativehub.com',
                'role' => Role::Admin,
                'bio' => 'Creative Hub owner and manager',
                'gender' => 'male',
                'birth_date' => '1985-09-10',
                'phone' => '+1234567892',
                'password' => 'password123'
            ],
            [
                'full_name' => 'Emily Chen',
                'username' => 'emily_chen',
                'email' => 'emily@businesscenter.com',
                'role' => Role::Staff,
                'bio' => 'Staff member at Business Center Downtown',
                'gender' => 'female',
                'birth_date' => '1992-03-18',
                'phone' => '+1234567893',
                'password' => 'password123'
            ],
            [
                'full_name' => 'John Doe',
                'username' => 'john_doe',
                'email' => 'john@example.com',
                'role' => Role::User,
                'bio' => 'Freelance developer looking for workspace',
                'gender' => 'male',
                'birth_date' => '1995-07-25',
                'phone' => '+1234567894',
                'password' => 'password123'
            ],
            [
                'full_name' => 'Lisa Rodriguez',
                'username' => 'lisa_rodriguez',
                'email' => 'lisa@startup.com',
                'role' => Role::User,
                'bio' => 'Startup founder seeking meeting spaces',
                'gender' => 'female',
                'birth_date' => '1991-11-08',
                'phone' => '+1234567895',
                'password' => 'password123'
            ]
        ];

        foreach ($users as $userData) {
            $phone = $userData['phone'];
            $password = $userData['password'];
            unset($userData['phone'], $userData['password']);
            
            $user = User::create($userData);
            
            // Create local auth for each user
            LocalAuth::create([
                'user_id' => $user->id,
                'phone_number' => $phone,
                'password_hash' => Hash::make($password),
                'is_phone_verified' => true
            ]);
        }
    }
}
