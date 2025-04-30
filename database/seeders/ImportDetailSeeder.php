<?php

namespace Database\Seeders;

use App\Models\ImportDetail;
use Illuminate\Database\Seeder;

class ImportDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Import 1 details
        ImportDetail::create([
            'id' => 1,
            'import_id' => 1,
            'product_id' => 1,
            'import_price' => 2800000,
            'amount' => 20
        ]);

        ImportDetail::create([
            'id' => 2,
            'import_id' => 1,
            'product_id' => 2,
            'import_price' => 1800000,
            'amount' => 30
        ]);

        // Import 2 details
        ImportDetail::create([
            'id' => 3,
            'import_id' => 2,
            'product_id' => 3,
            'import_price' => 1000000,
            'amount' => 40
        ]);

        ImportDetail::create([
            'id' => 4,
            'import_id' => 2,
            'product_id' => 4,
            'import_price' => 1100000,
            'amount' => 45
        ]);

        // Import 3 details
        ImportDetail::create([
            'id' => 5,
            'import_id' => 3,
            'product_id' => 5,
            'import_price' => 150000,
            'amount' => 60
        ]);

        ImportDetail::create([
            'id' => 6,
            'import_id' => 3,
            'product_id' => 6,
            'import_price' => 300000,
            'amount' => 50
        ]);

        ImportDetail::create([
            'id' => 7,
            'import_id' => 3,
            'product_id' => 7,
            'import_price' => 95000,
            'amount' => 70
        ]);

        // Import 4 details (processing)
        ImportDetail::create([
            'id' => 8,
            'import_id' => 4,
            'product_id' => 1,
            'import_price' => 2750000,
            'amount' => 10
        ]);

        ImportDetail::create([
            'id' => 9,
            'import_id' => 4,
            'product_id' => 3,
            'import_price' => 980000,
            'amount' => 15
        ]);

        // Import 5 details (pending)
        ImportDetail::create([
            'id' => 10,
            'import_id' => 5,
            'product_id' => 4,
            'import_price' => 1080000,
            'amount' => 20
        ]);

        // Import 6 details (cancelled)
        ImportDetail::create([
            'id' => 11,
            'import_id' => 6,
            'product_id' => 8,
            'import_price' => 2100000,
            'amount' => 10
        ]);
    }
}