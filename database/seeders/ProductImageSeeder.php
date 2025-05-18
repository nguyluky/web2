<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all products
        $products = Product::all();
        
        // Generate images for each product
        foreach ($products as $product) {
            // Determine how many images to create based on category
            $imageCount = $faker->numberBetween(3, 8);
            
            // Create primary image
            ProductImage::create([
                'product_id' => $product->id,
                'variant_id' => null,
                'image_url' => $this->generateImageUrl($product->category_id, $product->slug, 'main'),
                'is_primary' => true,
                'sort_order' => 1
            ]);
            
            // Create additional product images
            for ($i = 2; $i <= $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'variant_id' => null,
                    'image_url' => $this->generateImageUrl($product->category_id, $product->slug, $i),
                    'is_primary' => false,
                    'sort_order' => $i
                ]);
            }
            
            // Get product variants and create images for each variant
            $variants = ProductVariant::where('product_id', $product->id)->get();
            
            foreach ($variants as $variant) {
                // Create at least one image per variant
                $variantImageCount = $faker->numberBetween(1, 3);
                
                for ($i = 1; $i <= $variantImageCount; $i++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'variant_id' => $variant->id,
                        'image_url' => $this->generateVariantImageUrl($product->category_id, $product->slug, $variant->id, $i),
                        'is_primary' => ($i === 1), // First image is primary for the variant
                        'sort_order' => $i
                    ]);
                }
            }
        }
    }
    
    /**
     * Generate a realistic image URL based on category and product name
     */
    private function generateImageUrl($categoryId, $slug, $index)
    {
        $categories = [
            // Gaming laptop
            'laptop-gaming' => 'laptops/gaming',
            // Office laptop
            'laptop-van-phong' => 'laptops/office',
            // Macbook
            'macbook' => 'laptops/macbook',
            // iPhone
            'iphone' => 'phones/iphone',
            // Samsung
            'samsung' => 'phones/samsung',
            // Power bank
            'sac-du-phong' => 'accessories/powerbanks',
            // Smartwatch
            'dong-ho-thong-minh' => 'wearables/smartwatches'
        ];
        
        // Get category name from category ID
        $category = \App\Models\Category::find($categoryId);
        $categoryFolder = $categories[$category->slug] ?? 'products';
        
        // Format: /images/products/[category_folder]/[product_slug]-[index].jpg
        return "/images/products/{$categoryFolder}/{$slug}-{$index}.jpg";
    }
    
    /**
     * Generate image URL for product variants
     */
    private function generateVariantImageUrl($categoryId, $slug, $variantId, $index)
    {
        $category = \App\Models\Category::find($categoryId);
        
        // Different folder structure for variants
        $categories = [
            'laptop-gaming' => 'laptops/gaming/variants',
            'laptop-van-phong' => 'laptops/office/variants',
            'macbook' => 'laptops/macbook/variants',
            'iphone' => 'phones/iphone/variants',
            'samsung' => 'phones/samsung/variants',
            'sac-du-phong' => 'accessories/powerbanks/variants',
            'dong-ho-thong-minh' => 'wearables/smartwatches/variants'
        ];
        
        $categoryFolder = $categories[$category->slug] ?? 'products/variants';
        
        // Format: /images/products/[category_folder]/[product_slug]-v[variant_id]-[index].jpg
        return "/images/products/{$categoryFolder}/{$slug}-v{$variantId}-{$index}.jpg";
    }
}