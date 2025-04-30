<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RuleSeeder::class,
            CategorySeeder::class,
            AccountSeeder::class,
            ProfileSeeder::class,
            AddressSeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            ProductImageSeeder::class,
            OrderSeeder::class,
            OrderDetailSeeder::class,
            ImportSeeder::class,
            ImportDetailSeeder::class,
            WarrantySeeder::class,
            CartSeeder::class,
            ProductReviewSeeder::class,
            FunctionSeeder::class,
            RuleFunctionSeeder::class,
        ]);
    }
}