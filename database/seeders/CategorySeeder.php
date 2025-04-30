<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'id' => 1,
            'name' => 'Laptops',
            'slug' => 'laptops',
            'status' => 'active',
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Category::create([
            'id' => 2,
            'name' => 'Smartphones',
            'slug' => 'smartphones',
            'status' => 'active',
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Category::create([
            'id' => 3,
            'name' => 'Accessories',
            'slug' => 'accessories',
            'status' => 'active',
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Category::create([
            'id' => 4,
            'name' => 'Gaming Laptops',
            'slug' => 'gaming-laptops',
            'status' => 'active',
            'parent_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Category::create([
            'id' => 5,
            'name' => 'Business Laptops',
            'slug' => 'business-laptops',
            'status' => 'active',
            'parent_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Category::create([
            'id' => 6,
            'name' => 'Android Phones',
            'slug' => 'android-phones',
            'status' => 'active',
            'parent_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Category::create([
            'id' => 7,
            'name' => 'iOS Phones',
            'slug' => 'ios-phones',
            'status' => 'active',
            'parent_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}