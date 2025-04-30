<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Order 1 details
        OrderDetail::create([
            'id' => 1,
            'order_id' => 1,
            'product_variant_id' => 1,
            'serial' => 1001
        ]);

        OrderDetail::create([
            'id' => 2,
            'order_id' => 1,
            'product_variant_id' => 11,
            'serial' => 5001
        ]);

        // Order 2 details
        OrderDetail::create([
            'id' => 3,
            'order_id' => 2,
            'product_variant_id' => 3,
            'serial' => 2001
        ]);

        // Order 3 details
        OrderDetail::create([
            'id' => 4,
            'order_id' => 3,
            'product_variant_id' => 7,
            'serial' => 4001
        ]);

        OrderDetail::create([
            'id' => 5,
            'order_id' => 3,
            'product_variant_id' => 9,
            'serial' => 6001
        ]);

        OrderDetail::create([
            'id' => 6,
            'order_id' => 3,
            'product_variant_id' => 10,
            'serial' => 7001
        ]);

        // Order 4 details
        OrderDetail::create([
            'id' => 7,
            'order_id' => 4,
            'product_variant_id' => 5,
            'serial' => 3001
        ]);

        // Order 5 details
        OrderDetail::create([
            'id' => 8,
            'order_id' => 5,
            'product_variant_id' => 2,
            'serial' => 1002
        ]);

        // Order 6 details
        OrderDetail::create([
            'id' => 9,
            'order_id' => 6,
            'product_variant_id' => 4,
            'serial' => 2002
        ]);

        // Order 7 details
        OrderDetail::create([
            'id' => 10,
            'order_id' => 7,
            'product_variant_id' => 8,
            'serial' => 4002
        ]);
    }
}