<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin profile
        Profile::create([
            'id' => 1, // Same as account_id
            'fullname' => 'Admin User',
            'phone_number' => '0987654321',
            'email' => 'admin@example.com',
            'avatar' => null
        ]);

        // Manager profile
        Profile::create([
            'id' => 2, // Same as account_id
            'fullname' => 'Manager User',
            'phone_number' => '0987654322',
            'email' => 'manager@example.com',
            'avatar' => null
        ]);

        // Employee profile
        Profile::create([
            'id' => 3, // Same as account_id
            'fullname' => 'Employee One',
            'phone_number' => '0987654323',
            'email' => 'employee1@example.com',
            'avatar' => null
        ]);

        // Customer profiles
       for ($i = 4; $i <= 13; $i++) {
           Profile::create([
               'id' => $i, // Same as account_id
               'fullname' => 'Customer ' . ($i - 3),
               'phone_number' => '09876543' . str_pad($i + 20, 2, '0', STR_PAD_LEFT),
               'email' => 'customer' . ($i - 3) . '@example.com',
               'avatar' => null
           ]);
       }
    }
}
