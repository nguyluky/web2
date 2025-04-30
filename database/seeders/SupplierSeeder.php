<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'id' => 1,
            'name' => 'Tech Solutions Inc.',
            'tax' => '1234567890',
            'contact_name' => 'John Smith',
            'phone_number' => '0123456789',
            'email' => 'contact@techsolutions.com',
            'status' => 'active',
            'created_at' => Carbon::now()
        ]);

        Supplier::create([
            'id' => 2,
            'name' => 'Global Electronics',
            'tax' => '2345678901',
            'contact_name' => 'Jane Doe',
            'phone_number' => '0987654321',
            'email' => 'contact@globalelectronics.com',
            'status' => 'active',
            'created_at' => Carbon::now()
        ]);

        Supplier::create([
            'id' => 3,
            'name' => 'Digital World',
            'tax' => '3456789012',
            'contact_name' => 'Tom Wilson',
            'phone_number' => '0123498765',
            'email' => 'contact@digitalworld.com',
            'status' => 'active',
            'created_at' => Carbon::now()
        ]);

        Supplier::create([
            'id' => 4,
            'name' => 'Smart Gadgets Co.',
            'tax' => '4567890123',
            'contact_name' => 'Alice Brown',
            'phone_number' => '0123789456',
            'email' => 'contact@smartgadgets.com',
            'status' => 'active',
            'created_at' => Carbon::now()
        ]);

        Supplier::create([
            'id' => 5,
            'name' => 'Future Tech',
            'tax' => '5678901234',
            'contact_name' => 'Robert Johnson',
            'phone_number' => '0456789123',
            'email' => 'contact@futuretech.com',
            'status' => 'inactive',
            'created_at' => Carbon::now()->subMonths(1)
        ]);
    }
}