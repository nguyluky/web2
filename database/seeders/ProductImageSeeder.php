<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product 1 - Gaming Pro X1
        ProductImage::create([
            'id' => 1,
            'product_id' => 1,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => true,
            'sort_order' => 1,
            'created_at' => Carbon::now()
        ]);

        ProductImage::create([
            'id' => 2,
            'product_id' => 1,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => false,
            'sort_order' => 2,
            'created_at' => Carbon::now()
        ]);

        // Product 2 - Business Elite B5
        ProductImage::create([
            'id' => 3,
            'product_id' => 2,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => true,
            'sort_order' => 1,
            'created_at' => Carbon::now()
        ]);

        ProductImage::create([
            'id' => 4,
            'product_id' => 2,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => false,
            'sort_order' => 2,
            'created_at' => Carbon::now()
        ]);

        // Product 3 - Galaxy Ultra S25
        ProductImage::create([
            'id' => 5,
            'product_id' => 3,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => true,
            'sort_order' => 1,
            'created_at' => Carbon::now()
        ]);

        ProductImage::create([
            'id' => 6,
            'product_id' => 3,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => false,
            'sort_order' => 2,
            'created_at' => Carbon::now()
        ]);

        // Product 4 - iPhone 16 Pro
        ProductImage::create([
            'id' => 7,
            'product_id' => 4,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => true,
            'sort_order' => 1,
            'created_at' => Carbon::now()
        ]);

        ProductImage::create([
            'id' => 8,
            'product_id' => 4,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => false,
            'sort_order' => 2,
            'created_at' => Carbon::now()
        ]);

        // Product 5 - Pro Wireless Earbuds
        ProductImage::create([
            'id' => 9,
            'product_id' => 5,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => true,
            'sort_order' => 1,
            'created_at' => Carbon::now()
        ]);

        // Product 6 - Smart Watch Series 5
        ProductImage::create([
            'id' => 10,
            'product_id' => 6,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => true,
            'sort_order' => 1,
            'created_at' => Carbon::now()
        ]);

        // Product 7 - Gaming Mouse Pro
        ProductImage::create([
            'id' => 11,
            'product_id' => 7,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => true,
            'sort_order' => 1,
            'created_at' => Carbon::now()
        ]);

        // Product 8 - Gaming Stealth G7
        ProductImage::create([
            'id' => 12,
            'product_id' => 8,
            'variant_id' => null,
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => true,
            'sort_order' => 1,
            'created_at' => Carbon::now()
        ]);
        
        // Variant specific images
        ProductImage::create([
            'id' => 13,
            'product_id' => 3,
            'variant_id' => 5, // Galaxy Ultra S25 - Black
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => false,
            'sort_order' => 3,
            'created_at' => Carbon::now()
        ]);
        
        ProductImage::create([
            'id' => 14,
            'product_id' => 3,
            'variant_id' => 6, // Galaxy Ultra S25 - Silver
            'image_url' => 'https://placehold.co/600x600',
            'is_primary' => false,
            'sort_order' => 4,
            'created_at' => Carbon::now()
        ]);
    }
}