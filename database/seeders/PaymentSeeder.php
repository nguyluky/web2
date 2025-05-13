<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::create([
            'id' => 1,
            'description' => 'cash'
        ]);
        Payment::create([
            'id' => 2,
            'description' => 'bank_transfer'
        ]);
    }
}
