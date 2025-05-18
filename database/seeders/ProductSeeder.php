<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a faker instance
        $faker = Faker::create();
        
        // Get categories with their require_fields
        $laptopGaming = Category::where('slug', 'laptop-gaming')->first();
        $laptopVanPhong = Category::where('slug', 'laptop-van-phong')->first();
        $macbook = Category::where('slug', 'macbook')->first();
        $iphone = Category::where('slug', 'iphone')->first();
        $samsung = Category::where('slug', 'samsung')->first();
        $sacDuPhong = Category::where('slug', 'sac-du-phong')->first();
        $dongHoThongMinh = Category::where('slug', 'dong-ho-thong-minh')->first();
        
        // Create products for each category
        $this->createGamingLaptops($laptopGaming, $faker);
        $this->createOfficeLaptops($laptopVanPhong, $faker);
        $this->createMacbooks($macbook, $faker);
        $this->createIphones($iphone, $faker);
        $this->createSamsungPhones($samsung, $faker);
        $this->createPowerBanks($sacDuPhong, $faker);
        $this->createSmartWatches($dongHoThongMinh, $faker);
    }
    
    /**
     * Đảm bảo specifications có tất cả các trường yêu cầu từ danh mục
     * 
     * @param array $specs - Specifications hiện tại
     * @param Category $category - Danh mục với require_fields
     * @param \Faker\Generator $faker - Instance của Faker để tạo dữ liệu
     * @return array - Specifications đã được cập nhật
     */
    private function ensureRequiredFields($specs, $category, $faker)
    {
        // Nếu category không có require_fields, trả về specs gốc
        if (!$category->require_fields || empty($category->require_fields)) {
            return $specs;
        }
        
        // Mặc định cho các loại trường phổ biến
        $defaults = [
            'processor' => ['Intel Core i5', 'Intel Core i7', 'AMD Ryzen 5', 'MediaTek Dimensity 8200'],
            'graphics' => ['Integrated Graphics', 'NVIDIA GTX 1650', 'AMD Radeon Graphics'],
            'display' => ['1080p Full HD', '2K Display', '4K UHD Display'],
            'memory' => ['8GB', '16GB', '32GB'],
            'ram' => ['8GB', '16GB', '32GB'],
            'storage' => ['256GB SSD', '512GB SSD', '1TB SSD'],
            'os' => ['Windows 11', 'Android 14', 'iOS 17'],
            'battery' => ['4000mAh', '5000mAh'],
            'weight' => ['1.5kg', '1.8kg', '2.2kg'],
            'brand' => ['Samsung', 'Apple', 'Sony', 'LG', 'Xiaomi'],
            'color' => ['Black', 'White', 'Silver', 'Blue', 'Red'],
            'connectivity' => ['Bluetooth 5.0', 'Wi-Fi 6', '5G'],
            'material' => ['Aluminum', 'Plastic', 'Glass', 'Stainless Steel'],
            'capacity' => ['10000mAh', '20000mAh', '30000mAh'],
        ];
        
        // Đảm bảo tất cả các trường yêu cầu có trong specs
        foreach ($category->require_fields as $field) {
            if (!isset($specs[$field])) {
                if (isset($defaults[$field])) {
                    // Lấy một giá trị ngẫu nhiên từ mảng mặc định
                    $randomIndex = array_rand($defaults[$field]);
                    $specs[$field] = $defaults[$field][$randomIndex];
                } else {
                    $specs[$field] = "Standard $field"; // Giá trị mặc định nếu không có trong danh sách defaults
                }
            }
        }
        
        return $specs;
    }
    
    private function createGamingLaptops($category, $faker)
    {
        $brands = ['MSI', 'ASUS ROG', 'Acer Predator', 'Alienware', 'Lenovo Legion', 'HP Omen', 'Razer Blade'];
        $processors = ['Intel Core i9 13900K', 'Intel Core i7 13700K', 'Intel Core i9 14900K', 'AMD Ryzen 9 7950X', 'AMD Ryzen 7 7800X3D'];
        $graphics = ['NVIDIA RTX 4090', 'NVIDIA RTX 4080', 'NVIDIA RTX 4070 Ti', 'NVIDIA RTX 4060', 'AMD Radeon RX 7900 XTX'];
        $memory = ['16GB DDR5', '32GB DDR5', '64GB DDR5', '128GB DDR5'];
        $storage = ['1TB NVMe SSD', '2TB NVMe SSD', '4TB NVMe SSD', '512GB NVMe SSD + 2TB HDD'];
        $displays = ['15.6" FHD 144Hz', '16" QHD 240Hz', '17.3" QHD 240Hz', '17.3" 4K 120Hz', '18" QHD 360Hz'];
        
        for ($i = 1; $i <= 20; $i++) {
            $brand = $faker->randomElement($brands);
            $model = $faker->randomElement(['Pro', 'Elite', 'Extreme', 'Ultimate', 'Stealth', 'Titan', 'Dominator', 'Fury']);
            $number = $faker->randomElement(['X', 'Z', 'S', 'M', 'G']) . $faker->numberBetween(1, 9) . $faker->randomElement(['', 'i', 'X', 'Pro']);
            
            $name = "$brand $model $number";
            $slug = Str::slug($name);
            $processor = $faker->randomElement($processors);
            $graphic = $faker->randomElement($graphics);
            $ram = $faker->randomElement($memory);
            $display = $faker->randomElement($displays);
            
            $basePrice = $faker->numberBetween(1500, 4500);
            $originalPrice = $faker->numberBetween($basePrice, $basePrice + 500);
            
            // Tạo specifications cơ bản
            $specs = [
                'processor' => $processor,
                'graphics' => $graphic,
                'display' => $display,
                'memory' => $ram,
                'storage' => $faker->randomElement($storage),
                'os' => 'Windows 11 Pro',
                'battery' => $faker->randomElement(['70Wh', '80Wh', '90Wh', '99.9Wh']),
                'cooling' => 'Advanced Cooling Technology',
                'keyboard' => 'RGB Mechanical Keyboard'
            ];
            
            // Đảm bảo tất cả các trường yêu cầu đều có trong specs
            $specs = $this->ensureRequiredFields($specs, $category, $faker);
            
            Product::create([
                'sku' => 'GL-' . strtoupper(Str::random(4)),
                'name' => $name,
                'slug' => $slug,
                'description' => "Powerful gaming laptop featuring $processor processor and $graphic graphics for extreme gaming performance.",
                'category_id' => $category->id,
                'base_price' => $basePrice,
                'base_original_price' => $originalPrice,
                'status' => 'active',
                'specifications' => $specs,
                'features' => [
                    'RGB Keyboard',
                    'Thunderbolt 4',
                    'Wi-Fi 6E',
                    $faker->randomElement(['Advanced Cooling System', 'Per-key RGB', 'OLED Display', 'G-Sync', 'FreeSync Premium']),
                    $faker->randomElement(['AI Noise Cancellation', 'Dolby Atmos', 'DTS:X Ultra', 'HD Webcam with IR'])
                ],
                'created_at' => $faker->dateTimeBetween('-90 days', '-1 day'),
                'updated_at' => $faker->dateTimeBetween('-30 days', 'now')
            ]);
        }
    }
    
    private function createOfficeLaptops($category, $faker)
    {
        $brands = ['Dell', 'HP', 'Lenovo', 'ASUS', 'Acer', 'Microsoft Surface', 'LG Gram'];
        $processors = ['Intel Core i5 1240P', 'Intel Core i7 1260P', 'AMD Ryzen 5 7640U', 'AMD Ryzen 7 7840U', 'Intel Core i5 13500H', 'Intel Core i7 13700H'];
        $graphics = ['Intel Iris Xe', 'AMD Radeon Graphics', 'NVIDIA MX550', 'NVIDIA RTX 2050'];
        $memory = ['8GB DDR4', '16GB DDR4', '16GB DDR5', '32GB DDR5'];
        $storage = ['256GB NVMe SSD', '512GB NVMe SSD', '1TB NVMe SSD', '2TB NVMe SSD'];
        $displays = ['13.3" FHD IPS', '14" FHD IPS', '15.6" FHD IPS', '16" QHD+ IPS', '13.5" 2K Touch'];
        
        for ($i = 1; $i <= 20; $i++) {
            $brand = $faker->randomElement($brands);
            $series = $faker->randomElement(['Inspiron', 'Pavilion', 'ThinkPad', 'ZenBook', 'Swift', 'Laptop', 'Gram']);
            $model = $faker->randomElement(['', 'Plus', 'Pro', 'Ultra', 'Air', 'S', 'X']) . " " . $faker->numberBetween(3, 9) . $faker->randomElement(['00', '10', '20', '30', '40', '50']);
            
            $name = "$brand $series $model";
            $slug = Str::slug($name);
            $processor = $faker->randomElement($processors);
            $graphic = $faker->randomElement($graphics);
            $ram = $faker->randomElement($memory);
            $display = $faker->randomElement($displays);
            
            $basePrice = $faker->numberBetween(600, 1800);
            $originalPrice = $faker->randomElement([$basePrice, $basePrice + 100, $basePrice + 200]);
            
            // Tạo specifications cơ bản
            $specs = [
                'processor' => $processor,
                'graphics' => $graphic,
                'display' => $display,
                'memory' => $ram,
                'storage' => $faker->randomElement($storage),
                'os' => 'Windows 11 Home',
                'battery' => $faker->randomElement(['45Wh', '53Wh', '60Wh', '72Wh', '80Wh']),
                'weight' => $faker->randomFloat(2, 1.1, 2.2) . 'kg'
            ];
            
            // Đảm bảo tất cả các trường yêu cầu đều có trong specs
            $specs = $this->ensureRequiredFields($specs, $category, $faker);
            
            Product::create([
                'sku' => 'OL-' . strtoupper(Str::random(4)),
                'name' => $name,
                'slug' => $slug,
                'description' => "Business laptop featuring $processor processor and $display display for productive office work and multitasking.",
                'category_id' => $category->id,
                'base_price' => $basePrice,
                'base_original_price' => $originalPrice,
                'status' => 'active',
                'specifications' => $specs,
                'features' => [
                    'Backlit Keyboard',
                    'Fingerprint Reader',
                    'Wi-Fi 6',
                    $faker->randomElement(['Fast Charging', 'Aluminum Body', 'Spill-resistant Keyboard', 'Military Grade Durability']),
                    $faker->randomElement(['HD Webcam', 'FHD Webcam', 'Noise Cancelling Microphones', 'USB-C Power Delivery'])
                ],
                'created_at' => $faker->dateTimeBetween('-90 days', '-1 day'),
                'updated_at' => $faker->dateTimeBetween('-30 days', 'now')
            ]);
        }
    }
    
    private function createMacbooks($category, $faker)
    {
        $models = ['MacBook Air', 'MacBook Pro'];
        $processors = ['Apple M1', 'Apple M1 Pro', 'Apple M1 Max', 'Apple M2', 'Apple M2 Pro', 'Apple M2 Max', 'Apple M3', 'Apple M3 Pro', 'Apple M3 Max'];
        $memory = ['8GB Unified Memory', '16GB Unified Memory', '24GB Unified Memory', '32GB Unified Memory', '64GB Unified Memory', '96GB Unified Memory'];
        $storage = ['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD', '4TB SSD', '8TB SSD'];
        $displays = ['13.3" Retina', '13.6" Liquid Retina', '14" Liquid Retina XDR', '16" Liquid Retina XDR'];
        
        for ($i = 1; $i <= 20; $i++) {
            $model = $faker->randomElement($models);
            $size = $model == 'MacBook Air' ? $faker->randomElement(['13"', '15"']) : $faker->randomElement(['14"', '16"']);
            $year = $faker->randomElement(['2022', '2023', '2024', '']);
            $processor = $faker->randomElement($processors);
            $color = $faker->randomElement(['Space Gray', 'Silver', 'Midnight', 'Starlight', 'Space Black']);
            
            $name = "Apple $model $size $processor $year";
            $slug = Str::slug($name);
            $ram = $faker->randomElement($memory);
            $store = $faker->randomElement($storage);
            $display = $faker->randomElement($displays);
            
            $basePrice = $model == 'MacBook Air' ? $faker->numberBetween(999, 1999) : $faker->numberBetween(1999, 3999);
            $originalPrice = $faker->numberBetween($basePrice, $basePrice + 200);
            
            // Tạo specifications cơ bản
            $specs = [
                'processor' => $processor,
                'memory' => $ram,
                'storage' => $store,
                'display' => $display,
                'color' => $color,
                'os' => 'macOS ' . $faker->randomElement(['Ventura', 'Sonoma', 'Sequoia']),
                'battery' => $faker->randomElement(['Up to 18 hours', 'Up to 20 hours', 'Up to 22 hours']),
                'weight' => $model == 'MacBook Air' ? $faker->randomFloat(2, 1.1, 1.5) . 'kg' : $faker->randomFloat(2, 1.5, 2.2) . 'kg'
            ];
            
            // Đảm bảo tất cả các trường yêu cầu đều có trong specs
            $specs = $this->ensureRequiredFields($specs, $category, $faker);
            
            Product::create([
                'sku' => 'MB-' . strtoupper(Str::random(4)),
                'name' => $name,
                'slug' => $slug,
                'description' => "Apple $model with $processor chip, $ram, and $store storage. Features beautiful $display display.",
                'category_id' => $category->id,
                'base_price' => $basePrice,
                'base_original_price' => $originalPrice,
                'status' => 'active',
                'specifications' => $specs,
                'features' => [
                    'Magic Keyboard',
                    'Touch ID',
                    'Force Touch Trackpad',
                    $faker->randomElement(['Thunderbolt / USB 4 ports', 'MagSafe charging', 'HDMI port', 'SDXC card slot']),
                    $model == 'MacBook Pro' ? $faker->randomElement(['Liquid Retina XDR display', 'ProMotion technology', 'Mini-LED backlighting']) : 'Retina display'
                ],
                'created_at' => $faker->dateTimeBetween('-90 days', '-1 day'),
                'updated_at' => $faker->dateTimeBetween('-30 days', 'now')
            ]);
        }
    }
    
    private function createIphones($category, $faker)
    {
        $models = ['iPhone 13', 'iPhone 13 Pro', 'iPhone 13 Pro Max', 'iPhone 14', 'iPhone 14 Plus', 'iPhone 14 Pro', 'iPhone 14 Pro Max', 'iPhone 15', 'iPhone 15 Plus', 'iPhone 15 Pro', 'iPhone 15 Pro Max', 'iPhone 15 Air', 'iPhone SE'];
        $storage = ['128GB', '256GB', '512GB', '1TB'];
        $colors = ['Midnight', 'Starlight', 'Blue', 'Purple', 'Yellow', 'Product RED', 'Space Black', 'Silver', 'Gold', 'Natural Titanium', 'Blue Titanium'];
        
        for ($i = 1; $i <= 20; $i++) {
            $model = $faker->randomElement($models);
            $store = $faker->randomElement($storage);
            $color = $faker->randomElement($colors);
            
            $name = "Apple $model $store $color";
            $slug = Str::slug($name);
            
            $isPro = strpos($model, 'Pro') !== false;
            $isMax = strpos($model, 'Max') !== false;
            $isSE = strpos($model, 'SE') !== false;
            $iphone15 = strpos($model, '15') !== false;
            
            $basePrice = 0;
            if ($isSE) {
                $basePrice = $faker->numberBetween(429, 529);
            } elseif ($isPro && $isMax) {
                $basePrice = $faker->numberBetween(1199, 1599);
            } elseif ($isPro) {
                $basePrice = $faker->numberBetween(999, 1399);
            } elseif ($isMax || strpos($model, 'Plus') !== false) {
                $basePrice = $faker->numberBetween(899, 1099);
            } else {
                $basePrice = $faker->numberBetween(799, 999);
            }
            
            $originalPrice = $faker->numberBetween($basePrice, $basePrice + 100);
            
            $chip = $iphone15 ? 'A17 Pro' : ($isPro ? 'A16 Bionic' : 'A15 Bionic');
            
            // Tạo specifications cơ bản
            $specs = [
                'model' => $model,
                'storage' => $store,
                'color' => $color,
                'chip' => $chip,
                'display' => $isPro ? 'Super Retina XDR display with ProMotion' : 'Super Retina XDR display',
                'camera' => $isPro ? 'Pro camera system (48MP main, Ultra Wide, Telephoto)' : 'Dual camera system (12MP main, Ultra Wide)',
                'os' => 'iOS ' . $faker->numberBetween(16, 18),
                'battery' => $faker->randomElement(['Up to 20 hours video playback', 'Up to 22 hours video playback', 'Up to 25 hours video playback'])
            ];
            
            // Đảm bảo tất cả các trường yêu cầu đều có trong specs
            $specs = $this->ensureRequiredFields($specs, $category, $faker);
            
            Product::create([
                'sku' => 'IP-' . strtoupper(Str::random(4)),
                'name' => $name,
                'slug' => $slug,
                'description' => "Apple $model with $store storage in $color. Experience the latest in smartphone technology with amazing camera system and powerful performance.",
                'category_id' => $category->id,
                'base_price' => $basePrice,
                'base_original_price' => $originalPrice,
                'status' => 'active',
                'specifications' => $specs,
                'features' => [
                    'Face ID',
                    '5G capable',
                    'MagSafe',
                    $isPro ? 'Dynamic Island' : ($iphone15 ? 'Dynamic Island' : 'Notch design'),
                    $isPro ? 'Always-On display' : 'Super Retina XDR display',
                    $iphone15 ? 'USB-C connector' : 'Lightning connector'
                ],
                'created_at' => $faker->dateTimeBetween('-90 days', '-1 day'),
                'updated_at' => $faker->dateTimeBetween('-30 days', 'now')
            ]);
        }
    }
    
    private function createSamsungPhones($category, $faker)
    {
        $models = ['Galaxy S23', 'Galaxy S23+', 'Galaxy S23 Ultra', 'Galaxy S24', 'Galaxy S24+', 'Galaxy S24 Ultra', 'Galaxy Z Fold5', 'Galaxy Z Flip5', 'Galaxy A54', 'Galaxy A34', 'Galaxy A14', 'Galaxy M34'];
        $storage = ['128GB', '256GB', '512GB', '1TB'];
        $colors = ['Phantom Black', 'Cream', 'Green', 'Lavender', 'Graphite', 'Sky Blue', 'Lime', 'Red', 'Silver', 'Titanium Gray', 'Titanium Black', 'Titanium Violet'];
        
        for ($i = 1; $i <= 20; $i++) {
            $model = $faker->randomElement($models);
            $store = $faker->randomElement($storage);
            $ram = $faker->randomElement(['8GB', '12GB', '16GB']);
            $color = $faker->randomElement($colors);
            
            $name = "Samsung $model ($store/$ram) $color";
            $slug = Str::slug($name);
            
            $isUltra = strpos($model, 'Ultra') !== false;
            $isPlus = strpos($model, '+') !== false;
            $isFold = strpos($model, 'Fold') !== false;
            $isFlip = strpos($model, 'Flip') !== false;
            $isBudget = strpos($model, 'A') === 0 || strpos($model, 'M') === 0;
            $isS24 = strpos($model, 'S24') !== false;
            
            $basePrice = 0;
            if ($isUltra) {
                $basePrice = $faker->numberBetween(1199, 1399);
            } elseif ($isFold) {
                $basePrice = $faker->numberBetween(1799, 1999);
            } elseif ($isFlip) {
                $basePrice = $faker->numberBetween(999, 1099);
            } elseif ($isPlus) {
                $basePrice = $faker->numberBetween(999, 1099);
            } elseif ($isBudget) {
                $basePrice = $faker->numberBetween(249, 499);
            } else {
                $basePrice = $faker->numberBetween(799, 899);
            }
            
            $originalPrice = $faker->numberBetween($basePrice, $basePrice + 100);
            
            $processor = $isS24 ? 'Snapdragon 8 Gen 3' : 'Snapdragon 8 Gen 2';
            if ($isBudget) {
                $processor = $faker->randomElement(['Exynos 1380', 'MediaTek Dimensity 1080', 'Snapdragon 695']);
            }
            
            // Tạo specifications cơ bản
            $specs = [
                'processor' => $processor,
                'ram' => $ram,
                'storage' => $store,
                'display' => $isUltra ? '6.8" QHD+ Dynamic AMOLED 2X' : ($isPlus ? '6.6" FHD+ Dynamic AMOLED 2X' : '6.1" FHD+ Dynamic AMOLED 2X'),
                'color' => $color,
                'os' => 'Android ' . $faker->numberBetween(13, 14) . ' with One UI ' . $faker->randomElement(['5.1', '6.0', '6.1']),
                'battery' => $isUltra ? '5000mAh' : ($isPlus ? '4700mAh' : '3900mAh'),
                'camera' => $isUltra ? 'Triple camera system with 200MP main' : 'Triple camera system with 50MP main'
            ];
            
            // Đảm bảo tất cả các trường yêu cầu đều có trong specs
            $specs = $this->ensureRequiredFields($specs, $category, $faker);
            
            Product::create([
                'sku' => 'SM-' . strtoupper(Str::random(4)),
                'name' => $name,
                'slug' => $slug,
                'description' => "Samsung $model with $store storage and $ram RAM in $color. Premium Android experience with exceptional camera quality and smooth performance.",
                'category_id' => $category->id,
                'base_price' => $basePrice,
                'base_original_price' => $originalPrice,
                'status' => 'active',
                'specifications' => $specs,
                'features' => [
                    'Samsung Knox Security',
                    'IP68 Water and Dust Resistance',
                    'Fast Wireless Charging',
                    $isUltra ? 'S Pen Support' : 'Samsung DeX Support',
                    'Samsung Pay',
                    'Stereo Speakers tuned by AKG'
                ],
                'created_at' => $faker->dateTimeBetween('-90 days', '-1 day'),
                'updated_at' => $faker->dateTimeBetween('-30 days', 'now')
            ]);
        }
    }
    
    private function createPowerBanks($category, $faker)
    {
        $brands = ['Anker', 'Xiaomi', 'Samsung', 'Baseus', 'Belkin', 'RAVPower', 'AUKEY', 'Energizer'];
        $capacities = ['10000mAh', '15000mAh', '20000mAh', '25000mAh', '30000mAh'];
        $colors = ['Black', 'White', 'Blue', 'Green', 'Red', 'Silver', 'Gold'];
        
        for ($i = 1; $i <= 20; $i++) {
            $brand = $faker->randomElement($brands);
            $capacity = $faker->randomElement($capacities);
            $color = $faker->randomElement($colors);
            $model = $faker->randomElement(['PowerCore', 'PowerBank', 'PowerStation', 'MegaCharge', 'TurboCharge', 'SuperPower', 'UltraSlim']);
            
            $name = "$brand $model $capacity";
            $slug = Str::slug($name);
            
            $basePrice = 0;
            if (strpos($capacity, '10000') !== false) {
                $basePrice = $faker->numberBetween(19, 39);
            } elseif (strpos($capacity, '15000') !== false) {
                $basePrice = $faker->numberBetween(29, 49);
            } elseif (strpos($capacity, '20000') !== false) {
                $basePrice = $faker->numberBetween(39, 59);
            } elseif (strpos($capacity, '25000') !== false) {
                $basePrice = $faker->numberBetween(49, 69);
            } else {
                $basePrice = $faker->numberBetween(59, 89);
            }
            
            $originalPrice = $faker->numberBetween($basePrice, $basePrice + 20);
            
            // Tạo specifications cơ bản
            $specs = [
                'brand' => $brand,
                'capacity' => $capacity,
                'color' => $color,
                'input' => $faker->randomElement(['USB-C PD 20W', 'USB-C PD 30W', 'Micro USB 18W', 'Lightning + USB-C']),
                'output' => $faker->randomElement(['USB-C PD 30W + USB-A QC 18W', 'USB-C PD 20W + 2x USB-A 12W', '2x USB-A QC 3.0']),
                'ports' => $faker->numberBetween(2, 4),
                'weight' => $faker->numberBetween(200, 500) . 'g'
            ];
            
            // Đảm bảo tất cả các trường yêu cầu đều có trong specs
            $specs = $this->ensureRequiredFields($specs, $category, $faker);
            
            Product::create([
                'sku' => 'PB-' . strtoupper(Str::random(4)),
                'name' => $name,
                'slug' => $slug,
                'description' => "$brand $capacity power bank in $color. High-capacity portable charger with fast charging capabilities.",
                'category_id' => $category->id,
                'base_price' => $basePrice,
                'base_original_price' => $originalPrice,
                'status' => 'active',
                'specifications' => $specs,
                'features' => [
                    'Fast Charging',
                    'Multiple Ports',
                    'LED Indicator',
                    $faker->randomElement(['Compact Design', 'Airline Safe', 'Trickle Charging Mode', 'Power Delivery']),
                    $faker->randomElement(['Digital Display', 'Protection System', 'USB-C Support', 'Slim Design'])
                ],
                'created_at' => $faker->dateTimeBetween('-90 days', '-1 day'),
                'updated_at' => $faker->dateTimeBetween('-30 days', 'now')
            ]);
        }
    }
    
    private function createSmartWatches($category, $faker)
    {
        $brands = ['Apple', 'Samsung', 'Garmin', 'Fitbit', 'Huawei', 'Xiaomi', 'Amazfit'];
        $models = ['Watch', 'Watch Ultra', 'Galaxy Watch', 'Forerunner', 'Versa', 'Watch GT', 'Mi Band', 'GTR'];
        $sizes = ['38mm', '40mm', '42mm', '44mm', '45mm', '46mm', '49mm'];
        $colors = ['Black', 'Silver', 'Gold', 'Blue', 'Red', 'Green', 'White', 'Pink', 'Titanium'];
        
        for ($i = 1; $i <= 20; $i++) {
            $brand = $faker->randomElement($brands);
            $model = $faker->randomElement($models);
            $series = $faker->randomElement(['3', '4', '5', '6', 'SE', 'Pro', 'Ultra']) . " " . $faker->randomElement(['', 'Plus', 'Active', 'Classic']);
            $size = $faker->randomElement($sizes);
            $color = $faker->randomElement($colors);
            
            $name = "$brand $model $series $size";
            $slug = Str::slug($name);
            
            $isApple = $brand == 'Apple';
            $isSamsung = $brand == 'Samsung';
            $isGarmin = $brand == 'Garmin';
            $isPremium = strpos($series, 'Pro') !== false || strpos($series, 'Ultra') !== false || $isGarmin;
            
            $basePrice = 0;
            if ($isApple && $isPremium) {
                $basePrice = $faker->numberBetween(399, 799);
            } elseif ($isApple) {
                $basePrice = $faker->numberBetween(249, 399);
            } elseif ($isSamsung && $isPremium) {
                $basePrice = $faker->numberBetween(299, 449);
            } elseif ($isSamsung) {
                $basePrice = $faker->numberBetween(199, 299);
            } elseif ($isGarmin) {
                $basePrice = $faker->numberBetween(349, 699);
            } else {
                $basePrice = $faker->numberBetween(99, 199);
            }
            
            $originalPrice = $faker->numberBetween($basePrice, $basePrice + 50);
            
            // Tạo specifications cơ bản
            $specs = [
                'brand' => $brand,
                'model' => "$model $series",
                'size' => $size,
                'color' => $color,
                'display' => $faker->randomElement(['AMOLED', 'Retina LTPO', 'Super AMOLED', 'MIP', 'LCD']),
                'connectivity' => implode(', ', $faker->randomElements(['Bluetooth 5.0', 'Wi-Fi', 'GPS', 'LTE', 'NFC'], $faker->numberBetween(2, 5))),
                'battery_life' => $faker->randomElement(['Up to 18 hours', 'Up to 36 hours', 'Up to 7 days', 'Up to 14 days']),
                'water_resistance' => $faker->randomElement(['5 ATM', '10 ATM', 'IP68', 'WR50']),
                'os' => $isApple ? 'watchOS ' . $faker->numberBetween(8, 10) : ($isSamsung ? 'Wear OS' : 'Proprietary OS'),
                'health_features' => implode(', ', $faker->randomElements(['Heart Rate Monitor', 'SpO2', 'ECG', 'Sleep Tracking', 'Stress Monitoring'], $faker->numberBetween(3, 5)))
            ];
            
            // Đảm bảo tất cả các trường yêu cầu đều có trong specs
            $specs = $this->ensureRequiredFields($specs, $category, $faker);
            
            Product::create([
                'sku' => 'SW-' . strtoupper(Str::random(4)),
                'name' => $name,
                'slug' => $slug,
                'description' => "$brand $model $series smartwatch in $color. Advanced health tracking and smartphone notifications on your wrist.",
                'category_id' => $category->id,
                'base_price' => $basePrice,
                'base_original_price' => $originalPrice,
                'status' => 'active',
                'specifications' => $specs,
                'features' => [
                    'Activity Tracking',
                    'Sleep Monitoring',
                    'Smartphone Notifications',
                    $isPremium ? $faker->randomElement(['Blood Oxygen Monitoring', 'ECG', 'Fall Detection', 'Elevation Tracking']) : $faker->randomElement(['Heart Rate Monitoring', 'Step Counter', 'Calorie Tracking']),
                    $isPremium ? $faker->randomElement(['Titanium Case', 'Sapphire Glass', 'Always-On Altimeter', 'Built-in Maps']) : $faker->randomElement(['Water Resistant', 'Music Control', 'Customizable Watch Faces'])
                ],
                'created_at' => $faker->dateTimeBetween('-90 days', '-1 day'),
                'updated_at' => $faker->dateTimeBetween('-30 days', 'now')
            ]);
        }
    }
}