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
        Order::create([
            'id' => 1,
            'profile_id' => 1,
            'status' => 'pending',
            'payment_method' => 1,
            'address_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Order::create([
            'id' => 2,
            'profile_id' => 2,
            'status' => 'completed',
            'payment_method' => 2,
            'address_id' => 2,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Order::create([
            'id' => 3,
            'profile_id' => 3,
            'status' => 'shipped',
            'payment_method' => 1,
            'address_id' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Order::create([
            'id' => 4,
            'profile_id' => 4,
            'status' => 'cancelled',
            'payment_method' => 3,
            'address_id' => 4,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Order::create([
            'id' => 5,
            'profile_id' => 5,
            'status' => 'processing',
            'payment_method' => 2,
            'address_id' => 5,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Order::create([
            'id' => 6,
            'profile_id' => 6,
            'status' => 'pending',
            'payment_method' => 1,
            'address_id' => 6,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Order::create([
            'id' => 7,
            'profile_id' => 7,
            'status' => 'completed',
            'payment_method' => 3,
            'address_id' => 7,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
