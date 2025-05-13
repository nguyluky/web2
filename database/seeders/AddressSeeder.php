<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add employee addresses
        for ($i = 1; $i <= 3; $i++) {
            Address::create([
                'id' => $i,
                'profile_id' => $i,
                'phone_number' => '0987654' . ($i + 30),
                'name' => 'Employee Name ' . $i,
                'email' => 'employee' . $i . '@example.com',
                'street' => 'Employee Street ' . $i,
                'ward' => 'Ward ' . $i,
                'district' => 'District ' . $i,
                'city' => 'City ' . $i
            ]);
        }

        // Add customer addresses
        for ($i = 4; $i <= 13; $i++) {
            Address::create([
                'id' => $i,
                'profile_id' => $i,
                'phone_number' => '0987654' . ($i + 40),
                'name' => 'Customer Name ' . ($i - 3),
                'email' => 'customer' . ($i - 3) . '@example.com',
                'street' => 'Customer Street ' . ($i - 3),
                'ward' => 'Ward ' . rand(1, 10),
                'district' => 'District ' . rand(1, 5),
                'city' => 'City ' . rand(1, 3)
            ]);
            
            // Add a second address for some customers
            if ($i % 3 == 0) {
                Address::create([
                    'id' => $i + 20,
                    'profile_id' => $i,
                    'phone_number' => '0987654' . ($i + 60),
                    'name' => 'Secondary Name ' . ($i - 3),
                    'email' => 'secondary' . ($i - 3) . '@example.com',
                    'street' => 'Secondary Street ' . ($i - 3),
                    'ward' => 'Ward ' . rand(1, 10),
                    'district' => 'District ' . rand(1, 5),
                    'city' => 'City ' . rand(1, 3)
                ]);
            }
        }
    }
}