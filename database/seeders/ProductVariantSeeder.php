<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all products
        $products = Product::all();
        
        foreach ($products as $product) {
            // Determine how many variants to create based on product category
            $variantCount = $this->determineVariantCount($product->category_id);
            
            // Generate variants based on product category
            $this->generateProductVariants($product, $variantCount, $faker);
        }
    }
    
    /**
     * Determine number of variants based on product category
     */
    private function determineVariantCount($categoryId)
    {
        // Get category slug
        $category = \App\Models\Category::find($categoryId);
        
        switch ($category->slug) {
            case 'laptop-gaming':
            case 'laptop-van-phong':
                // Laptops typically have fewer variants
                return rand(1, 4);
            case 'macbook':
                // MacBooks have variants for storage, memory
                return rand(2, 5);
            case 'iphone':
            case 'samsung':
                // Phones often have color and storage variants
                return rand(3, 8);
            case 'sac-du-phong':
                // Power banks might have color variations
                return rand(1, 3);
            case 'dong-ho-thong-minh':
                // Smartwatches have size, material, band variants
                return rand(3, 6);
            default:
                return rand(2, 5);
        }
    }
    
    /**
     * Generate product variants based on product category
     */
    private function generateProductVariants($product, $variantCount, $faker)
    {
        // Get category slug
        $category = \App\Models\Category::find($product->category_id);
        
        switch ($category->slug) {
            case 'laptop-gaming':
            case 'laptop-van-phong':
                $this->createLaptopVariants($product, $variantCount, $faker);
                break;
            case 'macbook':
                $this->createMacbookVariants($product, $variantCount, $faker);
                break;
            case 'iphone':
                $this->createIphoneVariants($product, $variantCount, $faker);
                break;
            case 'samsung':
                $this->createSamsungVariants($product, $variantCount, $faker);
                break;
            case 'sac-du-phong':
                $this->createPowerBankVariants($product, $variantCount, $faker);
                break;
            case 'dong-ho-thong-minh':
                $this->createSmartwatchVariants($product, $variantCount, $faker);
                break;
            default:
                $this->createDefaultVariants($product, $variantCount, $faker);
        }
    }
    
    /**
     * Create laptop variants (RAM, storage, CPU, GPU configurations)
     */
    private function createLaptopVariants($product, $variantCount, $faker)
    {
        $ramOptions = ['8GB', '16GB', '32GB', '64GB'];
        $storageOptions = ['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD'];
        $cpuOptions = ['i5', 'i7', 'i9', 'Ryzen 5', 'Ryzen 7', 'Ryzen 9'];
        $gpuOptions = ['RTX 3050', 'RTX 3060', 'RTX 3070', 'RTX 4060', 'RTX 4070', 'Integrated'];
        
        // Base product specs
        $baseSpecs = $product->specifications ?? [];
        
        // Create variants
        for ($i = 1; $i <= $variantCount; $i++) {
            // Clone base specifications and update for variant
            $variantSpecs = $baseSpecs;
            $variantName = "";
            $priceAdjustment = 0;
            
            // For gaming laptops, vary RAM and GPU more often
            if ($i > 1) {
                // RAM variation
                if ($faker->boolean(80)) {
                    $newRam = $faker->randomElement($ramOptions);
                    $variantSpecs['memory'] = $newRam;
                    $variantName .= $newRam . " ";
                    
                    // Price adjustment based on RAM upgrade
                    if (strpos($newRam, '16GB') !== false) {
                        $priceAdjustment += 100;
                    } elseif (strpos($newRam, '32GB') !== false) {
                        $priceAdjustment += 250;
                    } elseif (strpos($newRam, '64GB') !== false) {
                        $priceAdjustment += 450;
                    }
                }
                
                // Storage variation
                if ($faker->boolean(90)) {
                    $newStorage = $faker->randomElement($storageOptions);
                    $variantSpecs['storage'] = $newStorage;
                    $variantName .= $newStorage . " ";
                    
                    // Price adjustment based on storage upgrade
                    if (strpos($newStorage, '512GB') !== false) {
                        $priceAdjustment += 100;
                    } elseif (strpos($newStorage, '1TB') !== false) {
                        $priceAdjustment += 200;
                    } elseif (strpos($newStorage, '2TB') !== false) {
                        $priceAdjustment += 350;
                    }
                }
                
                // GPU variation (for gaming laptops)
                if (strpos($product->category->slug ?? '', 'gaming') !== false && $faker->boolean(70)) {
                    $newGPU = $faker->randomElement($gpuOptions);
                    $variantSpecs['graphics'] = $newGPU;
                    $variantName .= $newGPU . " ";
                    
                    // Price adjustment based on GPU upgrade
                    if (strpos($newGPU, '3070') !== false) {
                        $priceAdjustment += 300;
                    } elseif (strpos($newGPU, '4060') !== false) {
                        $priceAdjustment += 400;
                    } elseif (strpos($newGPU, '4070') !== false) {
                        $priceAdjustment += 600;
                    }
                }
            }
            
            $variantSku = $product->sku . '-V' . $i;
            $price = $product->base_price + $priceAdjustment;
            $originalPrice = $product->base_original_price + $priceAdjustment;
            
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantSku,
                'price' => $price,
                'original_price' => $originalPrice,
                'stock' => $faker->numberBetween(5, 50),
                'status' => 'active',
                'specifications' => $variantSpecs,
                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
                'updated_at' => Carbon::now()
            ]);
        }
    }
    
    /**
     * Create Macbook variants (storage and memory configurations)
     */
    private function createMacbookVariants($product, $variantCount, $faker)
    {
        $storageOptions = ['256GB', '512GB', '1TB', '2TB', '4TB', '8TB'];
        $memoryOptions = ['8GB', '16GB', '24GB', '32GB', '48GB', '64GB', '96GB'];
        $colors = ['Space Gray', 'Silver', 'Midnight', 'Starlight', 'Space Black'];
        
        // Base product specs
        $baseSpecs = $product->specifications ?? [];
        
        // Create variants
        for ($i = 1; $i <= $variantCount; $i++) {
            // Clone base specifications and update for variant
            $variantSpecs = $baseSpecs;
            $priceAdjustment = 0;
            
            // Storage variation
            if ($i > 1 || $variantCount == 1) {
                $newStorage = $storageOptions[min($i-1, count($storageOptions)-1)];
                $variantSpecs['storage'] = $newStorage;
                
                // Price adjustment based on storage upgrade
                if ($newStorage == '512GB') {
                    $priceAdjustment += 200;
                } elseif ($newStorage == '1TB') {
                    $priceAdjustment += 400;
                } elseif ($newStorage == '2TB') {
                    $priceAdjustment += 800;
                } elseif ($newStorage == '4TB') {
                    $priceAdjustment += 1200;
                } elseif ($newStorage == '8TB') {
                    $priceAdjustment += 2000;
                }
                
                // Memory variation for some variants
                if ($i % 2 == 0 || $variantCount <= 2) {
                    $memIndex = min($i / 2, count($memoryOptions)-1);
                    $newMemory = $memoryOptions[$memIndex];
                    $variantSpecs['memory'] = $newMemory;
                    
                    // Price adjustment based on memory upgrade
                    if ($newMemory == '16GB') {
                        $priceAdjustment += 200;
                    } elseif ($newMemory == '24GB') {
                        $priceAdjustment += 400;
                    } elseif ($newMemory == '32GB') {
                        $priceAdjustment += 600;
                    } elseif ($newMemory == '48GB') {
                        $priceAdjustment += 800;
                    } elseif ($newMemory == '64GB') {
                        $priceAdjustment += 1000;
                    } elseif ($newMemory == '96GB') {
                        $priceAdjustment += 1600;
                    }
                }
                
                // Color variation for some variants
                if ($i % 3 == 0 && count($colors) > 0) {
                    $colorIndex = ($i / 3) % count($colors);
                    $variantSpecs['color'] = $colors[$colorIndex];
                }
            }
            
            $variantSku = $product->sku . '-V' . $i;
            $price = $product->base_price + $priceAdjustment;
            $originalPrice = $product->base_original_price + $priceAdjustment;
            
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantSku,
                'price' => $price,
                'original_price' => $originalPrice,
                'stock' => $faker->numberBetween(3, 30),
                'status' => 'active',
                'specifications' => $variantSpecs,
                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
                'updated_at' => Carbon::now()
            ]);
        }
    }
    
    /**
     * Create iPhone variants (storage and color configurations)
     */
    private function createIphoneVariants($product, $variantCount, $faker)
    {
        $storageOptions = ['128GB', '256GB', '512GB', '1TB'];
        $colors = ['Midnight', 'Starlight', 'Blue', 'Purple', 'Yellow', 'Product RED', 'Space Black', 'Silver', 'Gold', 'Natural Titanium', 'Blue Titanium'];
        
        // Base product specs
        $baseSpecs = $product->specifications ?? [];
        $baseColor = $baseSpecs['color'] ?? null;
        
        // Create a copy of colors array without the base color
        if ($baseColor) {
            $variantColors = array_filter($colors, function($color) use ($baseColor) {
                return $color !== $baseColor;
            });
        } else {
            $variantColors = $colors;
        }
        
        // Create variants
        for ($i = 1; $i <= $variantCount; $i++) {
            // Clone base specifications and update for variant
            $variantSpecs = $baseSpecs;
            $priceAdjustment = 0;
            
            // For first variants, vary only color
            if ($i <= count($variantColors) && $i <= 5) {
                // $variantSpecs['color'] = $variantColors[$i-1];
            }
            // For later variants, vary storage
            else {
                // Storage variation
                $storageIndex = ($i % count($storageOptions));
                $newStorage = $storageOptions[$storageIndex];
                $variantSpecs['storage'] = $newStorage;
                
                // Color variation
                $colorIndex = ($i % count($variantColors));
                // $variantSpecs['color'] = $variantColors[$colorIndex];
                
                // Price adjustment based on storage upgrade
                if ($newStorage == '256GB') {
                    $priceAdjustment += 100;
                } elseif ($newStorage == '512GB') {
                    $priceAdjustment += 300;
                } elseif ($newStorage == '1TB') {
                    $priceAdjustment += 500;
                }
            }
            
            $variantSku = $product->sku . '-V' . $i;
            $price = $product->base_price + $priceAdjustment;
            $originalPrice = $product->base_original_price + $priceAdjustment;
            
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantSku,
                'price' => $price,
                'original_price' => $originalPrice,
                'stock' => $faker->numberBetween(5, 50),
                'status' => 'active',
                'specifications' => $variantSpecs,
                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
                'updated_at' => Carbon::now()
            ]);
        }
    }
    
    /**
     * Create Samsung variants (storage, RAM, and color configurations)
     */
    private function createSamsungVariants($product, $variantCount, $faker)
    {
        $storageOptions = ['128GB', '256GB', '512GB', '1TB'];
        $ramOptions = ['8GB', '12GB', '16GB'];
        $colors = ['Phantom Black', 'Cream', 'Green', 'Lavender', 'Graphite', 'Sky Blue', 'Lime', 'Red', 'Silver', 'Titanium Gray', 'Titanium Black', 'Titanium Violet'];
        
        // Base product specs
        $baseSpecs = $product->specifications ?? [];
        
        // Create variants
        for ($i = 1; $i <= $variantCount; $i++) {
            // Clone base specifications and update for variant
            $variantSpecs = $baseSpecs;
            $priceAdjustment = 0;
            
            // For first 4-6 variants, vary only color
            if ($i <= 6) {
                $colorIndex = ($i - 1) % count($colors);
                $variantSpecs['color'] = $colors[$colorIndex];
            }
            // For later variants, vary storage and RAM
            else {
                // Storage variation
                $storageIndex = (($i - 6) % count($storageOptions));
                $newStorage = $storageOptions[$storageIndex];
                $variantSpecs['storage'] = $newStorage;
                
                // RAM variation on odd variants
                if ($i % 2 != 0) {
                    $ramIndex = (($i - 6) / 2) % count($ramOptions);
                    $ramIndex = (int) $ramIndex;
                    $variantSpecs['ram'] = $ramOptions[$ramIndex];
                    
                    // Price adjustment based on RAM upgrade
                    if ($ramOptions[$ramIndex] == '12GB') {
                        $priceAdjustment += 100;
                    } elseif ($ramOptions[$ramIndex] == '16GB') {
                        $priceAdjustment += 200;
                    }
                }
                
                // Color variation still applied
                $colorIndex = ($i - 1) % count($colors);
                $variantSpecs['color'] = $colors[$colorIndex];
                
                // Price adjustment based on storage upgrade
                if ($newStorage == '256GB') {
                    $priceAdjustment += 100;
                } elseif ($newStorage == '512GB') {
                    $priceAdjustment += 250;
                } elseif ($newStorage == '1TB') {
                    $priceAdjustment += 400;
                }
            }
            
            $variantSku = $product->sku . '-V' . $i;
            $price = $product->base_price + $priceAdjustment;
            $originalPrice = $product->base_original_price + $priceAdjustment;
            
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantSku,
                'price' => $price,
                'original_price' => $originalPrice,
                'stock' => $faker->numberBetween(5, 50),
                'status' => 'active',
                'specifications' => $variantSpecs,
                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
                'updated_at' => Carbon::now()
            ]);
        }
    }
    
    /**
     * Create power bank variants (color variations)
     */
    private function createPowerBankVariants($product, $variantCount, $faker)
    {
        $colors = ['Black', 'White', 'Blue', 'Green', 'Red', 'Silver', 'Gold'];
        
        // Base product specs
        $baseSpecs = $product->specifications ?? [];
        
        // Create variants
        for ($i = 1; $i <= $variantCount; $i++) {
            // Clone base specifications and update for variant
            $variantSpecs = $baseSpecs;
            
            // Color variation
            $colorIndex = ($i - 1) % count($colors);
            $variantSpecs['color'] = $colors[$colorIndex];
            
            $variantSku = $product->sku . '-V' . $i;
            $price = $product->base_price;
            $originalPrice = $product->base_original_price;
            
            // Some colors might have small price differences
            if (in_array($colors[$colorIndex], ['Gold', 'Silver'])) {
                $price += 5;
                $originalPrice += 5;
            }
            
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantSku,
                'price' => $price,
                'original_price' => $originalPrice,
                'stock' => $faker->numberBetween(10, 100),
                'status' => 'active',
                'specifications' => $variantSpecs,
                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
                'updated_at' => Carbon::now()
            ]);
        }
    }
    
    /**
     * Create smartwatch variants (case size, material, band type)
     */
    private function createSmartwatchVariants($product, $variantCount, $faker)
    {
        $caseSize = ['38mm', '40mm', '42mm', '44mm', '45mm', '46mm', '49mm'];
        $materials = ['Aluminum', 'Stainless Steel', 'Titanium', 'Plastic'];
        $bands = ['Sport Band', 'Leather Link', 'Milanese Loop', 'Solo Loop', 'Braided Solo Loop', 'Sport Loop'];
        $colors = ['Black', 'White', 'Blue', 'Green', 'Red', 'Silver', 'Gold', 'Graphite', 'Titanium'];
        
        // Base product specs
        $baseSpecs = $product->specifications ?? [];
        
        // Create variants
        for ($i = 1; $i <= $variantCount; $i++) {
            // Clone base specifications and update for variant
            $variantSpecs = $baseSpecs;
            $priceAdjustment = 0;
            $variantType = $i % 3; // 0 = size, 1 = material, 2 = band+color
            
            switch ($variantType) {
                case 0: // Case size variants
                    $sizeIndex = ($i / 3) % count($caseSize);
                    $sizeIndex = (int) $sizeIndex;
                    $variantSpecs['size'] = $caseSize[$sizeIndex];
                    
                    // Price adjustment for bigger size
                    if ($sizeIndex >= 3) { // 44mm and above
                        $priceAdjustment += 30;
                    }
                    break;
                    
                case 1: // Material variants
                    $materialIndex = ($i / 3) % count($materials);
                    $materialIndex = (int) $materialIndex;
                    $variantSpecs['material'] = $materials[$materialIndex];
                    
                    // Price adjustment for premium materials
                    if ($materials[$materialIndex] == 'Stainless Steel') {
                        $priceAdjustment += 100;
                    } elseif ($materials[$materialIndex] == 'Titanium') {
                        $priceAdjustment += 200;
                    }
                    break;
                    
                case 2: // Band type + color variants
                    $bandIndex = ($i / 3) % count($bands);
                    $bandIndex = (int) $bandIndex;
                    $colorIndex = ($i / 3) % count($colors);
                    $colorIndex = (int) $colorIndex;
                    
                    $variantSpecs['band_type'] = $bands[$bandIndex];
                    $variantSpecs['band_color'] = $colors[$colorIndex];
                    
                    // Price adjustment for premium bands
                    if (in_array($bands[$bandIndex], ['Leather Link', 'Milanese Loop', 'Braided Solo Loop'])) {
                        $priceAdjustment += 50;
                    }
                    break;
            }
            
            $variantSku = $product->sku . '-V' . $i;
            $price = $product->base_price + $priceAdjustment;
            $originalPrice = $product->base_original_price + $priceAdjustment;
            
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantSku,
                'price' => $price,
                'original_price' => $originalPrice,
                'stock' => $faker->numberBetween(5, 30),
                'status' => 'active',
                'specifications' => $variantSpecs,
                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
                'updated_at' => Carbon::now()
            ]);
        }
    }
    
    /**
     * Create default variants for any other product category
     */
    private function createDefaultVariants($product, $variantCount, $faker)
    {
        // Base product specs
        $baseSpecs = $product->specifications ?? [];
        
        // Create variants
        for ($i = 1; $i <= $variantCount; $i++) {
            // Clone base specifications and update for variant
            $variantSpecs = $baseSpecs;
            $priceAdjustment = $faker->numberBetween(-50, 100);
            
            // Add a variant identifier
            $variantSpecs['variant'] = "Option " . $i;
            
            $variantSku = $product->sku . '-V' . $i;
            $price = max($product->base_price + $priceAdjustment, $product->base_price * 0.9);
            $originalPrice = max($product->base_original_price + $priceAdjustment, $product->base_original_price * 0.9);
            
            ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantSku,
                'price' => $price,
                'original_price' => $originalPrice,
                'stock' => $faker->numberBetween(5, 50),
                'status' => 'active',
                'specifications' => $variantSpecs,
                'created_at' => Carbon::now()->subDays($faker->numberBetween(1, 30)),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}