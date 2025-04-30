<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Laptops
        Product::create([
            'id' => 1,
            'sku' => 'GP-X1',
            'name' => 'Gaming Pro X1',
            'slug' => 'gaming-pro-x1',
            'description' => 'High-performance gaming laptop with RTX 4080 graphics',
            'category_id' => 4, // Gaming Laptops
            'base_price' => 3199.99,
            'base_original_price' => 3499.99,
            'status' => 'active',
            'specifications' => json_encode([
                'processor' => 'Intel Core i9 13900K',
                'graphics' => 'NVIDIA RTX 4080',
                'display' => '17.3" QHD 240Hz',
                'memory' => '32GB DDR5'
            ]),
            'features' => json_encode([
                'RGB Keyboard',
                'Thunderbolt 4',
                'Wi-Fi 6E'
            ]),
            'created_at' => Carbon::now()->subDays(30),
            'updated_at' => Carbon::now()->subDays(30)
        ]);

        Product::create([
            'id' => 2,
            'sku' => 'BE-B5',
            'name' => 'Business Elite B5',
            'slug' => 'business-elite-b5',
            'description' => 'Professional laptop for business users with enhanced security features',
            'category_id' => 5, // Business Laptops
            'base_price' => 1999.99,
            'base_original_price' => 2199.99,
            'status' => 'active',
            'specifications' => json_encode([
                'processor' => 'Intel Core i7 1270P',
                'graphics' => 'Intel Iris Xe',
                'display' => '14" FHD IPS',
                'memory' => '16GB DDR5'
            ]),
            'features' => json_encode([
                'Fingerprint Reader',
                'TPM 2.0',
                'Spill-resistant Keyboard'
            ]),
            'created_at' => Carbon::now()->subDays(28),
            'updated_at' => Carbon::now()->subDays(28)
        ]);

        // Smartphones
        Product::create([
            'id' => 3,
            'sku' => 'GUS25',
            'name' => 'Galaxy Ultra S25',
            'slug' => 'galaxy-ultra-s25',
            'description' => 'Latest flagship Android smartphone with 200MP camera',
            'category_id' => 6, // Android Phones
            'base_price' => 1199.99,
            'base_original_price' => 1299.99,
            'status' => 'active',
            'specifications' => json_encode([
                'processor' => 'Snapdragon 8 Gen 3',
                'display' => '6.8" Dynamic AMOLED',
                'camera' => '200MP + 50MP + 12MP',
                'battery' => '5000mAh'
            ]),
            'features' => json_encode([
                'S Pen Support',
                'IP68 Water Resistance',
                '45W Fast Charging'
            ]),
            'created_at' => Carbon::now()->subDays(20),
            'updated_at' => Carbon::now()->subDays(20)
        ]);

        Product::create([
            'id' => 4,
            'sku' => 'IP16P',
            'name' => 'iPhone 16 Pro',
            'slug' => 'iphone-16-pro',
            'description' => 'The most advanced iPhone ever with A18 bionic chip',
            'category_id' => 7, // iOS Phones
            'base_price' => 1299.99,
            'base_original_price' => 1399.99,
            'status' => 'active',
            'specifications' => json_encode([
                'processor' => 'A18 Bionic',
                'display' => '6.1" Super Retina XDR',
                'camera' => '48MP + 12MP + 12MP',
                'battery' => 'Up to 23 hours video playback'
            ]),
            'features' => json_encode([
                'Dynamic Island',
                'Always-On Display',
                'ProMotion Technology'
            ]),
            'created_at' => Carbon::now()->subDays(15),
            'updated_at' => Carbon::now()->subDays(15)
        ]);

        // Accessories
        Product::create([
            'id' => 5,
            'sku' => 'PWE',
            'name' => 'Pro Wireless Earbuds',
            'slug' => 'pro-wireless-earbuds',
            'description' => 'Premium wireless earbuds with noise cancellation',
            'category_id' => 3, // Accessories
            'base_price' => 179.99,
            'base_original_price' => 199.99,
            'status' => 'active',
            'specifications' => json_encode([
                'battery' => 'Up to 8 hours',
                'connectivity' => 'Bluetooth 5.3',
                'driver' => '11mm dynamic driver'
            ]),
            'features' => json_encode([
                'Active Noise Cancellation',
                'Transparency Mode',
                'IPX4 Water Resistance'
            ]),
            'created_at' => Carbon::now()->subDays(10),
            'updated_at' => Carbon::now()->subDays(10)
        ]);

        Product::create([
            'id' => 6,
            'sku' => 'SWS5',
            'name' => 'Smart Watch Series 5',
            'slug' => 'smart-watch-series-5',
            'description' => 'Advanced smartwatch with health monitoring features',
            'category_id' => 3, // Accessories
            'base_price' => 349.99,
            'base_original_price' => 399.99,
            'status' => 'active',
            'specifications' => json_encode([
                'display' => '1.4" AMOLED',
                'sensors' => 'Heart rate, SpO2, ECG',
                'battery' => 'Up to 2 days'
            ]),
            'features' => json_encode([
                'Sleep Tracking',
                'GPS',
                '5ATM Water Resistance'
            ]),
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5)
        ]);

        Product::create([
            'id' => 7,
            'sku' => 'GMP',
            'name' => 'Gaming Mouse Pro',
            'slug' => 'gaming-mouse-pro',
            'description' => '25,000 DPI gaming mouse with RGB lighting',
            'category_id' => 3, // Accessories
            'base_price' => 99.99,
            'base_original_price' => 129.99,
            'status' => 'active',
            'specifications' => json_encode([
                'sensor' => '25,000 DPI optical',
                'buttons' => '8 programmable buttons',
                'weight' => '89g'
            ]),
            'features' => json_encode([
                'RGB Lighting',
                'Onboard Memory',
                'PTFE Feet'
            ]),
            'created_at' => Carbon::now()->subDays(3),
            'updated_at' => Carbon::now()->subDays(3)
        ]);

        Product::create([
            'id' => 8,
            'sku' => 'GSG7',
            'name' => 'Gaming Stealth G7',
            'slug' => 'gaming-stealth-g7',
            'description' => 'Ultra-thin gaming laptop with powerful performance',
            'category_id' => 4, // Gaming Laptops
            'base_price' => 2399.99,
            'base_original_price' => 2599.99,
            'status' => 'inactive',
            'specifications' => json_encode([
                'processor' => 'AMD Ryzen 9 7900X',
                'graphics' => 'NVIDIA RTX 4070',
                'display' => '15.6" QHD 165Hz',
                'memory' => '16GB DDR5'
            ]),
            'features' => json_encode([
                'Slim Design',
                'Per-key RGB',
                'Advanced Cooling System'
            ]),
            'created_at' => Carbon::now()->subDays(60),
            'updated_at' => Carbon::now()->subDays(60)
        ]);
    }
}