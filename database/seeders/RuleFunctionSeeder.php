<?php

namespace Database\Seeders;

use App\Models\RuleFunction;
use Illuminate\Database\Seeder;

class RuleFunctionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin - Has access to all functions
        for ($i = 1; $i <= 15; $i++) {
            RuleFunction::create([
                'rule_id' => 1,
                'function_id' => $i,
                'name' => 'Admin permission for function #' . $i
            ]);
        }

        // Manager - Has access to most functions except system settings
        for ($i = 1; $i <= 8; $i++) {
            RuleFunction::create([
                'rule_id' => 2,
                'function_id' => $i,
                'name' => 'Manager permission for function #' . $i
            ]);
        }
        // Add reports access for manager
        RuleFunction::create([
            'rule_id' => 2,
            'function_id' => 14,
            'name' => 'Manager permission for sales report'
        ]);
        RuleFunction::create([
            'rule_id' => 2,
            'function_id' => 15,
            'name' => 'Manager permission for inventory report'
        ]);

        // Employee - Limited access
        RuleFunction::create([
            'rule_id' => 3,
            'function_id' => 1,
            'name' => 'Employee permission for dashboard'
        ]);
        RuleFunction::create([
            'rule_id' => 3,
            'function_id' => 5,
            'name' => 'Employee permission for order management'
        ]);
        RuleFunction::create([
            'rule_id' => 3,
            'function_id' => 6,
            'name' => 'Employee permission for inventory management'
        ]);
        RuleFunction::create([
            'rule_id' => 3,
            'function_id' => 8,
            'name' => 'Employee permission for import management'
        ]);

        // Customer - Access to customer-specific functions
        RuleFunction::create([
            'rule_id' => 4,
            'function_id' => 10,
            'name' => 'Customer permission for shopping cart'
        ]);
        RuleFunction::create([
            'rule_id' => 4,
            'function_id' => 11,
            'name' => 'Customer permission for order history'
        ]);
        RuleFunction::create([
            'rule_id' => 4,
            'function_id' => 12,
            'name' => 'Customer permission for product review'
        ]);
        RuleFunction::create([
            'rule_id' => 4,
            'function_id' => 13,
            'name' => 'Customer permission for profile management'
        ]);
    }
}