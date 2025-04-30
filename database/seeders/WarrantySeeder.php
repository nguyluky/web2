<?php

namespace Database\Seeders;

use App\Models\Warranty;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WarrantySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Warranties for completed orders
        Warranty::create([
            'id' => 1,
            'product_id' => 1, // Order detail ID (not product ID)
            'supplier_id' => 1, // Tech Solutions Inc.
            'issue_date' => Carbon::now()->subDays(30)->format('Y-m-d'),
            'expiration_date' => Carbon::now()->subDays(30)->addYear()->format('Y-m-d'),
            'status' => 'active',
            'note' => 'Standard 12-month warranty for gaming laptop'
        ]);

        Warranty::create([
            'id' => 2,
            'product_id' => 3, // Order detail ID
            'supplier_id' => 2, // Global Electronics
            'issue_date' => Carbon::now()->subDays(25)->format('Y-m-d'),
            'expiration_date' => Carbon::now()->subDays(25)->addYears(2)->format('Y-m-d'),
            'status' => 'active',
            'note' => 'Extended 24-month warranty for business laptop'
        ]);

        Warranty::create([
            'id' => 3,
            'product_id' => 4, // Order detail ID
            'supplier_id' => 3, // Digital World
            'issue_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
            'expiration_date' => Carbon::now()->subDays(20)->addYear()->format('Y-m-d'),
            'status' => 'active',
            'note' => 'Premium 12-month warranty for smartphone'
        ]);

        Warranty::create([
            'id' => 4,
            'product_id' => 6, // Order detail ID
            'supplier_id' => 4, // Smart Gadgets Co.
            'issue_date' => Carbon::now()->subDays(20)->format('Y-m-d'),
            'expiration_date' => Carbon::now()->subDays(20)->addMonths(6)->format('Y-m-d'),
            'status' => 'active',
            'note' => 'Standard 6-month warranty for smartwatch'
        ]);

        // Expired warranty
        Warranty::create([
            'id' => 5,
            'product_id' => 2, // Order detail ID
            'supplier_id' => 1, // Tech Solutions Inc.
            'issue_date' => Carbon::now()->subYears(2)->format('Y-m-d'),
            'expiration_date' => Carbon::now()->subYears(2)->addMonths(3)->format('Y-m-d'),
            'status' => 'expired',
            'note' => 'Standard 3-month warranty for gaming accessories'
        ]);
    }
}