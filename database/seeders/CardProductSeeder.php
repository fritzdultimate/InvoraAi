<?php

namespace Database\Seeders;

use App\Models\CardProduct;
use Illuminate\Database\Seeder;

// The two launch cards. Safe to run again: it only adds a card that is missing.
class CardProductSeeder extends Seeder
{
    public function run(): void
    {
        CardProduct::firstOrCreate(['name' => 'Invora Visa'], [
            'network' => 'visa',
            'form' => 'physical',
            'design' => 'sapphire',
            'tagline' => 'Everyday spending, worldwide',
            'preorder_fee' => 300,
            'spending_limit' => 10000,
            'limit_period' => 'monthly',
            'features' => ['Contactless payments', 'ATM withdrawals', 'Online and in-store purchases', 'Spending tracked in your dashboard'],
            'delivery_estimate' => '10–15 business days',
            'max_per_user' => 1,
            'sort_order' => 1,
        ]);

        CardProduct::firstOrCreate(['name' => 'Invora Mastercard'], [
            'network' => 'mastercard',
            'form' => 'physical',
            'design' => 'gold',
            'tagline' => 'High limits for serious spenders',
            'preorder_fee' => 500,
            'spending_limit' => 100000,
            'limit_period' => 'monthly',
            'features' => ['Up to $100,000 limit', 'Contactless payments', 'ATM withdrawals worldwide', 'Priority support'],
            'delivery_estimate' => '10–15 business days',
            'max_per_user' => 1,
            'is_featured' => true,
            'sort_order' => 2,
        ]);
    }
}
