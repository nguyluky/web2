<?php

namespace Database\Seeders;

use App\Models\Import;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Completed imports
        Import::create([
            'id' => 1,
            'suppiler_id' => 1, // Tech Solutions Inc.
            'employee_id' => 3, // Employee
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(60)
        ]);

        Import::create([
            'id' => 2,
            'suppiler_id' => 2, // Global Electronics
            'employee_id' => 2, // Manager
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(45)
        ]);

        Import::create([
            'id' => 3,
            'suppiler_id' => 3, // Digital World
            'employee_id' => 3, // Employee
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(30)
        ]);

        // Processing imports
        Import::create([
            'id' => 4,
            'suppiler_id' => 4, // Smart Gadgets Co.
            'employee_id' => 2, // Manager
            'status' => 'processing',
            'created_at' => Carbon::now()->subDays(5)
        ]);

        // Pending import
        Import::create([
            'id' => 5,
            'suppiler_id' => 1, // Tech Solutions Inc.
            'employee_id' => 3, // Employee
            'status' => 'pending',
            'created_at' => Carbon::now()->subDays(2)
        ]);

        // Cancelled import
        Import::create([
            'id' => 6,
            'suppiler_id' => 5, // Future Tech
            'employee_id' => 2, // Manager
            'status' => 'cancelled',
            'created_at' => Carbon::now()->subDays(15)
        ]);
    }
}