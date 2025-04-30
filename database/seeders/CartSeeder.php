<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Customer 8 cart items
        Cart::create([
            'profile_id' => 11,
            'product_variant_id' => 1,
            'amount' => 1
        ]);

        Cart::create([
            'profile_id' => 11,
            'product_variant_id' => 9,
            'amount' => 2
        ]);

        // Customer 9 cart items
        Cart::create([
            'profile_id' => 12,
            'product_variant_id' => 5,
            'amount' => 1
        ]);

        Cart::create([
            'profile_id' => 12,
            'product_variant_id' => 10,
            'amount' => 1
        ]);

        // Customer 10 cart items
        Cart::create([
            'profile_id' => 13,
            'product_variant_id' => 3,
            'amount' => 1
        ]);
    }
}