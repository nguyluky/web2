<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create([
            'id' => 1,
            'rule' => 1,
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'status' => 'active',
            'created' => now(),
            'updated' => now()
        ]);

        Account::create([
            'id' => 2,
            'rule' => 2,
            'username' => 'manager',
            'password' => Hash::make('manager123'),
            'status' => 'active',
            'created' => now(),
            'updated' => now()
        ]);

        Account::create([
            'id' => 3,
            'rule' => 3,
            'username' => 'employee1',
            'password' => Hash::make('employee123'),
            'status' => 'active',
            'created' => now(),
            'updated' => now()
        ]);

        // Create 10 customer accounts
        for ($i = 4; $i <= 13; $i++) {
            Account::create([
                'id' => $i,
                'rule' => 4,
                'username' => 'customer' . ($i - 3),
                'password' => Hash::make('customer123'),
                'status' => 'active',
                'created' => now(),
                'updated' => now()
            ]);
        }
    }
}