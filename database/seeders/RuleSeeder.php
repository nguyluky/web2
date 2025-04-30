<?php

namespace Database\Seeders;

use App\Models\Rule;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Rule::create([
            'id' => 1,
            'name' => 'Admin',
            'status' => 1
        ]);

        Rule::create([
            'id' => 2,
            'name' => 'Manager',
            'status' => 1
        ]);

        Rule::create([
            'id' => 3,
            'name' => 'Employee',
            'status' => 1
        ]);

        Rule::create([
            'id' => 4,
            'name' => 'Customer',
            'status' => 1
        ]);
    }
}