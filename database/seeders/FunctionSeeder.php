<?php

namespace Database\Seeders;

use App\Models\Function_;
use Illuminate\Database\Seeder;

class FunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dashboard
        Function_::create([
            'id' => 1,
            'name' => 'dashboard'
        ]);

        // User management
        Function_::create([
            'id' => 2,
            'name' => 'user_management'
        ]);

        // Product management
        Function_::create([
            'id' => 3,
            'name' => 'product_management'
        ]);

        Function_::create([
            'id' => 4,
            'name' => 'category_management'
        ]);

        // Order management
        Function_::create([
            'id' => 5,
            'name' => 'order_management'
        ]);

        // Inventory management
        Function_::create([
            'id' => 6,
            'name' => 'inventory_management'
        ]);

        Function_::create([
            'id' => 7,
            'name' => 'supplier_management'
        ]);

        // Import management
        Function_::create([
            'id' => 8,
            'name' => 'import_management'
        ]);

        // System settings
        Function_::create([
            'id' => 9,
            'name' => 'system_settings'
        ]);

        // Customer functions
        Function_::create([
            'id' => 10,
            'name' => 'shopping_cart'
        ]);

        Function_::create([
            'id' => 11,
            'name' => 'order_history'
        ]);

        Function_::create([
            'id' => 12,
            'name' => 'product_review'
        ]);

        Function_::create([
            'id' => 13,
            'name' => 'manage_profile'
        ]);

        // Report functions
        Function_::create([
            'id' => 14,
            'name' => 'sales_report'
        ]);

        Function_::create([
            'id' => 15,
            'name' => 'inventory_report'
        ]);
    }
}