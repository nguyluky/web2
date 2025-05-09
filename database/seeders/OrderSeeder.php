<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Completed orders
        Order::create([
            'id' => 1,
            'profile_id' => 4,  // Customer 1
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(30),
            'payment_method' => 2
        ]);

        Order::create([
            'id' => 2,
            'profile_id' => 5,  // Customer 2
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(25),
            'payment_method' => 1
        ]);

        Order::create([
            'id' => 3,
            'profile_id' => 6,  // Customer 3
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(20),
            'payment_method' => 2
        ]);

        // Processing orders
        Order::create([
            'id' => 4,
            'profile_id' => 7,  // Customer 4
            'status' => 'processing',
            'created_at' => Carbon::now()->subDays(5),
            'payment_method' => 1
        ]);

        Order::create([
            'id' => 5,
            'profile_id' => 8,  // Customer 5
            'status' => 'processing',
            'created_at' => Carbon::now()->subDays(3),
            'payment_method' => 2
        ]);

        // Pending order
        Order::create([
            'id' => 6,
            'profile_id' => 9,  // Customer 6
            'status' => 'pending',
            'created_at' => Carbon::now()->subDays(1),
            'payment_method' => 1
        ]);

        // Cancelled order
        Order::create([
            'id' => 7,
            'profile_id' => 10,  // Customer 7
            'status' => 'cancelled',
            'created_at' => Carbon::now()->subDays(15),
            'payment_method' => 1
        ]);
    }
}
