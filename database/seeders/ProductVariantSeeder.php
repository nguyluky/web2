<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gaming Pro X1 variants
        ProductVariant::create([
            'id' => 1,
            'product_id' => 1,
            'sku' => 'GP-X1-4080-32-1TB',
            'price' => 3299.99,
            'original_price' => 3599.99,
            'stock' => 15,
            'status' => 'active',
            'attributes' => json_encode([
                'RAM' => '32GB',
                'Storage' => '1TB SSD',
                'Color' => 'Black'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        ProductVariant::create([
            'id' => 2,
            'product_id' => 1,
            'sku' => 'GP-X1-4080-64-2TB',
            'price' => 3899.99,
            'original_price' => 4199.99,
            'stock' => 10,
            'status' => 'active',
            'attributes' => json_encode([
                'RAM' => '64GB',
                'Storage' => '2TB SSD',
                'Color' => 'Black'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Business Elite B5 variants
        ProductVariant::create([
            'id' => 3,
            'product_id' => 2,
            'sku' => 'BE-B5-i7-16-512',
            'price' => 2199.99,
            'original_price' => 2399.99,
            'stock' => 25,
            'status' => 'active',
            'attributes' => json_encode([
                'Processor' => 'i7',
                'RAM' => '16GB',
                'Storage' => '512GB SSD',
                'Color' => 'Silver'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        ProductVariant::create([
            'id' => 4,
            'product_id' => 2,
            'sku' => 'BE-B5-i9-32-1TB',
            'price' => 2799.99,
            'original_price' => 2999.99,
            'stock' => 15,
            'status' => 'active',
            'attributes' => json_encode([
                'Processor' => 'i9',
                'RAM' => '32GB',
                'Storage' => '1TB SSD',
                'Color' => 'Gray'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Galaxy Ultra S25 variants
        ProductVariant::create([
            'id' => 5,
            'product_id' => 3,
            'sku' => 'GUS25-256-BLK',
            'price' => 1299.99,
            'original_price' => 1399.99,
            'stock' => 30,
            'status' => 'active',
            'attributes' => json_encode([
                'Storage' => '256GB',
                'Color' => 'Black'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        ProductVariant::create([
            'id' => 6,
            'product_id' => 3,
            'sku' => 'GUS25-512-SLV',
            'price' => 1499.99,
            'original_price' => 1599.99,
            'stock' => 25,
            'status' => 'active',
            'attributes' => json_encode([
                'Storage' => '512GB',
                'Color' => 'Silver'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // iPhone 16 Pro variants
        ProductVariant::create([
            'id' => 7,
            'product_id' => 4,
            'sku' => 'IP16P-256-MID',
            'price' => 1399.99,
            'original_price' => 1499.99,
            'stock' => 35,
            'status' => 'active',
            'attributes' => json_encode([
                'Storage' => '256GB',
                'Color' => 'Midnight'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        ProductVariant::create([
            'id' => 8,
            'product_id' => 4,
            'sku' => 'IP16P-512-GLD',
            'price' => 1699.99,
            'original_price' => 1799.99,
            'stock' => 20,
            'status' => 'active',
            'attributes' => json_encode([
                'Storage' => '512GB',
                'Color' => 'Gold'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Accessories variants
        ProductVariant::create([
            'id' => 9,
            'product_id' => 5,
            'sku' => 'PWE-WHT',
            'price' => 199.99,
            'original_price' => 219.99,
            'stock' => 50,
            'status' => 'active',
            'attributes' => json_encode([
                'Color' => 'White'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        ProductVariant::create([
            'id' => 10,
            'product_id' => 6,
            'sku' => 'SWS5-BLK',
            'price' => 399.99,
            'original_price' => 429.99,
            'stock' => 40,
            'status' => 'active',
            'attributes' => json_encode([
                'Color' => 'Black',
                'Band' => 'Silicone'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        ProductVariant::create([
            'id' => 11,
            'product_id' => 7,
            'sku' => 'GMP-RGB',
            'price' => 129.99,
            'original_price' => 149.99,
            'stock' => 60,
            'status' => 'active',
            'attributes' => json_encode([
                'Color' => 'Black',
                'Connection' => 'Wireless'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Gaming Stealth G7 variants
        ProductVariant::create([
            'id' => 12,
            'product_id' => 8,
            'sku' => 'GSG7-4070-16-1TB',
            'price' => 2499.99,
            'original_price' => 2699.99,
            'stock' => 5,
            'status' => 'inactive',
            'attributes' => json_encode([
                'Graphics' => 'RTX 4070',
                'RAM' => '16GB',
                'Storage' => '1TB SSD',
                'Color' => 'Black'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}