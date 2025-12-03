<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;
use App\Models\CurrencyExchangeRate;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if currencies already exist
        if (Currency::count() > 0) {
            $this->command->info('Currencies already exist. Skipping seeder.');
            return;
        }

        // Create USD currency (default)
        $usd = Currency::create([
            'code' => 'USD',
            'name' => 'United States Dollar',
            'symbol' => '$',
            'is_default' => true,
            'is_active' => true,
        ]);

        // Create LBP currency
        $lbp = Currency::create([
            'code' => 'LBP',
            'name' => 'Lebanese Pound',
            'symbol' => 'ل.ل',
            'is_default' => false,
            'is_active' => true,
        ]);

        // Set default exchange rate: 1 USD = 15000 LBP (example rate, should be updated)
        CurrencyExchangeRate::create([
            'from_currency_id' => $usd->id,
            'to_currency_id' => $lbp->id,
            'rate' => 15000.000000,
        ]);

        // Reverse rate: 1 LBP = 0.000067 USD (1/15000)
        CurrencyExchangeRate::create([
            'from_currency_id' => $lbp->id,
            'to_currency_id' => $usd->id,
            'rate' => 0.000067,
        ]);

        $this->command->info('Default currencies (USD and LBP) created successfully.');
    }
}
