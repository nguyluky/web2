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
            'account_id' => 4,  // Customer 1
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(30),
            'employee_id' => 3,
            'payment_method' => 'credit_card'
        ]);

        Order::create([
            'id' => 2,
            'account_id' => 5,  // Customer 2
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(25),
            'employee_id' => 3,
            'payment_method' => 'bank_transfer'
        ]);

        Order::create([
            'id' => 3,
            'account_id' => 6,  // Customer 3
            'status' => 'completed',
            'created_at' => Carbon::now()->subDays(20),
            'employee_id' => 3,
            'payment_method' => 'credit_card'
        ]);

        // Processing orders
        Order::create([
            'id' => 4,
            'account_id' => 7,  // Customer 4
            'status' => 'processing',
            'created_at' => Carbon::now()->subDays(5),
            'employee_id' => 3,
            'payment_method' => 'cash'
        ]);

        Order::create([
            'id' => 5,
            'account_id' => 8,  // Customer 5
            'status' => 'processing',
            'created_at' => Carbon::now()->subDays(3),
            'employee_id' => 3,
            'payment_method' => 'credit_card'
        ]);

        // Pending order
        Order::create([
            'id' => 6,
            'account_id' => 9,  // Customer 6
            'status' => 'pending',
            'created_at' => Carbon::now()->subDays(1),
            'employee_id' => 3,
            'payment_method' => 'bank_transfer'
        ]);

        // Cancelled order
        Order::create([
            'id' => 7,
            'account_id' => 10,  // Customer 7
            'status' => 'cancelled',
            'created_at' => Carbon::now()->subDays(15),
            'employee_id' => 3,
            'payment_method' => 'credit_card'
        ]);
    }
}